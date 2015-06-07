<?php
/*
+--------------------------------------------------------------------------
|	xml.php
|   ========================================
|	XML OUT
+--------------------------------------------------------------------------
*/

if (!isset($skipload)) {
	require "ini.inc.php";
	require "includes".CC_DS."global.inc.php";
	require "classes".CC_DS."db".CC_DS."db.php";
	$db		= new db();
	require "includes".CC_DS."functions.inc.php";
	require "classes".CC_DS."cache".CC_DS."cache.php";
	//require($glob['adminFolder'].CC_DS."includes".CC_DS."functions.inc.php");
	$config = fetchDbConfig("config");
}

## Login check
if (!empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
	$sql	= sprintf("SELECT * FROM %sImeiUnlock_admin_users WHERE username = %s AND password = MD5(%s) AND isSuper = '1'", $glob['dbprefix'], $db->mySQLsafe($_REQUEST['username']), $db->mySQLsafe($_REQUEST['password']));
	if ($db->numrows($sql) == 1) {
		## Authorised
		## Generate XML data
		header('content-type: text/xml');		
		$xml[] = '<?xml version="1.0" encoding="utf-8"?>';
		$xml[] = '<ImeiUnlock>';
		$xml[] = '<apiversion>1</apiversion>';
		
		## Get all recent/pending orders
		$ordersql	= sprintf("SELECT cart_order_id, prod_total, time FROM %sImeiUnlock_order_sum WHERE status = 1 ORDER BY time DESC", $glob['dbprefix']);
		$xml[] = '<orders>';
		$xml[] = sprintf('<ordercount>%d</ordercount>', $db->numrows($ordersql));
		if ($db->numrows($ordersql) >= 1) {
			$orders = $db->select($ordersql);
			$xml[] = '<recentorders>';
			foreach ($orders as $order) {
				$xml[] = sprintf('<order><orderid>%s</orderid><value>%s</value></order>', $order['cart_order_id'], $order['prod_total']);
			}
			$xml[] = '</recentorders>';
		}
		$xml[] = '</orders>';
		
		## Get all review alerts
		$reviewsql	= sprintf("SELECT time FROM %sImeiUnlock_reviews WHERE approved = '0'", $glob['dbprefix']);
		$xml[] = '<reviews>';
		$xml[] = sprintf('<reviewcount>%d</reviewcount>', $db->numrows($reviewsql));
		$xml[] = '</reviews>';
		
		## Get all stock warnings
		
		if ($config['stock_warn_type'] == 1) {
			$stocksql	= sprintf("SELECT productId, stock_level FROM %sImeiUnlock_inventory WHERE useStockLevel = '1' AND stock_level < stockWarn ORDER BY stock_level ASC", $glob['dbprefix']);
		} else {
			if (!isset($config['stock_warn_level'])) $config['stock_warn_level'] = 5;
			$stocksql = sprintf("SELECT productId, stock_level FROM %sImeiUnlock_inventory WHERE useStockLevel = 1 AND stock_level <= %d ORDER BY stock_level ASC", $glob['dbprefix'], $config['stock_warn_level']);
		}
		
		$xml[] = '<stockwarning>';
		$xml[] = sprintf('<warningcount>%d</warningcount>', $db->numrows($stocksql));
		if ($db->numrows($stocksql) >= 1) {
			$stock = $db->select($stocksql);
			foreach ($stock as $warning) {
				//$xml[] = sprintf('<warning><product>%d</product><stocklevel>%d</stocklevel></warning>', $warning['productId'], $warning['stock_level']);
			}
		}
		$xml[] = '</stockwarning>';
		
		$xml[] = '</ImeiUnlock>';
		echo implode("\n", $xml);
		
		## All done :)
		
	} else {
		die('Access Denied');
	}
} else {
	die('Access Denied');
}
?>
