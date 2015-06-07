<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure PayPal Express Checkout
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("settings","read",true);

if(isset($_POST['module'])){
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	$cache = new cache("config.".$moduleName);
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	$msg = writeDbConf($_POST['module'], $moduleName, $module);
	
}
$module = fetchDbConfig($moduleName);

$thisPage = currentPage(array("mode"=> 0));

if(!isset($_GET['mode']) && isset($module['mode']) && !empty($module['mode'])) {
	httpredir($thisPage."&mode=".$module['mode']);
} 
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p><a href="http://www.paypal.com/"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="" border="0" title="" /></a></p>
<?php 
if(isset($msg)){ 
	echo msg($msg); 
} 
?>
<p class="copyText">&quot;Get our most comprehensive solution to process credit cards directly on your site and also accept PayPal and bank account payments with Website Payments Pro.&quot;</p>

<p>Please choose your module mode: <select name="itdoesntneedonesoicalleditthis" onchange="jumpMenu('parent',this,0)">
		<?php if(!isset($_GET['mode'])) { ?><option value="<?php echo $thisPage; ?>" <?php if(!isset($_GET['mode'])) echo "selected='selected'"; ?>>-- Select --</option><?php } ?>
		<option value="<?php echo $thisPage; ?>&amp;mode=US" <?php if($_GET['mode']=="US") echo "selected='selected'"; ?>>Website Payments Pro</option>
		<option value="<?php echo $thisPage; ?>&amp;mode=UK" <?php if($_GET['mode']=="UK") echo "selected='selected'"; ?>>Website Payments Pro (PayFlow Edition)</option>
		<option value="<?php echo $thisPage; ?>&amp;mode=USECO" <?php if($_GET['mode']=="USECO") echo "selected='selected'"; ?>>Express Checkout Only</option>
		<option value="<?php echo $thisPage; ?>&amp;mode=USDPO" <?php if($_GET['mode']=="USDPO") echo "selected='selected'"; ?>>Direct Payment Only (US Only)</option>
    </select></p>

<?php 
$moduleGate = substr($_GET['mode'],0,2);

