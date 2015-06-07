<?php

/*

+--------------------------------------------------------------------------

|	index.inc.php

|   ========================================

|	Manage faqs :: FM 19-04-13

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



$lang = getLang("admin".CC_DS."admin_faqs.inc.php");

permission("faq","read",TRUE);

$testimonialsPerPage = 50;



if (isset($_POST['saveOrder']) && !empty($_POST['priority'])) {

	foreach ($_POST['priority'] as $index => $faq_id) {

		$sql = sprintf("UPDATE %sImeiUnlock_faqs SET priority = '%d' WHERE faq_id = '%d' LIMIT 1;", $glob['dbprefix'], $index+1, $faq_id);

		$db->misc($sql);

	}

	$cache = new cache();

	$cache->clearCache();

}



if (isset($_GET['faq_status'])) {

	$cache = new cache();

	$cache->clearCache();

	

	$record['faq_status'] = $_GET['faq_status'];

	$where = "faq_id=".$db->mySQLSafe($_GET['faq_id']);

	$update = $db->update($glob['dbprefix']."ImeiUnlock_faqs", $record, $where);

	

	$msg = ($update == true) ? "<p class='infoText'>".$lang['admin']['faqs_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_update_fail']."</p>";

} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {



	$cache = new cache();

	$cache->clearCache();



	// delete index

	$where = "faq_id=".$db->mySQLSafe($_GET["delete"]);	

	

	// delete testimonials

	$where = "faq_id=".$db->mySQLSafe($_GET["delete"]);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_faqs", $where);

	

	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['faqs_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_delete_failed']."</p>";



} else if (isset($_POST['faq_id'])) {



	$cache = new cache();

	$cache->clearCache();

	

	$record["faq_title"] = $db->mySQLSafe($_POST['faq_title']);

	$record["type"] = $db->mySQLSafe($_POST['type']);

	

	$fckEditor = (detectSSL()==true && $config['force_ssl']==false) ?  str_replace($config['rootRel_SSL'],$glob['rootRel'],$_POST['FCKeditor']) : $_POST['FCKeditor'];

	$record["faq_description"] = $db->mySQLSafe(str_replace('##HIDDEN##', '', $fckEditor));	

	//$record["testimonials_date"] = $db->mySQLSafe($_POST['testimonials_date']);	

	$record["faq_status"] = $db->mySQLSafe($_POST['faq_status']);	



	if (is_numeric($_POST['faq_id'])) {

		// update product count. This is gonna be tricky!!! 

		

		$where = "faq_id=".$db->mySQLSafe($_POST['faq_id']);

		$update = $db->update($glob['dbprefix']."ImeiUnlock_faqs", $record, $where);



		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['faq_title']."' ".$lang['admin']['faqs_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_update_fail']."</p>";

	} else {		

		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_faqs", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['faq_title']."' ".$lang['admin']['faqs_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_add_failed']."</p>";

	}

}



if (!isset($_GET['mode'])) {

	// make sql query

	if (isset($_GET['edit']) && $_GET['edit'] > 0) {

		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_faqs WHERE faq_id = %s", $db->mySQLSafe($_GET['edit'])); 

	} else {		

		$whereClause = "1=1";

		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_faqs WHERE (faq_title != 'Imported Products' OR faq_description != '##HIDDEN##') ORDER BY priority, faq_title ASC";

	}

	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;

	

	// query database

	$results = $db->select($query, $testimonialsPerPage, $page);

	$numrows = $db->numrows($query);

	$pagination = paginate($numrows, $testimonialsPerPage, $page, "page");

}



require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 

?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td class="pageTitle">FAQ</td>

    <td  align="right">

    <?php

if (!isset($_GET['mode'])) {

	$url = (permission("faq","write") == true) ? 'href="?_g=faq/index&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;

	echo sprintf('<span class="right"><a %s><img class="left" src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', "<strong style='margin:4px 4px 0' class='left'>Add New</strong>");

}

?>

    </td>

  </tr>

  <tr>

    <td  height="10">&nbsp;</td>

    <td>&nbsp;</td>

  </tr>

</table>





  



<?php 

if(isset($msg)) echo msg($msg);



if (!isset($_GET['mode']) && !isset($_GET['edit'])) {

?>

<p class="copyText"><?php echo $lang['admin']['faqs_faqs_listing_desc']; ?></p>







<form method="post" id="" enctype="multipart/form-data">



<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">

<tr>

<td class="tdTitle" width="7%" align="center"> Sr.#</td>

<td class="tdTitle" width="75%">FAQ</td>

<td class="tdTitle"  align="center"><?php echo $lang['admin']['faqs_action']; ?></td>

</tr>

<?php 

if ($results == true) {

	for ($i=0; $i<count($results); $i++){

		$cellColor	= cellColor($i);		

?>	

<tr class="<?php echo $cellColor; ?>">

<td align="center"><?php echo ($i+1)?></td>

<td  id="product_<?php echo $results[$i]['faq_id']; ?>" >

 <?php echo $results[$i]['faq_title'];?>

  <input type="hidden" name="priority[]" value="<?php echo $results[$i]['faq_id']; ?>" />

</td>

<td align="center" class="a2">

	<span class="action" >

	<?php

		switch($results[$i]['faq_status']) {

			case 0:

				$url	= (permission("faq","edit")==true) ? 'href="?_g=faq/index&amp;faq_status=1&amp;faq_id='.$results[$i]['faq_id'].'" class="txtLink"' : $link401;

				$title	= $lang['admin']['faqs_faqs_active'];

				break;

			case 1:

				$url	= (permission("faq","edit")==true) ? 'href="?_g=faq/index&amp;faq_status=0&amp;faq_id='.$results[$i]['faq_id'].'" class="txtLink"' : $link401;

				$title	= $lang['admin']['faqs_faqs_inactive'];

				break;

		}

		echo sprintf('<a %s>%s</a>', $url, $title);

	?>

	</span>

	

	<span class="action">	

	<?php

		if (permission("faq","delete")==true) {			

			$url = 'href="javascript:void(0);" onclick="javascript:decision(\''.$lang['admin_common']['delete_q'].'\',\'?_g=faq/index&amp;delete='.$results[$i]['faq_id'].'\');" class="txtLink"';			

		} else {

			$url = $link401;

		}

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['delete']);

	?>

	</span>

	

	<span class="action" style="width:30px;">

	<?php

		$url = (permission("faq","edit")==true) ? 'href="?_g=faq/index&amp;edit='.$results[$i]['faq_id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);

	?></span>
<span class="action" style="width:30px;">

	<?php

		$url = (permission("faq","edit")==true) ? 'href="?_g=faq/languages&amp;faq_master_id='. $results[$i]['faq_id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['nav_edit_langs']);

	?></span>

	

	

  

</td>

</tr>











<?php

	}  // end loop ?>

	</table>  

    <?php

	   

} else {

?>

  <div class="tdText">Sorry, no data found.</div>

<?php } ?>

</form>

<script type="text/javascript">

	//Sortable.create('reordertestimonials', {ghosting:true,constraint:false,tag:'div',only:'productRow'});

</script>

<p class="copyText" align="right"><span class="pagination"><?php echo $pagination; ?></span></p>





<?php 

} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  



if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 

?>



<form action="?_g=faq/index" method="post" enctype="multipart/form-data" name="form1">

<table border="0" cellspacing="1" cellpadding="3" class="mainTable " width="100%">

  <tr>

    <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> FAQ</td>

  </tr>

  <tr>

    <td width="17%" class="tdText" style="font-weight:bold;">Question:</td>

    <td width="83%">

      <input name="faq_title" type="text" class="textbox" value="<?php if(isset($results[0]['faq_title'])) echo validHTML($results[0]['faq_title']); ?>" maxlength="1000" />    </td>

  </tr>

  <tr>

    <td class="tdText" style="font-weight:bold;">Answer</td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td colspan="2" class="tdText">

	<?php

	

		require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");

		$oFCKeditor = new FCKeditor('FCKeditor');

		$oFCKeditor->BasePath = $glob['rootRel'].$glob['adminFolder'].'/includes/rte/' ;

		$oFCKeditor->Value = (isset($results[0]['faq_description'])) ? $results[0]['faq_description'] : $oFCKeditor->Value = "";

		if (!$config['richTextEditor']) $oFCKeditor->off = true;

		$oFCKeditor->Create();



?>

</td>

    </tr>		

	    <tr>



    <td class="tdText"><strong>type:</strong></td>



    <td class="tdText">



	<select name="type" class="textbox">



        <option value="1" <?php if(isset($results[0]['type']) && $results[0]['type']==1) echo "selected='selected'"; ?>>iPhone Unlock</option>



        <option value="0" <?php if(isset($results[0]['type']) && $results[0]['type']==0) echo "selected='selected'"; ?>>General Information</option>

 <option value="2" <?php if(isset($results[0]['type']) && $results[0]['type']==2) echo "selected='selected'"; ?>>Other handsets</option>



      </select>



	</td>



  </tr>

  <tr>

    <td class="tdText"><?php echo $lang['admin']['faqs_faqs_status'];?></td>

    <td>

	  <input name="faq_status" type="checkbox" value="1" <?php if(isset($results[0]['faq_status']) && $results[0]['faq_status'] == 1) { ?> checked="checked" <?php }?> />

	  </td>

  </tr>

  

   <tr>

    <td>&nbsp;</td>

    <td>

	<input type="hidden" name="faq_id" value="<?php echo $results[0]['faq_id']; ?>" />

	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>

  </tr>

  </table>

</form>

<?php 

} 



?>