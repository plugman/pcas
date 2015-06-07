<?php
/*
+--------------------------------------------------------------------------
|	extraImgs.inc.php
|   ========================================
|	Add/Edit/Delete Unlimited Extra Product Images	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

require("classes".CC_DS."gd".CC_DS."gd.inc.php");
require("classes".CC_DS."watermark".CC_DS."Thumbnail.class.php");
$lang		= getLang("admin".CC_DS."admin_products.inc.php");
if (isset($_POST['saveOrder']) && !empty($_POST['priority'])) {
	## Handler for drag/drop reordering
	foreach ($_POST['priority'] as $index => $id) {
		$sql = sprintf("UPDATE %sImeiUnlock_img_idx SET priority = '%d' WHERE id = '%d' LIMIT 1;", $glob['dbprefix'], $index+1, $id);
		$db->misc($sql);
	}
}
if(isset($_GET['mode'])&& $_GET['mode']=="update"){
	$tempimages = $db->select("SELECT * from tbl_tmpimg_idx where productId = ". $db->mySQLSafe($_GET['productId']));
	/*echo "<pre>"; print_r($tempimages);die();*/
	if(!empty($tempimages)){
		$counttemp = count($tempimages);
		for($i=0;$i<$counttemp;$i++){
			#echo "<pre>"; print_r($tempimages); 
			$tempfile			= CC_ROOT_DIR.CC_DS."uploads/tempfiles/".$tempimages[$i]['image'];
			$path_parts2 		= pathinfo($tempfile);
			#print_r($path_parts2);
			$ext 		 		= ".".$path_parts2['extension'];
			#echo "<pre>".$imageName 			= $tempimages[$i]['productId']."_".$path_parts2['filename'].$ext; 
			$imageName 			= $tempimages[$i]['productId']."_".$path_parts2['filename'].$ext;

			$MainImage			= filePathProdMainImage($imageName,"root"); // main image path 
		 	$rootMasterFile 	= imgPath($imageName,'',$path="root");
			$rootThumbFile 		= imgPath($imageName,'thumb',$path="root");
			$rootTinyFile 		= imgPath($imageName,"tiny",$path="root");
			if(file_exists($tempfile)){ 
				#echo "Max Image Size:".$config['gdmaxImgSize'].'<br>'."Thumb Image Size: ".$config['gdthumbSize']; die();
				// Main Image 
				copy($tempfile,$MainImage);
				// END Main Image 
				
				// Thumb Image
				$thumbFile = new gd($tempfile);
				$thumbFile->quality=100;
				$thumbFile->size_auto($config['gdthumbSize']);
				$thumbFile->save($rootThumbFile);
				 // End Thumb Image
				 
				 // Small Image
				$smallFile = new gd($tempfile);
				$smallFile->quality=100;
				$smallFile->size_auto($config['gdmaxImgSize']);
				$smallFile->save($rootMasterFile);
			
				
				# Insert Record 
				$imgindx['img'] 		= $db->mySQLSafe($imageName);
				$imgindx['productId']	= $db->mySQLSafe($tempimages[$i]['productId']);
				$insertimage			= $db->insert("ImeiUnlock_img_idx", $imgindx);			
				unset($imgindx);
				
				# Unlink Temp Image
				@unlink($tempfile);
			}
			$whereidx = "id = ".$db->mySQLSafe($tempimages[$i]['id']);
			$deleteIdx = $db->delete("tbl_tmpimg_idx", $whereidx);
			unset($whereidx);
		}
	}	
}
else if(isset($_GET['remove'])&& $_GET['remove']>0)
{
	$idx = $db->select(sprintf("SELECT * FROM %sImeiUnlock_img_idx WHERE id = %d limit 0,1;", $glob['dbprefix'], $_GET['remove']));
	$MainImage			= filePathProdMainImage($idx[0]['img'],"root"); // main image path 
	$rootMasterFile 	= imgPath($idx[0]['img'],'',$path="root");
	$rootThumbFile 		= imgPath($idx[0]['img'],'thumb',$path="root");
	$rootTinyFile 		= imgPath($idx[0]['img'],'tiny',$path="root");
	if(file_exists($MainImage))
	{
		@unlink($MainImage);
	}
	if(file_exists($rootThumbFile))
	{
		@unlink($rootThumbFile);
	}
	if(file_exists($rootMasterFile))
	{
		@unlink($rootMasterFile);
	}
	if(file_exists($rootTinyFile))
	{
		@unlink($rootTinyFile);
	}
	
	$delete = sprintf("DELETE FROM %sImeiUnlock_img_idx WHERE id = %s", $glob['dbprefix'], $_GET['remove']);
	$db->misc($delete);
	unset($query);
	$msg = "Image remove successfully.";
}


/*$query		= sprintf("SELECT image FROM %sCubeCart_inventory WHERE productId = %d LIMIT 1;", $glob['dbprefix'], $_GET['productId']);
$product	= $db->select($query);*/


