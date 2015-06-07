<?php
/*
+--------------------------------------------------------------------------
|	imageNoCache.inc.php
|   ========================================
|	Preview Image
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
$skipFooter = 1;

require("classes".CC_DS."gd".CC_DS."gd.inc.php");

$imagePath = ($glob['rootRel'] != CC_DS) ? str_replace($glob['rootRel'], '', $_GET['file']) : $_GET['file'];
$imagePath = CC_ROOT_DIR.CC_DS.$imagePath;

$img = new gd($imagePath);
$img->show(1);
?>