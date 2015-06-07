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
|	calc.php
|   ========================================
|	Shipping cost based on individual item costs	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
// per item shipping module

function Per_Item() {
	global $noItems, $lang;
	
	$moduleName = "Per_Item";
	$module = fetchDbConfig("Per_Item");
	$taxVal = taxRate($module['tax']);
	
	if ($module['status'] == true) {
		$sum =  $module['handling'] + ($module['cost'] * $noItems);
		
		if ($taxVal>0) $shippingTax = ($taxVal / 100) * $sum;
		
		$out[0]['value'] = $sum; 
		$out[0]['desc'] = priceFormat($sum, true);
		$out[0]['method'] = $lang['front']['misc_perItem'];
		$out[0]['taxId'] = $module['tax'];
		$out[0]['taxAmount'] = $shippingTax;
		
		return $out;
	}
	return false;
}
$shipArray[] = Per_Item();
?>