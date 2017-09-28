<?php

/**
 * L'interface DSupportFilters peut être implémentée par les classes qui supportent l'application de filtres
 * graphiques (DFilter).
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
interface DSupportFilters {

	/**
	 * Ajouter le filtre $filter.
	 * 
	 * @return int L'index du filtre.
	 * @throws MissingArgumentException Si l'argument $filter est manquant.
	 */
	public function addFilter(DFilter $filter);

	/**
	 * Retirer le filtre à l'index $index.
	 * 
	 * @param int $index Index du filtre à retirer.
	 * @throws MissingArgumentException Si l'argument $index est manquant.
	 * @throws BadArgumentTypeException Si l'argument $index n'est pas un entier.
	 * @return void
	 */
	public function removeFilter($index);

	/**
	 * Retourne un tableau contenant tous les filtres qui doivent être
	 * appliquer à l'image.
	 * 
	 * @return array&lt;DFilter&gt;
	 */
	public function getFilters();

	/**
	 * Supprimer tous les filtres.
	 * Cette méthode n'annule pas l'effet des filtres qui ont été appliqués,
	 * mais enleve tous les filtres à appliquer.
	 * 
	 * @return void
	 */
	public function removeFilters();

	/**
	 * Indique si la ressource a des filtres en attente à appliquer.
	 * 
	 * @return boolean
	 */
	public function haveFilters();

}

