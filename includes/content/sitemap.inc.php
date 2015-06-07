<?php
/*
+--------------------------------------------------------------------------
|	sitemap.inc.php
|   ========================================
|	Build Links to FAQ's
|	Created By Naveed Ul Islam ( SabriTech )	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$sitemap = new XTemplate ("content".CC_DS."sitemap.tpl");
		$currPage = currentPage();
	$typeresults = $db->select("SELECT cat_name,cat_id FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE hide =0 ORDER BY cat_name ASC");
	
if($typeresults){
	for ($i=0; $i<count($typeresults); $i++){
		$typeresults[$i]["cat_name"] =  validHTML($typeresults[$i]["cat_name"]);
		$typeresults[$i]["cat_id"] = $typeresults[$i]["cat_id"];
		$sitemap->assign("DATA", $typeresults[$i]);
		$proresults = $db->select("SELECT productId, name FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE cat_id =".$db->mysqlsafe($typeresults[$i]["cat_id"])." AND disabled =0 ORDER BY name ASC");
		if($proresults){
			for($j=0; $j<count($proresults); $j++){
			$sitemap->assign("PRO_NAME", $proresults[$j]['name']);
			$sitemap->assign("PRODUCT_ID", $proresults[$j]['productId']);
			$sitemap->parse("sitemap.alphabets_false.types_true.types_detail.productall");	
			}
			
		}
		$sitemap->parse("sitemap.alphabets_false.types_true.types_detail");		
	}
		$sitemap->parse("sitemap.alphabets_false.types_true");
}else{
	$sitemap->assign("LANG_NO_TYPES",$lang['sitemap']['notypes']);
	$sitemap->parse("sitemap.alphabets_false.types_false");
}
	
	$sitemap->parse("sitemap.alphabets_false");



$sitemap->parse("sitemap");
$page_content = $sitemap->text("sitemap");
?>
