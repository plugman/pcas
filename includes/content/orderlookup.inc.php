<?php
/*
+--------------------------------------------------------------------------
|	orderlookup.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."orderlookup.inc.php");

## delete cookie
$orderlookup = new XTemplate ("content".CC_DS."orderlookup.tpl");

$orderlookup->assign("LANG_orderlookup_TITLE",$lang['orderlookup']['orderlookup']);
$orderlookup->assign("LANG_TITLE", $lang['orderlookup']['title']);
$orderlookup->assign("LANG_TRACK", $lang['orderlookup']['Trackorder']);
$orderlookup->assign("LANG_EMAIL", $lang['orderlookup']['Email']);
$orderlookup->assign("LANG_MANDATORY", $lang['orderlookup']['Mandatory']);
$orderlookup->assign("LANG_P1", $lang['orderlookup']['paragraph1']);
$orderlookup->assign("LANG_IMEI", $lang['orderlookup']['Imei']);
$orderlookup->assign("LANG_ERROR", $lang['orderlookup']['error']);
$orderlookup->assign("LANG_P2", $lang['orderlookup']['paragraph2']);
$orderlookup->assign("LANG_P3", $lang['orderlookup']['paragraph3']);
$orderlookup->assign("VAL_CUSTOMER",$cc_session->ccUserData['firstName'].' '.$cc_session->ccUserData['lastName']);
$meta['siteTitle'] = "Track Your Unlock Status Here - IMEI Unlock";
if(isset($_POST['track'])){
if(!empty($_POST['email']) && !empty($_POST['cart_order_id'])){
	
		$query = "SELECT D.cart_order_id FROM ".$glob['dbprefix']."ImeiUnlock_order_inv AS D INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_sum AS I ON D.cart_order_id = I.cart_order_id WHERE 1=1 AND D.cart_order_id = ".$db->mySQLSafe($_POST['cart_order_id'])."AND I.email = ".$db->mySQLSafe($_POST['email']);

	$result = $db->select($query);
	if(!empty($result)){
		$orderId = $result[0]['cart_order_id']; 
		httpredir("index.php?_a=orderdetail&cart_order_id=".$orderId);
	}else {die();
		$orderlookup->assign("LANG_ERROR", " No Order Found");
		$orderlookup->parse("orderlookup.error");		
	}
	
}else {
		$orderlookup->assign("LANG_ERROR", "Please Enter Required");
		$orderlookup->parse("orderlookup.error");		
	}
}

$orderlookup->parse("orderlookup");
$page_content = $orderlookup->text("orderlookup");
?>