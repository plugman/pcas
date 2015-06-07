<?php
/*
+--------------------------------------------------------------------------
|	viewCat.inc.php
|   ========================================
|	Display the Current Category	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."viewCat.inc.php");
$lang2 = getLang("includes" . CC_DS . "content" . CC_DS . "contactus.inc.php");
$lang = array_merge($lang, $lang2);
$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;
$view_cat = new XTemplate ("content".CC_DS."repair.tpl");
$view_cat->assign("LANG_DIR_LOC", $lang['viewCat']['location']);
$pickudistence = $config['sarea'];
////////////////////////
// BUILD SUB CATEGORIES
////////////////////////
if(isset($_POST['repair']) && !empty($_POST['repair']['postcode'])){
/*echo "<pre>";
print_r($_POST);*/	
	try
{
    $info = get_driving_information($config['spostcode'], $_POST['repair']['postcode']);
	$distancee = $info['distance'];
    $distance =  'Distance: '.number_format($info['distance'], 2, '.', '').' Miles';
	if($distancee > $pickudistence)
	$distance = $distance.' Sorry Our Service is not Available in this area.';
}
catch(Exception $e)
{
    $errorMsg = $e->getMessage();
}if($_POST['repair']['pickup_time'] == $_POST['repair']['dropof_time']){
	$samepath = 'Pickup Time and Drop time are same';
}

# Outputs 229.00 miles 14640 seconds
}
$docresult = $db->select("SELECT doc_name,doc_content,doc_id,doc_metatitle,doc_metadesc,doc_metakeywords FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id IN(28,29,30,31,39,40)");
$foreignDocs = $db->select("SELECT doc_content,doc_master_id as doc_id, doc_name FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_lang = '" . LANG_FOLDER . "' AND doc_master_id IN(28,29,30,31.39,40)");

if (is_array($foreignDocs)) {
	for ($l=0; $l<count($docresult); $l++) {
			for ($k=0; $k<count($foreignDocs); $k++) {
				if ($foreignDocs[$k]['doc_id'] == $docresult[$l]['doc_id']) {
					 $docresult[$l]['doc_id'];
					$docresult[$l]['doc_content'] = $foreignDocs[$k]['doc_content'];
				}
			}
		}
}
$view_cat->assign("ABOUT_REPAIR", $docresult[1]['doc_content']);
$view_cat->assign("HOW_ITS_WORK", $docresult[0]['doc_content']);
if($_GET['added']==1) {		
		$view_cat->parse("view_cat.added");
	}
