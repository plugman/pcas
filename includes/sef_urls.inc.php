<?php
/*
+--------------------------------------------------------------------------
|	sef_urls.inc.php
|   ========================================
|	Search Engine Friendly URL's	
+--------------------------------------------------------------------------
*/

/*

LookBack:				All self-contained
LookBack + ForceType:	Needs the .htaccess RewriteRule file

*/

if (!defined('CC_INI_SET')) die("Access Denied");

function sef_script_name() {
	# Define the 'script' name for lookback requests
	global $config;
	return (!empty($config['sefscriptname'])) ? $config['sefscriptname'] : 'index';
}

function sef_get_base_url() {
	global $config;
	
	switch ($config['sefserverconfig']) {
		case 1:
		case 2:
			## LookBack
			return sef_script_name() . '.php/';
			break;
		default:
			## Mod_Rewrite 
			return '';
	}
}

function generateQueryStr($query) {
	## check if there is an ampersand first otherwise just return the same string
	if (!empty($query)) {
		if (preg_match('#^(\&amp\;|\&)(.*)$#i', $query, $matches)) {
			if (strlen($matches[2]) > 0) return '?'.$matches[2];
		}
		return '?'.$query;
	}
}

function generateSafeUrls($url = '') {
	## normalize accented characters
	$url = strtr($url, "\xA1\xAA\xBA\xBF\xC0\xC1\xC2\xC3\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD8\xD9\xDA\xDB\xDD\xE0\xE1\xE2\xE3\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF8\xF9\xFA\xFB\xFD\xFF", "_ao_AAAAACEEEEIIIIDNOOOOOUUUYaaaaaceeeeiiiidnooooouuuyy"); 
	## further character processing
	$url = strtr($url, array("\xC4"=>"Ae", "\xC6"=>"AE", "\xD6"=>"Oe", "\xDC"=>"Ue", "\xDE"=>"TH", "\xDF"=>"ss", "\xE4"=>"ae", "\xE6"=>"ae", "\xF6"=>"oe", "\xFC"=>"ue", "\xFE"=>"th"));
	## make sure its only english and dashes
	$search = array("/[^a-zA-Z0-9\/]/", "/--+/");
	$replace = array("-", "-");
	$url = sef_get_base_url().preg_replace($search, $replace, $url);
	## return safe url
	return($url);
}


function generateCategoryUrl($catid) {
	global $glob, $db, $config, $lang_folder;
	
	$sefpre	= ($config['sefserverconfig'] == 0 || $config['sefserverconfig'] == 3 || $config['sefserverconfig'] == 4) ? 'cat_' : 'c_';
	$ext	= ($config['sefserverconfig'] == 3) ? '.php' : '.html';
	
	if (is_numeric($catid)) {
		
		$cache = new cache('sef.categories.'.$catid);
		$sef_categories = $cache->readCache();
		
		if (!$cache->cacheStatus) {
			$query = sprintf("SELECT cat_name, cat_id, cat_father_id, seo_custom_url FROM %sImeiUnlock_category WHERE cat_id=%d", $glob['dbprefix'], $catid);
			$sef_categories = $db->select($query);
			$cache->writeCache($sef_categories);
		}
		
		if (isset($sef_categories[0]['seo_custom_url']) && !empty($sef_categories[0]['seo_custom_url'])) {
			$cat = str_replace('\\', '/', $sef_categories[0]['seo_custom_url']);
		} else {
			$prevDirSymbol		= $config['dirSymbol'];
			$config['dirSymbol']= '/';
			
			$prevLang 			= $lang_folder;
			$lang_folder		= $config['defaultLang'];
			
			$cat = getCatDir($sef_categories[0]['cat_name'], $sef_categories[0]['cat_father_id'], $sef_categories[0]['cat_id'], false, true);
			
			$config['dirSymbol']= $prevDirSymbol;
			$lang_folder		= $prevLang;
		}
	} else {
		## Sale Items
		$cat = $catid;
	}
	
	return strtolower(generateSafeUrls($cat)).'/'.$sefpre.$catid.$ext;
}

