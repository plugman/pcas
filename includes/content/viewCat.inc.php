<?php
/*
+--------------------------------------------------------------------------
|	mobileacces.inc.php
|   ========================================
|	Display the Current Category	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."viewCat.inc.php");
$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;
$mobile_access = new XTemplate ("content".CC_DS."viewCat.tpl");
$mobile_access->assign("LANG_DIR_LOC", $lang['viewCat']['location']);
require_once"includes".CC_DS."boxes".CC_DS."categories.inc.php";
$mobile_access->assign("CATEGORIES",$box_content);
$mobile_access->assign("LANG_HEADING", $lang['viewCat']['heading']);
////////////////////////
// BUILD SUB CATEGORIES
////////////////////////
//echo "<PRE>";
//print_r($lang);
if($_GET['added']==1) {		
		$mobile_access->parse("mobile_access.added");
	}

if (isset($_GET['catId']) && $_GET['catId']!='saleItems') {
$catbanners = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_cat_flashbanner WHERE img_status='1' AND lang = '" . LANG_FOLDER  . "' AND cat_id =".$db->mySQLSafe($_GET['catId']));
	if($catbanners){
		for($i=0;$i<count($catbanners);$i++){
			$mobile_access->assign("DATA", $catbanners[$i]);
			$mobile_access->parse("mobile_access.banner_true.repeat");
		}
		$mobile_access->parse("mobile_access.banner_true");
	}
}
if (isset($_GET['catId'])) {
	$_GET['catId'] = sanitizeVar($_GET['catId']);
	## build query
	$emptyCat	= ($config['show_empty_cat']) ? '' : ' AND noProducts >= 1';
	$query		= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = ".$db->mySQLSafe($_GET['catId'])." AND hide = '0'".$emptyCat." ORDER BY priority,cat_name ASC";
	
	## get category array in foreign innit
	$resultsForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '" . LANG_FOLDER . "'");
	
	## query database
	$subCategories = $db->select($query);
}

if (isset($_GET['catId']) && $_GET['catId']>0 && $subCategories) {
	## loop results
	for ($i=0; $i<count($subCategories); $i++) {
		if (is_array($resultsForeign)) {
			for ($k=0; $k<count($resultsForeign); $k++) {
				if ($resultsForeign[$k]['cat_id'] == $subCategories[$i]['cat_id']) {
					$subCategories[$i]['cat_name'] = $resultsForeign[$k]['cat_name'];
				}
			}
		}
		
		$catImg = imgPath($subCategories[$i]['cat_image'], true, 'rel');
		$catImgRoot = imgPath($subCategories[$i]['cat_image'], true, 'root');
		
		if (!empty($subCategories[$i]['cat_image']) && file_exists($catImgRoot)) {
			$mobile_access->assign("IMG_CATEGORY", $catImg);
		} else {
			$mobile_access->assign("IMG_CATEGORY", $GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/catnophoto.gif");
		}
		
		$mobile_access->assign("TXT_LINK_CATID", $subCategories[$i]['cat_id']);
		$mobile_access->assign("TXT_CATEGORY", validHTML($subCategories[$i]['cat_name']));
		$mobile_access->assign("NO_PRODUCTS", $subCategories[$i]['noProducts']);
		$mobile_access->parse("mobile_access.sub_cats.sub_cats_loop");
	}
	$mobile_access->parse("mobile_access.sub_cats");
}

////////////////////////////
// BUILD PRODUCTS		  //
////////////////////////////

## New! Product sorting by field
$allowedSort	= array('price', 'name', 'popularity');
if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], $allowedSort)) {
	switch ($_GET['sort_order']) {
		case 'high':
			$orderType = 'DESC';
			$orderText = '&uarr';
			$sortIcon = 'bullet_arrow_up.gif';
			break;
		case 'low':
			$orderType = 'ASC';
			$orderText = '&darr';
			$sortIcon = 'bullet_arrow_down.gif';
			break;
		default:
			$orderType = 'ASC';
			$sortIcon = 'bullet_arrow_down.gif';
	}
	$orderSort = sprintf(' ORDER BY %s %s', $_GET['sort_by'], $orderType);
} else {
	if ($config['cat_newest_first']) {
		$orderSort = sprintf(' ORDER BY date_added DESC, name ASC');
	} else {
		$orderSort = false;
	}
}

## build query
if (isset($_REQUEST['searchStr']) || !empty($_REQUEST['priceMin']) || !empty($_REQUEST['priceMax'])) {
	unset($_GET['Submit']);
	
	$_REQUEST['searchStr'] = trim(preg_replace(array('#^or#i','#^and#i'),'', $_REQUEST['searchStr']));
	
	/* LOG SEARCH PHRASE */
	$searchQuery = "SELECT id FROM ".$glob['dbprefix']."ImeiUnlock_search WHERE searchstr=".$db->mySQLsafe($_REQUEST['searchStr'])."";
	$searchLogs = $db->select($searchQuery);
					
	$insertStr['searchstr'] = $db->mySQLsafe($_REQUEST['searchStr']);
	$insertStr['hits'] = $db->mySQLsafe(1);
	$updateStr['hits'] = "hits+1";
					
	if ($searchLogs) {
		$counted = $db->update($glob['dbprefix']."ImeiUnlock_search", $updateStr,"id=".$searchLogs[0]['id'],$quote = "");
	} else if (!empty($_REQUEST['searchStr'])) {
		$counted = $db->insert($glob['dbprefix']."ImeiUnlock_search", $insertStr);
	}
	
	$indexes = $db->getFulltextIndex('inventory', 'I'); //array('inventory', 'inv_lang'));
		
	if (!empty($_REQUEST['priceMin']) && is_numeric($_REQUEST['priceMin'])) $where[] = sprintf("I.price >= %s", $_REQUEST['priceMin']);
	if (!empty($_REQUEST['priceMax']) && is_numeric($_REQUEST['priceMax'])) $where[] = sprintf("I.price <= %s", $_REQUEST['priceMax']);
	
	if (isset($_REQUEST['inStock'])) $where[] = "((I.useStockLevel = 0) OR (I.useStockLevel = 1 AND I.stock_level > 0))";
	
	if (!empty($_REQUEST['category'])) {
		if (is_array($_REQUEST['category'])) {
			foreach ($_REQUEST['category'] as $cat_id) {
				if (is_numeric($cat_id)) $cats[] = $cat_id;
			}
			$where[] = sprintf("I.cat_id IN (%s)", implode(',', $cats));
		} else if (is_numeric($_REQUEST['category'])) {
			$where[] = sprintf("I.cat_id = '%d'", $_REQUEST['category']);
		}
	}
	
	$where[] = "C.cat_id = I.cat_id";
	$where[] = "C.hide = '0'";
	$where[] = "(C.cat_desc != '##HIDDEN##' OR C.cat_desc IS NULL)";
	$where[] = "I.disabled = '0'";
	
	$whereString = sprintf('AND %s', implode(' AND ', $where));
	
	if (is_array($indexes)) {
		sort($indexes);
		$mode = '';
		if (empty($orderSort)) {
			$orderSort = ' ORDER BY SearchScore DESC';
		}
		//if (!empty($_REQUEST['searchStr'])) {
		$mode = '';
		if (isset($_REQUEST['searchStr'])) {
			$matchString = sprintf("MATCH (%s) AGAINST(%s%s)", implode(',', $indexes), $db->mySQLsafe($_REQUEST['searchStr']), $mode); 
			$search = sprintf("SELECT DISTINCT(I.productId), I.*, %2\$s AS SearchScore FROM %1\$sImeiUnlock_inventory AS I, %1\$sImeiUnlock_category AS C WHERE (%2\$s) >= %4\$F AND C.cat_id > 0 %3\$s %5\$s", $glob['dbprefix'], $matchString, $whereString, 0.5, $orderSort);
		} else {
			$search = sprintf("SELECT DISTINCT(I.productId), I.* FROM %1\$sImeiUnlock_inventory AS I, %1\$sImeiUnlock_category AS C WHERE I.cat_id > 0 %2\$s %3\$s", $glob['dbprefix'], $whereString, $orderSort);
		}
		$productListQuery = $search;
		## Moved into if to stop MySQL error on index failure
		$productResults = $db->select($productListQuery, $config['productPages'], $page);
	}
	// If there are no results try boolean search instead as it will return results for > 50% of products
	if(!$productResults) {
		$mode = ' IN BOOLEAN MODE';
		if (isset($_REQUEST['searchStr'])) {
			$matchString = sprintf("MATCH (%s) AGAINST(%s%s)", implode(',', $indexes), $db->mySQLsafe($_REQUEST['searchStr']), $mode); 
			$search = sprintf("SELECT DISTINCT(I.productId), I.*, %2\$s AS SearchScore FROM %1\$sImeiUnlock_inventory AS I, %1\$sImeiUnlock_category AS C WHERE (%2\$s) >= %4\$F AND C.cat_id > 0 %3\$s %5\$s", $glob['dbprefix'], $matchString, $whereString, 0.5, $orderSort);
		} else {
			$search = sprintf("SELECT DISTINCT(I.productId), I.* FROM %1\$sImeiUnlock_inventory AS I, %1\$sImeiUnlock_category AS C WHERE I.cat_id > 0 %2\$s %3\$s", $glob['dbprefix'], $whereString, $orderSort);
		}
		$productListQuery = $search;
		## Moved into if to stop MySQL error on index failure
		$productResults = $db->select($productListQuery, $config['productPages'], $page);
	}
} else if ($_GET['catId'] == 'saleItems' && $config['saleMode'] >= 1) {
	$productListQuery = sprintf("SELECT DISTINCT(I.productId), C.cat_id, I.productCode, I.min_quantity, I.shipping, I.description, I.image, I.price, I.name, I.popularity, I.sale_price, I.stock_level, I.useStockLevel FROM %1\$sImeiUnlock_cats_idx AS C INNER JOIN %1\$sImeiUnlock_inventory AS I ON C.productId = I.productId WHERE I.disabled = '0' AND I.digital = 0 AND I.sale_price != 0 AND C.cat_id > 0 GROUP BY I.productId %2\$s", $glob['dbprefix'], $orderSort);
}else if (!$_GET['catId']  && $config['showLatestProds'] >= true) {
	$productListQuery = sprintf("SELECT DISTINCT(I.productId), C.cat_id, I.productCode, I.min_quantity, I.shipping, I.description, I.image, I.price, I.name, I.popularity, I.sale_price, I.stock_level, I.useStockLevel FROM %1\$sImeiUnlock_cats_idx AS C INNER JOIN %1\$sImeiUnlock_inventory AS I ON C.productId = I.productId WHERE I.disabled = '0' AND I.digital = 0 AND C.cat_id > 0 AND I.showFeatured = '1'  GROUP BY I.productId %3\$s", $glob['dbprefix'], "I.showFeatured = '1'", $orderSort);
	
} else {
    $productListQuery = sprintf("SELECT DISTINCT(I.productId), C.cat_id, I.productCode, I.min_quantity, I.shipping, I.description, I.image, I.price, I.name, I.popularity, I.sale_price, I.stock_level, I.useStockLevel FROM %1\$sImeiUnlock_cats_idx AS C INNER JOIN %1\$sImeiUnlock_inventory AS I ON C.productId = I.productId WHERE I.disabled = '0' AND I.digital = 0 AND C.cat_id > 0 AND C.cat_id = '%2\$d' GROUP BY I.productId %3\$s", $glob['dbprefix'], $_GET['catId'], $orderSort);
}

