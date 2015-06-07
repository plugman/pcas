<?php
/*
+--------------------------------------------------------------------------
|	home.inc.php
|   ========================================
|	Manage Homepage Content
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_docs.inc.php");

permission("documents", "read", true);

$editLang = (isset($_GET['homeLang']) && !empty($_GET['homeLang'])) ? $_GET['homeLang'] : $config['defaultLang'];

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

$path = CC_DS.preg_replace('/[^a-zA-Z0-9_\-\+]/', '', $editLang).CC_DS."home.inc.php";

// update file
if (isset($_POST['FCKeditor'])) {
	
	$postVars['enabled']	= $_POST['enabled'];
	$postVars['title']		= $_POST['title'];
	$fckEditor				= (detectSSL() && !$config['force_ssl'] && $glob['rootRel'] != '/') ?  str_replace($config['rootRel_SSL'], $glob['rootRel'], $_POST['FCKeditor']) : $_POST['FCKeditor'];
	$postVars['copy']		= stripslashes($fckEditor);
	
	$postVars['doc_metatitle']		= $_POST['doc_metatitle'];
	$postVars['doc_metadesc']		= $_POST['doc_metadesc'];
	$postVars['doc_metakeywords']	= $_POST['doc_metakeywords'];
	
	$langArray	= serialize($postVars);
	$sql		= sprintf("SELECT * FROM %sImeiUnlock_lang WHERE identifier = %s LIMIT 1;", $glob['dbprefix'], $db->mySQLsafe($path));
	
	if ($db->numrows($sql) == 1) {	
		$cacheQuery = sprintf("UPDATE %sImeiUnlock_lang SET langArray = '%s' WHERE identifier = %s LIMIT 1;", $glob['dbprefix'], addslashes($langArray), $db->mySQLsafe($path));
	} else {
		$cacheQuery = sprintf("INSERT INTO %sImeiUnlock_lang (identifier, langArray) VALUES (%s,'%s');", $glob['dbprefix'], $db->mySQLsafe($path), addslashes($langArray));
	}
	$db->misc($cacheQuery);
	
	$msg = sprintf('<p class="infoText">%s</p></br>', "`".$_GET['homeLang']."` ".$lang['admin']['docs_update_success']);
	
	$cache = new cache();
	$cache->clearCache();
} // end if copy is set and not empty
// read file

?>
<p class="pageTitle"><?php echo $lang['admin']['docs_homepage']; ?></p><br />

<?php 
if (isset($msg)) { 
	echo msg($msg);
} else { 
?>
<p class="copyText"><?php echo $lang['admin']['docs_use_rich_text']; ?></p><br />

<?php
}

if (permission("documents","edit")) {	
	$sql = sprintf("SELECT * FROM %sImeiUnlock_lang WHERE identifier = %s", $glob['dbprefix'], $db->mySQLsafe($path));
	$result = $db->select($sql);
	
	if (!$result) {
		include 'language'.$path;
	} else {
		$home = unserialize($result[0]['langArray']);
	}
	
?>
<form action="<?php echo $glob['adminFile']; ?>?_g=docs/home&amp;homeLang=<?php echo $editLang; ?>" target="_self" method="post" language="javascript">
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable mainTable4 mainTable8">
  <tr>
  	<td colspan="2" class="tdTitle"><?php echo $lang['admin']['docs_homepage']; ?></td>
  </tr>
  <tr>
    <td width="25%" class=""><span class="copyText"><strong><?php echo $lang['admin']['docs_language']; ?></strong></span></td>
    <td class="">
	<?php
	$basePath = CC_ROOT_DIR.CC_DS."language";
	
	if ($dir = opendir($basePath)) {
		?>
        <div class="inputbox">
		<span class="bgleft"></span>
    	<select class="textbox" name="homeLang" onchange="jumpMenu('parent',this,0)">
		<?php
	
		while (FALSE !== ($folder = readdir($dir))) {
	
			if (preg_match('#^[a-z]{2}$#', $folder)){
			
				include($basePath.CC_DS.$folder.CC_DS."config.php");
			?>

				<option  value="<?php echo $glob['adminFile']; ?>?_g=docs/home&amp;homeLang=<?php echo $folder; ?>" <?php if($editLang==$folder) echo "selected='selected'"; ?>><?php echo $langName; ?></option>
			<?php 
			}
		} 
		?>
		</select>	
	   <span class="bgright"></span>
	   </div>
		
		
	<?php 
	} 
	?>
	</td>
  </tr>
  <tr>
    <td width="25%" class=""><span class="copyText"><strong><?php echo $lang['admin']['docs_enabled']; ?></strong><br />
	<?php echo $lang['admin']['docs_enabled_desc']; ?></span></td>
    <td class="">
       <div class="inputbox">
		<span class="bgleft"></span>
    	<select name="enabled">
			<?php if($editLang!==$config['defaultLang']) { ?>
			<option value="0"><?php echo $lang['admin_common']['no']; ?></option>
			<?php } ?>
			<option value="1" <?php if ($home['enabled']==1) echo 'selected="selected"'; ?>><?php echo $lang['admin_common']['yes']; ?></option>
		</select>	
	   <span class="bgright"></span>
	   </div>
		
	</td>
  </tr>
  <tr>
    <td width="25%" class=""><span class="copyText"><strong><?php echo $lang['admin']['docs_title']; ?></strong></span></td>
    <td class="">
      <div class="inputbox">
		<span class="bgleft"></span>
    	<input name="title" class="textbox" type="text" value="<?php echo stripslashes($home['title']); ?>" />	
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="">
<?php
	require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");
	
	$oFCKeditor				= new FCKeditor('FCKeditor');
	$oFCKeditor->BasePath	= $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/';
	$oFCKeditor->Value		= stripslashes($home['copy']);
		
	if (!$config['richTextEditor']) $oFCKeditor->off = true;
	$oFCKeditor->Create();
?>
	</td>
  </tr>
  <tr>
    <td colspan="2" class=""><input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin']['docs_update_homepage']; ?>" /></td>
  </tr>
</table>
<?php if ($config['seftags']) { ?>
<br />
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable mainTable4 mainTable8">
  <tr> 
	<td colspan="2" class="tdTitle"><strong><?php echo $lang['admin']['docs_seo_title']; ?></strong></td>
  </tr>
  <tr> 
	<td width="25%" class="tdText"><strong><?php echo $lang['admin']['settings_meta_browser_title']; ?></strong></td>
	<td align="left">
      <div class="inputbox">
		<span class="bgleft"></span>
    	<input name="doc_metatitle" type="text" size="35" value="<?php if(isset($home['doc_metatitle'])) echo stripslashes($home['doc_metatitle']); ?>" />	
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
  <tr> 
	<td width="25%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_desc'];?></strong></td>
	<td align="left"><textarea name="doc_metadesc" cols="35" rows="3" class="textarea textarea2"><?php if(isset($home['doc_metadesc'])) echo stripslashes($home['doc_metadesc']); ?></textarea></td>
  </tr>
  <tr> 
	<td width="25%" align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_keywords'];?></strong> <?php echo $lang['admin']['settings']['comma_separated'];?></td>
	<td align="left"><textarea name="doc_metakeywords" cols="35" rows="3" class="textarea textarea2"><?php if(isset($home['doc_metakeywords'])) echo stripslashes($home['doc_metakeywords']); ?></textarea></td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td><input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin']['docs_update_homepage']; ?>" /></td>
  </tr>
</table>
<?php } ?>
</form>
<?php } else { ?>
  <div><?php echo $contents; ?></div>
<?php } ?>