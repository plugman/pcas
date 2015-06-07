<?php
/*
+--------------------------------------------------------------------------
|	gateway.inc.php
|   ========================================
|	Choose and transfer to gateway
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

if($cc_session->ccUserData['customer_id']<1){
	httpredir("index.php?_g=co&_a=step1");
}
$meta['sefSiteTitle']		= "Please choose your preferred payment method - IMEI Unlock";	
## Session Required for PayPal Express Checkout
session_start();

## this fixes browser back on express checkout from "mark"
if(isset($_SESSION['ec_stage']) && $_SESSION['ec_stage'] == "SetExpressCheckout") {
	session_unset();
	httpredir("index.php?_g=co&_a=co&mode=emptyCart");
}

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."gateway.inc.php");

require_once("classes".CC_DS."cart".CC_DS."shoppingCart.php");
require_once("classes".CC_DS."cart".CC_DS."order.php");


$cart	= new cart();
$order	= new order();

if($_GET['contShop'] == true && isset($_GET['cart_order_id'])) {
	
	$pastBasket = $db->select(sprintf("SELECT `basket` FROM `%sImeiUnlock_order_sum` WHERE `cart_order_id` = %s",$glob['dbprefix'],$db->MySQLSafe($_GET['cart_order_id'])));
	
	if($pastBasket==true) {
		$record['basket'] = "'".$pastBasket[0]['basket']."'";
		$db->update($glob['dbprefix']."ImeiUnlock_sessions", $record, "`sessId`= '".$cc_session->ccUserData['sessId']."'");
	} 
	
	$order->orderStatus(6, $_GET['cart_order_id'], false, true);	
	httpredir('index.php');
}

$basket = $cart->cartContents($cc_session->ccUserData['basket']);
/*echo "<pre>";
print_r($basket);
die();*/
$gateway = new XTemplate ("content" . CC_DS . "gateway.tpl");
$gateway->assign("LANG_CART",$lang['gateway']['cart']);
$gateway->assign("LANG_CHECKOUT",$lang['gateway']['checkout']);
$gateway->assign("LANG_PAYMENT",$lang['gateway']['payment']);
$gateway->assign("LANG_COMPLETE",$lang['gateway']['complete']);

if (isset($_POST['PaRes']) || isset($_GET['wpp'])) $_POST['gateway'] = 'PayPal Website Payments Pro';

