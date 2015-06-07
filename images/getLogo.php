<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	getLogo.php
|   ========================================
|	Get Custom Logo if there is one	
+--------------------------------------------------------------------------
*/

define('CC_DS', DIRECTORY_SEPARATOR);

if (isset($_GET['skin']) && !empty($_GET['skin'])) {
	
	$custom		= 'logos'.CC_DS.str_replace(array('/', '\\'), '', $_GET['skin']);
	$default	= '..'.CC_DS.'skins'.CC_DS.$_GET['skin'].CC_DS.'styleImages'.CC_DS.'logo'.CC_DS.'default.gif';
	
	if (!empty($_GET['skin']) && file_exists($custom)) {
		$filename	= $custom;
	} else if (file_exists($default)) {
		$filename	= $default;
	}
	
	if (isset($filename)) {
		$file = getimagesize($filename);
		switch ($file[2]) {
			case 1:
				$mime = 'gif';
				break;
			case 2:
				$mime = 'jpeg';
				break;
			case 3:
				$mime = 'png';
				break;
			default:
				exit;
		}
		header('Content-Disposition: inline; filename="logo.'.$mime.'"');
		header('Content-Type: image/'.$mime);
		header('Content-Length: '.filesize($filename));
		echo file_get_contents($filename);
	}
}
?>