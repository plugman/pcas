<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$pppro = $db->select(sprintf("SELECT min(`id`), trans_id FROM `%sImeiUnlock_transactions` WHERE order_id = %s and `status` = 'SUCCESS' GROUP BY `id`",$glob['dbprefix'],$db->MySQLSafe($order_id)));

$authorizationID	=	trim($_POST['authorizationID']);
$amount				=	sprintf("%.2f",trim($_POST['amount']));
$note				=	trim($_POST['note']);
$finalCapture 		= 	$_POST['CompleteCodeType']=="Complete" ? "Y" : "N";

$nvpStr				=	"&ORIGID[".strlen($authorizationID)."]=".$authorizationID."&AMT[".strlen($amount)."]=".$amount."&COMMENT1[".strlen($note)."]=".$note."&CAPTURECOMPLETE[".strlen($finalCapture)."]=".$finalCapture;

$request_id = md5($authorizationID.date('YmdGis')."1");

$resArray = hash_call($_POST['TENDER'],"D",$nvpStr,$request_id);

$ack = $resArray["RESULT"] == 0 ? "SUCCESS" : "FAIL";

if($ack!=="SUCCESS"){

	$errorMsg = paypalErrors($resArray);
	$transNotes = "<strong>Error:</strong> ".$errorMsg["admin"]."<br /><strong>PayPal Response:</strong> ".$errorMsg["paypal"];
	$ppMsg = "<p class='warnText' id='ppresult'>".nl2br($transNotes)."</p>";		
} else {
	$transNotes = priceFormat($amount,true)." of authorization ID ".$authorizationID." captured successfully.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
	
	$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_transactions SET `remainder` = `remainder` + ".$amount." WHERE `id` = '".$_POST['id']."';";
	$db->misc($query);
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoCapture)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $resArray['PNREF'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= $amount;
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData);		
?>