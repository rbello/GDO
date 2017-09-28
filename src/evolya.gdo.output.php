<?php

/**
 * Type énuméré représentant les differences méthodes de redimensionnement d'un image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
final class DImageResizeMethod implements Enum {

	/**
	 * @var DImageResizeMethod
	 */
	public static $RESAMPLED = NULL;

	/**
	 * @var DImageResizeMethod
	 */
	public static $RESIZED = NULL;

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
	 * Constructeur de l'enum DImageResizeMethod.
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
	 * @param DImageResizeMethod $other L'autre constante à tester.
	 * @return boolean
	 */
	public function equals(DImageResizeMethod $other) {
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
	 * @return DImageResizeMethod
	 */
	public static function valueOf($name) {
		if (!isset($name)) return NULL;
		if ($name == 'RESAMPLED') return self::$RESAMPLED;
		if ($name == 'RESIZED') return self::$RESIZED;
		return NULL;
	}

	/**
	 * Initialise les constantes de l'enum.
	 * @return void
	 */
	public static function init() {
		self::$RESAMPLED = new DImageResizeMethod('RESAMPLED', 0);
		self::$RESIZED = new DImageResizeMethod('RESIZED', 1);
	}
}

DImageResizeMethod::init();

/**
 * Les objets qui peuvent être exportés en temps qu'image peuvent implémenter cette interface.
 * Elle définit trois méthodes pour :
 * - Afficher l'image au navigateur du client (render)
 * - Enregistrer l'image dans un fichier (save)
 * - Envoyer l'image au navigateur du client en forcant le téléchargement (download)
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
interface DSupportOutput {

	/**
	 * Afficher l'image au navigateur du client (render).
	 * 
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function render(DOutputConfig $config=NULL);

	/**
	 * Enregistrer l'image dans un fichier (save).
	 * 
	 * @param DFile $target Le fichier cible.
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function save(DFile $target, DOutputConfig $config=NULL);

	/**
	 * Envoyer l'image au navigateur du client en forcant le téléchargement (download).
	 * 
	 * @param string $filename Le nom du fichier tel que reçu au client.
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function download($filename, DOutputConfig $config=NULL);

}

/**
 * Interface définissant les méthodes d'une configuration d'export d'image. Cet objet
 * permet d'indiquer quel sera le format d'image (JPEG, GIF...) de l'image exportée,
 * ainsi que la méthode de redimensionnement.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
interface DOutputConfig {

	/**
	 * Renvoi le type d'image sous forme d'entier.
	 * 
	 * @return int
	 * @see DImageInfo::$imagesTypes
	 */
	public function getType();

	/**
	 * Renvoi l'extension de fichier correspondant au type d'image.
	 * 
	 * @return string
	 */
	public function getFileExtension();

	/**
	 * Renvoi le content-type de ce type d'image.
	 * 
	 * @return string
	 */
	public function getContentType();

}


