<?php
require_once '../../src/evolya.gdo.php';
$label = new DLabel('TTF Font', new DFont('alba.ttf'));
$label->setAngle(10);
$label->setForeground(DColor::$RED);
if (!defined('DO_NOT_RENDER')) $label->render();
?>