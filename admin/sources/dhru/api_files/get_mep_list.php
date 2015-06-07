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


$request = $api->action('meplist');


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>