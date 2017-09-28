<?php

/**
 * L'interface DImageStackInterface est implémenté par les objets capable de contenir
 * plusieurs calques, et de réaliser un assemblage d'image.
 * <br>Pour plus de simplicité, on désigne cet objet par <code>stack</code> dans
 * cette documenation.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagestack
 */
interface DImageStackInterface extends DSupportOutput {

	/**
	 * Ajouter le calque $layer dans le stack.
	 * <br>Renvoi TRUE si le calque a bien été ajouté.
	 * 
	 * @param DLayerInterface $layer Le calque à ajouter.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function addLayer(DLayerInterface $layer);

	/**
	 * Retirer le calque $layer du stack.
	 * <br>Renvoi TRUE si le calque a bien été retiré du stack.
	 * 
	 * @param DLayerInterface $layer Le calque à retirer.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function removeLayer(DLayerInterface $layer);

	/**
	 * Renvoi TRUE si le calque $layer est déja contenu dans le stack.
	 * 
	 * @param DLayerInterface $layer Le calque.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function containsLayer(DLayerInterface $layer);

	/**
	 * Renvoi le nombre de calques dans le stack.
	 * 
	 * @return int
	 */
	public function getLayerCount();

	/**
	 * Renvoi un tableau contenant tous les calques.
	 * <br>Le sens du tableau est le suivant : les calques du haut sont les premiers
	 * dans le tableau.
	 * 
	 * @return array&lt;DLayerInterface&gt;
	 */
	public function getLayers();

	/**
	 * Renvoi la position du calque $layer.
	 * <br>Renvoi <code>-1</code> si le calque n'a pas été trouvé.
	 * 
	 * @param DLayerInterface $layer Le calque.
	 * @return int
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function getLayerOrdinal(DLayerInterface $layer);

	/**
	 * Monter le calque $layer.
	 * <br>Les calques le plus haut passent au dessus des autres.
	 * 
	 * @param DLayerInterface $layer Le calque à repositionner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function moveUp(DLayerInterface $layer);

	/**
	 * Descendre le calque $layer.
	 * <br>Les calques le plus bas passent sous les autres et sont masqués.
	 * 
	 * @param DLayerInterface $layer Le calque à repositionner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function moveDown(DLayerInterface $layer);

	/**
	 * Transforme le stack en resource, en assemblant les differents calques.
	 * <br>Renvoi NULL si le stack ne contient aucun calque.
	 * 
	 * @return DResource
	 * @throws MemoryException Si les dimensions de la ressource excédent les capacités mémoire.
	 * @throws UnsupportedOperationException Si la fonction imagecreatetruecolor est incapable de
	 * 	créer la ressource.
	 * @throws InvalidDimensionException Si les dimensions de l'image à créer sont invalides.
	 */
	public function toResource();

}

/**
 * La classe DImageStack est un conteneur de calques.
 * <br>Il permet d'assembler plusieurs calques afin de créer une image à partir
 * d'un empilement d'images.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.imagestack
 */
class DImageStack implements DImageStackInterface {

	/**
	 * LIFO
	 * @var array&lt;DLayer&gt;
	 */
	protected $layers = array();

	/**
	 * Ajouter le calque $layer dans le stack.
	 * <br>Renvoi TRUE si le calque a bien été ajouté.
	 * 
	 * @param DLayerInterface $layer Le calque à ajouter.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function addLayer(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		if ($this->containsLayer($layer)) {
			return FALSE;
		}
		$this->layers[] = $layer;
		return TRUE;
	}

	/**
	 * Retirer le calque $layer du stack.
	 * <br>Renvoi TRUE si le calque a bien été retiré du stack.
	 * 
	 * @param DLayerInterface $layer Le calque à retirer.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function removeLayer(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		$key = NULL;
		foreach ($this->layers as $k => $v) {
			if ($v === $layer) {
				$key = $k;
				break;
			}
		}
		if ($key !== NULL) {
			unset($this->layers[$key]);
			$this->layers = array_values($this->layers);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Renvoi TRUE si le calque $layer est déja contenu dans le stack.
	 * 
	 * @param DLayerInterface $layer Le calque.
	 * @return boolean
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function containsLayer(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		foreach ($this->layers as $l) {
			if ($l === $layer) return TRUE;
		}
		return FALSE;
	}

	/**
	 * Renvoi le nombre de calques dans le stack.
	 * 
	 * @return int
	 */
	public function getLayerCount() {
		return sizeof($this->layers);
	}

	/**
	 * Renvoi la position du calque $layer.
	 * <br>Renvoi <code>-1</code> si le calque n'a pas été trouvé.
	 * 
	 * @param DLayerInterface $layer Le calque.
	 * @return int
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function getLayerOrdinal(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		$i = 0;
		foreach ($this->layers as $l) {
			if ($l === $layer) return $i;
			$i++;
		}
		return -1;
	}

	/**
	 * Renvoi un tableau contenant tous les calques.
	 * <br>Le sens du tableau est le suivant : les calques du haut sont les premiers
	 * dans le tableau.
	 * 
	 * @return array&lt;DLayerInterface&gt;
	 */
	public function getLayers() {
		// Retourne le tableau dans l'autre sens, car dans la logique les derniers calques sont sur le dessus
		$copy = array();
		for ($i = sizeof($this->layers) - 1; $i >= 0; $i--) {
			$copy[] = $this->layers[$i];
		}
		return $copy;
	}

