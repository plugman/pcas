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
|	form.inc.php
|   ========================================
|	Capture Credit Card Info
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

if(detectSSL() == false) die ("<strong>Critical Error:</strong> This page can ONLY be viewed under SSL!");

if($_GET['process']==1) {

	$transData['customer_id'] = $orderSum["customer_id"];
	$transData['order_id'] = $orderSum['cart_order_id'];
	$transData['amount'] = $orderSum['prod_total'];
	$transData['gateway'] = "Manual Card Processing";

	// first check card
	require("classes".CC_DS."validate".CC_DS."validateCard.php");
	
	$card = new validateCard();
	
	$cardNo			= $_POST["cardNumber"];
	$issueNo		= $_POST["issueNo"];
	$issueDate		= str_pad(trim($_POST["issueYear"]), 2, '0', STR_PAD_LEFT).str_pad(trim($_POST["issueMonth"]), 2, '0', STR_PAD_LEFT); 
	$issueFormat	= 4; 
	$expireDate		= str_pad(trim($_POST["expirationYear"]), 2, '0', STR_PAD_LEFT).str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT); 
	$expireFormat	= 4; 
	$scReqd			= (bool)$module['cvv_req'];
	$securityCode	= ($scReqd && !empty($_POST['cvc2'])) ? $_POST["cvc2"] : false;
	
	$card = $card->check($cardNo, $issueNo, $issueDate, $issueFormat, $expireDate, $expireFormat, $scReqd, $securityCode);
	
	
	if ($module['validation']==1 && $card['response']=="FAIL") {
		
		$errorMsg = "";
		
		foreach ($card['error'] as $val) {
			$errorMsg .= $val."<br />";
		}
		
		$transData['trans_id'] = "n/a";
		$transData['status'] = "Fail";
		$transData['notes'] = $errorMsg;
		
		$order->storeTrans($transData);
		
	} else {
		## store card details
		
		$cardData = array(
			'card_type'		=> $_POST['cardType'],
			'card_number'	=> $_POST['cardNumber'],
			'card_expire'	=> str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT)."/".str_pad(trim($_POST["expirationYear"]), 2, '0', STR_PAD_LEFT),
			'card_valid'	=> str_pad(trim($_POST["issueMonth"]), 2, '0', STR_PAD_LEFT)."/".str_pad(trim($_POST["issueYear"]), 2, '0', STR_PAD_LEFT),
			'card_issue'	=> $_POST['issueNo'],
			'card_cvv'		=> $_POST['cvc2']
		);
		
		$keyArray = array($orderSum['cart_order_id']);
		
		if (function_exists('mcrypt_module_open')) {
			require("classes".CC_DS."cart".CC_DS."encrypt.inc.php");
			$crypt = new encryption($keyArray);
			$record['offline_capture'] = "'".base64_encode($crypt->encrypt(serialize($cardData)))."'";
			$db->update($glob['dbprefix'].'ImeiUnlock_order_sum', $record, array('customer_id' => $orderSum['customer_id'], 'cart_order_id' => $orderSum['cart_order_id']));
		}
		## log trans details
		$transData['trans_id'] = "n/a";
		$transData['status'] = "Success";
		$transData['notes'] = "Card Details Captured.";
		$order->storeTrans($transData);		
		
		httpredir("index.php?_g=co&_a=confirmed&s=3");
	}
}


$formTemplate = new XTemplate ("modules".CC_DS."gateway".CC_DS.$_POST['gateway'].CC_DS."form.tpl",'',null,'main',true, true);

if (isset($errorMsg)) {
	$formTemplate->assign("LANG_ERROR",$errorMsg);
	$formTemplate->parse("form.error");
}
	
$billingName = makeName($orderSum['name']);
$deliveryName = makeName($orderSum['name_d']);

