<?php
/*
+--------------------------------------------------------------------------
|	shoppingCart.inc.php
|   ========================================
|	Shopping Cart Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

if (!$cc_session->user_is_search_engine()) { // || !$config['sef']) {

	## include lang file
	$lang = getLang("includes".CC_DS."boxes".CC_DS."shoppingCart.inc.php");
	$box_content = new XTemplate("boxes".CC_DS."shoppingCart.tpl");
	$box_content->assign("LANG_SHOPPING_CART_TITLE",$lang['shoppingCart']['shopping_cart']);
	
	require_once "classes".CC_DS."cart".CC_DS."shoppingCart.php";
	
	$cart	= new cart();
	$basket	= $cart->cartContents($cc_session->ccUserData['basket']);
/*echo "<pre>";
print_r($basket);
die();*/
	
	if (isset($_POST['add']) && $_POST['add']>0 ){
		if (!isset($_POST['productOptions'])) {
			## check product options are selected if they are required
			$prodOpts = $db->select("SELECT count(product) as noOpts FROM ".$glob['dbprefix']."ImeiUnlock_options_bot WHERE product=".$db->mySQLSafe($_POST['add']));
			## if they are required redirect to product view page
			if ($prodOpts[0]['noOpts'] > 0) {
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['add']).'?notice=1'; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['add'].'&notice=1';
				}
				httpredir($productUrl);
				exit;
			}
		}
		
		## add product to the cart
		$quantity	= (is_numeric($_POST['quan']) && $_POST['quan'] > 0) ? $_POST['quan'] : 1;
		## Allow for integer AND float quantities
		$quantity	= (isset($prodType[0]['prodType']) && $prodType[0]['prodType'] == 2) ? $quantity : ceil($quantity);
		$basket		= (isset($_POST['productOptions'])) ? $basket = $cart->add($_POST['add'], $quantity, $_POST['productOptions'], '') : $cart->add($_POST['add'], $quantity, '', '');
		
		## Go to cart or back to same page
		if ($config['add_to_basket_act'] == true) {
			## Go to the cart
			if ($cc_session->ccUserData['customer_id']>0) {
				httpredir($config['rootRel']."index.php?_g=co&_a=step2");
			} else {
				httpredir($config['rootRel']."index.php?_g=co&_a=cart");
			}
		} else {
			$allowedVars = array('_a', 'category', 'catId', 'docId', 'page', 'priceMax', 'priceMin', 'prodId', 'productId', 'review', 'searchStr');
			
			## Stay on same page but get rid of those pesky post variables
			parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $query);
			
			if (isset($query['searchStr'])) {
				## Rehash this later - could benefit from the code used below
				if (is_array($query) && !empty($query)) {
					foreach ($query as $key => $value) {
						if (in_array($key, $allowedVars) && !empty($value)) {
							#$append[$key] = $value;
							if (is_array($value)) {
								foreach ($value as $val) {
									$queryArray[] = sprintf('%s[]=%d', $key, $val);
								}
							} else {
								$queryArray[] = sprintf('%s=%s', $key, urlencode($value));
							}
						}
					}
					## Add flash basket
					$queryArray[]	= "added=1";
					$querystring	= '?'.implode('&', $queryArray);
				}
				httpredir($glob['storeURL'].'/index.php'.$querystring);
			} else {
				## need to add added=1, as well as any allowed vars - PHP5 compatible
				if (!preg_match('#^'.$GLOBALS['storeURL'].'#', $_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
					httpredir('index.php');
				} else {
					$return_url		= parse_url($_SERVER['HTTP_REFERER']);
					if (is_array($query)) {
						foreach ($query as $key => $value) {
							if (in_array($key, $allowedVars) && !empty($value)) {
								$append[$key] = $value;
							}
						}
					}
					$append['added']	= 1;
					$redirect_to = sprintf('%s/%s?%s', $GLOBALS['storeURL'], substr($return_url['path'], strlen($GLOBALS['rootRel'])), http_build_query($append));
					httpredir($redirect_to);
				}
			}
		}
		
	}if (isset($_POST['addrepair']) && $_POST['addrepair']>0 ){
		//echo "<pre>";
		//print_r($_POST);
		//die();
		if (!isset($_POST['productOptions'])) {
			## check product options are selected if they are required
			$prodOpts = $db->select("SELECT count(product) as noOpts FROM ".$glob['dbprefix']."ImeiUnlock_options_bot WHERE product=".$db->mySQLSafe($_POST['addrepair']));
			## if they are required redirect to product view page
			if ($prodOpts[0]['noOpts'] > 0) {
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['addrepair']).'?notice=1'; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['addrepair'].'&notice=1';
				}
				httpredir($productUrl);
				exit;
			}
		}
		
		## addrepair product to the cart
		$quantity	= (is_numeric($_POST['quan']) && $_POST['quan'] > 0) ? $_POST['quan'] : 1;
		## Allow for integer AND float quantities
		$quantity	= (isset($prodType[0]['prodType']) && $prodType[0]['prodType'] == 2) ? $quantity : ceil($quantity);
		$basket		= (isset($_POST['productOptions'])) ? $basket = $cart->add($_POST['addrepair'], $quantity, $_POST['productOptions'], '') : $cart->add($_POST['addrepair'], $quantity, '', '');
		
		## Go to cart or back to same page
		if ($config['add_to_basket_act'] == true) {
			## Go to the cart
			if ($cc_session->ccUserData['customer_id']>0) {
				httpredir($config['rootRel']."index.php?_g=co&_a=step2");
			} else {
				httpredir($config['rootRel']."index.php?_g=co&_a=cart");
			}
		} else {
			$allowedVars = array('_a', 'category', 'catId', 'docId', 'page', 'priceMax', 'priceMin', 'prodId', 'productId', 'review', 'searchStr');
			
			## Stay on same page but get rid of those pesky post variables
			parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $query);
			
			if (isset($query['searchStr'])) {
				## Rehash this later - could benefit from the code used below
				if (is_array($query) && !empty($query)) {
					foreach ($query as $key => $value) {
						if (in_array($key, $allowedVars) && !empty($value)) {
							#$append[$key] = $value;
							if (is_array($value)) {
								foreach ($value as $val) {
									$queryArray[] = sprintf('%s[]=%d', $key, $val);
								}
							} else {
								$queryArray[] = sprintf('%s=%s', $key, urlencode($value));
							}
						}
					}
					## Add flash basket
					$queryArray[]	= "added=1";
					$querystring	= '?'.implode('&', $queryArray);
				}
				httpredir($glob['storeURL'].'/index.php'.$querystring);
			} else {
				## need to add added=1, as well as any allowed vars - PHP5 compatible
				if (!preg_match('#^'.$GLOBALS['storeURL'].'#', $_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
					httpredir('index.php');
				} else {
					$return_url		= parse_url($_SERVER['HTTP_REFERER']);
					if (is_array($query)) {
						foreach ($query as $key => $value) {
							if (in_array($key, $allowedVars) && !empty($value)) {
								$append[$key] = $value;
							}
						}
					}
					$append['added']	= 1;
					$redirect_to = sprintf('%s/%s?%s', $GLOBALS['storeURL'], substr($return_url['path'], strlen($GLOBALS['rootRel'])), http_build_query($append));
					httpredir($redirect_to);
				}
			}
		}
		
	}if (isset($_POST['addimei']) && $_POST['addimei']>0 ){
		if (!isset($_POST['productOptions'])) {
			## check product options are selected if they are required
			$prodOpts = $db->select("SELECT count(product) as noOpts FROM ".$glob['dbprefix']."ImeiUnlock_options_bot WHERE product=".$db->mySQLSafe($_POST['add']));
			## if they are required redirect to product view page
			if ($prodOpts[0]['noOpts'] > 0 ) {
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['add']).'?notice=1'; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['add'].'&notice=1';
				}
				httpredir($productUrl);
				exit;
			}
		}
		if (!isset($_POST['imei']) ||$_POST['imei'] =="" && $_POST['digital']== '1' ) {
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['addimei']).'?notice=1'; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['addimei'].'&notice=1';
				}
				httpredir($productUrl);
				exit;
			}
		
		## add product to the cart
		$quantity	= (is_numeric($_POST['quan']) && $_POST['quan'] > 0) ? $_POST['quan'] : 1;
		//$imeis = explode($_POST['imei']);
		$imeis = preg_split('/\r\n|\r|\n/', $_POST['imei']);
		$count = count($imeis);
		if($count > 1){
		for($r=0;$r<$count;$r++){
			$aexit = $db->select("SELECT imei  FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE imei =".$db->mySQLSafe($imeis[$r])." AND stat = '1'");
			if($aexit){
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['addimei']).'?notice=2&imei='.$imeis[$r]; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['addimei'].'&notice=2&imei='.$imeis[$r];
				}
				httpredir($productUrl);
				exit;
			}else{
			if(strlen($imeis[$r]) > 1){
			$quantity	= (isset($prodType[0]['prodType']) && $prodType[0]['prodType'] == 2) ? $quantity : ceil($quantity);
		$basket		= (isset($_POST['productOptions'])) ? $basket = $cart->addimei($_POST['addimei'], '', $_POST['productOptions'], $imeis[$r]) : $cart->add($_POST['addimei'], '', '');
			}
		}
		}
		}else{
			$aexit = $db->select("SELECT imei  FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE imei =".$db->mySQLSafe($_POST['imei'])." AND stat = '1'");
			if($aexit){
				if ($config['sef']) {
					## Generate a SEO URL
					$productUrl = generateProductUrl($_POST['addimei']).'?notice=2&imei='.$_POST['imei']; 
				} else {
					$productUrl = 'index.php?_a=viewProd&productId='.$_POST['addimei'].'&notice=2&imei='.$_POST['imei'];
				}
				httpredir($productUrl);
				exit;
			}else{
		## Allow for integer AND float quantities
		$quantity	= (isset($prodType[0]['prodType']) && $prodType[0]['prodType'] == 2) ? $quantity : ceil($quantity);
		$basket		= (isset($_POST['productOptions'])) ? $basket = $cart->addimei($_POST['addimei'], '', $_POST['productOptions'], $_POST['imei']) : $cart->add($_POST['addimei'], '', '');
			}
		}
		## Go to cart or back to same page
		if ($config['add_to_basket_act'] == true) {
			## Go to the cart
			if ($cc_session->ccUserData['customer_id']>0) {
				httpredir($config['rootRel']."index.php?_g=co&_a=step2");
			} else {
				httpredir($config['rootRel']."index.php?_g=co&_a=cart");
			}
		} else {
			$allowedVars = array('_a', 'category', 'catId', 'docId', 'page', 'priceMax', 'priceMin', 'prodId', 'productId', 'review', 'searchStr');
			
			## Stay on same page but get rid of those pesky post variables
			parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $query);
			
			if (isset($query['searchStr'])) {
				## Rehash this later - could benefit from the code used below
				if (is_array($query) && !empty($query)) {
					foreach ($query as $key => $value) {
						if (in_array($key, $allowedVars) && !empty($value)) {
							#$append[$key] = $value;
							if (is_array($value)) {
								foreach ($value as $val) {
									$queryArray[] = sprintf('%s[]=%d', $key, $val);
								}
							} else {
								$queryArray[] = sprintf('%s=%s', $key, urlencode($value));
							}
						}
					}
					## Add flash basket
					$queryArray[]	= "added=1";
					$querystring	= '?'.implode('&', $queryArray);
				}
				httpredir($glob['storeURL'].'/index.php'.$querystring);
			} else {
				## need to add added=1, as well as any allowed vars - PHP5 compatible
				if (!preg_match('#^'.$GLOBALS['storeURL'].'#', $_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
					httpredir('index.php');
				} else {
					$return_url		= parse_url($_SERVER['HTTP_REFERER']);
					if (is_array($query)) {
						foreach ($query as $key => $value) {
							if (in_array($key, $allowedVars) && !empty($value)) {
								$append[$key] = $value;
							}
						}
					}
					$append['added']	= 1;
					$redirect_to = sprintf('%s/%s?%s', $GLOBALS['storeURL'], substr($return_url['path'], strlen($GLOBALS['rootRel'])), http_build_query($append));
					httpredir($redirect_to);
				}
			}
		}
		
	} else if (isset($_POST['gc']['cert']) && $_POST['gc']['cert'] == true) {
		$gc = fetchDbConfig('gift_certs');
		
		if (empty($_POST['gc']['amount']) || empty($_POST['gc']['recipName']) || (empty($_POST['gc']['recipEmail']) && $_POST['gc']['delivery'] == 'e')) {
			## Empty fields
			$errorGCMsg = 1;
		} else if ($_POST['gc']['delivery'] == 'e' && !validateEmail($_POST['gc']['recipEmail'])) {
			## Invalid email address
			$errorGCMsg = 2;
			
		} else {
			if (!isset($gc['min']) || empty($gc['min']))	$gc['min'] = 1;
			if ($_POST['gc']['amount'] < $gc['min'])		$errorGCMsg = 3;
			
			if (isset($gc['max']) && !empty($gc['max']) && $_POST['gc']['amount'] > $gc['max']) $errorGCMsg = 3;
			
			if (!isset($errorGCMsg)) {
	
				$basket = $cart->addCert($_POST['gc']);
				## Go to cart or back to same page
				if ($config['add_to_basket_act']) {
					## Go to the cart
					httpredir(basename($_SERVER['PHP_SELF'])."?_g=co&_a=cart");
				} else {
					// stay on same page but dump those mingy post vars
					httpredir(basename($_SERVER['PHP_SELF'])."?_a=giftCert&added=1");
				}
			}
		}
	
	} else if (isset($_POST['case']['case']) && $_POST['case']['case'] == true) {
		
		if (empty($_POST['case']['caseid']) || empty($_POST['case']['designid'])) {
			## Empty fields
			$errorGCMsg = 1;			
		} else {
						
			if (!isset($errorGCMsg)) {
				$caseinfo = $db->select("SELECT name, price, devicecode FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE id=".$db->mySQLSafe($_POST['case']['caseid']));
				$designinfo = $db->select("SELECT image,session_id, design_name FROM ".$glob['dbprefix']."ImeiUnlock_user_images_success WHERE id=".$db->mySQLSafe($_POST['case']['designid']));
				$_POST['case']['amount'] = $caseinfo[0]['price'];
				$_POST['case']['name'] = $caseinfo[0]['name'];
				$_POST['case']['devicecode'] = $caseinfo[0]['devicecode'];
				$_POST['case']['designname'] = $designinfo[0]['design_name'];
				$_POST['case']['image'] = 'thumb'.$designinfo[0]['image'];
				$_POST['case']['designimg'] = $designinfo[0]['image'];
			
				$basket = $cart->addCase($_POST['case']);
				## Go to cart or back to same page
				if ($config['add_to_basket_act']) {
					## Go to the cart
					httpredir(basename($_SERVER['PHP_SELF'])."?_g=co&_a=cart");
				} else {
					// stay on same page but dump those mingy post vars
					if($cc_session->ccUserData['customer_id']>0 && (!empty($cc_session->ccUserData['add_1']) && !empty($cc_session->ccUserData['town']) && !empty($cc_session->ccUserData['email']) && !empty($cc_session->ccUserData['county']) && !empty($cc_session->ccUserData['phone']))) {
		httpredir("index.php?_g=co&_a=step3"); 
	}else{
		httpredir("Cart.html");
	}
					
				}
			}
		}
	}
	$cartTotal = NULL;
	
	if (is_array($basket['conts']) && !empty($basket['conts'])) {
		foreach ($basket['conts'] as $key => $value) {
			if ($basket['conts'][$key]['custom'] == true) {
				$price = $basket['conts'][$key]['gcInfo']['amount'];
				$name =  $lang['shoppingCart']['gift_cert'];
			} elseif ($basket['conts'][$key]['custom'] == 2) {
				$price = $basket['conts'][$key]['caseInfo']['amount'];
				$name =  $basket['conts'][$key]['caseInfo']['name'];
			} else {
				$productId = $cart->getProductId($key);
				
				## Get product details
				$product = $db->select("SELECT name, price, sale_price, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId=".$db->mySQLSafe($productId));
				if (($val = prodAltLang($product[0]['productId'])) == true) {
					$product[0]['name'] = $val['name'];
				}
				
				## Build the product options
				
				$optionKeys = $cart->getOptions($key);
				
				$optionsCost = 0;
				
				if (!empty($optionKeys)) {					
					$options = explode('{|}', $optionKeys);
					foreach ($options as $value) {
						## Split on separator
						$value_data		= explode('{@}', $value);
						$option_top		= $db->select(sprintf("SELECT T.* FROM %1\$sImeiUnlock_options_top AS T WHERE T.option_id = %2\$s", $glob['dbprefix'], $db->mySQLSafe($value_data[0])));
						if ($option_top) {
							$option_name	= $option_top[0]['option_name'];
							if ($option_top[0]['option_type'] == 0) {
								$option		= $db->select(sprintf("SELECT M.*, B.* FROM %1\$sImeiUnlock_options_mid AS M, %1\$sImeiUnlock_options_bot AS B WHERE M.value_id = B.value_id AND B.assign_id = %2\$d", $glob['dbprefix'], $value_data[1]));
								if ($option) {
									$option_price	= $option[0]['option_price'];
									$option_symbol	= $option[0]['option_symbol'];
									$option_value	= $option[0]['value_name'];	
								}
							} else {
								$option			= $db->select(sprintf("SELECT B.* FROM %1\$sImeiUnlock_options_bot AS B WHERE B.option_id = %2\$d AND B.product = %3\$d LIMIT 1", $glob['dbprefix'], $value_data[0], $productId));	
								if ($option) {
									$option_price	= $option[0]['option_price'];
									$option_symbol	= $option[0]['option_symbol'];
									$option_value	= $value_data[1];
								}
							}
						}
						if ($option_price > 0) {
							if ($option_symbol == "+") {
								$optionsCost = $optionsCost + $option_price;
							} else if ($option_symbol == "-") {
								$optionsCost = $optionsCost - $option_price;
							} else if ($option_symbol == "~") {
								$optionsCost = 0;
							}
						}
					}
				}
				
				$price	= (salePrice($product[0]['price'], $product[0]['sale_price']) == false) ? $price = $product[0]['price'] : salePrice($product[0]['price'], $product[0]['sale_price']);
				$price += $optionsCost;
				if ($price < 0) $price = 0;
				$name	= $product[0]['name'];
			}
			if($cc_session->ccUserData['customer_type'] > 0){
		$wprice = getwprice($cc_session->ccUserData['customer_type'], $product[0]['productId']);
			if($wprice > 0){
				$price = $wprice;
				}
			}
			$box_content->assign("PRODUCT_PRICE", priceFormat($price, true));
			$box_content->assign("VAL_NO_PRODUCT", $cart->cartArray['conts'][$key]["quantity"]);
			$box_content->assign("PRODUCT_ID", $productId);
			
			## Chop name if too long
			if (strlen($name) > 15) $name = substr($name,0,15)."..";
			
			$box_content->assign("VAL_PRODUCT_NAME", validHTML($name));
			$box_content->parse("shopping_cart.contents_true");
			$cartTotal = $cartTotal + ($price * $cart->cartArray['conts'][$key]["quantity"]);
		}
	} else {
		$box_content->assign("LANG_CART_EMPTY",$lang['shoppingCart']['basket_empty']);
		$box_content->parse("shopping_cart.contents_false");
	}
	
	$box_content->assign("VAL_CART_ITEMS", $cart->noItems());
	$box_content->assign("LANG_ITEMS_IN_CART", $lang['shoppingCart']['items_in_cart']);
	
	if (isset($cartTotal) && $cartTotal>0) {
		$box_content->assign("VAL_CART_TOTAL", priceFormat($cartTotal,true));
	} else {
		$box_content->assign("VAL_CART_TOTAL", priceFormat(0, TRUE));
	}
	
	$box_content->assign("LANG_TOTAL_CART_PRICE",$lang['shoppingCart']['total']);
	$box_content->assign("LANG_VIEW_CART",$lang['shoppingCart']['view_basket']);
	
	if($cc_session->ccUserData['customer_id']>0 && (!empty($cc_session->ccUserData['add_1']) && !empty($cc_session->ccUserData['town']) && !empty($cc_session->ccUserData['email']) && !empty($cc_session->ccUserData['county']) && !empty($cc_session->ccUserData['phone']))) {
		$box_content->assign("CART_STEP", "step3");
	} else {
		$box_content->assign("CART_STEP", "cart");
	}
	
	if ($config['hide_prices'] && !$cc_session->ccUserData['customer_id'] && !$GLOBALS[CC_ADMIN_SESSION_NAME]) {
		// have a break, have a KitKat
	} else {
		$box_content->parse("shopping_cart.view_cart");
	}
	
	$box_content->parse("shopping_cart");
	$box_content = $box_content->text("shopping_cart");
} else {
	$box_content = null;
}
?>