<?php
/*
+--------------------------------------------------------------------------
|	cartNavi.inc.php
|   ========================================
|	Cart Pages Navigation Links Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$box_content = new XTemplate ("boxes".CC_DS."accountpanel.tpl");


$box_content->parse("accountpanel");
$box_content = $box_content->text("accountpanel");
?>