/**
 * Classe abstraite implémentant les méthodes de base d'un DOutputConfigInterface.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
abstract class DAbstractOutputConfig implements DOutputConfig, Finalizable {

	/**
	 * Le type d'image.
	 * @see DImageInfo::$imagesTypes
	 * @var int
	 */
	protected $type;

	/**
	 * Indique si cet objet a été finalisé.
	 * @var boolean
	 * @see Finalizable
	 */
	protected $finalized = FALSE;

	/**
	 * Entrelacement.
	 * @var boolean
	 * @link http://fr.wikipedia.org/wiki/Entrelacement_(image_matricielle)
	 */
	protected $interlace = FALSE;

	/**
	 * Constructeur de la classe DAbstractOutputConfig.
	 * 
	 * @param int $type Type d'image.
	 * @see DImageInfo::$imagesTypes
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type requis.
	 * @throws IllegalArgumentException Si le type d'image n'est pas supporté.
	 */
	public function __construct($type) {
		$this->setType($type);
	}

	/**
	 * Modifie le type d'image.
	 * 
	 * @param int $type Le type d'image.
	 * @return void
	 * @see DImageInfo::$imagesTypes
	 * @throws MissingArgumentException Si l'argument $type est manquant.
	 * @throws BadArgumentTypeException Si l'argument $type n'est pas un entier.
	 * @throws IllegalArgumentException Si le type d'image n'est pas supporté.
	 * @throws FinalizedObjectException Si cet objet est finalisé.
	 */
	protected function setType($type) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		$type = @PHPHelper::checkArgument('$type', $type, 'int', TRUE, TRUE);
		if ($type != 1 && $type != 2 && $type != 3) {
			throw new IllegalArgumentException('bad image type');
		}
		$this->type = $type;
	}

	/**
	 * Renvoi le type d'image sous forme d'entier.
	 * 
	 * @return int
	 * @see DImageInfo::$imagesTypes
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Modifie le status d'entrelacement.
	 * 
	 * @param boolean $value
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un boolean.
	 * @throws FinalizedObjectException Si cet objet est finalisé.
	 * @link http://fr.wikipedia.org/wiki/Entrelacement_(image_matricielle)
	 */
	public function setInterlace($value) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		$value = @PHPHelper::checkArgument('$value', $value, 'boolean');
		$this->interlace = $value;
	}

	/**
	 * Renvoi le status de l'entrelacement.
	 * 
	 * @return boolean
	 * @link http://fr.wikipedia.org/wiki/Entrelacement_(image_matricielle)
	 */
	public function isInterlace() {
		return $this->interlace;
	}

	/**
	 * (non-PHPdoc)
	 * @see Finalizable#finalize()
	 */
	public function finalize() {
		$this->finalized = TRUE;
	}

	/**
	 * (non-PHPdoc)
	 * @see Finalizable#isObjectFinalized()
	 */
	public function isObjectFinalized() {
		return $this->finalized;
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return 'OutputConfig '.DImageInfo::getImageTypeName($this->type).' '
			.$this->method.($this->interlace ? ' interlace' : '');
	}

}

/**
 * Configuration de sortie d'image au format JPEG.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
class DJPEGOutputConfig extends DAbstractOutputConfig {

	/**
	 * Indique la qualité de compression du format JPEG. La valeur est
	 * comprise entre 0 (compression maximum) et 100 (moindre compression).
	 * @var int
	 */
	protected $quality = 100;

	/**
	 * Constructeur de la classe DJPEGOutputConfig.
	 * 
	 * @param int $quality Valeur de qualité, entre 0 (compression maximum) et 100 (moindre compression).
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type requis.
	 * @throws IllegalArgumentException Si le type d'image n'est pas supporté ou si la qualité d'image
	 * 	est invalide.
	 */
	public function __construct($quality=100) {
		parent::__construct(2);
		$this->setQuality($quality);
	}

	/**
	 * Changer la qualité de compression de la sortie JPEG. La valeur est
	 * comprise entre 0 (compression maximum) et 100 (moindre compression).
	 * 
	 * @param int $quality
	 * @return void
	 * @throws MissingArgumentException Si l'argument $quality est manquant.
	 * @throws IllegalArgumentException Si la qualité n'a pas une valeur valide.
	 * @throws FinalizedObjectException Si l'objet est finalisé.
	 * 
	 */
	public function setQuality($quality) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		if (!isset($quality)) {
			throw new MissingArgumentException('$quality', 'int');
		}
		if (!is_int($quality) || $quality < 1 || $quality > 100) {
			throw new IllegalArgumentException('quality must be integer 1<=value<=100');
		}
		$this->quality = $quality;
	}

	/**
	 * Renvoi la qualité de compression de la sortie JPEG.
	 * 
	 * @return itn
	 */
	public function getQuality() {
		return $this->quality;
	}

	/**
	 * Renvoi l'extension de fichier correspondant au type d'image.
	 * 
	 * @return string
	 */
	public function getFileExtension() {
		return 'jpg';
	}

	/**
	 * Renvoi le content-type de ce type d'image.
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'image/jpeg';
	}

}

/**
 * Alias de DJPEGOutputConfig. 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 * @see DJPEGOutputConfig
 */
class DJPG extends DJPEGOutputConfig {
}

