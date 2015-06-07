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
		
 if (isset($_POST['id'])) {
	$record["id"] = $db->mySQLSafe($_POST['id']);	
	$record["subject"] = $db->mySQLSafe($_POST['subject']);
	$record["sms_content"] = $db->mySQLSafe($_POST['sms']);
	$fckEditor = (detectSSL() && !$config['force_ssl']) ?  str_replace($config['rootRel_SSL'], $glob['rootRel'], $_POST['FCKeditor']) : $_POST['FCKeditor'];
	$record["email_content"] = $db->mySQLSafe(str_replace('##HIDDEN##', '', $fckEditor));
	if (is_numeric($_POST['id'])) {
		
		$where = "id=".$db->mySQLSafe($_POST['id']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_email_content", $record, $where);
		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";
	} else {		
	
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_email_content", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['products_add_failed']."</p>";
		
	}
	## Rebuild the cached list
}

if (!isset($_GET['mode'])) {
	// make sql query
	if (isset($_GET['edit']) && $_GET['edit'] > 0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_email_content WHERE id = %s", $db->mySQLSafe($_GET['edit'])); 
	} else {
		//$whereClause = (is_numeric($_GET['parent'])) ? sprintf("cat_father_id = '%d'", $_GET['parent']) : 'cat_father_id = 0';
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_email_content";
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
	$url = (permission("products","write") == true) ? 'href="?_g=settings/emailContent&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;
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
   <span class="catName2" style="width:695px"> <?php echo "Email Title"; ?></span>
 	<span class="action2">
  <?php echo $lang['admin']['products_action']; ?>
  </span>
</div>

<?php 
if ($results == true) {
	$count = count($results)-1;
	foreach ($results as $i => $result) {
		$cellColor	= cellColor($i);
		$sql		= sprintf("SELECT id FROM %sImeiUnlock_email_content WHERE id = '%d'", $glob['dbprefix'], $results[$i]['id']);
		$subcat		= $db->numrows($sql);
		
?>	
<div id="product_<?php echo $i; ?>" class="productRow <?php echo $cellColor; ?> tdText">
<span class="catid">
  <?php
	echo ($subcat >= 1) ? sprintf('%s', $results[$i]['id']) : $results[$i]['id'];
  ?>
  </span>
  <span class="catName2" style="width:695px;" ><?php echo $results[$i]['content_type'];?></span>
  
  <div class="action2">
	<div class="right2" style="margin-right:60px;"><?php
		$url = (permission("products","edit")) ? 'href="?_g=settings/emailContent&amp;edit='.$results[$i]['id'].'" class="txtLink"' : $link401;
		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);
	?></div>
    
	<?php if (permission("products","edit")) { ?>
	<span class="action" style="display:none;">
	<?php if ($i >= 1) { ?>
	  <a href="?_g=settings/emailContent&amp;move=<?php echo $results[$i]['id']; ?>&amp;to=<?php echo $i-1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=up">
	  <img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_up.gif" border="0" /></a>
	<?php  } ?>
	<?php if ($i < $count) { ?>
	  <a href="?_g=settings/emailContent&amp;move=<?php echo $results[$i]['id']; ?>&amp;to=<?php echo $i+1; ?>&amp;parent=<?php echo $results[$i]['cat_father_id']; ?>&amp;dir=down">
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

  <div class="tdText"><?php echo "No Email Exit";?></div>
<?php } ?>
 
  </div>
</form>

<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  

if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 
?>

<form action="?_g=settings/emailContent" method="post" enctype="multipart/form-data" name="form1">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
  <tr>
    <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> <?php echo "Email Content";?></td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo "Email Subject";?></strong></td>
    <td>
      <input name="subject" type="text" class="textbox" value="<?php if(isset($results[0]['subject'])) echo $results[0]['subject']; ?>" maxlength="255"  required="required" />    
     
      
      </td>
  </tr>
  <tr>
    <td class="tdText" colspan="2"><strong><?php echo "Email Content";?></strong></td>
 
  </tr>
<tr>
<td colspan="2">
<?php
	
		require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");
		$oFCKeditor = new FCKeditor('FCKeditor');
		$oFCKeditor->BasePath = $glob['rootRel'].$glob['adminFolder'].'/includes/rte/' ;
		$oFCKeditor->Value = (isset($results[0]['email_content'])) ? $results[0]['email_content'] : $oFCKeditor->Value = "";
		if (!$config['richTextEditor']) $oFCKeditor->off = true;
		$oFCKeditor->Create();

?>
</td>

</tr>
  <tr>
    <td class="tdText"><strong><?php echo "SMS Content";?></strong></td>
    <td>
  <textarea name="sms" class="textbox" style="height:100px;"><?php if(isset($results[0]['sms_content'])) echo $results[0]['sms_content']; ?></textarea>
      </td>
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