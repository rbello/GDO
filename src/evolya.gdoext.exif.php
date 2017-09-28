<?php

/**
 * TODO
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdoext.exif
 */
class DExifImageInfo {

	public function __construct($infos) {
		$infos = @PHPHelper::checkArgument('$infos', $infos, 'array');
	}

	public static function getImageInfo(DFile $file) {
		if (!function_exists('exif_read_data')) {
			throw new UnsupportedOperationException('exif extension not loaded');
		}
		if (!isset($file)) {
			throw new MissingArgumentException('$file', 'DFile');
		}
		$infos = @exif_read_data();
		if (!$infos) return NULL;
		return new DExifImageInfo($infos);
	}

}

?>