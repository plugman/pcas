<?php
if (!defined('CC_INI_SET')) die("Access Denied");
/**
 * Copyright (C) 2006 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

  //Point to the correct directory
  //chdir("..");
  //Include all the required files
  require_once('library/googlecart.php');
  require_once('library/googleitem.php');
  require_once('library/googleshipping.php');
  require_once('library/googletaxrule.php');
  require_once('library/googletaxtable.php');

  // Invoke any of the provided use cases
class Google_Checkout {

	function buildIt($moduleName="") {
	
	global $altCheckoutInv, $altShippingPrices, $config, $config_tax_mod, $db, $glob;
	
	$module = fetchDbConfig($moduleName);
	
	// Create a new shopping cart object
	$merchant_id = $module['merchId'];  //Your Merchant ID
	$merchant_key = $module['merchKey'];  //Your Merchant Key
	$server_type = $module['mode'];
	
	require("classes".CC_DS."cart".CC_DS."order.php");
	$order = new order();
	
	$merchant_private_data = array("sessionid" => $GLOBALS[CC_SESSION_NAME],"cart_order_id" => $order->mkOrderNo());
	$cart =  new GoogleCart($merchant_id, $merchant_key, $server_type, $merchant_private_data); 
	
		// Add items to the cart
		if(is_array($altCheckoutInv)){
			
			/*
			$altCheckoutInv[$i]['taxType']; // to work out tax tables
			$altCheckoutInv[$i]['name'];
			$altCheckoutInv[$i]['options'];
			$altCheckoutInv[$i]['quantity'];
			$altCheckoutInv[$i]['price'];
			$altCheckoutInv[$i]['private_data']
			*/
			
			$taxTypes = array();
			
			for($i=1;$i<=count($altCheckoutInv);$i++)
			{
		
				$item = new GoogleItem($altCheckoutInv[$i]['name'], 
										$altCheckoutInv[$i]['options'], 
										$altCheckoutInv[$i]['quantity'],
										$altCheckoutInv[$i]['price'],
										$money = $config['defaultCurrency'],
										$private_data = $altCheckoutInv[$i]['private_data'],
										$tax_selector = "rule_".$altCheckoutInv[$i]['taxType']
										);
										
				$cart->AddItem($item);
				
				$taxTypes[$altCheckoutInv[$i]['taxType']] = TRUE;
				
			}
			
			// build tax rules and let fun fun commence
			if(is_array($taxTypes))
			{
				
				foreach($taxTypes as $key => $value) {
					
					if($config_tax_mod['status']==1) {
						
						$query = "SELECT t.tax_percent, c.iso, county_id, shipping FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates t LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_iso_countries c ON t.country_id = c.id WHERE `type_id` = 1 AND `active` = 1 ORDER BY tax_percent DESC";
						
						$taxRates = $db->select($query);
		
						if($taxRates==TRUE) {
						
							for($i=0;$i<count($taxRates);$i++) {
							
								$country_area = $taxRates[$i]['iso'];
								
									
									if($taxRates[$i]['shippping']==1) { $shipping_taxed = "true"; } else { $shipping_taxed = "false"; } 
									$tax_rule[$i] = new GoogleTaxRule("alternate", ($taxRates[$i]['tax_percent']*0.01), $country_area, $shipping_taxed);
								
								
								if($country_area == "US") {
									$states[0] = countyAbbrev($taxRates[$i]['county_id']);
									
									$tax_rule[$i]->SetStateAreas($states);
										
									
								} else {
									$tax_rule[$i]->SetStateAreas("FALSE");
								}
							
							}
							
							$tax_table = new GoogleTaxTable("alternate","rule_".$key, $standalone = "true");
							foreach($tax_rule as $tax_rule_key => $tax_rule_val) {
								$tax_table->AddTaxRules($tax_rule[$tax_rule_key]);
							}
							$cart->AddTaxTables($tax_table);
							
						}	
			
					
					} else {
						
						$taxRate = $db->select("SELECT `percent` FROM ".$glob['dbprefix']."ImeiUnlock_taxes WHERE `id` = ".$db->mySQLsafe($key));
						
						$country_area = getCountryFormat($config['taxCountry'],"id","iso");
						
						$tax_rule = new GoogleTaxRule("alternate", ($taxRate[0]['percent']*0.01), $country_area, $shipping_taxed="false");
						if($config['taxCounty']>0){
							$stateCode = countyAbbrev($config['taxCounty']);
							$tax_rule->SetStateAreas($stateCode);
						} else {
							// state required to set country?!!? No sense AT all.
							$tax_rule->SetStateAreas("FALSE");
						}
						
						$tax_table = new GoogleTaxTable("alternate","rule_".$key, $standalone = "true");
						$tax_table->AddTaxRules($tax_rule);
						$cart->AddTaxTables($tax_table);
						
					
					}
				
				}
			
			}
			
			if(is_array($altShippingPrices)) {
			
				for($i=0;$i<count($altShippingPrices);$i++) {
					$ship = new GoogleShipping($altShippingPrices[$i]['name'], "flat-rate", $altShippingPrices[$i]['price']);
					$ship->SetAllowedCountryArea("ALL");
					$cart->AddShipping($ship);
				}
			
			}
			
	
	
			if($module['debug']==1) {
				// echo XML out
				echo "<pre style='background-color: white; font-family: Courier New, Courier, mono;'><h3>XML -> Google Checkout</h3>".htmlspecialchars($cart->GetXML())."</pre>";
			}
	
			// Display Google Checkout button
			return $cart->CheckoutButtonCode($module['size']);
		}
	
	}

}
?>