/**
 * Configuration de sortie d'image au format PNG.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
class DPNGOutputConfig extends DAbstractOutputConfig {

	/**
	 * Indicateur de l'état du canal alpha (transparence).
	 * Si TRUE, la transparence sera gérée par l'image.
	 * @var boolean
	 */
	protected $alpha = TRUE;

	/**
	 * Constructeur de la classe DPNGOutputConfig.
	 * 
	 * @param boolean $alpha Activer le canal alpha (transparence).
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type requis.
	 * @throws IllegalArgumentException Si le type d'image n'est pas supporté ou si le paramètre
	 * 	$alpha n'a pas une valeur acceptable.
	 */
	public function __construct($alpha=TRUE) {
		parent::__construct(3);
		$this->setAlpha($alpha);
	}

	/**
	 * Modifie le paramètre d'activation du canal alpha (transparence).
	 * Si TRUE, la transparence sera gérée par l'image.
	 * 
	 * @param boolean $value Activation du canal alpha.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un boolean.
	 * @throws FinalizedObjectException Si l'objet est finalisé.
	 */
	public function setAlpha($value) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		if (!isset($value)) {
			throw new MissingArgumentException('$value', 'boolean');
		}
		if (!is_bool($value)) {
			throw new BadArgumentTypeException('$value', $value, 'boolean');
		}
		$this->alpha = $value;
	}

	/**
	 * Renvoi le paramètre d'activation du canal alpha (transparence).
	 * Si TRUE, la transparence sera gérée par l'image.
	 * 
	 * @return boolean
	 */
	public function isAlpha() {
		return $this->alpha;
	}

	/**
	 * Renvoi l'extension de fichier correspondant au type d'image.
	 * 
	 * @return string
	 */
	public function getFileExtension() {
		return 'png';
	}

	/**
	 * Renvoi le content-type de ce type d'image.
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'image/png';
	}

}

/**
 * Alias de DPNGOutputConfig. 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 * @see GPNGOutputConfig
 */
class DPNG extends DPNGOutputConfig {
}

/**
 * Configuration de sortie d'image au format GIF.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
class DGIFOutputConfig extends DAbstractOutputConfig {

	/**
	 * Tramage.
	 * @var boolean
	 * @see http://fr.wikipedia.org/wiki/Tramage_(informatique)
	 */
	private $dither = TRUE;

	/**
	 * Nombre de couleur de la palette.
	 * @var int
	 */
	private $colornbr = 256;

	/**
	 * Constructeur de la classe DGIFOutputConfig.
	 * 
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type requis.
	 * @throws IllegalArgumentException Si le type d'image n'est pas supporté.
	 */
	public function __construct() {
		parent::__construct(1);
	}

	/**
	 * Modifier le status du tramage.
	 * 
	 * Pour rappel, le tramage est une méthode qui conciste à simuler la présence de multiples
	 * couleurs en juxtaposant des pixels de certaines couleurs, qui vont donner l'illusion
	 * d'un mélance de couleur.
	 * 
	 * @param boolean $value La valeur de tramage.
	 * @return void
	 * @throws BadArgumentTypeException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si l'argument $value n'est pas un boolean.
	 * @throws FinalizedObjectException Si l'object est finalisé.
	 */
	public function setDitherEnabled($value) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		$value = @PHPHelper::checkArgument('$value', $value, 'boolean');
		$this->dither = $value;
	}

	/**
	 * Retourne la valeur du tramage.
	 * 
	 * @return boolean
	 * @see DGIFOutputConfig#setDitherEnabled()
	 */
	public function isDitherEnabled() {
		return $this->dither;
	}

	/**
	 * Limiter le nombre de couleur de la palette.
	 * 
	 * @param int $value Le nombre de couleur de la palette.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws IllegalArgumentException Si l'argument $value est < à 1
	 * @throws FinalizedObjectException Si l'object est finalisé.
	 * @todo Faut-il limiter à 256 couleurs ?
	 */
	public function setPaletteColorNumber($value) {
		if ($this->isObjectFinalized()) {
			throw new FinalizedObjectException();
		}
		if (!isset($value)) {
			throw new MissingArgumentException('$value', 'int');
		}
		if (!is_int($value)) {
			throw new BadArgumentTypeException('$value', $value, 'int');
		}
		if ($value < 1) {
			throw new IllegalArgumentException('invalid argument, must be integer > 0');
		}
		$this->colornbr = $value;
	}

	/**
	 * Renvoi le nombre de couleurs présentes de la palette.
	 * 
	 * @return int
	 */
	public function getPaletteColorNumber() {
		return $this->colornbr;
	}

	/**
	 * Renvoi l'extension de fichier correspondant au type d'image.
	 * 
	 * @return string
	 */
	public function getFileExtension() {
		return 'gif';
	}

	/**
	 * Renvoi le content-type de ce type d'image.
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'image/gif';
	}

}

/**
 * Alias de DGIFOutputConfig. 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 * @see DGIFOutputConfig
 */
