<?php

/**
 * Objet qui enregistre les dimensions d'un rectangle. Les dimensions sont des entiers.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DRectangle implements DResizable {

	/**
	 * Largeur du rectangle.
	 * @var int
	 */
	protected $width;

	/**
	 * Hauteur du rectangle.
	 * @var int
	 */
	protected $height;

	/**
	 * Constructeur de la classe DRectangle.
	 * 
	 * @construct DRectangle()
	 *  Construit un rectangle aux dimensions 0,0.
	 *
	 * @construct DRectangle(DResizable $r)
	 *  Construit une copie du rectangle $r. 
	 *
	 * @construct DRectangle(int $width, int $height)
	 *  Construit un rectangle avec les dimensions $width,$height.
	 * 
	 * @param DRectangle|int $arg0 Largeur, ou un DRectangle à copier.
	 * @param int $arg1 Largeur, ou NULL si $arg0 est un DRectangle.
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws IllegalArgumentException Si les dimensions données sont < à 0
	 */
	public function __construct($arg0=0, $arg1=0) {
		if ($arg0 instanceof DResizable) {
			$this->setDimension($arg0);
		}
		else {
			$this->setWidth($arg0);
			$this->setHeight($arg1);
		}
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
		$this->width = (int)($factor * $this->width);
		$this->height = (int)($factor * $this->height);
	}

	/**
	 * Modifier les dimensions de l'objet.
	 * 
	 * @param int $w Largeur de l'objet.
	 * @param int $h Hauteur de l'objet.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si les dimensions sont invalides.
	 * @throws InvalidWidthException Si la largeur est < à 0.
	 * @throws InvalidHeightException Si la hauteur est < à 0.
	 */
	public function setDimensions($w, $h) {
		$this->setWidth($w);
		$this->setHeight($h);
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return "{$this->width}x{$this->height}";
	}

}

?>