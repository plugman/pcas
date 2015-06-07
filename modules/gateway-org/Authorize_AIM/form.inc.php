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
|	form.inc.php
|   ========================================
|	Authorize AIM Payment 
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
if(detectSSL() == FALSE) die ("<strong>Critical Error:</strong> This page can ONLY be viewed under SSL!");

if($_GET['process']==1) {

	$transData['customer_id'] = $orderSum["customer_id"];
	$transData['order_id'] = $orderSum['cart_order_id'];
	$transData['amount'] = $orderSum['prod_total'];
	$transData['gateway'] = "Authorize AIM";

	// first check card
	require("classes".CC_DS."validate".CC_DS."validateCard.php");
	
	$card = new validateCard();
	
	$cardNo			= trim($_POST["cardNumber"]);
	$issueNo		= false;
	$issueDate		= false; 
	$issueFormat	= 4; 
	$expireDate		= trim($_POST["expirationYear"]).str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT);  
	$expireFormat	= 4; 
	if($module['reqCvv']==1) {
		$scReqd			= TRUE;
	} else {
		$scReqd			= FALSE;
	} 
	$securityCode	= trim($_POST["cvv"]);
	
	$card = $card->check($cardNo, 
						$issueNo, 
						$issueDate, 
						$issueFormat, 
						$expireDate, 
						$expireFormat, 
						$scReqd, 
						$securityCode);

	if($module['validation']==1 && $card['response']=="FAIL") {
		
		$errorMsg = "";
		
		foreach($card['error'] as $val) {
			$errorMsg .= $val."<br />";
		}
		
		$transData['trans_id'] = "";
		$transData['status'] = "Fail";
		$transData['notes'] = $errorMsg;

	} else {

		$debug = "<table border=\"0\" cellpadding=\"3\" width=\"600\">
			<tr>
				<td>
		
		
		<b>Authorize.Net<br />
		Advanced Implementation Method (AIM)<br />
		<br />
		<strong>Debug Info:</strong>
		
				</td>
			</tr>
			<tr>
				<td>";
		
		$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur
		
		if($module['testMode'] == 1) {
			//$auth_net_url = "https://certification.authorize.net/gateway/transact.dll";
			$auth_net_url 	= "https://test.authorize.net/gateway/transact.dll";
			$x_test_request = 'TRUE';				# Set the testing flag so that transactions are not live
		} else {
			$auth_net_url = "https://secure.authorize.net/gateway/transact.dll";
			$x_test_request = 'FALSE';
		}
		
		$authnet_values				= array (
			"x_test_request"		=> $x_test_request,
			"x_password"			=> $module['password'],
			"x_login"				=> $module['acNo'],
			"x_tran_key"			=> $module['txnkey'],
			"x_invoice_num"			=> $orderSum['cart_order_id'],
			"x_version"				=> "3.1",
			"x_delim_char"			=> "|",
			"x_delim_data"			=> "TRUE",
			"x_url"					=> "FALSE",
			"x_type"				=> "AUTH_CAPTURE",
			"x_method"				=> "CC",
			"x_relay_response"		=> "FALSE",
			"x_card_num"			=> trim($_POST["cardNumber"]),
			"x_card_code"			=> trim($_POST['cvv']),
			"x_exp_date"			=> trim($_POST["expirationMonth"]).trim($_POST["expirationYear"]),
			"x_description"			=> "Payment for order number ".$orderSum['cart_order_id'],
			"x_amount"				=> $orderSum['prod_total'],
			"x_first_name"			=> trim($_POST["firstName"]),
			"x_last_name"			=> trim($_POST["lastName"]),
			"x_address"				=> trim($_POST["addr1"]).' '.trim($_POST["addr2"]),
			"x_city"				=> trim($_POST["city"]),
			"x_state"				=> trim($_POST["state"]),
			"x_zip"					=> trim($_POST["postalCode"]),
			"x_country"				=> trim($_POST["country"]),
			"x_email"				=> trim($_POST['emailAddress']),
			"x_customer_ip" 		=> get_ip_address()
		);
		
		$fields = "";
		foreach( $authnet_values as $key => $value ) $fields .= $key."=" . urlencode( $value ) . "&";
		
		
		$debug .= "<hr />";
		///////////////////////////////////////////////////////////
		
		$debug .= "<b>01: Post the transaction (see the code for specific information):</b><br />";
		
		
		$ch = curl_init($auth_net_url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		if($config['proxy']==1) {
	  		curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
	  	}
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		
		$debug .= "<hr />";
		///////////////////////////////////////////////////////////
		
		$debug .= "<b>02: Get post results:</b><br />";
		$debug .= $resp;
		$debug .= "<br />";
		
		$debug .= "<hr />";
		///////////////////////////////////////////////////////////
		
		$debug .= "03: Parse post results (simple approach)<br />";
		
		$text = $resp;
		
		$debug .= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"1\">";
			$debug .= "<tr>";
				$debug .= "<td >";
		
		
		$tok = strtok($text,"|");
		while(!($tok === FALSE)) {
		//while ($tok) {
			$debug .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$tok."<br />";
			$tok = strtok("|");
		}
		
		
				$debug .= "</td>";
			$debug .= "</tr>";
		$debug .= "</table>";
		
		
		$debug .= "<hr />";
		///////////////////////////////////////////////////////////
		
		$debug .= "<b>04: Parse the results string into individual, meaningful segments:</b><br />";
		
		
		$debug .= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"1\">";
		
		///////////////////////////////////////////////////////////
		//  STATISTICAL USE ONLY:                                //
		///////////////////////////////////////////////////////////
		
			$debug .= "<tr>";
				$debug .= "<td>";
				$debug .= "Length of the returned string from Authorize.Net:";
				$debug .= "</td>";
		
				$debug .= "<td>";
				$debug .= strlen($resp);
				$debug .= "</td>";
		
			$debug .= "</tr>";
		
		$howMany = substr_count($resp, "|");
		
			$debug .= "<tr>";
				$debug .= "<td>";
				$debug .= "Number of delimiter characters in the returned string:";
				$debug .= "</td>";
		
				$debug .= "<td>";
				$debug .= $howMany;
				$debug .= "</td>";
		
			$debug .= "</tr>";
		///////////////////////////////////////////////////////////
		
		
		
		$text = $resp;
		$h = substr_count($text, "|");
		$h++;
		
			for($j=1; $j <= $h; $j++) {
		
			$p = strpos($text, "|");
		
			if ($p === false) { // note: three equal signs
		
				$debug .= "<tr>";
				$debug .= "<td>";
		
					//  x_delim_char is obviously not found in the last go-around
		
					if($j>=69) {
		
						$debug .= "Merchant-defined (".$j."): ";
						$debug .= ": ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $text;
						$debug .= "<br />";
		
					} else {
		
						$debug .= $j;
						$debug .= ": ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $text;
						$debug .= "<br />";
		
					}
		
		
				$debug .= "</td>";
				$debug .= "</tr>";
		
			} else {
		
				$p++;
		
				//  We found the x_delim_char and accounted for it . . . now do something with it
		
				//  get one portion of the response at a time
				$pstr = substr($text, 0, $p);
		
				//  this prepares the text and returns one value of the submitted
				//  and processed name/value pairs at a time
				//  for AIM-specific interpretations of the responses
				//  please consult the AIM Guide and look up
				//  the section called Gateway Response API
				$pstr_trimmed = substr($pstr, 0, -1); // removes "|" at the end
		
				if($pstr_trimmed=="") {
					$pstr_trimmed="NO VALUE RETURNED";
				}
		
		
				$debug .= "<tr>";
				$debug .= "<td>";
				
				switch($j) {
		
					case 1:
						$debug .= "Response Code: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$fval="";
						
						if($pstr_trimmed=="1") {
							$fval="Approved";
							$statusLog = $fval;
							$order->orderStatus(3,$orderSum['cart_order_id']);
							$jumpTo = "index.php?_g=co&_a=confirmed&s=2";
							$errorMsg = "Card has been charged.";
							
						} elseif($pstr_trimmed=="2") {
							$fval="Declined";
							$statusLog = $fval;
							$errorMsg = $lang['gateway']['card_declined'];
				
						} elseif($pstr_trimmed=="3") {
							$fval="Error";
							$statusLog = $fval;
							$errorMsg = $lang['gateway']['card_error'];
						}
		
						$debug .= $fval;
						$debug .= "<br />";
						break;
						
					case 2:
						$debug .= "Response Subcode: ";
						
						$debug .= "</td>";
						$debug .= "<td >";
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 3:
						$debug .= "Response Reason Code: ";
						
						$debug .= "</td>";
						$debug .= "<td >";
						$debug .= $pstr_trimmed;
						$errorMsg .= "<br />Error code ".$pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 4:
						$debug .= "Response Reason Text: ";
						
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$errorMsg .= " - &quot;".$pstr_trimmed."&quot;";
						$debug .= "<br />";
						break;
		
					case 5:
						$debug .= "Approval Code: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 6:
						$debug .= "AVS Result Code: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 7:
						$debug .= "Transaction ID: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$transactionId = $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 8:
						$debug .= "Invoice Number (x_invoice_num): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 9:
						$debug .= "Description (x_description): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 10:
						$debug .= "Amount (x_amount): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 11:
						$debug .= "Method (x_method): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 12:
						$debug .= "Transaction Type (x_type): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 13:
						$debug .= "Customer ID (x_cust_id): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 14:
						$debug .= "Cardholder First Name (x_first_name): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 15:
						$debug .= "Cardholder Last Name (x_last_name): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 16:
						$debug .= "Company (x_company): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 17:
						$debug .= "Billing Address (x_address): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 18:
						$debug .= "City (x_city): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 19:
						$debug .= "State (x_state): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 20:
						$debug .= "ZIP (x_zip): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 21:
						$debug .= "Country (x_country): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 22:
						$debug .= "Phone (x_phone): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 23:
						$debug .= "Fax (x_fax): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 24:
						$debug .= "E-Mail Address (x_email): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 25:
						$debug .= "Ship to First Name (x_ship_to_first_name): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 26:
						$debug .= "Ship to Last Name (x_ship_to_last_name): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 27:
						$debug .= "Ship to Company (x_ship_to_company): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 28:
						$debug .= "Ship to Address (x_ship_to_address): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 29:
						$debug .= "Ship to City (x_ship_to_city): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 30:
						$debug .= "Ship to State (x_ship_to_state): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 31:
						$debug .= "Ship to ZIP (x_ship_to_zip): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 32:
						$debug .= "Ship to Country (x_ship_to_country): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 33:
						$debug .= "Tax Amount (x_tax): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 34:
						$debug .= "Duty Amount (x_duty): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 35:
						$debug .= "Freight Amount (x_freight): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 36:
						$debug .= "Tax Exempt Flag (x_tax_exempt): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 37:
						$debug .= "PO Number (x_po_num): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 38:
						$debug .= "MD5 Hash: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					case 39:
						$debug .= "Card Code Response: ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$fval="";
						if($pstr_trimmed=="M"){
							$fval="M = Match";
						}elseif($pstr_trimmed=="N"){
							$fval="N = No Match";
						}elseif($pstr_trimmed=="P"){
							$fval="P = Not Processed";
						}elseif($pstr_trimmed=="S"){
							$fval="S = Should have been present";
						}elseif($pstr_trimmed=="U"){
							$fval="U = Issuer unable to process request";
						}else{
							$fval="NO VALUE RETURNED";
						}
		
						$debug .= $fval;
						$debug .= "<br />";
						break;
		
					case 40:
					case 41:
					case 42:
					case 43:
					case 44:
					case 45:
					case 46:
					case 47:
					case 48:
					case 49:
					case 50:
					case 51:
					case 52:
					case 53:
					case 54:
					case 55:
					case 55:
					case 56:
					case 57:
					case 58:
					case 59:
					case 60:
					case 61:
					case 62:
					case 63:
					case 64:
					case 65:
					case 66:
					case 67:
					case 68:
						$debug .= "Reserved (".$j."): ";
		
						$debug .= "</td>";
						$debug .= "<td >";
		
						$debug .= $pstr_trimmed;
						$debug .= "<br />";
						break;
		
					default:
		
						if($j>=69) {
		
							$debug .= "Merchant-defined (".$j."): ";
							$debug .= ": ";
		
							$debug .= "</td>";
							$debug .= "<td >";
		
							$debug .= $pstr_trimmed;
							$debug .= "<br />";
		
						} else {
		
							$debug .= $j;
							$debug .= ": ";
		
							$debug .= "</td>";
							$debug .= "<td >";
		
							$debug .= $pstr_trimmed;
							$debug .= "<br />";
		
						}
		
						break;
		
				}
		
				$debug .= "</td>";
				$debug .= "</tr>";
		
				// remove the part that we identified and work with the rest of the string
				$text = substr($text, $p);
		
			}
		
		}
		
		$debug .= "</table>";
		
		$debug .= "<br />";
		
		
		
		
		$debug .= "<hr />";
		///////////////////////////////////////////////////////////
	
		if(!empty($transactionId)) {
			$transData['trans_id'] = $transactionId;
		} else {
			$transData['trans_id'] = "N/A";
		}
		$transData['status'] = $statusLog;
		$transData['notes'] = $errorMsg;

	}
	
	$order->storeTrans($transData);
	
	if($module['debug'] == 1 && isset($debug)) {
		$debug .= "<b>04: Done.</b><br /></td>
			</tr>
		</table>";
		echo $debug;
		//exit;
	} elseif(isset($jumpTo) && !empty($jumpTo)) {
		httpredir($jumpTo);
	}
	
	unset($debug);
	
}