## Run query if we haven't already done a search
if (!isset($productResults)) {
	$productResults = $db->select($productListQuery, $config['productPages'] , $page);
}

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
if (isset($_GET['catId'])) {
	if ($config['seftags']) {
		if ($_GET['catId']>0) {
			
			$currentCatQuery	= "SELECT cat_metatitle, cat_metadesc, cat_metakeywords, cat_name, cat_father_id, cat_id, cat_image, cat_desc FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_id = ".$db->mySQLSafe($_GET['catId'])." ORDER BY priority,cat_name ASC";
			$currentCat			= $db->select($currentCatQuery);
			
			$prevDirSymbol				= $config['dirSymbol'];
			$config['dirSymbol']		= ' - ';
			$meta['siteTitle']			= getCatDir($currentCat[0]['cat_name'],$currentCat[0]['cat_father_id'], $currentCat[0]['cat_id'], false, true, $config['sefprodnamefirst'] ? FALSE : TRUE);
			$config['dirSymbol']		= $prevDirSymbol;

			$meta['metaDescription']	= strip_tags($config['metaDescription']);		
			$meta['sefSiteTitle']		= $currentCat[0]['cat_metatitle']; 
			$meta['sefSiteDesc']		= $currentCat[0]['cat_metadesc'];
			$meta['sefSiteKeywords']	= $currentCat[0]['cat_metakeywords'];
			
		} else if (strcmp($_GET['catId'], "saleItems") == 0) {
			$meta['siteTitle'] = $lang['front']['boxes']['sale_items'];
			$meta['metaDescription'] = strip_tags($config['metaDescription']);
		}
	} else if (is_numeric($_GET['catId'])) {
	    $currentCatQuery = "SELECT cat_name, cat_father_id, cat_id, cat_image, cat_desc FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_id = ".$db->mySQLSafe($_GET['catId'])." AND (cat_desc != '##HIDDEN##' OR cat_desc IS NULL) ORDER BY priority,cat_name ASC";
	    $currentCat = $db->select($currentCatQuery);
	}
	
	# Get translations
	$resultForeign = $db->select("SELECT cat_master_id as cat_id, cat_name, cat_desc FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '".LANG_FOLDER."' AND cat_master_id = ".$db->mySQLSafe($_GET['catId']));
	if ($resultForeign) {
		$currentCat[0]['cat_name'] = $resultForeign[0]['cat_name'];
		$currentCat[0]['cat_desc'] = $resultForeign[0]['cat_desc'];
	}
}

