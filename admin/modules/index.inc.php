<?php
/*
+--------------------------------------------------------------------------
|	admin.php
|   ========================================
|	Selects which encoding method to use
+--------------------------------------------------------------------------
*/


if (!defined('CC_INI_SET')) die("Access Denied");

$section = ($_GET['module']=='shipping') ? 'shipping' : 'settings';
permission($section, "read", true);

$lang = getLang("admin".CC_DS."admin_misc.inc.php");

require $glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php";
?>
<p class="pageTitle" style="margin-bottom:10px;"><?php echo ucfirst($module); ?> Modules</p>

<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" class="mainTable mainTable4 maintable5">
  <tr>
	<td class="tdTitle"><?php echo $lang['admin']['misc_module_name']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['misc_module_action']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['misc_module_status']; ?></td>
  </tr>
<?php

## New Module Loader

$modulePath = CC_ROOT_DIR.CC_DS.'modules'.CC_DS.$module;
$moduleList = listAddons($modulePath);

if (is_array($moduleList)) {
	$i = 0;
	foreach ($moduleList as $moduleDir) {
		$cellColor = cellColor($i);
		if (file_exists($modulePath.CC_DS.$moduleDir.CC_DS.'admin'.CC_DS.'index.inc.php')) {
			$i++;
			## Display the module
			$moduleStatus	= fetchDbConfig($moduleDir);
			$moduleName		= str_replace('_', ' ', $moduleDir);
			
			if (file_exists($modulePath.CC_DS.$moduleDir.CC_DS.'admin'.CC_DS.'logo.gif')) {
				$logo = sprintf('<img height="62" width="252" src="modules/%s/%s/admin/logo.gif" alt="%s" />', $module, $moduleDir, $moduleName);
			} else {
				$logo = $moduleName;
			}
			
			$statusImage = sprintf('<img src="images/admin/%d.gif" width="10" height="10" alt="" />', $moduleStatus['status']);
			$configLink = "?_g=modules&amp;module=$module/$moduleDir";
?>
	<tr>
	  <td align="left" valign="top" class="<?php echo $cellColor; ?>" width="75%">
      <table width="100%" height="100%">
      <tr align="left" valign="middle">
      <td>
	  	<a href="<?php echo $configLink; ?>" class="txtLink"><?php echo $logo; ?></a>
        </td>
        </tr>
        </table>
	  </td>
	  <td align="center" valign="middle" class="<?php echo $cellColor; ?>" width="10%">
		<a href="<?php echo $configLink; ?>" class="txtLink"><img alt="" src="images/admin/shipsetting.png" /></a>
	  </td>
	  <td align="center" valign="middle" class="<?php echo $cellColor; ?>" width="10%">
		<?php echo $statusImage; ?>
	  </td>
	</tr>
<?php
		}
	}
}
?>
</table>