<?php
/*
+--------------------------------------------------------------------------
|	permissions.inc.php
|   ========================================
|	Set Admin Users Permissions
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_adminusers.inc.php");

permission("administrators","read",$halt=TRUE);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if($_POST['noSections']>0)
{

// delete all current permissions to replace them
$delete = $db->delete($glob['dbprefix']."ImeiUnlock_admin_permissions","adminId=".$db->mySQLSafe($_POST['adminId']));

for($i=0; $i<=$_POST['noSections']; $i++){
	
	$data['sectId'] = $db->mySQLSafe($_POST['sectId'.$i]);
	$data['read'] = $db->mySQLSafe($_POST['read'.$i]);
	$data['write'] = $db->mySQLSafe($_POST['write'.$i]);
	$data['edit'] = $db->mySQLSafe($_POST['edit'.$i]);
	$data['delete'] = $db->mySQLSafe($_POST['delete'.$i]);
	$data['adminId'] = $db->mySQLSafe($_POST['adminId']);
	$insert = $db->insert($glob['dbprefix']."ImeiUnlock_admin_permissions",$data);

}

$msg = "<p class='infoText'>".$lang['admin']['adminusers_perms_updated']."</p>";

}
?>
<p class="pageTitle"><?php echo $lang['admin']['adminusers_permissions']; ?></p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>
<p class="copyText"><?php echo $lang['admin']['adminusers_set_perms']; ?></p>
<form action="<?php echo $glob['adminFile']; ?>?_g=adminusers/permissions&amp;adminId=<?php echo $_GET['adminId']; ?>" method="post" enctype="multipart/form-data" target="_self">
<table  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
  	<td class="tdTitle"><?php echo $lang['admin']['adminusers_section']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin_common']['read']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin_common']['write']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin_common']['edit']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin_common']['delete']; ?></td>
  </tr>
<?php
$sectionsQuery = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_admin_sections";
$sectionsResult = $db->select($sectionsQuery);

if($sectionsResult == TRUE) {

	for($i=0; $i<count($sectionsResult); $i++) {


	$permissionsQuery =  "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_admin_permissions WHERE adminId = ".$db->mySQLSafe($_GET['adminId'])." AND sectId = ".$db->mySQLSafe($sectionsResult[$i]['sectId']);
	$permissionsResult = $db->select($permissionsQuery);
		
	$cellColor = "";
	$cellColor = cellColor($i); 
?>
  <tr>
  	<td class="<?php echo $cellColor; ?>"><span class="copyText"><strong><?php echo ucfirst($sectionsResult[$i]['name']); ?></strong> - <?php echo $sectionsResult[$i]['description']; ?></span><input type="hidden" name="sectId<?php echo $i; ?>" value="<?php echo $sectionsResult[$i]['sectId']; ?>" /></td>
    <td align="center" valign="middle" class="<?php echo $cellColor; ?>"><input name="read<?php echo $i; ?>" type="checkbox" value="1" <?php if($permissionsResult[0]['read']==1) { echo "checked='checked'"; } ?> /></td>
    <td align="center" valign="middle" class="<?php echo $cellColor; ?>"><input name="write<?php echo $i; ?>" type="checkbox" value="1" <?php if($permissionsResult[0]['write']==1) { echo "checked='checked'"; } ?> /></td>
    <td align="center" valign="middle" class="<?php echo $cellColor; ?>"><input name="edit<?php echo $i; ?>" type="checkbox" value="1" <?php if($permissionsResult[0]['edit']==1) { echo "checked='checked'"; } ?> /></td>
	    <td align="center" valign="middle" class="<?php echo $cellColor; ?>"><input name="delete<?php echo $i; ?>" type="checkbox" value="1" <?php if($permissionsResult[0]['delete']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <?php
  }
}
  ?>
  <tr>
    <td colspan="5" align="right">
	<input type="hidden" value="<?php echo $_GET['adminId']; ?>" name="adminId" />
	<input type="hidden" value="<?php echo $i; ?>" name="noSections" />
	<input name="Submit" type="submit" class="submit" id="Submit" value="Update Permissions" /></td>
  </tr>
</table>
</form>
<span class="copyText"><?php echo $lang['admin']['adminusers_nb_bulk']; ?></span>