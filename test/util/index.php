<?php

require_once '../../src/evolya.gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

class TestDBounds extends Test {
	
	public function __construct() {
		parent::Test('DBounds');
	}
	
	public function run() {

		$b = new DBounds();
		$this->assertEquals($b->getX(), 0, 'Test 1 : invalid X');
		$this->assertEquals($b->getY(), 0, 'Test 1 : invalid Y');
		$this->assertEquals($b->getWidth(), 0, 'Test 1 : invalid Width');
		$this->assertEquals($b->getHeight(), 0, 'Test 1 : invalid Height');

		$b->setX(-10);
		$b->setY(-4);
		$b->setWidth(15);
		$b->setHeight(30);
		$this->assertEquals($b->getX(), -10, 'Test 2 : invalid X');
		$this->assertEquals($b->getY(), -4, 'Test 2 : invalid Y');
		$this->assertEquals($b->getWidth(), 15, 'Test 2 : invalid Width');
		$this->assertEquals($b->getHeight(), 30, 'Test 2 : invalid Height');
		
		$p = new DPoint(0, 0);
		$this->assertTrue($b->contains($p), 'Test 3 : contains()');

		$p = new DPoint(-15, -4);
		$this->assertFalse($b->contains($p), 'Test 3 : !contains()');

		$p = new DPoint(6, 0);
		$this->assertFalse($b->contains($p), 'Test 4 : contains()');

		$p = new DPoint(0, 27);
		$this->assertFalse($b->contains($p), 'Test 4 : !contains()');

		$b->extend(new DPoint(10, 30));
		$b->extend(new DPoint(-15, -6));
		$this->assertEquals($b->getX(), -15, 'Test 5 : invalid X');
		$this->assertEquals($b->getY(), -6, 'Test 5 : invalid Y');
		$this->assertEquals($b->getWidth(), 25, 'Test 5 : invalid Width (%1 != %2)');
		$this->assertEquals($b->getHeight(), 36, 'Test 5 : invalid Height (%1 != %2)');

		$b = new DBounds($b);
		$this->assertEquals($b->getX(), -15, 'Test 6 : invalid X after copy');
		$this->assertEquals($b->getY(), -6, 'Test 6 : invalid Y after copy');
		$this->assertEquals($b->getWidth(), 25, 'Test 6 : invalid Width after copy');
		$this->assertEquals($b->getHeight(), 36, 'Test 6 : invalid Height after copy');

		$b->setX(0);
		$b->setY(0);
		$b->setWidth(0);
		$b->setHeight(0);
		$this->assertEquals($b->getX(), 0, 'Test 7 : invalid X');
		$this->assertEquals($b->getY(), 0, 'Test 7 : invalid Y');
		$this->assertEquals($b->getWidth(), 0, 'Test 7 : invalid Width');
		$this->assertEquals($b->getHeight(), 0, 'Test 7 : invalid Height');

		$b->extend(new DPoint(5, 5));
		$this->assertEquals($b->getX(), 0, 'Test 8 : invalid X');
		$this->assertEquals($b->getY(), 0, 'Test 8 : invalid Y');
		$this->assertEquals($b->getWidth(), 5, 'Test 8 : invalid Width');
		$this->assertEquals($b->getHeight(), 5, 'Test 8 : invalid Height');

		$b->setX(0);
		$b->setY(0);
		$b->setWidth(0);
		$b->setHeight(0);
		$b->extend(new DPoint(-10, -10));
		$b->extend(new DPoint(10, 10));
		$this->assertEquals($b->getX(), -10, 'Test 9 : invalid X');
		$this->assertEquals($b->getY(), -10, 'Test 9 : invalid Y');
		$this->assertEquals($b->getWidth(), 20, 'Test 9 : invalid Width');
		$this->assertEquals($b->getHeight(), 20, 'Test 9 : invalid Height');
		
		$b->absolute();
		$this->assertEquals($b->getX(), 10, 'Test 10 : invalid X');
		$this->assertEquals($b->getY(), 10, 'Test 10 : invalid Y');
		$this->assertEquals($b->getWidth(), 20, 'Test 10 : invalid Width');
		$this->assertEquals($b->getHeight(), 20, 'Test 10 : invalid Height');
		
		$b->scale(0.5);
		$this->assertEquals($b->getX(), 5, 'Test 11 : invalid X');
		$this->assertEquals($b->getY(), 5, 'Test 11 : invalid Y');
		$this->assertEquals($b->getWidth(), 10, 'Test 11 : invalid Width');
		$this->assertEquals($b->getHeight(), 10, 'Test 11 : invalid Height');

		$b->setX(-4);
		$b->setY(-8);
		$b->setWidth(8);
		$b->setHeight(16);
		$this->assertTrue($b->getCenter()->equals(new DPoint(0, 0)), 'Test 12 : getCenter');

		$this->onSuccess();
	}

}

