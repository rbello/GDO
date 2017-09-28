<?php

/**
 * Interface definissant les méthodes d'une image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.image
 */
interface DImageInterface extends DSupportOutput, DResizable/*DSupportFilters*/ {

	/**
	 * Recadre l'image.
	 * TODO
	 * 
	 * @param DBounds $bounds
	 * @return void
	 * @throws MissingArgumentException Si un des argument est absent.
	 * @throws BadArgumentTypeException Si un des argument n'a pas le type requis.
	 * @throws OutOfBoundsException Si un des arguments est en dehors des limites des dimensions de l'image.
	 */
	//public function crop(DBounds $bounds);

	/**
	 * Copy
	 * TODO
	 * 
	 * @param DBounds $bounds
	 * @return DImage
	 * @throws MissingArgumentException Si un des argument est absent.
	 * @throws BadArgumentTypeException Si un des argument n'a pas le type requis.
	 * @throws OutOfBoundsException Si un des arguments est en dehors des limites des dimensions de l'image.
	 */
	//public function copy(DBounds $bounds);

	/**
	 * Nombre de frames dans l'image. Les images GIF animées peuvent avoir plusieurs frames.
	 * 
	 * @return int
	 */
	public function getFrameCount();

	/**
	 * Renvoi le conteneur des frames de l'image. Dans le cas où l'image GIF animée contiendrai
	 * plusieurs frames, le conteneur sera présent. Sinon, renvoi NULL.
	 *  
	 * @return DImageFrameContainer
	 */
	public function getImageFrameContainer();

	/**
	 * Indique si la ressource est bien liée. En cas de problème, la ressource GD associée
	 * peut ne pas être valide. Dans ce cas, l'objet n'est pas lié.
	 * Ce test permet de savoir si la ressource GD contenue dans l'instance du DResourceInterface
	 * est bien valide et prête à travailler.
	 * 
	 * @return boolean
	 */
	public function isBound();

	/**
	 * Detruit la ressource GD et libère la mémoire utilisée. Renvoi FALSE si la ressource
	 * n'a pu être détruite.
	 * 
	 * @return boolean
	 */
	public function destroy();

	/**
	 * Renvoi les informations sur l'image.
	 * 
	 * @return DImageInfoInterface
	 */
	public function getInfos();

	/**
	 * Renvoi la ressource GD contenue dans l'objet.
	 * 
	 * @return gd_resource
	 */
	public function getGDResource();

	/**
	 * Renvoi la somme de contrôle (checksum) de cette image.
	 * 
	 * @return string
	 */
	public function getChecksum();

	/**
	 * Modifie la couleur du pixel aux coordonnées $x,$y.
	 * 
	 * @param int $x La coordonnée en abscisse du pixel.
	 * @param int $y La coordonnée en ordonnée du pixel.
	 * @param DColorInterface $color La couleur.
	 * @return void
	 * @throws ImageNotBoundException Si la ressource n'est pas liée.
	 * @throws OutOfBoundsException Si les coordonnés dépassent les dimensions de l'image.
	 * @throws MissingArgumentException Si un des argument est manquant.
	 * @throws BadArgumentTypeException Si un des argument n'est pas du type requis.
	 */
	public function setColorAt($x, $y, DColorInterface $color);

	/**
	 * Renvoi la couleur du pixel aux coordonnées $x,$y.
	 * 
	 * @param int $x La coordonnée en abscisse du pixel.
	 * @param int $y La coordonnée en ordonnée du pixel.
	 * @return DColorInterface
	 * @throws ImageNotBoundException Si la ressource n'est pas liée.
	 * @throws OutOfBoundsException Si les coordonnés dépassent les dimensions de l'image.
	 * @throws MissingArgumentException Si un des argument est manquant.
	 * @throws BadArgumentTypeException Si un des argument n'est pas du type requis.
	 */
	public function getColorAt($x, $y);

}

?>