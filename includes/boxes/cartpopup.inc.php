<?php
/*
+--------------------------------------------------------------------------
|	cart.inc.php
|   ========================================
|	Core Checkout & Cart Pages	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) die("Access Denied");

session_start(); // required for PayPal Pro
require_once "classes".CC_DS."cart".CC_DS."shoppingCart.php";
/* BEGIN Browser back button fix - Thanks Convict */

if ($_SESSION['cc_back']) { // session variable set in gateway.inc.php around line 290

	$sess_cart_order_id = base64_decode($_SESSION['cc_back']);
	unset($_SESSION['cc_back']);

	require_once("classes".CC_DS."cart".CC_DS."order.php");
	$order	= new order();
	$sess_sum = $order->getOrderSum($sess_cart_order_id);
	if ($sess_sum['status']!=2 && $sess_sum['status']!=3) {
		$pastBasket = $db->select(sprintf("SELECT `basket` FROM `%sImeiUnlock_order_sum` WHERE `cart_order_id` = %s",$glob['dbprefix'],$db->MySQLSafe($sess_cart_order_id)));
		if ($pastBasket==true) {
			$record['basket'] = "'".$pastBasket[0]['basket']."'";
			$db->update($glob['dbprefix']."ImeiUnlock_sessions", $record, "`sessId`= '".$cc_session->ccUserData['sessId']."'");
		}
		$order->orderStatus(6, $sess_cart_order_id, false, true);
		httpredir(currentPage());
	}
}
/* END Browser back button fix - Thanks Convict */

// include lang file

//$lang1 = getLang("includes".CC_DS."content".CC_DS."reg.inc.php");
$lang = getLang("includes".CC_DS."content".CC_DS."cart.inc.php");
$box_content = new XTemplate ("boxes".CC_DS."cartpopup.tpl");
//$lang = array_merge($lang1, $lang2);

//require_once("classes".CC_DS."cart".CC_DS."shoppingCart.php");
$cart = new cart();

// Dangerous var fixed $box_content->assign("VAL_BACK_TO", $_GET['_a']);
//$allowed_a = array("cart","step1","step2");
//$current_a = (in_array($_GET['_a'],$allowed_a)) ? $_GET['_a'] : "cart";

// check the user is logged on

// check the user is logged on
if(empty($cc_session->ccUserData['customer_id'])) {
$onclick = "jQuery('#cart-box a.close').click();";
	$box_content->assign("CONT_VAL","index.php?_a=login&amp;redir=step3");
	//$box_content->assign("CLASS_CHECKOUT","login-window");
	$box_content->assign("ONCLICK_ACTION",'onclick="'.$onclick.'"');
	
}

// if user is logged in an act = cart jump ahead to step2
else if($cc_session->ccUserData['customer_id']>0) {
#	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
	//$box_content->assign("CONT_VAL","index.php?_g=co&_a=step3");
	$box_content->assign("CONT_VAL","javascript:Checkout();");
	$box_content->assign("ONCLICK_ACTION","");
	$box_content->assign("CLASS_CHECKOUT","");

}
	$box_content->assign("STOREURL", $glob['storeURL']);
if($_GET['_a']=="step2" && empty($cc_session->ccUserData['customer_id'])) {
	//httpredir("index.php?_g=co&_a=step1");
	
}
// if user is logged in an act = cart jump ahead to step2
else if($_GET['_a']=="cart" && $cc_session->ccUserData['customer_id']>0) {
#	$basket = $cart->cartContents($cc_session->ccUserData['basket']);
#	if (!empty($basket)) {
		//httpredir("index.php?_g=co&_a=step2");
		
#	}
}

$basket = $cart->cartContents($cc_session->ccUserData['basket']);
/*echo "<pre>";
print_r($basket);die();*/
// Flexible Taxes, by Estelle Winterflood
$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");

if(isset($_GET['remlast'])) {
	$cart->unsetVar("invArray");
	$cart->removeLastItem();
	$refresh = true;
}
if (isset($_GET['remCode'])) {
	$cart->removeCoupon($_GET['remCode']);
	// lose the post vars
	$refresh = true;
	
}

if (isset($_POST['coupon']) && !empty($_POST['coupon']) && !isset($basket['code'])){
	
	$cart->addCoupon($_POST['coupon']);
	// lose post vars
	$refresh = true;
	
}
if(isset($_POST['shipKey']) && $_POST['shipKey']>0) {

	$cart->setVar($_POST['shipKey'],"shipKey");
	// lose post vars
	$refresh = true;

} 
if(isset($_POST['delInf'])) {
	
	// start: Flexible Taxes, by Estelle Winterflood
	if (isset($_POST['which_field'])){
		$delivery = $_POST['delInf'];
		if ($_POST['which_field']=="T"){
			unset($delivery['county_sel']);
		} elseif ($_POST['which_field']=="S") {
			$delivery['county'] = $delivery['county_sel'];
			unset($delivery['county_sel']);
		}
		$_POST['delInf'] = $delivery;
	}
	
	$cart->setVar($_POST['delInf'],"delInf");
	$refresh = true;
	
}

if(isset($_GET['remove'])) {
	$cart->unsetVar("invArray");
	$cart->remove($_GET['remove']);
	$refresh = true;
} 

if (isset($_POST['quan'])) {
	$cart->unsetVar('invArray');
	foreach ($_POST['quan'] as $key => $value) {
		$cart->update($key, $value);
	}
	$refresh = true;
} 
if(isset($_GET['mode']) && $_GET['mode'] == "emptyCart") {
	## Empty the cart
	$cart->emptyCart();
	$refresh = true;
}

