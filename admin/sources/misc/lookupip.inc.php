<?
/*

|	lookupip.inc.php
|   ========================================
|	Look up IP Hostname
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
$skipFooter = 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $_GET['ip']; ?></title>
<link href="<?php echo $glob['adminFolder']; ?>/styles/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="pageTitle"><?php echo $_GET['ip']; ?></p>
<p class="copyText"><a href="http://www.dnsstuff.com/tools/ipall.ch?domain=<?php echo $_GET['ip']; ?>" class="txtLink" target="_parent"><?php echo gethostbyaddr($_GET['ip']); ?></a></p>
<p align="center" class="copyText">
	<strong>
		[<a href="javascript:window.close();" class="txtLink"><?php echo $lang['admin_common']['close_window']; ?></a>]
	</strong>
</p>
</body>
</html>
