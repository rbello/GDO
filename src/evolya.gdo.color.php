<?php

/**
 * L'interface DSupportFilters peut être implémentée par les classes qui représentent
 * des couleurs RVB et supportent le canal alpha (transparence).
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.color
 */
interface DColorInterface {

	/**
	 * Change le nom de la couleur.
	 * 
	 * @param string $name Le nouveau nom pour la couleur.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $name est manquant.
	 * @throws BadArgumentTypeException Si l'argument $name n'est pas du type requis.
	 */
	public function setName($name);

	/**
	 * Retourne le nom de la couleur s'il a été spécifié au constructeur,
	 * ou NULL si la couleur n'a pas de nom.
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Modifier les valeurs R, G, B et A de la couleur.
	 * 
	 * Pour la valeur alpha, 0 indique une opacité complète tandis que
	 * 127 indique une transparence complète.
	 * 
	 * @param int $r Valeur de rouge. Compris entre 0 et 255 inclus.
	 * @param int $g Valeur de vert. Compris entre 0 et 255 inclus.
	 * @param int $b Valeur de bleu. Compris entre 0 et 255 inclus.
	 * @param int $a Valeur de transparence. Compris entre 0 et 127 inclus.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws OutOfBoundsException Si les valeurs sont en dehors du champ accepté.
	 */
	public function setValuesRGB($r, $g, $b, $a=0);

	/**
	 * Renvoi un tableau contenant respectivement les quatres valeurs 
	 * RED, GREEN, BLUE, ALPHA
	 * 
	 * Index du tableau :
	 * <pre>
	 * 0 => rouge   red    (0~255)
	 * 1 => vert    green  (0~255)
	 * 2 => bleu    blue   (0~255)
	 * 3 => alpha   alpha  (0~127)
	 * </pre>
	 * 
	 * <br>Pour la valeur alpha, 0 indique une opacité complète tandis que
	 * 127 indique une transparence complète.
	 * 
	 * @return array&lt;int&gt;
	 */
	public function getValuesRGB();

	/**
	 * Renvoi un tableau contenant respectivement les quatres valeurs
	 * Teinte (hue), Saturation, Luminosité (brightness) et le canal
	 * ALPHA (transparence).
	 * 
	 * Index du tableau :
	 * <pre>
	 * 0 => teinte       hue         (0~360)
	 * 1 => saturation   saturation  (0~100)
	 * 2 => luminosité   brightness  (0~100)
	 * 3 => alpha        alpha       (0~127)
	 * </pre>
	 * 
	 * <br>Pour la valeur alpha, 0 indique une opacité complète tandis que
	 * 127 indique une transparence complète.
	 * 
	 * @return array&lt;int&gt;
	 */
	public function getValuesHSB();

	/**
	 * Retourne la valeur ROUGE (red) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getRedValue();

	/**
	 * Retourne la valeur VERT (green) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getGreenValue();

	/**
	 * Retourne la valeur BLEU (blue) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getBlueValue();

	/**
	 * Retourne la valeur ALPHA de cette couleur (transparence), comprise entre 0 et 127.
	 * 0 indique une opacité complète tandis que 127 indique une transparence complète.
	 * 
	 * @return int
	 */
	public function getAlphaValue();

	/**
	 * Afficher la couleur avec la notation hexadécimale. Par exemple, la couleur ayant
	 * les valeurs RVB 255,102,51  a la notation hexadécimale ff6633.
	 * 
	 * @return string
	 */
	public function toHexa();

	/**
	 * Converti cette couleur en format directement utilisable par les fonctions GD.
	 * Permet de se passer de la la fonction imagecolorallocate pour les couleurs sans transparence.
	 * En effet, le canal alpha n'est pas enregistré avec cette méthode.
	 * 
	 * @return int
	 * @link http://php.net/manual/fr/function.imagecolorallocate.php
	 */
	public function toGDColor();

}

/**
 * La classe DColor permet de représenter une couleur RVB. Par défaut, les couleurs sont
 * représentées dans l'espace colométrique sRVB.
 * <br>Par défaut, les couleurs ont une opacité complète (127).
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.color
 * @link http://www.w3.org/Graphics/Color/sRGB.html
 */
final class DColor implements DColorInterface, Finalizable {

	/**
	 * Couleur TRANSPARENTE.
	 * @var DColor
	 */
	public static $TRANSPARENT;

	/**
	 * Couleur NOIR.
	 * @var DColor
	 */
	public static $BLACK;

