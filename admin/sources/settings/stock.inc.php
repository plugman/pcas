<?php
/*
+--------------------------------------------------------------------------
|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	stock.inc.php
|   ========================================
|	Manage Store Stock, ryanmalin.co.uk
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_settings.inc.php");
permission('settings', 'read', true);

// get brand

	if(isset($_GET['is_brand'])){
		$wherestr = "where S.brand_id=".$_GET['is_brand']."";
	} else if(!isset($_GET['is_brand'])){
		$wherestr = "where S.is_brand <> '0'";
	}
	
 	$query ="SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_stock AS S ".$wherestr." ORDER BY name, product ASC";
	
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	} 
	else 
	{
		$page = 0;
	}

if(isset($_POST['id']))
{
	
	$cache = new cache();
	$cache->clearCache();
	
	$record["id"] = $db->mySQLSafe($_POST['id']);
	$record["name"] = $db->mySQLSafe($_POST['name']);  
	$record["product"] = $db->mySQLSafe($_POST['product']);
	$record["qty"] = $db->mySQLSafe($_POST['qty']);
	$record["notes"] = $db->mySQLSafe($_POST['notes']);
	$record["is_brand"] = $db->mySQLSafe($_POST['is_brand']);
	$record["brand_id"] = $db->mySQLSafe($_POST['brand_id']);

	if($_POST['id']>0)
	{
								
		$where = "id = ".$db->mySQLSafe($_POST['id']);
	
		$update = $db->update($glob['dbprefix']."ImeiUnlock_stock", $record, $where);
		
		if($update == TRUE)
		{
			$msg = "<p class='infoText'>".$_POST['product']." ".$_POST['name']." ".$lang['admin']['settings_update_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>".$_POST['product']." ".$_POST['name']." ".$lang['admin']['settings_update_fail']."</p>";
		}
	
	} 
	else 
	{
	
		$insert =$db->insert($glob['dbprefix']."ImeiUnlock_stock", $record);
		
		if($insert == TRUE)
		{
			$msg = "<p class='infoText'>".$_POST['product']." ".$_POST['name']." ".$lang['admin']['settings_add_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>".$_POST['product']." ".$_POST['name']." ".$lang['admin']['settings_add_fail']."</p>";
		}
	
	}

} 
elseif(isset($_GET['deleteProduct'])) 
{
	
	$cache = new cache();
	$cache->clearCache();

	$where = "id = ".$db->mySQLSafe($_GET['deleteProduct']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_stock", $where, "");

	if($delete == TRUE)
	{
		$msg = "<p class='infoText'>".$lang['admin']['settings_delete_success']."</p>";
	} 
	else 
	{
		$msg = "<p class='warnText'>".$lang['admin']['settings_delete_failed']."</p>";
	}

} 

	$stockPerPage = 20;
	
	$stock = $db->select($query, $stockPerPage, $page);
	$numrows = $db->numrows($query);
	$pagination = paginate($numrows, $stockPerPage, $page, "page");


require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>

<p class="pageTitle">Stock
<?php if(isset($_GET['is_brand'])){echo "<a href=\"".$glob['adminFile']."?_g=settings/stock\" class=\"submit\" style=\"padding:2px 4px;text-decoration:none\">View all brands</a>";} ?>
</p>
<?php
if(isset($msg))
{ 
	echo msg($msg); 
} 
else 
{ 
?>
<p class="copyText">Add or remove stock.</p>
<?php 
} 
?>
<form name="countries" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=settings/stock<?php if(isset($_GET['is_brand'])){echo "&amp;is_brand=".$_GET['is_brand'];} ?>">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
	<td align="center" class="tdTitle">ID</td>

<?php if(!isset($_GET['is_brand'])) { ?> 

	<td class="tdTitle">Brand</td>

<?php } else { ?>	

	<td class="tdTitle">Product</td>
    <td class="tdTitle">Quantity</td>
	<td class="tdTitle">Notes</td>

<?php } ?>

    <td colspan="8" align="center" class="tdTitle"><?php echo $lang['admin']['settings']['action'];?></td>
  </tr>

  <?php 
  if($stock == TRUE){ 
  
    for ($i=0; $i<count($stock); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
  
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?> copyText"><span style="color:#ccc;"><?php echo $stock[$i]['id']; ?></td>

<?php if(!isset($_GET['is_brand'])) { ?> 

	<td class="<?php echo $cellColor; ?> copyText"><?php echo "<a class=\"txtLink\" href=\"".$glob['adminFile']."?_g=settings/stock&amp;is_brand=".$stock[$i]['is_brand']."\">".$stock[$i]['name']."</a>"; ?>
	<?php 
	$result2 = mysql_query("SELECT MAX(is_brand) AS maxbr FROM ".$glob['dbprefix']."ImeiUnlock_stock where is_brand>0");
	$row = mysql_fetch_array($result2);
	$newb=$row["maxbr"]+1;
	?> 
	</td>
	
<?php } else { ?>

    <td class="<?php echo $cellColor; ?> copyText"><?php echo $stock[$i]['product']; ?></td>
	<td class="<?php echo $cellColor; ?> copyText" style="text-align:center" ><?php echo $stock[$i]['qty']; ?></td>
    <td class="<?php echo $cellColor; ?> copyText"><?php echo $stock[$i]['notes']; ?></td>

<?php } ?>

    <td class="<?php echo $cellColor; ?>"><a <?php if(permission("settings","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/stock&amp;editStock=<?php echo $stock[$i]['id']; ?><?php if(isset($_GET['is_brand'])){echo "&amp;is_brand=".$_GET['is_brand'];} ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>
	&nbsp;/&nbsp;<a <?php if(permission("settings","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/stock&amp;deleteProduct=<?php echo $stock[$i]['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a>
	  </td>
    </tr>
  <?php
  	}
  }
  else
  {
  ?>
  <tr>
    <td colspan="10" class="tdText">
		<?php if(!isset($_GET['is_brand'])) { ?>
			Add a brand name below.
		<?php } else { ?>
			Add a product below.
		<?php } ?>
	</td>
  </tr>
  <?php } 
  	if(isset($_GET['editStock']) && $_GET['editStock']>0)
	{
		
		$editStock = $db->select("select * FROM ".$glob['dbprefix']."ImeiUnlock_stock WHERE id = ".$db->mySQLsafe($_GET['editStock']));
	
	}
	?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>"><?php if(isset($editStock[0]['id'])) echo $editStock[0]['id']; ?></td>
    
	<?php if(!isset($_GET['is_brand'])) { ?> 
	<td class="<?php echo $cellColor; ?>">
		<input name="name" type="text" class="textbox" id="name" value="<?php if(isset($editStock[0]['name'])) echo $editStock[0]['name']; ?>" style="width:200px;" />
		<input type="hidden" name="is_brand" value="<?php if ($newb>0) echo $newb; else echo "1" ?>" />
	</td>
	<?php } else { ?>
	
    <td align="center" class="<?php echo $cellColor; ?>"><input name="product" type="text" class="textbox" id="product" value="<?php if(isset($editStock[0]['product'])) echo $editStock[0]['product']; ?>" style="width:250px;" /></td>
    <td align="center" class="<?php echo $cellColor; ?>"><input name="qty" type="text" class="textbox" id="qty" value="<?php if(isset($editStock[0]['qty'])) echo $editStock[0]['qty']; ?>" style="width:50px;text-align:center" /></td>
    <td align="center" class="<?php echo $cellColor; ?>">
		<input name="notes" type="text" class="textbox" id="notes" value="<?php if(isset($editStock[0]['notes'])) echo $editStock[0]['notes']; ?>" style="width:200px;" />
		<input type="hidden" name="brand_id" value="<?php echo $_GET['is_brand'] ?>" />
	</td>

	<?php } ?>

    <td colspan="2" align="left" class="<?php echo $cellColor; ?>"><input name="submit" type="submit" class="submit" id="submit" value="<?php if(isset($editStock) && $editStock == TRUE) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?>" />
	<input type="hidden" name="id" value="<?php if(isset($editStock[0]['id'])) echo $editStock[0]['id']; ?>" />
	</td>
  </tr>
</table>
<p class="copyText"><?php echo $pagination; ?></p>

</form>