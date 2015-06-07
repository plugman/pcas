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
|	Core functions for the 2Checkout (v2) Gateway	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

function repeatVars() {

		global $i, $orderInv;

		$hiddenVars = "<input type='hidden' name='c_prod_".$i."' value='".$orderInv['productCode'].",".$orderInv['quantity']."' />
		<input type='hidden' name='id_type_".$i."' value='1' />
		<input type='hidden' name='c_name_".$i."' value='".$orderInv['name']."' />
		<input type='hidden' name='c_description_".$i."' value='".$orderInv['name']."' />
		<input type='hidden' name='c_price_".$i."' value='".sprintf("%.2f",$orderInv['price'])."' />";
		
		if($product[0]["digital"]==1)
		{
			$hiddenVars .= "<input type='hidden' name='c_tangible_".$i."' value='Y' />";
		} 
		else 
		{
			$hiddenVars .= "<input type='hidden' name='c_tangible_".$i."' value='N' />";
		}
		
		return $hiddenVars;
	
}

function fixedVars()
{
	
	global $module, $orderSum, $config;
	
	$hiddenVars = "<input type='hidden' name='sid' value='".$module['acNo']."' />
			<input type='hidden' name='cart_order_id' value='".$orderSum['cart_order_id']."' />
			<input type='hidden' name='total' value='".$orderSum['prod_total']."' />
			<input type='hidden' name='card_holder_name' value='".$orderSum['name']."' />
			<input type='hidden' name='street_address' value='".$orderSum['add_1']." ".$orderSum['add_2']."' />
			<input type='hidden' name='city' value='".$orderSum['town']."' />
			<input type='hidden' name='state' value='".$orderSum['county']."' />
			<input type='hidden' name='country' value='".$orderSum['country']."' />
			<input type='hidden' name='zip' value='".$orderSum['postcode']."' />
			<input type='hidden' name='phone' value='".$orderSum['phone']."' />
			<input type='hidden' name='email' value='".$orderSum['email']."' />
			<input type='hidden' name='ship_name' value='".$orderSum['name_d']."' />
			<input type='hidden' name='ship_street_address' value='".$orderSum['add_1_d']." ".$orderSum['add_2_d']."' />
			<input type='hidden' name='ship_city' value='".$orderSum['town_d']."' />
			<input type='hidden' name='ship_state' value='".$orderSum['country_d']."' />
			<input type='hidden' name='ship_zip' value='".$orderSum['postcode_d']."' />
			<input type='hidden' name='ship_country' value='".$orderSum['country_d']."' />";
			
			if($module['testMode']=="Y")
			{
				
				$hiddenVars .= "<input type='hidden' name='demo' value='Y' />";
			
			} 
			else 
			{
				
				$hiddenVars .= "<input type='hidden' name='demo' value='N' />";
			
			}
				
			return $hiddenVars;
	
}


///////////////////////////
// Other Vars
////////
$formAction = "https://www2.2checkout.com/2co/buyer/purchase";
$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";

$emailText = "Charges for the purchase will appear on your statement under the name 2Checkout.com\r\n";
$emailText .= "Questions regarding your order should be directed to:\r\n\r\n";
$emailText .= $config['storeName']."\r\n";
$emailText .= $GLOBALS['storeURL']."\r\n";
$emailText .= $config['masterEmail'];
?>