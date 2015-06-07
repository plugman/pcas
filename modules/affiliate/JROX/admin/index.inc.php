<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a Trade Mark of Devellion Limited
|   Copyright Devellion Limited 2005 - 2006. All rights reserved.
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
|	index.inc.php
|   ========================================
|	Configure JROX.COM Affiliate Manager
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("settings","read",$halt=TRUE);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['module'])){
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	$cache = new cache("config.".$moduleName);
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	$msg = writeDbConf($_POST['module'], $moduleName, $module);

}
$module = fetchDbConfig($moduleName);
?>

<p><a href="http://jam.jrox.com" target="_blank"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="JROX.COM Affiliate Manager" border="0" title="" /></a></p>
<?php
if(isset($msg)){
	echo stripslashes($msg);
}
?>
<form action="<?php echo $module['URL']; ?>/admin/" id="fx_admin_login_form" name="fx_admin_login_form" method="post" target="_blank">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
	<tr>
		<td colspan="2" class="tdTitle">Login to JAM (Opens in new window) </td>
	</tr>
	<tr>
		<td align="left" class="tdText">Admin Username:</td>
		<td class="tdText"><input name="jX_User_Name" type="text" id="jX_User_Name" value="" size="20" maxlength="20" /></td>
	</tr>
	<tr>
		<td align="left" class="tdText">Admin Password:</td>
	  <td class="tdText"><input name="jX_Pass_Word" type="password" id="jX_Pass_Word" value="" size="20" maxlength="20" /></td>
	</tr>
	<tr>
		<td align="right" class="tdText">&nbsp;</td>
		<td class="tdText"><input type="submit" class="submit" value="Login As Admin" /><input name="fx_adm_login" type="hidden" id="fx_adm_login" value="fx_login" />
									<input name="adm_req" type="hidden" id="adm_req" value="fx_validate" /></td>
	</tr>
</table>
</form>
<br />
<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
	<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
	<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdText"><strong>URL to JAM:</strong></td>
    <td class="tdText"><input type="text" name="module[URL]" value="<?php echo $module['URL']; ?>" class="textbox" size="40" />
	</td>
  </tr>
  <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>


