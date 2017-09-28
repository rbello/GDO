<?php
require_once '../src-gdo/gdo.php';
$src = 'images/transparent'.$_GET['get'].'.png';
$img = new _Image_($src);
$img->render(_OutputHelper_::$PNG);
$img->destroy();
?>