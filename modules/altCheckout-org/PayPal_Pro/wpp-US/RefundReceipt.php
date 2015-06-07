<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$transaction_id 	= urlencode(trim($_POST['transactionId']));
$amount 			= sprintf("%.2f",trim($_POST['amount']));
$refundType 		= $amount == $orderSum[0]['prod_total'] ? "FULL" : "PARTIAL";
$refundType 		= urlencode($refundType);
$amount 			= urlencode($amount);
$currency 			= urlencode($config['defaultCurrency']);
$memo 				= urlencode(trim($_POST['note']));

$nvpStr				= 	"&TRANSACTIONID=".$transaction_id.
						"&REFUNDTYPE=".$refundType.
						"&CURRENCYCODE=".$currency.
						"&NOTE=".$memo;

if($refundType=="PARTIAL") {
	$nvpStr .=	"&AMT=".$amount;
}

$resArray = hash_call("RefundTransaction",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

if($ack!=="SUCCESS"){
	$transNotes = $resArray['L_LONGMESSAGE0'];
	$ppMsg = "<p class='warnText' id='ppresult'>".$resArray['L_LONGMESSAGE0']."</p>";		
} else {
	$transNotes = ucfirst(strtolower($refundType))." Refund of ".priceFormat($resArray['GROSSREFUNDAMT'],true)." successful.";
	
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
	$db->misc("UPDATE ".$glob['dbprefix']."ImeiUnlock_transactions SET `remainder` = `remainder` + ".$resArray['GROSSREFUNDAMT']." WHERE `id` = '".$_POST['id']."';");
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoRefund)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $resArray['REFUNDTRANSACTIONID'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= $resArray['GROSSREFUNDAMT'];
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData); 
?>