	/**
	 * Couleur BLANC.
	 * @var DColor
	 */
	public static $WHITE;

	/**
	 * Couleur ROUGE.
	 * @var DColor
	 */
	public static $RED;

	/**
	 * Couleur BLEU.
	 * @var DColor
	 */
	public static $BLUE;

	/**
	 * Couleur VERT.
	 * @var DColor
	 */
	public static $GREEN;

	/**
	 * Couleur CYAN.
	 * @var DColor
	 */
	public static $CYAN;

	/**
	 * Couleur MAGENTA.
	 * @var DColor
	 */
	public static $MAGENTA;

	/**
	 * Couleur JAUNE.
	 * @var DColor
	 */
	public static $YELLOW;

	/**
	 * Couleur VIOLET.
	 * @var DColor
	 */
	public static $PURPLE;

	/**
	 * Couleur ORANGE.
	 * @var DColor
	 */
	public static $ORANGE;

	/**
	 * Couleur MARRON.
	 * @var DColor
	 */
	public static $BROWN;

	/**
	 * Couleur ROSE.
	 * @var DColor
	 */
	public static $PINK;

	/**
	 * Couleur GRIS.
	 * @var DColor
	 */
	public static $GRAY;

	/**
	 * Facteur de transition pour les méthodes darker() et brighter().
	 * @var float
	 */
	protected static $FACTOR = 0.75;

	/**
	 * Valeur rouge de la couleur (RED).
	 * @var int
	 */
	protected $red = 0;

	/**
	 * Valeur vert de la couleur (GREEN).
	 * @var int
	 */
	protected $green = 0;

	/**
	 * Valeur bleu de la couleur (BLUE).
	 * @var int
	 */
	protected $blue = 0;

	/**
	 * Valeur alpha de la couleur (transparence).
	 * @var int
	 */
	protected $alpha = 0;

	/**
	 * Nom de la couleur.
	 * @var string
	 */
	protected $name = NULL;

	/**
	 * Indique si l'instance est finalisée.
	 * @var boolean
	 */
	protected $finalized = FALSE;

	/**
	 * Constructeur de la classe DColor.
	 * 
	 * @construct DColor()
	 *  Construit la couleur noir.
	 * @construct DColor(int $r, int $g, int $b)
	 *  Contstruit une couleur ayant les valeurs $r, $g, et $b.
	 * @construct DColor(int $r, int $g, int $b, string $name)
	 *  Contstruit une couleur ayant les valeurs $r, $g, et $b, et lui donne le nom $name.
	 * @construct DColor(int $r, int $g, int $b, int $a)
	 *  Contstruit une couleur ayant les valeurs $r, $g, et $b et la transparence $a.
	 * @construct DColor(int $r, int $g, int $b, int $a, string $name)
	 *  Contstruit une couleur ayant les valeurs $r, $g, et $b et la transparence $a, et
	 *  lui donne le nom $name.
	 * @construct DColor(DColorInterface $c)
	 *  Duplique la couleur $c.
	 * @construct DColor(DColorInterface $c, string $name)
	 *  Duplique la couleur $c, et prends le nom $name.
	 * @construct DColor(string hexa)
	 *  Construit la couleur à partir de la notation hexadecimale $hexa.
	 * @construct DColor(string $hexa, int $a)
	 *  Construit la couleur à partir de la notation hexadecimale $hexa, avec la transparence $a.
	 * @construct DColor(string $hexa, $string name)
	 *  Construit la couleur à partir de la notation hexadecimale $hexa et donne le nom $name.
	 * 
	 * TODO Impossible de NULLER l'$arg0 comme indiqué dans le constructeur... ?
	 * 
	 * @param mixed $arg0
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @param mixed $arg3
	 * @param mixed $arg4
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si les arguments ne sont pas du type requis.
	 */
	public function __construct($arg0, $arg1=NULL, $arg2=NULL, $arg3=NULL, $arg4=NULL) {

		$arg0 = @PHPHelper::checkArgument('$arg0', $arg0, 'string|DColorInterface|int');

		if (is_string($arg0)) {

			$rvb = self::hexa2rvb(strtolower($arg0));
			if (!is_array($rvb)) {
				throw new IllegalArgumentException('Invalid hexa format');
			}

			if (is_string($arg1)) {
				$this->setName($arg1);
			}
			else if (is_int($arg1)) {
				$this->setValuesRGB(
					$rvb[0],
					$rvb[1],
					$rvb[2],
					$arg1
				);
			}
			else if (isset($arg1)) {
				throw new BadArgumentTypeException('$name', $arg1, 'string');
			}
			else {
				$this->red = $rvb[0];
				$this->green = $rvb[1];
				$this->blue = $rvb[2];
			}

		}
		else if ($arg0 instanceof DColorInterface) {

			$this->red = $arg0->getRedValue();
			$this->green = $arg0->getGreenValue();
			$this->blue = $arg0->getBlueValue();
			$this->alpha = $arg0->getAlphaValue();

			if (isset($arg1)) {
				if (is_string($arg1)) {
					$this->setName($arg1);
				}
				else {
					throw new BadArgumentTypeException('$name', $arg1, 'string');
				}
			}
			else if ($arg0->getName() != NULL) {
				$this->setName($arg0->getName().' - Copy');
			}

		}
		else if (is_int($arg0)) {

			if (is_int($arg3)) {
				$this->setValuesRGB($arg0, $arg1, $arg2, $arg3);
				if (is_string($arg4)) {
					$this->setName($arg4);
				}
				else if (isset($arg4)) {
					throw new BadArgumentTypeException('$name', $arg4, 'string');
				}
			}
			else if (is_string($arg3)) {
				$this->setValuesRGB($arg0, $arg1, $arg2);
				$this->setName($arg3);
			}
			else if (isset($arg3)) {
				throw new BadArgumentTypeException('$arg3', $arg3, 'int|string');
			}
			else {
				$this->setValuesRGB($arg0, $arg1, $arg2);
			}

		}

	}

