<?php

$gdo_protectedimage = array(
	'img_folder'		=> 'images/',
	'cache_folder'		=> 'cache/',
	'query_var'			=> 'getimg',
	'copyright_text'	=> 'Copyright mon truc',
	'jpeg_quality'		=> 90
);

function gdo_protectedimage_html($target, $description='') {

	$width = 0;
	$height = 0;
	$srcImg = ''; // this file with query var, or cache if mixed cached used
	$srcBlank = ''; // this file with special query var, or dedicated file ?

	$html  = '<div style="width:'.$width.'px;height:'.$height.'px;background: transparent url('.$srcImg.') 0 0 no-repeat;">';
	$html .= '<img src="'.$srcBlank.'" alt="'.htmlentities($description).'" />';
	$html .= '</div>';
	
	return $html;

}

function gdo_protectedimage_execute() {

	if (headers_sent()) return;

	global $gdo_query_var;
	
	// Show error message if the query is not valid
	if (!isset($_GET[$gdo_query_var])) {
		$error = new _Label_('Invalid query');
		$error->render();
		exit();
	}
	
	// Get the target file and clean the path
	$target = $_GET[$gdo_query_var];
	
	// Read cache
	global $gdo_cache_folder;
	$cache = new _MixedCache_($gdo_cache_folder);
	if ($image = $cache->ouput($target)) {
		$image->render();
		return;
	}
	
	// 
	global $gdo_img_folder;
	
	if (is_file("$gdo_img_folder/$target")) {
	
		// Create an assembly image
		$assembly = new _Assembly_();
		
		// Load the target image and add it to the assembly image
		$image = new _Image_($target);
		$assembly->addLayer($image);
		
		// Add a copyright message to the assembly image
		global $gdo_copyright;
		$label = new _Label_($gdo_copyright);
		$assembly->addLayer(
			new _Layer_($label),
			_RelativePosition_::RIGHT & _RelativePosition_::BOTTOM
		);
		
		// Make assembly and get the final picture
		$result = $assembly->toResource();
		
		// Add an invisible watermark
		$result->addSignature($gdo_copyright);
		
		// Free temporary images
		$image->dispose();
		$image = NULL;
		$label->dispose();
		$label = NULL;
		
		// Save in cache with low quality
		$cache->input($result, $target, '.jpeg', 'jpeg', 90);
		
		// Show the final picture
		$target->render('jpeg', 90);
		
		// Destroy the final picture
		$target->dispose();
		
	}
	
	// If the target file is not found, show an error label
	else {
		$error = new _Label_('File not found');
		$error->render();
		return;
	}

}

register_shutdown_function('gdo_protectedimage_execute');

?>