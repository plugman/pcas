<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a Trade Mark of Devellion Limited
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
|	index.inc.php
|   ========================================
|	Module Install System - Version 1.0
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
##### Include these two line at the beginning of all your module's files #####
require_once CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'common.inc.php';
$modLoad = new ModuleLoader(__FILE__, $config['defaultLang'], $language, $moduleName);
##############################################################################



$allowedTypes = array(
	'language'	=> 'language',
	'modules' => array(
		'affiliate',
	//	'altCheckout',	# Not yet... altCheckouts require a few too many hooks all over the place
		'gateway',
		'installer',	# Yes, a self-installing, self-updating module. Clever shit, eh?
		'shipping',
	),
	'skin' => 'skins',
);

if (isset($_POST['install'])) {
	## Install the files
	
	# $installer = new Installer();
	# $installer->install(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');
	
	$ziplib = new dUnzip2(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');
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
		
	if (!$moduleDir) {
		$configData = unserialize($ziplib->unzip('package.conf.inc'));
	} else {
		if (!unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'))) {
			$configData = unserialize(base64_decode($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc')));
		} else {
			$configData = unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'));
		}
	}
	function cleandata(&$array, $key) {
		$array = strip_tags($array);
	}
	array_walk($configData, 'cleandata');		
	
	$installDir	= keySearch($configData['type'], $allowedTypes);
	
	if ($installDir !== false) {
		
		if ($moduleDir == false) {
			$installpath = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS.str_replace(' ', '_', $configData['name']);
		} else {
			$installpath = CC_ROOT_DIR.CC_DS.$installDir.CC_DS.$configData['type'].CC_DS;
		}
		
		if (!is_dir($installpath)) {
			@mkdir($installpath, 0777, true);
		}
		$ziplib->unzipAll($installpath);
		@chmod($installpath, 0755);
		$errors = $ziplib->getErrors();
		if (!$errors) {
			$msg = sprintf($language['lang_install_success'], $configData['name']);
		} else {
			$error  = $language['lang_install_failed'].'<br />';
			$error .= implode('<br />', $errors);
		}
	}
	@unlink(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');

} else if (isset($_POST['upload'])) {
	## read the package information, and display it on a confirmation screen
	
	# $install = $installer->prepare($modLoad->language);
	
	$install['allow'] = false;
	
	if (is_uploaded_file($_FILES['upload']['tmp_name']) && preg_match('#\.(zip)$#iu', $_FILES['upload']['name'])) {
		$ziplib = new dUnzip2($_FILES['upload']['tmp_name']);
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
		
		if (!$moduleDir) {
			$configData = unserialize($ziplib->unzip('package.conf.inc'));
		} else {
			if (!unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'))) {
				$configData = unserialize(base64_decode($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc')));
			} else {
				$configData = unserialize($ziplib->unzip($moduleDir.CC_DS.'package.conf.inc'));
			}
		}
				
		function cleandata(&$array, $key) {
			$array = strip_tags($array);
		}
		array_walk($configData, 'cleandata');		
		@move_uploaded_file($_FILES['upload']['tmp_name'], CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'installer-cache.zip');
		
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
?>

<form action="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=installer" method="post" class="installerForm">
  <div><?php echo $install['text']; ?></div><br />
  <div>
	<strong><?php echo $language['lang_description']; ?></strong><br />
	<?php
	echo wordwrap($configData['description'], 100);
	if (isset($configData['author']) && !empty($configData['author'])) {
	?><br /><br />
	<strong><?php echo $language['lang_author']; ?></strong>: <?php echo $configData['author']; ?><br />
	<?php } ?>
  	<strong><?php echo $language['lang_type']; ?></strong>: <?php echo ucfirst($configData['type']); ?>
  </div>
  <?php
  if ($install['allow']===true) echo '<br /><input type="submit" class="submit" name="install" value="'.$install['button'].'" />';
  ?>
</form>
<?php
	}	
}

if (isset($msg)) echo sprintf('<p class="infoText">%s</p>', $msg);
if (isset($error)) echo sprintf('<p class="warntext">%s</p>', $error);

?>
<div id="contentPad">
  <p class="pageTitle"><?php echo $language['lang_title']; ?></p>
  <form action="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=installer" method="post" enctype="multipart/form-data">
 	<div><?php echo $language['lang_install_info']; ?></div><br />
	<input type="file" name="upload" id="moduleUpload" class="textbox" /> 	
	<input type="submit" name="upload" id="upload" value="<?php echo $language['lang_button_upload']; ?>" class="submit" />
  </form>
  <br />
  <small><?php $language['lang_footer_note']; ?></small>
</div>

<?php require $glob['adminFolder'].CC_DS."includes".CC_DS."footer.inc.php"; ?>