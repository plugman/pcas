<?php
/*
+--------------------------------------------------------------------------
|	switch.inc.php
|   ========================================
|	Switch between secure and insecure pages	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die('Access Denied');

## Get/Set paths and directories
if (detectSSL()) {
	## We're in SSL Mode already
	$GLOBALS['storeURL']	= $config['storeURL_SSL'];
	$GLOBALS['rootRel']		= $config['rootRel_SSL'];
} else {
	## We're NOT in SSL Mode
	$GLOBALS['storeURL']	= $glob['storeURL'];
	$GLOBALS['rootRel']		= $glob['rootRel'];	
}

## Make $GLOBALS paths fool proof - until someone makes a better fool...
if (substr($GLOBALS['storeURL'], -1, 1) == '/') {
	# remove end slash, if its there
	$GLOBALS['storeURL'] = substr($GLOBALS['storeURL'], 0, strlen($GLOBALS['storeURL'])-1);
}
if (substr($GLOBALS['rootRel'], -1, 1) !== '/') {
	$GLOBALS['rootRel'] = $GLOBALS['rootRel'].'/';
}

if ($config['ssl']) {
	##Â SSL Mode is enabled - Build redirection URL
	$url_elements 	= parse_url(html_entity_decode($_SERVER['REQUEST_URI']));
	
	$section		= (isset($_GET['_a'])) ? sanitizeVar($_GET['_a']) : '';
	$currentPage	= explode('?', currentPage());
	$currentDir		= str_replace(array($GLOBALS['storeURL'], $GLOBALS['rootRel'], '?'.$url_elements['query']), '', $currentPage[0]);
	if (substr($currentDir, 0, 1) != '/') {
		$currentDir = '/'.$currentDir;
	}
	if (substr($currentDir, 0, 1) != '/') {
		$currentDir = '/'.$currentDir;
	}
	
	## Is the query string set?
	if (isset($url_elements['query']) && !empty($url_elements['query'])) {
		parse_str($url_elements['query'], $newParams);
		
		## Unset the querystring Session ID for security
		unset($newParams[CC_SESSION_NAME]);
		## Reset the Session ID
		if (!empty($GLOBALS[CC_SESSION_NAME])) {
			$newParams[CC_SESSION_NAME] = $GLOBALS[CC_SESSION_NAME];
		}
		## If the query array has some content, process it
		if (count($newParams) != 0) {
			if (function_exists('http_build_query')) {
				$queryString	= '?'.htmlentities(http_build_query($newParams, '', '&'));
				
			} else {
				foreach ($newParams as $key => $value) {
					$queryArray[] = sprintf('%s=%s', $key, $value);
				}
				$queryString	= '?'.htmlentities(implode('&', $queryArray));
			}
		} else {
			$queryString	= '';
		}
	} else if ($config['sef'] && !empty($url_elements['path'])) {
		$currentDir		= $GLOBALS['rootRel'] == "/" ? $url_elements['path'] : "/".str_replace($GLOBALS['rootRel'], '', $url_elements['path']);
		$queryString	= sprintf('?%s=%s', CC_SESSION_NAME, $GLOBALS[CC_SESSION_NAME]);
	} else if (!empty($GLOBALS[CC_SESSION_NAME])) {
		## All we need is the Session ID
		$queryString = '?'.CC_SESSION_NAME.'='.$GLOBALS[CC_SESSION_NAME];
	}
	
	
	$enableSSL	= (isset($sslPages[$section]) || $config['force_ssl'] || (isset($enableSSl) && $enableSSl)) ? true : false;
	if (detectSSL() && !$enableSSL) {
		## Exit SSL Mode
		httpredir($glob['storeURL'].$currentDir.$queryString);
	} else if (!detectSSL() && $enableSSL) {
		## Enter SSL Mode
		httpredir($config['storeURL_SSL'].$currentDir.$queryString);
	}
}

?>
