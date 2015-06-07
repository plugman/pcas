<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$pppro = $db->select(sprintf("SELECT min(`id`), trans_id FROM `%sImeiUnlock_transactions` WHERE order_id = %s and `status` = 'SUCCESS' GROUP BY `id`",$glob['dbprefix'],$db->MySQLSafe($order_id)));

$authorizationID = urlencode(trim($_POST['authorizationID']));

$amount=urlencode(trim($_POST['amount']));

$currency=urlencode($config['defaultCurrency']);

if($ppfunction=="doReAuth") {
	$nvpStr 	= 	"&AUTHORIZATIONID=".$authorizationID.
					"&AMT=".$amount.
					"&CURRENCYCODE=".$currency;
	$authMode 	= 	"DoReauthorization";
	$pre 		= 	"re";
} else {
	$nvpStr 	=	"&TRANSACTIONID=".$authorizationID.
					"&AMT=".$amount.
					"&CURRENCYCODE=".$currency.
					"&TRANSACTIONENTITY=Order";
	$authMode 	= 	"DoAuthorization";
	$pre 		= 	"";
}

$resArray=hash_call($authMode,$nvpStr);

$ack = strtoupper($resArray["ACK"]);

if($ack!=="SUCCESS"){
	$transNotes = $resArray['L_LONGMESSAGE0'];
	$ppMsg = "<p class='warnText' id='ppresult'>".$resArray['L_LONGMESSAGE0']."</p>";		
} else {
	$transNotes = priceFormat($_POST['amount'],true)." of ".$_POST['authorizationID']." ".$pre."authorized successfully.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
}

$transData['customer_id'] = $orderSum[0]["customer_id"];
$transData['gateway'] = "PayPal Website Payments Pro (DoAuthorization)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] = isset($resArray['TRANSACTIONID']) ? $resArray['TRANSACTIONID'] : $resArray['AUTHORIZATIONID'];
$transData['order_id'] = $orderSum[0]['cart_order_id'];
$transData['amount'] = trim($_POST['amount']);
$transData['status'] = $ack;
$transData['notes'] = $transNotes;
$order->storeTrans($transData);		
?>