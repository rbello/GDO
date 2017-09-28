<?php

/**
 * Type énuméré représentant les differentes valeurs de positionnement
 * sur l'axe des ordonnée : TOP, MIDDLE et BOTTOM.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
final class DVerticalLocation implements Enum {

	/**
	 * @var DVerticalLocation
	 */
	public static $TOP = NULL;

	/**
	 * @var DVerticalLocation
	 */
	public static $MIDDLE = NULL;

	/**
	 * @var DVerticalLocation
	 */
	public static $BOTTOM = NULL;

	/**
	 * Nom de la constante.
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Valeur de la constante.
	 * 
	 * @var int
	 */
	private $value;

	/**
	 * Constructeur de l'enum DVerticalLocation.
	 * 
	 * @param string $name Nom de la constante.
	 * @param int $value Valeur de la constante.
	 */
	private function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Renvoi la valeur de la constante.
	 * 
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Renvoi le nom de la constante.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Teste si $other correspond à la constante.
	 * 
	 * @param DVerticalLocation $other L'autre constante à tester.
	 * @return boolean
	 */
	public function equals(DVerticalLocation $other) {
		if (!isset($other)) return FALSE;
		return ($other->getValue() === $this->value);
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Renvoi la constante dont le nom est $name, ou NULL si aucune
	 * constante ne porte ce nom.
	 * 
	 * @param string $name Le nom de la constante.
	 * @return DVerticalLocation
	 */
	public static function valueOf($name) {
		if (!isset($name)) return NULL;
		if ($name == 'TOP') return self::$TOP;
		if ($name == 'MIDDLE') return self::$MIDDLE;
		if ($name == 'BOTTOM') return self::$BOTTOM;
		return NULL;
	}

	/**
	 * Initialise les constantes de l'enum.
	 * 
	 * @return void
	 */
	public static function init() {
		self::$TOP = new DVerticalLocation('TOP', 0);
		self::$MIDDLE = new DVerticalLocation('MIDDLE', 1);
		self::$BOTTOM = new DVerticalLocation('BOTTOM', 2);
	}
}

DVerticalLocation::init();

/**
 * Type énuméré représentant les differentes valeurs de positionnement
 * sur l'axe des abscisses : LEFT, CENTER et RIGHT.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
final class DHorizontalLocation implements Enum {

	/**
	 * @var DHorizontalLocation
	 */
	public static $LEFT = NULL;

	/**
	 * @var DHorizontalLocation
	 */
	public static $CENTER = NULL;

	/**
	 * @var DHorizontalLocation
	 */
	public static $RIGHT = NULL;

	/**
	 * Nom de la constante.
	 * @var string
	 */
	private $name;

	/**
	 * Valeur de la constante.
	 * @var int
	 */
	private $value;

	/**
	 * Constructeur de l'enum DHorizontalLocation.
	 * @param string $name Nom de la constante.
	 * @param int $value Valeur de la constante.
	 */
	private function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Renvoi la valeur de la constante.
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Renvoi le nom de la constante.
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Teste si $other correspond à la constante.
	 * 
	 * @param DHorizontalLocation $other L'autre constante à tester.
	 * @return boolean
	 */
	public function equals(DHorizontalLocation $other) {
		if (!isset($other)) return FALSE;
		return ($other->getValue() === $this->value);
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Renvoi la constante dont le nom est $name, ou NULL si aucune
	 * constante ne porte ce nom.
	 * 
	 * @param string $name Le nom de la constante.
	 * @return DHorizontalLocation
	 */
	public static function valueOf($name) {
		if (!isset($name)) return NULL;
		if ($name == 'LEFT') return self::$LEFT;
		if ($name == 'CENTER') return self::$CENTER;
		if ($name == 'RIGHT') return self::$RIGHT;
		return NULL;
	}

	/**
	 * Initialise les constantes de l'enum.
	 * 
	 * @return void
	 */
	public static function init() {
		self::$LEFT = new DHorizontalLocation('LEFT', 0);
		self::$CENTER = new DHorizontalLocation('CENTER', 1);
		self::$RIGHT = new DHorizontalLocation('RIGHT', 2);
	}
}

