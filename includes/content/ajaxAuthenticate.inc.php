<?php
/*
+--------------------------------------------------------------------------
|	login.inc.php
|   ========================================
|	Assign customer id to session	
+--------------------------------------------------------------------------
*/
require_once ("../../ini.inc.php");
require_once ("../../includes" . CC_DS . "global.inc.php");
require_once ("../../includes" . CC_DS . "functions.inc.php");
require_once ("../../classes" . CC_DS . "db" . CC_DS . "db.php");
$db = new db();
require_once ("../../classes" . CC_DS . "session" . CC_DS . "cc_session.php");
require_once ("../../classes" . CC_DS . "cache" . CC_DS . "cache.php");


$cc_session = new session();
$config = fetchdbconfig("config");

## Logging-CR [MI]: Include language file for logging
$lang_log = getLang("log.inc.php");

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
	$remember = (!empty($_REQUEST['remember'])) ? true : false;
	$user		= sanitizeVar($_REQUEST['username']);
		
 	$query = "SELECT `customer_id`, `salt` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `type` > 0 AND `email`=".$db->mySQLSafe($user);
	$salt = $db->select($query);
		
	 $passMD5	= md5(md5($salt[0]['salt']).md5($_REQUEST['password']));	
	 $query = "SELECT `customer_id`, `email` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE email=".$db->mySQLSafe($user)." AND `password` = ".$db->mySQLSafe($passMD5)." AND type > 0 ";
	
	$customer = $db->select($query);
	
	if(!empty($customer))
	{
		if($customer[0]['block'] == 1) {
			echo "0";
			## Start-Logging-CR [MI]: Log Login Success Message
		//	msg_user($customer[0]['email']." blocked!");
			## End-Logging-CR [MI]: Log Login Success Message
		} else {
			echo "1";
			## Start-Logging-CR [MI]: Log Login Success Message
			//msg_user($customer[0]['email']." authenticated!");
			## End-Logging-CR [MI]: Log Login Success Message
	
			}
	} else {
		echo "0";
		## Start-Logging-CR [MI]: Log Login Failure Message
		//msg_user($_REQUEST['username']." ".$lang_log['log']['log_user_login_fail']);
		## End-Logging-CR [MI]: Log Login Failure Message
	}
}else{
	echo "0";
}
exit();
?>