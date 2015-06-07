<?php
/*
+--------------------------------------------------------------------------
|	prodImages.php
|   ========================================
|	Popup image gallery for the product	(If Lightbox isn't selected)
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

// query database
$_GET['productId'] = sanitizeVar($_GET['productId']);

$results = $db->select("SELECT img FROM ".$glob['dbprefix']."ImeiUnlock_img_idx WHERE productId = ".$db->mySQLsafe($_GET['productId']));

$mainImage = $db->select("SELECT image FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = ".$db->mySQLsafe($_GET['productId']));

$prod_images = new XTemplate ("extra".CC_DS."prodImages.tpl");

$prod_images->assign("META_TITLE",$config['siteTitle'].$meta['siteTitle']);
$prod_images->assign("VAL_ISO",$charsetIso);

if ($results) {
	for ($i=0; $i<count($results); $i++) {
 		
		$imageRelPath = imgPath($results[$i]['img'], $thumb=0, $path="rel");
		$imageRootPath = imgPath($results[$i]['img'], $thumb=0, $path="root");
		
		$thumbRelPath = imgPath($results[$i]['img'], $thumb=1, $path="rel");
		$thumbRootPath = imgPath($results[$i]['img'], $thumb=1, $path="root");
		
		$prod_images->assign("VALUE_SRC",$imageRelPath);
		
		if (file_exists($thumbRootPath) && !empty($results[$i]['img'])) {
			$prod_images->assign("VALUE_THUMB_SRC", $thumbRelPath);
			$sizeThumb = getimagesize($thumbRootPath);
			$prod_images->assign("VALUE_THUMB_WIDTH", $sizeThumb[0]);
		} else {
			$prod_images->assign("VALUE_THUMB_SRC", $imageRelPath);
			$prod_images->assign("VALUE_THUMB_WIDTH", $config['gdthumbSize']);
		}
		$prod_images->assign("ALT_THUMB", $lang['front']['popup_thumb_alt']);
		$prod_images->parse("prod_images.thumbs");
	}
	
	## Original image
	$imgMasterRelPath = imgPath($mainImage[0]['image'], $thumb=0, $path="rel");
	$thumbMasterRootPath = imgPath($mainImage[0]['image'], $thumb=1, $path="root");
	$thumbMasterRelPath = imgPath($mainImage[0]['image'], $thumb=1, $path="rel");
	
	$prod_images->assign("VALUE_SRC",$imgMasterRelPath);
	
	
	if (file_exists($thumbMasterRootPath) && !empty($mainImage[0]['image'])) {
		$prod_images->assign("VALUE_THUMB_SRC", $thumbMasterRelPath);
		$sizeThumb = getimagesize($thumbMasterRootPath);
		$prod_images->assign("VALUE_THUMB_WIDTH", $sizeThumb[0]);
	} else {
		$prod_images->assign("VALUE_THUMB_SRC", $imgMasterRelPath);
		$prod_images->assign("VALUE_THUMB_WIDTH", $config['gdthumbSize']);
	}
	$prod_images->assign("ALT_THUMB", $lang['front']['popup_thumb_alt']);
	$prod_images->parse("prod_images.thumbs");
}

$prod_images->assign("VALUE_MASTER_SRC", $imgMasterRelPath);
$prod_images->assign("ALT_LARGE", $lang['front']['popup_large_alt']);

$prod_images->parse("prod_images");
$prod_images->out("prod_images");
?>