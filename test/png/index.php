<?php

require '../test.php';

define('DO_NOT_RENDER', TRUE);

class TestPNGDefaut extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un PNG par defaut avec transparence', 'test.default.php', $checksum);
	}
}

class TestPNGAlpha extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un PNG sans alpha', 'test.alpha.php', $checksum);
	}
}

class TestFree extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un PNG compatible FREE.FR', 'test.free.php', $checksum);
	}
}

?>
<html>
<head>
 <title>GDO : Tests : PNG Output</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">PNG Output</a>
</div>

<div id="content">
<?php

$test = new TestPNGDefaut('3b9aa70f7c2964959167471e20f15911');
$test->start();

$test = new TestPNGAlpha('3b9aa70f7c2964959167471e20f15911');
$test->start();

$test = new TestFree('ABCD');
$test->start();

?>
</div>
</body>
</html>