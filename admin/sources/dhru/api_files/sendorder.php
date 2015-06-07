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
$para['IMEI'] = $_POST['imei'];
$para['ID'] = $_POST['serviceid']; // got from 'imeiservicelist' [SERVICEID]
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


$apirequest = $api->action('placeimeiorder', $para, $maped[0]['vender_url'], $maped[0]['vender_user'], $maped[0]['vender_key']);
if($apirequest['SUCCESS'][0]['REFERENCEID'] > 0){
			$data['REFERENCEID'] = $apirequest['SUCCESS'][0]['REFERENCEID'];
			$where = "id = ".$db->mySQLSafe($_POST['orderid']);
			$db->update($glob['dbprefix']."ImeiUnlock_order_inv", $data, $where);	
			print_r($apirequest['SUCCESS'][0]);
}else
print_r($apirequest);
}
}
unset($api);
				unset($para);
				unset($apirequest);
				unset($apidata);
$db->close();
?>