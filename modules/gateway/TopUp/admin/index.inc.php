<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Printable Order Form
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("settings","read",$halt=TRUE);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['module'])){
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	## AH ## MC-CR to fix tdbug# 39  ## Start ## 
//	$carrierName 	= getCarrierName(1);
	//	$cache = new cache(strtolower("config.".$moduleName);
	$cache = new cache(strtolower($carrierName).".config.".$moduleName);
	## AH ## MC-CR to fix tdbug# 39  ## END ## 
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	## AH ## MC-CR to fix tdbug# 39  ## Start ## 
	//$msg = writeDbConf($_POST['module'], $moduleName, $module);
	$msg = writeDbConf($_POST['module'], $moduleName, $module, true, 1);
	## AH ## MC-CR to fix tdbug# 39  ## End ## 
	
}
## AH ## MC-CR to fix tdbug# 39  ## Start ## 
//$module = fetchDbConfig($moduleName);
$module = fetchDbConfig($moduleName,1);z
## AH ## MC-CR to fix tdbug# 39  ## End ## 
?>



<p class="pageTitle">Printable Order Form</p><br />

<p>This will display a page for the customer to print in order to pay via postal mail. </p><br />

<?php 
if(isset($msg))
{ 
	echo msg($msg); 
} 
?>
<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<div class="headingBlackbg">Configuration Settings</div>
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
  <tr>
    <td width="20%" align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select class="textbox5" name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>
	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select class="textbox5" name="module[default]">
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
	</select>	</td>
  </tr>
  <tr>
     	<td align="left" class="tdText"><strong>Description:</strong>
	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $module['desc']; ?>" class="textbox" size="30" />			</td>
   </tr>
   <tr>
     	<td align="left" class="tdText"><strong>Image:</strong>
	</td>
    <td class="tdText"> <input type="text" name="module[image]" value="<?php echo $module['image']; ?>" class="textbox" /></td>
   </tr>
  
    <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
