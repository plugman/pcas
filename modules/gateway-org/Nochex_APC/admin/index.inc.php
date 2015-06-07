<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock v3.0.15
|   ========================================
|   by Alistair Brookbanks
|	ImeiUnlock is a Trade Mark of Devellion Limited
|   Copyright Devellion Limited 2005 - 2006. All rights reserved.
|   Devellion Limited,
|   22 Thomas Heskin Court,
|   Station Road,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 3EE
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Date: Thursday, 4th January 2007
|   Email: sales (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Nochex
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
<p><a href="http://www.nochex.com/" target="_blank"><img src="modules/gateway/Nochex_APC/admin/logo.gif" alt="" border="0" title="Nochex" /></a></p>
<?php 
if(isset($msg)){ 
	echo stripslashes($msg); 
} 
?>
<p class="copyText">This gateway module is designed to work with the latest seller and merchant facilities offered by Nochex.</p>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Global Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong><br/>
    Enable or disable this payment gateway</td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>
	</td>
  </tr>
   <tr>
  	<td align="left" class="tdText"><strong>Description:</strong><br/>
  	The name of the gateway option as shown to customers
	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $module['desc']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Email Address:</strong><br/>
  Enter your Nochex-registered email address</td>
    <td class="tdText"><input type="text" name="module[email]" value="<?php echo $module['email']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Nochex Account Type</strong><br/>
    Choose the type of Nochex account you hold</td>
    <td class="tdText"><select name="module[accType]">
      <option value="seller" <?php if($module['accType'] == 'seller') echo "selected='selected'"; ?>>Seller / Personal Account</option>
      <option value="merchant" <?php if($module['accType'] == 'merchant') echo "selected='selected'"; ?>>Merchant Account</option>
    </select></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Merchant ID:</strong><br/>
  Merchant account holders can enter a merchant ID to <br/>use for the payments page, no effect for seller accounts.</td>
    <td class="tdText"><input type="text" name="module[merchant_id]" value="<?php echo $module['merchant_id']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Pass dynamic template to Nochex Payments Page?</strong><br/>
  Include your website header &amp; footer in the Nochex <br/>Payments Page (merchant accounts only!)</td>
      <td class="tdText">
	<select name="module[passTemplate]">
		<option value="1" <?php if($module['passTemplate'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['passTemplate'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>
	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong><br/>
    You may make test transactions without cost</td>
    <td class="tdText"><select name="module[testMode]">
      <option value="0" <?php if($module['testMode'] == 0) echo "selected='selected'"; ?>>Live Mode</option>
      <option value="1" <?php if($module['testMode'] == 1) echo "selected='selected'"; ?>>Test Mode</option>
    </select></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Default Gateway?</strong><br/>
  Make Nochex the default payment gateway on your site</td>
      <td class="tdText">
	<select name="module[default]">
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>
	</td>
  </tr>
  <tr>
    <td align="center" colspan="2" class="tdText"><input type="submit" class="submit" value="Save Config" /></td>
  </tr>
</table>
</form>