<?php

require_once '../../src/evolya.gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);


?><html>
<head>
 <title>GDO : Tests : Class DImageFilter : On class DImage</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class DImageFilter</a>
: <a href="index-image.php">On class DImage</a>
</div>

<div id="content">

	<h2>Original dimensions</h2>

	<p>Original : <img src="image.php" /></p>

	<p>Filtre DNegativeFilter : <img src="image.php?f=negative" /></p>

	<p>Filtre DGrayscaleFilter : <img src="image.php?f=grayscale" /></p>

	<p>Filtre DBrightnessFilter + : <img src="image.php?f=brightness1" /></p>

	<p>Filtre DBrightnessFilter - : <img src="image.php?f=brightness0" /></p>

	<p>Filtre DContrastFilter + : <img src="image.php?f=contrast1" /></p>

	<p>Filtre DContrastFilter - : <img src="image.php?f=contrast0" /></p>

	<p>Filtre DColorizeFilter red : <img src="image.php?f=colorred" /></p>

	<p>Filtre DEdgeDetectFilter : <img src="image.php?f=edge" /></p>

	<p>Filtre DEmbossFilter : <img src="image.php?f=emboss" /></p>

	<p>Filtre DGaussianBlurFilter : <img src="image.php?f=gaussian" /></p>

	<p>Filtre DSelectiveBlurFilter : <img src="image.php?f=selectiveblur" /></p>

	<p>Filtre DMeanRemovalFilter : <img src="image.php?f=meanremoval" /></p>

	<p>Filtre DSmoothFilter 0 : <img src="image.php?f=smooth0" /></p>

	<p>Filtre DSmoothFilter 6 : <img src="image.php?f=smooth6" /></p>

	<p>Filtre DSmoothFilter 10 : <img src="image.php?f=smooth10" /></p>

	<p>Filtre DSmoothFilter -5 : <img src="image.php?f=smooth5" /></p>
	
	<h2>No-GD filters</h2>

	<p>Filtre DSepiaFilter : <img src="image.php?f=sepia" /></p>

	<p>Filtre DSpherizeFilter : <img src="image.php?f=sphere" /></p>
	
	<p>Filtre DTwirlFilter : <img src="image.php?f=twirl" /></p>

	<p>Filtre DChannelMixFilter : <img src="image.php?f=mix1" /></p>

	<p>Filtre DHSBAdjustFilter : <img src="image.php?f=hsb" /></p>

	<p>Filtre DPixelateFilter (default) : <img src="image.php?f=pixelate1" /></p>

	<p>Filtre DConvolutionFilter sample 1 : <img src="image.php?f=convolution1" /></p>

	<p>Filtre DConvolutionFilter sample 2 : <img src="image.php?f=convolution2" /></p>

	<p>Filtre DConvolutionFilter sample 3 : <img src="image.php?f=convolution3" /></p>

	<h2>Resized</h2>

	<p>Filtre DNegativeFilter : <img src="image.php?f=negative-resized" /></p>

	<p>Filtre DGrayscaleFilter : <img src="image.php?f=grayscale-resized" /></p>

</div>

</body>
</html>