$formTemplate->assign("VAL_AMOUNT_DUE",sprintf($lang['gateway']['amount_due'],priceformat($orderSum['prod_total'],true)));
$formTemplate->assign("VAL_FIRST_NAME",$billingName[2]);
$formTemplate->assign("VAL_LAST_NAME",$billingName[3]);
$formTemplate->assign("VAL_EMAIL_ADDRESS",$orderSum['email']);
$formTemplate->assign("VAL_ADD_1",$orderSum['add_1']);
$formTemplate->assign("VAL_ADD_2",$orderSum['add_2']);
$formTemplate->assign("VAL_CITY",$orderSum['town']);
$formTemplate->assign("VAL_COUNTY",$orderSum['county']);
$formTemplate->assign("VAL_POST_CODE",$orderSum['postcode']);

$cache = new cache('glob.countries');
$countries = $cache->readCache();

if($cache->cacheStatus==FALSE)
{
	$countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
	$cache->writeCache($countries);
} 
	
	for($i=0; $i<count($countries); $i++)
	{
				
		if($countries[$i]['id'] == $orderSum['country'])
		{
			$formTemplate->assign("COUNTRY_SELECTED","selected='selected'");
		} 
		else 
		{
			$formTemplate->assign("COUNTRY_SELECTED","");
		}
	
		$formTemplate->assign("VAL_COUNTRY_ISO",$countries[$i]['iso']);

		$countryName = "";
		$countryName = $countries[$i]['printable_name'];

		if(strlen($countryName)>20)
		{

			$countryName = substr($countryName,0,20)."&hellip;";

		}

		$formTemplate->assign("VAL_COUNTRY_NAME",$countryName);
		$formTemplate->parse("form.repeat_countries");
	}
	
	$formTemplate->assign("LANG_CC_INFO_TITLE",$lang['gateway']['cc_info_title']);
	$formTemplate->assign("LANG_FIRST_NAME",$lang['gateway']['first_name']); 
	$formTemplate->assign("LANG_LAST_NAME",$lang['gateway']['last_name']); 
	
	ksort($module['cards']);

	foreach($module['cards'] as $key => $value) {
	
		$formTemplate->assign("VAL_CARD_TYPE",$key);
		
		if($value==1){
		
			if($key == $_POST['cardType']){
				$formTemplate->assign("CARD_SELECTED","selected='selected'");
			} else {
				$formTemplate->assign("CARD_SELECTED","");
			}
			$formTemplate->assign("VAL_CARD_NAME",str_replace("_"," ",$key));
		
		$formTemplate->parse("form.repeat_cards");
		
		}
	}
	
	if($module['issue_info']==1) {
		
		$formTemplate->assign("LANG_ISSUE_DATE", $lang['gateway']['issue_date']);
		$formTemplate->assign("LANG_ISSUE_NO", $lang['gateway']['issue_number']);
		$formTemplate->parse("form.issue_info");
	}
	
	if($module['cvv']==1) {
		$formTemplate->assign("LANG_SECURITY_CODE",$lang['gateway']['security_code']);
		$formTemplate->parse("form.cvv");
	}
	
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['expires']);
	$formTemplate->assign("LANG_MMYYYY",$lang['gateway']['mmyy']);
	$formTemplate->assign("LANG_CARD_TYPE",$lang['gateway']['card_type']);
	$formTemplate->assign("LANG_CUST_INFO_TITLE",$lang['gateway']['customer_info']);
	$formTemplate->assign("LANG_EMAIL",$lang['gateway']['email']);
	$formTemplate->assign("LANG_ADDRESS",$lang['gateway']['address']);
	$formTemplate->assign("LANG_CITY",$lang['gateway']['city']);
	$formTemplate->assign("LANG_STATE",$lang['gateway']['state']);
	$formTemplate->assign("LANG_ZIPCODE",$lang['gateway']['zipcode']);
	$formTemplate->assign("LANG_COUNTRY",$lang['gateway']['country']);
	$formTemplate->assign("LANG_OPTIONAL",$lang['gateway']['optional']);
	
	$formTemplate->assign("VAL_GATEWAY",sanitizeVar($_POST['gateway']));


$formTemplate->parse("form");
$formTemplate = $formTemplate->text("form");
?>