<?php

/**
 * Une représentation abstraite d'un chemin vers un dossier ou un fichier.
 * 
 * Cet objet représente un chemin dans le système de fichier, cet qui veut dire
 * qu'il peut pointer vers un fichier ou un dossier, qu'il existe réellement ou non.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @licence http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.file
 */
final class DFile {

	/**
	 * Caractère séparateur des dossiers.
	 * @var char
	 */
	const pathSeparator = '/';

	/**
	 * Caractère séparateur de l'extension de fichier.
	 * @var char
	 */
	const extensionSeparator = '.';

	/**
	 * Chemin vers le fichier, tel qu'il a été fourni au constructeur.
	 * @var string
	 */
	protected $pathname;

	/**
	 * Chemin absolue vers le fichier.
	 * @var string
	 */
	protected $absolutepath;

	/**
	 * Chemin vers le dossier contenant le fichier.
	 * @var string
	 */
	protected $dirname;

	/**
	 * Nom du fichier, avec son extension.
	 * @var string
	 */
	protected $filename;

	/**
	 * Extension du fichier.
	 * @var string
	 */
	protected $extension;

	/**
	 * Constructeur de la classe DFile.
	 *
	 * @construct DFile(string $path)
	 *  Construit un fichier vers le chemin $path.
	 * @construct DFile(string $parent, string $path)
	 *  Construit un fichier vers le chemin $path, par rapport au dossier $parent.
	 * @construct DFile(DFile $path)
	 *  Copie le fichier $path.
	 * @construct DFile(DFile parent, string $path)
	 *  Construit un fichier vers le chemin $path, par rapport au dossier $parent.
	 *
	 * @param string|DFile $arg0 Chemin vers le fichier, ou vers le parent (voir constructeurs)
	 * @param string $arg1 Chemin vers le fichier (voir constructeurs)
	 * @throws MissingArgumentException Si des arguments sont manquants.
	 * @throws BadArgumentTypeException Si des arguments ne sont pas du type attendu.
	 */
	public function __construct($arg0, $arg1=NULL) {

		$arg0 = @PHPHelper::checkArgument('$path', $arg0, 'string|DFile');

		if (is_string($arg0)) {
			if (!isset($arg1)) {
				$this->handlePathname($arg0);
			}
			else if (is_string($arg1)) {
				$this->handlePathname($arg0 . self::pathSeparator . $arg1);
			}
			else throw new BadArgumentTypeException('$path', $arg1, 'string');
		}
		if ($arg0 instanceof DFile) {
			if (!isset($arg1)) {
				$this->handlePathname($arg0->getAbsolutePath());
			}
			else if (is_string($arg1)) {
				$this->handlePathname($arg0->getAbsolutePath() . self::pathSeparator . $arg1);
			}
			else throw new BadArgumentTypeException('$path', $arg1, 'string');
		}
		// TODO Else ? throw something ?
	}

	/**
	 * Traite le chemin vers le fichier.
	 * 
	 * @param string $pathname
	 * @return void
	 */
	protected function handlePathname($pathname) {

		$this->pathname = str_replace('\\', '/', $pathname);

		while (substr_count($this->pathname, '//') > 0) {
			$this->pathname = str_replace('//', '/', $this->pathname);
		}
		if (substr($this->pathname, 0, 2) == './') {
			$this->pathname = substr($this->pathname, 2);
		}

		if (@file_exists($this->pathname)) {
			$this->absolutepath = str_replace('\\', '/', realpath($pathname));
			if (empty($this->absolutepath)) {
				$this->absolutepath = realpath('./').self::pathSeparator.$pathname;
				$this->absolutepath = str_replace('\\', '/', $this->absolutepath);
				$this->absolutepath = str_replace('//', '/', $this->absolutepath);
			}
		}
		else {
			$tmp = str_replace('\\', '/', realpath('.'));
			$tmp2 = str_replace('\\', '/', realpath('/'));
			$tmp3 = self::pathSeparator . str_replace($tmp2, '', $tmp);
			if (substr_count($this->pathname, $tmp) > 0) {
				$this->absolutepath = $this->pathname;
			}
			else if (substr_count($this->pathname, $tmp3) > 0) {
				$this->absolutepath = $tmp . self::pathSeparator . str_replace($tmp3, '', $this->pathname);
				$this->absolutepath = str_replace('//', '/', $this->absolutepath);
			}
			else {
				$tmp4 = str_replace('\\', '/', realpath('./'.dirname($this->pathname)));
				if (!empty($tmp4)) {
					$this->absolutepath = $tmp4 . self::pathSeparator . basename($this->pathname);
				}
				else {
					$this->absolutepath = $tmp . self::pathSeparator . $this->pathname;
				}
				$this->absolutepath = str_replace('//', '/', $this->absolutepath);
			}
			unset($tmp, $tmp2, $tmp3, $tmp4);
		}
		
		if (substr($this->absolutepath, -1) == '/' && strlen($this->absolutepath) > 1) {
			$this->absolutepath = substr($this->absolutepath, 0, -1);
		}
		
		$info = pathinfo($this->absolutepath);
		$this->dirname = isset($info['dirname']) ? $info['dirname'] : NULL;
		$this->filename = isset($info['basename']) ? $info['basename'] : NULL;
		$this->extension = isset($info['extension']) ? $info['extension'] : NULL;
	}

