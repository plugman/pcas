<?php
/*
+--------------------------------------------------------------------------
|	saleItems.inc.php
|   ========================================
|	Sales Items Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

## include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."saleItems.inc.php");

## query database
$cache = new cache('boxes.saleItems');
$saleItems = $cache->readCache();

if (!$cache->cacheStatus) {
	$saleItems = $db->select("SELECT I.name, I.productId, I.price, I.sale_price, I.price-I.sale_price AS saving FROM ".$glob['dbprefix']."ImeiUnlock_inventory AS I, ".$glob['dbprefix']."ImeiUnlock_category AS C WHERE C.cat_id = I.cat_id AND (C.cat_desc != '##HIDDEN##' OR C.cat_desc IS NULL) AND I.disabled = '0' AND I.price > I.sale_price AND I.sale_price > 0 AND I.cat_id > 0 ORDER BY saving DESC",$config['noSaleBoxItems']);
	$cache->writeCache($saleItems);
}

if ($saleItems && $config['saleMode']>0) {
	$salePrice = 0;
	$box_content=new XTemplate ("boxes".CC_DS."saleItems.tpl");
	$box_content->assign("LANG_SALE_ITEMS_TITLE",$lang['saleItems']['sale_items']);

	for ($i=0; $i<count($saleItems); $i++){
		if (($val = prodAltLang($saleItems[$i]['productId'])) == true) {
			$saleItems[$i]['name'] = $val['name'];
		}
		$salePrice = salePrice($saleItems[$i]['price'], $saleItems[$i]['sale_price']);
		$saleItems[$i]['name'] = validHTML($saleItems[$i]['name']);
		$box_content->assign("DATA",$saleItems[$i]);
		$box_content->assign("SAVING",priceFormat($saleItems[$i]['price'] - $salePrice,true));
		$box_content->assign("LANG_SAVE",$lang['saleItems']['save']);
		$box_content->parse("sale_items.li");
		
	}
	$box_content->parse("sale_items");
	$box_content = $box_content->text("sale_items");
} else {
	$box_content = '';
}
?>