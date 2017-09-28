<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/image3.jpg');
$img->setWidthRatio(300);
$cfg = new DGIFOutputConfig();
$cfg->setPaletteColorNumber(50);
$cfg->setDitherEnabled(FALSE);
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>