<?php

require_once '../../src/evolya.gdo.php';

$icons = array(
	'default' => '../../../urbex/images/styles/default.png',
	'architecture' => '../../../urbex/images/styles/architecture.png',
	'militaire' => '../../../urbex/images/styles/militaire.png'
);

if (@$_GET['icon'] == '1') {
	$icon = $icons['default'];
	$rate = 0;
	$done = FALSE;
}
else if (@$_GET['icon'] == '2') {
	$icon = $icons['architecture'];
	$rate = 5;
	$done = TRUE;
}
else if (@$_GET['icon'] == '3') {
	$icon = $icons['militaire'];
	$rate = 4;
	$done = FALSE;
}
else if (@$_GET['icon'] == '4') {
	$icon = $icons['default'];
	$rate = 3;
	$done = TRUE;
}
else {
	$label = new _Label_('Erreur');
	$label->render();
	exit();
}

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
			DHorizontalLocation::$RIGHT,
			DVerticalLocation::$TOP
		)
	));
}
if ($rate > 4) {
	$stack->addLayer(new DLayer(
		$star,
		new DRelativeLayerLocation(
			DHorizontalLocation::$CENTER,
			DVerticalLocation::$TOP
		)
	));
}

$img = new _Image_($stack->toResource());

if ($done) {
	$img->addFilter(new _GrayscaleFilter_());
}

$img->render();

?>