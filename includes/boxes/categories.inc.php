<?php
/*
+--------------------------------------------------------------------------
|	categories.inc.php
|   ========================================
|	Categories Box	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die('Access Denied');

// include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."categories.inc.php");

$menucache	= new cache('menu.'.SKIN_FOLDER.'.'.LANG_FOLDER);
$menuhtml	= $menucache->readCache();

if ($menucache->cacheStatus) {
	## Load new cached HTML menu, if it exists
	$box_content = $menuhtml;
} else {
	$box_content = new XTemplate("boxes".CC_DS."categories.tpl");
	$box_content->assign('LANG_CATEGORY_TITLE', $lang['categories']['shop_by_cat']);
	$box_content->assign('LANG_HOME', $lang['categories']['homepage']);
	
	$cache = new cache(LANG_FOLDER.'.boxes.categories');
	$treeData = $cache->readCache();
	
	if (!$cache->cacheStatus) {
		$resultsForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = ".$db->mySQLSafe(LANG_FOLDER));
		buildCatTree_tangible($treeData, $treekey = 0);
		$cache->writeCache($treeData);
	}
	
	if (is_array($treeData)) {
		for ($i=0; $i<count($treeData); $i++) {
			## Useful info to debug with
		#	echo $treeData[$i]['cat_name'].", cat_id=".$treeData[$i]['cat_id'] .", noProducts=".$treeData[$i]['noProducts'] .", cat_father_id=".$treeData[$i]['cat_father_id'] .", level=". $treeData[$i]['level']."<hr>";
			$upperlevel = (!isset($treeData[$i+1]['level'])) ? 1 : $upperlevel = $treeData[$i+1]['level'];
			
			if ($treeData[$i]['level'] > $upperlevel) {
				for ($n=0; $n<($treeData[$i]['level']-$upperlevel); $n++){
					$box_content->parse('categories.a.ul_end');
				}
				$box_content->parse('categories.a.li_end');
			} else if ($treeData[$i]['level'] == $upperlevel) {
				$box_content->parse('categories.a.li_end');
			}
			$box_content->parse('categories.a.li_start');
			$box_content->assign('DATA', $treeData[$i]);
			$box_content->parse('categories.a');
			if ($treeData[$i]['level']<$treeData[$i+1]['level']) {
				 $box_content->parse('categories.a.ul_start');
			}
		}
	}
	
	## Check if there are sale items
	if ($config['saleMode']) {
		$sale_items	= $db->select("SELECT COUNT(`productId`) AS count FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE disabled = '0' AND sale_price > 0");
		if ($sale_items[0]['count'] || $config['show_empty_cat']) {
			$box_content->assign('LANG_SALE_ITEMS', $lang['categories']['sale_items']);
			$box_content->parse('categories.sale');
		}
	}
	
	$gc = fetchDbConfig('gift_certs');
	if ($gc['status']) {
		$box_content->assign('LANG_GIFT_CERTS', $lang['categories']['gift_certificates']);
		$box_content->parse('categories.gift_certificates');		
	}
	$box_content->parse('categories');
	$box_content = $box_content->text('categories');
	
	## Cache the menu HTML - should speed things up dramatically
	$menucache->writeCache($box_content);
}
?>