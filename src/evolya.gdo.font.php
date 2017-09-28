<?php

/**
 * Type énuméré représentant les differents types de polices de caractères.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.font
 */
final class DFontTypeEnum implements Enum {

	/**
	 * @var DFontTypeEnum
	 */
	public static $GD = NULL;

	/**
	 * @var DFontTypeEnum
	 */
	public static $TTF = NULL;

	/**
	 * @var DFontTypeEnum
	 */
	public static $PS = NULL;

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
	 * Constructeur de l'enum DFontTypeEnum.
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
	 * @param DFontTypeEnum L'autre constante à tester.
	 * @return boolean
	 */
	public function equals(DFontTypeEnum $other) {
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
	 * @return DFontTypeEnum
	 */
	public static function valueOf($name) {
		if (!isset($name)) return NULL;
		if ($name == 'GD') return self::$GD;
		if ($name == 'TTF') return self::$TTF;
		if ($name == 'PS') return self::$PS;
		return NULL;
	}

	/**
	 * Initialise les constantes de l'enum.
	 * @return void
	 */
	public static function init() {
		self::$GD = new DFontTypeEnum('GD', 0);
		self::$TTF = new DFontTypeEnum('TTF', 1);
		self::$PS = new DFontTypeEnum('PS', 2);
	}
}

DFontTypeEnum::init();

/**
 * L'interface DFontInterface peut être implémentée par les classes qui représentent
 * une police de caractère et qui sont capables de dessiner du texte sur une image.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.font
 */
interface DFontInterface {

	/**
	 * Renvoi le type de police.
	 * 
	 * @return DFontTypeEnum
	 */
	public function getType();

	/**
	 * Changer la taille de la police.
	 * S'il s'agit d'une police de type GD, la taille est comprise entre 1 et 5.
	 * 
	 * @param int $size
	 * @return void
	 * @throws MissingArgumentException Si l'argument $size est manquant.
	 * @throws BadArgumentTypeException Si l'argument $size n'est pas un entier.
	 * @throws IllegalArgumentException Si la police est de type GD et que la taille
	 * 	n'est pas comprise entre 1 et 5 inclus; ou si la taille est négative ou zéro.
	 */
	public function setSize($size);

	/**
	 * Renvoi la taille de la police, en pixel.
	 * 
	 * @return int
	 */
	public function getSize();

	/**
	 * Calculer les bornes du rectangle réquis pour afficher le texte $text
	 * avec l'angle $angle en utilisant cette police. 
	 * 
	 * @param string $text Le texte.
	 * @param float $angle L'angle d'inclinaison en degrès du texte.
	 * @return DBounds
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type valide.
	 * @throws UnsupportedOperationException Si le type de police n'est pas supporté, ou si un
	 * 	angle d'inclinaison a été donné alors que la police est de type GD.
	 */
	public function calculateFontBox($text, $angle=0);

	/**
	 * Afficher le texte $text dans l'image $img, avec la couleur 
	 * $color, l'angle $angle, aux coordonnées $x et $y.
	 * 
	 * @param DImage $img L'image où dessiner le texte.
	 * @param string $text Le texte à afficher.
	 * @param DColor $color La couleur du texte.
	 * @param float $angle L'angle d'inclinaison du texte en degrés. 
	 * @param int $x Coordonnée en abscisse du texte. 
	 * @param int $y Coordonnée en ordonnées du texte.
	 * @return void
	 */
	public function draw(DImage $img, $text, DColor $color, $angle=0, $x=0, $y=0);

}

/**
 * La classe DFont représente une police de caractère utilisable dans GDO.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.font
 */
class DFont implements DFontInterface {

	/**
	 * La police par defaut.
	 * @var DFont
	 */
	public static $DEFAULT = NULL;

	/**
	 * Nom de la police.
	 * @var string
	 */
	protected $name = 'Unnamed font';

	/**
	 * Type de police.
	 * @var DFontTypeEnum
	 */
	protected $type = NULL;

	/**
	 * Si la police n'est pas GD, cette variable contient
	 * le chemin vers le fichier source de la police.
	 * Si la police est GD, contient le numero de police.
	 * @var string
	 */
	protected $value = NULL;

	/**
	 * Taille de la police.
	 * @var int
	 */
	protected $size = 12;

