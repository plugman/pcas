<?php
/*
+--------------------------------------------------------------------------
|	giftCert.inc.php
|   ========================================
|	Gift Certificates	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) die("Access Denied");

## Include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."giftCert.inc.php");

## Validate information

if (isset($_POST['gc']['cert']) && isset($errorGCMsg)) {
	switch($errorGCMsg) {
		case 1:
			$errorMsg = $lang['giftCert']['empty_fields'];
			break;
		case 2:
			$errorMsg = $lang['giftCert']['recip_invalid'];
			break;
		case 3:
			$errorMsg = $lang['giftCert']['amount_invalid'];
			break;
	}
}

$gc = fetchDbConfig('gift_certs');

if($gc['status']==false) {
	httpredir("index.php");
}

$gift_cert = new XTemplate("content".CC_DS."giftCert.tpl");

if (isset($errorMsg)) {
	$gift_cert->assign('VAL_ERROR', $errorMsg);
	$gift_cert->parse('gift_cert.error');
}

$gift_cert->assign("LANG_TITLE", $lang['giftCert']['gift_certificate']);
$gift_cert->assign("LANG_DESC", $lang['giftCert']['desc']);
$gift_cert->assign("LANG_BUY_CERT", $lang['giftCert']['buy_gift_cert']);
$gift_cert->assign("LANG_AMOUNT", $lang['giftCert']['amount']);

if($gc['min']>0 && $gc['max']>0) $gift_cert->assign("LANG_MIN_MAX", sprintf($lang['giftCert']['min_max'], priceFormat($gc['min'], true), priceFormat($gc['max'], true)));
global $currencyVars;
$gift_cert->assign("SYMBL_LEFT", $currencyVars[0]['symbolLeft']);
		
$gift_cert->assign("LANG_RECIP_NAME",$lang['giftCert']['recip_name']);
$gift_cert->assign("LANG_RECIP_EMAIL",$lang['giftCert']['recip_email']);
$gift_cert->assign("LANG_MESSAGE",$lang['giftCert']['message']);
$gift_cert->assign("LANG_METHOD",$lang['giftCert']['method']);

$gift_cert->assign("LANG_EMAIL",$lang['giftCert']['email']);
$gift_cert->assign("LANG_MAIL",$lang['giftCert']['mail']);

$gift_cert->assign("LANG_ADD_TO_BASKET",$lang['giftCert']['add_basket']);



$gift_cert->assign("VALUE_AMOUNT",sanitizeVar($_POST['gc']['amount']));
$gift_cert->assign("VALUE_RECIPNAME",sanitizeVar($_POST['gc']['recipName']));
$gift_cert->assign("VALUE_EMAIL",sanitizeVar($_POST['gc']['recipEmail']));
$gift_cert->assign("VALUE_MESSAGE",sanitizeVar($_POST['gc']['message']));

if (sanitizeVar($_POST['gc']['delivery'])=="e") {
	$gift_cert->assign("VAL_DELIVERY_E","selected='selected'");
} else {
	$gift_cert->assign("VAL_DELIVERY_M","selected='selected'");
}

if($gc['delivery']==1){
	$gift_cert->parse("gift_cert.email_opts");	
} elseif($gc['delivery']==2){
	$gift_cert->parse("gift_cert.mail_opts");
} elseif($gc['delivery']==3) {
	$gift_cert->parse("gift_cert.email_opts");
	$gift_cert->parse("gift_cert.mail_opts");
}
$models = $db->select("SELECT id,name,price,device_id,image,icon FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE hide = '0' ORDER BY device_id ASC");

if($models){
	for($i=0;$i<count($models);$i++){
		$gift_cert->assign("MODEL_NAME", $models[$i]['name']);
		$gift_cert->assign("MODEL_PRICE", $models[$i]['price']);
		$gift_cert->parse("gift_cert.all_models");
	}
}
$docresult = $db->select("SELECT doc_content,doc_name,doc_metatitle,doc_metadesc,doc_metakeywords FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id = ".$db->mySQLSafe(42));
$foreignDocs = $db->select("SELECT doc_content,doc_master_id as doc_id, doc_name FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_lang = '" . LANG_FOLDER . "' AND doc_master_id = ".$db->mySQLSafe(42));
if($foreignDocs)
$docresult[0]['doc_content'] = $foreignDocs[0]['doc_content'];
$gift_cert->assign("TXT_MAIN_TIT", $docresult[0]['doc_name']);
$gift_cert->assign("TXT_MAIN_TEXT", strip_tags($docresult[0]['doc_content']));
$gift_cert->parse("gift_cert");
$page_content = $gift_cert->text("gift_cert");

if ($config['seftags']) {
	$meta['sefSiteTitle']		= strip_tags($docresult[0]['doc_metatitle']); 
$meta['sefSiteDesc']		= strip_tags($docresult[0]['doc_metadesc']); 
$meta['sefSiteKeywords']		= strip_tags($docresult[0]['doc_metakeywords']);
}

?>