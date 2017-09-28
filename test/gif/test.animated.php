<?php
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
$img = new DImage('../images/animated.gif');
$cfg = new DGIFOutputConfig();
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>