<?php 
require_once ("../../ini.inc.php");
require_once ("../../includes".CC_DS."global.inc.php");
require_once ("../../includes".CC_DS."functions.inc.php");
require_once ("../../classes".CC_DS."db".CC_DS."db.php");
require_once ("../../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../../classes".CC_DS."session".CC_DS."cc_session.php");

$db = new db();
$cc_session = new session();
$config		= fetchdbconfig("config");
if(isset($_POST['catid']) && $_POST['catid'] >0){
	$result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = ".$db->mySQLSafe($_POST['catid']). " AND hide = '0' AND type = '2'  ORDER BY priority,cat_name ASC");
	if($result){
	$resultsForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '" . LANG_FOLDER . "'");
	}
	if($result){
		if($_POST['level'] == 1){
		$level= '2';
		$togle = 'devices';
		$html.= ' <h2 class="tit" onclick="togglethis(\'devices_1\');">Select your device.</h2>';
		}elseif($_POST['level'] == 2){
			$togle = 'model';
			$level = '3';
		$html.= ' <h2 class="tit" onclick="togglethis(\'model_1\');">Select your model.</h2>';
		}
		$html.= ' <div id="'.$togle.'_1" style="float:left;">';
		for($i=0;$i<count($result);$i++){
			if (is_array($resultsForeign)) {
			for ($k=0; $k<count($resultsForeign); $k++) {
				if ($resultsForeign[$k]['cat_id'] == $result[$i]['cat_id']) {
					$result[$i]['cat_name'] = $resultsForeign[$k]['cat_name'];
				}
			}
		}
			$thumbRoot		= imgPath($result[$i]['cat_image'], true, 'root');
			$thumbRootRel	= imgPath($result[$i]['cat_image'], true, 'rel');
			if (file_exists($thumbRoot)) {
			$img =  str_replace("&", "&amp;", $thumbRootRel);
			} else {
				$img =  $GLOBALS['rootRel']."skins/Classic/styleImages/thumb_nophoto.gif";
			}
			$html .= '<div class="detailbox55" onclick="loaddevices(\''.$result[$i]["cat_id"].'\',\''.$level.'\',\''."ch".$result[$i]["cat_id"].'\');">
          <div class="makeimg">
          <img src="'.$img.'" alt="'.$result[$i]['cat_name'].'"  />
 			<div class="make"  id="'."ch".$result[$i]["cat_id"].'" >
         <span class="maketitle" >'.$result[$i]['cat_name'].'</span>
        </div>
 </div>
        </div>';
		}
		$html.= '</div>';
		echo '1::'.$_POST['level'].'::'.$html;
	}else if(($result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE cat_id = ".$db->mySQLSafe($_POST['catid']). "  AND digital = '2'  ORDER BY productId ASC"))){
		if ($result && LANG_FOLDER !== $config['defaultLang']) {
	for ($r=0;$r<count($result);$r++) {
		if (($val = prodAltLang($result[$r]['productId'])) == true) {
			$result[$r]['name'] = $val['name'];
		}
	}
}
		$html.= '<h2 class="tit" onclick="togglethis(\'problems_1\');">What\'s your problem?.</h2>';
		$html.= ' <div id="problems_1" style="float:left;">';
		for($i=0;$i<count($result);$i++){
			$thumbRoot		= imgPath($result[$i]['image'], true, 'root');
			$thumbRootRel	= imgPath($result[$i]['image'], true, 'rel');
			if (file_exists($thumbRoot)) {
			$img =  str_replace("&", "&amp;", $thumbRootRel);
			} else {
				$img =  $GLOBALS['rootRel']."skins/Classic/styleImages/thumb_nophoto.gif";
			}
			$html .= '<div id="'."ch".$result[$i]["productId"].'" class="make_problem" onclick="loaddetails(\''.$result[$i]["productId"].'\',\''.$level.'\',\''."ch".$result[$i]["productId"].'\');">'.$result[$i]['name'].'
        </div>';
		}
		$html.= ' </div>';
		if($_POST['level'] == 2)
		$remov = "::007";
		echo '1::3::'.$html.$remov;
	}else
	echo '2::'.$_POST['level'];
}
$db->close();	
?>