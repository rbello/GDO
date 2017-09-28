<?php

require_once '../../../src/evolya.gdo.php';
require_once '../../../src/evolya.gdoext.facedetector.php';

/*$img = new DImage('../../images/obama.jpg');

$fd = new DFaceDetector($img);

if ($fd->getFace() != NULL) {
	imagerectangle(
		$img->getGDResource(),
		$fd->getFace()->getX(),
		$fd->getFace()->getY(),
		$fd->getFace()->getX()+$fd->getFace()->getWidth(),
		$fd->getFace()->getY()+$fd->getFace()->getHeight(),
		DColor::$RED->toGDColor()
	);
}

$img->render();*/

$fd = DFaceDetector::getInstance();

$img = new DImage('../../images/obama.jpg');

$face = $fd->detectFace($img);

$canvas = $img->getGDResource();

imagerectangle(
	$canvas,
	$face->getX(),
	$face->getY(),
	$face->getX() + $face->getWidth(),
	$face->getY() + $face->getHeight(),
	DColor::$RED->toGDColor()
);

$format = array(250, 300);



/*$img->crop(
	$face->getX(),
	$face->getY(),
	$face->getWidth(),
	$face->getHeight()
);*/

$img->render();

?>