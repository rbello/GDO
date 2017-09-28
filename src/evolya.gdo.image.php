<?php

/**
 * La classe DImage corresponds à une image, chargée depuis un fichier ou à partir de dimensions.
 * Pour plus de détails, voyez le constructeur de la clase.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.image
 */
class DImage implements DImageInterface, DSupportShape, DSupportFilters {

	/**
	 * Active ou non le contrôle du dépassement mémoire, et indique si des exceptions
	 * doivent être levées.
	 * @var boolean
	 */
	public static $throwExceptionForMemoryExhausted = TRUE;

	/**
	 * Compteur servant à numéroter chaque image.
	 * 
	 * @var int
	 */
	protected static $imageCount = 0;

	/**
	 * La ressource GD.
	 * @var gd_resource
	 */
	protected $res = NULL;

	/**
	 * Les informations sur l'image.
	 * @var DImageInfoInterface
	 */
	protected $info = NULL;

	/**
	 * Largeur de l'image.
	 * @var int
	 */
	protected $w = -1;

	/**
	 * Hauteur de l'image
	 * @var int
	 */
	protected $h = -1;

	/**
	 * Identifiant de l'image.
	 * 
	 * @var int
	 */
	protected $id = -1;

	/**
	 * Contient le conteneur de frames.
	 * @var DImageFrameContainer
	 */
	protected $frameContainer = NULL;

	/**
	 * Méthode utilisée pour le redimensionnement de l'image.
	 * @var DImageResizeMethod
	 */
	protected $resizeMethod = NULL;

	/**
	 * Contient les filtres appliqués à l'image.
	 * @var array&lt;DFilter&gt;
	 */
	protected $filters = array();

	/**
	 * Constructeur de la classe DImage.
	 * 
	 * @construct DImage(int $w, int $h)
	 *  Construit une image de dimension données avec fond transparent.
	 *  
	 * @construct DImage(resource_gd $res)
	 *  Construit une image à partir d'une resource gd déja crée.
	 *  
	 * @construct DImage(DImageInterface $image)
	 *  Copie l'image $image dans une nouvelle image.
	 *  
	 * @construct DImage(DFile $path)
	 *  Construit une image à partir d'un fichier.
	 *  
	 * @construct DImage(string $path)
	 *   Construit une image à partir d'un chemin de fichier.
	 *   
	 * @construct DImage(DResizable $dim)
	 *  Construit une image de dimension données avec fond transparent.
	 *  
	 * @construct DImage(DResizable $dim, DColorInterface $background)
	 *  Construit une image de dimension données avec fond de couleur $background.
	 *  
	 * @construct DImage(int $w, int $h, DColorInterface $background)
	 *  Construit une image de dimension données avec fond de couleur $background.
	 * 
	 * @param mixed $arg0
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @throws BadArgumentTypeException Si un des arguments n'a pas le type requis.
	 * @throws InvalidArgumentException Si un des arguments n'a pas l'état voulu.
	 * @throws MemoryException Si les dimensions de la ressource excédent les capacités mémoire.
	 * @throws UnsupportedOperationException Si la fonction imagecreatetruecolor est incapable de
	 * 	créer la ressource ou si le type d'image n'est pas supporté par GD ou GDO.
	 * @throws InvalidDimensionException Si les dimensions de l'image à créer sont invalides.
	 * @throws FileNotFoundException Si le fichier donné est introuvable ou ne pointe pas vers un fichier.
	 * @throws InvalidDataException Si le fichier n'est pas une image valide.
	 * @throws ImageCreateException Si la création de l'image a provoqué une erreur.
	 */
	public function __construct($arg0, $arg1=NULL, $arg2=NULL) {

		if (!isset($arg0)) {
			throw new MissingArgumentException('$arg0', 'mixed');
		}

		if (is_float($arg0)) $arg0 = intval($arg0);
		if (is_float($arg1)) $arg1 = intval($arg1);
		if (is_string($arg0)) $arg0 = new DFile($arg0);

		if (is_int($arg0) && is_int($arg1)) {
			if ($arg2 instanceof DColorInterface) {
				$this->construct_dimensions($arg0, $arg1, $arg2);
			}
			else {
				$this->construct_dimensions($arg0, $arg1);
			}
		}

		else if (is_resource($arg0)) {
			$this->construct_gdresource($arg0);
		}

		else if ($arg0 instanceof DImageInterface) {
			$this->construct_image($arg0);
		}

		else if ($arg0 instanceof DFile) {
			$this->construct_file($arg0);
		}

		else if ($arg0 instanceof DResizable) {
			if ($arg1 instanceof DColorInterface) {
				$this->construct_dimensions(
					$arg0->getWidth(),
					$arg0->getHeight(),
					$arg1
				);
			}
			else {
				$this->construct_dimensions(
					$arg0->getWidth(),
					$arg0->getHeight()
				);
			}
		}

		else {
			throw new IllegalArgumentException();
		}

		// On donne un identifiant unique à cette image.
		$this->id = self::$imageCount++;

		// On met une méthode de redimensionnement par défaut.
		$this->resizeMethod = DImageResizeMethod::$RESAMPLED;

		// Et on enregistre cet instance auprès du gestionnaire de mémoire.
		gdo_register_resource($this);
	}