/**
 * L'interface DFilter est implémentée par les classes qui peuvent appliquer des filtres graphiques
 * à une image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
interface DFilter {

	/**
	 * Appliquer le filtre à la ressource $res.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img);

}

/**
 * Cette classe abstraite apporte une méthode pour tester la ressource passée en argument
 * de la méthode DFilter::apply(). 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
abstract class DAbstractFilter implements DFilter {

	/**
	 * Tester la validité de l'image $img.
	 * 
	 * @param DImage $img La ressource à tester.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	protected function testImg(DImage $img) {
		$img = @PHPHelper::checkArgument('$img', $img, 'DImage');
		if (!$img->isBound()) {
			throw new ImageNotBoundException();
		}
	}

}

/**
 * Ce filtre permet de renverser toutes les couleurs de l'image,pour la rendre en négative. 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DNegativeFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DNegativeFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_NEGATE')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_NEGATE
		);
	}

}

/**
 * Ce filtre convertit l'image en niveaux de gris. Il s'agit d'une désaturation complète
 * qui transformes les nuances de couleur en niveaux de gris.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DGrayscaleFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DGrayscaleFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_GRAYSCALE')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est manquant.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_GRAYSCALE
		);
	}

}

/**
 * Ce filtre permet de modifier la luminosité de l'image.
 * Il accepte un paramètre entier compris entre -255 et 255 inclus qui correspond
 * à la luminosité à appliquer à l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DBrightnessFilter extends DAbstractFilter {

	/**
	 * La valeur de luminosité, compris entre -255 et 255 incluc.
	 * @var int
	 */
	protected $value;

	/**
	 * Constructeur de la classe DBrightnessFilter.
	 * 
	 * @param int $value Luminosité, compris entre -255 et 255 inclus.
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct($value=0) {
		if (!defined('IMG_FILTER_BRIGHTNESS')) {
			throw new UnsupportedFilterException();
		}
		$this->setValue($value);
	}

	/**
	 * Modifier la valeur de luminosité du filtre.
	 * 
	 * @param int $value Luminosité, compris entre -255 et 255 inclus.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value est en dehors du champ de valeurs admises.
	 */
	public function setValue($value) {
		@PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->value = $value;
	}

	/**
	 * Renvoi la valeur de luminosité du filtre.
	 * 
	 * @return int Valeur de luminosité, comprise entre -255 et 255 inclus.
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si la valeur de luminosité vaut 0, cette méthode ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->value == 0) {
			return TRUE;
		}
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_BRIGHTNESS,
			$this->value
		);
	}

}

/**
 * Ce filtre modifie le contraste de l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DContrastFilter extends DAbstractFilter {

	/**
	 * La valeur de contraste. Comprise entre 255 et -255 inclus.
	 * @var int
	 */
	protected $value;

	/**
	 * Constructeur de la classe DContrastFilter.
	 * 
	 * @param int $value Valeur comprise entre 255 et -255 inclus.
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct($value=0) {
		if (!defined('IMG_FILTER_CONTRAST')) {
			throw new UnsupportedFilterException();
		}
		$this->setValue($value);
	}

	/**
	 * Modifier la valeur de contraste du filtre.
	 * 
	 * @param int $value Contraste, compris entre -255 et 255 inclus.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value est en dehors du champ de valeurs admises.
	 */
	public function setValue($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->value = $value;
	}

	/**
	 * Renvoi la valeur de contraste de l'image.
	 * 
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si la valeur de contraste vaut 0, cette méthode ne fait rien du tout.
	 * Contrairement à la fonction imagefilter(IMG_FILTER_CONTRAST) de php/gd,
	 * cette méthode inverse la valeur (une valeur petite baisse le contraste,
	 * une valeur haute monte le contraste).
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->value == 0) {
			return TRUE;
		}
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_CONTRAST,
			-$this->value
		);
	}

}

/**
 * Ce filtre permet de modifier les tendances des couleurs.
 * Il est possible de rajouter de la couleur à l'image, ou bien de soustraire
 * une couleur par rapport aux autres.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DColorizeFilter extends DAbstractFilter {

	/**
	 * Valeur de rouge. Comprise entre -255 et 255.
	 * @var int
	 */
	protected $red = 0;

	/**
	 * Valeur de vert. Comprise entre -255 et 255.
	 * @var int
	 */
	protected $green = 0;

	/**
	 * Valeur de blue. Comprise entre -255 et 255.
	 * @var int
	 */
	protected $blue = 0;

	/**
	 * Valeur de alpha. Comprise entre 0 et 127.
	 * @var int
	 */
	protected $alpha = 0;

	/**
	 * Support de l'argument alpha.
	 * @var boolean
	 */
	protected $supportAlpha;

	/**
	 * Constructeur de la classe DColorizeFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_COLORIZE')) {
			throw new UnsupportedFilterException();
		}
		$this->supportAlpha = version_compare(PHP_VERSION, '5.3.0', '>=');
	}

	/**
	 * Renvoi TRUE si le canal alpha est supporté.
	 * PHP supporte le canal alpha depuis la version 5.2.5.
	 * 
	 * @return boolean
	 */
	public function isAlphaSupported() {
		return $this->supportAlpha;
	}

	/**
	 * Modifier la valeur du canal alpha.
	 * PHP supporte le canal alpha depuis la version 5.2.5.
	 * 0 signifie totalement opaque, tandis que 127 signifie totalement transparent.
	 * 
	 * @param int $value Valeur comprise entre 0 et 127 inclus.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 * @throws UnsupportedOperationException Si le canal alpha n'est pas supporté.
	 */
	public function setAlphaValue($value) {
		if (!$this->supportAlpha) {
			throw new UnsupportedOperationException();
		}
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < 0 || $value > 127) {
			throw new OutOfBoundsException('0 >= $value >= 127');
		}
		$this->alpha = $value;
	}

	/**
	 * Renvoi la valeur du canal alpha.
	 * PHP supporte le canal alpha depuis la version 5.2.5.
	 * 
	 * @return int Compris entre 0 et 127.
	 */
	public function getAlphaValue() {
		return $this->alpha;
	}

	/**
	 * Modifier la valeur de rouge.
	 * 
	 * @param int $value Valeur comprise entre -255 et 255.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function setRedValue($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->red = $value;
	}

	/**
	 * Renvoi la valeur de rouge.
	 * 
	 * @return int Compris entre -255 et 255.
	 */
	public function getRedValue() {
		return $this->red;
	}

	/**
	 * Modifier la valeur de vert.
	 * 
	 * @param int $value Valeur comprise entre -255 et 255.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function setGreenValue($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->green = $value;
	}

	/**
	 * Renvoi la valeur de vert.
	 * 
	 * @return int Compris entre -255 et 255.
	 */
	public function getGreenValue() {
		return $this->green;
	}

	/**
	 * Modifier la valeur de bleu.
	 * 
	 * @param int $value Valeur comprise entre -255 et 255.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function setBlueValue($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->blue = $value;
	}

	/**
	 * Renvoi la valeur de bleu.
	 * 
	 * @return int Compris entre -255 et 255.
	 */
	public function getBlueValue() {
		return $this->blue;
	}

	/**
	 * Modifier toutes les valeurs par celles de la couleur $color.
	 * Un objet DColorInterface ne peut contenir des valeurs négatives
	 * (rvb = méthode additive), donc cette méthode ne peut être utilisée
	 * que pour une addition de couleur. Pour une soustraction, il faut
	 * utiliser les trois méthodes qui permettent de spécifier la valeur
	 * de chaque couleur.
	 * 
	 * @param DColorInterface $color
	 * @return void
	 */
	public function setColor($color) {
		$color = @PHPHelper::checkArgument('$color', $color, 'DColorInterface');
		$this->red = $color->getRedValue();
		$this->blue = $color->getBlueValue();
		$this->green = $color->getGreenValue();
		$this->green = $color->getAlphaValue();
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si toutes les valeurs de couleur valent 0, ce filtre ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->red == 0 && $this->green == 0 && $this->blue == 0) {
			return TRUE;
		}
		if ($this->supportAlpha) {
			return @imagefilter(
				$img->getGDResource(FALSE),
				IMG_FILTER_COLORIZE,
				$this->red,
				$this->green,
				$this->blue,
				$this->alpha
			);
		}
		else {
			return @imagefilter(
				$img->getGDResource(FALSE),
				IMG_FILTER_COLORIZE,
				$this->red,
				$this->green,
				$this->blue
			);
		}
	}
}

