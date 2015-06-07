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





$gallery = new XTemplate ("content".CC_DS."social.tpl");



$gallery->assign("LANG_GALLERY_TITLE",$lang['gallery']['gallery']);

if($cc_session->ccUserData['customer_id'] > 0){

$OR =  "OR customerId = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);

}

$allsavedimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success WHERE session_id = ".$db->mySQLsafe($cc_session->ccUserData['sessId']). $OR . " ORDER BY id ASC");

if($allsavedimages){

	for($i=0;$i<count($allsavedimages);$i++){

		 $icnSrc = imgPath($allsavedimages[$i]['image'],'',$path="userimage" , $allsavedimages[$i]['session_id']);

			$gallery->assign("SAVED_IMAGE_SRC", $icnSrc);

			$gallery->assign("SAVED_IMAGES_ID", $allsavedimages[$i]['id']);

			$name = str_replace(' ' , '' , $allsavedimages[$i]['design_name']);

			$gallery->assign("SAVED_IMAGES_name", str_replace('#' , '' , $name));

			$gallery->parse("gallery.all_userimages_saved");

	}

}
$gallery->assign("VAL_CUSTOMER",$cc_session->ccUserData['firstName'].' '.$cc_session->ccUserData['lastName']);


$gallery->parse("gallery");

$page_content = $gallery->text("gallery");

?>