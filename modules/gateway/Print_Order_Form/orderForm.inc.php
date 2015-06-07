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
|	orderForm.inc.php
|   ========================================
|	Makes Printable Order Form	
+--------------------------------------------------------------------------
*/
	
	if (!defined('CC_INI_SET')) die("Access Denied");
	
	session_start();
	unset($_SESSION['cc_back']);
	
	$module = fetchDbConfig("Print_Order_Form");
	
	// include lang file
	$lang = getLang("includes".CC_DS."content".CC_DS."gateway.inc.php");
	
	require_once("classes".CC_DS."cart".CC_DS."order.php");
	$order = new order();
	
	// get exchange rates etc override users curency if need be
	if($module['multiCurrency']==0)
	{
		$cc_session->ccUserData['currency'] = $config['defaultCurrency'];
	}
	include_once("includes".CC_DS."currencyVars.inc.php");
	
	$print_order_form = new XTemplate("modules/gateway/Print_Order_Form/orderForm.tpl",'',null,'main',true,$skipPath=TRUE);
	
	$print_order_form->assign("LANG_RETURN_STORE",$lang['gateway']['return_to_store']);
	
	$orderInv = $order->getOrderInv($_GET["cart_order_id"]);
	$orderSum = $order->getOrderSum($_GET["cart_order_id"]);
	
	if ($orderSum == false) {
		httpredir($glob['rootRel']."index.php?_g=co&_a=step2");
	}
	
	## Incluse langauge config
	include("language".CC_DS.LANG_FOLDER.CC_DS."config.php");
	
	$print_order_form->assign("VAL_ISO",$charsetIso);
	
	$print_order_form->assign("VAL_STORE_NAME",$config['storeName']);
	$print_order_form->assign("VAL_STORE_URL",$glob['storeURL']);
	$print_order_form->assign("LANG_INVOICE_TO",$lang['gateway']['invoiceTo']);
	$print_order_form->assign("VAL_INVOICE_NAME",$orderSum['name']);
	$print_order_form->assign("VAL_INVOICE_COMPANY",$orderSum['companyName']);
	$print_order_form->assign("VAL_INVOICE_ADD1",$orderSum['add_1']);
	$print_order_form->assign("VAL_INVOICE_ADD2",$orderSum['add_2']);
	$print_order_form->assign("VAL_INVOICE_TOWN",$orderSum['town']);
	$print_order_form->assign("VAL_INVOICE_STATE",$orderSum['county']);
	$print_order_form->assign("VAL_INVOICE_POSTCODE",$orderSum['postcode']);
	$print_order_form->assign("VAL_INVOICE_COUNTRY",getCountryFormat($orderSum['country'],"id","printable_name"));
	
	$print_order_form->assign("LANG_DELIVER_TO",$lang['gateway']['deliverTo']);
	$print_order_form->assign("VAL_DELIVER_NAME",$orderSum['name_d']);
	$print_order_form->assign("VAL_DELIVER_COMPANY",$orderSum['companyName']);
	$print_order_form->assign("VAL_DELIVER_ADD1",$orderSum['add_1_d']);
	$print_order_form->assign("VAL_DELIVER_ADD2",$orderSum['add_2_d']);
	$print_order_form->assign("VAL_DELIVER_TOWN",str_replace("&amp;#39;","&#39;",$orderSum['town_d']));
	$print_order_form->assign("VAL_DELIVER_STATE",$orderSum['county_d']);
	$print_order_form->assign("VAL_DELIVER_POSTCODE",$orderSum['postcode_d']);
	$print_order_form->assign("VAL_DELIVER_COUNTRY",$orderSum['country_d']);
		  
	$print_order_form->assign("LANG_INVOICE_RECIEPT_FOR",$lang['gateway']['postalOrderFor']);
	$print_order_form->assign("LANG_ORDER_OF_TIME",$lang['gateway']['orderOf']);
	$print_order_form->assign("VAL_TIME_DATE",formatTime($orderSum['time']));
	$print_order_form->assign("LANG_CART_ORDER_ID",$lang['gateway']['orderID']);
	$print_order_form->assign("VAL_CART_ORDER_ID",$orderSum['cart_order_id']);
	
	$print_order_form->assign("LANG_PRODUCT",$lang['gateway']['product']);
	$print_order_form->assign("LANG_PRODUCT_CODE",$lang['gateway']['code']);
	$print_order_form->assign("LANG_QUANTITY",$lang['gateway']['qty']);
	$print_order_form->assign("LANG_PRICE",$lang['gateway']['price']);
	
	
	for($i=0;$i<count($orderInv);$i++)
	{
		$print_order_form->assign("VAL_PRODUCT_NAME",$orderInv[$i]['name']);
		//$print_order_form->assign("VAL_PRODUCT_OPTS",nl2br(str_replace("&amp;#39;","&#39;",$orderInv[$i]['product_options'])));
		$options = explode("\n", $orderInv[$i]['product_options']);
					$searchword = 'Image Path';
				
					$matches = array();
				
						foreach($options as $k=>$v) {
				
						if(preg_match("/\b$searchword\b/i", $v)) {
				
						unset($options[$k]);
						 }
				
				}
				
		$orderInv[$i]['product_options'] = implode("\n", $options);
		$print_order_form->assign("VAL_PRODUCT_OPTS",nl2br(str_replace('&amp;','&',$orderInv[$i]['product_options'])));
		$print_order_form->assign("VAL_PRODUCT_CODE",$orderInv[$i]['productCode']);
		$print_order_form->assign("VAL_PRODUCT_QUANTITY",$orderInv[$i]['quantity']);
		$print_order_form->assign("VAL_PRODUCT_PRICE",priceFormat($orderInv[$i]['price'],true));
		$print_order_form->parse("order_form.repeat_order_inv");
	}
	
	$print_order_form->assign("LANG_SHIPPING_METHOD",$lang['gateway']['shippingMethod']);
	$print_order_form->assign("VAL_SHIPPING_METHOD",$orderSum['shipMethod']);  
	$print_order_form->assign("LANG_SUBTOTAL",$lang['gateway']['subtotal']);
	$print_order_form->assign("VAL_SUBTOTAL",priceFormat($orderSum['subtotal'],true));
	
	$print_order_form->assign("LANG_DISCOUNT",$lang['gateway']['discount']);
	$print_order_form->assign("VAL_DISCOUNT",priceFormat($orderSum['discount'],true));
	
	// start mod: Flexible Taxes (http://www.beadberry.com/cubemods)
	$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");
	if ($config_tax_mod['status'])
	{
		// additional taxes
		for ($i=1; $i<3; $i++)
		{
			if ($orderSum['tax'.($i+1).'_disp'] != "")
			{
				$print_order_form->assign("LANG_TAX",$orderSum['tax'.($i+1).'_disp']);
				$print_order_form->assign("VAL_TAX",priceFormat($orderSum['tax'.($i+1).'_amt'],true));
				$print_order_form->parse("order_form.repeat_additional_taxes");
			}
		}
		// first tax
		if ($orderSum['tax1_disp'] != "")
		{
			$print_order_form->assign("LANG_TOTAL_TAX",$orderSum['tax1_disp']);
			$print_order_form->assign("VAL_TOTAL_TAX",priceFormat($orderSum['tax1_amt'],true));
		}
		else
		{
			$print_order_form->assign("LANG_TOTAL_TAX",$lang['gateway']['totalTax']);
			$print_order_form->assign("VAL_TOTAL_TAX",priceFormat($orderSum['total_tax'],true));
		}

		// tax registration number(s)
		$reg_number = $db->select("SELECT reg_number FROM ".$glob['dbprefix']."ImeiUnlock_tax_details;");
		$reg_string = "";
		for ($i=0; is_array($reg_number) && $i<count($reg_number); $i++)
		{
			if ($reg_number[$i]['reg_number']!="") {
				$reg_string .= $reg_number[$i]['reg_number']."<br/>";
			}
		}
		$print_order_form->assign("VAL_TAX_REG",$reg_string);
	}
	else
	{
	// end mod: Flexible Taxes
		$print_order_form->assign("LANG_TOTAL_TAX",$lang['gateway']['totalTax']);
		$print_order_form->assign("VAL_TOTAL_TAX",priceFormat($orderSum['total_tax'],true));
	} // mod: Flexible Taxes
	$print_order_form->assign("LANG_SHIPPING",$lang['gateway']['totalShipping']);
	$print_order_form->assign("VAL_SHIPPING",priceFormat($orderSum['total_ship'],true));
	$print_order_form->assign("LANG_GRAND_TOTAL",$lang['gateway']['grandTotal']);
	$print_order_form->assign("VAL_GRAND_TOTAL",priceFormat($orderSum['prod_total'],true));
	
	if(!empty($orderSum['customer_comments'])) {
		$print_order_form->assign("VAL_CUSTOMER_COMMENTS",$orderSum['customer_comments']);
		$print_order_form->parse("order_form.customer_comments");
	}
	
	if($module['cheque'] == 1){
		$print_order_form->assign("LANG_PAY_BY_CHEQUE",$lang['gateway']['payByCheck']);
		$print_order_form->assign("VAL_MAKE_CHEQUES_PAYABLE_TO",$lang['gateway']['payTo']." ".$module['payableTo'].".");
		$print_order_form->parse("order_form.check_true");
	}
	
	if($module['card'] == 1){
		$print_order_form->assign("LANG_PAY_BY_CARD",$lang['gateway']['payByCard']);
		$print_order_form->assign("LANG_CARD_TYPE",$lang['gateway']['cardType']);
		
		$cards = explode(",",$module['cards']);
		
		if(is_array($cards)){
			foreach($cards as $key => $value){
			$print_order_form->assign("VAL_CARD_NAME",$value);
			$print_order_form->parse("order_form.card_true.repeat_card");
			}
		}
		
		$print_order_form->assign("LANG_CARD_NO",$lang['gateway']['cardNo']);
		$print_order_form->assign("LANG_3_DIG_ID",$lang['gateway']['3-4DigiId']);
		$print_order_form->assign("LANG_EXPIRE_DATE",$lang['gateway']['expiryDate']);
		$print_order_form->assign("LANG_ISSUE_DATE",$lang['gateway']['issueDate']);
		$print_order_form->assign("LANG_ISSUE_NUMBER",$lang['gateway']['issueNo']);
		$print_order_form->assign("LANG_SIGNATURE",$lang['gateway']['signature']);
		$print_order_form->parse("order_form.card_true");
	}
	
	
	if($module['bank'] == 1){
		$print_order_form->assign("LANG_PAY_BY_WIRE",$lang['gateway']['payByTransfer']);
		$print_order_form->assign("LANG_BANK_NAME",$lang['gateway']['bankName']);
		$print_order_form->assign("VAL_BANK_NAME",$module['bankName']);
		$print_order_form->assign("LANG_ACCOUNT_NAME",$lang['gateway']['accountName']);
		$print_order_form->assign("VAL_ACCOUNT_NAME",$module['accName']);
		$print_order_form->assign("LANG_SORT_CODE",$lang['gateway']['sortCode']);
		$print_order_form->assign("VAL_SORT_CODE",$module['sortCode']);
		$print_order_form->assign("LANG_AC_NO",$lang['gateway']['accountNo']);
		$print_order_form->assign("VAL_AC_NO",$module['acNo']);
		$print_order_form->assign("LANG_SWIFT_CODE",$lang['gateway']['swiftCode']);
		$print_order_form->assign("VAL_SWIFT_CODE",$module['swiftCode']);
		$print_order_form->assign("LANG_ADDRESS",$lang['gateway']['bankAddress']);
		$print_order_form->assign("VAL_ADDRESS",$module['address']);
		$print_order_form->parse("order_form.bank_true");
	}
	
	if(!empty($module['notes'])){
	$print_order_form->assign("VAL_CUST_NOTES",$module['notes']);
	$print_order_form->parse("order_form.cust_notes");
	}
	
	$print_order_form->assign("LANG_THANK_YOU",$lang['gateway']['thanks']);
	$print_order_form->assign("LANG_SEND_TO",$lang['gateway']['postalAddress']);
	$print_order_form->assign("VAL_STORE_ADDRESS",$config['storeAddress']);
	
$print_order_form->parse("order_form");
	
$print_order_form->out("order_form");



?>
