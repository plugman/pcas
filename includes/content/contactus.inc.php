<?php
/*
+--------------------------------------------------------------------------
|	contactus.inc.php
|   ========================================
|	Displays a site contact us page	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) {
    die("Access Denied");
}

// include lang file
$lang = getLang("includes" . CC_DS . "content" . CC_DS . "contactus.inc.php");


$_GET['docId'] = sanitizeVar($_GET['docId']);
$contact_us = new XTemplate("content" . CC_DS . "contactus.tpl");

    $result = $db->select("SELECT doc_name, doc_content,doc_metatitle,doc_metadesc,doc_metakeywords FROM " . $glob['dbprefix'] . "ImeiUnlock_docs WHERE doc_id = " . $db->mySQLSafe($_GET['docId']));
	if (isset($result)) {
    $contact_us->assign("DOC_NAME", validHTML($result[0]['doc_name']));
    $contact_us->assign("DOC_CONTENT", (!get_magic_quotes_gpc()) ? stripslashes($result[0]['doc_content']) : $result[0]['doc_content']);
 if ($config['seftags']) {
        $meta['siteTitle'] = $result[0]['doc_name'];
        $meta['metaDescription'] = substr(strip_tags($result[0]['doc_content']), 0, 35);
        $meta['sefSiteTitle'] = $result[0]['doc_metatitle'];
        $meta['sefSiteDesc'] = $result[0]['doc_metadesc'];
        $meta['sefSiteKeywords'] = $result[0]['doc_metakeywords'];
    } else {
        $meta['siteTitle'] = $config['siteTitle'] . " - " . $result[0]['doc_name'];
        $meta['metaDescription'] = substr(strip_tags($result[0]['doc_content']), 0, 35);
    }
} else {
    $contact_us->assign("DOC_NAME", $lang['contactus']['error']);
    $contact_us->assign("DOC_CONTENT", $lang['contactus']['does_not_exist']);
}

$contact_us->assign("E_MAIL", $config['masterEmail']);
$contact_us->assign("STORE_CONTACT", $config['storeContact']);
$contact_us->parse("contact_us.form.view_doc");

if ($_POST) {
    $contact_us->assign("VAL_NAME", $_POST['name']);
    $contact_us->assign("VAL_EMAIL", $_POST['email']);
    $contact_us->assign("VAL_PHONE", $_POST['phone']);
    $contact_us->assign("VAL_COMPANY", $_POST['company']);
    $contact_us->assign("VAL_COMMENTS", $_POST['msg']);

    // start validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['msg'])) {
        $errorMsg = $lang['contactus']['fill_required'];

    } elseif (validateEmail($_POST['email']) == false) {

        $errorMsg = $lang['contactus']['enter_valid_email'];
    } elseif (!ereg("[0-9]", $_POST['phone']) && $_POST['phone'] != "") {

        $errorMsg = $lang['contactus']['enter_valid_tel'];

    } else {
        // send email

        $contact_us->assign("VAL_NAME", "");
        $contact_us->assign("VAL_EMAIL", "");
        $contact_us->assign("VAL_PHONE", "");
        $contact_us->assign("VAL_COMPANY", "");
        $contact_us->assign("VAL_COMMENTS", "");

        if ($errorMsg != "") {
            $contact_us->assign("VAL_ERROR", $errorMsg);
            $contact_us->parse("contact_us.error");
        }

        require ("classes" . CC_DS . "htmlMimeMail" . CC_DS . "htmlMimeMail.php");

        $lang = getLang("email.inc.php");
	
        $mail = new htmlMimeMail();
        $macroArray = array(
		"RECIP_NAME" => "Administrator", 
		"EMAIL" => $_POST['email'], 
		"NAME" => $_POST['name'], 
		"PHONE" => $_POST['phone'], 
		"COMPANY" => $_POST['company'], 
		"COMMENTS" => $_POST['msg'], 
		"STORE_URL" => $GLOBALS['storeURL'] . "/index.php?_a=contactus&docId=2", 
		"SENDER_IP" => get_ip_address());
        $text = macroSub($lang['email']['contact_us_body'], $macroArray);
        unset($macroArray);

     	$mail->setText($text);
        $mail->setReturnPath($_POST['email']);
        $mail->setFrom($_POST['name'] . ' <' . $_POST['email'] . '>');
        $mail->setSubject($config['masterName']." ".$lang['email']['contact_us_subject']);
        $mail->setHeader('X-Mailer', 'Mailer');
        $send = $mail->send(array($config['masterEmail']), $config['mailMethod']);
        $mailSent = true;

    }
}

if ($mailSent == true) {
    $contact_us->assign("MAIL_SENT", sprintf($lang['contactus']['mailsent'], $_POST['email']));
    $contact_us->assign("CONTACT_US_STATUS", $lang['contactus']['mailsent']);
	$contact_us->parse("contact_us.mail_sent");
} else {

    $contact_us->assign("CONTACT_US_STATUS",$lang['contactus']['fill_required']);
    $contact_us->assign("LANG_EMAIL",$lang['contactus']['email']);

    if ($errorMsg != "") {
        $contact_us->assign("VAL_ERROR", $errorMsg);
        $contact_us->parse("contact_us.error");
    }
}

$contact_us->assign("LANG_NAME", $lang['contactus']['name']);
$contact_us->assign("LANG_EMAIL", $lang['contactus']['email_address']);
$contact_us->assign("LANG_PHONE", $lang['contactus']['phone']);
$contact_us->assign("LANG_COMPANY", $lang['contactus']['company_Name']);
$contact_us->assign("LANG_COMMENTS", $lang['contactus']['comments']);
$contact_us->assign("TXT_SUBMIT", $lang['contactus']['send_pass']);

$contact_us->parse("contact_us.temp");
$contact_us->parse("contact_us.form");
if($config['Latitude'] && $config['Longitude']){
	$contact_us->assign("VAL_LATITUDE", $config['Latitude']);
	$contact_us->assign("VAL_LONGITUDE", $config['Longitude']);
	$contact_us->assign("VAL_TIT", trim(preg_replace('/\s\s+/', '<br>', $config['adtit'])));
	$contact_us->assign("VAL_LATITUDE2", $config['Latitude2']);
	$contact_us->assign("VAL_LONGITUDE2", $config['Longitude2']);
	$contact_us->assign("VAL_TIT2", trim(preg_replace('/\s\s+/', '<br>', $config['adtit2'])));
	 $contact_us->parse("contact_us.map_true");
}
$contact_us->parse("contact_us");
$page_content = $contact_us->text("contact_us");
?>