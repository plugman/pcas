<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	process.inc.php
|   ========================================
|	Process Worldpay Payment	
+--------------------------------------------------------------------------
*/
/*
FILE DEFUNCT LEFT FOR THOS WHO HAVEN"T UPDATED THEIR RESPONSE URL
*/

// read the post from PayPal system and add 'cmd'
if (!defined('CC_INI_SET')) die("Access Denied");
$customer = $db->select("SELECT `customer_id` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `email` = ".$db->MySQLSafe($_REQUEST['email']));


if ($customer == true) {
	
	$cart_order_id = $_REQUEST['cartId'];
	
	$transData['customer_id'] = $customer[0]["customer_id"];
	$transData['gateway'] = "WorldPay";
	$transData['trans_id'] = $_REQUEST['transId'];
	$transData['order_id'] = $cart_order_id;
	$transData['amount'] = $_REQUEST['amount'];
	
	if ($_REQUEST['transStatus'] == "Y") {
		$transData['status'] = "Success";
		$paymentResult = 2;
		$order->orderStatus(3,$cart_order_id);
		$transData['notes'] = "Payment was successful.";		
	} else {
		$transData['status'] = "Fail";
		$paymentResult = 1;
		$order->orderStatus(1,$cart_order_id);
		$transData['notes'] = "Payment unsuccessful. More information may be available in the WorldPay control panel.";
	}
	
	$order->storeTrans($transData);
} else {
	die("<strong>Fatal Error:</strong> Customer not found from email address.");
}
?>
