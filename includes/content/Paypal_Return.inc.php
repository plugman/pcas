<?php
/*
+--------------------------------------------------------------------------
|	call.inc.php
|   ========================================
|	IPN for the PayPal Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
$topup_balance = new XTemplate ("content".CC_DS."Paypal_Return.tpl");

/*echo "<PRE>";
print_r($_POST);die();*/
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
/*
    $_POST["CONTEXT"] = "X3-7SZn2ExXucINxlliZ_05NdFsrIIpaV9TcRYNLL_GiOwm9XgEZzWKQeV0";
    $_POST["myAllTextSubmitID"] =""; 
    $_POST["cmd"] = "_flow";
    $_POST["mc_gross"] = 0.01;
    $_POST["invoice"] = "110210-040222-9894";
    $_POST["protection_eligibility"] = "Ineligible";
    $_POST["address_status"] = "unconfirmed";
    $_POST["payer_id"] = "6M55NZEWVR8S2";
    $_POST["tax"] = 0.00;
    $_POST["address_street"] = "73 Victoria Gate";
    $_POST["payment_date"] = "04:04:12 Feb 10, 2011 PST";
    $_POST["payment_status"] = "Completed";
    $_POST["charset"] = "windows-1252";
    $_POST["address_zip"] = "CM17 9TB";
    $_POST["first_name"] = "Moin";
    $_POST["mc_fee"] = 0.01;
    $_POST["address_country_code"] = "GB";
    $_POST["address_name"] = "Sabri Technogies (UK) Ltd";
    $_POST["notify_version"] = 3.0;
    $_POST["custom"] = "";
    $_POST["payer_status"] = "verified";
    $_POST["business"] = "rakeshg@emsmobile.ae";
    $_POST["address_country"] = "United Kingdom";
    $_POST["address_city"] = "Harlow";
    $_POST["quantity"] = 1;
    $_POST["payer_email"] = "moin_sabri@hotmail.com";
    $_POST["verify_sign"] = "AiPC9BjkCyDFQXbSkoZcgqH3hpacAGojsdX5J6kseUc5mx4xqPWAdaSD";
    $_POST["txn_id"] = "76S62819FE516972A";
    $_POST["payment_type"] = "instant";
    $_POST["payer_business_name"] = "Sabri Technogies (UK) Ltd";
    $_POST["last_name"] = "Sabri";
    $_POST["address_state"] = "Essex";
    $_POST["receiver_email"] = "rakeshg@emsmobile.ae";
    $_POST["payment_fee"] = 0.01;
    $_POST["receiver_id"] = "GQFPZQ37LGW2E";
    $_POST["txn_type"] = "web_accept";
    $_POST["item_name"] = "Credit Card Topup Balance";
    $_POST["mc_currency"] = "USD";
    $_POST["item_number"] = "";
    $_POST["residence_country"] = "GB";
    $_POST["transaction_subject"] = "Credit Card Topup Balance";
    $_POST["handling_amount"] = 0.00;
    $_POST["payment_gross"] = 0.01;
    $_POST["shipping"] = 0.00;
    $_POST["form_charset"] = "UTF-8";*/

 //echo $_POST["payment_status"];

require_once ("ini.inc.php");
require_once ("includes" . CC_DS . "global.inc.php");
require_once ("includes" . CC_DS . "functions.inc.php");
require_once ("classes" . CC_DS . "db" . CC_DS . "db.php");
require_once ("classes" . CC_DS . "cart" . CC_DS . "shoppingCart.php");
require_once ("classes" . CC_DS . "cart" . CC_DS . "order.php");
require_once ("classes" . CC_DS . "session" . CC_DS . "cc_session.php");
require_once ("classes" . CC_DS . "cache" . CC_DS . "cache.php");

$db 		= new db();
$cc_session = new session();
$config		= fetchdbconfig("config");

$customerId = $cc_session->ccUserData['customer_id'];

$module = fetchDbConfig('PayPal');

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

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
$res1 = curl_exec($ch); // returns INVALID orVERIFIED

curl_close ($ch);
$res = $_POST["payer_status"];


if ($res == "VERIFIED" || $res =="verified" || $res1 == "VERIFIED" || $res1 =="verified") {
//echo "test";
	$success = true;
	//echo "test";

	// check the payment_status is Completed
	if($_POST['payment_status'] != "Completed")
	{
		$success = false;
		switch($_POST['payment_status'])
		{
			case "Canceled_Reversal":
			$transData['notes'] = "This means a reversal has been canceled; for example, you, the merchant, won a dispute with the customer and the funds for the transaction that was reversed have been returned to you.";
			$transData['status'] = 3;
			$s=6;
			break;
			case "Denied":
			$transData['notes'] = "You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the following pending reasons.";
			$transData['status'] = 3;
			$s=6;
			break;
			case "Failed":
			$transData['notes'] = "The payment has failed. This will only happen if the payment was attempted from your customer's bank account.";
			$transData['status'] = 3;
			$s=0;
			break;
			case "Pending":
			$transData['notes'] = "Pending";
			$transData['status'] = 2;
			$s=100;
			break;
			case "Refunded":
			$transData['notes'] = "You, the merchant, refunded the payment.";
			$transData['status'] = 3;
			$s=99;
			break;
			case "Reversed":
			$transData['notes'] = "This means that a payment was reversed due to a chargeback or other type of reversal. The funds have been debited from your account balance and returned to the customer. The reason for the reversal is given by the reason_code variable.";
			$transData['status'] = 3;
			$s=98;
			break;
			default:
			$transData['notes'] = "Unspecified Error.";
			$transData['status'] = 0;
			$s=0;
			break;
			
		}
		
	}

	/*echo "<pre>";
	print_r($_POST);
	die();*/
	// check that receiver_email is your Primary PayPal email
	if(trim($_POST['receiver_email']) != trim($module['email'])) {
		/*echo $_POST['receiver_email']."<br>";
		echo $module['email']."<br>";
*/		$success = false;
		$transData['notes'] = "Recipient account didn't match specified PayPal account.";
		$transData['status'] = 3;
		$s=0;
	}
	
	// process payment
	if($success == true) {
		$transData['notes'] = "Payment successful.";
		$transData['status'] = 1;
		$s=1;
	}
	$transData['notes'] = "";
	$transData['customer_id'] = $_POST['item_number'];
	$transData['gateway'] = "PayPal";
	$transData['transactionId'] = $_POST['txn_id'];
	$transData['paymentstatus'] = $_POST['payment_status'];	
	$actualAmount = explode(":", $_POST['item_name']);
	$transData['paypalfee'] =  $_POST['mc_gross'] - $actualAmount[1] ; 
	$transData['amount'] = GetPrice_defaultCurrency($_POST['mc_gross'] - $transData['paypalfee']);
	//echo "<PRE>";
//	print_r($transData);
//	die();
	if(topuptranidcheck($transData) == false){
		httpredir($glob['storeURL']."/index.php?_a=topupBalance&s=".$s);		
	}else{
		storePaymentTrans($transData);
		httpredir($glob['storeURL']."/index.php?_a=topupBalance&s=".$s);
	}
}
else{
			httpredir($glob['storeURL']."/index.php?_a=topupBalance&s=".$s);
}

// Uncomment the line below with your email address to dubug this file
//mail("you@example.com","PayPal IPN Debug","Post Vars From PayPal:\n".print_r($_POST,true)."\n------\ncURL String".$req."\n------\nPayPal Result:\n".$res."\n------\nTranaction Log Data:\n ".print_r($transData,true),"From: nobody@example.com");
	 
?>