<?php

/**
 * L'interface DShape est implémentée par les objets qui correspondent à des formes
 * qui peuvent être dessinnées sur des canevas de pixel.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.drawing
 */
interface DShape {

	/**
	 * Méthode appellée lorsque la shape doit se dessinner sur l'image $image.
	 * 
	 * @param DImageInterface $image
	 * @return boolean
	 */
	public function draw(DImageInterface $image);

}

/**
 * L'interface DSupportShape peut-être implémentée par les classes qui supportent
 * le dessin de formes (DShape).
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.drawing
 */
interface DSupportShape {

	/**
	 * Dessiner la forme $shape sur cet objet.
	 * 
	 * @param DShape $shape La forme à dessiner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $shape est manquant.
	 */
	public function drawShape(DShape $shape);

}

/**
 * Classe abstraite permettant de faciliter la création de nouvelles
 * shape. Cette classe apporte principalement l'implémentation de
 * l'interface DPositionnable.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.drawing
 */
abstract class DAbstractShape implements DPositionnable, DShape {

	/**
	 * Position de la shape.
	 * @var DPoint
	 */
	protected $point;

	/**
	 * Constructeur de la classe DAbstractShape.
	 * 
	 * @construct DAbstractShape()
	 *  Construit une DAbstractShape à la position 0,0.
	 * 
	 * @construct DAbstractShape(int $x, int $y)
	 *  Construit une DAbstractShape à la position $x,$y.
	 * 
	 * @param int $x Emplacement en abscisse de la forme.
	 * @param int $y Emplacement en ordonnée de la forme.
	 * @throws BadArgumentTypeException Si les arguments ne sont pas des types valides.
	 */
	public function __construct($x=0, $y=0) {
		$this->point = new DPoint($x, $y);
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
		$this->point->setX($value);
	}

	/**
	 * Renvoi la valeur en abscisse de l'objet.
	 *
	 * @return int
	 */
	public function getX() {
		return $this->point->getX();
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
		$this->point->setY($value);
	}

	/**
	 * Renvoi la valeur en ordonnée de l'objet.
	 *
	 * @return int
	 */
	public function getY() {
		return $this->point->getY();
	}

	/**
	 * Modifier l'emplacement de l'objet.
	 * 
	 * @param DPositionnable $p L'emplacement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $p n'est pas un DPositionnable.
	 */
	public function setLocation(DPositionnable $p) {
		$this->point->setLocation($p);
	}

	/**
	 * Renvoi l'emplacement de l'objet.
	 * 
	 * @return DPositionnable
	 */
	public function getLocation() {
		return $this->point->getLocation();
	}

}

/**
 * Cet objet permet de dessinner un rectangle dans une image.
 * <br>Les paramètres suivants sont modifiables : style et couleur du trait
 * de bordure, couleur de fond, emplacements et dimensions.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.drawing
 */
class DRectangleShape extends DAbstractShape {

	/**
	 * Largeur de la bordure.
	 * @var int
	 */
	protected $borderSize = 0;

	/**
	 * Couleur de la bordure, ou NULL.
	 * @var DColorInterface
	 */
	protected $borderColor = NULL;

	/**
	 * Couleur de fond.
	 * @var DColorInterface
	 */
	protected $bgColor = NULL;

	/**
	 * Dimensions du rectangle.
	 * @var DRectangle
	 */
	protected $dimensions = NULL;

	/**
	 * Constructeur de la classe DRectangleShape.
	 * 
	 * TODO Verifier la présence des arguments
	 * TODO Implémenter tous les constructeurs
	 * TODO Voir l'histoire des styles, les modifs à porter à cette classe.
	 * 
	 * @construct DRectangleShape(DBounds $dim, DColorInterface $borderColor, int $borderSize, DColorInterface $bgColor)
	 * @construct DRectangleShape(DBounds $dim, DColorInterface $borderColor, int $borderSize)
	 * @construct DRectangleShape(int $x, int $y, int $w, int $h, DColorInterface $borderColor, int $borderSize, DColorInterface $bgColor)
	 * @construct DRectangleShape(int $x, int $y, int $w, int $h, DColorInterface $borderColor, int $borderSize)
	 * 
	 * @param unknown_type $x
	 * @param unknown_type $y
	 * @param unknown_type $width
	 * @param unknown_type $height
	 * @param unknown_type $borderColor
	 * @param unknown_type $bgColor
	 * @return unknown_type
	 */
	public function __construct($x, $y, $width, $height, $borderColor=NULL, $borderSize=1, $bgColor=NULL) {
		parent::__construct($x, $y);
		$this->dimensions = new DRectangle($width, $height);
		$this->setBackgroundColor($bgColor);
		$this->setBorderSize($borderSize);
		$this->setBorderColor($borderColor);
	}

