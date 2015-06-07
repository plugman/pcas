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
|	Process SagePay Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
include("transfer.inc.php");

$Decoded = SimpleXor(base64Decode($_REQUEST['crypt']),$module['passphrase']);

// ** Split out the useful information into variables we can use **
$values = getToken($Decoded);

$customer = $db->select("SELECT `customer_id`, `prod_total` FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE `cart_order_id` = ".$db->MySQLSafe($_GET['cart_order_id']));

$cart_order_id = sanitizeVar($_GET['cart_order_id']); // Used in remote.php $cart_order_id is important for failed orders

$transData['customer_id'] = $customer[0]["customer_id"];
$transData['gateway'] = "SagePay";
$transData['trans_id'] = $values["VendorTxCode"];
$transData['order_id'] = $cart_order_id;
$transData['amount'] = $customer[0]["prod_total"];
$transData['status'] = $values['Status'];

if($values['Status']=="OK"){
	$paymentResult = 2;
	$order->orderStatus(3,$cart_order_id);
	$transData['notes'] = "Card charged successfully.";
} else {
	$paymentResult = 1;
	$transData['notes'] = "Payment failed.";	
}	
$order->storeTrans($transData);			
?>
