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
|	Core functions for the Nochex Gateway	
+--------------------------------------------------------------------------
*/

function repeatVars() {
	return false;
}

function fixedVars() {
	
	global $module, $cc_session, $orderSum, $config;
	
	$hiddenVars = "";
	$hiddenVars.= "<input type='hidden' name='description' value='Payment for order #".$orderSum['cart_order_id']."' />";
	$hiddenVars.= "<input type='hidden' name='amount' value='".$orderSum['prod_total']."' />";
	
	switch($module['accType']){
		case "seller":
			$hiddenVars.= "<input type='hidden' name='firstname' value='".$cc_session->ccUserData['firstName']."' />";
			$hiddenVars.= "<input type='hidden' name='lastname' value='".$cc_session->ccUserData['lastName']."' />";
			$hiddenVars.= "<input type='hidden' name='firstline' value='".$orderSum['add_1']."' />";
			$hiddenVars.= "<input type='hidden' name='town' value='".$orderSum['town']."' />";
			$hiddenVars.= "<input type='hidden' name='county' value='".$orderSum['county']."' />";
			$hiddenVars.= "<input type='hidden' name='postcode' value='".$orderSum['postcode']."' />";
			$hiddenVars.= "<input type='hidden' name='email_address_sender' value='".$orderSum['email']."' />";
			$hiddenVars.= "<input type='hidden' name='email' value='".$module['email']."' />";
			$hiddenVars.= "<input type='hidden' name='ordernumber' value='".$orderSum['cart_order_id']."' />";
			$hiddenVars.= "<input type='hidden' name='returnurl' value='".$GLOBALS['storeURL']."/index.php?_g=rm&type=gateway&cmd=process&module=Nochex_APC' />";
			$hiddenVars.= "<input type='hidden' name='cancelurl' value='".$GLOBALS['storeURL']."/index.php?_g=rm&type=gateway&cmd=process&module=Nochex_APC&cancel=true' />";
			$hiddenVars.= "<input type='hidden' name='responderurl' value='".$GLOBALS['storeURL']."/index.php?_g=rm&type=gateway&cmd=call&module=Nochex_APC' />";
			
			if ($module['testMode']) {
				$hiddenVars.= "<input type='hidden' name='status' value='test' />";
			}
			break;
			
		case "merchant":
		
			$billing_address = array();
			if (strlen($orderSum['add_1'])>0)	$billing_address[] = $orderSum['add_1'];
			if (strlen($orderSum['add_2'])>0)	$billing_address[] = $orderSum['add_2'];
			if (strlen($orderSum['town'])>0)	$billing_address[] = $orderSum['town'];
			if (strlen($orderSum['county'])>0)	$billing_address[] = $orderSum['county'];
			
			$merchant_id = (strlen($module['merchant_id'])>0) ? $module['merchant_id'] : $module['email'];
			
			$hiddenVars.= "<input type='hidden' name='postage' value='0.00' />";
			$hiddenVars.= "<input type='hidden' name='billing_fullname' value='".$orderSum['name']."' />";
			$hiddenVars.= "<input type='hidden' name='billing_address' value='".implode("\r\n", $billing_address)."' />";
			$hiddenVars.= "<input type='hidden' name='billing_postcode' value='".$orderSum['postcode']."' />";
			$hiddenVars.= "<input type='hidden' name='customer_phone_number' value='".$orderSum['phone']."' />";
			$hiddenVars.= "<input type='hidden' name='email_address' value='".$orderSum['email']."' />";
			$hiddenVars.= "<input type='hidden' name='order_id' value='".$orderSum['cart_order_id']."' />";
			$hiddenVars.= "<input type='hidden' name='merchant_id' value='".$merchant_id."' />";
			$hiddenVars.= "<input type='hidden' name='success_url' value='".$GLOBALS['storeURL']."/index.php?_g=co&amp;_a=confirmed&amp;s=2' />";
			$hiddenVars.= "<input type='hidden' name='test_success_url' value='".$GLOBALS['storeURL']."/index.php?_g=co&amp;_a=confirmed&amp;s=2' />";
			$hiddenVars.= "<input type='hidden' name='cancel_url' value='".$GLOBALS['storeURL']."/index.php?_g=co&amp;_a=confirmed&amp;s=1' />";
			$hiddenVars.= "<input type='hidden' name='declined_url' value='".$GLOBALS['storeURL']."/index.php?_g=co&amp;_a=confirmed&amp;s=1' />";
			$hiddenVars.= "<input type='hidden' name='callback_url' value='".$GLOBALS['storeURL']."/index.php?_g=rm&amp;type=gateway&amp;cmd=call&amp;module=Nochex_APC' />";
			
			if ($module['passTemplate']) {
				$template_html = nochex_get_template_html();
				$hiddenVars.= "<input type='hidden' name='header_html' value='".$template_html[0]."' />";
				$hiddenVars.= "<input type='hidden' name='footer_html' value='".$template_html[1]."' />";
			}
			if ($module['testMode']) {
				$hiddenVars.= "<input type='hidden' name='test_transaction' value='100' />";
			}
			break;
	}
	return $hiddenVars;
}

