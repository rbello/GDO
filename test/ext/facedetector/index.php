<?php

error_reporting(E_ALL);

require '../../test.php';
require_once '../../../src/evolya.gdo.php';

define('DO_NOT_RENDER', TRUE);

?>
<html>
<head>
 <title>GDO : Tests : GDO Ext - Face Detector</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../../index.php">Tests</a>
: <a href="index.php">Face Detector</a>
</div>

<div id="content">

<h2>Original method <a href="http://www.svay.com/blog/index/post/2009/06/19/Face-detection-in-pure-PHP-(without-OpenCV)" target="_blank">web</a></h2>
<img src="maurice-svay-method.php" />

<h2>GDO-Ext</h2>
<img src="fd.php" />


</div>
</body>
</html>