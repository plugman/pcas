<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require("../classes".CC_DS."watermark".CC_DS."Thumbnail.class.php");
require("../classes".CC_DS."gd".CC_DS."gd.inc.php");
$db = new db();
//echo $oldimg = $_POST['oldimg'];

if(isset($_POST['dataval']) && $_POST['dataval'] !=''){
$reciveddata = explode("||", $_POST['dataval']);
echo $reciveddata[1];
	$allimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = ".$db->mySQLsafe($reciveddata[0]).  "  ORDER BY customer_id ASC");
	if($allimages){
		
	
		if($allimages[0]['profileimg']){
			$icnSrc = imgPath($allimages[0]['profileimg'],'',$path="profimg", $_POST['userfolder']);
			$icnSrcroot = imgPath($allimages[0]['profileimg'],'',$path="profimgroot", $_POST['userfolder']);
			// Tiny Image				
				$tiny =new Thumbnail($icnSrcroot);		// Contructor and set source image file
				$tiny->quality=70;						// [OPTIONAL] default 75 , only for JPG format
				$tiny->size_auto(150);	// [OPTIONAL] set the biggest width or height for thumbnail				
				$tiny->process();							// generate image
				$tiny->save($icnSrcroot);		
		}
	
	 $oldimg =  imgPath($reciveddata[1],'',$path="profimgroot", $_POST['userfolder']);
	if(file_exists($oldimg))
		@unlink($oldimg);

	echo '::1::'.$icnSrc;
}
		
}
$db->close();
?>
