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
|	Pay Junction Processing
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
if(detectSSL() == FALSE) die ("<strong>Critical Error:</strong> This page can ONLY be viewed under SSL!");

if($_GET['process']==1)
{

	$transData['customer_id'] = $orderSum["customer_id"];
	$transData['order_id'] = $orderSum['cart_order_id'];
	$transData['amount'] = $orderSum['prod_total'];
	$transData['gateway'] = "PayJunction";
	
	// first check card
	require("classes".CC_DS."validate".CC_DS."validateCard.php");
	$card = new validateCard();
	
	$cardNo			= $_POST["dc_number"];
	$issueNo		= 0;
	$issueDate		= 0; 
	$issueFormat	= 4; 
	$expireDate		= str_pad(trim($_POST["dc_expiration_year"]), 2, '0', STR_PAD_LEFT).str_pad(trim($_POST["dc_expiration_month"]), 2, '0', STR_PAD_LEFT);
	$expireFormat	= 4; 
	$scReqd			= TRUE; 
	$securityCode	= trim($_POST["dc_verification_number"]);
	
	$card = $card->check($cardNo, 
						$issueNo, 
						$issueDate, 
						$issueFormat, 
						$expireDate, 
						$expireFormat, 
						$scReqd, 
						$securityCode);
	
	if($module['validation']==1 && $card['response']=="FAIL")
	{
	
		$errorMsg = "";
		
		foreach($card['error'] as $val)
		{
		
			$errorMsg .= $val."<br />";
		
		}

	} 
	else
	{
	
		## Required variables
		$curl_exec		= "/usr/bin/curl -m 64 -d";
		$server			= "https://payjunction.com/live/vendor/quick_link/transact";
		$request		= "";
		$response 		= array ();
		 
			$post_array 	= array
			(
				"dc_test"				=> $module['testMode'],
				"dc_logon"				=> $module['user'],
				"dc_password"			=> $module['pass'],
				"dc_transaction_type"	=> "AUTHORIZATION_CAPTURE",
				"dc_transaction_amount"	=> $orderSum['prod_total'],
				"dc_first_name"			=> trim($_POST['dc_first_name']),
				"dc_last_name"			=> trim($_POST['dc_last_name']),
				"dc_number"				=> trim($_POST['dc_number']),
				"dc_expiration_month"	=> str_pad(trim($_POST["dc_expiration_month"]), 2, '0', STR_PAD_LEFT),
				"dc_expiration_year"	=> str_pad(trim($_POST["dc_expiration_year"]), 2, '0', STR_PAD_LEFT),
				"dc_verification_number"=> trim($_POST['dc_verification_number']),
				"dc_address"			=> trim($_POST['dc_address']),
				"dc_city"				=> trim($_POST['dc_city']),
				"dc_state"				=> trim($_POST['dc_state']),
				"dc_zipcode"			=> trim($_POST['dc_zipcode']),
				"dc_country"			=> getCountryFormat($_POST['dc_country'],"iso","printable_name")
			);
		
			## Setup the POST string to send to the PayJunction Server
			reset($post_array);
			while (list ($key, $val) = each($post_array)) 
			{
				$request .= $key . "=" . urlencode($val) . "&";
			}
			$request = rtrim( $request, "&" );
			
			## Send the secure transaction request to PayJunction
			//$content = exec ("$curl_exec \"$request\" $server");
			
			// Use more secure curl_setop instead of exec
			$ch = curl_init($server); 
			curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // use HTTP POST to send form data
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
			if($config['proxy']==1){
				curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
			}
			$content = curl_exec($ch); //execute post and get results
			curl_close ($ch);
		
			## Parse the response from PayJunction
			$content = array_values (split (chr (28), $content));
			while ($key_value = next ($content))
			{
				list ($key, $value) = split ("=", $key_value);
				$response[$key] = $value; 
			}
			
		if ($response['response_code'] == "00" || $response['response_code'] == "85")
		{
				$order->orderStatus(3,$orderSum['cart_order_id']);
				$jumpTo = "index.php?_g=co&_a=confirmed&s=2";
				
				$transData['trans_id'] = $response['transaction_id'];
				$transData['status'] = "Success";
				$transData['notes'] = "Transaction was approved.";
				
					
		}
		else
		{ 
			switch($response['response_code'])
			{
				case "FE":
					$errorMsg = "There was a format error with your Trinity Gateway Service (API) request.";
				break;
				
				case "LE":
					$errorMsg =  "Could not log you in (problem with dc_logon and/or dc_password).";
				break;
				
				case "AE":
					$errorMsg =  "Address verification failed because address did not match.";
				break;
				
				case "ZE":
					$errorMsg =  "Address verification failed because zip did not match.";
				break;
				
				case "XE":
					$errorMsg =  "Address verification failed because zip and address did not match.";
				break;
				
				case "YE":
					$errorMsg =  "Address verification failed because zip and address did not match.";
				break;
				
				case "OE":
					$errorMsg =  "Address verification failed because address or zip did not match.";
				break;
				
				case "UE":
					$errorMsg =  "Address verification failed because cardholder address unavailable.";
				break;
				
				case "RE":
					$errorMsg =  "Address verification failed because address verification system is not working.";
				break;
				
				case "SE":
					$errorMsg =  "Address verification failed because address verification system is unavailable.";
				break;
				
				case "EE":
					$errorMsg =  "Address verification failed because transaction is not a mail or phone order.";
				break;
				
				case "GE":
					$errorMsg =  "Address verification failed because international support is unavailable.";
				break;
				
				case "CE":
					$errorMsg =  "Declined because CVV2/CVC2 code did not match.";
				break;
				
				case "NL":
					$errorMsg =  "Aborted because of a system error, please try again later.";
				break;
				
				case "AB":
					$errorMsg =  "Aborted because of an upstream system error, please try again later.";
				break;
				
				case "04":
					$errorMsg =  "Declined. Pick up card.";
				break;
				
				case "07":
					$errorMsg =  "Declined. Pick up card (Special Condition).";
				break;
				
				case "41":
					$errorMsg =  "Declined. Pick up card (Lost).";
				break;
				
				case "43":
					$errorMsg =  "Declined. Pick up card (Stolen).";
				break;
				
				case "13":
					$errorMsg =  "Declined because of the amount is invalid.";
				break;
				
				case "14":
					$errorMsg =  "Declined because the card number is invalid.";
				break;
					
				case "80":
					$errorMsg =  "Declined because of an invalid date.";
				break;
				
				case "05":
					$errorMsg =  "Declined. Do not honor.";
				break;
				
				case "51":
					$errorMsg =  "Declined because of insufficient funds.";
				break;
				
				case "N4":
					$errorMsg =  "Declined because the amount exceeds issuer withdrawal limit.";
				break;
				
				case "61":
					$errorMsg =  "Declined because the amount exceeds withdrawal limit.";
				break;
				
				case "62":
					$errorMsg =  "Declined because of an invalid service code (restricted).";
				break;
				
				case "65":
					$errorMsg =  "Declined because the card activity limit exceeded.";
				break;
				
				case "93":
					$errorMsg =  "Declined because there a violation (the transaction could not be completed).";
				break;
				
				case "06":
					$errorMsg =  "Declined because address verification failed.";
				break;
				
				case "54":
					$errorMsg =  "Declined because the card has expired.";
				break;
				
				case "15":
					$errorMsg =  "Declined because there is no such issuer.";
				break;
	
				case "96":
					$errorMsg =  "Declined because of a system error.";
				break;
				
				case "N7":
					$errorMsg =  "Declined because of a CVV2/CVC2 mismatch.";
				break;
				
				case "M4":
					$errorMsg =  "Declined.";
				break;
			}
			
			$transData['trans_id'] = "n/a";
			$transData['status'] = "Error";
			$transData['notes'] = $errorMsg;
		}
		
		
		
		$order->storeTrans($transData);
		
		if(isset($jumpTo) && !empty($jumpTo)) 
		{
		
			httpredir($jumpTo);
			
		}

	}

}


