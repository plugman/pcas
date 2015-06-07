<?php
/*
+--------------------------------------------------------------------------
|	serverInfo.inc.php
|   ========================================
|	Display Server Environment Info
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['misc_server_info'];?></p>
<p><span class="copyText"><?php echo $lang['admin']['misc_ini_set_desc'];?></span></p>
<center class="copyText">
<?php
ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();

// rip out head tags and content
$phpinfo = preg_replace("/(\<head)(.*?)(head>)/si", "", $phpinfo);
// add class to links
$phpinfo = str_replace("<a href", "<a class=\"txtLink\" href", $phpinfo);
// remove doctype
$phpinfo = preg_replace("/(\<!DOCTYPE)(.*?)(\">)/si", "", $phpinfo);
// remove other elements
$phpinfo = str_replace(array("<body>","</body>","<html>","</html>","<hr />"), "", $phpinfo);
// reclass
$phpinfo = str_replace("class=\"h\"","class=\"tdTitle\"",$phpinfo);
// reclass & style
$phpinfo = str_replace("class=\"e\"","class=\"tdText\" style=\"font-weight: bold;\"",$phpinfo);
$phpinfo = str_replace("class=\"v\"","class=\"tdText\"",$phpinfo);
// no cell spacing
$phpinfo = str_replace("<table","<table class=\"mainTable\" cellspacing=\"0\"",$phpinfo);
// bump up cell padding
$phpinfo = str_replace("cellpadding=\"3\"","cellpadding=\"4\"",$phpinfo);
echo $phpinfo;
?> 
</center>