<?php
/*
+--------------------------------------------------------------------------
|	gd.inc.php
|   ========================================
|	GD Class	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
class gd {
	
	var $config;
	var $glob;
	var $img;
	var $rootpath;
	var $valid = true;
	
	function gd($imgfile = '', $width = 0, $height = 0) {
		global $config, $glob, $rootpath;
		
		$this->config	= $config;
		$this->glob		= $glob;
		$this->rootpath	= $rootpath;
		
		## Detect image format
		$this->img["format"] = preg_replace('/.*\.(.*)$/',"\\1",$imgfile);
		$this->img["format"] = strtoupper($this->img["format"]);

		if ($config['gdversion']) {
			
			if (!file_exists($imgfile)) {
				echo 'no file at '.$imgfile;
				exit;
			}
			
			$img = getimagesize($imgfile);
			$this->img['format'] = $img[2];
			
			switch($img[2]) {
				case IMAGETYPE_JPEG:
					$this->img["src"] = imagecreatefromjpeg($imgfile);
					break;
				case IMAGETYPE_PNG:
					$this->img["src"] = imagecreatefrompng($imgfile);
					break;
				case IMAGETYPE_GIF:
					$this->img["src"] = imagecreatefromgif($imgfile);
					break;
				default:
					echo "Filetype ".$imgfile." unsupported!";
					$this->valid = false;
					return false;
			}
			
			if ($width>0 && $height>0) {
				$this->img["width"] = $width;
				$this->img["height"] = $height;
			} else {
				@$this->img["width"] = imagesx($this->img["src"]);
				@$this->img["height"] = imagesy($this->img["src"]);
			}

			## Default JPEG quality
			$this->img["quality"] = $this->config['gdquality'];
			return true;
		} else {
			return false;
		}
	}

	function size_custom($width = 100, $height = 100) {
		if(!$this->valid) return false;
		$this->img["width_thumb"] = $width;
    	$this->img["height_thumb"] = $height;
	}
	
	function size_width($size = 100) {
		if(!$this->valid) return false;
		$this->img["width_thumb"] = $size;
    	@$this->img["height_thumb"] = ceil(($this->img["width_thumb"]/$this->img["width"])*$this->img["height"]);
	}
	
	function size_height($size = 100) {
		if(!$this->valid) return false;
		$this->img["height_thumb"] = $size;
    	@$this->img["width_thumb"] = ceil(($this->img["height_thumb"]/$this->img["height"])*$this->img["width"]);
	}
	
	function size_auto($size = 100) {
		if(!$this->valid) return false;
		// size automatically
		if ($this->img["width"] >= $this->img["height"]) {
			$this->img["width_thumb"] = $size;
    		@$this->img["height_thumb"] = ceil(($this->img["width_thumb"]/$this->img["width"])*$this->img["height"]);
		} else {
			$this->img["height_thumb"] = $size;
    		@$this->img["width_thumb"] = ceil(($this->img["height_thumb"]/$this->img["height"])*$this->img["width"]);
		}
	}

	function jpeg_quality($quality = 80) {
		$this->img["quality"]=$quality;
	}
	
	
	function randImage($rand) {
		## Generate a CAPTCHA image
		if (!defined('CC_ROOT_DIR')) define('CC_ROOT_DIR', $this->rootPath);
		
		## Define some default colours
		$bgColor = imagecolorallocate ($this->img["src"], 255, 255, 255);
		$textColor = imagecolorallocate ($this->img["src"], 0, 0, 0);
		$lineColor = imagecolorallocate ($this->img["src"], 215, 215, 215);
		
		## Add  Random polygons
		$noise_x = $this->img["width"] - 5;
		$noise_y = $this->img["height"] - 2;
		for ($i=0; $i<3; $i++) {
			$polyCoords = array(
				rand(5, $noise_x), rand(5, $noise_y), 
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y),
				rand(5, $noise_x), rand(5, $noise_y)
			);
			$randomcolor = imagecolorallocate($this->img["src"], rand(150, 255), rand(150, 255),rand(150, 255));
			imagefilledpolygon($this->img["src"], $polyCoords, 6, $randomcolor);
		}
		
		## write the random chars
		$font = imageloadfont(CC_ROOT_DIR.CC_DS."classes".CC_DS."gd".CC_DS."fonts".CC_DS."anonymous.gdf");
		imagestring($this->img["src"], $font, 3, 0, $rand, $textColor);
		
		## Add Random noise
		for ($i = 0; $i < 25; $i++) {
			$rx1 = rand(0,$this->img["width"]);
			$rx2 = rand(0,$this->img["width"]);
			$ry1 = rand(0,$this->img["height"]);
			$ry2 = rand(0,$this->img["height"]);
			$rcVal = rand(0,255);
			$rc1 = imagecolorallocate($this->img["src"],rand(0,255),rand(0,255),rand(100,255));
			imageline ($this->img["src"], $rx1, $ry1, $rx2, $ry2, $rc1);
		}
		$this->show(1);
	}

	function show($noThumb = false) {
		global $config;
		if(!$this->valid) return false;
		@header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	@header("Cache-Control: no-store, no-cache, must-revalidate");
	   	@header("Cache-Control: post-check=0, pre-check=0", false);
	   	@header("Pragma: no-cache");
		@header("Content-Type: ".image_type_to_mime_type($this->img["format"]));
		
		
		if ($noThumb) {
			$this->img["width_thumb"] = $this->img["width"];
			$this->img["height_thumb"] =  $this->img["height"];
		}
		
	#	if ($config['gdversion'] >= 2) {
			$this->img["des"] = imagecreatetruecolor($this->img["width_thumb"],$this->img["height_thumb"]);
			@imagecopyresampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"],$this->img["height_thumb"], $this->img["width"], $this->img["height"]);
	#	} else if ($config['gdversion'] == 1) {    
	#		$this->img["des"] = imagecreate($this->img["width_thumb"],$this->img["height_thumb"]);
	#		@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"],$this->img["height_thumb"], $this->img["width"], $this->img["height"]);    
	#	}
		
		if ($config['gdversion']>0) {
			@touch($this->img["des"]);
			
			switch($this->img['format']) {
				case IMAGETYPE_JPEG:
					imagejpeg($this->img["des"], '', $this->img["quality"]);
					break;
				case IMAGETYPE_PNG:
					imagepng($this->img["des"]);
					break;
				case IMAGETYPE_GIF:
					imagegif($this->img["des"]);
					break;
			}
			imagedestroy($this->img["des"]);
		}
	}

	function save($save = '', $noThumb = false) {
		global $config;
		if(!$this->valid) return false;
		if ($noThumb) {
			$this->img["width_thumb"]	= $this->img["width"];
			$this->img["height_thumb"]	=  $this->img["height"];die();
		}
		
	#	if ($config['gdversion'] >= 2) {
			$this->img["des"] = imagecreatetruecolor($this->img["width_thumb"],$this->img["height_thumb"]);
			@imagecopyresampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
	#	} else if ($config['gdversion'] == 1) {
	#		$this->img["des"] = imagecreate($this->img["width_thumb"],$this->img["height_thumb"]);
	#		@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
	#	}
 		
		if ($config['gdversion']>0) {
			switch($this->img['format']) {
				case IMAGETYPE_JPEG:
					imagejpeg($this->img["des"], $save, $this->img["quality"]);
					break;
				case IMAGETYPE_PNG:
					imagepng($this->img["des"], $save);
					break;
				case IMAGETYPE_GIF:
					imagegif($this->img["des"], $save);
					break;
			}
			imagedestroy($this->img["des"]);
			@chmod($this->img["des"], 0644);
		}
	}
}
?>