if(!$_GET['productId'] && !$_GET['mail_in'] && !$_GET['procedure'] && !$_GET['rcontact']){

if (!isset($_GET['catId'])) {
	$query	= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = 0 AND hide = '0' AND type = '2' ORDER BY priority,cat_name ASC";
	$resultsForeign = $db->select("SELECT cat_master_id as cat_id, cat_name FROM ".$glob['dbprefix']."ImeiUnlock_cats_lang WHERE cat_lang = '" . LANG_FOLDER . "'");
	
}else{
	$query	= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = ".$db->mySQLSafe($_GET['catId']). " AND hide = '0' AND type = '2'  ORDER BY priority,cat_name ASC";
	
}
$catResult = $db->select($query);
if(!empty($catResult)){
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
## Get current category info
if (!empty($catResult[$j]['cat_image'])) {
	## loop results
		if (is_array($resultsForeign)) {
			for ($k=0; $k<count($resultsForeign); $k++) {
				if ($resultsForeign[$k]['cat_id'] == $catResult[$j]['cat_id']) {
					$catResult[$j]['cat_name'] = $resultsForeign[$k]['cat_name'];
				}
			}
		}
	$thumbRoot		= imgPath($catResult[$j]['cat_image'], true, 'root');
	$thumbRootRel	= imgPath($catResult[$j]['cat_image'], true, 'rel');
	if (file_exists($thumbRoot)) {
			$view_cat->assign("IMG_CURENT_CATEGORY", str_replace("&", "&amp;", $thumbRootRel));
			} else {
				$view_cat->assign("IMG_CURENT_CATEGORY", $GLOBALS['rootRel']."skins/". SKIN_FOLDER . "/styleImages/thumb_nophoto.gif");
			}
	$view_cat->assign("TXT_CURENT_CATEGORY", validHTML($catResult[$j]['cat_name']));
}else
$view_cat->assign("IMG_CURENT_CATEGORY", '');
$view_cat->assign("CURRENT_LOC", getCatDir($catResult[$j]['cat_name'], $catResult[$j]['cat_father_id'], $catResult[$j]['cat_id'], true));
	$view_cat->assign("TXT_CAT_TITLE", $catResult[$j]['cat_name']);
	$view_cat->assign("TXT_CAT_ID", $catResult[$j]['cat_id']);
if (!empty($currentCat[0]['cat_desc'])) {
	$view_cat->assign("TXT_CAT_DESC", $currentCat[0]['cat_desc']);
	$view_cat->parse("view_cat.cat_true.cat_desc");
}
$currPage = currentPage();
if ($config['sef']) {
	$currPage = '?';
}
## repeated region
	$view_cat->parse("view_cat.cat_selLoop");
	$view_cat->parse("view_cat.cate_true.cat_true");
}
}$view_cat->parse("view_cat.cate_true");
}elseif($_GET['rcontact'] && !$_GET['mail_in'] && !$_GET['procedure']){
	if ($_POST) {
    $view_cat->assign("VAL_NAME", $_POST['name']);
    $view_cat->assign("VAL_EMAIL", $_POST['email']);
    $view_cat->assign("VAL_PHONE", $_POST['phone']);
    $view_cat->assign("VAL_COMPANY", $_POST['company']);
    $view_cat->assign("VAL_COMMENTS", $_POST['msg']);

    // start validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['msg'])) {
        $errorMsg = $lang['contactus']['fill_required'];

    } elseif (validateEmail($_POST['email']) == false) {

        $errorMsg = $lang['contactus']['enter_valid_email'];
    } elseif (!ereg("[0-9]", $_POST['phone']) && $_POST['phone'] != "") {

        $errorMsg = $lang['contactus']['enter_valid_tel'];

    } else {
        // send email

        $view_cat->assign("VAL_NAME", "");
        $view_cat->assign("VAL_EMAIL", "");
        $view_cat->assign("VAL_PHONE", "");
        $view_cat->assign("VAL_COMPANY", "");
        $view_cat->assign("VAL_COMMENTS", "");
        $errorMsg = $lang['contactus']['mailsent'];

        if ($errorMsg != "") {
            $view_cat->assign("VAL_ERROR", $errorMsg);
            $view_cat->parse("view_cat.contactus.error");
        }

        require ("classes" . CC_DS . "htmlMimeMail" . CC_DS . "htmlMimeMail.php");

        $lang = getLang("email.inc.php");
	
        $mail = new htmlMimeMail();
        $macroArray = array(
		"RECIP_NAME" => "Administrator", 
		"EMAIL" => $_POST['email'], 
		"NAME" => $_POST['name'], 
		"PHONE" => $_POST['phone'], 
		"DEVICE" => $_POST['device'], 
		"COMMENTS" => $_POST['msg'], 
		"SENDER_IP" => get_ip_address());
        $text = macroSub($lang['email']['repair_contact_us_body'], $macroArray);
        unset($macroArray);

     	$mail->setText($text);
        $mail->setReturnPath($_POST['email']);
        $mail->setFrom($_POST['name'] . ' <' . $_POST['email'] . '>');
        $mail->setSubject($config['masterName']." ".$lang['email']['repair_contact_us_subject']);
        $mail->setHeader('X-Mailer', 'Mailer');
        $send = $mail->send(array($config['masterEmail']), $config['mailMethod']);
        $mailSent = true;

    }
}

