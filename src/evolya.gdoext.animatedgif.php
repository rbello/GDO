<?php

/**
 * Représente une frame dans une image GIF animée.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdoext.animatedgif
 */
class DGIFFrame implements DImageFrame {

	/**
	 * Contenu de la frame sous forme d'une string.
	 * @var string
	 */
	protected $contents;

	/**
	 * Méthode de disposal.
	 * @var int
	 * @link http://www.webreference.com/content/studio/disposal.html
	 */
	protected $dispos;

	/**
	 * Delai d'affichage de la frame avant la suivante.
	 * @var int
	 */
	protected $delay;

	/**
	 * Constructeur de la classe DGIFFrame.
	 * 
	 * @construct DGIFFrame(string $contents, int $disposalMethod, int $delay)
	 *  Constructeur par défaut de la classe DGIFFrame.
	 * 
	 * @param string $contents Le contenu de la frame.
	 * @param int $disposalMethod La méthode de disposal.
	 * @param int $delay Le delai d'affichage de la frame.
	 */
	public function __construct($contents, $disposalMethod, $delay) {
		$this->contents = $contents;
		$this->dispos = $disposalMethod;
		$this->delay = $delay;
	}

	/**
	 * Renvoi le contenu de la frame, sous forme d'une string.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * Renvoi la méthode de disposal.
	 * 
	 * <li>Unspecified. Use this option to replace one full-size, non-transparent frame with another.</li>
	 * <li>Do Not Dispose. In this option, any pixels not covered up by the next frame continue to
	 * display. This is the setting used most often for optimized animations. In the flashing light
	 * animation, we wanted to keep the first frame displaying, so the subsequent optimized frames
	 * would just replace the part that we wanted to change. That's what Do Not Dispose does.</li>
	 * <li>Restore to Background. The background color or background tile - rather than a previous
	 * frame - shows through transparent pixels. In the GIF specification, you can set a background
	 * color. In Netscape, it's the page's background color or background GIF that shows through.</li>
	 * <li>Restore to Previous. Restores to the state of a previous, undisposed frame. Figures 1 and
	 * 2 show the effect of this option. Figure 1 shows the three component frames of the animation.
	 * The first frame is a full-frame image of the letter A. For the second frame, we took just the
	 * top half of the letter and applied a Gaussian blur in Photoshop. For the third frame we took
	 * just the bottom half of the letter and applied Photoshop's filter.</li>
	 * 
	 * @return int
	 * @link http://www.webreference.com/content/studio/disposal.html
	 */
	public function getDisposalMethod() {
		return $this->dispos;
	}

	/**
	 * Renvoi le délai d'affichage de cette frame.
	 * 
	 * @return int
	 */
	public function getDelay() {
		return $this->delay;
	}

	/**
	 * Detruit le frame et libère la mémoire associée.
	 * 
	 * @return void
	 */
	public function dispose() {
		$this->contents = NULL;
		$this->dispose = NULL;
		$this->delay = NULL;
	}

	/**
	 * Affiche cet objet sous forme d'une stirng.
	 * 
	 * @return string
	 */
	public function __toString() {
		return '[Frame delay='.$this->delay.' disposal='.$this->dispos.']';
	}

}

/**
 * Un DGIFDecoder est capable de lire une fichier GIF animé et d'en
 * extraire les differentes frames.
 *
 * @author László Zsidi (zsidi.laszlo@freemail.hu)
 * @license Freeware
 * @package evolya.gdoext.animatedgif
 * @link http://www.phpclasses.org/browse/package/3234.html
 */
class DGIFDecoder implements DImageFrameContainer {

	/**
	 * Couleur de transparence.
	 * @var DColor
	 */
	protected $transparent = NULL;

	/**
	 * Indice du curseur lors de l'application de la couleur de transparence.
	 * @var int
	 */
	protected $transparentI = 0;

	/**
	 * Buffers de lecture utilisés par la classe.
	 * @var array&lt;byte&gt;
	 */
	protected $buffer = array();

