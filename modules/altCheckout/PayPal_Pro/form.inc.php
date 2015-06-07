<?php
/*
+--------------------------------------------------------------------------
|	form.inc.php
|   ========================================
|	PayPal Direct Payment Gateway
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
if(detectSSL() == FALSE) die ("<strong>Critical Error:</strong> This page can ONLY be viewed under SSL!");

// Get Direct Payment module vars
$module	= fetchDbConfig('PayPal_Pro');

$process_payment	= ((bool)$module['3ds_status']) ? false : true;
$display_3ds		= false;
$iframe_redirect	= false;

if ((bool)$_GET['process']) {
	
	include 'centinel'.CC_DS.'CentinelClient.php';
	require 'classes'.CC_DS.'validate'.CC_DS.'validateCard.php';
	
	$gatewayName	= sprintf("PayPal Website Payments Pro (%s)", $module['paymentAction']);
	$centinel		= new CentinelClient;
	
	if ($_POST['which_field']=="T") {
		$_POST['state'] = $_POST['county'];
	} else if ($_POST['which_field']=="S") {
		$_POST['state'] = $_POST['county_sel'];
	}
	
	$countryIso = getCountryFormat($_POST['country'],"id","iso");
	if ($countryIso == "US" || $countryIso == "CA") {
		$ISOstate = $db->select("SELECT `abbrev` FROM  `".$glob['dbprefix']."ImeiUnlock_iso_counties` WHERE `name` = ".$db->mySQLSafe($_POST['state'])." AND `countryId` = ".$db->mySQLSafe($_POST['country']).";");	
	}
	$processState = ($ISOstate==true) ? $ISOstate[0]['abbrev'] : trim($_POST['state']);
	
	$transData['customer_id']	= $orderSum['customer_id'];
	$transData['order_id']		= $orderSum['cart_order_id'];
	$transData['amount']		= $orderSum['prod_total'];
	$transData['gateway']		= $gatewayName;
	$transData['extra']			= 'C';	
	
	$cardNo			= trim($_POST['cardNumber']);
	$issueNo		= trim($_POST['issueNo']);
	$expireDate		= trim($_POST['expirationYear']).str_pad(trim($_POST['expirationMonth']), 2, '0', STR_PAD_LEFT);
	$expireFormat	= 6;
	$scReqd			= true;
	$securityCode	= trim($_POST['cvc2']);
	
	if ($config['defaultCurrency']== 'GBP') {
		$issueDate		= trim($_POST['issueYear']).str_pad(trim($_POST['issueMonth']), 2, '0', STR_PAD_LEFT);
		$issueFormat	= 6;
	} else {
		$issueDate		= false;
		$issueFormat	= false;
	}
	$card = new validateCard();
	$card = $card->check($cardNo, $issueNo, $issueDate, $issueFormat, $expireDate, $expireFormat, $scReqd, $securityCode);
	
	if ((bool)$module['validation'] && $card['response'] == 'FAIL') {
		$errorMsg['customer']	= '';
		
		foreach ($card['error'] as $val) {
			$errorMsg['customer'] .= $val.'<br />';
		}
		$transData['trans_id']	= '';
		$transData['status']	= 'FAIL';
		$transData['notes']		= $errorMsg['customer'];
		
		$order->storeTrans($transData);
		
		$display_3ds	= false;
	} else {
		if (isset($_POST['cardNumber'])) {
			$_SESSION['cardinfo']	= $_POST;
			
			if ((bool)$module['3ds_status']) {
				$centinel->add('MsgType', 'cmpi_lookup');
				$centinel->add('Version', '1.7');
				$centinel->add('ProcessorId', '134-01');
				$centinel->add('MerchantId', $module['3ds_merchant']);
				$centinel->add('TransactionPwd', $module['3ds_password']);
				$centinel->add('TransactionType', 'C');
				
				switch (strtoupper($config['defaultCurrency'])) {
					case 'CAD':
						$currency_code	= '124';
						break;
					case 'EUR':
						$currency_code	= '978';
						break;
					case 'GBP':
						$currency_code	= '826';
						break;
					case 'USD':
						$currency_code	= '840';
						break;
				}
				
				$centinel->add('Amount', (int)$orderSum['prod_total']*100);
				$centinel->add('CurrencyCode', $currency_code);
				$centinel->add('CardNumber', trim($_POST['cardNumber']));
				$centinel->add('CardExpMonth', trim($_POST['expirationMonth']));
				$centinel->add('CardExpYear', trim($_POST['expirationYear']));
				$centinel->add('OrderNumber', $orderSum['cart_order_id']);
				
				# https://paypal.cardinalcommerce.com/maps/txns.asp
				$centinel->sendHttp('https://centineltest.cardinalcommerce.com/maps/txns.asp', 5, 10);
			
				if ($centinel->getValue('Enrolled') == 'Y' && $centinel->getValue('ErrorDesc') == 0) {
					## Enrolled, get data, and display iframe content
					$_SESSION['centinel']	= array(
						'Enrolled'	=> $centinel->getValue('Enrolled'),
						'ErrorNo'	=> $centinel->getValue('ErrorNo'),
						'ErrorDesc'	=> $centinel->getValue('ErrorDesc'),
						'ECI'		=> $centinel->getValue('EciFlag'),
						'ACSUrl'	=> $centinel->getValue('ACSUrl'),
						'Payload'	=> $centinel->getValue('Payload'),
						'OrderId'	=> $centinel->getValue('OrderId'),
						'TransactionId'		=> $centinel->getValue('TransactionId'),
						'AuthenticationPath'=> $centinel->getValue('AuthenticationPath'),
						'TermUrl'			=> $GLOBALS['storeURL'].'/'.str_replace($GLOBALS['rootRel'], '', currentPage()),
					);
					$display_3ds	= true;
				} else {
					## Just DoDirectPayment, they're not enrolled for 3DS
					$_SESSION['centinel']	= array(
						'AUTHSTATUS3D'	=> '',
						'MPIVENDOR3DS'	=> $centinel->getValue('Enrolled'),
						'CAVV'			=> '',
						'ECI'			=> $centinel->getValue('EciFlag'),
						'XID'			=> '',
					);
					$process_payment	= true;
				}
			}
		}
		
		if (!empty($_SESSION['cardinfo'])) $_POST = array_merge($_POST, $_SESSION['cardinfo']);
		if ((bool)$module['3ds_status'] && isset($_POST['PaRes'])) {
			$centinel->add('MsgType', 'cmpi_authenticate');
			$centinel->add('Version', '1.7');
			$centinel->add('ProcessorId', '134-01');
			$centinel->add('MerchantId', $module['3ds_merchant']);
			$centinel->add('TransactionPwd', $module['3ds_password']);
			$centinel->add('TransactionType', 'C');
			$centinel->add('TransactionId', $_SESSION['centinel']['TransactionId']);
			$centinel->add('PAResPayload', $_POST['PaRes']);
			# https://paypal.cardinalcommerce.com/maps/txns.asp
			$centinel->sendHttp('https://centineltest.cardinalcommerce.com/maps/txns.asp', 5, 10);
			
			if ($centinel->getValue('ErrorNo') == 0 && in_array($centinel->getValue('PAResStatus'), array('Y', 'A')) && $centinel->getValue('SignatureVerification') == 'Y') {
				$response	= array(
					'AUTHSTATUS3D'		=> $centinel->getValue('PAResStatus'),
					'MPIVENDOR3DS'		=> $_SESSION['centinel']['Enrolled'],
					'CAVV'				=> $centinel->getValue('Cavv'),
					'ECI'				=> $centinel->getValue('EciFlag'),
					'XID'				=> $centinel->getValue('Xid'),
				);
				$_SESSION['centinel']	= $response;
				$process_payment	= true;
				$iframe_redirect	= true;
			} else {
				$process_payment	= false;
				$iframe_redirect	= true;
				$jumpTo				= 'index.php?_g=co&_a=step3&wpp=true&cart_order_id='.$orderSum['cart_order_id'];
				## Need to display an error message too...
				$_SESSION['centinel']['error']	= 'Payment could not be processed with that card. Please try again, or select an alternative payment method.';
			}
		}
			
		##
		if (!$display_3ds && $process_payment) {
		
			$extraNotes = '';
			$ppGate = substr($module['mode'],0,2);
			
			include "modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."wpp-".$ppGate.CC_DS."DoDirectPaymentReceipt.php";
			
			switch (strtoupper($ack)) {
				case 'SUCCESS':
					# Sale = Funds taken immediately otherwise they may be taken later
					$paymentResult		= ($module['paymentAction'] == "Sale") ? 3 : 2; // 3 is payment complete, 2 is processing
					$confirmationScreen	= ($module['paymentAction'] == "Sale") ? 2 : 3; // Just to confuse matter Al you plonker!! 2 = money taken 3 = money not yet taken (Authorized)
					$order->orderStatus($paymentResult, $orderSum['cart_order_id']);
					
					## Transaction Data
					$transData['trans_id']	= $resArray['TRANSACTIONID'];
					$transData['status']	= 'SUCCESS';
					$transData['notes']		= "Successful transaction via Credit Card. ".$extraNotes;
					## Redirect
					$jumpTo = 'index.php?_g=co&_a=confirmed&s='.$confirmationScreen;
					break;
				case 'SUCCESSWITHWARNING':
					## Make it Processing
					$order->orderStatus(2, $orderSum['cart_order_id']);
					
					## Transaction Data
					$transData['gateway'] = "PayPal Website Payments Pro (FMF Review)";
					$transData['status']	= 'PENDING';
					$transData['notes']		= "Error code: ".$resArray['L_ERRORCODE0'].". ".urldecode($resArray['L_SHORTMESSAGE0']);
					## Redirect
					$jumpTo = 'index.php?_g=co&_a=confirmed&s=3';
					break;
					
				default:
					$transData['trans_id']	= '';
					$transData['status']	= 'FAIL';
					$transData['notes']		= "<strong>Error displayed to customer:</strong> ".$errorMsg['customer']."<br /><strong>Error:</strong> ".$errorMsg['admin']."<br /><strong>PayPal Response:</strong> ".$errorMsg['paypal'];
			}
			$order->storeTrans($transData);
			unset($_SESSION['centinel'], $_SESSION['cardinfo']);
		}
		if (isset($jumpTo) && !empty($jumpTo)) {
			if ((bool)$iframe_redirect) {
				echo sprintf('<noscript><meta http-equiv="refresh" content="0;URL=%s"></noscript>', $jumpTo);
				echo sprintf('<script type="text/javascript">self.parent.location=\'%s\';</script>', $jumpTo);
			} else {
				httpredir($jumpTo);
			}
			exit;
		}
	}
}

if ((bool)$display_3ds) {
	## Display the 3D-Secure iframed content
	$xtpl = new XTemplate('modules'.CC_DS.'altCheckout'.CC_DS.'PayPal_Pro'.CC_DS.'form.tpl', '', null, '3ds', true, true);
	$xtpl->assign('STORE_URL', $GLOBALS['storeURL']);
	$xtpl->parse('3ds');
	$formTemplate = $xtpl->text('3ds');
} else {
	$formTemplate = new XTemplate ("modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."form.tpl", '', null, 'main', true, true);
	if (isset($_SESSION['centinel']['error'])) {
		$errorMsg['customer']	= $_SESSION['centinel']['error'];
		unset($_SESSION['centinel']);
	}
	
	if (isset($errorMsg)) {
		$formTemplate->assign("LANG_ERROR", $errorMsg['customer']);
		$formTemplate->parse("form.error");
	}
	
	$formTemplate->assign("VAL_AMOUNT_DUE",sprintf($lang['gateway']['amount_due'],priceformat($orderSum['prod_total'],true)));
	
	$formTemplate->assign("VAL_FIRST_NAME",$cc_session->ccUserData['firstName']);
	$formTemplate->assign("VAL_LAST_NAME",$cc_session->ccUserData['lastName']);
	$formTemplate->assign("VAL_EMAIL_ADDRESS",$orderSum['email']);
	$formTemplate->assign("VAL_ADD_1",$orderSum['add_1']);
	$formTemplate->assign("VAL_ADD_2",$orderSum['add_2']);
	$formTemplate->assign("VAL_CITY",$orderSum['town']);
	
	$formTemplate->assign("LANG_STATE",$lang['gateway']['state']);
	
	$jsScript = jsGeoLocationExtended("country", "county_sel", $lang['cart']['na'],"divCountySelect","divCountyText","county","which_field");
		
		
		if(isset($_POST['country'])) {
			$countryId = $_POST['country'];
		} else {
			$countryId = $cc_session->ccUserData['country'];
		}
		if(!isset($_POST['state'])) {
			$_POST['state'] = $cc_session->ccUserData['county'];
		}
	 
		$counties = $db->select("SELECT name FROM  ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = ".$db->mySQLSafe($countryId));
		
		$formTemplate->assign("VAL_DEL_COUNTY",sanitizeVar($_POST['state']));
		
		if (is_array($counties)) {
			$formTemplate->assign("VAL_COUNTY_SEL_STYLE", "style='display:block;'");
			$formTemplate->assign("VAL_COUNTY_TXT_STYLE", "style='display:none;'");
			$formTemplate->assign("VAL_COUNTY_WHICH_FIELD", "S");
		} else {
			$formTemplate->assign("VAL_COUNTY_SEL_STYLE", "style='display:none;'");
			$formTemplate->assign("VAL_COUNTY_TXT_STYLE", "style='display:block;'");
			$formTemplate->assign("VAL_COUNTY_WHICH_FIELD", "T");
		}
		$formTemplate->assign("JS_COUNTY_OPTIONS", '<script type="text/javascript">'.$jsScript."</script>");
	
		for($i=0; $i<count($counties); $i++) {
	
			if (strtolower($counties[$i]['name']) == strtolower($_POST['state'])) {
				$formTemplate->assign("COUNTY_SELECTED","selected='selected'");
			} else {
				$formTemplate->assign("COUNTY_SELECTED","");
			}
	
			$formTemplate->assign("VAL_DEL_COUNTY_ID",$counties[$i]['name']);
	
			$countyName = $counties[$i]['name'];
			
			if (strlen($countyName)>20) {
				$countyName = substr($countyName,0,20)."&hellip;";
			}
	
			$formTemplate->assign("VAL_DEL_COUNTY_NAME",$countyName);
			$formTemplate->parse("form.county_opts");
		}
		
		$countries = $db->select("SELECT id, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
		
		for ($i=0; $i<count($countries); $i++) {
			
			if($countryId==$countries[$i]['id']) {
				$formTemplate->assign("COUNTRY_SELECTED","selected='selected'");
			} else {
				$formTemplate->assign("COUNTRY_SELECTED","");
			}
			
			$formTemplate->assign("VAL_COUNTRY_ID",$countries[$i]['id']);
	
			$countryName = "";
			$countryName = $countries[$i]['printable_name'];
	
			if (strlen($countryName)>20) {
				$countryName = substr($countryName,0,20)."&hellip;";
			}
	
			$formTemplate->assign("VAL_COUNTRY_NAME",$countryName);
			$formTemplate->parse("form.country_opts");
		
		} 
		
		//$formTemplate->assign("VAL_COUNTRY",$cc_session->ccUserData['country']);
	
	$formTemplate->assign("VAL_POST_CODE",$orderSum['postcode']);
	$formTemplate->assign("VAL_CART_ORDER_ID",$basket['cart_order_id']);
	$formTemplate->assign("VAL_ORDER_TOTAL",$basket['grandTotal']);
	$formTemplate->assign("VAL_ITEM_TOTAL",$basket['subTotal']);
	$formTemplate->assign("VAL_TAX_TOTAL",$basket['tax']);
	$formTemplate->assign("VAL_SHIPPING_TOTAL",$basket['shipCost']);
	
	
	$currency = $db->select("SELECT currency FROM ".$glob['dbprefix']."ImeiUnlock_sessions WHERE sessId = ".$db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
	
	if($currency == TRUE && $currency[0]['currency'] != ''){
		$formTemplate->assign("VAL_CURRENCY_ID", $currency[0]['currency']);
	} else {
		$formTemplate->assign("VAL_CURRENCY_ID", $config['defaultCurrency']);
	}
	
	if ($config['defaultCurrency'] == 'USD' && $module['mode']=='US' || $module['mode']=='USDPO') {
		
		$cards = array(
			"Visa" => "Visa", 
			"MasterCard" => "MasterCard",
			"Discover" => "Discover", 
			"Amex" => "American Express"
		);
	
	} elseif($config['defaultCurrency'] == 'CAD' && $module['mode']=='US') {
	
		$cards = array(
			"Visa" => "Visa", 
			"MasterCard" => "MasterCard",
		);
	
	} elseif($config['defaultCurrency'] == 'GBP' && $module['mode']=='US') {
	
		$cards = array(
			"Visa" => "Visa", 
			"MasterCard" => "MasterCard", 
			"Solo" => "Solo",
		);
		if ((bool)$module['3ds_status'] || date('Ymd') <= '20091231') {
			$cards['Switch'] = "Switch / Maestro";
		}
	
	## Legacy PayFlow Code 
	} elseif($module['mode']=="UK") {
		
		$cards = array(
			"0" => "Visa",
			"8" => "Visa Electron",
			"8" => "Delta", 
			"1" => "MasterCard",
			"S" => "Solo"
		);
		if ((bool)$module['3ds_status'] || date('Ymd') <= '20091231') {
			$cards[9]	= "Maestro / Switch";
		}

	}
	
	foreach($cards as $key => $value) {
	
		$formTemplate->assign("VAL_CARD_TYPE",$key);
		
		if($key == $_POST['cardType']){
			$formTemplate->assign("CARD_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("CARD_SELECTED","");
		}
		$formTemplate->assign("VAL_CARD_NAME",$value);
		
		$formTemplate->parse("form.repeat_cards");
	
	}
	
	for($i=1;$i<=12;$i++) {
		
		$val = sprintf('%02d',$i);
		
		if($val == $_POST['expirationMonth']){
			$formTemplate->assign("EXPIRE_MONTHS_SELECTED","selected='selected'");
		} elseif($val == date("j")) {
			$formTemplate->assign("EXPIRE_MONTHS_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("EXPIRE_MONTHS_SELECTED","");
		}
		$formTemplate->assign("VAL_EXPIRE_MONTH",$val);
		
		$formTemplate->parse("form.expiration_months");
	
	}
	
	$thisYear = date("Y");
	$maxYear = $thisYear + 10;
	$selectedYear = isset($_POST['expirationYear']) ? $_POST['expirationYear'] : ($thisYear+2);
	
	for($i=$thisYear;$i<=$maxYear;$i++) {
		
		if($i == $selectedYear){
			$formTemplate->assign("EXPIRE_YEARS_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("EXPIRE_YEARS_SELECTED","");
		}
		$formTemplate->assign("VAL_EXPIRE_YEAR",$i);
		
		$formTemplate->parse("form.expiration_years");
	
	}
	
	$formTemplate->assign("LANG_CC_INFO_TITLE",$lang['gateway']['cc_info_title']);
	$formTemplate->assign("LANG_FIRST_NAME",$lang['gateway']['first_name']); 
	$formTemplate->assign("LANG_LAST_NAME",$lang['gateway']['last_name']); 
	$formTemplate->assign("LANG_CARD_TYPE",$lang['gateway']['card_type']);
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['lang_expires']);
	$formTemplate->assign("LANG_MMYYYY",$lang['gateway']['mmyyyy']);
	$formTemplate->assign("LANG_SECURITY_CODE",$lang['gateway']['security_code']);
	$formTemplate->assign("LANG_CUST_INFO_TITLE",$lang['gateway']['customer_info']);
	$formTemplate->assign("LANG_EMAIL",$lang['gateway']['email']);
	$formTemplate->assign("LANG_ADDRESS",$lang['gateway']['address']);
	$formTemplate->assign("LANG_CITY",$lang['gateway']['city']);
	$formTemplate->assign("LANG_ZIPCODE",$lang['gateway']['zipcode']);
	$formTemplate->assign("LANG_COUNTRY",$lang['gateway']['country']);
	$formTemplate->assign("LANG_OPTIONAL",$lang['gateway']['optional']);
	
	if($config['defaultCurrency'] == 'GBP'){
	
		for($i=1;$i<=12;$i++) {
		
			$val = sprintf('%02d',$i);
			
			if($val == $_POST['issueMonth']){
				$formTemplate->assign("ISSUE_MONTHS_SELECTED","selected='selected'");
			} else {
				$formTemplate->assign("ISSUE_MONTHS_SELECTED","");
			}
			$formTemplate->assign("VAL_ISSUE_MONTH",$val);
			
			$formTemplate->parse("form.issue_info.issue_months");
	
		}
	
		$minYear = $thisYear - 10;
		
		for($i = $thisYear; $i>=$minYear; $i--) {
			
			if($i == $_POST['issueYear']){
				$formTemplate->assign("ISSUE_YEARS_SELECTED","selected='selected'");
			} else {
				$formTemplate->assign("ISSUE_YEARS_SELECTED","");
			}
			$formTemplate->assign("VAL_ISSUE_YEAR",$i);
			
			$formTemplate->parse("form.issue_info.issue_years");
	
		}
	
		
		$formTemplate->assign("LANG_ISSUE_DATE", $lang['gateway']['issue_date']);
		$formTemplate->assign("LANG_ISSUE_NO", $lang['gateway']['issue_number']);
		$formTemplate->parse("form.issue_info");
	}
	
	$formTemplate->assign("VAL_GATEWAY","PayPal Website Payments Pro (".$module['paymentAction'].")");
	
	$formTemplate->parse("form");
	$formTemplate = $formTemplate->text("form");
}
?>
