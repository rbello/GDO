<?php

define('GDO_DEBUG', TRUE);

if (GDO_DEBUG == TRUE) {
	error_reporting(E_ALL);
}

if (!extension_loaded('gd')) {
	trigger_error('GD extension not loaded', E_USER_ERROR);
	exit(-1);
}
if (!function_exists('gd_info')) {
	trigger_error('Invalid GD extension version', E_USER_ERROR);
	exit(-2);
}

@ignore_user_abort(TRUE);

require_once 'evolya.common.exceptions.php';
require_once 'evolya.common.interfaces.php';
require_once 'evolya.common.point.php';
require_once 'evolya.common.rectangle.php';
require_once 'evolya.common.bounds.php';
require_once 'evolya.common.latlng.php';
require_once 'evolya.common.util.php';
require_once 'evolya.common.file.php';

require_once 'evolya.gdo.exceptions.php';
require_once 'evolya.gdo.memory.php';
require_once 'evolya.gdo.color.php';
require_once 'evolya.gdo.imageinfo.php';
require_once 'evolya.gdo.output.php';
require_once 'evolya.gdo.imagefilter.php';

require_once 'evolya.gdo.image.frame.php';
require_once 'evolya.gdo.drawing.php';
require_once 'evolya.gdo.image.interface.php';
require_once 'evolya.gdo.image.php';

require_once 'evolya.gdo.font.php';
require_once 'evolya.gdo.label.php';
require_once 'evolya.gdo.imagestack.php';
require_once 'evolya.gdo.layer.php';
require_once 'evolya.gdo.cache.php';
require_once 'evolya.gdo.drawing.php';

require_once 'evolya.gdoext.animatedgif.php';

@register_shutdown_function('gdo_dispose_all_resources');

?>