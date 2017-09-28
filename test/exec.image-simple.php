<?php
require_once '../src-gdo/gdo.php';
$img = new _Image_(100, 100);
if (!isset($doNotRender)) $img->render(_OutputHelper_::$JPEG_FULL);
?>