class DGIF extends DGIFOutputConfig {
}

/**
 * Classe utilitaire permettant de faire des sorties d'image (render, save, download).
 * 
 * Cette classe permet de regrouper ici tous le processus qui permet de générer une image
 * et de l'enregistrer ou de l'afficher.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.output
 */
final class DOutputHelper {

	/**
	 * Configuration PNG par défaut.
	 * @var DPNG
	 */
	public static $PNG;

	/**
	 * Configuration GIF par défaut.
	 * @var DGIF
	 */
	public static $GIF;

	/**
	 * Configuration JPEG par défaut.
	 * @var DJPEG
	 */
	public static $JPEG_FULL;

	/**
	 * Renvoi la configuration la plus adaptée par rapport à une extension de fichier.
	 * <br>Renvoi NULL si l'extension ne corresponds à aucune configuration.
	 * 
	 * @param string $ext
	 * @return DOutputConfig
	 */
	public static function getBestConfigurationByFileExtension($ext) {
		if (!isset($ext)) return NULL;
		$ext = strtolower($ext);
		$ext = str_replace('.', '', $ext);
		if ($ext == self::$PNG->getFileExtension()) {
			return self::$PNG;
		}
		if ($ext == self::$GIF->getFileExtension()) {
			return self::$GIF;
		}
		if ($ext == self::$JPEG_FULL->getFileExtension()) {
			return self::$JPEG_FULL;
		}
		return NULL;
	}

	/**
	 * TODO
	 * 
	 * @param $image
	 * @param $config
	 * @return unknown_type
	 */
	public static function render(DImageInterface $image, DOutputConfig $config=NULL) {
		return @self::output(
			$image,
			$config,
			NULL,
			FALSE
		);
	}

	/**
	 * TODO
	 * 
	 * @param DImageInterface $image
	 * @param DFile $target
	 * @param DOutputConfig $config
	 * @return unknown_type
	 */
	public static function save(DImageInterface $image, DFile $target, DOutputConfig $config=NULL) {
		return @self::output(
			$image,
			$config,
			$target,
			FALSE
		);
	}

	/**
	 * TODO
	 * 
	 * @param DImageInterface $image
	 * @param DFile $file
	 * @param DOutputConfig $config
	 * @return unknown_type
	 */
	public static function download(DImageInterface $image, DFile $file, DOutputConfig $config=NULL) {
		return @self::output(
			$image,
			$config,
			$file,
			TRUE
		);
	}

	/**
	 * TODO
	 * 
	 * @param DImageInterface $image
	 * @param DOutputConfig $config
	 * @param DFile $file
	 * @param unknown_type $download
	 * @return unknown_type
	 */
	protected static function output(DImageInterface $image, DOutputConfig $config=NULL, DFile $file=NULL, $download=FALSE) {

		$image = @PHPHelper::checkArgument('$image', $image, 'DImageInterface');
		$config = @PHPHelper::checkArgument('$config', $config, 'DOutputConfig', FALSE);
		$file = @PHPHelper::checkArgument('$file', $file, 'DFile', FALSE);
		$download = @PHPHelper::checkArgument('$download', $download, 'boolean');

		if (!$image->isBound()) {
			throw new ImageNotBoundException();
		}

		if ($config == NULL) {
			$config = self::$PNG;
		}

		if ($image instanceof DImageInterface) {
			if (class_exists('DGIFEncoder')) {
				if ($image->getFrameCount() > 1 && $config->getType() == 1) {
					return self::outputAnimatedGIF(
						$image,
						$config,
						$file,
						$download
					);
				}
			}
		}

		return self::outputResource(
			$image,
			$config,
			$file,
			$download
		);
	}

