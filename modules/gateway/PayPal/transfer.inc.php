<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock v4.0.0
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
|	Core functions for the PayPal Gateway	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

function repeatVars() {

		return FALSE;
	
}

function fixedVars() {
	
	global $module, $orderSum, $config;
	
	$billingName = makeName($orderSum['name']);
	
	$hiddenVars = "<input type='hidden' name='cmd' value='_xclick' />
				<input type='hidden' name='charset' value='UTF-8' />
				<input type='hidden' name='business' value='".$module['email']."' />
				<input type='hidden' name='item_name' value='Order #".$orderSum['cart_order_id']."' />
				<input type='hidden' name='item_number' value='".$orderSum['cart_order_id']."' />
				<input type='hidden' name='amount' value='".$orderSum['prod_total']."' />
				<input type='hidden' name='shipping' value='0.00' />
				<input type='hidden' name='invoice' value='".$orderSum['cart_order_id']."' />
				<input type='hidden' name='first_name' value='".$billingName[2]."' />
				<input type='hidden' name='last_name' value='".$billingName[3]."' />
				<input type='hidden' name='currency_code' value='".$config['defaultCurrency']."' />
				<input type='hidden' name='address1' value='".$orderSum['add_1']."' />
				<input type='hidden' name='address2' value='".$orderSum['add_2']."' />
				<input type='hidden' name='city' value='".$orderSum['town']."' />
				<input type='hidden' name='state' value='".$orderSum['county']."' />
				<input type='hidden' name='country' value='".getCountryFormat($orderSum['country'],"id","iso")."' />
				<input type='hidden' name='zip' value='".$orderSum['postcode']."' />
				<input type='hidden' name='day_phone_a' value='".$orderSum['phone']."' />
				<input type='hidden' name='add' value='1' />
				<input type='hidden' name='rm' value='2' />
				<input type='hidden' name='no_note' value='1' />";
				
				if($config['defaultCurrency']=="CAD") {
					$hiddenVars .= "<input type='hidden' name='bn' value='ImeiUnlock_Cart_ST_CA'>";
				} else {
					$hiddenVars .= "<input type='hidden' name='bn' value='ImeiUnlock_Cart_ST'>";	
				}
				
	$hiddenVars .= "<input type='hidden' name='upload' value='1' />
				<input type='hidden' name='notify_url' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=call&amp;module=PayPal' />
				<input type='hidden' name='return' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=process&amp;module=PayPal&amp;cart_order_id=".$orderSum['cart_order_id']."' />
				<input type='hidden' NAME='cancel_return' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=process&amp;module=PayPal&amp;cart_order_id=".$orderSum['cart_order_id']."&amp;c=1' />";
				
	return $hiddenVars;
	
}

///////////////////////////
// Other Vars
////////
if($module['testMode']==1) {

	$formAction = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	$formMethod = "post";
	$formTarget = "_self";

} else {

	$formAction = "https://www.paypal.com/cgi-bin/webscr";
	$formMethod = "post";
	$formTarget = "_self";

}
?>