	/**
	 * Monter le calque $layer.
	 * <br>Les calques le plus haut passent au dessus des autres.
	 * 
	 * @param DLayerInterface $layer Le calque à repositionner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function moveUp(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		$copy = array();
		$save = NULL;
		foreach ($this->layers as $l) {
			if ($l === $layer) {
				$save = $layer;
				continue;
			}
			$copy[] = $l;
			if ($save != NULL) {
				$copy[] = $save;
				$save = NULL;
			}
		}
		if ($save != NULL) $copy[] = $save;
		$this->layers = $copy;
	}

	/**
	 * Descendre le calque $layer.
	 * <br>Les calques le plus bas passent sous les autres et sont masqués.
	 * 
	 * @param DLayerInterface $layer Le calque à repositionner.
	 * @return void
	 * @throws MissingArgumentException Si l'argument $layer est manquant.
	 */
	public function moveDown(DLayerInterface $layer) {
		if (!isset($layer)) {
			throw new MissingArgumentException('$layer', 'DLayer');
		}
		$copy = array();
		for ($i = 0; $i < sizeof($this->layers); $i++) {
			$l = $this->layers[$i];
			$m = @$this->layers[$i+1];
			if ($m === $layer) {
				$copy[] = $m;
				$i++;
			}
			$copy[] = $l;
		}
		$this->layers = $copy;
	}

	/**
	 * (non-PHPdoc)
	 * @see DSupportOutput#render($config)
	 */
	public function render(DOutputConfig $config=NULL) {
		DOutputHelper::render(
			$this->toResource(),
			$config
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DSupportOutput#save($target, $config)
	 */
	public function save(DFile $target, DOutputConfig $config=NULL) {
		DOutputHelper::save(
			$this->toResource(),
			$target,
			$config
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DSupportOutput#download($filename, $config)
	 */
	public function download($filename, DOutputConfig $config=NULL) {
		DOutputHelper::download(
			$this->toResource(),
			$filename,
			$config
		);
	}

	/**
	 * Transforme le stack en resource, en assemblant les differents calques.
	 * <br>Renvoi NULL si le stack ne contient aucun calque.
	 * 
	 * @return DResource
	 * @throws MemoryException Si les dimensions de la ressource excédent les capacités mémoire.
	 * @throws UnsupportedOperationException Si la fonction imagecreatetruecolor est incapable de
	 * 	créer la ressource.
	 * @throws InvalidDimensionException Si les dimensions de l'image à créer sont invalides.
	 */
	public function toResource() {

		if ($this->getLayerCount() == 0) {
			return NULL;
		}

		$minX = 0;
		$minY = 0;
		$width = 0;
		$height = 0;

		foreach ($this->layers as $layer) {

			if (!$layer->isVisible()) continue;

			// Absolute location
			if ($layer->getLayerLocation() instanceof DAbsoluteLayerLocation) {
				if ($layer->getLayerLocation()->getX() < $minX) {
					$minX = $layer->getLayerLocation()->getX();
				}
				if ($layer->getLayerLocation()->getY() < $minY) {
					$minY = $layer->getLayerLocation()->getY();
				}
			}

		}

		foreach ($this->layers as $layer) {

			if (!$layer->isVisible()) continue;

			$w = $layer->getResource()->getInfos()->getWidth();
			$h = $layer->getResource()->getInfos()->getHeight();

			// Absolute location
			if ($layer->getLayerLocation() instanceof DAbsoluteLayerLocation) {
				$w += $layer->getLayerLocation()->getX();//$minX;
				$h += $layer->getLayerLocation()->getY();//$minY;
			}

			if ($w > $width) $width = $w;
			if ($h > $height) $height = $h;
			unset($w, $h);
		}

		$size = new DRectangle($width-$minX, $height-$minY);

		$res = new DImage($size);

		foreach ($this->layers as $layer) {
			if (!$layer->isVisible()) continue;
			$p = $layer->getLayerLocation()->calculateLocation(
				$size,
				new DRectangle(
					$layer->getResource()->getInfos()->getWidth(),
					$layer->getResource()->getInfos()->getHeight()
				)
			);
			if ($layer->getLayerLocation() instanceof DAbsoluteLayerLocation) {
				$p->addX(abs($minX));
				$p->addY(abs($minY));
			}
			// TODO Voir la conf nan ?
			$r = imagecopyresampled(
				$res->getGDResource(),
				$layer->getResource()->getGDResource(),
				$p->getX(),
				$p->getY(),
				0,
				0,
				$layer->getResource()->getInfos()->getWidth(),
				$layer->getResource()->getInfos()->getHeight(),
				$layer->getResource()->getInfos()->getWidth(),
				$layer->getResource()->getInfos()->getHeight()
			);
		}

		return $res;
	}

}

?>