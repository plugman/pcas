<?php
/*
+--------------------------------------------------------------------------
|	login.inc.php
|   ========================================
|	Assign customer id to session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."login.inc.php");
/*echo "<pre>";
print_r($_POST);
die();*/

if($_GET['redir']== "step3"){
$redirlogin = 1;
}else if($_GET['redir']== "step4"){
$redirlogin = 2;
}else if($_GET['_a']== "step1"){
$_GET['redir']= "index.php?_g=co&_a=step2";
}
if ($_GET['_a'] == "login" && isset($_POST['username']) && isset($_POST['password'])) {
	$remember = (!empty($_POST['remember'])) ? true : false;
	if($_POST['sociallog'] == 1){
	$cc_session->authenticate($_POST['username2'],$_POST['password'], $remember, $redirlogin, '', 1);
	}elseif($_POST['sociallog'] == 2){
	$cc_session->authenticate($_POST['username2'],$_POST['password'], $remember, $redirlogin, '', 2);
	}else{
	 $cc_session->authenticate($_POST['username'],$_POST['password'], $remember, $redirlogin);
	}
}


$login = new XTemplate ("content".CC_DS."login.tpl");

$login->assign("LANG_LOGIN_TITLE",$lang['login']['login']);

$login->assign("VAL_SELF",urlencode(sanitizeVar($_GET['redir'])));

$login->assign("LANG_USERNAME",$lang['login']['username']);

if(isset($_POST['username'])){
	$login->assign("VAL_USERNAME", sanitizeVar($_POST['username']));
}

$login->assign("LANG_PASSWORD",$lang['login']['password']);
$login->assign("LANG_REMEMBER",$lang['login']['remember_me']);
$login->assign("TXT_LOGIN",$lang['login']['login']);
$login->assign("LANG_FORGOT_PASS",$lang['login']['forgot_pass']);
$login->assign("LANG_REGISTER",$lang['login']['register']);

if(isset($_POST['remember']) && $_POST['remember']==1) $login->assign("CHECKBOX_STATUS","checked='checked'");

if($cc_session->ccUserData['customer_id'] > 0  && $cc_session->ccUserData['type']==1 &&  isset($_POST['submit'])){
	$login->assign("LOGIN_STATUS",$lang['login']['login_success']);
} elseif($cc_session->ccUserData['customer_id']>0 && $cc_session->ccUserData['type']==1 && !isset($_POST['submit'])) {
	$login->assign("LOGIN_STATUS",$lang['login']['already_logged_in']);
} elseif($cc_session->ccUserData['customer_id'] == 0 && isset($_POST['submit'])) {
	if($cc_session->ccUserBlocked == TRUE){
		$login->assign("LOGIN_STATUS",sprintf($lang['login']['blocked'],sprintf("%.0f",$ini['bftime']/60)));
	}else  if($cc_session->ccUserPBlocked == TRUE){
		$login->assign("LOGIN_STATUS", "your account has been locked for security reasons. Please get in touch with website administrator for more details.");
	}else{
		$login->assign("LOGIN_STATUS",$lang['login']['login_failed']);
	}
	$login->parse("login.form");
} else {
	$login->assign("LOGIN_STATUS","");
	$login->parse("login.form");
}
$login->parse("login");
$page_content = $login->text("login");
?>