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
|	transfer.inc.php
|   ========================================
|	Core functions for the WorldPay Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function repeatVars() {
	return false;
}

function fixedVars() {
	global $module, $orderSum, $config;
	
	$hiddenVars = "<input type='hidden' name='instId' value='".$module['acNo']."' />
					<input type='hidden' name='cartId' value='".$orderSum['cart_order_id']."' />
					<input type='hidden' name='amount' value='".$orderSum['prod_total']."' />
					<input type='hidden' name='currency' value='".$config['defaultCurrency']."' />
					<input type='hidden' name='desc' value='Payment for order #".$orderSum['cart_order_id']."' />
					<input type='hidden' name='name' value='".$orderSum['name']."' />";
	if (!empty($orderSum['add_2'])) {
		$add = $orderSum['add_1'].",&#10;".$orderSum['add_2'].",&#10;".$orderSum['town'].", ".$orderSum['county'].",&#10;".getCountryFormat($orderSum['country']);
	} else {
		$add = $orderSum['add_1'].",&#10;".$orderSum['town'].",&#10;".$orderSum['county'].",&#10;".getCountryFormat($orderSum['country']);
	}
	$hiddenVars .= "<input type='hidden' name='address' value='".$add."' />
					<input type='hidden' name='postcode' value='".$orderSum['postcode']."' />
					<input type='hidden' name='country' value='".getCountryFormat($orderSum['country'],"id","iso")."' />
					<input type='hidden' name='tel' value='".$orderSum['phone']."' />
					<input type='hidden' name='email' value='".$orderSum['email']."' />";
					
	//if(empty($module['authMode'])) $module['authMode'] = "E"; // WorldPay Default
	//$hiddenVars .= "<input type='hidden' name='authMode' value='".$module['authMode']."' />";
	
					
	if ($module['testMode']>0) {	
		$hiddenVars .= "<input type='hidden' name='testMode' value='".$module['testMode']."' />";
	}
	return $hiddenVars;
}

///////////////////////////
// Other Vars
////////
if($module['testMode']>0) {
	$formAction = "https://select-test.worldpay.com/wcc/purchase";
} else {
	$formAction = "https://select.worldpay.com/wcc/purchase";
}
$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";
?>