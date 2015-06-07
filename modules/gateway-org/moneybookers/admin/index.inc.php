<?php

if (!defined('CC_INI_SET')) die('Access Denied');
permission("settings","read",$halt=true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_GET['reset']) && $_GET['reset']){
	$db->misc("DELETE FROM `".$glob['dbprefix']."ImeiUnlock_Modules` WHERE `module` = 'gateway' AND `folder` = 'moneybookers'");
	$db->misc("DELETE FROM `".$glob['dbprefix']."ImeiUnlock_config` WHERE `name` = 'moneybookers'");
	$msg = "<p class='infoText'>The module settings have been reset.</p>";
}

if(!$_POST['module']['emailVerified'] && !$_POST['module']['quickbefore'] && $_POST['module']['quick']){
	echo "arse";
	$emailVerifiedFirst = true;
	$_POST['module']['quick'] = 0;
}

## Check configuration is valid for email address
if(isset($_POST['module']['emailVerified']) && $_POST['module']['emailVerified']==false){
	$mbURL = "https://www.moneybookers.com/app/email_check.pl?email=".trim($_POST['module']['email'])."&cust_id=6694063&password=".md5('truyuga4ah');
	$ch = curl_init($mbURL); 
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // uncomment this line if you get no gateway response. ###
	if($config['proxy']==1) {
	  	curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
	}
	$resp = curl_exec($ch); //execute post and get results
	$respParts = explode(",", $resp);
	if($respParts[0] == "OK") {
		//$accountEmail = true;
		$_POST['module']['emailVerified'] = 1;
	} else {
		//$accountEmail = false;
		$_POST['module']['emailVerified'] = 0;
	}
	curl_close ($ch);
}
if(isset($_POST['module']['secret']) && !$_POST['module']['secretVerified']) {	
	## Check secret word is correct
	if(!empty($_POST['module']['secret'])) {
		$md5hash = 	md5(md5(trim($_POST['module']['secret'])).md5('truyuga4ah'));
		$mbURL = "https://www.moneybookers.com/app/secret_word_check.pl?email=".trim($_POST['module']['email'])."&secret=".$md5hash."&cust_id=6694063";
		$ch = curl_init($mbURL); 
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // uncomment this line if you get no gateway response. ###
		if($config['proxy']==1) {
		  	curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
		}
		$resp = curl_exec($ch); //execute post and get results
		if(preg_match("/^OK/",$resp)) {
			$accountPass = true;
			$_POST['module']['secretVerified'] = 1;
		} else {
			$accountPass = false;
			$_POST['module']['secretVerified'] = 0;
		}
		curl_close ($ch);
	}
}

if(isset($_POST['module'])){
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	$cache = new cache("config.".$moduleName);
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	if(!$_POST['module']['cust_id'] || $_POST['module']['cust_id']==0){
		$_POST['module']['status'] = 0;
		$no_cust_id = true;
	}
	$newData = $_POST['module'];
	$newData['quick'] = ($emailVerifiedFirst==true) ? 0 : 1;
	$msg = writeDbConf($_POST['module'], $moduleName, $module);
	
}
$module = fetchDbConfig($moduleName);

