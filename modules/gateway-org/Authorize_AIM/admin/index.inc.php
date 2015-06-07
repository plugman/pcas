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
|   Date: Friday, 15 July 2005
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Authorize AIM
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("settings","read",$halt=TRUE);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['module']))
{ 
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	$cache = new cache("config.".$moduleName);
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	$msg = writeDbConf($_POST['module'], $moduleName, $module);
}
$module = fetchDbConfig($moduleName);
?>

<p><a href="http://www.authorize.net/"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="" border="0" title="" /></a></p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
} 
?>
<p class="copyText">&quot;Your Gateway to IP Transactions.&quot;</p>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
    <tr>
    <td align="left" class="tdText"><strong>Enable Card Validation:</strong></td>
    <td class="tdText">
	<select name="module[validation]">
		<option value="1" <?php if($module['validation']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['validation']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
      <tr>
    <td align="left" class="tdText"><strong>Require CVV Code:</strong></td>
    <td class="tdText">
	<select name="module[reqCvv]">
		<option value="1" <?php if($module['reqCvv']==1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['reqCvv']==0) echo "selected='selected'"; ?>>No</option>
    </select>	</td>
  </tr>
   <tr>
  	<td align="left" class="tdText"><strong>Description:</strong>	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $module['desc']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Merchant API Login Id:</strong></td>
    <td class="tdText"><input type="text" name="module[acNo]" value="<?php echo $module['acNo']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText" colspan="2"><strong>Please complete either the Transaction Key OR Password field, not both!</strong></td>
  </tr>
   <tr>
  <td align="left" class="tdText"><strong>Transaction Key:</strong><br/>Please login and create this at <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a> (opens in new window)</td>
    <td class="tdText"><input type="text" name="module[txnkey]" value="<?php echo $module['txnkey']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Password:</strong><br/>Only required if  Password-Required Mode is enabled. </td>
    <td class="tdText"><input type="text" name="module[password]" value="<?php echo $module['password']; ?>" class="textbox" size="30" /></td>
  </tr>
   <tr>
     <td align="left" class="tdText"><strong>Testing:</strong></td>
     <td class="tdText"><select name="module[testMode]">
       <option value="1" <?php if($module['testMode'] == 1) echo "selected='selected'"; ?>>Yes</option>
       <option value="0" <?php if($module['testMode'] == 0) echo "selected='selected'"; ?>>No</option>
     </select></td>
   </tr>
   <tr>
   <td align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select name="module[default]">
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>	</td>
  </tr>
   
   <tr>
     <td align="left" class="tdText"><strong>Debugging: </strong></td>
     <td class="tdText"><select name="module[debug]">
       <option value="0" <?php if($module['debug'] == 0) echo "selected='selected'"; ?>>No</option>
	   <option value="1" <?php if($module['debug'] == 1) echo "selected='selected'"; ?>>Yes</option>
     </select></td>
   </tr>
   <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