function nochex_get_template_html() {

	## If you want to overide this function please put a header.htm and footer.htm file in the modules directory 
	$header_path = CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'gateway'.CC_DS.'Nochex_APC'.CC_DS.'header.htm';
	$footer_path = CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'gateway'.CC_DS.'Nochex_APC'.CC_DS.'footer.htm';
	
	if(file_exists($header_path) && file_exists($footer_path)){
		$header_html = file_get_contents($header_path);
		$footer_html = file_get_contents($footer_path);
		return array(htmlspecialchars($header_html), htmlspecialchars($footer_html));
	}

	
	global $module, $cc_session, $config, $charsetIso, $db, $glob, $config, $lang, $currencyVer;
	
	ob_start();

	$body = new XTemplate("global".CC_DS."cart.tpl");

	## START CONTENT BOXES
	$body->assign("SEARCHSTR","");
	$body->assign("CURRENCY_VER", $currencyVer);
	$body->assign("VAL_ISO", $charsetIso);
	$body->assign("VAL_SKIN", $config['skinDir']);
	$body->assign("PAGE_CONTENT", "<!-- EXPLODER -->");
	
	require_once "includes".CC_DS."boxes".CC_DS."session.inc.php";
	$body->assign("SESSION", $box_content);
	require_once "includes".CC_DS."boxes".CC_DS."siteDocs.inc.php";
	$body->assign("SITE_DOCS", $box_content);
	require_once "includes".CC_DS."boxes".CC_DS."cartNavi.inc.php";
	$body->assign("CART_NAVI", $box_content);
	/*
	require_once "includes".CC_DS."boxes".CC_DS."searchForm.inc.php";
	$body->assign("SEARCH_FORM", $box_content);
	require_once"includes".CC_DS."boxes".CC_DS."currency.inc.php";
	$body->assign("CURRENCY",$box_content);
	require_once"includes".CC_DS."boxes".CC_DS."language.inc.php";
	$body->assign("LANGUAGE",$box_content);
	*/
	$body->parse("body");
	$body->out("body");
	$body = ob_get_contents();
	ob_end_clean();
	
	$searchArray = array(
	"'<head[^>]*?>'si",
	"'</head>'si",
	"'<body[^>]*?>'si",
	"'</body>'si",
	"'<form[^>]*?>'si",
	"'</form>'si",
	"'<meta[^>]*? />'si",
	"'<html[^>]*?>'si",
	"'</html>'si",
	"'<!DOCTYPE[^>]*?>'si",
	"'<title[^>]*?>.*?</title>'si",
	);
	
	$body = preg_replace($searchArray,"",$body);
	// dirty hack for on focus in search which corrupts
	$body = str_replace("onfocus=\"this.value=''\"","",$body);
	
	$body = array_shift(explode("</body>", $body));
	//die("<pre>".print_r($GLOBALS, true)."</pre>");
	$body = nochex_insert_absolute_urls($body, array("src", "href"));
	
	$body = explode("<!-- EXPLODER -->", $body);
	$header_html = "<center>".$body[0];
	$header_html.= "<div><img src=\"" . $GLOBALS['storeURL'] . "/modules/gateway/Nochex_APC/header.gif\" border=\"0\" alt=\"\" ></a></div>";
	$footer_html = $body[1]."</center>";
	
	return array(htmlspecialchars($header_html), htmlspecialchars($footer_html));

}

function nochex_insert_absolute_urls($markup, $attribs = array()) {
	foreach($attribs as $attrib) {
		$offset = 0;
		while($pos = strpos($markup, $attrib."=", $offset)) {
			if ($pos===false) break;
			
			if (substr($markup, $pos + strlen($attrib) + 1, 1)=="\"" || substr($markup, $pos + strlen($attrib) + 1, 1)=="'") {
				$pos++;
			}
			
			if (substr($markup, $pos + strlen($attrib) + 1, 7)!="http://" && substr($markup, $pos + strlen($attrib) + 1, 8)!="https://") {
				$markup = substr($markup, 0, $pos + strlen($attrib) + 1) . $GLOBALS['storeURL'] . "/" . substr($markup, $pos + strlen($attrib) + 1);
			}
			$offset = $pos;
		}
	}
	return $markup;
}

$formAction = "https://secure.nochex.com/";
$formMethod = "post";
$formTarget = "_self";

$transfer = "auto";
?>