<?php

	$outputDir = '...some output dir...';
	$inputDir = '...some input dir...';
	$subdirs = [...array of required subdirectories...];

	function handleImage($input, $output)
	{
		$size = getimagesize($input);

		$im = imagecreatetruecolor($size[0], $size[1]);
		
		$src_im = @imagecreatefrompng($input);
		
		$transparencyIndex = imagecolortransparent($src_im);

		if($transparencyIndex !== -1) {
		    $transparent_color = imagecolorsforindex($src_im, $transparencyIndex);
 
		    //Добавляем цвет в палитру нового изображения, и устанавливаем его как прозрачный
s		    $transparent_destination_index = imagecolorallocate($im, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);

		} else {
                    $transparent_destination_index = imagecolorallocate($im, 254, 254, 254);
		}

		imagecolortransparent($im, $transparent_destination_index);
		imagefill($im, 0, 0, $transparent_destination_index);
				
		imagecopyresampled($im ,$src_im, 0, 0, 0, 0, $size[0], $size[1], $size[0], $size[1]);
		s
		imagetruecolortopalette($im, false, 255);
		
		imageAlphaBlending($src_im, true);
		imageSaveAlpha($im, true);

		imagepng($im, $output, 9,  PNG_ALL_FILTERS);

		imagedestroy($im);
	}

	function dierctoryWalker($inputDir, $outputDir, $subdir) {
		if ($scanPath = realpath($inputDir . $subdir . '/img')) {

			$storePath = realpath($outputDir) . '/';
			if (!is_dir($storePath . $subdir)) {
				if (!mkdir($storePath . $subdir)) {
					echo("Error output subdir creation.\n");
					return 0;
				}
			}

			$storePath = realpath($outputDir . $subdir);

			if ($dh = @opendir($scanPath)) {
				while(false !== ($entry = readdir($dh))) {
					if (is_dir($entry)) {
						continue;
					}

					$info = pathinfo($entry);

					if (strcasecmp($info['extension'], 'png')) {
						continue;
					}

					handleImage($scanPath . "/{$entry}", $storePath . "/{$entry}");

					echo($subdir . ': ' . $entry . "\n");
				}

				closedir($dh);

				echo("\n");
			}
		}
	}

	foreach($subdirs as $subdir) {
		dierctoryWalker($inputDir, $outputDir, $subdir);
	}
