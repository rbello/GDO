<?php

require_once '../../src/evolya.gdo.php';

$filter = @$_GET['f'];

$gd = new DImage('../images/image7.jpg');

try {
	if ($filter == 'negative') {
		$gd->addFilter(new DNegativeFilter());
	}
	if ($filter == 'grayscale') {
		$gd->addFilter(new DGrayscaleFilter());
	}
	if ($filter == 'brightness1') {
		$gd->addFilter(new DBrightnessFilter(180));
	}
	if ($filter == 'brightness0') {
		$gd->addFilter(new DBrightnessFilter(-100));
	}
	if ($filter == 'contrast1') {
		$gd->addFilter(new DContrastFilter(50));
	}
	if ($filter == 'contrast0') {
		$gd->addFilter(new DContrastFilter(-50));
	}
	if ($filter == 'colorred') {
		$f = new DColorizeFilter();
		$f->setColor(DColor::$RED);
		$gd->addFilter($f);
	}
	if ($filter == 'edge') {
		$gd->addFilter(new DEdgeDetectFilter());
	}
	if ($filter == 'emboss') {
		$gd->addFilter(new DEmbossFilter());
	}
	if ($filter == 'gaussian') {
		$gd->addFilter(new DGaussianBlurFilter());
	}
	if ($filter == 'selectiveblur') {
		$gd->addFilter(new DSelectiveBlurFilter());
	}
	if ($filter == 'meanremoval') {
		$gd->addFilter(new DMeanRemovalFilter());
	}
	if ($filter == 'smooth0') {
		$gd->addFilter(new DSmoothFilter(0));
	}
	if ($filter == 'smooth6') {
		$gd->addFilter(new DSmoothFilter(6));
	}
	if ($filter == 'smooth10') {
		$gd->addFilter(new DSmoothFilter(10));
	}
	if ($filter == 'smooth5') {
		$gd->addFilter(new DSmoothFilter(-5));
	}
	if ($filter == 'sepia') {
		$gd->addFilter(new DSepiaFilter());
	}
	if ($filter == 'pixelate1') {
		$gd->addFilter(new DPixelateFilter());
	}
	if ($filter == 'sphere') {
		$gd->addFilter(new DSpherizeFilter());
	}
	if ($filter == 'twirl') {
		$f = new DTwirlFilter();
		$f->setCenter((int)(130/2), (int)(87/2));
		$f->setRadius(100);
		$f->setAngle(100);
		$f->setFullFill(TRUE);
		$gd->addFilter($f);
	}
	if ($filter == 'mix1') {
		$f = new DChannelMixFilter();
		$gd->addFilter($f);
	}
	if ($filter == 'hsb') {
		$f = new DHSBAdjustFilter();
		$gd->addFilter($f);
	}
	if ($filter == 'convolution1') {
		$f = new DConvolutionFilter();
		$f->setSampleValue1();
		$gd->addFilter($f);
	}
	if ($filter == 'convolution2') {
		$f = new DConvolutionFilter();
		$f->setSampleValue2();
		$gd->addFilter($f);
	}
	if ($filter == 'convolution3') {
		$f = new DConvolutionFilter();
		$f->setSampleValue3();
		$gd->addFilter($f);
	}
	if ($filter == 'negative-resized') {
		$gd->setWidth(250);
		$gd->addFilter(new DNegativeFilter());
	}
	if ($filter == 'grayscale-resized') {
		$gd->setWidth(250);
		$gd->addFilter(new DGrayscaleFilter());
	}
}
catch (Exception $ex) {
	$label = new DLabel('ERREUR ('.get_class($ex).' : '.$ex->getMessage().')');
	$label->render();
	exit();
}

$gd->render();

?>