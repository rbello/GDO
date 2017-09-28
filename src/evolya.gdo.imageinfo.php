<?php

/**
 * Interface definissant les méthodes de base pour un objet de type ImageInfo.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imageinfo
 */
interface DImageInfoInterface {

	/**
	 * Renvoi la largeur de l'image.
	 * 
	 * @return int
	 */
	public function getWidth();

	/**
	 * Renvoi la hauteur de l'image.
	 * 
	 * @return int
	 */
	public function getHeight();

}

/**
 * Première implémentation de DImageInfoInterface. Objet contenant les informations
 * essentielles sur une image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imageinfo
 */
class DImageInfo implements DImageInfoInterface {

	/**
	 * Contient tous les types d'image detectables par GD.
	 * @var array&lt;int:string&gt;
	 */
	public static $imagesTypes = array(
		1 => 'GIF',
		2 => 'JPG',
		3 => 'PNG',
		4 => 'SWF',
		5 => 'PSD',
		6 => 'BMP',
		7 => 'TIFF(intel byte order)',
		8 => 'TIFF(motorola byte order)',
		9 => 'JPC',
		10 => 'JP2',
		11 => 'JPX',
		12 => 'JB2',
		13 => 'SWC',
		14 => 'IFF',
		15 => 'WBMP',
		16 => 'XBM'
	);

	/**
	 * Largeur de l'image.
	 * @var int
	 */
	protected $width;

	/**
	 * Hauteur de l'image.
	 * @var int
	 */
	protected $height;

	/**
	 * Constructeur de la classe DImageInfo.
	 *
	 * <code>
	 *  new DImageInfo(DImageInfo $info)
	 *  new DImageInfo(int $width, int $height)
	 * </code>
	 *
	 * @param DImageInfo|int $arg0 Soit un DImageInfo à copier, soit un int pour la largeur de l'image
	 * @param int $arg1 Hauteur de l'image, si $arg0 est un int
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type attendu.
	 */
	public function __construct($arg0, $arg1=NULL) {

		if (!isset($arg0)) {
			throw new MissingArgumentException('$arg0', 'DImageInfo|int');
		}
		if ($arg0 instanceof DImageInfoInterface) {
			$this->width = $arg0->getWidth();
			$this->height = $arg0->getHeight();
		}
		else if (is_int($arg0) && is_int($arg1)) {
			$this->width = $arg0;
			$this->height = $arg1;
		}
		else if (is_float($arg0) && is_float($arg1)) {
			$this->width = (int) $arg0;
			$this->height = (int) $arg1;
		}
		else {
			throw new BadArgumentTypeException('$arg0', $arg0, 'DImageInfo|int');
		}
	}

	/**
	 * Renvoi la largeur de l'image.
	 * 
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Renvoi la hauteur de l'image.
	 * 
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Modifier l'information de largeur de l'image.
	 * 
	 * @param int $value La nouvelle valeur.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setWidth($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->width = $value;
	}

	/**
	 * Modifier l'information de hauteur de l'image.
	 * 
	 * @param int $value La nouvelle valeur.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setHeight($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->height = $value;
	}

	/**
	 * Afficher le nom d'un type d'image par son identifiant.
	 * 
	 * @param int $type Identifiant de type d'image, comme obtenu avec getimagesize
	 * @return string
	 */
	public static function getImageTypeName($type) {
		if (array_key_exists($type, DImageInfo::$imagesTypes)) {
			return DImageInfo::$imagesTypes[$type];
		}
		else return 'unknown';
	}

	/**
	 * Afficher cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->width.'x'.$this->height;
	}

}

/**
 * Surcharge de la classe DImageInfo, qui apporte en plus des informations liées
 * à l'ouverture d'une image d'après un fichier.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imageinfo
 */
class DFileImageInfo extends DImageInfo {

	/**
	 * Le fichier source.
	 * @var DFile
	 */
	protected $file;

	/**
	 * Le type d'image source.
	 * @var int
	 * @see DImageInfo::$imagesTypes
	 */
	protected $type;

	/**
	 * Profondeur de bits de l'image.
	 * Notez que JPC et JP2 sont capables d'avoir des composants avec une profondeur
	 * de bit différente. Dans ce cas, la valeur de bits est la plus grande
	 * profondeur de bit rencontrée.
	 * @var int
	 */
	protected $bits;

