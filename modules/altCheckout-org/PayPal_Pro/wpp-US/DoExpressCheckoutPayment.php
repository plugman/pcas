<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

session_start();

$token =urlencode($_SESSION['token']);

$paymentAmount = urlencode($orderSum['prod_total']);
$paymentType = urlencode($_SESSION['paymentType']);
$currCodeType = urlencode($_SESSION['currCodeType']);
$payerID = urlencode($_SESSION['payer_id']);
$serverName = urlencode($_SERVER['SERVER_NAME']);

$nvpStr	=	'&TOKEN='.$token.
			'&PAYERID='.$payerID.
			'&PAYMENTACTION='.$paymentType.
			'&AMT='.$paymentAmount.
			'&CURRENCYCODE='.$currCodeType.
			'&IPADDRESS='.$serverName.
			'&INVNUM='.$_GET['cart_order_id'];

switch($currCodeType) {
	case "USD":
	case "GBP":
		$nvpStr.="&BUTTONSOURCE=ImeiUnlock_Cart_EC";
	break;
	case "CAD":
		$nvpStr.="&BUTTONSOURCE=ImeiUnlock_Cart_EC_CA";
	break;
}


$resArray=hash_call("DoExpressCheckoutPayment",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

$transData['customer_id'] = $orderSum["customer_id"];
$transData['gateway'] = "PayPal Website Payments Pro (".$paymentType.")";
$transData['extra'] = "P";
$transData['trans_id'] = $resArray['TRANSACTIONID'];
$transData['order_id'] = $_GET['cart_order_id'];
$transData['amount'] = $orderSum['prod_total'];
$transData['status'] = $ack;
$transData['notes'] = "Successful transaction via PayPal Express Checkout. Buyers address '".strtolower($_SESSION['ADDRESSSTATUS'])."' and payer status is '".strtolower($_SESSION['PAYERSTATUS'])."'.";
$order->storeTrans($transData);

if($ack=="SUCCESS"){
	// Sale = Funds taken immediately otherwise they may be taken later
	$orderStatus = ($paymentType == "Sale") ? 3 : 2; // 3 is payment complete, 2 is processing
	
	$paymentResult = ($paymentType == "Sale") ? 2 : 3; // Just to confuse matter Al you plonker!! 2 = money taken 3 = money not yet taken (Athorized)
	$cart_order_id = sanitizeVar($_GET['cart_order_id']);
	$order->orderStatus($orderStatus,$cart_order_id);
	
} elseif ($ack == 'SuccessWithWarning' || $resArray['PAYMENTSTATUS'] == 'Pending' && $resArray['L_ERRORCODE0'] == 11610) {
	
	$order->orderStatus(2,$cart_order_id);
	$transData['gateway'] = "PayPal Website Payments Pro (FMF Review)";
	$transData['status']	= 'PENDING';
	$transData['notes']		= "Error code: ".$resArray['L_ERRORCODE0'].". ".urldecode($resArray['L_SHORTMESSAGE0']);
	$jumpTo = 'index.php?_g=co&_a=confirmed&s=3';
	
} else {
	$paymentResult = 1;
}

session_unset(); 
?>