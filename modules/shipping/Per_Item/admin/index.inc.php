<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure Shipping Per Item
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

<p class="pageTitle">Per Item</p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>
<p class="copyText">This shipping method is used to give a flat module per item.</p>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if ($module['status']) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if (!$module['status']) echo "selected='selected'"; ?>>Disabled</option>
    </select>
	</td>
  </tr>
  <tr>
  <td align="left" class="tdText"><strong>Shipping Cost:</strong></td>
    <td class="tdText"><input type="text" name="module[cost]" value="<?php echo $module['cost']; ?>" class="textbox" size="10" /></td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Handling Fee:</strong></td>
    <td class="tdText"><input type="text" name="module[handling]" value="<?php echo $module['handling']; ?>" class="textbox" size="10" /></td>
  </tr>
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
