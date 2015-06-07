<?php	
require_once ("ini.inc.php");
require_once ("includes" . CC_DS . "global.inc.php");
require_once ("includes" . CC_DS . "functions.inc.php");
require_once ("classes" . CC_DS . "db" . CC_DS . "db.php");
$db = new db();
require_once ("classes" . CC_DS . "session" . CC_DS . "cc_session.php");
require_once ("classes" . CC_DS . "cache" . CC_DS . "cache.php");
$config = fetchDbConfig("config");
if($_POST['vender']['username'] == 'imei-unlock.net' && $_POST['vender']['password'] == '9874563215789845645645648748974854564545454'){
	if($_POST['data']['data']){
		$counter = count($_POST['data']['data']);
		for($i=0;$i<$counter;$i++){
			$data['disabled'] = $db->mySQLSafe($_POST['data']['data'][$i]['disabled']);
			$where = "xmlproductId=".$db->mySQLSafe($_POST['data']['data'][$i]['productId'])." AND digital = '1'";
			$update[] = $db->update($glob['dbprefix']."ImeiUnlock_inventory", $data, $where);
		}
			if(count($update) == $counter)
			echo '1';
			else
			echo '0';
	}else die();
}else die();
$db->close();
?>