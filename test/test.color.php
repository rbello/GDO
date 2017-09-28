<html>
<head>
 <title>GDO : Tests : Class DColor</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>
<?php

require '../src/evolya.gdo.php';
require 'test.php';

class TestHexa extends Test {
	
	public function __construct() {
		parent::Test('Test Hexa');
	}
	
	public function run() {
		$this->test(1, 'ffffff', 255, 255, 255, 0);
		$this->test(2, '#ffffff', 255, 255, 255, 0);
		$this->test(3, '#7e7e7e', 126, 126, 126, 0);
		$this->test(4, '9e3d96', 158, 61, 150, 31);
		$this->test(5, '000000', 0, 0, 0, 0);
		$this->test(5, '#3b9c00', 59, 156, 0, 127);
		$this->onSuccess();
	}

	public function test($n, $hexa, $r, $v, $b, $a) {
		echo '<p>'.$n.' &raquo; new DColor(<code>"'.$hexa.'"</code>, '.$a.') ====&gt; ';
		$c = new DColor($hexa, $a);
		echo $c;
		echo '</p>';
		$this->assertEquals($r, $c->getRedValue(), 'Test '.$n.' : getREDvalue (%1 != %2)');
		$this->assertEquals($v, $c->getGreenValue(), 'Test '.$n.' : getGREENvalue (%1 != %2)');
		$this->assertEquals($b, $c->getBlueValue(), 'Test '.$n.' : getBLUEvalue (%1 != %2)');
		$this->assertEquals($a, $c->getAlphaValue(), 'Test '.$n.' : getALPHAvalue (%1 != %2)');
		$this->assertEquals(str_replace('#', '', $hexa), $c->toHexa(), 'Test '.$n.' : hexa (%1 != %2)');
	}

	public function echoRVB(DColor $c) {
		$rgba = $c->getValuesRGB();
		echo 'Rouge: '.$c->getRedValue().' ('.$rgba[0].')';
		echo ' Vert: '.$c->getGreenValue().' ('.$rgba[1].')';
		echo ' Bleu: '.$c->getBlueValue().' ('.$rgba[2].')';
		echo ' Alpha: '.$c->getAlphaValue().' ('.$rgba[3].')';
	}
}

class TestRVB extends Test {
	
	public function __construct() {
		parent::Test('Test RVB');
	}
	
	public function run() {
		$this->test(1, new DColor(0, 0, 0), 0, 0, 0, 0);
		$this->test(2, new DColor(255, 0, 0), 255, 0, 0, 0);
		$this->test(3, new DColor(0, 255, 0), 0, 255, 0, 0);
		$this->test(4, new DColor(0, 0, 255), 0, 0, 255, 0);
		$this->onSuccess();
	}

	public function test($n, DColor $c, $r, $v, $b, $a) {
		echo '<p>'.$n.' &raquo; new DColor('.$r.', '.$v.', '.$b.', '.$a;
		echo ') ====&gt; ';
		$this->echoRVB($c);
		echo ' ====&gt; ';
		echo $c;
		echo '</p>';
		$this->assertEquals($r, $c->getRedValue(), 'Test '.$n.' : getREDvalue (%1 != %2)');
		$this->assertEquals($v, $c->getGreenValue(), 'Test '.$n.' : getGREENvalue (%1 != %2)');
		$this->assertEquals($b, $c->getBlueValue(), 'Test '.$n.' : getBLUEvalue (%1 != %2)');
		$this->assertEquals($a, $c->getAlphaValue(), 'Test '.$n.' : getALPHAvalue (%1 != %2)');
		$rgba = $c->getValuesRGB();
		$this->assertEquals($r, $rgba[0], 'Test '.$n.' : getValuesRGB-red (%1 != %2)');
		$this->assertEquals($v, $rgba[1], 'Test '.$n.' : getValuesRGB-green (%1 != %2)');
		$this->assertEquals($b, $rgba[2], 'Test '.$n.' : getValuesRGB-blue (%1 != %2)');
		$this->assertEquals($a, $rgba[3], 'Test '.$n.' : getValuesRGB-alpha (%1 != %2)');
	}

	public function echoRVB(DColor $c) {
		$rgba = $c->getValuesRGB();
		echo 'Rouge: '.$c->getRedValue().' ('.$rgba[0].')';
		echo ' Vert: '.$c->getGreenValue().' ('.$rgba[1].')';
		echo ' Bleu: '.$c->getBlueValue().' ('.$rgba[2].')';
		echo ' Alpha: '.$c->getAlphaValue().' ('.$rgba[3].')';
	}
}

class TestHSB extends Test {
	
	public function __construct() {
		parent::Test('Test HSB');
	}
	
	public function run() {
		$this->test(1, new DColor(0, 0, 0), 0, 0, 0);
		$this->test(2, new DColor(255, 0, 0), 0, 100, 100);
		$this->test(3, new DColor(0, 255, 0), 120, 100, 100);
		$this->test(4, new DColor(0, 0, 255), 240, 100, 100);
		$this->test(5, new DColor(40, 30, 1), 44, 97, 15);
		$this->test(6, new DColor(115, 127, 177), 228, 35, 69);
		$this->test(7, new DColor(120, 120, 120), 0, 0, 47);
		$this->test(8, new DColor(61, 61, 61), 0, 0, 23);
		$this->test(9, new DColor(255, 255, 255), 0, 0, 100);
		$this->onSuccess();
	}

	public function test($n, DColor $c, $h, $s, $b) {
		echo '<p>'.$n.' &raquo; ';
		$this->echoRVB($c);
		echo ' ====&gt; ';
		$this->echoHSB($c);
		echo '</p>';
		$hsb = $c->getValuesHSB();
		$this->assertEquals($hsb[0], $h, 'Test '.$n.' : Teinte/Hue (%1 != %2)');
		$this->assertEquals($hsb[1], $s, 'Test '.$n.' : Saturation (%1 != %2)');
		$this->assertEquals($hsb[2], $b, 'Test '.$n.' : Luminosité/Brightness (%1 != %2)');
		$rvb = $c->getValuesRGB();
		$c->setValuesHSB($h, $s, $b);
		$rvb2 = $c->getValuesRGB();
		$this->assertEquals($rvb[0], $rvb2[0], 'Test '.$n.' : Red (%1 != %2)');
		$this->assertEquals($rvb[1], $rvb2[1], 'Test '.$n.' : Green (%1 != %2)');
		$this->assertEquals($rvb[2], $rvb2[2], 'Test '.$n.' : Blue (%1 != %2)');
	}

	public function echoHSB(DColor $c) {
		$hsb = $c->getValuesHSB();
		echo 'Teinte: '.$hsb[0].'° ';
		echo 'Saturation: '.$hsb[1].'% ';
		echo 'Luminosité: '.$hsb[2].'%';
	}

	public function echoRVB(DColor $c) {
		echo 'Rouge: '.$c->getRedValue();
		echo ' Vert: '.$c->getGreenValue();
		echo ' Bleu: '.$c->getBlueValue();
	}
}

?>

<div id="top">
GDO : <a href="index.php">Tests</a>
: <a href="test.color.php">Class DColor</a>
</div>

<div id="content">

<h1>Test GDO : Color</h1>
<?php

$test = new TestRVB();
$test->start();

$test = new TestHexa();
$test->start();

$test = new TestHSB();
$test->start();

?>

</div>
</body>
</html>