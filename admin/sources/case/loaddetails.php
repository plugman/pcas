<?php 
require_once ("../../../ini.inc.php");
require_once ("../../../includes".CC_DS."global.inc.php");
require_once ("../../../includes".CC_DS."functions.inc.php");
require_once ("../../../classes".CC_DS."db".CC_DS."db.php");
require_once ("../../../classes".CC_DS."cache".CC_DS."cache.php");
$db = new db();
if(isset($_POST['proid']) && $_POST['proid'] >0){
	$result = $db->select("SELECT I.name,I.price,I.description,I.productId,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE productId = ".$db->mySQLSafe($_POST['proid']). "  AND digital = '2' ");
	if($result){
		$html .= $result[0]['price'].'::'.$result[0]['productId']; 
		echo '1::'.$html;
	}
}
$db->close();
?>