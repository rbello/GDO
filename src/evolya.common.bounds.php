<?php

/**
 * Objet qui représente les limites (bornes) d'un espace.
 * Les coordonnées et les dimensions sont des entiers.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DBounds implements DResizable, DPositionnable {

	/**
	 * Coordonnée en X des bornes.
	 * @var int
	 */
	protected $x;

	/**
	 * Coordonnée en Y des bornes.
	 * @var int
	 */
	protected $y;

	/**
	 * Largeur des bornes.
	 * @var int
	 */
	protected $width;

	/**
	 * Hauteur des bornes.
	 * @var int
	 */
	protected $height;

	/**
	 * Constructeur de la classe DBounds.
	 * 
	 * @construct DBounds()
	 *  Construit un objet Bounds avec les valeurs <code>0,0,0,0</code>.
	 * @construct DBounds(Bounds $b)
	 *  Construit une copie de l'objet DBounds $b.
	 * @construct DBounds(int $x, int $y, int $width, int $height)
	 *  Construit un objet DBounds avec les valeurs données.
	 * 
	 * @param DBounds|int $arg0 Coordonnée X, ou DBounds à copier.
	 * @param int $arg1 Coordonnée Y, ou NULL si $arg0 est un DBounds.
	 * @param int $arg2 Largeur, ou NULL si $arg0 est un DBounds.
	 * @param int $arg3 Hauteur, ou NULL si $arg0 est un DBounds.
	 */
	public function __construct($arg0=0, $arg1=0, $arg2=0, $arg3=0) {
		if ($arg0 instanceof DBounds) {
			$this->setX($arg0->getX());
			$this->setY($arg0->getY());
			$this->setWidth($arg0->getWidth());
			$this->setHeight($arg0->getHeight());
		}
		else {
			$this->setX($arg0);
			$this->setY($arg1);
			$this->setWidth($arg2);
			$this->setHeight($arg3);
		}
	}

	/**
	 * Indique si le point $p est contenu dans les bornes.
	 * 
	 * @param DPoint $p Le point.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $p est manquant.
	 */
	public function contains(DPoint $p) {
		if (!isset($p)) {
			throw new MissingArgumentException('$p', 'DPoint');
		}
		if ($p->getX() < $this->getX()) {
			return FALSE;
		}
		if ($p->getY() < $this->getY()) {
			return FALSE;
		}
		if ($p->getX() > $this->getX() + $this->getWidth()) {
			return FALSE;
		}
		if ($p->getY() > $this->getY() + $this->getHeight()) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Ajouter ce point à cette borne. Si le point se trouve
	 * en dehors des bornes, les bornes seront élargies.
	 * 
	 * @param DPoint $p Le point à ajouter.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $p est manquant.
	 */
	public function extend(DPoint $p) {
		if (!isset($p)) {
			throw new MissingArgumentException('$p', 'DPoint');
		}
		if ($this->contains($p)) {
			return;
		}
		if ($p->getX() < $this->getX()) {
			$this->width += abs($p->getX() - $this->getX());
			$this->setX($p->getX());
		}
		if ($p->getY() < $this->getY()) {
			$this->height += abs($p->getY() - $this->getY());
			$this->setY($p->getY());
		}
		if ($p->getX() > $this->getX() + $this->getWidth()) {
			$this->width = $p->getX() - $this->getX();
		}
		if ($p->getY() > $this->getY() + $this->getHeight()) {
			$this->height = $p->getY() - $this->getY();
		}
	}

	/**
	 * Renvoi le point au centre de cette borne.
	 * 
	 * @return DPoint
	 */
	public function getCenter() {
		return new DPoint(
			$this->getX() + (int)($this->getWidth() / 2),
			$this->getY() + (int)($this->getHeight() / 2)
		);
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
	 * Modifier la largeur de l'objet.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidWidthException Si le paramètre $value est invalide.
	 */
	public function setWidth($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 0) {
			throw new InvalidWidthException($value);
		}
		$this->width = $value;
	}

	/**
	 * Modifier la largeur de l'objet, en ajustant automatiquement la hauteur
	 * de l'objet pour garder les proportions des dimensions.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidWidthException Si le paramètre $value est invalide.
	 */
	public function setWidthRatio($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 0) {
			throw new InvalidWidthException();
		}
		$ratio = $value / $this->getWidth();

		$this->info->setWidth((int)($this->getWidth() * $ratio));
		$this->info->setHeight((int)($this->getHeight() * $ratio));
	}

	/**
	 * Renvoi la largeur de l'objet.
	 * 
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Modifier la hauteur de l'objet.
	 * 
	 * @param int $value Hauteur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si l'argument $value est < à 0
	 */
	public function setHeight($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 0) {
			throw new InvalidHeightException($value);
		}
		$this->height = $value;
	}

	/**
	 * Modifier la hauteur de l'objet, en ajustant automatiquement la largeur
	 * de l'image pour garder les proportions des dimensions.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si le paramètre $value est invalide.
	 */
	public function setHeightRatio($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 0) {
			throw new InvalidHeightException();
		}
		$ratio = $value / $this->getHeight();

		$this->info->setWidth((int)($this->getWidth() * $ratio));
		$this->info->setHeight((int)($this->getHeight() * $ratio));
	}

	/**
	 * Renvoi la hauteur de l'objet.
	 * 
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Modifier les dimensions de l'objet.
	 * 
	 * @param DResizable $r La dimension.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws InvalidWidthException Si la largeur est < à 0.
	 * @throws InvalidHeightException Si la hauteur est < à 0.
	 */
	public function setDimension(DResizable $r) {
		if (!isset($r)) {
			throw new MissingArgumentException('$r', 'DResizable');
		}
		if ($r->getWidth() < 0) {
			throw new InvalidWidthException();
		}
		if ($r->getHeight() < 0) {
			throw new InvalidHeightException();
		}
		$this->width = $r->getWidth();
		$this->height = $r->getHeight();
	}

	/**
	 * Renvoi les dimensions de l'objet.
	 * 
	 * @return DRectangle
	 */
	public function getDimension() {
		return new DRectangle($this);
	}

	/**
	 * Modifier les dimensions de l'objet.
	 * 
	 * @param int $w Largeur de l'objet.
	 * @param int $h Hauteur de l'objet.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si les dimensions sont invalides.
	 */
	public function setDimensions($w, $h) {
		$this->setWidth($w);
		$this->setHeight($h);
	}

	/**
	 * Effectue une multiplication scalaire des dimensions par $factor.
	 *
	 * @param float $factor
	 * @return void
	 * @throws MissingArgumentException Si l'argument $factor est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $factor n'est pas un flottant.
	 * @throws IllegalArgumentException Si l'argument $factor est < à 0.
	 */
	public function scale($factor) {
		$factor = @PHPHelper::checkArgument('$factor', $factor, 'float', TRUE, TRUE);
		if ($factor < 0) {
			throw new IllegalArgumentException($factor);
		}
		$this->x = (int)($factor * $this->x);
		$this->y = (int)($factor * $this->y);
		$this->width = (int)($factor * $this->width);
		$this->height = (int)($factor * $this->height);
	}

	/**
	 * Met les dimensions en valeurs absolues.
	 * 
	 * @return void
	 */
	public function absolute() {
		$this->x = abs($this->x);
		$this->y = abs($this->y);
		$this->width = abs($this->width);
		$this->height = abs($this->height);
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string.
	 */
	public function __toString() {
		return "Bounds ({$this->x},{$this->y}) {$this->width}x{$this->height}";
	}

}

?>