<html>
<head>
 <title>GDO : Tests : Class _Image_</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="index.php">Tests</a>
: <a href="test.image.php">Class DImage</a>
</div>

<div id="content">

<h1>Test GDO : Image</h1>
<?php

require_once '../src/evolya.gdo.php';

require_once 'test.php';

class TestImageCopy extends Test {
	
	public function TestImageCopy() {
		parent::Test('Image copy');
	}
	
	public function run() {
		
		$res1 = new DImage('images/image1.jpg');
		
		//$res2 = new DImage($res1);
		
		//$this->assertNotEquals("$res1", "$res2", 'toString : %1 == %2');
		//$this->assertEquals($res1->getChecksum(), $res2->getChecksum(), 'Checksum !=');
		
		//echo $res1;
		//echo ' '.$res1->getChecksum();
		
		echo '<br />';
		//echo $res2;
		//echo ' '.$res2->getChecksum();

		echo $res1->destroy();
		//echo $res2->destroy();
		
		$this->onSuccess();
		
	}
	
}

class TestImageDimension extends Test {
	
	public function TestImageDimension() {
		parent::Test('Image with Dimension');
	}
	
	public function run() {
		
		$Resource = new DImage(500, 1500);

		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 500, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 1500, 'La largeur de l\'image est invalide');
		
		echo "<li>$Resource</li>";
		
		$Resource->destroy();
		$context = 'Apr�s dispose';
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
		
		echo "<li>$Resource</li>";
		
	}
	
}

class TestImageWithGDResource extends Test {
	
	public function TestImageWithGDResource() {
		parent::Test('Image with GD Resource');
	}
	
	public function run() {
	
		$im = imagecreatetruecolor(127, 284);
		
		$Resource = new DImage($im);
	
		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 127, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 284, 'La largeur de l\'image est invalide');
		
		echo "<li>$Resource</li>";
		
		$Resource->destroy();
		$context = 'Apr�s dispose';
		
		echo "<li>$Resource</li>";
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
	
	}
	
}

class TestImageWithResource extends Test {
	
	public function TestImageWithResource() {
		parent::Test('Image with Resource');
	}
	
	public function run() {
	
		$res = new DImage(345, 533);
		
		$Resource = new DImage($res);
	
		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 345, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 533, 'La largeur de l\'image est invalide');
		
		echo "<li>$Resource</li>";
		
		$Resource->destroy();
		$context = 'Apr�s dispose';
		
		echo "<li>$Resource</li>";
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
	
	}
	
}

class TestImageWithFile extends Test {
	
	public function TestImageWithFile() {
		parent::Test('Image with File and resize');
	}
	
	public function run() {
	
		$Resource = new DImage(new DFile('images/image1.jpg'));
		
		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 1000, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 669, 'La largeur de l\'image est invalide');
		
		//$this->assertEquals($Resource->getOriginalDimension()->getWidth(), 1000, 'La largeur originelle de l\'image est invalide');
		
		//$this->assertEquals($Resource->getOriginalDimension()->getHeight(), 669, 'La largeur originelle de l\'image est invalide');
		
		$Resource->setDimensions(500, 640);
		
		echo "<li>$Resource</li>";
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 500, 'La largeur de l\'image est invalide apres setDimension');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 640, 'La largeur de l\'image est invalide apres setDimension');
		
		$Resource->setWidth(100);
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 100, 'La largeur de l\'image est invalide apres setWidth');
		
		$Resource->setHeight(200);
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 200, 'La largeur de l\'image est invalide apres setHeight');

		echo "<li>$Resource</li>";
		
		$Resource->destroy();
		
		echo "<li>$Resource</li>";
		
		$this->assertNull($Resource->getInfos(), 'getInfos() ne doit renvoyer null');
		
		$this->assertNull($Resource->getGDResource(), 'getGDResource() doit renvoyer null');
		
		$this->assertFalse($Resource->isBound(), 'isBound() doit renvoyer false');
		
		
	}
	
}

class TestImageMemory extends Test {
	
	protected $file;
	protected $memory;
	
	public function TestImageMemory($file, $memory='8M') {
		parent::Test('Image with memory calculation');
		$this->file = $file;
		$this->memory = $memory;
	}
	
	public function run() {
	
		$lastMemoryLimitValue = ini_get('memory_limit');
	
		echo '<p>Set memory_limit = '.$this->memory.'</p>';
		
		ini_set('memory_limit', $this->memory);
		
		$file = new DFile($this->file);
		
		$info = DFileImageInfo::getImageInfo($file);
		
		echo "<p>$info</p>";
		
		$channels = $info->getChannels() > 0 ? $info->getChannels() : 3;
		$bits = $info->getBits();
		
		$needed = DMemoryManager::calculateImageMemoryCreateFromFile(
			$info->getWidth(),
			$info->getHeight(),
			$channels,
			$bits
		);
		
		echo '<p>Estimated memory needed : '.DMemoryManager::return_string($needed)." ($needed o)</p>";
		
		echo '<p>Memory available before starting : '.DMemoryManager::getAvailableMemoryVerbose().' / '.
			DMemoryManager::getTotalAvailableMemoryVerbose().'</p>';
		
		echo '<p>Estimated possibility : <strong>'.(DMemoryManager::canCreateFromFile($info->getWidth(), $info->getHeight(),
			$channels, $bits) ? 'YES' : 'NON').'</strong></p>';
		
		
		echo '<p>Trying to load picture..</p>';
		
		$mem = DMemoryManager::getAvailableMemory();
		
		try {
			$Resource = new DImage($file);
		} catch (Exception $ex) {
			echo '<p style="color:red"><strong>Exception</strong>: '.$ex.'</p>';
			ini_set('memory_limit', $lastMemoryLimitValue);
			return;
		}
		
		$used = $mem - DMemoryManager::getAvailableMemory();
		
		echo '<p>Ok, done! &nbsp; &nbsp; Memory used : '.DMemoryManager::return_string($used)." ($used o)</p>";
		
		$Resource->destroy();
		
		if ($used == 0) {
			echo '<p>Result : ???</p>';
		}
		else {
			$result = abs($needed / $used - 1);
			echo '<p>Result : <strong style="color:'.($used > $needed ? 'red' : 'black').'">'
			.($result < 0.1 ? 'LOW' : 'HIGH').'</strong> ('
			.round($result * 100, 0).' % of error)</p>';
		}
		
		ini_set('memory_limit', $lastMemoryLimitValue);
	
	}
	
}

/**************************************************************************************/

$test = new TestImageCopy();
$test->start();

echo '<p><img src="test.image-copy.php" /></p>';

$test = new TestImageDimension();
$test->start();

$test = new TestImageWithGDResource();
$test->start();

$test = new TestImageWithResource();
$test->start();

$test = new TestImageWithFile();
$test->start();

DImage::$throwExceptionForMemoryExhausted = FALSE;

$test = new TestImageMemory('images/image1.jpg', '21M');
$test->start();

$test = new TestImageMemory('images/image5.png', '14M');
$test->start();

$test = new TestImageMemory('images/image3-180-8b.jpg', '20M');
$test->start();

?>


<h4>Update at <?php echo time(); ?></h4>

</div>
</body>
</html>