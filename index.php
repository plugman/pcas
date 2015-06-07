<?php
/*
+--------------------------------------------------------------------------
|	index.php
|   ========================================
|	Decides which encoded master file to execute	
+--------------------------------------------------------------------------
*/

$debugStart = microtime();

## v3 Compatbility from old search Links
if(isset($_GET['act']) && !empty($_GET['act'])) {
	switch($_GET['act']) {
		case 'viewProd':
			$url = 'Location: index.php?_a=viewProd&productId='.$_GET['productId'];
		break;
		case 'viewCat':
			$url = ($_GET['catId']=='saleItems') ? 'Location: index.php?_a=viewCat&catId=saleItems' : 'Location: index.php?_a=viewCat&catId='.$_GET['catId'];
		break;
		case 'viewDoc':
			$url = 'Location: index.php?_a=viewDoc&docId='.$_GET['docId'];
		break;
		default:
			$url = 'index.php';
		break;
	}
	header($url, false, 301);
}


if (isset($_GET['_a']) && $_GET['_a'] == 'search') {
	## Do nothing yet...
} else {
	if(preg_match('#([a-z]{1,6})_([a-z0-9\+]+)\.?([a-z]+)?(\?.*)?$#i', $_SERVER['REQUEST_URI'], $matches)) {
		switch ($matches[1]) {
			case 'c':
			case 'cat':
				$_GET['_a'] = 'viewCat';
				$_GET['catId'] = $matches[2];
				break;
			case 'i':
			case 'info':
				$_GET['_a'] = 'viewDoc';
				$_GET['docId'] = $matches[2];
				break;
			case 'p':
			case 'prod':
				$_GET['_a'] = 'viewProd';
				$_GET['productId'] = $matches[2];
				break;
			case 's':
			case 'search':
				$_GET['_a'] = 'viewCat';
				$_GET['searchStr'] = $matches[2];
				break;
			case 't':
			case 'taf':
			case 'tell':
				$_GET['_a'] = 'tellafriend';
				$_GET['productId'] = $matches[2];
			break;
		}
	}
}

require_once 'ini.inc.php';

## If global.inc.php doesn't exist, the store probably needs to be installed
if (!file_exists('includes'.CC_DS.'global.inc.php')) {
	header('Location: setup/index.php');
	exit;
}
require_once 'includes'.CC_DS.'global.inc.php';

## If it does exist, then check that the install worked, and that the admin file exists
if (!$glob['installed'] || !isset($glob['adminFile'])) {
	# Nope, lets do the install
	header('Location: setup/index.php');
	exit;
}

## Let's load the encoded script, based on what encoders are available
if ($glob['encoder'] == 'zend') {
	## Zend Optimizer
	require_once 'index_enc.php';
} else {
	## IonCube Loader
	require_once 'index_enc.php';
}
?>