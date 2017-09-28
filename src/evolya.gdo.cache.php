<?php

/**
 * L'interface DCacheInterface peut être implémentée par les classes qui ont la capacité
 * de gerer la mise en cache et la restauration de ressources images.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.cache
 */
interface DCacheInterface {

	/**
	 * Enregistre la resource $img dans le cache, avec l'identifiant $id.
	 * La configuration $config permet de specifier le format d'enregistrement
	 * de l'image.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @param DImage $img La ressource à enregistrer.
	 * @param DOutputConfig $config La configuration de sortie de l'image.
	 * @return boolean
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws PictureNotBound Si la ressource fournie n'est pas liée.
	 */
	public function inputResource($id, DImage $img, DOutputConfig $config=NULL);

	/**
	 * Tente de rechercher la ressource avec l'identifiant $id et
	 * l'envoyer vers la sortie standard.
	 * <br>Il est conseillé de ne pas passer d'argument $config mais
	 * de laisser cette méthode déterminer automatiquement la bonne
	 * configuration appropriée.
	 * <br>Renvoi TRUE si la ressource a été trouvée et a été
	 * correctement envoyée au navigateur.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @param DOutputConfig $config La configuration de sortie.
	 * @return boolean
	 */
	public function outputResource($id, DOutputConfig $config=NULL);

	/**
	 * Renvoi le chemin vers le dossier d'enregistrement du cache. 
	 * 
	 * @return DFile
	 */
	public function getStorePath();

	/**
	 * Recherche la ressource stoquée en cache avec l'identifiant $id.
	 * <br>Si la ressource existe, cette méthode renvoi un DFile pointant
	 * vers le fichier de la ressource. Renvoi NULL si le cache ne contient
	 * aucune ressource portant cet identifiant.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @return DFile
	 */
	public function getStoredResourceById($id);

}

/**
 * Implémentation de l'interface DCacheInterface. Il s'agit d'un cache mixte IO, capable
 * d'enregistrer une image dans le cache, de la restaurer, ou de produire une URL pointant
 * directement vers l'image dans le cache.
 * 
 * Ce cache enregistre toutes les resources dans un répertoire, qui est indiqué au constructeur.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.cache
 */
class DCache implements DCacheInterface {

	/**
	 * Dossier d'enregistrement du cache.
	 * @var DFile
	 */
	protected $path;

	/**
	 * Constructeur de la classe DCache.
	 * 
	 * @construct DCache(string path)
	 * @construct DCache(string path, int $ttl)
	 * @construct DCache(DFile path)
	 * @construct DCache(string path, int $ttl)
	 * 
	 * TODO Traiter TTL
	 * TODO Documenter
	 * 
	 * @param string|DFile $path Chemin vers le dossier d'enregistrement du cache.
	 * @param int $ttl Temps de vie des éléments stoqués dans le cache, au delà de ce temps ils sont supprimés.
	 * @throws MissingArgumentException Si l'argument $path est manquant.
	 * @throws BadArgumentTypeException Si l'argument $path n'est pas d'un type requis.
	 * @throws InvalidArgumentException Si le chemin $path ne pointe pas vers un dossier valide et disponible en lecture/écriture.
	 */
	public function __construct($path, $ttl=0) {

		$path = @PHPHelper::checkArgument('$path', $path, 'string|DFile');
		$ttl = @PHPHelper::checkArgument('$ttl', $ttl, 'int');

		if (is_string($path)) {
			$path = new DFile($path);
		}
		if (!$path->isDirectory()) {
			throw new InvalidArgumentException('given path is not an existing directory');
		}
		if (!$path->isReadable() || !$path->isWritable()) {
			throw new InvalidArgumentException('given path is not readable and/or writable');
		}

		$this->path = $path;
	}

	/**
	 * Renvoi le chemin vers le dossier d'enregistrement du cache. 
	 * 
	 * @return DFile
	 */
	public function getStorePath() {
		return new DFile($this->path);
	}

