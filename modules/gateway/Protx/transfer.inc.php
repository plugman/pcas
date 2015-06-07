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
|	transfer.inc.php
|   ========================================
|	Core functions for the sagepay.com Gateway	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
/* Base 64 Encoding function **
** PHP does it natively but just for consistency and ease of maintenance, let's declare our own function **/

function base64Encode($plain) {
  // Initialise output variable
  $output = "";
  
  // Do encoding
  $output = base64_encode($plain);
  
  // Return the result
  return $output;
}

/* Base 64 decoding function **
** PHP does it natively but just for consistency and ease of maintenance, let's declare our own function **/

function base64Decode($scrambled) {
  // Initialise output variable
  $output = "";
  
  // Fix plus to space conversion issue
  $scrambled = str_replace(" ","+",$scrambled);
  
  // Do encoding
  $output = base64_decode($scrambled);
  
  // Return the result
  return $output;
}


/*  The SimpleXor encryption algorithm                                                                                **
**  NOTE: This is a placeholder really.  Future releases of VSP Form will use AES or TwoFish.  Proper encryption      **
**       This simple function and the Base64 will deter script kiddies and prevent the "View Source" type tampering    **
**      It won't stop a half decent hacker though, but the most they could do is change the amount field to something **
**      else, so provided the vendor checks the reports and compares amounts, there is no harm done.  It's still      **
**      more secure than the other PSPs who don't both encrypting their forms at all                                  */
/* NO ASCII
function simpleXor($data, $key) {
    $output = "";

    for($i = 0; $i < strlen($data); ) {
         for($j = 0; $j < strlen($key); $j++, $i++) {
             if($i < strlen($data))
                 $output .= $data[$i] ^ $key[$j];
             else
                 break;
         }
    }

    return $output;
}
*/
function simpleXor($InString, $Key) {
  // Initialise key array
  $KeyList = array();
  // Initialise out variable
  $output = "";
  
  // Convert $Key into array of ASCII values
  for($i = 0; $i < strlen($Key); $i++){
    $KeyList[$i] = ord(substr($Key, $i, 1));
  }

  // Step through string a character at a time
  for($i = 0; $i < strlen($InString); $i++) {
    // Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
    // % is MOD (modulus), ^ is XOR
    $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
  }

  // Return the result
  return $output;
}

/* The getToken function.                                                                                         **
** NOTE: A function of convenience that extracts the value from the "name=value&name2=value2..." VSP reply string **
**     Works even if one of the values is a URL containing the & or = signs.                                      */

