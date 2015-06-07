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
require_once ("../includes".CC_DS."currencyVars.inc.php");
if(isset($_POST['modelid']) && $_POST['modelid'] >0){
	$result = $db->select("SELECT image,price,name,parent,imagebg,width,height,icon FROM ".$glob['dbprefix']."ImeiUnlock_case_models  WHERE id = ".$db->mySQLSafe($_POST['modelid']));
	if($result){
		if($result[0]['image'])
		$imgSrc = imgPath($result[0]['image'],'',$path="pngimage");
		if($result[0]['imagebg'] != ''){
		$imgSrc2 = imgPath($result[0]['imagebg'],'',$path="bgimage");
		}else{
			$imgSrc2 = 1;
		}
		if($result[0]['parent'] > 0){
			$result2 = $db->select("SELECT name FROM ".$glob['dbprefix']."ImeiUnlock_case_models  WHERE id = ".$db->mySQLSafe($result[0]['parent']));
			$result[0]['name'] = $result2[0]['name'].' '.$result[0]['name'];
		}
		$html .= $imgSrc.'::'.priceFormat($result[0]['price']).'::'.$result[0]['name'].'::'; 
		$layouts = $db->select("SELECT id,layouthtml,icon FROM ".$glob['dbprefix']."ImeiUnlock_case_layouts  WHERE model_id = ".$db->mySQLSafe($_POST['modelid']));
		if($layouts){
			$html .= '<ul>';
			for($i=0;$i<count($layouts);$i++){
				$imgSrc = imgPath($layouts[$i]['icon'],'',$path="layout");
				$html .= '<li><img id="'.$layouts[$i]['id'].'" alt="'.$layouts[$i]['tittle'].'" src="'.$imgSrc.'"></li>';
			}
			$html .= '</ul>::'.$layouts[0]['layouthtml'];
		}else{
			$html .= '::';
		}
		if($result[0]['icon']){
$imgSrc3 = imgPath($result[0]['icon'],'',$path="smallicon");
}else{
	$imgSrc3 = 'skins/Classic/styleImages/case1.jpg';
}

		$html .= '::<li class="active"><a href="#" id="model-'.$_POST['modelid'].'" class="casetype">
                        <img alt="" src="'.$imgSrc3.'"   /><br />
                        Slim fit Case
                    </a></li>';
	if($result[0]['parent'] == 0){	
	$casetype = $db->select("SELECT icon,name,id FROM ".$glob['dbprefix']."ImeiUnlock_case_models  WHERE parent = ".$db->mySQLSafe($_POST['modelid']));
	if($casetype){
		for($i=0;$i<count($casetype);$i++){
		if($casetype[$i]['icon'])
	 	$imgSrc = imgPath($casetype[$i]['icon'],'',$path="smallicon");
		$html .= '<li><a href="#" id="model-'.$casetype[$i]['id'].'" class="casetype">
                        <img src="'.$imgSrc.'"   /><br />
                       '.$casetype[$i]['name'].'
                    </a></li>';
		}
		
	}
	
	$html .= '::1::'.$imgSrc2;
			}else{
				if($imgSrc2){
					$html .= '::0::'.$imgSrc2;
				}
					else{
						$html .= '::0::1';
					}
			}
	$width = $result[0]['width']*3.94*2;
	$height = $result[0]['height']*3.94*2;
		echo '1::'.$html.'::'.$width.'::'.$height;
	}
	
}
$db->close();
?>