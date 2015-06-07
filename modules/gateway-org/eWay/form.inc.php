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
|   Licence Info: http://www.cubecart.com/site/faq/license.php
+--------------------------------------------------------------------------
|	form.inc.php
|   ========================================
|	eWay Processing
+--------------------------------------------------------------------------
*/
if($_GET['process']==1){


$transData['customer_id'] = $orderSum["customer_id"];
	$transData['order_id'] = $orderSum['cart_order_id'];
	$transData['amount'] = $orderSum['prod_total'];
	$transData['gateway'] = "eWay";
	
	// first check card
	require("classes".CC_DS."validate".CC_DS."validateCard.php");
	$card = new validateCard();
	
	$cardNo			= $_POST["cardNumber"];
	$issueNo		= 0;
	$issueDate		= 0; 
	$issueFormat	= 4; 
	$expireDate		= str_pad(trim($_POST["expirationYear"]), 2, '0', STR_PAD_LEFT).str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT); 
	$expireFormat	= 4; 
	$scReqd			= TRUE; 
	$securityCode	= $_POST["cvc2"];
	
	$card = $card->check($cardNo, 
						$issueNo, 
						$issueDate, 
						$issueFormat, 
						$expireDate, 
						$expireFormat, 
						$scReqd, 
						$securityCode);
	
	if($module['validation']==1 && $card['response']=="FAIL"){
	
		$errorMsg = "";
		
		foreach($card['error'] as $val){
			$errorMsg .= $val."<br />";
		}

	} else {

		require_once('EwayPayment.php');
		
		if ($module['test']==true) {
			$module['acNo'] = '87654321';
			$gatewayURL = 'https://www.eway.com.au/gateway_cvn/xmltest/TestPage.asp';
		} else {
			$gatewayURL = 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp';
		}
		
		$eway = new EwayPayment($module['acNo'], $gatewayURL);
		$eway->setCustomerFirstname($_POST["firstName"]);
		$eway->setCustomerLastname($_POST["lastName"]);
		$eway->setCustomerEmail($_POST["emailAddress"]);
		$eway->setCustomerAddress($_POST["addr1"].' '.$_POST["addr2"].', '.$_POST["city"].', '.$_POST["state"].', '.$_POST["country"]);
		$eway->setCustomerPostcode($_POST["postalCode"]);
		$eway->setCustomerInvoiceDescription('Payment for order# '.$orderSum['cart_order_id']);
		$eway->setCustomerInvoiceRef($orderSum['cart_order_id']);
		$eway->setCardHoldersName($_POST["firstName"]." ".$_POST["lastName"]);
		$ccdelimeters = array(" ","-");
		$eway->setCardNumber(str_replace($ccdelimeters,"",$_POST["cardNumber"]));
		$eway->setCardExpiryMonth($_POST["expirationMonth"]);
		$eway->setCardExpiryYear($_POST["expirationYear"]);
		$eway->setCardCVN($_POST["cvc2"]);
		$eway->setTrxnNumber(str_replace("-","",$orderSum['cart_order_id']));
		
		// Eway takes payments in Cents
		$cents = $orderSum['prod_total'] * 100;
		$eway->setTotalAmount($cents);
		
		$paymentResult = $eway->doPayment();
		
			if($paymentResult == 0) {
				$order->orderStatus(3,$orderSum['cart_order_id']);
				
				$transData['trans_id'] = $eway->myTrxnNumber;
				$transData['status'] = "Success";
				$transData['notes'] = $eway->getErrorMessage();
				$order->storeTrans($transData);
				
				httpredir("index.php?_g=co&_a=confirmed&s=2");
			} else {
				$errorMsg = $eway->getErrorMessage();
			}
	
	}
	
	$transData['trans_id'] = "";
	$transData['status'] = "Fail";
	$transData['notes'] = $errorMsg;
	$order->storeTrans($transData);
	
}


$formTemplate = new XTemplate ("modules/gateway/".$_POST['gateway']."/form.tpl",'',null,'main',true,$skipPath=TRUE);

if(isset($errorMsg)) {
	$formTemplate->assign("LANG_ERROR",$errorMsg);
	$formTemplate->parse("form.error");
}

$billingName = makeName($orderSum['name']);

$formTemplate->assign("VAL_FIRST_NAME",$billingName[2]);
$formTemplate->assign("VAL_LAST_NAME",$billingName[3]);
$formTemplate->assign("VAL_EMAIL_ADDRESS",$orderSum['email']);
$formTemplate->assign("VAL_ADD_1",$orderSum['add_1']);
$formTemplate->assign("VAL_ADD_2",$orderSum['add_2']);
$formTemplate->assign("VAL_CITY",$orderSum['town']);
$formTemplate->assign("VAL_COUNTY",$orderSum['county']);
$formTemplate->assign("VAL_POST_CODE",$orderSum['postcode']);


$countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name"); 
	
	for($i=0; $i<count($countries); $i++){
	
		if($countries[$i]['id'] == $orderSum['country']){
			$formTemplate->assign("COUNTRY_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("COUNTRY_SELECTED","");
		}
	
		$formTemplate->assign("VAL_COUNTRY_ISO",$countries[$i]['iso']);

		$countryName = $countries[$i]['printable_name'];

		if(strlen($countryName)>20){
			$countryName = substr($countryName,0,20)."&hellip;";
		}

		$formTemplate->assign("VAL_COUNTRY_NAME",$countryName);
		$formTemplate->parse("form.repeat_countries");
	}
	
	$formTemplate->assign("LANG_CC_INFO_TITLE",$lang['gateway']['cc_info_title']);
	$formTemplate->assign("LANG_FIRST_NAME",$lang['gateway']['first_name']); 
	$formTemplate->assign("LANG_LAST_NAME",$lang['gateway']['last_name']); 
	//$formTemplate->assign("LANG_CARD_TYPE",$lang['gateway']['card_type']);
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['expires']);
	if(!empty($_POST["expirationMonth"]) && !empty($_POST["expirationYear"])) {
		$formTemplate->assign("VAL_MONTH",str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT));
		$formTemplate->assign("VAL_YEAR",str_pad(trim($_POST["expirationYear"]), 2, '0', STR_PAD_LEFT));
	}
	$formTemplate->assign("LANG_MMYY",$lang['gateway']['mmyy']);
	$formTemplate->assign("LANG_SECURITY_CODE",$lang['gateway']['security_code']);
	$formTemplate->assign("LANG_CUST_INFO_TITLE",$lang['gateway']['customer_info']);
	$formTemplate->assign("LANG_EMAIL",$lang['gateway']['email']);
	$formTemplate->assign("LANG_ADDRESS",$lang['gateway']['address']);
	$formTemplate->assign("LANG_CITY",$lang['gateway']['city']);
	$formTemplate->assign("LANG_STATE",$lang['gateway']['state']);
	$formTemplate->assign("LANG_ZIPCODE",$lang['gateway']['zipcode']);
	$formTemplate->assign("LANG_COUNTRY",$lang['gateway']['country']);
	$formTemplate->assign("LANG_OPTIONAL",$lang['gateway']['optional']);


$formTemplate->parse("form");
$formTemplate = $formTemplate->text("form");
?>