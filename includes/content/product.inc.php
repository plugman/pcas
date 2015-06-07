<?php
/*
+--------------------------------------------------------------------------
|	product.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."product.inc.php");


$product = new XTemplate ("content".CC_DS."product.tpl");

$product->assign("LANG_product_TITLE",$lang['product']['product']);

$design = $db->select("SELECT I.*, M.price FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success I INNER JOIN ".$glob['dbprefix']."ImeiUnlock_case_models M ON I.modelid = M.id WHERE  I.id=".$db->mySQLsafe($_GET['productId'])." ORDER BY id ASC");
if($design){
	 $icnSrc = imgPath($design[0]['image'],'',$path="userdesign" , $design[0]['session_id']);
			$product->assign("SAVED_IMAGE_SRC", $icnSrc);
			$product->assign("SAVED_IMAGES_ID", $design[0]['id']);
			$product->assign("SAVED_case_ID", $design[0]['modelid']);
			$product->assign("SAVED_IMAGES_name",  $design[0]['design_name']);
			$product->assign("SAVED_IMAGES_PRICE",  priceFormat($design[0]['price']));
			if($cc_session->ccUserData['customer_id'] > 0){
$OR =  "OR customerId = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);
}
$alldesign = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success  WHERE  (session_id = ".$db->mySQLsafe($cc_session->ccUserData['sessId']). $OR . ") AND id !=".$db->mySQLsafe($_GET['productId'])."ORDER BY id ASC");
if($alldesign){
for($i=0;$i<count($alldesign);$i++){
		 $icnSrc = imgPath($alldesign[$i]['image'],'',$path="userdesign" , $alldesign[$i]['session_id']);
			$product->assign("SAVED_IMAGE_SRC2", $icnSrc);
			$product->assign("SAVED_IMAGES_ID2", $alldesign[$i]['id']);
			$product->assign("DESIGN_NAME2", $alldesign[$i]['design_name']);
			$name = str_replace(' ' , '' , $alldesign[$i]['design_name']);
			$product->assign("SAVED_IMAGES_names2", str_replace('#' , '' , $name));
			$product->assign("USER_NAME2", $cc_session->ccUserData['firstName'].' '.$cc_session->ccUserData['lastName']);
			if($alldesign[$i]['fav'] == 1){
			$product->assign("ADD_TO_FAV", 'Favorite');
			$product->assign("FAV", 'favorite');
			}else{
				$product->assign("FAV", '');
				$product->assign("ADD_TO_FAV", 'Add to favorite');
			}
			$product->parse("product.design_true.all_userimages_saved");
	}
	$product->parse("product.design_true");
}
}

$product->parse("product");
$page_content = $product->text("product");
?>