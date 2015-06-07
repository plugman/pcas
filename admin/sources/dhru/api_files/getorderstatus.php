<?php 

require_once ("../../../../ini.inc.php");
require_once ("../../../../includes".CC_DS."global.inc.php");
require_once ("../../../../includes".CC_DS."functions.inc.php");
require_once ("../../../../classes".CC_DS."db".CC_DS."db.php");
include ('dhrufusionapi.class.php');
$db = new db();
if($_POST['vendor']> 0){
$maped		= $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders WHERE id = ".$db->mySQLSafe($_POST['vendor']));

if(!empty($maped)){
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
$api = new DhruFusion();
$api->debug = false;
$para['ID'] = $_POST['refid']; // got REFERENCEID from placeimeiorder
$apirequest = $api->action('getimeiorder', $para, $maped[0]['vender_url'], $maped[0]['vender_user'], $maped[0]['vender_key']);

if(!empty($apirequest['SUCCESS'][0]))
print_r($apirequest['SUCCESS'][0]);
else
print_r($apirequest['ERROR'][0]);

}
}
				unset($api);
				unset($para);
				unset($apirequest);
				unset($apidata);
$db->close();
?>