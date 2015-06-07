<?php
/*
+--------------------------------------------------------------------------
|	language.inc.php
|   ========================================
|	Language Jump Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die('Access Denied');

## Include lang file
$lang			= getLang('includes'.CC_DS.'boxes'.CC_DS.'language.inc.php');
$box_content	= new XTemplate('boxes'.CC_DS.'language.tpl');

$box_content->assign('LANG_LANGUAGE_TITLE', $lang['language']['language']);

$path = 'language';
if (is_dir($path)) {
	$returnPage = urlencode(str_replace($GLOBALS['storeURL']."/",$GLOBALS['rootRel'],currentPage()));
	foreach (glob($path.CC_DS.'*', GLOB_MARK) as $folder) {
		if (is_dir($folder) && preg_match('#[a-z]{2}(\_[A-Z]{2})?#i', $folder) && file_exists($folder.'config.php')) {
		#	ob_start();
			require $folder.'config.php';
			$box_content->assign('LANG_SELECTED', (LANG_FOLDER == str_replace(array('language', CC_DS), '', $folder)) ? 'selected="selected"' : '');
		
			$box_content->assign('LANG_NAME', $langName); //mb_convert_encoding($langName, 'UTF-8', mb_detect_encoding($langName)));
			$box_content->assign('LANG_VAL', str_replace(array('language', CC_DS), '', $folder));
			$box_content->assign('VAL_CURRENT_PAGE', $returnPage);
			$box_content->parse('language.option');
		#	ob_end_clean();
		}
	}
	## patch for bug #1031
	if (file_exists('language'.CC_DS.LANG_FOLDER.CC_DS.'config.php')) {
		include 'language'.CC_DS.LANG_FOLDER.CC_DS.'config.php';
	}
}

$box_content->assign('ICON_FLAG', LANG_FOLDER."/flag.gif");
$box_content->parse('language');
$box_content = $box_content->text('language');
?>