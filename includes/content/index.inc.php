<?php 

/*



|	index.inc.php

|   ========================================

|	The Homepage	

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");



## Include lang file

$lang = getLang("includes".CC_DS."content".CC_DS."index.inc.php");



$index = new XTemplate("content".CC_DS."index.tpl");

$home = getLang("home.inc.php");

require_once"includes".CC_DS."boxes".CC_DS."testimonials.inc.php";

$index->assign("TESTIMONIAL",$box_content);

require_once"includes".CC_DS."boxes".CC_DS."flashbanner.inc.php";

$index->assign("BANNERS",$box_content);

$langFolder	= (defined('LANG_FOLDER') && constant('LANG_FOLDER')) ? LANG_FOLDER :  $config['defaultLang'];



$homesql	= sprintf("SELECT langArray FROM %sImeiUnlock_lang WHERE identifier = %s", $glob['dbprefix'], $db->mySQLsafe(CC_DS.preg_replace('/[^a-zA-Z0-9_\-\+]/', '', $langFolder).CC_DS.'home.inc.php'));

$result		= $db->select($homesql);



if ($result) {

	$home	= unserialize($result[0]['langArray']);

} else {

	if (!$home['enabled']) require "language".CC_DS. $config['defaultLang'].CC_DS."home.inc.php";

}



if (!empty($home['title']) || !empty($home['copy'])) {

	if ($config['seftags']) {		

		$meta['sefSiteTitle']		= $home['doc_metatitle']; 

		$meta['sefSiteDesc']		= $home['doc_metadesc'];

		$meta['sefSiteKeywords']	= $home['doc_metakeywords'];

	}

	$index->assign('HOME_TITLE', validHTML(stripslashes($home['title'])));

	$index->assign('HOME_CONTENT', stripslashes($home['copy']));

	$index->parse('index.welcome_note');

}





$cache = new cache('content.LatestProds');

$latestProducts = $cache->readCache();



if (!$cache->cacheStatus) {

	$latestProducts = $db->select("SELECT I.productId, I.image, I.price, I.name, I.sale_price FROM ".$glob['dbprefix']."ImeiUnlock_inventory AS I, ".$glob['dbprefix']."ImeiUnlock_category AS C WHERE C.cat_id = I.cat_id AND I.disabled != '1' AND I.showFeatured = '1' AND I.cat_id > 0 AND C.hide != '1' ORDER BY I.productId DESC LIMIT ".$config['noLatestProds']);

	$cache->writeCache($latestProducts);

}



if ($config['showLatestProds'] == true && $latestProducts) {

	for ($i=0; $i<count($latestProducts); $i++) {

		if (($val = prodAltLang($latestProducts[$i]['productId'])) == true) {

			$latestProducts[$i]['name'] = $val['name'];

		}

		$thumbRootPath	= imgPath($latestProducts[$i]['image'], true, 'root');

		$thumbRelPath	= imgPath($latestProducts[$i]['image'], true, 'rel');

	

		if (file_exists($thumbRootPath) && !empty($latestProducts[$i]['image'])) {

			$index->assign('VAL_IMG_SRC', $thumbRelPath);

		} else {

			$index->assign('VAL_IMG_SRC',$GLOBALS['rootRel'].'skins/'. SKIN_FOLDER . '/styleImages/thumb_nophoto.gif');

		}

		

		if (!salePrice($latestProducts[$i]['price'], $latestProducts[$i]['sale_price']) || !$config['saleMode']) {

			$index->assign("TXT_PRICE", priceFormat($latestProducts[$i]['price'], true));

		} else {

			$index->assign("TXT_PRICE", "<span class='txtOldPrice'>".priceFormat($latestProducts[$i]['price'], true)."</span>");

		}

		

		$salePrice = salePrice($latestProducts[$i]['price'], $latestProducts[$i]['sale_price']);

		

		$index->assign("TXT_SALE_PRICE", priceFormat($salePrice, true));

		

		$index->assign("VAL_PRODUCT_ID", $latestProducts[$i]['productId']);

		$index->assign("VAL_PRODUCT_NAME", validHTML($latestProducts[$i]['name']));

		$index->parse("index.latest_prods.repeat_prods");

	}



	$index->assign("LANG_LATEST_PRODUCTS",$lang['index']['latest_products']);

	$index->assign("LANG_PRICE",$lang['index']['pricebox']);

	$index->assign("LANG_BOX1",$lang['index']['box1']);

	$index->assign("LANG_BOX2",$lang['index']['box2']);

	$index->assign("LANG_BOX3",$lang['index']['box3']);

	$index->assign("LANG_BOX4",$lang['index']['box4']);

	$index->parse("index.latest_prods");	

}

	if ($cc_session->ccUserData['customer_id']>0) {

	$index->assign("LINKLOC", 'index.php?_a=viewCat"');

	}

	else{

	$index->assign("LINKLOC", '#reg-box" class="login-window');

	}
$docresult = $db->select("SELECT doc_content,doc_name FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id IN(23,24,25,26,27)");
if($docresult){
	$index->assign("DATA", $docresult);
	
}
$index->assign("H_TXT", stripslashes($config['htext']));
	$index->assign("H_PRICE", stripslashes($config['hprice']));
	$index->assign("H_LINK", stripslashes($config['hlink']));
$index->parse("index");

$page_content = $index->text("index");



?>