function generateProductUrl($productid) {
	global $glob, $db, $config, $lang_folder;
	
	$cache = new cache('sef.products.'.$productid);
	$sef_products = $cache->readCache();
	
	if (!$cache->cacheStatus) {
		$query			= sprintf("SELECT I.productId, I.name, I.cat_id, I.seo_custom_url, C.cat_name, C.cat_father_id FROM %1\$sImeiUnlock_inventory AS I INNER JOIN %1\$sImeiUnlock_category AS C ON I.cat_id = C.cat_id WHERE I.productId='%2\$d' AND I.cat_id > 0", $glob['dbprefix'], $productid);
		$sef_products	= $db->select($query);
		$cache->writeCache($sef_products);
	}
	
	$sefpre			= ($config['sefserverconfig'] == 0 || $config['sefserverconfig'] == 3 || $config['sefserverconfig'] == 4) ? 'prod_' : 'p_';	
	$ext			= ($config['sefserverconfig'] == 3) ? '.php' : '.html';
	
	if (isset($sef_products[0]['seo_custom_url']) && !empty($sef_products[0]['seo_custom_url'])) {
		$prod		= str_replace('\\', '/', $sef_products[0]['seo_custom_url']);
	} else {
		$prevDirSymbol			= $config['dirSymbol'];
		$config['dirSymbol']	= '/';
		$prevLang				= $lang_folder;
		$lang_folder			= $config['defaultLang'];
		
		$prod = $glob['rootRel'].getCatDir($sef_products[0]['cat_name'], $sef_products[0]['cat_father_id'], $sef_products[0]['cat_id'], false, true) . '/'.$sef_products[0]['name'];
		
		$config['dirSymbol']	= $prevDirSymbol;
		$lang_folder 			= $prevLang;
	}
	return strtolower(generateSafeUrls($prod).'/'.$sefpre.$productid.$ext);
}


function generateDocumentUrl($docid) {
	global $glob, $db, $config;		

	$query = "SELECT doc_name FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id='".$docid."'"; 
	$sef_documents = $db->select($query);
	
	$sefpre = ($config['sefserverconfig'] == 0 || $config['sefserverconfig'] == 3 || $config['sefserverconfig'] == 4) ? 'info_' : 'i_';
	$ext = ($config['sefserverconfig'] == 3) ? '.php' : '.html';

	$doc = $sef_documents[0]['doc_name'];
	
	return strtolower(generateSafeUrls($doc).'/' . $sefpre . $docid . $ext);
}

function generateTellFriendUrl($docid) {
	global $glob, $db, $config;		

	$sefpre = ($config['sefserverconfig'] == 0 || $config['sefserverconfig'] == 3 || $config['sefserverconfig'] == 4) ? 'tell_' : 't_';
	$ext	= ($config['sefserverconfig'] == 3) ? '.php' : '.html';
	
	$doc = generateSafeUrls('tellafriend'). "/" . $sefpre . $docid . $ext;
	
	return strtolower($doc);
}

function generateSearchUrl($searchStr) {
	global $config;
	
	$ext	= ($config['sefserverconfig'] == 3) ? '.php' : '.html';
	return generateSafeUrls().'search_'.$searchStr.$ext;
}

