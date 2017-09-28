<?php

require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';

$cache = new DCache('cache');

error_reporting(E_ALL);

if (!$cache->outputResource('Animated image with GIF output config')) {
	$label = new DLabel('Erreur : impossible de trouver la ressource dans le cache');
	$label->render();
}

?>