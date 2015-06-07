<?php

/*

+--------------------------------------------------------------------------

|	viewtestimonial.inc.php

|   ========================================

|	List all testimonial	

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");

$meta['siteTitle'] = "Read our customers testimonials   ";

$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;

$qrytestimonial = "SELECT title, review, name,email FROM ".$glob['dbprefix']."ImeiUnlock_testimonials WHERE approved = '0' ORDER BY testimonial_id DESC" ;

$results = $db->select($qrytestimonial, $config['nooftestimonial'] ? $config['nooftestimonial'] : 25 , $page);

// include lang file

$lang = getLang("includes".CC_DS."content".CC_DS."testimonials.inc.php");

$viewtestimonial = new XTemplate ("content".CC_DS."testimonials.tpl");

$viewtestimonial->assign("LANG_TESTIMONIAL_TITLE",$lang['testimonial']['testimonial_title']);

## build attributes

if ($results) {

	for ($i=0; $i<count($results); $i++){

		$results[$i]["review"] = strip_tags($results[$i]["review"]);

		$results[$i]["title"] = strip_tags($results[$i]["title"]);	

		$results[$i]["name"] = strip_tags($results[$i]["name"]);
		$results[$i]["email"] = strip_tags($results[$i]["email"]);		

		$viewtestimonial->assign("DATA", $results[$i]);

		$viewtestimonial->parse("viewtestimonial.testimonial_true.testimonial_detail");		

	}

		$viewtestimonial->assign("LANG_VIEW_ALL_TESTIMONIAL",$lang['testimonial']['view_all_testimonial']);

		$viewtestimonial->parse("viewtestimonial.testimonial_true");

} else {

	$viewtestimonial->assign("LANG_NO_TESTIMONIAL",$lang['testimonial']['no_testimonial']);

	$viewtestimonial->parse("viewtestimonial.testimonial_false");

}

 $totalNotestimonial = $db->numrows($qrytestimonial);



$pagination = paginate($totalNotestimonial, $config['nooftestimonial'] ? $config['nooftestimonial'] : 25 , $page, "page");



if (!empty($pagination)) {

	$viewtestimonial->assign("PAGINATION", $pagination);

	//$viewtestimonial->parse("view_cat.pagination_top");

	$viewtestimonial->parse("viewtestimonial.pagination_bot");

}



$viewtestimonial->parse("viewtestimonial");

$page_content = $viewtestimonial->text("viewtestimonial");

?>