<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock v4
|   ========================================
|   by Alistair Brookbanks
|	ImeiUnlock is a Trade Mark of Devellion Limited
|   Copyright Devellion Limited 2005 - 2006. All rights reserved.
|   Devellion Limited,
|   22 Thomas Heskin Court,
|   Station Road,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 3EE
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Date: Thursday, 4th January 2007
|   Email: sales (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	call.inc.php
|   ========================================
|	APC for the Nochex Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
## Read the POST data and compile request string for validation call
$params = array();
foreach ($_POST as $key => $value){
	$value		= urlencode(stripslashes($value));
	$params[]	= "$key=$value";
}
$params			= implode('&', $params);
$validation_url	= "www.nochex.com";

## Post back to Nochex server to validate
$header .= "POST /nochex.dll/apc/apc HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: ".strlen($params)."\r\n\r\n";
$fp = fsockopen($validation_url, 80, $errno, $errstr, 30);

$extraNotes = ($_POST['status']=="test") ? 'This is a test transaction. No money has actually been received. ' : '';

if (!$fp) {
	## HTTP ERROR
} else {
	fputs($fp, $header.$params);
	while (!feof($fp)) {
		$res = fgets($fp, 1024);
		if (strcmp($res, 'AUTHORISED') == 0) {
			$order->orderStatus(3, $_POST['order_id']);
			$transData['notes'] = $extraNotes."Card charged successfully.";
			break;
		} else {
			$transData['notes'] = $extraNotes."Payment failed.";
		}
	}
	fclose($fp);
}

/*
	[transaction_id] => 1259615
	[transaction_date] => 27/04/2009 10:58:01
	[order_id] => 090427-100756-1552
	[amount] => 19.95
	[from_email] => customer@example.com
	[to_email] => merchant@example.com
	[security_key] => abcdefghijklmnopqrst1234567 (32 char string)
	[status] => test
	[custom] => 
*/

$customer = $db->select("SELECT `customer_id`, `prod_total` FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE `cart_order_id` = ".$db->MySQLSafe($_POST['order_id']));

$cart_order_id = sanitizeVar($_POST['order_id']); // Used in remote.php $cart_order_id is important for failed orders

$transData['customer_id'] = $customer[0]["customer_id"];
$transData['gateway'] = "Nochex";
$transData['trans_id'] = $_POST['transaction_id'];
$transData['order_id'] = $cart_order_id;
$transData['amount'] = $_POST['amount'];
$transData['status'] = $_POST['status'];	
$order->storeTrans($transData);	
?>