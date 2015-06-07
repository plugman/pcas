<?php
/*
+--------------------------------------------------------------------------
|	viewFaqs.inc.php
|   ========================================
|	FAQ's Module
|	Created By FM ( WC )	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."viewFaqs.inc.php");
$viewFaqs = new XTemplate ("content".CC_DS."viewFaq.tpl");

      //for owner faq
	 $qryAllviewFaqs_owner = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_faqs where faq_status = 1 and type='1' ORDER BY faq_id ASC";
	 $results_owner = $db->select($qryAllviewFaqs_owner);
     //for renter faq
	 $qryAllviewFaqs_renter = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_faqs where faq_status = 1 and type='0' ORDER BY faq_id ASC";
	 $results_renter = $db->select($qryAllviewFaqs_renter);
	  //for other
	 $qryAllviewFaqs_other = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_faqs where faq_status = 1 and type='2'";
	 $results_other = $db->select($qryAllviewFaqs_other);
	 //for policies site docs
	 $policiesdocs=$db->select("SELECT * from ".$glob['dbprefix']."ImeiUnlock_docs where doc_id in(18,19,20)");
	 $resultsForeign = $db->select("SELECT faq_master_id as faq_id ,faq_title, faq_description from ".$glob['dbprefix']."ImeiUnlock_faqs_lang WHERE faq_lang = '" . LANG_FOLDER . "'");
	 $foreigndocresult = $db->select("SELECT doc_master_id as doc_id , doc_name, doc_content FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_master_id IN (18,19,20) AND doc_lang=".$db->mySQLSafe(LANG_FOLDER));
	if($resultsForeign){
		 //for owner faq language
		for($i=0;$i<count($results_owner);$i++){
			if (is_array($resultsForeign)) {
				for ($k=0; $k<count($resultsForeign); $k++) {
					if ($resultsForeign[$k]['faq_id'] == $results_owner[$i]['faq_id']) {
						$results_owner[$i]['faq_title'] = $resultsForeign[$k]['faq_title'];
						$results_owner[$i]['faq_description'] = $resultsForeign[$k]['faq_description'];
					}
				}
			}
		}
		 //for renter language
			for($i=0;$i<count($results_renter);$i++){
			if (is_array($resultsForeign)) {
				for ($k=0; $k<count($resultsForeign); $k++) {
					if ($resultsForeign[$k]['faq_id'] == $results_renter[$i]['faq_id']) {
						$results_renter[$i]['faq_title'] = $resultsForeign[$k]['faq_title'];
						$results_renter[$i]['faq_description'] = $resultsForeign[$k]['faq_description'];
					}
				}
			}
		}
		 //for other faq language
			for($i=0;$i<count($results_other);$i++){
			if (is_array($resultsForeign)) {
				for ($k=0; $k<count($resultsForeign); $k++) {
					if ($resultsForeign[$k]['faq_id'] == $results_other[$i]['faq_id']) {
						$results_other[$i]['faq_title'] = $resultsForeign[$k]['faq_title'];
						$results_other[$i]['faq_description'] = $resultsForeign[$k]['faq_description'];
					}
				}
			}
		}
		 //for policies faq language
			for($i=0;$i<count($policiesdocs);$i++){
			if (is_array($foreigndocresult)) {
				for ($k=0; $k<count($foreigndocresult); $k++) {
					if ($foreigndocresult[$k]['doc_id'] == $policiesdocs[$i]['doc_id']) {
						$policiesdocs[$i]['doc_name'] = $foreigndocresult[$k]['doc_name'];
						$policiesdocs[$i]['doc_content'] = $foreigndocresult[$k]['doc_content'];
					}
				}
			}
		}
		
	}
$viewFaqs->assign("LANG_FAQ_TITLE",$lang['viewFaqs']['faqs_heading']);
$viewFaqs->assign("LANG_FAQ_DESC",$lang['viewFaqs']['faqs_desc']);
$viewFaqs->assign("LANG_FAQ_HEAD1",$lang['viewFaqs']['faqs_head1']);
$viewFaqs->assign("LANG_FAQ_HEAD2",$lang['viewFaqs']['faqs_head2']);
$viewFaqs->assign("LANG_FAQ_HEAD3",$lang['viewFaqs']['faqs_head3']);
$viewFaqs->assign("LANG_FAQ_HEAD4",$lang['viewFaqs']['faqs_head4']);

if ($config['seftags']) {
	
			$prevDirSymbol				= $config['dirSymbol'];
			$config['dirSymbol']		= ' - ';
			$meta['siteTitle']			= $config['siteTitle'];
			$config['dirSymbol']		= $prevDirSymbol;

			$meta['metaDescription']	= strip_tags($config['metaDescription']);		
			$meta['sefSiteTitle']		= ($config['faqTitle']!="")? $config['faqTitle']:$config['siteTitle'];
			$meta['sefSiteDesc']		= ($config['faqmetaDescription']!="")? $config['faqmetaDescription']:$config['metaDescription'];
			$meta['sefSiteKeywords']	= ($config['faqmetaKeyWords']!="")? $config['faqmetaKeyWords']:$config['metaKeyWords'];
	
}

## build attributes
 //for owner faq
if ($results_owner) {
	for ($i=0; $i<count($results_owner); $i++){
		$results_owner[$i]['faq_description'] = stripslashes($results_owner[$i]['faq_description']);

		$viewFaqs->assign("DATA", $results_owner[$i]);
		$viewFaqs->parse("viewAllfaq.viewFaqs_owner_true.faq_detail_owner");		
	}
	$viewFaqs->parse("viewAllfaq.viewFaqs_owner_true");
	
}  else {
	$viewFaqs->assign("LANG_NO_viewFaqs_owner",$lang['viewFaqs']['no_faqs']);
	$viewFaqs->parse("viewAllfaq.viewFaqs_owner_false");
}
 //for renter faq
if ($results_renter) {
	for ($J=0; $J<count($results_renter); $J++){
		$results_renter[$J]['faq_description'] = stripslashes($results_renter[$J]['faq_description']);
		$viewFaqs->assign("DATA", $results_renter[$J]);
		$viewFaqs->parse("viewAllfaq.viewFaqs_renter_true.faq_detail_renter");		
	}
	$viewFaqs->parse("viewAllfaq.viewFaqs_renter_true");
	
}  else {
	$viewFaqs->assign("LANG_NO_viewFaqs_owner",$lang['viewFaqs']['no_faqs']);
	$viewFaqs->parse("viewAllfaq.viewFaqs_renter_false");
}
 //for other faq
if ($results_other) {
		
	for ($m=0; $m < count($results_other); $m++){
		$results_other[$m]['faq_description'] = stripslashes($results_other[$m]['faq_description']);
		$viewFaqs->assign("DATA", $results_other[$m]);
		$viewFaqs->parse("viewAllfaq.viewFaqs_otherss_true.faq_detail_otherss");		
	}
	$viewFaqs->parse("viewAllfaq.viewFaqs_otherss_true");
	
}  else {
	$viewFaqs->assign("LANG_NO_viewFaqs_owner",$lang['viewFaqs']['no_faqs']);
	$viewFaqs->parse("viewAllfaq.viewFaqs_otherss_false");
}
 //for policies site docs
if ($policiesdocs) {
	for ($k=0; $k<count($policiesdocs); $k++){
		$viewFaqs->assign("DATA", $policiesdocs[$k]);
		$viewFaqs->parse("viewAllfaq.policy_true.policy_detail");		
	}
	$viewFaqs->parse("viewAllfaq.policy_true");
	
}  else {
	$viewFaqs->assign("LANG_NO_POLICY",$lang['viewFaqs']['no_policy']);
	$viewFaqs->parse("viewAllfaq.policy_false");
}
$meta['siteTitle']   = "Frequently Asked Questions - IMEI Unlock";
$viewFaqs->parse("viewAllfaq");
$page_content = $viewFaqs->text("viewAllfaq");
?>
