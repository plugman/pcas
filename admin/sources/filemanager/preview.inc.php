<?php
/*
+--------------------------------------------------------------------------
|	preview.inc.php
|   ========================================
|	Preview Image
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
$lang = getLang("admin".CC_DS."admin_filemanager.inc.php");

if (isset($_GET['file_id']) && is_numeric($_GET['file_id'])) {
	$sql	= sprintf("SELECT * FROM %sImeiUnlock_filemanager WHERE file_id = %d LIMIT 1", $glob['dbprefix'], $_GET['file_id']);
	$result	= $db->select($sql);
	
	if (file_exists($result[0]['filepath'])) {
		$size	= getimagesize($result[0]['filepath']);
		$show	= true;
		$file	= $result[0]['filepath'];
	}
} else {
	// deprecated functionality...
	$imagePath	= $GLOBALS['rootRel'] == "/" ? str_replace('/images', 'images', $_GET['file']) : str_replace($glob['rootRel'], '', $_GET['file']);
	$imagePath	= CC_ROOT_DIR.CC_DS.str_replace('/', CC_DS, $imagePath);
	$size		= getimagesize($imagePath);
	$file		= $_GET['file'];
}

$pageWidth	= $size[0]+12;
$pageHeight	= $size[1]+12;
$skipFooter	= true;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $lang['admin']['filemanager_prev_file'];?></title>
<link href="<?php echo $glob['adminFolder']; ?>/styles/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="greyBg" <?php echo 'onload="resizeOuterTo('.$pageWidth.','.$pageHeight.');"'; ?>>
<?php if (isset($_GET['file']) || isset($_GET['file_id']) && $show){ ?>
<div class="imgPreview" align="center" style="width:<?php echo $size[0];?>px; height:<?php echo $size[1];?>px;">
  <a href="javascript:window.close();">
	<img src="<?php echo sanitizeVar($file); ?>" alt="<?php echo $lang['admin']['filemanager_close_window'];?>" title="<?php echo $lang['admin']['filemanager_close_window'];?>" border="0" />
  </a>
</div>
<?php } else { ?>
<span class="copyText"><?php echo $lang['admin']['filemanager_no_image_selected'];?></span>
<?php } ?>
</body>
</html>