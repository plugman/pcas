<?php
/*
+--------------------------------------------------------------------------
|	currency.inc.php
|   ========================================
|	Manage Store Currencies	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_settings.inc.php");
permission('settings', 'read', true);

if (isset($_GET['currencyISO'])) {
	$cache = new cache();
	$cache->clearCache();
	
	if (!$config['currecyAuto']) {
		if (function_exists('curl_init')) {
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s='.$config['defaultCurrency'].$_GET['currencyISO'].'=X&f=sl1d1t1c1ohgv&e=.csv');
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			if ((bool)$config['proxy']) {
				curl_setopt($curl_handle, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']);
			}
			$contents = curl_exec($curl_handle);
			curl_close($curl_handle);
			
		} else {
			
			if (!$handle = fopen("http://finance.yahoo.com/d/quotes.csv?s=".$config['defaultCurrency'].$_GET['currencyISO']."=X&f=sl1d1t1c1ohgv&e=.csv", "rb")) {
				$handle = fopen("http://uk.old.finance.yahoo.com/d/quotes.csv?s=".$config['defaultCurrency'].$_GET['currencyISO']."=X&f=sl1d1t1c1ohgv&e=.csv", "rb");
			}
			if (function_exists('stream_get_contents')) {
				$contents = stream_get_contents($handle);
			} else {
				while (!feof($handle)) {
					$contents .= fread($handle, 8192);
				}
			}
			fclose($handle);
		}
		
		$pieces = explode(',', $contents);
		$rate = $pieces[1];
	}	

	$where = "code = ".$db->mySQLSafe($_GET['currencyISO']);
	$record['value'] = $db->mySQLSafe($rate);
	$record['lastUpdated'] = $db->mySQLSafe(time());
	
	$update = $db->update($glob['dbprefix']."ImeiUnlock_currencies", $record, $where);
	
	// clear cache
	$cache = new cache('glob.currencyVars.'.$_GET['currencyISO']);
	$cache->clearCache();
	
	if($update == TRUE)
	{
		$msg = '<p class="infoText">"'	.$_GET['currencyISO']."' ".$lang['admin']['settings_update_success']."</p>";
	} 
	else 
	{
		$msg = "<p class='warnText'>'".$_GET['currencyISO']."' ".$lang['admin']['settings_update_fail']."</p>";
	}
	
}
elseif(isset($_POST['currencyId']))
{
	
	$cache = new cache();
	$cache->clearCache();
	
	$record["code"] = $db->mySQLSafe($_POST['code']);
	$record["name"] = $db->mySQLSafe($_POST['name']);  
	$record["symbolLeft"] = $db->mySQLSafe(validHTML($_POST['symbolLeft']));
	$record["symbolRight"] = $db->mySQLSafe(validHTML($_POST['symbolRight']));
	$record["value"] = $db->mySQLSafe($_POST['value']);
	$record["decimalPlaces"] = $db->mySQLSafe($_POST['decimalPlaces']);
	$record["decimalSymbol"] = $db->mySQLSafe($_POST['decimalSymbol']);
	$record["lastUpdated"] = $db->mySQLSafe(time());

	if($_POST['currencyId']>0)
	{
								
		$where = "currencyId = ".$db->mySQLSafe($_POST['currencyId']);
	
		$update = $db->update($glob['dbprefix']."ImeiUnlock_currencies", $record, $where);
		
		if($update == TRUE)
		{
			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['settings_update_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>'".$_POST['name']."' ".$lang['admin']['settings_update_fail']."</p>";
		}
	
	} 
	else 
	{
	
		$insert =$db->insert($glob['dbprefix']."ImeiUnlock_currencies", $record);
		
		if($insert == TRUE)
		{
			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['settings_add_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>'".$_POST['name']."' ".$lang['admin']['settings_add_fail']."</p>";
		}
	
	}

} 
elseif(isset($_GET['deleteCurrency'])) 
{
	
	$cache = new cache();
	$cache->clearCache();

	$where = "currencyId = ".$db->mySQLSafe($_GET['deleteCurrency']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_currencies", $where, "");

	if($delete == TRUE)
	{
		$msg = "<p class='infoText'>".$lang['admin']['settings_delete_success']."</p>";
	} 
	else 
	{
		$msg = "<p class='warnText'>".$lang['admin']['settings_delete_failed']."</p>";
	}

} 
elseif(isset($_GET['active']) && $_GET['currencyId']>0)
{

	$cache = new cache();
	$cache->clearCache();
	
	$record["active"] = $_GET['active'];

	$where = "currencyId = ".$db->mySQLSafe($_GET['currencyId']);
	
	$update =$db->update($glob['dbprefix']."ImeiUnlock_currencies", $record, $where);

}

	$currenciesPerPage = 20;

// get countries
 	$query ="SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_currencies ORDER BY name ASC";
	
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	} 
	else 
	{
		$page = 0;
	}
	
	$currencies = $db->select($query, $currenciesPerPage, $page);
	$numrows = $db->numrows($query);
	$pagination = paginate($numrows, $currenciesPerPage, $page, "page");


require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['settings_currencies'];?></p>
<?php
if(isset($msg))
{ 
	echo msg($msg); 
} 
else 
{ 
?>
<p style="padding-top:10px; padding-bottom:5px;" class="copyText"><?php echo $lang['admin']['settings_currencies_desc'];?></p>
<?php 
} 
?>
<form name="countries" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=settings/currency">
<div class="currency">
<table cellspacing="0" cellpadding="0" width="100%" bordercolor="#d4d4d4" border="1" class="mainTable mainTable4">
  <tr>
    <td width="29" align="center" class="tdTitle"><?php echo $lang['admin']['settings_c_code'];?></td>
    <td width="97" align="center" class="tdTitle"><?php echo $lang['admin']['settings_c_name'];?></td>
    <td width="38" align="center" class="tdTitle"><?php echo $lang['admin']['settings_c_value'];?></td>
    <td width="69" align="center" class="tdTitle"><?php echo $lang['admin']['settings_symbol_left'];?></td>
    <td width="82" align="center" class="tdTitle"><?php echo $lang['admin']['settings_symbol_right'];?></td>
    <td width="91" align="center" class="tdTitle"><?php echo $lang['admin']['settings_decimal_places'];?></td>
    <td width="52" align="center" class="tdTitle"><?php echo $lang['admin']['settings_decimal_format'];?></td>
    <td width="83" align="center" class="tdTitle"><?php echo $lang['admin']['settings_last_updated'];?></td>
    <td width="30" align="center" class="tdTitle"><?php echo $lang['admin']['settings_c_status'];?></td>
    <td width="100" align="center" class="tdTitle"><?php echo $lang['admin']['settings_action'];?></td>
    </tr>
  <?php 
  if($currencies == TRUE){ 
  
    for ($i=0; $i<count($currencies); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
  
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['code']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['name']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['value']; ?>
	<?php
	if($currencies[$i]['code']!==$config['defaultCurrency'])
	{
	?>
<br />
<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency&amp;currencyISO=<?php echo $currencies[$i]['code']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['settings_autoupdate'];?></a>
	<?php 
	} 
	?>
</td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['symbolLeft']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['symbolRight']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo $currencies[$i]['decimalPlaces']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> copyText">
	<?php
	if($currencies[$i]['decimalSymbol']==1)
	{
	echo ", ".$lang['admin']['settings_comma'];
	}
	else
	{
	echo ". ".$lang['admin']['settings_decimal_point'];
	}
	?>    </td>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><?php echo formatTime($currencies[$i]['lastUpdated']); ?></td>
    <td align="center" class="<?php echo $cellColor; ?>"><img src="<?php echo $glob['adminFolder']; ?>/images/<?php echo $currencies[$i]['active']; ?>.gif" alt="" title="" /></td>
    <td align="center" class="a2 <?php echo $cellColor; ?>">	 <?php if($currencies[$i]['active']>0){ ?>
	<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency&amp;currencyId=<?php echo $currencies[$i]['currencyId']; ?>&amp;code=<?php echo $currencies[$i]['code']; ?>&amp;active=0" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['settings_disable'];?></a>
	<?php } else { ?>
	<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency&amp;currencyId=<?php echo $currencies[$i]['currencyId']; ?>&amp;code=<?php echo $currencies[$i]['code']; ?>&amp;active=1" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['settings_enable'];?></a>
	<?php } ?>	
	

	<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency&amp;editCurrency=<?php echo $currencies[$i]['currencyId']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>
	
	

	<a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency&amp;deleteCurrency=<?php echo $currencies[$i]['currencyId']; ?>&amp;code=<?php echo $currencies[$i]['code']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a>
	  </td>
    </tr>
  <?php
  	}
  }
  else
  {
  ?>
  <tr>
    <td colspan="10" class="tdText"><?php echo $lang['admin']['settings_no_currencies'];?></td>
  </tr>
  <?php } 
  	if(isset($_GET['editCurrency']) && $_GET['editCurrency']>0)
	{
		
		$editCurrency = $db->select("select * FROM ".$glob['dbprefix']."ImeiUnlock_currencies WHERE currencyId = ".$db->mySQLsafe($_GET['editCurrency']));
	
	}
	?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>">
    <div class="inputbox" style="width:35px;">
		<span class="bgleft"></span>
    <input name="code" type="text" style="width:75%;"  id="code" value="<?php if(isset($editCurrency[0]['code'])) echo $editCurrency[0]['code']; ?>"  />
	   <span class="bgright"></span>
	   </div>
    </td>
    <td class="<?php echo $cellColor; ?>">
      <div class="inputbox" style="width:135px;">
		<span class="bgleft"></span>
     <input name="name" style="width:90%;" type="text"  id="name" value="<?php if(isset($editCurrency[0]['name'])) echo $editCurrency[0]['name']; ?>" />
	   <span class="bgright"></span>
	   </div>
   </td>
    <td align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:90px;">
		<span class="bgleft"></span>
    <input name="value" type="text" id="value" value="<?php if(isset($editCurrency[0]['value'])) echo $editCurrency[0]['value']; ?>" style="width:90%;" />
	   <span class="bgright"></span>
	   </div>
   </td>
    <td align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:75px;">
		<span class="bgleft"></span>
    <input name="symbolLeft" type="text" id="symbolLeft" value="<?php if(isset($editCurrency[0]['symbolLeft'])) echo $editCurrency[0]['symbolLeft']; ?>"  style="width:85%;" />
	   <span class="bgright"></span>
	   </div>
    </td>
    <td align="center" class="<?php echo $cellColor; ?>">
      <div class="inputbox" style="width:75px;">
		<span class="bgleft"></span>
     <input name="symbolRight" type="text" id="symbolRight" value="<?php if(isset($editCurrency[0]['symbolRight'])) echo $editCurrency[0]['symbolRight']; ?>" style="width:85%;" />
	   <span class="bgright"></span>
	   </div>
   </td>
    <td align="center" class="<?php echo $cellColor; ?>">
        <div class="inputbox" style="width:75px;">
		<span class="bgleft"></span>
     <input name="decimalPlaces" type="text" class="textbox" id="decimalPlaces" value="<?php if(isset($editCurrency[0]['decimalPlaces'])) echo $editCurrency[0]['decimalPlaces']; ?>" style="width:85%;" />
	   <span class="bgright"></span>
	   </div>
   </td>
    <td colspan="2" align="center" class="<?php echo $cellColor; ?>">
     <div class="inputbox" style="width:145px;">
		<span class="bgleft"></span>
    <select name="decimalSymbol" style="width:93%;">
	<option value="0">. <?php echo $lang['admin']['settings_decimal_point'];?></option>
	<option value="1" <?php if($editCurrency[0]['decimalPlaces']==1) { echo "selected='selected'"; } ?>>, <?php echo $lang['admin']['settings_comma'];?></option>
    </select>  
	   <span class="bgright"></span>
	   </div>
	  </td>

    <td colspan="2" align="center" class="<?php echo $cellColor; ?>"><input name="submit" type="submit" class="submit" id="submit" value="<?php if(isset($editCurrency) && $editCurrency == TRUE) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['settings_currency'];?>" />
	<input type="hidden" name="currencyId" value="<?php if(isset($editCurrency[0]['currencyId'])) echo $editCurrency[0]['currencyId']; ?>" />	</td>
    </tr>
</table>
</div>
<p class="copyText" align="right"> <span class="pagination"><?php echo $pagination; ?></span></p>

</form>