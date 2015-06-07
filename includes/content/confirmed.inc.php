<?php

/*

+--------------------------------------------------------------------------

|	confirmed.inc.php

|   ========================================

|	Order Confirmation

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET') || !isset($_GET['s'])){ die("Access Denied"); }

## Fix for bug 1351

session_start();

unset($_SESSION['cc_back']);



// include lang file

$lang = getLang("includes".CC_DS."content".CC_DS."confirmed.inc.php");



$confirmation = new XTemplate ("content".CC_DS."confirmed.tpl");



$confirmation->assign("LANG_CONFIRMATION_SCREEN",$lang['confirmed']['confirmation_screen']);



$confirmation->assign("LANG_CART",$lang['confirmed']['cart']);

$confirmation->assign("LANG_CHECKOUT",$lang['confirmed']['checkout']);

$confirmation->assign("LANG_PAYMENT",$lang['confirmed']['payment']);

$confirmation->assign("LANG_COMPLETE",$lang['confirmed']['complete']);

	

// Payment Failed link to try again



if ($_GET['s'] == 1) {

	$confirmation->assign("LANG_ORDER_FAILED", $lang['confirmed']['order_fail']);

	$confirmation->assign("LANG_ORDER_RETRY", $lang['confirmed']['try_again_desc']);

	$confirmation->assign("LANG_RETRY_BUTTON", $lang['confirmed']['try_again']);

	$confirmation->assign("VAL_CART_ORDER_ID", sanitizeVar($_GET['cart_order_id']));

	$confirmation->parse("confirmation.order_failed");

}

// Payment success & complete 

elseif($_GET['s'] == 2) {

	$confirmation->assign("LANG_ORDER_SUCCESSFUL",$lang['confirmed']['order_success']);
	$confirmation->parse("confirmation.order_success");

	

}

// Payment may or may not have been approved yet 

elseif($_GET['s'] == 3) {

	$confirmation->assign("LANG_ORDER_PROCESSING",$lang['confirmed']['order_processing']);

	$confirmation->parse("confirmation.order_processing");

}

$confirmation->parse("confirmation");

$page_content = $confirmation->text("confirmation");



if (isset($cc_session) && $cc_session->ccUserData['type'] == 2) {

	## Purge the session if they're a ghost user (fixes bug #1588)

	$cc_session->destroySession($cc_session->ccUserData['sessId']);

}



?>