/**
 * Ce filtre utilise la détection des bords pour les mettre en évidence dans l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DEdgeDetectFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DEdgeDetectFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_EDGEDETECT')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_EDGEDETECT
		);
	}

}

/**
 * Ce filtre permet de graver l'image en relief.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DEmbossFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DEmbossFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_EMBOSS')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_EMBOSS
		);
	}

}

/**
 * Ce filtre permet d'appliquer un flou gaussien sur l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 * @link http://en.wikipedia.org/wiki/Gaussian_blur
 */
class DGaussianBlurFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DGaussianBlurFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_GAUSSIAN_BLUR')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_GAUSSIAN_BLUR
		);
	}

}

/**
 * Ce filtre permet d'appliquer un flou sélectif sur l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DSelectiveBlurFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DSelectiveBlurFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_SELECTIVE_BLUR')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_SELECTIVE_BLUR
		);
	}

}

/**
 * Ce filtre permet d'appliquer un effet de bruit à l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */ 
class DMeanRemovalFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DMeanRemovalFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_MEAN_REMOVAL')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_MEAN_REMOVAL
		);
	}

}

/**
 * Ce filtre permet d'appliquer un lissage à l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DSmoothFilter extends DAbstractFilter {

	/**
	 * La valeur de lissage. Compris entre -255 et 255.
	 * @var int
	 */
	protected $value;

	/**
	 * Constructeur de la classe DSmoothFilter.
	 * 
	 * @param int $value Valeur de lissage. Compris entre -255 et 255.
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct($value=0) {
		if (!defined('IMG_FILTER_SMOOTH')) {
			throw new UnsupportedFilterException();
		}
		$this->setValue($value);
	}

	/**
	 * Modifier la valeur de lissage.
	 * 
	 * @param int $value Valeur comprise entre -255 et 255.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function setValue($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < -255 || $value > 255) {
			throw new OutOfBoundsException('-255 >= $value >= 255');
		}
		$this->value = $value;
	}

	/**
	 * Renvoi la valeur de lissage.
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si la valeur de lissage vaut 0 ce filtre ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->value == 0) {
			return TRUE;
		}
		return @imagefilter(
			$img->getGDResource(FALSE),
			IMG_FILTER_SMOOTH,
			$this->value
		);
	}

}

/**
 * Ce filtre permet de transformer une image en mode de couleur sépia.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 * @link http://fr.wikipedia.org/wiki/S%C3%A9pia
 */
class DSepiaFilter extends DAbstractFilter {