if (!empty($currentCat[0]['cat_image'])) {
	$mobile_access->assign("IMG_CURENT_CATEGORY", imgPath($currentCat[0]['cat_image'], false, 'rel'));
	$mobile_access->assign("TXT_CURENT_CATEGORY", validHTML($currentCat[0]['cat_name']));
	$mobile_access->parse("mobile_access.cat_img");
}

if (isset($_REQUEST['searchStr']) || isset($_REQUEST['priceMin']) || isset($_REQUEST['priceMax'])) {
	$mobile_access->assign("TXT_CAT_TITLE", $lang['viewCat']['search_results']);
	$mobile_access->assign("CURRENT_LOC", $config['dirSymbol'].$lang['viewCat']['search_results']);
	
} else if ($_GET['catId']=="saleItems" && $config['saleMode']>0) {
	$mobile_access->assign("TXT_CAT_TITLE", $lang['viewCat']['sale_items']);
	$mobile_access->assign("CURRENT_LOC", $config['dirSymbol'].$lang['viewCat']['sale_items']);
} else {
	$mobile_access->assign("TXT_CAT_TITLE", validHTML($currentCat[0]['cat_name'] ? $currentCat[0]['cat_name'] : 'Latest Products'));
	$mobile_access->assign("CURRENT_LOC", getCatDir($currentCat[0]['cat_name'], $currentCat[0]['cat_father_id'], $currentCat[0]['cat_id'], true));
}
	