	/**
	 * Contient toutes les frames extraites par le GIFDcoder.
	 * @var array&lt;DGIFFrame&gt;
	 */
	protected $frames = array();

	/**
	 * Contient les délais d'affichage de chaque frame.
	 * @var array&lt;int&gt;
	 */
	protected $delays = array();

	/**
	 * Contient les méthodes de disposal pour chaque frame.
	 * @var array&lt;int&gt;
	 */
	protected $dispos = array();

	/**
	 * Enregistre le contenu du fichier GIF pour lecture.
	 * @var string
	 */
	protected $stream = '';

	/**
	 * Indique si le contenu des frames doit être enregistré, ou si
	 * on se contente de compter les frames.
	 * @var boolean
	 */
	protected $save = TRUE;

	/**
	 * Compteur du nombre de frames dans le GIF.
	 * @var int
	 */
	protected $count = 0;

	/**
	 * Variable temporaire utilisée pour composer chaque frame.
	 * @var string
	 */
	protected $str = '';

	/**
	 * Curseur.
	 * TODO Variable utilisée?
	 * @var int
	 */
	protected $bfseek = 0;

	/**
	 * Vaut 1 si le GIF doit boucler à la fin de la dernière frame.
	 * @var int
	 */
	protected $anloop = 0;

	/**
	 * Tableau de bits utilisé dans le processus.
	 * @var array&lt;byte&gt;
	 */
	protected $screen = array();

	/**
	 * Enregistre les paramètres de la couleur au cours du processus.
	 * @var array&lt;int&gt;
	 */
	protected $globalc = array();

	/**
	 * Vaut 1 si les frames sont dans l'ordre dans le GIF.
	 * @var int
	 */
	protected $sorted;

	/**
	 * Variable utilisée dans le processus.
	 * @var int
	 */
	protected $colorS;

	/**
	 * Variable utilisée dans le processus.
	 * @var int
	 */
	protected $colorC;

	/**
	 * Variable utilisée dans le processus.
	 * TODO Est-ce utile de mettre cette variable dans les membres de classe?
	 * @var int
	 */
	protected $colorFlag;

	/**
	 * Constructeur de la classe DGIFDecoder.
	 * 
	 * @param string $stream Contenu du GIF.
	 * @param boolean $save Indique si le contenu des frames doit être extrait et enregistré.
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws InvalidDataException Si le fichier GIF n'est pas au format valide.
	 */
	public function __construct($stream, $save=TRUE) {

		$stream = @PHPHelper::checkArgument('$stream', $stream, 'string');
		$save = @PHPHelper::checkArgument('$save', $save, 'boolean');
		
		if (substr($stream, 0, 3) != 'GIF') {
			throw new InvalidDataException('argument $stream is not a stream file contents');
		}

		$this->stream = $stream;
		$this->save = $save;

		$this->getByte(6);
		$this->getByte(7);

		$this->screen = $this->buffer;
		$this->colorFlag = $this->buffer[4] & 0x80 ? 1 : 0;
		$this->sorted = $this->buffer[4] & 0x08 ? 1 : 0;
		$this->colorC = $this->buffer[4] & 0x07;
		$this->colorS = 2 << $this->colorC;

		if ($this->colorFlag == 1) {
			$this->getByte(3 * $this->colorS);
			$this->globalc = $this->buffer;
		}
		for ($cycle = 1; $cycle; ) {
			if ($this->getByte(1)) {
				switch ($this->buffer[0]) {
					case 0x21:
						$this->readExtensions();
						break;
					case 0x2C:
						$this->readDescriptor();
						break;
					case 0x3B:
						$cycle = 0;
						break;
				}
			}
			else {
				$cycle = 0;
			}
		}

		$this->order();
		$this->dispose();
	}

