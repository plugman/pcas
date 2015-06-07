<?php

/* ImeiUnlock Server Compatibility Script */

$fail	= false;
$encoder= false;

function detectGD() {
	if (extension_loaded('gd') && function_exists('gd_info')) {
		$gd = gd_info();
		$version = preg_replace('#[^0-9\.]#i', '', $gd['GD Version']);
		return sprintf('%s', $version);
	}
	return false;
}

function has_curl() {
	if(function_exists('curl_exec')) {
		return true;
	}
	return false;
}

function has_mcrypt() {
	if (function_exists('mcrypt_module_open')) {
		return true;
	}
	return false;
}

function safe_mode_status() {
	if(@ini_get('safe_mode')==true){
		return true;
	}
	return false;
}

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
	global $encoder;
	# Detect ionCube
	if (extension_loaded('ionCube Loader')) {
		$encoder = true;
		return true;
	} else {
		# Try to load the Ioncube Loader
		$__oc=strtolower(substr(php_uname(),0,3));$__ln='/ioncube/ioncube_loader_'.$__oc.'_'.substr(phpversion(),0,3).(($__oc=='win')?'.dll':'.so');$__oid=$__id=@realpath(ini_get('extension_dir'));$__here=dirname(__FILE__);if(strlen($__id)>1&&$__id[1]==':'){$__id=str_replace('\\','/',substr($__id,2));$__here=str_replace('\\','/',substr($__here,2));}$__rd=str_repeat('/..',substr_count($__id,'/')).$__here.'/';$__i=strlen($__rd);while($__i--){if($__rd[$__i]=='/'){$__lp=substr($__rd,0,$__i).$__ln;if(@file_exists($__oid.$__lp)){$__ln=$__lp;break;}}}@dl($__ln);
		if (function_exists('_il_exec')) {
			$encoder = true;
			return true;
		}
	}
	return false;
}

function versionCheck($minimum, $current) {
	global $fail;
	if (version_compare($minimum, $current, '<=')) {
		return '<span class="pass">'.$current.'</span>';
	} else {
		$fail = true;
		return '<span class="fail">'.$current.'</span>';
	}
}

?>
<html>
<head>
<title>ImeiUnlock v4 Compatibility Check</title>
<style type="text/css">
html, body {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

div {
	margin: 5px auto;
	padding: 2px;
	width: 300px;
	clear: both;
	border-bottom: 1px dotted #A7A7A7;
}
div.header {
	font-weight: bold;
	text-align: center;
}
div.footer {
	font-size: 9px;
}
ul {
	list-style: square;
}
div.result {
	text-align: center;
}

span.result {
	float: right;
}

.pass {
	color: #009933;
	font-weight: bold;
}
.fail {
	color: #990033;
	font-weight: bold;
}	

</style>
</head>

<body>

<div class="header">ImeiUnlock 4 Requirements Test</div>
<div class="test"><span class="result"><?php echo has_ioncube_loader() ? '<span class="pass">Installed</span>' : '<span class="fail">Not Available</span>'; ?></span>Ioncube Loader:</div>
<div class="test"><span class="result"><?php echo has_zend_optimizer() ? '<span class="pass">Installed</span>' : '<span class="fail">Not Available</span>'; ?></span>Zend Optimizer:</div>
<div class="test"><span class="result"><?php echo detectGD() ? '<span class="pass">Version '.detectGD().'</span>' : '<span class="fail">Not Available</span>'; ?></span>GD Image Library:</div>
<div class="test"><span class="result"><?php echo versionCheck('5.1.0', PHP_VERSION); ?></span>PHP &ge; 5.1.0:</div>
<div class="test"><span class="result"><?php echo versionCheck('4.1', mysql_get_client_info()); ?></span>MySQL &ge; 4.1.0: (This detects your MySQL client version. The server version may differ.)</div>
<div class="result">
<?php

if ($encoder) {
	if (!$fail) {
		echo '<span class="pass">Congratulations.<br />Your server looks like it is compatible with ImeiUnlock v4</span>';
	} else {
		echo '<span class="fail">Sorry, your server is not compatible with ImeiUnlock v4</span>';
	}
} else {
	echo '<span class="fail">ImeiUnlock v4 will not run on your server without either Zend Optimizer or Ioncube</span>';
}

?>
</div>
<br />
<div class="header">Other Information</div>
<div class="test"><span class="result"><?php echo has_curl() ? '<span class="pass">Installed</span>' : '<span class="fail">Not Available</span>'; ?></span>cURL:</div>
<div class="test"><span class="result"><?php echo has_mcrypt() ? '<span class="pass">Installed</span>' : '<span class="fail">Not Available</span>'; ?></span>mcrypt:</div>
<div class="test"><span class="result"><?php echo safe_mode_status() ? '<span class="fail">On</span>' : '<span class="pass">Off</span>'; ?></span>Safe Mode:</div>

<div class="footer">
  <strong>Notes:</strong> 
  <ul>
  	<li>Ioncube OR Zend Optimizer is required for ImeiUnlock to work at all.</li>
    <li>GD Image Library is required for dynamic image manipulation.</li>
    <li>PHP 5.1.0 Is the minimum version ImeiUnlock will work with.</li>
   	<li> MySQL 4.1.0 is the minimum version ImeiUnlock will work with.</li>
   	<li> cURL is required to communicate with 3rd party servers for services such as taking credit card payments. </li>
   	<li> mcrypt is used by the manual credit card capture only for encrypting credit card data.</li>
   	<li> Safe Mode is recommended to be off.</li>
  </ul>

</div>
</body>
</html>