	/**
	 * Constructeur de la classe DSepiaFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_GRAYSCALE') || !defined('IMG_FILTER_COLORIZE')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return
			@imagefilter($img->getGDResource(FALSE), IMG_FILTER_GRAYSCALE)
			&&
			@imagefilter($img->getGDResource(FALSE), IMG_FILTER_COLORIZE, 90, 60, 40);
	}

}

/**
 * Ce filtre permet d'appliquer une matric de convolution à une image.
 * Une convolution est un traitement d'une matrice par une autre appelée matrice de convolution.
 * Une convolution est une combinaison de pixels de l’image d’entrée et de pixels environnants,
 * afin de produire une nouvelle image. Les convolutions permettent de réaliser une grande variété
 * d’opérations de retouche graphique (flou, détection des contours, netteté, estampage et
 * biseautage, etc.).
 *
 * Matrice de convolution 5x5 pour augmenter le contraste :
 * <pre>
 *  0  0  0  0  0
 *  0  0 -1  0  0
 *  0 -1  5 -1  0
 *  0  0 -1  0  0
 *  0  0  0  0  0
 * </pre>
 *
 * Matrice de convolution 5x5 pour appliquer du flou :
 * <pre>
 *  0  0  0  0  0
 *  0  1  1  1  0
 *  0  1  1  1  0
 *  0  1  1  1  0
 *  0  0  0  0  0
 * </pre>
 * 
 * Matrice de convolution 4x4 pour renforcer les bords :
 * <pre>
 *   0  0  0
 *  -1  1  0
 *   0  0  0
 * </pre>
 * 
 * Matrice de convolution 4x4 pour détecter les bords :
 * <pre>
 *   0  1  0
 *   1 -4  1
 *   0  1  0
 * </pre>
 * 
 * Matrice de convolution 4x4 pour appliquer une effet de relief (repoussage) :
 * <pre>
 *  -2 -1  0
 *  -1  1  1
 *   0  1  2
 * </pre>
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DConvolutionFilter extends DAbstractFilter {

	/**
	 * Matrice de convolution.
	 * @var array
	 */
	protected $matrix;

	/**
	 * Coeficient de division.
	 * @var int|float
	 */
	protected $div;

	/**
	 * Offset.
	 * @var int|float
	 */
	protected $offset;

	/**
	 * Constructeur de la classe DConvolutionFilter.
	 * 
	 * @param array $matrix Matrice de convolution.
	 * @param int|float $div Coeficient de division.
	 * @param int|float $offset Offset.
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct($matrix=array(), $div=0, $offset=0) {
		if (!function_exists('imageconvolution')) {
			throw new UnsupportedFilterException();
		}
		$this->setMatrix($matrix);
		$this->setDivisorCoeficient($div);
		$this->setOffset($offset);
	}

	/**
	 * Modifier la matrice de convolution.
	 * 
	 * @param array $matrix Matrice de convolution.
	 * @return void
	 */
	public function setMatrix($matrix) {
		if (!isset($matrix)) {
			throw new MissingArgumentException('$matrix', 'array');
		}
		if (!is_array($matrix)) {
			throw new BadArgumentTypeException('$matrix', $matrix, 'array');
		}
		$this->matrix = $matrix;
	}

	/**
	 * Renvoi la matric de convolution du filtre.
	 * 
	 * @return array
	 */
	public function getMatrix() {
		return $this->matrix;
	}

	/**
	 * Modifier le coeficient de division.
	 * 
	 * @param int|float $value Coeficient de division.
	 * @return void
	 */
	public function setDivisorCoeficient($value) {
		if (!isset($value)) {
			throw new MissingArgumentException('$value', 'int|float');
		}
		if (!is_int($value) && !is_float($value)) {
			throw new BadArgumentTypeException('$value', $value, 'int|float');
		}
		$this->div = (float) $value;
	}

	/**
	 * Renvoi le coeficient de division du filtre.
	 * 
	 * @return int|float
	 */
	public function getDivisorCoeficient() {
		return $this->div;
	}

	/**
	 * Modifier la valeur d'offset.
	 * 
	 * @param int|float $value Offset.
	 * @return void
	 */
	public function setOffset($value) {
		if (!isset($value)) {
			throw new MissingArgumentException('$value', 'int|float');
		}
		if (!is_int($value) && !is_float($value)) {
			throw new BadArgumentTypeException('$value', $value, 'int|float');
		}
		$this->offset = (float) $value;
	}

	/**
	 * Renvoi l'offset du filtre.
	 * 
	 * @return int|float
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * Appliquer les valeurs d'exemple n° 1 (emboss).
	 * 
	 * @return void
	 */
	public function setSampleValue1() {
		$this->matrix = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
		$this->div = 1;
		$this->offset = 127;
	}

	/**
	 * Appliquer les valeurs d'exemple n° 1 (floue gaussien).
	 * 
	 * @return void
	 */
	public function setSampleValue2() {
		$this->matrix = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
		$this->div = 16;
		$this->offset = 0;
	}