if(isset($_POST['productCode']) && !empty($_POST['productCode'])) {
	$cart->addByCode($_POST['productCode']);
	
	$refresh = true;
}

if ($refresh) {
	$excludeGetVars = array("editDel" => 1, "remCode" => 1, "mode" => 1, "remove" => 1, "remlast" => 1);
	httpredir(currentpage($excludeGetVars));
}


$box_content->assign("LANG_CART",$lang['cart']['cart']);
$box_content->assign("LANG_CHECKOUT", $lang['cart']['checkout']);
$box_content->assign("LANG_PAYMENT", $lang['cart']['payment']);
$box_content->assign("LANG_COMPLETE", $lang['cart']['complete']);
$box_content->assign("LANG_ADD_PRODCODE",$lang['cart']['add_more']);
$box_content->assign("LANG_ADD", $lang['cart']['add']);
$box_content->assign("LANG_QTY",$lang['cart']['qty']);
$box_content->assign("LANG_PRODUCT",$lang['cart']['product']);
$box_content->assign("VAL_CART_ITEMS", $cart->noItems());
$box_content->assign("LANG_CODE",$lang['cart']['code']);


$box_content->assign("LANG_STOCK",$lang['cart']['stock']);
$box_content->assign("LANG_PRICE",$lang['cart']['price']);
$box_content->assign("LANG_LINE_PRICE",$lang['cart']['line_price']);
$box_content->assign("LANG_DELETE",$lang['cart']['delete']);
$box_content->assign("LANG_REMOVE_ITEM",$lang['cart']['remove']);

