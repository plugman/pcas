<?php 
/*

|	licForm.inc.php
|   ========================================
|	Used For ImeiUnlock Copyright Removal
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
$skipFooter = 1;
$lang = getLang("admin" . CC_DS . "admin_misc.inc.php");

$currentUrl = $GLOBALS['storeURL']."/".$glob['adminFile']."?_g=misc/licForm";


$header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charsetIso."\" />
<title>".$lang['admin']['misc_license_form']."</title>
<link href=\"".$glob['adminFolder']."/styles/style.css\" rel=\"stylesheet\" type=\"text/css\" />
</head>
<body style=\"margin: 0px; padding: 0px; background: #EAEAEA;\">";
$footer = "</body>\r\n</html>";
 
if(isset($_GET['r']) && $_GET['r']=="T" && isset($_GET['l']) && !empty($_GET['l'])){

	$licVars['lkv'] = 1;
	$licVars['lk'] = base64_decode($_GET['l']);
	
	$result = writeDbConf($licVars,"config", $config, "config", $output = FALSE);
	
	if ($result == TRUE){
		httpredir("https://www.xxxx.xxxx/external/licVer4.php?step=2&v=4&licKey=".$_GET['l']);
	} else {
		echo $header;
?>
<div class="warnText"><?php echo $lang['admin']['misc_write_error']; ?></div><p><a href="<?php echo $glob['adminFile']."?_g=misc/licForm";?>" class="txtLink" target="_self"><?php echo $lang['admin']['misc_try_again'];  ?></a></p>

<?php
echo $footer;
	}	
	
} elseif(isset($_GET['r']) && $_GET['r']=="F") {

	echo $header;

?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
  <tr>
    <td class="tdTitle"><?php echo $lang['admin']['misc_purchase_cubecart']; ?></td>
  </tr>
  <tr>
    <td><p class="copyText"><?php echo $lang['admin']['misc_invalid_key']; ?></p>
    <p><a href="https://www.xxxx.com/site/purchase/" class="txtLink" target="_blank"><?php echo $lang['admin']['misc_purchase_license_key']; ?></a> | <a href="<?php echo $glob['adminFile']."?_g=misc/licForm";?>" class="txtLink" target="_self"><?php echo $lang['admin']['misc_try_again']; ?></a></p></td>
  </tr>
</table>
<?php
	
	echo $footer;

} else {

	echo $header;

?>
<form name="licForm" method="post" action="https://www.xxxx.xxxx/external/licVer4.php?step=1" style="padding: 0px; margin: 0px;"><table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php echo $lang['admin']['misc_purchase_cubecart']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><p class="copyText"><?php echo $lang['admin']['misc_run_unlicensed']; ?></p>
    <p><a href="https://www.xxxx.xxxx/site/purchase/" class="txtLink" target="_blank"><?php echo $lang['admin']['misc_purchase_license_key']; ?></a></p></td>
  </tr>
  <tr>
    <td><span  class="tdText"><?php echo $lang['admin']['misc_license_key']; ?></span></td>
    <td> 
		<input type="text" name="licKey" id="licKey" size="30" class="textbox" value="XXXXXX-XXXXXX-XXXX" onclick="this.value = '';" />
		<input type="hidden" name="domain" value="<?php echo $glob['storeURL']; ?>" />
		<input type="hidden" name="reDir" value="<?php echo base64_encode($currentUrl); ?>" />
		<input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin']['misc_submit_key']; ?>" /> 
	</td>
  </tr>
</table>
</form>
<?php 
	echo $footer;
} 
?>