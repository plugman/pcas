<?php 
require_once ("../../ini.inc.php");
require_once ("../../includes".CC_DS."global.inc.php");
require_once ("../../includes".CC_DS."functions.inc.php");
require_once ("../../classes".CC_DS."db".CC_DS."db.php");
require_once ("../../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../../classes".CC_DS."session".CC_DS."cc_session.php");

$db = new db();
$cc_session = new session();
	$config = fetchDbConfig("config");	
require_once ("../../includes".CC_DS."currencyVars.inc.php");
require_once ("../../includes".CC_DS."sef_urls.inc.php");


if(isset($_POST['proid']) && $_POST['proid'] >0){
	$result = $db->select("SELECT I.name,I.price,I.description,I.productId,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE productId = ".$db->mySQLSafe($_POST['proid']). "  AND digital = '2' ");
	if($result){
		if (($val = prodAltLang($result[0]['productId'])) == true) {
			$result[0]['name'] = $val['name'];
		}
		$tree = getproglemtree($result[0]['cat_name'], $result[0]['cat_father_id'], $result[0]['cat_id']);
		$url = generateProductUrl($result[0]['productId']);
		$url = str_replace('prod', 'procedure', $url);
		$url2 = str_replace('procedure', 'Repair_Contact', $url);
		$html .= $result[0]['name'].'::'.priceFormat($result[0]['price']).'::'.$result[0]['description'].'::'.$result[0]['productId'].'::'.$tree.'::'.$url2.'::'.$url; 
		echo '1::'.$html;
	}
}
$db->close();
?>