	/**
	 * Ordonne les frames.
	 * 
	 * @return void
	 */
	protected function order() {
		$frames = array();
		$i = 0;
		foreach ($this->frames as $frame) {
			$tmp = new DGIFFrame(
				$frame,
				@$this->dispos[$i],
				@$this->delays[$i]
			);
			$frames[] = $tmp;
			$i++;
		}
		unset($tmp);
		$this->frames = $frames;
	}

	/**
	 * Détruit cet objet.
	 * 
	 * @return void
	 */
	protected function dispose() {
		$this->transparentI = NULL;
		$this->buffer = NULL;
		$this->stream = NULL;
		$this->str = NULL;
		$this->delays = NULL;
		$this->dispos = NULL;
		$this->bfseek = NULL;
		$this->screen = NULL;
		$this->globalc = NULL;
		$this->sorted = NULL;
		$this->colorS = NULL;
		$this->colorC = NULL;
		$this->colorFlag = NULL;
	}

	/**
	 * Lit les extensions dans les données.
	 * 
	 * @return void
	 */
	protected function readExtensions() {
		$this->getByte(1);
		if ($this->buffer[0] == 0xff) {
			while (true) {
				$this->getByte(1);
				if (($u = $this->buffer[0]) == 0x00) {
					break;
				}
				$this->getByte($u);
				if ($u == 0x03) {
					$this->anloop = ($this->buffer[1] | $this->buffer[2] << 8);
				}
			}
		}
		else {
			while (true) {
				$this->getByte(1);
				if (($u = $this->buffer[0]) == 0x00) {
					break;
				}
				$this->getByte($u);
				if ($u == 0x04) {
					if (@$this->buffer[4] & 0x80) {
						if ($this->save) {
							$this->dispos[] = ($this->buffer[0] >> 2) - 1;
						}
					}
					else {
						if ($this->save) {
							$this->dispos[] = ($this->buffer[0] >> 2) - 0;
						}
					}

					if ($this->save) {
						$this->delays[] = ($this->buffer[1] | $this->buffer[2] << 8);
					}

					if ($this->buffer[3]) {
						$this->transparentI = $this->buffer[3];
					}
				}
			}
		}
	}

	/**
	 * Lit la zone de description du fichier GIF.
	 * 
	 * @return void
	 */
	protected function readDescriptor() {
		$screen = array();

		$this->getByte(9);
		$screen = $this->buffer;
		$colorFlag = $this->buffer[8] & 0x80 ? 1 : 0;
		if ($colorFlag) {
			$code = $this->buffer[8] & 0x07;
			$sort = $this->buffer[8] & 0x20 ? 1 : 0;
		}
		else {
			$code = $this->colorC;
			$sort = $this->sorted;
		}

		$size = 2 << $code;
		$this->screen[4] &= 0x70;
		$this->screen[4] |= 0x80;
		$this->screen[4] |= $code;
		if ($sort) {
			$this->screen[4] |= 0x08;
		}

		 # GIF Data Begin
		if ($this->transparentI) {
			$this->str = "GIF89a";
		}
		else {
			$this->str = "GIF87a";
		}

		$this->putByte($this->screen);

		if ( $colorFlag == 1 ) {
			$this->getByte ( 3 * $size );
			if ($this->transparentI) {
				try {
					$this->transparent = new DColor(
						$this->buffer[3 * $this->transparentI + 0],
						$this->buffer[3 * $this->transparentI + 1],
						$this->buffer[3 * $this->transparentI + 2]
					);
				} catch (Exception $ex) { }
			}
			$this->putByte($this->buffer);
		}
		else {
			if ($this->transparentI) {
				try {
					$this->transparent = new DColor(
						$this->globalc[3 * $this->transparentI + 0],
						$this->globalc[3 * $this->transparentI + 1],
						$this->globalc[3 * $this->transparentI + 2]
					);
				} catch (Exception $ex) { }
			}
			$this->putByte($this->globalc);
		}

		if ($this->transparentI) {
			$this->str .= "!\xF9\x04\x1\x0\x0".chr($this->transparentI)."\x0";
		}

		$this->str .= chr(0x2C);
		$screen[8] &= 0x40;
		$this->putByte($screen);
		$this->getByte(1);
		$this->putByte($this->buffer);

		while (true) {
			$this->getByte(1);
			$this->putByte($this->buffer);
			if (($u = $this->buffer[0]) == 0x00) {
				break;
			}
			$this->getByte($u);
			$this->putByte($this->buffer);
		}
		$this->str .= chr(0x3B);

		# GIF Data End
		if ($this->save) {
			$this->frames[] = $this->str;
		}
		$this->count++;
	}

