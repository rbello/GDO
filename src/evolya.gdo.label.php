<?php

// TODO Implementer padding, et commenter les méthodes de padding

/**
 * Interface definissant les méthodes d'un label.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.label
 */
interface DLabelInterface extends DSupportOutput {

	/**
	 * Modifier le texte du label.
	 * 
	 * @param string $text Le texte.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $text est manquant.
	 * @throws BadArgumentTypeException Si l'argument $text n'est pas une string.
	 */
	public function setText($text);
	
	/**
	 * Renvoi le texte du label.
	 * 
	 * @return string
	 */
	public function getText();
	
	/**
	 * Modifier la couleur du texte. 
	 * 
	 * @param DColorInterface $color La couleur du texte.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $color est manquant.
	 * @throws BadArgumentTypeException Si l'argument $color n'est pas une DColorInterface.
	 */
	public function setForeground($color);

	/**
	 * Renvoi la couleur du texte.
	 * 
	 * @return DColorInterface
	 */
	public function getForeground();
	
	/**
	 * Modifier la couleur d'arrière plan. La couleur peut être NULL, dans ce cas
	 * le fond sera transparent.
	 * 
	 * @param DColorInterface $color La couleur d'arrière plan.
	 * @return void
	 * @throws BadArgumentTypeException Si l'argument $color n'est pas une DColorInterface.
	 */
	public function setBackground($color);

	/**
	 * Renvoi la couleur d'arrière plan, ou NULL si elle n'est pas spécifiée.
	 * 
	 * @return DColorInterface
	 */
	public function getBackground();
	
	/**
	 * Modifier la police de caractère.
	 * 
	 * @param $font La police.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $font est manquant.
	 * @throws BadArgumentTypeException Si l'argument $font n'est pas une DFontInterface.
	 */
	public function setFont(DFontInterface $font);
	
	/**
	 * Renvoi la police utilisée pour dessiner le texte du label.
	 * 
	 * @return DFontInterface
	 */
	public function getFont();
	
	/**
	 * Modifier l'angle d'inclinaison du texte.
	 * 
	 * @param float $value L'angle en degrés.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un float valide.
	 */
	public function setAngle($value);
	
	/**
	 * Renvoi l'angle d'inclinaison du texte, en degrés.
	 * 
	 * @return float|int
	 */
	public function getAngle();
	
	/**
	 * Modifier les valeurs du padding.
	 * 
	 * @param $top Valeur du padding au dessus du texte, en pixel.
	 * @param $right Valeur du padding à droite du texte, en pixel.
	 * @param $bottom Valeur du padding au dessous du texte, en pixel.
	 * @param $left Valeur du padding à gauche du texte, en pixel.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas un entier valide.
	 */
	public function setPadding($top, $right, $bottom, $left);
	
	/**
	 * TODO
	 */
	public function getPaddingTop();
	
	/**
	 * TODO
	 */
	public function getPaddingRight();
	
	/**
	 * TODO
	 */
	public function getPaddingBottom();
	
	/**
	 * TODO
	 */
	public function getPaddingLeft();
	
	/**
	 * TODO
	 */
	public function setPaddingTop($value);
	
	/**
	 * TODO
	 */
	public function setPaddingRight($value);
	
	/**
	 * TODO
	 */
	public function setPaddingBottom($value);
	
	/**
	 * TODO
	 */
	public function setPaddingLeft($value);
	
	/**
	 * Afficher le label sous forme d'une image.
	 * 
	 * @return DImage
	 */
	public function toImage();
	
}

/**
 * Implémentation d'un label.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.label
 */
class DLabel implements DLabelInterface {

	/**
	 * Le texte à afficher dans le label.
	 * @var string
	 */
	protected $text;

	/**
	 * La couleur du texte.
	 * @var DColorInterface
	 */
	protected $foreground	= NULL;

	/**
	 * La couleur d'arrière plan.
	 * @var DColorInterface
	 */
	protected $background	= NULL;

	/**
	 * La police de caractère.
	 * @var DFontInterface
	 */
	protected $font			= NULL;

	/**
	 * L'angle d'inclinaison du texte, en degrès.
	 * @var float
	 */
	protected $angle		= 0;

