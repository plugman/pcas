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
|	call.inc.php
|   ========================================
|	Server Call for ChronoPay
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
/*********************************************************
Basic IPN script to be used on your server to receive IPN POSTed (back) 
from Chronopay payments.

This script is provided "as is" with NO WARRANTY OF ANY KIND, INCLUDING
THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE


**********************************************************/

/* testing
$_POST = array(
		'transaction_type' => 'onetime', 
		'customer_id' => '003197-000000050', 
		'site_id' => '003197-0005', 
		'product_id' => '003197-0005-0001', 
		'date' => '', 
		'time' => '',
		'transaction_id' => '', 
		'email' => 'sam.pipe@gmail.com', 
		'country' => 'BHS', 
		'name' => 'JIM BOB', 
		'city' => 'Auckland', 
		'street' => '21 Jump St',  
		'phone' => '1231231123', 
		'state' => 'XX', 
		'zip' => '1001', 
		'language' => 'EN', 
		'cs1' => '060926-061411-6075', 
		'cs2' => '7722a94230cc9cd0f28fe8e06319ba9f', 
		'cs3' => '6.99', 
		'username' => '',
		'password' => '', 
		'total' => '6.99', 
		'currency' => 'USD', 
		);
// testing */

// process response.
$trans_type 	= trim( stripslashes($_POST['transaction_type']) );
$trans_id 		= trim( stripslashes($_POST['transaction_id']) );
$cust_id 		= trim( stripslashes($_POST['customer_id']) );
$site_id 		= trim( stripslashes($_POST['site_id']) );
$product_id 	= trim( stripslashes($_POST['product_id']) );
$trans_date 	= trim( stripslashes($_POST['date']) );
$trans_time 	= trim( stripslashes($_POST['time']) );
$trans_name 	= trim( stripslashes($_POST['name']) );
$trans_email 	= trim( stripslashes($_POST['email']) );
$total		 	= trim( stripslashes($_POST['total']) );
$cart_order_id	= trim( stripslashes($_POST['cs1']) );
$cHash			= trim( stripslashes($_POST['cs2']) );
$cTotal 		= trim( stripslashes($_POST['cs3']) );
$amount			= trim( stripslashes($_POST['total']) );

$transData['customer_id'] = $cust_id;
$transData['gateway'] = "ChronoPay";
$transData['trans_id'] = $trans_id;
$transData['order_id'] = $cart_order_id;
$transData['amount'] = $amount;

// now validate the request
$retHash = md5( $glob['storeURL'].$product_id.$cTotal );

if( $retHash==$cHash && $trans_type!='' && $cart_order_id!='' )
{	
	// validate taht the order hasn't already been processed.
	if( $orderSum['status']!==1 )
	{
		// order is not at a pending status.. fail as this must be an error somewhere or a fake request.
		$transData['status'] = "Aborted";
		$transData['notes'] = "Order status has already been updated. This payment may be a duplicate or a fake.";
		$order->storeTrans($transData); 
		exit();
	}	
	
	// check the status of the payment
	if( $trans_type=='onetime' || $trans_type=='initial' || $trans_type=='rebill' )
	{
		// successful payment
		$transData['status'] = "Successful";
		$transData['notes'] = "Payment has been taken successfully.";
		$order->orderStatus(3,$cart_order_id);
	}
	else
	{
		// failed payment
		$transData['status'] = "Failed";
		$transData['notes'] = "Payment failed.";
		
	}
}
else
{
	// invalid request from Chronopay server.
	// if the transaction is fraudolent or not correct do something to notify yourself of the problem
	// failed payment
		$transData['status'] = "Error";
		$transData['notes'] = "Invalid request from Chronopay server.";
}
$order->storeTrans($transData);
?>