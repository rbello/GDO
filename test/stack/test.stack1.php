<?php

require_once '../../src/evolya.gdo.php';

$stack = new DImageStack();

$stack->addLayer(new DLayer(new DImage('../images/image6.jpg')));

$stack->addLayer(new DLayer(
	new DImage('../images/transparent2.png'),
	new DAbsoluteLayerLocation(10, 10)
));

$stack->addLayer(new DLayer(
	new DImage('../images/transparent1.png'),
	new DRelativeLayerLocation(
		DHorizontalLocation::$RIGHT,
		DVerticalLocation::$BOTTOM
	)
));

$stack->addLayer(new DLayer(
	new DImage('../images/image4.jpg'),
	new DRelativeLayerLocation(
		DHorizontalLocation::$CENTER,
		DVerticalLocation::$MIDDLE
	)
));

$stack->render(new DPNG());

?>