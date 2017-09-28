<?php
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
$res = new DImage('../images/animated.gif');
$res->render(new DGIF());
?>