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
|	Core functions for the ccNOW GATEWAY	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function repeatVars()
{

		global $i, $orderInv; 
		/*
		OLD CODE FOR PRODUCT BREAKDOWN
		$hiddenVars = "<input type='hidden' name='x_product_sku_".$i."' value='".$orderInv['productCode']."' />
		<input type='hidden' name='x_product_title_".$i."' value='".$orderInv['name']."' />
		<input type='hidden' name='x_product_quantity_".$i."' value='".$orderInv['quantity']."' />
		<input type='hidden' name='x_product_unitprice_".$i."' value='".sprintf("%.2f",$orderInv['price']/$orderInv['quantity'])."' />
		<input type='hidden' name='x_product_url_".$i."' value='".$GLOBALS['storeURL']."/index.php?_a=viewProd&productId=".$orderInv['productId']."' />";
		*/
		$hiddenVars = "";
		return $hiddenVars;
	
}

function fixedVars()
{
	
	global $module, $orderSum, $config;
	
	//print_r($orderSum);
	//die;

	$orderSum['country_ISO'] = getCountryFormat($orderSum['country'],"id","iso");
	
	$orderSum['country_ISO_d'] =  getCountryFormat($orderSum['country_d'],"id","iso");
	// NEW CODE TO FIX PRODUCT AS ORDER ID
$hiddenVars = "<input type='hidden' name='x_product_sku_1' value='".$orderSum['cart_order_id']."' />
		<input type='hidden' name='x_product_title_1' value='Cart order id ".$orderSum['cart_order_id']."' />
		<input type='hidden' name='x_product_quantity_1' value='1' />
		<input type='hidden' name='x_product_unitprice_1' value='".$orderSum['prod_total']."' />
		<input type='hidden' name='x_product_url_1' value='".$GLOBALS['storeURL']."/index.php' />";

	$hiddenVars .= "<input type='hidden' name='x_login' value='".$module['acName']."' />
					<input type='hidden' name='x_version' value='1.0' />
					<input type='hidden' name='x_fp_arg_list' value='x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code' />
					<input type='hidden' name='x_fp_hash' value='".md5($module['acName']."^x_login^x_fp_arg_list^x_fp_sequence^x_amount^x_currency_code^".$orderSum['cart_order_id']."^".$orderSum['prod_total']."^".$config['defaultCurrency']."^".$module['actKey'])."' />
					<input type='hidden' name='x_fp_sequence' value='".$orderSum['cart_order_id']."' />
					<input type='hidden' name='x_currency_code' value='".$config['defaultCurrency']."' />
					<input type='hidden' name='x_method' value='NONE' />
					<input type='hidden' name='x_name' value='".$orderSum['name']."' />
					<input type='hidden' name='x_address' value='".$orderSum['add_1']."' />
					<input type='hidden' name='x_address2' value='".$orderSum['add_2']."' />
					<input type='hidden' name='x_city' value='".$orderSum['town']."' />
					<input type='hidden' name='x_state' value='".$orderSum['county']."' />
					<input type='hidden' name='x_zip' value='".$orderSum['postcode']."' />
					<input type='hidden' name='x_country' value='".$orderSum['country_ISO']."' />
					<input type='hidden' name='x_phone' value='".$orderSum['phone']."' />
					<input type='hidden' name='x_email' value='".$orderSum['email']."' />
					<input type='hidden' name='x_ship_to_name' value='".$orderSum['name_d']."' />
					<input type='hidden' name='x_ship_to_address' value='".$orderSum['add_1_d']."' />
					<input type='hidden' name='x_ship_to_address2' value='".$orderSum['add_2_d']."' />
					<input type='hidden' name='x_ship_to_city' value='".$orderSum['town_d']."' />
					<input type='hidden' name='x_ship_to_state' value='".$orderSum['county_d']."' />
					<input type='hidden' name='x_ship_to_zip' value='".$orderSum['postcode_d']."' />
					<input type='hidden' name='x_ship_to_country' value='".$orderSum['country_ISO_d']."' />
					<input type='hidden' name='x_invoice_num' value='".$orderSum['cart_order_id']."' />
					<input type='hidden' name='x_instructions' value='".$orderSum['customer_comments']."' />
					<input type='hidden' name='x_amount' value='".$orderSum['prod_total']."' />
					<input type='hidden' name='x_shipping_amount' value='0.00' />";
				
			return $hiddenVars;
	
}

///////////////////////////
// Other Vars
////////
$formAction = "http://www.ccnow.com/cgi-local/transact.cgi";
$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";
?>