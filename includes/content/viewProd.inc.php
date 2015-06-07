<?php
/*
+--------------------------------------------------------------------------
|	viewProduct.inc.php
|   ========================================
|	Displays the Product in Detail
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."viewProd.inc.php");
require_once($backPath."includes".CC_DS."functions.inc.php");
$view_prod = new XTemplate ("content".CC_DS."viewProd.tpl");
require_once"includes".CC_DS."boxes".CC_DS."testimonials.inc.php";
$view_prod->assign("TESTIMONIAL",$box_content);
// query database
if($_GET['productId']>0){
$digital=$db->select("select digital from ".$glob['dbprefix']."ImeiUnlock_inventory where productId=".$_GET['productId']);
}
if(isset($_GET['productId']) && $_GET['productId'] > 0 && $digital[0]['digital'] == 0)
	{
		$prdId =$_GET['productId'];
		
		if (!isset($_COOKIE['recentview'][$prdId]))
		{
			//$i++;
			setcookie("recentview[".$prdId."]", $prdId, time()+(3600*24*30));	
			
			
		}	
		
	}
if($_GET['added']==1) {		
		$view_prod->parse("view_prod.added");
	}
		$catquery		= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE type = '0' AND hide = '0' AND (cat_father_id = 0  OR cat_father_id =59) ORDER BY priority,cat_name ASC";


$catResult = $db->select($catquery); 

if(!empty($catResult)){
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
		if (($catval = catAltLang($catResult[$j]['cat_id'])) == true) {
			 $catResult[$j]['cat_name'] = $catval['cat_name'];
				}
		$queryall = "SELECT productId, name  FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE disabled='0' AND cat_id= ".$catResult[$j]['cat_id'];
		
		$ResultAll= $db->select($queryall);
		if(!empty($ResultAll)){ 
			$countAll = count($ResultAll);
			for($i=0; $i<$countAll;$i++){
				if (($val = prodAltLang($ResultAll[$i]['productId'])) == true) {
			$ResultAll[$i]['name'] = $val['name'];
				}
				if($j===0){
					$selResult["selected_name"] =  $ResultAll[0]['name'];
					$selResult["selected_id"] 	=  $ResultAll[0]['productId'];
				}
				if($ResultAll[$i]['productId']==$_GET['productId']){
				$selectedProd['name'] 		= $ResultAll[$i]['name'];
				$selectedProd['productId'] = $ResultAll[$i]['productId'];  
				}
				
				$view_prod->assign("ALL_PROD", $ResultAll[$i]);
				$view_prod->parse("view_prod.digital.prod_true.topcat.cat_loop.all.loop");
			}
			$view_prod->parse("view_prod.digital.prod_true.topcat.cat_loop.all");
			}
		$view_prod->assign("CAT_NAME", $catResult[$j]['cat_name']);
		$view_prod->parse("view_prod.digital.prod_true.topcat.cat_loop");
	}
	$view_prod->parse("view_prod.digital.prod_true.topcat");
}
if($_GET['productId']>0){
$_GET['productId'] = sanitizeVar($_GET['productId']);
$showDigital =0;
}else{
$showDigital =1;
$selectedProd['name'] 		= (isset($selResult["selected_name"])&&$selResult["selected_name"]!="" )?$selResult["selected_name"] :$ResultAll[0]['name'];
$_GET['productId']  = (isset($selResult["selected_id"])&& $selResult["selected_id"]!="" )?$selResult["selected_id"] :$ResultAll[0]['productId'];  
}


$query = "SELECT I.*, C.cat_name, C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory AS I LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_category AS C ON I.cat_id = C.cat_id WHERE I.disabled='0' AND I.productId = ".$db->mySQLSafe($_GET['productId']);

$prodArray = $db->select($query);
/*echo "<PRE>";
print_r($prodArray);*/
if($prodArray[0]['digital'] == 1 || $showDigital == 1){
if ($prodArray) {
	
	$meta['metaDescription'] = substr(strip_tags($prodArray[0]['description']), 0, 35);
	
	if($config['seftags']){
	
		// get the native language for the category name
		$resultForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '".LANG_FOLDER."' AND cat_master_id = ".$db->mySQLSafe($prodArray[0]['cat_id']));		
		
		if($resultForeign == TRUE){			
			$prodArray[0]['cat_name'] = $resultForeign[0]['cat_name'];		
		}
	
		// get alternate language title for this product
		$sefval = "";
		$sefLangProdTitle = $prodArray[0]['name'];
		if (($sefval = prodAltLang($prodArray[0]['productId'])) == TRUE){			
			// change the titles as they make more sense to the user if they are in their native languages
			// however to the search engine they will always be in the default language regardless as they can't change languages
			$sefLangProdTitle = $sefval['name'];	
			// don't worry about description it will always be in the default language regardless as search 
			// engines can't change languages	
		}
	
		// create title and metas
		$prevDirSymbol = $config['dirSymbol'];
		$config['dirSymbol'] = ' - ';
		
		if($config['sefprodnamefirst']){
			$meta['siteTitle'] = $sefLangProdTitle.$config['dirSymbol'].getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'], FALSE, TRUE, FALSE);
		} else {
			$meta['siteTitle'] = getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'], false, true).$config['dirSymbol'].$sefLangProdTitle;		
		}
		$config['dirSymbol'] = $prevDirSymbol;
		$meta['sefSiteTitle'] = $prodArray[0]['prod_metatitle']; 
		$meta['sefSiteDesc'] = $prodArray[0]['prod_metadesc'] ? $prodArray[0]['prod_metadesc'] : $meta['metaDescription'];
		$meta['sefSiteKeywords'] = $prodArray[0]['prod_metakeywords'];
	} else {
		$meta['siteTitle'] = $config['siteTitle']." - ".$prodArray[0]['name'];
	}
	
	$val = "";
	
	if(($val = prodAltLang($prodArray[0]['productId'])) == TRUE) {
				
		$prodArray[0]['name'] = $val['name'];
		$prodArray[0]['description'] = $val['description'];
			
	}

	// update amount of views
	$upPop['popularity'] = "popularity+1"; 
	$db->update($glob['dbprefix']."ImeiUnlock_inventory",$upPop,"productId = ".$db->mySQLSafe($_GET['productId']));
	
	if($_GET['notice']==1) {
		$view_prod->assign("LANG_OPTS_NOTICE",$lang['viewProd']['opts_notice']);
		$view_prod->parse("view_prod.digital.prod_true.opts_notice");
	}elseif($_GET['notice']==2 && $_GET['imei']> 0) {
		$view_prod->assign("LANG_OPTS_NOTICE","Following IMEI <span style='color:#FF3A3A; font-weight:bold;'>'".$_GET['imei']."'</span> is already in Process in our Database");
		$view_prod->parse("view_prod.digital.prod_true.opts_notice");
	}
	

	
	
	$view_prod->assign("LANG_PRODTITLE", $lang['viewProd']['product']);
		$view_prod->assign("LANG_DELTIMETXT", $lang['viewProd']['deltimelan']);
		$view_prod->assign("LANG_UNSURE",$lang['viewProd']['unsure']);
			$view_prod->assign("LANG_ORDERIMEI",$lang['viewProd']['orderimei']);
			$view_prod->assign("LANG_IMEILINK",$lang['viewProd']['imeilink']);
	$view_prod->assign("LANG_PRODINFO",$lang['viewProd']['product_info']);
	$view_prod->assign("LANG_FIRST_TO_REVIEW",$lang['viewProd']['first_to_review']);
	$view_prod->assign("LANG_PRICE",$lang['viewProd']['price']);
	$view_prod->assign("LANG_PRODCODE",$lang['viewProd']['product_code']);
	$view_prod->assign("LANG_TELLFRIEND",$lang['viewProd']['tellafriend']);
	$view_prod->assign("LANG_READ_REVIEWS",$lang['viewProd']['read_reviews']);
	$view_prod->assign("LANG_WRITE_REVIEWS",$lang['viewProd']['write_reviews']);
	$view_prod->assign("TXT_PRODTITLE",validHTML(stripslashes($prodArray[0]['name'])));
	$descp = strip_tags($prodArray[0]['description']);
	if(!empty($prodArray[0]['description']) && strlen($descp) > 3){
	$view_prod->assign("TXT_DESCRIPTION",$prodArray[0]['description']);
	}
	else 
	$view_prod->assign("TXT_DESCRIPTION", "Get your iPhone officially unlocked without jail breaking with a trust worthy company (staff based in Australia) - by whitelisting your IMEI in the Apple iTunes database.<br />
<br />
Our iPhone unlocks are permanent (whether iPhone or other handsets) legal, doesn't void your handset warranty. We guarantee you secure iOS (software) upgrade via iTunes in the future, enabling you to use your handset on any GSM network, worldwide.<br />
<br />
<br />
We provide quickest handset unlock service worldwide keeping it twice as cheap then competitors as we are direct source for most of the network unlocks. If you have a question about our services you can contact us via email &amp; live chat (24/7).<br />
<br />
100% no quibble money back guarantee.<br />
<br />
No need to run any complicated software - once we notify your handset has been locked, simply connect your iPhone to iTunes to complete your handset unlock.<br />
<br />
Effortless mobile unlocking services since 2006 with 120,000+ handsets unlocked to date.<br />
<br />
So! What are you waiting for? Just grab the best unlock deal &amp; get your iPhone unlocked today.<br />
<br />
We can also provide mobile unlock codes for Samsung, Blackberry, HTC, LG, NOKIA, Alcatel, Motorola, Acer, Dell, ZTE, Duru.");
	if($prodArray[0]['pro_title'])
	$view_prod->assign("TXT_PRO_TITLE",$prodArray[0]['pro_title']);	
	else
	$view_prod->assign("TXT_PRO_TITLE", "Unlocking Your Apple iPhone");	
	$sdescp = strip_tags($prodArray[0]['short_desc']);
	if(!empty($prodArray[0]['short_desc']) && strlen($sdescp) > 3){
	$view_prod->assign("TXT_SUPHANDSETS",$sdescp);
	}
	else 
	$view_prod->assign("TXT_SUPHANDSETS", "iPhone 2g, 3g, 3gs, 4, 4s, 5");	
	$excluded = array("add"=>1,"quan"=>1,"notice"=>1,"added"=>1);
	$view_prod->assign("CURRENT_URL", currentPage($excluded));
	
		if($cc_session->ccUserData['customer_type'] == 0){
		if (!salePrice($prodArray[0]['price'], $prodArray[0]['sale_price']) || $config['saleMode'] == false){
		$view_prod->assign("TXT_PRICE_VIEW", priceFormat($prodArray[0]['price'],true));
		$decprice = priceonly($prodArray[0]['price'],true);
		}
		else{
		$view_prod->assign("TXT_PRICE_VIEW", priceFormat($prodArray[0]['sale_price'],true));
		$decprice = priceonly($prodArray[0]['sale_price'],true);
		}
		}
		else{
			$wprice = getwprice($cc_session->ccUserData['customer_type'], $prodArray[0]['productId']);
			if($wprice > 0){
				$view_prod->assign("TXT_PRICE_VIEW", priceFormat($wprice, true));
				$decprice = priceonly($wprice,true);
				}
				else{
				if (!salePrice($prodArray[0]['price'], $prodArray[0]['sale_price']) || $config['saleMode'] == false){
				$view_prod->assign("TXT_PRICE_VIEW", priceFormat($prodArray[0]['price'],true));
				$decprice = priceonly($prodArray[0]['price'],true);
				}
				else{
				$view_prod->assign("TXT_PRICE_VIEW", priceFormat($prodArray[0]['sale_price'],true));
				$decprice = priceonly($prodArray[0]['sale_price'],true);
				}
				}
			}
		if($prodArray[0]['mulimei'] == 1){
			global $currencyVars;
		$view_prod->assign("SYMBL_LEFT", $currencyVars[0]['symbolLeft']);
		$view_prod->assign("PROPRICE", $decprice);
	$view_prod->parse("view_prod.digital.prod_true.prod_opts.multipleimei");
		}
	else
	$view_prod->parse("view_prod.digital.prod_true.prod_opts.singleimei");
	$view_prod->assign("TXT_PRODCODE", $prodArray[0]['productCode']);
	$view_prod->assign("TXT_SDESC", $prodArray[0]['sdesc']);
	$view_prod->assign("TXT_DELTIME", $prodArray[0]['deltime']);

	$view_prod->assign("LANG_HOME", $lang['viewProd']['home']);
	$view_prod->assign("CURRENT_DIR",getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'],$link=TRUE).$config['dirSymbol'].$prodArray[0]['name']);	

	$view_prod->assign("LANG_QUAN",$lang['viewProd']['quantity']);

	$view_prod->assign("PRODUCT_ID",$prodArray[0]['productId']);

	if (!empty($prodArray[0]['image'])) {
		$imgRelPath = imgPath($prodArray[0]['image'], false, 'rel');
		$view_prod->assign("IMG_SRC", $imgRelPath);
	} else {
		$view_prod->assign("IMG_SRC", "skins/". SKIN_FOLDER . "/styleImages/nophoto.gif");
	}
	if (!empty($prodArray[0]['image2'])) {
		$imgRelPath2 = imgPath($prodArray[0]['image2'], false, 'rel');
		$view_prod->assign("IMG_SRC2", $imgRelPath2);
	} else {
		$view_prod->assign("IMG_SRC2", "skins/". SKIN_FOLDER . "/styleImages/iphone.jpg");
	}
	if ($prodArray[0]['noImages']>0) {
	
		if (!isset($config['imgGalleryType']) || !$config['imgGalleryType']) {
			$view_prod->assign("LANG_MORE_IMAGES",$lang['viewProd']['popup_more_images']);
			$view_prod->parse("view_prod.digital.prod_true.popup_gallery");
		
		} else if ($config['imgGalleryType']) {
			
			$imgs = $db->select("SELECT img FROM ".$glob['dbprefix']."ImeiUnlock_img_idx WHERE productId = ".$db->mySQLsafe($prodArray[0]['productId'])." ORDER BY id ASC");
			if ($imgs) {
				foreach ($imgs as $img) {
					$thumbRootPath	= imgPath($img['img'], true, 'root');
					$thumbRelPath	= imgPath($img['img'], true, 'rel');
					
					if (file_exists($thumbRootPath)) {
						$sizeThumb	= getimagesize($thumbRootPath);
						$view_prod->assign("VALUE_THUMB_SRC", $thumbRelPath);
						$view_prod->assign("VALUE_THUMB_WIDTH", $sizeThumb[0]);
						
					} else {
						$view_prod->assign("VALUE_THUMB_SRC", imgPath($img['img'], false, 'rel'));
						$view_prod->assign("VALUE_THUMB_WIDTH", $config['gdthumbSize']);
					}
					
					$view_prod->assign("VALUE_IMG_SRC", imgPath($img['img'], false, 'rel'));
					$view_prod->assign("ALT_THUMB", $lang['viewProd']['thumb_alt']);
					$view_prod->parse("view_prod.digital.prod_true.image_gallery.img_repeat");
				}
				$view_prod->assign("IMAGE_GALLERY", $lang['viewProd']['image_gallery']);
				$view_prod->parse("view_prod.digital.prod_true.image_gallery");
			}
		}
	}
	
	## Review stars
	$commentQuery = sprintf("SELECT COUNT(id) as noComments FROM %sImeiUnlock_reviews WHERE approved = 1 AND productId = %d", $glob['dbprefix'], $prodArray[0]['productId']);
	$comments = $db->select($commentQuery);
	
	if ($comments[0]['noComments'] >= 1) {
		$reviewQuery = "SELECT COUNT(id) AS noReviews, AVG(rating) as aveRating FROM `".$glob['dbprefix']."ImeiUnlock_reviews` WHERE `type` = 0 AND approved = 1 AND productId =".$db->mySQLsafe($prodArray[0]['productId']);
		$review = $db->select($reviewQuery);
		
		if ($review[0]['noReviews'] >= 1) {
			
			for ($i=0; $i<5; $i++) {
				$view_prod->assign("VAL_STAR", starImg($i,$review[0]['aveRating']));
				$view_prod->parse("view_prod.prod_true.reviews_true.review_stars");	
			}
			$view_prod->assign("LANG_BASED_ON_X_REVIEWS", sprintf($lang['viewProd']['based_on_x_reviews'], $review[0]['noReviews']));
		}
		$view_prod->parse("view_prod.digital.prod_true.reviews_true");
		$view_prod->parse("view_prod.digital.prod_true.read_reviews");
	} else {
		
		$view_prod->parse("view_prod.digital.prod_true.reviews_false");
	}
	
	if (!$prodArray[0]['disabled']) {
		if ($config['outofstockPurchase']) {
		
			$view_prod->assign("BTN_ADDBASKET",$lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.digital.prod_true.buy_btn");
			
		} else if ($prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		
			$view_prod->assign("BTN_ADDBASKET", $lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.digital.prod_true.buy_btn");	
			
		} else if (!$prodArray[0]['useStockLevel']) {
	
			$view_prod->assign("BTN_ADDBASKET", $lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.digital.prod_true.buy_btn");
		}
	}

	$view_prod->assign('LANG_DIR_LOC', $lang['viewProd']['location']);

	if ($config['stockLevel'] && $prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		$view_prod->assign('TXT_INSTOCK', $lang['viewProd']['no_instock'].' '.$prodArray[0]['stock_level']);
		
	} else if ($prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		$view_prod->assign("TXT_INSTOCK",$lang['viewProd']['instock']);
	} 


	if ($prodArray[0]['stock_level']<1 && $prodArray[0]['useStockLevel'] && !$prodArray[0]['digital']) {
		$view_prod->assign('TXT_OUTOFSTOCK', $lang['viewProd']['out_of_stock']);
	}
	
	## Build SQL for product options
	$query		= sprintf("SELECT B.*, T.option_name, T.option_type, M.value_name FROM %1\$sImeiUnlock_options_bot AS B LEFT JOIN %1\$sImeiUnlock_options_mid AS M ON B.value_id = M.value_id, %1\$sImeiUnlock_options_top AS T WHERE B.option_id = T.option_id AND B.product = %2\$d ORDER BY T.option_id, M.value_name ASC", $glob['dbprefix'], $_GET['productId']);
	$options	= $db->select($query);
	
	if ($options) {
		$view_prod->assign('TXT_PROD_OPTIONS', $lang['viewProd']['prod_opts']);
		foreach ($options as $option) {
			if (!empty($option)) {
				if ($option['option_type'] == '0') {
					$selectArray[$option['option_id']][] = $option;
				} else {
					$inputArray[$option['option_id']][] = $option;
				}
			}
		}
		
		## Select based options
		if ($selectArray) {
			foreach ($selectArray as $option_id => $option) {
				foreach ($option as $values) {
					$view_prod->assign('VAL_OPTION_ID', $values['option_id']);
					$view_prod->assign('VAL_ASSIGN_ID', $values['assign_id']);
					
					$view_prod->assign('VAL_VALUE_NAME', htmlentities(stripslashes(str_replace(array('&#39;', '&amp;#39;'), "'", utf8_decode($values['value_name'])))));
				
					if ($values['option_price']>0) {
						$view_prod->assign('VAL_OPT_SIGN', $values['option_symbol']);
						$view_prod->assign('VAL_OPT_PRICE', priceFormat($values['option_price'], true));
						$view_prod->parse('view_prod.digital.prod_true.prod_opts.repeat_options.repeat_values.repeat_price');
					}
					$view_prod->parse('view_prod.digital.prod_true.prod_opts.repeat_options.repeat_values');
				}
				$view_prod->assign('VAL_OPTS_NAME', $option[0]['option_name']);
				$view_prod->parse('view_prod.digital.prod_true.prod_opts.repeat_options');
			}
		}
		
		## Text-based options
		if ($inputArray) {
			foreach ($inputArray as $option_id => $option) {
				foreach ($option as $value) {
					
					$view_prod->assign('VAL_VALUE_NAME', htmlentities(stripslashes($value['value_name'])));
					
					$view_prod->assign('VAL_ASSIGN_ID', $value['assign_id']);
					$view_prod->assign('VAL_OPTION_ID', $value['option_id']);
					
					if ($value['option_price']>0) {
						$view_prod->assign('VAL_OPT_SIGN', $value['option_symbol']);
						$view_prod->assign('VAL_OPT_PRICE', priceFormat($value['option_price'], true));
						$view_prod->parse('view_prod.digital.prod_true.prod_opts.text_opts.repeat_price');
					}
					
					if ($value['option_type'] == 2) {
						$view_prod->parse('view_prod.digital.prod_true.prod_opts.text_opts.textarea');
					} else {
						$view_prod->parse('view_prod.digital.prod_true.prod_opts.text_opts.textbox');
					}
				}
				$view_prod->assign('VAL_OPTS_NAME', $option[0]['option_name']);
				$view_prod->parse('view_prod.digital.prod_true.prod_opts.text_opts');
			}	
		}
		$view_prod->parse('view_prod.digital.prod_true.prod_opts');
	}

	// start product reviews/comment
	if ($_GET['review']=='write' && $prodArray) {
		
		if (isset($_POST['review'])) {
			
			// start validation
			if($config['floodControl']=="recaptcha") {
				$response = recaptcha_check_answer(	$ini['recaptcha_private_key'], 
											$_SERVER['REMOTE_ADDR'],
											$_POST['recaptcha_challenge_field'],
											$_POST['recaptcha_response_field']);
			} elseif($config['floodControl']==1) {
				$spamCode = fetchSpamCode($_POST['ESC'], true);
			}
			
			if($config['floodControl']=="recaptcha" && !$response->is_valid) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['code_error']);
				$view_prod->parse("view_prod.digital.prod_true.write_review.error");
			} elseif ($config['floodControl']==1 && !isset($_POST['review']['spambot']) || ($config['floodControl']==1 && $spamCode['SpamCode']!==strtoupper($_POST['review']['spambot'])) || ($config['floodControl']==1 && get_ip_address()!==$spamCode['userIp'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['code_error']);
				$view_prod->parse("view_prod.digital.prod_true.write_review.error");
				
			} elseif (empty($_POST['review']['name']) || empty($_POST['review']['email']) || empty($_POST['review']['title']) || empty($_POST['review']['review'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['empty_fields']);
				$view_prod->parse("view_prod.digital.prod_true.write_review.error");
				
			} else if (!validateEmail($_POST['review']['email'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['invalid_email']);
				$view_prod->parse("view_prod.digital.prod_true.write_review.error");
				
			} else {
				$data['productId'] = $db->MySQLSafe($_GET['productId']);
				$data['type'] = $db->MySQLSafe($_POST['review']['type']);
				$data['rating'] = $db->MySQLSafe($_POST['review']['rating']);
				$data['name'] = $db->MySQLSafe(htmlentities(stripslashes($_POST['review']['name']),ENT_QUOTES,"UTF-8"));
				$data['email'] = $db->MySQLSafe($_POST['review']['email']);
				$data['title'] = $db->MySQLSafe(htmlentities(stripslashes($_POST['review']['title']),ENT_QUOTES,"UTF-8"));
				
				//Filteration of Abusive words here :: 
				//Function return array; 0 index contains isBad bit and 1 index contains modified comment
				$result_arr 	= abusive_filteration( $_POST['review']['review'] , "***" );
				$data['review'] = $db->MySQLSafe(htmlentities(stripslashes($result_arr[1]),ENT_QUOTES,"UTF-8"));
				$data['isBad']  = $db->MySQLSafe($result_arr[0]);
				
				
				/*
				$data['title'] = $db->MySQLSafe(strip_tags(get_magic_quotes_gpc () ? stripslashes($_POST['review']['title']) : $_POST['review']['title']));
				$data['review'] = $db->MySQLSafe(strip_tags(get_magic_quotes_gpc () ? stripslashes($_POST['review']['review']) : $_POST['review']['review']));
				*/
				$data['ip'] = $db->MySQLSafe(get_ip_address());
				$data['time'] = time();
				
				$reviewResult = $db->insert($glob['dbprefix']."ImeiUnlock_reviews", $data);
				$reviewId = $db->insertid();
				
				$view_prod->assign("VAL_SUCCESS",$lang['viewProd']['submit_success']);
				$view_prod->parse("view_prod.digital.prod_true.write_review.success");
				
				
				// notify store owner
				require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
				
				$mail = new htmlMimeMail();
				
				$lang = getLang("email.inc.php");
		
				$macroArray = array(
			
					"AUTHOR_NAME" => sanitizeVar($_POST['review']['name']),
					"AUTHOR_EMAIL" => sanitizeVar($_POST['review']['email']),
					"SENDER_ID" => sanitizeVar(get_ip_address()),
					"PRODUCT_NAME" => sanitizeVar($prodArray[0]['name']),
					"RATING" => sanitizeVar($_POST['review']['rating']),
					"REVIEW_TITLE" => sanitizeVar(strip_tags(stripslashes($_POST['review']['title']))),
					"REVIEW_COPY" => sanitizeVar(strip_tags(stripslashes($_POST['review']['review']))),
					"APPROVE_URL" => $glob['storeURL']."/".$glob['adminFile']."?_g=reviews/index&approved=1&id=".$reviewId,
					"DECLINE_URL" => $glob['storeURL']."/".$glob['adminFile']."?_g=reviews/index&approved=0&id=".$reviewId
			
				);
		
				$text = stripslashes(macroSub($lang['email']['new_review_body'],$macroArray));
				unset($macroArray);
				
				$mail->setText($text);
				$mail->setReturnPath($config['masterEmail']);
				$mail->setFrom(sanitizeVar($_POST['review']['name']).' <'.sanitizeVar($_POST['review']['email']).'>');
				$mail->setSubject($lang['email']['new_review_subject']);
				$mail->setHeader('X-Mailer', 'CubeCart Mailer');
				$send = $mail->send(array($config['masterEmail']), $config['mailMethod']);
				
			}
				
		}
		
		$view_prod->assign("LANG_SUBMIT_REVIEW",$lang['viewProd']['submit_review']);
		
		if(!$reviewResult) {
		
			$view_prod->assign("LANG_SUBMIT_REVIEW_COMPLETE",$lang['viewProd']['submit_review_complete']);
			$view_prod->assign("LANG_REVIEW_TYPE",$lang['viewProd']['contrib_type']);
			$view_prod->assign("LANG_REVIEW",$lang['viewProd']['review']);
			$view_prod->assign("LANG_COMMENT",$lang['viewProd']['comment']);
			
			
			$view_prod->assign("LANG_RATING",$lang['viewProd']['rating']);
			$view_prod->assign("LANG_SPAMBOT",$lang['viewProd']['spambot']);
			$view_prod->assign("LANG_NAME",$lang['viewProd']['review_name']);
			$view_prod->assign("LANG_EMAIL",$lang['viewProd']['email']);
			$view_prod->assign("LANG_NOT_DISPLAYED",$lang['viewProd']['not_displayed']);
			$view_prod->assign("LANG_TITLE",$lang['viewProd']['title']);
			$view_prod->assign("LANG_DETAILS",$lang['viewProd']['review_details']);
			
			if(isset($_POST['review']['name']) && isset($_POST['review']['email'])) {
				$view_prod->assign("VAL_REV_NAME",sanitizeVar($_POST['review']['name']));
				$view_prod->assign("VAL_REV_EMAIL",sanitizeVar($_POST['review']['email']));
			} elseif($cc_session->ccUserData['customer_id']>0) {
				$view_prod->assign("VAL_REV_NAME",$cc_session->ccUserData['firstName']." ".$cc_session->ccUserData['lastName']);
				$view_prod->assign("VAL_REV_EMAIL",$cc_session->ccUserData['email']);
			} else {
				$view_prod->assign("VAL_REV_NAME",$lang['viewProd']['anon']);
			}
			
			if($_POST['review']['type']==1) {
				$view_prod->assign("VAL_REV_TYPE_C_SELECTED","selected='selected'");
			} else {
				$view_prod->assign("VAL_REV_TYPE_R_SELECTED","selected='selected'");
			}
			
			$view_prod->assign("VAL_REV_TITLE",sanitizeVar($_POST['review']['title']));
			$view_prod->assign("VAL_REVIEW",nl2br(sanitizeVar(strip_tags($_POST['review']['review']))));
			
			$view_prod->assign("VAL_ROOT_REL", $GLOBALS['rootRel']);
			
			if(isset($_POST['review']['rating']) && $_POST['review']['rating']>0) {
				
				for($i=0;$i<5;$i++){
					$view_prod->assign("VAL_STAR",starImg($i,$_POST['review']['rating']));
					$view_prod->assign("VAL_STAR_I",$i+1);
					$view_prod->parse("view_prod.digital.prod_true.write_review.form.review_stars");	
				}
				
				$view_prod->assign("VAL_RATING",sanitizeVar($_POST['review']['rating']));
			} else {
				
				for($i=0;$i<5;$i++){
					$view_prod->assign("VAL_STAR",starImg($i,0));
					$view_prod->assign("VAL_STAR_I",$i+1);
					$view_prod->parse("view_prod.digital.prod_true.write_review.form.review_stars");	
				}
				$view_prod->assign("VAL_RATING",0);
			}
			
			// Start Spam Bot Control
			if($config['floodControl']=="recaptcha") {
				$view_prod->assign("TXT_SPAMBOT", "Spambot:");
				$recaptcha = custom_recaptcha_get_html($ini['recaptcha_public_key'],false,detectSSL());
				$view_prod->assign("RECAPTCHA", $recaptcha);
				$view_prod->parse("view_prod.digital.prod_true.write_review.form.recaptcha");
			} elseif($config['floodControl']) {
				
				$spamCode = strtoupper(randomPass(5));
				$ESC = createSpamCode($spamCode);
				
				$imgSpambot = imgSpambot($ESC);
				
				$view_prod->assign("VAL_ESC",$ESC);
				$view_prod->assign("TXT_SPAMBOT","Spambot:");
				$view_prod->assign("IMG_SPAMBOT",$imgSpambot);
				$view_prod->parse("view_prod.digital.prod_true.write_review.form.spambot");
			}
			
			$view_prod->parse("view_prod.digital.prod_true.write_review.form");
		}
		
		$view_prod->parse("view_prod.digital.prod_true.write_review");
	}
	
	if ($_GET['review']=="read" && $prodArray) {
		
		$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;
		
		$reviews_query = "SELECT * FROM `".$glob['dbprefix']."ImeiUnlock_reviews` WHERE approved=1 AND productId=".$db->mySQLsafe($prodArray[0]['productId'])." ORDER BY time DESC";
		$reviews = $db->select($reviews_query, 10, $page);
		$totalNoReviews = $db->numrows($reviews_query);
			
		$reviewsPagination = paginate($totalNoReviews, 10, $page, "page");
		
		if ($reviews) {
			$view_prod->assign("VAL_REVIEW_PAGINATION",$reviewsPagination);
			
			$view_prod->assign("LANG_REVIEWS_AND_COMMENTS",$lang['viewProd']['reviews_and_comments']);
			$view_prod->assign("LANG_BY",$lang['viewProd']['by']);
			
			for($i=0;$i<count($reviews);$i++) {
				
				if($reviews[$i]['type']==1){
					$view_prod->assign("LANG_TYPE",$lang['viewProd']['comment_colon']);
				} else{
					$view_prod->assign("LANG_TYPE","");
				}
				$view_prod->assign("VAL_REVIEW_TITLE", $reviews[$i]['title']);
				$view_prod->assign("VAL_REVIEW_NAME", $reviews[$i]['name']);
				$view_prod->assign("VAL_REVIEW_DATE", formatTime($reviews[$i]['time']));
				$view_prod->assign("VAL_REVIEW",$reviews[$i]['review']);
	
				if($reviews[$i]['type']==0) {
					for($j=0;$j<5;$j++) {
						$view_prod->assign("VAL_REVIEW_STAR",starImg($j,$reviews[$i]['rating']));
						$view_prod->assign("VAL_REVIEW_STAR_I",$j);
						$view_prod->parse("view_prod.digital.prod_true.read_review.reviews_true.review_stars");	
					}
				}
				
				$view_prod->parse("view_prod.digital.prod_true.read_review.reviews_true");
			}
			
		} else {
			$view_prod->assign("LANG_REVIEWS_AND_COMMENTS",$lang['viewProd']['reviews_and_comments']);
			$view_prod->assign("LANG_NO_REVIEWS_MADE",$lang['viewProd']['no_reviews_made']);
			$view_prod->parse("view_prod.digital.prod_true.read_review.reviews_false");
		}
		
		$view_prod->parse("view_prod.digital.prod_true.read_review");
	}
	
	## start customers who bought this also bought...
	
	// if (version_compare('4.1', $db->serverVersion(), '<=')) {
	/* TOO HEAVY ON MYSQL SERVERS WITH LOADS OF ORDERS
	$query = sprintf("SELECT DISTINCT O.productId, I.name, I.image, I.price, I.sale_price FROM %1\$sImeiUnlock_order_inv AS O, %1\$sImeiUnlock_inventory AS I WHERE I.disabled = 0 AND I.productId = O.productId AND O.cart_order_id IN (SELECT DISTINCT cart_order_id FROM %1\$sImeiUnlock_order_inv WHERE productId = %2\$d) AND O.productId <> %2\$d LIMIT 3;", $glob['dbprefix'], $db->MySQLSafe($_GET['productId'], ''));
	
	$pastOrders = $db->select($query);
	*/
	$query = "SELECT `cart_order_id` FROM `".$glob['dbprefix']."ImeiUnlock_order_inv` WHERE `productId` = ".$db->MySQLSafe($_GET['productId'], '');
	$pastOrders = $db->select($query);
	if($pastOrders) {
		foreach($pastOrders as $pastOrder) {
			$query = "SELECT `productId`, `quantity` FROM `".$glob['dbprefix']."ImeiUnlock_order_inv` WHERE `cart_order_id` = '".$pastOrder['cart_order_id']."' AND `productId` <> ".$db->MySQLSafe($_GET['productId'], '');
			$productIds = $db->select($query);
			if($productIds) {
				foreach($productIds as $product){
					## Fix for custom products in inventory with zero product id
					if($product['productId']>0) $related[$product['productId']] += $product['quantity'];
				}
			}
		}
	
		if(is_array($related)) {
			arsort($related,SORT_NUMERIC);	
			$i = 0;
			$noRelatedProducts = count($related);
			$query = "SELECT `productId`, `image`, `price`, `sale_price`, `name` FROM `".$glob['dbprefix']."ImeiUnlock_inventory` WHERE `disabled` = 0 AND (";	
			foreach($related as $productId => $salesVolume){
				$query .= "`productId` = ".$productId; 
				if($i==($noRelatedProducts-1)) {
					break;
				} else {
				$query .= " OR ";
				}
				$i++;
			}
			$query .= ") LIMIT 3";
			$relatedProducts = $db->select($query);
			
			if ($relatedProducts == true && !isset($_GET['review'])) {
				for ($i=0; $i<count($relatedProducts); $i++) {
					if (($val = prodAltLang($relatedProducts[$i]['productId'])) == TRUE) {
						$relatedProducts[$i]['name'] = $val['name'];
					}
					$thumbRootPath = imgPath($relatedProducts[$i]['image'], $thumb=1, $path="root");
					$thumbRelPath = imgPath($relatedProducts[$i]['image'], $thumb=1, $path="rel");
				
					if (file_exists($thumbRootPath) && !empty($relatedProducts[$i]['image'])) {
						$view_prod->assign("VAL_IMG_SRC",$thumbRelPath);
					} else {
						$view_prod->assign("VAL_IMG_SRC",$GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
					}
					
					if (!salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price'])) {
						$view_prod->assign("TXT_PRICE", priceFormat($relatedProducts[$i]['price'], true));
					} else {
						$view_prod->assign("TXT_PRICE","<span class='txtOldPrice'>".priceFormat($relatedProducts[$i]['price'], true)."</span>");
					}
					$salePrice = salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price']);
					$view_prod->assign("TXT_SALE_PRICE", priceFormat($salePrice, true));
					
					$view_prod->assign("VAL_PRODUCT_ID", $relatedProducts[$i]['productId']);
					$view_prod->assign("VAL_PRODUCT_NAME",validHTML($relatedProducts[$i]['name']));
					$view_prod->parse("view_prod.digital.prod_true.related_products.repeat_prods");			
				}
				$view_prod->assign("LANG_RELATED_PRODUCTS",$lang['viewProd']['related_products']);
				$view_prod->parse("view_prod.digital.prod_true.related_products");
			}
		
		}
	}
	


$view_prod->assign("SELECTED_PROD", $selectedProd);
	// }
	if($config['facebookbutton']== 1 && isset($config['fbpageaddress'])){
		$view_prod->assign("FACEBOOK_PAGE_ADDRESS",$config['fbpageaddress']);
		$view_prod->parse("view_prod.digital.prod_true.facebook_page");
	}
	$view_prod->parse("view_prod.digital.prod_true");

} else {// end if product array is true
	$view_prod->assign("LANG_PRODUCT_EXPIRED",$lang['viewProd']['prod_not_found']);
	$view_prod->parse("view_prod.digital.prod_false");
}
	$view_prod->parse("view_prod.digital");
}
else if($prodArray[0]['digital'] == 0){
    if ($prodArray) {
	
	$meta['metaDescription'] = substr(strip_tags($prodArray[0]['description']), 0, 35);
	
	if($config['seftags']){
	
		// get the native language for the category name
		$resultForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '".LANG_FOLDER."' AND cat_master_id = ".$db->mySQLSafe($prodArray[0]['cat_id']));		
		
		if($resultForeign == TRUE){			
			$prodArray[0]['cat_name'] = $resultForeign[0]['cat_name'];		
		}
	
		// get alternate language title for this product
		$sefval = "";
		$sefLangProdTitle = $prodArray[0]['name'];
		if (($sefval = prodAltLang($prodArray[0]['productId'])) == TRUE){			
			// change the titles as they make more sense to the user if they are in their native languages
			// however to the search engine they will always be in the default language regardless as they can't change languages
			$sefLangProdTitle = $sefval['name'];	
			// don't worry about description it will always be in the default language regardless as search 
			// engines can't change languages	
		}
	
		// create title and metas
		$prevDirSymbol = $config['dirSymbol'];
		$config['dirSymbol'] = ' - ';
		
		if($config['sefprodnamefirst']){
			$meta['siteTitle'] = $sefLangProdTitle.$config['dirSymbol'].getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'], FALSE, TRUE, FALSE);
		} else {
			$meta['siteTitle'] = getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'], false, true).$config['dirSymbol'].$sefLangProdTitle;		
		}
		$config['dirSymbol'] = $prevDirSymbol;
		$meta['sefSiteTitle'] = $prodArray[0]['prod_metatitle']; 
		$meta['sefSiteDesc'] = $prodArray[0]['prod_metadesc'] ? $prodArray[0]['prod_metadesc'] : $meta['metaDescription'];
		$meta['sefSiteKeywords'] = $prodArray[0]['prod_metakeywords'];
	} else {
		$meta['siteTitle'] = $config['siteTitle']." - ".$prodArray[0]['name'];
	}
	
	$val = "";
	
	if(($val = prodAltLang($prodArray[0]['productId'])) == TRUE) {
				
		$prodArray[0]['name'] = $val['name'];
		$prodArray[0]['description'] = $val['description'];
			
	}

	// update amount of views
	$upPop['popularity'] = "popularity+1"; 
	$db->update($glob['dbprefix']."ImeiUnlock_inventory",$upPop,"productId = ".$db->mySQLSafe($_GET['productId']));
	
	if($_GET['notice']==1) {
		$view_prod->assign("LANG_OPTS_NOTICE",$lang['viewProd']['opts_notice']);
		$view_prod->parse("view_prod.tangible.prod_true.opts_notice");
	}
	
	$view_prod->assign("LANG_PRODTITLE", $lang['viewProd']['product']);
	$view_prod->assign("LANG_PRODINFO",$lang['viewProd']['product_info']);
	$view_prod->assign("LANG_FIRST_TO_REVIEW",$lang['viewProd']['first_to_review']);
	$view_prod->assign("LANG_PRICE",$lang['viewProd']['price']);
	$view_prod->assign("LANG_PRODCODE",$lang['viewProd']['product_code']);
	$view_prod->assign("LANG_TELLFRIEND",$lang['viewProd']['tellafriend']);
	$view_prod->assign("LANG_READ_REVIEWS",$lang['viewProd']['read_reviews']);
	$view_prod->assign("LANG_WRITE_REVIEWS",$lang['viewProd']['write_reviews']);
	$view_prod->assign("TXT_PRODTITLE",validHTML(stripslashes($prodArray[0]['name'])));
	$view_prod->assign("TXT_DESCRIPTION",$prodArray[0]['description']);
	
	$excluded = array("add"=>1,"quan"=>1,"notice"=>1,"added"=>1);
	$view_prod->assign("CURRENT_URL", currentPage($excluded));

	if (!salePrice($prodArray[0]['price'], $prodArray[0]['sale_price']) || $config['saleMode'] == false) {
		$view_prod->assign("TXT_PRICE_VIEW", priceFormat($prodArray[0]['price'],true));
	} else {
		$view_prod->assign("TXT_PRICE_VIEW","<span class='txtOldPrice'>".priceFormat($prodArray[0]['price'],true)."</span>");
	}
	$salePrice = salePrice($prodArray[0]['price'], $prodArray[0]['sale_price']);
	$view_prod->assign("TXT_SALE_PRICE_VIEW", priceFormat($salePrice,true));
	$view_prod->assign("TXT_PRODCODE", $prodArray[0]['productCode']);
	$view_prod->assign("LANG_HOME", $lang['viewProd']['home']);
	$view_prod->assign("CURRENT_DIR",getCatDir($prodArray[0]['cat_name'],$prodArray[0]['cat_father_id'], $prodArray[0]['cat_id'],$link=TRUE).$config['dirSymbol'].$prodArray[0]['name']);	

	$view_prod->assign("LANG_QUAN",$lang['viewProd']['quantity']);

	$view_prod->assign("PRODUCT_ID",$prodArray[0]['productId']);
	$view_prod->assign("TXT_PROCESS_TIME",$prodArray[0]['processingtime']);
	$view_prod->assign("TXT_MIN_QUAN",$prodArray[0]['min_quantity']);
	$view_prod->assign("TXT_DEL_TIME",$prodArray[0]['deltime']);
		$view_prod->assign("TXT_SHIPPING",$prodArray[0]['shipping']);
		$shippingdesc = $db->select("SELECT doc_content FROM " . $glob['dbprefix'] . "ImeiUnlock_docs WHERE doc_id = " . $db->mySQLSafe(19));
		$view_prod->assign("TXT_SHIPPINGRETURNS",$shippingdesc[0]['doc_content']);
		/* bulk discount code :: FM START*/
		$bulkdisc=$db->select("select * from ".$glob['dbprefix']."ImeiUnlock_discount_idx where productId=".$prodArray[0]['productId']." order by quantity ASC");
		if($bulkdisc){
	     for($b=0;$b<count($bulkdisc);$b++){
			 $view_prod->assign("TXT_BULK_PRICE", priceFormat($bulkdisc[$b]['dprice'],true));
			 $view_prod->assign("DATA", $bulkdisc[$b]);
		$view_prod->parse("view_prod.tangible.prod_true.bulk_discount.repeat");
		 }
		 	$view_prod->parse("view_prod.tangible.prod_true.bulk_discount");
		}
		/* bulk discount code :: FM END*/

	if (!empty($prodArray[0]['image'])) {
		$imgRelPath = imgPath($prodArray[0]['image'], false, 'rel');
		$view_prod->assign("IMG_SRC", $imgRelPath);
	} else {
		$view_prod->assign("IMG_SRC", "skins/". SKIN_FOLDER . "/styleImages/nophoto.gif");
	}
	

	
		/*if (!isset($config['imgGalleryType']) || !$config['imgGalleryType']) {
			$view_prod->assign("LANG_MORE_IMAGES",$lang['viewProd']['popup_more_images']);
			$view_prod->parse("view_prod.tangible.prod_true.popup_gallery");
		
		} else*/ if ($config['imgGalleryType']) {
			
			$imgs = $db->select("SELECT img FROM ".$glob['dbprefix']."ImeiUnlock_img_idx WHERE productId = ".$db->mySQLsafe($prodArray[0]['productId'])." ORDER BY id ASC");
			if ($imgs) {
				foreach ($imgs as $img) {
					$thumbRootPath	= imgPath($img['img'], true, 'root');
					$thumbRelPath	= imgPath($img['img'], true, 'rel');
					
					if (file_exists($thumbRootPath)) {
						$sizeThumb	= getimagesize($thumbRootPath);
						$view_prod->assign("VALUE_THUMB_SRC", $thumbRelPath);
						$view_prod->assign("VALUE_THUMB_WIDTH", $sizeThumb[0]);
						
					} else {
						$view_prod->assign("VALUE_THUMB_SRC", imgPath($img['img'], false, 'rel'));
						$view_prod->assign("VALUE_THUMB_WIDTH", $config['gdthumbSize']);
					}
					
					$view_prod->assign("VALUE_IMG_SRC", imgPath($img['img'], false, 'rel'));
					$view_prod->assign("ALT_THUMB", $lang['viewProd']['thumb_alt']);
					$view_prod->parse("view_prod.tangible.prod_true.image_gallery.img_repeat");
				}
				$view_prod->assign("IMAGE_GALLERY", $lang['viewProd']['image_gallery']);
				$view_prod->parse("view_prod.tangible.prod_true.image_gallery");
			}
		}
	
	
	## Review stars
	$commentQuery = sprintf("SELECT COUNT(id) as noComments FROM %sImeiUnlock_reviews WHERE approved = 1 AND productId = %d", $glob['dbprefix'], $prodArray[0]['productId']);
	$comments = $db->select($commentQuery);
	
	if ($comments[0]['noComments'] >= 1) {
		$reviewQuery = "SELECT COUNT(id) AS noReviews, AVG(rating) as aveRating FROM `".$glob['dbprefix']."ImeiUnlock_reviews` WHERE `type` = 0 AND approved = 1 AND productId =".$db->mySQLsafe($prodArray[0]['productId']);
		$review = $db->select($reviewQuery);
		
		if ($review[0]['noReviews'] >= 1) {
			
			for ($i=0; $i<5; $i++) {
				$view_prod->assign("VAL_STAR", starImg($i,$review[0]['aveRating']));
				$view_prod->parse("view_prod.tangible.prod_true.reviews_true.review_stars");	
			}
			$view_prod->assign("LANG_BASED_ON_X_REVIEWS", sprintf($lang['viewProd']['based_on_x_reviews'], $review[0]['noReviews']));
		}
		$view_prod->parse("view_prod.tangible.prod_true.reviews_true");
		$view_prod->parse("view_prod.tangible.prod_true.read_reviews");
	} else {
		
		$view_prod->parse("view_prod.tangible.prod_true.reviews_false");
	}
	
	if (!$prodArray[0]['disabled']) {
		if ($config['outofstockPurchase']) {
		
			$view_prod->assign("BTN_ADDBASKET",$lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.tangible.prod_true.buy_btn");
			
		} else if ($prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		
			$view_prod->assign("BTN_ADDBASKET", $lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.tangible.prod_true.buy_btn");	
			
		} else if (!$prodArray[0]['useStockLevel']) {
	
			$view_prod->assign("BTN_ADDBASKET", $lang['viewProd']['add_to_basket']);
			$view_prod->parse("view_prod.tangible.prod_true.buy_btn");
		}
	}

	$view_prod->assign('LANG_DIR_LOC', $lang['viewProd']['location']);

	if ($config['stockLevel'] && $prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		$view_prod->assign('TXT_INSTOCK', $lang['viewProd']['no_instock'].' '.$prodArray[0]['stock_level']);
		
	} else if ($prodArray[0]['useStockLevel'] && $prodArray[0]['stock_level']>0) {
		$view_prod->assign("TXT_INSTOCK",$lang['viewProd']['instock']);
	} 


	if ($prodArray[0]['stock_level']<1 && $prodArray[0]['useStockLevel'] && !$prodArray[0]['digital']) {
		$view_prod->assign('TXT_OUTOFSTOCK', $lang['viewProd']['out_of_stock']);
	}
	
	## Build SQL for product options
	$query		= sprintf("SELECT B.*, T.option_name, T.option_type, M.value_name FROM %1\$sImeiUnlock_options_bot AS B LEFT JOIN %1\$sImeiUnlock_options_mid AS M ON B.value_id = M.value_id, %1\$sImeiUnlock_options_top AS T WHERE B.option_id = T.option_id AND B.product = %2\$d ORDER BY T.option_name, M.value_name ASC", $glob['dbprefix'], $_GET['productId']);
	$options	= $db->select($query);
	
	if ($options) {
		$view_prod->assign('TXT_PROD_OPTIONS', $lang['viewProd']['prod_opts']);
		foreach ($options as $option) {
			if (!empty($option)) {
				if ($option['option_type'] == '0') {
					$selectArray[$option['option_id']][] = $option;
				} else {
					$inputArray[$option['option_id']][] = $option;
				}
			}
		}
		
		## Select based options
		if ($selectArray) {
			foreach ($selectArray as $option_id => $option) {
				foreach ($option as $values) {
					$view_prod->assign('VAL_OPTION_ID', $values['option_id']);
					$view_prod->assign('VAL_ASSIGN_ID', $values['assign_id']);
					
					$view_prod->assign('VAL_VALUE_NAME', htmlentities(stripslashes(str_replace(array('&#39;', '&amp;#39;'), "'", utf8_decode($values['value_name'])))));
				
					if ($values['option_price']>0) {
						$view_prod->assign('VAL_OPT_SIGN', $values['option_symbol']);
						$view_prod->assign('VAL_OPT_PRICE', priceFormat($values['option_price'], true));
						$view_prod->parse('view_prod.tangible.prod_true.prod_opts.repeat_options.repeat_values.repeat_price');
					}
					$view_prod->parse('view_prod.tangible.prod_true.prod_opts.repeat_options.repeat_values');
				}
				$view_prod->assign('VAL_OPTS_NAME', $option[0]['option_name']);
				$view_prod->parse('view_prod.tangible.prod_true.prod_opts.repeat_options');
			}
		}
		
		## Text-based options
		if ($inputArray) {
			foreach ($inputArray as $option_id => $option) {
				foreach ($option as $value) {
					
					$view_prod->assign('VAL_VALUE_NAME', htmlentities(stripslashes($value['value_name'])));
					
					$view_prod->assign('VAL_ASSIGN_ID', $value['assign_id']);
					$view_prod->assign('VAL_OPTION_ID', $value['option_id']);
					
					if ($value['option_price']>0) {
						$view_prod->assign('VAL_OPT_SIGN', $value['option_symbol']);
						$view_prod->assign('VAL_OPT_PRICE', priceFormat($value['option_price'], true));
						$view_prod->parse('view_prod.tangible.prod_true.prod_opts.text_opts.repeat_price');
					}
					
					if ($value['option_type'] == 2) {
						$view_prod->parse('view_prod.tangible.prod_true.prod_opts.text_opts.textarea');
					} else {
						$view_prod->parse('view_prod.tangible.prod_true.prod_opts.text_opts.textbox');
					}
				}
				$view_prod->assign('VAL_OPTS_NAME', $option[0]['option_name']);
				$view_prod->parse('view_prod.tangible.prod_true.prod_opts.text_opts');
			}	
		}
		$view_prod->parse('view_prod.tangible.prod_true.prod_opts');
	}

	// start product reviews/comment
	if ($prodArray) {
		
		if (isset($_POST['review'])) {
			
			// start validation
			if($config['floodControl']=="recaptcha") {
				$response = recaptcha_check_answer(	$ini['recaptcha_private_key'], 
											$_SERVER['REMOTE_ADDR'],
											$_POST['recaptcha_challenge_field'],
											$_POST['recaptcha_response_field']);
			} elseif($config['floodControl']==1) {
				$spamCode = fetchSpamCode($_POST['ESC'], true);
			}
			
			if($config['floodControl']=="recaptcha" && !$response->is_valid) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['code_error']);
				$view_prod->parse("view_prod.tangible.prod_true.review.error");
			} elseif ($config['floodControl']==1 && !isset($_POST['review']['spambot']) || ($config['floodControl']==1 && $spamCode['SpamCode']!==strtoupper($_POST['review']['spambot'])) || ($config['floodControl']==1 && get_ip_address()!==$spamCode['userIp'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['code_error']);
				$view_prod->parse("view_prod.tangible.prod_true.review.error");
				
			} elseif (empty($_POST['review']['name']) || empty($_POST['review']['email']) || empty($_POST['review']['title']) || empty($_POST['review']['review'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['empty_fields']);
				$view_prod->parse("view_prod.tangible.prod_true.review.error");
				
			} else if (!validateEmail($_POST['review']['email'])) {
				$view_prod->assign("VAL_ERROR",$lang['viewProd']['invalid_email']);
				$view_prod->parse("view_prod.tangible.prod_true.review.error");
				
			} else {
				$data['productId'] = $db->MySQLSafe($_GET['productId']);
				$data['type'] = $db->MySQLSafe($_POST['review']['type']);
				$data['rating'] = $db->MySQLSafe($_POST['review']['rating']);
				$data['name'] = $db->MySQLSafe(htmlentities(stripslashes($_POST['review']['name']),ENT_QUOTES,"UTF-8"));
				$data['email'] = $db->MySQLSafe($_POST['review']['email']);
				$data['title'] = $db->MySQLSafe(htmlentities(stripslashes($_POST['review']['title']),ENT_QUOTES,"UTF-8"));
				
				//Filteration of Abusive words here :: 
				//Function return array; 0 index contains isBad bit and 1 index contains modified comment
				$result_arr 	= abusive_filteration( $_POST['review']['review'] , "***" );
				$data['review'] = $db->MySQLSafe(htmlentities(stripslashes($result_arr[1]),ENT_QUOTES,"UTF-8"));
				$data['isBad']  = $db->MySQLSafe($result_arr[0]);
				
				
				/*
				$data['title'] = $db->MySQLSafe(strip_tags(get_magic_quotes_gpc () ? stripslashes($_POST['review']['title']) : $_POST['review']['title']));
				$data['review'] = $db->MySQLSafe(strip_tags(get_magic_quotes_gpc () ? stripslashes($_POST['review']['review']) : $_POST['review']['review']));
				*/
				$data['ip'] = $db->MySQLSafe(get_ip_address());
				$data['time'] = time();
				
				$reviewResult = $db->insert($glob['dbprefix']."ImeiUnlock_reviews", $data);
				$reviewId = $db->insertid();

				
				$view_prod->assign("VAL_SUCCESS",$lang['viewProd']['submit_success']);
				$view_prod->parse("view_prod.tangible.prod_true.review.success");
				
				
				// notify store owner
				require("classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");
				
				$mail = new htmlMimeMail();
				
				$lang = getLang("email.inc.php");
		
				$macroArray = array(
			
					"AUTHOR_NAME" => sanitizeVar($_POST['review']['name']),
					"AUTHOR_EMAIL" => sanitizeVar($_POST['review']['email']),
					"SENDER_ID" => sanitizeVar(get_ip_address()),
					"PRODUCT_NAME" => sanitizeVar($prodArray[0]['name']),
					"RATING" => sanitizeVar($_POST['review']['rating']),
					"REVIEW_TITLE" => sanitizeVar(strip_tags(stripslashes($_POST['review']['title']))),
					"REVIEW_COPY" => sanitizeVar(strip_tags(stripslashes($_POST['review']['review']))),
					"APPROVE_URL" => $glob['storeURL']."/".$glob['adminFile']."?_g=reviews/index&approved=1&id=".$reviewId,
					"DECLINE_URL" => $glob['storeURL']."/".$glob['adminFile']."?_g=reviews/index&approved=0&id=".$reviewId
			
				);
		
				$text = stripslashes(macroSub($lang['email']['new_review_body'],$macroArray));
				unset($macroArray);
				
				$mail->setText($text);
				$mail->setReturnPath($config['masterEmail']);
				$mail->setFrom(sanitizeVar($_POST['review']['name']).' <'.sanitizeVar($_POST['review']['email']).'>');
				$mail->setSubject($lang['email']['new_review_subject']);
				$mail->setHeader('X-Mailer', 'CubeCart Mailer');
				$send = $mail->send(array($config['masterEmail']), $config['mailMethod']);
				
			}
				
		}
		
		$view_prod->assign("LANG_SUBMIT_REVIEW",$lang['viewProd']['submit_review']);
		
		if(!$reviewResult) {
		
			$view_prod->assign("LANG_SUBMIT_REVIEW_COMPLETE",$lang['viewProd']['submit_review_complete']);
			$view_prod->assign("LANG_REVIEW_TYPE",$lang['viewProd']['contrib_type']);
			$view_prod->assign("LANG_REVIEW",$lang['viewProd']['review']);
			$view_prod->assign("LANG_COMMENT",$lang['viewProd']['comment']);
			$view_prod->assign("LANG_RATING",$lang['viewProd']['rating']);
			$view_prod->assign("LANG_SPAMBOT",$lang['viewProd']['spambot']);
			$view_prod->assign("LANG_NAME",$lang['viewProd']['review_name']);
			$view_prod->assign("LANG_EMAIL",$lang['viewProd']['email']);
			$view_prod->assign("LANG_NOT_DISPLAYED",$lang['viewProd']['not_displayed']);
			$view_prod->assign("LANG_TITLE",$lang['viewProd']['title']);
			$view_prod->assign("LANG_DETAILS",$lang['viewProd']['review_details']);
			
			if(isset($_POST['review']['name']) && isset($_POST['review']['email'])) {
				$view_prod->assign("VAL_REV_NAME",sanitizeVar($_POST['review']['name']));
				$view_prod->assign("VAL_REV_EMAIL",sanitizeVar($_POST['review']['email']));
			} elseif($cc_session->ccUserData['customer_id']>0) {
				$view_prod->assign("VAL_REV_NAME",$cc_session->ccUserData['firstName']." ".$cc_session->ccUserData['lastName']);
				$view_prod->assign("VAL_REV_EMAIL",$cc_session->ccUserData['email']);
			} else {
				$view_prod->assign("VAL_REV_NAME",$lang['viewProd']['anon']);
			}
			
			if($_POST['review']['type']==1) {
				$view_prod->assign("VAL_REV_TYPE_C_SELECTED","selected='selected'");
			} else {
				$view_prod->assign("VAL_REV_TYPE_R_SELECTED","selected='selected'");
			}
			
			$view_prod->assign("VAL_REV_TITLE",sanitizeVar($_POST['review']['title']));
			$view_prod->assign("VAL_REVIEW",nl2br(sanitizeVar(strip_tags($_POST['review']['review']))));
			
			$view_prod->assign("VAL_ROOT_REL", $GLOBALS['rootRel']);
			
			if(isset($_POST['review']['rating']) && $_POST['review']['rating']>0) {
				
				for($i=0;$i<5;$i++){
					$view_prod->assign("VAL_STAR",starImg($i,$_POST['review']['rating']));
					$view_prod->assign("VAL_STAR_I",$i+1);
					$view_prod->parse("view_prod.tangible.prod_true.review.form.review_stars");	
				}
				
				$view_prod->assign("VAL_RATING",sanitizeVar($_POST['review']['rating']));
			} else {
				
				for($i=0;$i<5;$i++){
					$view_prod->assign("VAL_STAR",starImg($i,0));
					$view_prod->assign("VAL_STAR_I",$i+1);
					$view_prod->parse("view_prod.tangible.prod_true.review.form.review_stars");	
				}
				$view_prod->assign("VAL_RATING",0);
			}
			
			// Start Spam Bot Control
			if($config['floodControl']=="recaptcha") {
				$view_prod->assign("TXT_SPAMBOT", "Spambot:");
				$recaptcha = custom_recaptcha_get_html($ini['recaptcha_public_key'],false,detectSSL());
				$view_prod->assign("RECAPTCHA", $recaptcha);
				$view_prod->parse("view_prod.tangible.prod_true.review.form.recaptcha");
			} elseif($config['floodControl']) {
				
				$spamCode = strtoupper(randomPass(5));
				$ESC = createSpamCode($spamCode);
				
				$imgSpambot = imgSpambot($ESC);
				
				$view_prod->assign("VAL_ESC",$ESC);
				$view_prod->assign("TXT_SPAMBOT","Spambot:");
				$view_prod->assign("IMG_SPAMBOT",$imgSpambot);
				$view_prod->parse("view_prod.tangible.prod_true.review.form.spambot");
			}
			
			$view_prod->parse("view_prod.tangible.prod_true.review.form");
		}
		
	
		
		$reviews_query = "SELECT * FROM `".$glob['dbprefix']."ImeiUnlock_reviews` WHERE approved=1 AND productId=".$db->mySQLsafe($prodArray[0]['productId'])." ORDER BY time DESC";
		$reviews = $db->select($reviews_query, 10, $page);
		$totalNoReviews = $db->numrows($reviews_query);
			
		$reviewsPagination = paginate($totalNoReviews, 10, $page, "page");
		
		if ($reviews) {
			$view_prod->assign("VAL_REVIEW_PAGINATION",$reviewsPagination);
			
			$view_prod->assign("LANG_REVIEWS_AND_COMMENTS",$lang['viewProd']['reviews_and_comments']);
			$view_prod->assign("LANG_BY",$lang['viewProd']['by']);
			
			for($i=0;$i<count($reviews);$i++) {
				
				if($reviews[$i]['type']==1){
					$view_prod->assign("LANG_TYPE",$lang['viewProd']['comment_colon']);
				} else{
					$view_prod->assign("LANG_TYPE","");
				}
				$view_prod->assign("VAL_REVIEW_TITLE", $reviews[$i]['title']);
				$view_prod->assign("VAL_REVIEW_NAME", $reviews[$i]['name']);
				$view_prod->assign("VAL_REVIEW_DATE", formatTime($reviews[$i]['time']));
				$view_prod->assign("VAL_REVIEW",$reviews[$i]['review']);
	
				if($reviews[$i]['type']==0) {
					for($j=0;$j<5;$j++) {
						$view_prod->assign("VAL_REVIEW_STAR",starImg($j,$reviews[$i]['rating']));
						$view_prod->assign("VAL_REVIEW_STAR_I",$j);
						$view_prod->parse("view_prod.tangible.prod_true.review.reviews_true.review_stars");	
					}
				}
				
				$view_prod->parse("view_prod.tangible.prod_true.review.reviews_true");
			}
			
		} else {
			$view_prod->assign("LANG_REVIEWS_AND_COMMENTS",$lang['viewProd']['reviews_and_comments']);
			$view_prod->assign("LANG_NO_REVIEWS_MADE",$lang['viewProd']['no_reviews_made']);
			$view_prod->parse("view_prod.tangible.prod_true.review.reviews_false");
		}
		
		$view_prod->parse("view_prod.tangible.prod_true.review");
	}
	 /*RELATED PRODUCTS WORKING :: START*/	
	   $relatedprodquery="select I.* from ".$glob['dbprefix']."ImeiUnlock_inventory as I inner join " .$glob['dbprefix']."ImeiUnlock_related as r on r.relatedproductId = I.ProductId where I.digital = '0' AND r.productId=".$db->MySQLSafe($_GET['productId']);
	
		$relatedProducts = $db->select($relatedprodquery);
		//   echo "<PRE>";
		 //  echo $relatedprodquery;
	 //  print_r($relatedprodquery);
							if ($relatedProducts) {
				for ($i=0; $i<count($relatedProducts); $i++) {
					if (($val = prodAltLang($relatedProducts[$i]['productId'])) == TRUE) {
						$relatedProducts[$i]['name'] = $val['name'];
					}
					$thumbRootPath = imgPath($relatedProducts[$i]['image'], 'small', $path="root");
					$thumbRelPath = imgPath($relatedProducts[$i]['image'], 'small', $path="rel");
				
					if (file_exists($thumbRootPath) && !empty($relatedProducts[$i]['image'])) {
						$view_prod->assign("VAL_IMG_SRC",$thumbRelPath);
					} else {
						$view_prod->assign("VAL_IMG_SRC",$GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
					}
					
					if (!salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price'])) {
						$view_prod->assign("TXT_PRICE", priceFormat($relatedProducts[$i]['price'], true));
					} else {
						$view_prod->assign("TXT_PRICE","<span class='txtOldPrice'>".priceFormat($relatedProducts[$i]['price'], true)."</span>");
					}
					$salePrice = salePrice($relatedProducts[$i]['price'], $relatedProducts[$i]['sale_price']);
					$view_prod->assign("TXT_SALE_PRICE", priceFormat($salePrice, true));
					
					$view_prod->assign("VAL_PRODUCT_ID", $relatedProducts[$i]['productId']);
					$view_prod->assign("VAL_PRODUCT_NAME",validHTML($relatedProducts[$i]['name']));
					$view_prod->assign("VAL_PRODUCT_TITLE",validHTML($relatedProducts[$i]['title']));
					$view_prod->assign("VAL_PRODUCT_LOCATION",validHTML($relatedProducts[$i]['location']));
					
					$view_prod->parse("view_prod.tangible.prod_true.related_products.repeat_prods");			

							}
				$view_prod->assign("LANG_RELATED_PRODUCTS",$lang['viewProd']['related_products']);
				$view_prod->parse("view_prod.tangible.prod_true.related_products");
			}
		/*RELATED PRODUCTS WORKING :: END*/	
	// }
	$resultsdoc = $db->select("SELECT doc_content FROM ".$glob['dbprefix']."ImeiUnlock_docs Where doc_id = '22'");
	if($resultsdoc){
		for($s=0;$s<count($resultsdoc);$s++){
			$resultsdoc[$s]["doc_content"] =strip_tags($resultsdoc[$s]["doc_content"]);
			$view_prod->assign('DATA', $resultsdoc[$s]);
		$view_prod->parse('view_prod.tangible.prod_true.site_docs.repeat');
		}
				$view_prod->parse('view_prod.tangible.prod_true.site_docs');
	}
	$view_prod->parse("view_prod.tangible.prod_true");

} else {// end if product array is true
	$view_prod->assign("LANG_PRODUCT_EXPIRED",$lang['viewProd']['prod_not_found']);
	$view_prod->assign("LANG_UNSURE",$lang['viewProd']['unsure']);
			$view_prod->assign("LANG_ORDERIMEI",$lang['viewProd']['orderimei']);
			$view_prod->assign("LANG_IMEILINK",$lang['viewProd']['imeilink']);
			$query = "SELECT prod_metadesc, prod_metakeywords, prod_metatitle FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = ".$db->mySQLSafe($_GET['productId']);

$prodArray = $db->select($query);
/*echo "<PRE>";
print_r($prodArray);*/
if($prodArray){
$meta['sefSiteTitle'] = $prodArray[0]['prod_metatitle']; 
		$meta['sefSiteDesc'] = $prodArray[0]['prod_metadesc'] ? $prodArray[0]['prod_metadesc'] : $meta['metaDescription'];
		$meta['sefSiteKeywords'] = $prodArray[0]['prod_metakeywords'];
	$view_prod->parse("view_prod.tangible.prod_false");
}
else header("Location: ".$glob['storeURL']."/index.php");
}

	$view_prod->parse("view_prod.tangible");
}

$view_prod->parse("view_prod");
$page_content = $view_prod->text("view_prod");
?>