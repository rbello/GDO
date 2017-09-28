<?php

require_once '../../src/evolya.gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

class GettersSettersTest extends Test {

	private $layer1;
	private $layer2;
	private $layer3;

	public function __construct() {
		parent::Test('-- Getters/Setters Test');
	}

	protected function showOrder() {
		echo '<pre>';
		foreach ($this->stack->getLayers() as $l) {
			if ($l == $this->layer1) echo "Layer 1\n";
			if ($l == $this->layer2) echo "Layer 2\n";
			if ($l == $this->layer3) echo "Layer 3\n";
		}
		echo '</pre>';
	}

	public function run() {

		$this->stack = new DImageStack();

		$this->assertEquals($this->stack->getLayerCount(), 0, 'Layer count step # 1');

		$this->layer1 = new DLayer(new DImage('../images/transparent1.png'));
		$this->stack->addLayer($this->layer1);

		$this->assertEquals($this->stack->getLayerCount(), 1, 'Layer count step # 2');

		$this->layer2 = new DLayer(new DImage('../images/transparent2.png'));
		$this->stack->addLayer($this->layer2);

		$this->assertEquals($this->stack->getLayerCount(), 2, 'Layer count step # 3');

		$this->layer3 = new DLayer(new DImage('../images/image5.png'));
		$this->stack->addLayer($this->layer3);

		echo '<p>Start stack order</p>';
		$this->showOrder();

		echo '<p>Move Layer 1 UP two times</p>';
		$this->stack->moveUp($this->layer1);
		$this->stack->moveUp($this->layer1);
		$this->showOrder();

		echo '<p>Move Layer 3 UP one times</p>';
		$this->stack->moveUp($this->layer3);
		$this->showOrder();

		echo '<p>Move Layer 3 UP one times</p>';
		$this->stack->moveUp($this->layer3);
		$this->showOrder();

		echo '<p>Move Layer 3 DOWN two times</p>';
		$this->stack->moveDown($this->layer3);
		$this->stack->moveDown($this->layer3);
		$this->showOrder();

		$this->assertTrue($this->stack->containsLayer($this->layer1), 'Layer contains n° 1 step # 1');
		$this->assertTrue($this->stack->containsLayer($this->layer2), 'Layer contains n° 2 step # 1');
		$this->assertTrue($this->stack->containsLayer($this->layer3), 'Layer contains n° 3 step # 1');
		$this->assertEquals($this->stack->getLayerCount(), 3, 'Layer count step # 4');

		$this->stack->removeLayer($this->layer3);

		$this->assertEquals($this->stack->getLayerCount(), 2, 'Layer count step # 5');
		$this->assertTrue($this->stack->containsLayer($this->layer1), 'Layer contains n° 1 step # 2');
		$this->assertTrue($this->stack->containsLayer($this->layer2), 'Layer contains n° 2 step # 2');
		$this->assertFalse($this->stack->containsLayer($this->layer3), 'Layer not contains n° 3 step # 2');

		echo '<p>Final stack order</p>';
		$this->showOrder();

		$this->onSuccess();

	}
}

?><html>
<head>
 <title>GDO : Tests : Class _ImageStack_</title>
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
</div>

<div id="content">

<p>See also, <a href="index2.php">tests for urbexplorer</a>.</p>

<?php

$test = new GettersSettersTest();
$test->start();

?>

<h2>Stack test 1</h2>
<p><img src="test.stack1.php" /></p>

<h2>Stack test 2</h2>
<p><img src="test.stack2.php" /></p>

<h2>Stack test 3</h2>
<p><img src="test.stack3.php" /></p>

</div>

</body>
</html>