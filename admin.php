<?php
/*
+--------------------------------------------------------------------------|	admin.php
|   ========================================
|	Selects which encoding method to use
+--------------------------------------------------------------------------
*/

require_once 'ini.inc.php';
require_once 'includes'.CC_DS.'global.inc.php';
require_once 'includes'.CC_DS.'functions.inc.php';

## If you are behind a proxy, please configure the fields below
## Examples below are for GoDaddy hosting
$glob['proxyEnable']= false;
$glob['proxyHost']	= ''; // e.g. proxy.shr.secureserver.net
$glob['proxyPort']	= ''; // e.g. 3128
$glob['proxyUser']	= ''; // leave this empty for godaddy
$glob['proxyPass']	= ''; // leave this empty for godaddy

## Load the decoded file
	require_once 'admin_enc.php';
?>