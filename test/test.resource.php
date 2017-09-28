<html>
<head>
 <title>GDO : Tests : Class DResource</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="index.php">Tests</a>
: <a href="test.resource.php">Class DResource</a>
</div>

<div id="content">

<h1>Test GDO : Resource</h1>
<?php

require_once '../src/evolya.gdo.php';

require_once 'test.php';

class TestResourceCopy extends Test {

	public function TestResourceCopy() {
		parent::Test('Resource copy');
	}

	public function run() {
		
		$res1 = new DResource(new Rectangle(50, 50));
		
		$res2 = new DResource($res1);
		
		$res3 = new DResource(new Rectangle(50, 50));
		
		echo $res1.' / '.$res1->getChecksum();
		$res1->destroy();
		
		echo '<br />';
		echo $res2.' / '.$res2->getChecksum();
		$res2->destroy();
		
		echo '<br />';
		echo $res3.' / '.$res3->getChecksum();
		$res3->destroy();
		
		
	}

}

class TestResourceDimension extends Test {
	
	public function TestResourceDimension() {
		parent::Test('Resource with Dimension');
	}
	
	public function run() {
		
		$Resource = new DResource(500, 1500);
		
		echo '<p>Checksum : '.$Resource->getChecksum().'</p>';

		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 500, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 1500, 'La largeur de l\'image est invalide');
		
		$Resource->destroy();
		$context = 'Après dispose';
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
		
	}
	
}

class TestResourceWithGDResource extends Test {
	
	public function TestResourceWithGDResource() {
		parent::Test('Resource with GD Resource');
	}
	
	public function run() {
	
		$im = imagecreatetruecolor(127, 284);
		
		$Resource = new DResource($im);
	
		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 127, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 284, 'La largeur de l\'image est invalide');
		
		$Resource->destroy();
		$context = 'Après dispose';
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
	
	}
	
}


class TestResourceWithResource extends Test {
	
	public function TestResourceWithResource() {
		parent::Test('Resource with other Resource');
	}
	
	public function run() {
	
		$Previous = new DResource(2000, 649);
	
		$Resource = new DResource($Previous);
	
		$this->assertNotNull($Resource->getInfos(), 'getInfos() ne doit pas renvoyer null');
		
		$this->assertNotNull($Resource->getGDResource(), 'getGDResource() ne doit pas renvoyer null');
		
		$this->assertTrue($Resource->isBound(), 'isBound() doit renvoyer true');
		
		$this->assertEquals($Resource->getInfos()->getWidth(), 2000, 'La largeur de l\'image est invalide');
		
		$this->assertEquals($Resource->getInfos()->getHeight(), 649, 'La largeur de l\'image est invalide');
		
		$Previous->destroy();
		$Resource->destroy();
		$context = 'Après dispose';
		
		$this->assertNull($Resource->getInfos(), $context.' : getInfos() doit renvoyer null');
		$this->assertNull($Resource->getGDResource(), $context.' : getGDResource() doit renvoyer null');
		$this->assertFalse($Resource->isBound(), $context.' : isBound() doit renvoyer false');
	
	}
	
}

/**************************************************************************************/

$test = new TestResourceCopy();
$test->start();

$mem = DMemoryHelper::getAvailableMemory();

$test = new TestResourceDimension();
$test->start();

?>
<p>Loose of memory : <?php echo DMemoryHelper::return_string($mem - DMemoryHelper::getAvailableMemory()); ?></p>
<?php

$mem = DMemoryHelper::getAvailableMemory();

$test = new TestResourceWithGDResource();
$test->start();

?>
<p>Loose of memory : <?php echo DMemoryHelper::return_string($mem - DMemoryHelper::getAvailableMemory()); ?></p>
<?php

$mem = DMemoryHelper::getAvailableMemory();

$test = new TestResourceWithResource();
$test->start();

?>
<p>Loose of memory : <?php echo DMemoryHelper::return_string($mem - DMemoryHelper::getAvailableMemory()); ?></p>
<h1>Test GDO : Resource + Memory</h1>
<p>Available memory BEFORE creating resource : <?php echo DMemoryHelper::getAvailableMemory(); ?> (<?php echo DMemoryHelper::getAvailableMemoryVerbose(); ?>)</p>
<?php

$w = 5000;
$h = 4000;

$prediction = DMemoryHelper::calculateImageMemory($w, $h);
$predictionTweak = DMemoryHelper::calculateImageMemoryCreateTrueColor($w, $h);

DResource::$throwExceptionForMemoryExhausted = FALSE;

?>
<p>Prediction for <?php echo $w.'x'.$h; ?> : <?php echo $prediction; ?> (<?php echo DMemoryHelper::return_string($prediction); ?>)</p>
<p>Prediction with tweak : <?php echo $predictionTweak; ?> (<?php echo DMemoryHelper::return_string($predictionTweak); ?>)</p>
<p>Can create : <strong><?php echo (DMemoryHelper::canCreateResource($w, $h) ? 'OUI' : 'NON'); ?></strong></p>
<p>Memory security :<?php echo DResource::$throwExceptionForMemoryExhausted ? 'enabled' : 'disabled'; ?></p>
<?php

$size = DMemoryHelper::getAvailableMemory();

try {

	$Resource = new DResource($w, $h);
	
	?>
	<p>Available memory AFTER creating resource : <?php echo DMemoryHelper::getAvailableMemory(); ?> (<?php echo DMemoryHelper::getAvailableMemoryVerbose(); ?>)</p>
	<?php

	$size = $size - DMemoryHelper::getAvailableMemory();
	$width = $Resource->getInfos()->getWidth();
	$height = $Resource->getInfos()->getHeight();
	$pixels = $width * $height;

	?>
	<li>Note* : <?php echo $width.'x'.$height.' ['.$pixels.'px : ratio '.round($size/$pixels, 5).' o/px] = '.$size.' ('.DMemoryHelper::return_string($size).')'; ?></li>
	<?php

	$Resource->destroy();

	?>
	<p>Available memory AFTER resource dispose : <?php echo DMemoryHelper::getAvailableMemory(); ?> (<?php echo DMemoryHelper::getAvailableMemoryVerbose(); ?>)</p>
	<?php
	
} catch (Exception $ex) {
	echo '<p style="color:red"><strong>'.get_class($ex).'</strong> : '.$ex->getMessage().'</p>';
}

?>


<h4>Update at <?php echo time(); ?></h4>

</div>
</body>
</html>