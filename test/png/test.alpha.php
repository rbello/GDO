<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/transparent2.png');
$cfg = new DPNGOutputConfig();
$cfg->setAlpha(FALSE);
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>