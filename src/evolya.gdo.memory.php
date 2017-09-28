<?php

/**
 * Classe utilitaire qui apporte des fonctionnalités pour gérer l'utilisation de la mémoire
 * par GDO.
 * <br>Cette classe apporte des méthodes pour tenter d'évaluer la demande en mémoire de certaines
 * opérations, afin de lever des exceptions au lieu de provoquer des erreurs fatales qui stopent
 * l'execution du code.
 * <br>De plus, toutes les ressources qui sont crées s'enregistrent auprès de cette classe, afin
 * de pouvoir libérer la mémoire à la fin du script.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.memory
 */
final class DMemoryManager {

	/**
	 * Contient toutes les ressources crées.
	 * @var array&lt;DResourceInterface&gt;
	 */
	private static $resources = array();

	/**
	 * Renvoi un tableau contenant toutes les resources gérées par le DMemoryManager.
	 * 
	 * @return array&lt;DImageInterface&gt;
	 */
	public static function getAllResources() {
		return array_values(self::$resources);
	}

	/**
	 * Enregistre la ressource $image.
	 * 
	 * @param DImageInterface $resource
	 * @return void
	 */
	public static function registerResource(DImageInterface $image) {
		self::$resources[] = $image;
	}

	/**
	 * Dé-enregistre la ressource $image.
	 * 
	 * @param DImageInterface $resource
	 * @return void
	 */
	public static function unregisterResource(DImageInterface $image) {
		$key = array_search($image, self::$resources);
		if ($key !== FALSE) {
			unset(self::$resources[$key]);
		}
	}

	/**
	 * Détruit toutes les ressources crées par GDO. Cette méthode est appelée
	 * à la fin de l'execution du code afin de libérer la mémoire.
	 * 
	 * @return void
	 */
	public static function disposeAllResources() {
		$tmp = array_values(self::$resources);
		self::$resources = array();
		foreach ($tmp as $res) {
			$res->destroy();
		}
	}

