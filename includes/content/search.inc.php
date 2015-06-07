<?php
/*
+--------------------------------------------------------------------------
|	search.inc.php
|   ========================================
|	Advanced Product Search
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");
$lang = getLang("includes".CC_DS."content".CC_DS."search.inc.php");

$content = new XTemplate ("content".CC_DS."search.tpl");

$sql		= sprintf("SELECT cat_id, cat_father_id, cat_name FROM %sImeiUnlock_category WHERE hide = '0' AND (cat_desc != '##HIDDEN##' OR cat_desc IS NULL) AND noProducts >= 1 GROUP BY cat_father_id, cat_id", $glob['dbprefix']);
$results	= $db->select($sql);

foreach ($results as $result) {
	$options[$result['cat_id']] = getCatDir($result['cat_name'], $result['cat_father_id'], $result['cat_id']);
}
asort($options);

foreach ($options as $option_id => $option_name) {
	$content->assign('OPTION_VALUE', $option_id);
	$content->assign('OPTION_TITLE', $option_name);
	$content->parse("adv_search.adv_search_category");
}

$content->assign('LANG_SEARCH_TITLE', $lang['search']['search_title']);
$content->assign('LANG_SEARCH_KEYWORD', $lang['search']['search_keyword']);
$content->assign('LANG_SEARCH_PRICE', $lang['search']['search_price']);
$content->assign('LANG_SEARCH_INSTOCK', $lang['search']['search_instock']);
$content->assign('LANG_SEARCH_CATEGORY', $lang['search']['search_category']);
$content->assign('LANG_SEARCH_CATEGORY_HELP', $lang['search']['search_category_help']);
$content->assign('LANG_SEARCH_SUBMIT', $lang['search']['search_submit']);
$content->assign('LANG_SEARCH_RESET', $lang['search']['search_reset']);


$content->parse("adv_search");
$page_content = $content->text("adv_search");

?>