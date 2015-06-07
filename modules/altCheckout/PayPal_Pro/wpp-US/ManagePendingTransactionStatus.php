<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$transaction_id		=	urlencode(trim($_POST['transaction_id']));
$nvpStr				=	"&TRANSACTIONID=".$transaction_id.
						"&ACTION=".urlencode(trim($_POST['action']));

$resArray = hash_call("ManagePendingTransactionStatus",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

if($ack!=="SUCCESS"){
	$transNotes = $resArray['L_LONGMESSAGE0'];
	$ppMsg = "<p class='warnText' id='ppresult'>".$resArray['L_LONGMESSAGE0']."</p>";		
} else {
	$transNotes = "Fraud management filter for transaction id ".$_POST['transaction_id']." set to '".$_POST['action']."'.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (FMF ".$_POST['action'].")";
$transData['extra'] 		= "";
$transData['trans_id'] 		= $_POST['transaction_id'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= "";
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData);
?>