if(!$_POST['module']['quickbefore'] && $_POST['module']['quick'] && $module['cust_id']>0){

	//Email to ecommerce@moneybookers.com

	$body = "Platform Name: ImeiUnlock ".$ini['ver']."\n";
	$body .= "Merchant Name: ".$ccAdminData['name']."\n";
	$body .= "Moneybookers Email Address: ".$module['email']."\n";
	$body .= "Moneybookers Customer ID: ".$module['cust_id']."\n";
	$body .= "URL of merchant's shop: ".$glob['storeURL']."\n";
	$body .= "Language: ".$config['defaultLang']."\n";
	
	require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
		
	$mail = new htmlMimeMail();
	$mail->setText($body);
	$mail->setFrom('mbactivate@cubecart.com');
	$mail->setReturnPath($config['masterEmail']);
	$mail->setSubject("Quick Checkout Request");
	$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
	$send = $mail->send(array('ecommerce@moneybookers.com'), $config['mailMethod']);
} 
?>
<p><a href="http://www.cubecart.com/moneybookers" target="_blank"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="" border="0" title="" /></a></p>
<p class="copyText">&quot;And money moves .&quot;</p>
<p>To have access to the international payment network of Moneybookers please <a href='https://www.moneybookers.com/app/register.pl' class="txtLink">register here</a> for a free account if you don’t have one yet.</p> More information can be found <a href='http://www.moneybookers.com/partners/cubecart/' class='txtLink'>here</a>.
<?php 
if (isset($msg)) echo stripslashes($msg);
?>
<?php
if($no_cust_id){
?>
<p class='warnText'>It appears that you haven't opened a Moneybookers account yet. Please register one <a href='https://www.moneybookers.com/app/register.pl' class='txtLink'>here</a>. This module cannot be enabled until your moneybookers email address has been verified.</p>
<?php
}
?>
<?php
if(isset($accountPass) && $accountPass) {
?>
<p class='infoText'>You are now ready to use all direct payment options of Moneybookers – please set the module status on enabled.</p>
<?php	
} elseif(isset($accountPass) && !$accountPass){
?>
<p class='warnText'>Your moneybookers secret word could not be verified. Please choose a secret word in the Merchant Tools section of your Moneybookers account and submit it in the section below.</p>
<?php
}
if(isset($emailVerifiedFirst) && $emailVerifiedFirst){
?>
<p class='warnText'>Quick Checkout cannot be activated until your email address has been verified.</p>
<?php
}
?>
<?php
if($send) {
?>
<p class='infoText'>You have sent a request for activation on the <?php echo date('d/m/Y');?>. Please be aware that the verification process to use Moneybookers Quick Checkout could take up to 72 hours.  You will be contacted by Moneybookers when the verification process has been completed.<br /><br />
After activation, Moneybookers will give you access to a new section in your Moneybookers
Account called Merchant Tools. Please choose a secret word (NOT the same as your password) there and also enter it in the section below to connect to Moneybookers. The secret word is the last step of your activation process and encrypts your payments securely. After successful submission you are ready to use all direct payment options of Moneybookers.
<br /><br />
Attention: The secret word should be different from your password.  Please make sure you enter your secret word in the Merchant Tools section of your Moneybookers account first.</p>
<?php
}
?>
<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td width="30%" align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>
	</td>
  </tr>
   <tr>
  	<td width="30%" align="left" class="tdText"><strong>Description:</strong>	</td>
    <td class="tdText"><input type="text" name="module[desc]" value="<?php if(empty($module['desc'])) { echo "Pay by Credit / Debit Card"; } else { echo $module['desc']; } ?>" class="textbox" size="30" /></td>
  </tr>
  
  <tr>
  <td width="30%" align="left" class="tdText"><strong>Email Address:</strong></td>
    <td class="tdText"><input type="text" name="module[email]" value="<?php echo $module['email']; ?>" class="textbox" size="30" /> 
    </td>
  </tr>
  <?php if(empty($module['logoURL'])) { $logo_path = $GLOBALS['storeURL']."/images/getLogo.php?skin=".$config['skinDir']; } else { $logo_path =  $module['logoURL']; } ?>
   <tr>
  <td width="30%" align="left" class="tdText"><strong>Logo URL:</strong> (Displayed on the Moneybookers Payment Page)<br />
  <img src='<?php echo $logo_path; ?>' />
  </td>
      <td class="tdText">
		<input type="text" name="module[logoURL]" value="<?php echo $logo_path; ?>" class="textbox" style='width: 90%' />
	</td>
  </tr>

  <tr>
  <td width="30%" align="left" class="tdText"><strong>Default:</strong></td>
      <td class="tdText">
	<select name="module[default]">
		<option value="1" <?php if($module['default'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['default'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>
	</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="tdText">&nbsp;</td>
    <td class="tdText">
    <input type='hidden' id='quick' value='<?php echo $module['quick']; ?>' name="module[quick]" />
    <input type='hidden' value='<?php echo $module['quick']; ?>' name="module[quickbefore]" />
    <input type='hidden' value='<?php echo $module['emailVerified']; ?>' name="module[emailVerified]" />
    <input type='hidden' value='<?php echo $module['secretVerified']; ?>' name="module[secretVerified]" />

    <input type="hidden" name="module[cust_id]" value="<?php if(isset($respParts[1]) && $respParts[1]>0) { echo $respParts[1]; } else { echo $module['cust_id']; } ?>" />
    </td>
  </tr>
<tr>
    <td colspan="2" class="tdTitle"><strong>Activate Quick Checkout</strong></td>
  </tr>
  <tr>
  <?php
  if($module['quick']==false) {
  ?>
    <td class="tdText" colspan='2'><div style="float: right; height: 100px; width: 300px; text-align: center; padding-top: 20px;"><input type='button' onclick='findObj("quick").value=1; this.form.submit();' value='Activate' class='submit' /></div>Moneybookers Quick Checkout enables you to take payments from credit cards, debit cards and over 50 other local payment options in over 200 countries. The highly competitive rates for this service are published on the Moneybookers website at <a href='http://www.moneybookers.com/app/help.pl?s=m_fees' class="txtLink">www.moneybookers.com</a>
<p>More information can be found <a href='http://www.moneybookers.com/app/help.pl?s=terms' class="txtLink">here</a>.</p>
    
    </td>
    
    <?php
} else {
?>
<td colspan='2'>Quick Checkout has been activated. If you have not had confirmation from Moneybookers within 72 hours please contact their sales department.</td>
<?php
}
?>
  </tr>
  <?php
  if($module['quick']) {
  ?>
  <tr>
  <td align="left" class="tdText"><strong>Secret Word:</strong></td>
    <td class="tdText"><input type="text" name="module[secret]" value="<?php echo $module['secret']; ?>" class="textbox" size="30" /> 
    </td>
</tr>

<tr>
  <td align="left" class="tdText" colspan="2">Attention: The secret word should be different from your Moneybookers login password.  Please make sure you enter your secret word in the Merchant Tools section of your Moneybookers account first.</td>
  </tr>
  
  <?php
  }
  ?>
  <tr>
  <td>&nbsp;
  
  </td>
  <td><input type="submit" class="submit" value="Edit Config" /> <input type="button" onclick='location.href="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>&amp;reset=1"' class="submit" value="Reset Module" /></td>
  </tr>
</table>

</form>
