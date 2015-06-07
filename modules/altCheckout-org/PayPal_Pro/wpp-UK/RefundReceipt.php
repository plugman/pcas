<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$ORIGID = trim($_POST['transactionId']);

$amount 			= sprintf("%.2f",trim($_POST['amount']));
$refundType 		= $amount == $orderSum[0]['prod_total'] ? "FULL" : "PARTIAL";
$memo 				= trim($_POST['note']);
$nvpStr				= "&ORIGID[".strlen($ORIGID)."]=".$ORIGID."&MEMO[".strlen($memo)."]=".$memo;

if($refundType=="PARTIAL") $nvpStr .= "&AMT[".strlen($amount)."]=".$amount;

$request_id = md5($_POST['transactionId'].date('YmdGis')."1");

$resArray = hash_call($_POST['TENDER'],"C",$nvpStr,$request_id);

$ack = $resArray["RESULT"] == 0 ? "SUCCESS" : "FAIL";

if($ack!=="SUCCESS"){
	$errorMsg = paypalErrors($resArray);
	$transNotes = "<strong>Error:</strong> ".$errorMsg["admin"]."<br /><strong>PayPal Response:</strong> ".$errorMsg["paypal"];
	$ppMsg = "<p class='warnText' id='ppresult'>".nl2br($transNotes)."</p>";		
} else {
	$transNotes = ucfirst(strtolower($refundType))." Refund of ".priceFormat($amount,true)." successful.";
	
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
	$db->misc("UPDATE ".$glob['dbprefix']."ImeiUnlock_transactions SET `remainder` = `remainder` + ".$amount." WHERE `id` = '".$_POST['id']."';");
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoRefund)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $resArray['PNREF'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= $amount;
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData); 
?>