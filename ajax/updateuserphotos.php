<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require("../classes".CC_DS."watermark".CC_DS."Thumbnail.class.php");
require("../classes".CC_DS."gd".CC_DS."gd.inc.php");
$db = new db();
if(isset($_POST['userfolder']) && $_POST['userfolder'] !=''){
	$customerid = $db->select("SELECT customer_id FROM ".$glob['dbprefix']."ImeiUnlock_sessions WHERE sessId = ".$db->mySQLsafe($_POST['userfolder']));
if($customerid[0]['customer_id'] > 0){
 $OR =  " OR customerId = ".$db->mySQLsafe($customerid[0]['customer_id']);
}
	$allimages = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_user_images WHERE session_id = ".$db->mySQLsafe($_POST['userfolder']). $OR . "  ORDER BY id ASC");
	if($allimages){
		$html .= '<ul class="social-pics">';
	for($i=0;$i<count($allimages);$i++){
		if($allimages[$i]['image']){
			
			$icnSrc = imgPath($allimages[$i]['image'],'',$path="userimage", $_POST['userfolder']);
			$icnSrcroot = imgPath($allimages[$i]['image'],'',$path="userimageroot", $_POST['userfolder']);
			// Tiny Image				
				$tiny =new Thumbnail($icnSrcroot);		// Contructor and set source image file
				$tiny->quality=70;						// [OPTIONAL] default 75 , only for JPG format
				$tiny->size_auto(500);	// [OPTIONAL] set the biggest width or height for thumbnail				
				$tiny->process();							// generate image
				$tiny->save($icnSrcroot);		
			$html .= '<li  class="column4"><div><i id="'.$allimages[$i]['id'].'">X</i><img id="userphoto-'.$allimages[$i]['id'].'" src="'.$icnSrc.'" ondragstart="drag(event)" class="dragable-image" source="'.$icnSrc.'"><div class="spinner-wrap absolute"><div class="spiner hide"><div class="circle absolute f-height f-width hide"></div><div class="circle absolute f-height f-width"></div></div></div>
</div></li>';
		}
	}
	$html .= '</ul>';
	echo '1::'.$html;
}
		
}
$db->close();
?>