class TestDPoint extends Test {
	
	public function __construct() {
		parent::Test('DPoint');
	}
	
	public function run() {
		$v = new DPoint();
		$this->assertEquals($v->getX(), 0, 'Test 1 : invalid X');
		$this->assertEquals($v->getY(), 0, 'Test 1 : invalid Y');
		$v->setX(50);
		$v->setY(-1);
		$this->assertEquals($v->getX(), 50, 'Test 2 : invalid X');
		$this->assertEquals($v->getY(), -1, 'Test 2 : invalid Y');
		$v = new DPoint(60, 180);
		$this->assertEquals($v->getX(), 60, 'Test 3 : invalid X');
		$this->assertEquals($v->getY(), 180, 'Test 3 : invalid Y');
		$v = new DPoint(3.5, 6.9);
		$this->assertEquals($v->getX(), 3, 'Test 4 : invalid X');
		$this->assertEquals($v->getY(), 6, 'Test 4 : invalid Y');
		$v->addX(2);
		$v->addY(-6);
		$this->assertEquals($v->getX(), 5, 'Test 5 : invalid X');
		$this->assertEquals($v->getY(), 0, 'Test 5 : invalid Y');
		$v->addX(2.7541);
		$v->addY(-3.156);
		$this->assertEquals($v->getX(), 7, 'Test 6 : invalid X');
		$this->assertEquals($v->getY(), -3, 'Test 6 : invalid Y');
		$v->negate();
		$this->assertEquals($v->getX(), -7, 'Test 7 : invalid X');
		$this->assertEquals($v->getY(), 3, 'Test 7 : invalid Y');
		$v->scale(1.5);
		$this->assertEquals($v->getX(), -10, 'Test 8 : invalid X');
		$this->assertEquals($v->getY(), 4, 'Test 8 : invalid Y');
		
		$this->assertTrue($v->equals(new DPoint(-10, 4)), 'Test 9 : equals');
		$this->assertTrue($v->epsilonEquals(new DPoint(-11, 6), 2), 'Test 10 : equalsEpsilon');
		$this->assertFalse($v->epsilonEquals(new DPoint(-11, 6), 1), 'Test 11 : equalsEpsilon');
		
		$this->assertEquals($v->distance($v), 0, 'Test 12 : distance : %1 != %2');
		
		$v->set(8, 0);
		$this->assertEquals($v->getX(), 8, 'Test 13 : invalid X');
		$this->assertEquals($v->getY(), 0, 'Test 13 : invalid Y');
		$this->assertEquals($v->distance(new DPoint(0, 0)), 8, 'Test 14 : distance : %1 != %2');
		$v->setY(8);
		$this->assertEquals($v->distance(new DPoint(0, 0)), 11, 'Test 15 : distance : %1 != %2');
		
		$v->set(-10, -15);
		$v->absolute();
		$this->assertEquals($v->getX(), 10, 'Test 16 : invalid X');
		$this->assertEquals($v->getY(), 15, 'Test 16 : invalid Y');
		
		$v->scale(-1);
		$this->assertEquals($v->getX(), -10, 'Test 17 : invalid X');
		$this->assertEquals($v->getY(), -15, 'Test 17 : invalid Y');

		$v->scale(-0.5);
		$this->assertEquals($v->getX(), 5, 'Test 18 : invalid X');
		$this->assertEquals($v->getY(), 7, 'Test 18 : invalid Y');
		
		$this->onSuccess();
	}
}

class TestDLatLng extends Test {

	public function __construct() {
		parent::Test('DLatLng');
	}
	
