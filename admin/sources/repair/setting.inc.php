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



 <script>

	  

	function toggle4(id1,id2,name){  

      $j("#"+id2).addClass('off');

	  $j("#"+id2).removeClass('on');

      $j("#"+id1).addClass("on");

	  $j("#"+id1).removeClass("off");

	$j("#"+name).val("0") ;

	}

function toggle2(id1,id2,name){  

      $j("#"+id2).addClass('on');

	   $j("#"+id2).removeClass('off');

    $j("#"+id1).addClass("off");

	$j("#"+id1).removeClass("on");

	$j("#"+name).val("1");

	}

</script>

<form name="updateSettings" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=repair/setting">

<div class="setting">





<div class="tabs">

       

        

       

        <div  id="tab4">

           <div class="headingBlackbg">Mobile Repair Module</div>



<table border="0" cellspacing="0" cellpadding="" class="mainTable" width="100%">

    <tr>

	  <td class="tdText" width="24%"><strong>Shop Area post Code</strong></td>

	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

      <input type="text" size="25" class="textbox" name="config[spostcode]" value="<?php echo $config['spostcode']; ?>" /> 

	  <span class="bgright"></span></div>

         </td>

    </tr>

    <tr>



	  <td class="tdText"><strong>Service Area (Miles)</strong></td>



	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

      <input type="text" size="25" class="textbox" name="config[sarea]" value="<?php echo $config['sarea']; ?>" /> 

		<span class="bgright"></span></div>

	     </td>



    </tr>

<tr>



	   <td class="tdText"><strong>PickUp Service</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['spickup']== 0) echo "class='on'"; else echo "class='off'";?>  id="z1"  onclick="toggle4('z1', 'z2', 'spickup');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['spickup']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="z2"  onclick="toggle2('z1', 'z2', 'spickup');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[spickup]" value="<?php echo $config['spickup']; ?>" id="spickup"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>

    <tr>



	   <td class="tdText"><strong>Mailin Service</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['smailin']== 0) echo "class='on'"; else echo "class='off'";?>  id="z3"  onclick="toggle4('z3', 'z4', 'smailin');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['smailin']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="z4"  onclick="toggle2('z3', 'z4', 'smailin');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[smailin]" value="<?php echo $config['smailin']; ?>" id="smailin"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>

    <tr>



	  <td class="tdText"><strong>PickUp Charges</strong></td>



	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

     <input type="text" size="35" class="textbox" name="config[rpickupcharges]" value="<?php echo $config['rpickupcharges']; ?>" id="config[rpickupcharges]" /> 

		<span class="bgright"></span></div>

	     </td>



    </tr>



    



    

    



    <tr>



<td  colspan=" 2"><div class="seprator2"></div>

	<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>



</tr>



    



    </table>

        </div>

        <div  id="tab4">

           <div class="headingBlackbg">SMS Global Api Setting</div>



<table border="0" cellspacing="0" cellpadding="" class="mainTable" width="100%">

<tr>



	   <td class="tdText"><strong>SmsGlobal Integration</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobal']== 0) echo "class='on'"; else echo "class='off'";?>  id="s1"  onclick="toggle4('s1', 's2', 'SmsGlobal');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobal']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="s2"  onclick="toggle2('s1', 's2', 'SmsGlobal');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobal]" value="<?php echo $config['SmsGlobal']; ?>" id="SmsGlobal"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>

    <tr>



	   <td class="tdText"><strong>Send Sms on Processing Order</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobalp']== 0) echo "class='on'"; else echo "class='off'";?>  id="sp1"  onclick="toggle4('sp1', 'sp2', 'SmsGlobalp');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobalp']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="sp2"  onclick="toggle2('sp1', 'sp2', 'SmsGlobalp');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobalp]" value="<?php echo $config['SmsGlobalp']; ?>" id="SmsGlobalp"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>

    <tr>



	   <td class="tdText"><strong>Send Sms on Order Completion</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobalc']== 0) echo "class='on'"; else echo "class='off'";?>  id="sc1"  onclick="toggle4('sc1', 'sc2', 'SmsGlobalc');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobalc']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="sc2"  onclick="toggle2('sc1', 'sc2', 'SmsGlobalc');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobalc]" value="<?php echo $config['SmsGlobalc']; ?>" id="SmsGlobalc"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>
<tr>



	   <td class="tdText"><strong>Send Sms on Order pending approval</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobalpa']== 0) echo "class='on'"; else echo "class='off'";?>  id="spa1"  onclick="toggle4('spa1', 'spa2', 'SmsGlobalpa');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobalpa']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="spa2"  onclick="toggle2('spa1', 'spa2', 'SmsGlobalpa');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobalpa]" value="<?php echo $config['SmsGlobalpa']; ?>" id="SmsGlobalpa"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>
    
    <tr>



	   <td class="tdText"><strong>Send Sms on Order pending quote approval</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobalpq']== 0) echo "class='on'"; else echo "class='off'";?>  id="spq1"  onclick="toggle4('spq1', 'spq2', 'SmsGlobalpq');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobalpq']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="spq2"  onclick="toggle2('spq1', 'spq2', 'SmsGlobalpq');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobalpq]" value="<?php echo $config['SmsGlobalpq']; ?>" id="SmsGlobalpq"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>
    <tr>



	   <td class="tdText"><strong>Send Sms on Order Cancel</strong></td>



	  <td align="left">

      <div class="maindiv">

      <span <?php if($config['SmsGlobalr']== 0) echo "class='on'"; else echo "class='off'";?>  id="sr1"  onclick="toggle4('sr1', 'sr2', 'SmsGlobalr');" ><span><?php echo $lang['admin_common']['no'];?></span></span>

        <span <?php if($config['SmsGlobalr']== 1) echo "class='on'"; else echo "class='off'"; ?>  id="sr2"  onclick="toggle2('sr1', 'sr2', 'SmsGlobalr');" ><span><?php echo $lang['admin_common']['yes'];?></span></span>

       <input type="hidden" name="config[SmsGlobalr]" value="<?php echo $config['SmsGlobalr']; ?>" id="SmsGlobalr"/>

         <span class="sm left">

        </span>

       </div>

 </td>



    </tr>

    <tr>



	  <td class="tdText"><strong>Title</strong></td>



	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

      <input type="text" size="25" class="textbox" name="config[smsglobaltitle]" value="<?php echo $config['smsglobaltitle']; ?>" /> 

		<span class="bgright"></span></div>

	     </td>



    </tr>

    <tr>

	  <td class="tdText" width="24%"><strong>Smsglobal User Name</strong></td>

	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

      <input type="text" size="25" class="textbox" name="config[smsglobaluser]" value="<?php echo $config['smsglobaluser']; ?>" /> 

	  <span class="bgright"></span></div>

         </td>

    </tr>

    <tr>



	  <td class="tdText"><strong>Smsglobal Password</strong></td>



	  <td align="left" class="tdText">

       <div class="inputbox">

      <span class="bgleft"></span>

      <input type="text" size="25" class="textbox" name="config[smsglobalpass]" value="<?php echo $config['smsglobalpass']; ?>" /> 

		<span class="bgright"></span></div>

	     </td>



    </tr>



    



    



    

    



    <tr>



<td  colspan=" 2"><div class="seprator2"></div>

	<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" /></td>



</tr>



    



    </table>

        </div>



    </div>



<div class="clear"></div>









<!--<p class="copyText">* <?php echo $lang['admin']['settings_ref_only'];?></p>-->

















































</div>

</form>