	/**
	 * Délégué du constructeur : DFile $source
	 * 
	 * Permet de construire l'objet DImage en chargeant un fichier image.
	 * 
	 * @param DFile $source Fichier source.
	 * @return void
	 * @throws FileNotFoundException Si le fichier donné est introuvable ou ne pointe pas vers un fichier.
	 * @throws InvalidDataException Si le fichier n'est pas une image valide.
	 * @throws UnsupportedOperationException Si le type d'image n'est pas supporté par GD ou GDO.
	 * @throws MemoryException Si l'opération demande plus de mémoire que celle disponible.
	 * @throws ImageCreateException Si la création de l'image a provoqué une erreur.
	 */
	protected function construct_file(DFile $source) {
		//if (!isset($source)) { Si on arrive là on a obligatoirement un argument
		//	throw new MissingArgumentException('$source', 'DFile');
		//}
		if (!$source->isFile()) {
			throw new FileNotFoundException($source);
		}

		$info = DFileImageInfo::getImageInfo($source);
		if (!($info instanceof DFileImageInfo)) {
			throw new InvalidDataException('invalid image file');
		}

		if (!imagetypes() & $info->getType()) {
			throw new UnsupportedOperationException('image type not supported by GD');
		}

		if (self::$throwExceptionForMemoryExhausted) {
			$channels = $info->getChannels() > 0 ? $info->getChannels() : 3;
			// On calcule si on va avoir assez de mémoire pour réaliser cette opération
			if (!DMemoryManager::canCreateResource($info->getWidth(), $info->getHeight(),
				$channels, $info->getBits())) {
				throw new MemoryException('Not enough memory to load picture');
			}
		}

		$errorRecorder = new DErrorRecorder();

		switch ($info->getType()) {
			case 1 :
				if (imagetypes() & IMG_GIF) {
					# Animated GIF support
					if (class_exists('DGIFDecoder')) {
						$gifDecoder = new DGIFDecoder(file_get_contents($source->getPath()), TRUE);
						# Animated GIF image
						if ($gifDecoder->getFrameCount() > 1) {
							$this->frameContainer = $gifDecoder;
							$frames = $gifDecoder->getFrames();
							$im = imagecreatefromstring($frames[0]->getContents());
							unset($frames);
						}
						# Non-animated GIF image
						else {
							$im = imagecreatefromgif($source->getPath());
						}
					}
					else {
						$im = imagecreatefromgif($source->getPath());
					}
				} else {
					throw new UnsupportedOperationException('GIF image type not supported by GD');
				}
				break;
			case 2 :
				if (imagetypes() & IMG_JPG) {
					$im = imagecreatefromjpeg($source->getPath());
				} else {
					throw new UnsupportedOperationException('JPEG image type not supported by GD');
				}
				break;
			case 3 :
				if (imagetypes() & IMG_PNG) {
					$im = imagecreatefrompng($source->getPath());
				} else {
					throw new UnsupportedOperationException('PNG image type not supported by GD');
				}
				break;
			default:
				throw new UnsupportedOperationException('image type not supported by GDO');
				return;
		}

		$errorRecorder->stop();
		$errorMsg = $errorRecorder->getContents(); 
		unset($errorRecorder);

		if (!$im || !is_resource($im) || !empty($errorMsg)) {
			throw new ImageCreateException(strip_tags($errorMsg));
		}

		$this->res =& $im;
		
		$this->info = $info;

		$this->w = $info->getWidth();
		$this->h = $info->getHeight();
		
	}

