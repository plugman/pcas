<?php
/*
+--------------------------------------------------------------------------
|	skin.inc.php
|   ========================================
|	Skin Jump Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."skin.inc.php");

$ismobile = check_user_agent('mobile');
if($ismobile && $config['mobilesking']) {
	$box_content = new XTemplate ("boxes".CC_DS."skin.tpl");
	$box_content->assign("VAL_CURRENT_PAGE", $returnPage);
	if(SKIN_FOLDER == 'mobile'){
	$box_content->assign("SKIN_VAL", 'Classic');
	$box_content->assign("VAL_SKIN", 'Desktop Skin');
	}elseif(SKIN_FOLDER == 'Classic'){
	$box_content->assign("SKIN_VAL", 'mobile');
	$box_content->assign("VAL_SKIN", 'Mobile Skin');
	}
	$box_content->parse("skin");
	$box_content = $box_content->text("skin");
} else {
	$box_content = NULL;
}
?>