	/**
	 * Indique s'il est possible d'écrire dans le fichier. Renvoi FALSE si le fichier n'existe pas,
	 * que le script ne dispose pas des autorisations néccessaires pour écrire ou que le fichier est
	 * ouvert.
	 * 
	 * @return boolean
	 */
	public function isWritable() {
		if (!$this->exists()) return FALSE;
		return @is_writable($this->getAbsolutePath());
	}

	/**
	 * Indique s'il est possible de lire le fichier. Renvoi FALSE si le script ne dispose pas des
	 * autorisations nécessaires pour lire le fichier.
	 * 
	 * @return boolean
	 */
	public function isReadable() {
		if (!$this->exists()) return FALSE;
		return @is_readable($this->getAbsolutePath());
	}

	/**
	 * Tente de créer un nouveau fichier à l'emplacement courant. Renvoi FALSE si le fichier existe
	 * déja ou s'il est impossible de créer ce fichier.
	 * 
	 * @return boolean
	 */
	public function createNewFile() {
		if ($this->exists()) return FALSE;
		$fp = @fopen($this->getAbsolutePath(), 'w');
		if (!$fp) return FALSE;
		@fclose($fp);
		return TRUE;
	}

	/**
	 * Renvoi la taille en octets du fichier. Renvoi -1 si le fichier pointe vers un dossier.
	 * 
	 * @return int
	 */
	public function getSize() {
		if (!$this->isFile()) return -1;
		return @filesize($this->getAbsolutePath());
	}

	/**
	 * Renvoi un timestamp indiquant la dernière modification du fichier. Renvoi -1 si le fichier
	 * pointe vers un dossier.
	 * 
	 * @return int
	 */
	public function getLastModified() {
		if (!$this->isFile()) return -1;
		return @filemtime($this->getAbsolutePath());
	}

	/**
	 * Renvoi un timestamp indiquant le dernier accès au fichier. Renvoi -1 si le fichier pointe
	 * vers un dossier.
	 * 
	 * @return int
	 */
	public function getLastAccess() {
		if (!$this->isFile()) return -1;
		return @fileatime($this->getAbsolutePath());
	}

	/**
	 * Renvoi un timestamp indiquant le dernière accès à l'INODE du fichier. Renvoi -1 si le fichier
	 * pointe vers un dossier.
	 * 
	 * @return int
	 */
	public function getLastInodeAccess() {
		if (!$this->isFile()) return -1;
		return @filectime($this->getAbsolutePath());
	}

	/**
	 * Tente de créer un dossier à partir du chemin du fichier. Renvoi FALSE en cas d'erreur ou si le
	 * dossier existe déja. Si le chemin vers le dossier à créer existe de créer plusieurs sous-dossiers,
	 * cette méthode va renvoyer FALSE. Utiliser la méthode mkdirs() dans ce cas.
	 * 
	 * @return boolean
	 */
	public function mkdir() {
		if ($this->exists()) return FALSE;
		return @mkdir($this->getAbsolutePath(), 0777, FALSE);
	}

