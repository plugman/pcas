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
|	functions.inc.php
|   ========================================
|	Installer Functions
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
function keySearch($find, $array, $keyname = null) {
	foreach ($array as $key => $arrayVal) {
		if (is_array($arrayVal)) {
			$result = keySearch($find, $arrayVal, $key);
			if ($result != false) return $result;
		} else {
			if (strtolower($arrayVal) == strtolower($find)) {
				return (!empty($keyname)) ? $keyname : $key;
			}
		}
	}
	return false;
}

?>