<?php
/*
+--------------------------------------------------------------------------***************************************************
CallerService.php

This file uses the constants.php to get parameters needed 
to make an API call and calls the server.if you want use your
own credentials, you have to change the constants.php

Called by TransactionDetails.php, ReviewOrder.php, 
DoDirectPaymentReceipt.php and DoExpressCheckoutPayment.php.

****************************************************/
if (!defined('CC_INI_SET')) die("Access Denied");
require_once 'constants.php';

$API_UserName=API_USERNAME;
$API_Password=API_PASSWORD;
$API_Signature=API_SIGNATURE;
$API_Endpoint =API_ENDPOINT;
$version=VERSION;

session_start();

/**
  * hash_call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @nvpStr is nvp string.
  * returns an associtive array containing the response from the server.
*/
function pp_debug($nvpreq,$response) {

	// sanitize string
	$filename = "modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."logs".CC_DS.'PayPal-NVP-US-Log-'.date("Ymd");
	$content = "=== ".date("r")." ===\n";
	$content .= "NVP String:\n".$nvpreq."\n--------------------\n";
	$content .= "cURL Response:\n".$response."\n=======================================\n\n\n\n";
	
	$handle = @fopen($filename, 'a'); 
	@fwrite($handle, $content);
	@fclose($handle);
}

function hash_call($methodName,$nvpStr) {
	//declaring of global variables
	global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature;

	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
	if(USE_PROXY)
	curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

	//NVPRequest for submitting to server
	$nvpreq	=	"METHOD=".urlencode($methodName).
				"&VERSION=".urlencode($version).
				"&PWD=".urlencode($API_Password).
				"&USER=".urlencode($API_UserName).
				"&SIGNATURE=".urlencode($API_Signature).$nvpStr;

	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	//getting response from server
	$response = curl_exec($ch);

	if(PAYPAL_DEBUG==true) {
		pp_debug($nvpreq,$response);
	}
	
	//convrting NVPResponse to an Associative Array
	$nvpResArray=deformatNVP($response);
	$nvpReqArray=deformatNVP($nvpreq);
	$_SESSION['nvpReqArray']=$nvpReqArray;

	if (curl_errno($ch)) {
		switch (curl_errno($ch)) {
			case 18:
				break;
			default:
				echo "<strong>Curl Error No:</strong> ".curl_errno($ch)."<br />For further info please see: <a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>http://curl.haxx.se/libcurl/c/libcurl-errors.html</a>";
				exit;
		}
	}
	curl_close($ch);
	return $nvpResArray;
}

/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */

function deformatNVP($nvpStr) {

	$intial=0;
 	$nvpArray = array();


	while(strlen($nvpStr)){
		//postion of Key
		$keypos= strpos($nvpStr,'=');
		//position of value
		$valuepos = strpos($nvpStr,'&') ? strpos($nvpStr,'&'): strlen($nvpStr);

		/*getting the Key and Value values and storing in a Associative Array*/
		$keyval=substr($nvpStr,$intial,$keypos);
		$valval=substr($nvpStr,$keypos+1,$valuepos-$keypos-1);
		//decoding the respose
		$nvpArray[urldecode($keyval)] =urldecode( $valval);
		$nvpStr=substr($nvpStr,$valuepos+1,strlen($nvpStr));
     }
	return $nvpArray;
}
?>
