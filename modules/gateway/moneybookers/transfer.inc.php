<?php

$module = fetchDbConfig('moneybookers');

function repeatVars() {
	return false;
}

function fixedVars() {
	global $module, $orderSum, $config;
	
	$billingName = makeName($orderSum['name']);
	
	$hiddenVars = "<input type='hidden' name='pay_to_email' value='".$module['email']."' />
		<input type='hidden' name='transaction_id' value='".$orderSum['cart_order_id']."' />
		<input type='hidden' name='return_url' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=process&amp;module=moneybookers&amp;cart_order_id=".$cart_order_id."' />
		<input type='hidden' name='cancel_url' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=process&amp;module=moneybookers&amp;cart_order_id=".$cart_order_id."&amp;cancelled=true' />
		<input type='hidden' name='status_url' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=call&amp;module=moneybookers' />
		<input type='hidden' name='language' value='EN' />
		<input type='hidden' name='pay_from_email' value='".$orderSum['email']."' />
		<input type='hidden' name='amount' value='".$orderSum['prod_total']."' />
		<input type='hidden' name='currency' value='".$config['defaultCurrency']."' />
		<input type='hidden' name='firstname' value='".$billingName[2]."' />
		<input type='hidden' name='lastname' value='".$billingName[3]."' />
		<input type='hidden' name='address' value='".$orderSum['add_1']." ".$orderSum['add_2']."' />
		<input type='hidden' name='postal_code' value='".$orderSum['postcode']."' />
		<input type='hidden' name='city' value='".$orderSum['town']."' />
		<input type='hidden' name='country' value='".getCountryFormat($orderSum['country'],"id","iso")."' />
		<input type='hidden' name='hide_login' value='1' />
		<input type='hidden' name='payment_methods' value='ACC,WLT,' />
		<input type='hidden' name='recipient_description' value='".$config['siteTitle']."' />
		<input type='hidden' name='merchant_fields' value='referring_platform' />
		<input type='hidden' name='referring_platform' value='cubecart' />
		<input TYPE='hidden' name='status_url2' value='mailto:".$config['masterEmail']."' />";
		if(!empty($module['logoURL'])){
			$hiddenVars .= "<input type='hidden' name='logo_url' value='".$module['logoURL']."'>";
		}
	return $hiddenVars;
}

///////////////////////////
// Other Vars
////////
$formAction = "https://www.moneybookers.com/app/payment.pl";
$formMethod = "post";
$formTarget = "_self";
$transfer	= "auto";
$stateUpdate= true;