if ($mailSent == true) {
    $view_cat->assign("MAIL_SENT", sprintf($lang['contactus']['mailsent'], $_POST['email']));
    $view_cat->assign("view_cat_STATUS", $lang['contactus']['mailsent']);
} else {

    //$view_cat->assign("view_cat_STATUS",$lang['contactus']['fill_required']);
    //$view_cat->assign("LANG_EMAIL",$lang['contactus']['email']);

    if ($errorMsg != "") {
        $view_cat->assign("VAL_ERROR", $errorMsg);
        $view_cat->parse("view_cat.contactus.error");
    }
}
$docresult3 = $db->select("SELECT doc_content FROM ".$glob['dbprefix']."ImeiUnlock_docs_lang WHERE doc_master_id IN(21) AND doc_lang = '" . LANG_FOLDER . "'");
$docresult2 = $db->select("SELECT doc_content,doc_metatitle,doc_metadesc,doc_metakeywords FROM ".$glob['dbprefix']."ImeiUnlock_docs WHERE doc_id IN(21)");
if($docresult3)
$docresult2[0]['doc_content'] = $docresult3[0]['doc_content'];
if(isset($_GET['rcontact'])){
	
	$result = $db->select("SELECT I.name,I.productId,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE productId = ".$db->mySQLSafe($_GET['rcontact']). "  AND digital = '2' ");
	if($result){
		if (($val = prodAltLang($result[0]['productId'])) == true) {
			$result[0]['name'] = $val['name'];
		}
	$view_cat->assign("VAL_DEVICE", getproglemtree($result[0]['cat_name'], $result[0]['cat_father_id'], $result[0]['cat_id']).' - '.$result[0]['name']);
}
}
$view_cat->assign("LANG_NAME", $lang['contactus']['name']);
$view_cat->assign("LANG_DEVICE", $lang['contactus']['device']);
$view_cat->assign("LANG_EMAIL", $lang['contactus']['email_address']);
$view_cat->assign("LANG_PHONE", $lang['contactus']['phone']);
$view_cat->assign("LANG_COMPANY", $lang['contactus']['company_Name']);
$view_cat->assign("LANG_COMMENTS", $lang['contactus']['comments']);
$view_cat->assign("TXT_SUBMIT", $lang['contactus']['send_pass']);
$view_cat->assign("DOC_CONTENT", $docresult2[0]['doc_content']);
if($config['Latitude'] && $config['Longitude']){
	$view_cat->assign("VAL_LATITUDE", $config['Latitude']);
	$view_cat->assign("VAL_LONGITUDE", $config['Longitude']);
	$view_cat->assign("VAL_TIT", trim(preg_replace('/\s\s+/', '<br>', $config['adtit'])));
	$view_cat->assign("VAL_LATITUDE2", $config['Latitude2']);
	$view_cat->assign("VAL_LONGITUDE2", $config['Longitude2']);
	$view_cat->assign("VAL_TIT2", trim(preg_replace('/\s\s+/', '<br>', $config['adtit2'])));
	 $view_cat->parse("view_cat.contactus.map_true");
}
	$view_cat->parse("view_cat.contactus");
}elseif($_GET['procedure']){
	$url = generateProductUrl($_GET['procedure']);
	$url = str_replace('prod', 'problem', $url);
	$url2 = str_replace('problem', 'mail_in', $url);
	$url3 = str_replace('mail_in', 'Repair_Contact', $url2);
	$view_cat->assign("CONTACT_PICK_UP", $url3);
	$view_cat->assign("PICK_UP", $url);
	$view_cat->assign("MAIL_IN", $url2);
	$view_cat->assign("MAIL_IN_TEXT", $docresult[2]['doc_content']);
	$view_cat->assign("MAIL_IN_NAME", $docresult[2]['doc_name']);
	if($config['spickup']){
		$view_cat->assign("PICK_UP_TEXT", $docresult[4]['doc_content']);
		$view_cat->assign("PICK_UP_NAME", $docresult[4]['doc_name']);

		$view_cat->parse("view_cat.procedure.pickup_true");
	}
	if($config['smailin']){
		$view_cat->parse("view_cat.procedure.mailin_true");
	}if(!$config['smailin'] && !$config['spickup']){
		$view_cat->parse("view_cat.procedure.procedure_false");
	}
$view_cat->parse("view_cat.procedure");
}elseif($_GET['productId']){
	if($errorMsg != "" || $distance != ""){
	 if ($errorMsg != "") {
		 if($samepath !="")
		 $errorMsg .= "<br />" .$samepath;
            $view_cat->assign("VAL_ERROR", $errorMsg);
            $view_cat->parse("view_cat.pick_up.errors.error");
        }
		if ($distance != "" && $errorMsg == "") {
			if($samepath !="")
		 	$distance .= "<br />" .$samepath;
            $view_cat->assign("VAL_INFO", $distance);
            $view_cat->parse("view_cat.pick_up.errors.info");
        }
		 $view_cat->parse("view_cat.pick_up.errors");
	}
	if($errorMsg == "" && $distancee < $pickudistence && $_POST['repair']['postcode'] && $_POST['repair']['pickup_time'] != $_POST['repair']['dropof_time']){
		$result = $db->select("SELECT I.productId,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE productId = ".$db->mySQLSafe($_GET['productId']). "  AND digital = '2' ");
	if($result){
		if (($val = prodAltLang($result[0]['productId'])) == true) {
			$result[0]['name'] = $val['name'];
		}
		$tree = getproglemtree($result[0]['cat_name'], $result[0]['cat_father_id'], $result[0]['cat_id']);
		$view_cat->assign("CATE", $tree);
	}
		$view_cat->assign("PICK_UP", $_POST['repair']['pickup_time']);
		$view_cat->assign("DROP", $_POST['repair']['dropof_time']);
		$view_cat->assign("PRO_ID", $_GET['productId']);
		$view_cat->assign("POST_CODE", $_POST['repair']['postcode']);
		$view_cat->parse("view_cat.pick_up.addtobasket");
	}
if(date('N', time()) == 1)
$order= "1,2,3,4,5,6,7";
elseif(date('N', time()) == 2)
$order= "2,3,4,5,6,7,1";
elseif(date('N', time()) == 3)
$order= "3,4,5,6,7,1,2";
elseif(date('N', time()) == 4)
$order= "4,5,6,7,1,2,3";
elseif(date('N', time()) == 5)
$order= "5,6,7,1,2,3,4";
elseif(date('N', time()) == 6)
$order= "6,7,1,2,3,4,5";
elseif(date('N', time()) == 7)
$order= "7,1,2,3,4,5,6";
$days = 0;

$results = $db->select("SELECT day FROM ".$glob['dbprefix']."ImeiUnlock_pickup GROUP BY day ORDER BY FIELD(day ,".$order.") ");
	for($i=0;$i<count($results);$i++){
		$daytime = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_pickup WHERE day = ".$results[$i]['day']." ORDER BY ".$glob['dbprefix']."ImeiUnlock_pickup.from DESC");
			$days = $results[$i]['day'] - date('N', time());
			if($days < 0)
			$days = 7 - str_replace('-', '', $results[$i]['day'] - date('N', time()));
			for($j=0;$j<count($daytime);$j++){
			$view_cat->assign("DAY_TIME", $daytime[$j]['from'].' - ' .$daytime[$j]['to'].' . '. date("l, d M", strtotime('+'.$days.' day')));
			$view_cat->assign("DAY_TIME_VALUE", date("o-m-d ", strtotime('+'.$days.' day')).$daytime[$j]['from']);
			$view_cat->parse("view_cat.pick_up.frmrepeat_date.frmrepeat_time");
			$view_cat->parse("view_cat.pick_up.repeat_date.repeat_time");
			}
			$view_cat->assign("DAY_GROUP", date("D, d M Y", strtotime('+'.$days.' day')));
			$view_cat->parse("view_cat.pick_up.frmrepeat_date");
			$view_cat->parse("view_cat.pick_up.repeat_date");
			$excluded = array("add"=>1,"quan"=>1,"notice"=>1,"added"=>1);
			$view_cat->assign("CURRENT_URL", currentPage($excluded));
			$view_cat->assign("PICK_UP_TITLE", $docresult[5]['doc_name']);
			$view_cat->assign("PICK_UP_DETAIL", $docresult[5]['doc_content']);
	}
///print_r($results);

$view_cat->parse("view_cat.pick_up");
}elseif($_GET['mail_in']){
	$excluded = array("add"=>1,"quan"=>1,"notice"=>1,"added"=>1);
	$view_cat->assign("CURRENT_URL", currentPage($excluded));
	$result = $db->select("SELECT I.productId,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE productId = ".$db->mySQLSafe($_GET['mail_in']). "  AND digital = '2' ");
	if($result){
		if (($val = prodAltLang($result[0]['productId'])) == true) {
			$result[0]['name'] = $val['name'];
		}
		$tree = getproglemtree($result[0]['cat_name'], $result[0]['cat_father_id'], $result[0]['cat_id']);
		$view_cat->assign("CATE", $tree);
	}
	$view_cat->assign("PRO_ID", $_GET['mail_in']);
	$url = generateProductUrl($_GET['procedure']);
	$url = str_replace('prod', 'problem', $url);
	$url2 = str_replace('problem', 'mail_in', $url);
	$view_cat->assign("PICK_UP", $url);
	$view_cat->assign("MAIL_IN", $url2);
	$view_cat->assign("MAIL_IN_TITLE", $docresult[3]['doc_name']);
	$view_cat->assign("MAIL_IN_DETAIL", $docresult[3]['doc_content']);
	
$view_cat->parse("view_cat.mailin");
}

$meta['sefSiteTitle']		= strip_tags($docresult[1]['doc_metatitle']); 
$meta['sefSiteDesc']		= strip_tags($docresult[1]['doc_metadesc']); 
$meta['sefSiteKeywords']		= strip_tags($docresult[1]['doc_metakeywords']); 
$view_cat->parse("view_cat");
$page_content = $view_cat->text("view_cat");
?>