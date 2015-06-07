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
if(isset($_POST['layoutid']) && $_POST['layoutid'] >0){
		$layouts = $db->select("SELECT layouthtml FROM ".$glob['dbprefix']."ImeiUnlock_case_layouts  WHERE id = ".$db->mySQLSafe($_POST['layoutid']));
		if($layouts){
			$html .=  str_replace("&nbsp;" , '' , $layouts[0]['layouthtml']);
		}
		echo '1::'.$html;
	}
$db->close();
?>