$formTemplate = new XTemplate ("modules".CC_DS."gateway".CC_DS.$_POST['gateway'].CC_DS."form.tpl",'',null,'main',true,$skipPath=TRUE);

if(isset($errorMsg)) 
{
	
	$formTemplate->assign("LANG_ERROR",$errorMsg);
	$formTemplate->parse("form.error");

}
$billingName = makeName($orderSum['name']);

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
				
			
		if($countries[$i]['printable_name'] == $orderSum['country'])
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
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['expires']);
	$formTemplate->assign("LANG_MMYYYY",$lang['gateway']['mmyyyy']);
	$formTemplate->assign("LANG_SECURITY_CODE",$lang['gateway']['security_code']);
	$formTemplate->assign("LANG_CUST_INFO_TITLE",$lang['gateway']['customer_info']);
	$formTemplate->assign("LANG_ADDRESS",$lang['gateway']['address']);
	$formTemplate->assign("LANG_CITY",$lang['gateway']['city']);
	$formTemplate->assign("LANG_STATE",$lang['gateway']['state']);
	$formTemplate->assign("LANG_ZIPCODE",$lang['gateway']['zipcode']);
	$formTemplate->assign("LANG_COUNTRY",$lang['gateway']['country']);
	$formTemplate->assign("VAL_GATEWAY",sanitizeVar($_POST['gateway']));


$formTemplate->parse("form");
$formTemplate = $formTemplate->text("form");
?>