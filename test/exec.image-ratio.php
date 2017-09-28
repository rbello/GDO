<?php
require_once '../src-gdo/gdo.php';
$img = new _Image_('images/image1.jpg');
$img->setWidthRatio(100);
if (!isset($doNotRender)) $img->render();
?>