	/**
	 * Constructeur de la classe DFont.
	 * 
	 * @construct DFont(string $path)
	 *  Construit une police à partir d'un chemin vers un fichier de police.
	 * @construct DFont(DFile $path)
	 *  Construit une police à partir d'une fichier de police.
	 * @construct DFont(int $gdPolice)
	 *  Construit une police GD. $gdPolice doit être compris entre 1 et 5 inclus.
	 * 
	 * @param int|string|DFile $font La police.
	 * @param int $size La taille de la police.
	 * @throws MissingArgumentException Si un des argument est manquant.
	 * @throws BadArgumentTypeException Si un des argument n'est pas d'un type valide.
	 * @throws IllegalArgumentException Si l'identifiant de la police GD n'est pas compris entre 1 et 5 inclus.
	 * @throws FileNotFoundException Si le fichier de police est introuvable.
	 * @throws UnknownFontTypeException Si l'extension du fichier de police n'est pas valide.
	 */
	public function __construct($font, $size=NULL) {

		$font = @PHPHelper::checkArgument('$font', $font, 'int|string|DFile'); 

		# File Path
		if (is_string($font)) {
			$font = new DFile($font);
		}

		# GD Font
		if (is_int($font)) {
			if ($font < 1 && $font > 5) {
				throw new IllegalArgumentException('GD Font identifier must be within 1 and 5 included');
			}
			$this->type = DFontTypeEnum::$GD;
			$this->setSize($font);
			return;
		}

		# File
		else if ($font instanceof DFile) {

			if (!$font->isFile()) {
				throw new FileNotFoundException($font);
			}

			$ext = strtolower($font->getExtension());
			switch ($ext) {
				case 'ttf' :
					$this->type = DFontTypeEnum::$TTF;
					$this->value = $font->getPath();
					$this->name = $font->getName();
					break;
				case 'pfb' :
					$this->type = DFontTypeEnum::$PS;
					$this->value = $font->getPath();
					$this->name = $font->getName();
					break;
				default :
					throw new UnknownFontTypeException($ext);
					break;
			}

			if ($size !== NULL) {
				$this->setSize($size);
			}
		}

		else {
			throw new BadArgumentTypeException('$font', $font, 'int|string|DFile');
		}
	}

	/**
	 * Renvoi le type de police.
	 * 
	 * @return DFontTypeEnum
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Renvoi le nom de la police.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Changer la taille de la police.
	 * S'il s'agit d'une police de type GD, la taille est comprise entre 1 et 5.
	 * 
	 * @param int $size
	 * @return void
	 * @throws MissingArgumentException Si l'argument $size est manquant.
	 * @throws BadArgumentTypeException Si l'argument $size n'est pas un entier.
	 * @throws IllegalArgumentException Si la police est de type GD et que la taille
	 * 	n'est pas comprise entre 1 et 5 inclus; ou si la taille est négative ou zéro.
	 */
	public function setSize($size) {

		$size = @PHPHelper::checkArgument('$size', $size, 'int');

		if ($this->type == DFontTypeEnum::$GD) {
			if ($size < 1 || $size > 5) throw new IllegalArgumentException('1 >= size <= 5');
			$this->value = $size;
			$this->size = $size;
			$this->name = 'Built-in GD Font No '.$size;
			return;
		}

		if ($size <= 0) {
			throw new IllegalArgumentException('positive int or float required');
		}
		$this->size = $size;
	}

	/**
	 * Renvoi la taille de la police, en pixel.
	 * 
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	// TODO Voir si cette méthode est utilisée.
//	public function getValue() {
//		return $this->value;
//	}

	/**
	 * Calculer les bornes du rectangle réquis pour afficher le texte $text
	 * avec l'angle $angle en utilisant cette police. 
	 * 
	 * @param string $text Le texte.
	 * @param float $angle L'angle d'inclinaison en degrès du texte.
	 * @return DBounds
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type valide.
	 * @throws UnsupportedOperationException Si le type de police n'est pas supporté, ou si un
	 * 	angle d'inclinaison a été donné alors que la police est de type GD.
	 */
	public function calculateFontBox($text, $angle=0) {

		$text = @PHPHelper::checkArgument('$text', $text, 'string');
		$angle = @PHPHelper::checkArgument('$angle', $angle, 'float', TRUE, TRUE);

		switch ($this->type) {
			
			case DFontTypeEnum::$GD :
				if ($angle != 0) {
					throw new UnsupportedOperationException('GD Fonts does not support $angle value');
				}
				return new DBounds(
					0, // TODO
					0, // TODO
					imagefontwidth($this->value) * strlen($text),
					imagefontheight($this->value)
				);

			case DFontTypeEnum::$TTF :
				$rect = self::imagettfbbox_fixed($this->size, $angle, $this->value, $text);
				if (!$rect || empty($rect)) {
					throw new UnsupportedOperationException('Unable to calculate drawing box for TTF font');
				}
				
				$minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
				$maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
				$minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
				$maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));

