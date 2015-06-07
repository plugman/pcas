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
|	line.php
|   ========================================
|	Calculates Category Line Shipping Cost	
+--------------------------------------------------------------------------
*/

// per category shipping module
$module = fetchDbConfig("Per_Category");

// get the delivery ISO
$countryISO = getCountryFormat($basket['delInf']['country'],"id","iso");

// build array of ISO Codes
$zones['n'] = explode(",",str_replace(" ","",strtoupper($module['national'])));
$zones['i'] = explode(",",str_replace(" ","",strtoupper($module['international'])));

// find the country
foreach ($zones as $key => $value) {
	foreach ($zones[$key] as $no => $iso) {
		if ($iso == $countryISO) {
			$shipZone = $key;
		}
	}
}

if($shipZone == "n")
{

	$lineShip = ($product[0]['item_ship'] * $quantity) + $lineShip;
	
	if(!isset($perShipPrice) OR $perShipPrice<$product[0]['per_ship'])
	{
		$perShipPrice = $product[0]['per_ship'];
	}

} 
elseif($shipZone == "i")
{
	
	$lineShip = ($product[0]['item_int_ship'] * $quantity) + $lineShip;
	
	if(!isset($perShipPrice) OR $perShipPrice<$product[0]['per_int_ship'])
	{
		$perShipPrice = $product[0]['per_int_ship'];
	}

} 
?>