	/**
	 * Place les $len prochains bytes dans le buffer.
	 * 
	 * @param int $len Longeur.
	 * @return int 0 ou 1
	 */
	protected function getByte($len) {
		$this->buffer = array();
		for ($i = 0; $i < $len; $i++) {
			if ($this->bfseek > strlen($this->stream)) {
				return 0;
			}
			$this->buffer[] = ord($this->stream { $this->bfseek++ });
		}
		return 1;
	}

	/**
	 * Place tous les bytes dans la variable qui compose la frame.
	 * 
	 * @param array&lt;byte&gt; $bytes Les bytes.
	 * @return void
	 */
	protected function putByte($bytes) {
		foreach ($bytes as $byte) {
			$this->str .= chr($byte);
		}
	}

	/**
	 * Renvoi une liste contenant toutes les frames du conteneur.
	 * 
	 * @return array&lt;DImageFrame&gt;
	 */
	public function getFrames() {
		return $this->frames;
	}

	/**
	 * Renvoi le nombre de frames du conteneur.
	 * 
	 * @return int
	 */
	public function getFrameCount() {
		return $this->count;
	}

	/**
	 * Renvoi 1 si le GIF doit boucler à la fin de la dernière frame.
	 * 
	 * @return int
	 */
	public function getLoops() {
		return $this->anloop;
	}

	/**
	 * Renvoi la couleur de transparence détectée dans l'image.
	 * 
	 * @return DColor
	 */
	public function getTransparentColor() {
		return $this->transparent;
	}

	/**
	 * Detruit le conteneur de frame ainsi que toutes les frames contenues.
	 * 
	 * @return void
	 */	
	public function disposeAll() {
		$this->dispose();
		$this->transparent = NULL;
		$this->frames = NULL;
		$this->count = NULL;
		$this->anloop = NULL;
	}

}

/**
 * Cette classe permet d'assembler plusieurs DGIFFrame afin de produire une image
 * GIF animée.
 *
 * TODO Virer la variable $this->DIS : ce paramètre se trouve dans chaque frame
 * TODO Gestion des erreurs invalide (exit!)
 * TODO $this->IMG ça sert à quelque chose ce truc ?
 * TODO Harmoniser les noms des variables et des arguments de méthodes
 * TODO Dispose
 * TODO Constructeur : verification des paramètres à mettre à jour
 * TODO Ajouter plein d'unset pour optimiser les grosses boucles
 *
 * @author László Zsidi (zsidi.laszlo@freemail.hu)
 * @license Freeware
 * @version 2.05
 * @package evolya.gdoext.animatedgif
 * @link http://www.phpclasses.org/browse/package/3163.html
 */
class DGIFEncoder {

	/**
	 * Contient l'image GIF le long des opérations.
	 * Au début, contient l'en-tête GIF 6 bytes.
	 * @var string
	 */
	protected $GIF = 'GIF89a';

	/**
	 * Nombre de boucles de répétition du fichier GIF.
	 * @var int
	 */
	protected $loop;

	/**
	 * Méthode de disposal.
	 * @var int
	 */
	protected $DIS = 2;

	/**
	 * Couleur de transparence.
	 * @var DColorInterface
	 */
	protected $color = -1;

	/**
	 * Status de l'image pendant le processus.
	 * @var int
	 */
	protected $IMG = -1;

