<?php
if(!empty($_POST['image']) && !empty($_POST['image2'])){
//session_start(); 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cart".CC_DS."shoppingCart.php");
require_once ("../classes".CC_DS."cart".CC_DS."order.php");
require_once ("../classes".CC_DS."session".CC_DS."cc_session.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");

$db = new db();
$cart = new cart();
$order	= new order();
$cc_session = new session();
$config = fetchdbconfig("config");
require("../classes".CC_DS."watermark".CC_DS."Thumbnail.class.php");
require("../classes".CC_DS."gd".CC_DS."gd.inc.php");
	
	$cart	= new cart();
	$basket	= $cart->cartContents($cc_session->ccUserData['basket']);
    $image = $_POST['image'];
	$image2 = $_POST['image2'];
	$filedir = CC_ROOT_DIR."/uploads/userdesigns";
    $name = time();
	$thumbname = time();
	 $name2 = 'origional'.$name;
	  $name3 = 'thumb'.$name;


    $image = str_replace('data:image/png;base64,', '', $image);
    $decoded = base64_decode($image);
	$image2 = str_replace('data:image/png;base64,', '', $image2);
    $decoded2 = base64_decode($image2);
$customerid = $db->select("SELECT customer_id FROM ".$glob['dbprefix']."ImeiUnlock_sessions WHERE sessId = ".$db->mySQLsafe($_POST['filedir']));
if($customerid[0]['customer_id'] > 0){
 $OR =  " OR customerId = ".$db->mySQLsafe($customerid[0]['customer_id']);
}
$allimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success WHERE session_id = ".$db->mySQLsafe($_POST['filedir']).  $OR . "  ORDER BY id ASC");
		if(file_put_contents($filedir . "/" . $name . ".png", $decoded, LOCK_EX)&& file_put_contents($filedir . "/" . $name2 . ".png", $decoded2, LOCK_EX)){
		
			// Tiny Image				
				$tiny =new Thumbnail($filedir . "/" . $name . ".png");		// Contructor and set source image file
				$tiny->quality=70;						// [OPTIONAL] default 75 , only for JPG format
				$tiny->size_auto(150);	// [OPTIONAL] set the biggest width or height for thumbnail				
				$tiny->process();							// generate image
				$tiny->save($filedir . "/" . $name3 . ".png");	
		if($allimages)
		$numberofcases = count($allimages)+1;
		else
		$numberofcases = 1;
				if(!$customerid[0]['customer_id'])
				$data['session_id']  	= $db->mySQLSafe($_POST['filedir']);
				$data['customerId']  	= $db->mySQLSafe($customerid[0]['customer_id']);
				$data['modelid']  		= $db->mySQLSafe($_POST['handsetid']);
				$data['design_name'] 	= $db->mySQLSafe("My Design # ".$numberofcases);
				$data['image']			= $db->mySQLSafe($name.".png");
				$status = $db->insert("ImeiUnlock_user_images_success", $data);	
				$designid = $db->insertid()	;	
	}

				$caseinfo = $db->select("SELECT name, price, devicecode FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE id=".$db->mySQLSafe($_POST['handsetid']));
				
				$designcart['case']['amount'] = $caseinfo[0]['price'];
				$designcart['case']['name'] = $caseinfo[0]['name'];
				$designcart['case']['devicecode'] = $caseinfo[0]['devicecode'];
				$designcart['case']['designname'] = 'My Design # '.$numberofcases;
				$designcart['case']['image'] = 'thumb'.$name.".png";
				$designcart['case']['designimg'] = $name.".png";
				$designcart['case']['caseid'] = $_POST['handsetid'];
				$designcart['case']['designimg'] = $name.".png";
				$cart->addCase($designcart['case']);
 $html =  "1::".imgPath($name . ".png",'',$path="userdesign", $_POST['filedir'])."::".$designid."::".$glob['storeURL'].'/product/'.str_replace(array(' ', '#', '\''), '', $data["design_name"]).'/product_'.$designid.'.html::'."My Design # ".$numberofcases.'::';
   echo $html;
   $db->close();
   }else{
	   die('how you come here');
   }
?>    