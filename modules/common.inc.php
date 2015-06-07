<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	common.inc.php
|   ========================================
|	Core Module Functions
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die('Access Denied');

permission('settings', 'read', true);
$lang = getLang('admin'.CC_DS.'admin_misc.inc.php');

require $glob['adminFolder'].CC_DS.'includes'.CC_DS.'header.inc.php';

class ModuleLoader {
	
	# Private
	var $path;
	var $moduleName;
	
	# Public
	var $info;
	var $language;
	var $settings;
	var $message = false;
	
	function moduleLoader($path, $locale = 'en', &$language, $moduleName = '') {
		$this->modulePath($path);
		$this->moduleInfo();
		
		$this->moduleClasses();
		
		$this->moduleLanguage($locale);
		$this->moduleSettings($moduleName);
		
		$this->moduleName = $moduleName;
		
		if (isset($_POST['module']) && is_array($_POST['module'])) {
			$this->moduleSave($_POST['module']);
		}
		if (empty($language)) $language	= $this->language;
	}
	
	function modulePath($path) {
		$replace = array(CC_DS.'admin', CC_DS.'classes', CC_DS.'images', CC_DS.'language');
		$this->path = str_replace($replace, '', dirname($path));
		$this->path	= preg_replace('#/+#', '/', $this->path.'/');
	}
	
	function moduleInfo() {
		if (file_exists($this->path.'package.conf.inc')) {
			$this->info = unserialize(file_get_contents($this->path.'package.conf.inc'));
		}
	}

	function moduleClasses() {
		if (is_dir($this->path.'classes')) {
			foreach (glob($this->path.'classes'.CC_DS.'*.inc.php') as $include) {
				if (!is_dir($include)) include $include;
			}
		}
	}
	
	function moduleSettings($moduleName) {
		if (!empty($moduleName)) {
			$this->settings = fetchDbConfig($moduleName);
		}
	}

	function moduleLanguage($locale) {
		
		if (file_exists($this->path.'language'.CC_DS.$locale.'.lang.php')) {
			include $this->path.'language'.CC_DS.$locale.'.lang.php';
		} else if (file_exists($this->path.'language'.CC_DS.'default.lang.php')) {
			include $this->path.'language'.CC_DS.'default.lang.php';
		}		
		$this->language = ($language) ? $language : false;
	}
	
	function moduleSave($settingsArray) {
		global $glob, $db;
		$this->message = writeDbConf($settingsArray, $this->moduleName, $this->settings);
		$this->moduleSettings($this->moduleName);
	}
}

include CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';

?>