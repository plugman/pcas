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
|   Licence Info: http://www.cubecart.com/site/faq/license.php
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Protx
+--------------------------------------------------------------------------
*/

##### Include these two line at the beginning of all your module's files #####
require_once CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'common.inc.php';
$modLoad = @new ModuleLoader(__FILE__, $config['defaultLang'], $language, $moduleName);
##############################################################################
?>
<?php if ($modLoad->message) echo msg($modLoad->message); ?>

<p><a href="http://www.eway.com.au/"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="" border="0" title="" /></a></p>

<p class="copyText">&quot;e-commerce the easy way.&quot;</p>

<form action="?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if($modLoad->settings['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($modLoad->settings['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>
	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Enable Card Validation:</strong></td>
    <td class="tdText">
	<select name="module[validation]">
		<option value="1" <?php if($modLoad->settings['validation']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($modLoad->settings['validation']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Test Mode:</strong></td>
    <td class="tdText">
	<select name="module[test]">
		<option value="1" <?php if($modLoad->settings['test']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($modLoad->settings['test']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
   <tr>
  	<td align="left" class="tdText"><strong>Description:</strong>
	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php echo $modLoad->settings['desc']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Merchant Id:</strong></td>
    <td class="tdText"><input type="text" name="module[acNo]" value="<?php echo $modLoad->settings['acNo']; ?>" class="textbox" size="30" /></td>
  </tr>
  <td align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select name="module[default]">
		<option value="1" <?php if($modLoad->settings['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($modLoad->settings['default'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>
	</td>
  </tr>
  <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>