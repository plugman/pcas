<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'constants.php';

session_start();

function pp_debug($nvpreq,$response) {
	
	$filename = "modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."logs".CC_DS.'PayPal-NVP-UK-Log-'.date("Ymd");
	$content = "=== ".date("r")." ===\n";
	$content .= "NVP String:\n".$nvpreq."\n--------------------\n";
	$content .= "cURL Response:\n".$response."\n=======================================\n\n\n\n";
	
	$handle = @fopen($filename, 'a'); 
	@fwrite($handle, $content);
	@fclose($handle);
}

function hash_call($TENDER,$TRXTYPE,$nvpStr,$request_id) {

	global $ini;

	$headers[] = "Content-Type: text/namevalue"; // either text/namevalue or text/xml
	$headers[] = "X-VPS-Timeout: 30";
	$headers[] = "X-VPS-VIT-OS-Name: ".PHP_OS;  // Name of your Operating System (OS)
	$headers[] = "X-VPS-VIT-OS-Version: Unknown";  // OS Version
	$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";  // Language you are using
	$headers[] = "X-VPS-VIT-Client-Version: 0.01";  // For your info
	$headers[] = "X-VPS-VIT-Client-Architecture: Unknown";  // For your info
	$headers[] = "X-VPS-VIT-Client-Certification-Id: 33baf5893fc2123d8b191d2d011b7fdc"; // This header requirement will be removed
	$headers[] = "X-VPS-VIT-Integration-Product: ImeiUnlock";  // For your info, would populate with application name
	$headers[] = "X-VPS-VIT-Integration-Version: ".$ini['ver']; // Application version
	$headers[] = "X-VPS-Request-ID: ".$request_id;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, API_ENDPOINT);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_HEADER, 0); // tells curl to include headers in response
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	if(USE_PROXY) curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

	//NVPRequest for submitting to server
	
	$verbosity = "MEDIUM";
	
	$nvpreq = 	"TRXTYPE[".strlen($TRXTYPE)."]=".$TRXTYPE.
				"&TENDER[".strlen($TENDER)."]=".$TENDER.
				"&PWD[".strlen(PWD)."]=".PWD.
				"&USER[".strlen(USER)."]=".USER.
				"&VENDOR[".strlen(VENDOR)."]=".VENDOR.
				"&PARTNER[".strlen(PARTNER)."]=".PARTNER.
				"&VERBOSITY[".strlen($verbosity)."]=".$verbosity.
				$nvpStr;

	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

	//getting response from server
	$response = curl_exec($ch);

	if(PAYPAL_DEBUG==true) {
		pp_debug($nvpreq,$response);
	}
	
	//convrting NVPResponse to an Associative Array
	$nvpResArray = deformatNVP($response);
	$nvpReqArray = deformatNVP($nvpreq);
	$_SESSION['nvpReqArray']=$nvpReqArray;

	if (curl_errno($ch)) {
		echo "<strong>Curl Error No:</strong> ".curl_errno($ch)."<br />For further info please see: <a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>http://curl.haxx.se/libcurl/c/libcurl-errors.html</a>";
		exit;
		  
	 } else {
		 //closing the curl
			curl_close($ch);
	  }

return $nvpResArray;

}

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
		$nvpArray[urldecode($keyval)] = urldecode( $valval);
		$nvpStr=substr($nvpStr,$valuepos+1,strlen($nvpStr));
     }
	return $nvpArray;
}

