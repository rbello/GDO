<?php

require '../test.php';

require '../../src/evolya.gdo.php';
require '../../src/evolya.gdoext.animatedgif.php';

define('DO_NOT_RENDER', TRUE);

?>
<html>
<head>
 <title>GDO : Tests : GIF Output : Animated GIF</title>
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
: <a href="animated.php">Animated GIF</a>
</div>

<div id="content">
<?php

class AnimatedTest {
	public function __construct($file, $img) {

		echo '<h2 style="background:#ddd"><img src="'.$img.'" /> '.basename($file).'</h2>';
	
		$contents = file_get_contents($file);

		$gifDecoder = new DGIFDecoder($contents);
		
		echo '<li>Counted frame(s) : '.$gifDecoder->getFrameCount().'</li>';
		echo '<li>Recorded frame(s) : '.sizeof($gifDecoder->getFrames()).'</li>';
		echo '<li>Transparent color : '.$gifDecoder->getTransparentColor().'</li>';
		echo '<li>Loop value : ';
		var_dump($gifDecoder->getLoops());
		echo '</li>';
		echo '<li>Frames : ';
		foreach ($gifDecoder->getFrames() as $frame) {
			echo " $frame";
		}
		echo '</li>';
	}
}

new AnimatedTest('../images/animated.gif', 'test.animated.php');
new AnimatedTest('../images/icon.gif', 'test.default.php');
new AnimatedTest('../images/icon3.gif', 'test.withoutalpha.php');

?>
</div>
</body>
</html>