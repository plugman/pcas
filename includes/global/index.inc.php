<?php
/*
+--------------------------------------------------------------------------|	index.inc.php
|   ========================================
|	Main pages of the store	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

$body = new XTemplate ("global".CC_DS."index.tpl");

## Extra Events
$extraEvents = "";
if (isset($_GET['added']) && !empty($_GET['added'])) {
	if ($cc_session->ccUserData['customer_id'] == false && $config['hide_prices'] == 1) {
		## have a break, have a KitKat
	} else {
		$extraEvents = "flashBasket(6);";
	}
}
$body->assign("EXTRA_EVENTS",$extraEvents);

if (isset($_GET['searchStr'])) {
	$body->assign("SEARCHSTR", sanitizeVar($_GET['searchStr']));
} else {
	$body->assign("SEARCHSTR","");
}

$body->assign("CURRENCY_VER",$currencyVer);

## Incluse langauge config
include("language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$body->assign("VAL_ISO",$charsetIso);

## START CONTENT BOXES
require_once "includes".CC_DS."boxes".CC_DS."searchForm.inc.php";
$body->assign("SEARCH_FORM",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."session.inc.php";
$body->assign("SESSION",$box_content);

$body->assign("STOREURL", $glob['storeURL']);

$ismobile = check_user_agent('mobile');
if($ismobile && $_GET['_a'] == "casecustomization"){
	header('Location: http://www.photocase.ie/error_mobile.html');
}
if($ismobile && SKIN_FOLDER != 'Classic' ) {
	if(!$_GET['_a'] && $config['mobilesking']){
		require_once"includes".CC_DS."boxes".CC_DS."menu.inc.php";
		$body->assign("MENU",$box_content);
	}
}
	else{
		require_once"includes".CC_DS."boxes".CC_DS."menu.inc.php";
		$body->assign("MENU",$box_content);
	}


require_once"includes".CC_DS."boxes".CC_DS."language.inc.php";
$body->assign("LANGUAGE",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."currency.inc.php";
$body->assign("CURRENCY",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."shoppingCart.inc.php";
$body->assign("SHOPPING_CART",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."cartpopup.inc.php";
$body->assign("CARTPOPUP",$box_content);



require_once"includes".CC_DS."boxes".CC_DS."mailList.inc.php";
$body->assign("MAIL_LIST",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."siteDocs.inc.php";
$body->assign("SITE_DOCS",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."skin.inc.php";
$body->assign("SKIN",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."sociallinks.inc.php";
$body->assign("SOCIAL_LINKS",$box_content);
## END CONTENT BOXES

## START  MAIN CONTENT
if (!empty($_GET['_a'])) {
	#if ($_GET['_a'] == 'search') $_GET['_a'] = 'viewCat';
	if (file_exists("includes".CC_DS."content".CC_DS.sanitizeVar($_GET['_a']).".inc.php")) {
		require_once("includes".CC_DS."content".CC_DS.sanitizeVar($_GET['_a']).".inc.php");
	} else {
		require_once("includes".CC_DS."content".CC_DS."index.inc.php");
	}
} else {
	require_once("includes".CC_DS."content".CC_DS."index.inc.php");
}

## END MAIN CONTENT



## START META DATA
if (isset($meta)) {
	$meta['title'] = sefMetaTitle();
	$meta['description'] = sefMetaDesc();
	$meta['keywords'] = sefMetaKeywords();
	
} else {
	$meta['title'] = str_replace("&#39;","'",$config['siteTitle']);
	$meta['description'] = $config['metaDescription'];
	$meta['keywords'] = $config['metaKeyWords'];
}

$body->assign("META_TITLE", stripslashes($meta['title']));
$body->assign("META_DESC", stripslashes($meta['description']));
$body->assign("META_KEYWORDS", stripslashes($meta['keywords']));


if($config['olark'] == 1){
	$body->assign("VAL_OLARKID", stripslashes($config['olarkid']));
	$body->parse("body.olark_true");
	}
##facebook feed start by Imran 
if($config['fbaid'] != "" && $config['fbsid'] != "" && $config['fbid'] != ""){
	$cache = new cache('facebook.feed');
	$filename = $cache->filename.'facebook.feed.inc.php';
	if (file_exists($filename)) {
		if (filectime($filename)< (time()-1800)) {  // 1800 = 60*30 30 mint
          unlink($filename);
        }
}
	$fbApiGetPosts = $cache->readCache();
	
	if (!$cache->cacheStatus) {
require_once 'facebook.php';
$facebook = new Facebook(array(
    'appId' => stripslashes($config['fbaid']),
    'secret' => stripslashes($config['fbsid']),
));

 $fbApiGetPosts = $facebook->api('/'.stripslashes($config['fbid']).'/feed?limit=3');
if (isset($fbApiGetPosts["data"]) && !empty($fbApiGetPosts["data"])) {
    // display contents of $fbApiGetPosts["data"] array
	$fbApiGetPosts = $fbApiGetPosts["data"];
	$cache->writeCache($fbApiGetPosts);
	
		}
	}
	if($fbApiGetPosts){
		for($i=0;$i<count($fbApiGetPosts);$i++){
			$dTime = strtotime($fbApiGetPosts[$i]['created_time']);
    		 $datediff = time() - $dTime;
     		$days_no = floor($datediff/(60*60*24));
			
			$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
			$replacement = "";
			$msg = preg_replace($pattern, $replacement, $fbApiGetPosts[$i]['message'] ? $fbApiGetPosts[$i]['message'] : $fbApiGetPosts[$i]['story']);
			$postlink = "http://www.facebook.com/".str_replace('_', '/posts/', $fbApiGetPosts[$i]['id']);
			$body->assign("POST_MSG", $msg);
			$body->assign("POST_PIC", $fbApiGetPosts[$i]['picture']);
			$body->assign("POST_LINK", $postlink);
			$body->assign("POST_DAYS", $days_no);
			$body->parse("body.footer.fbpost_true.repeat_posts");
		}
		$body->parse("body.footer.fbpost_true");
	}
}
$models = $db->select("SELECT M.id,M.name FROM ".$glob['dbprefix']."ImeiUnlock_case_models AS M INNER JOIN ".$glob['dbprefix']."ImeiUnlock_case_devices AS D ON M.device_id = D.id WHERE M.hide = '0' AND M.isfot = '1' ORDER BY device_id ASC");
if($models){
	for($i=0;$i<count($models);$i++){
		$body->assign("MODEL_NAME", $models[$i]['name']);
		$body->assign("MODEL_ID", $models[$i]['id']);
		$name = str_replace(' ' , '' , $models[$i]['name']);
		$body->assign("MODEL_NAME", $name);
		$body->parse("body.footer.all_models");
	}
}
##facebook feed end by Imran 
if ($_GET['_a'] != "casecustomization") {
	$body->parse("body.headercss");
	$body->parse("body.header");
	$body->parse("body.footer");
	
}else{$body->assign("CUSTOMCASE", "display:none");
	$body->parse("body.casegram");
}
?>