				return new DBounds(
					abs($minX),
					abs($minY),
					$maxX - $minX,
					$maxY - $minY
				);
				break;

			case DFontTypeEnum::$PS :
				// TODO
				throw new UnsupportedOperationException('PostScript font not supported yet');
				break;

			default :
				throw new UnsupportedOperationException('Font type not supported');
				break;
		}
		
	}

	/**
	 * Retourne le rectangle entourant un texte et dessiné avec une police TrueType.
	 * Effectue des corrections par rapport aux données renvoyées par la fonction imagettfbbox.
	 * 
	 * @param int $size Taille de la police.
	 * @param float $angle Angle en degrés.
	 * @param string $font
	 * @param string $text
	 * @return array&lt;int&gt;
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type valide.
	 * @link http://php.net/manual/fr/function.imagettfbbox.php
	 */
	protected static function imagettfbbox_fixed($size, $angle, $font, $text)
	{
		$size = @PHPHelper::checkArgument('$size', $size, 'int');
		$angle = @PHPHelper::checkArgument('$angle', $angle, 'float', TRUE, TRUE);
		$font = @PHPHelper::checkArgument('$font', $font, 'string');
		$text = @PHPHelper::checkArgument('$text', $text, 'string');

		$bbox = @imagettfbbox($size, 0, $font, $text);
		if (!$bbox) {
			return NULL;
		}

		// Rotate the boundingbox
		$angle = pi() * 2 - $angle * pi() * 2 / 360;
		for ($i=0; $i<4; $i++)
		{
			$x = $bbox[$i * 2];
			$y = $bbox[$i * 2 + 1];
			$bbox[$i * 2] = cos($angle) * $x - sin($angle) * $y;
			$bbox[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y;
		}

		return $bbox;
	}

	/**
	 * Afficher le texte $text dans l'image $img, avec la couleur 
	 * $color, l'angle $angle, aux coordonnées $x et $y.
	 * 
	 * TODO exceptions
	 * 
	 * @param DImage $img L'image où dessiner le texte.
	 * @param string $text Le texte à afficher.
	 * @param DColor $color La couleur du texte.
	 * @param float $angle L'angle d'inclinaison du texte en degrés. 
	 * @param int $x Coordonnée en abscisse du texte. 
	 * @param int $y Coordonnée en ordonnées du texte.
	 * @return void
	 */
	public function draw(DImage $img, $text, DColor $color, $angle=0, $x=0, $y=0) {

		// TODO utiliser MissingArgumentException
		if (!isset($img) || !isset($text) || !isset($color)) {
			throw new IllegalArgumentException('missing arguments');
		}

		if (!$img->isBound()) {
			throw new ImageNotBoundException();
		}

		if (!is_string($text)) {
			throw new IllegalArgumentException('argument $text must be a string');
		}

		if (is_float($x)) $x = intval($x);
		if (is_float($y)) $y = intval($y);

		if (!is_int($x)) {
			throw new IllegalArgumentException('argument $x must be integer or float');
		}
		if (!is_int($y)) {
			throw new IllegalArgumentException('argument $y must be integer or float');
		}

		if (!is_int($angle) && !is_float($angle)) {
			throw new IllegalArgumentException('argument $angle must be integer or float');
		}

		switch ($this->type) {

			case DFontTypeEnum::$GD :
				return @imagestring(
					$img->getGDResource(),
					$this->value,
					$x,
					$y,
					$text,
					$color->toGDColor()
				);
				break;

			case DFontTypeEnum::$TTF :
				return @imagettftext(
					$img->getGDResource(),
					(float) $this->size,
					(float) $angle,
					$x,
					$y,
					$color->toGDColor(),
					$this->value,
					$text
				);
				break;

			case DFontTypeEnum::$PS :
				throw new UnsupportedOperationException('PostScript font type not supported yet');
				break;

			default :
				throw new UnsupportedOperationException('Font type not supported');
				break;

		}

	}

	/**
	 * Afficher cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return "Font {$this->name}";
	}

}

DFont::$DEFAULT = new DFont(2);

?>