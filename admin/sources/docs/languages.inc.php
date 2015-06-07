<?php
/*
+--------------------------------------------------------------------------
|	languages.inc.php
|   ========================================
|	Manage Site Docs in Other Languages
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_docs.inc.php");

permission("documents","read", true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

$path = CC_ROOT_DIR.CC_DS."language";
$options = "";
foreach (glob($path.CC_DS.'*') as $langpath) {
	$folder = basename($langpath);
	if (is_dir($langpath) && preg_match('#^[a-z]{2}(\_[A-Z]{2})?$#iuU', $folder) && $folder !== $config['defaultLang']) {
		if (file_exists($langpath.CC_DS.'config.php')) {
			include $langpath.CC_DS.'config.php';
			
			$selected = ($config['defaultLang']==$folder) ? ' selected="selected"' : '';
			$options .= sprintf('<option value="%s"%s>%s</option>', $folder, $selected, $langName);
		}
	}
}

// delete document
if(isset($_GET['delete']) && $_GET['delete']>0){

$where = "id = ".$db->mySQLSafe($_GET['delete']);

$delete = $db->delete($glob['dbprefix']."ImeiUnlock_docs_lang", $where, ""); 

	if($delete == TRUE){
		$msg = "<p class='infoText'>".$lang['admin']['docs_delete_success']."</p>";
	} else {
		$msg = "<p class='infoText'>".$lang['admin']['docs_delete_fail']."</p>";
	}
	$cache = new cache();
	$cache->clearCache();
	
} elseif(isset($_POST['id']) && $_POST['id']>0){
// instantiate db class

$record["doc_name"] = $db->mySQLSafe($_POST['doc_name']);
$record["doc_lang"] = $db->mySQLSafe($_POST['doc_lang']);
$record["doc_master_id"] = $db->mySQLSafe($_GET['doc_master_id']);
$fckEditor = (detectSSL()==true && $config['force_ssl']==false) ?  str_replace($config['rootRel_SSL'],$glob['rootRel'],$_POST['FCKeditor']) : $_POST['FCKeditor'];		
$record["doc_content"] = $db->mySQLSafe($fckEditor);
							
$where = "id = ".$db->mySQLSafe($_POST['id']);

$update =$db->update($glob['dbprefix']."ImeiUnlock_docs_lang", $record, $where);
			
	if($update == TRUE){
		$msg = "<p class='infoText'>'".$_POST['doc_name']."' ".$lang['admin']['docs_update_success']."</p>"; 
	} else {
		$msg = "<p class='warnText'>'".$_POST['doc_name']."' ".$lang['admin']['docs_update_fail']."</p>"; 
	}
	
	$cache = new cache();
	$cache->clearCache();

} elseif(isset($_POST['id']) && empty($_POST['id'])){
// instantiate db class

$record["doc_name"] = $db->mySQLSafe($_POST['doc_name']);
$record["doc_lang"] = $db->mySQLSafe($_POST['doc_lang']);
$record["doc_master_id"] = $db->mySQLSafe($_GET['doc_master_id']);
$fckEditor = (detectSSL()==true && $config['force_ssl']==false) ?  str_replace($config['rootRel_SSL'],$glob['rootRel'],$_POST['FCKeditor']) : $_POST['FCKeditor'];			
$record["doc_content"] = $db->mySQLSafe($fckEditor);	

$insert = $db->insert($glob['dbprefix']."ImeiUnlock_docs_lang", $record);

	if($insert == TRUE){
		$msg = "<p class='infoText'>'".$_POST['doc_name']."' ".$lang['admin']['docs_add_success']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['docs_add_fail']."</p>";
	}
}

// retrieve current documents
if(!isset($_GET['mode'])){
	
	// make sql query
	if(isset($_GET['edit']) && $_GET['edit']>0){
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE id = %s", $db->mySQLSafe($_GET['edit'])); 
	} else {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_master_id = %s ORDER BY doc_name ASC", $db->mySQLSafe($_GET['doc_master_id'])); 
	} 
	
	// query database
	$results = $db->select($query);
} // end if mode is not new
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap'><p class="pageTitle"><?php echo $lang['admin']['docs_site_docs_other_lang']; ?></p></td>
    <?php if(!isset($_GET["mode"])){ ?><td align="right" valign="middle"><a <?php if(permission("documents","write")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=docs/languages&amp;mode=new&amp;doc_master_id=<?php echo $_GET['doc_master_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new']; ?></a></td><?php } ?>
  </tr>
</table>
<?php 
if((isset($_GET['edit']) && $_GET['edit']>0) || (isset($_GET['mode']) && $_GET['mode']=="new")){  
		
	if($options == "") {
		
		echo "<p class='copyText'>".$lang['admin']['docs_no_langs']."</p>";
		
	} else {
		
		if($_GET['mode']=="new"){
		// get recordset of old doc to translate
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id = %s", $db->mySQLSafe($_GET['doc_master_id']));
		$results = $db->select($query);
		
		}
		?>

<form action="<?php echo $glob['adminFile']; ?>?_g=docs/languages&amp;doc_master_id=<?php echo $_GET['doc_master_id']; ?>" target="_self" method="post" language="javascript">
<p class="copyText"><?php echo $lang['admin']['docs_use_rich_text'];?></p>
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
  	<td class="tdTitle"><?php echo $lang['admin']['docs_site_doc_other_lang'];?></td>
  </tr>
  <tr>
    <td class="tdRichText"><span class="copyText"><strong><?php echo $lang['admin']['docs_doc_name'];?></strong></span> <input name="doc_name" class="textbox" value="<?php echo $results[0]['doc_name']; ?>" type="text" maxlength="255" /></td>
  </tr>
  <tr>
    <td class="tdRichText"><span class="copyText"><strong><?php echo $lang['admin']['docs_language'];?></strong></span>
		<select class="textbox" name="doc_lang">
		<?php echo $options; ?>
			</select>
	</td>
  </tr>
  <tr>
    <td class="tdRichText">
<?php
	require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");
	$oFCKeditor = new FCKeditor('FCKeditor');
	$oFCKeditor->BasePath = $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/';
	$oFCKeditor->Value = $results[0]['doc_content'];
	if($config['richTextEditor']==0) {
		$oFCKeditor->off = TRUE;
	}
	$oFCKeditor->Create();
?></td>
  </tr>
  <tr>
    <td class="tdRichText">
	<input type="hidden" value="<?php echo $_GET['edit']; ?>" name="id" />
	<input name="submit" type="submit" class="submit" id="submit" <?php if($_GET['mode']!=="new"){ ?>value="<?php echo $lang['admin']['docs_update_doc'];?>"<?php } else { echo "value=\"".$lang['admin']['docs_save_doc']."\""; } ?> /></td>
  </tr>
</table>
</form>
<?php
	} 
} 
else 
{
if(isset($msg))
{ 
	echo msg($msg); 
} else { 
?>
<p class="copyText"><?php echo $lang['admin']['docs_current_doc_list']; ?></p>
<?php } ?>
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td class="tdTitle" width="80%"><?php echo $lang['admin']['docs_doc_name2']; ?></td>
    <td class="tdTitle" colspan="2" align="center" width="20%"><?php echo $lang['admin']['docs_action']; ?></td>
  </tr>
  <?php 
  if($results == TRUE){
  	for ($i=0; $i<count($results); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
	
  ?>
  <tr>
    <td width="80%" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['doc_name']; ?></span> <img src="language/<?php echo $results[$i]['doc_lang']; ?>/flag.gif" alt="" title="" /></td>
    <td align="center" width="10%" class="<?php echo $cellColor; ?>"><a <?php if(permission("documents","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=docs/languages&amp;edit=<?php echo $results[$i]['id']; ?>&amp;doc_master_id=<?php echo $_GET['doc_master_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?> ><?php echo $lang['admin_common']['edit']; ?></a></td>
    <td align="center" width="5%" class="<?php echo $cellColor; ?>"><a <?php if(permission("documents","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=docs/languages&amp;delete=<?php echo $results[$i]['id']; ?>&amp;doc_master_id=<?php echo $_GET['doc_master_id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a></td>
  </tr>
  <?php } // end loop
  } else { ?>
   <tr>
    <td colspan="3" class="tdText"><?php echo $lang['admin']['docs_no_site_docs']; ?></td>
  </tr>
  <?php } ?>
</table>

<?php 
} 
?>