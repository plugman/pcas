<?php
/*
+--------------------------------------------------------------------------
|	forgotPass.inc.php
|   ========================================
|	Password Reset Page	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."forgotPass.inc.php");

if(isset($_POST['submit'])){
		
	$query = "SELECT firstName, lastName FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `email` = ".$db->mySQLSafe($_POST['email'])." AND `type`>0";
	$result = $db->select($query);
	
	// start validation	
	if($config['floodControl']=="recaptcha") {
		$response = recaptcha_check_answer(	$ini['recaptcha_private_key'], 
											$_SERVER['REMOTE_ADDR'],
											$_POST['recaptcha_challenge_field'],
											$_POST['recaptcha_response_field']);
	} elseif($config['floodControl']==1) {
		$spamCode = fetchSpamCode($_POST['ESC'], true);
	}
	
	if ($result == false || empty($_POST['email'])) {
		$errorMsg = $lang['forgotPass']['email_not_found'];
	} elseif($config['floodControl']=="recaptcha" && !$response->is_valid) {
			$errorMsg = $lang['forgotPass']['error_code'];
	} else if ($config['floodControl']==1 && (!isset($_POST['spamcode']) || ($spamCode['SpamCode']!==strtoupper($_POST['spamcode'])) || (get_ip_address()!==$spamCode['userIp']))) {
		$errorMsg = $lang['forgotPass']['error_code'];
	} else {
		// update to new password
		$salt = randomPass(6);
		$newPass = randomPass();
		$data['salt'] = "'".$salt."'";
		$data['password'] = $db->mySQLSafe(md5(md5($salt).md5($newPass)));
		$where = "`email` = ".$db->mySQLSafe($_POST['email']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $data, $where);
		
		// send email
		require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
		
		$lang = getLang("email.inc.php");
		
		$mail = new htmlMimeMail();
		
		$macroArray = array(
			"RECIP_NAME"	=> $result[0]['firstName']." ".$result[0]['lastName'],
			"EMAIL"			=> $_POST['email'],
			"PASSWORD"		=> $newPass,
			"STORE_URL"		=> $GLOBALS['storeURL']."/index.php?_a=login",
			"SENDER_IP"		=> get_ip_address()
		);
		
		$text = macroSub($lang['email']['reset_password_body'],$macroArray);
		unset($macroArray);
		
		$mail->setText($text);
		$mail->setReturnPath($config['masterEmail']);
		$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
		$mail->setSubject($lang['email']['reset_password_subject']);
		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		$send = $mail->send(array($_POST['email']), $config['mailMethod']);
		$passSent = true;
	
	}

}

$forgot_pass = new XTemplate ("content".CC_DS."forgotPass.tpl");

$forgot_pass->assign("LANG_FORGOT_PASS_TITLE",$lang['forgotPass']['forgot_pass']);

if($passSent == true) {
	$forgot_pass->assign("FORGOT_PASS_STATUS", sprintf($lang['forgotPass']['new_pass_sent'],$_POST['email']));
} else {
	$forgot_pass->assign("FORGOT_PASS_STATUS",$lang['forgotPass']['enter_email']);
	
	$forgot_pass->assign("LANG_EMAIL",$lang['forgotPass']['email']);
	
	
	// Start Spam Bot Control
	if($config['floodControl']=="recaptcha") {
		$forgot_pass->assign("TXT_SPAMBOT", $lang['forgotPass']['spambot']);
		$recaptcha = custom_recaptcha_get_html($ini['recaptcha_public_key'],false,detectSSL());
		$forgot_pass->assign("RECAPTCHA", $recaptcha);
		$forgot_pass->parse("forgot_pass.form.recaptcha");
	} elseif($config['floodControl']) {
			
		$spamCode = strtoupper(randomPass(5));
		$ESC = createSpamCode($spamCode);
		$imgSpambot = imgSpambot($ESC);
		
		$forgot_pass->assign("VAL_ESC", $ESC);
		$forgot_pass->assign("TXT_SPAMBOT", $lang['forgotPass']['spambot']);
		$forgot_pass->assign("IMG_SPAMBOT", $imgSpambot);
		$forgot_pass->parse("forgot_pass.form.spambot");
	}
	
	$forgot_pass->assign("TXT_SUBMIT",$lang['forgotPass']['send_pass']);
	
	if(isset($errorMsg)){
				
		$forgot_pass->assign("VAL_ERROR",$errorMsg);
		$forgot_pass->parse("forgot_pass.error");
		
	}

$forgot_pass->parse("forgot_pass.form");

}

$forgot_pass->parse("forgot_pass");
$page_content = $forgot_pass->text("forgot_pass");
?>