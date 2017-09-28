<?php

# from http://us.php.net/manual/en/function.imagecreatefromjpeg.php

/*
    $imageInfo = getimagesize($filename);
    $MB = 1048576;  // number of bytes in 1M
    $K64 = 65536;    // number of bytes in 64K
    $TWEAKFACTOR = 1.5;  // Or whatever works for you
    $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
                                           * $imageInfo['bits']
                                           * $imageInfo['channels'] / 8
                             + $K64
                           ) * $TWEAKFACTOR
                         );
*/

/*
$memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
*/

/*
$imageInfo = GetImageSize($imageFilename);
$memoryNeeded = Round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
*/

/*

    //# [070203]
    //# check for jpeg file header and footer - also try to fix it
    function check_jpeg($f, $fix=false ){
        if ( false !== (@$fd = fopen($f, 'r+b' )) ){
            if (fread($fd,2)==chr(255).chr(216) ){
                fseek ( $fd, -2, SEEK_END );
                if ( fread($fd,2)==chr(255).chr(217) ){
                    fclose($fd);
                    return true;
                }else{
                    if ($fix && fwrite($fd,chr(255).chr(217)) ){
                        fclose($fd);
                        return true;
                    }                   
                    fclose($fd);
                    return false;
                }
            } else {
                fclose($fd);
                return false;
            }
        } else {
            return false;
        }
    }
*/

/*
function LoadJPEG ($imgURL) {

    ##-- Get Image file from Port 80 --##
    $fp = fopen($imgURL, "r");
    $imageFile = fread ($fp, 3000000);
    fclose($fp);

    ##-- Create a temporary file on disk --##
    $tmpfname = tempnam ("/temp", "IMG");

    ##-- Put image data into the temp file --##
    $fp = fopen($tmpfname, "w");
    fwrite($fp, $imageFile);
    fclose($fp);

    ##-- Load Image from Disk with GD library --##
    $im = imagecreatefromjpeg ($tmpfname);

    ##-- Delete Temporary File --##
    unlink($tmpfname);

    ##-- Check for errors --##
    if (!$im) {
        print "Could not create JPEG image $imgURL";
    }

    return $im;
}
*/

/*
My function to know how much bytes imagecreate or imagecreatetruecolor require before using it.
<?php
function getNeededMemoryForImageCreate($width, $height, $truecolor) {
  return $width*$height*(2.2+($truecolor*3));
}
?>
*/


header('Content-Type: text/plain');

//ini_set('memory_limit', '50M');

function format_size($size) {
  if ($size < 1024) {
    return $size . ' bytes';
  }
  else {
    $size = round($size / 1024, 2);
    $suffix = 'KB';
    if ($size >= 1024) {
      $size = round($size / 1024, 2);
      $suffix = 'MB';
    }
    return $size . ' ' . $suffix;
  }
}

$start_mem = memory_get_usage();

echo <<<INTRO
The memory required to load an image using imagecreatefromjpeg() is a function
of the image's dimensions and the images's bit depth, multipled by an overhead.
It can calculated from this formula:
Num bytes = Width * Height * Bytes per pixel * Overhead fudge factor
Where Bytes per pixel = Bit depth/8, or Bits per channel * Num channels / 8.
This script calculates the Overhead fudge factor by loading images of
various sizes.
INTRO;

echo "\n\n";

echo 'Limit: ' . ini_get('memory_limit') . "\n";
echo 'Usage before: ' . format_size($start_mem) . "\n";

// Place the images to load in the following array:
$images = array('image1.jpg', 'image2.jpg', 'image3.jpg', 'image3-180-8b.jpg', 'image1-16b.png');
$ffs = array();

echo "\n";

foreach ($images as $image) {
  $image = "images/$image";
  $info = getimagesize($image);
  printf('Loading image %s, size %sx%s, bpp %s, channels %s... ',
         $image, $info[0], $info[1], $info['bits'], $info['channels']);

  if ($info[2] == 3) {
	$im = imagecreatefrompng($image);
	$info['channels'] = 3;
  } else {
    $im = imagecreatefromjpeg($image);
  }

  $mem = memory_get_usage();
  echo 'done' . "\n";
  echo 'Memory used: ' . format_size($mem) . "\n";
  echo 'Real memory usage: ' . format_size($mem - $start_mem) . "\n";
  $ff = (($mem - $start_mem) /
         ($info[0] * $info[1] * ($info['bits'] / 8) * $info['channels']));
  $ffs[] = $ff;
  echo 'Real memory usage / (Width * Height * Bytes per pixel): ' . $ff . "\n";
  imagedestroy($im);
  $start_mem = memory_get_usage();
  echo 'Destroyed. Memory used: ' . format_size($start_mem) . "\n";

  echo "\n";
}

echo 'Mean fudge factor: ' . (array_sum($ffs) / count($ffs));

?>