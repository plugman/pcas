<?php 
/*

|	401.inc.php
|   ========================================
|	Admin Access Denied Page	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<p class="warnText"><?php echo $lang['admin_common']['other_401']; ?></p>
<p align="center"><img src="images/largeWarning.gif" alt="" width="220" height="192" title="" /></p>