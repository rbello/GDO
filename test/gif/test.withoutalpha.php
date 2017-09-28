<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/icon3.gif');
$cfg = DOutputHelper::$GIF;
if (!defined('DO_NOT_RENDER')) $img->render($cfg);
else echo $cfg;
?>