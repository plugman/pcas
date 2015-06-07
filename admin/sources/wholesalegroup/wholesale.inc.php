<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage products
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_features.inc.php");

permission("products", "read", true);
require("classes".CC_DS."gd".CC_DS."gd.inc.php");
$catsPerPage = 25;

if (isset($_POST['saveOrder'])) {
	## Handler for drag/drop reordering
	foreach ($_POST['wholesaleId'] as $index => $wholesaleId) {
		$sql = sprintf("UPDATE %sImeiUnlock_customer_type SET priority = '%d' WHERE wholesaleId = '%d' LIMIT 1;", $glob['dbprefix'], $index+1, $wholesaleId);
		$db->misc($sql);
	}
	$cache = new cache();
	$cache->clearCache();
	
	## Rebuild the cached list
	buildCatList();
}

if (isset($_GET['move']) && isset($_GET['to'])) {
	## Handler for roundtrip reordering
	
	$db->update($glob['dbprefix'].'ImeiUnlock_customer_type', array('wholesaleId' => (int)$_GET['move']));
	$cache = new cache();
	$cache->clearCache();
	
	## Rebuild the cached list
	buildCatList();
	httpredir('?_g=wholesalegroup/wholesale&parent='.(int)$_GET['parent']);
}
$record['hide']	= ($_POST['hide'] == 1) ? '1' : '0';
		
