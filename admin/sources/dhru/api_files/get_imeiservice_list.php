<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON");
define('DHRUFUSION_URL', "http://gsmeasy.biz/api/index.php");
define("USERNAME", "imeiunlock");
define("API_ACCESS_KEY", "DBN-88H-Q57-IKW-VO7-T5D-QQG-F8P");
$api = new DhruFusion();

// Debug on
$api->debug = true;


$request = $api->action('imeiservicelist');


echo '<PRE>';
//print_r($request['SUCCESS'][0]['LIST']['Romania']['SERVICES']['252']);
foreach ($request['SUCCESS'][0]['LIST'] as $GROUPNAME) {
	//print_r( $GROUPNAME['GROUPNAME']);
	$catdata = $GROUPNAME['GROUPNAME'];
	$insert = $db->insert("tbl_topup_payment_transactions", $record);
	foreach ($GROUPNAME['SERVICES'] as $SERVICES) {
	$prodata = $GROUPNAME['GROUPNAME'];
	$insert = $db->insert("tbl_topup_payment_transactions", $record);
	//print_r($SERVICES['SERVICENAME']);
	echo '<br/>';
}
}

?>