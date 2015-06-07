<?php
/*
+--------------------------------------------------------------------------
|	logo.inc.php
|   ========================================
|	Manage Store Logo	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_settings.inc.php");
permission("settings", "read", true);

if (isset($_GET['revert'])) {
	@unlink(CC_ROOT_DIR."/images/logos/".$_GET['revert']);
	$msg .= '<p class="infoText">'.sprintf($lang['admin']['settings_logo_reverted'], $_GET['revert']).'</p>';
}

if (isset($_POST['submit'])) {
	if (is_array($_FILES)) {
		$mimeArray = array(
			'image/jpeg',
			'image/pjpeg',	# This mime type was magically created by the twats responsible for IE7
			'image/gif',
			'image/png',
			'image/x-png',
		);
		foreach ($_FILES as $key => $value) {
			if ($_FILES[$key]['size'] == 0 || $_FILES[$key]['error'] == UPLOAD_ERR_NO_FILE) {
			#	$msg .= "<p class='warnText'>No file for logo `".$key."` was selected.</p>";
			} else {
				if (in_array($_FILES[$key]['type'], $mimeArray) && $_FILES[$key]['error'] == UPLOAD_ERR_OK) {
					if (cc_is_writable(CC_ROOT_DIR.CC_DS.'images'.CC_DS.'logos')) {
						@move_uploaded_file($_FILES[$key]['tmp_name'], CC_ROOT_DIR.CC_DS.'images'.CC_DS.'logos'.CC_DS.$key);
						@chmod(CC_ROOT_DIR.CC_DS.'images'.CC_DS.'logos'.CC_DS.$key, 0775);
						$msg .= '<p class="infoText">'.sprintf($lang['admin']['settings_logo_changed'], $key).'</p>';
					}
				} else {
					$msg .= '<p class="warnText">'.sprintf($lang['admin']['settings_logo_invalid'], $key).'</p>';
				}
			}
		}
	}
}

require $glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php";
?>
<p class="pageTitle"><?php echo $lang['admin']['settings_logo_title'] ?></p>
<?php if (isset($msg)) echo msg($msg); ?>
<form action="<?php echo $glob['adminFile']; ?>?_g=settings/logo" method="post" enctype="multipart/form-data" target="_self">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['settings_logo_skin_name']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['settings_logo_default_skin']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['settings_logo_default_logo']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['settings_logo_current_logo']; ?></td>
	<td align="center" class="tdTitle"><?php echo $lang['admin']['settings_logo_action']; ?></td>
  </tr>
<?php
$skinList = listAddons('skins');
$i=0;

foreach ($skinList as $folder) {
	$i++;
	$cellColor = cellColor($i);
	if (!preg_match('#^\.#', $folder)) {
		$custom = CC_ROOT_DIR."/images/logos/".$folder;
		$default = CC_ROOT_DIR."/skins/".$folder."/styleImages/logo/default.gif";
		$defaultSize = getimagesize($default);
		?>
  <tr>
	<td class='<?php echo $cellColor ?> copyText'><strong><?php echo $folder ?></strong></td>
	<td class='<?php echo $cellColor ?>' align="center">
	<?php if ($folder ==$config['skinDir']) { ?>
	<img src="<?php echo $glob['adminFolder']; ?>/images/1.gif" width="10" height="10" alt="" title="" />
	<?php } else { ?>
	<img src="<?php echo $glob['adminFolder']; ?>/images/0.gif" width="10" height="10" alt="" title="" />
	<?php } ?>
	</td>
	<td align="center" class='<?php echo $cellColor ?> copyText'><img src='<?php echo $GLOBALS['rootRel'] ?>skins/<?php echo $folder ?>/styleImages/logo/default.gif' /><br /></td>
	<td align="center" class='<?php echo $cellColor ?> copyText'>
	<?php
	
	if (file_exists($custom)) {
		$defaultImg = 0;
		
		$imgType = getimagesize($custom);
		switch ($imgType[2]) {
			case 1;
				$mime = "gif";
				break;
			case 2;
				$mime = "jpeg";
				break;
			case 3;
				$mime = "png";
				break;
		}
		?>
		<img src='images/getLogo.php?skin=<?php echo $folder ?>' />
		<?php
	} else if (file_exists($default)) {
		$defaultImg = 1;
		?>
		<img src='<?php echo $GLOBALS['rootRel'] ?>skins/<?php echo $folder ?>/styleImages/logo/default.gif' />
		<?php
	} else {
		echo $lang['admin']['settings_logo_default_missing'];
	}
	?>
	</td>
	<td class='<?php echo $cellColor ?>'><input type='file' name='<?php echo $folder ?>' class='textbox' /><br />
	<?php if ($defaultImg == false) { ?>
	<a href="<?php echo $glob['adminFile']; ?>?_g=settings/logo&amp;revert=<?php echo $folder ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"><?php echo $lang['admin']['settings_logo_revert']; ?></a>
	<?php } ?>
	</td>
  </tr>
  <tr>
	<td colspan="2" class='<?php echo $cellColor ?> copyText'>&nbsp;</td>
	<td align="center" class='<?php echo $cellColor ?> copyText'><?php echo sprintf($lang['admin']['settings_logo_dimensions'],$defaultSize[0],$defaultSize[1]); ?></td>
	<td colspan="2" align="center" class='<?php echo $cellColor ?> copyText'> </td>
  </tr>
	<?php
	}
} 
?>
  <tr>
	<td colspan="4">&nbsp;</td>
	<td><input type="submit" name="submit" class="submit" value="<?php echo $lang['admin']['settngs_logo_upload'];?>" /></td>
  </tr>
</table>
</form>