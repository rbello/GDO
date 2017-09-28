<?php

require '../test.php';
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';

define('DO_NOT_RENDER', TRUE);

class TestGIFDefaut extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un GIF par defaut', 'test.default.php', $checksum);
	}
}

class TestGIFAnimated extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un GIF animé', 'test.animated.php', $checksum);
	}
}

class TestGIFFromTrueColor extends Test2 {
	public function __construct($checksum) {
		parent::__construct('Render d\'un GIF à partir d\'une image TrueColor JPG', 'test.savetruecolortogif.php', $checksum);
	}
}

class TestDownloadGIF extends Test {
	public function TestDownloadGIF() {
		parent::Test('Download simple GIF');
	}
	public function run() {
		echo '<input type="button" value="Download" onclick="document.location.href=\'test.download-simple-gif.php\';" />';
	}
}

class TestDownloadAnimatedGIF extends Test {
	public function TestDownloadAnimatedGIF() {
		parent::Test('Download animated GIF');
	}
	public function run() {
		echo '<input type="button" value="Download" onclick="document.location.href=\'test.download-animated-gif.php\';" />';
	}
}

class TestSaveGIF extends Test {
	public function TestSaveGIF() {
		parent::Test('Save simple GIF');
	}
	public function run() {
		$img = new DImage('../images/icon.gif');
		
		$file = new DFile('test.save-simple.gif');
		if ($file->isFile()) {
			$file->delete();
		}

		$img->save($file, new DGIF());
		
		echo '<img src="test.save-simple.gif" />';
	}
}

class TestSaveAnimatedGIF extends Test {
	public function TestSaveAnimatedGIF() {
		parent::Test('Save animated GIF');
	}
	public function run() {
		$img = new DImage('../images/animated.gif');
		
		$file = new DFile('test.save-animated.gif');
		if ($file->isFile()) {
			$file->delete();
		}

		echo $img->save($file, new DGIF()) ? '' : 'error';
		
		echo '<img src="test.save-animated.gif" />';
	}
}

?>
<html>
<head>
 <title>GDO : Tests : GIF Output</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">GIF Output</a>
</div>
<div id="content">

<p>See also <a href="animated.php">animated GIF test</a> or <a href="oldschool-solution.php">test with László Zsidi's solution</a>.</p>
<?php

$test = new TestGIFDefaut('3ae87c7e056a900a4826f35031711ecf');
$test->start();

$test = new TestGIFAnimated('149d50fc9694cf6e5011e82b73f1a11d');
$test->start();

$test = new TestGIFFromTrueColor('7ee32f1d4dd5d0c2441430e224277810');
$test->start();

$test = new TestDownloadGIF();
$test->start();

$test = new TestDownloadAnimatedGIF();
$test->start();

$test = new TestSaveGIF();
$test->start();

$test = new TestSaveAnimatedGIF();
$test->start();

?>
</div>
</body>
</html>