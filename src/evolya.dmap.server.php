<?php

$source = '';

$image = new DImage($source);

$cluster = new DRectangle(100, 100);

class Decoupage {

	protected $cluster;
	protected $row;
	protected $col;

	public function __construct(DResizable $map, DResizable $cluster) {

		$this->cluster = $cluster;

		$this->row = (int)($map->getWidth() / $cluster->getWidth());
		$this->col = (int)($map->getHeight() / $cluster->getHeight());

		$diffWidth = $map->getWidth() - $this->col * $cluster->getWidth();
		$diffHeight = $map->getHeight() - $this->row * $cluster->getHeight();

		//$this->last = new D

	}

	public function getRowCount() {
		return $this->row;
	}

	public function getColCount() {
		return $this->col;
	}

	public function getNormalClusterDimension() {
		return $this->cluster;
	}

	public function getLastClusterDimension() {
		
	}

}

$decoupage = new Decoupage($image->getDimension(), $cluster);

?>