if (isset($_GET['hide'])) {
	$cache = new cache();
	$cache->clearCache();
	$record['hide']	= sprintf("'%d'", $_GET['hide']);
	$where			= "wholesaleId=".$db->mySQLSafe($_GET['wholesaleId']);
	$update			= $db->update($glob['dbprefix']."ImeiUnlock_customer_type", $record, $where);
		
	$msg	= ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";
		## Rebuild the cached list
} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {

	$cache = new cache();
	$cache->clearCache();
	$where = "wholesaleId=".$db->mySQLSafe($_GET["delete"]);
	// delete index
	
	$selectimageid="select wholesaleId from ".$glob['dbprefix']."ImeiUnlock_customer_type where ".$where;
	$select=$db->select($selectimageid);
	$deleteIdx = $db->delete($glob['dbprefix']."ImeiUnlock_wholesale_prices", $where);
	
	// delete category
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_customer_type", $where);
	
	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['products_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_delete_failed']."</p>";
	## Rebuild the cached list
	
} else if (isset($_POST['wholesaleId'])) {

	$cache = new cache();
	$cache->clearCache();
	$record["wholesaleId"] = $db->mySQLSafe($_POST['wholesaleId']);	
	$record["customer_type"] = $db->mySQLSafe($_POST['name']);	
	$record["discount"] = $db->mySQLSafe($_POST['discount']);	
	if (is_numeric($_POST['wholesaleId'])) {
		
		$where = "wholesaleId=".$db->mySQLSafe($_POST['wholesaleId']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_customer_type", $record, $where);

		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";
	} else {		
	
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_customer_type", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_add_failed']."</p>";
		
	}
	## Rebuild the cached list
}

if (!isset($_GET['mode'])) {
	// make sql query
	if (isset($_GET['edit']) && $_GET['edit'] > 0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer_type WHERE wholesaleId = %s", $db->mySQLSafe($_GET['edit'])); 
	} else {
		//$whereClause = (is_numeric($_GET['parent'])) ? sprintf("cat_father_id = '%d'", $_GET['parent']) : 'cat_father_id = 0';
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer_type WHERE wholesaleId != '1'";
	}
		
	// query database
	$results = $db->select($query);
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<div>
<div class="maindiv" style="margin-bottom:10px;">
<?php
if (!isset($_GET['mode'])) {
	$url = (permission("products","write") == true) ? 'href="?_g=wholesalegroup/wholesale&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;
	echo sprintf('<span style="float: right;"><a %s><img src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', $lang['admin_common']['add_new']);
}
?>
</div>
<div class="clear"></div>
  <!--<p class="pageTitle">
	<?php
	echo $lang['admin']['products_products'];
	?>
  </p>-->
</div>
<?php 
if(isset($msg)) echo msg($msg);

if (!isset($_GET['mode']) && !isset($_GET['edit'])) {
?>
<!--<p class="copyText"><?php echo $lang['admin']['products_products_desc']; ?></p>

<p class="copyText"><strong><?php echo $lang['admin']['products_loc']; ?></strong> <a href="?_g=wholesalegroup/wholesale" class="txtLink"><?php echo $lang['admin']['products_loc_home']; ?></a><?php if (is_numeric($_GET['parent'])) echo getCatDir('', $_GET['parent'], 0, true, false, true, true); ?></p>-->
<form method="post" id="reorderCategory" enctype="multipart/form-data">
<div class="wbox">
<div  class="headingBlackbg2">
  <span class="catid"><?php echo "ID" ?></span>
   <span class="catName2" style="width:585px"> <?php echo $lang['admin']['products_name']; ?></span>
   <span class="catName2" style="width:100px; text-align:center"> <?php echo "Discount %"; ?></span>
 	<span class="action2">
  <?php echo $lang['admin']['products_action']; ?>
  </span>
</div>

<?php 
if ($results == true) {
	$count = count($results)-1;
	foreach ($results as $i => $result) {
		$cellColor	= cellColor($i);
		$sql		= sprintf("SELECT wholesaleId FROM %sImeiUnlock_customer_type WHERE wholesaleId = '%d'", $glob['dbprefix'], $results[$i]['wholesaleId']);
		$subcat		= $db->numrows($sql);
		
?>	
<div id="product_<?php echo $i; ?>" class="productRow <?php echo $cellColor; ?> tdText">
<span class="catid">
  <?php
	echo ($subcat >= 1) ? sprintf('%s', $results[$i]['wholesaleId']) : $results[$i]['wholesaleId'];
  ?>
  </span>
  <span class="catName2" style="width:584px;" ><?php echo $results[$i]['customer_type'];?></span>
  <span class="catName2" style="width:100px; text-align:center" ><?php if($results[$i]['discount'] > 0)echo $results[$i]['discount'] . "%"; else echo "&nbsp;"?> </span>
  <div class="action2">
  	<div class="right2"><?php
		switch($results[$i]['hide']) {
		
			case 0:
				$url	= (permission("products","edit")) ? 'href="?_g=wholesalegroup/wholesale&amp;hide=1&amp;wholesaleId='.$results[$i]['wholesaleId'].'" class="txtLink"' : $link401;
				$title	= $lang['admin_common']['show'];
				break;
			case 1:
				$url	= (permission("products","edit")) ? 'href="?_g=wholesalegroup/wholesale&amp;hide=0&amp;wholesaleId='.$results[$i]['wholesaleId'].'" class="txtLink"' : $link401;
				$title	= $lang['admin_common']['hide'];
				break;
		}
		echo sprintf('<a %s>%s</a>', $url, $title);
	?></div>
	<!--<div class="right1"><?php
		$url = (permission("products","edit"))? 'href="?_g=products/languages&amp;cat_master_id='.$results[$i]['wholesaleId'].'" class="txtLink"' : $link401;
		echo sprintf('<a %s>%s</a>', $url, $lang['admin']['products_languages']);
	?></div>-->
	<div class="right2"><?php
		if (permission("products","delete") && $results[$i]['wholesaleId'] != 0) {
			if ($results[$i]['noProducts'] <= 0) {
				$url = 'href="?_g=wholesalegroup/wholesale&amp;delete='.$results[$i]['wholesaleId'].'" onclick=" return confirm(\''.str_replace("\n", '\n', $lang['admin_common']['delete_q']).'\');" class="txtLink"';
			} else {
				$url = 'href="#" onclick="alert(\''.$lang['admin']['products_cannot_del'].'\')" class="txtNullLink"';
			}
		} else {
			$url = $link401;
		}
		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['delete']);
	?></div>
	<div class="right2"><?php
		$url = (permission("products","edit")) ? 'href="?_g=wholesalegroup/wholesale&amp;edit='.$results[$i]['wholesaleId'].'" class="txtLink"' : $link401;
		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);
	?></div>
    
	<?php if (permission("products","edit")) { ?>
	<span class="action" style="display:none;">
	<?php if ($i >= 1) { ?>
	  <a href="?_g=wholesalegroup/wholesale&amp;move=<?php echo $results[$i]['wholesaleId']; ?>&amp;to=<?php echo $i-1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=up">
	  <img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_up.gif" border="0" /></a>
	<?php  } ?>
	<?php if ($i < $count) { ?>
	  <a href="?_g=wholesalegroup/wholesale&amp;move=<?php echo $results[$i]['wholesaleId']; ?>&amp;to=<?php echo $i+1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=down">
	  <img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_down.gif" /></a>
	<?php } ?>
	</span>
	<?php } ?>
  </div>
  
  

  
  
  
  <input type="hidden" name="priority[]" value="<?php echo $results[$i]['wholesaleId']; ?>" />
</div>

<?php
	} // end loop
	?></div>
    <?php
} else {
?>

  <div class="tdText"><?php echo $lang['admin']['products_no_cats_exist'];?></div>
<?php } ?>
  <p>To re-order the products, drag and drop them into your prefered order, then save</p>
  <p><input type="submit" class="submit" name="saveOrder" value="Save Order" /></p>
  </div>
</form>
<script type="text/javascript">
	Sortable.create('reorderCategory', {ghosting:true,constraint:false,tag:'div',only:'productRow'});
</script>

<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  

if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 
?>
<p class="copyText"><?php echo $lang['admin']['products_add_desc'];?></p>
<form action="?_g=wholesalegroup/wholesale" method="post" enctype="multipart/form-data" name="form1">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
  <tr>
    <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> <?php echo $lang['admin']['products_category'];?></td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['products_category_name'];?></td>
    <td>
      <input name="name" type="text" class="textbox" value="<?php if(isset($results[0]['customer_type'])) echo $results[0]['customer_type']; ?>" maxlength="255" />    
     
      
      </td>
  </tr>
  <tr>
    <td class="tdText"><?php echo "Discount %";?></td>
    <td>
      <input name="discount" type="number" class="textbox" value="<?php if(isset($results[0]['discount'])) echo $results[0]['discount']; ?>" maxlength="255" />    
     
      
      </td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['products_category_status'];?></td>
  <td><input name="hide" value="1" type="checkbox" <?php if ($results[0]['hide']) echo 'checked="checked" '; ?>/></td>
  </tr>

  
  
     <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="wholesaleId" value="<?php echo $results[0]['wholesaleId']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>
  </tr>



  </table>
  
  <?php if ($config['seftags']) { ?><br />

                <table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%" style="display:none">
                  <tr> 
                    <td colspan="2" class="tdTitle"><strong><?php echo $lang['admin']['products_meta_data']; ?></strong></td>
                  </tr>
				  <tr> 
					<td width="30%" class="tdText"><strong><?php echo $lang['admin']['category_custom_url'];?></strong></td>
					<td align="left"><input name="seo_custom_url" type="text" size="35" class="textbox" value="<?php if(isset($results[0]['seo_custom_url'])) echo $results[0]['seo_custom_url']; ?>" /></td>
				  </tr>
                  <tr> 
                    <td width="30%" class="tdText"><strong><?php echo $lang['admin']['products_browser_title']; ?></strong></td>
                    <td align="left"><input name="cat_metatitle" type="text" size="35" class="textbox" value="<?php if(isset($results[0]['cat_metatitle'])) echo $results[0]['cat_metatitle']; ?>" /></td>
                  </tr>
                  <tr> 
                    <td width="30%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['products_meta_desc'];?></strong></td>
                    <td align="left"><textarea name="cat_metadesc" cols="35" rows="3" class="textbox"><?php if(isset($results[0]['cat_metadesc'])) echo $results[0]['cat_metadesc']; ?></textarea></td>
                  </tr>
                  <tr> 
                    <td width="30%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['products_meta_keywords'];?></strong> <?php echo $lang['admin']['settings']['comma_separated'];?></td>
                    <td align="left"><textarea name="cat_metakeywords" cols="35" rows="3" class="textbox"><?php if(isset($results[0]['cat_metakeywords'])) echo $results[0]['cat_metakeywords']; ?></textarea></td>
                  </tr>
				  <tr>
    <td>&nbsp;</td>
    <td>
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>
  </tr>
                </table>
<?php } ?>
</form>
<?php 
} 

?>