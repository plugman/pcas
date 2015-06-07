<?php

/*

+--------------------------------------------------------------------------

|	session.inc.php

|   ========================================

|	Session Links & Welcome Text	

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");



if (!$cc_session->user_is_search_engine() || $config['sef'] == false)  {



	## include lang file

	$lang = getLang("includes".CC_DS."boxes".CC_DS."session.inc.php");

	$box_content = new XTemplate ("boxes".CC_DS."session.tpl");

	if($config['sef']){

		

	$box_content->assign("BALANCE",'Balance.html');

	$box_content->assign("ACCOUNT",'YourAccount.html');

	$box_content->assign("ORDERLOOKUP",'Order-Lookup.html');

	$box_content->assign("LOGOUT",'Logout.html');

	$box_content->assign("LOGIN",'Login.html');

	$box_content->assign("REGISTER",'Register.html');

}

else{

	$box_content->assign("BALANCE",'index.php?_a=topupBalance');

	$box_content->assign("ACCOUNT",'index.php?_a=account');

	$box_content->assign("ORDERLOOKUP",'index.php?_a=orderlookup');

	$box_content->assign("LOGOUT",'index.php?_a=logout');

	$box_content->assign("LOGIN",'index.php?_a=reg');

	$box_content->assign("REGISTER",'index.php?_a=reg');

}

	## build attributes

	if ($cc_session->ccUserData['customer_id']>0 && $cc_session->ccUserData['type']==1 && $_GET['_a'] !== "logout") {

		

		//Show the User Cards Balance

	$balanceRs 	= $db->select("SELECT card_balance AS balance FROM ImeiUnlock_customer WHERE customer_id = '".$cc_session->ccUserData				['customer_id']."';");

	

	$box_content->assign("VAL_BALANCE",  priceFormat($balanceRs[0]['balance']) );

		

		$box_content->assign("LANG_WELCOME_BACK", $lang['session']['welcome_back']);

	

		$box_content->assign("TXT_USERNAME", $cc_session->ccUserData['firstName']);
		if($cc_session->ccUserData['profileimg']){
		$icnSrc = imgPath($cc_session->ccUserData['profileimg'],'',$path="profimg" , '');
		$box_content->assign("USER_IMAGE", $icnSrc);
		}else{
			$box_content->assign("USER_IMAGE", "skins/". SKIN_FOLDER . "/styleImages/noimg.jpg");
		}
		$box_content->assign("LANG_LOGOUT", $lang['session']['logout']);

		$box_content->assign("LANG_YOUR_ACCOUNT", $lang['session']['your_account']);

		$box_content->parse("session.session_true");

		

	} else {

		$box_content->assign("LANG_WELCOME_GUEST", $lang['session']['welcome_guest']);

		$box_content->assign("VAL_SELF", urlencode(str_replace("&amp;","&",currentPage())));

		$box_content->assign("LANG_LOGIN", $lang['session']['login']);

		$box_content->assign("LANG_REGISTER", $lang['session']['register']);

		$box_content->parse("session.session_false");

	}

	
$box_content->assign("STOREURL", $GLOBALS['rootRel']);

	$box_content->assign("FTAPPID", $config['fbaid']);

	$box_content->parse("session");

	$box_content = $box_content->text("session");

	



} else {

	$box_content = null;

}

?>