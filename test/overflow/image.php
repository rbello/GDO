<?php

require_once '../../src-gdo/gdo.php';

/********************/

function clean() {
	$t = array();
	foreach ($_SESSION['gdo_queries'] as $time) {
		if (time() - $time < 2) {
			$t[] = $time;
		}
	}
	$_SESSION['gdo_queries'] = $t;
}

session_start();

if (!isset($_SESSION['gdo_count'])) {
	$_SESSION['gdo_queries'] = array();
}

clean();

while (sizeof($_SESSION['gdo_queries']) > 2) {
	sleep(1);
	clean();
}

$_SESSION['gdo_queries'][] = time();

/********************/

$filter = @$_GET['f'];

$gd = new _Image_('../images/image7.jpg');

if ($filter == 'negative') {
	$gd->addFilter(new _NegativeFilter_());
}
if ($filter == 'grayscale') {
	$gd->addFilter(new _GrayscaleFilter_());
}
if ($filter == 'brightness1') {
	$gd->addFilter(new _BrightnessFilter_(180));
}
if ($filter == 'brightness0') {
	$gd->addFilter(new _BrightnessFilter_(-100));
}
if ($filter == 'contrast1') {
	$gd->addFilter(new _ContrastFilter_(50));
}
if ($filter == 'contrast0') {
	$gd->addFilter(new _ContrastFilter_(-50));
}

$gd->render();

?>