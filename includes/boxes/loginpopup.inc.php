<?php
/*
+--------------------------------------------------------------------------
|	loginpopup.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."loginpopup.inc.php");


$box_content = new XTemplate ("boxes".CC_DS."loginpopup.tpl");




$box_content->parse("loginpopup");
$box_content = $box_content->text("loginpopup");


?>