	/**
	 * TODO VIRER CE TRUC
	 */
	protected $ERR = Array (
		'ERR00' => 'Does not supported function for only one image!',
		'ERR01' => 'Source is not a GIF image!',
		'ERR02' => 'Unintelligible flag',
		'ERR03' => 'Does not make animation from animated GIF source'
	);

	/**
	 * Constructeur de la classe DGIFEncoder.
	 * 
	 * @construct DGIFEncoder(array&lt;DGIFFrame&gt; $frames, DColorInterface $color, int $loop)
	 * 
	 * @param array&lt;DGIFFrame&gt; $frames Les frames.
	 * @param DColorInterface $color La couleur de transparence.
	 * @param int $loop Indique si le gif doit boucler. Valeurs : 1 ou 0.
	 */
	public function __construct($frames, DColorInterface $color, $loop) {

		if (!isset($frames)) {
			throw new IllegalArgumentException('argument $frames missing');
		}
		if (!is_array($frames)) {
			throw new IllegalArgumentException('argument $frames must be an array');
		}

		if (!isset($loop)) {
			throw new IllegalArgumentException('argument $loop missing');
		}
		if (!is_int($loop)) {
			throw new IllegalArgumentException('argument $loop must be an integer');
		}

		$this->loop = $loop > -1 ? $loop : 0;
		// TODO Cette variable ne devrais pas exister, voir l'autre to do
		$this->DIS = 3;//($disposal > -1) ? (($disposal < 3) ? $disposal : 3) : 2;

		if ($color != NULL) {
			$this->color = ($color->getRedValue() | ($color->getGreenValue() << 8) | ($color->getBlueValue() << 16));
		}

		foreach ($frames as $frame) {
			$buf = $frame->getContents();

			if (substr($buf, 0, 6) != 'GIF87a' && substr($buf, 0, 6) != 'GIF89a') {
				// ERR01
				exit('ERR01');
			}
			for ($j = (13 + 3 * (2 << (ord($buf { 10 }) & 0x07))), $k = TRUE; $k; $j++) {
				switch ($buf { $j }) {
					case '!':
						if ((substr($buf, ($j + 3), 8)) == 'NETSCAPE') {
							//ERR03
							exit('ERR03');
						}
						break;
					case ';':
						$k = FALSE;
						break;
				}
			}

			unset($buf);
		}

		$first = $frames[0]->getContents();

		$this->addHeader($first);

		foreach ($frames as $frame) {
			$this->addFrame($frame, $first);
		}

		unset($first);

		$this->addFooter();
	}

	/**
	 * Ajouter l'entête du fichier GIF.
	 * 
	 * @param string $first
	 * @return void
	 */
	protected function addHeader($first) {
		$cmap = 0;

		if (ord($first { 10 }) & 0x80) {
			$cmap = 3 * (2 << (ord($first { 10 }) & 0x07));

			$this->GIF .= substr($first, 6, 7);
			$this->GIF .= substr($first, 13, $cmap);
			$this->GIF .= "!\377\13NETSCAPE2.0\3\1".$this->word($this->loop)."\0";
		}
	}

