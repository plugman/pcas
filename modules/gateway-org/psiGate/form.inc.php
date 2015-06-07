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
|	psiGate process payment
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
if(detectSSL() == FALSE) die ("<strong>Critical Error:</strong> This page can ONLY be viewed under SSL!");

if($_GET['process']==1){

	$transData['customer_id'] = $orderSum["customer_id"];
	$transData['order_id'] = $orderSum['cart_order_id'];
	$transData['amount'] = $orderSum['prod_total'];
	$transData['gateway'] = "psiGate";

	// first check card
	require("classes".CC_DS."validate".CC_DS."validateCard.php");
	
	$card = new validateCard();
	
	$cardNo			= $_POST["cardNumber"];
	$issueNo		= 0;
	$issueDate		= 0; 
	$issueFormat	= 4; 
	$expireDate		= trim($_POST["expirationYear"]).str_pad(trim($_POST["expirationMonth"]), 2, '0', STR_PAD_LEFT); 
	$expireFormat	= 4; 
	$scReqd			= TRUE; 
	$securityCode	= $_POST["cvc2"];
	
	$card = $card->check($cardNo, 
						$issueNo, 
						$issueDate, 
						$issueFormat, 
						$expireDate, 
						$expireFormat, 
						$scReqd, 
						$securityCode);
	
	if($module['validation']==1 && $card['response']=="FAIL"){
	
		$errorMsg = "";
		
		foreach($card['error'] as $val){
		
			$errorMsg .= $val."<br />";
		
		}

	} else {
	
	$XPost = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
	<Order>
	";
		
		if($module['test']==1){
			$XPost .= "<StoreID>teststore</StoreID>
			<Passphrase>psigate1234</Passphrase>";
		} else {
			$XPost .= "<StoreID>".$module['acNo']."</StoreID>
		<Passphrase>".$module['passPhrase']."</Passphrase>
		";
		}
		
		$XPost .= 
		"<Subtotal>".$orderSum['prod_total']."</Subtotal>
		<PaymentType>CC</PaymentType>
		<CardAction>0</CardAction>
		<CardNumber>".trim($_POST["cardNumber"])."</CardNumber>
		<CardExpMonth>".trim($_POST["expirationMonth"])."</CardExpMonth>
		<CardExpYear>".trim($_POST["expirationYear"])."</CardExpYear>
		<CardIDCode>1</CardIDCode>
		<CardIDNumber>".trim($_POST["cvc2"])."</CardIDNumber>
		<CustomerIP>".trim(get_ip_address())."</CustomerIP>
		<Item>
			<ItemID>".$orderSum['cart_order_id']."</ItemID>
			<ItemDescription>Order #".$orderSum['cart_order_id']."</ItemDescription>
			<ItemQty>1</ItemQty>
			<ItemPrice>".$orderSum['prod_total']."</ItemPrice>
		</Item>	
		<Bname>".trim($_POST["firstName"])." ".$_POST["lastName"]."</Bname>
		<Baddress1>".trim($_POST["addr1"])."</Baddress1>
		<Baddress2>".trim($_POST["addr2"])."</Baddress2>
		<Bcity>".trim($_POST["city"])."</Bcity>
		<Bprovince>".trim($_POST["state"])."</Bprovince>
		<Bpostalcode>".trim($_POST["postalCode"])."</Bpostalcode>
		<Bcountry>".trim($_POST["country"])."</Bcountry>
		<Email>".trim($_POST["emailAddress"])."</Email>
	</Order>";
	
	if($module['test']==1){
		$url = "https://dev.psigate.com:7989/Messenger/XMLMessenger";
	} else {
		$url = $module['url'];
	}
	
	
	$ch = curl_init();    // initialize curl handle
	curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
	curl_setopt($ch, CURLOPT_TIMEOUT, 4); // times out after 4s
	curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost); // add POST fields
	if($config['proxy']==1){
		curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
	}
	$result = curl_exec($ch); // run the whole process
	$curl_error = curl_errno($ch);
	if ($curl_error) {
	   echo "<strong>Curl Error: ".$curl_error."</strong> ".curl_error($ch)." - ".$url;
	} else {
	   curl_close($ch);
	}
	
	// use XML Parser on $result, and your set!
	
		   $xml_parser = xml_parser_create();
		   xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,0);
		   xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,1);
		   xml_parse_into_struct($xml_parser, $result, $vals, $index);
		   xml_parser_free($xml_parser);
	
	// $vals = array of XML tags.  Go get em!
	
	/* 
	foreach($vals as $key => $value){
	
		echo $key ." = ".$value."<br/>";
		foreach($value as $key2 => $value2){
		echo $key2 ." = ".$value2."<br/>";
		}
		
		
		echo "<hr/>";
	}
	echo "Array 4 Value = ".$vals[4]['value'];
	exit;
	*/
	$notes = explode(":",$vals[6]['value']);
	
		if($vals[4]['value'] == "APPROVED") {
			$order->orderStatus(3,$orderSum['cart_order_id']);
			$jumpTo = "index.php?_g=co&_a=confirmed&s=2";
			
			$transData['trans_id'] = $vals[13]['value'];
			$transData['status'] = $vals[4]['value'];
			$transData['notes'] = $notes[1];
		} else {
			$transData['trans_id'] = "n/a";
			$transData['status'] = $vals[4]['value'];
			$errorMsg = "This transaction has been declined. Please check the card details and try again.";
			$transData['notes'] = $errorMsg;
		}
		
	}
	
	$order->storeTrans($transData);
	
	if(isset($jumpTo) && !empty($jumpTo)) {
	
		httpredir($jumpTo);
		
	}
	
}


