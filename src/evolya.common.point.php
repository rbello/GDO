<?php

/**
 * Un DPoint correspond aux coordonnées d'un point dans un espace à deux dimensions aux valeurs entières.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DPoint implements DPositionnable {

	/**
	 * Valeur en abscisse.
	 * @var int
	 */
	protected $x = 0;

	/**
	 * Valeur en ordonnée.
	 * @var int
	 */
	protected $y = 0;

	/**
	 * Constructeur de la classe DPoint.
	 *
	 * @construct new DPoint()
	 *  Construit un point aux coordonnées <code>0,0</code>.
	 *
	 * @construct new DPoint(DPositionnable $p)
	 *  Construit une copie du point $p.
	 *
	 * @construct new DPoint(int $x, int $y)
	 *  Construit un point aux coordonnées <code>$x,$y</code>.
	 *
	 * @param DPoint|int $arg0 Cordonnée en X, ou un DPoint.
	 * @param int $arg1 Cordonnée en Y, ou NULL si $arg0 est un DPoint.
	 * @throws BadArgumentTypeException Si les arguments ne sont pas des types valides.
	 */
	public function __construct($arg0=0, $arg1=0) {
		if ($arg0 instanceof DPositionnable) {
			$this->setLocation($arg0);
		}
		else {
			$this->setX($arg0);
			$this->setY($arg1);
		}
	}

	/**
	 * Changer la coordonnée en abscisse de l'objet.
	 *
	 * @param int $value La valeur en X.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setX($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->x = $value;
	}

	/**
	 * Renvoi la valeur en abscisse de l'objet.
	 *
	 * @return int
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * Changer la coordonnée en ordonnée de l'objet.
	 *
	 * @param int $value La valeur en Y.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setY($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->y = $value;
	}

	/**
	 * Renvoi la valeur en ordonnée de l'objet.
	 *
	 * @return int
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * Changer les coordonnée du point.
	 *
	 * @param int $x La valeur en X.
	 * @param int $y La valeur en Y.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $x ou $y est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $x ou $y n'est pas un entier.
	 */
	public function set($x, $y) {
		$x = @PHPHelper::checkArgument('$x', $x, 'int', TRUE, TRUE);
		$y = @PHPHelper::checkArgument('$y', $y, 'int', TRUE, TRUE);
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * Modifier l'emplacement de l'objet.
	 * 
	 * @param DPositionnable $p L'emplacement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $p n'est pas un DPositionnable.
	 */
	public function setLocation(DPositionnable $p) {
		if (!isset($p)) {
			throw new MissingArgumentException('$p', 'DPositionnable');
		}
		$this->x = $p->getX();
		$this->y = $p->getY();
	}

	/**
	 * Renvoi l'emplacement de l'objet.
	 * 
	 * @return DPoint
	 */
	public function getLocation() {
		return new DPoint($this);
	}

	/**
	 * Ajouter la valeur $value à la coordonnée en asbcisse du point.
	 *
	 * @param int $value La valeur à ajouter.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function addX($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->x += intval($value);
	}

	/**
	 * Ajouter la valeur $value à la coordonnée en ordonnée du point.
	 *
	 * @param int $value La valeur à ajouter
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function addY($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->y += intval($value);
	}

	/**
	 * Ajouter les coordonnées du point $p à celles de ce point.
	 *
	 * @param DPoint $p Le point aux coordonnées à ajouter.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $p est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $p n'est pas un DPoint.
	 */
	public function add(DPoint $p) {
		$p = @PHPHelper::checkArgument('$p', $p, 'DPoint');
		$this->x += $p->getX();
		$this->y += $p->getY();
	}

	/**
	 * Inverser le signe des coordonnées.
	 *
	 * @return void
	 */
	public function negate() {
		$this->x = -$this->x;
		$this->y = -$this->y;
	}

	/**
	 * Effectue une multiplication scalaire des coordonnées par $value.
	 *
	 * @param float $value
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un flottant.
	 */
	public function scale($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'float', TRUE, TRUE);
		$this->x = (int)($value * $this->x);
		$this->y = (int)($value * $this->y);
	}

	/**
	 * Renvoi TRUE si le point $p a les mêmes coordonnées que l'instance du point.
	 *
	 * @return boolean
	 */
	public function equals(DPoint $p) {
		if (!isset($p)) return FALSE;
		return ($p->getX() == $this->getX()) && ($p->getY() == $this->getY());
	}

	/**
	 * Renvoi TRUE si la différence entre les coordonnées de l'instance et celles de $p
	 * sont inférieures au paramètre $epsilon.
	 *
	 * @param DPoint $p L'autre point.
	 * @param float $epsilon Valeur du delta.
	 * @return boolean
	 */
	public function epsilonEquals(DPoint $p, $epsilon) {
		if (!isset($p)) return FALSE;
		if (!isset($epsilon)) return FALSE;
		$epsilon = intval($epsilon);
		if (abs($this->getX() - $p->getX()) > $epsilon) return FALSE;
		if (abs($this->getY() - $p->getY()) > $epsilon) return FALSE;
		return TRUE;
	}

	/**
	 * Limiter les coordonnées en $x et $y du point.
	 *
	 * @param int $x La valeur maximum pour l'axe des abscisses.
	 * @param int $y La valeur maximum pour l'axe des ordonnées.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $x ou $y est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $x ou $y n'est pas un entier.
	 */
	public function limit($x, $y) {
		$x = @PHPHelper::checkArgument('$x', $x, 'int', TRUE, TRUE);
		$y = @PHPHelper::checkArgument('$y', $y, 'int', TRUE, TRUE);
		if ($this->x > $x) {
			$this->x = $x;
		}
		if ($this->y > $y) {
			$this->y = $y;
		}
	}

	/**
	 * Change chaque coordonnée de ce point pour leurs valeurs absolues.
	 *
	 * @return void
	 */
	public function absolute() {
		$this->x = abs($this->x);
		$this->y = abs($this->y);
	}

	/**
	 * Calculer la distance entre le point $p et ce point.
	 *
	 * @param DPoint $p L'autre point.
	 * @return int
	 * @throws MissingArgumentException Si l'argument $p est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $p n'est pas un DPoint.
	 */
	public function distance(DPoint $p) {
		$p = @PHPHelper::checkArgument('$p', $p, 'DPoint');
		$dx = $this->x - $p->getX();  
		$dy = $this->y - $p->getY();
		return (int) sqrt($dx*$dx + $dy*$dy);
	}

	/**
	 * Afficher l'instance du point sous forme d'une chaîne de caractère.
	 *
	 * @return string
	 */
	public function __toString() {
		return "({$this->x},{$this->y})";
	}

}

?>