<?php
require_once '../src-gdo/gdo.php';
$img = new _Image_('images/image1.jpg');
$img->setWidth(500);
$img->setHeight(100);
if (!isset($doNotRender)) $img->render();
?>