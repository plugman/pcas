	<?php
/*
+--------------------------------------------------------------------------
|	language.inc.php
|   ========================================
|	Manage Language Files
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) die("Access Denied");

permission("filemanager", "read", true);

$lang = getLang("admin".CC_DS."admin_languages.inc.php");

if (isset($_POST['custom'])) {
	
	$cache = new cache();
	$cache->clearCache();
	
	foreach ($_POST['custom'] as $def => $array) {
		foreach ($array as $key => $value) {
			$string = html_entity_decode(stripslashes($value));
			if (!strstr($_POST['identifier'], 'email.inc.php')) {
				$string = nl2br($string);
			}
			$saved[$def][$key] = $string;
		}
	}
	$serializedArray = serialize($saved);
	// $db->MySQLSafe() will break the serialized array
	
	$data['langArray'] = "'".addslashes($serializedArray)."'";
	$exist = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_lang WHERE identifier = ".$db->MySQLSafe($_POST['identifier']));
	
	if ($exist) {
		$result = $db->update($glob['dbprefix']."ImeiUnlock_lang", $data, "identifier = ".$db->MySQLSafe($_POST['identifier']));
	} else {
		$data['identifier'] = $db->MySQLSafe($_POST['identifier']);
		$result = $db->insert($glob['dbprefix']."ImeiUnlock_lang", $data);
	}
	
	## Notify
	if ($result) {
		$msg = "<p class='infoText'>".sprintf($lang['admin']['langs_lang_updated'], $_POST['identifier'])."</p>";
	} else {
		$msg = "<p class='warnText'>".sprintf($lang['admin']['langs_lang_not_updated'], $_POST['identifier'])."</p>";
	}
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
if (!isset($_GET['loc'])) { $_GET['loc'] = "/"; }

?>
<p class="pageTitle"><?php echo $lang['admin']['langs_edit_langs']; ?></p>

<?php if (isset($msg)) { echo msg($msg); } ?>

<p class="copyText"><strong><?php echo $lang['admin']['langs_currrent_loc']; ?></strong> <?php echo str_replace(".inc.php","",$_GET['loc']); ?></p>
<?php
$locArray = explode('/',$_GET['loc']);
$backLink = $glob['adminFile']."?_g=filemanager/language&amp;loc=";

if ($_GET['loc'] == '/') {
	$backLink .= '/';
} elseif (is_array($locArray)) {
	for ($i=0; $i<(count($locArray)-2); $i++) {
		$backLink .= $locArray[$i].'/';	
	}
}

if (isset($_GET['loc']) && $_GET['loc'] !== '/') {
?>
<a href="<?php echo $backLink; ?>" class="txtLink"><img src="<?php echo $glob['adminFolder']; ?>/images/back.gif" width="20" height="22" border="0" /> <?php echo $lang['admin']['langs_back']; ?></a>	<br />
<?php
}

if (preg_match('#inc\.php$#', $_GET['loc'])) {
?>
<p class="copyText"><?php echo $lang['admin']['langs_macro_notice'];?></p>
<form action="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc']; ?>" method="post" enctype="multipart/form-data" />
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td class="tdTitle"><?php echo $lang['admin']['langs_key'];?></td>
    <td class="tdTitle"><?php echo $lang['admin']['langs_def_cur_val'];?></td>
	<td width="100" align="center" class="tdTitle"><?php echo $lang['admin']['langs_action'];?></td>
  </tr>
<?php

	## GET DEFAULT
	$langBully = true;
	require 'language'.str_replace('/', CC_DS, $_GET['loc']);
	$pathParts = explode("/",$_GET['loc']);
	
	include 'language'.CC_DS.$pathParts[1].CC_DS.'config.php';
	$formDefaultNat = formArray($bully);
	
	## GET CUSTOM
	$customArrayNat = $db->select("SELECT langArray FROM ".$glob['dbprefix']."ImeiUnlock_lang WHERE identifier = ".$db->mySQLSafe($_GET['loc']));
	
	if ($customArrayNat == true && !empty($customArrayNat)) {
		## add slashes to single quotes only
		$unserialized	= $customArrayNat[0]['langArray'];
		$formCustomNat		= formArray(unserialize($unserialized));
		if (empty($formCustomNat) || !is_array($formCustomNat)) {
			$formCustomNat = $formDefaultNat;
		}
	} else {
		$formCustomNat = $formDefaultNat;
	}
	
	// convert array types
	foreach($formDefaultNat as $key => $value) {
		
		$formDefault[$value['key']]['flatvalue'] = $value['flatvalue'];
		$formDefault[$value['key']]['flatkey'] = $value['flatkey'];
	}
	foreach($formCustomNat as $key => $value) {
		
		$formCustom[$value['key']]['flatvalue'] = $value['flatvalue'];
		$formCustom[$value['key']]['flatkey'] = $value['flatkey'];
	} 
	unset($customArrayNat,$formDefaultNat);
	
	if (is_array($formDefault)) {
		
		$hiddenVars = "";
		$i = 0;
		
		foreach ($formDefault as $key => $value) {
			
			if (!isset($formCustom[$key])) {
    			$formCustom[$key] = $value;
			}
		
			//if (isset($formCustom[$key])) {
			
				$cellColorSame = cellColor($i);
				
				$formDefault[$key]['flatvalue']	= html_entity_decode_utf8(stripslashes($formDefault[$key]['flatvalue']), ENT_COMPAT, $charsetIso);
				if (empty($formCustom[$key]['flatvalue'])) {
					$formCustom[$key]['flatvalue']	=  $formDefault[$key]['flatvalue'];
					$formCustom[$key]['flatkey']		=  $formDefault[$key]['flatkey'];
				} else {
					$formCustom[$key]['flatvalue']	=  html_entity_decode_utf8(stripslashes($formCustom[$key]['flatvalue']), ENT_COMPAT, $charsetIso);
				}
				
			//	print_r(substr_compare($formCustom[$i]['flatvalue'], $formDefault[$i]['flatvalue'], 0)); 
				
				if (md5(str_replace(array("\r","\n"), '', $formCustom[$key]['flatvalue'])) !== md5(str_replace(array("\r","\n"), '', $formDefault[$key]['flatvalue']))) {
					$cellColor = "tdModified";
					$link = "<div id='revertLink_".$i."'><a href=\"javascript:;\" onclick=\"revert(".$i.",'".$cellColorSame."');\" class=\"txtLink\">".$lang['admin']['langs_revert']."</a></div>";
					$missMatch = true;
				} else {
					$cellColor = "";
					$cellColor = $cellColorSame;
					$link = "<div id='revertLink_".$i."' style='display:none;'><a href=\"javascript:;\" onclick=\"revert(".$i.",'".$cellColorSame."');\" class=\"txtLink\">".$lang['admin']['langs_revert']."</a></div>";
				}
				
			?>
			  <tr id="tr_<?php echo $i;?>">
				<td class="<?php echo $cellColor; ?>"><span class="tdText"><?php echo $key; ?></span></td>
				<td class="<?php echo $cellColor; ?>">
				
				<?php
				if (strstr($formDefault[$key]['flatvalue'], "\n")) {
				?>
				<textarea name="default<?php echo $formDefault[$key]['flatkey']; ?>" id="default_<?php echo $i; ?>" cols="45" rows="5" class="langDisabled" disabled="disabled"><?php echo $formDefault[$key]['flatvalue']; ?></textarea>
				<?php
				} else {
				?>
				<input name="default<?php echo $formDefault[$key]['flatkey']; ?>" id="default_<?php echo $i; ?>" type="text" value="<?php echo htmlspecialchars($formDefault[$key]['flatvalue']); ?>" size="35" class="langDisabled" disabled="disabled" />
				<?php } ?><br />
				
				<?php
				if (strstr($formDefault[$key]['flatvalue'], "\n")) {
				?>
				<textarea name="custom<?php echo $formCustom[$key]['flatkey']; ?>" id="custom_<?php echo $i; ?>" cols="45" rows="5" class="textbox" onchange="compareInputbox(<?php echo $i; ?>);"><?php echo str_replace("\"","&quot;",$formCustom[$key]['flatvalue']); ?></textarea>
				<?php
				} else {
				?>
				<input name="custom<?php echo $formCustom[$key]['flatkey']; ?>" id="custom_<?php echo $i; ?>" type="text" value="<?php echo htmlspecialchars($formCustom[$key]['flatvalue']) ?>" size="35" class="textbox" onchange="compareInputbox(<?php echo $i; ?>);" />
				<?php
				}
				?>
				</td>
				<td width="100" align="center" class="<?php echo $cellColor; ?>">
				<?php 
				echo $link;
				?>
				</td>
			  </tr>
			<?php
			//}
		$i++;
		}  	
	} else {
	?>
	<tr>
	  <td colspan="3" class="tdText"><?php echo $lang['admin']['langs_file_empty']; ?></td>
	</tr>
<?php
	}
?>
  <tr>
	  <td colspan="2" align="center" class="tdText">
	  <input type="hidden" name="identifier" value="<?php echo $_GET['loc']; ?>" />
	  <input type="submit" name="submit" class="submit" value="Modify Language File"  />	  </td>
      <td class="tdText" align="center">
	  <div id="revAllLink" <?php if(!isset($missMatch)) { echo "style='display:none;'";} ?>><img src="<?php echo $glob['adminFolder']; ?>/images/selectAll.gif" width="16" height="11" />
	  <a href="javascript:;" onclick="revertAll('<?php echo $i;?>');" class="txtLink"><?php echo $lang['admin']['langs_revert_all'];?></a></div>
	  </td>	
  </tr>
</table>
</form>
<?php
} else {

	$path = CC_ROOT_DIR.CC_DS."language".str_replace('/', CC_DS, $_GET['loc']);
	$path = substr($path, 0, strlen($path)-1);

	$dirArray = walkDir($path, false, 0, 0, true, $int = 0);
	
	
	if (is_array($dirArray)) {
		
		$n = 0;
		
		?>
		<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
		<tr>
		<td colspan="3" class="tdTitle"><?php echo $lang['admin']['langs_lang_file_folders'];?></td>
		</tr>
		<?php
		foreach ($dirArray as $file) {
		
			$fileParts = explode(CC_DS, $file);
			$fileName = $fileParts[count($fileParts)-1];
			
			if (is_dir($file)) {
				$n++;
				if(file_exists($file.CC_DS."flag.gif")) {
					$imgFolder = "language/".$fileName."/flag.gif";
				} else {
					$imgFolder = $glob['adminFolder']."/images/folder.gif";
				}
			?>
			
			<tr>
			<td width="22" class="<?php echo cellColor($n);?>">
	
			<a href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc'].$fileName;?>/" class="txtLink">
			<img src="<?php echo $imgFolder;?>" border="0" alt="<?php echo $_GET['loc'].$fileName;?>" title="<?php echo $_GET['loc'].$fileName;?>" /></a></td>
			<td class="<?php echo cellColor($n);?>">
				<?php if($_GET['loc']=="/"){ ?>
				<strong><a href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc'].$fileName;?>/" class="txtLink"><?php echo $fileName;?></a></strong>
				<?php } else { ?>
				<strong><a href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc'].$fileName;?>/" class="txtLink"><?php echo $lang['admin'][$fileName];?></a></strong><br />
				<span class="tdText"><?php echo $lang['admin']["desc_".$fileName];?></span>
				<?php } ?>
			</td>
			</tr>
			<?php
			} else if (strstr($fileName, "inc.php") && is_file($file) && $fileName!=="home.inc.php") {
				$n++;
			?>
			<tr>
			<td width="22" class="<?php echo cellColor($n);?>">
			<a href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc'].$fileName;?>" class="txtLink">
			<img src="<?php echo $glob['adminFolder']; ?>/images/file.gif" width="20" height="22" border="0" alt="<?php echo $_GET['loc'].$fileName;?>" title="<?php echo $_GET['loc'].$fileName;?>" /></a> </td>
			<td class="<?php echo cellColor($n);?>"><strong><a href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&amp;loc=<?php echo $_GET['loc'].$fileName;?>" class="txtLink"><?php echo $lang['admin'][str_replace(".inc.php","",$fileName)];?></a></strong><br />
			<span class="tdText"><?php echo $lang['admin']["desc_".str_replace(".inc.php","",$fileName)];?></span><br />
			</td>
			</tr>
			<?php
			}
		
		}
		?>
		</table>
		<?php
	}
}
?>