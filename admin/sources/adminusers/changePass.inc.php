<?php 
/*

|	changePass.inc.php
|   ========================================
|	Change Admin Password
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) { die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_adminusers.inc.php");

if(isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['confirmPass'])){
	
	$query = "SELECT `adminId`, `salt` FROM ".$glob['dbprefix']."ImeiUnlock_admin_users WHERE `adminId`=".$db->mySQLSafe($ccAdminData['adminId']);
	$salt = $db->select($query);
	
	$query = sprintf("SELECT `adminId` FROM ".$glob['dbprefix']."ImeiUnlock_admin_users WHERE `password` = %s AND `adminId` = %s", 
						$db->mySQLSafe(md5(md5($salt[0]['salt']).md5($_POST['oldPass']))),
						$db->mySQLSafe($ccAdminData['adminId']));
	$result = $db->select($query);
	if($result == true) {
		$salt = randomPass(6);
		$data['salt'] = $db->mySQLSafe($salt);
		$data['password'] = $db->mySQLSafe(md5(md5($salt).md5($_POST['newPass'])));
		$update = $db->update($glob['dbprefix']."ImeiUnlock_admin_users",$data,"adminId=".$result[0]['adminId']);
		$msg = "<p class='infoText'>".$lang['admin']['adminusers_pass_updated']."</p>";	
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['adminusers_pass_not_updated']."</p>";
	}
}
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
if(isset($msg)){ 
	echo msg($msg); 
}
?>
<p class="pageTitle"><?php echo $lang['admin']['adminusers_change_password']; ?></p>
<form action="<?php echo $glob['adminFile']; ?>?_g=adminusers/changePass" method="post" enctype="multipart/form-data" name="login" target="_self">
<table border="0" align="center" cellpadding="3" cellspacing="1" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php echo $lang['admin']['adminusers_change_pass_below']; ?></td>
    </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['adminusers_old_pass']; ?></td>
    <td><input name="oldPass" type="password" id="oldPass" class="textbox" /></td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['adminusers_new_pass']; ?></td>
    <td><input name="newPass" type="password" id="newPass" class="textbox" /></td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['adminusers_confirm_pass']; ?></td>
    <td><input name="confirmPass" type="password" id="confirmPass" class="textbox" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="login" type="submit" class="submit" id="login" value="<?php echo $lang['admin']['adminusers_update_pass']; ?>" /></td>
  </tr>
</table>
</form>