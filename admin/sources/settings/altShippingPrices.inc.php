<?php
/*
+--------------------------------------------------------------------------
|	altShippingPrices.inc.php
|   ========================================
|	Alternate Shipping Prices (Google Checkout)
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_altShipping.inc.php");

permission("settings","read",$halt=TRUE);

if(isset($_GET['delete']))
{
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_alt_shipping_prices","id=".$db->MySQLSafe($_GET['delete']));
	
	if($delete == TRUE)
	{
		$msg = "<p class='infoText'>".$lang['admin']['shipping_alt_ship_delete_success']."</p>";	
	}
	else
	{
		$msg = "<p class='warnText'>".$lang['admin']['shipping_alt_ship_delete_fail']."</p>";
	}
	
}
elseif(isset($_POST['submit']))
{

	$data['alt_ship_id']= $db->MySQLSafe($_GET['id']);
	$data['low']		= $db->MySQLSafe($_POST['low']);
	$data['high']		= $db->MySQLSafe($_POST['high']);
	$data['price']		= $db->MySQLSafe($_POST['price']);
	
	if($_POST['id']>0)
	{
		$update = $db->update($glob['dbprefix']."ImeiUnlock_alt_shipping_prices",$data,"`id`=".$db->MySQLSafe($_POST['id']));
		
		if($update == TRUE)
		{
			$msg = "<p class='infoText'>".$lang['admin']['shipping_alt_ship_updated_success']."</p>";
		}
		else
		{
			$msg = "<p class='warnText'>".$lang['admin']['shipping_alt_ship_updated_fail']."</p>";
		}
		
	}
	else
	{
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_alt_shipping_prices",$data);
		
		if($insert == TRUE)
		{
			$msg = "<p class='infoText'>".$lang['admin']['shipping_alt_ship_add_success']."</p>";
		}
		else
		{
			$msg = "<p class='warnText'>".$lang['admin']['shipping_alt_ship_add_fail']."</p>";
		}
	
	}
	
}



// get shipping detail
$result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping WHERE `id` = ".$db->MySQLSafe($_GET['id']));

if($result == FALSE)
{
	$msg = "<p class='warnText'>No alternate shipping method was found with id ".$_GET['id'].". </p>";
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap'><p class="pageTitle"><?php echo $lang['admin']['shipping_alt_ship_prices_title']." &quot;".$result[0]['name']."&quot;";?></p></td>
     <?php if(!isset($_GET["mode"]) && $result == TRUE){ ?><td align="right" valign="middle"><a <?php if(permission("settings","write")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=settings/altShippingPrices&amp;mode=new&amp;id=<?php echo $_GET['id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new'];?></a></td><?php } ?>
  </tr>
</table>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
if($result == TRUE)
{
?>
<p class="tdText">
<?php
if($result[0]['byprice']==1)
{
	$method = $lang['admin']['shipping_by_price'];
	$methodShort = $lang['admin']['shipping_price'];
}
else
{
	$method = $lang['admin']['shipping_by_weight'];
	$methodShort = $lang['admin']['shipping_weight'];
} 
echo sprintf($lang['admin']['shipping_alt_ship_method'],$result[0]['name'],strtolower($method),$glob['adminFile']."?_g=settings/altShipping&amp;edit=".$_GET['id']); 
?>
</p>
<p><a href="<?php echo $glob['adminFile']; ?>?_g=settings/altShipping" class="txtLink"><?php echo $lang['admin']['shipping_alt_ship_home'];?></a></p>
<form action="<?php echo $glob['adminFile']."?_g=settings/altShippingPrices&amp;id=".$_GET['id']; ?>" method="post" name="shipping" target="_self">
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td align="center" class="tdTitle"><?php echo sprintf($lang['admin']['shipping_alt_ship_low'],$methodShort); ?></td>
    <td align="center" class="tdTitle"><?php echo sprintf($lang['admin']['shipping_alt_ship_high'],$methodShort); ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['shipping_alt_cost_to_cust']; ?></td>
    <td colspan="2" align="center" class="tdTitle">&nbsp;</td>
  </tr>
  <?php
  $results = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping_prices WHERE `alt_ship_id` = ".$db->MySQLSafe($_GET['id'])." ORDER BY `low`,`high` ASC");
  
  if($results == TRUE)
  {
  
  	for ($i=0; $i<count($results); $i++)
	{ 
		
	$cellColor = "";
	$cellColor = cellColor($i);
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['low']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['high']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['price']; ?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText">
	<a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $global['adminFile']; ?>?_g=settings/altShippingPrices&amp;edit=<?php echo $results[$i]['id']; ?>&amp;id=<?php echo $_GET['id']; ?>" class="txtLink"<?php } else {  echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>	</td>
    <td align="center" class="<?php echo $cellColor; ?> tdText">
	<a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/altShippingPrices&amp;delete=<?php echo $results[$i]['id']; ?>&amp;id=<?php echo $_GET['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else {  echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a>
	</td>
    </tr>
  <?php
  	}
  }
  else
  {
  ?>
  <tr>
    <td colspan="5" class="tdText"><?php echo $lang['admin']['shipping_alt_no_prices_set'];?></td>
  </tr>
  <?php
  }
  if(isset($_GET['edit']) && $_GET['edit']>0)
  { 
  $edit = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_alt_shipping_prices WHERE `id` = ".$db->MySQLSafe($_GET['edit']));
  }
  if($_GET['mode']=="new" || $edit == TRUE)
  {
  ?>
  <tr>
    <td align="center" class="tdText"><input name="low" type="text" value="<?php echo $edit[0]['low']; ?>" class="textbox" /></td>
    <td align="center" class="tdText"><input name="high" type="text" value="<?php echo $edit[0]['high']; ?>" class="textbox" /></td>
    <td align="center" class="tdText"><input name="price" type="text" value="<?php echo $edit[0]['price']; ?>" class="textbox" /></td>
    <td colspan="2" align="center" class="tdText">
	<input type="hidden" name="id" value="<?php echo $edit[0]['id']; ?>" />
	<input name="submit" class="submit" type="submit" value="<?php if($edit==TRUE) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['shipping_alt_cost_submit']; ?>" />	</td>
  </tr>
  <?php
  }
  ?>
</table>
</form>
<?php
}
?>
