<?php

require_once '../../src/evolya.gdo.php';

$icon = @$_GET['icon'];
$rate = @$_GET['rate'];
$rate = intval($rate);
$done = @$_GET['done'] == 'true';
$save = @$_GET['save'] == 'true';

$image = new _Image_($icon);

$star = new _Image_('../../../urbex/images/star.png');

$stack = new DImageStack();

$stack->addLayer(new DLayer($image));

if ($rate > 2) {
	$stack->addLayer(new DLayer(
		$star,
		new DRelativeLayerLocation(
			DHorizontalLocation::$LEFT,
			DVerticalLocation::$TOP
		)
	));
}
if ($rate > 3) {
	$stack->addLayer(new DLayer(
		$star,
		new DRelativeLayerLocation(
			DHorizontalLocation::$CENTER,
			DVerticalLocation::$TOP
		)
	));
}
if ($rate > 4) {
	$stack->addLayer(new DLayer(
		$star,
		new DRelativeLayerLocation(
			DHorizontalLocation::$RIGHT,
			DVerticalLocation::$TOP
		)
	));
}

$img = new _Image_($stack->toResource());

if ($done) {
	$img->addFilter(new _GrayscaleFilter_());
}

if ($save) {
	$filename = @$_GET['style'];
	if ($done) $filename .= '_done';
	if ($rate > 2) {
		$filename .= '_'.$rate;
	}
	$filename .= '.png';
	$filename = 'icons/'.$filename;
	$img->save(new DFile($filename), new DPNG());
}

$img->render();

?>