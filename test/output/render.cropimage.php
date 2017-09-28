<?php
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
$res = new DImage('../images/animated.gif');
$res->crop(0, 0, 40, 40);
$res->render(new DGIF());
?>