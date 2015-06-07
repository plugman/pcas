<?php 

require_once ("../../../../ini.inc.php");
require_once ("../../../../includes".CC_DS."global.inc.php");
require_once ("../../../../includes".CC_DS."functions.inc.php");
require_once ("../../../../classes".CC_DS."db".CC_DS."db.php");
include ('dhrufusionapi.class.php');
$db = new db();
if($_POST['vendor']> 0){
$required		=  $db->select("SELECT SERVICEID FROM ".$glob['dbprefix']."dhru_products".$_POST['vendor']." WHERE SERVICEID = ".$db->mySQLSafe($_POST['paraid'])." AND `Requires.Mobile` =".$db->mySQLSafe('Required'));
		if(!empty($required)){
			$vendata		= $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders WHERE id = ".$db->mySQLSafe($_POST['vendor']));
			define("REQUESTFORMAT", "JSON");
			$api = new DhruFusion();			
			// Debug on
			$api->debug = false;			
			$para['ID'] = $required[0]['SERVICEID']; // got from 'imeiservicelist' [SERVICEID]
			$request = $api->action('modellist', $para, $vendata[0]['vender_url'], $vendata[0]['vender_user'], $vendata[0]['vender_key']);
			if($request['SUCCESS'][0]['LIST']){
			$Branddata .='<select name="brand">';
				foreach ($request['SUCCESS'][0]['LIST'] as $Brands) {
             $Branddata .='<option value="'.$Brands["ID"].'">'.$Brands["NAME"].'</option>';
				}
             $Branddata .='</select>';
			 $modeldata .='<select name="model">';
			 foreach ($Brands['MODELS'] as $MODELS) {
			 $modeldata .='<option value="'.$MODELS["ID"].'">'.$MODELS["NAME"].'</option>';
			 }
			 $modeldata .='</select>';
			} 
			unset($required);
			unset($api);
			unset($request);
			unset($vendata);
		}
	
		
	$required		=  $db->select("SELECT SERVICEID FROM ".$glob['dbprefix']."dhru_products".$_POST['vendor']." WHERE SERVICEID = ".$db->mySQLSafe($_POST['paraid'])." AND `Requires.Provider` =".$db->mySQLSafe('Required'));
		if($required){
			$vendata		= $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders WHERE id = ".$db->mySQLSafe($_POST['vendor']));
			define("REQUESTFORMAT", "JSON");
			$api = new DhruFusion();			
			// Debug on
			$api->debug = false;			
			$para['ID'] = $required[0]['SERVICEID']; // got from 'imeiservicelist' [SERVICEID]
			$request = $api->action('providerlist', $para, $vendata[0]['vender_url'], $vendata[0]['vender_user'], $vendata[0]['vender_key']);
			if($request['SUCCESS'][0]['LIST']){
			$countrydata .='<select name="country">';
				foreach ($request['SUCCESS'][0]['LIST'] as $COUNTRY) {
             $countrydata .='<option value="'.$COUNTRY["ID"].'">'.$COUNTRY["NAME"].'</option>';
				}
             $countrydata .='</select>';
			 $providerdata .='<select name="provider">';
			 foreach ($COUNTRY['PROVIDERS'] as $PROVIDERS) {
			 $providerdata .='<option value="'.$PROVIDERS["ID"].'">'.$PROVIDERS["NAME"].'</option>';
			 }
			 $providerdata .='</select>';
			}
		}
		echo $Branddata;
		echo "::".$modeldata;
		echo "::".$countrydata;
		echo "::".$providerdata;
}

$db->close();
?>
