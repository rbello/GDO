<?php
require_once '../../src/evolya.gdo.php';
$size = @intval($_GET['size']);
if (!$size) $size = 1;
$label = new DLabel('Label with size '.$size);
$label->getFont()->setSize($size);
if (!defined('DO_NOT_RENDER')) $label->render();
?>