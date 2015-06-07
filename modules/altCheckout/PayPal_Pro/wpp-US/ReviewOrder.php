<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

require_once 'constants.php';

session_start();

if(isset($_REQUEST['token'])) {
	
	$token = urlencode( $_REQUEST['token']);
	
	$nvpStr="&TOKEN=".$token;
	
	$resArray=hash_call("GetExpressCheckoutDetails",$nvpStr);
	
	$_SESSION['reshash']=$resArray;
	$_SESSION['PAYERSTATUS'] = $resArray['PAYERSTATUS'];
	$_SESSION['ADDRESSSTATUS'] = $resArray["ADDRESSSTATUS"];
	$_SESSION['ec_stage'] = "GetExpressCheckoutDetails";
	
	$ack = strtoupper($resArray["ACK"]);
	
	if($module['confAddress']==1 && strtoupper($resArray["ADDRESSSTATUS"])!=="CONFIRMED") {
		httpredir("index.php?_g=co&_a=error&code=10003");
	} 
	
	if($ack=="SUCCESS"){			
		require_once "GetExpressCheckoutDetails.php";
		exit;				 
	} else  { 
		include("APIError.php");
		exit;
	}
		
} else {

	$inventory = unserialize(base64_decode($_GET['items']));
	
	if(is_array($inventory)) {
		$i = 0;
		$basketItems = "";
		foreach($inventory as $key => $value) {
			$basketItems .="&L_NAME".$i."=".urlencode($inventory[$key]['name']);
			$basketItems .="&L_NUMBER".$i."=".urlencode($inventory[$key]['private_data']['productcode']);
			$basketItems .="&L_DESC".$i."=".urlencode($inventory[$key]['options']);
			$price = isset($inventory[$key]['priceIncTax']) ? $inventory[$key]['priceIncTax'] : $inventory[$key]['price'];
			$basketItems .="&L_AMT".$i."=".urlencode($price);
			$basketItems .="&L_QTY".$i."=".urlencode($inventory[$key]['quantity']);
			$i++;
		}
	}

	$parts = explode(",",base64_decode($_GET['ccb']));
	$paymentAmount = $parts[0];
	$currencyCodeType = $parts[1];
	$paymentType = $module['paymentAction'];
	
	$storeURL = (!empty($config['storeURL_SSL']) && $config['ssl']) ? $config['storeURL_SSL'] : $glob['storeURL'];
	
	$returnURL =urlencode($storeURL.'/index.php?_g=rm&type=altCheckout&cmd=process&module=PayPal_Pro&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount);
	$cancelURL =urlencode($storeURL."/index.php?_g=rm&type=altCheckout&cmd=process&module=PayPal_Pro&paymentType=".$paymentType);
	
	$nvpStr	=	"&Amt=".sprintf("%.2f",$paymentAmount).
				"&PAYMENTACTION=".$paymentType.
				"&ReturnUrl=".$returnURL.
				"&CANCELURL=".$cancelURL .
				"&CURRENCYCODE=".$currencyCodeType;
				
	$nvpStr .=	$basketItems; 
		
	$resArray = hash_call("SetExpressCheckout",$nvpStr);
	
	$_SESSION['reshash']	= $resArray;
	$_SESSION['ec_stage']	= "SetExpressCheckout";
	
	$ack = strtoupper($resArray["ACK"]);
	
	if($ack=="SUCCESS"){
			// Redirect to paypal.com here
			$token = urldecode($resArray["TOKEN"]);
			$payPalURL = PAYPAL_URL.$token;
			header("Location: ".$payPalURL);
			exit;
	} else  {
			 include("APIError.php");
			exit;
	}
	
}
?>