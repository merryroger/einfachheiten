<?php

$targetDir = "...some target directory...";
$sourceDir = "...some source directory...";
$subdirs = [...array of required subdirectories...];

if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
	$toFile = realpath($targetDir . $_POST['subdir'] . '/img/' . $_POST['file']);
	$fromFile = realpath($sourceDir . '/' . $_POST['subdir'] . '/' . $_POST['file']);
	rename($fromFile, $toFile);
}

$page = "<!DOCTYPE html>\n<html>\n<head>\n";

$page .= "<title>Picture comparator</title>";

$page .= "<style>\n";
$page .= "html, body { width: 100%; background-color: #ccc; }\n";
$page .= "</style>\n";

$page .= "</head>\n<body>\n";

$page .= "<table>\n";

$subdir = $subdirs[7];

$source = $sourceDir . '/' . $subdir;
$target = $targetDir . '/' . $subdir . '/img';

if ($dh = @opendir($source)) {

	while(false !== ($entry = readdir($dh))) {
		if (is_dir($entry)) {
			continue;
		}

		$tr = "<tr>";
		$tr .= "<td><img load=\"lazy\" src=\"" . $target . "/{$entry}" . "\"></td>";
		$tr .= "<td>";
		$tr .= '<form action="/" method="POST">';
		$tr .= "<input type=\"hidden\" name=\"file\" value=\"{$entry}\">";
		$tr .= "<input type=\"hidden\" name=\"subdir\" value=\"{$subdir}\">";
		$tr .= '<button type="submit"><< Replace</button>';
		$tr .= "</form>";
		$tr .= "</td>";
		$tr .= "<td><img load=\"lazy\" src=\"" . $source . "/{$entry}" . "\"></td>";
		$tr .= "</tr>";

		$page .= $tr;
	}

	closedir($dh);
}

$page .= "</table>\n";

$page .= "</body>\n</html>\n";

echo($page);