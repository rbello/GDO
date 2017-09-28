<?php

define('DO_NOT_RENDER', TRUE);

require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
require_once '../test.php';

class ResourceTest extends Test {
	public function __construct() {
		parent::Test('Test de resources');
	}
	public function run() {

		$mem = DMemoryManager::getMemoryUsage();

		$res = imagecreatefromgif('../images/animated.gif');

		$res = new DImage($res);
		$this->assertEquals($res->getInfos()->getWidth(), 64, 'Test 1 : getWidth');
		$this->assertEquals($res->getInfos()->getHeight(), 64, 'Test 1 : getHeight');
		$this->assertTrue($res->isBound(), 'Test 1 : isBound');

		$img = new DImage($res);
		$this->assertEquals($img->getInfos()->getWidth(), 64, 'Test 2 : getWidth');
		$this->assertEquals($img->getInfos()->getHeight(), 64, 'Test 2 : getHeight');
		//$this->assertEquals($img->getOriginalDimension()->getWidth(), 64, 'Test 2 : originalDimension.getWidth');
		//$this->assertEquals($img->getOriginalDimension()->getHeight(), 64, 'Test 2 : originalDimension.getHeight');
		$this->assertTrue($res->isBound(), 'Test 2 : isBound');

		$res->destroy();
		$this->assertFalse($res->isBound(), 'Test 3 : isBound');
		$this->assertTrue($img->isBound(), 'Test 4 : isBound');

		$img->destroy();
		$this->assertFalse($res->isBound(), 'Test 5 : isBound');
		$this->assertFalse($img->isBound(), 'Test 6 : isBound');

		$img = new DImage('../images/animated.gif');
		$this->assertEquals($img->getInfos()->getWidth(), 64, 'Test 7 : getWidth');
		$this->assertEquals($img->getInfos()->getHeight(), 64, 'Test 7 : getHeight');
		//$this->assertEquals($img->getOriginalDimension()->getWidth(), 64, 'Test 7 : originalDimension.getWidth');
		//$this->assertEquals($img->getOriginalDimension()->getHeight(), 64, 'Test 7 : originalDimension.getHeight');
		$img->setWidth(200);
		$this->assertEquals($img->getInfos()->getWidth(), 200, 'Test 8 : getWidth');
		$this->assertEquals($img->getInfos()->getHeight(), 64, 'Test 8 : getHeight');
		//$this->assertEquals($img->getOriginalDimension()->getWidth(), 64, 'Test 8 : originalDimension.getWidth');
		//$this->assertEquals($img->getOriginalDimension()->getHeight(), 64, 'Test 8 : originalDimension.getHeight');
		//$img->applyChanges();
		$this->assertEquals($img->getInfos()->getWidth(), 200, 'Test 9 : getWidth');
		$this->assertEquals($img->getInfos()->getHeight(), 64, 'Test 9 : getHeight');
		//$this->assertEquals($img->getOriginalDimension()->getWidth(), 200, 'Test 9 : originalDimension.getWidth (%1 != %2)');
		//$this->assertEquals($img->getOriginalDimension()->getHeight(), 64, 'Test 9 : originalDimension.getHeight');

		$img->destroy();
		$this->assertFalse($res->isBound(), 'Test 10 : isBound');
		$this->assertFalse($img->isBound(), 'Test 11 : isBound');

		$mem = abs(DMemoryManager::getMemoryUsage() - $mem);

		echo '<p>Difference de m�moire : '.DMemoryManager::return_string($mem).'</p>';
		$this->onSuccess();
	}
}

?><html>
<head>
 <title>GDO : Tests : Output Helper</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Output Helper</a>
</div>

<div id="content">

<p>Render as image</p>
<img src="render.image.php" />

<p>Render as resized image</p>
<img src="render.modifimage.php" />

<p>Render as croped image</p>
<img src="render.cropimage.php" />

<p>Render as croped and resized image</p>
<img src="render.cropmodifimage.php" />

<?php
$test = new ResourceTest();
$test->start();
?>
</div>

</body>
</html>