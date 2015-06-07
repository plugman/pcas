<?php	
include_once("includes/global.inc.php");
	include_once("classes/db/db.php");
	include_once("classes/cache/cache.php");
	include_once("ini.inc.php");
	$db = new db();
	include_once("includes/functions.inc.php");
	$config = fetchDbConfig("config");
$orders = $db->select("SELECT cart_order_id FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE status = ".$db->mySQLSafe(2) ."AND osend = ".$db->mySQLSafe(0));
//echo "<pre>";
//print_r($orders);
//die();
if($orders){
for($i=0;$i<count($orders);$i++){
	$responce = sendorderreq($orders[$i]['cart_order_id']);
}
}
$db->close();	
?>