	/**
	 * Délégué du constructeur pour l'argument : DImageInterface $image
	 * 
	 * @param DImageInterface $image L'image à dupliquer.
	 * @return void
	 * @throws ImageNotBoundException Si l'image $image n'est pas liée.
	 * @throws ImageCreateException Si la création de l'image ou la copie a échouée.
	 * TODO Suivre les erreurs de $this->construct_dimensions
	 */
	protected function construct_image(DImageInterface $image) {
		if (!$image->isBound()) {
			throw new ImageNotBoundException();
		}

		// En premier on fabrique une nouvelle ressource avec les dimensions de $image
		$this->construct_dimensions(
			$image->getWidth(),
			$image->getHeight()
		);

		// On fait une copie de l'image
		$recorder = new DErrorRecorder();
		$r = imagecopy(
			$this->res,
			$image->getGDResource(),
			0,
			0,
			0,
			0,
			$image->getWidth(),
			$image->getHeight()
		);
		$recorder->stop();

		// En cas d'erreur, on lève des exceptions
		if (!$recorder->isEmpty()) {
			throw new ImageCreateException($recorder->getContents());
		}
		if (!$r) {
			throw new ImageCreateException('imagecopy returns false');
		}

		$this->w = $image->getWidth();
		$this->h = $image->getHeight();

		$this->info = new DImageInfo(
			$image->getWidth(),
			$image->getHeight()
		);
	}

	/**
	 * Délégué du constructeur pour l'argument : gd_resource $resource
	 * 
	 * @param gd_resource $resource La ressource GD à encapsuler dans l'objet.
	 * @return void
	 * @throws BadArgumentTypeException Si la ressource n'est pas une ressource gd valide.
	 */
	protected function construct_gdresource(&$resource) {

		if (get_resource_type($resource) != 'gd') {
			throw new BadArgumentTypeException('$resource', $resource, 'gd_resource');
		}

		$this->res = &$resource;

		$this->w = imagesx($resource);
		$this->h = imagesy($resource);

		$this->info = new DImageInfo(
			$this->w,
			$this->h
		);

	}

	/**
	 * Délégué du constructeur pour l'argument : int $width, int $height
	 * 
	 * @param int $width La largeur de l'image à créer
	 * @param int $height La hauteur de l'image à créer
	 * @param DColorInterface $bg La couleur de fond de l'image, ou NULL pour du transparent.
	 * @return void
	 * @throws InvalidWidthException Si la largeur est inférieure à 1
	 * @throws InvalidHeightException Si la hauteur est inférieure à 1
	 * @throws MemoryException Si les dimensions de la ressource excédent les capacités mémoire.
	 * @throws UnsupportedOperationException Si la fonction imagecreatetruecolor est incapable de
	 * 	créer la ressource.
	 * @throws InvalidDimensionException Si les dimensions de l'image à créer sont invalides.
	 */
	protected function construct_dimensions($width, $height, DColorInterface $bg=NULL) {

		if ($width < 1) {
			throw new InvalidWidthException();
		}
		if ($height < 1) {
			throw new InvalidHeightException();
		}

		if (self::$throwExceptionForMemoryExhausted) {
			if (!DMemoryManager::canCreateResource($width, $height)) {
				throw new MemoryException('Not enough memory to create resource');
			}
		}

		$errorBool = FALSE;
		$errorRecorder = new DErrorRecorder();

		$im = imagecreatetruecolor($width, $height) or $errorBool = TRUE;

		$errorRecorder->stop();
		$errorMsg = $errorRecorder->getContents();
		$errorRecorder->dispose();

		if ($errorBool || !empty($errorMsg)) {
			if (strpos($errorMsg, 'Invalid image dimensions') != FALSE) {
				throw new InvalidDimensionException();
			}
			else if (strpos($errorMsg, 'Allowed memory size of') !== FALSE) {
				throw new MemoryException();
			}
			else throw new UnsupportedOperationException('imagecreatetruecolor returns error : '
				.strip_tags($errorMsg));
		}

		if (!isset($bg)) {
			$bg = DColor::$TRANSPARENT;
		}
		@imagefill($im, 0, 0, $bg->toGDColor());

		$this->res = &$im;

		$this->info = new DImageInfo(
			$width,
			$height
		);

		$this->w = $width;
		$this->h = $height;

		unset($errorBool, $errorRecorder, $errorMsg);
	}

	/**
	 * Renvoi la méthode de redimensionnement.
	 * 
	 * @return DImageResizeMethod
	 */
	public function getImageResizeMethod() {
		return $this->method;
	}

