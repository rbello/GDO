<html>
<head>
 <title>GDO : Tests : Memory</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Memory</a>
</div>

<div id="content">

<h1>Test GDO : Memory</h1>
<?php

require_once '../../src/evolya.gdo.php';

require_once '../test.php';

echo '<h2>Test utility function DMemoryHelper::return_bytes()</h2>';
echo '<p>1 = '.DMemoryHelper::return_bytes('1') . ' = 1 o</p>';
echo '<p>1O = '.DMemoryHelper::return_bytes('1O') . ' = 1 o</p>';
echo '<p>1K = '.DMemoryHelper::return_bytes('1K') . ' = 1024 o</p>';
echo '<p>2K = '.DMemoryHelper::return_bytes('2K') . ' = 2048 o</p>';
echo '<p>1M = '.DMemoryHelper::return_bytes('1M') . ' = '.(1024 * 1024).' o</p>';
echo '<p>2M = '.DMemoryHelper::return_bytes('2M') . ' = '.(2 * 1024 * 1024).' o</p>';
echo '<p>8M = '.DMemoryHelper::return_bytes('8M') . ' = '.(8 * 1024 * 1024).' o</p>';
echo '<p>128M = '.DMemoryHelper::return_bytes('128M') . ' = '.(128 * 1024 * 1024).' o</p>';
echo '<p>1G = '.DMemoryHelper::return_bytes('1G') . ' = '.(1024 * 1024 * 1024).' o</p>';
echo '<p>2G = '.DMemoryHelper::return_bytes('2G') . ' = '.(2 * 1024 * 1024 * 1024).' o</p>';

echo '<h2>Current memory informations</h2>';
echo '<p>Total available memory for this script : '.DMemoryHelper::getTotalAvailableMemory().' ('
	.DMemoryHelper::getTotalAvailableMemoryVerbose().')</p>';
echo '<p>Current available memory for this script : '.DMemoryHelper::getAvailableMemory().' ('
	.DMemoryHelper::getAvailableMemoryVerbose().')</p>';


echo '<p>getMemoryUsage : '.DMemoryHelper::getMemoryUsage().' ('.DMemoryHelper::return_string(DMemoryHelper::getMemoryUsage()).')</p>';
echo '<p>getRealMemoryUsage : '.DMemoryHelper::getRealMemoryUsage().' ('.DMemoryHelper::return_string(DMemoryHelper::getRealMemoryUsage()).')</p>';
echo '<p>getPeakMemoryUsage : '.DMemoryHelper::getPeakMemoryUsage().' ('.DMemoryHelper::return_string(DMemoryHelper::getPeakMemoryUsage()).')</p>';
echo '<p>getRealPeakMemoryUsage : '.DMemoryHelper::getRealPeakMemoryUsage().' ('.DMemoryHelper::return_string(DMemoryHelper::getRealPeakMemoryUsage()).')</p>';

echo '<h2>Shutdown auto-dispose resources</h2>';

$canvas = new DResource(5, 5);

?>


<h4>Update at <?php echo time(); ?></h4>

</div>
</body>
</html>