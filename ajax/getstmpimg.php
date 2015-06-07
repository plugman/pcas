<?php 
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../classes".CC_DS."session".CC_DS."cc_session.php");
$db = new db();
$cc_session = new session();
$config = fetchDbConfig("config");
if(isset($_POST['stmp']) && $_POST['stmp'] >0){
		$stampimages = $db->select("SELECT img,id FROM ".$glob['dbprefix']."ImeiUnlock_stamp_img  WHERE productId = ".$db->mySQLSafe($_POST['stmp']));
		if($stampimages){
	
	for($i=0;$i<count($stampimages);$i++){
	
			 $icnSrc = imgPath($stampimages[$i]['img'], false, 'rel');
			  $thumbRootPath	= imgPath($stampimages[$i]['img'], true, 'rel');
				$html .= '<li class="column4"><div><img id="stmp-'.$stampimages[$i]['id'].'" src="'.$thumbRootPath.'" ondragstart="drag(event)" class="dragable-image" source="'.$icnSrc.'"><div class="spinner-wrap absolute"><div class="spiner hide"><div class="circle absolute f-height f-width hide"></div><div class="circle absolute f-height f-width"></div></div></div>
</div></li>';
	
	}
		echo '1::'.$html;
	}
}
$db->close();
?>