	/**
	 * Modifier la méthode de redimensionnement.
	 * 
	 * @param DImageResizeMethod $method La nouvelle méthode.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $method est manquant.
	 * @throws BadArgumentTypeException Si l'argument $method n'est pas un DImageResizeMethod.
	 * @throws FinalizedObjectException Si cet objet est finalisé.
	 */
	public function setImageResizeMethod(DImageResizeMethod $method) {
		$method = @PHPHelper::checkArgument('$method', $method, 'DImageResizeMethod');
		$this->resizeMethod = $method;
	}

	/**
	 * Modifier la largeur de l'image.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidWidthException Si le paramètre $value est < à 1
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function setWidth($value) {
		@$this->setDimensions($value, $this->h);
	}

	/**
	 * Modifier la largeur de l'image, en ajustant automatiquement la hauteur
	 * de l'objet pour garder les proportions de l'image.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidWidthException Si le paramètre $value est invalide.
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function setWidthRatio($value) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 1) {
			throw new InvalidWidthException($value);
		}
		$this->setDimensions(
			$value,
			(int)($this->h * $value / $this->w)
		);
	}

	/**
	 * Renvoi la largeur de l'image.
	 * 
	 * @return int
	 */
	public function getWidth() {
		if (!$this->isBound()) {
			return -1;
		}
		return $this->w;
	}

	/**
	 * Modifier la hauteur de l'image.
	 * 
	 * @param int $value Hauteur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si l'argument $value est < à 1
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function setHeight($value) {
		@$this->setDimensions($this->w, $value);
	}

	/**
	 * Modifier la hauteur de l'image, en ajustant automatiquement la largeur
	 * de l'image pour garder les proportions des dimentions.
	 * 
	 * @param int $value Largeur en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws InvalidHeightException Si le paramètre $value est invalide.
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function setHeightRatio($value) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		if ($value < 1) {
			throw new InvalidHeightException($value);
		}
		$this->setDimensions(
			(int)($this->w * $value / $this->h),
			$value
		);
	}

	/**
	 * Renvoi la hauteur de l'image.
	 * 
	 * @return int
	 */
	public function getHeight() {
		if (!$this->isBound()) {
			return -1;
		}
		return $this->h;
	}

	/**
	 * Modifier les dimensions de l'image.
	 * 
	 * @param DResizable $r La dimension.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si les dimensions sont invalides.
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function setDimension(DResizable $r) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$this->setDimensions(
			$r->getWidth(),
			$r->getHeight()
		);
	}

	/**
	 * Modifier les dimensions de l'image.
	 * 
	 * @param int $w Largeur de l'image.
	 * @param int $h Hauteur de l'image.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws IllegalArgumentException Si les dimensions sont invalides.
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 * @throws InvalidWidthException Si la largeur est < à 1.
	 * @throws InvalidHeightException Si la hauteur est < à 1.
	 * TODO Suivre exceptions du construct_dimensions
	 */
	public function setDimensions($w, $h) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$w = @PHPHelper::checkArgument('$w', $w, 'int', TRUE, TRUE);
		$h = @PHPHelper::checkArgument('$h', $h, 'int', TRUE, TRUE);
		if ($w < 1) {
			throw new InvalidWidthException($w);
		}
		if ($h < 1) {
			throw new InvalidHeightException($h);
		}
		if ($w == $this->w && $h == $this->h)  return;

		// Ok on commence. On prépare quelques variables et objets.
		$result = FALSE;
		$recorder = new DErrorRecorder();

		// On sauvegarde la ressource qui était utilisée jusqu'à présent.
		$copy =& $this->res;

		// On sauvegarde aussi les anciennes infos de l'image
		$info = $this->getInfos();

		// On appel le constructeur pour créer une nouvelle ressource.
		$this->construct_dimensions($w, $h);

		// Ensuite il suffit de copier l'ancienne image dans la nouvelle.
		if ($this->resizeMethod == DImageResizeMethod::$RESAMPLED) {
			$result = imagecopyresampled(
				$this->res,
				$copy,
				0, 0, 0, 0,
				$w, $h, $info->getWidth(), $info->getHeight()
			);
		}
		else {
			$result = imagecopyresized(
				$this->res,
				$copy,
				0, 0, 0, 0,
				$w, $h, $info->getWidth(), $info->getHeight()
			);
		}

		$recorder->stop();

		if (!$result || !$recorder->isEmpty()) {
			// En cas d'erreur, on remet les valeurs d'avant la modification
			$this->res =& $copy;
			$this->w = $info->getWidth();
			$this->h = $info->getHeight();
			$this->info = $info;
			throw new ImageCreateException($recorder->getContents());
		}
		$recorder->dispose();

