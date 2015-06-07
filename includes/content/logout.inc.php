<?php
/*
+--------------------------------------------------------------------------
|	logout.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."logout.inc.php");

## delete cookie
$logout = new XTemplate ("content".CC_DS."logout.tpl");

$logout->assign("LANG_LOGOUT_TITLE",$lang['logout']['logout']);

if($cc_session->ccUserData['customer_id']>0){
	$cc_session->destroySession($GLOBALS[CC_SESSION_NAME]);
	## lose any session data that may be lingering e.g. PayPal Express Checkout
	session_unset();
	$logout->assign("LANG_LOGOUT_STATUS",$lang['logout']['session_destroyed']);
	httpredir($_SERVER['HTTP_REFERER']);
} else {
	$logout->assign("LANG_LOGOUT_STATUS",$lang['logout']['no_session']);
}

$logout->parse("logout");
$page_content = $logout->text("logout");
?>