<?php

$doNotRender = TRUE;

?><html>
<head>
 <title>GDO : Tests : Image Render&amp;Resize</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 pre { border: 1px dotted gray; background: #eee; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="index.php">Tests</a>
: <a href="test.image-resize-render.php">Image Render&amp;Resize</a>
</div>

<div id="content">

<h1>Test GDO : Image Render&amp;Resize</h1>

<h2>Simple</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-simple.php'));

?></pre>
<h3>Result : <div style="width:100px;height:100px;padding:5px;border:4px solid #ccc;background: red url(exec.image-simple.php) 0 0 no-repeat;">Test de texte qui passe au dessus</div></h3>
<?php
include 'exec.image-simple.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Resize and render</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-resize-render.php'));

?></pre>
<h3>Result : <img src="exec.image-resize-render.php" /></h3>
<?php
include 'exec.image-resize-render.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Crop</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-crop.php'));

?></pre>
<h3>Result : <img src="exec.image-crop.php" /></h3>
<?php
include 'exec.image-crop.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Multiples crop</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-crop-multiple.php'));

?></pre>
<h3>Result : <img src="exec.image-crop-multiple.php" /></h3>
<?php
include 'exec.image-crop-multiple.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Ratio</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-ratio.php'));

?></pre>
<h3>Result : <img src="exec.image-ratio.php" /></h3>
<?php
include 'exec.image-ratio.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Ratio + Crop</h2>

<h2>Crop + Ratio</h2>

<h2>Crop + setDimension</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-crop-dimension.php'));

?></pre>
<h3>Result : <img src="exec.image-crop-dimension.php" /></h3>
<?php
include 'exec.image-crop-dimension.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Crop + setDimension to original dimensions</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-crop-dimension-original.php'));

?></pre>
<h3>Result : <img src="exec.image-crop-dimension-original.php" /></h3>
<?php
include 'exec.image-crop-dimension-original.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>

<h2>Original</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-original.php'));

?></pre>
<h3>Result : <img src="exec.image-original.php" /></h3>
<?php
include 'exec.image-original.php';
?>
<p>toString : <?php echo $img; ?></p>
<p>Infos : <?php echo $img->getInfos(); ?></p>
<?php $img->destroy(); ?>


<h2>Transparence</h2>
<pre><?php

echo htmlentities(file_get_contents('exec.image-transparence.php'));

?></pre>
<h3>Result : <div style="background: #ccc; display: inline; padding: 10px">
<img src="exec.image-transparence.php?get=1" /> <img src="exec.image-transparence.php?get=2" /></div></h3>

</div>
</body>
</html>