<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/transparent2.png');
$cfg = DOutputHelper::$JPEG_FULL;
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>