if ($_GET['_a'] == 'cart') {

	$box_content->assign("CONT_VAL","index.php?_g=co&_a=step1");
	$box_content->assign("LANG_CHECKOUT_BTN",$lang['cart']['checkout_btn']);
	$box_content->assign("LANG_VIEW_CART",$lang['cart']['view_cart']);
	$box_content->assign("CLASS_CART","class='txtcartProgressCurrent'");
	$box_content->assign("CLASS_STEP2","");
	
} else if ($_GET['_a'] == 'step2' && !empty($basket['conts'])) {
	
#	if (empty($basket)) httpredir('?_g=co&_a=cart');
	
	// Place Order Link
	$box_content->assign("CONT_VAL", "index.php?_g=co&_a=step3");
	$box_content->assign("CONT_VAL", ($_GET['editDel'] == 1) ? "javascript:submitDoc('cart');" : "index.php?_g=co&_a=step3");
	
	$box_content->assign("CLASS_CART","");
	$box_content->assign("CLASS_STEP2","class='txtcartProgressCurrent'");
	$box_content->assign("LANG_VIEW_CART",$lang['cart']['place_order_title']);

	$box_content->assign("LANG_INVOICE_ADDRESS",$lang['cart']['invoice_address']);
	$box_content->assign("LANG_DELIVERY_ADDRESS",$lang['cart']['delivery_address']);
	
	$box_content->assign("TXT_TITLE",$lang['cart']['title']);
	$box_content->assign("LANG_TITLE_DESC",$lang['reg']['title_desc']);

	$box_content->assign("TXT_FIRST_NAME",$lang['cart']['first_name']);
	$box_content->assign("TXT_LAST_NAME",$lang['cart']['last_name']);
	$box_content->assign("TXT_COMPANY_NAME",$lang['cart']['company_name']);
	$box_content->assign("TXT_ADD_1",$lang['cart']['address2']);
	$box_content->assign("TXT_ADD_2","");
	$box_content->assign("TXT_TOWN",$lang['cart']['town']);
	$box_content->assign("TXT_COUNTY",$lang['cart']['county']);
	$box_content->assign("TXT_POSTCODE",$lang['cart']['postcode']);
	$box_content->assign("TXT_COUNTRY",$lang['cart']['country']);
	
	// PayPal EC make payment button
	if (isset($_SESSION['token']) && isset($_SESSION['payer_id'])) {
		$lang = getLang("includes".CC_DS."content".CC_DS."gateway.inc.php");
		$box_content->assign("LANG_CHECKOUT_BTN",$lang['gateway']['continue']);
	} else {
		$box_content->assign("LANG_CHECKOUT_BTN",$lang['cart']['place_order']);
	}
	
	// stick in delivery details
	if (!isset($basket['delInf']) || $config['shipAddressLock']) {
		$iniDeliv['title']			= stripslashes(html_entity_decode($cc_session->ccUserData['title']));
		$iniDeliv['firstName']		= stripslashes(html_entity_decode($cc_session->ccUserData['firstName']));
		$iniDeliv['lastName']		= stripslashes(html_entity_decode($cc_session->ccUserData['lastName']));
		$iniDeliv['companyName']	= stripslashes(html_entity_decode($cc_session->ccUserData['companyName']));
		$iniDeliv['add_1']			= stripslashes(html_entity_decode($cc_session->ccUserData['add_1']));
		$iniDeliv['add_2']			= stripslashes(html_entity_decode($cc_session->ccUserData['add_2']));
		$iniDeliv['town']			= stripslashes(html_entity_decode($cc_session->ccUserData['town']));
		$iniDeliv['county']			= stripslashes(html_entity_decode($cc_session->ccUserData['county']));
		$iniDeliv['postcode']		= stripslashes(html_entity_decode($cc_session->ccUserData['postcode']));
		$iniDeliv['country']		= stripslashes(html_entity_decode($cc_session->ccUserData['country']));
		$basket = $cart->setVar($iniDeliv, 'delInf');
	} 	
		
	// stick in delivery details
	$box_content->assign("VAL_DEL_TITLE", stripslashes(html_entity_decode($basket['delInf']['title'])));
	$box_content->assign("VAL_DEL_FIRST_NAME",stripslashes(html_entity_decode($basket['delInf']['firstName'])));
	$box_content->assign("VAL_DEL_LAST_NAME",stripslashes(html_entity_decode($basket['delInf']['lastName'])));
	$box_content->assign("VAL_DEL_COMPANY_NAME",stripslashes(html_entity_decode($basket['delInf']['companyName'])));
	$box_content->assign("VAL_DEL_ADD_1",stripslashes(html_entity_decode($basket['delInf']['add_1'])));
	$box_content->assign("VAL_DEL_ADD_2",stripslashes(html_entity_decode($basket['delInf']['add_2'])));
	$box_content->assign("VAL_DEL_TOWN",stripslashes(html_entity_decode($basket['delInf']['town'])));
	$box_content->assign("VAL_DEL_COUNTY",stripslashes(html_entity_decode($basket['delInf']['county'])));
	$box_content->assign("VAL_DEL_POSTCODE",stripslashes(html_entity_decode($basket['delInf']['postcode'])));
	$box_content->assign("VAL_DEL_COUNTRY",getCountryFormat($basket['delInf']['country'],"id","printable_name"));
	
	// stick in invoice details
	$box_content->assign("VAL_TITLE",stripslashes($cc_session->ccUserData['title']));
	$box_content->assign("VAL_FIRST_NAME",stripslashes($cc_session->ccUserData['firstName']));
	$box_content->assign("VAL_LAST_NAME",stripslashes($cc_session->ccUserData['lastName']));
	$box_content->assign("VAL_COMPANY_NAME",stripslashes($cc_session->ccUserData['companyName']));
	$box_content->assign("VAL_ADD_1",stripslashes($cc_session->ccUserData['add_1']));
	$box_content->assign("VAL_ADD_2",stripslashes($cc_session->ccUserData['add_2']));
	$box_content->assign("VAL_TOWN",stripslashes($cc_session->ccUserData['town']));
	$box_content->assign("VAL_COUNTY",stripslashes($cc_session->ccUserData['county']));
	$box_content->assign("VAL_POSTCODE",stripslashes($cc_session->ccUserData['postcode']));
	$box_content->assign("VAL_COUNTRY",getCountryFormat($cc_session->ccUserData['country'],"id","printable_name"));
	
	$box_content->assign("LANG_CHANGE_INV_ADD",$lang['cart']['edit_invoice_address']);
	$box_content->assign("VAL_BACK_TO", $current_a);
	
	// start: Flexible Taxes, by Estelle Winterflood
	// counties selector

	if (isset($_GET['editDel']) && $_GET['editDel'] == true && !$config['shipAddressLock']) {
		$jsScript = jsGeoLocationExtended('country', 'county_sel', $lang['cart']['na'], 'divCountySelect', 'divCountyText', 'county', 'which_field');
		$counties = $db->select("SELECT * FROM  ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = '".$basket['delInf']['country']."';");
		
		if (is_array($counties)){
			$box_content->assign("VAL_COUNTY_SEL_STYLE", "style='display:block;'");
			$box_content->assign("VAL_COUNTY_TXT_STYLE", "style='display:none;'");
			$box_content->assign("VAL_COUNTY_WHICH_FIELD", "S");
		} else {
			$box_content->assign("VAL_COUNTY_SEL_STYLE", "style='display:none;'");
			$box_content->assign("VAL_COUNTY_TXT_STYLE", "style='display:block;'");
			$box_content->assign("VAL_COUNTY_WHICH_FIELD", 'T');
		}
		$box_content->assign('JS_COUNTY_OPTIONS', '<script type="text/javascript">'.$jsScript.'</script>');
	
		for ($i=0; $i<count($counties); $i++) {
			if ($counties[$i]['name'] == $basket['delInf']['county']){
				$box_content->assign('COUNTY_SELECTED', 'selected="selected"');
			} else {
				$box_content->assign('COUNTY_SELECTED', '');
			}
	
			$countyName = $counties[$i]['name'];
			if (strlen($countyName)>20) $countyName = substr($countyName ,0, 20).'&hellip;';
			
			$box_content->assign('VAL_DEL_COUNTY_NAME', $countyName);
			$box_content->parse('cartpopup.cart_true.edit_delivery.county_opts');
		}
		
		// end: Flexible Taxes
		$cache		= new cache('glob.countries');
		$countries	= $cache->readCache();
		
		if (!$cache->cacheStatus) {
			$countries = $db->select("SELECT id, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
			$cache->writeCache($countries);
		} 
	
		for ($i=0; $i<count($countries); $i++) {
			if ($countries[$i]['id'] == $basket['delInf']['country']) {
				$box_content->assign('COUNTRY_SELECTED', 'selected="selected"');
			} else {
				$box_content->assign('COUNTRY_SELECTED', '');
			}
			$box_content->assign("VAL_DEL_COUNTRY_ID",$countries[$i]['id']);
			$countryName = $countries[$i]['printable_name'];
	
			if (strlen($countryName)>20) {
				$countryName = substr($countryName,0,20)."&hellip;";
			}
	
			$box_content->assign('VAL_DEL_COUNTRY_NAME', $countryName);
			$box_content->parse('cartpopup.cart_true.edit_delivery.country_opts');
		}
		$box_content->parse('cartpopup.cart_true.edit_delivery');
	} else {
		if (!$config['shipAddressLock']) {
			$box_content->assign('LANG_CHANGE_DEL_ADD', $lang['cart']['edit_delivery_address']);
			$box_content->parse('cartpopup.cart_true.fixed_delivery.edit_btn');
		}
		$box_content->parse("cartpopup.cart_true.fixed_delivery");
	}
}

## See if there are contents in the basket array

if ($basket['conts']) {

	$tax = 0;
	$taxCustomer = 0;
	$taxZone = array();
	
	// $config['priceTaxDelInv'] 0 = devivery address
	// $config['priceTaxDelInv'] 1 = invoice address
	//$countyDel = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE `abbrev` = ".$db->MySQLSafe($basket['delInf']['county'])." AND `countryId` = ".$db->MySQLSafe($basket['delInf']['country']));
	
	/* START OLD CODE TO WORK OUR TAX ON CUSTOMER INFO
	$countyDel = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE (`abbrev` = ".$db->MySQLSafe($basket['delInf']['county'])." OR `name` = ".$db->MySQLSafe($basket['delInf']['county']).") AND `countryId` = ".$db->MySQLSafe($basket['delInf']['country']));
	
	$countyInv = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE `name` = ".$db->mySQLSafe($cc_session->ccUserData['county'])." AND `countryId` = ".$db->MySQLSafe($cc_session->ccUserData['country']));
	END OLD CODE TO WORK OUR TAX ON CUSTOMER INFO */
	
	/* START IMPROVED FUNCTIONALITY TO CALC TAX ON STORE LOCALE IF CUSTOMER IS UNKNOWN */
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
	if (!$config['priceIncTax'] && $config_tax_mod['status']) {
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
	
	foreach($basket['conts'] as $key => $value){
		
		$i++;
		$linePrice		= 0;	// line price for basket
		$optionsCost	= 0;	// product options cost
		$plainOpts		= '';	// options as plain text

		
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
			$box_content->assign("VAL_OPT_NAME",$lang['cart']['gift_cert_recip_name']);
			$box_content->assign("VAL_OPT_VALUE",$basket['conts'][$key]['gcInfo']['recipName']);
				
			$plainOpts .= $lang['cart']['gift_cert_recip_name']." - ".$basket['conts'][$key]['gcInfo']['recipName']."\r\n";
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.options");
			
			$box_content->assign("VAL_OPT_NAME",$lang['cart']['gift_cert_recip_email']);
			$box_content->assign("VAL_OPT_VALUE",$basket['conts'][$key]['gcInfo']['recipEmail']);
				
			$plainOpts .= $lang['cart']['gift_cert_recip_email']." - ".$basket['conts'][$key]['gcInfo']['recipEmail']."\r\n";
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.options");
			
			$box_content->assign("VAL_OPT_NAME",$lang['cart']['gift_cert_recip_message']);
			
			$gcMessage = $basket['conts'][$key]['gcInfo']['message'];
		
			if (strlen($gcMessage) > 30) $gcMessage = substr($gcMessage, 0, 30).'&hellip;';
			
			$box_content->assign("VAL_OPT_VALUE", $gcMessage);
				
			$plainOpts .= $lang['cart']['gift_cert_recip_message']." - ".$basket['conts'][$key]['gcInfo']['message']."\r\n";
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.options");
			
			$box_content->assign("VAL_OPT_NAME",$lang['cart']['gift_cert_delivery']);
			$box_content->assign("VAL_OPT_VALUE",$lang['cart']['delivery_method_'.$basket['conts'][$key]['gcInfo']['delivery']]);
				
			$plainOpts .= $lang['cart']['gift_cert_delivery']." - ".$lang['cart']['delivery_method_'.$basket['conts'][$key]['gcInfo']['delivery']]."\r\n";
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.options");
			
			// END PSEUDO PRODUCT OPTS
		}  ## Check for custom basket data e.g custom case
		else if ($basket['conts'][$key]['custom'] == 2) {
					
			$gc = fetchDbConfig('gift_certs');
			
			$product[0]['productId']	= 0;
			$product[0]['productCode']	= $basket['conts'][$key]['devicecode'];
			$product[0]['quantity']		= $basket['conts'][$key]['quantity'];
			$product[0]['price']		= $basket['conts'][$key]['caseInfo']['amount'];
			$product[0]['name']			= $basket['conts'][$key]['caseInfo']['name'];
			 $product[0]['image']		= $basket['conts'][$key]['caseInfo']['image'];
			$product[0]['cat_id']		= 0;
			$product[0]['sale_price']	= 0;
			$product[0]['stock_level']	= 0;
			$product[0]['useStockLevel']= 0;
			$product[0]['digital']		= 0;
			$product[0]['prodWeight']	= 0;
			
			$product[0]['case']		= true;
			
			// START PSEUDO PRODUCT OPTS
			$box_content->assign("VAL_OPT_NAME","Design Name");
			$box_content->assign("VAL_OPT_VALUE",$basket['conts'][$key]['caseInfo']['designname']);
				
			$plainOpts .= "Design Name - ".$basket['conts'][$key]['caseInfo']['designname']."\r\n";
			$plainOpts .= "Case ID - ".$basket['conts'][$key]['caseInfo']['caseid']."\r\n";
			$plainOpts .= "Design ID - ".$basket['conts'][$key]['caseInfo']['designid']."\r\n";
			$plainOpts .= "Price - ".$basket['conts'][$key]['caseInfo']['amount']."\r\n";
			$plainOpts .= "Device Name - ".$basket['conts'][$key]['caseInfo']['name']."\r\n";
			$plainOpts .= "Image Path - ".$basket['conts'][$key]['caseInfo']['image']."\r\n";
			$designimg = $basket['conts'][$key]['caseInfo']['designimg'];
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.options");
			
			// END PSEUDO PRODUCT OPTS
		}else {
		
			$productId	= $cart->getProductId($key);
			$imei		= $cart->getIMEI($key);
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
	
		$box_content->assign("TD_CART_CLASS",cellColor($i, 'tdcartEven', 'tdcartOdd'));
		$box_content->assign("VAL_PRODUCT_ID",$productId);
		$box_content->assign("VAL_IMEI",$imei);

		$box_content->assign("VAL_CURRENT_STEP",$current_a);
		$box_content->assign("VAL_PRODUCT_KEY", md5($key));
		
		if ($product[0]['giftCert'] === true) {
			$box_content->assign('VAL_IMG_SRC', imgPath($product[0]['image'], false, ''));
		} elseif ($product[0]['case'] === true) {
			$box_content->assign('VAL_IMG_SRC', imgPath($GLOBALS['rootRel']."uploads/userdata/".$product[0]['image'], false, ''));
		}else {
			if (file_exists(imgPath($product[0]['image'], true, 'root')) && !empty($product[0]['image'])) {
				$box_content->assign('VAL_IMG_SRC', imgPath($product[0]["image"], true, 'rel'));
			} else {
				$box_content->assign('VAL_IMG_SRC', 'skins/'. SKIN_FOLDER . '/styleImages/thumb_nophoto.gif');
			}
		}
		
		## Only calculate shipping IF the product is tangible
		if (!$product[0]['digital']) {
			$orderTangible = true;
		}
		
		$box_content->assign("VAL_PRODUCT_NAME", validHTML($product[0]["name"]));
		$box_content->assign("VAL_PRODUCT_CODE", $product[0]["productCode"]);  
		$box_content->assign("VAL_DELTIME", $product[0]["deltime"]);
		
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
					$box_content->assign('VAL_OPT_NAME', validHTML($option_name));
					$box_content->assign('VAL_OPT_VALUE', htmlentities(strip_tags($option_value), ENT_QUOTES, 'UTF-8'));
					
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
					$box_content->parse('cartpopup.cart_true.repeat_cart_contents.options');
				}
			}
		}
	
		if ($product[0]['useStockLevel'] && $config['stockLevel']){
			$box_content->assign('VAL_INSTOCK', $product[0]['stock_level']);
		} else {
			$box_content->assign('VAL_INSTOCK', '&infin;');
		}
		
		if (($config['outofstockPurchase']) && ($product[0]["stock_level"]<$cart->cartArray['conts'][$key]["quantity"]) && ($product[0]['useStockLevel'])) {
			$box_content->assign("VAL_STOCK_WARN",$lang['cart']['stock_warn']);
			
			$quantity = $cart->cartArray['conts'][$key]["quantity"];
			$box_content->parse("cartpopup.repeat_cart_contents.stock_warn");
		
		} else if ((!$config['outofstockPurchase']) && ($product[0]["stock_level"]<$cart->cartArray['conts'][$key]["quantity"]) && ($product[0]['useStockLevel'])) {

			$box_content->assign("VAL_STOCK_WARN",$lang['cart']['amount_capped']." ".$product[0]["stock_level"].".");
			$quantity = $product[0]["stock_level"];
			$basket = $cart->update($key, $quantity);
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.stock_warn");
		} else {
			$quantity = $cart->cartArray['conts'][$key]["quantity"];
		}
		$box_content->assign("VAL_QUANTITY", $quantity);
		
		if ($basket['conts'][$key]['custom']==1 || !salePrice($product[0]['price'], $product[0]['sale_price'])) {
			$price = $product[0]['price'];
		} else {
			$price = salePrice($product[0]['price'], $product[0]['sale_price']);
		}
		
		$price = ($price+$optionsCost < 0) ? 0 : $price+($optionsCost);
		
		if (!$basket['conts'][$key]['custom']) {
			$altCheckoutInv[$i]['taxType']	= $product[0]['taxType'];
			$altCheckoutInv[$i]['name']		= $product[0]['name'];
			$altCheckoutInv[$i]['options']	= $plainOpts;
			$altCheckoutInv[$i]['quantity'] = $quantity;
			
			if($product[0]['tax_inclusive'] == true) 
			{
				
				$altCheckoutInv[$i]['price'] = sprintf("%.2f",$price / (($product[0]['percent'] / 100) +1));
				$altCheckoutInv[$i]['priceIncTax']	= sprintf("%.2f",$price);
			} else {
				$altCheckoutInv[$i]['price']	= sprintf("%.2f",$price);
			}
			
			## Private data 
			$altCheckoutInv[$i]['private_data']['digital']		= $product[0]['digital'];
			$altCheckoutInv[$i]['private_data']['productcode']	= $product[0]['productCode'];
			$altCheckoutInv[$i]['private_data']['productid']	= $productId;
		} else {
			## Alternative checkout can only be used for tangible goods right now
			$customWarn = true;
		}
		if($cc_session->ccUserData['customer_type'] > 0){
		$wprice = getwprice($cc_session->ccUserData['customer_type'], $product[0]['productId']);
			if($wprice > 0){
				$price = $wprice;
				}
			} 
			if((int)$product[0]['digital'] == 0){
			$box_content->assign("LANG_P_NAME","Product:");
			$box_content->assign("LANG_DEV","<br />Dilivery Time:<br />");
			$box_content->assign("LANG_IMEI","");
			$box_content->assign("VAL_IMEI","");
			$box_content->assign("VAL_UNITPRICE", priceFormat($price, true));
			$box_content->assign("LANG_UNITPRICE","Unit Price");
		}elseif((int)$product[0]['digital'] == 1){
		$box_content->assign("LANG_P_NAME","Network:");
		$box_content->assign("LANG_IMEI","<br />IMEI #:<br />");
		$box_content->assign("LANG_DEV","<br />Dilivery Time:<br />");	
		$box_content->assign("VAL_IMEI",$imei.'<br />');
		$box_content->assign("VAL_UNITPRICE"," ");
			$box_content->assign("LANG_UNITPRICE"," ");
		}
	
		if((int)$product[0]['digital'] == 2){	
			$box_content->assign("LANG_P_NAME","Problem :");
			$box_content->assign("LANG_DEV","");
			$box_content->assign("LANG_IMEI","");	
		}
		if($basket['conts'][$key]['custom']){
			$box_content->assign("LANG_P_NAME","Case Device");
			$box_content->assign("LANG_DEV","");
			$box_content->assign("LANG_IMEI","");	
		}
		$linePrice = $price * $quantity;
		
		// set live vars for order inv and its the last step
		$basket = $cart->setVar($productId,"productId","invArray",$i);
		$basket = $cart->setVar($imei,"imei","invArray",$i);
		$basket = $cart->setVar($product[0]['image'],"image","invArray",$i);
		$basket = $cart->setVar($product[0]['name'],"name","invArray",$i);
		$basket = $cart->setVar($product[0]['productCode'],"productCode","invArray",$i);
		$basket = $cart->setVar($plainOpts, "prodOptions", "invArray", $i);
		$basket = $cart->setVar($designimg, "designimg", "invArray", $i);
		$basket = $cart->setVar(sprintf("%.2f",$linePrice),"price","invArray",$i);
		$basket = $cart->setVar($quantity,"quantity","invArray",$i);
		$basket = $cart->setVar($product[0]['digital'],"digital","invArray",$i);
		
		if ((bool)$basket['conts'][$key]['custom']) {
			if($basket['conts'][$key]['custom'] == 1)
			$basket = $cart->setVar(serialize($basket['conts'][$key]['gcInfo']),"custom","invArray",$i);
			else
			$basket = $cart->setVar(serialize($basket['conts'][$key]['caseInfo']),"custom","invArray",$i);
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.quanDisabled");
		} else {
			$box_content->parse("cartpopup.cart_true.repeat_cart_contents.quanEnabled");
		}
		
		$box_content->assign("VAL_IND_PRICE", priceFormat($price, true));
		$box_content->assign("VAL_LINE_PRICE", priceFormat($linePrice, true));
		
		if ($shipByCat) {
			## Calculate the line category shipping price
			require CC_ROOT_DIR.CC_DS."modules".CC_DS."shipping".CC_DS."Per_Category".CC_DS."line.inc.php";
		}
		
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
		//if ($product[0]['prodWeight']>0 && !$product[0]['digital']) {
		if ($product[0]['prodWeight']>0) {
			$totalWeight = ($product[0]['prodWeight'] * $quantity) + $totalWeight;
		}
		
		## Calculate tax
		if ($taxCustomer) {
			// start: Flexible Taxes, by Estelle Winterflood
			// calculate Tax on Goods
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
		$box_content->parse("cartpopup.cart_true.repeat_cart_contents");
	}
	
	## Deduct the total discount from the subtotal - Fixes bug report #705
	//$subTotal -= $totalDiscount;
			
	## Work out discount on price if any - OLD CODE, left for reference
	/*
	if ($basket['discount_percent']>0) {
		if ($basket['discount_percent'] > 100) $basket['discount_percent'] = 100;
		$totalDiscount	= ($basket['discount_percent']/100) * $subTotal;
		$subTotal		= $subTotal - $totalDiscount;
		
	} else if ($basket['discount_price']>0) {
		if (isset($discountRemainder) && $discountRemainder>0) { 
			$lineDiscount = $discountRemainder; 
		} else if (!isset($discountRemainder)) { 
			$lineDiscount = $basket['discount_price']; 
		}
		if ($lineDiscount<$linePrice) {
			$discount = $lineDiscount;
		} else {
			$discount = $linePrice;
			$discountRemainder = $lineDiscount - $discount;
		}
		
		$totalDiscount	= $discount;
		$subTotal -= $totalDiscount;
		
	} else {
		$discount = 0;
	}
	*/
	
	// start: Flexible Taxes, by Estelle Winterflood
	if ($config_tax_mod['status'] && is_array($taxes_config) && $config_tax_mod['debug']){
			$tax_debug[] =  "<p><strong>Tax applied to goods:</strong><br/>".$debug."</p>";
	}
	// end: Flexible Taxes
	
	// calculate shipping when we have reached step2
	//if ($_GET['_a'] == 'step2' && $orderTangible) {
	if ($orderTangible) {
		$shippingModules = $db->select("SELECT DISTINCT `folder` FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE module='shipping' AND status = 1");
		
		$noItems 	= $cart->noItems();
		$sum		= 0;

		if (is_array($shippingModules) && !empty($shippingModules)) {
			// if selected key has not been set, set it
			if (!isset($basket['shipKey'])) $basket = $cart->setVar(1, 'shipKey');
			
			foreach ($shippingModules as $shippingModule) {
				$shippingCalcPath = 'modules'.CC_DS.'shipping'.CC_DS.$shippingModule['folder'].CC_DS.'calc.php';
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
			
			if (is_array($shippingPrice)) {
				$shippingPrice = '<select class="textbox" style="width: 115px ; height:20px;" id="shipping-select" name="shipKey" onchange="updateshipkey(this.value);">'.implode("\n", $shippingPrice).'</select>';
			}
			
			## if no shipping method is available go to error page
			if (!$shipKey) httpredir('index.php?_g=co&_a=error&code=10001');
			
			## if shipping key is greater than those available set to 1, and redirect
			if ($basket['shipKey']>$shipKey) {
				$cart->setVar(1, 'shipKey');
				$basket = $cart->setVar(0.00, 'shipCost');
				httpredir('index.php?_g=co&_a=step2') or die('help');
			}
				
		} else {
			$shippingPrice .= priceFormat(0, true);
			$basket = $cart->setVar($lang['cart']['free_shipping'], 'shipMethod');
			$basket = $cart->setVar(0.00, 'shipCost');
		}
	} else {
		$shippingPrice = $lang['cart']['na'];
		## set shipping cost to 0.00 just incase
 		$basket = $cart->setVar(0.00, 'shipCost');
	}
	
	
	
	## If the voucher is a gift certificate, we'll let them use it to discount shipping
	
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
	
	// start: Flexible Taxes, by Estelle Winterflood
	// calculate tax on shipping
	if ($taxCustomer && $config_tax_mod['status']) {
		
		if ($config_tax_mod['debug'] && is_array($taxes_config))
			$tax_debug[] =  "<p><strong>Tax applied to shipping &amp; handling:</strong><br/>";

		for ($i=0; is_array($taxes_config) && $i<count($taxes_config); $i++){
			$tax_config = $taxes_config[$i];
			if ($tax_config['type_id'] == $shippingTaxId){
				// tax on shipping
				
				if ($tax_config['shipping']) {
					$lineTax = getTax($basket['shipCost'], true, $tax_config['tax_percent']);
					for ($j=0; $j<count($taxes); $j++){
						if ($taxes[$j]['name'] == $tax_config['name']){
							// "Testing" mode info display
							if ($config_tax_mod['debug']) {
								$tax_debug[] =  "Method [".$folder."] [".$tax_config['type_name']."] [".priceFormat($basket['shipCost'], true)."] --- Tax [".$taxes[$j]['name']."] [".$tax_config['type_name']."] [".number_format($tax_config['tax_percent'],2)." %] --- ".priceFormat($lineTax,TRUE)."<br />";
							}
							$taxes[$j]['amount'] += $lineTax;
						}
					}
				}
			}
		}
		if ($config_tax_mod['debug'] && is_array($taxes_config))
		
		$tax_debug[] =  "</p>";
	} elseif($taxCustomer) { ## claculate basic tax on shipping
		$tax += $shippingTaxAmount;
	}
	if ($config_tax_mod['status'] && $config_tax_mod['debug']){
		## "Testing" mode info display
			$tax_debug[] = "</div>";
	}
	## end: Flexible Taxes
	$box_content->assign("LANG_SHIPPING", $lang['cart']['shipping']);
	
	$box_content->assign("VAL_SHIPPING", $shippingPrice);
	
	// start: Flexible Taxes, by Estelle Winterflood
	// display taxes
	if ($config_tax_mod['status']){
		
		// Unset remnant taxes from prempted values
		$basket = $cart->unsetVar('tax1_disp');
		$basket = $cart->unsetVar('tax2_disp');
		$basket = $cart->unsetVar('tax3_disp');
		$basket = $cart->unsetVar('tax1_amt');
		$basket = $cart->unsetVar('tax2_amt');
		$basket = $cart->unsetVar('tax3_amt');
		
		if (count($taxes)==0){
			$box_content->assign("LANG_TAX",$lang['cart']['tax']);
			$box_content->assign("VAL_TAX",priceFormat(0,TRUE));
		} else {
			// any additional taxes
			for ($i=1; $i<count($taxes); $i++) {
				$box_content->assign("LANG_TAX",$taxes[$i]['display']);
				$box_content->assign("VAL_TAX",priceFormat($taxes[$i]['amount'],TRUE));
				$box_content->parse("cartpopup.cart_true.repeat_more_taxes");
			}

			// first tax
			$box_content->assign("LANG_TAX",$taxes[0]['display']);
			$box_content->assign("VAL_TAX",priceFormat($taxes[0]['amount'],TRUE));
		}

		// tax registration number(s)
		$reg_number = $db->select("SELECT reg_number FROM ".$glob['dbprefix']."ImeiUnlock_tax_details;");
		$reg_string = "";
		for ($i=0; is_array($reg_number) && $i<count($reg_number); $i++) {
			if (strlen($reg_string) && $reg_number[$i]['reg_number']!="") 
			{
				$reg_string .= "<br/>";
			}
			$reg_string .= $reg_number[$i]['reg_number'];
		}
		$box_content->assign("VAL_TAX_REG",$reg_string);
		// end: Flexible Taxes
	} else {
		$box_content->assign("LANG_TAX",$lang['cart']['tax']);
		$box_content->assign("VAL_TAX",priceFormat($tax,TRUE));
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
	$box_content->assign("LANG_DISCOUNT",$lang['cart']['discount']);
	// set discount to return value if null
	if(empty($basket['discount']) || !isset($basket['discount'])) { 
		$basket['discount'] = 0; 
	} 
	$box_content->assign("VAL_DISCOUNT", priceFormat($basket['discount'], true));

	$box_content->assign("LANG_SUBTOTAL", $lang['cart']['subtotal']);
	$box_content->assign("VAL_SUBTOTAL", priceFormat($subTotal, true));
	
	if($totalWeight>0){
		$box_content->assign("LANG_BASKET_WEIGHT",$lang['cart']['basket_weight']);
		$box_content->assign("VAL_BASKET_WEIGHT",$totalWeight.$config['weightUnit']);
	}
	
	// paypal processing fee
	$paypal = $config['paypal'];
	
	if(isset($paypal) && $paypal > 0){
		$paypalfee = $subTotal / 100 * $paypal ;
		$basket = $cart->setVar($paypalfee, "paypalfee");
		$box_content->assign("LANG_PAYPAL", $lang['cart']['paypal']);
		$box_content->assign("VAL_PAYPAL_FEE",priceFormat($paypalfee));
		$box_content->parse("cartpopup.paypalfee");		
		}
		else {
			
			$paypalfee =0;
			}
		// end paypal processing fee
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

	
	$box_content->assign("LANG_CART_TOTAL",$lang['cart']['cart_total']);
	$box_content->assign("VAL_CART_TOTAL",priceFormat($grandTotal, true));
	
	if (isset($basket['codeResult'])) {
		if (!$basket['codeResult']) {
			# add remove link
			$box_content->assign("LANG_CODE_REMOVE",$lang['cart']['coupon_remove']);
			$box_content->assign("VAL_OLD_CODE",base64_encode($basket['code']));
			$box_content->assign("VAL_CURRENT_PAGE",currentPage());
			$box_content->parse("cartpopup.cart_true.coupon_code_result.remove");
		}
		$box_content->assign("LANG_CODE_RESULT",$lang['cart']['coupon_result_'.$basket['codeResult']]);
		$box_content->parse("cartpopup.cart_true.coupon_code_result");
		
	}

	if (!isset($basket['codeResult']) || $basket['codeResult']>0) {
		$box_content->assign("LANG_GOT_CODE", $lang['cart']['got_code']);
		$box_content->assign("LANG_ENTER_CODE", $lang['cart']['enter_code']);
		$box_content->parse("cartpopup.cart_true.enter_coupon_code");
		$basket = $cart->unsetVar("codeResult");
	}
			
	// build array of price vars in session data
	$basket = $cart->setVar(sprintf("%.2f",$subTotal),"subTotal");
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
	// end: Flexible Taxes	
	
	$box_content->assign("LANG_UPDATE_CART_DESC",$lang['cart']['if_changed_quan']);
	
	$box_content->assign("LANG_UPDATE_CART",$lang['cart']['update_cart']);
	
	$box_content->assign("LANG_CHECKOUT",$lang['cart']['checkout']);
	// see if passports are enabled
	$altCheckout = $db->select("SELECT `folder`, `default` FROM `".$glob['dbprefix']."ImeiUnlock_Modules` WHERE `module`='altCheckout' AND `status` = 1");
	
	// stop alt checkout if they have aleady chosen PayPal... mission	
	if($grandTotal>0 && !isset($_SESSION['token']) && !isset($_SESSION['payer_id']) && $altCheckout) {
		
		$noAltCheckouts = count($altCheckout);
		
		if($noAltCheckouts == 1 && $altCheckout[0]['folder']=="PayPal_Pro") { unset($customWarn); }
		
		if ($customWarn) {
			$box_content->assign("LANG_CUSTOM_WARN",$lang['cart']['custom_warn']);
			$box_content->parse("cartpopup.cart_true.alt_checkout.custom_warn");	
		}
		$box_content->assign("LANG_ALTERNATIVE_CHECKOUT",$lang['cart']['alternative']);
		
		## Get alt checkout shipping
		$query = "SELECT `name`, `byprice`, `low`, `high`, `price` FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping a LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_alt_shipping_prices b ON a.id = b.alt_ship_id ORDER BY `order`, `low`, `high` ASC";
		$altShipping = $db->select($query);
		
		if ($altShipping) {
			$n=0;
			for($i=0;$i<count($altShipping);$i++) {
				if($altShipping[$i]['byprice']==1 && $subTotal>=$altShipping[$i]['low'] && $subTotal<=$altShipping[$i]['high']){
					$altShippingPrices[$n]['name'] = $altShipping[$i]['name'];
					$altShippingPrices[$n]['price'] = $altShipping[$i]['price'];
					$n++;
				} elseif($altShipping[$i]['byprice']==0 && $totalWeight>=$altShipping[$i]['low'] && $totalWeight<=$altShipping[$i]['high']) {
					$altShippingPrices[$n]['name'] = $altShipping[$i]['name'];
					$altShippingPrices[$n]['price'] = $altShipping[$i]['price'];
					$n++;
				}
			}
		}
		
		if (!isset($customWarn) || $customWarn != true) {
			## build checkout buttons
			for ($i=0;$i<count($altCheckout);$i++) {
				require("modules".CC_DS."altCheckout".CC_DS.$altCheckout[$i]['folder'].CC_DS."button.php");
				## get module config
				$altCheckoutConf = fetchDbConfig($altCheckout[$i]['folder']);
				if($altCheckoutConf['mode']!=="USDPO") {
					## Run class and functions
					$altCheckoutButton = new $altCheckout[$i]['folder']();
					$buttonCode = $altCheckoutButton->buildIt($altCheckout[$i]['folder']);
					$box_content->assign("IMG_CHECKOUT_ALT",$buttonCode);
					$box_content->parse("cartpopup.cart_true.alt_checkout.loop_button");
					## set include path right again - this fixes things, for some reason
					ini_set('include_path', ini_get('include_path').CC_PS.CC_ROOT_DIR);
				}
			}
		}
		$box_content->parse("cartpopup.cart_true.alt_checkout");	
	}
	$box_content->assign("VAL_FORM_ACTION", currentPage());
	$box_content->parse("cartpopup.cart_true");
} else {
	
	$box_content->assign("CONT_VAL","index.php?_g=co&_a=step1");
	$box_content->assign("LANG_CHECKOUT_BTN",$lang['cart']['checkout_btn']);
	$box_content->assign("LANG_VIEW_CART",$lang['cart']['view_cart']);
	$box_content->assign("CLASS_CART","class='txtcartProgressCurrent'");
	$box_content->assign("CLASS_STEP2","");
	
	$box_content->assign("LANG_CART_EMPTY", $lang['cart']['cart_empty']);
	$box_content->parse("cartpopup.cart_false");

} 

$box_content->parse("cartpopup");
$box_content = $box_content->text("cartpopup");
?>