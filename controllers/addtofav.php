<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../classes".CC_DS."session".CC_DS."cc_session.php");
$db = new db();
if(isset($_POST['photoid']) && $_POST['photoid'] >0){
	$data['fav']  = $db->mySQLSafe(1);
	$where = "id = ".$_POST['photoid'];
	$update = $db->update($glob['dbprefix']."ImeiUnlock_user_images_success",$data,$where);
	if($update){
		echo '1';
	}
}
$db->close();
?>