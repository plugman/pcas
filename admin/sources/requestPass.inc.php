<?php
/*
+--------------------------------------------------------------------------
|	requestPass.inc.php
|   ========================================
|	Request Admin Password
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

if (isset($_POST['email'])){
	
	$query = sprintf("SELECT `adminId`, `username`, `name` FROM ".$glob['dbprefix']."ImeiUnlock_admin_users WHERE `email` = %s", $db->mySQLSafe($_POST['email']));
 
	$result = $db->select($query);
	
	
	if($result == true) {
	
		$newPass = randomPass();
		$salt = randomPass(6);
		$data['salt'] = $db->mySQLSafe($salt);
		$data['password'] = $db->mySQLSafe(md5(md5($salt).md5($newPass)));
		$update = $db->update($glob['dbprefix']."ImeiUnlock_admin_users",$data,"adminId=".$result[0]['adminId']);
		
		// make email
		require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
		
		$mail = new htmlMimeMail();
        
		$lang = getLang("email.inc.php");
		
			$macroArray = array(
				"RECIP_NAME" => $result[0]['name'],
				"USERNAME" => $result[0]['username'],
				"PASSWORD" => $newPass,
				"STORE_URL" => $GLOBALS['storeURL'],
				"SENDER_IP" => get_ip_address()
			);
		
		$text = macroSub($lang['email']['admin_reset_pass_body'],$macroArray);
		unset($macroArray);
		
		$mail->setText($text);
		$mail->setReturnPath($_POST['email']);
		$mail->setFrom('ImeiUnlock Mailer <'.$config['masterEmail'].'>');
		$mail->setSubject($lang['email']['admin_reset_pass_subject']);
		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		$result = $mail->send(array($_POST['email']), $config['mailMethod']);
		
		httpredir($glob['adminFile']."?_g=login&email=".urlencode($_POST['email']));
		
	} else {
		$msg = "<p class='warnText'>".$lang['admin_common']['other_pass_reset_failed']."</p>";
	}

}
 require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
if(isset($msg))
{ 
	echo msg($msg); 
}
else
{
// paragraph just so the display sits better
?>
<p>&nbsp;</p>
<?php
}
?>

<form action="<?php echo $GLOBALS['rootRel']; ?><?php echo $glob['adminFile']; ?>?_g=requestPass" method="post" enctype="multipart/form-data" name="login" target="_self">
<div class="loginbox">
<div class="toplogin">
	<?php echo $lang['admin_common']['other_enter_email_below'];?>
</div>
<table border="0" align="center" width="822" cellpadding="0" cellspacing="0" class="mainTable2">
 
  <tr>
    <td  colspan="2">
	<div class="inner">
    <div class="inner2">
    	<span class="txt18 justfill">Just fill in your email and we'll help you
reset your password.</span>
    </div>
    <div class="inner2" style="padding-left:20px;">
	<span class="txt14 label txt-grey"><?php echo $lang['admin_common']['other_email_address'];?></span><br />
    <input name="email" type="text" id="email" class="textbox" />
    </div>
    </div>
    </td>
  </tr>
  <tr>
    <td>
    	<a href="<?php echo  $glob['adminFile']; ?>?_g=login" class="forget"><?php echo  $lang['admin_common']['other_back_login'];?></a>
    </td>
    <td><input name="login" type="submit" class="submit2" id="login" value="<?php echo $lang['admin_common']['other_send_pass'];?>" /></td>
  </tr>
</table>
</div>
</form>
