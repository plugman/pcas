<?php
/*
+--------------------------------------------------------------------------
|	header.inc.php
|   ========================================
|	Admin Header
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

$langFolder = (defined('LANG_FOLDER') && constant('LANG_FOLDER')) ? LANG_FOLDER :  $config['defaultLang'];
include CC_ROOT_DIR.CC_DS.'language'.CC_DS.$langFolder.CC_DS.'config.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charsetIso; ?>" />
<link href="favicon.ico" rel="icon" type="image/x-icon" />
<link href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/styles/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/styles/repair.css" rel="stylesheet" type="text/css" />
<script>
    document.write(unescape('%3Cscript type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/jquery-1.9.1.min.js"%3E%3C/script%3E'));
</script>
<script>
    jQuery.noConflict();
    var $j = jQuery;
</script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/prototype.js"></script>

<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/jslibrary.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/menuecokies.js"></script>
<script type="text/javascript">




jQuery(document).ready(function(){	
	jQuery("a.txtLink").each(function(){
		val_txtLink = jQuery(this).html();
		if(val_txtLink == 'Edit')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/edit.png" alt="Edit" title="Edit" />');
		if(val_txtLink == 'Delete')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/delete.png" alt="Delete" title="Delete" />');
		if(val_txtLink == 'Clone')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/clone.png" alt="Clone" title="Product Clone" />');
		if(val_txtLink == 'Enable')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/disable.png" alt="Enable" title="Click to Enable" />');
		if(val_txtLink == 'Disable')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/enable.png" alt="Disable" title="Click to Disable" />');
		if(val_txtLink == 'Languages')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/lang.png" alt="Language" title="Language" />');
	    if(val_txtLink == 'Resize')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/resize.png" alt="Resize" title="Click to Resize" />');

		if(val_txtLink == 'Active')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/disable.png" alt="Active" title="Click to Active" />');
		if(val_txtLink == 'Inactive')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/enable.png" alt="Inactive" title="Click to Inactive" />');
		if(val_txtLink == 'Hide')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/show.png" alt="Hide" title="Click to Hide" />');
		if(val_txtLink == 'Show')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/hide.png" alt="Show" title="Click to Show" />');
		if(val_txtLink == 'hide')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/show.png" alt="Hide" title="Click to Hide" />');
		if(val_txtLink == 'show')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/hide.png" alt="Show" title="Click to Show" />');
		
		if(val_txtLink == 'Export to CSV')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/csv_text.png" alt="Export to CSV" title="Export to CSV" />');
		
			
	
		if(val_txtLink == 'Un-Block')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/userDisable.png" alt="Un-Block" title="Un-Block User" />');
		if(val_txtLink == 'Block')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/userEnable.png" alt="Block User" title="Block User" />');			
	
	});
	
	jQuery("a.txtNullLink").each(function(){
		val_txtNullLink = jQuery(this).html();
		if(val_txtNullLink == 'Delete')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/deleteDisable.png" alt="delete" title="This cannot be deleted" />');
		if(val_txtNullLink == 'Edit')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/editDisable.png" alt="Edit" title="This cannot be Edit" />');
		if(val_txtNullLink == 'Languages')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/lang_disable.png" alt="Languages" title="This cannot be Accessed" />');
		if(val_txtNullLink == 'Clone')
			jQuery(this).html('<img src="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/images/clone_disable.png" alt="Clone" title="This cannot be Accessed" />');
			
			

		
	});
	
});
</script>

<?php 
if (isset($jsScript)) { ?>
<script type="text/javascript">
<?php echo $jsScript; ?>
</script>
<?php
}
?>
<title><?php echo $config['siteTitle']?> - <?php echo $lang['admin_common']['incs_administration'];?></title>
</head>
<body id="pageTop">
<?php 
if (isset($ccAdminData['adminId']) && $ccAdminData['adminId']>0) {
?>
<!-- start wrapping table -->
<table  border="0" cellspacing="0" cellpadding="0"  class="maincenter">
  <tr>
    <td valign="top" width="251" rowspan="3"  class="patern2" >
      
   
<?php require(CC_ROOT_DIR . CC_DS . $glob['adminFolder'] . CC_DS . "includes" .CC_DS. "navigation.inc.php"); ?> 
	 
	</td>
  </tr>
  <tr>
  <td valign="top" class="tdContent">
<!-- end wrapping table -->
<div id="topBar">
<div class="maindiv" style="position:relative">
<span class="left heading"><?php echo $lang['admin_common']['other_welcome_note']; ?></span>
<div id="loginBar">
	<div class="toggleMainbox">
	<div class="togglebox" onclick="javascript: NavigationSlider2('navStoreConfig2');"   >
       <span class="name"> Hi <?php echo $ccAdminData['username']; ?></span>
    </div>
    <div class="toggleinner unchecked" id="navStoreConfig2" >
        <span class="arrow"></span>
        <a href="<?php echo $GLOBALS['rootRel']; ?><?php echo $glob['adminFile']; ?>?_g=logout" ><?php echo $lang['admin_common']['incs_logout'];?></a> 
        <a href="<?php echo $GLOBALS['rootRel']; ?><?php echo $glob['adminFile']; ?>?_g=adminusers/changePass" ><?php echo $lang['admin_common']['incs_change_pass'];?></a>
     </div>
    </div>
	<!--<span class="txtLogin"><?php echo $lang['admin_common']['incs_logged_in_as'];?> <strong><?php echo $ccAdminData['username']; ?></strong> [ </span><a href="<?php echo $GLOBALS['rootRel']; ?><?php echo $glob['adminFile']; ?>?_g=logout" class="txtLink"><?php echo $lang['admin_common']['incs_logout'];?></a> <span class="txtLogin">|</span> <a href="<?php echo $GLOBALS['rootRel']; ?><?php echo $glob['adminFile']; ?>?_g=adminusers/changePass" class="txtLink"><?php echo $lang['admin_common']['incs_change_pass'];?></a> <span class="txtLogin">]</span>-->
</div>

<div id="dateBar">
	<!--<span ><?php echo formatTime(time(),$strftime); ?></span>-->
</div>
</div>
<div  class="maindiv" style="background:#fff; padding-bottom:17px;">
	<ul class="breadcurm">
     	<li><a href="admin.php"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/home.jpg'; ?>" /></a></li>
         <?= getbc() ?>
    </ul>
</div>
</div>
<!-- start of admin content -->
<div id="contentPad">


<?php } ?>