		// On replace les anciennes informations qu'on met à jour.
		$this->info = $info;
		$info->setWidth($w);
		$info->setHeight($h);

		// On supprime l'ancienne ressource.
		@imagedestroy($copy);
	}

	/**
	 * Renvoi les dimensions de l'image.
	 * 
	 * @return DResizable
	 * @throws ImageNotBoundException Si l'image n'est pas liée.
	 */
	public function getDimension() {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		return new DRectangle($this->w, $this->h);
	}

	/**
	 * Effectue une multiplication scalaire des dimensions par $factor.
	 *
	 * @param float $factor
	 * @return void
	 * @throws MissingArgumentException Si l'argument $factor est absent ou NULL.
	 * @throws BadArgumentTypeException Si l'argument $factor n'est pas un flottant.
	 * @throws IllegalArgumentException Si l'argument $factor est <= à 0.
	 */
	public function scale($factor) {
		$factor = @PHPHelper::checkArgument('$factor', $factor, 'float', TRUE, TRUE);
		if ($factor <= 0) {
			throw new IllegalArgumentException();
		}
		$this->setDimensions(
			(int)($this->w * $factor),
			(int)($this->h * $factor)
		);
	}

	/**
	 * Indique si la ressource est bien liée. En cas de problème, la ressource GD associée
	 * peut ne pas être valide. Dans ce cas, l'objet n'est pas lié.
	 * <br>Ce test permet de savoir si la ressource GD contenue dans l'instance du
	 * DResourceInterface est bien valide et prête à travailler.
	 * 
	 * @return boolean
	 */
	public function isBound() {
		if (!isset($this->res)) return FALSE;
		if (gettype($this->res) != 'resource') return FALSE;
		return TRUE;
	}

	/**
	 * Detruit la ressource GD et libère la mémoire utilisée.
	 * 
	 * @return void
	 */
	public function destroy() {
		if ($this->isBound()) {
			if (!@imagedestroy($this->res)) { }
		}
		unset($this->res);
		$this->res = NULL;
		$this->w = -1;
		$this->h = -1;
		$this->info = NULL;
		gdo_unregister_resource($this);
	}

	/**
	 * Renvoi les informations sur l'image.
	 * 
	 * @return DImageInfoInterface
	 */
	public function getInfos() {
		if (!$this->isBound()) {
			return NULL;
		}
		return $this->info;
	}

	/**
	 * Renvoi la ressource GD utilisée par cette instance d'image.
	 * 
	 * @return gd_resource
	 */
	public function getGDResource() {
		if (!$this->isBound()) {
			//throw new ImageNotBoundException();
			return null;
		}
		return $this->res;
	}

	/**
	 * Afficher l'image au navigateur du client (render).
	 * 
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function render(DOutputConfig $config=NULL) {
		$this->applyFilters();
		DOutputHelper::render($this, $config);
	}

	/**
	 * Enregistrer l'image dans un fichier (save).
	 * 
	 * @param DFile $target Le fichier cible.
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function save(DFile $target, DOutputConfig $config=NULL) {
		$this->applyFilters();
		DOutputHelper::save($this, $target, $config);
	}

	/**
	 * Envoyer l'image au navigateur du client en forcant le téléchargement (download).
	 * 
	 * @param string $filename Le nom du fichier tel que reçu au client.
	 * @param DOutputConfig $config La configuration d'image.
	 * @return void
	 * TODO throws
	 */
	public function download($filename, DOutputConfig $config=NULL) {
		$this->applyFilters();
		DOutputHelper::download($this, new DFile($filename), $config);
	}

	/**
	 * Dessiner la forme $shape sur cet objet.
	 * 
	 * @param DShape $shape La forme à dessiner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $shape est manquant.
	 */
	public function drawShape(DShape $shape) {
		if (!$this->isBound()) {
			return new ImageNotBoundException();
		}
		if (!isset($shape)) {
			throw new MissingArgumentException('$shape', 'DShape');
		}
		$shape->draw($this);
	}

	/**
	 * Renvoi l'identifiant de la ressource GD qui est utilisée par
	 * cette instance.
	 * 
	 * @return int
	 */
	public function getResourceID() {
		if (!$this->isBound()) {
			return FALSE;
		}
		return intval(str_replace('Resource id #', '', print_r($this->res, TRUE)));
	}

	/**
	 * Renvoi le numéro de l'image.
	 * <br>Lors de sa création, chaque image se voit attribuer
	 * un identifiant.
	 * 
	 * @return int
	 */
	public function getImageID() {
		return $this->id;
	}

	/**
	 * Nombre de frames dans l'image. Les images GIF animées peuvent avoir plusieurs frames.
	 * 
	 * @return int
	 */
	public function getFrameCount() {
		if ($this->frameContainer != NULL) {
			return $this->frameContainer->getFrameCount();
		}
		else return 1;
	}

	/**
	 * Renvoi le conteneur des frames de l'image. Dans le cas où l'image GIF animée contiendrai
	 * plusieurs frames, le conteneur sera présent. Sinon, renvoi NULL.
	 *  
	 * @return DImageFrameContainer
	 */
	public function getImageFrameContainer() {
		return $this->frameContainer;
	}

	/**
	 * Renvoi la somme de contrôle (checksum) de cette image.
	 * 
	 * @return string
	 * @throws ImageNotBoundException Si la ressource n'est pas liée.
	 */
	public function getChecksum() {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$r = new DOutputBuffer();
		@imagepng($this->res);
		$r->stop();
		return md5($r->getContents());
	}

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
	public function setColorAt($x, $y, DColorInterface $color) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$x = @PHPHelper::checkArgument('$x', $x, 'int', TRUE, TRUE);
		$y = @PHPHelper::checkArgument('$y', $y, 'int', TRUE, TRUE);
		$color = @PHPHelper::checkArgument('$color', $color, 'DColorInterface');
		if (!@imagesetpixel(
				$this->res,
				$x,
				$y,
				$color->toGDColor()
			)) {
			throw new OutOfBoundsException();
		}
	}

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
	public function getColorAt($x, $y) {
		if (!$this->isBound()) {
			throw new ImageNotBoundException();
		}
		$x = @PHPHelper::checkArgument('$x', $x, 'int', TRUE, TRUE);
		$y = @PHPHelper::checkArgument('$y', $y, 'int', TRUE, TRUE);
		$rgb = @imagecolorat($this->res, $x, $y);
		if ($rgb === FALSE) {
			throw new OutOfBoundsException($x.'x'.$y);
		}
		return DColor::int2color($rgb);
	}

	/**
	 * Ajouter le filtre $filter.
	 * 
	 * @return int L'index du filtre.
	 * @throws MissingArgumentException Si l'argument $filter est manquant.
	 */
	public function addFilter(DFilter $filter) {
		$this->filters[] = $filter;
	}

	/**
	 * Retirer le filtre à l'index $index.
	 * 
	 * @param int $index Index du filtre à retirer.
	 * @throws MissingArgumentException Si l'argument $index est manquant.
	 * @throws BadArgumentTypeException Si l'argument $index n'est pas un entier.
	 * @return void
	 */
	public function removeFilter($index) {
		unset($this->filters[$index]);
	}

	/**
	 * Retourne un tableau contenant tous les filtres qui doivent être
	 * appliquer à l'image.
	 * 
	 * @return array&lt;DFilter&gt;
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * Supprimer tous les filtres.
	 * Cette méthode n'annule pas l'effet des filtres qui ont été appliqués,
	 * mais enleve tous les filtres à appliquer.
	 * 
	 * @return void
	 */
	public function removeFilters() {
		$this->filters = array();
	}

	/**
	 * Indique si la ressource a des filtres en attente à appliquer.
	 * 
	 * @return boolean
	 */
	public function haveFilters() {
		return sizeof($this->filters) > 0;
	}

	/**
	 * Applique tous les filtres, et vide la liste des filtres.
	 * 
	 * @return void
	 */
	public function applyFilters() {
		foreach ($this->filters as $filter) {
			$filter->apply($this);
		}
		$this->removeFilters();
	}

	/**
	 * Méthode appellée lorsqu'un clone de l'image est créé.
	 * <br>Cette méthode sert à modifier l'identifiant d'image
	 * qui doit modifié et non pas seulement dupliqué.
	 * 
	 * TODO Faut-il en plus enregistrer cet image auprès du gestionnaire de mémoire ?
	 * 
	 * @return void
	 */
	public function __clone() {
		$this->id = self::$imageCount++;
	}

	/**
	 * Afficher cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		if (!$this->isBound()) {
			return 'DImage '.$this->id.' [not bound]';
		}
		$r = 'DImage '.$this->id.' ['.$this->info;
		if ($this->getFrameCount() > 1) {
			$r .= ' frames:'.$this->getFrameCount();
		}
		$r .= ' #'.$this->getResourceID();
		$r .= ']';
		return $r;
	}
}

?>