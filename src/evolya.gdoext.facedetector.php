<?php

/**
 * Detection de visage.
 * 
 * Exemple d'utilisation :
 * <php>
 * $img = new DImage('portrait.png');
 * 
 * $face = DFaceDetector::getInstance()->detectFace($img);
 * 
 * if ($face != NULL) {
 * &nbsp;// $face is a Bounds
 * }
 * </php>
 * 
 * <br>Portage d'un code du projet OpenCV
 * <br>Auteur Karthik Tharavaad (karthik_tharavaad@yahoo.com), portage par Maurice Svay (maurice@svay.com)
 *
 * @author Karthik Tharavaad
 * @licence http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @package evolya.gdoext.facedetector
 */
class DFaceDetector {

	/**
	 * Contient l'instance du DFaceDetector.
	 * @var DFaceDetector
	 */
	protected static $INSTANCE = NULL;

	/**
	 * Contient les données servant à la detection.
	 * @var object
	 */
	protected $detection_data;

	/**
	 * Renvoi l'unique instance du DFaceDetector. 
	 * 
	 * @return DFaceDetector
	 * @pattern Singleton
	 */
	public static function getInstance() {
		if (self::$INSTANCE == NULL) {
			self::$INSTANCE = new DFaceDetector();
		}
		return self::$INSTANCE;
	}

	/**
	 * Supprime le DFaceDetector.
	 *  
	 * @return void
	 */
	public static function dispose() {
		if (self::$INSTANCE != NULL) {
			unset(self::$INSTANCE->detection_data);
			self::$INSTANCE = NULL;
		}
	}

	/**
	 * Constructeur de la classe DFaceDetector.
	 * 
	 * @throws FileNotFoundException Si le fichier evolya.gdoext.facedetector.dat est introuvable.
	 * @throws InvalidDataException Si la lecture du fichier evolya.gdoext.facedetector.dat est invalide.
	 */
	protected function __construct() {
		$data = new DFile(
			PHPHelper::getSourceFolder(),
			'evolya.gdoext.facedetector.dat'
		);
		if (!$data->isFile()) {
			throw new FileNotFoundException($data);
		}
		$data = $data->getContents();
		if (!$data) {
			throw new InvalidDataException('getContents');
		}
		$data = @unserialize($data);
		if (!$data) {
			throw new InvalidDataException('unserialize');
		}
		$this->detection_data = $data;
	}

	/**
	 * Detecter un visage dans la source $src.
	 * 
	 * @param string|DFile|DImage $src L'image source.
	 * @return Bounds
	 * @throws MissingArgumentException Si l'argument $src est manquant.
	 * @throws BadArgumentTypeException Si l'argument $src n'est pas d'un type requis.
	 */
	public function detectFace($src) {

		$file = @PHPHelper::checkArgument('$src', $src, 'string|DFile|DImage');

		if (is_string($src)) {
			$src = new DFile($src);
		}

		if ($src instanceof DFile) {
			if (!$src->isFile()) {
				throw new FileNotFoundException();
			}
			$fromFile = TRUE;
			$src = new DImage($src);
		}
		else {
			$fromFile = FALSE;
			$src = new DImage($src);
		}

		
		$im_width = $src->getInfos()->getWidth();
		$im_height = $src->getInfos()->getHeight();

		$ratio = $this->calculateRatio($im_width, $im_height);

		if ($ratio != 0) {
			$src->setDimensions($im_width / $ratio, $im_height / $ratio);
		}
		$canvas = $src->getGDResource();

		$stats = $this->get_img_stats($canvas);

		$face = $this->do_detect_greedy_big_to_small(
			$stats['ii'],
			$stats['ii2'],
			$stats['width'],
			$stats['height']
		);

		if ($ratio != 0) {
			$face->scale($ratio);
		}

		// On detruit l'image si elle a été créer pour l'occasion, ou s'il s'agit d'un redimensionnement
		if ($fromFile || $ratio != 0) {
			$src->destroy();
		}

		return $face;
	}

	/**
	 * Calculer le coeficient de ratio des dimensions données. 
	 * 
	 * @param int $w Largeur de l'image.
	 * @param int $h Longeur de l'image.
	 * @return float
	 */
	protected function calculateRatio($w, $h) {
		$ratio = 0;
		$diff_width = 320 - $w;
		$diff_height = 240 - $h;
		if ($diff_width > $diff_height) {
			$ratio = $w / 320;
		} else {
			$ratio = $h / 240;
		}
		return $ratio;
	}

	/**
	 * Calculer les informations de la ressource $canvas.
	 * 
	 * @param gd_resource $canvas La ressource GD à utiliser.
	 * @return array&lt;mixed&gt;
	 */
	protected function get_img_stats($canvas){
		$image_width = imagesx($canvas);
		$image_height = imagesy($canvas);     
		$iis = $this->compute_ii(
			$canvas,
			$image_width,
			$image_height
		);

		return array(
			'width' => $image_width,
			'height' => $image_height,
			'ii' => $iis['ii'],
			'ii2' => $iis['ii2']
		);
	}

