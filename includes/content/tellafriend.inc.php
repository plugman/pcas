<?php
/*
+--------------------------------------------------------------------------
|	tellafriend.inc.php
|   ========================================
|	Tell a friend about a product	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."tellafriend.inc.php");

// query database
$_GET['productId'] = sanitizeVar($_GET['productId']) ;
$result = $db->select("SELECT name FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = ".$db->mySQLSafe($_GET['productId'])); 

if (LANG_FOLDER !== $config['defaultLang']) {
	$foreignVal = $db->select("SELECT name FROM ".$glob['dbprefix']."ImeiUnlock_inv_lang WHERE prod_master_id = ".$db->mySQLSafe($_GET['productId'])." AND prod_lang=".$db->mySQLSafe(LANG_FOLDER));
	if ($foreignVal == true) {
		$result[0]['name'] = $foreignVal[0]['name'];
	}
}

// send email if form is submit
if (isset($_POST['submit'])) {

	## Form Validation
	if (validateEmail($_POST['senderEmail']) && validateEmail($_POST['recipEmail'])) {
		## Are the names set
		if (empty($_POST['senderName']) || empty($_POST['recipName'])) {
			$errorMsg = $lang['tellafriend']['error_name'];
		}
		
		## valid emails, lets roll
		
		if($config['floodControl']=="recaptcha") {
		$response = recaptcha_check_answer(	$ini['recaptcha_private_key'], 
											$_SERVER['REMOTE_ADDR'],
											$_POST['recaptcha_challenge_field'],
											$_POST['recaptcha_response_field']);
		} elseif($config['floodControl']==1) {
			$spamCode = fetchSpamCode($_POST['ESC'], true);
		}
		
		if($config['floodControl']=="recaptcha" && !$response->is_valid) {
			$errorMsg = $lang['tellafriend']['error_code'];
		} elseif ($config['floodControl']==1) {
			if (!isset($_POST['spamcode']) || ($spamCode['SpamCode']!==strtoupper($_POST['spamcode'])) || (get_ip_address()!==$spamCode['userIp'])) {
				$errorMsg = $lang['tellafriend']['error_code'];
			}
		}
		
		if (!isset($errorMsg)) {
			## make email
			require 'classes'.CC_DS.'htmlMimeMail'.CC_DS.'htmlMimeMail.php';
			$lang = getLang("email.inc.php");
			$mail = new htmlMimeMail();
					
			$macroArray = array(
				'RECIP_NAME'	=> sanitizeVar($_POST['recipName']),
				'MESSAGE'		=> strip_tags(stripslashes(html_entity_decode($_POST['message']))),
				'PRODUCT_URL'	=> $GLOBALS['storeURL'].'/index.php?_a=viewProd&productId='.sanitizeVar($_GET['productId']),
				'STORE_URL'		=> $GLOBALS['storeURL'],
				'SENDER_IP'		=> $_SERVER['REMOTE_ADDR'],
				'SENDER_NAME'	=> sanitizeVar($_POST['senderName'])
			);
			
			$text = macroSub($lang['email']['tellafriend_body'], $macroArray);
			
			$mail->setText($text);
			$mail->setReturnPath($_POST['senderEmail']);
			$mail->setFrom($_POST['senderName'].' <'.$config['masterEmail'].'>');
			$mail->setSubject(macroSub($lang['email']['tellafriend_subject'], $macroArray));
			$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
			$mail->setHeader('Reply-To', $_POST['senderEmail']);
			$mail->setHeader('Return-Path',$config['masterEmail']);
			$mail->send(array($_POST['recipEmail']), $config['mailMethod']);
		}
	} else {
		$errorMsg = $lang['tellafriend']['error_email'];
	}
}

$tellafriend	= new XTemplate("content".CC_DS."tellafriend.tpl");


	$tellafriend->assign("PRODUCT_ID",sanitizeVar($_GET['productId']));
	
	$tellafriend->assign("TAF_TITLE",$lang['tellafriend']['tellafriend']);
	
	if (isset($_POST['submit']) && !isset($errorMsg)) {
		$tellafriend->assign("TAF_DESC", sprintf($lang['tellafriend']['message_sent'], $_POST['recipName'], $result[0]['name']));
	} else {
		if (isset($errorMsg)) {
			$tellafriend->assign("VAL_ERROR",$errorMsg);
			$tellafriend->parse("tellafriend.error");
			
			## set the friend's details again, so they can correct problems
			$tellafriend->assign('VAL_RECIP_EMAIL', sanitizeVar($_POST['recipEmail']));
			$tellafriend->assign('VAL_RECIP_NAME', sanitizeVar($_POST['recipName']));
		}
	
		$tellafriend->assign("TAF_DESC", sprintf($lang['tellafriend']['fill_out_below'], $result[0]['name']));
	
	}
	
	$tellafriend->assign("TXT_RECIP_NAME",$lang['tellafriend']['friends_name']);
	
	$tellafriend->assign("TXT_RECIP_EMAIL",$lang['tellafriend']['friends_email']);
	
	
	$tellafriend->assign("TXT_SENDER_NAME",$lang['tellafriend']['your_name']);
	
	if(isset($_POST['senderName'])){
		$tellafriend->assign("VAL_SENDER_NAME",sanitizeVar($_POST['senderName']));
	}
	
	$tellafriend->assign("TXT_SENDER_EMAIL",$lang['tellafriend']['your_email']);
	
	if(isset($_POST['senderName'])){
		$tellafriend->assign("VAL_SENDER_EMAIL",sanitizeVar($_POST['senderEmail']));
	}
	
	$tellafriend->assign("TXT_MESSAGE",$lang['tellafriend']['message']);
	
	if(isset($_POST['message'])){
		$tellafriend->assign("VAL_MESSAGE",stripslashes($_POST['message']));
	} else {
		$tellafriend->assign("VAL_MESSAGE",sprintf($lang['tellafriend']['default_message'],$result[0]['name']));
	}
	
	$tellafriend->assign("TXT_SUBMIT",$lang['tellafriend']['send']);
	
	// Start Spam Bot Control
	if($config['floodControl']=="recaptcha") {
		$tellafriend->assign("TXT_SPAMBOT", $lang['tellafriend']['spambot']);
		$recaptcha = custom_recaptcha_get_html($ini['recaptcha_public_key'],false,detectSSL());
		$tellafriend->assign("RECAPTCHA", $recaptcha);
		$tellafriend->parse("tellafriend.recaptcha");
	} elseif($config['floodControl']) {
		
		$spamCode = strtoupper(randomPass(5));
		$ESC = createSpamCode($spamCode);
		
		$imgSpambot = imgSpambot($ESC);
		
		$tellafriend->assign("VAL_ESC",$ESC);
		$tellafriend->assign("TXT_SPAMBOT",$lang['tellafriend']['spambot']);
		$tellafriend->assign("IMG_SPAMBOT",$imgSpambot);
		$tellafriend->parse("tellafriend.spambot");
	}

	
	
$tellafriend->parse("tellafriend");
$page_content = $tellafriend->text("tellafriend");
?>