## Get the main image from the inventory table
$query		= sprintf("SELECT * FROM %sImeiUnlock_img_idx WHERE productId = %d ORDER BY priority ASC;", $glob['dbprefix'], $_GET['productId']);
$imageidx	= $db->select($query);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<html>
<head>
<title><?php echo $lang['admin']['products_image_management'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $glob['adminFolder']; ?>/styles/style.css">
<link href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/styles/uploadify.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/dragdrop.js"></script>
 <script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/prototype.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/scriptaculous.js"></script>
</head>
<body>
<div style="float:right;"><a <?php if(permission("products","write")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/extraImgs&amp;productId=<?=$_REQUEST['productId']?>&amp;mode=new" class="txtLink" <?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new'];?></a></div>
<p class="pageTitle"><?php echo $lang['admin']['products_manage_images'];?> </p>
<?php
	
	if(isset($msg)) echo "<p style='color:red;' >".msg($msg)."</p>";	
	//echo '<p class="copyText">'.$pagination.'</p>';
?>
<?php
	if(isset($_GET['mode'])&& $_GET['mode']=="new")
	{
	?>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/uploadify/jquery-1.3.2.min.js"></script> 
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/uploadify/swfobject.js"></script> 

<script type="text/javascript" src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<table border="0" width="100%" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td class="tdTitle" colspan="2">Upload Multiple <?php echo $lang['admin']['products_image'];?></td>
  </tr>
  <tr id="TRScreenshots">
    <td width="24%" class="copyText" valign="top"><strong>Relevant Image Files:</strong></td>
    <td width="76%" class="copyText" valign="bottom"><div id="fileQueue" style="min-height:100px; background-color:#CCC;"></div>
      <input type="file" name="uploadifyScreenshots" id="uploadifyScreenshots" />
      <p><a href="javascript:jQuery('#uploadifyScreenshots').uploadifyUpload();">Upload Files</a>&nbsp;<a href="javascript:jQuery('#uploadifyScreenshots').uploadifyClearQueue()">Cancel</a></p>
      <script type="text/javascript">
					$.noConflict();
   					jQuery(document).ready(function(){
					jQuery("#uploadifyScreenshots").uploadify({
					'uploader'       : '<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/uploadify/uploadify.swf',
					'script'         : 'upload.php',
					'cancelImg'      : '<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/uploadify/cancel.png',
					'folder'         : 'uploads/tempfiles',
					'queueID'        : 'fileQueue',
					'auto'           : false,
					'scriptData'     : {'prdid': '<?=$_GET['productId']?>'},
					'fileExt'        : '*.GIF;*.gif;*.JPG;*.jpg;*.JPEG;*.jpeg;*.PNG;*.png',
					'fileDesc'       : 'Only gif, jpg, png allowed',
					'multi'          : true
					});
					});
				</script></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td  class="copyText"><a class="submit" href="<?php echo $glob['adminFile']; ?>?_g=products/extraImgs&amp;productId=<?=$_REQUEST['productId']?>&mode=update" style="text-decoration:none; padding:2px 2px 2px 2px"> Save Record </a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td  class="copyText">&nbsp;</td>
  </tr>
</table>
<?php
	}else{
	?>
 

<form method="post" id="reorderimg" enctype="multipart/form-data" style="margin-top:20px;">
<div id="columnHeaders" class="tdTitle">
   <span class="actions" ><?php echo $lang['admin']['products_image'];?></span>
    <span class="actions" style="float:right; width:150px;"><?php echo $lang['admin']['products_action'];?></span>
</div>
  <?php 
	  	if(!empty($imageidx))
		{
			$count = count($imageidx);
			
			for($i=0;$i<$count;$i++)
			{
			$imageidx[$i]['img'];
			$thumbRoot = imgPath($imageidx[$i]['img'],"thumb",'root'); 
			$thumbImg = imgPath($imageidx[$i]['img'],"thumb",'rel');
			$cellColor	= cellColor($i);
			//echo $thumbImg;
	  ?>
 <div id="product_<?php echo $imageidx[$i]['id']; ?>" class="imgrow <?php echo $cellColor; ?> tdText">
 <input type="hidden" name="priority[]" value="<?php echo $imageidx[$i]['id']; ?>" />
    <span class="directory"><?php if (file_exists($thumbRoot)) { ?>
      <img src="<?php echo $thumbImg; ?>" alt="" title="" /> <br />
      <?php
		} 
		?></span>
     <span class="directory" style="float:right; position: absolute;
    right: 60px;
    top: 50px;"><a href="<?php echo $glob['adminFile']; ?>?_g=products/extraImgs&amp;productId=<?php echo $_GET['productId']?>&amp;remove=<?php echo $imageidx[$i]['id']; ?>" class="txtLink"><?php echo $lang['admin_common']['remove'];?></a></span>
</div>
 
  <?php 
		}
		?>
        <p><input type="submit" class="submit" name="saveOrder" value="Save Order" /></p>
</form>
<script type="text/javascript">
	Sortable.create('reorderimg', {ghosting:true,constraint:false,tag:'div',only:'imgrow'});
</script>
        <?php
	}
	else
	{
	?>
  <div class="tdText">No Relevant Image Available</div>
  <?php
    }
	?>
<?php 
	}
	?>
<p align="center"><a href="javascript:window.close();" class="txtLink"><?php echo $lang['admin']['products_close_window'];?></a></p>
</body>
</html>