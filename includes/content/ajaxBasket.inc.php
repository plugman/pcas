<?php 
/*
+--------------------------------------------------------------------------
|	Linked with Basket.inc.php
|   ========================================
|	Assign customer id to session	
+--------------------------------------------------------------------------
*/
session_start(); 
require_once ("../../ini.inc.php");
require_once ("../../includes".CC_DS."global.inc.php");
require_once ("../../includes".CC_DS."functions.inc.php");
require_once ("../../classes".CC_DS."db".CC_DS."db.php");
require_once ("../../classes".CC_DS."cart".CC_DS."shoppingCart.php");
require_once ("../../classes".CC_DS."cart".CC_DS."order.php");
require_once ("../../classes".CC_DS."session".CC_DS."cc_session.php");
require_once ("../../classes".CC_DS."cache".CC_DS."cache.php");

$db = new db();
$cart = new cart();
$order	= new order();
$cc_session = new session();
$config = fetchdbconfig("config");
$html = "";
$coupon_code_result="1::";

$lang1 = getLang("includes".CC_DS."content".CC_DS."reg.inc.php");
$lang2 = getLang("includes".CC_DS."content".CC_DS."cart.inc.php");

$lang = array_merge($lang1, $lang2);
 $storeurl = $glob['storeURL'];
if (!empty($cc_session->ccUserData['currency'])) {
	$cCode = $cc_session->ccUserData['currency'];
	
}  else {
	
 	$cCode = $config['defaultCurrency'];
}
	$querycurr			= sprintf("SELECT value, symbolLeft, symbolRight, decimalPlaces, name, decimalSymbol FROM %sImeiUnlock_currencies WHERE code=%s", $glob['dbprefix'], $db->mySQLSafe($cCode));
	$currencyResult	= $db->select($querycurr);

$basket = $cart->cartContents($cc_session->ccUserData['basket']);

if(isset($_POST['remove'])&& !empty($_POST['remove'])) {	
	$cart->unsetVar("invArray");
	$cart->remove($_POST['remove']);	
	//$refresh = true;
	unset($basket);
	$cc_session = new session();
	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
}
if(isset($_POST['shipKey']) && $_POST['shipKey']>0) {
	$cart->setVar($_POST['shipKey'],"shipKey");
	// lose post vars
	unset($basket);
	$cc_session = new session();
	$basket = $cart->cartContents($cc_session->ccUserData['basket']);

} 

if(isset($_POST['remlast'])&& !empty($_POST['remlast'])) {
	$cart->unsetVar("invArray");
	$cart->removeLastItem();
	// lose the post vars
	//$refresh = true;	
	unset($basket);
	$cc_session = new session();
	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
}
if (isset($_POST['remCode'])&& !empty($_POST['remCode'])) {
	$cart->removeCoupon($_POST['remCode']);
	// lose the post vars
	//$refresh = true;	
	unset($basket);
	$cc_session = new session();
	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
}

if (isset($_POST['coupon']) && !empty($_POST['coupon']) && !isset($basket['code'])){	
	$cart->addCoupon($_POST['coupon']);
	// lose post vars
	//$refresh = true;	
	unset($basket);
	$cc_session = new session();
	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
}

if(empty($cc_session->ccUserData['customer_id'])) {
	$CONT_VAL = $storeurl."/index.php?_a=login&amp;redir=step3";
	//$CLASS_CHECKOUT="login-window";
}
// if user is logged in an act = cart jump ahead to step2
else if($cc_session->ccUserData['customer_id']>0) {
#	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
	//$box_content->assign("CONT_VAL","index.php?_g=co&_a=step3");
	$CONT_VAL = "javascript:Checkout();";
	$CLASS_CHECKOUT="";
}