	/**
	 * Ajouter la frame $frame dans le GIF.
	 * 
	 * @param DGIFFrame $frame
	 * @param string $first
	 * @return void
	 */
	protected function addFrame(DGIFFrame $frame, $first) {

		$buf = $frame->getContents();
		$d = $frame->getDelay();

		$locals_str = 13 + 3 * (2 << (ord($buf { 10 }) & 0x07));

		$locals_end = strlen($buf) - $locals_str - 1;
		$locals_tmp = substr($buf, $locals_str, $locals_end);

		$global_len = 2 << (ord($first { 10 }) & 0x07);
		$locals_len = 2 << (ord($buf { 10 }) & 0x07);

		$global_rgb = substr(
			$first,
			13,
			3 * (2 << (ord($first { 10 }) & 0x07))
		);

		$locals_rgb = substr(
			$buf,
			13,
			3 * (2 << (ord($buf { 10 }) & 0x07))
		);

		$locals_ext = "!\xF9\x04"
			.chr(($this->DIS << 2) + 0) // TODO On devrais pas récupérer DIS dans la frame ?
			.chr(($d >> 0) & 0xFF)
			.chr(($d >> 8) & 0xFF)
			."\x0\x0";

		if ($this->color > -1 && ord($buf { 10 }) & 0x80) {
			for ($j = 0; $j < (2 << (ord($buf { 10 }) & 0x07)); $j++) {
				if (
						ord($locals_rgb { 3 * $j + 0 }) == (($this->color >> 16) & 0xFF) &&
						ord($locals_rgb { 3 * $j + 1 }) == (($this->color >>  8) & 0xFF) &&
						ord($locals_rgb { 3 * $j + 2 }) == (($this->color >>  0) & 0xFF)
					) {
					$locals_ext = "!\xF9\x04"
						.chr(($this->DIS << 2) + 1)
						.chr(($d >> 0) & 0xFF)
						.chr(($d >> 8) & 0xFF )
						.chr($j)
						."\x0";
					break;
				}
			}
		}

		switch ($locals_tmp { 0 }) {
			case '!':
				$locals_img = substr($locals_tmp, 8, 10);
				$locals_tmp = substr($locals_tmp, 18, strlen($locals_tmp) - 18);
				break;
			case ',':
				$locals_img = substr($locals_tmp, 0, 10);
				$locals_tmp = substr($locals_tmp, 10, strlen($locals_tmp) - 10);
				break;
		}
		if (ord($buf { 10 }) & 0x80 && $this->IMG > -1) {
			if ($global_len == $locals_len) {
				if ($this->blockCompare($global_rgb, $locals_rgb, $global_len)) {
					$this->GIF .= ($locals_ext . $locals_img . $locals_tmp);
				}
				else {
					$byte  = ord($locals_img { 9 });
					$byte |= 0x80;
					$byte &= 0xF8;
					$byte |= (ord($first { 10 }) & 0x07);
					$locals_img { 9 } = chr ($byte);
					$this->GIF .= ($locals_ext . $locals_img . $locals_rgb . $locals_tmp);
				}
			}
			else {
				$byte  = ord($locals_img { 9 });
				$byte |= 0x80;
				$byte &= 0xF8;
				$byte |= (ord($buf { 10 }) & 0x07);
				$locals_img { 9 } = chr ($byte);
				$this->GIF .= ($locals_ext . $locals_img . $locals_rgb . $locals_tmp);
			}
		}
		else {
			$this->GIF .= ($locals_ext . $locals_img . $locals_tmp);
		}
		$this->IMG = 1;
	}

	/**
	 * Ajouter le pied de page du GIF.
	 * 
	 * @return void
	 */
	protected function addFooter() {
		$this->GIF .= ';';
	}

	/**
	 * @param string $GlobalBlock
	 * @param string $LocalBlock
	 * @param int $len
	 * @return int 1 ou 0
	 */
	protected function blockCompare($GlobalBlock, $LocalBlock, $len) {

		for ($i = 0; $i < $len; $i++) {
			if (
					$GlobalBlock { 3 * $i + 0 } != $LocalBlock { 3 * $i + 0 } ||
					$GlobalBlock { 3 * $i + 1 } != $LocalBlock { 3 * $i + 1 } ||
					$GlobalBlock { 3 * $i + 2 } != $LocalBlock { 3 * $i + 2 }
				) {
				return 0;
			}
		}

		return 1;
	}

	/**
	 * @param int $int
	 * @return int
	 */
	protected function word($int) {
		return (chr($int & 0xFF).chr(($int >> 8) & 0xFF));
	}

	/**
	 * Renvoi le contenu de l'image GIF une fois assemblée.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->GIF;
	}

}

?>