	// throw IllegalArgumentException si les arguments sont invalides ou null
	// throw ImageNotBoundException si la resource n'est pas liée
	// throw HeadersSentException si les headers ont déja été envoyés alors qu'il est neccessaire d'en envoyer
	/**
	 * Méthode globale pour sortir une image avec une simple frame. En fonction des paramètres,
	 * cette méthode peut servir à afficher l'image au navigateur (render), enregistrer l'image
	 * sur le disque (save) ou forcer le téléchargement du fichier image (download).
	 * 
	 * <code>
	 *  outputResource(DImageInterface $image, DOutputConfig $config, NULL, FALSE) // Render
	 *  outputResource(DImageInterface $image, DOutputConfig $config, DFile $file, TRUE) // Download
	 *  outputResource(DImageInterface $image, DOutputConfig $config, DFile $file, FALSE) // Save
	 * </code>
	 * 
	 * @param DResource $image La ressource à afficher.
	 * @param DOutputConfig $config La configuration de sortie de l'image.
	 * @param DFile $file Le fichier de sortie, en cas de download ou de save.
	 * @param boolean $download Indique si le fichier doit être enregistré.
	 * TODO revoir la verification des arguments
	 * TODO throws
	 * @return boolean
	 */
	protected static function outputResource(DImageInterface $image, DOutputConfig $config, DFile $file=NULL, $download=FALSE) {

		// Check arguments
		if ($image == NULL || $config == NULL) {
			throw new IllegalArgumentException('$image or $config is NULL');
		}
		if (!$image->isBound()) {
			throw new ImageNotBoundException();
		}
		if (!is_bool($download)) {
			throw new IllegalArgumentException('$download must be a boolean');
		}
		if ($file !== NULL && !($file instanceof DFile)) {
			throw new IllegalArgumentException('$file must be a DFile');
		}

		@imageinterlace($image->getGDResource(), $config->isInterlace() ? 1 : 0);

		// Special actions for download
		if ($download) {
			if ($file == NULL) {
				throw new IllegalArgumentException('$file required to use download');
			}
			
			if (headers_sent()) {
				throw new HeadersSentException('unable to force download');
			}

			@header('Content-disposition: attachment; filename="'.addslashes($file->getName()).'"');
			@header('Content-Type: application/force-download');

			switch ($config->getType()) {
				case 1 :
					@header('Content-Transfer-Encoding: image/gif');
					break;
				case 2 :
					@header('Content-Transfer-Encoding: image/jpeg');
					break;
				case 3 :
					@header('Content-Transfer-Encoding: image/png');
					break;
			}

			#header("Content-Length: ".filesize($filepath);
			@header('Pragma: no-cache');
			@header('Cache-Control: must-revalidate, post-check=0, pre-check=0, public');
			@header('Expires: 0');

			// Pour eviter de confondre avec un save
			$file = NULL;
		}

		// If headers are sent it's not possible to render the picture
		if ($file == NULL && !$download && headers_sent()) {
			throw new HeadersSentException('unable to render picture');
		}

		// Send a content type if picture is renderer
		if ($file == NULL && !$download) {

			$contentType = NULL;

			switch ($config->getType()) {
				case 1 :
					$contentType = 'image/gif';
					break;
				case 2 :
					$contentType = 'image/jpeg';
					break;
				case 3 :
					$contentType = 'image/png';
					break;
			}

			@header("Content-type: $contentType");
		}

		// Stop all output buffer if picture is renderer or downloaded
		if ($file == NULL || $download) {
			self::flushAllOutputBuffers();
		}

		if ($file != NULL) {
			$file = $file->getPath();
		}

		// Generate picture
		$result = FALSE;
		switch ($config->getType()) {
			case 1 :
				@imagetruecolortopalette(
					$image->getGDResource(),
					$config->isDitherEnabled(),
					$config->getPaletteColorNumber()
				);
				if ($file == NULL) {
					$result = @imagegif($image->getGDResource());
				}
				else {
					$result = @imagegif($image->getGDResource(), $file);
				}
				break;
			case 2 :
				$result = @imagejpeg($image->getGDResource(), $file, $config->getQuality());
				break;
			case 3 :
				@imagealphablending($image->getGDResource(), FALSE);
				@imagesavealpha($image->getGDResource(), $config->isAlpha());
				if ($file == NULL) {
					$result = @imagepng($image->getGDResource());
				}
				else {
					$result = @imagepng($image->getGDResource(), $file);
				}
				break;
		}

		// Return result
		return $result;
	}

