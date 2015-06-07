<?php
/*
+--------------------------------------------------------------------------
|	geo.inc.php
|   ========================================
|	Manage Geographical Zones	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_settings.inc.php");

permission("settings","read",$halt=TRUE);

if(isset($_POST['mode']) && $_POST['mode']=='country') {
	
	$cache = new cache();
	$cache->clearCache();
	
	$record["iso"] = $db->mySQLSafe($_POST['iso']);  
	$record["printable_name"] = ucwords($db->mySQLSafe($_POST['printable_name']));
	$record["iso3"] = $db->mySQLSafe($_POST['iso3']);
	$record["numcode"] = $db->mySQLSafe($_POST['numcode']); 

	if($_POST['countryId']>0) {
								
		$where = "id = ".$db->mySQLSafe($_POST['countryId']);
	
		$update =$db->update($glob['dbprefix']."ImeiUnlock_iso_countries", $record, $where);
		
		if($update == TRUE){
			$msgCountry = "<p class='infoText'>'".$_POST['printable_name']."' ".$lang['admin']['settings_update_success']."</p>";
		} else {
			$msgCountry = "<p class='warnText'>'".$_POST['printable_name']."' ".$lang['admin']['settings_update_fail']."</p>";
		}
	
	} else {
	
		$insert =$db->insert($glob['dbprefix']."ImeiUnlock_iso_countries", $record);
		
		if($insert == TRUE) {
			$msgCountry = "<p class='infoText'>'".$_POST['printable_name']."' ".$lang['admin']['settings_add_success']."</p>";
		} else {
			$msgCountry = "<p class='warnText'>'".$_POST['printable_name']."' ".$lang['admin']['settings_add_fail']."</p>";
		}
	
	}

} elseif(isset($_GET['deleteCountry'])) {

	$cache = new cache();
	$cache->clearCache();

	$where = "id = ".$db->mySQLSafe($_GET['deleteCountry']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_iso_countries", $where, ""); 

	if($delete == TRUE) {
		$msgCountry = "<p class='infoText'>".$lang['admin']['settings_delete_success']."</p>";
	} else {
		$msgCountry = "<p class='warnText'>".$lang['admin']['settings_delete_failed']."</p>";
	}
	
	$where = "countryId = ".$db->mySQLSafe($_GET['deleteCountry']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_iso_counties", $where, "");

}

if(isset($_POST['mode']) && $_POST['mode']=='county') {
	
	$cache = new cache();
	$cache->clearCache();
	
	$record["countryId"] = $db->mySQLSafe($_POST['countryId']);  
	$record["abbrev"] = $db->mySQLSafe($_POST['abbrev']);
	$record["name"] = $db->mySQLSafe($_POST['name']);

	if($_POST['countyId']>0) {
								
		$where = "id = ".$db->mySQLSafe($_POST['countyId']);
	
		$update =$db->update($glob['dbprefix']."ImeiUnlock_iso_counties", $record, $where);
		
		if($update == TRUE) {
			$msgCounty = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['settings_update_success']."</p>";
		} else {
			$msgCounty = "<p class='warnText'>'".$_POST['name']."' ".$lang['admin']['settings_update_fail']."</p>";
		}
	
	} else {
	
		$insert =$db->insert($glob['dbprefix']."ImeiUnlock_iso_counties", $record);
		
		if($insert == TRUE){
			$msgCounty = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['settings_add_success']."</p>";
		} else {
			$msgCounty = "<p class='warnText'>'".$_POST['name']."' ".$lang['admin']['settings_add_fail']."</p>";
		}
	
	}

} elseif(isset($_GET['deleteCounty'])) {
	$cache = new cache();
	$cache->clearCache();
	
	$where = "id = ".$db->mySQLSafe($_GET['deleteCounty']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_iso_counties", $where, ""); 

	if($delete == TRUE){
		$msgCounty = "<p class='infoText'>".$lang['admin']['settings_delete_success']."</p>";
	} else {
		$msgCounty = "<p class='warnText'>".$lang['admin']['settings_delete_fail']."</p>";
	}

}

	$countriesPagination = "";
	$countiesPagination = "";
	$counties = "";
	$countriesPerPage = 20;
	$countiesPerPage = 20;
	
	// excluded get vars
	$excluded = array("deleteCountry" => null,"deleteCounty" => null);

	// get countries
 	$query ="SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY UPPER(printable_name) ASC";
	
	if(isset($_GET['countriesPage'])) {
		$countriesPage = $_GET['countriesPage'];
	} else {
		$countriesPage = 0;
	}
	
	$countries = $db->select($query, $countriesPerPage, $countriesPage);
	$numrows = $db->numrows($query);
	$countriesPagination = paginate($numrows, $countriesPerPage, $countriesPage, "countriesPage", "txtLink", 7, $excluded);

	// get counties
 	$query = "SELECT ".$glob['dbprefix']."ImeiUnlock_iso_counties.id, countryId, abbrev, name, iso, printable_name, iso3, numcode FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties INNER JOIN ".$glob['dbprefix']."ImeiUnlock_iso_countries ON ".$glob['dbprefix']."ImeiUnlock_iso_counties.countryId = ".$glob['dbprefix']."ImeiUnlock_iso_countries.id ORDER BY printable_name, name  ASC";
	
	if(isset($_GET['countiesPage'])) {
		$countiesPage = $_GET['countiesPage'];
	} else {
		$countiesPage = 0;
	}
	
	$counties = $db->select($query, $countiesPerPage, $countiesPage);
	$numrows = $db->numrows($query);
	$countiesPagination = paginate($numrows, $countiesPerPage, $countiesPage, "countiesPage", "txtLink", 7,$excluded);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['settings_countries'];?></p>
<?php
if(isset($msgCountry)){ 
	echo $msgCountry;
} else { 
?>
<p class="copyText" style="padding:5px 0"><?php echo $lang['admin']['settings_edit_countries_below'];?></p>
<?php } ?>
<form name="countries" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=settings/geo<?php if($_GET['countriesPage']) { echo "&amp;countriesPage=".$_GET['countriesPage']; }?>">
<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
  <tr>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['adminusers_id'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['settings_iso'];?></td>
    <td class="tdTitle"><?php echo $lang['admin']['settings_iso_name'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['settings_iso3'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['settings_num_code'];?></td>
    <td colspan="2" align="center" class="tdTitle"><?php echo $lang['admin']['settings_action'];?></td>
  </tr>
  <?php 
  if($countries == TRUE){ 
  
    for ($i=0; $i<count($countries); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
  
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $countries[$i]['id']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $countries[$i]['iso']; ?></span></td>
    <td class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $countries[$i]['printable_name']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $countries[$i]['iso3']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $countries[$i]['numcode']; ?></span></td>
    <td colspan="2" align="center" class="<?php echo $cellColor; ?> a2"><a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/geo&amp;editCountry=<?php echo $countries[$i]['id']; if($_GET['countriesPage']) { echo "&amp;countriesPage=".$_GET['countriesPage']; } ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>
    <a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/geo&amp;deleteCountry=<?php echo $countries[$i]['id']; if($_GET['countriesPage']) { echo "&amp;countriesPage=".$_GET['countriesPage']; } ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a></td>
  </tr>
  <?php
  	}
  } else {
  ?>
  <tr>
    <td colspan="7" class="tdText"><?php echo $lang['admin']['settings_no_countries_in_db'];?></td>
  </tr>
  <?php } 
  	if(isset($_GET['editCountry']) && $_GET['editCountry']>0){
		
		$editCountry = $db->select("select * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries WHERE id = ".$db->mySQLsafe($_GET['editCountry']));
	
	}
	?>
  <tr>
    <td colspan="2" align="center" class="<?php echo $cellColor; ?>">
    <div class="inputbox" style="width:100px; float:none;">
    <span class="bgleft"></span>
    <input style="width:90px" name="iso" type="text" class="textbox" id="iso" value="<?php if (isset($editCountry[0]['iso'])) echo $editCountry[0]['iso']; ?>" size="4" />
    <span class="bgright"></span></div>
    </td>
    <td align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:100px; float:none;">
    <span class="bgleft"></span>
    <input style="width:90px" name="printable_name" type="text" class="textbox" id="printable_name" value="<?php if(isset($editCountry[0]['printable_name'])) echo $editCountry[0]['printable_name']; ?>" />
    <span class="bgright"></span></div>
    </td>
    <td align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:100px; float:none;">
    <span class="bgleft"></span>
    <input style="width:90px" name="iso3" type="text" class="textbox" id="iso3" value="<?php if(isset($editCountry[0]['iso3'])) echo $editCountry[0]['iso3']; ?>" size="4" />
    <span class="bgright"></span></div>
    </td>
    <td align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:100px; float:none;">
    <span class="bgleft"></span>
    <input style="width:90px" name="numcode" type="text" class="textbox" id="numcode" value="<?php if(isset($editCountry[0]['numcode'])) echo $editCountry[0]['numcode']; ?>" size="4" />
    <span class="bgright"></span></div>
    </td>
    <td colspan="2" align="center" class="<?php echo $cellColor; ?>">
    <input  name="submit" type="submit" class="submit" id="submit" value="<?php if(isset($editCountry) && $editCountry == TRUE) { echo "Edit"; } else { ?>Add<?php } ?> Country" />
	<input type="hidden" name="countryId" value="<?php if(isset($editCountry[0]['id'])) echo $editCountry[0]['id']; ?>" />
	<input type="hidden" name="mode" value="country" />	</td>
    </tr>
</table>
<p class="copyText" align="right"><span class="pagination"><?php echo $countriesPagination; ?></span></p>

</form>
<p class="pageTitle"><?php echo $lang['admin']['settings_zone'];?></p>
<?php
if(isset($msgCounty) && !empty($msgCounty)){ 
	echo $msgCounty;
} else { 
?>
<p class="copyText" style="padding:5px 0"><?php echo $lang['admin']['settings_edit_counties'];?></p>
<?php } ?>
<form name="counties" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=settings/geo&amp;county=1<?php if($_GET['countiesPage']) { echo "&amp;countiesPage=".$_GET['countiesPage']; }?>">
<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
  <tr>
    <td align="left" class="tdTitle"><?php echo $lang['admin']['settings_country'];?></td>
    <td class="tdTitle"><?php echo $lang['admin']['settings_iso_name'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['settings_iso'];?></td>
    <td  width="150" align="center" class="tdTitle"><?php echo $lang['admin']['settings_action'];?></td>
  </tr>
  <?php 
  if($counties == TRUE){ 
  
    for ($i=0; $i<count($counties); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
  
  ?>
  <tr>
    <td align="left" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $counties[$i]['printable_name']; ?></span></td>
    <td class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $counties[$i]['name']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $counties[$i]['abbrev']; ?></span></td>
    <td  align="center" class="<?php echo $cellColor; ?> a2"><a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/geo&amp;editCounty=<?php echo $counties[$i]['id']; if($_GET['countiesPage']) { echo "&amp;countiesPage=".$_GET['countiesPage']; } ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>
    <a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/geo&amp;deleteCounty=<?php echo $counties[$i]['id']; if($_GET['countiesPage']) { echo "&amp;countiesPage=".$_GET['countiesPage']; } ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a></td>
  </tr>
  <?php
  	}
  } else {
  ?>
  <tr>
    <td colspan="4" class="tdText"><?php echo $lang['admin']['settings_no_counties_in_db'];?></td>
  </tr>
  <?php } 
  	if(isset($_GET['editCounty']) && $_GET['editCounty']>0){
		
		$editCounty = $db->select("select * FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE id = ".$db->mySQLsafe($_GET['editCounty']));
	
	}
	?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>">
		<?php
		$allCountries = $db->select("SELECT printable_name, id FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY printable_name ASC");
		?>
		<div class="inputbox" style="width:150px; float:none;">
        <span class="bgleft"></span>
		<select name="countryId" style="width:140px">
		<?php for($i=0; $i<count($allCountries); $i++){ ?>
		<option value="<?php echo $allCountries[$i]['id']; ?>" <?php if(isset($editCounty[0]['countryId']) && $allCountries[$i]['id']==$editCounty[0]['countryId']) echo "selected='selected'"; ?>><?php echo $allCountries[$i]['printable_name']; ?></option>
		<?php } ?>
		</select>
        <span class="bgright"></span></div>
	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
    <div class="inputbox" style="width:150px; float:none;">
        <span class="bgleft"></span>
    <input style="width:140px" name="name" type="text" class="textbox" id="name" value="<?php if(isset($editCounty[0]['name'])) echo $editCounty[0]['name']; ?>" />
    <span class="bgright"></span></div>
    </td>
    <td align="center" class="<?php echo $cellColor; ?>">
    <div class="inputbox" style="width:70px; float:none;">
        <span class="bgleft"></span>
    <input style="width:60px" name="abbrev" type="text" class="textbox" id="abbrev" value="<?php if(isset($editCounty[0]['abbrev'])) echo $editCounty[0]['abbrev']; ?>" size="4" />
    <span class="bgright"></span></div>
    </td>
    <td  align="center" class="<?php echo $cellColor; ?>"><input name="submit" type="submit" class="submit" id="submit" value="<?php if(isset($editCounty) && $editCounty == TRUE) { echo "Edit"; } else { ?>Add<?php } ?> Zone" />
	<input type="hidden" name="countyId" value="<?php if(isset($editCounty[0]['id'])) echo $editCounty[0]['id']; ?>" />
	<input type="hidden" name="mode" value="county" />
	</td>
    </tr>
</table>
<p class="copyText" align="right"><span class="pagination"><?php echo $countiesPagination; ?></span></p>
</form>