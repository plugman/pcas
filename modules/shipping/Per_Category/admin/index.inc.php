<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Shipping Per Category
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

<p class="pageTitle">Per Category </p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>
<p class="copyText">This shipping method is used to give shipping cost per category. With this enabled it will be possible to add/edit these costs in the categories section of admin. </p>

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
  <td align="left" class="tdText"><strong>National Countries:</strong><br />
    <span class="copyText">(List comma separated ISO codes)</span>  </td>
    <td class="tdText">
      <input type="text" name="module[national]" value="<?php echo $module['national']; ?>" class="textbox" size="45" /></td>
  </tr>
    <td align="left" class="tdText"><strong>International Countries:</strong><br />
    <span class="copyText">(List comma separated ISO codes)</span></td>
      <td class="tdText"><input type="text" name="module[international]" value="<?php echo $module['international']; ?>" class="textbox" size="45" /></td>
  </tr>
	  <tr>
    <td align="left" class="tdText"><strong>Handling Fee: </strong></td>
    <td class="tdText"><input name="module[handling]" type="text" value="<?php echo $module['handling']; ?>" size="9" /></td>
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
    <td align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
<p class="copyText">Please select which countries to apply international shipping charges to:</p>
<p class="copyText">How is shipping calculated?<br />
  Shipping is calculated by category:</p>
<table border="0" cellpadding="4" cellspacing="0" class="copyText">
  <tbody>
    <tr>
      <td width="110" nowrap='nowrap'><strong>Category</strong></td>
      <td width="110" nowrap='nowrap'><strong>Per Shipment</strong></td>
      <td width="110" nowrap='nowrap'><strong>Per Item</strong></td>
      <td width="110" nowrap='nowrap'><strong>Per Shipment (International)</strong></td>
      <td width="110" nowrap='nowrap'><strong>Per Item (International)</strong></td>
    </tr>
    <tr>
      <td width="110">Gloves</td>
      <td width="110"> 1.20</td>
      <td width="110"> 0.50</td>
      <td width="110"> 6.50</td>
      <td width="110"> 3.45</td>
    </tr>
    <tr>
      <td width="110">Shoes</td>
      <td width="110"> 1.95</td>
      <td width="110"> 0.95</td>
      <td width="110"> 8.25</td>
      <td width="110"> 6.30</td>
    </tr>
  </tbody>
</table>

<p class="copyText">If a combination of items are being purchased the higher shipping rate shall apply:<br />
    e.g. 1 Pair of shoes + 1 Pair of gloves = 1.95 + 0.95 + 0.50 = 3.40<br />
    e.g. 1 Pair of shoes + 1 Pair of gloves = 8.25 + 6.30 + 3.45 = 18.00 (International) </p>
<span class="copyText">
Key:
<ul>
  <li><strong>Exclude:</strong> Countries you do not want to deliver to.</li>
  <li><strong>International:</strong> Countries to apply international shipping charge to. </li>
  <li><strong>National:</strong> Countries NOT to apply international shipping charge to.</li>
</ul>
</span>
