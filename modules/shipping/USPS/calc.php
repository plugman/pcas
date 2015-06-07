<?php
/*
+--------------------------------------------------------------------------
|	calc.inc.php
|   ========================================
|	Calculate USPS Shipping
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function USPS() {

	//global $totalWeight, $basket, $cc_session, $module;
	global $totalWeight, $basket, $cc_session;
	
	$totalWeight = ($totalWeight>0) ? $totalWeight : 0.01; //fix to stop errors if basket weight is zero.
	$moduleName = "USPS";
	$module = fetchDbConfig("USPS");
	$taxVal = taxRate($module['tax']);
	
	if ($module['status'] == 1) {
		/* Demo code for the USPS class, which retrieves real-time shipping quotes for domestic and international shipments, directly from the United States Postal Service. You'll need cURL and DOMXML to use the class. IT DOES NOT WORK WITH PHP 5 */
		require "class.usps.php";
		
		//Instantiate the class; the last parameter determines what kind of quote you would like (international packages or domestic packages). Use RateV3 for 
		//domestic shipments and IntlRate for international. Bear in mind that the parameters given for add_package() differ depending on the API you choose.
		//You cannot mix and match APIs! If you instantiate the class with RateV3, you must ONLY put domestic packages in your request. (Use the reset() method
		//of the class if you want to clear everything, then set $usps->api to the new API you want to use.) Attempts to add packages that do not fit into
		//the requirements of the current API will fail.
		//echo $module['username']." - ".$module['password'];
		
		$iso = getCountryFormat('USA', 'iso3', 'id');
		$api = ($basket['delInf']['country'] !== $iso || $iso === false) ? "IntlRate" : "RateV3";

		
		//Add a package - create an array with the required parameters and send that to add_package.
		//Domestic shipments require the following parameters:
		//service - Parcel, Priority, Express, First Call, BPM, Library, Media, All
		//ziporigin - The originating ZIP code of the package
		//zip_dest - The destinaton ZIP code of the package
		//pounds, ounces - The weight of the package
		//size - Regular, Large, or Oversize
		//machinable - true or false, only applies to service types Parcel and All
		//Be sure to declare every parameter of the package as a string, or add_package will fail!
		//International packages require the following parameters:
		//pounds, ounces - Package weight
		//mail_type - package, postcards or aerogrammes, matter for the blind, envelope
		//country - Must be a full name of a country, in the USPS list of valid countries. Look at the top of the class code for the list.
		
		if (empty($basket["delInf"]["postcode"]) && $basket["delInf"]["postcode"] !== $cc_session->ccUserData['postcode']) {
			$GLOBALS['basket']['delInf']['postcode'] = $cc_session->ccUserData['postcode'];
		}
		
		$zip_dest = $basket["delInf"]["postcode"];
		
		// fix for US zipcodes e.g. 12345-1234 will become 12345
		
		if (getCountryFormat($basket['delInf']['country'], 'id','iso') == 'US') {
			$zip_dest = str_replace(' ', '', $zip_dest);
			$zip_dest = substr($zip_dest, 0, 5); 
		}
		
		$countryName = getCountryFormat($basket['delInf']['country'], 'id','printable_name');
		
		// convert $totalWeight into proper pounds and ounces for USPS use.
		// conversion math functions added by Sir William
		$lbs = floor($totalWeight);
		$oz = ceil(($totalWeight - $lbs) * 16);
	
		switch($countryName) {
			case "United Kingdom": 
				$countryName = "Great Britain";
				break;
		}
		
		function overWeight($max) {
			global $totalWeight;
			return ($totalWeight>$max) ? true : false;
		}
	
		foreach ($module as $moduleKey => $moduleValue) {
			if (preg_match("#service\_#i", $moduleKey) && $moduleValue) {
				// fix for security changes in post/get vars
			} else if (preg_match("#service#i", $moduleKey) && $moduleValue) {
		
				$moduleKey = str_replace('service', '', $moduleKey);
				
				// only proceed if module is enabled
				if($module["service".$moduleKey]==1){

				switch($moduleKey) {
				
					case "Express":
						$package = array(
							'service' => 'EXPRESS',
							'size' => $module['expressSize'],
							'container' => $module['expressContainer'],
							'mail_type' => "Package"
						);
						$overWeight = overWeight(70);
					break;
					
					case "FirstClass":
						$package = array(
							'service' => 'FIRST CLASS',
							/*'size' => $module['FirstClassSize'],*/
							'mail_type' => "Package",
							'first_class_mail_type' => $module['FirstClassMailType'],
							'machinable' => $module['FirstClassMachineable']
						);
						$overWeight = overWeight(0.812);
					break;
					
					case "Priority":
						$package = array(
							'service' => 'PRIORITY',
							'size' => $module['prioritySize'],
							'container' => $module['priorityContainer'],
							'mail_type' => "Package",
							'width' => $module['priorityWidth'],
							'height' => $module['priorityHeight'],
							'length' => $module['priorityLength'],
							'girth' => $module['priorityGirth']
						);
						$overWeight = overWeight(70);
					break;
					
					case "Parcel":	
						$package = array(
							'service' => 'PARCEL',
							'size' => $module['parcelSize'],
							'machinable' => $module['parcelMachineable'],
							'mail_type' => "Package"
						);
						$overWeight = overWeight(70);
					break;
					
					case "BPM":
						$package = array(
							'service' => 'BPM',
							'size' => $module['BPMSize'],
							'mail_type' => "Package"
						);
						$overWeight = overWeight(15);
					break;
					
					case "Library":
						$package = array(
							'service' => 'LIBRARY',
							'size' => $module['LibrarySize'],
							'mail_type' => "Package"
						);
						$overWeight = overWeight(70);
					break;
					
					case "Media":
						$package = array(
							'service' => 'MEDIA',
							'size' => $module['MediaSize'],
							'mail_type' => "Package"
						);
						$overWeight = overWeight(70);
					break;
			
				}
				
				$package['ziporigin'] = $module['ziporigin'];
				$package['zip_dest'] = $zip_dest;
				$package['pounds'] = $lbs;
				$package['ounces'] = $oz;
				$package['country'] = $countryName;
				$package['value'] = ($basket['grandTotal']>1) ? $basket['grandTotal'] : 1;
				
					if ($overWeight == false) {
					
						$usps = new USPS($module['username'], $module['password'], $api);
						
						if (!$usps->add_package($package) && $module['debug']==0) die("Failed to add the package");
						
						$usps->submit_request();
						
						//Pass the package ID of the shipment you want to get rates for. The USPS API begins counting from 0, so 0 will be the first package
						//added, 1 will be second, etc. If the package you requested returned an error, the error itself will be returned as a string. For here,
						//though, we'll assume all went well. If you want to get the FULL error details for a certain package (such as error number and other
						//such junk), you can call get_package_error($package_id), which will return an array with the error details.
						
						$rates = $usps->get_rates(0);
						$usps->get_package_error($package_id);
						
						if (is_array($rates)) {
							$i = 1;
							foreach ($rates as $key => $value) {
							
								$compareKey = preg_replace("/[^a-zA-Z0-9s]/", "", $key);
								
								if(($usps->api == 'IntlRate' && $module[$compareKey]==true) || $usps->api == 'RateV3') {
							
									$sum = $value;
									
									if (is_numeric($module['handling'])) {
										$sum = $sum + $module['handling'];
									}
									if ($sum>0) {
										if ($taxVal>0) {
											$shippingTax = ($taxVal / 100) * $sum;
										}
										
										$out[] = array(
											'value'		=> $sum,
											'desc'		=> priceFormat($sum, true).' (USPS '.$key.')',
											'method'	=> 'USPS '.$key,
											'taxId'		=> $module['tax'],
											'taxAmount'	=> $shippingTax,
										);
										$i++;
									}
								}
							}
						}
					} 
				} 
				//International shipments have other information returned as well. To access extra, relevant information after making a query to the IntlRate
				//API, use one of the following accessor functions:
				//get_prohibitions($package_id) - Gets a string containing information about stuff that cannot be shipped to that country
				//get_restrictions($package_id) - Gets other restrictions about shipments into that country
				//get_observations($package_id) - Gets other, miscellaneous observations about shipments into that country
				//get_areas_served($package_id) - Gets a list of the areas served within that country
				
				if($api == "IntlRate" && is_array($rates)) break;
			}
		
		}
		
	}
	return ($out) ? $out : false;
}

$shipArray[] = USPS();
?>
