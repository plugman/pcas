<?php
/*
+--------------------------------------------------------------------------
|	tax_rates.inc.php
|   ========================================
|   Setup Tax Details
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_settings.inc.php");

permission("settings","read",$halt=TRUE);

$config = fetchDbConfig("config");

$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");


// Update Tax Rates/Zones
if(isset($_POST['rates'])){
	
	$cache = new cache();
	$cache->clearCache();
	
	$ratesArray = $_POST['rates'];

	$update = 0;
	
	foreach ($ratesArray as $rates){
		
		$record["tax_percent"] = $db->mySQLSafe($rates['tax_percent']);
		if ($rates['active']==1){
			$record["active"] = $db->mySQLSafe($rates['active']);
		} else {
			$record["active"] = $db->mySQLSafe('0');
		}

		$where = "id = ".$db->mySQLSafe($rates['id']);
	
		$res = $db->update($glob['dbprefix']."ImeiUnlock_tax_rates", $record, $where);
		if ($res) {
			$update++;
		}
	}
	$msgTaxType = "";
	if($update) {
		$msgTaxType = "<p class='infoText'>".$update." ".$lang['admin']['settings_tax_rates_upd_success']."</p>";
	}
}
// Add/Update Tax Rates/Zones
elseif(isset($_POST['new_rates'])) {
	
	$cache = new cache();
	$cache->clearCache();
	
	$rates = $_POST['new_rates'];

	$add = false;
	$edit = false;

	if (isset($rates['id'])) {
		$record["id"] = $db->mySQLSafe($rates['id']);
		$edit = TRUE;
	} else {
		$add = TRUE;
	}
	$record["type_id"] = $db->mySQLSafe($rates['type_id']);
	$record["details_id"] = $db->mySQLSafe($rates['details_id']);
	$record["country_id"] = $db->mySQLSafe($rates['country_id']);
	$record["county_id"] = $db->mySQLSafe($rates['county_id']);
	$record["tax_percent"] = $db->mySQLSafe($rates['tax_percent']);
	$record["goods"] = $db->mySQLSafe(substr($rates['goods_shipping'],0,1));
	$record["shipping"] = $db->mySQLSafe(substr($rates['goods_shipping'],1,2));
	
	if($rates['active']==1){
		$record["active"] = $db->mySQLSafe($rates['active']);
	} else {
		$record["active"] = $db->mySQLSafe('0');
	}

	if($add) {
		if($rates['county_id']==0) {
			$check_add = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE type_id=".$record['type_id']." AND details_id=".$record['details_id']." AND country_id=".$record['country_id']);
		} else {
			$check_add = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE type_id=".$record['type_id']." AND details_id=".$record['details_id']." AND country_id=".$record['country_id']." AND (county_id=".$record['county_id']." OR county_id=".$db->mySQLSafe('0').")");
		}
		if($check_add) {
			$error = TRUE;
		}
		if(!$error){
			$insert = $db->insert($glob['dbprefix']."ImeiUnlock_tax_rates", $record);
		}
	}

	if ($edit) {
		if ($rates['county_id']==0) {
			$check_edit = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE type_id=".$record['type_id']." AND details_id=".$record['details_id']." AND country_id=".$record['country_id']." AND id <> ".$record['id']);
		} else {
			$check_edit = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates WHERE type_id=".$record['type_id']." AND details_id=".$record['details_id']." AND country_id=".$record['country_id']." AND (county_id=".$record['county_id']." OR county_id=".$db->mySQLSafe('0').") AND id <> ".$record['id']);
		}
		if($check_edit) {
			$error = true;
		}
		if(!$error) {
			$where = "id = ".$record['id'];
			$update = $db->update($glob['dbprefix']."ImeiUnlock_tax_rates", $record, $where);
		}
	}
		
	if($add) {
		if ($insert) {
			$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_tax_rates_add_success']."</p>";
		} else {
			$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_tax_rates_add_failure']."</p>";
		}
	} elseif($edit) {
		if($error){
			$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_tax_rates_upd_error']."</p>";
		} elseif($update){
			$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_tax_rates_upd_success']."</p>";
		} else {
			$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_tax_rates_upd_failure']."</p>";
		}
	}
}
// Delete Tax Rates/Zones
elseif(isset($_GET['delete_rates'])) {
	
	$cache = new cache();
	$cache->clearCache();
	
	$where = "id = ".$db->mySQLSafe($_GET['delete_rates']);
	
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_tax_rates", $where, ""); 
	if($delete == TRUE) {
		$msgTaxType = "<p class='infoText'>".$lang['admin']['settings_tax_rates_del_success']."</p>";
	} else {
		$msgTaxType = "<p class='warnText'>".$lang['admin']['settings_tax_rates_del_failure']."</p>";
	}

}

// Save country filter
if (isset($_POST['show_country'])) {
	$show_country = $_POST['show_country'];
} elseif (isset($_GET['show_country'])) {
	$show_country = $_GET['show_country'];
} else {
	$show_country = 0;
}

// Current/Next URL

$currentURL = $glob['adminFile']."?_g=settings/tax_rates";
if ($show_country > 0) {
	$currentURL .= (strstr($currentURL,"?") ? "&" : "?")
		."show_country=".$show_country;
}

// If error editing field, then leave it in edit state

if ($edit && $error) {
	$_GET['edit'] = $rates['id'];
}

// If successfully added/edited field, make sure country filter
// allows the changes to be seen

if ((isset($insert) && $insert) || (isset($update) && $update))
{
	if ($rates['country_id'] != 0 && $show_country != 0
			&& $rates['country_id'] != $show_country) {
		$show_country = 0;
	}
}

// Lookup Existing Taxes/Config

$taxDetails = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_tax_details ORDER BY id");

$taxRates = $db->select("SELECT r.id, type_id, t.taxName as class, details_id, d.name as tax, country_id, county_id, tax_percent, goods, shipping, active, status FROM ".$glob['dbprefix']."ImeiUnlock_tax_rates AS r LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_tax_details AS d ON r.details_id = d.id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_taxes AS t ON r.type_id = t.id ORDER BY type_id, country_id, county_id, d.id");

$all_countries = $db->select("SELECT id, iso, printable_name FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries");
$all_states = $db->select("SELECT id, countryId, abbrev, name FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties");

$country_names = array();
$state_names = array();

if(is_array($taxRates)) {

	foreach ($taxRates as $rate) {
		$id = $rate['country_id'];
		foreach ($all_countries as $country) {
			if ($id == $country['id'])
			{
				$country_names[$id] = $country['printable_name'];
				break;
			}
		}
		$id = $rate['county_id'];
		
		foreach ($all_states as $state)
		{
			if ($id == $state['id'])
			{
				$state_names[$id] = $state['name'];
				break;
			}
		}
	
	}
}

$taxTypes = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes");

// Setup default country for inserts

if ($show_country)
{
	$defaultCountry = $show_country;
}
else
{
	$defaultCountry = $db->select("SELECT id FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries WHERE iso='US'"); 
	if(is_array($defaultCountry))
	{
		$defaultCountry = $defaultCountry[0]['id'];
	}
	else
	{
		$defaultCountry = 1;
	}
}

$jsScript = jsGeoLocation("taxCountry", "taxCounty", "-- ".$lang['admin_common']['all']." --");
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>

<p class="pageTitle"><?php $lang['admin']['settings_tax_settings']; ?></p>
<?php
if(isset($msg))
{ 
	echo msg($msg); 
}

if(isset($msgTaxType))
{ 
	echo msg($msgTaxType); 
}
?>


<p class="copyText"><strong>
<?php echo $lang['admin']['settings_tax_rates']; ?></strong>&nbsp;
<a href="#" class="txtLink" onclick="javascript:
if (findObj('help_class').style.display=='none') {
  findObj('help_class').style.display='block';
  findObj('help_country').style.display='block';
  findObj('help_tax').style.display='block';
  findObj('help_rate').style.display='block';
  findObj('help_apply').style.display='block';
  findObj('help_active').style.display='block';
} else {
  findObj('help_class').style.display='none';
  findObj('help_country').style.display='none';
  findObj('help_tax').style.display='none';
  findObj('help_rate').style.display='none';
  findObj('help_apply').style.display='none';
  findObj('help_active').style.display='none';
}
return false;"><?php echo $lang['admin']['settings_show_help']; ?></a>
</p>


<?php
	unset($rate);
	if (isset($_GET['edit']) && $_GET['edit']>0)
	{
		foreach ($taxRates as $r)
		{
			if ($r['id'] == $_GET['edit'])
			{
				$rate = $r;
				break;
			}
		}
	}
?>
<form name="taxRates" action="<?php echo $currentURL; ?>" method="post" enctype="multipart/form-data" target="_self">
<table border="0" cellspacing="0" cellpadding="3" class="mainTable">
	<tr valign="top">
		<td colspan="3" class="tdTitle"><strong><?php echo $lang['admin']['settings_tax_rates']; ?> - <? if (isset($rate)) echo $lang['admin']['update']; else echo "Add"; ?></strong></td>
	</tr>
<?php
	if (is_array($taxTypes) && is_array($taxDetails))
	{
?>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_class']; ?></strong></td>
	  <td align="left">
		
<?php
			if (isset($rate)) {
?>
		<input type="hidden" name="new_rates[id]" value="<?php echo $rate['id']; ?>" />
<?php
			}
?>
		<select class="textbox" name="new_rates[type_id]">
<?php
			$found = false;
			foreach ($taxTypes as $type)
			{
				if (isset($rate) && $rate['type_id']==$type['id']) {
					$found = TRUE;
				}
?>
		<option value="<?php echo $type['id']; ?>" <?php if (isset($rate) && $rate['type_id'] == $type['id']) echo "selected"; ?>><?php echo $type['taxName']; ?></option>
<?php
			}
			if (isset($rate) && !$found) {
?>
		<option value="0" selected><?php echo $lang['admin']['settings_deleted']; ?></option>
<?php
			}
?>
		</select>
	  </td>
	  <td>
		  <div id="help_class" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_class']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_country']; ?>:</strong></td>
	  <td align="left">
		<select class="textbox" name="new_rates[country_id]" id="taxCountry" onChange="updateCounty(this.form,'taxCountry','taxCounty');" style="width: 120px;">
<?php
			foreach ($all_countries as $country)
			{
				if (isset($rate) && $rate['country_id']==$country['id']) {
					$found = TRUE;
				}
?>
		<option value="<?php echo $country['id']; ?>" <?php if (isset($rate) && $rate['country_id'] == $country['id']) { echo "selected"; } elseif (!isset($rate) && $defaultCountry == $country['id']) { echo "selected" ; } ?>><?php echo $country['printable_name']; ?></option>
<?php
			}
			if (isset($rate) && !$found) {
?>
		<option value="0" selected><?php echo $lang['admin']['settings_deleted']; ?></option>
<?php
			}
?>
		</select>
	  </td>
	  <td rowspan="2">
		  <div id="help_country" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_country']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_state']; ?></strong></td>
	  <td align="left">
		<select class="textbox" name="new_rates[county_id]" id="taxCounty" style="width: 150px;">
		<option value="0">-- <?php echo $lang['admin_common']['all']; ?> --</option>
<?php
			foreach ($all_states as $state)
			{
				if (isset($rate) && $state['countryId']!=$rate['country_id']) {
					continue;
				}
				if (isset($rate) && $rate['county_id']==$state['id']) {
					$found = TRUE;
				}
				if (!isset($rate) && $state['countryId']!=$defaultCountry) {
					continue;
				}
?>
		<option value="<?php echo $state['id']; ?>" <?php if (isset($rate) && $rate['county_id'] == $state['id']) echo "selected"; ?>><?php echo $state['name']; ?></option>
<?php
			}
			if (isset($rate) && !$found) {
?>
		<option value="0" selected><?php echo $lang['admin']['settings_deleted']; ?></option>
<?php
			}
?>
		</select>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_tax']; ?></strong></td>
	  <td align="left">
		<select class="textbox" name="new_rates[details_id]">
<?php
			$found = false;
			foreach ($taxDetails as $details)
			{
				if (isset($rate) && $rate['details_id']==$details['id']) {
					$found = TRUE;
				}
?>
		<option value="<?php echo $details['id']; ?>" <?php if (isset($rate) && $rate['details_id'] == $details['id']) echo "selected"; ?>><?php echo $details['name']; ?></option>
<?php
			}
			if (isset($rate) && !$found) {
?> 
		<option value="0" selected><?php echo $lang['admin']['settings_deleted']; ?></option>
<?php
			}
?>
		</select>
	  </td>
	  <td>
		  <div id="help_tax" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_tax']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_rate']; ?></strong></td>
	  <td align="left">
		<input type="text" size="10" class="textbox" name="new_rates[tax_percent]" value="<?php if (isset($rate)) echo number_format($rate['tax_percent'],4); ?>" />
	  </td>
	  <td>
		  <div id="help_rate" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_rate']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_apply_to']; ?></strong></td>
	  <td align="left">
		<select class="textbox" name="new_rates[goods_shipping]">
		  <option value="11" <?php if (isset($rate) && $rate['goods']==1 && $rate['shipping']==1) echo "selected"; ?>><?php echo $lang['admin']['settings_goods_and_shipping']; ?></option>
		  <option value="10" <?php if (isset($rate) && $rate['goods']==1 && $rate['shipping']==0) echo "selected"; ?>><?php echo $lang['admin']['settings_goods_only']; ?></option>
		  <option value="01" <?php if (isset($rate) && $rate['goods']==0 && $rate['shipping']==1) echo "selected"; ?>><?php echo $lang['admin']['settings_shipping_only']; ?></option>
		</select>
	  </td>
	  <td>
		  <div id="help_apply" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_apply']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_active']; ?></strong></td>
	  <td align="left">
<?php
			if ((isset($rate) && $rate['status']==1) || !isset($rate) ) {
?>
		<input type="checkbox" name="new_rates[active]" value="1" <?php if((isset($rate) && $rate['active']) || !isset($rate)) { echo "checked"; } ?> />
<?php
			} else {
?>
		<input type="checkbox" name="noname[<?php echo $id; ?>]" disabled />
		<input type="hidden" name="rates[<?php echo $id; ?>][active]" value="<?php echo $rates['active']; ?>" />
<?php
			}
?>
	  </td>
	  <td>
		  <div id="help_active" style="display:none;">
		  <span class="copyText"><?php echo $lang['admin']['settings_tax_rates_help_active']; ?></span>
		  </div>
	  </td>
	</tr>
	<tr valign="top">
	  <td class="copyText">
		<a href="#" class="txtLink" onclick="javascript:
		if (findObj('help_class').style.display=='none') {
		  findObj('help_class').style.display='block';
		  findObj('help_country').style.display='block';
		  findObj('help_tax').style.display='block';
		  findObj('help_rate').style.display='block';
		  findObj('help_apply').style.display='block';
		  findObj('help_active').style.display='block';
		} else {
		  findObj('help_class').style.display='none';
		  findObj('help_country').style.display='none';
		  findObj('help_tax').style.display='none';
		  findObj('help_rate').style.display='none';
		  findObj('help_apply').style.display='none';
		  findObj('help_active').style.display='none';
		}
		return false;"><?php echo $lang['admin']['settings_show_help']; ?>
		</a>
	  </td>
	  <td align="left">
<?php
			if (isset($rate)) {
?>
		<input type="submit" class="submit" value="<?php echo $lang['admin_common']['update']; ?>" />
		<a href="<?php echo $currentURL; ?>" class="txtLink"><input name="noname" type="button" class="submit" value="<?php echo $lang['admin']['settings_cancel']; ?>" /></a>
<?php
			} else {
?>
		<input type="submit" class="submit" value="<?php echo $lang['admin_common']['add']; ?>" />
<?php
			}
?>
	  </td>
	  <td>&nbsp;</td>
    </tr>
<?php
	} // end if (is_array($taxTypes) && is_array($taxDetails))
	else
	{
?>
	<tr>
		<td class="copyText">
		  <?php echo $lang['admin']['settings_please_setup_tax_details']; ?>
		</td>
	</tr>
<?php
	}
?>
</table>
</form>
<br/>

<form name="selCountry" action="<?php echo $currentURL; ?>" method="post" enctype="multipart/form-data" target="_self">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
	<tr>
	  <td colspan="4" class="tdTitle"><strong><?php echo $lang['admin']['settings_tax_rates']; ?> - <?php echo $lang['admin_common']['edit']."/".$lang['admin_common']['delete']; ?></strong></td>
	  <td colspan="6" class="tdTitle">
	    
		<strong><?php echo $lang['admin']['settings_filter_by_country']; ?></strong>
		<select name="show_country" class="textbox">
		<option value="0">-- <?php echo $lang['admin_common']['all']; ?> --</option>
<?php
$last_id = 0;
for ($i=0; $i<count($taxRates) && is_array($taxRates); $i++) {
	if ($taxRates[$i]['country_id'] != $last_id) {
		$last_id = $taxRates[$i]['country_id'];
		echo "<option value=\"".$taxRates[$i]['country_id']."\" ";
		if ($show_country == $taxRates[$i]['country_id']) {
			echo "selected";
		}
		echo ">".$country_names[$last_id]."</option>";
	}
}
?>
		</select>
		<input name="submit" type="submit" class="submit" value="<?php echo $lang['admin']['settings_show']; ?>" />
		<input name="reset" type="button" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']."?_g=settings/tax_rates"; ?>'); disableSubmit(this,'<?php echo $lang['admin_common']['please_wait']; ?>');" value="<?php echo $lang['admin']['settings_reset']; ?>" />
		</td>
	</tr>
<?php

	if (is_array($taxRates))
	{
?>
	<tr>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_class']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_country']; ?>:</strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_state']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_tax']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_rate']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_apply_to']; ?></strong></td>
	  <td class="tdText"><strong><?php echo $lang['admin']['settings_active']; ?></strong></td>
	  <td colspan="2" class="tdText">&nbsp;</td>
	</tr>
<?php
		$c = 0;
		for ($j=0; $j<count($taxRates); $j++)
		{
			$rates = $taxRates[$j];
			$id = $rates['id'];

			if ($show_country && $rates['country_id']!=$show_country) {
				continue;
			}

			$cellColor = "";
			$cellColor = cellColor($c++);
?>
	<tr>
	  <td align="left" class="<?php echo $cellColor; ?> copyText">
		<input type="hidden" name="rates[<?php echo $id; ?>][id]" value="<?php echo $id; ?>" />
		<?php echo $rates['class']; ?>	  </td>
	  <td align="left" class="<?php echo $cellColor; ?> copyText">
	    <?php echo $country_names[$rates['country_id']]; ?>	  </td>
	  <td align="left" class="<?php echo $cellColor; ?> copyText">
	    <?php if ($rates['county_id'] > 0) { echo $state_names[$rates['county_id']]; } else { echo "-- ".$lang['admin_common']['all']." --"; } ?>	  </td>
	  <td align="left" class="<?php echo $cellColor; ?> copyText">
	    <?php echo $rates['tax']; ?>	  </td>
	  <td align="left" class="<?php echo $cellColor; ?> copyText">
		<input type="text" size="10" class="textbox" name="rates[<?php echo $id; ?>][tax_percent]" value="<?php echo number_format($rates['tax_percent'],4); ?>" />	  </td>
	  <td align="center" class="<?php echo $cellColor; ?> copyText">
<?php
	if ($rates['goods']==1 && $rates['shipping']==1) {
		echo $lang['admin']['settings_goods_and_shipping'];
	} elseif ($rates['goods']==1 && $rates['shipping']==0) {
		echo $lang['admin']['settings_goods_only'];
	} elseif ($rates['goods']==0 && $rates['shipping']==1) {
		echo $lang['admin']['settings_shipping_only'];
	} else {
		echo "??";
	}
?>	  </td>
	  <td align="center" class="<?php echo $cellColor; ?> copyText">
<?php
			if($rates['status'])
			{
?>
		<input type="checkbox" name="rates[<?php echo $id; ?>][active]" value="1" <?php if($rates['active']) { echo "checked"; } ?> />
<?php
			} else {
?>
		<input type="checkbox" name="noname[<?php echo $id; ?>]" disabled />
		<input type="hidden" name="rates[<?php echo $id; ?>][active]" value="<?php echo $rates['active']; ?>" />
<?php
			}
?>	  </td>
	  <td align="center" class="<?php echo $cellColor; ?> copyText">
	  <a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $currentURL; echo (strstr($currentURL,"?") ? "&" : "?"); ?>edit=<?php echo $id;?>" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a></td>
	  <td align="center" class="<?php echo $cellColor; ?> copyText"><a <?php if(permission("settings","delete")==true){ ?>href="<?php echo $currentURL; echo (strstr($currentURL,"?") ? "&" : "?"); ?>delete_rates=<?php echo $id; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a></td>
	</tr>
<?php
		}
	}
?>
	<tr>
	  <td colspan="7" align="right" class="<?php echo $cellColor; ?> copyText">
		  <?php echo $lang['admin']['settings_update_all_rates']; ?>	
		  <input type="submit" class="submit" value="<?php echo $lang['admin']['settings_update_all']; ?>" />	  </td>
	  <td colspan="2" class="<?php echo $cellColor; ?> copyText">&nbsp;</td>
	</tr>
</table>
</form>