	/**
	 * Le padding du label.
	 * @var array&lt;int&gt;
	 */
	protected $padding		= array(0, 0, 0, 0);

	/**
	 * Constructeur de la classe DLabel.
	 * 
	 * @construct DLabel(string $text)
	 *  Construit un label avec les paramètres par defaut et le texte $text.
	 * @construct DLabel(string $text, DFontInterface $font)
	 *  Construit un label avec la police $font et le texte $text.
	 * @construct DLabel(string $text, DColorInterface $foreground)
	 *  Construit un label avec la couleur de texte $foreground et le texte $text.
	 * @construct DLabel(string $text, DColorInterface $foreground, DColorInterface $background)
	 *  Construit un label avec la couleur de texte $foreground, la couleur de fond
	 *  $background et le texte $text.
	 * @construct DLabel(string $text, DColorInterface $foreground, DColorInterface $background, DFontInterface $font)
	 *  Construit un label avec la couleur de texte $foreground, la couleur de fond
	 *  $background, la police $font et le texte $text.
	 * 
	 * @param string $text Le texte du label.
	 * @param DFontInterface|DColorInterface $arg1 Soit la couleur de foreground, soit la police.
	 * @param DColorInterface $bg La couleur de background.
	 * @param DFontInterface $font La police
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 */
	public function __construct($text, $arg1=NULL, $bg=NULL, $font=NULL) {

		$this->setText($text);

		if ($arg1 instanceof DFontInterface) {
			$this->setFont($arg1);
		}
		
		else if ($arg1 instanceof DColorInterface) {

			$this->setForeground($arg1);

			if ($bg instanceof DColorInterface) {

				$this->setBackground($bg);

				if ($font instanceof DFontInterface) {
					$this->setFont($font);
				}
				else if ($font !== NULL) {
					throw new BadArgumentTypeException('$font', $font, 'DFontInterface');
				}

			}
			else if ($bg !== NULL) {
				throw new BadArgumentTypeException('$bg', $bg, 'DColorInterface');
			}

		}

		else if ($arg1 !== NULL) {
			throw new BadArgumentTypeException('$arg1', $arg1, 'DFontInterface|DColorInterface');
		}

		// init default properties
		if ($this->font == NULL) {
			$this->font = DFont::$DEFAULT;
		}
		if ($this->foreground == NULL) {
			$this->foreground = DColor::$BLACK;
		}
	}

	/**
	 * Modifier le texte du label.
	 * 
	 * @param string $text Le texte.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $text est manquant.
	 * @throws BadArgumentTypeException Si l'argument $text n'est pas une string.
	 */
	public function setText($text) {
		$text = @PHPHelper::checkArgument('$text', $text, 'string');
		$this->text = $text;
	}
	
	/**
	 * Renvoi le texte du label.
	 * 
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Modifier la couleur du texte.
	 * 
	 * @param DColorInterface $color La couleur du texte.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $color est manquant.
	 * @throws BadArgumentTypeException Si l'argument $color n'est pas une DColorInterface.
	 */
	public function setForeground($color) {
		$color = @PHPHelper::checkArgument('$color', $color, 'DColorInterface');
		$this->foreground = $color;
	}

	/**
	 * Renvoi la couleur du texte.
	 * 
	 * @return DColorInterface
	 */
	public function getForeground() {
		return $this->foreground;
	}
	
	/**
	 * Modifier la couleur d'arrière plan. La couleur peut être NULL, dans ce cas
	 * le fond sera transparent.
	 * 
	 * TODO Tester si ça marche bien
	 * 
	 * @param DColorInterface $color La couleur d'arrière plan.
	 * @return void
	 * @throws BadArgumentTypeException Si l'argument $color n'est pas une DColorInterface.
	 */
	public function setBackground($color) {
		$color = @PHPHelper::checkArgument('$color', $color, 'DColorInterface', TRUE);
		$this->background = $color;
	}

	/**
	 * Renvoi la couleur d'arrière plan, ou NULL si elle n'est pas spécifiée.
	 * 
	 * @return DColorInterface
	 */
	public function getBackground() {
		return $this->background;
	}
	
