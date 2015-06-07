<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a Trade Mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	status.php
|   ========================================
|	Manage Module State
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

if (isset($_POST['module'])) {
	$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_Modules WHERE module = %s AND folder = %s", $db->mySQLSafe($module), $db->mySQLSafe($moduleName));
	$moduleState = $db->select($query);
	
	$data['status'] = $db->mySQLSafe($_POST['module']['status']);
	$data['default'] = $db->mySQLSafe($_POST['module']['default']);
	
	if($moduleType['default']==1){
	
		$resetData['default'] = 0;
		$where = "module = ".$db->mySQLSafe($module);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_Modules", $resetData, $where);
	
	}
	
	if($moduleState == true){
	
		$where = "module = ".$db->mySQLSafe($module)." AND folder = ".$db->mySQLSafe($moduleName);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_Modules", $data, $where);
	
	} else {
	
		$data['folder'] = $db->mySQLSafe($moduleName);
		$data['module'] = $db->mySQLSafe($module);
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_Modules", $data);
	
	}
}
?>