$formTemplate = new XTemplate ("modules".CC_DS."gateway".CC_DS.$_POST['gateway'].CC_DS."form.tpl",'',null,'main',true,$skipPath=TRUE);

if(isset($errorMsg)) {
	
	$formTemplate->assign("LANG_ERROR",$errorMsg);
	$formTemplate->parse("form.error");

}
	
$billingName = makeName($orderSum['name']);
$deliveryName = makeName($orderSum['name_d']);

$formTemplate->assign("VAL_AMOUNT_DUE",sprintf($lang['gateway']['amount_due'],priceformat($orderSum['prod_total'],true)));
$formTemplate->assign("VAL_FIRST_NAME",$billingName[2]);
$formTemplate->assign("VAL_LAST_NAME",$billingName[3]);
$formTemplate->assign("VAL_EMAIL_ADDRESS",$orderSum['email']);
$formTemplate->assign("VAL_ADD_1",$orderSum['add_1']);
$formTemplate->assign("VAL_ADD_2",$orderSum['add_2']);
$formTemplate->assign("VAL_CITY",$orderSum['town']);
$formTemplate->assign("VAL_COUNTY",$orderSum['county']);
$formTemplate->assign("VAL_POST_CODE",$orderSum['postcode']);

$countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
	
	for($i=0; $i<count($countries); $i++) {
				
		if($countries[$i]['id'] == $orderSum['country']) {
			$formTemplate->assign("COUNTRY_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("COUNTRY_SELECTED","");
		}
	
		$formTemplate->assign("VAL_COUNTRY_ISO",$countries[$i]['iso']);

		$countryName = "";
		$countryName = $countries[$i]['printable_name'];

		if(strlen($countryName)>20) {
			$countryName = substr($countryName,0,20)."&hellip;";
		}

		$formTemplate->assign("VAL_COUNTRY_NAME",$countryName);
		$formTemplate->parse("form.repeat_countries");
	}
	
	$formTemplate->assign("LANG_CC_INFO_TITLE",$lang['gateway']['cc_info_title']);
	$formTemplate->assign("LANG_FIRST_NAME",$lang['gateway']['first_name']); 
	$formTemplate->assign("LANG_LAST_NAME",$lang['gateway']['last_name']); 
	//$formTemplate->assign("LANG_CARD_TYPE",$lang['gateway']['card_type']);
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	
	if($module['reqCvv']==1) {
		$formTemplate->assign("LANG_CVV",$lang['gateway']['security_code']);
		$formTemplate->parse("form.cvv");
	}
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['expires']);
	$formTemplate->assign("LANG_MMYYYY",$lang['gateway']['mmyyyy']);
	$formTemplate->assign("LANG_SECURITY_CODE",$lang['gateway']['security_code']);
	$formTemplate->assign("LANG_CUST_INFO_TITLE",$lang['gateway']['customer_info']);
	$formTemplate->assign("LANG_EMAIL",$lang['gateway']['email']);
	$formTemplate->assign("LANG_ADDRESS",$lang['gateway']['address']);
	$formTemplate->assign("LANG_CITY",$lang['gateway']['city']);
	$formTemplate->assign("LANG_STATE",$lang['gateway']['state']);
	$formTemplate->assign("LANG_ZIPCODE",$lang['gateway']['zipcode']);
	$formTemplate->assign("LANG_COUNTRY",$lang['gateway']['country']);
	$formTemplate->assign("LANG_OPTIONAL",$lang['gateway']['optional']);
	
	$formTemplate->assign("VAL_GATEWAY",sanitizeVar($_POST['gateway']));


$formTemplate->parse("form");
$formTemplate = $formTemplate->text("form");
?>