DHorizontalLocation::init();


/**
 * Interface définissant les méthodes d'un objet capable de calculer l'emplacement
 * d'un calque dans une pile d'image (imagestack).
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
interface DLayerLocation {

	/**
	 * Calculer l'emplacement du calque.
	 *
	 * @param DRectangle $parent Les dimensions du parent, c'est à dire les dimensions globales de l'image.
	 * @param DRectangle $image Les dimensions du calque à positionner. 
	 * @return Point2d
	 */
	public function calculateLocation(DRectangle $parent, DRectangle $image);

}

/**
 * Interface définissant les méthodes d'un calque.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
interface DLayerInterface {

	/**
	 * Changer l'emplacement du calque.
	 *
	 * @param DLayerLocation $location L'emplacement du calque.
	 * @return void
	 */
	public function setLayerLocation(DLayerLocation $location);

	/**
	 * Renvoi l'emplacement du calque.
	 *
	 * @return DLayerLocation
	 */
	public function getLayerLocation();

	/**
	 * Modifier la visibilité du calque. Un calque non visible ne sera pas pris en compte dans une pile d'image (imagestack).
	 *
	 * @param boolean $value Visiblité du calque
	 * @return void
	 */
	public function setVisible($value);

	/**
	 * Renvoi l'état de visibilité du calque.
	 *
	 * @return boolean
	 */
	public function isVisible();

	/**
	 * Renvoi la ressource (image) contenue dans le calque.
	 *
	 * @return DResource
	 */
	public function getResource();

}

/**
 * Un calque (layer) corresponds à une image dans une pile d'image (imagestack). A l'image de photoshop, une pile
 * d'image permet de rassembler plusieurs images dans une pile, et de produire une nouvelle image qui sera composée
 * de la superposition de tous les calques.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
class DLayer implements DLayerInterface {

	/**
	 * Stoque la ressource associée à ce calque.
	 * @var DResource
	 */
	protected $resource;

	/**
	 * Indique si le calque est visible ou non.
	 * @var boolean
	 */
	protected $visible = TRUE;

	/**
	 * Constructeur de la classe DLayer.
	 *
	 * @construct DLayer(DImageInterface $resource)
	 *  Construit un layer à partir de la resource $resource.
	 * @construct DLayer(DImageInterface $resource, DLayerLocation $location)
	 *  Construit un layer à partir de la resource $resource et positionne le calque à $location.
	 *
	 * <br>Si aucun emplacement ($location) n'est passé au constructeur, le calque sera positionné
	 * à la coordonnée absolue <code>0;0</code>.
	 *
	 * @param DImage $resource La ressource image à placer dans le calque.
	 * @param DLayerLocation $location L'emplacement du calque, ou NULL pour l'emplacement par défaut.
	 * @throws MissingArgumentException Si un des argument est omis ou NULL.
	 * @throws BadArgumentTypeException Si un des arguments est d'un type invalide.
	 */
	public function __construct(DImage $resource, DLayerLocation $location=NULL) {

		$resource = @PHPHelper::checkArgument('$resource', $resource, 'DImage');
		$location = @PHPHelper::checkArgument('$location', $location, 'DLayerLocation', FALSE);

		if ($location == NULL) {
			$location = new DAbsoluteLayerLocation(0, 0);
		}
		$this->resource = $resource;
		$this->location = $location;
	}

	/**
	 * Changer l'emplacement du calque.
	 *
	 * @param DLayerLocation $location L'emplacement du calque.
	 * @return void
	 * @throws MissingArgumentException Si un l'argument $location est omis ou NULL.
	 * @throws BadArgumentTypeException Si un l'argument $location d'un type invalide.
	 */
	public function setLayerLocation(DLayerLocation $location) {
		$location = @PHPHelper::checkArgument('$location', $location, 'DLayerLocation');
		$this->location = $location;
	}

	/**
	 * Renvoi l'emplacement du calque.
	 *
	 * @return DLayerLocation
	 */
	public function getLayerLocation() {
		return $this->location;
	}

	/**
	 * Renvoi la ressource (image) contenue dans le calque.
	 *
	 * @return DImage
	 */
	public function getResource() {
		return $this->resource;
	}

	/**
	 * Modifier la visibilité du calque. Un calque non visible ne sera pas pris en compte dans une pile d'image (imagestack).
	 *
	 * @param boolean $value Visiblité du calque.
	 * @return void
	 * @throws MissingArgumentException Si un l'argument $value est omis ou NULL.
	 * @throws BadArgumentTypeException Si un l'argument $value n'est pas un boolean.
	 */
	public function setVisible($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'boolean');
		$this->visible = $value;
	}

	/**
	 * Renvoi l'état de visibilité du calque.
	 *
	 * @return boolean
	 */
	public function isVisible() {
		return $this->visible;
	}

}

