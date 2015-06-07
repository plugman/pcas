<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

session_start();

$nvpStr	=	"&ACTION[1]=D".
			"&CURRENCY[3]=GBP".
			"&TOKEN[".strlen($_SESSION['token'])."]=".$_SESSION['token'].
			"&PAYERID[".strlen($_SESSION['payer_id'])."]=".$_SESSION['payer_id'].
			"&AMT[".strlen($orderSum['prod_total'])."]=".$orderSum['prod_total'].
			"&INVNUM[".strlen($_GET['cart_order_id'])."]=".$_GET['cart_order_id'];


$BUTTONSOURCE = "ImeiUnlock_Cart_PRO2EC";

$nvpStr.="&BUTTONSOURCE[".strlen($BUTTONSOURCE)."]=".$BUTTONSOURCE;


$TRXTYPE = $module['paymentAction'] == "Sale" ? "S" : "A";

$request_id = md5($_SESSION['payer_id'].$orderSum['prod_total'].$_SESSION['token'].date('YmdGis')."1");

$resArray = hash_call("P",$TRXTYPE,$nvpStr,$request_id);

$ack = $resArray["RESULT"] == 0 ? "SUCCESS" : "FAIL";

$transData['customer_id'] = $orderSum["customer_id"];
$transData['gateway'] = "PayPal Website Payments Pro (".$_SESSION['paymentType'].")";
$transData['extra'] = "P";
$transData['trans_id'] = $resArray['PNREF'];
$transData['order_id'] = $_GET['cart_order_id'];
$transData['amount'] = $orderSum['prod_total'];
$transData['status'] = $ack;

include("responseCodes.php");

if(isset($resArray["AVSADDR"])) {
	$extraNotes .= "Address: ".basicResponse($resArray["AVSADDR"]).", ";
}
if(isset($resArray["AVSZIP"])) {
	$extraNotes .= "Zip/Post Code: ".basicResponse($resArray["AVSZIP"]).", ";
}

$extraNotes = substr($extraNotes0,-2);

if(strlen($extraNotes>5)) {
	$extraNotes = substr($extraNotes0,-2).".";
}

$transData['notes'] = "Successful transaction via PayPal Express Checkout. Payer status is '".strtolower($_SESSION['PAYERSTATUS'])."'. ".$extraNotes;
$order->storeTrans($transData);

if($ack=="SUCCESS"){
	// Sale = Funds taken immediately otherwise they may be taken later
	$orderStatus = ($_SESSION['paymentType'] == "Sale") ? 3 : 2; // 3 is payment complete, 2 is processing
	
	$paymentResult = ($_SESSION['paymentType'] == "Sale") ? 2 : 3; // Just to confuse matter Al you plonker!! 2 = money taken 3 = money not yet taken (Athorized)
	
	$cart_order_id = sanitizeVar($_GET['cart_order_id']);
	$order->orderStatus($orderStatus,$cart_order_id);
} else {
	$paymentResult = 1;
}

session_unset(); 
?>