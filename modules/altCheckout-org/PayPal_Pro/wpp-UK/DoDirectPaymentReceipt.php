<?php
if (!defined('CC_INI_SET')) die("Access Denied");

require_once 'CallerService.php';

$AMT = sprintf("%.2f",$orderSum['prod_total']);
$ACCTTYPE = trim($_POST['cardType']);
$ACCT = trim($_POST['cardNumber']);
$EXPDATE = str_pad(trim($_POST['expirationMonth']), 2, '0', STR_PAD_LEFT).substr(trim($_POST['expirationYear']),2,2);
$CVV2 = trim($_POST['cvc2']);
$INVNUM = $orderSum['cart_order_id'];
$EMAIL = $orderSum['email'];
$FIRSTNAME = trim($_POST['firstName']);
$LASTNAME = trim($_POST['lastName']);
$STREET = trim($_POST['addr1']." ".$_POST['addr2']);
$CITY = trim($_POST['city']);
$STATE = $processState;
$ZIP = trim($_POST['postalCode']);
$COUNTRY = $countryIso; // specified in form.inc.php
$CURRENCY = $config['defaultCurrency'];
$CLIENTIP = get_ip_address();

$CARDSTART = str_pad(trim($_POST['issueMonth']), 2, '0', STR_PAD_LEFT).substr(trim($_POST['issueYear']),2,2);
$CARDISSUE = trim($_POST['issueNo']);

$AUTHSTATUS3D = $_SESSION['centinel']['AUTHSTATUS3D'];
$MPIVENDOR3DS = $_SESSION['centinel']['MPIVENDOR3DS'];
$CAVV = $_SESSION['centinel']['CAVV'];
$ECI = $_SESSION['centinel']['ECI'];
$XID = $_SESSION['centinel']['XID'];


$nvpStr =
"&AMT[".strlen($AMT)."]=".$AMT
."&ACCTTYPE[".strlen($ACCTTYPE)."]=".$ACCTTYPE
."&ACCT[".strlen($ACCT)."]=".$ACCT
."&EXPDATE[".strlen($EXPDATE)."]=".$EXPDATE
."&CVV2[".strlen($CVV2)."]=".$CVV2
."&INVNUM[".strlen($INVNUM)."]=".$INVNUM
."&EMAIL[".strlen($EMAIL)."]=".$EMAIL
."&FIRSTNAME[".strlen($FIRSTNAME)."]=".$FIRSTNAME
."&LASTNAME[".strlen($LASTNAME)."]=".$LASTNAME
."&STREET[".strlen($STREET)."]=".$STREET
."&CITY[".strlen($CITY)."]=".$CITY
."&STATE[".strlen($STATE)."]=".$STATE
."&ZIP[".strlen($ZIP)."]=".$ZIP
."&COUNTRY[".strlen($COUNTRY)."]=".$COUNTRY
."&CURRENCY[".strlen($CURRENCY)."]=".$CURRENCY
."&CLIENTIP[".strlen($CLIENTIP)."]=".$CLIENTIP;

if ((bool)$module['3ds_status']) {
	$nvpStr	.= "&AUTHSTATUS3D[".strlen($AUTHSTATUS3D)."]=".$AUTHSTATUS3D
	."&MPIVENDOR3DS[".strlen($MPIVENDOR3DS)."]=".$MPIVENDOR3DS
	."&CAVV[".strlen($CAVV)."]=".$CAVV
	."&ECI[".strlen($ECI)."]=".$ECI
	."&XID[".strlen($XID)."]=".$XID;
}

// start && || issue for Solo/Maestro
if($_POST['cardType'] == "9" || $_POST['cardType'] == "S") {
	$nvpStr .= "&CARDISSUE[".strlen($CARDISSUE)."]=".$CARDISSUE
	."&CARDSTART[".strlen($CARDSTART)."]=".$CARDSTART;
}

$BUTTONSOURCE = "ImeiUnlock_Cart_PRO2DP";

$nvpStr .="&BUTTONSOURCE[".strlen($BUTTONSOURCE)."]=".$BUTTONSOURCE;

$TRXTYPE = $module['paymentAction'] == "Sale" ? "S" : "A";

$request_id = md5($_GET['cart_order_id'].$_POST['AMT'].time());

$resArray = hash_call("C",$TRXTYPE,$nvpStr,$request_id);

## New code, PayPal can return 0 with an Approved warning.
## e.g. Approved: 10571-This transaction was approved. However, the Card Security Code provided had too few, too many, or invalid character types but, as per your account option settings, was not required in the approval process.

if ((int)$resArray['RESULT'] == 0) {
	
	$ack = "SUCCESS";
	// PNREF is the PayPal response transaction ID
	$resArray['TRANSACTIONID'] = $resArray["PNREF"];

	include("responseCodes.php");

	if(isset($resArray["AVSADDR"])) {
		$extraNotes .= "<strong>Address:</strong> ".basicResponse($resArray["AVSADDR"])."<br />";
	}
	if(isset($resArray["AVSZIP"])) {
		$extraNotes .= "<strong>Zip/Post Code:</strong> ".basicResponse($resArray["AVSZIP"])."<br />";
	}

	if(isset($resArray["AUTHCODE"])) {
		$extraNotes .= "<strong>Auth code:</strong> ".basicResponse($resArray["AUTHCODE"])."<br />";
	}
	
	if(isset($resArray["CVV2MATCH"])) {
		$extraNotes .= "<strong>CVV2 Match:</strong> ".basicResponse($resArray["CVV2MATCH"])."<br />";
	}
	if(isset($resArray["IAVS"])) {
		$extraNotes .= "<strong>International AVS address response:</strong> ".basicResponse($resArray["IAVS"])."<br />";
	}

} else { 
	
	$ack = "FAIL";
	$errorMsg = paypalErrors($resArray);
	
}
?>