	/**
	 * Couches de couleurs. Une image RVB a trois couches, RVBA quatre, et une image
	 * en noir et blanc n'a qu'une couche.
	 * @var int
	 */
	protected $channels;

	/**
	 * Constructeur de la classe DFileImageInfo.
	 * 
	 * <code>
	 *  new DFileImageInfo(DFileImageInfo $info)
	 *  new DFileImageInfo(DFile $file, int $width, int $height, int $type, int $bits, int $channels)
	 * </code>
	 * 
	 * @param DFileImageInfo|DFile $arg0 Fichier source, ou DFileImageInfo à copier.
	 * @param int $width Largeur de l'image.
	 * @param int $height Hauteur de l'image.
	 * @param int $type Format d'image.
	 * @param int $bits Profondeur de bits.
	 * @param int $channels Nombre de couches.
	 * @see DImageInfo::$imagesTypes
	 * @throws MissingArgumentException Si $arg0 des arguments est manquant
	 * @throws IllegalArgumentException Si les arguments ne forment pas une combinaison valide.
	 */
	public function __construct($arg0, $width=NULL, $height=NULL, $type=NULL, $bits=NULL, $channels=NULL) {
		if (!isset($arg0)) {
			throw new MissingArgumentException('$arg0', 'DFileImageInfo|DFile');
		}
		if ($arg0 instanceof DFileImageInfo) {
			$this->file = $arg0->getFile();
			$this->width = $arg0->getWidth();
			$this->height = $arg0->getHeight();
			$this->type = $arg0->getType();
			$this->bits = $arg0->getBits();
			$this->channels = $arg0->getChannels();
		}
		else if ($arg0 instanceof DFile && is_int($width) && is_int($height) && is_int($type)
			&& is_int($bits) && is_int($channels)) {
			$this->file = $arg0;
			$this->width = $width;
			$this->height = $height;
			$this->type = $type;
			$this->bits = $bits;
			$this->channels = $channels;
		}
		else {
			throw new IllegalArgumentException();
		}
	}

	/**
	 * Renvoi une copie du fichier source originel.
	 * 
	 * @return DFile
	 */
	public function getFile() {
		return new DFile($this->file);
	}

	/**
	 * Renvoi le type d'image.
	 * 
	 * @return int
	 * @see DImageInfo::$imagesTypes
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Profondeur de bits de l'image.
	 * Notez que JPC et JP2 sont capables d'avoir des composants avec une profondeur
	 * de bit différente. Dans ce cas, la valeur de bits est la plus grande
	 * profondeur de bit rencontrée.
	 * 
	 * @return int
	 */
	public function getBits() {
		return $this->bits;
	}

	/**
	 * Couches de couleurs. Une image RVB a trois couches, RVBA quatre, et une image
	 * en noir et blanc n'a qu'une couche.
	 * 
	 * @return int
	 */
	public function getChannels() {
		return $this->channels;
	}

	/**
	 * Factory de la classe DFileImageInfo.
	 * Permet de créer un objet DFileImageInfo à partir d'un ficher. 
	 * 
	 * Renvoi NULL si le fichier source est invalide ou que la fonction getimagesize
	 * n'a pas fonctionnée (ce qui indique un fichier corrompu).
	 * 
	 * @param DFile $file Fichier source
	 * @return DFileImageInfo
	 */
	public static function getImageInfo(DFile $file) {

		if (!$file->isFile()) {
			return NULL;
		}

		$info = @getimagesize($file->getPath());
		if (!$info) return NULL;

		if (!isset($info['bits'])) {
			// Si on ne connait pas le nombre de bits on met 0
			$info['bits'] = 0;
		}

		if (!isset($info['channels'])) {
			// Si on ne connait pas le nombre de channels on met 0
			$info['channels'] = 0;
		}

		return new DFileImageInfo(
			$file,
			$info[0], // width
			$info[1], // height
			$info[2], // type
			$info['bits'],
			$info['channels']
		);
	}

	/**
	 * Afficher cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		//return "FileImageInfo [file:{$this->file}; width:{$this->width}; height:{$this->height}; type:" . DImageInfo::getImageTypeName($this->type) .
		//"; bits:{$this->bits}; channels:{$this->channels}]";
		return $this->width.'x'.$this->height.' ('.DImageInfo::getImageTypeName($this->type).' @ '.$this->file.')';
	}

}

?>