	/**
	 * Change le nom de la couleur.
	 * 
	 * @param string $name Le nouveau nom pour la couleur.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $name est manquant.
	 * @throws BadArgumentTypeException Si l'argument $name n'est pas du type requis.
	 * @throws FinalizedObjectException Si l'objet est finalisé.
	 */
	public function setName($name) {
		if ($this->finalized) {
			throw new FinalizedObjectException();
		}
		$name = @PHPHelper::checkArgument('$name', $name, 'string');
		$this->name = $name;
	}

	/**
	 * Retourne le nom de la couleur s'il a été spécifié au constructeur,
	 * ou NULL si la couleur n'a pas de nom.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Afficher la couleur avec la notation hexadécimale. Par exemple, la couleur ayant
	 * les valeurs RVB 255,102,51  a la notation hexadécimale ff6633.
	 * 
	 * @return string
	 */
	public function toHexa() {
		return   self::dechex2($this->red)
				.self::dechex2($this->green)
				.self::dechex2($this->blue);
	}

	/**
	 * Transforme un int compris entre 0 et 255 en sa valeur hexa.
	 * 
	 * @param int $value Valeur entre 0 et 255.
	 * @return string
	 */
	protected static function dechex2($value) {
		$r = dechex($value);
		if (strlen($r) < 2) $r = "0$r";
		return $r;
	}

	/**
	 * Transforme une couleur en notation hexa en tableau de valeurs RVB.
	 * Renvoi un tableau de trois int, respectivement les valeurs R, V et B.
	 * Renvoi NULL si $color n'est pas du bon format.
	 * 
	 * @param string $color La couleur en notation hexa (#ffffff).
	 * @return array&lt;int&gt;
	 */
	public static function hexa2rvb($color) {
		if (preg_match("/^[0-9ABCDEFabcdef]{6}$/i", $color)) {
			return array(
				hexdec(substr($color, 0, 2)),
				hexdec(substr($color, 2, 2)),
				hexdec(substr($color, 4, 2))
			);
		}
		if (preg_match("/^\#[0-9ABCDEFabcdef]{6}$/i", $color)) {
			return array(
				hexdec(substr($color, 1, 2)),
				hexdec(substr($color, 3, 2)),
				hexdec(substr($color, 5, 2))
			);
		}
		return NULL;
	}

	/**
	 * Renvoi un tableau contenant respectivement les quatres valeurs 
	 * RED, GREEN, BLUE, ALPHA
	 * 
	 * Index du tableau :
	 * <pre>
	 * 0 => rouge   red    (0~255)
	 * 1 => vert    green  (0~255)
	 * 2 => bleu    blue   (0~255)
	 * 3 => alpha          (0~127)
	 * </pre>

	 * @return array&lt;int&gt;
	 */
	public function getValuesRGB() {
		return array(
			$this->red,
			$this->green,
			$this->blue,
			$this->alpha
		);
	}

