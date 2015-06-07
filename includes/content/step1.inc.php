<?php
/*
+--------------------------------------------------------------------------
|	step1.inc.php
|   ========================================
|	Step 1 Of the Checkout Pages (Login/Sign Up)	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."step1.inc.php");

$enableSSl = 1;
require_once("classes".CC_DS."cart".CC_DS."shoppingCart.php");
$cart = new cart();
$basket = $cart->cartContents($cc_session->ccUserData['basket']);

if($cc_session->ccUserData['customer_id']>0) {
	httpredir("index.php?_g=co&_a=step3");
}
else
httpredir("index.php?_a=login&amp;redir=step3");
$login_register = new XTemplate ("content".CC_DS."step1.tpl");

$login_register->assign("LANG_CART",$lang['step1']['cart']);
$login_register->assign("LANG_CHECKOUT",$lang['step1']['checkout']);
$login_register->assign("LANG_PAYMENT",$lang['step1']['payment']);
$login_register->assign("LANG_COMPLETE",$lang['step1']['complete']);

if($basket == false) {
	$login_register->assign("LANG_CART_EMPTY",$lang['step1']['lang_empty_cart']);
	$login_register->parse("session_page.cart_false");
	} else {
	$login_register->assign("LANG_LOGIN_TITLE",$lang['step1']['allready_customer']);
	$login_register->assign("LANG_LOGIN_BELOW",$lang['step1']['login_below']);
	$login_register->assign("VAL_SELF",urlencode( currentPage()) );
	$login_register->assign("LANG_USERNAME",$lang['step1']['username']);
	
	if(isset($_POST['username'])) {
		$login_register->assign("VAL_USERNAME",sanitizeVar($_POST['username']));
	}
	
	$login_register->assign("LANG_PASSWORD",$lang['step1']['password']);
	$login_register->assign("LANG_REMEMBER",$lang['step1']['remember_me']);
	$login_register->assign("TXT_LOGIN",$lang['step1']['login']);
	$login_register->assign("LANG_FORGOT_PASS",$lang['step1']['forgot_pass_q']);
	$login_register->assign("LANG_EXPRESS_REGISTER",$lang['step1']['need_register']);
	$login_register->assign("LANG_CONT_REGISTER",$lang['step1']['express_register']);
	$login_register->assign("LANG_REGISTER_BUTN",$lang['step1']['reg_and_cont']);
	$login_register->assign("LANG_CONT_SHOPPING",$lang['step1']['cont_shopping_q']);
	$login_register->assign("LANG_CONT_SHOPPING_BTN",$lang['step1']['cont_shopping']);
	$login_register->assign("LANG_CONT_SHOPPING_DESC",$lang['step1']['cont_browsing']);
	$login_register->parse("session_page.cart_true");

} 

$login_register->parse("session_page");
$page_content = $login_register->text("session_page");
?>