<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
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
|	index.inc.php
|   ========================================
|	Configure Card Capture
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("settings","read", true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['module'])){
	if ($_POST['module']['status']==1 && !function_exists('mcrypt_encrypt')) {
		$_POST['module']['status'] = false;
		$msg = "<p class='warnText'>This module requires the mcrypt PHP functions. It is not possible to enable it until this has been added by your server administrator.</p>";
	} else {
		require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
		$cache = new cache("config.".$moduleName);
		$cache->clearCache();
		//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
		$module = array(); // Comment this out if you don't want the old config to merge with new
		$msg = writeDbConf($_POST['module'], $moduleName, $module);
	}
	
}
$module = fetchDbConfig($moduleName);
?>
<p class="pageTitle">Manual Credit Card Capture</p><br />

<p>This modules captures the customers credit card information and securely encrypts it in the database for processing later. The credit card information can be viewed by vewing the customers order.</p><br />

<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>
<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<div class="headingBlackbg">Configuration Settings</div>
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td width="20%" align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select class="textbox5" name="module[status]">
		<option value="1" <?php if($module['status']==1 && function_exists('mcrypt_encrypt')) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0 || !function_exists('mcrypt_encrypt')) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
  <tr>
   <td align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select class="textbox5" name="module[default]">
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Enable Card Validation:</strong></td>
    <td class="tdText">
	<select class="textbox5" name="module[validation]">
		<option value="1" <?php if($module['validation']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['validation']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Card Issue Number / Issue Date:</strong></td>
    <td class="tdText">
	<select class="textbox5" name="module[issue_info]">
		<option value="1" <?php if($module['issue_info']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['issue_info']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Store CVV Security Code:</strong> <br />
(Please check with your merchant account provider that this is allowed before enabling!)</td>
    <td class="tdText">
	<select class="textbox5" name="module[cvv]">
		<option value="1" <?php if($module['cvv']==1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['cvv']==0) echo "selected='selected'"; ?>>No</option>
    </select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Require CVV Security Code:</strong> <br /></td>
    <td class="tdText">
	<select class="textbox5" name="module[cvv_req]">
		<option value="1" <?php if($module['cvv_req']==1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['cvv_req']==0) echo "selected='selected'"; ?>>No</option>
    </select>	</td>
  </tr>
   <tr>
  	<td align="left" class="tdText"><strong>Description:</strong>	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $module['desc']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Accepted Cards:</strong></td>
    <td class="tdText">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="tdText">Visa</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Visa]" value="1" <?php if($module['cards']['Visa']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">MasterCard</td>
    <td class="tdText"><input type="checkbox" name="module[cards][MasterCard]" value="1" <?php if($module['cards']['MasterCard']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">Discover</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Discover]" value="1" <?php if($module['cards']['Discover']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">American Express</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Amex]" value="1" <?php if($module['cards']['Amex']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">Bankcard</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Bankcard]" value="1" <?php if($module['cards']['Bankcard']==1) { echo "checked='checked'"; } ?> /> 
     </td>
  </tr>
  <tr>
    <td align="left" class="tdText">China UnionPay</td>
    <td class="tdText"><input type="checkbox" name="module[cards][China_UnionPay]" value="1" <?php if($module['cards']['China_UnionPay']==1) { echo "checked='checked'"; } ?> /> 
     </td>
  </tr>
  <tr>
    <td align="left" class="tdText">Diners Club</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Diners_Club]" value="1" <?php if($module['cards']['Diners_Club']==1) { echo "checked='checked'"; } ?> /> 
     </td>
  </tr>
  <tr>
    <td align="left" class="tdText">JCB</td>
    <td class="tdText"><input type="checkbox" name="module[cards][JCB]" value="1" <?php if($module['cards']['JCB']==1) { echo "checked='checked'"; } ?> /> 
     </td>
  </tr>
  <tr>
    <td align="left" class="tdText">Switch</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Switch]" value="1" <?php if($module['cards']['Switch']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">Maestro</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Maestro]" value="1" <?php if($module['cards']['Maestro']==1) { echo "checked='checked'"; } ?> /></td>
  </tr>
  <tr>
    <td align="left" class="tdText">Solo</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Solo]" value="1" <?php if($module['cards']['Solo']==1) { echo "checked='checked'"; } ?> /> 
</td>
  </tr>
  <tr>
    <td align="left" class="tdText">Laser</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Laser]" value="1" <?php if($module['cards']['Laser']==1) { echo "checked='checked'"; } ?> /> 
</td>
  </tr>
  <tr>
    <td align="left" class="tdText">Other</td>
    <td class="tdText"><input type="checkbox" name="module[cards][Other]" value="1" <?php if($module['cards']['Other']==1) { echo "checked='checked'"; } ?> /> 
</td>
  </tr>
  <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>