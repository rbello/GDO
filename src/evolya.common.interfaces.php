<?php

/**
 * TODO
 */
interface DResizable {

	/**
	 * Modifier la largeur de l'objet.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidWidthException Si le paramètre $value est invalide.
	 */
	public function setWidth($value);

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
	public function setWidthRatio($value);

	/**
	 * Renvoi la largeur de l'objet.
	 * 
	 * @return int
	 */
	public function getWidth();

	/**
	 * Modifier la hauteur de l'objet.
	 * 
	 * @param int $value Hauteur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si l'argument $value est < à 0
	 */
	public function setHeight($value);

	/**
	 * Modifier la hauteur de l'objet, en ajustant automatiquement la largeur
	 * de l'image pour garder les proportions des dimentions.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si le paramètre $value est invalide.
	 */
	public function setHeightRatio($value);

	/**
	 * Renvoi la hauteur de l'objet.
	 * 
	 * @return int
	 */
	public function getHeight();

	/**
	 * Modifier les dimensions de l'objet.
	 * 
	 * @param DResizable $r La dimension.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si les dimensions sont invalides.
	 */
	public function setDimension(DResizable $r);

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
	public function setDimensions($w, $h);

	/**
	 * Renvoi les dimensions de l'objet.
	 * 
	 * @return DResizable
	 */
	public function getDimension();

	/**
	 * Effectue une multiplication scalaire des dimensions par $factor.
	 *
	 * @param float $factor
	 * @return void
	 * @throws MissingArgumentException Si l'argument $factor est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $factor n'est pas un flottant.
	 * @throws IllegalArgumentException Si l'argument $factor est < à 0.
	 */
	public function scale($factor);

}

/**
 * TODO
 */
interface DPositionnable {

	/**
	 * Changer la coordonnée en abscisse de l'objet.
	 *
	 * @param int $value La valeur en X.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setX($value);

	/**
	 * Renvoi la valeur en abscisse de l'objet.
	 *
	 * @return int
	 */
	public function getX();

	/**
	 * Changer la coordonnée en ordonnée de l'objet.
	 *
	 * @param int $value La valeur en Y.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setY($value);

	/**
	 * Renvoi la valeur en ordonnée de l'objet.
	 *
	 * @return int
	 */
	public function getY();

	/**
	 * Modifier l'emplacement de l'objet.
	 * 
	 * @param DPositionnable $p L'emplacement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $p n'est pas un DPositionnable.
	 */
	public function setLocation(DPositionnable $p);

	/**
	 * Renvoi l'emplacement de l'objet.
	 * 
	 * @return DPositionnable
	 */
	public function getLocation();

}

/**
 * L'interface Runnable peut être implémentée par les classes qui doivent
 * executer une opération de manière générique.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
interface Runnable {

	/**
	 * Méthode appellée pour lancer l'opération.
	 * 
	 * @return void
	 * @throws Exception
	 */
	public function run();

}

/**
 * L'interface Finalizable peut être implémentée par les classes qui ont la propriétés
 * d'être finalisées, c'est à dire qu'elles ne pourront plus être modifiées.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
interface Finalizable {

	/**
	 * Finalise l'objet.
	 * 
	 * @return void
	 */
	public function finalize();

	/**
	 * Indique si un objet est finalisé.
	 * 
	 * @return boolean
	 */
	public function isObjectFinalized();

}

/**
 * Cette interface doit être implémentée par les types énumérés.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
interface Enum {

	/**
	 * Renvoi la valeur de la constante du type énuméré.
	 * 
	 * @return int
	 */
	public function getValue();

	/**
	 * Renvoi le nom de la constante du type énuméré.
	 * 
	 * @return string
	 */
	public function getName();

}

?>