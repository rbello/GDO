<?php

require_once '../../src/evolya.gdo.php';

$stack = new DImageStack();

$stack->addLayer(new DLayer(
	new DImage('../images/image6.jpg'),
	new DRelativeLayerLocation(
		DHorizontalLocation::$LEFT,
		DVerticalLocation::$TOP
	)
));

$stack->addLayer(new DLayer(
	new DImage('../images/image4.jpg'),
	new DRelativeLayerLocation(
		DHorizontalLocation::$RIGHT,
		DVerticalLocation::$BOTTOM
	)
));

$stack->addLayer(new DLayer(
	new DImage('../images/transparent2.png'),
	new DAbsoluteLayerLocation(
		-40,
		-10
	)
));

$stack->render(new DPNG());

?>