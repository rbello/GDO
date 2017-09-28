<?php
require_once '../../src/evolya.gdo.php';
$img = new DImage('../images/icon.gif');
$img->download('test.gif', new DGIF());
?>