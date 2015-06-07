<?php
/*
+--------------------------------------------------------------------------
|	casecustomization.inc.php
|   ========================================
|	casecustomization page	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");
$lang = getLang("includes".CC_DS."content".CC_DS."casecustomization.inc.php");
$case = new XTemplate ("content".CC_DS."casecustomization.tpl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
$models = $db->select("SELECT M.id,M.name,price,device_id,M.image,M.imagebg,icon,width,height,ble_width,ble_height FROM ".$glob['dbprefix']."ImeiUnlock_case_models AS M INNER JOIN ".$glob['dbprefix']."ImeiUnlock_case_devices AS D ON M.device_id = D.id WHERE D.hide = '0' AND M.hide = '0' ORDER BY M.device_id,M.id ASC");
/*echo "<pre>";
print_r($models);*/
if($models){
	for($i=0;$i<count($models);$i++){
		$case->assign("MODEL_NAME", $models[$i]['name']);
		$case->assign("MODEL_ID", $models[$i]['id']);
		if($_GET['modelId'] > 0 && $models[$i]['id'] == $_GET['modelId']){
		$activemodel = $models[$i]['id'];
		$activemodelimg = $models[$i]['image'];
		$activemodelimg2 = $models[$i]['imagebg'];
		$activemodelcase = $models[$i]['icon'];
		$case->assign("IF_ACTIVE", 'class="active-model"');
	 	 $actmodelname =  $models[$i]['name'];
		 $price = priceFormat($models[$i]['price']);
		  $height = $models[$i]['height'];
		  $width = $models[$i]['width'];
		  $ble_height = $models[$i]['ble_height'];
		  $ble_width = $models[$i]['ble_width'];
		}else{
			if(!$activemodel){
			$activemodelimg = $models[0]['image'];
			$activemodelimg2 = $models[0]['imagebg'];
			$activemodelcase = $models[0]['icon'];
			$activemodel = $models[0]['id'];
			$actmodelname = $models[0]['name'];
			$price = priceFormat($models[0]['price']);
			$height = $models[0]['height'];
		  $width = $models[0]['width'];
		  $ble_height = $models[0]['ble_height'];
		  $ble_width = $models[0]['ble_width'];
			}
			$case->assign("IF_ACTIVE", $i == 0 && $_GET['modelId'] < 1  ? 'class="active-model"' : '');
		}
		$case->parse("casecustomization.all_models");
	}
}
$imgSrc = imgPath($activemodelimg,'',$path="pngimage");
$imgSrc2 = imgPath($activemodelimg2,'',$path="bgimage");
if($activemodelcase){
$imgSrc3 = imgPath($activemodelcase,'',$path="smallicon");
}else{
	$imgSrc3 = 'skins/Classic/styleImages/case1.jpg';
}
$layouts = $db->select("SELECT id,icon,layouthtml FROM ".$glob['dbprefix']."ImeiUnlock_case_layouts WHERE hide = '0' AND model_id = ".$db->mySQLsafe($activemodel)." ORDER BY id ASC");
if($layouts){
	for($i=0;$i<count($layouts);$i++){
		if($layouts[$i]['icon']){
			$icnSrc = imgPath($layouts[$i]['icon'],'',$path="layout");
			$case->assign("LAYOUT_SRC", $icnSrc);
			$case->assign("LAYOUT_ID", $layouts[$i]['id']);
			$case->assign("IF_LAYOUT", $i == 0 ? 'active-layout' : '');
			$case->parse("casecustomization.all_layouts");
		}
	}
}
$case->assign("SRC", $imgSrc);
$case->assign("SRC2", $imgSrc2);
$case->assign("ACT_MODEL_NAME", $actmodelname);
$case->assign("ACT_MODEL_PRICE", $price);
$case->assign("ACT_MODEL_WIDTH", $width*3.779527559*2);
$case->assign("ACT_MODEL_HEIGHT", $height*3.779527559*2);
$case->assign("ACT_MODEL_INER_HEIGHT", $height*3.779527559);
$case->assign("ACT_MODEL_INER_WIDTH", $width*3.779527559);
$case->assign("ACT_MODEL_BLEEDING_HEIGHT", $ble_height*3.779527559);
$case->assign("ACT_MODEL_BLEEDING_WIDTH", $ble_width*3.779527559);

$case->assign("LAYOUT_HTML", str_replace("&nbsp;" , '' , $layouts[0]['layouthtml']));
if($cc_session->ccUserData['customer_id'] > 0){
 $OR =  " OR customerId = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);
}
$allimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images WHERE session_id = ".$db->mySQLsafe($cc_session->ccUserData['sessId']). $OR . " ORDER BY id ASC");
if($allimages){
	for($i=0;$i<count($allimages);$i++){
		if($allimages[$i]['image']){
			 $icnSrc = imgPath($allimages[$i]['image'],'',$path="userimage" , $allimages[$i]['session_id']);
			$case->assign("IMAGE_SRC", $icnSrc);
			$case->assign("IMAGES_ID", $allimages[$i]['id']);
			$case->parse("casecustomization.all_userimages");
		}
	}
	
}
$stampimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_stamp_folders WHERE hide = '0' ORDER BY id ASC");
if($stampimages){
	
	for($i=0;$i<count($stampimages);$i++){
	
			 //$icnSrc = imgPath($stampimages[$i]['image'], false, 'rel');
			  $thumbRootPath	= imgPath($stampimages[$i]['image'], true, 'rel');
				$case->assign("VALUE_THUMB_SRC", $thumbRootPath);
			$case->assign("STMP_NAME", $stampimages[$i]['name']);
			$case->assign("STMP_ID", $stampimages[$i]['id']);
			$case->parse("casecustomization.all_stampimages");
		
	}
	
}
$case->assign("TYPE_ACT", $activemodel);
$case->assign("TYPE_CASE", $imgSrc3);
if($result[0]['imagebg'] != ''){
		$imgSrc2 = imgPath($result[0]['imagebg'],'',$path="bgimage");
		}else{
			$imgSrc2 = 1;
		}
$casetype = $db->select("SELECT id,name,icon,width,height,parent FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE hide = '0' AND parent = ".$db->mySQLsafe($activemodel)." ORDER BY id ASC");
if($casetype){
	
	for($i=0;$i<count($casetype);$i++){
	
			 //$icnSrc = imgPath($stampimages[$i]['image'], false, 'rel');
			  $thumbRootPath	= imgPath($casetype[$i]['icon'],'',$path="smallicon");
				$case->assign("VALUE_TYPE_SRC", $thumbRootPath);
			$case->assign("TYPE_NAME", $casetype[$i]['name']);
			
			$case->assign("TYPE_ID", $casetype[$i]['id']);
			$case->parse("casecustomization.all_casetype");
		
	}
	
}
$case->assign("USER_DIR", 'uploads/userdata/'.$cc_session->ccUserData['sessId']);
$case->assign("USER_FOL", $cc_session->ccUserData['sessId']);
#### Assign languae Text
$case->assign("case_desc", $lang['casecustomization']['case_desc']);
$case->assign("Shipping", $lang['casecustomization']['Shipping']);
$case->parse("casecustomization");
$page_content = $case->text("casecustomization");
?>