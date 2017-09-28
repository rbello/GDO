<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/image4.jpg');
$cfg = DOutputHelper::$JPEG_FULL;
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>