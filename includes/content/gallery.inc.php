<?php
/*
+--------------------------------------------------------------------------
|	Gallery.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."gallery.inc.php");
if($cc_session->ccUserData['customer_id'] == 0){
	httpredir("index.php?_a=login&redir=step4");
}

$gallery = new XTemplate ("content".CC_DS."gallery.tpl");
if($cc_session->ccUserData['customer_id'] != 0){
	
	$gallery->assign("USER_NAME", $cc_session->ccUserData['firstName'].' '.$cc_session->ccUserData['lastName']);
	if($cc_session->ccUserData['profileimg']){
		 $icnSrc = imgPath($cc_session->ccUserData['profileimg'],'',$path="profimg" , '');
		$gallery->assign("USER_IMAGE", $icnSrc);
		}else{
			$gallery->assign("USER_IMAGE", "skins/". SKIN_FOLDER . "/styleImages/noimg.jpg");
		}
		if($cc_session->ccUserData['cover_photo']){
		$icnSrc = imgPath($cc_session->ccUserData['cover_photo'],'',$path="profimg" , '');
		$gallery->assign("USER_COVER", $icnSrc);
		}else{
			$gallery->assign("USER_COVER", "");
		}
		$gallery->assign("USER_FOL", $cc_session->ccUserData['customer_id']);
			$gallery->parse("gallery.session_true.customer_true");
}

if($cc_session->ccUserData['customer_id'] > 0){
$OR =  "OR customerId = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);
}
$allsavedimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success WHERE session_id = ".$db->mySQLsafe($cc_session->ccUserData['sessId']). $OR . " ORDER BY id ASC");
if($allsavedimages){
	for($i=0;$i<count($allsavedimages);$i++){
		 $icnSrc = imgPath($allsavedimages[$i]['image'],'',$path="userdesign" , $allsavedimages[$i]['session_id']);
			$gallery->assign("SAVED_IMAGE_SRC", $icnSrc);
			$gallery->assign("SAVED_IMAGES_ID", $allsavedimages[$i]['id']);
			$gallery->assign("DESIGN_NAME", $allsavedimages[$i]['design_name']);
			$name = str_replace(' ' , '' , $allsavedimages[$i]['design_name']);
			$gallery->assign("SAVED_IMAGES_name", str_replace('#' , '' , $name));
			if($allsavedimages[$i]['fav'] == 1){
			$gallery->assign("ADD_TO_FAV", 'Favorite');
			$gallery->assign("FAV", 'favorite');
			}else{
				$gallery->assign("FAV", '');
				$gallery->assign("ADD_TO_FAV", 'Add to favorite');
			}
			$gallery->parse("gallery.session_true.img_true.all_userimages_saved");
			
			if($allsavedimages[$i]['fav'] == 1){
			$gallery->parse("gallery.session_true.img_truef.all_userimages_savedf");
			}
	}
	$gallery->parse("gallery.session_true.img_truef");
	$gallery->parse("gallery.session_true.img_true");
}else{
	$gallery->parse("gallery.session_true.img_false");
	$gallery->parse("gallery.session_true.img_falsef");
}
	$gallery->parse("gallery.session_true");
$gallery->parse("gallery");
$page_content = $gallery->text("gallery");
?>