	/**
	 * Appliquer les valeurs d'exemple n° 1 (sharpening).
	 * 
	 * @return void
	 */
	public function setSampleValue3() {
		$this->matrix = array(-1,-1,-1,-1,16,-1,-1,-1,-1);
		$this->div = 8;
		$this->offset = 0;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si la matrice est NULL, ce filtre ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->matrix == NULL) {
			return TRUE;
		}
		return @imageconvolution(
			$img->getGDResource(FALSE),
			$this->matrix,
			$this->div,
			$this->offset
		);
	}

}

/**
 * Ce filtre permet de transformer l'image en bichromie en utilisant une couleur donnée.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DBichromieFilter extends DAbstractFilter {

	/**
	 * La couleur.
	 * @var DColorInterface
	 */
	protected $color = NULL;

	/**
	 * Constructeur de la classe DBichromieFilter.
	 * 
	 * @param $color La couleur à utiliser.
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct($color=NULL) {
		if (!defined('IMG_FILTER_GRAYSCALE') || !defined('IMG_FILTER_COLORIZE')) {
			throw new UnsupportedFilterException();
		}
		if (isset($color)) {
			$this->setColor($color);
		}
	}

	/**
	 * Modifier la couleur utilisée pour la bichromie.
	 * 
	 * @param DColorInterface $color La couleur à utiliser.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $color est omis.
	 * @throws BadArgumentTypeException Si l'argument $color n'est pas une DColorInterface.
	 */
	public function setColor(DColorInterface $color) {
		$color = @PHPHelper::checkArgument('$color', $color, 'DColorInterface');
		$this->color = $color;
	}

	/**
	 * Renvoi la couleur utilisée.
	 * 
	 * @return DColorInterface
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * Appliquer le filtre à la ressource $img. Si la couleur à appliquer
	 * est NULL, cette méthode ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		if ($this->color == NULL) {
			return TRUE;
		}
		return
			@imagefilter($img->getGDResource(), IMG_FILTER_GRAYSCALE)
			&&
			@imagefilter(
				$img->getGDResource(FALSE),
				IMG_FILTER_COLORIZE,
				$this->color->getRedValue(),
				$this->color->getGreenValue(),
				$this->color->getBlueValue()
			);
	}

}

/**
 * Ce filtre applique un effet de pixelisation à l'image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DPixelateFilter extends DAbstractFilter {

	/**
	 * Taille des blocs, en pixel.
	 * @var int
	 */
	protected $blockSize = 1;

	/**
	 * Activer la pixelisation avancée.
	 * @var boolean
	 */
	protected $advancedPixelation = FALSE;

	/**
	 * Constructeur de la classe DPixelateFilter.
	 * 
	 * @throws UnsupportedFilterException Si ce filtre n'est pas supporté par la version de GD.
	 */
	public function __construct() {
		if (!defined('IMG_FILTER_PIXELATE')) {
			throw new UnsupportedFilterException();
		}
	}

	/**
	 * Modifier le paramètre de pixelisation avancé.
	 * 
	 * @param boolean $value Activer la pixelisation avancée.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est omis.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un boolean.
	 */
	public function setAdvancedPixelation($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'bool');
		$this->advancedPixelation = $value;
	}

	/**
	 * Indique si la pixelisation avancée est activée.
	 * 
	 * @return boolean
	 */
	public function isAdvancedPixelation() {
		return $this->advancedPixelation;
	}

	/**
	 * Modifier la taille des blocs, en pixel.
	 * 
	 * @param int $value TODO Verifier les bornes
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value est en dehors du champ de valeurs admises.
	 */
	public function setBlockSize($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < 1 || $value > 255) {
			throw new OutOfBoundsException('1 >= $value >= 255');
		}
		$this->blockSize = $value;
	}

	/**
	 * Renvoi la taille des blocs en pixels.
	 * 
	 * @return int
	 */
	public function getBlockSize() {
		return $this->blockSize;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);
		return
			@imagefilter(
				$img->getGDResource(FALSE),
				IMG_FILTER_PIXELATE,
				$this->blockSize,
				$this->advancedPixelation
			);
	}

}

