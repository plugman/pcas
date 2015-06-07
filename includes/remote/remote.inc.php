<?php
/*
+--------------------------------------------------------------------------
|	remote.inc.php
|   ========================================
|	Manages remote calls from 3rd party servers	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

## START  MAIN CONTENT
if (in_array($_REQUEST['type'], array('gateway', 'altCheckout'))) {
	## make sure module is enabled
	if (isset($_REQUEST['module'])) {
		$query = "SELECT folder FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE module=".$db->mySQLSafe($_REQUEST['type'])." AND folder=".$db->mySQLSafe($_REQUEST['module'])." AND status = 1";
		$gatewayStatus = $db->select($query);
	}
	if (!$gatewayStatus) exit;
	
	$modulePath = "modules".CC_DS.$_REQUEST['type'].CC_DS.sanitizeVar($_REQUEST['module']).CC_DS;
	
	$module = fetchDbConfig($gatewayStatus[0]['folder']);
	
	switch(sanitizeVar($_REQUEST['cmd'])) {
		## Payment Gateway Callbacks
		case "call":
			$moduleFullPath = $modulePath."call.inc.php";
			break;
		## Process Payment
		case "process":
			$moduleFullPath = $modulePath."process.inc.php";
			break;
	}

	if (file_exists($moduleFullPath)) {
		require_once "classes".CC_DS."cart".CC_DS."order.php";
		$order = new order();
		include_once $moduleFullPath;
	} else {
		die("Module path doesn't exist!");
	}
}

if ($_REQUEST['cmd'] == "process") {
	/*
	1 = Payment Failed link to try again
	2 = Payment successful and complete 
	3 = Payment may or may not have been approved yet
	*/
	if (!isset($paymentResult)) { 
		$paymentResult = "3";
	}
	
	$redirect = $glob['storeURL']."/index.php?_g=co&_a=confirmed&amp;s=".$paymentResult;
	
	if(isset($cart_order_id) && !empty($cart_order_id)) {
		$redirect .= "&amp;cart_order_id=".$cart_order_id;
	}
	## Some payment modules mask URL's :( e.g WorldPay/PayPoint (headache)
	if($use_html_meta_refresh) {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Redirecting&hellip;</title>
<meta http-equiv="Refresh" content="0;URL='.$redirect.'" />
</head>

<body>
</body>
</html>';
	} else {
		httpredir($redirect);
	}
}
?>