	/**
	 * Modifier la couleur de fond (remplissage) de la forme.
	 * 
	 * @param DColorInterface $color Couleur de fond, ou NULL pour du transparent.
	 * @return void
	 */
	public function setBackgroundColor(DColorInterface $color=NULL) {
		$this->bgColor = $color;
	}

	/**
	 * Renvoi la couleur de fond (remplissage) de la forme. Renvoi NULL
	 * si la forme n'a pas de couleur de remplissage (transparent).
	 * 
	 * @return DColorInterface
	 */
	public function getBackgroundColor() {
		return $this->bgColor;
	}

	/**
	 * Modifier la largeur du contour de la forme.
	 * 
	 * @param int $size Largeur, en pixels.
	 * @return void
	 */
	public function setBorderSize($size) {
		// TODO verifier $size
		$this->borderSize = $size;
	}

	/**
	 * Renvoi la largeur du contour de la forme, en pixels.
	 * 
	 * @return int
	 */
	public function getBorderSize() {
		return $this->borderSize;
	}

	/**
	 * Modifier la couleur de bordure de la forme.
	 * 
	 * @param DColorInterface $color Couleur de bordure, ou NULL pour du transparent.
	 * @return void
	 */
	public function setBorderColor(DColorInterface $color=NULL) {
		$this->borderColor = $color;
	}

	/**
	 * Renvoi la couleur de bordure de la forme. Renvoi NULL
	 * si la forme n'a pas de couleur de contours (transparent).
	 * 
	 * @return DColorInterface
	 */
	public function getBorderColor() {
		return $this->borderColor;
	}

	/**
	 * Méthode appellée lorsque la shape doit se dessinner sur l'image $image.
	 * 
	 * @param DImageInterface $image
	 * @return boolean
	 */
	public function draw(DImageInterface $image) {
		if (!$image->isBound()) {
			return FALSE;
		}
		if ($this->borderSize <= 0 && $this->borderColor == NULL && $this->bgColor == NULL) {
			return FALSE;
		}
		if ($this->dimensions->getWidth() == 0 || $this->dimensions->getHeight() == 0) {
			return FALSE;
		}
		if ($this->borderSize > 0 && $this->borderColor != NULL) {
			$i = 1;
			while ($i <= $this->borderSize) {
				imageline(
					$image->getGDResource(),
					$this->getX()-$i,
					$this->getY()-$i,
					$this->getX()+$this->dimensions->getWidth()+$i,
					$this->getY()-$i,
					$this->borderColor->toGDColor()
				);
				imageline(
					$image->getGDResource(),
					$this->getX()+$this->dimensions->getWidth()+$i,
					$this->getY()-$i,
					$this->getX()+$this->dimensions->getWidth()+$i,
					$this->getY()+$this->dimensions->getHeight()+$i,
					$this->borderColor->toGDColor()
				);
				imageline(
					$image->getGDResource(),
					$this->getX()+$this->dimensions->getWidth()+$i,
					$this->getY()+$this->dimensions->getHeight()+$i,
					$this->getX()-$i,
					$this->getY()+$this->dimensions->getHeight()+$i,
					$this->borderColor->toGDColor()
				);
				imageline(
					$image->getGDResource(),
					$this->getX()-$i,
					$this->getY()-$i,
					$this->getX()-$i,
					$this->getY()+$this->dimensions->getHeight()+$i,
					$this->borderColor->toGDColor()
				);
				$i++;
			}
		}
		if ($this->bgColor != NULL) {
			imagefilledrectangle(
				$image->getGDResource(),
				$this->getX(),
				$this->getY(),
				$this->getX()+$this->dimensions->getWidth(),
				$this->getY()+$this->dimensions->getHeight(),
				$this->bgColor->toGDColor()
			);
		}
		return TRUE;
	}

}

?>