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
|	Core functions for the PayOffline Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function repeatVars(){
		return FALSE;
}

function fixedVars(){

	global $module, $orderSum, $config;
	
	$hiddenVars = "<input type='hidden' name='instId' value='".$module['acNo']."' />
	                <input type='hidden' name='sign' value='".md5($module['acNo'].$orderSum['cart_order_id'].$orderSum['prod_total'].$module['password'])."' />
					<input type='hidden' name='cartId' value='".$orderSum['cart_order_id']."' />
					<input type='hidden' name='MC_OID' value='".$orderSum['cart_order_id']."' />
					<input type='hidden' name='amount' value='".$orderSum['prod_total']."' />
					<input type='hidden' name='currency' value='".$config['defaultCurrency']."' />
					<input type='hidden' name='desc' value='Cart - ".$orderSum['cart_order_id']."' />
					<input type='hidden' name='name' value='".$orderSum['name']."' />
					<input type='hidden' name='RetURL' value='index.php?_g=co&_a=confirmed&amp;s=1' />
					<input type='hidden' name='storeurl' value='".$GLOBALS['storeURL']."' />";
					
					if(!empty($orderSum['add_2'])){

						$add = $orderSum['add_1'].",&#10;".$orderSum['add_2'].",&#10;".$orderSum['town'].", ".$orderSum['county'].",&#10;".getCountryFormat($orderSum['country'],"id","printable_name");
					
					} else {
						
						$add = $orderSum['add_1'].",&#10;".$orderSum['town'].",&#10;".$orderSum['county'].",&#10;".getCountryFormat($orderSum['country'],"id","printable_name");
					
					}
					
					$hiddenVars .= "<input type='hidden' name='address' value='".$add."' />
					<input type='hidden' name='postcode' value='".$orderSum['postcode']."' />
					<input type='hidden' name='country' value='".getCountryFormat($orderSum['country'],"id","iso")."' />
					<input type='hidden' name='tel' value='".$orderSum['phone']."' />
					<input type='hidden' name='email' value='".$orderSum['email']."' />";
				
				if($module['testMode']>0){	
					$hiddenVars .= "<input type='hidden' name='testMode' value='".$module['testMode']."' />";
				}
				
			return $hiddenVars;
			
}

///////////////////////////
// Other Vars
///////////////////////////
if($module['testMode']>0){    
    $formAction = "http://test.PayOffline.com/TestTrans/ImeiUnlock.aspx";
} else {
    $formAction = "http://secure.PayOffline.com/ImeiUnlock/Invoice.aspx";
}

$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";
$stateUpdate = TRUE;
?>