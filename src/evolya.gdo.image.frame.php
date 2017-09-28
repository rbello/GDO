<?php

/**
 * Interface definissant les méthodes d'une frame.
 * L'interface DImageFrame peut être implémentée par les classes qui représentent
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.image
 */
interface DImageFrame {

	/**
	 * Renvoi le contenu de la frame sous forme de string.
	 * 
	 * @return string
	 */
	public function getContents();

	/**
	 * Detruit le frame et libère la mémoire associée.
	 * 
	 * @return void
	 */
	public function dispose();

}

/**
 * Interface definissant les méthodes d'un conteneur de frames.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.image
 */
interface DImageFrameContainer {

	/**
	 * Renvoi une liste contenant toutes les frames du conteneur.
	 * 
	 * @return array&lt;DImageFrame&gt;
	 */
	public function getFrames();

	/**
	 * Renvoi le nombre de frames du conteneur.
	 * 
	 * @return int
	 */
	public function getFrameCount();

	/**
	 * Detruit le conteneur de frame ainsi que toutes les frames contenues.
	 * 
	 * @return void
	 */
	public function disposeAll();

}

?>