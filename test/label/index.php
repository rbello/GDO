<?php

require_once '../../src/evolya.gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

class TestLabel {

	public function TestLabel($title, $file) {
		echo '<h2>'.$title.'</h2>';
		echo '<pre>';
		echo htmlentities(file_get_contents($file));
		echo '</pre>';
		echo '<h3 style="background:#ddd">Result : <img src="'.$file.'" /></h3>';
		include $file;
		echo '<p>toString : '.$label.'</p>';
	}

}

class TestLabelConstructors extends Test {
	
	public function TestLabelConstructors() {
		parent::Test('Constructeurs');
	}
	
	public function run() {
		
		// Construct 1
		$label = new DLabel('Test');
		$this->assertNotNull($label->getForeground(), 'Construct 1 : Le foreground ne devrais pas etre null');
		$this->assertNull($label->getBackground(), 'Construct 1 : Le background devrais etre null');
		$this->assertEquals($label->getFont(), DFont::$DEFAULT, 'Construct 1 : La font devrais etre par defaut');
		$this->assertEquals($label->getAngle(), 0, 'Construct 1 : l\'angle devrait etre de 0');
		echo "<p>Construct 1 : $label</p>";
		
		// Construct 2
		$foreground = new DColor(234, 76, 23, 'couleur foreground');
		$label = new DLabel('Test', $foreground);
		$this->assertEquals($label->getForeground(), $foreground, 'Construct 2 : Le foreground devrait correspondre � '.$foreground);
		$this->assertNull($label->getBackground(), 'Construct 2 : Le background devrais etre null');
		$this->assertEquals($label->getFont(), DFont::$DEFAULT, 'Construct 2 : La font devrais etre par defaut');
		$this->assertEquals($label->getAngle(), 0, 'Construct 2 : l\'angle devrait etre de 0');
		echo "<p>Construct 2 : $label</p>";
		
		// Construct 3
		$background = new DColor(123, 1, 233, 'couleur background');
		$label = new DLabel('Test', $foreground, $background);
		$this->assertEquals($label->getForeground(), $foreground, 'Construct 3 : Le foreground devrait correspondre � '.$foreground);
		$this->assertEquals($label->getBackground(), $background, 'Construct 3 : Le background devrait correspondre � '.$background);
		$this->assertEquals($label->getFont(), DFont::$DEFAULT, 'Construct 3 : La font devrais etre par defaut');
		$this->assertEquals($label->getAngle(), 0, 'Construct 3 : l\'angle devrait etre de 0');
		echo "<p>Construct 3 : $label</p>";

		// Construct 4
		$font = new DFont(5);
		$label = new DLabel('Ceci est un label avec un text assez long quand m�me', $foreground, $background, $font);
		$this->assertEquals($label->getForeground(), $foreground, 'Construct 4 : Le foreground devrait correspondre � '.$foreground);
		$this->assertEquals($label->getBackground(), $background, 'Construct 4 : Le background devrait correspondre � '.$background);
		$this->assertNotEquals($label->getFont(), DFont::$DEFAULT, 'Construct 4 : La font ne devrais pas etre par defaut');
		$this->assertEquals($label->getFont(), $font, 'Construct 4 : La font devrait correspondre � '.$font);
		$this->assertEquals($label->getAngle(), 0, 'Construct 4 : l\'angle devrait etre de 0');
		echo "<p>Construct 4 : $label</p>";
		
		$this->onSuccess();
		
		
	}

}

class TestLabelGetterSetters extends Test {
	
	public function TestLabelGetterSetters() {
		parent::Test('Getters Setters');
	}
	
	public function run() {
		$label = new DLabel('Test');
		$label->setAngle(110);
		$label->setAngle(0);
		echo $label;
		$this->onSuccess();
	}

}

class TestLabelMultisize extends TestLabel {
	public function TestLabelMultisize($title, $file) {
		echo '<h2>'.$title.'</h2>';
		echo '<pre>';
		echo htmlentities(file_get_contents($file));
		echo '</pre>';
		echo '<h3 style="background:#ddd">Result 1 : <img src="'.$file.'?size=1" /></h3>';
		echo '<h3 style="background:#ddd">Result 2 : <img src="'.$file.'?size=2" /></h3>';
		echo '<h3 style="background:#ddd">Result 3 : <img src="'.$file.'?size=3" /></h3>';
		echo '<h3 style="background:#ddd">Result 4 : <img src="'.$file.'?size=4" /></h3>';
		echo '<h3 style="background:#ddd">Result 5 : <img src="'.$file.'?size=5" /></h3>';
		include $file;
	}
}

class TestTTFBox extends Test {
	public function TestTTFBox() {
		parent::Test('TTF Box');
	}
	
	public function run() {
		echo '<ol>';
		$this->test(0);
		$this->test(25);
		$this->test(45);
		$this->test(90);
		echo '</ol>';
	}
	
	public function test($angle) {
		$box = imagettfbbox(40, $angle, 'alba.ttf', 'Helloque World !');
		echo '<li>Angle '.$angle.'� &nbsp; '.print_r($box, TRUE);
		
		$bbox = $this->calculateTextBox('Helloque World !', 'alba.ttf', 40, $angle);
		
		$r = new DRectangle(
			$bbox['width'] + abs($bbox['left']),
			$bbox['height'] + abs($bbox['top'])
		);
		
		echo ' &nbsp; Box : '.$r;
		echo '</li>';
	}
	
	public function calculateTextBox($text, $fontFile, $fontSize, $fontAngle) {
	  $rect = imagettfbbox($fontSize,$fontAngle,$fontFile,$text);
	 
	  $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
	  $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
	  $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
	  $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));

	  return array(
	    "left"   => abs($minX),
	    "top"    => abs($minY),
	    "width"  => $maxX - $minX,
	    "height" => $maxY - $minY,
	    "box"    => $rect
	  );
	}

	
}


?><html>
<head>
 <title>GDO : Tests : Class DLabel</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class DLabel</a>
</div>

<div id="content">

<h1>Test GDO : Label</h1>

<?php

$test = new TestLabelConstructors();
$test->start();

$test = new TestLabelGetterSetters();
$test->start();

$test = new TestLabel('Hello World', 'exec.label-helloworld.php');

$test = new TestLabelMultisize('GD Font sizes', 'exec.label-gdfont-size.php');

$test = new TestTTFBox();
$test->start();

$test = new TestLabel('TTF Font', 'exec.label-ttf.php');

?>


</div>

</body>
</html>