<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$authorizationID	=	urlencode(trim($_POST['authorization_id']));
$note				=	urlencode(trim($_POST['note']));
$nvpStr				=	"&AUTHORIZATIONID=".$authorizationID.
						"&NOTE=".$note;

$resArray = hash_call("DoVoid",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

if($ack!=="SUCCESS"){
	$transNotes = $resArray['L_LONGMESSAGE0'];
	$ppMsg = "<p class='warnText' id='ppresult'>".$resArray['L_LONGMESSAGE0']."</p>";		
} else {
	$transNotes = "Authorization ".$_POST['authorization_id']." has been void.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoVoid)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $_POST['authorization_id'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= "";
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData);
?>