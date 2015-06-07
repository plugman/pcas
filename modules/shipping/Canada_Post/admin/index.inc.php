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
|   Date: Friday, 15 April 2005
|   Email: info@cubecart.com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Canada Post
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
##### Include these two line at the beginning of all your module's files #####
require_once CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'common.inc.php';
$modLoad = @new ModuleLoader(__FILE__, $config['defaultLang'], $language, $moduleName);
##############################################################################
?>
<?php if ($modLoad->message) echo msg($modLoad->message); ?>

<p><a href="http://www.canadapost.ca/"><img src="modules/shipping/Canada_Post/admin/logo.gif" alt="" border="0" title="" /></a></p>

<p class="pageTitle"><?php echo $language['lang_name']; ?></p>

<form action="?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php echo $modLoad->language['lang_title']; ?></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong><?php echo $modLoad->language['lang_status']; ?></strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if ($modLoad->settings['status'] == true) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['enabled']; ?></option>
		<option value="0" <?php if ($modLoad->settings['status'] == false) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['disabled']; ?></option>
    </select>	</td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong><?php echo $modLoad->language['lang_merchant_id']; ?></strong></td>
    <td class="tdText"><input type="text" name="module[merchant]" class="textbox" value="<?php echo $modLoad->settings['merchant']; ?>" /></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong><?php echo $modLoad->language['lang_postcode']; ?></strong></td>
    <td class="tdText"><input type="text" name="module[postcode]" class="textbox" value="<?php echo $modLoad->settings['postcode']; ?>" /></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong><?php echo $modLoad->language['lang_box_size']; ?></strong></td>
    <td class="tdText">
	  <input type="text" name="module[height]" value="<?php echo $modLoad->settings['height']; ?>" class="textbox" size="4" /> x
	  <input type="text" name="module[width]" value="<?php echo $modLoad->settings['width']; ?>" class="textbox" size="4" /> x
	  <input type="text" name="module[length]" value="<?php echo $modLoad->settings['length']; ?>" class="textbox" size="4" />
	</td>
  </tr>
  <tr>
    <td align="left" class="tdText">&nbsp;</td>
    <td class="tdText"><?php echo $modLoad->language['lang_box_help']; ?></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><?php echo $modLoad->language['lang_handling_fee']; ?></td>
    <td class="tdText"><input name="module[handling]" type="text" value="<?php echo $modLoad->settings['handling']; ?>" class="textbox" size="10" /></td>
  </tr>
  	<tr>
    <td align="left" class="tdText"><?php echo $modLoad->language['lang_tax_class']; ?></td>
    <td class="tdText">
	<?php
	$tax = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes");
	?>
	<select name="module[tax]">
	<?php for($i=0; $i<count($tax); $i++){ ?>
	<option value="<?php echo $tax[$i]['id']; ?>" <?php if($modLoad->settings['tax'] == $tax[$i]['id']) echo "selected='selected'"; ?>>
	<?php echo $tax[$i]['taxName']; ?>	</option>
	<?php } ?>
	</select>	</td>
  </tr>
    <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="<?php echo $lang['admin_common']['update']; ?>" /></td>
  </tr>
</table>
</form>