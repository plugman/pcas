<?php
if (!defined('CC_INI_SET')) die("Access Denied");
session_start();

$_SESSION['token']			= $_REQUEST['token'];
$_SESSION['payer_id'] 		= $_REQUEST['PayerID'];
$_SESSION['paymentAmount']	= $_REQUEST['paymentAmount'];
$_SESSION['currCodeType']	= $_REQUEST['currencyCodeType'];
$_SESSION['paymentType']	= $_REQUEST['paymentType'];

$resArray = $_SESSION['reshash'];

if(!isset($resArray["SHIPTOSTATE"])) {
	session_unset();
	header("Location: index.php");
	exit;
}

$customer = $db->select("SELECT `customer_id` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `email` = ".$db->MySQLSafe($resArray['EMAIL']));

$countryId = getCountryFormat($resArray["SHIPTOCOUNTRYCODE"],"iso","id");

$state = $db->select("SELECT `name` FROM ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE `abbrev` = ".$db->MySQLSafe($resArray["SHIPTOSTATE"])." AND `countryId` = ".$countryId);

if($state==true) {
	$stateName = $state[0]['name'];
} else {
	$stateName = $resArray["SHIPTOSTATE"];
}

$customerData['title']		= $db->MySQLSafe("");
$customerData['firstName'] 	= $db->MySQLSafe($resArray["FIRSTNAME"]);
$customerData['lastName']	= $db->MySQLSafe($resArray["LASTNAME"]);
$customerData['add_1'] 		= $db->MySQLSafe($resArray["SHIPTOSTREET"]);
$customerData['add_2'] 		= $db->MySQLSafe($resArray["SHIPTOSTREET2"]);
$customerData['town'] 		= $db->MySQLSafe($resArray["SHIPTOCITY"]);
$customerData['county']		= $db->MySQLSafe($stateName);
$customerData['postcode']	= $db->MySQLSafe($resArray["SHIPTOZIP"]);
$customerData['country']	= $db->MySQLSafe($countryId);


if($customer==true) {
	$db->update($glob['dbprefix']."ImeiUnlock_customer",$customerData,"`email` = ".$db->MySQLSafe($resArray['EMAIL']));
	$customer_id = $customer[0]['customer_id'];
} else {

	// added insert data
	$password 					= randomPass(6);
	$salt 						= randomPass(6);
	$customerData['salt'] 		= $db->MySQLSafe($salt);
	$customerData['password'] 	= $db->MySQLSafe(md5(md5($salt).md5($password)));
	$customerData['email']		= $db->MySQLSafe($resArray['EMAIL']);
	$customerData['regTime']	= $db->MySQLSafe(time());
	$customerData['ipAddress']	= $db->MySQLSafe(get_ip_address()); // this will be googles IP :-/ // no it won't you idiot it will be PayPals!!
	// ghost membership
	$customerData['type'] 		= $db->MySQLSafe(2);
	
	$db->insert($glob['dbprefix']."ImeiUnlock_customer", $customerData);
	$customer_id = $db->insertid();
	
	// send welcome email
	if($module['welcomeEmail'] == 1) {
		$emailLang = getLang("email.inc.php");
		
		include(CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
		$lang = getLang("email.inc.php");
				$mail = new htmlMimeMail();
				
		$macroArray = array(
			"CUSTOMER_NAME" => sanitizeVar($resArray["FIRSTNAME"])." ".sanitizeVar($resArray["LASTNAME"]),
			"EMAIL"			=> sanitizeVar($resArray['EMAIL']),
			##"PASSWORD"	=> sanitizeVar((isset($randomPass)) ? $randomPass : $_POST['password']),
			"PASSWORD"		=> $password,
			"STORE_URL"		=> $GLOBALS['storeURL'],
			"SENDER_IP"		=> get_ip_address()
		);
		
		$text = macroSub($lang['email']['new_reg_body'],$macroArray);
		unset($macroArray);
		
		$mail->setText($text);
		$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
		$mail->setReturnPath($config['masterEmail']);
		$mail->setSubject($lang['email']['new_reg_subject']);
		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		$mail->send(array(sanitizeVar($resArray['EMAIL'])), $config['mailMethod']);
	}

}
## Add Customer ID to session data
$data['customer_id'] = $customer_id;
$update = $db->update($glob['dbprefix']."ImeiUnlock_sessions", $data,"sessId=".$db->mySQLSafe($_COOKIE[CC_SESSION_NAME]));
header("Location: index.php?_g=co&_a=step2");
exit;
?>