<?php
/*
+--------------------------------------------------------------------------
|	filemanager.class.php
|   ========================================
|	Filemanager Class	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
class FileManager {
	
	private $_config;
	private $_db;
	
	private $_manageDir;
	
	private $_imageDir;
	private $_hiresDir;
	private $_thumbDir;
	private $_downloadDir;
	
	
	##############################################
	
	public function __construct($currentDir = '/') {
		global $glob;
		## Register some Filemanager constants
		if (!defined('FM_FILETYPE_IMG'))	define('FM_FILETYPE_IMG',	1);
		if (!defined('FM_FILETYPE_DL'))		define('FM_FILETYPE_DL',	2);
		
		## Grab the global config files
		$this->_config	= array_merge($GLOBALS['config'], $glob);
		## Define the paths
				
		$this->_imageDir	= (isset($this->_config['imageRoot']) && !empty($this->_config['imageRoot'])) ? $this->_config['imageRoot'] : CC_ROOT_DIR.'/images/uploads'.$currentDir;
	#	$this->_hiresDir	= (isset($this->_config['hiresRoot']) && !empty($this->_config['hiresRoot'])) ? $this->_config['hiresRoot'] : CC_ROOT_DIR.'/images/products/hi-res';
		$this->_thumbDir	= (isset($this->_config['thumbRoot']) && !empty($this->_config['thumbRoot'])) ? $this->_config['thumbRoot'] : CC_ROOT_DIR.'/images/uploads/thumbs'.$currentDir;
		$this->_downloadDir	= (isset($this->_config['downloadRoot']) && !empty($this->_config['downloadRoot'])) ? $this->_config['downloadRoot'] : '';
		
		## Connect to the database
		$this->_db =& $GLOBALS['db'];
	}
	
	##############################################

	public function showDownloads($page = 1) {
		## List downloads using the default options
		return $this->getFileList(FM_FILETYPE_DL, $page);
	}
	
	public function showImages($page = 1) {
		## List images using the default options
		return $this->getFileList(FM_FILETYPE_IMG, $page);
	}
	
	##############################################
	
	public function upload($type) {
		switch ($type) {
			case FM_FILETYPE_DL:
				foreach ($_FILES as $file) {
					if (!empty($file['tmp_name']) && $file['error'] == '0' && is_uploaded_file($file['tmp_name'])) {
						## Move to download directory
						$filepath = $this->_downloadDir.$file['name'];
						move_uploaded_file($file['tmp_name'], $filepath);
						$record = array(
							'type'		=> "'".FM_FILETYPE_DL."'",
							'filepath'	=> "'".str_replace(CC_DS,'/' , $filepath)."'",
							'filename'	=> "'".$file['name']."'",
							'filesize'	=> "'".$file['size']."'",
							'mimetype'	=> "'".$file['type']."'",
							'md5hash'	=> "'".md5_file($filepath)."'",
						);
						$this->_db->insert($this->_config['dbprefix'].'ImeiUnlock_filemanager', $record);
					}
				}
				break;
			case FM_FILETYPE_IMG:
			default:
				## GD is designed to handle Image Uploading
			#	$gd	= new GD($this->_imageDir);
				foreach ($_FILES as $file) {
					if (!empty($file['tmp_name']) && $file['error'] == '0' && is_uploaded_file($file['tmp_name'])) {
						## Use GD to resize and move the images
					#	$gd->gdUpload($file['tmp_name'], $file['name']);
					#	$gd->gdSave($file['name']);
					
						$filepath	= str_replace(CC_ROOT_DIR.CC_DS, '', $this->_imageDir).CC_DS.$file['name'];
						$record		= array(
							'type'		=> "'".FM_FILETYPE_IMG."'",
							'filepath'	=> "'".str_replace(CC_DS,'/' , $filepath)."'",
							'filename'	=> "'".$file['name']."'",
							'filesize'	=> "'".$file['size']."'",
							'mimetype'	=> "'".$file['type']."'",
							'md5hash'	=> "'".md5_file($file['tmp_name'])."'",
						);
						$this->_db->insert($this->_config['dbprefix'].'ImeiUnlock_filemanager', $record);
					}
				}
		}
		return true;
	}
	
	public function showFileList($type, $page = 0, $perPage = 50) {
		global $glob;
		
		if (!is_numeric($type)) {
			switch ($type) {
				case 'download':
					$type = FM_FILETYPE_DL;
					break;
				case 'image':
					$type = FM_FILETYPE_IMG;
					break;
				default:
					return false;
			}
		}
		
		$limit = sprintf('LIMIT %d,%d', ($page*$perPage), $perPage);
		
		$sql	= sprintf("SELECT * FROM %sImeiUnlock_filemanager WHERE type = '%d' AND disabled = '0' ORDER BY filename ASC %s", $this->_config['dbprefix'], $type, $limit);
		$files	= $this->_db->select($sql);
		
		if ($files) {
			foreach ($files as $key => $file) {
				if (!file_exists(CC_ROOT_DIR.CC_DS.$file['filepath'])) {
					unset($files[$key]);
				}
			}
			return $files;
		} else {
			## Uh-oh - lets run the database builder, and have a last ditch attempt at showing something
			if ($this->buildDatabase()) {
				return $this->showFileList($type);
			}
		}
		return false;
	}
	
	public function buildDatabase($purge = false) {
		$dir		= str_replace('/', CC_DS, $this->_imageDir);
		$i			= 0;
		$count		= 0;
		
		$fileArray	= walkDir($dir, true, false, false, true, $i);
		
		if ($fileArray) {
			if ($purge) {
				$this->_db->truncate($this->_config['dbprefix'].'ImeiUnlock_filemanager');
			} else {
				$dupes	= sprintf("SELECT filepath FROM %sImeiUnlock_filemanager WHERE 1 ORDER BY filename ASC;", $this->_config['dbprefix']);
				$dupes	= $this->_db->select($dupes);
				if ($dupes) {
					foreach ($dupes as $file) {
						$dupeArray[] = $file['filepath'];
					}
				}
			}
			## require(CC_ROOT_DIR.CC_DS.'classes'.CC_DS.'gd'.CC_DS.'gd.inc.php'); Removed as it is toooo much load. Thumbnail tool needs to be used instead.
			foreach ($fileArray as $key => $file) {
				if (preg_match('#(gif|jpeg|jpg|png)$#i', $file) && (!isset($dupeArray) || !in_array(str_replace(CC_ROOT_DIR.CC_DS, '', $file), $dupeArray))) {
					$data	= getimagesize($file);
					$record = array(
						'type'		=> "'".FM_FILETYPE_IMG."'",
						'filepath'	=> "'".addslashes(str_replace(CC_DS,'/' ,str_replace(CC_ROOT_DIR.CC_DS, '', $file)))."'",
						'filename'	=> "'".addslashes(basename($file))."'",
						'filesize'	=> "'".filesize($file)."'",
						'mimetype'	=> "'".$data['mime']."'",
						'md5hash'	=> "'".md5_file($file)."'",
					);
					
					## Hash comparison
				#	$target_dir	= addslashes(str_replace(CC_DS, '/', str_replace(CC_ROOT_DIR.CC_DS, '', dirname($file).CC_DS));
				#	$checksql	= sprintf("SELECT COUNT(`file_id`) as Count FROM %sImeiUnlock_filemanager WHERE md5hash = %s AND filepath LIKE '%s%%';", $this->_config['dbprefix'], $record['md5hash'], $target_dir);
					$checksql	= sprintf("SELECT COUNT(`file_id`) as Count FROM %sImeiUnlock_filemanager WHERE filepath = %s;", $this->_config['dbprefix'], $record['md5hash'], $record['filepath']);
					$checkquery	= $this->_db->select($checksql);
					if ($checkquery[0]['Count'] == 0) {
						$this->_db->insert($this->_config['dbprefix'].'ImeiUnlock_filemanager', $record);
						$count++;
						## Delete original thumbnail
						/* Why?!
						$thumbPath	= imgPath($file, true, 'root');
						
						if (file_exists($thumbPath)) {
							$newConfig['uploadSize'] -= @filesize($thumbPath);
							@unlink($thumbPath);
							$newThumb[$thumbName] = false;
						}
						$img = new gd($file);
						$img->size_auto($this->_config['gdthumbSize']);
						$img->save($thumbPath);
						*/
						
					}
				}
			}
			if ($count > 0) return true;
		}
		return false;
	}
		
	public function addFile($type, $fileArray) {
		$filepath	= str_replace(str_replace(CC_DS, '/', CC_ROOT_DIR.CC_DS), '', str_replace(CC_DS, '/', $this->_imageDir)).$fileArray['name'];
		$record		= array(
			'type'		=> "'".$type."'",
			'filepath'	=> "'".$filepath."'",
			'filename'	=> "'".$fileArray['name']."'",
			'filesize'	=> "'".$fileArray['size']."'",
			'mimetype'	=> "'".$fileArray['type']."'",
			'md5hash'	=> "'".$fileArray['hash']."'",
		);
		$this->_db->insert($this->_config['dbprefix'].'ImeiUnlock_filemanager', $record);
	}
	
	##############################################
	## Filesystem Methods
	
	public function createDirectory($new_dir, $current_dir) {
		## Check the path is safe
		if (!strstr($new_dir, '..') && !strstr($current_dir, '..') && is_writable($current_dir)) {
			$dir = CC_ROOT_DIR.CC_DS.$current_dir.CC_DS.$new_dir;
			## Create a new directory for saving
			return mkdir(str_replace('/', DIRECTORY_SEPARATOR, $dir), 0777, true);
		}
		return false;
	}
	
	public function deleteFile($file_id) {
		$sql	= sprintf("SELECT `filepath` FROM `%sImeiUnlock_filemanager` WHERE `file_id` = %d", $this->_config['dbprefix'], $file_id);
		$result = $this->_db->select($sql);
		if ($result) {
			## Check for thumbnails
			$thumbnail = imgPath($result[0]['filepath'], true, 'root');
			if (file_exists($thumbnail)) {
				unlink($thumbnail);
			}
			## Check for the original file
			$file = imgPath($result[0]['filepath'], false, 'root');
			if (file_exists($file)) {
				unlink($file);
			}
			$sql = sprintf('DELETE FROM `%sImeiUnlock_filemanager` WHERE `file_id` = %d', $this->_config['dbprefix'], $file_id);
			$this->_db->misc($sql);
			## Delete from image idx table
			$idx_path = str_replace("images/uploads/","",$result[0]['filepath']);
			## Update image counts
			$sql	= sprintf("SELECT `productId` FROM `%sImeiUnlock_img_idx` WHERE `img` = '%s'", $this->_config['dbprefix'], $idx_path);
			$affectedProducts = $this->_db->select($sql);
			if($affectedProducts){
				foreach($affectedProducts as $row){
					## Set product count for product to n -1
					$sql = sprintf('UPDATE `%sImeiUnlock_inventory` SET `noImages` = `noImages` -1 WHERE `productId` = %d', $this->_config['dbprefix'], $row['productId']);
					$this->_db->misc($sql);
				}
			}
			## Remove associated file name from inventory table
			$sql = sprintf("UPDATE `%sImeiUnlock_inventory` SET `image` = '' WHERE `image` = '%s'", $this->_config['dbprefix'], $idx_path);
			$this->_db->misc($sql);
			## Remove index from table
			$sql = sprintf("DELETE FROM `%sImeiUnlock_img_idx` WHERE `img` = '%s'", $this->_config['dbprefix'], $idx_path);
			$this->_db->misc($sql);
			return true;
		}
		return false;
	}
	
	##############################################
}

?>