	/**
	 * Modifier la police de caractère.
	 * 
	 * @param $font La police.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $font est manquant.
	 * @throws BadArgumentTypeException Si l'argument $font n'est pas une DFontInterface.
	 */
	public function setFont(DFontInterface $font) {
		$font = @PHPHelper::checkArgument('$font', $font, 'DFontInterface');
		$this->font = $font;
	}
	
	/**
	 * Renvoi la police utilisée pour dessiner le texte du label.
	 * 
	 * @return DFontInterface
	 */
	public function getFont() {
		return $this->font;
	}
	
	/**
	 * Modifier l'angle d'inclinaison du texte.
	 * TODO Tester un setAngle = 0, voir si le PHPHelper ne se fait pas avoir
	 * 
	 * @param float $value L'angle en degrés.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $value est manquant.
	 * @throws BadArgumentTypeException Si l'argument $value n'est pas un float valide.
	 */
	public function setAngle($value) {
		$value = @PHPHelper::checkArgument('$value', $value, 'float', TRUE, TRUE);
		$this->angle = $value;
	}
	
	/**
	 * Renvoi l'angle d'inclinaison du texte, en degrés.
	 * 
	 * @return float|int
	 */
	public function getAngle() {
		return $this->angle;
	}
	
	/**
	 * Modifier les valeurs du padding.
	 * 
	 * @param $top Valeur du padding au dessus du texte, en pixel.
	 * @param $right Valeur du padding à droite du texte, en pixel.
	 * @param $bottom Valeur du padding au dessous du texte, en pixel.
	 * @param $left Valeur du padding à gauche du texte, en pixel.
	 * @return void
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas un entier valide.
	 */
	public function setPadding($top, $right, $bottom, $left) {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function getPaddingTop() {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function getPaddingRight() {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function getPaddingBottom() {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function getPaddingLeft() {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function setPaddingTop($value) {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function setPaddingRight($value) {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function setPaddingBottom($value) {
		throw new UnsupportedOperationException('not implemented yet');
	}
	
	/**
	 * TODO
	 */
	public function setPaddingLeft($value) {
		throw new UnsupportedOperationException('not implemented yet');
	}

	/**
	 * Afficher le label sous forme d'une image.
	 * 
	 * @return DImage
	 * TODO throws
	 */
	public function toImage() {
		
		// TODO BUG
		$box = $this->font->calculateFontBox($this->text, $this->angle);
		
		$box->setWidth($box->getWidth() + $this->padding[1] + $this->padding[3]);
		$box->setHeight($box->getHeight() + $this->padding[0] + $this->padding[2]);

		$img = new DImage($box->getWidth(), $box->getHeight(), $this->background);
		
		$fg = $this->foreground;
		if ($fg == NULL) $fg = DColor::$BLACK;
		
		$r = $this->font->draw(
			$img,
			$this->text,
			$fg,
			$this->angle,
			$this->padding[3] + $box->getX(),
			$this->padding[0] + $box->getY()
		);
		
		if (!$r) {
			// throw exception because unable to write text
			// TODO
			throw new GDOException();
		}
		
		return $img;
		
	}
	
	/**
	 * TODO
	 */
	public function render(DOutputConfig $config=NULL) {
		$res = $this->toImage();
		DOutputHelper::render($res, $config);
		$res->destroy();
	}
	
	/**
	 * TODO
	 */
	public function save(DFile $target, DOutputConfig $config=NULL) {
		$res = $this->toImage();
		DOutputHelper::save($res, $target, $config);
		$res->destroy();
	}
	
	/**
	 * TODO
	 */
	public function download($filename, DOutputConfig $config=NULL) {
		$res = $this->toImage();
		DOutputHelper::download($res, new DFile($filename), $config);
		$res->destroy();
	}
	
	/**
	 * TODO
	 */
	public function __toString() {
		$text = strlen($this->text) > 20 ? substr($this->text, 0, 17).'...' : $this->text;
		return 'Label "'.$text.'" '.
		'(fg: '.$this->foreground
		.'; bg: '.$this->background
		.'; font: '.$this->font
		.'; angle: '.$this->angle.')';
	}

}

?>