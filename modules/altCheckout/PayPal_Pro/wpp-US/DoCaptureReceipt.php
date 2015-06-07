<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$pppro = $db->select(sprintf("SELECT min(`id`), trans_id FROM `%sImeiUnlock_transactions` WHERE order_id = %s and `status` = 'SUCCESS' GROUP BY `id`",$glob['dbprefix'],$db->MySQLSafe($order_id)));

$authorizationID	=	urlencode(trim($_POST['authorizationID']));
$completeCodeType	=	urlencode(trim($_POST['CompleteCodeType']));
$amount				=	urlencode(trim($_POST['amount']));
$invoiceID			=	urlencode($order_id);
$currency			=	urlencode($config['defaultCurrency']);
$note				=	urlencode(trim($_POST['note']));

$nvpStr				=	"&AUTHORIZATIONID=".$authorizationID.
						"&AMT=".$amount.
						"&COMPLETETYPE=".$completeCodeType.
						"&CURRENCYCODE=".$currency.
						"&NOTE=".$note.
						
						"&AUTHSTATUS3D=".$AUTHSTATUS3D.
						"&MPIVENDOR3DS=".$MPIVENDOR3DS.
						"&CAVV=".$CAVV.
						"&ECI3DS=".$ECI.
						"&XID=".$XID;

						

$resArray 			= 	hash_call("DoCapture",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

if($ack!=="SUCCESS"){
	$transNotes = $resArray['L_LONGMESSAGE0'];
	$ppMsg = "<p class='warnText' id='ppresult'>".$resArray['L_LONGMESSAGE0']."</p>";		
} else {
	$transNotes = priceFormat(trim($_POST['amount']),true)." of authorization ID ".$_POST['authorizationID']." captured successfully.";
	$ppMsg = "<p class='infoText' id='ppresult'>".$transNotes."</p>";
	// Update balance (remainder)
	if($_POST['CompleteCodeType'] == "Complete"){
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_transactions SET `remainder` = `amount` WHERE `id` = '".$_POST['id']."';";
		$db->misc($query);
		$transNotes .= " Final capture complete.";
	} else { 
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_transactions SET `remainder` = `remainder` + ".$resArray['AMT']." WHERE `id` = '".$_POST['id']."';";
		$db->misc($query);
	}
}

$transData['customer_id'] 	= $orderSum[0]["customer_id"];
$transData['gateway'] 		= "PayPal Website Payments Pro (DoCapture)";
$transData['extra'] 		= $_POST['TENDER'];
$transData['trans_id'] 		= $resArray['TRANSACTIONID'];
$transData['order_id'] 		= $orderSum[0]['cart_order_id'];
$transData['amount'] 		= $resArray['AMT'];
$transData['status'] 		= $ack;
$transData['notes'] 		= $transNotes;
$order->storeTrans($transData);		
?>