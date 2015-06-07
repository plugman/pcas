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
|	IPN for the PayPal Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
// Returned variables...
/*
[mc_gross]
[invoice]
[address_status] 
[payer_id] 
[tax] 
[address_street]
[payment_date] 
[payment_status] 
[charset] 
[address_zip] 
[first_name] 
[mc_fee] 
[address_country_code]
[address_name] 
[notify_version] 
[custom] 
[payer_status] 
[business] 
[address_country] 
[address_city] 
[quantity] 
[verify_sign] 
[payer_email] 
[txn_id] 
[payment_type] 
[last_name]
[address_state] 
[receiver_email] 
[payment_fee] 
[receiver_id] 
[txn_type] 
[item_name] 
[mc_currency] 
[item_number] 
[residence_country] 
[test_ipn]
[payment_gross] 
[shipping] 
*/


// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
$cart_order_id = sanitizeVar($_POST['invoice']);
$order->getOrderSum($cart_order_id);

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

if($module['testMode']==1) { 
	$ipnUrl = "www.sandbox.paypal.com";
} else {
	$ipnUrl = "www.paypal.com";
}

$ipnPath = "/cgi-bin/webscr";

$ch = curl_init();
$headers[] = 'POST '.$ipnPath.' HTTP/1.0';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Content-Length: ' . strlen ($req);
curl_setopt($ch, CURLOPT_URL,$ipnUrl.$ipnPath);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1); 

if($config['proxy']==1) {
	curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']);
} 

curl_setopt($ch,CURLOPT_POSTFIELDS,$req);

//getting response from server
$res = curl_exec($ch); // returns INVALID orVERIFIED

curl_close ($ch);

if ($res == "VERIFIED") {
	
	$success = true;
	
	// check the payment_status is Completed
	if($_POST['payment_status']!=="Completed") {
	
		$success = false;
		
		switch($_POST['payment_status']) {
		
			case "Canceled_Reversal":
			$transData['notes'] = "This means a reversal has been canceled; for example, you, the merchant, won a dispute with the customer and the funds for the transaction that was reversed have been returned to you.";
			$order->orderStatus(6,$cart_order_id);
			break;
			
			case "Denied":
			$transData['notes'] = "You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the following pending reasons.";
			$order->orderStatus(6,$cart_order_id);
			break;
			
			case "Failed":
			$transData['notes'] = "The payment has failed. This will only happen if the payment was attempted from your customer�s bank account.";
			break;
			
			case "Pending":
			$transData['notes'] = "The payment is pending; see the pending_reason variable for more information. Please note, you will receive another Instant Payment Notification when the status of the payment changes to
\"Completed,\" \"Failed,\" or
\"Denied.\"";
			break;
			
			case "Refunded":
			$transData['notes'] = "You, the merchant, refunded the payment.";
			if($_POST['mc_gross'] * -1 == $order->orderSum['prod_total']) $order->orderStatus(6,$cart_order_id);
			break;
			
			case "Reversed":
			$transData['notes'] = "This means that a payment was reversed due to a chargeback or other type of reversal. The funds have been debited from your account balance and returned to the customer. The reason for the reversal is given by the reason_code variable.";
			$order->orderStatus(6,$cart_order_id);
			break;
			
			default:
			$transData['notes'] = "Unspecified Error.";
			break;
			
		}
		
	}

	// check that txn_id has not been previously processed
	$txn_id = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_transactions WHERE `trans_id` = ".$db->mySQLsafe($_POST['txn_id']));
	
	if($txn_id == TRUE) {
		$success = false;
		$transData['notes'] = "This transaction id has been processed before. ";
	}
	
	// check that receiver_email is your Primary PayPal email
	if(trim($_POST['receiver_email'])!==trim($module['email'])) {
		$success = false;
		$transData['notes'] = "Recipient account didn't match specified PayPal account.";
	}
	
	// make sure amount paid is same as in database
	if(trim($_POST['mc_gross']) != $order->orderSum['prod_total']) {
		$success = false;
		$transData['notes'] = "Amount paid didn't match amount on invoice.";
	}

	// process payment
	if($success == true) {
		$transData['notes'] = "Payment successful. <br />Address: ".$_POST['address_status']."<br />Payer Status: ".$_POST['payer_status'];
		$order->orderStatus(3,$cart_order_id);
	}
	
	
	$transData['customer_id'] = $order->orderSum["customer_id"];
	$transData['gateway'] = "PayPal IPN";
	$transData['trans_id'] = $_POST['txn_id'];
	$transData['order_id'] = $cart_order_id;
	$transData['status'] = $_POST['payment_status'];
	$transData['amount'] = $_POST['mc_gross'];
	$order->storeTrans($transData);
	
}


// Uncomment the line below with your email address to dubug this file
//mail("you@example.com","PayPal IPN Debug","Post Vars From PayPal:\n".print_r($_POST,true)."\n------\ncURL String".$req."\n------\nPayPal Result:\n".$res."\n------\nTranaction Log Data:\n ".print_r($transData,true),"From: nobody@example.com");
	 
?>