if (!empty($currentCat[0]['cat_desc'])) {
	$mobile_access->assign("TXT_CAT_DESC", $currentCat[0]['cat_desc']);
	$mobile_access->parse("mobile_access.cat_desc");
}
	
$mobile_access->assign("LANG_IMAGE", $lang['viewCat']['image']);
$mobile_access->assign("LANG_DESC", $lang['viewCat']['description']);
$mobile_access->assign("LANG_NAME", $lang['viewCat']['name']);
$mobile_access->assign("LANG_PRICE", $lang['viewCat']['price']);
$mobile_access->assign("LANG_BEST", $lang['viewCat']['best']);
$mobile_access->assign("LANG_SORTBY", $lang['viewCat']['sortby']);
//$mobile_access->assign("LANG_DATE", $lang['viewCat']['date_added']);

$pagination = paginate($totalNoProducts, $config['productPages'], $page, 'page', 'txtLink', 5, array('Submit' => 1));
	
if (!empty($pagination)) {
	$mobile_access->assign("PAGINATION", $pagination);
	$mobile_access->parse("mobile_access.pagination_top");
	$mobile_access->parse("mobile_access.pagination_bot");
}
	
## create the links for product sorting - need improving later
$sort_order = (!isset($_GET['sort_order']) || $_GET['sort_order'] == 'high') ? 'low' : 'high'; 
switch($_GET['sort_by']) {
	case 'name':
		$mobile_access->assign('SORT_NAME_SELECTED', ' selected="selected"');
		break;
	case 'price':
		$mobile_access->assign('SORT_PRICE_SELECTED', ' selected="selected"');
		break;
	case 'popularity':
		$mobile_access->assign('SORT_BEST_SELECTED', ' selected="selected"');
		break;
}