$formTemplate = new XTemplate ("modules".CC_DS."gateway".CC_DS.$_POST['gateway'].CC_DS."form.tpl",'',null,'main',true,$skipPath=TRUE);

if(isset($errorMsg)) {
	
	$formTemplate->assign("LANG_ERROR",$errorMsg);
	$formTemplate->parse("form.error");

}

$billingName = makeName($orderSum['name']);

$formTemplate->assign("VAL_AMOUNT_DUE",sprintf($lang['gateway']['amount_due'],priceformat($orderSum['prod_total'],true)));
$formTemplate->assign("VAL_FIRST_NAME",$billingName[2]);
$formTemplate->assign("VAL_LAST_NAME",$billingName[3]);
$formTemplate->assign("VAL_EMAIL_ADDRESS",$orderSum['email']);
$formTemplate->assign("VAL_ADD_1",$orderSum['add_1']);
$formTemplate->assign("VAL_ADD_2",$orderSum['add_2']);
$formTemplate->assign("VAL_CITY",$orderSum['town']);
$formTemplate->assign("VAL_COUNTY",$orderSum['county']);
$formTemplate->assign("VAL_POST_CODE",$orderSum['postcode']);
$formTemplate->assign("VAL_CART_ORDER_ID",$orderSum['cart_order_id']);
$formTemplate->assign("VAL_GRAND_TOTAL",$orderSum['prod_total']);
$formTemplate->assign("VAL_MERCH_ID",$module['acNo']);


$cache = new cache('glob.countries');
$countries = $cache->readCache();

if (!$cache->cacheStatus) {
	$countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name");
	$cache->writeCache($countries);
}
	
	for($i=0; $i<count($countries); $i++) {
		if ($countries[$i]['id'] == $orderSum['country']) {
			$formTemplate->assign("COUNTRY_SELECTED","selected='selected'");
		} else {
			$formTemplate->assign("COUNTRY_SELECTED","");
		}
	
		$formTemplate->assign("VAL_COUNTRY_ISO", $countries[$i]['iso']);

		$countryName = "";
		$countryName = $countries[$i]['printable_name'];

		if (strlen($countryName)>20) {
			$countryName = substr($countryName,0,20).'&hellip;';
		}

		$formTemplate->assign("VAL_COUNTRY_NAME",$countryName);
		$formTemplate->parse("form.repeat_countries");
	}
	
	$formTemplate->assign("LANG_CC_INFO_TITLE",$lang['gateway']['cc_info_title']);
	$formTemplate->assign("LANG_FIRST_NAME",$lang['gateway']['first_name']); 
	$formTemplate->assign("LANG_LAST_NAME",$lang['gateway']['last_name']); 
	//$formTemplate->assign("LANG_CARD_TYPE",$lang['gateway']['card_type']);
	$formTemplate->assign("LANG_CARD_NUMBER",$lang['gateway']['card_number']);
	$formTemplate->assign("LANG_EXPIRES",$lang['gateway']['expires']);
	$formTemplate->assign("LANG_MMYY",$lang['gateway']['mmyy']);
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