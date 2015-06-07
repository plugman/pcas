<?php
/*
+--------------------------------------------------------------------------
|	extra.inc.php
|   ========================================
|	Extra Thingamybobs	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
switch (sanitizeVar($_GET['_a'])) {
	case "prodImages":
		require_once "includes".CC_DS."extra".CC_DS."prodImages.inc.php";
		break;
}
?>