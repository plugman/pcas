<?php
/*
+--------------------------------------------------------------------------
|	ini.inc.php
|   ========================================
|	Initialization at start of script	
+--------------------------------------------------------------------------
*/

if (version_compare(PHP_VERSION, '5.1.0', '<')) {
	die('You need to upgrade to PHP Version 5.1.0 or better to use ImeiUnlock. You are currently running PHP Version '.PHP_VERSION);
}

## Version Number
$ini['ver'] = 	'1.0.0';

## reCaptcha Key - Change this if you want to use your own!
$ini['recaptcha_public_key'] = '6Lfc8wMAAAAAAJXKhHHvwZ136W94Z4RKSPzkOE-G';
$ini['recaptcha_private_key'] = '6Lfc8wMAAAAAAKSg9U_vG4Jccll34dE-8qbuIfTP';

## Brute Force Protection
$ini['bfattempts'] 	= 	5;						## Allowed number of login attempts
$ini['bftime'] 		= 	600; 					## Number of seconds to prevent login for

define('CC_SESSION_NAME', 'ccUser'); 			## Default session name is ccUser, this can be changed
define('CC_ADMIN_SESSION_NAME', 'ccAdmin'); 	## Default admin session name is ccAdmin, this can be changed
define('PHP51_MODE', (bool)version_compare(PHP_VERSION, '5.2.0', '<'));

## Pages which need to run under SSL if enabled/forced
$sslPages = array(
	"unsubscribe"	=> true,
	"login" 		=> true,
	"logout" 		=> true,
	"forgotPass"	=> true,
	"account"		=> true,
	"profile"		=> true,
	"changePass"	=> true,
	"newsletter"	=> true,
	"cart"			=> true,
	"step1"			=> true,
	"step2"			=> true,
	"step3"			=> true,
	"reg"			=> true,
	"viewOrders"	=> true,
	"viewOrder"		=> true,
	"confirmed"		=> true,
	"verifyGD"		=> true
);

## Stop includes, etc from being executed outside of the main application
define('CC_INI_SET', NULL);

## Define a few environmental variables
define('CC_DS', DIRECTORY_SEPARATOR);
define('CC_PS', PATH_SEPARATOR);			# Is this really needed anymore? Left for compatibility, in case any 3rd party mods use it
define('CC_ROOT_DIR', dirname(__FILE__));

## Define the order statuses as constants
define('ORDER_PENDING',		1);
define('ORDER_PROCESS',		2);
define('ORDER_COMPLETE',	3);
define('ORDER_DECLINED',	4);
define('ORDER_FAILED',		5);
define('ORDER_CANCELLED',	6);

## List here all the paths you wish to allow _p to be
$allowed_modules = array(
	'modules/gateway/Print_Order_Form/orderForm.inc.php',
	'images/random/verifyGD.inc.php',
	'images/random/verifySTD.inc.php',
);

## Enable Script Profiling
#if (extension_loaded('APD')) apd_set_pprof_trace('cache');
#if (extension_loaded('XDebug')) xdebug_start_trace('test', 4);

## Set error reporting to all but notices
error_reporting(E_ALL ^ E_NOTICE);
## display errors
ini_set('display_errors', true);
## Disable 'Register Globals' for security
ini_set('register_globals', false);
## Disable '<?' style php short tags for xml happiness
ini_set('short_open_tag', false);
## Set argument separator to &amp; from & for XHTML validity
ini_set('arg_separator.output', '&amp;');
## Automatically detect line endings
ini_set('auto_detect_line_endings', true);
## turn off magic quotes if on
ini_set('magic_quotes_gpc', false);

@set_magic_quotes_runtime(false); // depreciated in PHP 5.3

## NEW - Let's enable page compression by default, if output_buffering is not enabled
if (!ini_get('output_buffering')) {
	ini_set('zlib.output_compression', true);
	ini_set('zlib.output_compression_level', 5);
}

## Windows/IIS can be a pain in CGI mode - this tries to alleviate our suffering...
if (stristr(PHP_OS, 'WIN') && stristr($_SERVER['SERVER_SOFTWARE'], 'IIS')) {
    switch (strtolower(PHP_SAPI)) {
        case 'cgi-fcgi':
            ini_set('fastcgi.impersonate', true);
            break;
        case 'cgi':
            ini_set('cgi.rfc2616_headers', true);    ## Set RFC2616 compliant headers for Windows servers running in CGI mode
            ini_set('cgi.force_redirect', false);    ## Disable force redirect 
            break;
    }
}
## default encoding UTF-8
ini_set('default_charset','UTF-8');

//date_default_timezone_set('UTC');

/************* START INITIAL SECURITY CHECKS *************/