	/**
	 * Compute II.
	 * 
	 * @param gd_resource $canvas La ressource GD à utiliser.
	 * @param int $image_width Largeur de l'image.
	 * @param int $image_height Hauteur de l'image.
	 * @return array&lt;mixed&gt;
	 */
	protected function compute_ii($canvas, $image_width, $image_height) {
		$ii_w = $image_width+1;
		$ii_h = $image_height+1;
		$ii = array();
		$ii2 = array();

		for ($i=0; $i < $ii_w; $i++) {
			$ii[$i] = 0;
			$ii2[$i] = 0;
		}

		for ($i=1; $i < $ii_w; $i++) {  
			$ii[$i*$ii_w] = 0;
			$ii2[$i*$ii_w] = 0; 
			$rowsum = 0;
			$rowsum2 = 0;

			for ($j=1; $j<$ii_h; $j++) {
				$rgb = @imagecolorat($canvas, $j, $i);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;
				$grey = (0.2989*$red + 0.587*$green + 0.114*$blue) >> 0; // this is what matlab uses
				$rowsum += $grey;
				$rowsum2 += $grey*$grey;

				$ii_above = ($i-1)*$ii_w + $j;
				$ii_this = $i*$ii_w + $j;

				@$ii[$ii_this] = $ii[$ii_above] + $rowsum;
				@$ii2[$ii_this] = $ii2[$ii_above] + $rowsum2;
			}
		}
		return array(
			'ii' => $ii,
			'ii2' => $ii2
		);
	}

	/**
	 * Effectue la recherche de visage dans l'image.
	 * 
	 * @param array&lt;mixed&gt; $ii Résultat II.
	 * @param array&lt;mixed&gt; $ii2 Résultat II2.
	 * @param int $width Largeur de l'image.
	 * @param int $height Hauteur de l'image.
	 * @return DBounds
	 */
	protected function do_detect_greedy_big_to_small($ii, $ii2, $width, $height) {
		$s_w = $width / 20.0;
		$s_h = $height / 20.0;
		$start_scale = $s_h < $s_w ? $s_h : $s_w;
		$scale_update = 1 / 1.2;

		for ($scale = $start_scale; $scale > 1; $scale *= $scale_update) {
			$w = (20*$scale) >> 0;
			$endx = $width - $w - 1;
			$endy = $height - $w - 1;
			$step = max( $scale, 2 ) >> 0;
			$inv_area = 1 / ($w*$w);
			for ($y = 0; $y < $endy ; $y += $step) {
				for ($x = 0; $x < $endx ; $x += $step) {
					$passed = $this->detect_on_sub_image($x, $y, $scale, $ii, $ii2, $w, $width+1, $inv_area);
					if ($passed) {
						return new DBounds($x, $y, $w, $w);
					}
				}
			}
		}
		return NULL;

	}

	/**
	 * Rechercher un visage dans une portion d'image.
	 * 
	 * @param int $x
	 * @param int $y
	 * @param float $scale
	 * @param array&lt;mixed&gt; $ii
	 * @param array&lt;mixed&gt; $ii2
	 * @param int $w
	 * @param int $iiw
	 * @param float $inv_area
	 * @return boolean
	 */
	protected function detect_on_sub_image($x, $y, $scale, $ii, $ii2, $w, $iiw, $inv_area) {

		$mean = @($ii[($y+$w)*$iiw + $x + $w] + $ii[$y*$iiw+$x] - $ii[($y+$w)*$iiw+$x] - $ii[$y*$iiw+$x+$w]) * $inv_area;
		$vnorm = @($ii2[($y+$w)*$iiw + $x + $w] + $ii2[$y*$iiw+$x] - $ii2[($y+$w)*$iiw+$x] - $ii2[$y*$iiw+$x+$w]) * $inv_area - ($mean*$mean);    
		$vnorm = $vnorm > 1 ? sqrt($vnorm) : 1;

		$passed = TRUE;

		for ($i_stage = 0; $i_stage < count($this->detection_data); $i_stage++) {
			$stage = $this->detection_data[$i_stage];  
			$trees = $stage[0];  

			$stage_thresh = $stage[1];
			$stage_sum = 0;

			for ($i_tree = 0; $i_tree < count($trees); $i_tree++) {
				$tree = $trees[$i_tree];
				$current_node = $tree[0];
				$tree_sum = 0;

				while ($current_node != NULL){

					$vals = $current_node[0];
					$node_thresh = $vals[0];
					$leftval = $vals[1];
					$rightval = $vals[2];
					$leftidx = $vals[3];
					$rightidx = $vals[4];
					$rects = $current_node[1];

					$rect_sum = 0;
					for ($i_rect = 0; $i_rect < count($rects); $i_rect++) {

						$s = $scale;
						$rect = $rects[$i_rect];
						$rx = ($rect[0]*$s+$x) >> 0;
						$ry = ($rect[1]*$s+$y) >> 0;
						$rw = ($rect[2]*$s) >> 0;  
						$rh = ($rect[3]*$s) >> 0;
						$wt = $rect[4];

						$r_sum = @($ii[($ry+$rh)*$iiw + $rx + $rw] + $ii[$ry*$iiw+$rx] - $ii[($ry+$rh)*$iiw+$rx] - $ii[$ry*$iiw+$rx+$rw]) * $wt;
						$rect_sum += $r_sum;
					}

					$rect_sum *= $inv_area;

					$current_node = null;
					if ($rect_sum >= $node_thresh * $vnorm) {
						if ($rightidx == -1) {
							$tree_sum = $rightval;
						}
						else {
							$current_node = $tree[$rightidx];
						}
					} else {
						if ($leftidx == -1) {
							$tree_sum = $leftval;
						}
						else {
							$current_node = $tree[$leftidx];
						}
					}

				}
				$stage_sum += $tree_sum;
			} 
			if ($stage_sum < $stage_thresh) {
				return FALSE;
			}
		} 
		return TRUE;
	}

}

?>