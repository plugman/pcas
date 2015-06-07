<?php
/*
+--------------------------------------------------------------------------
|	altShipping.inc.php
|   ========================================
|	Alternate Shipping (Google Checkout)
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_altShipping.inc.php");

permission("settings","read",$halt=TRUE);

if(isset($_GET['delete']))
{
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_alt_shipping_prices","alt_ship_id=".$db->MySQLSafe($_GET['delete']));
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_alt_shipping","id=".$db->MySQLSafe($_GET['delete']));
	
	if($delete == TRUE)
	{
		$msg = "<p class='infoText'>".$lang['admin']['shipping_ship_delete_success']."</p>";	
	}
	else
	{
		$msg = "<p class='warnText'>".$lang['admin']['shipping_ship_delete_fail']."</p>";
	}
	
}
elseif(isset($_GET['dir']))
{

	if($_GET['dir']=="up")
	{
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_alt_shipping SET `order` = `order` - 1 WHERE `id` = ".$_GET['id'];
		$db->misc($query);
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_alt_shipping SET `order` = `order` + 1 WHERE `id` = ".$_GET['affected'];
		$db->misc($query);	
	}
	elseif($_GET['dir']=="dn")
	{
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_alt_shipping SET `order` = `order` + 1 WHERE `id` = ".$_GET['id'];
		$db->misc($query);
		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_alt_shipping SET `order` = `order` - 1 WHERE `id` = ".$_GET['affected'];
		$db->misc($query);
	}
	
	

}
elseif(isset($_POST['submit']))
{

	$data['name']		= $db->MySQLSafe($_POST['name']);
	$data['order']		= $db->MySQLSafe($_POST['order']);
	$data['status']		= $db->MySQLSafe($_POST['status']);
	$data['byprice']	= $db->MySQLSafe($_POST['byprice']);
	$data['global']		= $db->MySQLSafe($_POST['global']);
	$data['notes']		= $db->MySQLSafe($_POST['notes']);
	
	if($_POST['id']>0)
	{
		$update = $db->update($glob['dbprefix']."ImeiUnlock_alt_shipping",$data,"`id`=".$db->MySQLSafe($_POST['id']));
		
		if($update == TRUE)
		{
			$msg = "<p class='infoText'>".sprintf($lang['admin']['shipping_updated_success'],$_POST['name'])."</p>";
		}
		else
		{
			$msg = "<p class='warnText'>".sprintf($lang['admin']['shipping_updated_fail'],$_POST['name'])."</p>";
		}
		
	}
	else
	{
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_alt_shipping",$data);
		
		if($insert == TRUE)
		{
			$msg = "<p class='infoText'>".sprintf($lang['admin']['shipping_add_success'],$_POST['name'])."</p>";
		}
		else
		{
			$msg = "<p class='warnText'>".sprintf($lang['admin']['shipping_add_fail'],$_POST['name'])."</p>";
		}
	
	}
	
}


require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap'><p class="pageTitle"><?php echo $lang['admin']['shipping_alt_shipping_title'];?></p></td>
     <?php if(!isset($_GET["mode"])){ ?><td align="right" valign="middle"><a <?php if(permission("settings","write")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=settings/altShipping&amp;mode=new" class="txtLink"<?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new'];?></a></td><?php } ?>
  </tr>
</table>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>
<form id="shipping" name="shipping" method="post" action="">
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
<tr>
	<td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_name']; ?></td>
	<td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_sort_order']; ?></td>
	<td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_status']; ?></td>
	<td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_byprice']; ?></td>
	<td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_global']; ?></td>
	<td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_notes']; ?></td>
	<td colspan="3" align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['shipping_action']; ?></td>
</tr>
<?php
// repeat region
$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping ORDER BY `order` ASC";
// query database
$results = $db->select($query);
if($results == TRUE)
{

	for ($i=0; $i<count($results); $i++)
	{ 
		
	$cellColor = "";
	$cellColor = cellColor($i);
	?>
	<tr>
		<td class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['name']; ?></td>
		<td align="center" class="<?php echo $cellColor; ?>">
		<?php 
		if($i>0)
		{
		?>
		<a href="<?php echo $glob['adminFile']; ?>?_g=settings/altShipping&amp;dir=up&amp;id=<?php echo $results[$i]['id']; ?>&amp;affected=<?php echo $results[$i-1]['id']; ?>">
		<img src="<?php echo $glob['adminFolder']; ?>/images/up.gif" border="0" />		</a>
		<?php
		}
		if($i!==count($results)-1)
		{
		?>
		<a href="<?php echo $glob['adminFile']; ?>?_g=settings/altShipping&amp;dir=dn&amp;id=<?php echo $results[$i]['id']; ?>&amp;affected=<?php echo $results[$i+1]['id']; ?>">
		<img src="<?php echo $glob['adminFolder']; ?>/images/down.gif" border="0" />		</a>
		<?php
		}
		?>		</td>
		<td align="center" class="<?php echo $cellColor; ?>"><img src="<?php echo $glob['adminFolder']; ?>/images/<?php echo $results[$i]['status']; ?>.gif" border="0" /></td>
		<td align="center" class="<?php echo $cellColor; ?> tdText">
		<?php 
		if($results[$i]['byprice']==1)
		{
			echo $lang['admin']['shipping_price']; 
		}
		else
		{
			 echo $lang['admin']['shipping_weight'];
		}
		?>		</td>
		<td align="center" class="<?php echo $cellColor; ?>">
		<img src="<?php echo $glob['adminFolder']; ?>/images/<?php echo $results[$i]['global']; ?>.gif" border="0" /></td>
		<td class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['notes']; ?></td>
		<td align="center" class="<?php echo $cellColor; ?>">
		<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $global['adminFile']; ?>?_g=settings/altShipping&amp;edit=<?php echo $results[$i]['id']; ?>" class="txtLink"<?php } else {  echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>		</td>
	    <td align="center" class="<?php echo $cellColor; ?>"><a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/altShipping&amp;delete=<?php echo $results[$i]['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else {  echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a></td>
	    <td align="center" class="<?php echo $cellColor; ?>"><a <?php if(permission("settings","read")==TRUE){ ?>href="<?php echo $global['adminFile']; ?>?_g=settings/altShippingPrices&amp;id=<?php echo $results[$i]['id']; ?>" class="txtLink"<?php } else {  echo $link401; } ?>><?php echo $lang['admin']['shipping_set_prices']; ?></a></td> 
	</tr>
	<?php
	}
}
else
{
?>
<tr>
	<td colspan="9" class="tdText"><?php echo $lang['admin']['shipping_no_alt_shipping']; ?></td>
</tr>
<?php
}


if((isset($_GET['edit']) && $_GET['edit']>0) || $_GET['mode']=="new")
{

	if(isset($_GET['edit']) && $_GET['edit']>0)
	{
		$editShip = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping WHERE `id` = ".$db->MySQLSafe($_GET['edit']));
	}
?>
<tr>
	<td>
	  <input name="name" type="text" id="name" class="textbox" value="<?php echo $editShip[0]['name']; ?>" />	</td>
	<td align="center">
	  <input name="orderPseudo" type="text" id="order" class="textboxDisabled" style="text-align: center;" size="3" maxlength="3" disabled="disabled" value="<?php if(isset($_GET['edit'])){ $order = $editShip[0]['order']; } else { $order = ($i+1); } echo $order; ?>" />
	  <input type="hidden" name="order" value="<?php echo $order; ?>" />	</td>
	<td align="center">
	  <select name="status" id="status" class="textbox">
	   <option value="0" <?php if($editShip[0]['status']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['disabled']; ?></option>
	   <option value="1" <?php if($editShip[0]['status']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['enabled']; ?></option>
	   </select>	</td>
	<td align="center">
	  <select name="byprice" id="byprice" class="textbox">
	  <option value="0" <?php if($editShip[0]['byprice']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['shipping_by_weight'];?></option>
	   <option value="1" <?php if($editShip[0]['byprice']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['shipping_by_price'];?></option>
	    </select>	</td>
	<td align="center">
	  <select name="global" id="global" class="textbox">
	  <option value="0" <?php if($editShip[0]['global']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['shipping_use_global_false'];?></option>
	  <option value="1" <?php if($editShip[0]['global']==1) { echo "selected='selected'"; } ?>"><?php echo $lang['admin']['shipping_use_global'];?></option>
	  
      </select>	</td>
	<td><input name="notes" type="text" id="notes" class="textbox" value="<?php echo $editShip[0]['notes']; ?>" /></td>
	<td colspan="3">
		<input type="hidden" name="id" value="<?php echo $editShip[0]['id']; ?>" />
	  <input type="submit" name="submit" value="<?php if($editShip==TRUE) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> " class="submit" />	</td>
</tr>
<?php
}
?>
</table>
</form>