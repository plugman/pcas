<?php
/*
+--------------------------------------------------------------------------
|	currency.inc.php
|   ========================================
|	Currency Jump Box	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

## include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."currency.inc.php");

$cache = new cache('boxes.currency');
$currencies = $cache->readCache();

if (!$cache->cacheStatus) {
	$currencies = $db->select("SELECT name, symbolLeft, symbolRight, code FROM ".$glob['dbprefix']."ImeiUnlock_currencies WHERE active = 1 ORDER BY name ASC");
	$cache->writeCache($currencies);
}

if ($currencies) {
	$box_content = new XTemplate ("boxes".CC_DS."currency.tpl");
	$box_content->assign("LANG_CURRENCY_TITLE", $lang['currency']['currency']);
	for ($i=0; $i<count($currencies); $i++){
		$currencyName = (strlen($currencies[$i]['name'])>20) ? substr($currencies[$i]['name'], 0, 18)."&hellip;" : $currencies[$i]['name'];

		$box_content->assign("VAL_CURRENCY", $currencies[$i]['code']);
		$box_content->assign("SYMBOLLEFT", $currencies[$i]['symbolLeft']);
			$box_content->assign("SYMBOLRIGHT", $currencies[$i]['symbolRight']);
		$box_content->assign("CURRENCY_NAME", $currencyName);
		$box_content->assign("VAL_CURRENT_PAGE", $returnPage);
		$box_content->parse("currency.option");
	}
	if ($cc_session->ccUserData['currency'] == $currencies[$i]['code']) {
   $box_content->assign("CURRENCY_SELECTED", $currencies[$i]['symbolLeft']. $currencies[$i]['code'].$currencies[$i]['symbolRight']);
  } else if (($config['defaultCurrency'] == $currencies[$i]['code']) && empty($cc_session->ccUserData['currency'])) {
   $box_content->assign("CURRENCY_SELECTED", $currencies[$i]['symbolLeft']. $currencies[$i]['code'].$currencies[$i]['symbolRight']);
  } else {
   $box_content->assign("CURRENCY_SELECTED", '');
  }
  global $currencyVars;
  $box_content->assign("CURRENCY_SELECTED", $currencyVars[0]['symbolLeft']. "&nbsp;". $currencyVars[0]['code'].$currencyVars[0]['symbolRight']);
	$box_content->parse("currency");
	$box_content = $box_content->text("currency");
} else {
	$box_content = '';
}
?>