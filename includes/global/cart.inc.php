<?php
/*
+--------------------------------------------------------------------------
|	cart.inc.php
|   ========================================
|	Controls Cart Actions	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

$body = new XTemplate("global".CC_DS."cart.tpl");

if (isset($_GET['searchStr'])) {
	$body->assign("SEARCHSTR", sanitizeVar($_GET['searchStr']));
} else {
	$body->assign("SEARCHSTR", "");
}
$body->assign("CURRENCY_VER",$currencyVer);

## Incluse langauge config
include("language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$body->assign("VAL_ISO",$charsetIso);

## START META DATA
$body->assign("META_TITLE", stripslashes(str_replace("&#39;", "'", $config['siteTitle'])));
$body->assign("META_DESC", stripslashes($config['metaDescription']));
$body->assign("META_KEYWORDS", stripslashes($config['metaKeyWords']));

$returnPage = urlencode(currentPage());

## START  MAIN CONTENT
switch (sanitizeVar($_GET['_a'])) {
	case "step1":
		require_once "includes".CC_DS."content".CC_DS."cart.inc.php";
		break;
	case "cart":
	case "step2":
		require_once "includes".CC_DS."content".CC_DS."cart.inc.php";
		break; 
	case "step3":
		require_once "includes".CC_DS."content".CC_DS."cart.inc.php";
		break;
	case "step4":
		require_once "includes".CC_DS."content".CC_DS."gateway.inc.php";
		break; 
	case "topup":
 	    require_once "includes".CC_DS."content".CC_DS."topup.inc.php";
		break;
	case "reg":
		require_once "includes".CC_DS."content".CC_DS."reg.inc.php";
		break;
	case "viewOrders":
		require_once "includes".CC_DS."content".CC_DS."viewOrders.inc.php";
		break;
	case "viewOrder":
		require_once "includes".CC_DS."content".CC_DS."viewOrder.inc.php";
		break;
	case "error":
		require_once "includes".CC_DS."content".CC_DS."error.inc.php";
		break;
	case "confirmed":
		require_once "includes".CC_DS."content".CC_DS."confirmed.inc.php";
		break;
	default:
		httpredir("index.php");
}

## START CONTENT BOXES
require_once "includes".CC_DS."boxes".CC_DS."searchForm.inc.php";
$body->assign("SEARCH_FORM", $box_content);

require_once "includes".CC_DS."boxes".CC_DS."session.inc.php";
$body->assign("SESSION", $box_content);

require_once "includes".CC_DS."boxes".CC_DS."siteDocs.inc.php";
$body->assign("SITE_DOCS", $box_content);



## added in 4.0.3 - not part of templates, but designers can use them if they want
require_once"includes".CC_DS."boxes".CC_DS."currency.inc.php";
$body->assign("CURRENCY",$box_content);



require_once"includes".CC_DS."boxes".CC_DS."mailList.inc.php";
$body->assign("MAIL_LIST",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."shoppingCart.inc.php";
$body->assign("SHOPPING_CART",$box_content);



require_once"includes".CC_DS."boxes".CC_DS."sociallinks.inc.php";
$body->assign("SOCIAL_LINKS",$box_content);

$body->assign("STOREURL", $glob['storeURL']);

$ismobile = check_user_agent('mobile');
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

require_once"includes".CC_DS."boxes".CC_DS."popularProducts.inc.php";
$body->assign("POPULAR_PRODUCTS",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."saleItems.inc.php";
$body->assign("SALE_ITEMS",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."skin.inc.php";
$body->assign("SKIN",$box_content);

$meta['title'] = str_replace("&#39;","'",$config['siteTitle']);
	$meta['description'] = $config['metaDescription'];
	$meta['keywords'] = $config['metaKeyWords'];
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
##facebook feed end by Imran 
$models = $db->select("SELECT id,name FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE hide = '0' AND isfot = '1' ORDER BY device_id ASC");
if($models){
	for($i=0;$i<count($models);$i++){
		$body->assign("MODEL_NAME", $models[$i]['name']);
		$body->assign("MODEL_ID", $models[$i]['id']);
		$name = str_replace(' ' , '' , $models[$i]['name']);
		$body->assign("MODEL_NAME", $name);
		$body->parse("body.footer.all_models");
	}
}
if ($_GET['_a'] != "casecustomization") {
	$body->parse("body.headercss");
	$body->parse("body.header");
	$body->parse("body.footer");
	
}else{$body->assign("CUSTOMCASE", "display:none");
	$body->parse("body.casegram");
}
?>