/**
 * Positionnement absolue d'un calque.
 * Il s'agit d'un positionnement en pixel, avec les coordonnées X (abscisses) et Y (ordonnées).
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
final class DAbsoluteLayerLocation extends DPoint implements DLayerLocation {

	/**
	 * Calculer l'emplacement du calque.
	 * Comme ici le positionnement est absolue, il n'y a aucun calcule à faire : on se contente de renvoyer les valeurs de la position.
	 *
	 * @param DRectangle $parent Les dimensions du parent, c'est à dire les dimensions globales de l'image.
	 * @param DRectangle $image Les dimensions du calque à positionner. 
	 * @return Point2d
	 */
	public function calculateLocation(DRectangle $parent, DRectangle $image) {
		return $this;
	}

}

/**
 * Positionnement relatif d'un calque.
 * Un positionnement relatif permet de positionner un calque en fonction des autres calques. Ainsi,
 * le calque peut être positionné "en haut à droite" ou "en bas à gauche" sans avoir à calculer les
 * dimensions complètes de l'image avec tous les calques.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.layer
 */
final class DRelativeLayerLocation implements DLayerLocation {

	/**
	 * L'emplacement relatif horizontal du calque.
	 * @var GHorizontalLocation
	 */
	protected $horizontal;

	/**
	 * L'emplacement relatif vertical du calque.
	 * @var GVerticalLocation
	 */
	protected $vertical;

	/**
	 * L'offset horizontal à ajouter au positionnement du calque.
	 * @var int
	 */
	protected $offsetX;

	/**
	 * L'offset vertical à ajouter au positionnement du calque.
	 * @var int
	 */
	protected $offsetY;

	/**
	 * Constructeur de la classe DRelativeLayerLocation.
	 *
	 * @construct DRelativeLayerLocation(DHorizontalLocation $horizontal, DVerticalLocation $vertical)
	 *  Construit un objet DRelativeLayerLocation aux deux coordonnées relatives $horizontal et $vertical.
	 * @construct DRelativeLayerLocation(DHorizontalLocation $horizontal, DVerticalLocation $vertical, int $offsetX, int $offsetY)
	 *  Construit un objet DRelativeLayerLocation aux deux coordonnées relatives $horizontal et $vertical,
	 *  en spécifiant les deux valeurs d'offset $offsetX et $offsetY.
	 *
	 * Si aucun emplacement ($location) n'est passé au constructeur, le calque sera positionné à la coordonnée absolue 0;0.
	 *
	 * @param DHorizontalLocation $horizontal Emplacement horizontale.
	 * @param DVerticalLocation $vertical Emplacement vertical.
	 * @param int $offsetX Décalage (offset) sur l'axe des abscisses, par rapport à l'emplacement calculé.
	 * @param int $offsetY Décalage (offset) sur l'axe des ordonnées, par rapport à l'emplacement calculé.
	 * @throws MissingArgumentException Si un des argument est omis ou NULL.
	 * @throws BadArgumentTypeException Si un des arguments est d'un type invalide.
	 */
	public function __construct(DHorizontalLocation $horizontal, DVerticalLocation $vertical, $offsetX=0, $offsetY=0) {
		$this->setHorizontalLocation($horizontal);
		$this->setVerticalLocation($vertical);
		$this->setHorizontalOffset($offsetX);
		$this->setVerticalOffset($offsetY);
	}

