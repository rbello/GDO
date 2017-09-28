<?php

require_once '../../src-gdo/gdo.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

?><html>
<head>
 <title>GDO : Tests : Overflow test</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Overflow test</a>
</div>

<div id="content">

<h1>Overflow test</h1>

<?php

$nbr = 10;

$filters = array(
	'negative',
	'grayscale',
	'brightness1',
	'brightness0',
	'contrast1',
	'contrast0'
);

echo '<p style="width:650px;margin:0 auto">';
while ($nbr > 0) {
	echo '<img src="image.php?f='.$filters[rand(0, 5)].'&t='.time().'" />';
	$nbr--;
}
echo '</p>';

?>

</div>

</body>
</html>