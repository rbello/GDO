<?php

require_once '../../src/evolya.gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

?><html>
<head>
 <title>GDO : Tests : Class _ImageStack_ : tests for urbex</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class _ImageStack_</a>
: <a href="index2.php">tests for urbex</a>
</div>

<div id="content">

<li>Erreur : <img src="urbex-marker.php" /></li>
<li>Default rate 0 : <img src="urbex-marker.php?icon=1" /></li>
<li>Architecture rate 5 : <img src="urbex-marker.php?icon=2" /></li>
<li>Military rate 4 done : <img src="urbex-marker.php?icon=3" /></li>
<li>Default rate 3 : <img src="urbex-marker.php?icon=4" /></li>

<p>See also, <a href="index3.php">Icons generator</a></p>

</div>

</body>
</html>