<?php
require_once '../src-gdo/gdo.php';
$img = new _Image_('images/image1.jpg');
$img->crop(0, 0, 1000, 669);
$img->setDimension(1000, 669);
$img->resetDimension();
if (!isset($doNotRender)) $img->render();
?>