<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

require_once 'constants.php';

session_start();

$TRXTYPE = $module['paymentAction'] == "Sale" ? "S" : "A";

if(isset($_REQUEST['token'])) {

	$token = $_REQUEST['token'];

	$nvpStr = "&ACTION[1]=G&TOKEN[".strlen($token)."]=".$token;
	$request_id = md5($token.date('YmdGis')."1");
	//$resArray=hash_call("GetExpressCheckoutDetails",$nvpStr);
	$resArray = hash_call("P",$TRXTYPE,$nvpStr,$request_id);
	
	$_SESSION['reshash']=$resArray;
	$_SESSION['PAYERSTATUS'] = $resArray['PAYERSTATUS'];
	$_SESSION['ec_stage'] = "GetExpressCheckoutDetails";
	
	if($resArray["RESULT"]==0){			
		require_once "GetExpressCheckoutDetails.php";
		exit;				 
	} else  { 
		include("APIError.php");
		exit;
	}
	   	
} else {

	$parts = explode(",",base64_decode($_GET['ccb']));
	$paymentAmount = $parts[0];
	$currencyCodeType = $parts[1];
	$paymentType = $module['paymentAction'];
	
	$storeURL = (!empty($config['storeURL_SSL']) && $config['ssl']) ? $config['storeURL_SSL'] : $glob['storeURL'];
	
	$returnURL = $storeURL.'/index.php?_g=rm&type=altCheckout&cmd=process&module=PayPal_Pro&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount;
	$cancelURL = $storeURL."/index.php?_g=rm&type=altCheckout&cmd=process&module=PayPal_Pro&paymentType=".$paymentType;
	
	$AMT = sprintf("%.2f",$paymentAmount);
	
	$nvpStr =	"&ACTION[1]=S".
				"&CURRENCY[3]=GBP".
				"&AMT[".strlen($AMT)."]=".$AMT.
				"&RETURNURL[".strlen($returnURL)."]=".$returnURL.
				"&CANCELURL[".strlen($cancelURL)."]=".$cancelURL;
				
	if($module['confAddress']==1) {
		$nvpStr.="&REQCONFIRMSHIPPING[1]=1";
	} else {
		$nvpStr.="&REQCONFIRMSHIPPING[1]=0";
	}
	
	
	$BUTTONSOURCE = "ImeiUnlock_Cart_PRO2EC";
	
	$nvpStr.="&BUTTONSOURCE[".strlen($BUTTONSOURCE)."]=".$BUTTONSOURCE;
	
	$request_id = md5($_POST['ACCT'].$paymentAmount.date('YmdGis')."1");
	//$resArray = hash_call("SetExpressCheckout",$nvpStr);
	$resArray = hash_call("P",$TRXTYPE,$nvpStr,$request_id);
	
	$_SESSION['reshash'] = $resArray;
	$_SESSION['ec_stage'] = "SetExpressCheckout";
	
	if($resArray["RESULT"]==0){
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

