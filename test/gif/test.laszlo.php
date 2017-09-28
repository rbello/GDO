<?php

include 'gifdecoder.php';

$gifDecoder = new GIFDecoder(file_get_contents('../images/animated.gif')); 

include 'gifencoder.php';

$gifEncoder = new GIFEncoder(
	$gifDecoder->GIFGetFrames(),
	$gifDecoder->GIFGetDelays(),
	$gifDecoder->GIFGetLoop(),
	$gifDecoder->GIFGetDisposal(),
	$gifDecoder->GIFGetTransparentR(),
	$gifDecoder->GIFGetTransparentG(),
	$gifDecoder->GIFGetTransparentB(),
	'bin'
);

header('Content-type: image/gif');

echo $gifEncoder->GetAnimation();

?>