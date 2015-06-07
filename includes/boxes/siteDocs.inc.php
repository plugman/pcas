<?php

/*

+--------------------------------------------------------------------------

|	siteDocs.inc.php

|   ========================================

|	Build Links to Site Docs	

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");



## query database

$cache = new cache('boxes.siteDocs.master');

$results = $cache->readCache();



if (!$cache->cacheStatus) {

	$results = $db->select("SELECT doc_id, doc_name, doc_url, doc_url_openin FROM ".$glob['dbprefix']."ImeiUnlock_docs Where doc_id not in(20,21,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,42) ORDER BY doc_order ASC");

	$cache->writeCache($results);

}



$cache = new cache('boxes.siteDocs.foreign.'.LANG_FOLDER);

$foreignDocs = $cache->readCache();



if (!$cache->cacheStatus) {

	$foreignDocs = $db->select("SELECT doc_master_id as doc_id, doc_name FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_lang = '" . LANG_FOLDER . "'");

	$cache->writeCache($foreignDocs); 

}



$box_content = new XTemplate('boxes'.CC_DS.'siteDocs.tpl');



## Build attributes

if ($results) {

	foreach ($results as $i => $result) {

		if ($i<count($results)-1) {

			$box_content->parse('site_docs.a.sep');

		}

		if (is_array($foreignDocs)) {

			foreach ($foreignDocs as $key => $values) {

				if ($values['doc_id'] == $result['doc_id']) {

					$result['doc_name'] = $values['doc_name'];

				}

			}

		}

		$result['doc_name'] = validHTML($result['doc_name']);

		

		if (!isset($result['doc_url']) || empty($result['doc_url'])) {

			$result['doc_url']		= 'index.php?_a=viewDoc&amp;docId='.$result['doc_id'];

		}

		

		switch ($result['doc_url_openin']) {

			case '1':

				$result['doc_url_target'] = 'target="_blank"';

				break;

		#	case '2':

		#		$result['doc_url'] += '?KeepThis=true&amp;TB_iframe=true';

		#		$result['doc_url_target'] = 'class="thickbox"';

		#		break;

			default:

				$result['doc_url_target'] = '';

		}

		$box_content->assign('DATA', $result);

		$box_content->parse('site_docs.a');

	}

}


$box_content->parse('site_docs');

$box_content = $box_content->text('site_docs');

?>