<?php

/*

|	video_categories.inc.php

|   ========================================

|	video_categories Box	

+--------------------------------------------------------------------------

*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }



// include lang file

//$lang = getLang("includes".CC_DS."boxes".CC_DS."flashbanner.inc.php");



$box_content=new XTemplate ("boxes".CC_DS."flashbanner.tpl");


$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_flashbanner WHERE img_status = 1 AND lang = '" . LANG_FOLDER  . "' ORDER BY priority, img_id ASC";



$results = $db->select($query);
if(!$results){
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_flashbanner WHERE img_status = 1  ORDER BY priority, img_id ASC";



	$results = $db->select($query);
}

$countf = count($results);

if (is_array($results)) {

	for ($i=0; $i<$countf; $i++) {	

			

			
			$box_content->assign("imgserial", ($i+1));
			$box_content->assign("FDATA", $results[$i]);
			$box_content->assign("TXT_THU", $results[$i]['img_title']);
			$box_content->parse("flash_banner.true.li");

	}

	$box_content->parse("flash_banner.true");

}







$box_content->parse("flash_banner");

$box_content = $box_content->text("flash_banner");

?>