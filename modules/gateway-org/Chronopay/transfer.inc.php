<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a Trade Mark of Devellion Limited
|   Copyright Devellion Limited 2005 - 2006. All rights reserved.
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
|   Date: Thursday, 17th August 2006
|   Email: sales (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	transfer.inc.php
|   ========================================
|	Core functions for the Chronopay Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function repeatVars(){

		global $i, $orderInv;

		// product specific fields are not required.
		$hiddenVars = "";

		return $hiddenVars;
}

function fixedVars(){
	
	global $module, $orderSum, $cc_session, $config;
	
	// get language for payment page
	switch( trim($ccUserData[0]['lang']) )
	{
		case 'nl':
			$chronoLang = 'NL';
			break;
		case 'ru':
			$chronoLang = 'RU';
			break;
		case 'es':
			$chronoLang = 'ES';
			break;
		default:
			$chronoLang = 'EN';
			break;
	}

	$cb_url = $GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=call&amp;module=Chronopay";
	$fail_url = $GLOBALS['storeURL']."/index.php?_g=co&_a=confirmed&amp;s=1";

	// small security hash to just add a little request validation
	$cHash = md5( $glob['storeURL'].$module['productId'].$basket['grandTotal'] );
	
	$hiddenVars = "<input type='hidden' name='product_id' value='".$module['productId']."' />
			<input type='hidden' name='product_name' value='".$module['productName']."' />
			<input type='hidden' name='product_price' value='".$orderSum['prod_total']."' />
			<input type='hidden' name='product_price_currency' value='".$config['defaultCurrency']."' />
			<input type='hidden' name='language' value='".$chronoLang."' />
			<input type='hidden' name='cs1' value='".$orderSum['cart_order_id']."' />
			<input type='hidden' name='cs2' value='".$cHash."' />
			<input type='hidden' name='cs3' value='".$orderSum['prod_total']."' />
			<input type='hidden' name='cb_url' value='".$cb_url."' />
			<input type='hidden' name='cb_type' value='P' />
			<input type='hidden' name='decline_url' value='".$fail_url."' />
			<input type='hidden' name='f_name' value='".$cc_session->ccUserData['firstName']."' />
			<input type='hidden' name='s_name' value='".$cc_session->ccUserData['lastName']."' />
			<input type='hidden' name='street' value='".$orderSum['add_1']." ".$orderSum['add_2']."' />
			<input type='hidden' name='city' value='".$orderSum['town']."' />
			<input type='hidden' name='state' value='".$orderSum['county']."' />
			<input type='hidden' name='zip' value='".$orderSum['postcode']."' />
			<input type='hidden' name='country' value='".getCountryFormat($orderSum['country'],"id","printable_name")."' />
			<input type='hidden' name='email' value='".$orderSum['email']."' />
			<input type='hidden' name='phone' value='".$orderSum['phone']."' />";
			
			return $hiddenVars;
	
}

///////////////////////////
// Other Vars
////////
$formAction = "https://secure.chronopay.com/index_shop.cgi";
$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";

$emailText = "Questions regarding your order should be directed to:\r\n\r\n";
$emailText .= $config['storeName']."\r\n";
$emailText .= $GLOBALS['storeURL']."\r\n";
$emailText .= $config['masterEmail'];
?>