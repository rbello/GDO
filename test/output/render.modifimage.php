<?php
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
$res = new DImage('../images/animated.gif');
$res->setWidth(200);
$res->render(new DGIF());
?>