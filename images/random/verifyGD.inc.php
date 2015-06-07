<?php
if (!defined('CC_INI_SET')) die("Access Denied");
if(isset($_GET['esc'])) {
	require("classes".CC_DS."gd".CC_DS."gd.inc.php");
	$spamCode = fetchSpamCode($_GET['esc']);
	$config = fetchDbConfig("config");
	$img = new gd(CC_ROOT_DIR.CC_DS."images".CC_DS."random".CC_DS."verify.png",120,40);
	$img->randImage($spamCode['SpamCode']);
}
?>