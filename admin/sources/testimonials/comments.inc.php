<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage testimonials
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }
$lang = getLang("admin".CC_DS."admin_testimonials.inc.php");
permission("testimonials","read",$halt=TRUE);
//require("classes".CC_DS."gd".CC_DS."gd.inc.php");
$testimonialsPerPage = 25;
if (isset($_GET['approved'])) {
	$cache = new cache();
	$cache->clearCache();
	$record['approved'] = $_GET['approved'];
	$where = "testimonial_id=".$db->mySQLSafe($_GET['testimonial_id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_testimonials", $record, $where);
		
	$msg = ($update == true) ? "<p class='infoText'>Testimonial Updated Successfully.</p>" : "<p class='warnText'>Testimonial Updated Failed</p>";
}#else if (isset($_GET['new']) && $_GET['new']>0) {
	
	#$cache = new cache();
#	$cache->clearCache();	
#	$record['new'] = $_GET['mark'];
#	$where = "testimonial_id=".$db->mySQLSafe($_GET['new']);
#	$update = $db->update($glob['dbprefix']."ImeiUnlock_testimonials", $record, $where);		
#	$msg = ($update == true) ? "<p class='infoText'>Testimonial Updated Successfully.</p>" : "<p class='warnText'>Testimonial Updated Failed</p>";}
  else if (isset($_GET["delete"]) && $_GET["delete"]>0) {

	$cache = new cache();
	$cache->clearCache();

	// delete index
	$where = "testimonial_id=".$db->mySQLSafe($_GET["delete"]);	
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_testimonials", $where);
	$msg = ($delete == true) ? "<p class='infoText'>Testimonial Deleted Successfully.</p>" : "<p class='warnText'>Testimonial Deleted Failed</p>";
} else if (isset($_POST['testimonial_id'])) {
	$cache = new cache();
	$cache->clearCache();        
	
	$record["name"] = $db->mySQLSafe($_POST['testimonials_name']);
	$record["email"] = $db->mySQLSafe($_POST['testimonials_email']);
	$record["title"] = $db->mySQLSafe($_POST['testimonials_title']);
	$record["review"]= $db->mySQLSafe($_POST['testimonials_sdescription']);	
	$record["modified_on"]= $db->mySQLSafe(time());	
	$record['approved']	= (isset($_POST['approved'])&& $_POST['approved']>0 )?$db->mySQLSafe($_POST['approved']):0;		
	if (is_numeric($_POST['testimonial_id'])) {
		// update product count. This is gonna be tricky!!! 		
		$where = "testimonial_id=".$db->mySQLSafe($_POST['testimonial_id']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_testimonials", $record, $where);
		$msg .= ($update == true) ? "<p class='infoText'>'".$_POST['testimonials_title']."' Testimonial Updated Successfully!</p>" : "<p class='warnText'>".$lang['admin']['testimonials_update_fail']."</p>";
	} else {		
		$record["time"]= $db->mySQLSafe(time());
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_testimonials", $record);
		$msg .= ($insert == true) ? "<p class='infoText'>'".$_POST['testimonials_title']."'Testimonial Added Successfully </p>" : "<p class='warnText'>".$lang['admin']['testimonials_add_failed']."</p>";
	}
}

if (!isset($_GET['mode'])) {
	// make sql query
	if (isset($_GET['edit']) && $_GET['edit'] > 0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_testimonials WHERE testimonial_id = %s", $db->mySQLSafe($_GET['edit'])); 
	} else {		
		$whereClause = "1=1";
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_testimonials WHERE (title != 'Imported Products' OR review != '##HIDDEN##') ORDER BY  title ASC";
	}
	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
	
	// query database
	$results = $db->select($query, $testimonialsPerPage, $page);
	$numrows = $db->numrows($query);
	$pagination = paginate($numrows, $testimonialsPerPage, $page, "page");
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<div>
<?php
if (!isset($_GET['mode'])) {
	$url = (permission("testimonials","write") == true) ? 'href="?_g=testimonials/comments&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;
	echo sprintf('<span style="float: right;"><a %s><img src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', $lang['admin_common']['add_new']);
}
?>
  <p class="pageTitle">

	Customer Testimonials
	
  </p>
</div>
<?php 
if(isset($msg)) echo msg($msg);

if (!isset($_GET['mode']) && !isset($_GET['edit'])) {
?>
<p class="copyText"><?php echo $lang['admin']['testimonials_testimonials_listing_desc']; ?></p>
<p class="pagination right"><?php echo $pagination; ?></p>
<form method="post" id="reorderservice" >
<table width="100%" border="1" cellspacing="1" cellpadding="3" class="mainTable mainTable4">
  <tr>
     <td width="300px" align="center" nowrap="nowrap" class="tdTitle">Name</td>
    <td width="300px" align="center" nowrap="nowrap" class="tdTitle">Title</td>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['testimonials_action']; ?></td>
 
  </tr>
 
  <?php /*echo $lang['admin']['testimonials_status'];*/
if ($results == true) {
	for ($i=0; $i<count($results); $i++){
		$cellColor	= cellColor($i);		
?>	
<tr>
    <td align="center" class="<?php echo $cellColor; ?> tdText">
	<?php if($results[$i]['new']==1){?><img src="admin/images/new.png" alt="" style="float:left" width="25"><?php }?><?php echo $results[$i]['title'];?>
	</td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $results[$i]['name'];?> &nbsp;(<?php echo $results[$i]['email'];?>) <input type="hidden" name="priority[]" value="<?php echo $results[$i]['testimonial_id']; ?>" /></td>
     <td align="center" colspan="4" class="<?php echo $cellColor; ?>">
     	<?php
		switch($results[$i]['approved']) {
			case 0:
				$url	= (permission("testimonials","edit")==true) ? 'href="?_g=testimonials/comments&amp;approved=1&amp;testimonial_id='.$results[$i]['testimonial_id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin']['testimonials_testimonials_inactive'];
				break;
			case 1:
				$url	= (permission("testimonials","edit")==true) ? 'href="?_g=testimonials/comments&amp;approved=0&amp;testimonial_id='.$results[$i]['testimonial_id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin']['testimonials_testimonials_active'];
				break;
		}
		echo  sprintf('<a %s>%s</a>', $url, $title);
		
		$url = (permission("testimonials","edit")==true)? 'href="?_g=testimonials/languages&amp;testimonials_master_id='.$results[$i]['testimonial_id'].'" class="txtLink"' : $link401;
		echo sprintf('<a style="margin-left:35px;display:none;" %s>%s</a>', $url, $lang['admin']['testimonials_languages']);
		
			if (permission("testimonials","delete")==true) {			
			$url = 'href="javascript:void(0);" onclick="javascript:decision(\''.$lang['admin_common']['delete_q'].'\',\'?_g=testimonials/comments&amp;delete='.$results[$i]['testimonial_id'].'\');" class="txtLink"';			
		} else {
			$url = $link401;
		}
		echo sprintf('<a style="margin-left:35px;" %s>%s</a>', $url, $lang['admin_common']['delete']);
		
		$url = (permission("testimonials","edit")==true) ? 'href="?_g=testimonials/comments&amp;edit='.$results[$i]['testimonial_id'].'" class="txtLink"' : $link401;
		echo sprintf('<a style="margin-left:35px;" %s>%s</a>', $url, $lang['admin_common']['edit']);
	?>
    
</td>
  </tr>
<?php
	} // end loop
} else {
?>
 <tr>
<td align="center" colspan="6" class="<?php echo $cellColor; ?> tdText"><?php echo $lang['admin']['testimonials_no_cats_exist'];?></td>
 
  </tr>
<?php } ?>
</table>
  <!--<p>To re-order the testimonials, drag and drop them into your prefered order, then save</p>
  <p><input type="submit" class="submit" name="saveOrder" value="Save Order" /></p>-->
</form>
<script type="text/javascript">
	Sortable.create('reorderservice', {ghosting:true,constraint:false,tag:'div',only:'productRow'});
</script>
<p class="pagination right"><?php echo $pagination; ?></p>


<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  

if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 
?>
<p class="copyText"><?php echo $lang['admin']['testimonials_add_desc'];?></p><br />

<form action="?_g=testimonials/comments" method="post" enctype="multipart/form-data" name="form1">
<div class="headingBlackbg"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }?> <?php echo $lang['admin']['testimonials_testimonials'];?></div>
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">

 <? /*<tr>
    <td class="tdText">Date and Time:</td>
    <td>
      <input name="testimonials_date" type="text" class="textbox" value="<?php if(isset($results[0]['time'])) echo validHTML($results[0]['time']); ?>" size="10" maxlength="10" />    </td>
  </tr>*/?>
  <tr>
    <td width="17%" class="tdText">Author's Name:</td>
    <td width="83%">
       <div class="inputbox">
		<span class="bgleft"></span>
    	<input name="testimonials_name" type="text" class="textbox" value="<?php if(isset($results[0]['name'])) echo validHTML($results[0]['name']); ?>" maxlength="255" />
	   <span class="bgright"></span>
	   </div>
          </td>
  </tr>
  <tr>
    <td width="17%" class="tdText">Author's Email:</td>
    <td width="83%">
    <div class="inputbox">
		<span class="bgleft"></span>
    	<input name="testimonials_email" type="text" class="textbox" value="<?php if(isset($results[0]['email'])) echo validHTML($results[0]['email']); ?>" maxlength="255" />
	   <span class="bgright"></span>
	   </div>
          </td>
  </tr>
  <tr>
    <td width="17%" class="tdText">Title:</td>
    <td width="83%">
     <div class="inputbox">
		<span class="bgleft"></span>
    	<input name="testimonials_title" type="text" class="textbox" value="<?php if(isset($results[0]['title'])) echo validHTML($results[0]['title']); ?>" maxlength="255" />
	   <span class="bgright"></span>
	   </div>
          </td>
  </tr>
  <tr>
    <td width="17%" class="tdText">Comments & reviews:</td>
    <td width="83%">
      <textarea class="textarea3" style="width:386px; padding:2px;" name="testimonials_sdescription" id="testimonials_sdescription" rows="5" cols="10"><?php if(isset($results[0]['review'])) echo validHTML($results[0]['review']); ?></textarea>
     </td>
  </tr>
  <tr>
    <td width="17%" class="tdText">Status:</td>
    <td width="83%">
     <div class="inputbox">
		<span class="bgleft"></span>
    	 <select name="approved" id="hide">
    <option value="0" <?php if (isset($results[0]['approved']) && $results[0]['approved']==0) echo 'selected="selected"'; ?>>Publish</option>
    <option value="1" <?php if (isset($results[0]['approved']) && $results[0]['approved']==1) echo 'selected="selected"'; ?>>Unpublish</option>    
    </select> 
	   <span class="bgright"></span>
	   </div>
     </td>
  </tr> 
   <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="testimonial_id" value="<?php echo $results[0]['testimonial_id']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>
  </tr>
  </table>
</form>
<?php 
} 

?>