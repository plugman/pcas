<?php
if(!empty($_POST['designid']) && !empty($_POST['email'])) {
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");
require_once ("../classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
$db = new db();
$config = fetchDbConfig("config");
				$mail = new htmlMimeMail();
				
				
				
				$text = "A social design service where you can design your own iPhone 5s case using your own Instagram & Facebook photos.

All the best,

The Caseprint Team";
				
				$mail->setText($text);
				$attachment = $mail->getFile($_POST['image']);
				$mail->addAttachment($attachment, $_POST['designname'].'.png');
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setReturnPath($config['masterEmail']);
				$mail->setSubject("Art Work Sharing");
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array(sanitizeVar($_POST['email'])), $config['mailMethod']);
				 echo "1";
}else{
	die("invalid path");
}
 $db->close();
?>    