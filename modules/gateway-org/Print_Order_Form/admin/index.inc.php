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
|	Configure Printable Order Form
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



<p class="pageTitle">Printable Order Form</p>
<p>This will display a page for the customer to print in order to pay via postal mail. </p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
} 
?>
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
    </select>
	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select name="module[default]">
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
	</select>	</td>
  </tr>
    <tr>
  	<td align="left" class="tdText"><strong>Allow Payment in multiple Currencies?:</strong>
	</td>
    <td class="tdText">
	<select name="module[multiCurrency]">
       <option value="1" <?php if($module['multiCurrency']==1) echo "selected='selected'"; ?>>Yes</option>
       <option value="0" <?php if($module['multiCurrency']==0) echo "selected='selected'"; ?>>No</option>
     </select>
	 </td>
  <tr>
  	<td align="left" class="tdText"><strong>Description:</strong>
	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $module['desc']; ?>" class="textbox" size="30" />			</td>
   <tr>
     <td align="left" class="tdText"><strong>Allow Cheque Payments?</strong></td>
     <td class="tdText">
	 <select name="module[cheque]">
       <option value="1" <?php if($module['cheque']==1) echo "selected='selected'"; ?>>Yes</option>
       <option value="0" <?php if($module['cheque']==0) echo "selected='selected'"; ?>>No</option>
     </select></td>
   </tr>
   <tr>
  	<td align="left" class="tdText"><strong>Checks payable to:</strong>
	</td>
    <td class="tdText"><input type="text" name="module[payableTo]" value="<?php echo $module['payableTo']; ?>" class="textbox" size="30" /></td>
  </tr>
   <tr>
     <td align="left" class="tdText"><strong>Allow Card Payments?</strong></td>
     <td class="tdText"><select name="module[card]">
       <option value="1" <?php if($module['card']==1) echo "selected='selected'"; ?>>Yes</option>
       <option value="0" <?php if($module['card']==0) echo "selected='selected'"; ?>>No</option>
     </select></td>
   </tr>
   <tr>
  <td align="left" class="tdText"><strong>Cards Accepted:</strong><br />
    (Comma separated)
  </td>
    <td class="tdText"><input type="text" name="module[cards]" value="<?php echo $module['cards']; ?>" class="textbox" size="30" /></td>
  </tr>
    <tr>
      <td align="left" class="tdText"><strong>Allow Bank Transfer?</strong></td>
      <td class="tdText"><select name="module[bank]">
        <option value="1" <?php if($module['bank']==1) echo "selected='selected'"; ?>>Yes</option>
        <option value="0" <?php if($module['bank']==0) echo "selected='selected'"; ?>>No</option>
      </select>
	  </td>
    </tr>
      <td align="left" class="tdText"><strong>Bank Name:</strong></td>
      <td class="tdText"><input type="text" name="module[bankName]" value="<?php echo $module['bankName']; ?>" class="textbox" size="30" />
	</td>
  </tr>
    <tr>
      <td align="left" class="tdText"><strong>Account Name: </strong></td>
      <td class="tdText"><input type="text" name="module[accName]" value="<?php echo $module['accName']; ?>" class="textbox" size="30" /></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Sort Code: </strong></td>
      <td class="tdText"><input type="text" name="module[sortCode]" value="<?php echo $module['sortCode']; ?>" class="textbox" size="30" /></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Account Number: </strong></td>
      <td class="tdText"><input type="text" name="module[acNo]" value="<?php echo $module['acNo']; ?>" class="textbox" size="30" /></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Swift Code: </strong></td>
      <td class="tdText"><input type="text" name="module[swiftCode]" value="<?php echo $module['swiftCode']; ?>" class="textbox" size="30" /></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="tdText"><strong>Address:</strong></td>
      <td class="tdText"><textarea name="module[address]" cols="30" rows="5"><?php echo $module['address']; ?></textarea></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="tdText"><strong>Notes to Customer: </strong></td>
      <td class="tdText"><textarea name="module[notes]" cols="30" rows="5"><?php echo $module['notes']; ?></textarea></td>
    </tr>
    <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
