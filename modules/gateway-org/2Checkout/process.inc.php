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
|	Process 2CO Gateway	
+--------------------------------------------------------------------------
*/
$customer = $db->select("SELECT `customer_id` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `email` = ".$db->MySQLSafe($_POST['email']));

if($customer==TRUE) {

	$cart_order_id = $_POST['cart_order_id'];// Used in remote.php $cart_order_id is important for failed orders

	$transData['customer_id'] = $customer[0]["customer_id"];
	$transData['gateway'] = "2Checkout";
	$transData['trans_id'] = $_POST['order_number'];
	$transData['order_id'] = $cart_order_id;
	$transData['amount'] = $_POST['total'];
	$transData['status'] = $_POST['credit_card_processed'];
	
	if($_POST['credit_card_processed']=="Y") {
		$paymentResult = 2;
		$order->orderStatus(3,$cart_order_id);
		$transData['notes'] = "Card charged successfully.";
	} elseif($_POST['credit_card_processed']=="K") {
		$paymentResult = 3;
		$order->orderStatus(2,$cart_order_id);
		$transData['notes'] = "Card waiting for approval.";
	}
	
	$order->storeTrans($transData);
} else {
	die("<strong>Fatal Error:</strong> Customer not found from email address.");
}			
?>