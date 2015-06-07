<?php

/*

+--------------------------------------------------------------------------

|	index.php

|   ========================================

|	Manage Main Store Settings	

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



$lang = getLang("admin".CC_DS."admin_settings.inc.php");

$lang = getLang("orders.inc.php");



$msg = false;



permission("settings","read", true);



if (isset($_POST['install_htaccess'])) {

	$htaccess = CC_ROOT_DIR.CC_DS.'.htaccess';

	$ht_new = file_get_contents($glob['adminFolder'].CC_DS.'sources'.CC_DS.'settings'.CC_DS.'seo-htaccess.txt');

	## Some hosting companies need a RewriteBase if we can detect them e.g. Mosso

	if($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) {

		$ht_new = str_replace("RewriteEngine On","RewriteEngine On\nRewriteBase ".$glob['rootRel'],$ht_new);

	}

	if (@file_exists($htaccess)) {

		## .htaccess file already exists - lets check if it already has the settings, and append them if it doesn't

		$ht_old = @file_get_contents($htaccess);

		if (!strstr($ht_old, $ht_new) && @cc_is_writable($htaccess)) {

			## Append the rewrite rules

			$fp = @fopen($htaccess, 'ab');

			if (@fwrite($fp, $ht_new, strlen($ht_new))) {

				$msg .= '<p class="infoText">.htaccess was successfully created.</p>';

			} else {

				$msg .= '<p class="warnText">.htaccess file could not be written. Please create it manually.</p>';

			}

			@fclose($fp);

		}

	} else {

		$fp = @fopen(CC_ROOT_DIR.CC_DS.'.htaccess', 'wb');

		if (!@fwrite($fp, $ht_new)) {

			$msg .= '<p class="warnText">.htaccess file could not be written. Please create it manually.</p>';

		} else {

			$msg .= '<p class="infoText">.htaccess was successfully created.</p>';

		}

		@fclose($fp);

	}

} elseif (isset($_POST['install_rewrite_script'])) {

	## rewrite.script has to sit in web root folder 

	$rewrite_script = $_SERVER['DOCUMENT_ROOT'].CC_DS.'rewrite.script';

	$ht_new = file_get_contents($glob['adminFolder'].CC_DS.'sources'.CC_DS.'settings'.CC_DS.'seo-rewrite.script.txt');

	$ht_new = str_replace("{VAL_ROOT_REL}",$glob['rootRel'],$ht_new);

	if (@file_exists($rewrite_script)) {

		## rewrite.script file already exists - lets check if it already has the settings, and append them if it doesn't

		$ht_old = @file_get_contents($rewrite_script);

		if (!strstr($ht_old, $ht_new) && @cc_is_writable($rewrite_script)) {

			## Append the rewrite rules

			$fp = @fopen($rewrite_script, 'ab');

			if (@fwrite($fp, $ht_new, strlen($ht_new))) {

				$msg .= '<p class="infoText">rewrite.script was successfully created.</p>';

			} else {

				$msg .= '<p class="warnText">rewrite.script file could not be written. Please create it manually.</p>';

			}

			@fclose($fp);

		}

	} else {

		$fp = @fopen($_SERVER['DOCUMENT_ROOT'].CC_DS.'rewrite.script', 'wb');

		if (!@fwrite($fp, $ht_new)) {

			$msg .= '<p class="warnText">rewrite.script file could not be written. Please create it manually.</p>';

		} else {

			$msg .= '<p class="infoText">rewrite.script was successfully created.</p>';

		}

		@fclose($fp);

	}

}

if($_FILES['googleverify']['name'] != ""){
      $allowed_filetypes = array('.html'); // These will be the types of file that will pass the validation.
      $upload_path = ''; // The place the files will be uploaded to (currently a 'files' directory).
   $filename = $_FILES['googleverify']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext,$allowed_filetypes)){
   $msg = "<p class='warnText'>Upload Failed.</p>";
   }
   else{
   move_uploaded_file($_FILES['googleverify']['tmp_name'],$upload_path . $filename);
   $msg = "<p class='warnText'>Upload Success.</p>";
   }
}
if($_FILES['sitemapxml']['name'] != ""){
      $allowed_filetypes = array('.xml'); // These will be the types of file that will pass the validation.
      $upload_path = ''; // The place the files will be uploaded to (currently a 'files' directory).
   $filename = $_FILES['sitemapxml']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext,$allowed_filetypes)){
	    $msg = "<p class='warnText'>Upload Failed.</p>";
		}
		else{
   move_uploaded_file($_FILES['sitemapxml']['tmp_name'],$upload_path . $filename);
	$msg = "<p class='warnText'>Upload Success.</p>";
}
}

if (isset($_POST['config'])) {

	

	$cache = new cache();

	$cache->clearCache();

	

	## fix for Bug #147

	$fckEditor = (detectSSL()==true && $config['force_ssl']==false) ?  str_replace($config['rootRel_SSL'],$glob['rootRel'],$_POST['FCKeditor']) : $_POST['FCKeditor'];

	$_POST['config']['offLineContent'] = base64_encode($fckEditor);

	

	$config = fetchDbConfig("config");

	## DIRTY BUT MAKES SUPPORT EASIER!!

	if ($_POST['config']['ssl'] && !strstr($_POST['config']['rootRel_SSL'], '/')) { 

		$msg .= "<p class='warnText'>The HTTPS Root Relative Path entered is not valid! SSL has not been enabled.</p>";

		$_POST['config']['force_ssl'] = false;

		$_POST['config']['ssl'] = false;

		

	}

	

	if ($_POST['config']['ssl'] && !strstr($_POST['config']['storeURL_SSL'], 'https')) {

		$msg .= "<p class='warnText'>The absolute HTTPS Absolute URL entered is not valid. SSL has not been enabled.</p>";

		$_POST['config']['force_ssl'] = false;

		$_POST['config']['ssl'] = false;

	}

	

	if ($_POST['config']['sqlSessionExpiry'] && $_POST['config']['sqlSessionExpiry']<7200) {

		$msg .= "<p class='infoText'>The minimum session time has been set to 2 hours (7200 seconds). This will prevent IE session problems.</p>";

		$_POST['config']['sqlSessionExpiry'] = 7200;

	}

	

	$msg .= writeDbConf($_POST['config'], 'config', $config, true);

}

$config = fetchDbConfig("config");
$jsScript = jsGeoLocation("siteCountry", "siteCounty", "-- ".$lang['admin_common']['na']." --");
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>

<p class="pageTitle"><?php echo $lang['admin']['settings_store_settings']; ?></p>



<?php if (isset($msg)) echo msg($msg); ?>

<p class="copyText" style="padding:5px 0"><?php echo $lang['admin']['settings_edit_below']; ?></p>


<form name="updateSettings" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=settings/index">
<div class="setting">

<!--<select name="jump" onchange="jumpMenu('parent',this,0)">

<option value="#meta_data"><?php echo $lang['admin']['settings_meta_data']; ?></option>

<option value="#dirs_folders"></option>

<option value="#digital_downloads"></option>

<option value="#styles_misc"></option>

<option value="#gd_settings"></option>

<option value="#stock_settings"><?php echo $lang['admin']['settings_stock_settings'];?></option>

<option value="#abusive_comments">Abusive Comments Filteration</option>

<option value="#time_and_date"><?php echo $lang['admin']['settings_time_and_date'];?></option>

<option value="#locale_settings"><?php echo $lang['admin']['settings_locale_settings'];?></option>

<option value="#off_line_settings"><?php echo $lang['admin']['settings_off_line_settings'];?></option>

<option value="#proxy"><?php echo $lang['admin']['settings_proxy'];?></option>

<option value="#sef"><?php echo $lang['admin']['settings_sef'];?></option>

</select>-->

</p>

<script type="text/javascript">

$(function () {
			var tabContainers = $('div.tabs > div');
			tabContainers.hide().filter(':first').show();
			
			$('div.tabs ul.tabNavigation a').click(function () {
				tabContainers.hide();
				tabContainers.filter(this.hash).show();
				$('div.tabs ul.tabNavigation a').removeClass('selected');
				$(this).addClass('selected');
				return false;
			}).filter(':first').click();
		});
</script>

<div class="tabs">
        <ul class="tabNavigation">
            <li><a class="" href="#tab1">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t1.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_meta_data']; ?></a></li>
             <li><a class="" href="#tab2">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t2.jpg'; ?>"  /></span>
            <!--<?php echo $lang['admin']['settings_dirs_folders']; ?>--> SSL information </a></li>
            <!-- <li><a class="" href="#tab3">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t3.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_digital_downloads'];?></a></li>-->
             <li><a class="" href="#tab4">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t4.jpg'; ?>"  /></span>
            Social Links</a></li>
             <li><a class="" href="#tab5">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t5.jpg'; ?>"  /></span>
           <?php echo $lang['admin']['settings_styles_misc'];?> </a></li>
           <!--  <li><a class="" href="#tab6">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t6.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_gd_settings'];?></a></li>
             <li><a class="" href="#tab7">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t7.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_stock_settings'];?></a></li>
             <li><a class="" href="#tab8">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t8.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_time_and_date'];?></a></li>
             -->
             <li><a class="" href="#tab9">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t9.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_default_currency'];?></a></li>
             <li><a class="" href="#tab10">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t10.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_off_line_settings'];?></a></li>
             <li><a class="" href="#tab11">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t11.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_proxy'];?></a></li>
             <li><a class="" href="#tab12">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t12.jpg'; ?>"  /></span>
            <?php echo $lang['admin']['settings_sef'];?></a></li>
            
             <li><a class="" href="#tab13">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t12.jpg'; ?>"  /></span>
            Abusive Comments Filteration</a></li>
             <li><a class="" href="#tab14">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t12.jpg'; ?>"  /></span>
            Vender Information for Order Process</a></li>
            
            <li><a class="" href="#tab15">
            <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/t1.jpg'; ?>"  /></span>
            Olark Chat</a></li>
            
            
        </ul>
        <div  id="tab1">
         <div class="headingBlackbg"><?php echo $lang['admin']['settings_meta_data']; ?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainTable">

	

	<tr>

	  <td width="24%" align="right" class="tdText"><strong><?php echo $lang['admin']['settings_browser_title']; ?></strong></td>

	  <td align="right">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input name="config[siteTitle]" type="text" size="35" class="textbox" value="<?php echo $config['siteTitle']; ?>" />
      <span class="bgright"></span>
      </div>
      </td>

    </tr>

	<tr>

	  <td  align="right" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_desc'];?></strong></td>

	  <td align="left"><textarea name="config[metaDescription]" cols="35" rows="3" class="textarea textarea2"><?php echo $config['metaDescription']; ?></textarea></td>

    </tr>

	<tr>

	  <td  align="right" valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_meta_keywords'];?></strong><br />

 <?php echo $lang['admin']['settings_comma_separated'];?></td>

	  <td align="left"><textarea name="config[metaKeyWords]" cols="35" rows="3" class="textarea textarea2"><?php echo $config['metaKeyWords']; ?></textarea></td>

    </tr>

	<tr>

	  <td  align="right" class="tdText"><strong><?php echo $lang['admin']['settings_store_co_name'];?></strong></td>

	  <td>
      <div class="inputbox"><span class="bgleft"></span>
      <input name="config[storeName]" type="text" size="35" class="textbox" value="<?php echo $config['storeName']; ?>" />
      <span class="bgright"></span></div>
      </td>

    </tr>

	<tr>

	  <td  align="right" class="tdText"><strong><?php echo $lang['admin']['settings_store_address'];?></strong></td>

	  <td><textarea name="config[storeAddress]" cols="35" rows="3" class="textarea textarea2"><?php echo $config['storeAddress']; ?></textarea textarea2></td>

    </tr>


	<tr>

	  <td  align="right" class="tdText"><strong><?php echo $lang['admin']['settings_country'];?></strong></td>

      <td>

	  <?php 

	  $countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries"); 

	  ?>

	
	<div class="inputbox"><span class="bgleft"></span>
	<select name="config[siteCountry]" id="siteCountry" onChange="updateCounty(this.form);">

	<?php

	for($i=0; $i<count($countries); $i++)

	{

	?>

	<option value="<?php echo $countries[$i]['id']; ?>" <?php if($countries[$i]['id'] == $config['siteCountry']) echo "selected='selected'"; ?>><?php echo $countries[$i]['printable_name']; ?></option>

	<?php 

	} 

	?>

	</select>
	<span class="bgright"></span></div>
	  </td>

	</tr>

	<tr>

	  <td  align="right" class="tdText"><strong><?php echo $lang['admin']['settings_zone'];?></strong></td>

	  <td>

	  <?php 

	  $counties = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = '".$config['siteCountry']."'"); 

	  ?>
		<div class="inputbox"><span class="bgleft"></span>
	  <select name="config[siteCounty]" id="siteCounty">

	  <option value="" <?php if(empty($config['siteCounty'])) echo "selected='selected'"; ?>>-- <?php echo $lang['admin_common']['na'];?> --</option>

	  <?php

	  if($counties == TRUE)

	  {

	   for($i=0; $i<count($counties); $i++)

	   { ?>

	  <option value="<?php echo $counties[$i]['id']; ?>" <?php if($counties[$i]['id']==$config['siteCounty']) echo "selected='selected'"; ?>><?php echo $counties[$i]['name']; ?></option>

	  <?php 

	    }  

	  } ?>

      </select>
      <span class="bgright"></span></div>
      </td>

    </tr>
    <tr>

<td colspan="2">
<div class="maindiv seprator2"></div> 
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>

        </div>
        <div  id="tab2">
           <div class="headingBlackbg"><!--<?php echo $lang['admin']['settings_dirs_folders'];?>--> SSL information</div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">
	<!--<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_rootRel'];?></strong><br />

<?php echo $lang['admin']['settings_eg_rootRel'];?></td>

		<td align="left"><span class="textboxDisabled"><?php echo $glob['rootRel']; ?></span> <a href="javascript:;" class="txtLink" onclick="alert('<?php echo $lang['admin']['settings_ref_only'];?>');">*</a></td>

	</tr>
	<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_storeURL'];?></strong> <br />

	  <?php echo $lang['admin']['settings_eg_domain_com'];?> </td>

		<td align="left"><span class="textboxDisabled"><?php echo $glob['storeURL']; ?></span> <a href="javascript:;" class="txtLink" onclick="alert('<?php echo $lang['admin']['settings_ref_only'];?>');">*</a></td>

	</tr>
	<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_rootDir'];?></strong><br />

	  <?php echo $lang['admin']['settings_eg_root_path'];?>

	</td>

		<td align="left"><span class="textboxDisabled"><?php echo CC_ROOT_DIR; ?></span> <a href="javascript:;" class="txtLink" onclick="alert('<?php echo $lang['admin']['settings_ref_only'];?>');">*</a></td>

	</tr>-->
	<tr>

	  <td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_enable_ssl'];?></strong></td>

	  <td align="left">
      <div class="maindiv">
      
      <script>
	  
	function toggle(id1,id2,name){  
      $("#"+id2).addClass('off');
	  $("#"+id2).removeClass('on');
      $("#"+id1).addClass("on");
	  $("#"+id1).removeClass("off");
	$("#"+name).val("0") ;
	}
function toggle2(id1,id2,name){  
      $("#"+id2).addClass('on');
	   $("#"+id2).removeClass('off');
    $("#"+id1).addClass("off");
	$("#"+id1).removeClass("on");
	$("#"+name).val("1");
	}
</script>

      	<span <?php if($config['ssl']== 0) echo "class='on'"; else echo "class='off'";?>   id="first"  onclick="toggle('first', 'second', 'ssl');"  ><span><?php echo $lang['admin_common']['no'];?></span></span>
        <span <?php if($config['ssl']== 1) echo "class='on'"; else echo "class='off'";?>  id="second" onclick="toggle2('first', 'second', 'ssl');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>
       <!-- <span class="help"><?php echo $lang['admin']['settings_ssl_warn'];?></span>-->
         <input type="hidden" name="config[ssl]" value="<?php echo $config['ssl'];?>" id="ssl"/>
      </div>
	 <!-- <select name="config[ssl]" class="textbox"  >

		<option value="1" <?php if($config['ssl']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

		<option value="0" <?php if($config['ssl']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

	  </select>--> 
	  </td>

    </tr>

	<tr>

	  <td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_force_ssl'];?></strong></td>

	  <td align="left">
      <div class="maindiv">
      <span <?php if($config['ssl']== 0) echo "class='on'"; else echo "class='off'";?>  id="g1"  onclick="toggle('g1', 'g2', 'force_ssl');" ><span><?php echo $lang['admin_common']['no'];?></span></span>
        <span <?php if($config['ssl']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="g2"  onclick="toggle2('g1', 'g2', 'force_ssl');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>
         <span class="sm left">
	    <?php echo $lang['admin']['settings_force_ssl_desc'];?>
        </span>
         <input type="hidden" name="config[force_ssl]" value="<?php echo $config['force_ssl']; ?>" id="force_ssl"/>
       </div>
      

	  <!--<select name="config[force_ssl]" class="textbox">

		<option value="1" <?php if($config['force_ssl']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

		<option value="0" <?php if($config['force_ssl']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

	  </select>--> </td>

    </tr>
	<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_rootRel_SSL'];?></strong> 

	<br />

 </td>

		<td align="left">
        <div class="inputbox">
        	<span class="bgleft"></span>
        <input type="text" size="35" class="textbox" name="config[rootRel_SSL]" value="<?php echo $config['rootRel_SSL']; ?>" />
        	<span class="bgright"></span></div>
            <div class="left sm"><?php echo $lang['admin']['settings_eg_rootRel'];?></div>
         </td>

	</tr>

	<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_storeURL_SSL'];?></strong> <br />

	  <?php echo $lang['admin']['settings_eg_domain_SSL'];?></td>

		<td align="left">
         <div class="inputbox">
        	<span class="bgleft"></span>
        <input type="text" size="35" class="textbox" name="config[storeURL_SSL]" value="<?php echo $config['storeURL_SSL']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<!--<tr>

	<td width="24%" class="tdText" align="right"><strong><?php echo $lang['admin']['settings_rootDir_SSL'];?></strong><br />

	  <?php echo $lang['admin']['settings_eg_root_path_secure'];?></td>

		<td align="left">

		

		<input type="text" size="35" class="textbox" name="config[rootDir_SSL]" value="<?php echo $config['rootDir_SSL']; ?>" />

		

		<span class="textboxDisabled"><?php echo CC_ROOT_DIR; ?></span>  <a href="javascript:;" class="txtLink" onclick="alert('<?php echo $lang['admin']['settings_ref_only'];?>');">*</a>

		</td>

	</tr>-->

	

	<tr>



<td colspan="2">
<div class="maindiv seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>
        </div>
        
        <!--<div  id="tab3">
          <div class="headingBlackbg"><?php echo $lang['admin']['settings_digital_downloads'];?></div>

<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	<tr>

	  <td width="24%" class="tdText"><strong><?php echo $lang['admin']['settings_download_expire_time'];?></strong><br/>

      <?php echo $lang['admin']['settings_seconds'];?></td>

	  <td align="left">
       <div class="inputbox">
        	<span class="bgleft"></span>
      <input type="text" size="35" class="textbox" name="config[dnLoadExpire]" value="<?php echo $config['dnLoadExpire']; ?>" />
      <span class="bgright"></span></div>
      </td>

    </tr>

	<tr>

	  <td class="tdText"><strong><?php echo $lang['admin']['settings_download_attempts'];?></strong><br />

      <?php echo $lang['admin']['settings_attempts_desc'];?></td>

	  <td align="left">
      	 <div class="inputbox">
        	<span class="bgleft"></span>
      <input type="text" size="35" class="textbox" name="config[dnLoadTimes]" value="<?php echo $config['dnLoadTimes']; ?>" />
      <span class="bgright"></span></div>
      </td>

    </tr><tr>



<td colspan="2"><div class="seprator2"></div> <input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>
        </div>-->
        <div  id="tab4">
           <div class="headingBlackbg">Social Links</div>

<table border="0" cellspacing="0" cellpadding="" class="mainTable" width="100%">
    <tr>
	  <td class="tdText" width="24%"><strong>FaceBook Link</strong></td>
	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[Fbadd]" value="<?php echo $config['Fbadd']; ?>" /> 
	  <span class="bgright"></span></div>
         </td>
    </tr>
    <tr>

	  <td class="tdText"><strong>Twitter Link</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[Twiadd]" value="<?php echo $config['Twiadd']; ?>" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>
<tr>

	   <td class="tdText"><strong>Enable facebook like button</strong></td>

	  <td align="left">
      <div class="maindiv">
      <span <?php if($config['facebookbutton']== 0) echo "class='on'"; else echo "class='off'";?>  id="z1"  onclick="toggle('z1', 'z2', 'facebookbutton');" ><span><?php echo $lang['admin_common']['no'];?></span></span>
        <span <?php if($config['facebookbutton']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="z2"  onclick="toggle2('z1', 'z2', 'facebookbutton');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>
       <input type="hidden" name="config[facebookbutton]" value="<?php echo $config['facebookbutton']; ?>" id="facebookbutton"/>
         <span class="sm left">
        </span>
       </div>
 </td>

    </tr>
    <tr>

	  <td class="tdText"><strong>Facebook page Address</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
     <input type="text" size="35" class="textbox" name="config[fbpageaddress]" value="<?php echo $config['fbpageaddress']; ?>" id="config[fbpageaddress]" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>

    

    
    

    <tr>

<td  colspan=" 2"><div class="seprator2"></div>
	<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

    

    </table>
        </div>
        
        <div  id="tab5">
          <div class="headingBlackbg"><?php echo $lang['admin']['settings_styles_misc'];?></div>

<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">
<!--
	<tr>

	  <td width="24%" class="tdText"><strong><?php echo $lang['admin']['settings_default_language'];?></strong></td>

	  <td align="left">

		<select class="textbox" name="config[defaultLang]">

		<?php

		$path = CC_ROOT_DIR.CC_DS."language";

		foreach (glob($path.CC_DS.'*') as $langpath) {

			$folder = basename($langpath);

			if (is_dir($langpath) && preg_match('#^[a-z]{2}(\_[A-Z]{2})?$#iuU', $folder)) {

				if (file_exists($langpath.CC_DS.'config.php')) {

					include $langpath.CC_DS.'config.php';

					

					$selected = ($config['defaultLang']==$folder) ? ' selected="selected"' : '';

					echo sprintf('<option value="%s"%s>%s</option>', $folder, $selected, $langName);

				}

			}

		}

		?>

		</select>

	  </td>

    </tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_store_skin'];?></strong></td>

	  <td align="left">

		<select class="textbox" name="config[skinDir]">

		<?php

		$skinPath = CC_ROOT_DIR.CC_DS.'skins';

		$skinList = listAddons($skinPath);

		

		foreach ($skinList as $folder) {

			if (file_exists($skinPath.CC_DS.$folder.CC_DS.'package.conf.php')) {

			//	loadAddonConfig();

			//	include $skinPath.CC_DS.$folder.CC_DS.'package.conf.php';

			} else {

				$skin['name'] = $folder;

			}

			$selected = ($config['skinDir'] == $folder) ? ' selected="selected"' : '';

			echo sprintf('<option value="%s"%s>%s</option>', $folder, $selected, $skin['name']);

		} 

		?>

		</select>

	  </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_changeskin'];?></strong></td>

	<td align="left">

		<select class="textbox" name="config[changeskin]">

		  <?php

		  $array = array($lang['admin_common']['no'], $lang['admin_common']['yes']);

		  foreach ($array as $key => $title) {

		  	$selected = ($config['changeskin']==$key) ? 'selected="selected"' : '';

			echo sprintf('<option value="%s"%s>%s</option>', $key, $selected, $title);

		  }

		  ?>

		</select>

	</td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_show_latest'];?></strong></td>

		<td align="left">

		<select class="textbox" name="config[showLatestProds]">

			<option value="0" <?php if($config['showLatestProds']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

			<option value="1" <?php if($config['showLatestProds']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

		</select>		</td>

	</tr>

	<tr>

	<td class="tdText"><strong><?php echo $lang['admin']['settings_no_latest'];?></strong></td>

		<td align="left">

		<input type="text" class="textbox" size="3" name="config[noLatestProds]" value="<?php echo $config['noLatestProds']; ?>" />		</td>

	</tr>-->

	<!--

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_no_cats_per_row'];?></strong></td>

		<td align="left"><input type="text" size="3" class="textbox" name="config[displaycatRows]" value="<?php echo $config['displaycatRows']; ?>" /></td>

	</tr>

	

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_dir_symbol'];?></strong></td>

		<td align="left"><input type="text" size="20" class="textbox" name="config[dirSymbol]" value="<?php echo $config['dirSymbol']; ?>" /></td>

	</tr>
-->
<tr>

	  <td class="tdText" width="24%"><strong>Paypal Precessing Fee</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[paypal]" value="<?php echo $config['paypal']; ?>" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>
    <tr>
	<td  class="tdText"><strong><?php echo $lang['admin']['google_analytics'];?></strong><br />

	  <?php echo $lang['admin']['google_analytics_info'];?></td>

	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="10" class="textbox" name="config[google_analytics]" value="<?php echo $config['google_analytics']; ?>" />
      <span class="bgright"></span></div>
      </td>

	</tr>
    
<!--	<tr>

	<td width="24%"  class="tdText"><strong><?php echo $lang['admin']['settings_prods_per_page'];?></strong></td>

		<td align="left">
        <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[productPages]" value="<?php echo $config['productPages']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

    <tr>

	<td  class="tdText"><strong>No Testimonials per Page</strong></td>

		<td align="left">
        <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[nooftestimonial]" value="<?php echo $config['nooftestimonial']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_precis_length'];?></strong><?php echo $lang['admin']['settings_chars'];?></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[productPrecis]" value="<?php echo $config['productPrecis']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_no_sale_items'];?></strong></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[noSaleBoxItems]" value="<?php echo $config['noSaleBoxItems']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_no_pop_prod'];?></strong></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[noPopularBoxItems]" value="<?php echo $config['noPopularBoxItems']; ?>" /><span class="bgright"></span></div></td>

	</tr>
-->


	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_email_name'];?></strong><br />

	  <?php echo $lang['admin']['settings_email_name_desc'];?></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="35" class="textbox" name="config[masterName]" value="<?php echo $config['masterName']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_email_address'];?></strong><br />

	<?php echo $lang['admin']['settings_email_address_desc'];?></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="35" class="textbox" name="config[masterEmail]" value="<?php echo $config['masterEmail']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_mail_method'];?></strong><br />

	  <?php echo $lang['admin']['settings_mail_recommended'];?> </td>

		<td align="left">
		 <div class="inputbox" style="width:183px">
        <span class="bgleft"></span>
			<select name="config[mailMethod]" class="textbox"  style="width:174px">

				<option value="mail" <?php if($config['mailMethod']=="mail") echo "selected='selected'"; ?>>mail()</option>

				<option value="smtp" <?php if($config['mailMethod']=="smtp") echo "selected='selected'"; ?>>SMTP</option>

			</select>	
            <span class="bgright"></span></div>
            </td>

	</tr>

	<tr>

	  <td class="tdText"><?php echo $lang['admin']['settings_smtpHost'];?></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
        <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[smtpHost]" value="<?php echo $config['smtpHost']; ?>" /> 
		<span class="bgright"></span></div>
        <span class="sm left">
	     <?php echo $lang['admin']['settings_defaultHost'];?></span></td>

    </tr>

		<tr>

		  <td class="tdText"><?php echo $lang['admin']['settings_smtpPort'];?></td>

		  <td align="left" class="tdText">
           <div class="inputbox" >
        <span class="bgleft"></span>
          <input type="text" size="3"  name="config[smtpPort]" value="<?php echo $config['smtpPort']; ?>" />
		<span class="bgright"></span></div><span class="sm left">
	      <?php echo $lang['admin']['settings_defaultPort'];?></span></td>

    </tr>

		<tr>

		  <td class="tdText"><?php echo $lang['admin']['settings_smtpAuth'];?></td>

		  <td align="left" class="tdText">
          <div class="maindiv">
          	<span  onclick="toggle('t2', 't3', 'smtpAuth');" id="t2" <?php if($config['smtpAuth']== 0) echo "class='on'"; else echo "class='off'";?>  "><span><?php echo $lang['admin_common']['no'];?></span></span>
            <span onclick="toggle2('t2', 't3', 'smtpAuth');" id="t3" <?php if($config['smtpAuth']== 1) echo "class='on'"; else echo "class='off'";?>  "><span><?php echo $lang['admin_common']['yes'];?></span></span>
            <span class="sm left">
		  <?php echo $lang['admin']['settings_defaultAuth'];?></span>
           <input type="hidden" name="config[smtpAuth]" value="<?php echo $config['smtpAuth']; ?>" id="smtpAuth"/>
          </div>
       <!-- 
          <select name="config[smtpAuth]" class="textbox">

            <option value="FALSE" <?php if($config['smtpAuth']=="FALSE") echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

			<option value="TRUE" <?php if($config['smtpAuth']=="TRUE") echo "selected='selected'"; ?>>
			<?php echo $lang['admin_common']['yes'];?></option>

          </select> -->
		</td>

    </tr>

		<tr>

		  <td class="tdText"><?php echo $lang['admin']['settings_smtpUsername'];?></td>

		  <td align="left">
          <div class="inputbox" >
        <span class="bgleft"></span>
          <input type="text" size="25" class="textbox" name="config[smtpUsername]" value="<?php echo $config['smtpUsername']; ?>" />
          <span class="bgright"></span></div>
          </td>

    </tr>

		<tr>

		  <td class="tdText"><?php echo $lang['admin']['settings_smtpPassword'];?></td>

		  <td align="left">
          <div class="inputbox" >
        <span class="bgleft"></span>
          <input type="text" size="25" class="textbox" name="config[smtpPassword]" value="<?php echo $config['smtpPassword']; ?>" />
          <span class="bgright"></span></div>
          </td>

    </tr>

		<!--<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_max_upload_size'];?></strong><br />

	  <?php echo $lang['admin']['settings_under_x_recom'];?></td>

		<td align="left">
        <div class="inputbox" style="width:183px">
        <span class="bgleft"></span>
        <input type="text" size="10" class="textbox" style="width:174px" name="config[maxImageUploadSize]" value="<?php echo $config['maxImageUploadSize']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_max_sess_length'];?></strong><br />

	  <?php echo $lang['admin']['settings_seconds'];?></td>

	  <td align="left">
      <div class="inputbox" style="width:183px" >
        <span class="bgleft"></span>
      <input type="text" size="10" style="width:174px" class="textbox" name="config[sqlSessionExpiry]" value="<?php echo $config['sqlSessionExpiry']; ?>" />
      <span class="bgright"></span></div>
      </td>

	</tr>-->

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_floodControl'];?></strong></td>

	  <td align="left">
		 <div class="inputbox"  >
        <span class="bgleft"></span>
	  <select name="config[floodControl]" class="textbox">

			<option value="0" <?php if($config['floodControl']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no']; ?></option>

			<option value="1" <?php if($config['floodControl']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes']; ?></option>

			<option value="recaptcha" <?php if($config['floodControl']=="recaptcha") echo "selected='selected'"; ?>>reCaptcha (http://www.recaptcha.net)</option>

		</select>
        <span class="bgright"></span></div>
        <span class="sm left"><?php echo $lang['admin']['settings_floodControlDesc'];?></span>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_richTextEditor'];?></strong></td>

	  <td align="left" class="tdText">
		
        <span <?php if($config['richTextEditor']== 0) echo "class='on'"; else echo "class='off'";?> id="t4" onclick="toggle('t4', 't5', 'richTextEditor');">
        <span><?php echo $lang['admin_common']['no']; ?></span>
        </span>
        <span <?php if($config['richTextEditor']== 1) echo "class='on'"; else echo "class='off'";?> id="t5" onclick="toggle2('t4', 't5', 'richTextEditor');">
       <span> <?php echo $lang['admin_common']['yes']; ?></span>
        </span>
         <input type="hidden" name="config[richTextEditor]" value="<?php echo $config['richTextEditor']; ?>" id="richTextEditor"/>
	  <!--<select name="config[richTextEditor]" class="textbox">

			<option value="0" <?php if($config['richTextEditor']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes']; ?></option>

			<option value="1" <?php if($config['richTextEditor']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no']; ?></option>

		</select>--> 
		<div class="left">
		<span class="sm" style="width:50px"><?php echo $lang['admin']['settings_rte_height'];?></span>
        </div>
        <div class="inputbox"  style="width:50px; margin-right:10px;">
        <span class="bgleft"></span>
         <input type="text" style="width:40px;" name="config[rteHeight]" size="5" class="textbox" value="<?php echo $config['rteHeight']; ?>" /> 
		 <div class="bgright"></div>
        </div>
        <div class="inputbox" style="width:50px;"><span class="bgleft"></span>
        <select name="config[rteHeightUnit]" class="textbox" style="width:42px">

			<option value="%" <?php if($config['rteHeightUnit']=="%") echo "selected='selected'"; ?>>%</option>

			<option value="" <?php if($config['rteHeightUnit']=="") echo "selected='selected'"; ?>>px</option>

		</select>
        <span class="bgright"></span></div>

	  </td>

	</tr>	

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_debug'];?></strong></td>

	  <td align="left" class="tdText">
		<span <?php if($config['debug']== 0) echo "class='on'"; else echo "class='off'";?> id="t7" onclick="toggle('t7', 't8', 'debug');">
        <span><?php echo $lang['admin_common']['no']; ?></span>
        </span>
        <span <?php if($config['debug']== 1) echo "class='on'"; else echo "class='off'";?> id="t8" onclick="toggle2('t7', 't8', 'debug');">
        <span><?php echo $lang['admin_common']['yes']; ?></span>
        </span>
        <input type="hidden" name="config[debug]" value="<?php echo $config['debug']; ?>" id="debug"/>
	  <!--<select name="config[debug]" class="textbox">

			<option value="0" <?php if($config['debug']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no']; ?></option>

			<option value="1" <?php if($config['debug']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes']; ?></option>

		</select>-->
        
        <span class="sm">
          <?php echo $lang['admin']['settings_debug_desc']; ?>
        </span>
        </td>
	</tr>
	<!--<tr>
	<td  class="tdText"><strong><?php echo $lang['admin']['settings_latestNewsRSS'];?></strong></td>
	  <td align="left" class="tdText">
      <div class="inputbox">
      	<span class="bgleft"></span>
	  <input type="text" name="config[latestNewsRRS]" size="35" class="textbox" value="<?php echo $config['latestNewsRRS']; ?>" />
      <span class="bgright"></span></div>
      </td>
	</tr>-->
	<!--<tr>
	<td  class="tdText"><strong><?php echo $lang['admin']['settings_add_to_basket_act'];?></strong></td>
		<td align="left">
       <span onclick="toggle('t9', 't10', 'add_to_basket_act');" id="t9" <?php if($config['add_to_basket_act']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t9', 't10', 'add_to_basket_act');" id="t10" <?php if($config['add_to_basket_act']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span>
         <input type="hidden" name="config[add_to_basket_act]" value="<?php echo $config['add_to_basket_act']; ?>" id="add_to_basket_act"/>
			<!--<select name="config[add_to_basket_act]" class="textbox">
				<option value="0" <?php if($config['add_to_basket_act']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['add_to_basket_act']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				

			</select>-->		</td>

	</tr>-->

	

	

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_img_gallery_type'];?></strong></td>

		<td align="left">
			<div class="inputbox">
            <span class="bgleft"></span>
			<select name="config[imgGalleryType]" class="textbox">
				<option value="0" <?php if($config['imgGalleryType']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_img_gallery_type_popup'];?></option>

				<option value="1" <?php if($config['imgGalleryType']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_img_gallery_type_lightbox'];?></option>

				

			</select>
            <span class="bgright"></span></div>
            
            		</td>

	</tr>

	

	<!--<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_cat_tree'];?></strong></td>

		<td align="left">
	   <span onclick="toggle('t11', 't12', 'cat_tree');" id="t11" <?php if($config['cat_tree']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t11', 't12', 'cat_tree');" id="t12" <?php if($config['cat_tree']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span>
        <input type="hidden" name="config[cat_tree]" value="<?php echo $config['cat_tree']; ?>" id="cat_tree"/>
			<!--<select name="config[cat_tree]" class="textbox">

				<option value="0" <?php if($config['cat_tree']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['cat_tree']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				

			</select>-->		
            
        </td>

	</tr>-->

	

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['hide_prices'];?></strong></td>

		<td align="left">
			<span onclick="toggle('t13', 't14', 'hide_prices');" id="t13" <?php if($config['hide_prices']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t13', 't14', 'hide_prices');" id="t14" <?php if($config['hide_prices']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span>
         <input type="hidden" name="config[hide_prices]" value="<?php echo $config['hide_prices']; ?>" id="hide_prices"/>
			<!--<select name="config[hide_prices]" class="textbox">

				<option value="0" <?php if($config['hide_prices']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['hide_prices']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				

			</select>-->		</td>

	</tr>

	

	<!--<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['pop_products_source'];?></strong></td>

		<td align="left">
			<span onclick="toggle('t15', 't16', 'pop_products_source');" id="t15" <?php if($config['pop_products_source']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t15', 't16', 'pop_products_source');" id="t16" <?php if($config['pop_products_source']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span> <input type="hidden" name="config[pop_products_source]" value="<?php echo $config['pop_products_source']; ?>" id="pop_products_source"/>
        
			<!--<select name="config[pop_products_source]" class="textbox">

				<option value="0" <?php if($config['pop_products_source']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['pop_products_views'];?></option>

				<option value="1" <?php if($config['pop_products_source']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['pop_products_sales'];?></option>

				

			</select>-->		</td>

	</tr>-->

		<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['use_cache'];?></strong></td>

		<td align="left">
		<span onclick="toggle('t17', 't18', 'cache');" id="t17" <?php if($config['cache']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t17', 't18', 'cache');" id="t18" <?php if($config['cache']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span> <input type="hidden" name="config[cache]" value="<?php echo $config['cache']; ?>" id="cache"/>
			<!--<select name="config[cache]" class="textbox">

				<option value="0" <?php if($config['cache']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['cache']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				

			</select>-->		</td>

	</tr>

	

	<!--<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['show_empty_cat'];?></strong></td>

		<td align="left">
		<span onclick="toggle('t19', 't20', 'show_empty_cat');" id="t19" <?php if($config['show_empty_cat']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t19', 't20', 'show_empty_cat');" id="t20" <?php if($config['show_empty_cat']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span> <input type="hidden" name="config[show_empty_cat]" value="<?php echo $config['show_empty_cat']; ?>" id="show_empty_cat"/>
			<!--<select name="config[show_empty_cat]" class="textbox">

				<option value="0" <?php if($config['show_empty_cat']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['show_empty_cat']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				

			</select>--></td>

	</tr>-->

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['disable_alert_email'];?></strong></td>

		<td align="left">
		<span onclick="toggle('t21', 't22', 'disable_alert_email');" id="t21" <?php if($config['disable_alert_email']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t21', 't22', 'disable_alert_email');" id="t22" <?php if($config['disable_alert_email']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span> <input type="hidden" name="config[disable_alert_email]" value="<?php echo $config['disable_alert_email']; ?>" id="disable_alert_email"/>
			<!--<select name="config[disable_alert_email]" class="textbox">

				<option value="0" <?php if($config['disable_alert_email']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['disable_alert_email']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

			</select>--></td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['cat_newest_first'];?></strong><br />

	<?php echo $lang['admin']['cat_newest_first_info'];?>

	</td>

		<td align="left">
			<span onclick="toggle('t23', 't24', 'cat_newest_first');" id="t23" <?php if($config['cat_newest_first']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t23', 't24', 'cat_newest_first');" id="t24" <?php if($config['cat_newest_first']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span> <input type="hidden" name="config[cat_newest_first]" value="<?php echo $config['cat_newest_first']; ?>" id="cat_newest_first"/>
			<!--<select name="config[cat_newest_first]" class="textbox">

				<option value="0" <?php if(!$config['cat_newest_first']) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['cat_newest_first']) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

			</select>--></td>

	</tr>
	

	

	<!--<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_order_expire'];?></strong><br />

	  <?php echo $lang['admin']['settings_seconds'];?></td>

	  <td align="left">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="10" class="textbox" name="config[orderExpire]" value="<?php echo $config['orderExpire']; ?>" />
      <span class="bgright"></span></div>
      <span class="sm"><?php echo $lang['admin']['settings_zero_disabled'];?> </span>
      </td>

	</tr>-->

	<tr>

<tr>

	  <td class="tdText" width="24%"><strong>Home Page Header text</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[htext]" value="<?php echo $config['htext']; ?>" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>
    <tr>

	  <td class="tdText" width="24%"><strong>Home Page Header Price</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[hprice]" value="<?php echo $config['hprice']; ?>" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>
<tr>

	  <td class="tdText" width="24%"><strong>Home Page Header Link</strong></td>

	  <td align="left" class="tdText">
       <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="25" class="textbox" name="config[hlink]" value="<?php echo $config['hlink']; ?>" /> 
		<span class="bgright"></span></div>
	     </td>

    </tr>
<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>
        </div>
        <div  id="tab6">
            <div class="headingBlackbg"><?php echo $lang['admin']['settings_gd_settings'];?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">
	<tr>

	<td width="24%"  class="tdText"><strong><?php echo $lang['admin']['settings_gd_ver'];?></strong></td>

		<td align="left">
		<!--<span onclick="toggle('t25', 't26', 'gdversion');" id="t25" <?php if($config['gdversion']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t25', 't26', 'gdversion');" id="t26" <?php if($config['gdversion']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span>
        <input type="hidden" name="config[gdversion]" value="<?php echo $config['gdversion']; ?>" id="gdversion"/>-->
        <div class="inputbox">
        <span class="bgleft"></span>
			<select name="config[gdversion]" class="textbox">

				<option value="2" <?php if($config['gdversion']==2) echo "selected='selected'"; ?>>2</option>

				<option value="0" <?php if($config['gdversion']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['na']; ?></option>

			</select>	<span class="bgright"></span></div>	</td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_gd_gif_support'];?></strong></td>

		<td align="left">
		<span onclick="toggle('t27', 't28', 'gdGifSupport');" id="t27" <?php if($config['gdGifSupport']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t27', 't28', 'gdGifSupport');" id="t28" <?php if($config['gdGifSupport']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[gdGifSupport]" value="<?php echo $config['gdGifSupport']; ?>" id="gdGifSupport"/>
			<!--<select name="config[gdGifSupport]" class="textbox">

				<option value="0" <?php if($config['gdGifSupport']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

				<option value="1" <?php if($config['gdGifSupport']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

			</select>-->		
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_gd_thumb_size'];?></strong></td>

		<td align="left">
        <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="4" class="textbox" name="config[gdthumbSize]" value="<?php echo $config['gdthumbSize']; ?>" />
        	<span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_gd_max_img_size'];?></strong></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="4" class="textbox" name="config[gdmaxImgSize]" value="<?php echo $config['gdmaxImgSize']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_gd_img_quality'];?></strong><br />

<?php echo $lang['admin']['settings_recom_quality'];?></td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="3" class="textbox" name="config[gdquality]" value="<?php echo $config['gdquality']; ?>" />
        <span class="bgright"></span></div>
        </td>

	</tr>
    <tr>



<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>

        </div>
        <div  id="tab7">
           <div class="headingBlackbg"><?php echo $lang['admin']['settings_stock_settings'];?></div>

<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">
	

		<tr>

	<td width="24%" class="tdText"><strong><?php echo $lang['admin']['settings_use_stock'];?></strong></td>

		<td align="left">
	   <span onclick="toggle('t29', 't30', 'stockLevel');" id="t29" <?php if($config['stockLevel']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t29', 't30', 'stockLevel');" id="t30" <?php if($config['stockLevel']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[stockLevel]" value="<?php echo $config['stockLevel']; ?>" id="stockLevel"/>
			<!--<select name="config[stockLevel]" class="textbox">

				<option value="1" <?php if($config['stockLevel']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				<option value="0" <?php if($config['stockLevel']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

			</select>-->		</td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_allow_out_of_stock_purchases'];?></strong></td>

		<td align="left">
	    <span onclick="toggle('t31', 't32', 'outofstockPurchase');" id="t31" <?php if($config['outofstockPurchase']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t31', 't32', 'outofstockPurchase');" id="t32" <?php if($config['outofstockPurchase']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[outofstockPurchase]" value="<?php echo $config['outofstockPurchase']; ?>" id="outofstockPurchase"/>
			<!--<select name="config[outofstockPurchase]" class="textbox">

				<option value="1" <?php if($config['outofstockPurchase']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

				<option value="0" <?php if($config['outofstockPurchase']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

			</select>-->		</td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_stock_change_time'];?></strong></td>

		<td align="left">
		<span onclick="toggle('t33', 't34', 'stock_change_time');" id="t33" <?php if($config['stock_change_time']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t33', 't34', 'stock_change_time');" id="t34" <?php if($config['stock_change_time']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[stock_change_time]" value="<?php echo $config['stock_change_time']; ?>" id="stock_change_time"/>
        
			<!--<select name="config[stock_change_time]" class="textbox">

				<option value="0" <?php if($config['stock_change_time']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_stock_change_timement'];?></option>

				<option value="1" <?php if($config['stock_change_time']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_stock_decrease_onprocessing'];?></option>

				<option value="2" <?php if($config['stock_change_time']==2) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_stock_decrease_onorderbuild'];?></option>

			</select>-->

	  </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_stock_replace_time']; ?></strong></td>

		<td align="left">

		<input type="checkbox" value="1" name="config[stock_replace_time][1]"  <?php if($config['stock_replace_time'][1]==1) echo "checked='checked'"; ?> /> <?php echo $lang['glob']['orderState_1'];?> <br />

		  <input type="checkbox" value="1" name="config[stock_replace_time][2]"  <?php if($config['stock_replace_time'][2]==1) echo "checked='checked'"; ?> /> <?php echo $lang['glob']['orderState_2'];?> <br />

		  <input type="checkbox" value="1" name="config[stock_replace_time][4]"  <?php if($config['stock_replace_time'][4]==1) echo "checked='checked'"; ?> /> <?php echo $lang['glob']['orderState_4'];?> <br />

		  <input type="checkbox" value="1" name="config[stock_replace_time][5]"  <?php if($config['stock_replace_time'][5]==1) echo "checked='checked'"; ?> /> <?php echo $lang['glob']['orderState_5'];?> <br />

		  <input type="checkbox" value="1" name="config[stock_replace_time][6]"  <?php if($config['stock_replace_time'][6]==1) echo "checked='checked'"; ?> /> <?php echo $lang['glob']['orderState_6'];?> 

	  </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_stock_warn_type'];?></strong>

</td>

		<td align="left" class="tdText">
			<span onclick="toggle('t35', 't36', 'stock_warn_type');" id="t35" <?php if($config['stock_warn_type']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t35', 't36', 'stock_warn_type');" id="t36" <?php if($config['stock_warn_type']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[stock_warn_type]" value="<?php echo $config['stock_warn_type']; ?>" id="stock_warn_type"/>
			<!--<select name="config[stock_warn_type]" class="textbox">

				<option value="0" <?php if($config['stock_warn_type']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_stock_global_warn'];?></option>

				<option value="1" <?php if($config['stock_warn_type']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_stock_product_warn'];?></option>

			</select>-->

	  </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_stock_warn_level'];?></strong>

</td>

		<td align="left" class="tdText">
			<div class="inputbox">
            <span class="bgleft"></span>
			<input type="text" size="3" class="textbox" name="config[stock_warn_level]" id="stock_warn_level" value="<?php echo $config['stock_warn_level']; ?>" />
            
            <span class="bgright"></span></div>
			<span class="sm"> <?php echo $lang['admin']['settings_stock_warn_level_desc'];?></span>

	  </td>

	</tr>

	<tr>

<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>
        </div>
         <div  id="tab8">
            <div class="headingBlackbg"><?php echo $lang['admin']['settings_time_and_date'];?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	<tr>

	<td width="24%"  class="tdText"><strong><?php echo $lang['admin']['settings_time_format'];?></strong>

	 </td>

		<td align="left">
        <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="20" class="textbox" name="config[timeFormat]" value="<?php echo $config['timeFormat']; ?>" />
        <span class="bgright"></span></div>
        <span class="sm"> <?php echo $lang['admin']['settings_time_format_desc'];?></span>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_time_offset'];?></strong>

	 </td>

		<td align="left">
         <div class="inputbox">
        <span class="bgleft"></span>
        <input name="config[timeOffset]" type="text" class="textbox" value="<?php echo $config['timeOffset']; ?>" size="20" />
        <span class="bgright"></span></div>
        <span class="sm"> <?php echo $lang['admin']['settings_time_offset_desc'];?></span>
        </td>

	</tr>

	<tr>

	<td  class="tdText"><strong><?php echo $lang['admin']['settings_date_format'];?></strong> 

	 </td>

		<td align="left">
        <div class="inputbox">
        <span class="bgleft"></span>
        <input type="text" size="35" class="textbox" name="config[dateFormat]" value="<?php echo $config['dateFormat']; ?>" />
        <span class="bgright"></span></div>
        <span class="sm"> <?php echo $lang['admin']['settings_date_format_desc'];?></span>
        </td>

	</tr><tr>

<td colspan="2">

<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>

        </div>
        <div  id="tab9">
          <div class="headingBlackbg"><?php echo $lang['admin']['settings_default_currency'];?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">
	<tr>

	  <td width="24%"  class="tdText"><strong><?php echo $lang['admin']['settings_default_currency'];?></strong></td>

	  <td align="left">

	  <?php

	  $currencies = $db->select("SELECT name, code FROM ".$glob['dbprefix']."ImeiUnlock_currencies WHERE active = 1 ORDER BY name ASC");

		?>
			<div class="inputbox">
            <span class="bgleft"></span>
		<select name="config[defaultCurrency]">

		<?php

		for($i=0; $i<count($currencies); $i++){

		?>

		<option value="<?php echo $currencies[$i]['code']; ?>" <?php if($currencies[$i]['code']==$config['defaultCurrency']) echo "selected='selected'"; ?>><?php echo $currencies[$i]['name']; ?></option>

		<?php

		}

	  ?>

	  </select>
      		<span class="bgright"></span></div>
        </td>
    </tr>
	

	

	<tr>



<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

</tr>

</table>
        </div>
        <div  id="tab10">
         <div class="headingBlackbg"><?php echo $lang['admin']['settings_off_line_settings'];?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	

	<tr>

	  <td  width="24%" class="tdText"><strong><?php echo $lang['admin']['settings_off_line'];?></strong></td>

	  <td align="left">
		 <span onclick="toggle('t51', 't52', 'offLine');" id="t51" <?php if($config['offLine']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t51', 't52', 'offLine');" id="t52" <?php if($config['offLine']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[offLine]" value="<?php echo $config['offLine']; ?>" id="offLine"/>
	  <!--<select name="config[offLine]" class="textbox">

        <option value="1" <?php if($config['offLine']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

        <option value="0" <?php if($config['offLine']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

      </select>--></td>

    </tr>

	<tr>

	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_off_line_allow_admin'];?></strong></td>

	  <td align="left">
		 <span onclick="toggle('t53', 't54', 'offLineAllowAdmin');" id="t53" <?php if($config['offLineAllowAdmin']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t53', 't54', 'offLineAllowAdmin');" id="t54" <?php if($config['offLineAllowAdmin']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[offLineAllowAdmin]" value="<?php echo $config['offLineAllowAdmin']; ?>" id="offLineAllowAdmin"/>
	  <!--<select name="config[offLineAllowAdmin]" class="textbox">

        <option value="1" <?php if($config['offLineAllowAdmin']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

        <option value="0" <?php if($config['offLineAllowAdmin']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

      </select>--></td>

    </tr>

	<tr>

	  <td valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_off_line_content'];?></strong></td>

	  <td align="left">&nbsp;</td>

    </tr>

	<tr>

	  <td colspan="2" valign="top" class="tdText">

	    <?php

			require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");

			$oFCKeditor = new FCKeditor('FCKeditor');

			$oFCKeditor->BasePath = $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/';

			$oFCKeditor->Value = stripslashes(base64_decode($config['offLineContent']));

			if ($config['richTextEditor'] == false) {

				$oFCKeditor->off = TRUE;

			}

			$oFCKeditor->Create();

		?>

	  </td>

    </tr>

	

	<tr>

	

	  <td align="left" colspan="2">	  
		<div class="seprator2"></div>
        
	  <input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

	</tr>

</table>

        </div>
        <div  id="tab11">
          <div class="headingBlackbg"><?php echo $lang['admin']['settings_proxy'];?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	<tr>

	  <td  width="24%" class="tdText"><strong><?php echo $lang['admin']['settings_use_proxy'];?></strong></td>

	  <td align="left">
		 <span onclick="toggle('t55', 't56', 'proxy');" id="t55" <?php if($config['proxy']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t55', 't56', 'proxy');" id="t56" <?php if($config['proxy']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[proxy]" value="<?php echo $config['proxy']; ?>" id="proxy"/>
	  <!--<select name="config[proxy]" class="textbox">

        <option value="0" <?php if($config['proxy']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

		<option value="1" <?php if($config['proxy']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

      </select>-->

	  </td>
    </tr>
	<tr>
	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_proxy_host'];?></strong></td>
	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="30" class="textbox" name="config[proxyHost]" value="<?php echo $config['proxyHost']; ?>" />
      <span class="bgright"></span></div>
      </td>

    </tr>

	<tr>

	  <td valign="top" class="tdText"><strong><?php echo $lang['admin']['settings_proxy_port'];?></strong></td>

	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="5" class="textbox" name="config[proxyPort]" value="<?php echo $config['proxyPort']; ?>" />
      <span class="bgright"></span></div>
      </td>

    </tr>

	

	<tr>

	

	  <td align="left" colspan="2">
      <div class="seprator2"></div>	  

	  <input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

	</tr>

</table>
        </div>
		<div  id="tab12">
           <div class="headingBlackbg"><?php echo $lang['admin']['settings_sef'];?></div>

<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%" id="sef">
	
	<tr>
	  <td width="24%" class="tdText"><?php echo $lang['admin']['settings_use_seo']; ?></td>
	  <td align="left">
		 <span onclick="toggle('t57', 't58', 'seff');" id="t57" <?php if($config['sef']== 0) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['no'];?></span>
        </span>
        <span onclick="toggle2('t57', 't58', 'seff');" id="t58" <?php if($config['sef']== 1) echo "class='on'"; else echo "class='off'";?>>
        <span><?php echo $lang['admin_common']['yes'];?></span>
        </span><input type="hidden" name="config[sef]" value="<?php echo $config['sef']; ?>" id="seff"/>
	  <!--<select name="config[sef]" class="textbox">

        <option value="1" <?php if($config['sef']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

        <option value="0" <?php if($config['sef']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

      </select>--></td>

    </tr>

<?php 

if($config['sef']) { 

?>

	<tr>

	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_url_method'];?></strong>

	  </td>

	  <td align="left">
		<div class="inputbox">
        <span class="bgleft"></span>
	  <select name="config[sefserverconfig]" class="textbox">

	    <?php if(eregi('zeus', $_SERVER['SERVER_SOFTWARE'])) { ?>

	    <option value="4" <?php if($config['sefserverconfig']==4) echo "selected='selected'"; ?>>Zeus Rewrite Script (Recommended)</option>

	    <?php } else { ?>

        <option value="0" <?php if($config['sefserverconfig']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_seo_method_mod_rewrite'];?></option>

        <?php } ?>

        <option value="2" <?php if($config['sefserverconfig']==2 || $config['sefserverconfig']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_seo_method_lookback'];?></option>

        <option value="3" <?php if($config['sefserverconfig']==3) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_seo_method_ftp'];?></option>

      </select>
      <span class="bgright"></span></div><span class="sm"><?php echo $lang['admin']['settings_seo_method']; ?></span>
      	  </td>

    </tr>

	<?php 

	if (in_array($config['sefserverconfig'], array(0))) { 

	?>

	<!--<tr>

	  <td valign="top" class="tdText"><p><strong>.htaccess</strong>

	  <br /><?php echo $lang['admin']['settings_seo_htaccess']; ?></td>

	  <td align="left" class="tdText">

	  	<textarea cols="50" rows="15" wrap="off"><?php

	  	$htaccess_conts = file_get_contents($glob['adminFolder'].CC_DS.'sources'.CC_DS.'settings'.CC_DS.'seo-htaccess.txt');

	  	if($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) {

			$htaccess_conts = str_replace("RewriteEngine On","RewriteEngine On\nRewriteBase ".$glob['rootRel'],$htaccess_conts);

		}

		echo $htaccess_conts;

	  	?>

	  	</textarea><br />

		<br />

		<input type="submit" name="install_htaccess" class="submit" id="install_htaccess" value="Install .htaccess" />

	  </td>

    </tr>-->

    <?php 

	} else if ($config['sefserverconfig'] == 3) { 

	?>

	<tr>

	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_seo_generate_pages'];?></strong><br />

<?php echo $lang['admin']['settings_seo_generate_pages_desc'];?></td>

	  <td align="left" class="tdText">

<strong><?php echo $lang['admin']['settings_ftp_server'];?></strong> <input type="text" size="25" class="textbox" name="config[ftp_server]" value="<?php echo $config['ftp_server'];?>" /><br />

<strong><?php echo $lang['admin']['settings_ftp_user'];?> </strong><input type="text" size="25" class="textbox" name="config[ftp_username]" value="<?php echo $config['ftp_username'];?>" /><br />

<strong><?php echo $lang['admin']['settings_ftp_pass'];?></strong> <input type="text" size="25" class="textbox" name="config[ftp_password]" value="<?php echo $config['ftp_password'];?>" /><br />

<strong><?php echo $lang['admin']['settings_ftp_dir'];?></strong> <input type="text" size="25" class="textbox" name="config[ftp_root_dir]" value="<?php echo $config['ftp_root_dir'];?>" /><br />

		<?php echo sprintf($lang['admin']['settings_seo_generate_pages_inst'],$glob['adminFile']."?_g=settings/sef_genpages");?>      </td>

    </tr>

<?php

	} else if ($config['sefserverconfig'] == 4) {

?>

	<tr>

	  <td valign="top" class="tdText"><p><strong>rewrite.script</strong>

	  <br />To use either "Zeus Rewrite Script" it is required that a "rewrite.script" file is created in the root directory of your store. To do this please open a text editor such as Notepad or TextEdit, copy and paste the contents of the text area opposite into it and save it as "rewrite.script.txt". Upload this file to your server and rename it to "rewrite.script".</td>

	  <td align="left" class="tdText">

	  	<textarea cols="50" rows="15" wrap="off"><?php

	  	$seo_rewrite_script = file_get_contents($glob['adminFolder'].CC_DS.'sources'.CC_DS.'settings'.CC_DS.'seo-rewrite.script.txt');

	  	echo str_replace("{VAL_ROOT_REL}",$glob['rootRel'],$seo_rewrite_script);

	  	?>

	  	</textarea><br />

		<br />

		<input type="submit" name="install_rewrite_script" class="submit" id="install_rewrite_script" value="Install rewrite.script" />

	  </td>

    </tr> 

<?php

	} 

}

?>

	<tr>

	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_meta_behaviour'];?></strong> <br />

<?php echo $lang['admin']['settings_meta_behaviour_desc'];?></td>

	  <td align="left">
		<div class="inputbox">
        <span class="bgleft"></span>
	  <select name="config[seftags]" class="textbox">

        <option value="2" <?php if($config['seftags']==2) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_meta_or_glob_desc_key'];?></option>

        <option value="1" <?php if($config['seftags']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_meta_combined'];?></option>

        <option value="0" <?php if($config['seftags']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_meta_disabled'];?></option>

      </select>
      <span class="bgright"></span></div>
      </td>

    </tr>

    <?php 

	if($config['seftags']) { 

	?>

	<tr>

	  <td  class="tdText"><strong><?php echo $lang['admin']['settings_meta_browser_title_format'];?></strong> <br />

<?php echo $lang['admin']['settings_meta_browser_cat_and_prod'];?></td>

	  <td align="left">
		<div class="inputbox">
        <span class="bgleft"></span>
	  <select name="config[sefprodnamefirst]" class="textbox">

        <option value="1" <?php if($config['sefprodnamefirst']==1) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_seo_prod_name_cat_cat'];?></option>

        <option value="0" <?php if($config['sefprodnamefirst']==0) echo "selected='selected'"; ?>><?php echo $lang['admin']['settings_seo_cat_cat_prod'];?></option>

      </select>
      <span class="bgright"></span></div>
      </td>

    </tr>
<tr>

	  <td  class="tdText"><strong><?php echo "Upload Google webmaster html verification file";?></strong>

	  </td>

	  <td align="left">
		<div class="inputbox">
        <span class="bgleft"></span>
	<input type="file" name="googleverify"  />
      <span class="bgright"></span></div><span class="sm"><?php echo "Only Html file is allowed"; ?></span>
      	  </td>

    </tr>
    <tr>

	  <td  class="tdText"><strong><?php echo "Upload Site map xml file";?></strong>

	  </td>

	  <td align="left">
		<div class="inputbox">
        <span class="bgleft"></span>
	<input type="file" name="sitemapxml"  />
      <span class="bgright"></span></div><span class="sm"><?php echo "Only xml file is allowed"; ?></span>
      	  </td>

    </tr>
<?php 

	} 

?>



<tr>

	  <td align="left" colspan="2">	  
		<div class="seprator2"></div>
	  <input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>

	</tr>

</table>
        </div>
       <div  id="tab13">
          <div class="headingBlackbg">Abusive Comments Filteration</div>

<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	<tr>

	  <td  width="24%" class="tdText"><strong>Abusive Keywords:</strong></td>

	  <td align="left">

		<textarea name="config[abusive_words]" cols="35" rows="3" class="textarea textarea2"><?php echo $config['abusive_words']; ?></textarea>

	  </td>

    </tr>

	<tr>


<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="Update Keywords" /></td>

</tr>

</table>

        </div>
        <div  id="tab14">
           <div class="headingBlackbg"><?php echo "Vender Information for Order Process";?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	
	<tr>
	  <td width="24%" class="tdText"><strong><?php echo "Vender Email";?></strong>
     </td>
	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="35" class="textbox" name="config[vemail]" value="<?php echo $config['vemail']; ?>" />
      <span class="bgright"></span></div>
      </td>
    </tr>
	<tr>
	  <td  class="tdText"><strong><?php echo "Vender Password";?></strong></td>
	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="password" size="35" class="textbox" name="config[vpassword]" value="<?php echo $config['vpassword']; ?>" />
      <span class="bgright"></span></div>
      </td>
    </tr>
    <tr>
	  <td  class="tdText"><strong><?php echo "Current Balance on Imeiunlock.net";?></strong></td>
      <?php $wbalance = getwbalance($config['vemail'],$config['vpassword']); ?>
	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="35" class="textbox" name="wbalance" value="<?php echo $wbalance; ?>" />
      <span class="bgright"></span></div>
      </td>
    </tr>
    <tr>

<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>
</tr>
</table>
        </div>
        <div  id="tab15">
           <div class="headingBlackbg"><?php echo "Olark chat Configuration";?></div>
<table border="0" cellspacing="0" cellpadding="3" class="mainTable" width="100%">

	
	<tr>

	   <td class="tdText"><strong>Enable Olark Chat</strong></td>

	  <td align="left">
      <div class="maindiv">
      <span <?php if($config['olark']== 0) echo "class='on'"; else echo "class='off'";?>  id="o1"  onclick="toggle('o1', 'o2', 'olarkb');" ><span><?php echo $lang['admin_common']['no'];?></span></span>
        <span <?php if($config['olark']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="o2"  onclick="toggle2('o1', 'o2', 'olarkb');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>
       <input type="hidden" name="config[olark]" value="<?php echo $config['olark']; ?>" id="olarkb"/>
         <span class="sm left">
        </span>
       </div>
 </td>

    </tr>
    <tr>
	  <td  class="tdText"><strong><?php echo "Olark chat identity Nummber </br>(format :xxxx-xxx-xx-xxxx)";?></strong></td>
	  <td align="left">
      <div class="inputbox">
      <span class="bgleft"></span>
      <input type="text" size="35" class="textbox" name="config[olarkid]" value="<?php echo $config['olarkid']; ?>" />
       <span class="bgright"></span></div><span class="sm"><?php echo "You can find olark identify Number in last of the olark code provided by olark without ' &nbsp;' "; ?></span>
      </td>
    </tr>
    <tr>

<td colspan="2">
<div class="seprator2"></div>
<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" />
</td>
</tr>
</table>
        </div>
    </div>

<div class="clear"></div>




<!--<p class="copyText">* <?php echo $lang['admin']['settings_ref_only'];?></p>-->
























</div>
</form>