function getToken($thisString) {

  // List the possible tokens
  $Tokens = array(
    "Status",
    "StatusDetail",
    "VendorTxCode",
    "VPSTxId",
    "TxAuthNo",
    "Amount",
    "AVSCV2", 
    "AddressResult", 
    "PostCodeResult", 
    "CV2Result", 
    "GiftAid", 
    "3DSecureStatus", 
    "CAVV" );

  // Initialise arrays
  $output = array();
  $resultArray = array();
  
  // Get the next token in the sequence
  for ($i = count($Tokens)-1; $i >= 0 ; $i--){
    // Find the position in the string
    $start = strpos($thisString, $Tokens[$i]);
	// If it's present
    if ($start !== false){
      // Record position and token name
      $resultArray[$i]->start = $start;
      $resultArray[$i]->token = $Tokens[$i];
    }
  }
  
  // Sort in order of position
  sort($resultArray);
	// Go through the result array, getting the token values
  for ($i = 0; $i<count($resultArray); $i++){
    // Get the start point of the value
    $valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;
	// Get the length of the value
    if ($i==(count($resultArray)-1)) {
      $output[$resultArray[$i]->token] = substr($thisString, $valueStart);
    } else {
      $valueLength = $resultArray[$i+1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
	  $output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
    }      

  }

  // Return the ouput array
  return $output;
}

// Randomise based on time
function randomise() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

/////////////////////////////////////////////////////////
///////////////   END OF sagepay.com FUNCTIONS  ///////////////
/////////////////////////////////////////////////////////

function repeatVars() {

	return FALSE;
}

function fixedVars() {
	
	global $module, $orderSum, $config;
	
	$VendorTxCode = 'CC4'.(rand(0,32000)*rand(0,32000));
	
	$invName 	= makeName($orderSum['name']);
	$delName 	= makeName($orderSum['name_d']);
	$invCountry = getCountryFormat($orderSum['country'],'id','iso');
	$delCountry = getCountryFormat($orderSum['country_d'],'printable_name','iso');
	
	$cryptVars = 
	"VendorTxCode=".$VendorTxCode
	."&Amount=".$orderSum['prod_total']
	."&Currency=".$config['defaultCurrency']
	."&Description=Cart - ".$orderSum['cart_order_id']
	."&CustomerEmail=".$orderSum['email']
	."&CustomerName=".$orderSum['name']
	."&VendorEmail=".$config['masterEmail']
	
	/* not longer used in 2.23
	."&DeliveryAddress=".$delAdd 
	."&BillingAddress=".$invAdd
	."&ContactNumber=".$orderSum['phone']
	*/
	
	."&ApplyAVSCV2=0&Apply3DSecure=0"
	."&SuccessURL=".$GLOBALS['storeURL']."/index.php?_g=rm&type=gateway&cmd=process&module=Protx&cart_order_id=".$orderSum['cart_order_id']
	."&FailureURL=".$GLOBALS['storeURL']."/index.php?_g=rm&type=gateway&cmd=process&module=Protx&cart_order_id=".$orderSum['cart_order_id']
	
	## New required fields for 2.23
	."&BillingSurname=".$invName[3]
	."&BillingFirstnames=".$invName[2]
	."&BillingAddress1=".$orderSum['add_1']
	."&BillingAddress2=".$orderSum['add_2'] // optional
	."&BillingCity=".$orderSum['town']
	."&BillingCountry=".$invCountry;
	/*
	if($invCountry=="US") {
		$cryptVars.="&BillingState=".$orderSum['county']; // optional
	}
	*/
	$cryptVars.="&BillingPostCode=".$orderSum['postcode']
	."&BillingPhone=".$orderSum['phone'] // optional
	."&DeliverySurname=".$delName[3] 
	."&DeliveryFirstnames=".$delName[2]
	."&DeliveryAddress1=".$orderSum['add_1_d']
	."&DeliveryAddress2=".$orderSum['add_2_d'] // optinal
	."&DeliveryCity=".$orderSum['town_d']
	."&DeliveryPostCode=".$orderSum['postcode_d']
	."&DeliveryCountry=".$delCountry;
	/*
	if($delCountry=="US") {
		$cryptVars.="&DeliveryState=".$orderSum['county_d']; // optional
	}
	*/
	$cryptVars.="&DeliveryPhone=".$orderSum['phone'] // optional
	."&Basket=" // optional
	."&AllowGiftAid=0" // optional
	."&SendEMail=1"
	."&EMailMessage=" // optional
	."&Refferid={32839EA8-8935-49A4-95FB-369E755B632C}"; 
	
	$encrypted = base64Encode(SimpleXor($cryptVars,trim($module['passphrase'])));
	
	$hiddenVars = "<input type='hidden' name='VendorTxCode' value='".$VendorTxCode."' /> 
		<input type='hidden' name='VPSProtocol' value='2.23' />
		<input type='hidden' name='TxType' value='PAYMENT' />
		<input type='hidden' name='Vendor' value='".trim($module['acNo'])."' />
		<input type='hidden' name='Crypt' value='".$encrypted."' />";
	
	return $hiddenVars;	
}

///////////////////////////
// Other Vars
////////

if($module['gate'] == "sim") {
	$formAction = "https://test.sagepay.com/Simulator/VSPFormGateway.asp";
} elseif($module['gate'] == "test") {
	$formAction ="https://test.sagepay.com/gateway/service/vspform-register.vsp";
} elseif($module['gate'] == "live"){
	$formAction ="https://live.sagepay.com/gateway/service/vspform-register.vsp";
}

$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";
?>