	/**
	 * Tente de créer un dossier et tous les sous-dossiers nécessaires à partir du chemin du fichier.
	 * Renvoi FALSE en cas d'erreur ou si le dossier existe déja.
	 * 
	 * @return boolean
	 */
	public function mkdirs() {
		if ($this->exists()) return FALSE;
		return @mkdir($this->getAbsolutePath(), 0777, TRUE);
	}

	/**
	 * Tente de supprimer le fichier ou le dossier. Renvoi FALSE si le fichier n'existe pas.
	 * 
	 * @return boolean
	 */
	public function delete() {
		if (!$this->exists()) return FALSE;
		if ($this->isFile()) {
			return unlink($this->getAbsolutePath());
		}
		else if ($this->isDirectory()) {
			return self::deleteDirectoryRecursive($this->getAbsolutePath());
		}
		return FALSE;
	}

	/**
	 * Indique si le fichier ou le dossier pointé existe réellement dans le système de fichier.
	 * 
	 * @return boolean
	 */
	public function exists() {
		return @file_exists($this->getAbsolutePath());
	}

	/**
	 * Indique si le fichier pointe vers un fichier physique du système de fichier.
	 * 
	 * @return boolean
	 */
	public function isFile() {
		return @is_file($this->getAbsolutePath());
	}

	/**
	 * Indique si le fichier pointe vers un dossier physique du système de fichier.
	 * 
	 * @return boolean
	 */
	public function isDirectory() {
		return @is_dir($this->getAbsolutePath());
	}

	/**
	 * Indique si le fichier pointe vers un lien physique du système de fichier.
	 * 
	 * @return boolean
	 */
	public function isLink() {
		return @is_link($this->getAbsolutePath());
	}

	/**
	 * Renvoi l'ID du propriétaire du fichier (systèmes UNIX uniquement).
	 * 
	 * @return int
	 */
	public function getOwnerID() {
		if (!$this->exists()) return NULL;
		return @fileowner($this->getAbsolutePath());
	}

	/**
	 * Renvoi le nom du propriétaire du fichier (systèmes UNIX uniquement).
	 * 
	 * @return string
	 */
	public function getOwnerName() {
		$id = $this->getOwnerID();
		if ($id === NULL) return NULL;
		if (!function_exists('posix_getpwuid')) return NULL;
		$infos = @posix_getpwuid($id);
		return isset($infos['name']) ? $infos['name'] : NULL;
	}

	/**
	 * Renvoi le contenu du fichier sous forme d'une string. Renvoi NULL si le
	 * fichier pointe vers un dossier, et FALSE si la lecture a échouée.
	 * 
	 * @return string
	 */
	public function getContents() {
		if (!$this->isFile()) return NULL;
		return @file_get_contents($this->getAbsolutePath());
	}

	/**
	 * Tente d'écrire les données $contents dans le fichier. Renvoi FALSE si le fichier pointe vers
	 * un dossier ou si l'écriture des données a échoué. 
	 * 
	 * @param string $contents Les données à écrire dans le fichier.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $data est manquant.
	 * @throws BadArgumentTypeException Si l'argument $data n'est pas du type attendu.
	 */
	public function putContents($contents) {
		$contents = @PHPHelper::checkArgument('$contents', $contents, 'string');
		if (!$this->isFile()) return FALSE;
		return @file_put_contents($this->getAbsolutePath(), $contents) ? TRUE : FALSE;
	}

	/**
	 * Renvoi le niveau de permission assigné au fichier, ou NULL si le fichier n'existe
	 * pas (systèmes UNIX uniquement).
	 * 
	 * @return int
	 */
	public function getPerms() {
		if (!$this->exists()) return NULL;
		return @fileperms($this->getAbsolutePath());
	}