	/**
	 * Méthode utilitaire interne pour vérifier un argument de couleur.
	 * 
	 * @param int $v La valeur, comprise entre 0 et 255.
	 * @return boolean
	 */
	protected static function check($v) {
		if (!isset($v)) return FALSE;
		if (!is_int($v)) return FALSE;
		if ($v < 0 || $v > 255) return FALSE;
		return TRUE;
	}

	/**
	 * Méthode utilitaire pour encoder une couleur dans un entier, ce qui lui permet
	 * d'être directement utilisable dans les fonctions de GD qui demandent un identifiant
	 * de couleur (en fait il ne s'agit pas vraiment d'un identifiant, mais bien d'un
	 * entier tel que renvoyé par cette méthode.)
	 * 
	 * <br>Cette méthode renvoi un entier sur 32 bits :
	 * <li>les 8 premiers bits forment le canal ALPHA</li>
	 * <li>les 8 bits suivants forment la valeur BLUE</li>
	 * <li>les 8 bits suivants forment la valeur GREEN</li>
	 * <li>8 bits suivants forment la valeur RED</li>
	 * 
	 * @param DColorInterface $color La couleur à transposer.
	 * @return int
	 */
	public static function createColor(DColorInterface $color) {
		return bindec(
			 decbin($color->getAlphaValue())
			.decbin(hexdec($color->toHexa()))
		);
	}

	/**
	 * Fabrique une couleur à partir d'un entier de 32 bit.
	 * 
	 * @param int $int La couleur sous forme d'entier.
	 * @return DColor
	 */
	public static function int2color($int) {
		if (!is_int($int)) return NULL;
		$a = ($int >> 24) & 0xFF;
		$r = ($int >> 16) & 0xFF;
		$g = ($int >> 8) & 0xFF;
		$b = $int & 0xFF;
		return new DColor($r, $g, $b, $a);
	} 

	/**
	 * Retourne la valeur ROUGE (red) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getRedValue() {
		return $this->red;
	}

	/**
	 * Retourne la valeur VERT (green) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getGreenValue() {
		return $this->green;
	}

	/**
	 * Retourne la valeur BLEU (blue) de cette couleur, comprise entre 0 et 255.
	 * 
	 * @return int
	 */
	public function getBlueValue() {
		return $this->blue;
	}

	/**
	 * Retourne la valeur ALPHA de cette couleur (transparence), comprise entre 0 et 127.
	 * 0 indique une opacité complète tandis que 127 indique une transparence complète.
	 * 
	 * @return int
	 */
	public function getAlphaValue() {
		return $this->alpha;
	}

	/**
	 * Converti cette couleur en format directement utilisable par les fonctions GD.
	 * Permet de se passer de la la fonction imagecolorallocate.
	 * 
	 * @return int
	 * @link http://php.net/manual/fr/function.imagecolorallocate.php
	 */
	public function toGDColor() {
		return self::createColor($this);
	}

	/**
	 * Renvoi un tableau contenant respectivement les quatres valeurs
	 * Teinte (hue), Saturation, Luminosité (brightness) et le canal
	 * ALPHA (transparence).
	 * 
	 * Index du tableau :
	 * <pre>
	 * 0 => teinte       hue         (0~360)
	 * 1 => saturation   saturation  (0~100)
	 * 2 => luminosité   brightness  (0~100)
	 * 3 => alpha        alpha       (0~127)
	 * </pre>
	 * 
	 * <br>Pour la valeur alpha, 0 indique une opacité complète tandis que
	 * 127 indique une transparence complète.
	 * 
	 * @return array&lt;int&gt;
	 */
	public function getValuesHSB() {
		$r = $this->getRedValue();
		$g = $this->getGreenValue();
		$b = $this->getBlueValue();

		$min = min($r, $g, $b);
		$max = max($r, $g, $b);
		$delta = $max - $min;

		$v = $max / 255;

		if ($max != 0) {
			$s = $delta / $max;
		}
		else {
			return array(0, 0, 0, $this->alpha);
		}

		if ($delta != 0) {
			$redc = ($max - $r) / $delta;
			$greenc = ($max - $g) / $delta;
			$bluec = ($max - $b) / $delta;

			if ($r == $max) $h = $bluec - $greenc;
			else if ($g == $max) $h = 2 + $redc - $bluec;
			else if ($b == $max) $h = 4 + $greenc - $redc;

			$h /= 6;
			if ($h < 0) {
				$h += 1;
			}
		}
		else {
			$h = 0;
		}

		return array(
			(int)($h * 360),
			(int)($s * 100),
			(int)($v * 100),
			$this->alpha
		);
	}