/**
 * Ce filtre applique un effet de spérisation à l'image.
 * Utilise les formules de changement de repere plan-&gt;sphere.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DSpherizeFilter extends DAbstractFilter {

	/**
	 * Coefficient de distorsion.
	 * @var float
	 */
	protected $coef = 1.0;

	/**
	 * Constructeur de la classe DSpherizeFilter.
	 * 
	 * @param int $coef Coefficient de distorsion, compris entre 0 et 2000.
	 * @throws MissingArgumentException Si l'argument $coef est manquant.
	 * @throws BadArgumentTypeException Si l'argument $coef n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $coef n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function __construct($coef=100) {
		$this->setDistortionCoef($coef);
	}

	/**
	 * Modifier le coefficient de distorsion.
	 * La valeur de 100 corresponds à un coefficient de 1.
	 * 
	 * @param int $value Valeur comprise entre 0 et 2000.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 * @throws OutOfBoundsException Si l'argument $value n'est pas compris dans le champ de valeurs acceptées.
	 */
	public function setDistortionCoef($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int');
		if ($value < 0 || $value > 2000) {
			throw new OutOfBoundsException('0 >= $value >= 2000');
		}
		$this->coef = ($value / 100);
	}

	/**
	 * Renvoi le coefficient de distorsion.
	 * 
	 * @return float
	 */
	public function getDistortionCoef() {
		return $this->coef * 100;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);

		$width = $img->getInfos()->getWidth();
		$height = $img->getInfos()->getHeight();
		$atanscale = 2 / pi();

		try {
			$t = new DImage($width, $height);
		} catch (Exception $ex) {
			return FALSE;
		}

		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {

				// Center + rescale coords in [-1,1]
				$xk = (float)($x/$width) * 2 - 1;
				$yk = (float)($y/$height) * 2 - 1;

				// Outside of the sphere -> next
				if (($yk*$yk + $xk*$xk) >= 0.999) continue;

				// Compute zk from (xk,yk)
				$zk = sqrt(1-($xk*$xk + $yk*$yk));

				// Cartesian coords (xk,yk,zk) in [-1,1] -> spherical coords (xs,ys) in [-1,1]
				$xs = atan($this->coef * $xk / $zk) * $atanscale;
				$ys = atan($this->coef * $yk / $zk) * $atanscale;

				// Spherical coords (xs,ys) -> texture coords
				$xtex = (int)($width * ($xs+1) / 2);
				$ytex = (int)($height * ($ys+1) / 2);

				$t->setColorAt($x, $y, $img->getColorAt($xtex, $ytex));
			}
		}

		// Copy
		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {
				$img->setColorAt($x, $y, $t->getColorAt($x, $y));
			}
		}

		$t->destroy();

		return TRUE;
	}

}