if ($basket['conts']) {
	$tax = 0;
	$taxCustomer = 0;
	$taxZone = array();
	
		$configCounty = $db->select("SELECT `name`, `abbrev` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE `id` = ".$db->mySQLSafe($config['siteCounty']));
	
	$countyInvName = !empty($cc_session->ccUserData['county']) ? $cc_session->ccUserData['county'] : $configCounty[0]['name'];
	$countryInvId 	= !empty($cc_session->ccUserData['country']) ? $cc_session->ccUserData['country'] : $config['siteCountry'];
	
	$query = "SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE `name` = ".$db->mySQLSafe($countyInvName)." AND `countryId` = ".$db->MySQLSafe($countryInvId);
	$countyInv = $db->select($query);
	
	$countyDelName = !empty($basket['delInf']['county']) ? $basket['delInf']['county'] : $configCounty[0]['name'];
	$countyDelAbbr = !empty($basket['delInf']['county']) ? $basket['delInf']['county'] : $configCounty[0]['abbrev'];
	$countryDelId  = !empty($basket['delInf']['country']) ? $basket['delInf']['country'] : $config['siteCountry'];
	
	$query = "SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE (`abbrev` = ".$db->MySQLSafe($countyDelAbbr)." OR `name` = ".$db->MySQLSafe($countyDelName).") AND `countryId` = ".$db->MySQLSafe($countryDelId);
	$countyDel = $db->select($query);
	/* END IMPROVED FUNCTIONALITY TO CALC TAX ON STORE LOCALE IF CUSTOMER IS UNKNOWN */
	
	
			if(!$config['priceIncTax']) { // Only tax customers if tax is enabled
	
		if($config['priceTaxDelInv']==0 || $config['shipAddressLock'] == 1) {
			// calculate tax on delivery address
			$taxZone['countyId'] = $countyDel[0]['id'];
			$taxZone['countryId'] = !empty($basket['delInf']['country']) ? $basket['delInf']['country'] : $countryDelId;
			
		} elseif($config['priceTaxDelInv']==1) {
			// calculate tax on invoice address
			$taxZone['countyId'] = $countyInv[0]['id'];
			$taxZone['countryId'] = !empty($cc_session->ccUserData['country']) ? $cc_session->ccUserData['country'] : $countryInvId;
		}
 	
		if ($taxZone['countryId']==$config['taxCountry']) {
			if ($config['taxCounty']==0) {
				// tax customer
				$taxCustomer = 1;
			} else if ($taxZone['countyId']==$config['taxCounty']) {
				// tax customer
				$taxCustomer = 1;
			}
		}
	}

	
	// start: Flexible Taxes, by Estelle Winterflood
	if ($config_tax_mod['status']){
		// get specific entries for this state, and also entries for this whole country (ie. county_id=0)
		$query = "SELECT d.name AS name, taxName AS type_name, type_id, country_id, county_id, abbrev, tax_percent, goods, shipping, display FROM ".$glob['dbprefix']."ImeiUnlock_taxes AS t, ".$glob['dbprefix']."ImeiUnlock_tax_rates AS r LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_tax_details AS d ON r.details_id=d.id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_iso_counties AS c ON c.id=county_id WHERE r.type_id=t.id AND d.status='1' AND r.active='1' AND country_id='".$taxZone['countryId']."' AND (county_id='0' OR (county_id = '".$taxZone['countyId']."'))";
		
		// "Testing" mode info display
		if ($config_tax_mod['debug']) {
			$tax_debug[] =  "<div style=\"border: 1px solid grey; background: white; text: black; margin-bottom: 1em; padding: 0.5em 1em; font-family: verdana; font-size: 11px;\">";
			$tax_debug[] =  "<p>This information is being printed because the taxes is in &quot;Testing Configuration&quot; mode.<br/>Please use this information to ensure you have your taxes configured correctly, then set the Flexible Taxes mod to &quot;Live Store&quot; mode.</p>";
			$tax_debug[] =  "<p><strong>Customer Delivery Location:</strong><br/>";
			if (empty($basket['delInf']['county'])) {
				$state_id = "n/a";
			} else {
				$state_id = $taxZone['countyId'];
			}
			$tax_debug[] =  "Country id [".$taxZone['countryId']."] State/County/Zone ID [".$state_id."]</p>";
		}

		$taxes_config = $db->select($query);

		$taxes = array();

		// is there any possibility of taxing this delivery address?
		if (is_array($taxes_config))
		{
			if ($config_tax_mod['debug'])
				$tax_debug[] =  "<p><strong>Taxes which affect this customer:</strong><br/>";

			for ($i=0; $i<count($taxes_config); $i++){
				$tax_config = $taxes_config[$i];

				// "Testing" mode info display
				if ($config_tax_mod['debug']) {
					if ($tax_config['abbrev']=="") {
						$state_abbrev = "--all--";
					} else {
						$state_abbrev = $tax_config['abbrev'];
					}
					$tax_debug[] =  "Tax [".$tax_config['name']."] Class [".$tax_config['type_name']."] Country ID [".$tax_config['country_id']."] State/County/Zone [".$state_abbrev."] Rate [".number_format($tax_config['tax_percent'],2)." %]<br/>";
				}

				// Prepare array to hold tax name/display/amount
				// The tax_config array may contain multiple occurances
				// of the same tax if there are multiple tax classes
				$setup = false;
				for ($j=0; $j<count($taxes); $j++){
					if ($taxes[$j]['name'] == $tax_config['name'])
						$setup = true;
				}
				if (!$setup){
					$idx = count($taxes);
					$taxes[$idx]['name'] = $tax_config['name'];
					$taxes[$idx]['display'] = $tax_config['display'].":";
					$taxes[$idx]['amount'] = 0;
				}
			}

			$taxCustomer = 1;

			if ($config_tax_mod['debug'])
				$tax_debug[] =  "</p>";
		}
		// "Testing" mode info display
		elseif ($config_tax_mod['debug']) {
			$tax_debug[] =  "<p><strong>No taxes active/enabled for this location.</strong></p>";
		}
	}

	// end: Flexible Taxes
	$totalWeight	= "";
	$i				= 0;
	$subTotal		= 0;
	$shipCost		= 0;
	$grandTotal		= 0;
	$discount		= 0;
	
	##########################
	## START PRODUCTS LOOP  ##
	##########################
	
	## Start discounts
	if ($basket['discount_percent']>0) {
		$discount_percent	= $basket['discount_percent'];
		
	} else if ($basket['discount_price']>0) {
		$discount_remainder = $basket['discount_price'];
	}
	
	$html='1::  <table cellpadding="0" cellspacing="0" width="630">';
	
	foreach($basket['conts'] as $key => $value){
		$i++;
		$linePrice		= 0;	// line price for basket
		$optionsCost	= 0;	// product options cost
		$plainOpts		= '';	// options as plain text
		$stock_warn		= false;	
		// fetch shipping by category module
		$module = fetchDbConfig("Per_Category");
		$shipByCat = $module['status'];
		
		$extraJoin = "";
		
		if ($shipByCat==1) {
			$extraJoin = "INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category AS C ON I.cat_id = C.cat_id";
		}
		
		## Check for custom basket data e.g Gift Certificates
		if ($basket['conts'][$key]['custom'] == 1) {
					
			$gc = fetchDbConfig('gift_certs');
			
			$product[0]['productId']	= 0;
			$product[0]['productCode']	= $gc['productCode'];
			$product[0]['quantity']		= $basket['conts'][$key]['quantity'];
			$product[0]['price']		= $basket['conts'][$key]['gcInfo']['amount'];
			$product[0]['name']			= $lang['cart']['gift_cert'];
			$product[0]['image']		= $GLOBALS['rootRel'].'images/general/giftcert.gif';
			$product[0]['cat_id']		= 0;
			$product[0]['sale_price']	= 0;
			$product[0]['stock_level']	= 0;
			$product[0]['useStockLevel']= 0;
			$product[0]['digital']		= (strtolower($basket['conts'][$key]['gcInfo']['delivery']) == 'm') ? false : true;
			$product[0]['prodWeight']	= ($gc['delivery']=="e") ? 0 : $gc['weight'];
			$product[0]['taxType']		= $gc['taxType'];
			$product[0]['tax_inclusive']= ($gc['tax']) ? true : false;
			$product[0]['giftCert']		= true;
			
			// START PSEUDO PRODUCT OPTS
				
			$plainOpts .= $lang['cart']['gift_cert_recip_name']." - ".$basket['conts'][$key]['gcInfo']['recipName']."\r\n";
			$plainOpts .= $lang['cart']['gift_cert_recip_email']." - ".$basket['conts'][$key]['gcInfo']['recipEmail']."\r\n";
			$gcMessage = $basket['conts'][$key]['gcInfo']['message'];
		
			if (strlen($gcMessage) > 30) $gcMessage = substr($gcMessage, 0, 30).'&hellip;';
			$plainOpts .= $lang['cart']['gift_cert_recip_message']." - ".$basket['conts'][$key]['gcInfo']['message']."\r\n";
			$plainOpts .= $lang['cart']['gift_cert_delivery']." - ".$lang['cart']['delivery_method_'.$basket['conts'][$key]['gcInfo']['delivery']]."\r\n";
			// END PSEUDO PRODUCT OPTS
		} else {
		
			$productId	= $cart->getProductId($key);	
			$imei		= $cart->getIMEI($key);		
			//$coupon_discount	= $cart->getDiscountValue($key);
		//	$compaignId			= $cart->getCompaignValue($key);
			$product	= $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory AS I INNER JOIN ".$glob['dbprefix']."ImeiUnlock_taxes AS T ON T.id = taxType ".$extraJoin." WHERE I.productId=".$db->mySQLSafe($productId));
			
			// FIX FOR DELETED TAX BANDS PRE 3.0.5
			if (!$product) {
				$product = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId=".$db->mySQLSafe($productId));
				$product[0]['percent'] = 0;
			}
			if (($val = prodAltLang($product[0]['productId'])) == TRUE) {
				$product[0]['name'] = $val['name'];
			}
		}
	
		if ($product[0]['giftCert'] === true) {
	
			$ProdImage =  $storeurl.'/'.imgPath($product[0]["image"], false, '');
		} elseif ($product[0]['case'] === true) {
			$ProdImage =  $storeurl.'/'.imgPath($product[0]["image"], false, '');
		}else {
			if (file_exists(imgPath($product[0]['image'], true, 'root')) && !empty($product[0]['image'])) {
				$ProdImage =  $storeurl.'/'.imgPath($product[0]["image"], true, 'rel');
			} else {
				$ProdImage =  $storeurl.'/skins/'. SKIN_FOLDER . '/styleImages/thumb_nophoto.gif';
			}
		}

		## Only calculate shipping IF the product is tangible
		if (!$product[0]['digital']) {
			$orderTangible = true;
		}
				## Build the product options
		$optionKeys = $cart->getOptions($key);

		if (!empty($optionKeys)) {
			$options = explode('{|}', $optionKeys);
			
			foreach ($options as $value) {
				## Split on separator
				$value_data		= explode('{@}', $value);
				## Get Option Data
				unset($option_name, $option_value);
				$option_top		= $db->select(sprintf("SELECT T.* FROM %1\$sImeiUnlock_options_top AS T WHERE T.option_id = %2\$s", $glob['dbprefix'], $db->mySQLSafe($value_data[0])));
				if ($option_top) {
					$option_name	= $option_top[0]['option_name'];
					if ($option_top[0]['option_type'] == 0) {
						$option		= $db->select(sprintf("SELECT M.*, B.* FROM %1\$sImeiUnlock_options_mid AS M, %1\$sImeiUnlock_options_bot AS B WHERE M.value_id = B.value_id AND B.assign_id = %2\$d", $glob['dbprefix'], $value_data[1]));
						if ($option) {
							$option_price	= $option[0]['option_price'];
							$option_symbol	= $option[0]['option_symbol'];
							$option_value	= $option[0]['value_name'];
						}
					} else {
						$option			= $db->select(sprintf("SELECT B.* FROM %1\$sImeiUnlock_options_bot AS B WHERE B.option_id = %2\$d AND B.product = %3\$d LIMIT 1", $glob['dbprefix'], $value_data[0], $productId));	
						if ($option) {
							$option_price	= $option[0]['option_price'];
							$option_symbol	= $option[0]['option_symbol'];
							$option_value	= $value_data[1];
						}
					}
				}
				if($product[0]['digital'] == 2){
					$option_name	= $value_data[0];
					$option_value	= $value_data[1];
				}
				if (isset($option_value) && isset($option_name)) {
					## Assign values
					//$view_cart->assign('VAL_OPT_NAME', validHTML($option_name));
					//$view_cart->assign('VAL_OPT_VALUE', htmlentities(strip_tags($option_value), ENT_QUOTES, 'UTF-8'));
					$product[0]['option'] .= '<tr><td class="rcolr" nowrap="nowrap"><strong>'.validHTML($option_name).'</strong>:</td><td>'.htmlentities(strip_tags($option_value), ENT_QUOTES, 'UTF-8').'</td></tr>';
					
					$plainOpts .= $option_name.' - '.$option_value."\r\n";
					
					if ($option_price > 0) {
						if ($option_symbol == "+") {
							$optionsCost = $optionsCost + $option_price;
						} else if ($option_symbol == "-") {
							$optionsCost = $optionsCost - $option_price;
						} else if ($option_symbol == "~") {
							$optionsCost = 0;
						}
					}
					
				}
			}
		}
			if ($product[0]['useStockLevel'] && $config['stockLevel']){
			//$view_cart->assign('VAL_INSTOCK', $product[0]['stock_level']);
		} else {
			//$view_cart->assign('VAL_INSTOCK', '&infin;');
		}
				## check if license Key true
		if($product[0]["is_licencekey"] > 0){}else if (($config['outofstockPurchase']) && ($product[0]["stock_level"]<$cart->cartArray['conts'][$key]["quantity"]) && ($product[0]['useStockLevel'])) {
		//	$box_content->assign("VAL_STOCK_WARN",$lang['cart']['stock_warn']);
			
			$quantity = $cart->cartArray['conts'][$key]["quantity"];
			//$box_content->parse("basket.repeat_cart_contents.stock_warn");
		
		} else if ((!$config['outofstockPurchase']) && ($product[0]["stock_level"]<$cart->cartArray['conts'][$key]["quantity"]) && ($product[0]['useStockLevel']))
		{

			//$box_content->assign("VAL_STOCK_WARN",$lang['cart']['amount_capped']." ".$product[0]["stock_level"].".");
			$quantity 	= $product[0]["stock_level"];
			$stock_warn	= true;
			$basket 	= $cart->update($key, $quantity);
			//$box_content->parse("basket.cart_true.repeat_cart_contents.stock_warn");
		} else {
		
			$quantity = $cart->cartArray['conts'][$key]["quantity"];
		}
		//$box_content->assign("VAL_QUANTITY", $quantity);
		
		if ($basket['conts'][$key]['custom']==1 || !salePrice($product[0]['price'], $product[0]['sale_price'])) {
			$price = $product[0]['price'];
		} else {
			$price = salePrice($product[0]['price'], $product[0]['sale_price']);
		}
		
		$price = ($price+$optionsCost < 0) ? 0 : $price+($optionsCost);
		
		if($cc_session->ccUserData['customer_type'] > 0){
		$wprice = getwprice($cc_session->ccUserData['customer_type'], $product[0]['productId']);
			if($wprice > 0){
				$price = $wprice;
				}
			}
		
	 	$linePrice = $price * $quantity;
		 ## Apply discounts
		
		$itemDiscount = 0;
		if (isset($coupon_discount) && $coupon_discount > 0 ) {
		$itemDiscount	= $linePrice*($coupon_discount/100);
		$totalDiscount	+= $linePrice*($coupon_discount/100);
		}
		$linePrice -= $itemDiscount;
		
		$totalApp = $totalApp+ $quantity;
		// set live vars for order inv and its the last step
		$ems_Share 		=($product[0]['revenue']/100)*$linePrice;
		$vendor_Share	= $linePrice - $ems_Share;
		
		$basket = $cart->setVar($productId,"productId","invArray",$i);
		$basket = $cart->setVar($imei,"imei","invArray",$i);	
		$basket = $cart->setVar($product[0]['name'],"name","invArray",$i);
		$basket = $cart->setVar($product[0]['productCode'],"productCode","invArray",$i);
		$basket = $cart->setVar($plainOpts, "prodOptions", "invArray", $i);
		$basket = $cart->setVar(sprintf("%.2f",$linePrice),"price","invArray",$i);
		$basket = $cart->setVar($quantity,"quantity","invArray",$i);
		$basket = $cart->setVar($product[0]['digital'],"digital","invArray",$i);
		
			if ((bool)$basket['conts'][$key]['custom']) {
				$basket = $cart->setVar(serialize($basket['conts'][$key]['gcInfo']),"custom","invArray",$i);
			} else {
	
			}
		
		//$box_content->assign("VAL_IND_PRICE", price_Format($price, true));
		//$box_content->assign("VAL_LINE_PRICE", price_Format($linePrice, true));
		
		## Apply discounts
		$itemDiscount = 0;
		if (isset($discount_percent) && $discount_percent > 0 && !$product[0]['giftCert']) {
			## Percentile discounts
			$itemDiscount	= $linePrice*($discount_percent/100);
			$totalDiscount	+= $linePrice*($discount_percent/100);
						
		} else if (isset($discount_remainder) && $discount_remainder > 0 && !$product[0]['giftCert']) {
			## Fixed value discounts
			if ($discount_remainder <= $linePrice) {
				$discount			= $discount_remainder;
				$discount_remainder	= 0;
			} else if ($discount_remainder > $linePrice) {
				$discount			= $linePrice;
				$discount_remainder = $discount_remainder - $discount;
			}
			
			if ($discount > 0) {
				$totalDiscount	+= $discount;
				$itemDiscount	= $discount;
				$discount		= 0;
			}
		}
		
		$linePrice -= $itemDiscount;
		
		## Calculate weight
		if ($product[0]['prodWeight']>0) {
			$totalWeight = ($product[0]['prodWeight'] * $quantity) + $totalWeight;
		}
		
		## Calculate tax
		if ($taxCustomer) {
			//start: Flexible Taxes, by Estelle Winterflood
			//calculate Tax on Goods
			if ($config_tax_mod['status']) {
				for ($j=0; is_array($taxes_config) && $j<count($taxes_config); $j++) {
					$tax_config = $taxes_config[$j];
					if ($tax_config['type_id'] == $product[0]['taxType']) {
						// tax on goods
						if ($tax_config['goods']) {
						
							$lineTax = getTax($linePrice, ($product[0]['tax_inclusive']) ? false : true, $tax_config['tax_percent']);
							
							for ($k=0; $k<count($taxes); $k++){
								if ($taxes[$k]['name'] == $tax_config['name']){
									// "Testing" mode info display
									if ($config_tax_mod['debug']) {
										if (!isset($debug)) $debug = '';
										$debug .= "Item ".$i." [".$product[0]['name']."] [".$product[0]['taxName']."] [".$quantity." x ".priceFormat($price, true)."] --- Tax [".$taxes[$k]['name']."] [".$tax_config['type_name']."] [".number_format($tax_config['tax_percent'],2)." %] --- ".priceFormat($lineTax,TRUE)."<br/>";
									}
									$taxes[$k]['amount'] += $lineTax;
								}
							}
						}
					}
				}
				## end: Flexible Taxes
			} else if (!$config['priceIncTax']) {
				$tax += getTax($linePrice, ($product[0]['tax_inclusive']) ? false : true, $product[0]['percent']);
			}
		}
		$subTotal += $linePrice;
		$total_Share += $ems_Share;
		
		$md5key = md5($key);
		if(!isset($_POST['remove'])||(isset($_POST['remove'])&& $_POST['remove']!=$md5key)){
			
				$linePrice = round($linePrice,2);
				$linepriceFormate= number_format($linePrice*$currencyResult[0]['value'], $currencyResult[0]['decimalPlaces'], $decimalSymbol, '');
				$html.='<tr >
				
          <td align="center" width="85px"><img alt="" class="imgcartpopup radius3px" src="'.$ProdImage.'"  /> </td>
            
         <td width="250">
              ';
                if($imei==0){
		        $html.='<span class="pname">Product:</span><br/>';
		 }
		 else{
			  $html.='<span class="pname"> Network: </span><br/>';
		 }
$html.= validHTML($product[0]["name"]).'<input name="quan['.md5($key).']" type="hidden" value="'.$quantity.'" size="2" class="textbox" style="text-align:center;"  />
					<br/><span class="pname"> Dilivery time: </span><br/>
            
                '.$product[0]["deltime"].'    ';
			  if($imei>0){
				 $html.=' <br><span class="pname"> IMEI # </span><br/>'.$imei.'<br/>'.$product[0]['option'].'</td>';
				  
			  }	
			 
            
			  $html.= '<td align="left" width="86">';
			
			 $html.= $currencyResult[0]['symbolLeft'].$price;
			
	
			 $html.= '</td>';	
				
				
			$html.= '<td> <input name="" type="text" value="" class="quantityPopup radius3px"  /></td> <td>'.$currencyResult[0]['symbolLeft'].$linepriceFormate.'</td>';
			 $html.='<td>
					 <a onclick="RemoveProduct(\''.$md5key.'\','.$productId.');" href="javascript:void(0);" class="removeedit">&nbsp;</a></td></tr>';
					 
					 if($stock_warn == true)
				{
				   $html.='<tr><td >'.$lang['cart']['amount_capped'].' '.$quantity.'</td></tr>';
				}
					 
		}
		
	}
	/*$html.='</table></div> <div class="botombox3"> <a href="index.php" class="pinkclr" style="float:left; margin:16px 10px 0 0 ;" >Continue Shopping</a> <a href="'.$CONT_VAL.'" class="'.$CLASS_CHECKOUT." button".'">make payment </a> </div>';*/
	## If the voucher is a gift certificate, we'll let them use it to discount shipping
		if ($orderTangible) {
		$shippingModules = $db->select("SELECT DISTINCT `folder` FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE module='shipping' AND status = 1");
		
		$noItems 	= $cart->noItems();
		$sum		= 0;

		if (is_array($shippingModules) && !empty($shippingModules)) {
			// if selected key has not been set, set it
			if (!isset($basket['shipKey'])) $basket = $cart->setVar(1, 'shipKey');
			
			foreach ($shippingModules as $shippingModule) {
				$shippingCalcPath = '../../modules'.CC_DS.'shipping'.CC_DS.$shippingModule['folder'].CC_DS.'calc.php';
				if (file_exists($shippingCalcPath)) {
					include($shippingCalcPath);
				}
			}
			
			#:convict:# Shipping cost from lowest to highest >>
			$Shipping = array();
			if(is_array($shipArray)) {
				foreach ($shipArray as $shipMethod) {
					if (!empty($shipMethod) && is_array($shipMethod)) {
						foreach ($shipMethod as $shipDetails) {
							$Shipping[] = $shipDetails;
						}
					}
				}
			}
			
			function cmp($a, $b) { $b=floatval($b['value']); $a=floatval($a['value']); return $a<$b ? -1 : ($a>$b ? 1 : 0); }
			usort($Shipping, "cmp");
			$shipKey = 0;
			foreach ($Shipping as $shipDetails) {
				$shipKey++;
				if ($shipKey == $basket['shipKey']) {
					$selected = ' selected="selected"';
							
					$basket = $cart->setVar($shipDetails['method'], 'shipMethod');
					$basket = $cart->setVar(sprintf("%.2f", $shipDetails['value']), 'shipCost');
							
					$shippingTaxId		= $shipDetails['taxId'];
					$shippingTaxAmount	= $shipDetails['taxAmount'];
				} else {
					$selected = '';
				}
				$shippingPrice[] = '<option value="'.$shipKey.'"'.$selected.'>'.$shipDetails['desc'].'</option>';
			}
			
		}
		}
	if ($basket['code_is_purchased'] && $totalDiscount < $basket['discount_price']) {
		$shippingCost = (is_numeric($shippingPrice)) ? $shippingPrice : $basket['shipCost'];
		
		if ($discount_remainder <= $basket['shipCost']) {
			$shippingCost	-= $discount_remainder;
			$totalDiscount	= $basket['discount_price'];
			$basket			= $cart->setVar(sprintf('%.2f', $shippingCost), 'shipCost');
			
		} else if ($discount_remainder > $basket['shipCost']) {
			$totalDiscount += $basket['shipCost'];
			$basket			= $cart->setVar(0.00, 'shipCost');
		}
	}
	
	## Work out if the Gift Certificate has any discount left
	if ($basket['code_is_purchased'] == true && $totalDiscount <= $basket['discount_price']) {
		$couponRemainder = $basket['discount_price'] - $totalDiscount;
		$basket	= $cart->setVar(sprintf("%.2f", $couponRemainder),"code_remainder");
		
		## Update coupon so that it can be used again with remainder, if there is any	
		if ($couponRemainder > 0) {
			$giftRecord['discount_price'] = $db->mySQLSafe(sprintf("%.2f", $couponRemainder));
		} else {
			$giftRecord = array(
				'discount_price'	=> 0,
				'allowed_uses'		=> 0,
				'count'				=> 1,
			);
		}
		$basket	= $cart->setVar(sprintf("%.2f",$couponRemainder),"code_remainder");
		
		$where	= 'code = '.$db->mySQLSafe($basket['code']);
		$update	= $db->update($glob['dbprefix'].'ImeiUnlock_Coupons', $giftRecord, $where);
	}

	$basket = $cart->setVar($totalDiscount, "discount");
	// set discount to return value if null
	if(empty($basket['discount']) || !isset($basket['discount'])) { 
		$basket['discount'] = 0; 
	} 	
	if($totalWeight>0){
	//	$box_content->assign("LANG_BASKET_WEIGHT",$lang['cart']['basket_weight']);
	//	$box_content->assign("VAL_BASKET_WEIGHT",$totalWeight.$config['weightUnit']);
	}
	
	
	// paypal processing fee
	$paypal = $config['paypal'];
	
	if(isset($paypal) && $paypal > 0){
		$paypalfee = $subTotal / 100 * $paypal ;
		
		$basket = $cart->setVar($paypalfee, "paypalfee");
		//$box_content->assign("LANG_PAYPAL", $lang2['cart']['paypal']);
		//$box_content->assign("VAL_PAYPAL_FEE",priceFormat($paypalfee));
		
		}
		else {
			
			$paypalfee =0;
			}
	// start: Flexible Taxes, by Estelle Winterflood
	// grand total
	if ($config_tax_mod['status']) {
		$tax = 0;
		for ($i=0; is_array($taxes) && $i<count($taxes); $i++) {
			$tax += $taxes[$i]['amount'];
		}
	 	$grandTotal = $subTotal + $paypalfee  + $tax + $basket['shipCost'];
	}
	// end: Flexible Taxes
	else {
		$grandTotal = $subTotal + $paypalfee + $tax + $basket['shipCost'];
	}

	//echo $paypalfee;
	
	   $coupon_code_result.= '<div class="orange10 bold " style="padding-top:3px; padding-left:20px; padding-bottom:10px; display:none;" id="errorDiscount">Please enter discount code</div>';

	if (isset($basket['codeResult'])) {
		if (!$basket['codeResult']) {
			$base64BasketCode = base64_encode($basket['code']);
					}
					
		if($basket['codeResult']==0){
		/*	$coupon_code_result .= '<span class="txtdrakgreen txt14 arialBold">Add a gift certificate or coupon code</span>
    <input type="text" name="coupon" id="txtcoupon" placeholder="Enter code...." />
   <a href="javascript:void(0);" class="helBold couponSubmit"  onclick="BasketPage();">Apply Now</a>';*/
  
			$coupon_code_result .= ' <span class="coupontxt">Discount code has been applied successfully! <a href="javascript:void(0);" onclick="RemoveCouponCode(\''.$base64BasketCode.'\');" style="color:#F00;">(Remove)</a></span>';
		} else if($basket['codeResult']==1)	{
			$coupon_code_result .= '<span class="coupontxt">Add a gift certificate or coupon code</span> <br/><span class="coupontxt">Sorry, that code has reached its maximum usage.</span>';
		}else if($basket['codeResult']==2){
			$coupon_code_result .= '<span class="coupontxt">Add a gift certificate or coupon code</span> <br/><span style="color:#F00;">Sorry, that code has now expired.</span>';
		}else if($basket['codeResult']==3){
			$coupon_code_result .= '<span style="color:#F00;">Sorry, that code was not found.</span><br>';
		}
	}

	if (!isset($basket['codeResult']) || $basket['codeResult']>0) {
		
		$coupon_code_result .= ' <span class="coupontxt">Add a gift certificate or coupon code</span>
		<input type="text" value="" class="textbox2" name="coupon" id="txtcoupon" onclick="this.value=\'\';" placeholder="Enter code...."  />
       
         <a href="javascript:void(0);"  onclick="BasketPage();" class="button">  Apply Now
		 </a>';
		$basket = $cart->unsetVar("codeResult");
	}
			
	// build array of price vars in session data
	$basket = $cart->setVar(sprintf("%.2f",$subTotal),"subTotal");
	$basket = $cart->setVar(sprintf("%.2f",$total_Share),"totalemsshare");
	$basket = $cart->setVar(sprintf("%.2f",$tax),"tax");
	$basket = $cart->setVar(sprintf("%.2f",$grandTotal),"grandTotal");
	// start: Flexible Taxes, by Estelle Winterflood
	// leave "tax" in session data as the total tax
	// define a new array to hold the individual tax names/amounts
	if ($config_tax_mod['status']) {
		for ($i=1; $i<=count($taxes); $i++) {
			$basket = $cart->setVar($taxes[$i-1]['display'],'tax'.$i.'_disp');
			$basket = $cart->setVar(sprintf("%.2f",$taxes[$i-1]['amount']),'tax'.$i.'_amt');
		}
	}
	
	$grandTotal = round($grandTotal,2);
	$grandTotal = number_format($grandTotal*$currencyResult[0]['value'], $currencyResult[0]['decimalPlaces'], $decimalSymbol, '');

/*if(isset($_POST['remove'])&& !empty($_POST['remove']))
{ 
 	$selectRemoveLK 	= $db->select("select lic_id from tbl_license_indx where hide = 0 AND used = 0 AND reserved = 1 AND customer_id = ".$cc_session->ccUserData['customer_id']." AND productId = ". $db->mySQLSafe($_POST['prdid'])." order by lic_id ASC ");
	if(!empty($selectRemoveLK)){ ## remove extrat reserve keys
		$countRemoveLK = count($selectRemoveLK);		
		for($i=0; $i < $countRemoveLK ; $i++ )
		{
			$time					= "0000:00:00 00:00:00";
			$whereRemoveLK 			= "lic_id = ". $selectRemoveLK[$i]["lic_id"];
			$recordLK['reserved']	= $db->mySQLSafe(0);
			$recordLK['customer_id']= $db->mySQLSafe(0);
			$recordLK['reserveTime']= $db->mySQLSafe($time);
			$updateLK = $db->update("tbl_license_indx",$recordLK, $whereRemoveLK);
			unset($whereRemoveLK);
			unset($recordLK);
			unset($updateLK);
		}
	}
}*/


if($totalApp>0 && isset($_POST['remove'])&& !empty($_POST['remove']))
	{
		$html.= "::";
		$html.= $currencyResult[0]['symbolLeft']." ".$grandTotal;
		$html.= "::";
		$html.= "Your shopping cart has ".$totalApp." items";
		$html.= "::";
		$html.= $totalApp;
		$html.= "::";
		$html.= $currencyResult[0]['symbolLeft']." ".$paypalfee;
			echo $html;
	}
else if(isset($_POST['shipKey'])&& !empty($_POST['shipKey']))
	{
		$html = '';
		$html.= "::";
		$html.= $currencyResult[0]['symbolLeft']." ".$grandTotal;
		$html.= "::";
		$html.= $currencyResult[0]['symbolLeft']." ".$paypalfee;
			echo $html;
	}else if( $totalApp>0 && isset($_POST['SCQuantity'])&& $_POST['SCQuantity']==1)	{
		$prodID = (isset($_POST['pid']) && $_POST['pid'] > 0)?$_POST['pid']:0  ; 
		$prodPrice = (isset($_POST['prodprice']) && $_POST['prodprice'] > 0)?$_POST['prodprice']:0  ;  
		$html.= "::";
		$html.= $currencyResult[0]['symbolLeft']." ".$grandTotal;
		$html.= "::";
		$html.= "Your shopping Basket has ".$totalApp." items";
		$html.= "::";
		$html.= $totalApp;
		$html.= "::";
		$html.= $prodID;
		$html.= "::";
		$html.= $prodPrice;
			echo $html;
	}
	else if(isset($_POST['coupon']) && !empty($_POST['coupon']) )
	{
		$coupon_code_result.= "::";
		$coupon_code_result.=$currencyResult[0]['symbolLeft']." ".$grandTotal;
		$coupon_code_result.= "::";
		$coupon_code_result.= $currencyResult[0]['symbolLeft']." ".$paypalfee;
		$coupon_code_result.= "::".$html;		
		echo $coupon_code_result;
	}
	else if(isset($_POST['remCode'])&& !empty($_POST['remCode']))
	{
		$coupon_code_result.= "::";
		$coupon_code_result.=$currencyResult[0]['symbolLeft']." ".$grandTotal;
		$coupon_code_result.= "::";
		$coupon_code_result.= $currencyResult[0]['symbolLeft']." ".$paypalfee;
		$coupon_code_result.= "::".$html;
		echo $coupon_code_result;
	}
	else if(isset($_POST['checkoutval'])&& $_POST['checkoutval']==1)
	{
		echo $check_stockwarn;
	}
	else
	{
	 $error = "0";
	 $error.= "::";
	 $error.= "<p style='color:#F8BA49; font-weight:bold; text-align:center; margin-top:20px;'>Basket is empty</p>";
	 $error.= "::";
     $error.= "Your shopping cart has 0 items";
	 echo $error;
	}
} 
else
{	 $error = "0";
	 $error.= "::";
	 $error.= "<p style='color:#F8BA49; font-weight:bold; text-align:center; margin-top:20px;'>Basket is empty</p>";
	 $error.= "::";
     $error.= "Your shopping cart has 0 items";
	 echo $error;
}
?>