<?php

/**
 * DLatLng est un point géographique ayant des coordonnées en longitude et latitude.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DLatLng {

	/**
	 * Symbol degrés
	 * @var char
	 */
	const symbolDeg = '°';

	/**
	 * Symbol minutes
	 * @var char
	 */
	const symbolMin = '\'';

	/**
	 * Symbol secondes
	 * @var char
	 */
	const symbolSec = '"';

	/**
	 * Latitude.
	 * @var float
	 */
	protected $lat = 0;

	/**
	 * Longitude.
	 * @var float
	 */
	protected $lng = 0;

	/**
	 * Constructeur de la classe DLatLng.
	 * 
	 * @construct DLatLng()
	 *  Construit un objet DLatLng aux coordonnées 0,0.
	 * @construct DLatLng(LatLng $pos)
	 *  Construit une copie de $pos.
	 * @construct DLatLng(int|float $lat, int|float $lng)
	 *  Construit un objet DLatLng aux coordonnées $lat,$lng.
	 * @construct DLatLng(string $dms)
	 *  Construit un objet DLatLng à partir d'une string.
	 * 
	 * @param DLatLng|int|float|string $arg0
	 * @param int|float $arg1
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws IllegalArgumentException Si le format de la coordonnée en string n'est pas valide.
	 */
	public function __construct($arg0=0, $arg1=0) {
		if (!isset($arg0)) {
			return;
		}
		if ($arg0 instanceof DLatLng) {
			$this->setLat($arg0->getLat());
			$this->setLng($arg0->getLng());
		}
		else if (is_string($arg0)) {
			$this->setLatLngDegree($arg0);
		}
		else {
			$this->setLat($arg0);
			$this->setLng($arg1);
		}
	}

	/**
	 * Modifier l'emplacement en latitude du point.
	 * 
	 * @param float $value Latitude au format décimal.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un flottant.
	 */
	public function setLat($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'float', TRUE, TRUE);
		$this->lat = $value;
	}

	/**
	 * Renvoi la coordonnée latitude du point en degrés, compris entre +90 et -90.
	 * 
	 * @return float
	 */
	public function getLat() {
		return $this->lat;
	}

	/**
	 * Renvoi la coordonnée latitude en radians, compris entre -PI/2 et +PI/2.
	 * 
	 * @return float
	 */
	public function getLatRad() {
		return deg2rad($this->lat);
	}

	/**
	 * Modifier l'emplacement en longitude du point.
	 * 
	 * @param float $value Longitude au format décimal.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un flottant.
	 */
	public function setLng($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'float', TRUE, TRUE);
		$this->lng = $value;
	}

	/**
	 * Renvoi la coordonndonée longitude du point en degrés, compris entre +90 et -90.
	 * 
	 * @return float
	 */
	public function getLng() {
		return $this->lng;
	}

	/**
	 * Renvoi la coordonnée longitude en radians, compris entre -PI/2 et +PI/2.
	 * 
	 * @return float
	 */
	public function getLngRad() {
		return deg2rad($this->lng);
	}

	/**
	 * Renvoi la distance en mètres, entre ce point et le point $p. Par défaut, le distance est
	 * calculée par rapport au rayon moyen de la Terre. Mais comme notre planète est approximativement
	 * une sphère, la distance peut être differente d'environs 0.3%, spécialement vers les pôles.
	 * Vous pouvez aussi passer un $radius pour calculer la distance sur des sphères autres que la Terre. 
	 * <br>
	 * <br>Rayon moyen de la terre : <code>6374892.5 m</code>
	 * <br>Rayon à l'équateur : <code>6378137 m</code>
	 * 
	 * @param DLatLng $p L'autre point.
	 * @param float $radius Le rayon 
	 * @return float
	 */
	public function distance(DLatLng $p, $radius=6374892.5) {
		if (!isset($p)) {
			throw new MissingArgumentException('$p', 'GLatLng');
		}

		$s = acos(
			sin($this->getLatRad()) * sin($p->getLatRad())
			+ cos($this->getLatRad()) * cos($p->getLatRad()) * cos($p->getLngRad()
			- $this->getLngRad()));

		return $s * $radius;
	}

	/**
	 * Indique si le point $p est situé au même emplacement que ce point.
	 * 
	 * @param DLatLng $p L'autre point.
	 * @return boolean
	 */
	public function equals(DLatLng $p) {
		if (!isset($p)) return FALSE;
		if ($p->getLat() !== $this->getLat()) return FALSE;
		if ($p->getLng() !== $this->getLng()) return FALSE;
		return TRUE;
	}

	/**
	 * Afficher l'emplacement du point au format degrès-minutes-secondes.
	 * 
	 * @param boolean $format Activer le format avec les points cardinaux.
	 * @return string
	 */
	public function toDegree($format=TRUE) {
		if ($format) {
			return self::decimal2degree($this->lat, TRUE, TRUE) . ',' . self::decimal2degree($this->lng, FALSE, TRUE);
		}
		else {
			return self::decimal2degree($this->lat, TRUE, FALSE) . ', ' . self::decimal2degree($this->lng, FALSE, FALSE);
		}
	}

	/**
	 * Méthode utilitaire pour transformer une coordonnée décimale en coordonnée au
	 * format degrès-minutes-secondes.
	 * 
	 * @param float $coord La coordonnée.
	 * @param boolean $lat Indique s'il s'agit de la latitude (TRUE), ou de la longitude (FALSE).
	 * @param boolean $format Activer le format avec les points cardinaux.
	 * @return string
	 */
	protected static function decimal2degree($coord, $lat, $format=TRUE) {
		$decpos = strpos($coord, '.');
		$whole_part = substr($coord, 0, $decpos);
		$decimal_part = abs($coord - $whole_part);
		$minutes = intval($decimal_part * 60);
		$seconds = round(($decimal_part * 60 - $minutes) * 60, 2);
		if ($lat && $format) {
			if ($whole_part < 0) {
				$whole_part = -$whole_part;
				$L = 'S';
			}
			else {
				$L = 'N';
			}
		}
		else if ($format) {
			if ($whole_part < 0) {
				$whole_part = -$whole_part;
				$L = 'W';
			}
			else {
				$L = 'E';
			}
		}
		if ($format) {
			return $whole_part.self::symbolDeg.$minutes.self::symbolMin.$seconds.self::symbolSec.$L;
		}
		else {
			return ($whole_part < 0 ? $whole_part : '+'.$whole_part).
				self::symbolDeg.$minutes.self::symbolMin.$seconds.self::symbolSec;
		}
	}

	/**
	 * Modifie l'emplacement du point en donnant les coordonnées au format degrès-minutes-secondes.
	 * 
	 * <br>Ces formats sont acceptés :
	 * <pre>
	 *  +1°9'35.56", -9°14'25.15"
	 *  1°9'35.56"N,9°14'25.15"W
	 *  +1° 9' 35.56", -9° 14' 25.15"
	 *  1° 9' 35.56" N, 9° 14' 25.15" W
	 * </pre>
	 * 
	 * @param string $coord La coordonnée au format degrès-minutes-secondes.
	 * @return void
	 * @throws IllegalArgumentException Si le format des coordonnées est invalide.
	 */
	public function setLatLngDegree($coord) {
		$coord = explode(',', $coord);
		if (sizeof($coord) != 2) {
			throw new IllegalArgumentException();
		}
		$lat = self::degree2decimal(trim($coord[0]));
		$lng = self::degree2decimal(trim($coord[1]));
		$this->setLat($lat);
		$this->setLng($lng);
	}

	/**
	 * Méthode utilitaire pour convertir une coordonnée du format
	 * degrès-minutes-secondes au format décimal.
	 * 
	 * <br>Ces formats sont acceptés :
	 * <pre>
	 *  +1°9'35.56", -9°14'25.15"
	 *  1°9'35.56"N,9°14'25.15"W
	 *  +1° 9' 35.56", -9° 14' 25.15"
	 *  1° 9' 35.56" N, 9° 14' 25.15" W
	 * </pre>
	 * 
	 * @param string $coord La coordonnée.
	 * @return float
	 */
	public function degree2decimal($coord) {
		$coord = explode(self::symbolDeg, $coord);
		$degrees = intval($coord[0]);
		$coord = explode(self::symbolMin, $coord[1]);
		$minutes = intval($coord[0]);
		$coord = explode(self::symbolSec, $coord[1]);
		$seconds = floatval($coord[0]);
		$direction = $coord[1];

		$seconds = $seconds / 60;
		$minutes = $minutes + $seconds;
		$minutes = $minutes / 60;
		$decimal = abs($degrees) + $minutes;

		if ($direction == 'S' || $direction == 'W' || $degrees < 0) {
			$decimal = -$decimal;
		}
		return $decimal;
	}

	/**
	 * Afficher cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return "{$this->lat},{$this->lng}";
	}

}

?>