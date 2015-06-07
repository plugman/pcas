<?php
/*
+--------------------------------------------------------------------------
|	closed.inc.php
|   ========================================
|	Store Closed Splash Page	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')) die("Access Denied");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charsetIso; ?>" />
<title><?php echo htmlspecialchars(str_replace("&#39;","'",$config['siteTitle'])); ?></title>
</head>

<body
<?php echo stripslashes(base64_decode($config['offLineContent'])); ?>
</body>
</html>