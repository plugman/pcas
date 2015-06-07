<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$authorizationID = trim($_POST['authorization_id']);
$note = trim($_POST['note']);

$nvpStr="&ORIGID[".strlen($authorizationID)."]=".$authorizationID;
$nvpStr.="&NOTE[".strlen($note)."]=".$note;

$request_id = md5($authorizationID.date('YmdGis')."1");

$resArray = hash_call($_POST['TENDER'],"V",$nvpStr,$request_id);

$ack = $resArray["RESULT"] == 0 ? "SUCCESS" : "FAIL";

if($ack!=="SUCCESS"){
	$errorMsg = paypalErrors($resArray);
	$transNotes = "<strong>Error:</strong> ".$errorMsg["admin"]."<br /><strong>PayPal Response:</strong> ".$errorMsg["paypal"];
	$ppMsg = "<p class='warnText' id='ppresult'>".nl2br($transNotes)."</p>";		
} else {
	$transNotes = "Authorization ".$_POST['authorization_id']." has been void.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoVoid)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $authorizationID;
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= "";
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData);
?>