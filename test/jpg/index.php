<?php

require '../test.php';

define('DO_NOT_RENDER', TRUE);

class TestJPEGDefaut extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un JPEG par defaut', 'test.default.php', $checksum);
	}
}

class TestJPEGProgressif extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un JPEG par progressif (entrelacé)', 'test.progressif.php', $checksum);
	}
}

class TestJPEGFromPNG extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un JPEG à partir d\'un PNG transparent', 'test.png-transparent.php', $checksum);
	}
}

?>
<html>
<head>
 <title>GDO : Tests : JPEG Output</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">JPEG Output</a>
</div>

<div id="content">
<?php

$test = new TestJPEGDefaut('dc47b7fb154962c38397898e2fbf6aee');
$test->start();

$test = new TestJPEGProgressif('dc47b7fb154962c38397898e2fbf6aee'); // Cela ne devrais pas fonctionner avec ce checksum...
$test->start();

$test = new TestJPEGFromPNG('3b9aa70f7c2964959167471e20f15911');
$test->start();

?>
</div>
</body>
</html>