<?php
/*
+--------------------------------------------------------------------------
|	resize.inc.php
|   ========================================
|	Resize Master Image
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_filemanager.inc.php");

$imagePath = ($glob['rootRel'] != CC_DS) ? str_replace($glob['rootRel'], '', $_GET['file']) : $_GET['file'];
$imagePath = CC_ROOT_DIR.CC_DS.str_replace('/', CC_DS, $imagePath);

if (isset($_POST['method'])) {
	require 'classes'.CC_DS.'gd'.CC_DS.'gd.inc.php';
	
	$img = new gd($imagePath);
	
	if ($_POST['method']=="width") {
		$img->size_width($_POST['x']);
		
	} else if ($_POST['method']=="height") {
		$img->size_height($_POST['y']);
		
	} else if ($_POST['method']=="exact") {
		$img->size_custom($_POST['x'],$_POST['y']);
	}
	$img->save($imagePath);
	httpredir($glob['adminFile']."?_g=filemanager/resize&file=".urlencode($_GET['file'])."&x=".$size[0]."&y=".$size[1]);
} 

$size = @getimagesize($imagePath);

$pageWidth = $size[0]+20;
$pageHeight = $size[1]+180;

if($pageWidth<500){ $pageWidth=500; }
if($pageHeight<400){ $pageHeight=400; }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $lang['admin']['filemanager_prev_file'];?></title>
<link href="<?php echo $glob['rootRel'].$glob['adminFolder']; ?>/styles/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo $glob['rootRel']; ?>js/jslibrary.js"></script>
</head>

<body class="greyBg" <?php echo "onload=\"resizeOuterTo(".$pageWidth.",".$pageHeight.");\""; ?>>

<?php if(isset($_GET['file'])){ ?>
<div class="imgPreview" align="center" style="text-align: center; margin-bottom: 10px; width: <?php echo $size[0]; ?>; height: <?php echo $size[1]; ?>">
  <a href="javascript:window.close();">
	<img src="<?php echo $glob['rootRel'].$glob['adminFile']; ?>?_g=filemanager/imageNoCache&amp;file=<?php echo $_GET['file']; ?>" alt="<?php echo $lang['admin']['filemanager_close_window'];?>" title="<?php echo $lang['admin']['filemanager_close_window'];?>" border="0" onclick="window.close();" />
  </a>
</div>

<form action="<?php echo $glob['adminFile']; ?>?_g=filemanager/resize&amp;file=<?php echo $_GET['file']; ?>" enctype="multipart/form-data" method="post" name="resize">
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
	<td class="tdTitle" colspan="4"><?php echo $lang['admin']['filemanager_irreversible'];?></td>
  </tr>
  <tr>
	<td class="tdText"><strong><?php echo $lang['admin']['filemanager_method'];?></strong></td>
	<td class="tdText">
	<?php echo $lang['admin']['filemanager_auto_width'];?> <input name="method" type="radio" value="width" onclick="editVal('y','');editVal('x','<?php echo $size[0]; ?>');" /></td>
	<td class="tdText"><?php echo $lang['admin']['filemanager_auto_height'];?>
  <input name="method" type="radio" value="height" onclick="editVal('x','');editVal('y','<?php echo $size[1]; ?>');" /></td>
	<td class="tdText"><?php echo $lang['admin']['filemanager_exact'];?>
  <input name="method" type="radio" value="exact" onclick="editVal('x','<?php echo $size[0]; ?>');editVal('y','<?php echo $size[1]; ?>');" checked="checked" /></td>
  </tr>
  <tr>
	<td align="right" class="tdText">&nbsp;</td>
	<td align="left" class="tdText"><?php echo $lang['admin']['filemanager_width'];?>
	<input name="x" id="x" type="text" size="3" maxlength="3" value="<?php echo $size[0]; ?>" style="text-align: center;" /> px</td>
	<td align="left" class="tdText"><?php echo $lang['admin']['filemanager_height'];?> 
    <input name="y" id="y" type="text" size="3" maxlength="3" value="<?php echo $size[1]; ?>" style="text-align: center;" /> px</td>
	<td align="left" class="tdText"><input name="submit" type="submit" class="submit" value="<?php echo $lang['admin']['filemanager_resize_now'];?>"/></td>
  </tr>
</table>
</form>

<p align="center"><a href="javascript:window.close();" class="txtLink"><?php echo $lang['admin']['filemanager_close_win'];?></a></p>


<?php } else { ?>
<span class="copyText"><?php echo $lang['admin']['filemanager_no_image_selected'];?></span>
<?php } ?>
</body>
</html>