function sef_rewrite_urls($page) {
	global $config, $glob;
	
	if ($config['sef']) {
		
		$store_url = (detectSSL()) ? $config['storeURL_SSL'] : $glob['storeURL'];
		
		$search = array(
			'#href=[\"\'][a-z/]*index\.php\?_a=(search|viewcat|viewprod|tellafriend|viewdoc)\&?(amp;)[a-z]+=([a-z0-9\+]+)?([^\"\']*)[\"\']#ie',
			
		#	'#(action|background|href|src|value)\=([\"\'])(index.php|(?![a-z]+:|/|\"|\'|\#|\?))([^\'\"]*)([\"\'])#i',
			'#(\"|\')(index\.php)#i',
			'#(href|src|action|background)\=([\"\'])(?![a-z]+:|/|\"|\'|\#|\?)([^\'\"]*)([\"\'])#i',
		);
		$replace = array(
			'"href=\"".generateXurl(\'$3\', \'$1\').generateQueryStr(\'$4\')."\""',
			
		#	'$1=$2'.$GLOBALS['rootRel'].'$3$4$5',
			'$1'.$store_url.'/$2',
			'$1=$2'.$store_url.'/$3$4',
		);		
		$page = preg_replace($search, $replace, $page);
		## preg_match_all?
	}
	return $page;
}

function generateXurl($id, $type) {
	switch (strtolower($type)) {
		case 'search':
			return generateSearchUrl($id);
			break;
		case 'tellafriend':
			return generateTellFriendUrl($id);
			break;
		case 'viewcat':
			return generateCategoryUrl($id);
			break;
		case 'viewdoc':
			return generateDocumentUrl($id);
			break;
		case 'viewprod':
			return generateProductUrl($id);
			break;
	}
}


function sefHtmlWalk(&$input, $key) {
	$input = htmlspecialchars($input, ENT_COMPAT, 'UTF-8');
}


function sefMetaTitle($glue = ' - ') {
	global $config, $meta;
	
	if ($config['seftags'] == false) {
		$title[] = $meta['siteTitle'];
	} else {
	
		$seftitle = '';
		
		if ($config['seftags'] == '1') {
			## Combine
			if (!empty($meta['sefSiteTitle']))	$title[] = $meta['sefSiteTitle'];
			if (!empty($meta['siteTitle']))		$title[] = $meta['siteTitle'];
			
		} else if ($config['seftags'] == '2') {
			## Override
			if (!empty($meta['sefSiteTitle'])) {
				$title[] = $meta['sefSiteTitle'];
			} else {
				if (!empty($meta['siteTitle'])) {
					$title[] = $meta['siteTitle'];
				}
			}
		}
		if (!empty($config['siteTitle']))	$title[] = $config['siteTitle'];
		//array_walk($title, 'sefHtmlWalk');
	}
	## Fix for bug 1247
	if(!is_array($title)) {
		$title[] = $meta['siteTitle'];
	}
	return implode($glue, $title);
}

function sefMetaDesc() {
	global $config, $meta;	

	if ($config['seftags'] == '0') {
		$sefdesc = $config['metaDescription'];
	} else if ($config['seftags'] == '1') {
		$sefdesc = $meta['sefSiteDesc'];
		if (strlen($sefdesc) > 0 && strlen($config['metaDescription']) > 0) {
			$sefdesc = $sefdesc . " - ";
		}
		$sefdesc = $sefdesc . $config['metaDescription'];
	
	} else {
		$sefdesc = $meta['sefSiteDesc'];
		$sefdesc = (strlen($sefdesc) == 0) ? $config['metaDescription'] : $meta['sefSiteDesc'];
	}
	return $sefdesc;
}


function sefMetaKeywords() {
	global $config, $meta;

	if ($config['seftags'] == '0') {
		$sefkeywords = $config['metaKeyWords'];
		
	} else if ($config['seftags'] == '1') {
		$sefkeywords = $meta['sefSiteKeywords'];
		
		if (strlen($sefkeywords ) > 0 && strlen($config['metaKeyWords']) > 0) {
			$sefkeywords = $sefkeywords.",";
		}
		$sefkeywords = $sefkeywords . $config['metaKeyWords'];
	
	} else {
		$sefkeywords = $meta['sefSiteKeywords'];
		$sefkeywords = (strlen($sefkeywords) == 0) ? $config['metaKeyWords'] : $meta['sefSiteKeywords'];
	}
	return $sefkeywords;
}
?>