if(isset($_GET['mode']) && !empty($_GET['mode'])) { ?>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>&amp;mode=<?php echo $_GET['mode']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="800">
  <tr>
    <td colspan="2" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td width="33%" align="left" class="tdText"><strong>PayPal Pro Mode:</strong></td>
    <td class="tdText">
	<?php 
	if($_GET['mode']=="US") { 
		echo "Website Payments Pro"; 
	} elseif($_GET['mode']=="UK") { 
		echo "Website Payments Pro (PayFlow Edition)"; 
	} elseif($_GET['mode']=="USECO") { 
		echo "Express Checkout Only"; 
	} elseif($_GET['mode']=="USDPO") { 
		echo "Direct Payment Only (US Only)"; 
	} 	
	?>
	<input type="hidden" name="module[mode]" value="<?php echo $_GET['mode'];?>" />
	  </td>
  </tr>
  <tr>
    <td width="33%" align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText">
	<select name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>	</td>
  </tr>
  <tr>
    <td width="33%" align="left" class="tdText"><strong>Currency:</strong></td>
    <td class="tdText"><?php echo $config['defaultCurrency']; ?> (This can be edited under general settings)</td>
  </tr>
  <?php if($_GET['mode']!=="USECO") { ?>
  <tr>
    <td width="33%" align="left" class="tdText"><strong>Enable Card Validation:</strong></td>
    <td class="tdText"><select name="module[validation]">
      <option value="1" <?php if($module['validation']==1) echo "selected='selected'"; ?>>Enabled</option>
      <option value="0" <?php if($module['validation']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select></td>
  </tr>
  <?php } ?>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Gateway Server:</strong></td>
    <td class="tdText">
	<select name="module[gateway]">
		<option value="1" <?php if($module['gateway'] == 1) echo "selected='selected'"; ?>>Live</option>
		<option value="0" <?php if($module['gateway'] == 0) echo "selected='selected'"; ?>>Sandbox</option>
	</select>	</td>
  </tr>
  <?php
  if($moduleGate=="US") {
  ?>
  <tr>
  <td width="33%" align="left" class="tdText"><strong> API Username:</strong></td>
    <td class="tdText"><input type="text" name="module[username]" value="<?php echo $module['username']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>API Password:</strong></td>
    <td class="tdText"><input type="text" name="module[password]" value="<?php echo $module['password']; ?>" class="textbox" size="30" /></td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>API Signature:</strong></td>
    <td class="tdText">
	<input type="text" name="module[signature]" value="<?php echo $module['signature']; ?>" class="textbox" size="65" />	</td>
  </tr>
  <?php
  } else {
  ?>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Username:</strong><br />
If you set up one or more additional users on the account, 
this value is the ID of the user authorised to process 
transactions. If, however, you have not setup additional 
users on the account, username should be the same value as 
Merchant ID.</td>
    <td class="tdText"><input type="text" name="module[user]" value="<?php echo $module['user']; ?>" class="textbox" size="45" /></td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Password:</strong><br />
The 6 to 32-character password that you defined while 
registering for the account.</td>
    <td class="tdText"><input type="text" name="module[pass]" value="<?php echo $module['pass']; ?>" class="textbox" size="45" /></td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Merchant ID:</strong><br />
    Your merchant login ID that you created when you 
registered for the Website Payments Pro account. 

</td>
    <td class="tdText">
	<input type="text" name="module[vendor]" value="<?php echo $module['vendor']; ?>" class="textbox" size="45" />	</td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Partner:</strong><br />
The ID provided to you by the authorised PayPal Reseller 
who registered. If you 
purchased your account directly from PayPal, use 
PayPalUK.</td>
    <td class="tdText">
	<input type="text" name="module[partner]" value="<?php echo $module['partner']; ?>" class="textbox" size="45" />	</td>
  </tr>
  <?php
  } 
  ?>
  <tr>
    <td width="33%" align="left" valign="top" class="tdText"><strong>Require PayPal confirmed address:</strong><br />
(Express Checkout Only)</td>
    <td class="tdText"><select name="module[confAddress]">
		<option value="0" <?php if($module['confAddress'] == 0) echo "selected='selected'"; ?>>No</option>
		<option value="1" <?php if($module['confAddress'] == 1) echo "selected='selected'"; ?>>Yes</option>
		
	</select></td>
  </tr>
   <tr>
    <td width="33%" align="left" valign="top" class="tdText"><strong>Send Welcome Email:</strong><br />
(Contains user/password for future access)</td>
    <td class="tdText"><select name="module[welcomeEmail]">
		<option value="0" <?php if($module['welcomeEmail'] == 0) echo "selected='selected'"; ?>>No</option>
		<option value="1" <?php if($module['welcomeEmail'] == 1) echo "selected='selected'"; ?>>Yes</option>
		
	</select></td>
  </tr>
  <tr>
  <td width="33%" align="left" valign="top" class="tdText"><strong>Payment Acton:</strong></td>
    <td class="tdText">
	<select name="module[paymentAction]">
		<option value="Sale" <?php if($module['paymentAction'] == "Sale") echo "selected='selected'"; ?>>Sale</option>
		<option value="Authorization" <?php if($module['paymentAction'] == "Authorization") echo "selected='selected'"; ?>>Authorization</option>
		<?php
  if($moduleGate=="US") {
  ?>
		<option value="Order" <?php if($module['paymentAction'] == "Order") echo "selected='selected'"; ?>>Order (Express Checkout Only*)</option>
		<?php
		}
		?>
	</select>
	<br />
	- Sale indicates that this is a final sale for which you are requesting payment.<br />
	- Authorization <?php if($moduleGate=="US") { ?>or Order<?php } ?> indicates that this payment is subject to settlement with PayPal Authorization &amp; Capture.
	<?php if($moduleGate=="US") { ?><p>* If this is selected and a credit card payment is made it will force "Authorization" instead.</p><?php } ?></td>
  </tr>
  <tr>
  <td width="33%" align="left" class="tdText"><strong>Debug:</strong></td>
    <td class="tdText">
	<select name="module[debug]">
		<option value="1" <?php if($module['debug'] == 1) echo "selected='selected'"; ?>>Yes</option>
		<option value="0" <?php if($module['debug'] == 0) echo "selected='selected'"; ?>>No</option>
	</select>	</td>
  </tr>
  <tr>
    <td width="33%" align="right" class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
<br />
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="800">
  <tr>
    <td colspan="2" class="tdTitle">3D-Secure Settings </td>
  </tr>
  <tr>
	<td colspan="2" class="tdText">
	  For UK Merchants who wish to accept Maestro, 3D-Secure will be required from January 1st 2010.<br />
	  For more information on the 3D-Secure system, please visit the <a href="https://www.paypal-business.co.uk/3dsecure.asp" target="_blank" class="txtLink">PayPal Business website</a>,
	  where you will also be able to <a href="https://paypal3dsregistration.cardinalcommerce.com" target="_blank" class="txtLink">register</a> to obtain the required credentials.</td>
  </tr>
  <tr>
	<td width="33%" class="tdText"><strong>Status:</strong></td>
	<td class="tdText">
	  <select name="module[3ds_status]">
		<option value="1" <?php if($module['3ds_status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['3ds_status']==0) echo "selected='selected'"; ?>>Disabled</option>
	  </select>
	</td>
  </tr>
  <tr>
	<td width="33%"class="tdText"><strong>Merchant ID:</strong></td>
	<td class="tdText"><input type="text" name="module[3ds_merchant]" value="<?php echo $module['3ds_merchant']; ?>" class="textbox" size="45" /></td>
  </tr>
  <tr>
	<td width="33%" class="tdText"><strong>Transaction Password:</strong></td>
	<td class="tdText"><input type="password" name="module[3ds_password]" value="<?php echo $module['3ds_password']; ?>" class="textbox" size="45" /></td>
  </tr>
  <tr>
	<td width="33%" class="tdText">&nbsp;</td>
	<td class="tdText"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
  <tr>
	<td colspan="2" class="tdText">	For support with 3D Secure, please contact <a href="mailto:implement@cardinalcommerce.com" class="txtLink">implement@cardinalcommerce.com</a>.
</td>
  </tr>
</table>
</form>

<?php 
}
?>
<?php
if($moduleGate=="US") { ?>
<p class="pageTitle">How to add PayPal Merchant Referral Banner</p>
The Merchant Referral Bonus Program rewards you for bringing new businesses to PayPal.<br /><br />
1. Login to your PayPal account at <a href="http://www.paypal.com" target="_blank" class="txtLink">http://www.paypal.com</a><br />
2. Click on the &quot;Referrals&quot; link.<br />
3. You can then see HTML code which can be cut and paste into the stores HTML template files.<br />
<br />
The HTML template files can be found and edited using an <a href="http://en.wikipedia.org/wiki/FTP_Client" target="_blank" class="txtLink">FTP</a> Client to access the website files:<br />
<br />
<span style="font-family:'Courier New', Courier, monospace;">/skins/<?php echo $config['skinDir'];?>/styleTemplates/global/index.tpl</span>
<?php
}
?>
</li>
</ol>
