<?php
if (!defined('CC_INI_SET')) die("Access Denied");

	define('USER', $module['user']);
	define('PWD', $module['pass']);
	define('VENDOR', $module['vendor']);
	define('PARTNER', $module['partner']);

if($module['gateway']==1){ ## LIVE MODE
	
	define('API_ENDPOINT', 'https://payflowpro.verisign.com/transaction:443/');
	define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
} else { ## SANDBOX MODE
	define('API_ENDPOINT', 'https://pilot-payflowpro.verisign.com/transaction:443/');
	//define('API_ENDPOINT', 'https://test-payflow.verisign.com/transaction:443/');
	define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
}

if($config['proxy']==1){
	define('USE_PROXY', true);
	define('PROXY_HOST', $config['proxyHost']);
	define('PROXY_PORT', $config['proxyPort']);
} else {
	define('USE_PROXY', false);
	define('PROXY_HOST', '127.0.0.1');
	define('PROXY_PORT', '808');
}

if($module['debug']==true) {
	define("PAYPAL_DEBUG", true);
} else {
	define("PAYPAL_DEBUG", false);
}
?>