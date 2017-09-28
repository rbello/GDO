<?php
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
$img = new DImage('../images/animated.gif');
$img->download('test.gif', new DGIF());
?>