/**
 * Ce filtre applique un effet de rotation aux pixels de l'image.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DTwirlFilter extends DAbstractFilter {

	/**
	 * Coordonnée en X du centre de la rotation.
	 * @var int
	 */
	protected $centerX = 0;

	/**
	 * Coordonnée en Y du centre de la rotation.
	 * @var int
	 */
	protected $centerY = 0;

	/**
	 * Rayon du cercle de rotation, en pixel.
	 * @var int
	 */
	protected $radius = 0;

	/**
	 * Angle de rotation, en radians.
	 * @var float
	 */
	protected $angleRad = 0;

	/**
	 * Processus de remplissage.
	 * @var boolean
	 */
	protected $fullFill = FALSE;

	/**
	 * Change le status du comportement de fullfill.
	 * 
	 * @param boolean $value Activer le comportement.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un boolean.
	 */
	public function setFullFill($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'bool');
		$this->fullFill = $value;
	}

	/**
	 * Indique le status du comportement de fullfill.
	 * 
	 * @return boolean
	 */
	public function isFullFill() {
		return $this->fullFill;
	}

	/**
	 * Modifier l'emplacement du centre de rotation du filtre. Cette méthode ne
	 * contrôle absolument pas que les coordonnées sont dans les limites de l'image
	 * de destination. C'est lors de l'execution de filtre (et de l'appel à la
	 * méthode apply()) que les erreurs sont détectés.
	 * 
	 * <code>
	 *  setCenter(int $x, int $y)
	 *  setCenter(Point2d $p)
	 * </code>
	 * 
	 * @param int|Point2d $arg0 Coordonnée en X, ou un Point2d.
	 * @param int $arg1 Coordonnée en Y, ou NULL si $arg0 est un Point2d.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 */
	public function setCenter($arg0, $arg1=NULL) {
		$arg0 = @PHPHelper::checkArgument('$arg0', $arg0, 'int|Point2d');
		if ($arg0 instanceof Point2d) {
			$this->centerX = $arg0->getX();
			$this->centerY = $arg0->getY();
		}
		else if (!is_int($arg1)) {
			throw new BadArgumentTypeException('$arg1', $arg1, 'int');
		}
		else {
			$this->centerX = $arg0;
			$this->centerY = $arg1;
		}
	}

	/**
	 * Renvoi le centre de rotation du filtre.
	 * 
	 * @return Point2d
	 */
	public function getCenter() {
		return new Point2d($this->centerX, $this->centerY);
	}

	/**
	 * Modifier l'angle de rotation du filtre.
	 * 
	 * @param float $value Le rayon de rotation, en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setAngle($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'float', TRUE, TRUE);
		$this->angleRad = deg2rad($value);
	}

	/**
	 * Renvoi l'angle de rotation, en degrés.
	 * 
	 * @return float
	 */
	public function getAngle() {
		return rad2deg($this->angleRad);
	}

	/**
	 * Modifier le rayon de rotation, en pixel.
	 * 
	 * @param float $value Le rayon de rotation, en pixel.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un entier.
	 */
	public function setRadius($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'int', TRUE, TRUE);
		$this->radius = $value;
	}

	/**
	 * Renvoi le rayon de rotation, en pixel.
	 * 
	 * @return float
	 */
	public function getRadius() {
		return $this->radius;
	}

	/**
	 * Appliquer le filtre à la ressource $img.
	 * Si le rayon ou l'angle est egale à 0, cette méthode ne fait rien du tout.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est manquant.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);

		if ($this->radius == 0 || $this->angleRad == 0) {
			return TRUE;
		}

		$width = $img->getInfos()->getWidth();
		$height = $img->getInfos()->getHeight();

		if ($this->centerX < 0 || $this->centerX > $width-1) {
			throw new OutOfBoundsException('$centerX');
		}
		if ($this->centerY < 0 || $this->centerY > $height-1) {
			throw new OutOfBoundsException('$centerY');
		}

		// TODO Optimisation : ces deux variables sont inutiles
		$iCenterX = $this->centerX;
		$iCenterY = $this->centerY;
		if ($this->radius == 0) {
			$this->radius = min($iCenterX, $iCenterY);
		}

		try {
			$t = new DImage($width, $height);
		} catch (Exception $ex) {
			return FALSE;
		}

		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {

				$p = $this->transform($x, $y, $iCenterX, $iCenterY);

				if ($this->fullFill) {
					if ($p->getX() < 0) {
						$p->setX(0);
					}
					if ($p->getY() < 0) {
						$p->setY(0);
					}
					if ($p->getX() >= $width) {
						$p->setX($width-1);
					}
					if ($p->getY() >= $height) {
						$p->setY($height-1);
					}
				}
				else if ($p->getX() < 0 || $p->getX() >= $width || $p->getY() < 0 || $p->getY() >= $height) {
					continue;
				}

				$t->setColorAt($x, $y, $img->getColorAt($p->getX(), $p->getY()));

			}
		}

		// Copy
		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {
				$img->setColorAt($x, $y, $t->getColorAt($x, $y));
			}
		}

		$t->destroy();

		return TRUE;
	}

	/**
	 * Applique la transformation de pixel.
	 * 
	 * @param int $x Coordonnée en X du pixel.
	 * @param int $y Coordonnée en Y du pixel.
	 * @param int $iCenterX Coordonnée en X du centre de rotation.
	 * @param int $iCenterY Coordonnée en Y du centre de rotation.
	 * @return Point2d
	 */
	protected function transform($x, $y, $iCenterX, $iCenterY) {
		$dx = $x - $iCenterX;
		$dy = $y - $iCenterY;
		$distance = $dx*$dx + $dy*$dy;
		if ($distance > $this->radius*$this->radius) {
			return new DPoint($x, $y);
		} else {
			$distance = (float) sqrt($distance);
			$a = (float) atan2($dy, $dx) + $this->angleRad * ($this->radius - $distance) / $this->radius;
			return new DPoint(
				$iCenterX + $distance * cos($a),
				$iCenterY + $distance * sin($a)
			);
		}
	}

}