#	$mobile_access->assign('SORT_DIRECTION_TEXT', $orderText);
unset($_GET['sort_by'], $_GET['sort_order']);
$meta['sefSiteTitle']		= strip_tags($config['accesstitle']); 
$meta['sefSiteDesc']		= strip_tags($config['accessdesc']); 
$meta['sefSiteKeywords']		= strip_tags($config['accesskey']); 
$currPage = currentPage();
if ($config['sef']) {
	$currPage = '?';
}

$sortTypes = array(
	//'SORT_PROD_CODE'=> 'productCode',
	'SORT_PRICE'	=> 'price',
	//'SORT_DESC'		=> 'description',
	'SORT_NAME'		=> 'name',
	//'SORT_DATE'		=> 'date_added',
	'SORT_BEST'		=> 'popularity',
);

$queryString	= parse_url(html_entity_decode(currentPage()), PHP_URL_QUERY);
parse_str($queryString, $currentQuery);
//ksort($currentQuery); REMOVED AS _a first invokes SEO which we don't want on pagiation
foreach ($sortTypes as $assign_key => $field) {
	$currentQuery['sort_by']	= $field;
	$currentQuery['sort_order']	= $sort_order;
	// str_replace is a hack to fix pagination seo rewrite
	$mobile_access->assign($assign_key, $currPage.http_build_query($currentQuery, '', '&amp;'));
}
//echo $assign_key;

#$mobile_access->assign('SORT_PROD_CODE', $currPage."&amp;sort_by=productCode&amp;sort_order=".$sort_order);
#$mobile_access->assign('SORT_PRICE', $currPage."&amp;sort_by=price&amp;sort_order=".$sort_order);
#$mobile_access->assign('SORT_DESC', $currPage."&amp;sort_by=description&amp;sort_order=".$sort_order);
#$mobile_access->assign('SORT_NAME', $currPage."&amp;sort_by=name&amp;sort_order=".$sort_order);
#$mobile_access->assign('SORT_DATE', $currPage."&amp;sort_by=date_added&amp;sort_order=".$sort_order);

if (!empty($sortIcon) && file_exists('skins'.CC_DS.SKIN_FOLDER.CC_DS.'styleImages'.CC_DS.'icons'.CC_DS.$sortIcon)) {
	$mobile_access->assign('SORT_ICON', sprintf('<img src="%s", alt="" />', 'skins'.CC_DS.SKIN_FOLDER.CC_DS.'styleImages'.CC_DS.'icons'.CC_DS.$sortIcon));
}
	
