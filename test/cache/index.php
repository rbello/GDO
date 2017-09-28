<?php

require_once '../../src/evolya.gdo.php';
require_once '../../src/evolya.gdoext.animatedgif.php';
require '../test.php';

define('DO_NOT_RENDER', TRUE);

$jour = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
$mois = array('','Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre');

function format_date_fr($timestamp) {

	global $jour, $mois;

	$elapse = time() - $timestamp;
	
	if ($elapse < 60) {
		return 'Il y a '.$elapse.' secondes';
	}
	if ($elapse <= 59*60) {
		$m = round($elapse/60);
		return 'Il y a '.$m.' minute'.($m > 1 ? 's' : '');
	}
	if ($elapse <= 3*3600) {
		$h = round($elapse/3600);
		$m = round(($elapse - $h*3600)/60);
		if ($m < 1) {
			return 'Il y a '.$h.' heure'.($h > 1 ? 's' : '');
		} else {
			return 'Il y a '.$h.' heure'.($h > 1 ? 's' : '').' et '.$m.' minute'.($m > 1 ? 's' : '');
		}
	}
	if ($timestamp < time()) {
		$jourRef = date('j', time());
		$jourTim = date('j', $timestamp);
		
		$dif = $jourRef - $jourTim;
		
		if ($dif == 0) {
			return 'Aujourd\'hui à '.date('H:i', $timestamp);
		}
		else if ($dif == 1) {
			return 'Hier à '.date('H:i', $timestamp);
		}
		else if ($dif < 7) {
			if (intval(date('w', $timestamp)) < intval(date('w', time()))) {
				return $jour[date('w', $timestamp)].' à '.date('H:i', $timestamp);
			}
		}
	}
	
	return $jour[date('w', $timestamp)].' '.date('d', $timestamp).' '.$mois[date('n', $timestamp)].' '.date('Y', $timestamp)
			.' à '.date('H', $timestamp).':'.date('i', $timestamp);
}


?><html>
<head>
 <title>GDO : Tests : Class DCache</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class DCache</a>
</div>

<div id="content">

<h2>Test INODE</h2>
<?php

$file = 'test.ini';

echo '<p>'.$file.'</p>';
echo '<p>Last inode : '.format_date_fr(filectime($file)).'</p>';
echo '<p>Last modified : '.format_date_fr(filemtime($file)).'</p>';
echo '<p>Last access : '.format_date_fr(fileatime($file)).'</p>';

echo '<p>Get Contents</p>';
$fp = file_get_contents($file);

echo '<p>Last inode : '.format_date_fr(filectime($file)).'</p>';
echo '<p>Last modified : '.format_date_fr(filemtime($file)).'</p>';
echo '<p>Last access : '.format_date_fr(fileatime($file)).'</p>';

echo '<p>Put Contents</p>';
//file_put_contents($file, $fp);

echo '<p>Last inode : '.format_date_fr(filectime($file)).'</p>';
echo '<p>Last modified : '.format_date_fr(filemtime($file)).'</p>';
echo '<p>Last access : '.format_date_fr(fileatime($file)).'</p>';

?>
<h2>DCache</h2>
<?php

$cache = new DCache('cache');

$img = new DImage('../images/animated.gif');

$cache->inputResource(
	'Animated image with default output config',
	$img
);

$cache->inputResource(
	'Animated image with GIF output config',
	$img,
	new DGIF()
);

if ($src = $cache->getStoredResourceById('Animated image with default output config')) {
	echo '<p>Animated image with default output config : <img src="cache/'.$src->getName().'" /></p>';
}
else {
	echo '<p>Resource not found : Animated image with default output config</p>';
}

if ($src = $cache->getStoredResourceById('Animated image with GIF output config')) {
	echo '<p>Animated image with GIF output config : <img src="cache/'.$src->getName().'" /></p>';
}
else {
	echo '<p>Resource not found : Animated image with GIF output config</p>';
}

echo '<p>Image chargée depuis un script PHP : <img src="cache.loader.php" /></p>';

?>
</div>

</body>
</html>