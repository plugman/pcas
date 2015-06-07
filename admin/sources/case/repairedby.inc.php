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
$record['hide']	= ($_POST['hide'] == 1) ? '1' : '0';
		
if (isset($_GET['hide'])) {
	$cache = new cache();
	$cache->clearCache();
	$record['hide']	= sprintf("'%d'", $_GET['hide']);
	$where			= "id=".$db->mySQLSafe($_GET['id']);
	$update			= $db->update($glob['dbprefix']."ImeiUnlock_repaired_by", $record, $where);
	$msg	= ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";
		## Rebuild the cached list
} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {

	$cache = new cache();
	$cache->clearCache();
	$where = "id=".$db->mySQLSafe($_GET["delete"]);
	// delete index
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_repaired_by", $where);
	
	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['products_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_delete_failed']."</p>";
	## Rebuild the cached list
	
} else if (isset($_POST['id'])) {
	$record["id"] = $db->mySQLSafe($_POST['id']);	
	$record["title"] = $db->mySQLSafe($_POST['name']);	
	if (is_numeric($_POST['id'])) {
		
		$where = "id=".$db->mySQLSafe($_POST['id']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_repaired_by", $record, $where);
		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";
	} else {		
	
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_repaired_by", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_add_failed']."</p>";
		
	}
	## Rebuild the cached list
}

if (!isset($_GET['mode'])) {
	// make sql query
	if (isset($_GET['edit']) && $_GET['edit'] > 0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_repaired_by WHERE id = %s", $db->mySQLSafe($_GET['edit'])); 
	} else {
		//$whereClause = (is_numeric($_GET['parent'])) ? sprintf("cat_father_id = '%d'", $_GET['parent']) : 'cat_father_id = 0';
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_repaired_by";
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
	$url = (permission("products","write") == true) ? 'href="?_g=repair/repairedby&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;
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
<form method="post" id="reorderCategory" enctype="multipart/form-data">
<div class="wbox">
<div  class="headingBlackbg2">
  <span class="catid"><?php echo "ID" ?></span>
   <span class="catName2" style="width:695px"> <?php echo "Title"; ?></span>
 	<span class="action2">
  <?php echo $lang['admin']['products_action']; ?>
  </span>
</div>

<?php 
if ($results == true) {
	$count = count($results)-1;
	foreach ($results as $i => $result) {
		$cellColor	= cellColor($i);
		$sql		= sprintf("SELECT id FROM %sImeiUnlock_repaired_by WHERE id = '%d'", $glob['dbprefix'], $results[$i]['id']);
		$subcat		= $db->numrows($sql);
		
?>	
<div id="product_<?php echo $i; ?>" class="productRow <?php echo $cellColor; ?> tdText">
<span class="catid">
  <?php
	echo ($subcat >= 1) ? sprintf('%s', $results[$i]['id']) : $results[$i]['id'];
  ?>
  </span>
  <span class="catName2" style="width:695px;" ><?php echo $results[$i]['title'];?></span>
  
  <div class="action2">
  	<div class="right2"><?php
		switch($results[$i]['hide']) {
		
			case 0:
				$url	= (permission("products","edit")) ? 'href="?_g=repair/repairedby&amp;hide=1&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin_common']['show'];
				break;
			case 1:
				$url	= (permission("products","edit")) ? 'href="?_g=repair/repairedby&amp;hide=0&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin_common']['hide'];
				break;
		}
		echo sprintf('<a %s>%s</a>', $url, $title);
	?></div>
	<!--<div class="right1"><?php
		$url = (permission("products","edit"))? 'href="?_g=products/languages&amp;cat_master_id='.$results[$i]['id'].'" class="txtLink"' : $link401;
		echo sprintf('<a %s>%s</a>', $url, $lang['admin']['products_languages']);
	?></div>-->
	<div class="right2"><?php
		if (permission("products","delete") && $results[$i]['id'] != 0) {
			if ($results[$i]['noProducts'] <= 0) {
				$url = 'href="?_g=repair/repairedby&amp;delete='.$results[$i]['id'].'" onclick=" return confirm(\''.str_replace("\n", '\n', $lang['admin_common']['delete_q']).'\');" class="txtLink"';
			} else {
				$url = 'href="#" onclick="alert(\''.$lang['admin']['products_cannot_del'].'\')" class="txtNullLink"';
			}
		} else {
			$url = $link401;
		}
		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['delete']);
	?></div>
	<div class="right2"><?php
		$url = (permission("products","edit")) ? 'href="?_g=repair/repairedby&amp;edit='.$results[$i]['id'].'" class="txtLink"' : $link401;
		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);
	?></div>
    
	<?php if (permission("products","edit")) { ?>
	<span class="action" style="display:none;">
	<?php if ($i >= 1) { ?>
	  <a href="?_g=repair/repairedby&amp;move=<?php echo $results[$i]['id']; ?>&amp;to=<?php echo $i-1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=up">
	  <img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_up.gif" border="0" /></a>
	<?php  } ?>
	<?php if ($i < $count) { ?>
	  <a href="?_g=repair/repairedby&amp;move=<?php echo $results[$i]['id']; ?>&amp;to=<?php echo $i+1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=down">
	  <img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_down.gif" /></a>
	<?php } ?>
	</span>
	<?php } ?>
  </div>
  
  

  
  
  
  <input type="hidden" name="priority[]" value="<?php echo $results[$i]['id']; ?>" />
</div>

<?php
	} // end loop
	?></div>
    <?php
} else {
?>

  <div class="tdText"><?php echo "No Data Exit";?></div>
<?php } ?>
 
  </div>
</form>

<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  

if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 
?>

<form action="?_g=repair/repairedby" method="post" enctype="multipart/form-data" name="form1">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
  <tr>
    <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> <?php echo "Repaired By";?></td>
  </tr>
  <tr>
    <td class="tdText"><?php echo "Title";?></td>
    <td>
      <input name="name" type="text" class="textbox" value="<?php if(isset($results[0]['title'])) echo $results[0]['title']; ?>" maxlength="255"  required="required" />    
     
      
      </td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['products_category_status'];?></td>
  <td><input name="hide" value="1" type="checkbox" <?php if ($results[0]['hide']) echo 'checked="checked" '; ?>/></td>
  </tr>

  
  
     <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="id" value="<?php echo $results[0]['id']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>
  </tr>



  </table>
</form>
<?php 
} 

?>