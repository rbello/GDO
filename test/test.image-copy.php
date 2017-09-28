<?php

require_once '../src/evolya.gdo.php';

$stack = new DImageStack();

$res1 = new DImage('images/image4.jpg');

$stack->addLayer(new DLayer(
	$res1
));

$res2 = new DImage($res1);

$stack->addLayer(new DLayer(
	$res2,
	new DAbsoluteLayerLocation(
		$res1->getInfos()->getWidth(),
		0
	)
));

$stack->render();

?>