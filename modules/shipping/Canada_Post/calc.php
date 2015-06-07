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
|   Date: Friday, 15 July 2007
|   Email: info@cubecart.com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	calc.php
|   ========================================
|	Canada Post Module
+--------------------------------------------------------------------------
*/
// Canada Post
if (!defined('CC_INI_SET')) die("Access Denied");
require_once 'classes'.CC_DS.'canadapost.class.php';

function Canada_Post() {
	global $lang, $subTotal, $totalWeight, $basket, $config; // $basket is the basket array
	
	
	// convert $totalWeight to KG's
	$totalWeightKg = ($config['weightUnit']=="Kg") ? $totalWeight : ceil($totalWeight*0.45359237);
	
	$moduleName = "Canada_Post";
	$module = fetchDbConfig($moduleName);
	$taxVal = taxRate($module['tax']);
	
	if ($module['status'] == true) {
		$postageArray = array(
				'subTotal'	=> $subTotal,
				'totalWeight'=> $totalWeightKg,
				'basket'	=> array(
				'city'		=> $basket['delInf']['town'],
				'state'		=> $basket['delInf']['county'],
				'postcode'	=> $basket['delInf']['postcode'],
				'country'	=> getCountryFormat($basket['delInf']['country']),
			),
		);
		$cpost = new Canada_Post($module, $postageArray);
		
		$i = 0;
		if (is_array($cpost->request())) {
			
			foreach ($cpost->request() as $method) {
				
				$method['price'] = number_format($method['price'],2) + number_format($module['handling'],2);
				
				$out[$i]['value']	= $method['price']; # Shipping value out e.g. 4.34 
				$out[$i]['desc']	= priceFormat($method['price'], true)." ".$method['name']; # Formatted price to show on cart page
				$out[$i]['method']	= "Canada Post (".$method['name'].") ".priceFormat($module['level'], true); // Description of shipping method e.g. Canada Post (Air Mail) $3.25
				## depending on how the store is configured tax can either be calculated now or later with the id taxRate() will bring back a value based on ID
				$out[$i]['taxId']	= $module['tax']; // Tax ID number used by cart.inc.php to work out tax on shipping later
				$out[$i]['taxAmount']= $taxVal > 0 ? $taxVal/100 * $method['price'] : 0; // Tax amount as an amount
				$i++;
			}
			return $out;
		}
	}
	return false;
}

$shipArray[] = Canada_Post();

?>