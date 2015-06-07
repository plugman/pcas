<?php
/*
+--------------------------------------------------------------------------
|	cache.php
|   ========================================
|	Cache Class to Save MySQL Processes
+--------------------------------------------------------------------------
*/

class cache {
	var $filename;
	var $out;
	var $cacheStatus = false;
	var $identifier;
	
	function cache($identifier = null) {
		if (!$this->enabled()) return false;
		if (!cc_is_writable(CC_ROOT_DIR.CC_DS."cache")) die("<strong>Error: ".CC_DS."cache folder is not writable!</strong> This can be made writable by accessing your store files with an FTP client or via your web hosting control panel if it has a file manager. The cache folder requires a file permission of 0777 (or as high as your hosting company allows e.g. 0775)");
		$this->identifier	 = $identifier;
		$this->filename		.= CC_ROOT_DIR.CC_DS."cache".CC_DS;
		$this->readCache(false);
	}
	
	function enabled() {
		global $config;
		return ($config['cache']) ? true : false;
	}
	
	function writeCache($dataIn) {
		if (!$this->enabled()) return false;
		$this->filename .= 	$this->path();
		$this->out = serialize($dataIn);	
		return $this->write();
	}
	
	function clearCache() {
		if (!$this->enabled()) return false;
		
		if (!empty($this->identifier)) {
			$filename = $this->filename.$this->path();
			@unlink($filename);
			
		#########################	
			
		} else {
			foreach(glob($this->filename."*.php") as $filename) {
				@unlink($filename);
			}
		}
	}
	
	function path() {
		if (!$this->enabled()) return false;
		return $this->identifier.".inc.php";
	}
	
	function write() {
		if (!$this->enabled()) return false;
		if (strlen($this->out)>0) {
		   	if (!$handle = fopen($this->filename, 'w')) {
				$error = true;
			}
			
			if (fwrite($handle, $this->out) === false) {
				$error = TRUE;
			}
			fclose($handle);
				
			if ($error) {
				die("Cache not writable! Please disable cache functionality or make it writable.");
			} else {
				return true;
			}
		}
	}
	
	function readCache($return = true) {
		if (!$this->enabled()) return false;
		$cacheFile = $this->filename.$this->path();
		if (!@file_exists($cacheFile)) {
        	return false;
		} else {
			$this->cacheStatus = true;
		}
		if ($return == true) {
			$fileConts = file_get_contents($cacheFile);
			$data = unserialize($fileConts);
			return (!empty($data)) ? $data : false;
		}
	}
}
?>