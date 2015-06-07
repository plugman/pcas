<?php
/*
+--------------------------------------------------------------------------
|	error.inc.php
|   ========================================
|	Display error message from language file	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("includes".CC_DS."content".CC_DS."error.inc.php");
$error = new XTemplate ("content".CC_DS."error.tpl");

$error->assign("LANG_SORRY", sprintf($lang['error']['error'], sanitizeVar($_GET['code'])));

if (array_key_exists($_GET['code'], $lang['error'])) {
	$error->assign("LANG_DESC", $lang['error'][$_GET['code']]);
} else {
	$error->assign("LANG_DESC", $lang['error']['no_error_msg']);
}

$error->parse("error");
$page_content = $error->text("error");
?>