	/**
	 * TODO DOC + NE FONCTIONNE PAS
	 * 
	 * TODO Link http://www.docjar.com/html/api/java/awt/Color.java.html
	 * 
	 * @param int $hue La teinte, en degrès et 0 à 360.
	 * @param int $saturation La saturation, en pourcentage de 0 à 100.
	 * @param int $brightness La luminosité, en pourcentage de 0 à 100.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws OutOfBoundsException Si les valeurs sont en dehors du champ accepté.
	 * @throws FinalizedObjectException Si cette objet est finalisé.
	 */
	public function setValuesHSB($hue, $saturation, $brightness) {

		if ($this->finalized) {
			throw new FinalizedObjectException();
		}

		$hue = @PHPHelper::checkArgument('$hue', $hue, 'int');
		$saturation = @PHPHelper::checkArgument('$saturation', $saturation, 'int');
		$brightness = @PHPHelper::checkArgument('$brightness', $brightness, 'int');

		if ($hue < 0 || $hue > 360) {
			throw new OutOfBoundsException('0 <= $hue >= 360');
		}
		if ($saturation < 0 || $saturation > 100) {
			throw new OutOfBoundsException('0 <= $saturation >= 100');
		}
		if ($brightness < 0 || $brightness > 100) {
			throw new OutOfBoundsException('0 <= $brightness >= 100');
		}

		$saturation /= 100;
		$brightness /= 100;

		if ($saturation == 0) {
			$r = $g = $b = (int) ($brightness * 255 + 0.5);
			$this->red = $r;
			$this->green = $g;
			$this->blue = $b;
		}
		else {
			$h = ($hue - floor($hue)) * 6;      
			$f = $h - floor($h);   
			$p = $brightness * (1 - $saturation);
			$q = $brightness * (1 - $saturation * $f);
			$t = $brightness * (1 - $saturation * (1 - $f));
	
			switch ((int) $h) {
				case 0 :
					$r = $brightness;
					$g = $t;
					$b = $p;
					break;
				case 1 :
					$r = $q;
					$g = $brightness;
					$b = $p;
					break;
				case 2 :
					$r = $p;
					$g = $brightness;
					$b = $t;
					break;
				case 3 :
					$r = $p;
					$g = $q;
					$b = $brightness;
					break;
				case 4 :
					$r = $t;
					$g = $p;
					$b = $brightness;
					break;
				default :
					$r = $brightness;
					$g = $p;
					$b = $q;
			}

			$this->red = (int) ($r * 255 + 0.5);
			$this->green = (int) ($g * 255 + 0.5);
			$this->blue = (int) ($b * 255 + 0.5);
		}
	}

	/**
	 * Modifier les valeurs R, G, B et A de la couleur.
	 * 
	 * <br>Pour la valeur alpha, 0 indique une opacité complète tandis que
	 * 127 indique une transparence complète.
	 * 
	 * @param int $r Valeur de rouge. Compris entre 0 et 255 inclus.
	 * @param int $g Valeur de vert. Compris entre 0 et 255 inclus.
	 * @param int $b Valeur de bleu. Compris entre 0 et 255 inclus.
	 * @param int $a Valeur de transparence. Compris entre 0 et 127 inclus.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws OutOfBoundsException Si les valeurs sont en dehors du champ accepté.
	 * @throws FinalizedObjectException Si cette objet est finalisé.
	 */
	public function setValuesRGB($r, $g, $b, $a=0) {

		if ($this->finalized) {
			throw new FinalizedObjectException();
		}

		$r = @PHPHelper::checkArgument('$r', $r, 'int');
		$g = @PHPHelper::checkArgument('$g', $g, 'int');
		$b = @PHPHelper::checkArgument('$b', $b, 'int');
		$a = @PHPHelper::checkArgument('$a', $a, 'int');

		if ($r < 0 || $r > 255) {
			throw new OutOfBoundsException('0 <= $r >= 255');
		}
		if ($g < 0 || $g > 255) {
			throw new OutOfBoundsException('0 <= $g >= 255');
		}
		if ($b < 0 || $b > 255) {
			throw new OutOfBoundsException('0 <= $b >= 255');
		}
		if ($a < 0 || $a > 127) {
			throw new OutOfBoundsException('0 <= $a >= 127');
		}

		$this->red = $r;
		$this->green = $g;
		$this->blue = $b;
		$this->alpha = $a;
	}

