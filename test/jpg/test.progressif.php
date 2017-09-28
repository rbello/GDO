<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/image4.jpg');

$cfg = new DJPEGOutputConfig();
$cfg->setQuality(100);
$cfg->setInterlace(TRUE);

if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>