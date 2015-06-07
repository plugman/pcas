<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "http://yoursite.com/");
define("USERNAME", "XXXXXXXX");
define("API_ACCESS_KEY", "XXX-XXX-XXX-XXX-XXX-XXX-XXX-XXX");
$api = new DhruFusion();


// Debug on
$api->debug = true;


$para['IMEI'] = "111111111111116";
$para['ID'] = "1382"; // got from 'imeiservicelist' [SERVICEID]
// PARAMETRES IS REQUIRED
// $para['MODELID'] = "";
// $para['PROVIDERID'] = "";
// $para['MEP'] = "";
// $para['PIN'] = "";
// $para['KBH'] = "";
// $para['PRD'] = "";
// $para['TYPE'] = "";
// $para['REFERENCE'] = "";
// $para['LOCKS'] = "";


$request = $api->action('placeimeiorder', $para);


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>