	/**
	 * Enregistre la resource $img dans le cache, avec l'identifiant $id.
	 * La configuration $config permet de specifier le format d'enregistrement
	 * de l'image.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @param DImage $img La ressource à enregistrer.
	 * @param DOutputConfig $config La configuration de sortie de l'image.
	 * @return boolean
	 * @throws MissingArgumentException Si un des arguments est manquant.
	 * @throws BadArgumentTypeException Si un des arguments n'est pas du type requis.
	 * @throws PictureNotBound Si la ressource fournie n'est pas liée.
	 */
	public function inputResource($id, DImage $img, DOutputConfig $config=NULL) {

		$id = @PHPHelper::checkArgument('$id', $id, 'string');
		$img = @PHPHelper::checkArgument('$img', $img, 'DImage');
		$config = @PHPHelper::checkArgument('$config', $config, 'DOutputConfig', FALSE);

		if ($img instanceof DImageInterface) {
			return DOutputHelper::save(
				$img,
				$this->createPath($id, $config),
				$config
			);
		}
		else {
			return DOutputHelper::save(
				$img,
				$this->createPath($id, $config),
				$config
			);
		}
	}

	/**
	 * Tente de rechercher la ressource avec l'identifiant $id et
	 * l'envoyer vers la sortie standard.
	 * <br>Il est conseillé de ne pas passer d'argument $config mais
	 * de laisser cette méthode déterminer automatiquement la bonne
	 * configuration appropriée.
	 * <br>Renvoi TRUE si la ressource a été trouvée et a été
	 * correctement envoyée au navigateur.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @param DOutputConfig $config La configuration de sortie.
	 * @return boolean
	 */
	public function outputResource($id, DOutputConfig $config=NULL) {

		$id = @PHPHelper::checkArgument('$id', $id, 'string');
		$config = @PHPHelper::checkArgument('$config', $config, 'DOutputConfig', FALSE);

		$file = $this->getStoredResourceById($id);
		if ($file == NULL) return FALSE;

		if ($config == NULL) {
			$config = DOutputHelper::getBestConfigurationByFileExtension($file->getExtension());
			if ($config == NULL) {
				$config = DOutputHelper::$PNG;
			}
		}

		if (headers_sent()) {
			throw new HeadersSentException();
		}

		$fp = @fopen($file->getAbsolutePath(), 'rb');

		@header('Content-Type: '.$config->getContentType());
		@header('Content-Length: '.$file->getSize());

		if (!$fp) return FALSE;

		if (!@fpassthru($fp)) return FALSE;
		@fclose($fp);

		return TRUE;
	}

	/**
	 * Recherche la ressource stoquée en cache avec l'identifiant $id.
	 * <br>Si la ressource existe, cette méthode renvoi un DFile pointant
	 * vers le fichier de la ressource. Renvoi NULL si le cache ne contient
	 * aucune ressource portant cet identifiant.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @return DFile
	 */
	public function getStoredResourceById($id) {

		$id = @PHPHelper::checkArgument('$id', $id, 'string');

		$list = $this->path->getFolderList();
		$id = self::cleanId($id);
		foreach ($list as $file) {
			if (!$file->isFile()) continue;
			$name = $file->getName();
			if ($file->getExtension() != NULL) {
				$name = substr($name, 0, -1 * (1 + strlen($file->getExtension())));
			}
			if ($name == $id) {
				return $file;
			}
		}
		return NULL;

	}

	/**
	 * Cette méthode a deux usages : si $useTtl vaut TRUE, toutes les ressources
	 * du cache qui n'ont pas été accédées depuis un certain temps (cf. $ttl du
	 * constructeur) sont supprimées. Si $useTtl vaut FALSE, toutes les ressources
	 * du cache sont supprimées.
	 * 
	 * @return void
	 */
	public function cleanCache($useTtl=TRUE) {
		throw new UnsupportedOperationException();
	}

	/**
	 * 
	 * @param string $id Un identifiant de ressource pour le cache.
	 * @return string
	 */
	protected static function cleanId($id) {
		/*$lenght = strlen($id);
		$acceptedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
		$r .= '';
		for ($i = 0; $i < $length; $i++) {
			if (strpos($acceptedChars, $id { $i }) === FALSE) {
				$r .= '_';
			}
			else {
				$r .= $id { $i };
			}
		}
		return $r;*/
		return md5($id);
	}

	/**
	 * Fabrique un chemin de fichier valide pour enregistrer la ressource
	 * avec l'identifiant $id.
	 * <br>La paramètre $config permet de déterminer l'extension que doit avoir le fichier.
	 * 
	 * @param string $id L'identifiant de la resource dans le cache.
	 * @param DOutputConfig $config La configuration de sortie.
	 * @return DFile
	 */
	protected function createPath($id, DOutputConfig $config=NULL) {
		if ($config == NULL) {
			$config = DOutputHelper::$PNG;
		}
		return new DFile(
			$this->path,
			self::cleanId($id) . '.' . $config->getFileExtension()
		);
	}

}

?>