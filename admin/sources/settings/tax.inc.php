<?php
/*
+--------------------------------------------------------------------------
|	tax.inc.php
|   ========================================
|	Setup Tax Types & Tax Zones	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_settings.inc.php");

permission("settings","read",$halt=TRUE);

if(isset($_POST['config'])) {
	$cache = new cache("config.config");
	$cache->clearCache();
	$config = fetchDbConfig("config");
	$msg = writeDbConf($_POST['config'],"config", $config, "config");
}
$config = fetchDbConfig("config");

// start: Flexible Taxes, by Estelle Winterflood
function selected($var,$val){
	if ($var==$val){
		echo "selected";
	}
}

$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");

if (!$config_tax_mod){
	$cache = new cache();
	$cache->clearCache();
	
	// setup default values for initial install
	$config_tax_mod['status']=0;
	$config_tax_mod['debug']=0;
	writeDbConf($config_tax_mod, "Multiple_Tax_Mod", $config_tax_mod);
}
// end: Flexible Taxes

if(isset($_POST['taxId'])){
	
	$cache = new cache();
	$cache->clearCache();
	
	$record["taxName"] = $db->mySQLSafe($_POST['taxName']);		
	$record["percent"] = $db->mySQLSafe($_POST['percent']);

	if($_POST['taxId']>0){
								
		$where = "id = ".$db->mySQLSafe($_POST['taxId']);
	
		$update =$db->update($glob['dbprefix']."ImeiUnlock_taxes", $record, $where);
		
		if($update){
			$msgTaxType = "<p class='infoText'>'".$_POST['taxName']."' ".$lang['admin']['settings_update_success']."</p>";
		} else {
			$msgTaxType = "<p class='warnText'>'".$_POST['taxName']."' ".$lang['admin']['settings_update_fail']."</p>";
		}
	
	} else {
	
		$insert =$db->insert($glob['dbprefix']."ImeiUnlock_taxes", $record);
		
		if($insert){
			$msgTaxType = "<p class='infoText'>'".$_POST['taxName']."' ".$lang['admin']['settings_add_success']."</p>";
		} else {
			$msgTaxType = "<p class='warnText'>'".$_POST['taxName']."' ".$lang['admin']['settings_add_fail']."</p>";
		}
	
	}

} elseif(isset($_GET['delete'])) {

	$cache = new cache();
	$cache->clearCache();
	
	$where = "id = ".$db->mySQLSafe($_GET['delete']);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_taxes", $where, ""); 

	if($delete == TRUE) {
		$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_delete_success']."</p>";
	} else {
		$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_delete_failed']."</p>";
	}

}
// start: Flexible Taxes, by Estelle Winterflood
// Config
elseif(isset($_POST['config_tax_mod'])) {
	$cache = new cache();
	$cache->clearCache();
	$msg = writeDbConf($_POST['config_tax_mod'], "Multiple_Tax_Mod", $config_tax_mod);
	$config_tax_mod = $_POST['config_tax_mod'];
}
// Update Tax Details
elseif(isset($_POST['details'])) {
	
	$cache = new cache();
	$cache->clearCache();
	
	$detailsArray = $_POST['details'];

	$update = 0;
	$error = 0;
	foreach ($detailsArray as $details) {
		$record["name"] = $db->mySQLSafe($details['name']);
		$record["display"] = $db->mySQLSafe($details['display']);
		$record["reg_number"] = $db->mySQLSafe($details['reg_number']);
		$record["status"] = $db->mySQLSafe($details['status']);

		$where = "id = ".$db->mySQLSafe($details['id']);
		
		$check = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_details WHERE name=".$record['name']." AND id<>".$db->mySQLSafe($details['id']));
		if ($check) {
			$error++;
		} else {
			$res = $db->update($glob['dbprefix']."ImeiUnlock_tax_details", $record, $where);
			if ($res) {
				$update++;
			}
		}
	}
	$msgTaxType = "";
	
	if($update) {
		$msgTaxType = "<p class='infoText'>".$update." ".$lang['admin']['settings_tax_details_upd_success']."</p>";
	} elseif (!$error) {
		$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_tax_details_upd_failure']."</p>";
	} if ($error) {
		$msgTaxType .= "<p class='warnText'>".$error." ".$lang['admin']['settings_tax_details_upd_error']."</p>";
	}
}
// Add Tax Details
elseif(isset($_POST['new_details'])) {
	$cache = new cache();
	$cache->clearCache();
	
	$details = $_POST['new_details'];

	$record["name"] = $db->mySQLSafe($details['name']);
	$record["display"] = $db->mySQLSafe($details['display']);
	$record["reg_number"] = $db->mySQLSafe($details['reg_number']);
	$record["status"] = $db->mySQLSafe($details['status']);

	$check = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_details WHERE name=".$record['name']);
	if($check) {
		$insert = false;
	} else {
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_tax_details", $record);
	}
		
	if($insert) {
		$msgTaxType = "<p class='infoText'>'".$details['name']."' ".$lang['admin']['settings_add_success']."</p>";
	} else {
		$msgTaxType = "<p class='warnText'>'".$details['name']."' ".$lang['admin']['settings_add_fail']."</p>";
	}

}
// Delete Tax Details
elseif(isset($_GET['delete_details'])) {
	$where = "id = ".$db->mySQLSafe($_GET['delete_details']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_tax_details", $where, ""); 
	if($delete){
		$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_tax_details_del_success']."</p>";
	} else {
		$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_tax_details_del_failure']."</p>";
	}

}

// Lookup Existing Taxes/Config
if ($config_tax_mod['status']) {
	$taxDetails = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_details ORDER BY id");

	$taxRates = $db->select("SELECT r.id, type_id, t.taxName, details_id, d.name, country_id, county_id, tax_percent, goods, shipping, active, status FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates AS r LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_tax_details AS d ON r.details_id = d.id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_taxes AS t ON r.type_id = t.id ORDER BY type_id, country_id, county_id, d.id");

	$country_names = array();
	$all_countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries");
	
	if($taxRates) {
	
		foreach ($taxRates as $rate) {
			$id = $rate['country_id'];
			foreach ($all_countries as $country) {
				if ($id == $country['id']){
					$country_names[$id] = $country['printable_name'];
					break;
				}
			}
		}
	
	}

	$taxTypes = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes");

	$defaultCountry = $db->select("SELECT id FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries WHERE iso='US'"); 
	
	if (is_array($defaultCountry)){
		$defaultCountry = $defaultCountry[0]['id'];
	} else {
		$defaultCountry = 1;
	}
}
// end: Flexible Taxes

$jsScript = jsGeoLocation("taxCountry", "taxCounty", "-- ".$lang['admin_common']['all']." --");
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>

<p class="pageTitle"><?php echo $lang['admin']['settings_tax_settings']; ?></p>
<?php
if(isset($msg)) { 
	echo msg($msg); 
}
if(isset($msgTaxType)) { 
	echo msg($msgTaxType); 
}
?>
<!-- Flexible Taxes - Configuration -->


	<form name="configForm" action="<?php echo $config['adminFile']; ?>?_g=settings/tax" method="post" enctype="multipart/form-data">
	<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
	<tr>
	  <td class="tdTitle">
	    <?php echo $lang['admin']['settings_multi_tax_config']; ?>
	  </td>
	</tr>
	<tr valign="middle">
	  <td class="tdText">
	    <p style="margin: 0;"><?php echo $lang['admin']['settings_multi_tax_info']; ?></p>
	    <p style="margin: 0.8em 0;"><strong><?php echo $lang['admin']['settings_status']; ?></strong> <?php echo $lang['admin']['settings_status_help']; ?></p>
	    <p style="margin: 0.5em 0;"><strong><?php echo $lang['admin']['settings_mode']; ?></strong> <?php echo $lang['admin']['settings_mode_help']; ?></p>
	  </td>
	</tr>
	<tr valign="middle" align="left">
	  <td class="tdText">
	    <strong><?php echo $lang['admin']['settings_status']; ?></strong>
	    <select class="textbox" name="config_tax_mod[status]">
	      <option value="1" <?php selected($config_tax_mod['status'],1); ?> ><?php echo $lang['admin']['settings_enabled']; ?></option>
	      <option value="0" <?php selected($config_tax_mod['status'],0); ?> ><?php echo $lang['admin']['settings_disabled']; ?></option>
	    </select>
		&nbsp;
	    <strong><?php echo $lang['admin']['settings_mode']; ?></strong>
	    <?php echo $lang['admin']['related_prods']['status']; ?>
	    <select class="textbox" name="config_tax_mod[debug]">
	      <option value="0" <?php selected($config_tax_mod['debug'],0); ?> ><?php echo $lang['admin']['settings_live']; ?></option>
	      <option value="1" <?php selected($config_tax_mod['debug'],1); ?> ><?php echo $lang['admin']['settings_testing']; ?></option>
	    </select>
		&nbsp;
	    <input type="submit" class="submit" value="<?php echo $lang['admin']['settings_update']; ?>" />
	  </td>
	</tr>
	</table>
	</form>
<?php
if ($config_tax_mod['status']==1 && $config_tax_mod['debug']==1) {
	echo "<p class='warnText'>".$lang['admin']['settings_tax_warn_testing']."</p>";
} else {
	echo "<br/>";
}

if ($config_tax_mod['status']==0) {
		//---- OLD TAX SETTINGS - Taxable State/Country ----//
?>
<form name="updateSettings" method="post" enctype="multipart/form-data" target="_self">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
	<tr>
		<td colspan="2" class="tdTitle"><strong><?php echo $lang['admin']['settings_tax_only_to']; ?></strong></td>
	</tr>
	<tr>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_country']; ?></strong></td>
	  <td align="left">
	  <?php
		$countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name ASC");
		?>
	
	<select name="config[taxCountry]" id="taxCountry" onChange="updateCounty(this.form);">
	<?php
	for($i=0; $i<count($countries); $i++){
	?>
	<option value="<?php echo $countries[$i]['id']; ?>" <?php if($countries[$i]['id'] == $config['taxCountry']) echo "selected='selected'"; ?>><?php echo $countries[$i]['printable_name']; ?></option>
	<?php } ?>
	</select></td>
    </tr>
	<tr>
	  <td align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_zone'];?></strong></td>
	  <td align="left">
	  <?php
		$counties = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = '".$config['taxCountry']."' ORDER BY countryId, name ASC");
	  ?>
	  <select name="config[taxCounty]" id="taxCounty">
	  
	  <option value="" <?php if(empty($config['taxCounty'])) { ?>selected='selected'<?php } ?>>-- <?php echo $lang['admin_common']['all'];?> --</option>
	  
	  <?php for($i=0; $i<count($counties); $i++){ ?>
	  <option value="<?php echo $counties[$i]['id']; ?>" <?php if($counties[$i]['id']==$config['taxCounty']) echo "selected='selected'"; ?>><?php echo $counties[$i]['name']; ?></option>
	  <?php } ?>
      </select></td>
	</tr>
	<tr>
	  <td align="left" valign="top" class="tdText">&nbsp;</td>
	  <td align="left"><input name="submitTax" type="submit" class="submit" value="<?php echo $lang['admin_common']['update']; ?>" /></td>
    </tr>
</table>
</form>
<br/>
<?php

} // End OLD TAX SETTINGS

//---- TAX TYPES/CLASSES - Edit ----//

if ($config_tax_mod['status']) {
?>
<p class="copyText"><strong>
1. <?php echo $lang['admin']['settings_tax_classes']; ?>
</strong>
</p>
<?php
}

$taxTypes = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes ");
?>
<form name="taxTypes" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
	<tr>
		<td class="tdTitle"><strong><?php echo $lang['admin']['settings_tax_class2'];?></strong></td>
	    <?php
if (!$config_tax_mod['status']){
?><td class="tdTitle"><strong><?php echo $lang['admin']['settings_rate_per'];?></strong></td><?php } ?>
	    <td colspan="2" align="center" class="tdTitle"><strong><?php echo $lang['admin']['settings_action'];?></strong></td>
	</tr>
	<?php if($taxTypes == TRUE){  for($i=0; $i<count($taxTypes);$i++){ ?>
			<tr>
			  <td class="tdText"><?php echo $taxTypes[$i]['taxName']; ?></td>
<?php
if (!$config_tax_mod['status']){
?>
			  <td class="tdText"><?php echo $taxTypes[$i]['percent']; ?></td>
<?php
}
?>
			  <td align="center"><a <?php if(permission("settings","edit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=settings/tax&amp;edit=<?php echo $taxTypes[$i]['id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a></td>
			  <td align="center">
			  <?php
			  // check for product dependancies
			  $noProducts = $db->select("SELECT `productId` FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE taxType=".$taxTypes[$i]['id']);
			  $noRates = false;
			  if ($config_tax_mod['status']) {
				  $noRates = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE type_id=".$taxTypes[$i]['id']);
			  }
 
			  if($noProducts == FALSE && $noRates == FALSE){
			  ?>
			  <a <?php if(permission("settings","delete")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=settings/tax&amp;delete=<?php echo $taxTypes[$i]['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a>
			  <?php
			  } else {
			  ?>
			  <a href="javascript:;" class="txtNullLink"><?php echo $lang['admin_common']['delete'];?></a>
			  <?php
			  }
			  ?>
			  
			  </td>
			</tr>
		<?php }  } else { ?>
	<tr>
	  <td class="tdText" colspan="4"><?php echo $lang['admin']['settings_no_taxes_setup'];?></td>
	</tr>
	<?php }  
	if(isset($_GET['edit']) && $_GET['edit']>0){
		
		$editTax = $db->select("select * FROM ".$glob['dbprefix']."ImeiUnlock_taxes WHERE id = ".$db->mySQLsafe($_GET['edit']));
	
	}
	?>
	<tr>
		<td><input name="taxName" class="textbox" type="text" maxlength="50" value="<?php if(isset($editTax[0]['taxName'])) echo $editTax[0]['taxName']; ?>" /></td>
<?php
	if (!$config_tax_mod['status']) {
?>
		<td align="center" class="tdText"><input name="percent" type="text" class="textbox" value="<?php if(isset($editTax[0]['percent'])) echo $editTax[0]['percent']; ?>" size="6" /> %</td>
<?php
	}
?>
		<td colspan="2" align="center"><input name="submit" type="submit" class="submit" id="submit" value="<?php if(isset($editTax) && $editTax==TRUE){ echo $lang['admin_common']['edit']; } else{ echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['settings_tax'];?>" /></td>
	</tr>
</table>
<input type="hidden" name="taxId" value="<?php echo $editTax[0]['id']; ?>" />
</form>


<?php
if ($config_tax_mod['status'])
{
?>

<!-- Flexible Taxes - Tax Details -->


<p class="copyText"><strong>
2. <?php echo $lang['admin']['settings_tax_details']; ?></strong> &nbsp;
<a href="#" class="txtLink" onclick="javascript:
if (findObj('help1').style.display=='none') {
  findObj('help1').style.display='block';
} else {
  findObj('help1').style.display='none';
}
return false;"><?php echo $lang['admin']['settings_show_help']; ?></a>
</p>
<div id="help1" style="display:none;">
	<p class="copyText"><?php echo $lang['admin']['settings_tax_details_help']; ?></p>
</div>

<form name="taxDetails" action="<?php echo $glob['adminFile']; ?>?_g=settings/tax" method="post" enctype="multipart/form-data" target="_self">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
	<tr>
		<td colspan="6" class="tdTitle"><strong><?php echo $lang['admin']['settings_tax_details']; ?> - <?php echo $lang['admin']['settings_add_edit_delete']; ?></strong></td>
	</tr>
<?php
	if (is_array($taxDetails))
	{
?>
	<tr>
	  <td>&nbsp;</td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_name']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_display_as']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_reg_number']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_status']; ?></strong></td>
	  <td>&nbsp;</td>
	</tr>
<?php
		for ($i=0; $i<count($taxDetails); $i++)
		{
			$details = $taxDetails[$i];
			$id = $details['id'];
?>
	<tr>
	  <td>
<?php
			// check for dependancies and permission
			$noRates = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE details_id=".$id);
			if($noRates==false && permission("settings","delete"))
			{
?>
		  <a href="<?php echo $glob['adminFile']; ?>?_g=settings/tax&amp;delete_details=<?php echo $id; ?>" class="txtLink"><img src="<?php echo $glob['adminFolder']; ?>/images/del.gif" alt="delete" title="Click To Delete" width="12" height="12" border="0" style="padding-right: 5px;" /></a>
<?php
			} else {
?>
		  <img src="<?php echo $glob['adminFolder']; ?>/images/no_del.gif" alt="can't delete" title="Can't Delete" width="12" height="12" border="0" style="padding-right: 5px;" />
<?php
			}
?>
	  </td>
	  <td>
		  <input type="hidden" class="textbox" name="details[<?php echo $id; ?>][id]" value="<?php echo $id; ?>" />

		  <input type="text" class="textbox" name="details[<?php echo $id; ?>][name]" value="<?php echo $details['name']; ?>" size="25" />
	  </td>
	  <td>
		  <input type="text" class="textbox" name="details[<?php echo $id; ?>][display]" value="<?php echo $details['display']; ?>" />
	  </td>
	  <td>
		  <input type="text" class="textbox" name="details[<?php echo $id; ?>][reg_number]" value="<?php echo $details['reg_number']; ?>" size="25" />
	  </td>
	  <td>
		  <select class="textbox" name="details[<?php echo $id; ?>][status]">
		    <option value="1" <?php selected($details['status'],1); ?> ><?php echo $lang['admin']['settings_enabled']; ?></option>
		    <option value="0" <?php selected($details['status'],0); ?> >-<?php echo $lang['admin']['settings_disabled']; ?>-</option>
		  </select>
	  </td>
	  <td>
<?php if ($i == count($taxDetails)-1) { ?>
		  <input type="submit" class="submit" value="<?php echo $lang['admin']['settings_update_all']; ?>" />
<?php } else { ?>
		  &nbsp;
<?php } ?>
	  </td>
	</tr>
<?php
		}
	}
?>
	<tr>
	  <td>&nbsp;</td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_name']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_display_as']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_reg_number']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_status']; ?></strong></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td>
</form>
<form name="newDetails" action="<?php echo $glob['adminFile']; ?>?_g=settings/tax" method="post" enctype="multipart/form-data" target="_self">
	  </td>
	  <td>
		  <input type="text" class="textbox" name="new_details[name]" size="25" />
	  </td>
	  <td>
		  <input type="text" class="textbox" name="new_details[display]" />
	  </td>
	  <td>
		  <input type="text" class="textbox" name="new_details[reg_number]" size="25" />
	  </td>
	  <td>
		  <select class="textbox" name="new_details[status]">
		    <option value="1"><?php echo $lang['admin']['settings_enabled']; ?></option>
		    <option value="0">-<?php echo $lang['admin']['settings_disabled']; ?>-</option>
		  </select>
	  </td>
	  <td align="center">
		  <input type="submit" class="submit" value="<?php echo $lang['admin_common']['add']; ?>" />
	  </td>
	</tr>
</table>
</form>


<p class="copyText"><strong>
3. <?php echo $lang['admin']['settings_tax_rates']; ?></strong>
</p>
<form name="selCountry" action="<?php echo $config['adminfile']; ?>?_g=settings/tax_rates" method="post" enctype="multipart/form-data" target="_self">
<input name="submit" type="submit" class="submit" value="<?php echo $lang['admin']['settings_popup']; ?>" />
</form>


<?php
} // end if ($config_tax_mod['status'])
?>