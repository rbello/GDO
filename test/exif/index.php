<?php

require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.exif.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);


?><html>
<head>
 <title>GDO : Tests : Class DExifImageInfo</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class DExifImageInfo</a>
</div>

<div id="content">
<?php

$infos = DExifImageInfo::getImageInfo(new DFile('../images/image-exif.jpg'));

echo $infos;

?>
</div>

</body>
</html>