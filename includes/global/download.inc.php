<?php
/*
+--------------------------------------------------------------------------|	download.inc.php
|   ========================================
|	Gathers the customers digital download	
+--------------------------------------------------------------------------
*/
	
if (!defined('CC_INI_SET')) die("Access Denied");


$query		= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_Downloads INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory ON ".$glob['dbprefix']."ImeiUnlock_Downloads.productId =  ".$glob['dbprefix']."ImeiUnlock_inventory.productId WHERE cart_order_id = ".$db->mySQLSafe(base64_decode($_GET['oid']))." AND ".$glob['dbprefix']."ImeiUnlock_Downloads.productId = ".$db->mySQLSafe($_GET['pid'])." AND accessKey = ".$db->mySQLSafe($_GET['ak'])." AND noDownloads<".$config['dnLoadTimes']." AND  expire>".time();
$download	= $db->select($query);

if ($download) {
	if (strstr($download[0]['digitalDir'], "ftp://") || strstr($download[0]['digitalDir'], "http://") || strstr($download[0]['digitalDir'], "https://")) {
		$record['noDownloads']	= "noDownloads + 1";
		
		$where					= "cart_order_id = ".$db->mySQLSafe(base64_decode($_GET['oid']))." AND productId = ".$db->mySQLSafe($_GET['pid'])." AND accessKey = ".$db->mySQLSafe($_GET['ak']);
		$update					= $db->update($glob['dbprefix']."ImeiUnlock_Downloads", $record, $where);
		httpredir($download[0]['digitalDir']);
	} else {
		
		$record['noDownloads']	= "noDownloads + 1";
		
		$where		= "cart_order_id = ".$db->mySQLSafe(base64_decode($_GET['oid']))." AND ".$glob['dbprefix']."ImeiUnlock_Downloads.productId = ".$db->mySQLSafe($_GET['pid'])." AND accessKey = ".$db->mySQLSafe($_GET['ak']);
		$update		= $db->update($glob['dbprefix']."ImeiUnlock_Downloads", $record, $where);
		
		## Close the session to allow for header() to be sent
		session_write_close();
	
		if (deliverFile($download[0]['digitalDir'])) {
			exit;
		} else {
			die ("There was an error dowloading the file. Please contact a member of support for help.");
		}
	}
} else {
	httpredir("index.php?_g=co&_a=error&code=10002");
}
?>