	/**
	 * Convertir une taille du format texte en entier.
	 * <br>Le format est celui utilisé dans le fichier php.ini pour spécifier
	 * la mémoire allouée au script.
	 * <br>Renvoi la taille en octets.
	 * 
	 * @param string $val La valeur.
	 * @return int
	 */
	public static function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
			case 'o': $val *= 1;
		}
		return $val;
	}

	/**
	 * Afficher une taille en octets de manière lisible.
	 * 
	 * @param int $bytes La taille en octets.
	 * @param int $precision La précision.
	 * @return string
	 */
	public static function return_string($bytes, $precision=2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . ' ' . $units[$pow];
	} 

	/**
	 * Renvoi la quantité de mémoire en octets allouée au script
	 * dans le fichier php.ini (propriété <code>memory_limit</code>).
	 * 
	 * @return int
	 */
	public static function getTotalAvailableMemory() {
		return self::return_bytes(
			DMemoryManager::getTotalAvailableMemoryVerbose()
		);
	}

	/**
	 * Renvoi la quantité de mémoire allouée au script dans le fichier
	 * php.ini (propriété <code>memory_limit</code>).
	 * 
	 * @return string
	 */
	public static function getTotalAvailableMemoryVerbose() {
		$v = @ini_get('memory_limit');
		if (empty($v)) return '0M';
		return $v;
	}

	/**
	 * Renvoi la quantité de mémoire disponible au moment de l'appel à la méthode,
	 * en octets.
	 * 
	 * @return int
	 */
	public static function getAvailableMemory() {
		return self::getTotalAvailableMemory() - self::getRealMemoryUsage();
	}

	/**
	 * Renvoi la quantité de mémoire disponible au moment de l'appel à la méthode,
	 * sous forme d'une string lisible.
	 * 
	 * @return string
	 */
	public static function getAvailableMemoryVerbose() {
		return self::return_string(DMemoryManager::getAvailableMemory());
	}

	/**
	 * Renvoi la quantité de mémoire utilisée au moment de l'appel à la méthode.
	 * <br>Renvoi -1 s'il est impossible d'obtenir la quantité de mémoire.
	 * 
	 * @return int
	 */
	public static function getMemoryUsage() {
		if (function_exists('memory_get_usage')) {
			return memory_get_usage(FALSE);
		} else {
			return -1;
		}
	}

	/**
	 * Renvoi la taille réelle de la mémoire allouée par le système.
	 * <br>Renvoi -1 s'il est impossible d'obtenir la quantité de mémoire.
	 * 
	 * @return int
	 */
	public static function getRealMemoryUsage() {
		if (function_exists('memory_get_usage')) {
			return memory_get_usage(TRUE);
		} else {
			return -1;
		}
	}

	/**
	 * Renvoi la quantité maximale de mémoire qui a été utilisée.
	 * <br>Renvoi -1 s'il est impossible d'obtenir la quantité de mémoire.
	 * 
	 * @return int
	 */
	public static function getPeakMemoryUsage() {
		if (function_exists('memory_get_peak_usage')) {
			return memory_get_peak_usage(FALSE);
		} else {
			return -1;
		}
	}

	/**
	 * Renvoi la taille réelle de la mémoire utilisée par le système.
	 * <br>Renvoi -1 s'il est impossible d'obtenir la quantité de mémoire.
	 * 
	 * @return int
	 */
	public static function getRealPeakMemoryUsage() {
		if (function_exists('memory_get_peak_usage')) {
			return memory_get_peak_usage(TRUE);
		} else {
			return -1;
		}
	}

	/**
	 * Renvoi le nombre de pixels d'une image de dimensions
	 * $widthInch x $heightInch avec la résolution $dpi.
	 * <br>
	 * <br>1 inch = 2.54 centimeters
	 * <br>dpi = dots per inch = pixels par pouce.
	 * 
	 * @param int $widthInch Largeur de l'image en pouce.
	 * @param int $heightInch Hauteur de l'image en pouce.
	 * @param int $dpi Nombre de pixels au pouce carré.
	 * @return int
	 */
	public static function calculatePixelCount($widthInch, $heightInch, $dpi) {
		return ($widthInch * $dpi) * ($heightInch * $dpi);
	}

	/**
	 * Calculer la quantité de mémoire réquise pour fabriquer une image
	 * de dimensions $widthPx x $heightPx et de type $type.
	 * <br>Les types peuvent être :
	 * <pre>
	 *  1 bit Line art
	 *  8 bit Grayscale
	 *  16 bit Grayscale
	 *  24 bit RGB
	 *  32 bit CMYK
	 *  48 bit RGB
	 * </pre>
	 * <br>Par défaut, $type vaut <code>24 bit RGB</code>.
	 * 
	 * @return int
	 */
	public static function calculateImageMemory($widthPx, $heightPx, $type='24 bit RGB') {
		$factor = 0;
		switch ($type) {
			case '1 bit Line art' : $factor = 1/8; break;
			case '8 bit Grayscale' : $factor = 1; break;
			case '16 bit Grayscale' : $factor = 2; break;
			case '24 bit RGB' : $factor = 3; break;
			case '32 bit CMYK' : $factor = 4; break;
			case '48 bit RGB' : $factor = 6; break;
		}
		return $widthPx * $heightPx * $factor;
	}

	/**
	 * Evaluer la quantité de mémoire réquise pour créer une image true color de dimensions
	 * $widthPx x $heightPx. Le paramètre $channels permet de spécifier la création d'une
	 * couleur noir et blanc (1), bichromique (2) ou RVB (3).
	 * <br>Renvoi la taille mémoire en octets.
	 * 
	 * @param int $widthPx Hauteur en pixel.
	 * @param int $heightPx Largeur en pixel.
	 * @param int $channels Nombre de canaux.
	 * @param int $bits Nombre de bits.
	 * @param float $tweak Valeur de tweak.
	 * @return int
	 */
	public static function calculateImageMemoryCreateTrueColor($widthPx, $heightPx, $channels=3, $bits=8, $tweak=NULL) {
		if (!is_numeric($tweak)) {
			$pixels = $widthPx * $heightPx;
			if 		($pixels > 20000000)	$tweak = 1.8;
			else if	($pixels > 3240000)		$tweak = 1.7;
			else if	($pixels > 1860000)		$tweak = 1.6;
			else if	($pixels > 1600000)		$tweak = 1.5;
			else if	($pixels > 1200000)		$tweak = 1.4;
			else if	($pixels > 810000)		$tweak = 1.3;
			else if	($pixels > 560000)		$tweak = 1.2;
		}
		return $widthPx * $heightPx * ($bits/8) * $channels * $tweak;
	}

	/**
	 * Tente d'évaluer la quantitié de mémoire requise pour charger une image
	 * à partir d'un fichier.
	 * <br>Renvoi la taille mémoire en octets.
	 * 
	 * @param $widthPx Largeur de l'image.
	 * @param $heightPx Hauteur de l'image.
	 * @param $channels Nombre de canaux.
	 * @param $bits Nombre de bits.
	 * @param $tweak Valeur de tweak.
	 * @return int
	 */
	public static function calculateImageMemoryCreateFromFile($widthPx, $heightPx, $channels=4, $bits=8, $tweak=NULL) {
		if (!is_numeric($tweak)) {
			$tweak = 1;
		}
		
		// ATTENTION
		// La mémoire neccessaire pour charger une image est > à la place qu'elle prend en mémoire !!!
		// On peut compter presque 17 Mo pour une image de 10 Mo en mémoire
		
		// Toujours > et constante autours de 20~30%
		//return self::calculateImageMemoryCreateTrueColor($widthPx, $heightPx, $channels, $bits, NULL) * 0.85;
		
		// Mauvais et <
		//return $widthPx * $heightPx * $bits * $channels/ 8 + pow(2, 16) * 1.65;
		
		// Mauvais et toujours <
		//return $widthPx * $heightPx * ($bits / 8) * $channels * $tweak;
		
		// Mauvais mais toujours >
		return ($widthPx * $heightPx * $bits * $channels / 8 + 65536) * 2.2;
	}

	/**
	 * Renvoi TRUE si la création d'une image de dimensions $width x $height est possible.
	 * 
	 * @param $width Largeur de l'image à créer, en pixels.
	 * @param $height Hauteur de l'image à créer, en pixels.
	 * @return boolean
	 */
	public static function canCreateResource($width, $height) {
		return self::calculateImageMemoryCreateTrueColor($width, $height)
			< self::getAvailableMemory();
	}

	/**
	 * Renvoi TRUE si la création d'une image à partir d'un fichier est possible.
	 * 
	 * @param $width Largeur de l'image, en pixel.
	 * @param $height Hauteur de l'image, en pixel.
	 * @param $channels Nombre de canaux.
	 * @param $bits Nombre de bits.
	 * @return boolean
	 */
	public static function canCreateFromFile($width, $height, $channels=3, $bits=8) {
		return self::calculateImageMemoryCreateFromFile($width, $height, $channels, $bits)
			< self::getAvailableMemory();
	}

}

function gdo_dispose_all_resources() {
	DMemoryManager::disposeAllResources();
}

function gdo_register_resource(DImageInterface $image) {
	DMemoryManager::registerResource($image);
}

function gdo_unregister_resource(DImageInterface $image) {
	DMemoryManager::unregisterResource($image);
}

?>