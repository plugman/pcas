<?php

/*

+--------------------------------------------------------------------------

|	siteDocs.inc.php

|   ========================================

|	Manage Site Docs

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_docs.inc.php");

permission("documents","read", true);





if (isset($_POST['saveterms']) && !empty($_POST['termsid'])) {

	$sql = sprintf("UPDATE %sImeiUnlock_docs SET doc_terms = 0 WHERE 1;", $glob['dbprefix']);

	$db->misc($sql);

	$sql = sprintf("UPDATE %sImeiUnlock_docs SET doc_terms = 1 WHERE doc_id = %d;", $glob['dbprefix'], $_POST['termsid']);

	$db->misc($sql);

}



## delete document

if (isset($_GET['dir'])) {

	switch ($_GET['dir']) {

		case 'up':

			$query = sprintf("UPDATE %sImeiUnlock_docs SET doc_order = '%d' WHERE doc_order = '%d'", $glob['dbprefix'], $_GET['moveto']+1, $_GET['moveto']);

			$db->misc($query);

				

			$query = sprintf("UPDATE %sImeiUnlock_docs SET doc_order = '%d' WHERE doc_id = '%d'", $glob['dbprefix'], $_GET['moveto'], $_GET['id']);

			$db->misc($query);

			break;

			

		case 'down':

		case 'dn':

			$query = sprintf("UPDATE %sImeiUnlock_docs SET doc_order = '%d' WHERE doc_order = '%d'", $glob['dbprefix'], $_GET['moveto']-1, $_GET['moveto']);

			$db->misc($query);

				

			$query = sprintf("UPDATE %sImeiUnlock_docs SET doc_order = '%d' WHERE doc_id = '%d'", $glob['dbprefix'], $_GET['moveto'], $_GET['id']);

			$db->misc($query);

			break;

			

		case 'reset':

			$query = sprintf("UPDATE %sImeiUnlock_docs SET doc_order = doc_id WHERE 1", $glob['dbprefix']);

			$db->misc($query);

			break;

	}

	

	$cache = new cache();

	$cache->clearCache();



} else if (isset($_GET['delete']) && $_GET['delete']>0) {



	$where = "doc_id = ".$db->mySQLSafe($_GET['delete']);

	

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_docs", $where, ""); 

	

	if ($delete) {

		$msg = "<p class='infoText'>".$lang['admin']['docs_delete_success']."</p>";

	} else {

		$msg = "<p class='warnText'>".$lang['admin']['docs_delete_fail']."</p>";

	}

	

	$cache = new cache();

	$cache->clearCache();

	

} else if (isset($_POST['docId'])) {



	$record['doc_name']		= $db->mySQLSafe($_POST['doc_name']);

	$record['doc_url']		= $db->mySQLSafe($_POST['doc_url']);
	//$record['box']		= $db->mySQLSafe($_POST['box']);

	$record['doc_url_openin']	= $db->mySQLSafe($_POST['doc_url_openin']);

	

	## Fix for bug 315

	$fckEditor				= (detectSSL() && !$config['force_ssl'] && $glob['rootRel'] != '/') ? str_replace($config['rootRel_SSL'], $glob['rootRel'], $_POST['FCKeditor']) : $_POST['FCKeditor'];

	$record['doc_content']	= $db->mySQLSafe($fckEditor);

	

	if ($config['seftags']) {

		$record['doc_metatitle']	= $db->mySQLSafe($_POST['doc_metatitle']);

		$record['doc_metadesc']		= $db->mySQLSafe($_POST['doc_metadesc']);

		$record['doc_metakeywords']	= $db->mySQLSafe($_POST['doc_metakeywords']);

	}



	if (is_numeric($_POST['docId']) && !is_null($_POST['docId'])) {

		$update = $db->update($glob['dbprefix'].'ImeiUnlock_docs', $record, array('doc_id' => $_POST['docId']));

		

		if ($update) {

			$msg = '<p class="infoText">\''.$_POST['doc_name'].'\' '.$lang['admin']['docs_update_success'].'</p>'; 

		} else {

			$msg = '<p class="warnText">\''.$_POST['doc_name'].'\' '.$lang['admin']['docs_update_fail'].'</p>';

		}

	} else {

		$insert = $db->insert($glob['dbprefix'].'ImeiUnlock_docs', $record);

		

		if ($insert) {

			$msg = '<p class="infoText">\''.$_POST['doc_name'].'\' '.$lang['admin']['docs_add_success'].'</p>';

			$db->misc("UPDATE ".$glob['dbprefix']."ImeiUnlock_docs SET doc_order = doc_id WHERE doc_id = ".$db->insertid());

		} else {

			$msg = '<p class="infoText">'.$lang['admin']['docs_add_fail'].'</p>';

		}

	}

	$cache = new cache();

	$cache->clearCache();	

}



## Retrieve current documents

if (!isset($_GET['mode'])) {

	## Create the SQL query

	if (isset($_GET['edit']) && $_GET['edit']>0) {

		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id = %s", $db->mySQLSafe($_GET['edit'])); 

	} else {

		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_docs ORDER BY doc_order ASC";

	}

	$results = $db->select($query);

}





require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

echo msg($msg);

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="mainTable mainTable4 mianTable8">

  <tr>

    <td nowrap='nowrap'><p style="margin-bottom:10px;" class="pageTitle"><?php echo $lang['admin']['docs_site_docs']; ?></p></td>

    <?php if (!isset($_GET["mode"])){ ?><td align="right" valign="middle">

	<?php if (permission("documents", "write") == true) { ?>

	<a href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs&amp;mode=new" class="txtLink">

	<?php } else { echo '<a '.$link401.'>'; } ?>

	<img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new']; ?></a>

	</td><?php } ?>

  </tr>

</table>

<?php if((isset($_GET['edit']) && $_GET['edit']>0) || (isset($_GET['mode']) && $_GET['mode']=="new")){ ?>

<form action="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs" target="_self" method="post">

<p style="margin-bottom:5px;" class="copyText"><?php echo $lang['admin']['docs_use_rich_text'];?></p>

<div class="headingBlackbg"><?php echo $lang['admin']['docs_site_doc']; ?></div>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="mainTable">

  <tr>

    <td width="24%" align="right"><span class="copyText" style="float:left; line-height:34px; margin-right:10px"><strong>

    <?php echo $lang['admin']['docs_doc_name'];?></strong></span> 

     <div class="inputbox"  style=" width:200px;">

		<span class="bgleft"></span>

    	 <input name="doc_name" style="width:190px;" class="textbox" value="<?php if(isset($results[0]['doc_name'])) echo $results[0]['doc_name']; ?>" type="text" maxlength="255" />	

	   <span class="bgright"></span>

	   </div>

   </td>

  </tr>

  <tr>

    <td width="24%" align="right" class=""><span class="copyText" style="float:left; line-height:34px; margin-right:20px"><strong><?php echo $lang['admin']['docs_url'];?></strong></span>

	 <div class="inputbox"  style=" width:200px;">

		<span class="bgleft"></span>

    	<input name="doc_url" class="textbox" style="width:190px;" value="<?php if(isset($results[0]['doc_url'])) echo $results[0]['doc_url']; ?>" type="text" maxlength="255" />	

	   <span class="bgright"></span>

	   </div>

       <div class="inputbox"  style=" width:200px; margin-left:10px;">

		<span class="bgleft"></span>

     <select name="doc_url_openin" style="width:190px;" class="textbox">

		<?php

		$options = array(

			$lang['admin']['docs_url_open_same'],

			$lang['admin']['docs_url_open_new'],

		#	$lang['admin']['docs_url_open_light'],

		);

		foreach ($options as $key => $value) {

			$selected = ($key == $results[0]['doc_url_openin']) ? 'selected="selected"' : '';

			echo sprintf('<option value="%d"%s>%s</option>', $key, $selected, $value);

		}

		?>

	  </select>	

	   <span class="bgright"></span>

	   </div>

	 

	</td>

  </tr>
<!--<tr>

    <td width="24%" align="right" class=""><span class="copyText" style="float:left; line-height:34px; margin-right:60px"><strong>Doc Box</strong></span>

       <div class="inputbox"  style=" width:200px; ">

		<span class="bgleft"></span>

     <select name="box" style="width:190px;" class="textbox">

		<?php

		$options = array(

			

			"Footer",
			"Repair Box",

		#	$lang['admin']['docs_url_open_light'],

		);

		foreach ($options as $key => $value) {

			$selected = ($key == $results[0]['box']) ? 'selected="selected"' : '';

			echo sprintf('<option value="%d"%s>%s</option>', $key, $selected, $value);

		}

		?>

	  </select>	

	   <span class="bgright"></span>

	   </div>

	 

	</td>

  </tr>-->
  <tr>

    <td class="">

<?php



	require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");

	$oFCKeditor = new FCKeditor('FCKeditor');

	$oFCKeditor->BasePath = $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/';

	$oFCKeditor->Value = (isset($results[0]['doc_content'])) ? (!get_magic_quotes_gpc ()) ? stripslashes($results[0]['doc_content']) : $results[0]['doc_content'] : '';

	if ($config['richTextEditor'] == 0) {

		$oFCKeditor->off = true;

	}

	$oFCKeditor->Create();

?></td>

  </tr>

   <tr>

	<td><input type="hidden" value="<?php if(isset($_GET['edit'])) echo $_GET['edit']; ?>" name="docId" />

	<input name="doc_order" type="hidden" value="<?php echo ($results == true) ? $results[0]['doc_order'] : 0; ?>" />

	<input name="submit" type="submit" class="submit" id="submit" value="<?php echo (isset($results) && $results == true) ? $lang['admin']['docs_update_doc'] : $lang['admin']['docs_save_doc']; ?>" /></td>

    </tr>

 </table>

	

	<?php if ($config['seftags']) { // && $config['sef']) { ?>

<br />

<div class="headingBlackbg"><?php echo $lang['admin']['docs_seo_title']; ?></div>

	<table style="margin-top:5px;" width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">

	  <tr> 

		<td width="14%" class="tdText"><strong><?php echo $lang['admin']['settings_meta_browser_title']; ?></strong></td>

		<td align="left">

         <div class="inputbox"  style=" width:200px;">

		<span class="bgleft"></span>

    	 <input name="doc_metatitle" style="width:190px;" type="text" size="35" class="textbox" value="<?php if(isset($results[0]['doc_metatitle'])) echo stripslashes($results[0]['doc_metatitle']); ?>" />

	   <span class="bgright"></span>

	   </div>

       </td>

	  </tr>

	  <tr> 

		<td width="14%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_desc'];?></strong></td>

		<td align="left"><textarea name="doc_metadesc" cols="35" rows="3" class="textarea textarea2"><?php if(isset($results[0]['doc_metadesc'])) echo stripslashes($results[0]['doc_metadesc']); ?></textarea></td>

	  </tr>

	  <tr> 

		<td width="14%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_keywords'];?></strong> <?php echo $lang['admin']['settings']['comma_separated'];?></td>

		<td align="left"><textarea name="doc_metakeywords" cols="35" rows="3" class="textarea textarea2"><?php if(isset($results[0]['doc_metakeywords'])) echo stripslashes($results[0]['doc_metakeywords']); ?></textarea></td>

	  </tr>

	  <tr>

	    <td>&nbsp;</td>

	    <td>

		<input name="submit" type="submit" class="submit" id="submit" value="<?php echo (isset($results) && $results == true) ? $lang['admin']['docs_update_doc'] : $lang['admin']['docs_save_doc']; ?>" />

		</td>

	  </tr>

	</table>

<?php } ?>

</form>

<?php

} else { 

?>

<p class="copyText"><?php echo $lang['admin']['docs_current_doc_list']; ?></p>



<form action="" id="setTerms" method="post">

<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mainTable mainTable4 mainTable6">

  <tr>

    <td class="tdTitle" width="10">&nbsp;</td>

    <td class="tdTitle"><?php echo $lang['admin']['docs_doc_name2']; ?></td>

    <td align="center" width="10%" class="tdTitle"><?php echo $lang['admin']['docs_order']; ?></td>

    <td class="tdTitle" width="15%" colspan="3" align="center"><?php echo $lang['admin']['docs_action']; ?></td>

  </tr>

<?php 

	if ($results) {

		$cellColor = "";

		$pos = 1;

		for ($i=0; $i<count($results); $i++) { 

			$cellColor = cellColor($i);

?>

  <tr>

    <td class="<?php echo $cellColor; ?>"><input name="termsid" type="radio" value="<?php echo $results[$i]['doc_id']; ?>" <?php if ($results[$i]['doc_terms'] == 1) echo 'checked="checked"'; ?> /></td>

    <td class="<?php echo $cellColor; ?>"><a href="<?php echo $glob['rootRel']."index.php?_a=viewDoc&amp;docId=".$results[$i]['doc_id']; ?>" target="_blank" class="txtLink"><?php echo $results[$i]['doc_name']; ?></a></td>

    <td width="10%" align="center" class="<?php echo $cellColor; ?>">

<?php

		if ($i>0) {

		?>

		<a href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs&amp;dir=up&amp;id=<?php echo $results[$i]['doc_id']; ?>&amp;moveto=<?php echo $pos-1; ?>">

		<img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_up.gif" border="0" /></a>

<?php

		} 

		if ($i!==count($results)-1) {

		?>

		<a href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs&amp;dir=dn&amp;id=<?php echo $results[$i]['doc_id']; ?>&amp;moveto=<?php echo $pos+1; ?>">

		<img src="<?php echo $glob['rootRel']; ?>images/admin/arrow_down.gif" border="0" /></a>

		<?php

		}

	?>

	</td>

    <td   class="<?php echo $cellColor; ?>" colspan="3">

	<?php if (permission("documents","edit") == true) { ?>

	<a style="margin-left:20px;" href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs&amp;edit=<?php echo $results[$i]['doc_id']; ?>" class="txtLink">

	<?php } else { echo '<a '.$link401.'>'; } ?>

	<img alt="" src="images/admin/edit.png"  /></a>



	<?php 

	if (permission("documents","delete") == true) { ?>

	<a style="margin-left:20px;" href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs&amp;delete=<?php echo $results[$i]['doc_id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink">

	<?php } else { echo '<a '.$link401.'>'; } ?>

	<img alt="" src="images/admin/delete.png" /></a>



	<?php if(permission("documents","edit") == true) { ?>

	<a style="margin-left:20px;" href="<?php echo $glob['adminFile']; ?>?_g=docs/languages&amp;doc_master_id=<?php echo $results[$i]['doc_id']; ?>" class="txtLink">

	<?php } else { echo '<a '.$link401.'>'; } ?>

	<img alt="" src="images/admin/lang.gif"  /></a>

	</td>

  </tr>

  <?php

  		$pos++;

  	} // end loop

?>

  <tr>

	<td>&nbsp;</td>

	<td colspan="5"><input type="submit" class="submit" name="saveterms" value="<?php echo $lang['admin']['docs_set_tac'];?>" /></td>

  </tr>



<?php

  } else { ?>

   <tr>

    <td colspan="5" class="tdText"><?php echo $lang['admin']['docs_no_docs']; ?></td>

  </tr>

  <?php } ?>

</table>

</form>



<?php 

} 

?>

