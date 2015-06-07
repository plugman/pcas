<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure UPS Shipping
+--------------------------------------------------------------------------
*/


if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("shipping","read",$halt=TRUE);

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



<p><a href="http://www.ups.com/"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="" border="0" title="" /></a></p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings</td>
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
  <td align="left" class="tdText"><strong>Store Post/Zip Code:</strong></td>
    <td class="tdText"><input type="text" name="module[postcode]" value="<?php echo $module['postcode']; ?>" class="textbox" size="10" /></td>
  </tr>
  <tr>
    <td  class="tdText"><strong>Packaging Type:</strong></td>
    <td valign="top"  class="tdText">
	  <select name="module[container]">
	  	<option value="CP">Customer Packaging</option>
		<option value="ULE">UPS Letter Envelope</option>
		<option value="UT">UPS Tube</option>
		<option value="UEB">Express Box</option>
		<option value="UW25">Worldwide 25 kilo</option>
	  </select>
	</td>
  </tr>
  	<tr>
    <td align="left" class="tdText"><strong>Tax Class:</strong></td>
    <td class="tdText">
	<?php
	$tax = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes");
	?>
	<select name="module[tax]">
	<?php for($i=0; $i<count($tax); $i++){ ?>
	<option value="<?php echo $tax[$i]['id']; ?>" <?php if($module['tax'] == $tax[$i]['id']) echo "selected='selected'"; ?>>
	<?php echo $tax[$i]['taxName']; ?>
	</option>
	<?php } ?>
	</select>
	</td>
  </tr>
  <tr>
    <td class="tdText"><strong>Rate:</strong></td>
    <td>
      <select name="module[rate]">
      <option value="RDP" <?php if($module['rate']=="RDP") echo "selected='selected'"; ?>>Regular Daily Pickup</option>        
<option value="OCA" <?php if($module['rate']=="OCA") echo "selected='selected'"; ?>>On Call Air</option>        
<option value="OTP" <?php if($module['rate']=="OTP") echo "selected='selected'"; ?>>One Time Pickup</option>        
<option value="LC" <?php if($module['rate']=="LC") echo "selected='selected'"; ?>>Letter Center</option>         
<option value="CC" <?php if($module['rate']=="CC") echo "selected='selected'"; ?>>Customer Counter</option>
	  </select>
    </td>
  </tr>
  <tr>
    <td class="tdText"><strong>Address Type:</strong> (Destination) </td>
    <td>
      <select name="module[rescom]">
        <option value="RES" <?php if($module['rescom']=="RES") echo "selected='selected'"; ?>>Residential</option>
        <option value="COM" <?php if($module['rescom']=="COM") echo "selected='selected'"; ?>>Commercial</option>
      </select>
    </td>
  </tr>
    <tr>
    <td align="left" class="tdText"><strong>Handling Fee: </strong></td>
    <td class="tdText"><input name="module[handling]" type="text" value="<?php echo $module['handling']; ?>" size="9" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tdTitle">UPS Products</td>
  </tr>
    <td align="left" class="tdText"><strong>Next Day Air Early AM</strong></td>
      <td class="tdText"><select name="module[product1DM]">
        <option value="1" <?php if($module['product1DM']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product1DM']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr><tr>
      <td align="left" class="tdText"><strong>Next Day Air</strong></td>
      <td class="tdText"><select name="module[product1DA]">
        <option value="1" <?php if($module['product1DA']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product1DA']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Next Day Air Saver</strong></td>
      <td class="tdText"><select name="module[product1DP]">
        <option value="1" <?php if($module['product1DP']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product1DP']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>2nd Day Air Early AM</strong></td>
      <td class="tdText"><select name="module[product2DM]">
        <option value="1" <?php if($module['product2DM']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product2DM']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>2nd Day Air</strong></td>
      <td class="tdText"><select name="module[product2DA]">
        <option value="1" <?php if($module['product2DA']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product2DA']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>3 Day Select</strong></td>
      <td class="tdText"><select name="module[product3DS]">
        <option value="1" <?php if($module['product3DS']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['product3DS']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Ground</strong></td>
      <td class="tdText"><select name="module[productGND]">
        <option value="1" <?php if($module['productGND']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['productGND']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Canada Standard</strong></td>
      <td class="tdText"><select name="module[productSTD]">
        <option value="1" <?php if($module['productSTD']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['productSTD']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Worldwide Express</strong></td>
      <td class="tdText"><select name="module[productXPR]">
        <option value="1" <?php if($module['productXPR']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['productXPR']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Worldwide Express Plus</strong></td>
      <td class="tdText"><select name="module[productXDM]">
        <option value="1" <?php if($module['productXDM']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['productXDM']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td align="left" class="tdText"><strong>Worldwide Expedited</strong></td>
      <td class="tdText"><select name="module[productXPD]">
        <option value="1" <?php if($module['productXPD']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['productXPD']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
  <tr>
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
