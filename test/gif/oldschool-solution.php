<?php

require '../test.php';
require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';

define('DO_NOT_RENDER', TRUE);


?>
<html>
<head>
 <title>GDO : Tests : GIF Output : László Zsidi's solution</title>
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
: <a href="oldschool-solution.php">László Zsidi's solution</a>
</div>
<div id="content">

<h2>Animated GIF with László Zsidi's solution</h2>
<img src="test.laszlo.php" />

<?php

include 'gifdecoder.php';

class TestGIFDecoder extends Test {
	public function TestGIFDecoder() {
		parent::Test('GDO and László Zsidi\'s implementation TEST');
	}
	public function run() {
		$contents = file_get_contents('../images/animated.gif');
		$gifDecoder1 = new GIFDecoder($contents);
		$gifDecoder2 = new DGIFDecoder($contents, TRUE);
		
		$this->assertEquals($gifDecoder1->GIFGetTransparentR(), $gifDecoder2->getTransparentColor()->getRedValue(), 'Transparent color : red');
		$this->assertEquals($gifDecoder1->GIFGetTransparentG(), $gifDecoder2->getTransparentColor()->getGreenValue(), 'Transparent color : green');
		$this->assertEquals($gifDecoder1->GIFGetTransparentB(), $gifDecoder2->getTransparentColor()->getBlueValue(), 'Transparent color : blue');
		
		$this->assertEquals($gifDecoder1->GIFGetLoop(), $gifDecoder2->getLoops(), 'Loops value');
		
		$frames = $gifDecoder1->GIFGetFrames();
		$delays = $gifDecoder1->GIFGetDelays();
		$dispos = $gifDecoder1->GIFGetDisposal();

		for ($i = 0; $i < $gifDecoder2->getFrameCount(); $i++) {
			$fr = $gifDecoder2->getFrames();
			$fr = $fr[$i];
			
			$this->assertEquals($fr->getDelay(), $delays[$i], 'Delay '.$i.' &raquo; 1='.$delays[$i].' != 2='.$fr->getDelay());
			$this->assertEquals(md5($fr->getContents()), md5($frames[$i]), 'Contents '.$i.' &raquo; 1='.md5($frames[$i]).' != 2='.md5($fr->getContents()));
			$this->assertEquals($fr->getDisposalMethod(), $dispos[$i], 'Disposal '.$i.' &raquo; 1='.$dispos[$i].' != 2='.$fr->getDisposalMethod());
		}
		
		$this->onSuccess('Success');

	}
}

$test = new TestGIFDecoder();
$test->start();

?>

</div>
</body>
</html>