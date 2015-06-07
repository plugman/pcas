<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../classes".CC_DS."session".CC_DS."cc_session.php");
$db = new db();
$cc_session = new session();
$config = fetchDbConfig("config");
if(isset($_POST['photoid']) && $_POST['photoid'] >0){
	$image = $db->select("SELECT image FROM ".$glob['dbprefix']."ImeiUnlock_user_images WHERE id=".$db->mySQLSafe($_POST["photoid"]));
	$rootMasterFile = CC_ROOT_DIR.CC_DS.'uploads'.CC_DS.'userdata'.CC_DS.$cc_session->ccUserData['sessId'].CC_DS.$image[0]['image'];
	if(file_exists($rootMasterFile)){
		@unlink($rootMasterFile);
			}		
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_user_images", "id=".$db->mySQLSafe($_POST["photoid"]));
	if($delete){
		echo '1';
	}
}
$db->close();
?>