function paypalErrors($resArray) {


	switch($resArray["RESULT"]) {
		
		case 1: 
			$error['admin'] = "User authentication failed. Error is caused by one or more of the following: 
		Invalid Processor information entered. Contact merchant bank to verify. 
		'Allowed IP Address' security feature implemented. The transaction is coming from 
		an unknown IP address. See PayPal Manager Online Help for details on how to use 
		Manager to update the allowed IP addresses. 
		You are using a test (not active) account to submit a transaction to the live PayPal 
		servers. Change the URL from test-payflow.verisign.com to payflow.verisign.com.";
			$error['customer'] = false; 
		break;
		
		case 2: 
			$error['admin'] = "Invalid tender type. Your merchant bank account does not support the following 
		credit card type that was submitted.";
			$error['customer'] = false; 
		break;
		
		case 3: 
			$error['admin'] = "Invalid transaction type. Transaction type is not appropriate for this transaction. For 
		example, you cannot credit an authorisation-only transaction.";
			$error['customer'] = false;
		break;
		
		case 4: 
			$error['admin'] = "Invalid amount format. Use the format: “#####.##” Do not include currency symbols 
		or commas.";
			$error['customer'] = false; 
		break;
		
		case 5: 
			$error['admin'] = "Invalid merchant information. Processor does not recognise your merchant account 
		information. Contact your bank account acquirer to resolve this problem.";
			$error['customer'] = false; 
		break;
		
		case 6: 
			$error['admin'] = "Invalid or unsupported currency code";
			$error['customer'] = false; 
		break;
		
		case 7: 
			$error['admin'] = "Field format error. Invalid information entered. See RESPMSG.";
			$error['customer'] = false; 
		break;
		
		case 8: 
			$error['admin'] = "Not a transaction server";
			$error['customer'] = false; 
		break;
		
		case 9: 
			$error['admin'] = "Too many parameters or invalid stream";
			$error['customer'] = false;
		break;
		
		case 10: 
			$error['admin'] = "Too many line items";
			$error['customer'] = false; 
		break;
		
		case 11: 
			$error['admin'] = "Client time-out waiting for response";
			$error['customer'] = false; 
		break;
		
		case 12: 
			$error['admin'] = "Declined. Check the credit card number, expiry date and transaction information to 
		make sure they were entered correctly. If this does not resolve the problem, have the 
		customer call their card issuing bank to resolve.";
			$error['customer'] = "Card declined. Please check the card number and expiry date have been entered correctly."; 
		break;
		
		case 13: 
			$error['admin'] = "Referral. Transaction cannot be approved electronically but can be approved with a 
		verbal authorisation. Contact your merchant bank to obtain an authorisation and submit 
		a manual Voice Authorisation transaction.";
			$error['customer'] = "Unfortunately this transaction cannot be approved electronically. Please contact you bank and ask them to manually authorize this transaction."; 
		break;
		
		case 14: 
			$error['admin'] = "Invalid Client Certification ID. Check the HTTP header. If the tag, X-VPS-VIT- 
		CLIENT-CERTIFICATION-ID, is missing, RESULT code 14 is returned.";
			$error['customer'] = false; 
		break;
		
		case 19: 
			$error['admin'] = "Original transaction ID not found. The transaction ID you entered for this 
		transaction is not valid. See RESPMSG.";
			$error['customer'] = false; 
		break;
		
		case 20: 
			$error['admin'] = "Cannot find the customer reference number";
			$error['customer'] = false; 
		break;
		
		case 22: 
			$error['admin'] = "Invalid ABA number";
			$error['customer'] = false; 
		break;
		
		case 23: 
			$error['admin'] = "Invalid account number. Check credit card number and re-submit.";
			$error['customer'] = "The credit card number entered is invalid. Please check and try again."; 
		break;
		
		case 24: 
			$error['admin'] = "Invalid expiry date. Check and re-submit.";
			$error['customer'] = "Invalid expiry date. Please check and try again."; 
		break;
		
		case 25: 
			$error['admin'] = "Invalid Host Mapping. You are trying to process a tender type such as Discover Card, 
		but you are not set up with your merchant bank to accept this card type.";
			$error['customer'] = false; 
		break;
		
		case 26: 
			$error['admin'] = "Invalid vendor account";
			$error['customer'] = false; 
		break;
		
		case 27: 
			$error['admin'] = "Insufficient partner permissions";
			$error['customer'] = false; 
		break;
		
		case 28: 
			$error['admin'] = "Insufficient user permissions";
			$error['customer'] = false; 
		break;
		
		case 29: 
			$error['admin'] = "Invalid XML document. This could be caused by an unrecognised XML tag or a bad 
		XML format that cannot be parsed by the system.";
			$error['customer'] = false; 
		break;
		
		case 30: 
			$error['admin'] = "Duplicate transaction";
			$error['customer'] = false; 
		break;
		
		case 31: 
			$error['admin'] = "Error in adding the recurring profile";
			$error['customer'] = false; 
		break;
		
		case 32: 
			$error['admin'] = "Error in modifying the recurring profile";
			$error['customer'] = false; 
		break;
		
		case 33: 
			$error['admin'] = "Error in cancelling the recurring profile";
			$error['customer'] = false; 
		break;
		
		case 34: 
			$error['admin'] = "Error in forcing the recurring profile";
			$error['customer'] = false; 
		break;
		
		case 35: 
			$error['admin'] = "Error in reactivating the recurring profile";
			$error['customer'] = false; 
		break;
		
		case 36: 
			$error['admin'] = "OLTP Transaction failed";
			$error['customer'] = false; 
		break;
		
		case 37: 
			$error['admin'] = "Invalid recurring profile ID";
			$error['customer'] = false; 
		break;
		
		case 50: 
			$error['admin'] = "Insufficient funds available in account";
			$error['customer'] = false; 
		break;
		
		case 51: 
			$error['admin'] = "Exceeds per transaction limit";
			$error['customer'] = false; 
		break;
		
		case 99: 
			$error['admin'] = "General error. See RESPMSG.";
			$error['customer'] = false; 
		break;
		
		case 100: 
			$error['admin'] = "Transaction type not supported by host";
			$error['customer'] = false; 
		break;
		
		case 101: 
			$error['admin'] = "Time-out value too small";
			$error['customer'] = false; 
		break;
		
		case 102: 
			$error['admin'] = "Processor not available";
			$error['customer'] = false; 
		break;
		
		case 103: 
			$error['admin'] = "Error reading response from host";
			$error['customer'] = false; 
		break;
		
		case 104: 
			$error['admin'] = "Timeout waiting for processor response. Try your transaction again.";
			$error['customer'] = false; 
		break;
		
		case 105: 
			$error['admin'] = "Credit error. Make sure you have not already credited this transaction, or that this 
		transaction ID is for a creditable transaction. (For example, you cannot credit an 
		authorisation.)";
			$error['customer'] = false; 
		break;
		
		case 106: 
			$error['admin'] = "Host not available";
			$error['customer'] = false;
		break;
		
		case 107: 
			$error['admin'] = "Duplicate suppression time-out";
			$error['customer'] = false; 
		break;
		
		case 108: 
			$error['admin'] = "Void error. See RESPMSG. Make sure the transaction ID entered has not already been 
		voided. If not, then look at the Transaction Detail screen for this transaction to see if it 
		has settled. (The Batch field is set to a number greater than zero if the transaction has 
		been settled.) If the transaction has already settled, your only recourse is a reversal 
		(credit a payment or submit a payment for a credit).";
			$error['customer'] = false; 
		break;
		
		case 109: 
			$error['admin'] = "Time-out waiting for host response";
			$error['customer'] = false; 
		break;
		
		case 110: 
			$error['admin'] = "Referenced auth (against order) Error";
			$error['customer'] = false; 
		break;
		
		case 111: 
			$error['admin'] = "Capture error. Either an attempt to capture a transaction that is not an authorisation 
		transaction type, or an attempt to capture an authorisation transaction that has already 
		been captured.";
			$error['customer'] = false; 
		break;
		
		case 112: 
			$error['admin'] = "Failed AVS check. Address and ZIP code do not match. An authorisation may still 
		exist on the cardholder’s account.";
			$error['customer'] = "Failed AVS check. The address and zip/postcode does not match the card holders account details.";
		break;
		
		case 113: 
			$error['admin'] = "Merchant sale total will exceed the sales cap with current transaction. ACH 
		transactions only.";
			$error['customer'] = false; 
		break;
		
		case 114: 
			$error['admin'] = "Card Security Code (CSC) Mismatch. An authorisation may still exist on the 
		cardholder’s account.";
			$error['customer'] = "The CVV2 security code does not match. Please try again."; 
		break;
		
		case 115: 
			$error['admin'] = "System busy, try again later";
			$error['customer'] = false; 
		break;
		
		case 116: 
			$error['admin'] = "VPS Internal error. Failed to lock terminal number";
			$error['customer'] = false; 
		break;
		
		case 117: 
			$error['admin'] = "Failed merchant rule check. One or more of the following three failures occurred: 
		An attempt was made to submit a transaction that failed to meet the security settings 
		specified on the PayPal Manager Security Settings page. If the transaction exceeded the 
		Maximum Amount security setting, then no values are returned for AVS or CSC. 
		AVS validation failed. The AVS return value should appear in the RESPMSG. 
		CSC validation failed. The CSC return value should appear in the RESPMSG.";
			$error['customer'] = false; 
		break;
		
		case 118: 
			$error['admin'] = "Invalid keywords found in string fields";
			$error['customer'] = false; 
		break;
		
		case 119: 
			$error['admin'] = "General failure within PIM Adapter";
			$error['customer'] = false; 
		break;
		
		case 120: 
			$error['admin'] = "Attempt to reference a failed transaction";
			$error['customer'] = false; 
		break;
		
		case 121: 
			$error['admin'] = "Not enabled for feature";
			$error['customer'] = false; 
		break;
		
		case 122: 
			$error['admin'] = "Merchant sale total will exceed the credit cap with current transaction. ACH 
		transactions only.";
			$error['customer'] = false;
		break;
		
		case 125: 
			$error['admin'] = "Fraud Protection Services Filter — Declined by filters";
			$error['customer'] = false; 
		break;
		
		case 126: 
			$error['admin'] = "Fraud Protection Services Filter — Flagged for review by filters 
		Important Note: Result code 126 indicates that a transaction triggered a fraud filter. 
		This is not an error, but a notice that the transaction is in a review status. The 
		transaction has been authorised but requires you to review and manually accept the 
		transaction before it will be allowed to settle. 
		Result code 126 is intended to give you an idea of the kind of transaction that is 
		considered suspicious to enable you to evaluate whether you can benefit from using the 
		Fraud Protection Services. 
		To eliminate result 126, turn the filters off. 
		For more information, see the Fraud Protection Services documentation for your 
		payments solution. It is available on the PayPal Manager Documentation page.";
			$error['customer'] = false; 
		break;
		
		case 127: 
			$error['admin'] = "Fraud Protection Services Filter — Not processed by filters";
			$error['customer'] = false; 
		break;
		
		case 128: 
			$error['admin'] = "Fraud Protection Services Filter — Declined by merchant after being flagged for 
		review by filters";
			$error['customer'] = false; 
		break;
		
		case 131: 
			$error['admin'] = "Version 1 Website Payments Pro SDK client no longer supported. Upgrade to the 
		most recent version of the Website Payments Pro client.";
			$error['customer'] = false; 
		break;
		
		case 132: 
			$error['admin'] = "Card has not been submitted for update";
			$error['customer'] = false; 
		break;
		
		case 133: 
			$error['admin'] = "Data mismatch in HTTP retry request";
			$error['customer'] = false; 
		break;
		
		case 150: 
			$error['admin'] = "Issuing bank timed out";
			$error['customer'] = false; 
		break;
		
		case 151: 
			$error['admin'] = "Issuing bank unavailable";
			$error['customer'] = false; 
		break;
		
		case 200: 
			$error['admin'] = "Reauth error";
			$error['customer'] = false; 
		break;
		
		case 201: 
			$error['admin'] = "Order error";
			$error['customer'] = false; 
		break;
		
		case 402: 
			$error['admin'] = "PIM Adapter Unavailable";
			$error['customer'] = false; 
		break;
		
		case 403: 
			$error['admin'] = "PIM Adapter stream error";
			$error['customer'] = false; 
		break;
		
		case 404: 
			$error['admin'] = "PIM Adapter Timeout";
			$error['customer'] = false; 
		break;
		
		case 600: 
			$error['admin'] = "Cybercash Batch Error";
			$error['customer'] = false; 
		break;
		
		case 601: 
			$error['admin'] = "Cybercash Query Error";
			$error['customer'] = false; 
		break;
		
		case 1000: 
			$error['admin'] = "Generic host error. This is a generic message returned by your credit card processor. 
		The RESPMSG will contain more information describing the error.";
			$error['customer'] = false;
		break;
		
		case 1001: 
			$error['admin'] = "Buyer Authentication Service unavailable";
			$error['customer'] = false; 
		break;
		
		case 1002: 
			$error['admin'] = "Buyer Authentication Service — Transaction timeout";
			$error['customer'] = false; 
		break;
		
		case 1003: 
			$error['admin'] = "Buyer Authentication Service — Invalid client version";
			$error['customer'] = false; 
		break;
		
		case 1004: 
			$error['admin'] = "Buyer Authentication Service — Invalid timeout value";
			$error['customer'] = false; 
		break;
		
		case 1011: 
			$error['admin'] = "Buyer Authentication Service unavailable";
			$error['customer'] = false; 
		break;
		
		case 1012: 
			$error['admin'] = "Buyer Authentication Service unavailable";
			$error['customer'] = false; 
		break;
		
		case 1013: 
			$error['admin'] = "Buyer Authentication Service unavailable";
			$error['customer'] = false; 
		break;
		
		case 1014: 
			$error['admin'] = "Buyer Authentication Service — Merchant is not enrolled for Buyer 
		Authentication Service (3-D Secure).";
			$error['customer'] = false; 
		break;
		
		case 1016: 
			$error['admin'] = "Buyer Authentication Service — 3-D Secure error response received. Instead of 
		receiving a PARes response to a Validate Authentication transaction, an error response 
		was received.";
			$error['customer'] = false; 
		break;
		
		case 1017: 
			$error['admin'] = "Buyer Authentication Service — 3-D Secure error response is invalid. An error 
		response is received and the response is not well formed for a Validate Authentication 
		transaction.";
			$error['customer'] = false; 
		break;
		
		case 1021: 
			$error['admin'] = "Buyer Authentication Service — Invalid card type";
			$error['customer'] = false; 
		break;
		
		case 1022: 
			$error['admin'] = "Buyer Authentication Service — Invalid or missing currency code";
			$error['customer'] = false; 
		break;
		
		case 1023: 
			$error['admin'] = "Buyer Authentication Service — merchant status for 3D secure is invalid";
			$error['customer'] = false; 
		break;
		
		case 1041: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: missing or 
		invalid PARES";
			$error['customer'] = false; 
		break;
		
		case 1042: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: PARES format is 
		invalid";
			$error['customer'] = false; 
		break;
		
		case 1043: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Cannot find 
		successful Verify Enrolment";
			$error['customer'] = false; 
		break;
		
		case 1044: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Signature 
		validation failed for PARES";
			$error['customer'] = false; 
		break;
		
		case 1045: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid amount in PARES";
			$error['customer'] = false; 
		break;
		
		case 1046: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid acquirer in PARES";
			$error['customer'] = false; 
		break;
		
		case 1047: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid Merchant ID in PARES";
			$error['customer'] = false; 
		break;
		
		case 1048: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid card number in PARES";
			$error['customer'] = false; 
		break;
		
		case 1049: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid currency code in PARES";
			$error['customer'] = false;
		break;
		
		case 1050: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid XID in PARES";
			$error['customer'] = false;
		break;
		
		case 1051: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: Mismatched or 
		invalid order date in PARES";
			$error['customer'] = false; 
		break;
		
		case 1052: 
			$error['admin'] = "Buyer Authentication Service — Validate Authentication failed: This PARES was 
		already validated for a previous Validate Authentication transaction";
			$error['customer'] = false;
		break;
		
		default:
			$error['admin'] = "No error code returned by PayPal. I have no idea what is going on.";
			$error['customer'] = false;
		break;
	}
	
	if($error['customer'] == false) {
		$error['customer'] = "This transaction is pending approval. A member of staff should contact you shortly with further advice. If you have any questions concerning this message or if you do not hear back from a staff member within a reasonable time please contact us quoting your order number.";
	}
	
	$error['paypal'] = $resArray["RESPMSG"];
	
	return $error;
}
?>