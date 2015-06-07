<?php
/*
+--------------------------------------------------------------------------
|	profile.inc.php
|   ========================================
|	Customers Profile	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) die("Access Denied");

## include lang file
$lang1 = getLang("includes".CC_DS."content".CC_DS."reg.inc.php");
$lang2 = getLang("includes".CC_DS."content".CC_DS."profile.inc.php");

$lang = array_merge($lang1, $lang2);

## send email if form is submit
if (isset($_POST['submit']) && $cc_session->ccUserData['customer_id']>0) {

	if ($_POST['which_field']=="T"){
		$county = $_POST['county'];
	} elseif ($_POST['which_field']=="S") {
		$county = $_POST['county_sel'];
	}

	if ($_POST['email']!==$cc_session->ccUserData['email']) {
		$emailArray = $db->select("SELECT customer_id, type FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE email=".$db->mySQLSafe($_POST['email']));
	}

	if (empty($_POST['firstName'])  || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['add_1']) || empty($_POST['town']) || empty($_POST['county'])) {
		$errorMsg = $lang['profile']['complete_all'];
	} elseif(!empty($_POST['firstName']) && !preg_match('#^([a-zA-Z\s]+)$#', $_POST['firstName'])){
		
		$errorMsg = "Only Characters are allowed in first name.";
		
	}elseif(!empty($_POST['lastName']) && !preg_match('#^([a-zA-Z\s]+)$#', $_POST['lastName'])){
		
		$errorMsg = "Only Characters are allowed in last name.";
		
	}else if (!validateEmail($_POST['email'])) {
		$errorMsg = $lang['profile']['email_invalid'];
		
	} else if(!empty($_POST['phone']) && !preg_match('#^([0-9\-\s\+\.\(\)]+)$#',$_POST['phone'])) {
		$errorMsg = $lang['profile']['enter_valid_tel'];
	} else if(!empty($_POST['mobile']) && !preg_match('#^([0-9-\s]+)$#', $_POST['mobile'])) {
		$errorMsg = $lang['profile']['enter_valid_tel'];
	} else if(isset($emailArray) && $emailArray == true && $emailArray[0]['type'] == 1) {
		$errorMsg = $lang['profile']['email_inuse'];
	}else {
		## update database
		$data['tw_add'] = $db->mySQLSafe($_POST['tw_add']);
		$data['firstName'] = $db->mySQLSafe($_POST['firstName']);
		$data['lastName'] = $db->mySQLSafe($_POST['lastName']); 
		$data['email'] = $db->mySQLSafe($_POST['email']); 
		$data['add_1'] = $db->mySQLSafe($_POST['add_1']);
		$data['add_2'] = $db->mySQLSafe($_POST['add_2']);
		$data['town'] = $db->mySQLSafe($_POST['town']); 
		$data['county'] = $db->mySQLSafe($_POST['county']); 
		$data['dadd_1'] = $db->mySQLSafe($_POST['dadd_1']);
		$data['dtown'] = $db->mySQLSafe($_POST['dtown']); 
		$data['dcounty'] = $db->mySQLSafe($_POST['dcounty']); 
		$data['postcode'] = $db->mySQLSafe($_POST['postcode']);
		$data['dpostcode'] = $db->mySQLSafe($_POST['dpostcode']);
		$data['country'] = $db->mySQLSafe($_POST['country']);
		$data['phone'] = $db->mySQLSafe($_POST['phone']); 
		$data['mobile'] = $db->mySQLSafe($_POST['mobile']);
		$data['fb_add'] = $db->mySQLSafe($_POST['fb_add']);

		$where = "customer_id = ".$cc_session->ccUserData['customer_id'];
		$updateAcc = $db->update($glob['dbprefix']."ImeiUnlock_customer",$data,$where);
		
		## make email
		require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
		
		$lang = getLang("email.inc.php");
		
		$mail = new htmlMimeMail();
		
		$macroArray = array(
			"CUSTOMER_NAME" => sanitizeVar($_POST['firstName']." ".$_POST['lastName']),
			"STORE_URL" => $GLOBALS['storeURL'],
			"SENDER_IP" => get_ip_address()
		);
		
		$text = macroSub($lang['email']['profile_mofified_body'], $macroArray);
		unset($macroArray);
		
		$mail->setText($text);
		$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
		$mail->setReturnPath($config['masterEmail']);
		$mail->setSubject($lang['email']['profile_mofified_subject']);
		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		$send = $mail->send(array(sanitizeVar($_POST['email'])), $config['mailMethod']);
		
		$getF = sanitizeVar($_GET['f']); // fixes Fatal error: Can't use function return value in write context
		
		if(!empty($getF)) {
			httpredir("index.php?_g=co&_a=".sanitizeVar($_GET['f']));
		}

		## rebuild customer array
		$query	= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_sessions INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_sessions.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE sessId = '".$GLOBALS[CC_SESSION_NAME]."'";
		$result	= $db->select($query);
		$cc_session->ccUserData = $result[0];
	}
}

$profile = new XTemplate ("content".CC_DS."profile.tpl");

$profile->assign("LANG_PERSONAL_INFO_TITLE",$lang['profile']['personal_info']);
if((isset($_GET['f']) && !isset($_GET['d']) && $_GET['f'] == 'step2') || $errorMsg){
	$profile->parse("profile.session_true.update_info");
}elseif(isset($_GET['f']) && isset($_GET['d']) && $_GET['d'] == 'step2'){
	$profile->parse("profile.session_true.update_add");
}
if (isset($updateAcc) && $updateAcc == true) {
	$profile->assign("LANG_PROFILE_DESC",$lang['profile']['account_updated']);
	$profile->parse("profile.session_true.no_error");

} else if(isset($errorMsg)) {

	$profile->assign("VAL_ERROR",$errorMsg);
	$profile->parse("profile.session_true.error");
} else {
	$profile->assign("LANG_PROFILE_DESC",$lang['profile']['edit_below']);
	$profile->parse("profile.session_true.no_error");
}

if ($cc_session->ccUserData['customer_id'] > 0 || $cc_session->ccUserData[0]['customer_id'] > 0) { 
	if(isset($_GET['f']) && !empty($_GET['f'])) {
		$profile->assign("VAL_EXTRA_GET","&amp;f=".sanitizeVar($_GET['f']));
	}
	
	
	$profile->assign("TXT_TITLE",$lang['profile']['title']);
	$profile->assign("VAL_TITLE",$cc_session->ccUserData['title']);
	
	$profile->assign("LANG_TITLE_DESC",$lang['reg']['title_desc']);
	
	$profile->assign("TXT_FIRST_NAME",$lang['profile']['first_name']);
	$profile->assign("VAL_FIRST_NAME",$cc_session->ccUserData['firstName'] ? $cc_session->ccUserData['firstName'] : $_POST['firstName'] );
	
	$profile->assign("TXT_LAST_NAME",$lang['profile']['last_name']);
	$profile->assign("VAL_LAST_NAME",$cc_session->ccUserData['lastName'] ? $cc_session->ccUserData['lastName'] : $_POST['lastName']);
	
	$profile->assign("TXT_COMPANY_NAME",$lang['profile']['company_name']);
	$profile->assign("VAL_COMPANY_NAME",$cc_session->ccUserData['companyName']);

	$profile->assign("TXT_EMAIL",$lang['profile']['email']);
	$profile->assign("VAL_EMAIL",$cc_session->ccUserData['email'] ? $cc_session->ccUserData['email'] : $_POST['email']);
	
	$profile->assign("TXT_ADD_1",$lang['profile']['address']);
	$profile->assign("VAL_ADD_1",$cc_session->ccUserData['add_1'] ? $cc_session->ccUserData['add_1'] : $_POST['add_1']);
	$profile->assign("VAL_FACEBOOK",$cc_session->ccUserData['fb_add'] ? $cc_session->ccUserData['fb_add'] : $_POST['fb_add']);
	$profile->assign("VAL_TWITTER",$cc_session->ccUserData['tw_add'] ? $cc_session->ccUserData['tw_add'] : $_POST['tw_add']);
	
	$profile->assign("TXT_ADD_2","");
	$profile->assign("VAL_ADD_2",$cc_session->ccUserData['add_2']);
	
	$profile->assign("TXT_TOWN",$lang['profile']['town']);
	$profile->assign("VAL_TOWN",$cc_session->ccUserData['town'] ? $cc_session->ccUserData['town'] : $_POST['town']);
	
	$profile->assign("VAL_DTOWN",$cc_session->ccUserData['dtown'] ? $cc_session->ccUserData['dtown'] : $_POST['dtown']);
	$profile->assign("VAL_DADD_1",$cc_session->ccUserData['dadd_1']? $cc_session->ccUserData['dadd_1'] : $_POST['dadd_1']);
	$profile->assign("VAL_DPOSTCODE",$cc_session->ccUserData['dpostcode'] ? $cc_session->ccUserData['dpostcode'] : $_POST['dpostcode']);
	$profile->assign("VAL_DEL_DCOUNTY",$cc_session->ccUserData['dcounty'] ? $cc_session->ccUserData['dcounty'] : $_POST['dcounty']);
	
	$profile->assign("TXT_COUNTY",$lang['profile']['county']);
		$profile->assign("VAL_SKYPE_IM",$cc_session->ccUserData['skype']);
		$profile->assign("VAL_CUSTOMER",$cc_session->ccUserData['firstName'].' '.$cc_session->ccUserData['lastName']);
		if($cc_session->ccUserData['profileimg']){
		$icnSrc = imgPath($cc_session->ccUserData['profileimg'],'',$path="profimg" , '');
		$profile->assign("USER_IMAGE", $icnSrc);
		}else{
			$profile->assign("USER_IMAGE", "skins/". SKIN_FOLDER . "/styleImages/noimg.jpg");
		}
		
	
	$profile->assign("VAL_COUNTY",$cc_session->ccUserData['county'] ? $cc_session->ccUserData['county'] : $_POST['county']);
	
	
	$profile->assign("TXT_POSTCODE",$lang['profile']['postcode']);
	$profile->assign("VAL_POSTCODE",$cc_session->ccUserData['postcode'] ? $cc_session->ccUserData['postcode'] : $_POST['postcode']);
	
	$profile->assign("TXT_COUNTRY",$lang['profile']['country']);
		
	
	$jsScript = jsGeoLocationExtended("country", "county_sel", $lang['cart']['na'],"divCountySelect","divCountyText","county","which_field");
	
	$counties = $db->select("SELECT name FROM  ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = '".$cc_session->ccUserData['country']."';");
	
	$profile->assign("VAL_DEL_COUNTY",$cc_session->ccUserData['county']);
	
	if (is_array($counties)) {
		$profile->assign("VAL_COUNTY_SEL_STYLE", "style='display:block;'");
		$profile->assign("VAL_COUNTY_TXT_STYLE", "style='display:none;'");
		$profile->assign("VAL_COUNTY_WHICH_FIELD", "S");
	} else {
		$profile->assign("VAL_COUNTY_SEL_STYLE", "style='display:none;'");
		$profile->assign("VAL_COUNTY_TXT_STYLE", "style='display:block;'");
		$profile->assign("VAL_COUNTY_WHICH_FIELD", "T");
	}
	$profile->assign("JS_COUNTY_OPTIONS", '<script type="text/javascript">'.$jsScript."</script>");

	for($i=0; $i<count($counties); $i++) {
	
		
		if (strtolower($counties[$i]['name']) == strtolower($cc_session->ccUserData['county'])) {
			$profile->assign("COUNTY_SELECTED","selected='selected'");
		} else {
			$profile->assign("COUNTY_SELECTED","");
		}

		$profile->assign("VAL_DEL_COUNTY_ID",$counties[$i]['name']);

		$countyName = $counties[$i]['name'];
		
		if (strlen($countyName)>20) {
			$countyName = substr($countyName,0,20)."&hellip;";
		}

		$profile->assign("VAL_DEL_COUNTY_NAME",$countyName);
		$profile->parse("profile.session_true.county_opts");
	}
	// end: Flexible Taxes
	
	$cache = new cache('glob.countries');
	$countries = $cache->readCache();
	
	if (!$cache->cacheStatus) {
		$countries = $db->select("SELECT id, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
		$cache->writeCache($countries);
	} 
	for ($i=0; $i<count($countries); $i++) {
		if ($countries[$i]['id'] == $cc_session->ccUserData['country']) {
			$profile->assign("COUNTRY_SELECTED","selected='selected'");
		} else {
			$profile->assign("COUNTRY_SELECTED","");
		}
		
		$profile->assign("VAL_COUNTRY_ID",$countries[$i]['id']);

		$countryName = "";
		$countryName = $countries[$i]['printable_name'];

		if (strlen($countryName)>20) {
			$countryName = substr($countryName,0,20)."&hellip;";
		}

		$profile->assign("VAL_COUNTRY_NAME",$countryName);
		$profile->parse("profile.session_true.repeat_countries");
	
	} 
	
	$profile->assign("VAL_COUNTRY",$cc_session->ccUserData['country']);
	
	$profile->assign("TXT_PHONE",$lang['profile']['phone']);
	$profile->assign("VAL_PHONE",$cc_session->ccUserData['phone'] ? $cc_session->ccUserData['phone'] : $_POST['phone']);
	
	$profile->assign("TXT_MOBILE",$lang['profile']['mobile']);
	$profile->assign("VAL_MOBILE",$cc_session->ccUserData['mobile'] ? $cc_session->ccUserData['mobile'] : $_POST['mobile']);
	
	$profile->assign("TXT_SUBMIT",$lang['profile']['update_account']);
	$profile->assign("USER_FOL", $cc_session->ccUserData['customer_id']);
	$profile->parse("profile.session_true");

} else { 
	$profile->assign("LANG_LOGIN_REQUIRED",$lang['profile']['login_required']);
	$profile->parse("profile.session_false");
}
	
$profile->parse("profile");
$page_content = $profile->text("profile");
?>