/**
 * Ce filtre permet de mixer chaque channel (rouge, vert ou bleu) aux autres channels.
 * 
 * TODO Documenter ce truc
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DChannelMixFilter extends DAbstractFilter {

	/**
	 * TODO
	 * @var DColor
	 */
	protected $into = NULL;

	/**
	 * TODO
	 * @var int
	 */
	protected $blueGreen = 0;

	/**
	 * TODO
	 * @var int
	 */
	protected $redBlue = 0;

	/**
	 * TODO
	 * @var int
	 */
	protected $greenRed = 0;

	/**
	 * Appliquer le filtre à la ressource $res.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);

		$width = $img->getInfos()->getWidth();
		$height = $img->getInfos()->getHeight();

		$this->into = new DColor(255, 255, 255); // TODO Virer

		if ($this->into == NULL) {
			return TRUE;
		}

		$intoR = $this->into->getRedValue();
		$intoG = $this->into->getGreenValue();
		$intoB = $this->into->getBlueValue();

		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {

				$c = $img->getColorAt($x, $y);
				$a = $c->getAlphaValue();
				$r = $c->getRedValue();
				$g = $c->getGreenValue();
				$b = $c->getBlueValue();

				$nr = self::clamp(($intoR * ($this->blueGreen * $g + (255 - $this->blueGreen) * $b) / 255 + (255 - $intoR) * $r) / 255);
				$ng = self::clamp(($intoG * ($this->redBlue * $b + (255 - $this->redBlue) * $r) / 255 + (255 - $intoG) * $g) / 255);
				$nb = self::clamp(($intoB * ($this->greenRed * $r + (255 - $this->greenRed) * $g) / 255 + (255 - $intoB) * $b) / 255);

				$img->setColorAt($x, $y, new DColor((int)$nr, (int)$ng, (int)$nb), $a);
			}
		}

		return TRUE;
	}

	/**
	 * TODO
	 * 
	 * @param int $v
	 * @return int
	 */
	protected static function clamp($v) {
		if ($v < 0) return 0;
		if ($v > 255) return 255;
		return $v;
	}

}

/**
 * Ce filtre permet d'ajuster la teinte (hue), la saturation et la luminosité (brightness) de
 * l'image.
 * 
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagefilter
 */
class DHSBAdjustFilter extends DAbstractFilter {

	/**
	 * Facteur de teinte (hue).
	 * @var float
	 */
	protected $hFactor = 0.5;

	/**
	 * Facteur de saturation.
	 * @var float
	 */
	protected $sFactor = 1;

	/**
	 * Facteur de luminosité (brightness).
	 * @var float
	 */
	protected $bFactor = 1;

	/**
	 * Appliquer le filtre à la ressource $res.
	 * 
	 * @param DImage $img
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $img est omis.
	 * @throws BadArgumentTypeException Si l'argument $img n'est pas une ressource vailde.
	 * @throws ImageNotBoundException Si l'argument $img n'est pas une ressource liée.
	 */
	public function apply(DImage $img) {
		$this->testImg($img);

		$width = $img->getInfos()->getWidth();
		$height = $img->getInfos()->getHeight();

		// TODO Ne fonctionne pas
		for ($y=0; $y < $height; $y++) {
			for ($x=0; $x < $width; $x++) {

				$hsb = $img->getColorAt($x, $y)->getValuesHSB();

				$hsb[0] += $this->hFactor;

				while ($hsb[0] < 0) {
					$hsb[0] += pi() * 2;
				}
				$hsb[1] += $this->sFactor;
				if ($hsb[1] < 0) {
					$hsb[1] = 0;
				}
				else if ($hsb[1] > 1.0) {
					$hsb[1] = 1.0;
				}
				$hsb[2] += $this->bFactor;
				if ($hsb[2] < 0) {
					$hsb[2] = 0;
				}
				else if ($hsb[2] > 1.0) {
					$hsb[2] = 1.0;
				}

				$c = new DColor(0, 0, 0);
				$c->setValuesHSB(
					(int) $hsb[0],
					(int) $hsb[1],
					(int) $hsb[2]
				);
				
				$img->setColorAt($x, $y, $c);

			}

		}

		return TRUE;
	}

}

// TODO Ajouter les filtres de http://www.devx.com/webdev/Article/37179/0/page/5
// Ils permettent de regler separement H, S et B

?>