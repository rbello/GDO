<?php

error_reporting(E_ALL);

$img = imagecreatefrompng('../images/transparent2.png');

if (!defined('DO_NOT_RENDER')) {
	header("Content-type: image/png");
	imagealphablending($img, FALSE);
	imagesavealpha($img, TRUE);
	imagepng($img);
}

?>