	/**
	 * TODO
	 * 
	 * @param DImageInterface $img
	 * @param DOutputConfig $config
	 * @param DFile $file
	 * @param unknown_type $download
	 * @return unknown_type
	 */
	protected static function outputAnimatedGIF(DImageInterface $img, DOutputConfig $config, DFile $file = NULL, $download = FALSE) {

		// Check arguments
		if (!isset($img) || !isset($config)) {
			throw new IllegalArgumentException('argument missing');
		}
		if (!$img->isBound()) {
			throw new PictureNotBound();
		}
		if (!is_bool($download)) {
			throw new IllegalArgumentException('$download must be a boolean');
		}
		if ($file !== NULL && !($file instanceof DFile)) {
			throw new IllegalArgumentException('$file must be a DFile');
		}
		if (!($img->getImageFrameContainer() instanceof DGIFDecoder)) {
			throw new IllegalArgumentException('method _OutputHelper_::outputAnimatedGIF only supports DGIFDecoder');
		}

		// Special actions for download
		if ($download) {

			if ($file == NULL) {
				throw new IllegalArgumentException('$file required to use download');
			}

			if (headers_sent()) {
				throw new HeadersSentException('unable to force download');
			}

			@header('Content-disposition: attachment; filename='.$file->getName());
			@header('Content-Type: application/force-download');
			@header('Content-Transfer-Encoding: image/gif');
			@header('Pragma: no-cache');
			@header('Cache-Control: must-revalidate, post-check=0, pre-check=0, public');
			@header('Expires: 0');

			// Pour eviter de confondre avec un save
			$file = NULL;
		}

		// If headers are sent it's not possible to render the picture
		if ($file == NULL && !$download && headers_sent()) {
			throw new HeadersSentException('unable to render picture');
		}

		$gifEncoder = new DGIFEncoder(
			$img->getImageFrameContainer()->getFrames(),
			$img->getImageFrameContainer()->getTransparentColor(),
			$img->getImageFrameContainer()->getLoops()
		);

		if ($file == NULL && !$download) {
			@header('Content-type: image/gif');
		}

		// Stop all output buffer if picture is renderer or downloaded
		if ($file == NULL || $download) {
			self::flushAllOutputBuffers();
		}

		if ($file == NULL) {
			echo $gifEncoder->getContents();
		}
		else {
			if (!$file->isFile()) {
				if (!$file->createNewFile()) return FALSE;
			}
			return $file->putContents($gifEncoder->getContents());
		}

		return TRUE;

	}

	/**
	 * TODO est-ce que cette boucle va bien vider les nested ob dans le bon ordre ?...
	 * Là on risque de se retrouver avec des contenus répétés car les buffers de haut
	 * niveau ont un contenu qui doit se retrouver dans les buffers de bas niveaux...
	 * TODO A tester
	 */
	public static function flushAllOutputBuffers() {
		if (ob_get_level() > 0) {
			$i = 0;
			while (sizeof(ob_list_handlers()) > 0) {
				@ob_end_flush();
				$i++;
				if ($i > 100) return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}
	
}

DOutputHelper::$PNG = new DPNG();
DOutputHelper::$PNG->finalize();

DOutputHelper::$GIF = new DGIF();
DOutputHelper::$GIF->finalize();

DOutputHelper::$JPEG_FULL = new DJPG(100);
DOutputHelper::$JPEG_FULL->finalize();

?>