	/**
	 * Clone cette couleur.
	 * 
	 * @return DColor
	 */
	public function __clone() {
		return new DColor($this);
	}

	/**
	 * Finalise l'objet.
	 * 
	 * @return void
	 */
	public function finalize() {
		$this->finalized = TRUE;
	}

	/**
	 * Indique si un objet est finalisé.
	 * 
	 * @return boolean
	 */
	public function isObjectFinalized() {
		return $this->finalized;
	}

	/**
	 * Renvoi TRUE si la couleur $c a les mêmes valeurs
	 * que cette couleur.
	 * 
	 * @param DColorInterface $c L'autre couleur.
	 * @return boolean
	 */
	public function equals(DColorInterface $c) {
		if (!isset($c)) return FALSE;
		if ($this->red != $c->getRedValue()) return FALSE;
		if ($this->green != $c->getGreenValue()) return FALSE;
		if ($this->blue != $c->getBlueValue()) return FALSE;
		if ($this->alpha != $c->getAlphaValue()) return FALSE;
		return TRUE;
	}

	/**
	 * Renvoi une nouvelle couleur qui sera un peu plus clair que celle-ci.
	 * 
	 * @return DColor
	 */
	public function brighter() {
		$r = $this->red;
		$g = $this->green;
		$b = $this->blue;
		$i = (int)(1 / (1 - self::$FACTOR));
		if ($r == 0 && $g == 0 && $b == 0) {
			return new DColor($i, $i, $i);
		}
		if ($r > 0 && $r < $i) $r = $i;
		if ($g > 0 && $g < $i) $g = $i;
		if ($b > 0 && $b < $i) $b = $i;
		return new DColor(
			min((int)($r / self::$FACTOR), 255),
			min((int)($g / self::$FACTOR), 255),
			min((int)($b / self::$FACTOR), 255),
			$this->alpha
		);
	}

	/**
	 * Renvoi une nouvelle couleur qui sera un peu plus sombre celle-ci. 
	 * 
	 * @return DColor
	 */
	public function darker() {
		return new DColor(
			max((int)($this->red * self::$FACTOR), 0),
			max((int)($this->green * self::$FACTOR), 0),
			max((int)($this->blue * self::$FACTOR), 0),
			$this->alpha
		);
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return "Color {$this->name} [{$this->red},{$this->green},{$this->blue},{$this->alpha}]";
	}

}

DColor::$TRANSPARENT	= new DColor(255, 255, 255, 127, 'TRANSPARENT');
DColor::$TRANSPARENT->finalize();

DColor::$BLACK			= new DColor(0, 0, 0, 0, 'BLACK');
DColor::$BLACK->finalize();

DColor::$WHITE			= new DColor(255, 255, 255, 0, 'WHITE');
DColor::$WHITE->finalize();

DColor::$RED			= new DColor(255, 0, 0, 0, 'RED');
DColor::$RED->finalize();

DColor::$GREEN			= new DColor(0, 255, 0, 0, 'GREEN');
DColor::$GREEN->finalize();

DColor::$BLUE			= new DColor(0, 0, 255, 0, 'BLUE');
DColor::$BLUE->finalize();

DColor::$CYAN			= new DColor(0, 255, 255, 0, 'CYAN');
DColor::$CYAN->finalize();

DColor::$MAGENTA		= new DColor(255, 0, 255, 0, 'MAGENTA');
DColor::$MAGENTA->finalize();

DColor::$YELLOW			= new DColor(255, 255, 0, 0, 'YELLOW');
DColor::$YELLOW->finalize();

DColor::$PURPLE			= new DColor(102, 0, 153, 0, 'PURPLE');
DColor::$PURPLE->finalize();

DColor::$ORANGE			= new DColor(255, 165, 0, 0, 'ORANGE');
DColor::$ORANGE->finalize();

DColor::$BROWN			= new DColor(150, 75, 0, 0, 'BROWN');
DColor::$BROWN->finalize();

DColor::$PINK			= new DColor(255, 175, 175, 0, 'PINK');
DColor::$PINK->finalize();

DColor::$GRAY			= new DColor(150, 150, 150, 0, 'GRAY');
DColor::$GRAY->finalize();

?>