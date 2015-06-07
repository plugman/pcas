<?php
if (!defined('CC_INI_SET')) die("Access Denied");


require_once 'CallerService.php';
 
// Order is Express Checkkout Only
if($module['paymentAction'] == "Order") {
	$module['paymentAction'] = "Authorization";
}
 
$paymentType 		= urlencode($module['paymentAction']);
$firstName 			= urlencode(trim($_POST['firstName']));
$lastName 			= urlencode(trim($_POST['lastName']));
$creditCardType 	= urlencode(trim($_POST['cardType']));
$creditCardNumber 	= urlencode(trim($_POST['cardNumber']));
$expDateMonth 		= urlencode(trim($_POST['expirationMonth']));
$expDateYear 		= urlencode(trim($_POST['expirationYear']));
$cvv2Number 		= urlencode(trim($_POST['cvc2']));
$address1 			= urlencode(trim($_POST['addr1']));
$address2 			= urlencode(trim($_POST['addr2']));
$city 				= urlencode(trim($_POST['city']));
$state 				= urlencode($processState);
$zip 				= urlencode(trim($_POST['postalCode']));
$amount 			= urlencode(sprintf("%.2f",$orderSum['prod_total']));
$currencyCode		= urlencode($config['defaultCurrency']);
$countryCode		= urlencode($countryIso); // specified in form.inc.php
$ipAddress 			= urlencode(get_ip_address());
$padDateMonth 		= str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);


/* new in 4.2.2 (shipping address) - START */
$countryIso_ship = getCountryFormat($orderSum['country_d'],"printable_name","iso");
$countryId_ship = getCountryFormat($orderSum['country_d'],"printable_name","id");

if($countryIso_ship == "US" || $countryIso_ship == "CA") {
	$ISOstate_ship = $db->select("SELECT `abbrev` FROM  `".$glob['dbprefix']."ImeiUnlock_iso_counties` WHERE `name` = '".$orderSum['county_d']."' AND `countryId` = ".$countryId_ship.";");	
}

$processState_ship = ($ISOstate_ship==true) ? $ISOstate_ship[0]['abbrev'] : trim($_POST['state']);
/* new in 4.2.2 (shipping address) - END */

$nvpStr =
"&PAYMENTACTION=".$paymentType.
"&AMT=".$amount.
"&CREDITCARDTYPE=".$creditCardType.
"&ACCT=".$creditCardNumber.
"&EXPDATE=".$padDateMonth.$expDateYear.
"&CVV2=".$cvv2Number.
"&FIRSTNAME=".$firstName.
"&LASTNAME=".$lastName.
"&STREET=".$address1.
"&CITY=".$city.
"&STATE=".$state.
"&ZIP=".$zip.
"&COUNTRYCODE=".$countryCode.
"&CURRENCYCODE=".$currencyCode.
"&IPADDRESS=".$ipAddress;

/* new in 4.2.2 (shipping address) - START */
"&SHIPTONAME=".urlencode($orderSum['name_d']).
"&SHIPTOSTREET=".urlencode($orderSum['add_1_d']).
"&SHIPTOSTREET2=".urlencode($orderSum['add_2_d']).
"&SHIPTOCITY=".urlencode($orderSum['town_d']).
"&SHIPTOSTATE=".urlencode($processState_ship).
"&SHIPTOZIP=".urlencode($orderSum['postcode_d']).
"&SHIPTOCOUNTRYCODE=".urlencode($countryIso_ship);
/* new in 4.2.2 (shipping address) - END */

if ((bool)$module['3ds_status']) {
	$nvpStr	.= "&AUTHSTATUS3D=".$_SESSION['centinel']['AUTHSTATUS3D'].
	"&MPIVENDOR3DS=".$_SESSION['centinel']['MPIVENDOR3DS'].
	"&CAVV=".$_SESSION['centinel']['CAVV'].
	"&ECI3DS=".$_SESSION['centinel']['ECI'].
	"&XID=".$_SESSION['centinel']['XID'];
};

/* new in 4.2.3 for GBP transactions - START */
if(isset($_POST['issueMonth']) && isset($_POST['issueYear'])) {
	// Please note 20 forced onto year start to meet MMYYYY requirements
	$nvpStr .= "&STARTDATE=".str_pad(trim($_POST['issueMonth']), 2, '0', STR_PAD_LEFT)."20".substr(trim($_POST['issueYear']),2,4);
}
if(isset($_POST['issueNo'])) {
	$nvpStr .= "&ISSUENUMBER=".trim($_POST['issueNo']);
}
/* new in 4.2.3 for GBP transactions - END */

switch($currCodeType) {
	case "USD":
		$nvpStr.="&BUTTONSOURCE=ImeiUnlock_Cart_DP_US";
	break;
	case "GBP":
		$nvpStr.="&BUTTONSOURCE=ImeiUnlock_Cart_DP";
	break;
	case "CAD":
		$nvpStr.="&BUTTONSOURCE=ImeiUnlock_Cart_DP_CA";
	break;
}



$resArray=hash_call("doDirectPayment",$nvpStr);

$ack = strtoupper($resArray["ACK"]);

$errorMsg['customer'] = $resArray["L_LONGMESSAGE0"];
?>