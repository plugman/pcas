<?php
/*
+--------------------------------------------------------------------------
|	currencyVars.inc.php
|   ========================================
|	Gets Currency Array	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$override = array(
	"viewOrder"		=> true,
	"viewOrders"	=> true,
	"giftCert"		=> true,
);

$page = sanitizeVar($_GET['_a']);

if (isset($override[$page]) && $override[$page] == true) {
	$cCode = $config['defaultCurrency'];
} else if (!empty($cc_session->ccUserData['currency'])) {
	$cCode = $cc_session->ccUserData['currency'];
	
} else if (!empty($order[0]['currency'])) {
	$cCode = $order[0]['currency'];
	
} else {
	/* CODE NOT IN USE DANGEROUS IF IS IN USE!! BE CAREFUL
	if (isset($_COOKIE['currency']) && !empty($_COOKIE['currency'])) {
		$cCode = $_COOKIE['currency'];
	}
	*/
	$cCode = $config['defaultCurrency'];
}

$cache = new cache('glob.currencyVars.'.$cCode);
$currencyVars = $cache->readCache();

if (!$cache->cacheStatus) {
	$query			= sprintf("SELECT value, symbolLeft, code, symbolRight, decimalPlaces, name, decimalSymbol FROM %sImeiUnlock_currencies WHERE code=%s", $glob['dbprefix'], $db->mySQLSafe($cCode));
	$currencyVars	= $db->select($query);
	$cache->writeCache($currencyVars);
}
?>