if (!$basket && isset($_GET['cart_order_id']) && isset($_POST['gateway']) && !preg_match('#[^0-9a-z\-\_\(\)\s]+#i', $_POST['gateway'])){
	if (isset($_SESSION['cc_back'])) unset($_SESSION['cc_back']);
	// get module config
	$module = fetchDbConfig($_POST['gateway']);
	
	$setgateand['gateway'] = $db->mySQLSafe($_POST['gateway']);
	if(isset($_POST['customer_comments']) && !empty($_POST['customer_comments'])) $setgateand['customer_comments'] = $db->mySQLSafe($_POST['customer_comments']);

	$db->update($glob['dbprefix']."ImeiUnlock_order_sum",$setgateand,"cart_order_id = ".$db->MySQLSafe($_GET["cart_order_id"]));
	
	$order->getOrderInv($_GET["cart_order_id"]);
	$orderSum = $order->getOrderSum($_GET["cart_order_id"]);
	if($_POST['gateway']=="TopUp")
	{
		$ordId = $_GET['cart_order_id'];		
		httpredir("index.php?_g=co&_a=topup&cart_order_id=$ordId");
	exit();	
	}
	if($_REQUEST['gateway'] == "PayPal_Pro") {
	
		$pastBasket = $db->select(sprintf("SELECT `basket` FROM `%sImeiUnlock_order_sum` WHERE `cart_order_id` = %s",$glob['dbprefix'],$db->MySQLSafe($_GET['cart_order_id'])));
		
		$_SESSION['cart_order_id'] = $_GET['cart_order_id'];
	
		if ($pastBasket) {
			$record['basket'] = "'".$pastBasket[0]['basket']."'";
			$db->update($glob['dbprefix']."ImeiUnlock_sessions", $record, "`sessId`= '".$cc_session->ccUserData['sessId']."'");
		} 	
		require("modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."button.php");
			
		// run class & functs
		$altCheckoutButton = new PayPal_Pro();
		$formAction = $altCheckoutButton->buildIt("PayPal_Pro", true);
		$formMethod = "post";
		$formTarget = "_self";
	
	} else {
		
		$transVars = "";
		
		if(strstr($_POST['gateway'], 'PayPal Website Payments Pro')) {
			$moduleType = "altCheckout";
			$moduleName = "PayPal_Pro";
		} else {
			$moduleType = "gateway";
			$moduleName = $_POST['gateway'];
		}
		
		$transferPath = "modules".CC_DS.$moduleType.CC_DS.$moduleName.CC_DS."transfer.inc.php";
		
		if (file_exists($transferPath)) {
			require($transferPath);
		} else {
			die("Required path doesn't exist!");
		}
		
		for($i=0;$i<count($order->orderInv);$i++){
			$orderInv['productId']			= $order->orderInv[$i]['productId'];
			$orderInv['name']				= $order->orderInv[$i]['name']; 			
			$orderInv['price']				= $order->orderInv[$i]['price'];
			$orderInv['quantity']			= $order->orderInv[$i]['quantity'];
			$orderInv['product_options']	= $order->orderInv[$i]['product_options'];
			$orderInv['productCode']		= $order->orderInv[$i]['productCode'];
			$transVars .= repeatVars();
		}
		$transVars .= fixedVars();
	}
	
	if($transfer == "manual") {
		
		$gateway->assign("VAL_FORM_ACTION",$formAction);
		$gateway->assign("VAL_FORM_METHOD",$formMethod);
		$gateway->assign("VAL_TARGET",$formTarget);
		
		$gateway->assign("LANG_FORM_TITLE",$lang['gateway']['fill_out_below']);
		
		require("modules".CC_DS.$moduleType.CC_DS.$moduleName.CC_DS."form.inc.php");
		
		$gateway->assign("FORM_TEMPLATE",$formTemplate);
		
		$gateway->parse("gateway.cart_true.transfer.manual_submit");
		$gateway->assign("LANG_CHECKOUT_BTN",$lang['gateway']['confirm_payment']);
		
	} else {
		
		$gateway->assign("VAL_FORM_ACTION",$formAction);
		$gateway->assign("VAL_FORM_METHOD",$formMethod);
		$gateway->assign("VAL_TARGET",$formTarget);
		
		$gateway->assign("LANG_TRANSFERRING",$lang['gateway']['transferring']);
		$gateway->parse("gateway.cart_true.transfer.manual_submit");
		$gateway->parse("gateway.cart_true.transfer.auto_submit");
		$gateway->assign("LANG_CHECKOUT_BTN",$lang['gateway']['go_now']);
	}
	
	$gateway->assign("FORM_PARAMETERS", $transVars);
	
	$gateway->parse("gateway.cart_true.transfer");
	$gateway->parse("gateway.cart_true");
	
} 
// build order and display payment methods
elseif(($basket==TRUE || isset($_GET['cart_order_id'])) && !isset($_POST['gateway'])) {
	
	// Express Checkout should not be here if back button is used. GetExpressCo hasn't be excuted....
	// ABORT ABORT ABORT
	/*
	if(isset($_SESSION['cart_order_id']) && !isset($_SESSION['payer_id'])) {
		session_unset();
		httpredir("index.php?_g=co&_a=step2&mode=emptyCart");
	}
	*/
	
	// if order id has already been made for PayPal express CO we only need to take payment
	// i.e. Customer Has chosen to pay with paypal after clicking "Place Order"
	if (isset($_SESSION['cart_order_id'])) {
		$cart_order_id = sanitizeVar($_SESSION['cart_order_id']);
		$skipEmail = true;
		$order->orderStatus(6,$_SESSION['cart_order_id'], false, true);
		if (isset($_SESSION['payer_id'])) $order->deleteOrder($_SESSION['cart_order_id']);
	} elseif(isset($_GET['cart_order_id'])){
		$cart_order_id = sanitizeVar($_GET['cart_order_id']);
	} elseif(!isset($basket['cart_order_id']) && empty($basket['cart_order_id'])) {
		$cart_order_id = $order->mkOrderNo();
	} else {
		$order->deleteOrder($basket["cart_order_id"]);
	}
	
	if(!isset($_GET['cart_order_id'])){
		// order inventory
		for($i=1;$i<=count($basket['invArray']);$i++){

			$orderInv[$i]['productId'] 			= $basket['invArray'][$i]["productId"];
			$orderInv[$i]['name'] 				= $basket['invArray'][$i]["name"];
			$orderInv[$i]['price'] 				= $basket['invArray'][$i]["price"];
			$orderInv[$i]['quantity'] 			= $basket['invArray'][$i]["quantity"];
			$orderInv[$i]['product_options'] 	= $basket['invArray'][$i]["prodOptions"];
			$orderInv[$i]['productCode'] 		= $basket['invArray'][$i]["productCode"];
			$orderInv[$i]['digital'] 			= $basket['invArray'][$i]["digital"];
			$orderInv[$i]['custom'] 			= $basket['invArray'][$i]["custom"];
			$orderInv[$i]['imei'] 				= $basket['invArray'][$i]["imei"];
			$orderInv[$i]['image'] 				= $basket['invArray'][$i]["designimg"];
			if(!empty($orderInv[$i]['image'])){
		 	$rootMasterFile = CC_ROOT_DIR.CC_DS.'uploads'.CC_DS.'orderdesigns'.CC_DS.$orderInv[$i]['image'];
			$rootMasterFileorg = CC_ROOT_DIR.CC_DS.'uploads'.CC_DS.'orderdesigns'.CC_DS.'origional'.$orderInv[$i]['image'];
		 	$rootMasterFilesource = CC_ROOT_DIR.CC_DS.'uploads'.CC_DS.'userdesigns'.CC_DS.$orderInv[$i]['image'];
			$rootMasterFileorigsource = CC_ROOT_DIR.CC_DS.'uploads'.CC_DS.'userdesigns'.CC_DS.'origional'.$orderInv[$i]['image'];
			copy($rootMasterFileorigsource,$rootMasterFileorg);
			copy($rootMasterFilesource,$rootMasterFile);
			}
	
		}
		
		// order summary
		
		//////////////////
		// Invoice info
		/////
		
		$orderSum['cart_order_id'] 	= $cart_order_id;
		$orderSum['customer_id'] 	= $cc_session->ccUserData['customer_id'];
		$orderSum['email'] 			= $cc_session->ccUserData['email'];
		$orderSum['name'] 			= $cc_session->ccUserData['title']." ".$cc_session->ccUserData['firstName']." ".$cc_session->ccUserData['lastName'];
		$orderSum['companyName']	= $cc_session->ccUserData['companyName']; 
		$orderSum['add_1'] 			= $cc_session->ccUserData['add_1'];
		$orderSum['add_2'] 			= $cc_session->ccUserData['add_2'];
		$orderSum['town'] 			= $cc_session->ccUserData['town'];
		$orderSum['county'] 		= $cc_session->ccUserData['county'];
		$orderSum['postcode'] 		= $cc_session->ccUserData['postcode'];
		$orderSum['country'] 		= getCountryFormat($cc_session->ccUserData['country'],"id","printable_name");
		$orderSum['phone'] 			= $cc_session->ccUserData['phone'];
		$orderSum['mobile'] 		= $cc_session->ccUserData['mobile'];
		
		$currency = $db->select("SELECT currency FROM ".$glob['dbprefix']."ImeiUnlock_sessions WHERE sessId = ".$db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
		
		if($currency == TRUE){
			$orderSum['currency'] = $currency[0]['currency'];
		} else {
			$orderSum['currency'] = $config['defaultCurrency'];
		}
		//////////////////
		// Delivery info
		/////
		$orderSum['name_d'] 	= $basket['delInf']['title']." ".$basket['delInf']['firstName']." ".$basket['delInf']['lastName']; 
		$orderSum['companyName_d'] = $basket['delInf']['companyName'];
		$orderSum['add_1_d'] 	= $cc_session->ccUserData['dadd_1'];
		$orderSum['add_2_d'] 	= $basket['delInf']['add_2'];
		$orderSum['town_d'] 	= $cc_session->ccUserData['dtown'];
		$orderSum['county_d'] 	= $cc_session->ccUserData['dcounty'];
		$orderSum['postcode_d'] = $cc_session->ccUserData['dpostcode'];
		$orderSum['country_d'] 	= getCountryFormat($basket['delInf']['country'],"id","printable_name");
		
		//////////////////
		// Summary
		/////
		

		$orderSum['subtotal'] 	= $basket['subTotal'];
		$orderSum['discount'] 	= $basket['discount'];
		$orderSum['paypalfee'] 	= $basket['paypalfee'];		
		$orderSum['total_ship'] = $basket['shipCost'];
		$orderSum['total_tax'] 	= $basket['tax'];
		$orderSum['prod_total'] = $basket['grandTotal'];
		$orderSum['shipMethod'] = $basket['shipMethod']; 
		$orderSum['basket']		= $cc_session->ccUserData['basket'];
	
		// start: Flexible Taxes, by Estelle Winterflood
		$orderSum['tax1_disp'] 	= $basket['tax1_disp'];
		$orderSum['tax1_amt'] 	= $basket['tax1_amt'];
		$orderSum['tax2_disp'] 	= $basket['tax2_disp'];
		$orderSum['tax2_amt'] 	= $basket['tax2_amt'];
		$orderSum['tax3_disp'] 	= $basket['tax3_disp'];
		$orderSum['tax3_amt'] 	= $basket['tax3_amt'];
		// end: Flexible Taxes
		
		if(isset($_SESSION['token']) && isset($_SESSION['payer_id'])) {
			$orderSum['gateway'] 	= 'PayPal Website Payments Pro ('.$_SESSION['paymentType'].')'; // this will be updated later
		} else {
			$orderSum['gateway'] 	= 'Undefined'; // this will be updated later
		}
		$order->createOrder($orderInv, $orderSum, $skipEmail, $cc_session->ccUserData['lang'],$basket['code']);
	
	} else {
		$orderSum = $order->getOrderSum($_GET["cart_order_id"]);
	}

	$gateway->assign("VAL_FORM_ACTION","index.php?_g=co&amp;_a=step4&amp;cart_order_id=".$cart_order_id);
	$gateway->assign("VAL_FORM_METHOD","post");
	$gateway->assign("VAL_TARGET","_self");
	$gateway->assign("LANG_CHECKOUT_BTN",$lang['gateway']['continue']);
	$gateway->assign("LANG_PAYMENT_SUMMARY", sprintf($lang['gateway']['payment_summary'], '<span style="color:#fdd831">'. priceFormat($orderSum['prod_total'], true).'</span>', '<span style="color:#fdd831">'.$cart_order_id.'</span>'));
	
	$gateway->assign("LANG_CHOOSE_GATEWAY",$lang['gateway']['choose_method']);
	
	/* BEGIN Browser back button fix - Thanks Convict */
	$_SESSION['cc_back'] = base64_encode($orderSum['cart_order_id']);
	/* END Browser back button fix - Thanks Convict */
	
	$gatewayModules = $db->select("SELECT `folder`, `default`, `module` FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE (`module`='gateway' OR `module`='altCheckout') AND status = 1");
	
	if ($gatewayModules) {
	
		$gateway->assign("LANG_COMMENTS",$lang['gateway']['your_comments']);
	
		for ($i=0; $i<count($gatewayModules); $i++){
			
			if ($gatewayModules[$i]['module'] == 'gateway' && file_exists(CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'gateway'.CC_DS.$gatewayModules[$i]['folder'].CC_DS.'transfer.inc.php')) {
			
				$gateway->assign("TD_CART_CLASS",cellColor($i, $tdEven="tdcartEven", $tdOdd="tdcartOdd"));
	
				$module = fetchDbConfig($gatewayModules[$i]['folder']);
				if($module['image'])
				$gateway->assign("VAL_GATEWAY_DESC",'<img alt="" src="skins/Classic/styleImages/'.$module['image'].'" />');
				else
				$gateway->assign("VAL_GATEWAY_DESC",$module['desc']);
				$gateway->assign("VAL_GATEWAY_FOLDER",$gatewayModules[$i]['folder']);
				
				if($gatewayModules[$i]['default'] == 1){
					$gateway->assign('VAL_CHECKED', 'checked="checked"');
				} else {
					$gateway->assign('VAL_CHECKED', '');
				}
				
				$gateway->parse("gateway.cart_true.choose_gate.gateways_true");
			}
		}
		
		$module_express_co = fetchDbConfig("PayPal_Pro");
		
		if($module_express_co['status']==true){
			$gateway->assign('VAL_CHECKED', '');
			if($module_express_co['mode']!=="USDPO") {
				$gateway->assign("TD_CART_CLASS",cellColor($i++, $tdEven="tdcartEven", $tdOdd="tdcartOdd"));
				$gateway->assign("VAL_GATEWAY_DESC","<img src='https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif' border='0' title='' alt='The safer, easier way to pay.' />");
				$gateway->assign("VAL_GATEWAY_FOLDER","PayPal_Pro");
				$gateway->parse("gateway.cart_true.choose_gate.gateways_true");
			}
			if($module_express_co['mode']!=="USECO") {
				
				switch($config['defaultCurrency']) {
					case "GBP":
						$cardsLocale = "uk";
					break;
					case "USD":
						$cardsLocale = "us";
					break;
					case "CAD":
						$cardsLocale = "ca";
					break;				
				}
			
				$gateway->assign("TD_CART_CLASS",cellColor($i++, $tdEven="tdcartEven", $tdOdd="tdcartOdd"));
				$gateway->assign("VAL_GATEWAY_DESC","<img src='modules/altCheckout/PayPal_Pro/paypal_cards_".$cardsLocale.".gif' border='0' title='' alt='' />");
				$gateway->assign("VAL_GATEWAY_FOLDER","PayPal Website Payments Pro (".$module_express_co['paymentAction'].")");
				$gateway->parse("gateway.cart_true.choose_gate.gateways_true");
			}
		} 
		
		if(isset($_GET['cart_order_id'])){
			// add fixed & repeat vars
			$query = "SELECT customer_comments FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE cart_order_id = ".$db->MySQLSafe($_GET["cart_order_id"]);
			$comments = $db->select($query);
			$gateway->assign("VAL_CUSTOMER_COMMENTS",$comments[0]['customer_comments']);
		
		}
		 
		$gateway->parse("gateway.cart_true.choose_gate");
	
	} else {
		
		$gateway->assign("LANG_GATEWAYS_FALSE",$lang['gateway']['none_configured']);
		$gateway->parse("gateway.cart_true.choose_gate.gateways_false");
		$gateway->parse("gateway.cart_true.choose_gate");
	
	}
	
	// affiliate tracking code
	$query = "SELECT folder, `default` FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE module='affiliate' AND status = 1";
	$affiliateModule = $db->select($query);

	if ($affiliateModule == TRUE){
	
		for($i=0; $i<count($affiliateModule); $i++){
			
			require("modules".CC_DS."affiliate".CC_DS.$affiliateModule[$i]['folder'].CC_DS."tracker.inc.php");

			$gateway->assign("VAL_AFFILIATE_TRACK_HTML",$affCode);
			$gateway->parse("gateway.cart_true.affiliate_code");
		
		}
	
	}
	
	$gateway->parse("gateway.cart_true");
	// empty basket
	$basket = $cart->emptyCart($keepStock = true);
	
	// deal with free orders! Whoopie! All orders for me should be free!
	if(true && $orderSum['prod_total']==0){
		$order->orderStatus(3,$cart_order_id);
		httpredir("index.php?_g=co&_a=confirmed&s=2");
	}
	
	// Make express checkout payment
	if(isset($_SESSION['token']) && isset($_SESSION['payer_id'])) {
		httpredir("index.php?_g=rm&type=altCheckout&cmd=process&module=PayPal_Pro&payment=1&cart_order_id=".$cart_order_id);
	}
	
	// to prevent refresh errors & duplicate orders
	if(!isset($_GET['cart_order_id'])) {
		httpredir("index.php?_g=co&_a=step4&cart_order_id=".$cart_order_id);
	}
	
} else {
	
	$gateway->assign("LANG_CART_EMPTY",$lang['gateway']['cart_empty']);
	$gateway->parse("gateway.cart_false");

} 

$gateway->parse("gateway");
$page_content = $gateway->text("gateway");
?>
