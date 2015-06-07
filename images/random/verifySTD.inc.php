<?php
if (!defined('CC_INI_SET')) die("Access Denied");
if(isset($_GET['esc'])){
	require("classes".CC_DS."gd".CC_DS."gd.inc.php");
	$spamCode = fetchSpamCode($_GET['esc']);
	$filename = "images".CC_DS."random".CC_DS."chars".CC_DS.substr($spamCode['SpamCode'],$_GET['n']-1,1).".gif";
	@header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
	@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	@header("Cache-Control: no-store, no-cache, must-revalidate");
	@header("Cache-Control: post-check=0, pre-check=0", false);
	@header("Pragma: no-cache");
	@header("Content-Type: image/gif");
	$fp = fopen($filename, 'rb');
	fpassthru($fp);
	fclose($filename);
	exit;
}
?>