	/**
	 * Modifier la valeur du décalage (offset) sur l'axe des abscisses, par rapport à l'emplacement calculé.
	 * 
	 * @param int $value La valeur de l'offset.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas du type attendu.
	 */
	public function setHorizontalOffset($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		$this->offsetX = $value;
	}

	/**
	 * Modifier la valeur du décalage (offset) sur l'axe des ordonnées, par rapport à l'emplacement calculé.
	 * 
	 * @param int $value La valeur de l'offset.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas du type attendu.
	 */
	public function setVerticalOffset($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		$this->offsetY = $value;
	}

	/**
	 * Modifier l'emplacement sur l'axe des abscisses.
	 * 
	 * @param DHorizontalLocation $value Le nouvel emplacement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas du type attendu.
	 */
	public function setHorizontalLocation(DHorizontalLocation $value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'DHorizontalLocation');
		$this->horizontal = $value;
	}

	/**
	 * Modifier l'emplacement sur l'axe des ordonnées.
	 * 
	 * @param DVerticalLocation $value Le nouvel emplacement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas du type attendu.
	 */
	public function setVerticalLocation(DVerticalLocation $value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'DVerticalLocation');
		$this->vertical = $value;
	}

	/**
	 * Renvoi l'emplacement sur l'axe des abscisses.
	 * 
	 * @return DHorizontalLocation
	 */
	public function getHorizontalLocation() {
		return $this->horizontal;
	}

	/**
	 * Renvoi l'emplacement sur l'axe des ordonnées.
	 * 
	 * @return DVerticalLocation
	 */
	public function getVerticalLocation() {
		return $this->vertical;
	}

	/**
	 * Calculer l'emplacement du calque.
	 * Le calcul se fait en prennant en compte les dimensions totales de tous les calques ($parent).
	 *
	 * @param DRectangle $parent Les dimensions du parent, c'est à dire les dimensions globales de l'image.
	 * @param DRectangle $image Les dimensions du calque à positionner. 
	 * @return DPoint
	 */
	public function calculateLocation(DRectangle $parent, DRectangle $image) {
		$pt = new DPoint(0, 0);
		# Horizontal
		if ($this->horizontal == DHorizontalLocation::$LEFT) {
			$pt->setX(0);
		}
		else if ($this->horizontal == DHorizontalLocation::$CENTER) {
			$pt->setX((int)($parent->getWidth() / 2 - $image->getWidth() / 2));
		}
		else if ($this->horizontal == DHorizontalLocation::$RIGHT) {
			$pt->setX($parent->getWidth() - $image->getWidth());
		}
		# Vertical
		if ($this->vertical == DVerticalLocation::$TOP) {
			$pt->setY(0);
		}
		else if ($this->vertical == DVerticalLocation::$MIDDLE) {
			$pt->setY((int)($parent->getHeight() / 2 - $image->getHeight() / 2));
		}
		else if ($this->vertical == DVerticalLocation::$BOTTOM) {
			$pt->setY($parent->getHeight() - $image->getHeight());
		}
		# Offset
		$pt->addX($this->offsetX);
		$pt->addY($this->offsetY);
		# Return
		return $pt;
	}

}

?>