## repeated region
if ($productResults) {
	for ($i=0; $i<count($productResults); $i++) {
		## alternate class
		if (isset($productResults[$i]['name']) && !empty($productResults[$i]['name'])) {
			$mobile_access->assign("CLASS", cellColor($i, "tdEven", "tdOdd"));
	
			$thumbRoot		= imgPath($productResults[$i]['image'], true, 'root');
			$thumbRootRel	= imgPath($productResults[$i]['image'], true, 'rel');
			
			if (file_exists($thumbRoot)) {
				$mobile_access->assign("SRC_PROD_THUMB", $thumbRootRel);
			} else {
				$mobile_access->assign("SRC_PROD_THUMB", $GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
			}
	
			$mobile_access->assign("TXT_TITLE", validHTML(stripslashes($productResults[$i]['name'])));
			
			if (strlen($productResults[$i]['description']) > $config['productPrecis']) {
				$mobile_access->assign("TXT_DESC", substr(strip_tags($productResults[$i]['description']), 0, $config['productPrecis'])."&hellip;");
			} else {
				$mobile_access->assign("TXT_DESC", strip_tags($productResults[$i]['description']));
			}
			
			
			if (salePrice($productResults[$i]['price'], $productResults[$i]['sale_price']) == false) {
				$mobile_access->assign("TXT_PRICE", priceFormat($productResults[$i]['price'], true));
				$mobile_access->assign("TXT_SALE_PRICE", '');
				
			} else {
				$mobile_access->assign("TXT_PRICE","<span class='txtOldPrice'>".priceFormat($productResults[$i]['price'], true)."</span>");
				$salePrice = salePrice($productResults[$i]['price'], $productResults[$i]['sale_price']);
				$mobile_access->assign("TXT_SALE_PRICE", priceFormat($salePrice, true));
			}
	
			if (isset($_GET['add']) && isset($_GET['quan'])) {
				$mobile_access->assign("CURRENT_URL", str_replace(array("&amp;add=".$_GET['add'], "&amp;quan=".$_GET['quan']), '', currentPage()));
				
			} else {
				$mobile_access->assign("CURRENT_URL", currentPage());
			}
	
			if ($config['outofstockPurchase'] == true) {
				$mobile_access->assign("BTN_BUY", $lang['viewCat']['buy']);
				$mobile_access->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$mobile_access->parse("mobile_access.productTable.products.buy_btn");
			
			} else if ($productResults[$i]['useStockLevel'] == true && $productResults[$i]['stock_level']>0) {
				$mobile_access->assign("BTN_BUY", $lang['viewCat']['buy']);
				$mobile_access->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$mobile_access->parse("mobile_access.productTable.products.buy_btn");
			
			} else if ($productResults[$i]['useStockLevel'] == false) {
				$mobile_access->assign("BTN_BUY", $lang['viewCat']['buy']);
				$mobile_access->assign("PRODUCT_ID", $productResults[$i]['productId']);
				$mobile_access->parse("mobile_access.productTable.products.buy_btn");
			}
	
			$mobile_access->assign("BTN_MORE", $lang['viewCat']['more']);
			$mobile_access->assign("PRODUCT_ID", $productResults[$i]['productId']);
	
		if ($productResults[$i]['stock_level']<1 && $productResults[$i]['useStockLevel'] == true && $productResults[$i]['digital'] == false) {
				$mobile_access->assign("TXT_OUTOFSTOCK", $lang['viewCat']['out_of_stock']);
			} else {
				$mobile_access->assign("TXT_OUTOFSTOCK", '');
			}
			$mobile_access->assign("MIN_QUANTITY", $productResults[$i]['min_quantity']);
				$mobile_access->assign("SHIPPING", $productResults[$i]['shipping']);
			$mobile_access->parse("mobile_access.productTable.products");
		}
	}
	
	$mobile_access->assign("LANG_SORT", $lang['viewCat']['sort']);
	$mobile_access->parse("mobile_access.productTable");
	
} else if (!$productResults && isset($_REQUEST['searchStr'])) {
	$mobile_access->assign("TXT_NO_PRODUCTS", sprintf($lang['viewCat']['no_products_match'], htmlspecialchars(stripslashes($_REQUEST['searchStr']))));
	$mobile_access->parse("mobile_access.noProducts");

} else {
	$mobile_access->assign("TXT_NO_PRODUCTS", $lang['viewCat']['no_prods_in_cat']);
	$mobile_access->parse("mobile_access.noProducts");
}
 /*RELATED PRODUCTS WORKING :: START*/	
	   $relatedprodquery="select I.* from ".$glob['dbprefix']."ImeiUnlock_inventory as I inner join " .$glob['dbprefix']."ImeiUnlock_category as c on c.cat_id = I.cat_id where I.premium = '1' order by productId DESC";
	
		$relatedProducts = $db->select($relatedprodquery);
		//   echo "<PRE>";
		 //  echo $relatedprodquery;
	//   print_r($relatedProducts);
							if ($relatedProducts) {
				for ($i=0; $i<count($relatedProducts); $i++) {
				
				
					if (($val = prodAltLang($relatedProducts[$i]['productId'])) == TRUE) {
						$relatedProducts[$i]['name'] = $val['name'];
					}
					$thumbRootPath = imgPath($relatedProducts[$i]['image'], true, $path="root");
					$thumbRelPath = imgPath($relatedProducts[$i]['image'], true, $path="rel");
				
					if (file_exists($thumbRootPath) && !empty($relatedProducts[$i]['image'])) {
						$mobile_access->assign("VAL_IMG_SRC",$thumbRelPath);
					} else {
						$mobile_access->assign("VAL_IMG_SRC",$GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
					}
					
					if (!salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price'])) {
						$mobile_access->assign("TXT_PRICE", priceFormat($relatedProducts[$i]['price'], true)." / <span class='pricetext'>per piece</span>");                       $mobile_access->assign("P","style='display:none;'");
					} else {
						$mobile_access->assign("TXT_PRICE","<span class='txtOldPrice'>".priceFormat($relatedProducts[$i]['price'], true)." / per piece</span>");
						 $mobile_access->assign("P","");	
					}
				$salePrice = salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price']);
					$mobile_access->assign("TXT_SALE_PRICE", priceFormat($salePrice, true));
					
					$mobile_access->assign("VAL_PRODUCT_ID", $relatedProducts[$i]['productId']);
					$mobile_access->assign("VAL_PRODUCT_NAME",validHTML($relatedProducts[$i]['name']));
					$mobile_access->assign("VAL_PRODUCT_TITLE",validHTML($relatedProducts[$i]['title']));
					$mobile_access->assign("VAL_PRODUCT_LOCATION",validHTML($relatedProducts[$i]['location']));
					
					$mobile_access->parse("mobile_access.related_products.repeat_prods");			
				
							}
				$mobile_access->assign("LANG_RELATED_PRODUCTS",$lang['viewProd']['related_products']);
				$mobile_access->parse("mobile_access.related_products");
			}
		if(isset($_COOKIE['recentview'])){
		foreach($_COOKIE['recentview'] as $key){
			$sql=$db->select("select productId, image from ".$glob['dbprefix']."ImeiUnlock_inventory where productId =".$db->mySQLSafe($_COOKIE['recentview'][$key]));
			for($h=0;$h<count($sql);$h++){
			$mobile_access->assign('PROD_ID',$sql[$h]['productId']);	
			 $thumbRootPath	= imgPath($sql[$h]['image'], true, 'root');
		$thumbRelPath	= imgPath($sql[$h]['image'], true, 'rel');
	

		if (file_exists($thumbRootPath) && !empty($sql[$h]['image'])) {

			$mobile_access->assign('REC_IMG_SRC', $thumbRelPath);

		} else {

			$mobile_access->assign('REC_IMG_SRC',$GLOBALS['rootRel'].'skins/'. SKIN_FOLDER . '/styleImages/thumb_nophoto.gif');

		}
	
			}
				$mobile_access->parse("mobile_access.recent.repeat");
			}
			$mobile_access->parse("mobile_access.recent");
		}

		
		/*RELATED PRODUCTS WORKING :: END*/	

$mobile_access->parse("mobile_access");
$page_content = $mobile_access->text("mobile_access");
?>