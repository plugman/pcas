<?php
if (!defined('CC_INI_SET')) die("Access Denied");

class Installer {

	var $db;
	
	var $installPath;
	
	var $moduleName;
	
	var $sqlQueryLog;
	var $sqlQueryCount	= 0;
	var $sqlSucessCount	= 0;
	var $sqlFailCount	= 0;
	
	var $sqlSecureTables= array();
	
	###############################
	
	function __construct($installPath) {
		@ini_set('auto_detect_line_endings', true);
	//	$this->installPath = $installPath;
	}
	
	function keySearch($find, $array, $keyname = null) {
		foreach ($array as $key => $arrayVal) {
			if (is_array($arrayVal)) {
				$result = $this->keySearch($find, $arrayVal, $key);
				if ($result != false) return $result;
			} else {
				if (strtolower($arrayVal) == strtolower($find)) {
					return (!empty($keyname)) ? $keyname : $key;
				}
			}
		}
		return false;
	}
	
	function prepare($language, $uploadName = 'upload') {
		$install['allow'] = false;
		
		if (is_uploaded_file($_FILES[$uploadName]['tmp_name']) && preg_match('#\.(zip)$#iu', $_FILES[$uploadName]['name'])) {
			$ziplib = new dUnzip2($_FILES[$uploadName]['tmp_name']);
			$filelist = $ziplib->getList();
			
			if (!in_array('package.conf.inc', $filelist)) {
				foreach ($filelist as $entry => $detail) {
					$file = explode(CC_DS, $entry);
					if (!preg_match('#(^[\.])#i', $file[0])) {
						$moduleDir = $file[0];
						break;
					}
				}
			} else {
				$moduleDir = false;
			}
			
			@move_uploaded_file($_FILES[$uploadName]['tmp_name'], CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');
			$configData	= ($moduleDir === false) ? unserialize($ziplib->unzip('package.conf.inc')) : unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'));
			
			## Check for existing installations
			$installDir	= keySearch($configData['type'], $allowedTypes);
			
			if ($moduleDir === false) {
				$installTo = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS;
			} else {
				$installTo = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS.str_replace(' ', '_', $configData['name']).CC_DS;
			}
			
			if ($installDir != false && is_dir($installTo)) {
				if (file_exists($installTo.'package.conf.inc')) {
					$configExisting = unserialize(file_get_contents($installTo.'package.conf.inc'));
					if (version_compare($configData['version'], $configExisting['version'], '>')) {
						$install['text']	= sprintf($language['lang_upgrade_text'], $configData['name'], $configExisting['version'], $configData['version']);
						$install['button']	= $language['lang_button_upgrade'];
						$install['allow']	= true;
					} else {
						$install['text']	= sprintf($language['lang_latest_text'], $configData['name']);	
					}
				}
			} else {
				$install['text']	= sprintf($language['lang_install_text'], $configData['name'], $configData['version']);
				$install['allow']	= true;
				$install['button']	= $language['lang_button_install'];
			}
			return $install;
		}
		return false;
	}
	
	function install() {
		## Install the files
		$package = (!empty($package)) ? $package : CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip';
		
		$ziplib = new dUnzip2($package);
		$filelist = $ziplib->getList();
		
		if (!in_array('package.conf.inc', $filelist)) {
			foreach ($filelist as $entry => $detail) {
				$file = explode(CC_DS, $entry);
				if (!preg_match('#(^[\.])#i', $file[0])) {
					$moduleDir = $file[0];
					break;
				}
			}
		} else {
			$moduleDir = false;
		}
			
		$configData	= ($moduleDir == false) ? unserialize($ziplib->unzip('package.conf.inc')) : unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'));
		$installDir	= $this->keySearch($configData['type'], $allowedTypes);
		
		if ($installDir !== false) {
			
			if ($moduleDir == false) {
				$this->installPath = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS.str_replace(' ', '_', $configData['name']);
			} else {
				$this->installPath = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS;
			}
			
			if (!is_dir($installpath)) {
				@mkdir($installpath, 0777, true);
			}
			$ziplib->unzipAll($installpath);
			@chmod($this->installPath, 0755);
			$errors = $ziplib->getErrors();
			if (!$errors) {
				$msg = sprintf($language['lang_install_success'], $configData['name']);
			} else {
				$error  = $language['lang_install_failed'].'<br />';
				$error .= implode('<br />', $errors);
			}
		}
		@unlink(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');
		
		if (file_exists($this->installPath.CC_DS.'sql')) {
			foreach (glob(CC_DS.'*.sql') as $sqlfile) {
				$this->installSQL($sqlfile);
			}
			$return['SQL'] = array(
				'SuccessCount'	=> $this->sqlSuccessCount,
				'QueryCount'	=> $this->sqlQueryCount,
				'FailureCount'	=> $this->sqlFailCount,
				'Querylog'		=> $this->sqlQueryLog,
			);
		}
		
	}
	
	### Process SQL Files ###
	function loadSQLfile($sqlFile = '', $dbprefix = '') {
		if (!empty($sqlFile) && file_exists($this->installPath.CC_DS.$sqlFile)) {
			$sql = file_get_contents($this->installPath.CC_DS.$sqlFile);
			if (!empty($dbprefix)) $sql = str_replace('`ImeiUnlock_', '`'.$dbprefix.'ImeiUnlock_', $sql);
			return $sql;
		}
		return false;
	}
	
	function installSQL($sqlFile) {
		
		if (($sql = $this->loadSqlFile($sqlFile)) === false) return false;		
		
		$queryArray = explode('; #EOQ', $sql);
		foreach ($queryArray as $query) {
			$query = trim($query);
			if (!empty($query)) { ## && !preg_match('/^#/iU', $query)) {
				## Just a bit of safety - Don't want some smart-ass making a ImeiUnlock 'Virus'
				if (preg_match('#^(DROP|TRUNCATE)#iu', trim($query))) break;
				
				if (preg_match('#^(ALTER)#iuxmU', trim($query))) {
					$queryLines = explode("\n", trim($query));
					for ($i=0; $i<count($queryLines); $i++) {
						if ($i==0) {
							if (count($queryLines) == 1) {
								if ($db->misc($queryLines[0], false)) {
									$this->sqlSuccessCount++;
									$this->sqlQueryLog['Success'][] = $queryLines[0];
								} else {
									$this->sqlQueryLog['Failed'][] = $queryLines[0];
								}
							} else {
								$prefix = $queryLines[0];
							}
						} else {
							$this->sqlQueryCount++;
							$queryTemp = sprintf('%s %s', $prefix, preg_replace('#,$#iu', ';', $queryLines[$i]));
							if ($db->misc($query, false)) {
								$this->sqlSuccessCount++;
								$this->sqlQueryLog['Success'][]	= $query;
							} else {
								$this->sqlQueryLog['Failed'][]	= $query;
							}
							unset($queryTemp);
						}
					}
				} else {
					## if its an INSERT or UPDATE, then check what table it's trying to access
					
					// if (preg_match('#^(INSERT)#iu', trim($query))
					
					$this->sqlQueryCount++;
					$query = str_replace(array("\n", "\r"), '', trim($query)).';';
					
					if ($db->misc($query, false)) {
						$this->sqlSuccessCount++;
						$this->sqlQueryLog['Success'][]	= $query;
					} else {
						$this->sqlQueryLog['Failed'][]	= $query;
					}
				}
			}
		}
		$this->sqlFailCount	= $this->sqlQueryCount-$this->sqlSuccessCount;
	}
	
}

?>