	public function run() {
		$p = new DLatLng();
		$this->assertEquals($p->getLat(), .0, 'Test 1 - getLat() [%1 != %2]');
		$this->assertEquals($p->getLng(), .0, 'Test 1 - getLng() [%1 != %2]');
		$this->assertEquals("$p", '0,0', 'Test 1 - toString() [%1 != %2]');

		$p->setLat(50.085344);
		$p->setLng(-5.645599);
		$this->assertEquals($p->getLat(), 50.085344, 'Test 2 - getLat() [%1 != %2]');
		$this->assertEquals($p->getLng(), -5.645599, 'Test 2 - getLng() [%1 != %2]');
		$this->assertEquals("$p", '50.085344,-5.645599', 'Test 2 - toString() [%1 != %2]');
		$this->assertEquals($p->toDegree(), '50°5\'7.24"N,5°38\'44.16"W', 'Test 2 - toDegree() [%1 != %2]');

		$p->setLat(0);
		$p->setLng(0);
		$this->assertEquals($p->getLat(), .0, 'Test 3 - getLat() [%1 != %2]');
		$this->assertEquals($p->getLng(), .0, 'Test 3 - getLng() [%1 != %2]');
		$this->assertEquals("$p", '0,0', 'Test 3 - toString() [%1 != %2]');
		$this->assertEquals($p->toDegree(), '°0\'0"N,°0\'0"E', 'Test 3 - toDegree() [%1 != %2]');
		$this->assertTrue($p->equals(new DLatLng(0, 0)), 'Test 4 - equals()');

		$p->setLat(1.085344);
		$p->setLng(1.645599);
		$this->assertTrue($p->equals(new DLatLng(1.085344, 1.645599)), 'Test 5 - equals()');
		$this->assertTrue($p->equals(new DLatLng($p)), 'Test 6 - equals()');

		$p2 = new DLatLng('1°5\'7.24"N,1°38\'44.16"E');
		$this->assertEquals($p->toDegree(), $p2->toDegree(), 'Test 7 - equals() + construct [%1 != %2]');
		$this->assertTrue(
			$p2->equals(new DLatLng('+1°5\'7.24", +1°38\'44.16"')),
			'Test 8 - equals() + construct with both formats');

		$p->setLatLngDegree('50°5\'7.24"S,5°38\'44.16"E');
		$p2->setLatLngDegree('-50°5\'7.24", +5°38\'44.16"');
		$this->assertEquals($p->getLat(), $p2->getLat(), 'Test 10 - getLat() [%1 != %2]');
		$this->assertEquals($p->getLng(), $p2->getLng(), 'Test 10 - getLng() [%1 != %2]');

		$p->setLatLngDegree('50°5\'7.24"N,5°38\'44.16"W');
		$p2->setLatLngDegree('+50°5\'7.24", -5°38\'44.16"');
		$this->assertEquals($p->getLat(), $p2->getLat(), 'Test 11 - getLat() [%1 != %2]');
		$this->assertEquals($p->getLng(), $p2->getLng(), 'Test 11 - getLng() [%1 != %2]');

		$this->onSuccess();
	}

}

?><html>
<head>
 <title>GDO : Tests : Util</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Util</a>
</div>

<div id="content">

<?php

$test = new TestDPoint();
$test->start();

$test = new TestDBounds();
$test->start();

$test = new TestDLatLng();
$test->start();

?>

<h2>Enum</h2>
<?php
echo '<p>Enum MyEnum exists ? '.(class_exists('MyEnum') ? 'OUI' : 'NON').'</p>';
echo '<p>Creating enum...</p>';
PHPHelper::createEnum(
	'MyEnum',
	array('CHIEN', 'CHAT', 'OISEAU', 'POUSSIN')
);
echo '<p>Enum MyEnum exists ? '.(class_exists('MyEnum') ? 'OUI' : 'NON').'</p>';
echo '<p>Test value OISEAU : '.MyEnum::$OISEAU.'</p>';
echo '<p>Test value CHIEN : '.MyEnum::valueOf('CHIEN').'</p>';
?>
<h2>Test if a var is null</h2>
<?php

function test_var($var) {
	$level = error_reporting();
	error_reporting(E_ALL);
	ob_start();
	if ($var) { }
	$warn = ob_get_contents();
	error_reporting($level);
	ob_end_clean();
	return array(
		'isset' => isset($var),
		'toString' => @"$var",
		'self-test' => @($var),
		'large test' => @$var == NULL,
		'strict test' => @$var === NULL,
		'is_null' => @is_null($var),
		'gettype' => @gettype($var),
		'in defined_vars' => array_key_exists('var', get_defined_vars()),
		'warn' => !empty($warn)
	);
}

echo '<p>UNDEFINED</p>';
@print_r(test_var());
echo '<p>NULL</p>';
$a = NULL;
@print_r(test_var($a));
unset($a);
echo '<p>UNSET</p>';
@print_r(test_var($a));

?>
</div>

</body>
</html>