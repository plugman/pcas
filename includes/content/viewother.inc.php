<?php

/*

+--------------------------------------------------------------------------

|	viewother.inc.php

|   ========================================

|	Display the Current Category	

+--------------------------------------------------------------------------

*/

if (!defined('CC_INI_SET')) die("Access Denied");



// include lang file

$lang = getLang("includes".CC_DS."content".CC_DS."viewother.inc.php");

$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;

$view_cat = new XTemplate ("content".CC_DS."viewother.tpl");

$view_cat->assign("LANG_DIR_LOC", $lang['viewother']['location']);



////////////////////////

// BUILD SUB CATEGORIES

////////////////////////

if (!isset($_GET['catId'])) {

	$query	= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE (cat_father_id = 116  OR cat_id =116) AND hide = '0' ORDER BY priority,cat_name ASC";
	## get category array in foreign innit
	$resultsForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '" . LANG_FOLDER . "'");

}else{

	$query	= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_id = ".$db->mySQLSafe($_GET['catId']). " AND hide = '0'  ORDER BY priority,cat_name ASC";

}



$catResult = $db->select($query); 




if(!empty($catResult)){
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
			if (is_array($resultsForeign)) {
			for ($k=0; $k<count($resultsForeign); $k++) {
				if ($resultsForeign[$k]['cat_id'] == $catResult[$j]['cat_id']) {
					$catResult[$j]['cat_name'] = $resultsForeign[$k]['cat_name'];
				}
			}
		}
////////////////////////////
// BUILD PRODUCTS		  //
////////////////////////////


  $productListQuery = sprintf("SELECT DISTINCT(I.productId), C.cat_id, I.productCode, I.description,  I.price, I.name, I.deltime, I.popularity, I.image, I.sale_price, I.stock_level, I.useStockLevel FROM %1\$sImeiUnlock_cats_idx AS C INNER JOIN %1\$sImeiUnlock_inventory AS I ON (C.productId = I.productId) INNER JOIN %1\$sImeiUnlock_category AS cat ON (C.cat_id = cat.cat_id)  WHERE I.disabled = '0' AND I.digital = 1 AND cat.type = '0' AND C.cat_id > 0 AND C.cat_id = '%2\$d' GROUP BY I.productId %3\$s", $glob['dbprefix'], $catResult[$j]['cat_id'], $orderSort);

## Run query if we haven't already done a search

	$productResults = $db->select($productListQuery);


## Get different languages 
if ($productResults && LANG_FOLDER !== $config['defaultLang']) {
	for ($i=0;$i<count($productResults);$i++) {
		if (($val = prodAltLang($productResults[$i]['productId'])) == true) {
			$productResults[$i]['name'] = $val['name'];
			$productResults[$i]['description'] = $val['description'];
		}
	}
}
$totalNoProducts = $db->numrows($productListQuery);

## Get current category info


if (!empty($currentCat[0]['cat_image'])) {
	$view_cat->assign("IMG_CURENT_CATEGORY", imgPath($currentCat[0]['cat_image'], false, 'rel'));
	$view_cat->assign("TXT_CURENT_CATEGORY", validHTML($currentCat[0]['cat_name']));
	$view_cat->parse("view_cat.cat_true.cat_img");
}

	$view_cat->assign("TXT_CAT_TITLE", $catResult[$j]['cat_name']);
	$view_cat->assign("TXT_CAT_ID", $catResult[$j]['cat_id']);
	$view_cat->assign("LANG_NAME", $lang['viewCat']['name']);

if (!empty($currentCat[0]['cat_desc'])) {
	$view_cat->assign("TXT_CAT_DESC", $currentCat[0]['cat_desc']);
	$view_cat->parse("view_cat.cat_true.cat_desc");
}
	
$view_cat->assign("LANG_IMAGE", $lang['viewCat']['image']);
$view_cat->assign("LANG_DESC", $lang['viewCat']['description']);
$view_cat->assign("LANG_NAME", $lang['viewCat']['name']);

$view_cat->assign("LANG_PRICE", $lang['viewCat']['price']);
$view_cat->assign("LANG_DATE", $lang['viewCat']['date_added']);


$view_cat->assign("LANG_TITLE", $lang['viewCat']['title']);
$view_cat->assign("LANG_COUNTRY", $lang['viewCat']['jump']);
$view_cat->assign("LANG_H1", $lang['viewCat']['h1']);
$view_cat->assign("LANG_H2", $lang['viewCat']['h2']);
$view_cat->assign("LANG_PARAGRAPH", $lang['viewCat']['paragraphp']);
$view_cat->assign("LANG_H3", $lang['viewCat']['h3']);
	

$currPage = currentPage();
if ($config['sef']) {
	$currPage = '?';
}
## repeated region
if ($productResults) {
	for ($i=0; $i<count($productResults); $i++) {
		## alternate class
		if (isset($productResults[$i]['name']) && !empty($productResults[$i]['name'])) {
			$view_cat->assign("CLASS", cellColor($i, "tdEven", "tdOdd"));
	
			$thumbRoot		= imgPath($productResults[$i]['image'], true, 'root');
			$thumbRootRel	= imgPath($productResults[$i]['image'], true, 'rel');
			
			if (file_exists($thumbRoot)) {
			$view_cat->assign("SRC_PROD_THUMB", str_replace("&", "&amp;", $thumbRootRel));
			} else {
				$view_cat->assign("SRC_PROD_THUMB", $GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
			}
	
		$view_cat->assign("TXT_TITLE", validHTML(stripslashes( str_replace("&", "&amp;", $productResults[$i]['name']))));
			
			if (strlen($productResults[$i]['description']) > $config['productPrecis']) {
				$view_cat->assign("TXT_DESC", substr(strip_tags($productResults[$i]['description']), 0, $config['productPrecis'])."&hellip;");
			} else {
				$view_cat->assign("TXT_DESC", strip_tags($productResults[$i]['description']));
			}
			
			$view_cat->assign("TXT_DESCDELVERY_TIME", strip_tags($productResults[$i]['deltime']));
		if($cc_session->ccUserData['customer_type'] == 0){
		if (!salePrice($productResults[$i]['price'], $productResults[$i]['sale_price']) || $config['saleMode'] == false)
		$view_cat->assign("TXT_PRICE", priceFormat($productResults[$i]['price'],true));
		else
		$view_cat->assign("TXT_PRICE", priceFormat($productResults[$i]['sale_price'],true));
		}
		else{
			$wprice = getwprice($cc_session->ccUserData['customer_type'], $productResults[$i]['productId']);
			if($wprice > 0){
				$view_cat->assign("TXT_PRICE", priceFormat($wprice, true));
				}
				else{
				if (!salePrice($productResults[$i]['price'], $productResults[$i]['sale_price']) || $config['saleMode'] == false)
				$view_cat->assign("TXT_PRICE", priceFormat($productResults[$i]['price'],true));
				else
				$view_cat->assign("TXT_PRICE", priceFormat($productResults[$i]['sale_price'],true));
				}
			}
				
	
			if (isset($_GET['add']) && isset($_GET['quan'])) {
				$view_cat->assign("CURRENT_URL", str_replace(array("&amp;add=".$_GET['add'], "&amp;quan=".$_GET['quan']), '', currentPage()));
				
			} else {
				$view_cat->assign("CURRENT_URL", currentPage());
			}
	
			if ($config['outofstockPurchase'] == true) {
				$view_cat->assign("BTN_BUY", $lang['viewCat']['buy']);
				$view_cat->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$view_cat->parse("view_cat.cat_true.productTable.products.buy_btn");
			
			} else if ($productResults[$i]['useStockLevel'] == true && $productResults[$i]['stock_level']>0) {
				$view_cat->assign("BTN_BUY", $lang['viewCat']['buy']);
				$view_cat->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$view_cat->parse("view_cat.cat_true.productTable.products.buy_btn");
			
			} else if ($productResults[$i]['useStockLevel'] == false) {
				$view_cat->assign("BTN_BUY", $lang['viewCat']['buy']);
				$view_cat->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$view_cat->parse("view_cat.cat_true.productTable.products.buy_btn");
			}
	
			$view_cat->assign("BTN_MORE", $lang['viewCat']['more']);
			$view_cat->assign("PRODUCT_ID", $productResults[$i]['productId']);
	
		if ($productResults[$i]['stock_level']<1 && $productResults[$i]['useStockLevel'] == true && $productResults[$i]['digital'] == false) {
				$view_cat->assign("TXT_OUTOFSTOCK", $lang['viewCat']['out_of_stock']);
			} else {
				$view_cat->assign("TXT_OUTOFSTOCK", '');
			}
	
			$view_cat->parse("view_cat.cat_true.productTable.products");
		}
	}
	
	$view_cat->assign("LANG_SORT", $lang['viewCat']['sort']);
	$view_cat->parse("view_cat.cat_true.productTable");
	$view_cat->assign("JUMPTO", "#ab".$catResult[$j]['cat_id']);
	$view_cat->assign("IMAGEINDEX", ($j));
	$view_cat->parse("view_cat.cat_selLoop");
	$view_cat->parse("view_cat.cat_true");
	
}

	
}
} else if (!$productResults && isset($_REQUEST['searchStr'])) {

	$view_cat->assign("TXT_NO_PRODUCTS", sprintf($lang['viewother']['no_products_match'], htmlspecialchars(stripslashes($_REQUEST['searchStr']))));

	$view_cat->parse("view_cat.noProducts");



} else {

	$view_cat->assign("TXT_NO_PRODUCTS", $lang['viewother']['no_prods_in_cat']);

	$view_cat->parse("view_cat.noProducts");

}



$view_cat->parse("view_cat");

$page_content = $view_cat->text("view_cat");

?>