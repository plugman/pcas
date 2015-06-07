<?php
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require("../classes".CC_DS."watermark".CC_DS."Thumbnail.class.php");
$db = new db();
    if(!empty($_POST['email'])){
		if($_POST['insta'] == 1)$field = 'username';else $field = 'email';
		$emailArray = $db->select("SELECT customer_id FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE ".$field."=".$db->mySQLSafe($_POST['email']));
		if($emailArray){
		echo "000";
		}else{
		echo "1000";
		}
	}else{
		die('Invalid or Not Allowed');
	}
   $db->close();
?>    