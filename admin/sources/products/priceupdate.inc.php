<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Add/Edit/Delete Products	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
$lang = getLang("admin".CC_DS."admin_products.inc.php");
require("classes".CC_DS."gd".CC_DS."gd.inc.php");
require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");
require_once ("classes" . CC_DS . "xmlparse" . CC_DS . "xml2array.php");
permission('products', 'read', true);
$productsPerPage = 25;
if (isset($_POST['DeleteSelected']) && is_array($_POST['product'])) {
	$query = sprintf("DELETE FROM %sImeiUnlock_inventory WHERE `productId` IN (%s)", $glob['dbprefix'], implode(',', $_POST['product']));
	$db->misc($query);
}
if(isset($_POST['normPer']) || isset($_POST['salePer']))
{
	$cache = new cache();
	$cache->clearCache();
	
	$sqlUpdateWhere = "";
	
	if(is_array($_POST['cat_id_price']))
	{
		
		for ($n=0; $n<count($_POST['cat_id_price']); $n++)
		{
			if($_POST['cat_id_price'][$n]>0)
			{
				if($n==0)
				{
					 $sqlUpdateWhere .= " WHERE cat_id = ".$db->mySQLSafe($_POST['cat_id_price'][$n]);
				}
				else
				{
				$sqlUpdateWhere .= "OR cat_id = ".$db->mySQLSafe($_POST['cat_id_price'][$n]);
				}
			}else
			$sqlUpdateWhere .= " WHERE digital = '1'";
		}	
	}else
			$sqlUpdateWhere .= " WHERE digital = '1'";
	
	if (is_numeric($_POST['normPer'])) {
		
		if($_POST['normPerMethod']=="percent" && $_POST['normPer']>0){
			$sum = "`price` * ".($_POST['normPer']/100);
		} elseif($_POST['normPerMethod']=="value" && $_POST['normPer']<0) {
			$sum = "`price` ".$_POST['normPer'];
		} elseif($_POST['normPerMethod']=="value") {
			$sum = "`price` + ".$_POST['normPer'];
		} elseif($_POST['normPerMethod']=="actual" && $_POST['normPer']>0) {
			$sum = $_POST['normPer'];
		} else {
			$sum = "`price`";
		}
		
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET `price` = ".$sum.$sqlUpdateWhere;
		$result = $db->misc($query);
	}
	
	if (is_numeric($_POST['salePer'])) {
		
		if ($_POST['salePerMethod']=="percent" && $_POST['salePer']>0) {
			$sum = "`sale_price` * ".($_POST['salePer']/100);
		} else if ($_POST['salePerMethod']=="value" && $_POST['salePer']<0) {
			$sum = "`sale_price` ".$_POST['salePer'];
		} elseif ($_POST['salePerMethod']=="value") {
			$sum = "`sale_price` + ".$_POST['salePer'];
		} elseif ($_POST['salePerMethod']=="actual" && $_POST['salePer']>0) {
			$sum = $_POST['salePer'];
		} else {
			$sum = "`sale_price`";
		}
		
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET `sale_price` = ".$sum.$sqlUpdateWhere;
		$result = $db->misc($query);
	}
	if ($result) {
		$msg2 = "<p class='infoText'>".$lang['admin']['products_price_upd_successful']."</p>";
	} else {
		$msg2 = "<p class='warnText'>".$lang['admin']['products_price_upd_fail']."</p>";
	}
} 
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
if (isset($msg)) echo msg($msg); 
 ?>
 
<p  style="text-align: left; font-size:18px; color:#333333; padding-bottom:15px; font-weight:bold"><?php echo "Bulk Update Network Unlock Prices"; ?></p>
<?php
if(isset($msg2))
{ 
	echo msg($msg2); 
}
?>
<form id="update_prices" name="update_prices" method="post" action="<?php echo $glob['adminFile']; ?>?_g=products/priceupdate">
  <table  cellspacing="0" cellpadding="/" class="mainTable mainTable4" width="100%">
    <tr>
      <td colspan="6" class="tdTitle"><?php echo "Update Price by Network"; ?></td>
    </tr>
    <tr>
      <td width="120" ><strong><?php echo $lang['admin']['products_normal_price2']; ?></strong></td>
 
       <td colspan="2"> <input name="normPer" type="text" size="5" maxlength="5"  style=" width:103px; height:31px" class="textbox" />
       <select name="normPerMethod"  class="textbox" style="width:163px; margin-left:10px;">
          <option value="percent"><?php echo $lang['admin']['products_val_percent']; ?></option>
          <option value="value"><?php echo $lang['admin']['products_val_amount']; ?></option>
          <option value="actual"><?php echo $lang['admin']['products_val_actual']; ?></option>
        </select></td>
     <td  > <strong>   <?php echo $lang['admin']['products_sale_price2']; ?></strong></td>
      <td>  <input name="salePer" type="text" size="5" maxlength="5" style=" width:102px;" class="textbox"/></td>
      <td>  <select name="salePerMethod" class="textbox" style="width:163px;">
          <option value="percent"><?php echo $lang['admin']['products_val_percent']; ?></option>
          <option value="value"><?php echo $lang['admin']['products_val_amount']; ?></option>
          <option value="actual"><?php echo $lang['admin']['products_val_actual']; ?></option>
        </select></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="tdText" ><strong><?php echo "Networks"; ?></strong><br />
        <?php echo "(Hold down `Ctrl` to select multiple networks)"; ?></td>
      <td colspan="5" align="left" valign="top" class="tdText">
      <select name="cat_id_price[]" size="5" multiple="multiple" class="textbox" style="width:370px; height:113px">
          <option value="0">-- <?php echo $lang['admin_common']['all']; ?> --</option>
          <?php echo showCatList($results[0]['cat_id']); ?>
        </select></td>
     </tr>
     <tr>
      <td colspan="6" align="left" valign="bottom" class="tdText"><input type="submit" name="submit_prices" value="<?php echo $lang['admin']['products_update_prices']; ?>" <?php if(permission("products","edit")==false){ echo "disabled='disabled' class='submitDisabled'"; } else { echo "class='submit'"; } ?> /></td>
    </tr>
    <tr>
      <td colspan="6" align="left" class="tdText"><?php echo $lang['admin']['products_eg_1']; ?><br />
        <?php echo $lang['admin']['products_eg_2']; ?></td>
    </tr>
  </table>
</form>

