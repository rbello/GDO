<?php

require_once '../../src-gdo/gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

?><html>
<head>
 <title>GDO : Tests : Class _ImageStack_ : tests for urbex : icon generator</title>
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
: <a href="index2.php">tests for urbex</a>
: <a href="index3.php">icon generator</a>
</div>

<div id="content">
<?php

$styles = array(
	'autrecoinphoto',
	'autre',
	'chapel',
	'chateau',
	'chercherinfo',
	'cimetiere',
	'closed',
	'default',
	'ferme',
	'gardien',
	'maison',
	'moulin',
	'parking',
	'ruine',
	'usine',
	'mine',
	'medical',
	'militaire',
	'architecture',
	'multiple'
);

$save = @$_GET['save'] == 'true';
$save = $save ? 'true' : 'false';

$wayto = '../../../urbex/images/styles/';

foreach ($styles as $style) {
	echo '<p>'.$style.' :';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&rate=3&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&rate=4&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&rate=5&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&done=true&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&done=true&rate=3&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&done=true&rate=4&save='.$save.'" />';
	echo ' <img src="urbex-marker-gen.php?icon='.urlencode($wayto.$style.'.png').'&style='.$style.'&done=true&rate=5&save='.$save.'" />';
	echo '</p>';
}

?>
<p><a href="index3.php?save=true">SAVE</a></p>
</div>

</body>
</html>