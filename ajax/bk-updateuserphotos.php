<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
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
			$html .= '<li><div><i id="'.$allimages[$i]['id'].'">X</i><img id="userphoto-'.$allimages[$i]['id'].'" src="'.$icnSrc.'" ondragstart="drag(event)" class="dragable-image" source="'.$icnSrc.'">
</div></li>';
		}
	}
	$html .= '</ul>';
	echo '1::'.$html;
}
		
}
$db->close();
?>