## Check for possible global overwrite and end script execution if detected
/* OLD UNSET FUNCTION
function unset_globals() {
	if (ini_get('register_globals')) {
		if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
			$die = "<h1 style='font-family: Arial, Helvetica, sans-serif; color: red;'>Security Warning</h1><p style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>\nGLOBALS overwrite attempt detected! Script execution has been terminated.</p>\n";
			die($die);
		}
		
		## Variables that shouldn't be unset
		$skip = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
		foreach ($input as $key => $value) {
			if (!in_array($key, $skip) && isset($GLOBALS[$key])) {
				unset($GLOBALS[$key]);
			}
		}
	}
}
END OLD UNSET FUNCTION */

/* BEGIN NEW UNSET FUNCTION thanks Technocrat  */
function unset_globals() {
    //If ini_get isn't
    if (!function_exists('ini_get') || @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') {
        if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
            $die = "<h1 style='font-family: Arial, Helvetica, sans-serif; color: red;'>Security Warning</h1><p style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>\nGLOBALS overwrite attempt detected! Script execution has been terminated.\n";
            die($die);
        }
        
        ## Variables that shouldn't be unset
        $skip = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        $input = array_merge(array_keys($_GET), array_keys($_POST), array_keys($_COOKIE), array_keys($_SERVER), array_keys($_ENV), array_keys($_FILES), isset($_SESSION) && is_array($_SESSION) ? array_keys($_SESSION) : array());
        foreach ($input as $key) {
            if (isset($GLOBALS[$key]) && !in_array($key, $skip)) {
                unset($GLOBALS[$key]);
            }
        }
    }
}
/* END NEW UNSET FUNCTION thanks Technocrat  */

## Run the function
unset_globals();


function has_zend_optimizer() {
	global $encoder;
	# Detect Zend Optimizer
	ob_start();
	phpinfo(INFO_GENERAL);
	$info = ob_get_contents();
	ob_end_clean();
	
	$info = str_replace('&nbsp;', ' ', $info);
	if (stristr($info, 'Zend Optimizer')) {
		$encoder = true;
		return true;
	}
	return false;
} 

function has_ioncube_loader() {
	# Detect ionCube
	return extension_loaded('ionCube Loader');
}

class clean_data {
	
	public function clean_data(&$data) {
	 	/* Begin optimization by Technocrat */
	 	if (empty($data)) {
            return true;
        }
        /* End optimization by Technocrat */
		## keys to skip
		$skipKeys = array('FCKeditor');
		if (isset($_GET['_g']) && urldecode($_GET['_g']) == 'filemanager/language') {
			$skipKeys[] = 'custom';
		}
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				if (preg_match('#([^a-z0-9\-\_\:\@\|])#i', urldecode($key))) {
					$die = "<h1 style='font-family: Arial, Helvetica, sans-serif; color: red;'>Security Warning</h1><p style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>\nParsed array keys can not contain illegal characters! Script execution has been halted.</p><p style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>It may be possible to fix this error by deleting your browsers cookies and refresh this page.</p>\n";
					die($die);
				}
				## Multi dimentional arrays.. dig deeper.
				if (is_array($val) && !in_array($key, $skipKeys)) {
					$this->clean_data($data[$key]);
				} else if (!empty($val) && !in_array($key, $skipKeys)) {
					$data[$key] = $this->safety($val);
				}
			}
		} else {
			$data = $this->safety($data);
		}
	}

	private function safety($val) {
		if(PHP51_MODE) { // if we don't have PHP 5.2 try to mimick filters as best we can FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES
			return htmlentities(strip_tags(str_replace("\0", '', $val)), ENT_NOQUOTES, 'UTF-8');
		} else {
			return filter_var($val, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
	}
	
}

$clean = new clean_data($data);

$clean->clean_data($_GET);
$clean->clean_data($_POST);
$clean->clean_data($_COOKIE);
$clean->clean_data($_REQUEST);

/************* END INITIAL SECURITY CHECKS *************/

if (!empty($_GET[CC_SESSION_NAME])){
	$GLOBALS[CC_SESSION_NAME] = $_GET[CC_SESSION_NAME];
	
} else if (!empty($_COOKIE[CC_SESSION_NAME])){
	$GLOBALS[CC_SESSION_NAME] = $_COOKIE[CC_SESSION_NAME];
}

if (!empty($_GET[CC_ADMIN_SESSION_NAME])){
	$GLOBALS[CC_ADMIN_SESSION_NAME] = $_GET[CC_ADMIN_SESSION_NAME];
	
} else if (!empty($_COOKIE[CC_ADMIN_SESSION_NAME])) {
	$GLOBALS[CC_ADMIN_SESSION_NAME] = $_COOKIE[CC_ADMIN_SESSION_NAME];
}
?>