	/**
	 *  Renvoi le niveau de permission assigné au fichier sous forme d'une string, ou
	 *  NULL si le fichier n'existe pas (systèmes UNIX uniquement).
	 *  
	 * @return string
	 */
	public function getPermsString() {
		if (!$this->exists()) return NULL;
		$perms = @fileperms($this->getAbsolutePath());

		if (($perms & 0xC000) == 0xC000) {
			// Socket
			$info = 's';
		} elseif (($perms & 0xA000) == 0xA000) {
			// Lien symbolique
			$info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) {
			// Regulier
			$info = '-';
		} elseif (($perms & 0x6000) == 0x6000) {
			// Block special
			$info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) {
			// Dossier
			$info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) {
			// Caractere special
			$info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) {
			// pipe FIFO
			$info = 'p';
		} else {
			// Inconnu
			$info = 'u';
		}

		// Autres
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= 	(($perms & 0x0040) ?
					(($perms & 0x0800) ? 's' : 'x' ) :
					(($perms & 0x0800) ? 'S' : '-'));

		// Groupe
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= 	(($perms & 0x0008) ?
					(($perms & 0x0400) ? 's' : 'x' ) :
					(($perms & 0x0400) ? 'S' : '-'));

		// Tout le monde
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= 	(($perms & 0x0001) ?
					(($perms & 0x0200) ? 't' : 'x' ) :
					(($perms & 0x0200) ? 'T' : '-'));

		return $info;
	}

	/**
	 * Renvoi le nom de fichier.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->filename;
	}

	/**
	 * Renvoi l'extension du fichier.
	 * 
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
	}

	/**
	 * Renvoi le chemin relatif vers le fichier.
	 * 
	 * @return string
	 */
	public function getPath() {
		return $this->pathname;
	}

	/**
	 * Renvoi le dossier parent du fichier.
	 * 
	 * @return DFile
	 */
	public function getParent() {
		return $this->dirname == NULL ? NULL : new DFile($this->dirname);
	}

	/**
	 * Renvoi le chemin absolue vers le fichier.
	 * 
	 * @return string
	 */
	public function getAbsolutePath() {
		return $this->absolutepath;
	}

	/**
	 * Si ce fichier pointe vers un dossier, renvoi tous les fichiers et sous-dossiers contenu dans le dossier.
	 * Renvoi NULL si le fichier pointe vers un fichier physique.
	 * 
	 * @return array&lt;DFile&gt;
	 */
	public function getFolderList() {
		if (!$this->isDirectory()) return NULL;
		$list = array();
		if ($dh = @opendir($this->getAbsolutePath())) {
			while (($file = @readdir($dh)) !== FALSE) {
				if ($file != '.' && $file != '..') {
					$list[] = new DFile($this, $file);
				}
			}
		}
		else return NULL;
		return $list;
	}

	/**
	 * Teste si ce fichier corresponds à un autre fichier ou à un chemin donné.
	 * 
	 * @param string|DFile $arg0
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $arg0 est manquant.
	 * @throws BadArgumentTypeException Si l'argument $arg0 n'est pas du type attendu.
	 */
	public function equals($arg0) {

		$arg0 = @PHPHelper::checkArgument('$arg0', $arg0, 'string|DFile');

		if (is_string($arg0)) {
			return $arg0 == $this->getAbsolutePath();
		}
		else if ($arg0 instanceof DFile) {
			return $arg0->getAbsolutePath() == $this->getAbsolutePath();
		}
		else throw new BadArgumentTypeException('$arg0', $arg0, 'string|DFile');
	}

	/**
	 * Méthode statique utilitaire pour supprimer un dossier et ses sous-dossiers récursivement.
	 * 
	 * @param string $dir Le chemin vers le dossier à supprimer.
	 * @return boolean
	 */
	public static function deleteDirectoryRecursive($dir) {
		if (!isset($dir)) return FALSE;
		if (is_dir($dir)) {
			if ($dh = @opendir($dir)) {
				while (($file = @readdir($dh)) !== FALSE) {
					if ($file != '.' && $file != '..') {
						if (is_dir("$dir/$file")) {
							if (!self::deleteDirectoryRecursive("$dir/$file")) return FALSE;
						}
						else {
							if (!unlink("$dir/$file")) return FALSE;
						}
					}
				}
				@closedir($dh);
				if (!rmdir($dir)) return FALSE;
			}
			else return FALSE;
		}
		else return FALSE;
		return TRUE;
	}

	/**
	 * Affiche cet objet sous forme d'une string.
	 * 
	 * @return string
	 */
	public function __toString() {
		return ''.$this->getAbsolutePath();
	}

}

?>