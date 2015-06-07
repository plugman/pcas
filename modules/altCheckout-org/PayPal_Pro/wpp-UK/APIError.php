<?php
/*
+--------------------------------------------------------------------------************************************************
APIError.php

Displays error parameters.

Called by DoDirectPaymentReceipt.php, TransactionDetails.php,
GetExpressCheckoutDetails.php and DoExpressCheckoutPayment.php.

*************************************************/
if (!defined('CC_INI_SET')) die("Access Denied");
session_start();
$resArray=$_SESSION['reshash']; 
?>
<html>
<head>
<title>PayPal PHP API Response</title>
<link href="sdk.css" rel="stylesheet" type="text/css"/>
</head>

<body alink=#0000FF vlink=#0000FF>

<center>

<table width="700">
<tr>
	<td colspan="2"><h3>The PayPal API has returned an error!</h3></td>
</tr>

<?php  //it will print if any URL errors 
if(isset($_SESSION['curl_error_no'])) { 
	$errorCode = $_SESSION['curl_error_no'] ;
	$errorMessage = $_SESSION['curl_error_msg'] ;		
?>

   
<tr>
		<td><strong>Error Number:</strong></td>
		<td><?php $errorCode ?></td>
	</tr>
	<tr>
		<td><strong>Error Message:</strong></td>
		<td><?php $errorMessage ?></td>
	</tr>
<?php } else {

/* If there is no URL Errors, Construct the HTML page with 
   Response Error parameters.   
   */
?>

		<td><strong>Ack:</strong></td>
		<td><?php $resArray['ACK']; ?></td>
	</tr>
	<tr>
		<td><strong>Correlation ID:</strong></td>
		<td><?php $resArray['CORRELATIONID']; ?></td>
	</tr>
	<tr>
		<td><strong>Version:</strong></td>
		<td><?php $resArray['VERSION']; ?></td>
	</tr>
<?php
	$count=0;
	while (isset($resArray["L_SHORTMESSAGE".$count])) {		
		  $errorCode    = $resArray["L_ERRORCODE".$count];
		  $shortMessage = $resArray["L_SHORTMESSAGE".$count];
		  $longMessage  = $resArray["L_LONGMESSAGE".$count]; 
		  $count=$count+1; 
?>
	<tr>
		<td><strong>Error Number:</strong></td>
		<td><?php $errorCode; ?></td>
	</tr>
	<tr>
		<td><strong>Short Message:</strong></td>
		<td><?php $shortMessage; ?></td>
	</tr>
	<tr>
		<td><strong>Long Message:</strong></td>
		<td><?php $longMessage; ?></td>
	</tr>
	
<?php }//end while
}// end else
?>
</table>
<a class="home" id="CallsLink" href="index.php">Home</a>
</center>
</body>
</html>
<?php 
session_unset();
exit;
?>