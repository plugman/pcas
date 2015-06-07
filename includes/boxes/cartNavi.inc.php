<?php
/*
+--------------------------------------------------------------------------
|	cartNavi.inc.php
|   ========================================
|	Cart Pages Navigation Links Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."cartNavi.inc.php");

$box_content = new XTemplate ("boxes".CC_DS."cartNavi.tpl");
$box_content->assign("LANG_LINKS",$lang['cartNavi']['lang_links']);

if (isset($links)) unset($links);

switch ($_GET['_a']) {
	case 'step1':
		$links[] = array (
			'link' => "index.php?_g=co&amp;_a=reg&amp;redir=%2Findex.php%3F_g=co%26_a%3Dstep1",
			'text' => $lang['cartNavi']['reg_and_checkout']
		);
	break;
		
	
	case 'step2':
		if($basket['conts'] == TRUE) {
			$links[] = array (
				'link' => "index.php?_a=profile&amp;f=".sanitizeVar($_GET['_a']),
				'text' => $lang['cartNavi']['edit_inv_add']
			);
			if($config['shipAddressLock'] == 0){
				$links[] = array (
					'link' => "index.php?_g=co&amp;_a=step2&amp;editDel=1",
					'text' => $lang['cartNavi']['edit_del_add']
				);
			}
	
			$links[] = array (
				'link' => "index.php?_g=co&amp;_a=".sanitizeVar($_GET['_a'])."&amp;mode=emptyCart",
				'text' => $lang['cartNavi']['empty_cart']
			);
		}
	break;

	case 'cart':
		$links[] = array (
			'link' => "index.php?_g=co&amp;_a=".sanitizeVar($_GET['_a'])."&amp;mode=emptyCart",
			'text' => $lang['cartNavi']['empty_cart']
		);
	break;
	case 'error':
		$links[] = array (
			'link' => "index.php?_g=co&amp;_a=cart&amp;mode=emptyCart",
			'text' => $lang['cartNavi']['empty_cart']
		);
	
	break;
}

$contShopLink = $_GET['_a'] == "step3" ? "?_g=co&amp;_a=step3&amp;contShop=1&amp;cart_order_id=".$_GET['cart_order_id'] : "";

$links[] = array (
			'link' => "index.php".$contShopLink,
			'text' => $lang['cartNavi']['cont_shopping']
		);

if ($_GET['_a']!=="step3" && !empty($_SERVER['HTTP_REFERER'])) {
	$links[] = array (
		'link' => str_replace("&","&amp;",$_SERVER['HTTP_REFERER']),
		'text' => $lang['cartNavi']['prev_page']
	);
}

if($_GET['_a']!=="step3") {
	$links[] = array (
		'link' => "index.php",
		'text' => $lang['cartNavi']['homepage']
	);
}

for ($i=0; $i<count($links); $i++){
	$box_content->assign("VAL_LINK", $links[$i]['link']);
	$box_content->assign("TXT_LINK", $links[$i]['text']);
	$box_content->parse("links.repeat_region");
}
$box_content->parse("links");

$box_content = $box_content->text("links");
?>