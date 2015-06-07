<?php
## Include Core functions & variables
include ("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."global.inc.php");
$redirect = $glob['storeURL'].'/index.php?_g=rm&type=gateway&cmd=process&module=WorldPay&email='.$_REQUEST['email'].'&cartId='.$_REQUEST['cartId'].'&transId='.$_REQUEST['transId'].'&amount='.$_REQUEST['amount'].'&transStatus='.$_REQUEST['transStatus'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Redirecting&hellip;</title>
<meta http-equiv="Refresh" content="0;URL=<?php echo $redirect; ?>" />
</head>
<body>
<p>If your browser doesn't redirect within a few seconds, please click <a href="<?php echo $redirect; ?>">HERE.</a></p>
</body>
</html>