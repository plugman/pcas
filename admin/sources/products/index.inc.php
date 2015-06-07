<?php

/*

+--------------------------------------------------------------------------

|	index.inc.php

|   ========================================

|	Add/Edit/Delete Products	

+--------------------------------------------------------------------------

*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_products.inc.php");

require("classes".CC_DS."gd".CC_DS."gd.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");

require_once ("classes" . CC_DS . "xmlparse" . CC_DS . "xml2array.php");

permission('products', 'read', true);

$productsPerPage = 25;

if (isset($_POST['DeleteSelected']) && is_array($_POST['product'])) {

	$query = sprintf("DELETE FROM %sImeiUnlock_inventory WHERE `productId` IN (%s)", $glob['dbprefix'], implode(',', $_POST['product']));

	$db->misc($query);

}

if (isset($_POST['EditSelected']) && is_array($_POST['product']) && !empty($_POST['MoveTo'])) {

	$i=0;

	foreach ($_POST['product'] as $key => $product_id) {

		

		if ($_POST['current'][$product_id] == $_POST['MoveTo']) break;

		

		## Update primary category

		$query = sprintf("UPDATE %sImeiUnlock_inventory SET `cat_id`='%d' WHERE `productId`='%d';", $glob['dbprefix'], $_POST['MoveTo'], $product_id);

		$db->misc($query);

		

		## Check if target category was added as an extra for product before

		$query = sprintf("SELECT cat_id FROM %sImeiUnlock_cats_idx WHERE `cat_id` = '%d' AND productId = '%d' LIMIT 1;", $glob['dbprefix'], $_POST['MoveTo'], $product_id);

		$previous = $db->select($query);

		

		if ($previous) {

			## Delete old primary category record only, new category already there

			$query = sprintf("DELETE FROM %sImeiUnlock_cats_idx WHERE cat_id = '%d' AND productId = '%d'", $glob['dbprefix'], $_POST['current'][$product_id], $product_id );

			$db->misc($query);

			

			## Product counted before we dont need it for target category product count

			unset($_POST['product'][$key]);

		} else {

			## Update secondary categories

			$query = sprintf("UPDATE %1\$sImeiUnlock_cats_idx SET cat_id = '%2\$d' WHERE productId = '%3\$d' AND cat_id = '%4\$d' LIMIT 1;", $glob['dbprefix'], $_POST['MoveTo'], $product_id, $_POST['current'][$product_id]);

			$db->misc($query);

		}

		## Update category product count

		$db->categoryNos($_POST['current'][$product_id], '-', 1);

		$db->categoryNos($_POST['MoveTo'], '+', 1);

		$i++;

	}

	

	$cache = new cache();

	$cache->clearCache();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getproducts")



{

	$data = getpricexml();

	if(isset($data) && !empty($data))



	{

		$converter = new Xml2Array();

		$converter->setXml($data);

		$xml_array = $converter->get_array();

		 $_count	   = $xml_array['data']['products']['counter']['count']['#text'];

		$_catcount = 0;

		if($_count>1){

		//do nothing

		}else{

		$xml_array['data']['products']['product'][0]= $xml_array['data']['products']['product'];

		}



		



		



		/*echo "<PRE>";



		print_r($xml_array['data']['products']);



		exit();*/



		



		



		//Extracting and Pushing Data into DB



		for($i=0; $i< $_count; $i++)



		{



				$xmlcproId				= $xml_array['data']['products']['product'][$i]['product_id']['#text']; 

				if($xml_array['data']['products']['product'][$i]['disabled']['#text'] == 1)

				$record["costprice"] 	= $db->mySQLSafe(0);

				else

				$record["costprice"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['price']['#text']);

				/*echo "<pre>";

				print_r($record);*/

				//echo $record[$i]['product_id']; die();

						$_catcount++; 

						$where = "xmlproductId=" . $db->mySQLSafe($xmlcproId);

						$update = $db->update("ImeiUnlock_inventory", $record, $where);

						unset($record);		

	}

		if($_catcount==0)



		{



			$msg = "<p class='infoText'>Failed to Update Database.</p>";	



		}else



		{

			$msg = "<p class='infoText'>".$_catcount."&nbsp;Products were Updated successfully.</p>";	



		}

		//$xml_array['message']['device'][0]['model']['#text'];



	}



	else if(empty($data)){



	$msg = "<p class='infoText'>Failed to Update Database</p>";



	}



	else{



	$msg = "<p class='infoText'>Invalid Configuration Setting Contact with Imei-Unlock Administration</p>";



	}



}

if(isset($_POST['normPer']) || isset($_POST['salePer']))

{

	$cache = new cache();

	$cache->clearCache();

	

	$sqlUpdateWhere = "";

	

	if(is_array($_POST['cat_id_price']))

	{

		for ($n=0; $n<count($_POST['cat_id_price']); $n++)

		{

			if($_POST['cat_id_price'][$n]>0)

			{

				if($n==0)

				{

					$sqlUpdateWhere .= " WHERE cat_id = ".$db->mySQLSafe($_POST['cat_id_price'][$n]);

				}

				else

				{

					$sqlUpdateWhere .= " OR cat_id = ".$db->mySQLSafe($_POST['cat_id_price'][$n]);

				}

			}

		}	

	}

	

	if (is_numeric($_POST['normPer'])) {

		

		if($_POST['normPerMethod']=="percent" && $_POST['normPer']>0){

			$sum = "`price` * ".($_POST['normPer']/100);

		} elseif($_POST['normPerMethod']=="value" && $_POST['normPer']<0) {

			$sum = "`price` ".$_POST['normPer'];

		} elseif($_POST['normPerMethod']=="value") {

			$sum = "`price` + ".$_POST['normPer'];

		} elseif($_POST['normPerMethod']=="actual" && $_POST['normPer']>0) {

			$sum = $_POST['normPer'];

		} else {

			$sum = "`price`";

		}

		

		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET `price` = ".$sum.$sqlUpdateWhere;

		$result = $db->misc($query);

	}

	

	if (is_numeric($_POST['salePer'])) {

		

		if ($_POST['salePerMethod']=="percent" && $_POST['salePer']>0) {

			$sum = "`sale_price` * ".($_POST['salePer']/100);

		} else if ($_POST['salePerMethod']=="value" && $_POST['salePer']<0) {

			$sum = "`sale_price` ".$_POST['salePer'];

		} elseif ($_POST['salePerMethod']=="value") {

			$sum = "`sale_price` + ".$_POST['salePer'];

		} elseif ($_POST['salePerMethod']=="actual" && $_POST['salePer']>0) {

			$sum = $_POST['salePer'];

		} else {

			$sum = "`sale_price`";

		}

		

		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET `sale_price` = ".$sum.$sqlUpdateWhere;

		$result = $db->misc($query);

	}

	if ($result) {

		$msg2 = "<p class='infoText'>".$lang['admin']['products_price_upd_successful']."</p>";

	} else {

		$msg2 = "<p class='warnText'>".$lang['admin']['products_price_upd_fail']."</p>";

	}

} else if (isset($_GET['delete']) && $_GET["delete"]>0) {

	$cache = new cache();

	$cache->clearCache();

		

	// delete product

	$where = "productId=".$db->mySQLSafe($_GET["delete"]);

	$selectimageid="select image image2 from ".$glob['dbprefix']."ImeiUnlock_inventory where ".$where;

	$select=$db->select($selectimageid);

	if($select[0]['image']!="" && file_exists("images/uploads/" . $select[0]['image'])){

	unlink("images/uploads/" . $select[0]['image']);

	}

	if($select[0]['image']!="" && file_exists("images/uploads/thumbs/thumb_" . $select[0]['image'])){

	unlink("images/uploads/thumbs/thumb_" . $select[0]['image']);

	}

	if($select[0]['image2']!="" && file_exists("images/uploads/" . $select[0]['image2'])){

	unlink("images/uploads/" . $select[0]['image2']);

	}

	if($select[0]['image2']!="" && file_exists("images/uploads/thumbs/thumb_" . $select[0]['image2'])){

	unlink("images/uploads/thumbs/thumb_" . $select[0]['image2']);

	}

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_inventory", $where);

	// set categories -1

	$cats = $db->select("SELECT cat_id FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx WHERE productId=".$db->mySQLSafe($_GET["delete"]));

	if ($cats == true) {

		for ($i=0;$i<count($cats);$i++) {

			$db->categoryNos($cats[$i]['cat_id'], '-');

		}

	}

	

	// delete category index

	$where = "productId=".$db->mySQLSafe($_GET["delete"]);  

	$deleteIdx = $db->delete($glob['dbprefix']."ImeiUnlock_cats_idx", $where);

	unset($record);

	

	// delete product options

	$record['product'] = $db->mySQLSafe($_GET["delete"]);

	$where = "product=".$db->mySQLSafe($_GET["delete"]);  

	$deleteOps = $db->delete($glob['dbprefix']."ImeiUnlock_options_bot", $where);

	unset($record);

	

	if ($delete == true) {

		$msg = "<p class='infoText'>".$lang['admin']['products_delete_success']."</p>";

	} else {

		$msg = "<p class='warnText'>".$lang['admin']['products_delete_fail']."</p>";

	}

} else if (isset($_POST['productId'])) {

	$cache = new cache();

	$cache->clearCache();

	/*echo $config['gdthumbSize'];

	echo $config['gdmaxImgSize'];*/

	if ($_FILES['primaryImage']['name'] != ""){

				$path_parts2 	= pathinfo($_FILES['primaryImage']['name']);

				$ext 		 	= ".".$path_parts2['extension'];

				$imageName1		= date("jnYHis")."_1".$ext ;

				

				$TempPath		= filePathTemp($imageName1, $path="root"); 

			    $rootMasterFile = imgPath($imageName1,'',$path="root");	

				$rootThumbFile 	= imgPath($imageName1,'thumb',$path="root");	

				$rootSmallFile 	= imgPath($imageName1,'small',$path="root");

					$rootTinyFile 	= imgPath($imageName1,'tiny',$path="root");		

				$magicToolBox 	= imgPath($imageName1,'magictoolbox',$path="root");		

				if (!move_uploaded_file($_FILES['primaryImage']['tmp_name'], $TempPath)){

						$Flash_Type_Error = true;

					}

				if(file_exists($TempPath)){

					$oldrootMasterFile = imgPath($_POST['oldimage'],'',$path="root");

					$oldrootThumbFile 	= imgPath($_POST['oldimage'],'thumb',$path="root");

						$oldrootSmallFile 	= imgPath($_POST['oldimage'],'small',$path="root");

						$oldrootTinyFile 	= imgPath($_POST['oldimage'],'tiny',$path="root");							

					$oldmagicToolBox 	= imgPath($_POST['oldimage'],'magictoolbox',$path="root");	

					if(file_exists($oldrootMasterFile)){

						@unlink($oldrootMasterFile);

					}					

					if(file_exists($oldrootThumbFile)){

						@unlink($oldrootThumbFile);

					}

					// Main Image

					$imgMain = new gd($TempPath); 

									// Contructor and set source image file

					$imgMain->size_auto($config['gdmaxImgSize']);		// [OPTIONAL] set the biggest width or height for thumbnail	

					$imgMain->save($rootMasterFile);					// save your thumbnail to file		

					

					// Thumb Image 				

					$imgThumb = new gd($TempPath); 						// Contructor and set source image file

					//echo $config['gdthumbSize']; exit();

					$imgThumb->size_auto($config['gdthumbSize']);		

					$imgThumb->save($rootThumbFile);				

							

					@unlink($TempPath);

					

							$record['image']	= $db->mySQLSafe($imageName1);		

				}

			}

			if ($_FILES['primaryImage2']['name'] != ""){

				$path_parts2 	= pathinfo($_FILES['primaryImage2']['name']);

				$ext 		 	= ".".$path_parts2['extension'];

				$imageName1		= date("jnYHis")."_1".$ext ;

				

				$TempPath		= filePathTemp($imageName1, $path="root"); 

			    $rootMasterFile = imgPath($imageName1,'',$path="root");	

				$rootThumbFile 	= imgPath($imageName1,'thumb',$path="root");	

				$rootSmallFile 	= imgPath($imageName1,'small',$path="root");

					$rootTinyFile 	= imgPath($imageName1,'tiny',$path="root");		

				$magicToolBox 	= imgPath($imageName1,'magictoolbox',$path="root");		

				if (!move_uploaded_file($_FILES['primaryImage2']['tmp_name'], $TempPath)){

						$Flash_Type_Error = true;

					}

				if(file_exists($TempPath)){

					$oldrootMasterFile = imgPath($_POST['oldimage2'],'',$path="root");

					$oldrootThumbFile 	= imgPath($_POST['oldimage2'],'thumb',$path="root");

						$oldrootSmallFile 	= imgPath($_POST['oldimage2'],'small',$path="root");

						$oldrootTinyFile 	= imgPath($_POST['oldimage2'],'tiny',$path="root");							

					$oldmagicToolBox 	= imgPath($_POST['oldimage2'],'magictoolbox',$path="root");	

					if(file_exists($oldrootMasterFile)){

						@unlink($oldrootMasterFile);

					}					

					if(file_exists($oldrootThumbFile)){

						@unlink($oldrootThumbFile);

					}

					// Main Image

					$imgMain = new gd($TempPath); 

									// Contructor and set source image file

					$imgMain->size_auto($config['gdmaxImgSize']);		// [OPTIONAL] set the biggest width or height for thumbnail	

					$imgMain->save($rootMasterFile);					// save your thumbnail to file		

					

					// Thumb Image 				

					$imgThumb = new gd($TempPath); 						// Contructor and set source image file

					//echo $config['gdthumbSize']; exit();

					$imgThumb->size_auto($config['gdthumbSize']);		

					$imgThumb->save($rootThumbFile);				

							

					@unlink($TempPath);

					

							$record['image2']	= $db->mySQLSafe($imageName1);		

				}

			}

	$allowedFields = $db->getFields($glob['dbprefix'].'ImeiUnlock_inventory');

	

	foreach ($_POST as $name => $value) {

		if (in_array($name, $allowedFields)) { // && (!empty($value) || $value == '0')) {

			$record[$name] = $db->mySQLSafe($value);

		}

	}

		

	## Custom field translation

	//$record['image']			= $db->mySQLSafe(imgPath($_POST['imageName'], false, ''));

	$record['tax_inclusive']	= ($_POST['tax_inclusive'] == 1) ? '1' : '0';

	$record['cat_id']			= $db->mySQLSafe($_POST['cat_id']);

	

	$description				= (detectSSL() && !$config['force_ssl'] && $config['rootRel'] != CC_DS) ?  str_replace($config['rootRel_SSL'], $glob['rootRel'], $_POST['FCKeditor']) : $_POST['FCKeditor'];	

	$record['description']		= (!empty($description)) ? $db->mySQLSafe($description) : 'NULL';

		

	## Generate product code

	if (empty($_POST['productCode'])) {

		$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N",

				"O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3",

				"4","5","6","7","8","9","0");

		$max_chars = count($chars) - 1;

		srand((double)microtime()*1000000);

		for ($i = 0; $i < 5; $i++) {

			$randChars = ($i == 0) ? $chars[rand(0, $max_chars)] : $randnum . $chars[rand(0, $max_chars)];

		}

		$record["productCode"] = $db->mySQLSafe(strtoupper(substr($_POST['name'],0,3)).$randChars.$_POST['cat_id']);

	} else {

		$record["productCode"] = $db->mySQLSafe($_POST['productCode']);	

	}

	

	$record["productCode"] = preg_replace("/[^a-z0-9-]/i","",$record["productCode"]);

	$record["productCode"] = $db->mySQLSafe($record["productCode"]);

	$record['short_title']		= $db->mySQLSafe($_POST['short_title']);

	// if image is a JPG check thumbnail doesn't exist and if not make one

	

	/*

	$imageFormat = strtoupper(ereg_replace(".*\.(.*)$","\\1",$_POST['imageName']));

	if($imageFormat == "JPG" || $imageFormat == "JPEG" || $imageFormat == "PNG" || ($imageFormat == "GIF" && $config['gdGifSupport']==1)) {

		$rootThumbFile = imgPath($_POST['imageName'],$thumb=1,$path="root");

		$rootMasterFile = imgPath($_POST['imageName'],$thumb=0,$path="root");

		if(file_exists($rootThumbFile)) {

			@unlink($rootThumbFile);

		}

		$img = new gd($rootMasterFile);

		$img->size_auto($config['gdthumbSize']);

		$img->save($rootThumbFile);

	}

	*/

	

	if (isset($_POST['productId']) && $_POST['productId']>0) {

 		

		$disable_sql = sprintf('SELECT disabled FROM '.$glob['dbprefix'].'ImeiUnlock_inventory WHERE productId = '.$_POST['productId']);

		$disabled = $db->select($disable_sql);

		

		$disable_flag = $disabled_flag = false;

		

		if ($disabled) {

			if ($disabled[0]['disabled'] && !$_POST['disabled']) {

				$db->categoryNos($_POST['cat_id'], '+');

				$disabled_flag = true;

			} else if (!$disabled[0]['disabled'] && $_POST['disabled']) {

				$db->categoryNos($_POST['cat_id'], '-');

				$disable_flag = true;

			}

		}

		

		$where = "productId=".$db->mySQLSafe($_POST['productId']);

		$product_id = $_POST['productId'];

		

		$update = $db->update($glob['dbprefix']."ImeiUnlock_inventory", $record, $where);

		unset($record, $where);

		

		// update category count

		if($_POST['oldCatId']!==$_POST['cat_id']) {

				

				## set old category -1 IF IT WAS IN THERE BEFORE

				$numOldCat = $db->numrows("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx WHERE cat_id = ".$db->mySQLSafe($_POST['oldCatId'])." AND productId = ".$db->mySQLSafe($_POST['productId']));

				if ($numOldCat>0 && !$disabled_flag) {

					$db->categoryNos($_POST['oldCatId'], "-");

				}

				## set new category +1 IF IT WAS NOT IN THERE BEFORE

				$numNewCat = $db->numrows("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx WHERE cat_id = ".$db->mySQLSafe($_POST['cat_id'])." AND productId = ".$db->mySQLSafe($_POST['productId']));

				if($numNewCat == 0 && !$disabled_flag && !$disable_flag) {

					$db->categoryNos($_POST['cat_id'], "+");

				}

				

				## delete old index

				$where = "productId = ".$db->mySQLSafe($_POST['productId'])." AND cat_id = ".$db->mySQLSafe($_POST['oldCatId']);  

				$deleteIdx = $db->delete($glob['dbprefix']."ImeiUnlock_cats_idx", $where);

				unset($record);

				

				// delete new index if it was added as an extra before

				$where = "productId = ".$db->mySQLSafe($_POST['productId'])." AND cat_id = ".$db->mySQLSafe($_POST['cat_id']);  

				$deleteIdx = $db->delete($glob['dbprefix']."ImeiUnlock_cats_idx", $where);

				unset($record);

							

				// add new idx

				$record['productId'] = $db->mySQLSafe($_POST['productId']);

				$record['cat_id'] = $db->mySQLSafe($_POST['cat_id']);  

				$insertIdx = $db->insert($glob['dbprefix']."ImeiUnlock_cats_idx", $record);

				unset($record);

				

		}

		

		// replace into cat_idx for missing values bug fix from v3 upgrade

		$query = "DELETE FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx WHERE cat_id = ".$db->mySQLSafe($_POST['cat_id'])." AND productId = ".$db->mySQLSafe($_POST['productId']);

		$db->misc($query);

		$query = "INSERT INTO ".$glob['dbprefix']."ImeiUnlock_cats_idx SET cat_id = ".$db->mySQLSafe($_POST['cat_id']).", productId = ".$db->mySQLSafe($_POST['productId']);

		$db->misc($query);

		if(isset($_POST['wsprice']) && !empty($_POST['wsprice']) && isset($_POST['wholesaleId']) && !empty($_POST['wholesaleId'])){

			for($i=0;$i<count($_POST['wsprice']);$i++){

				$j =$i +1 ;			

				$wsrecord['productId']=$db->mySQLSafe($_POST['productId']);

				$wsrecord['wsprice']=$db->mySQLSafe($_POST['wsprice'][$i]);

			if($_POST['wholesaleId'][$i]>0 && isset($_POST['wsprice'][$i])){	//update record		

			$where = " productId = ".$db->mySQLSafe($_POST['productId'])." AND wholesaleId= ".$db->mySQLSafe($_POST['wholesaleId'][$i]);

			$update2 = $db->update("ImeiUnlock_wholesale_prices", $wsrecord, $where);	

			unset($wsrecord);

			}

			else{	//insert new record

							if(isset($_POST['wsprice'][$i]) && $_POST['wsprice'][$i] > 0){

							$wsrecord['wholesaleId']=$db->mySQLSafe($_POST['customer_type'][$i]);

							$update = $db->insert("ImeiUnlock_wholesale_prices", $wsrecord);

							unset($wsrecord);

				}					

						}

			}

		}

		if ($update || $update2) {

			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_successful']."</p>";

		} else {

			$msg = "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";

		}

 		

	} else {

	 	

		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_inventory", $record);

		unset($record);

		

		$record['cat_id']		= $db->mySQLSafe($_POST['cat_id']);

		$record['productId']	= $db->insertid();  

		$product_id				= $db->insertid();

		

		$db->insert($glob['dbprefix']."ImeiUnlock_cats_idx", $record);

		unset($record);

		if ($insert) {

			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_add_success']."</p>";

			// notch up amount of products in category

			if ($_POST['disabled'] == 0) {

				$db->categoryNos($_POST['cat_id'], '+');

			}

		} else {

			$msg = "<p class='warnText'>".$lang['admin']['products_add_fail']."</p>";

		}

	}

		

	## Option manager

	if (isset($_POST['option_add']) && is_array($_POST['option_add']) && !empty($_POST['option_add'])) {

		foreach ($_POST['option_add'] as $option) {

			//$value		= explode('{|}', $option);

			$value		= explode('|', $option);

			$value[2] 	= preg_replace("#[^\d+\.\-]#", "", $value[2]);

			$data	= array(

				'product'		=> ($product_id) ? $product_id : $_POST['productId'],

				'option_id'		=> is_numeric($value[0]) ? $value[0] : $value[1],

				'value_id'		=> is_numeric($value[0]) ? $value[1] : 0,

				'option_price'	=> preg_replace('#[^0-9\.]#i', '', $value[2]),

				'option_symbol'	=> "'".(($value[2] >= 0) ? '+' : '-')."'",

			);

			$opt_insert = $db->insert($glob['dbprefix'].'ImeiUnlock_options_bot', $data);

		}

		if ($opt_insert) {

			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_successful']."</p>";

		} else {

			$msg = "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";

		}

	}

	if (isset($_POST['option_edit']) && is_array($_POST['option_edit']) && !empty($_POST['option_edit'])) {

		# Update existing product options

		$product_id = ($product_id) ? $product_id : $_POST['productId'];

		

		foreach ($_POST['option_edit'] as $assign_id => $option) {

			$value	= explode('|', $option);

			$value[2] 	= preg_replace("#[^\d+\.\-]#", "", $value[2]);

			$data	= array(

				'product'		=> $product_id,

				'option_id'		=> is_numeric($value[0]) ? $value[0] : $value[1],

				'value_id'		=> is_numeric($value[0]) ? $value[1] : 0,

				'option_price'	=> preg_replace('#[^0-9\.]#i', '', $value[2]),

				'option_symbol'	=> "'".(($value[2] >= 0) ? '+' : '-')."'",

			);

			$opt_update = $db->update($glob['dbprefix'].'ImeiUnlock_options_bot', $data, array('assign_id' => $assign_id, 'product' => $product_id));

		}

		if ($opt_update) {

			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_successful']."</p>";

		} else {

			$msg = "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";

		}

	}

	

	if (isset($_POST['option_remove']) && is_array($_POST['option_remove']) && !empty($_POST['option_remove'])) {

		foreach ($_POST['option_remove'] as $option) {

			$opt_delete = $db->delete($glob['dbprefix'].'ImeiUnlock_options_bot', array('assign_id' => $option, 'product' => ($product_id) ? $product_id : $_POST['productId']));

		}

		if ($opt_delete) {

			$msg = "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['products_update_successful']."</p>";

		} else {

			$msg = "<p class='warnText'>".$lang['admin']['products_update_fail']."</p>";

		}

	}

}

if (!isset($_GET['mode'])) {

	

	if(isset($_GET['edit']) && $_GET['edit']>0 OR isset($_GET['clone']) && $_GET['clone']>0){

		

		$product = (isset($_GET['edit']) && $_GET['edit']>0) ? $db->mySQLSafe($_GET['edit']) : $db->mySQLSafe($_GET['clone']);		

		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = $product"); 

		

	} else {

		if (isset($_GET['orderCol']) && isset($_GET['orderDir'])) {

			$orderBy =  "I.".$_GET['orderCol']." ".$_GET['orderDir'];

		} else {

			$orderBy = "I.productId ASC";

		}

		

		$whereClause = '';

		

		if (isset($_GET['searchStr']) && !empty($_GET['searchStr'])) {

			$searchwords = split ( "[ ,]", trim($_GET['searchStr'])); /* bug fix 1448 thanks Brivtech */

			  

			foreach ($searchwords as $word) {

				$searchArray[]=$word;

			}

			$noKeys = count($searchArray);

			for ($i=0; $i<$noKeys;$i++) {

		

				$ucSearchTerm = strtoupper($searchArray[$i]);

				if (($ucSearchTerm !== "AND") && ($ucSearchTerm !== "OR")) {

					$like .= "(I.name LIKE '%".$searchArray[$i]."%' OR I.description LIKE '%".$searchArray[$i]."%' OR  I.productCode LIKE '%".$searchArray[$i]."%') OR ";

				} else {

					$like = substr($like,0,strlen($like)-3);

					$like .= $ucSearchTerm;

				}

			}

			$like = substr($like,0,strlen($like)-3);

			$whereClause .= "AND ".$like;

		}

	

		if (isset($_GET['category']) && $_GET['category']>0) {

			$whereClause .= (isset($like)) ? ' AND ' : ' WHERE ';

			$whereClause .= "CI.cat_id = ".$_GET['category'];

			$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx AS CI INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON CI.productId = I.productId INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category AS C ON I.cat_id = C.cat_id  ".$whereClause." AND I.digital = '1' ORDER BY ".$orderBy;

		} else {

			$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory AS I INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category AS C ON I.cat_id = C.cat_id  WHERE I.digital = '1' ".$whereClause." ORDER BY ".$orderBy;

		} 

	}

	

	// query database

	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;

		

	$results = $db->select($query, $productsPerPage, $page);

	$numrows = $db->numrows($query);

	$pagination = paginate($numrows, $productsPerPage, $page, "page", "txtLink", 7, array('delete'));

	

}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>

<!--<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td nowrap='nowrap' class="pageTitle"><?php echo $lang['admin']['products_prod_inventory'];?></td>

    

    <td  align="right">

    <?php

if (!isset($_GET['mode'])) {

	$url = (permission("FAQs","write") == true) ? 'href="?_g=products/index&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;

	echo sprintf('<span class="right"><a %s><img class="left" src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', "<strong style='margin:4px 4px 0' class='left'>Add New</strong>");

}

?>

    </td>

  

  </tr>

  <tr>

  	<td colspan="2" height="10px"></td>

  </tr>

</table>-->

<script type="text/javascript">



function Extractproducts()



{



	document.getElementById('imgProgress').style.display = '';



	document.getElementById('addpro').style.display = 'none';



	document.getElementById('action').value = "getproducts";



	document.getElementById('frmExtract').submit();



}



</script>

<?php 

if (isset($msg)) echo msg($msg); 

/*if (!isset($_GET['mode']) && !isset($_GET['edit'])) {*/

if(!isset($_GET['mode']) && !isset($_GET['edit']) && !isset($_GET['clone'])){

	if ($results == true) {

?>

<p class="copyText"><strong><?php echo $lang['admin']['products_current_prods_in_db'];?></strong>



 </p>

<form name="filter" method="get" action="<?php echo $glob['adminFile']; ?>">

  <input type="hidden" name="_g" value="products/index" />

  <p  class="copyText" style="margin-top:5px; margin-bottom:10px">

    <select name="category" class="textbox2">

      <option value="All" <?php if(isset($_GET['category']) && $_GET['category']=="All") echo "selected='selected'"; ?>><?php echo $lang['admin']['products_all_cats'];?></option>

      <?php echo showCatList_digital(isset($_GET['category']) ? $_GET['category'] : null); ?>

    </select>

    <!--<?php echo $lang['admin']['products_by']; ?>-->

    <!--<select name="orderCol" class="textbox">

      <option value="name" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="name") echo "selected='selected'";?>><?php echo $lang['admin']['products_prod_name'];?></option>

      <option value="productId" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="productId") echo "selected='selected'";?>><?php echo $lang['admin']['products_prod_id'];?></option>

      <option value="productCode" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="productCode") echo "selected='selected'";?>><?php echo $lang['admin']['products_prod_code'];?></option>

      <option value="cat_id" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="cat_id") echo "selected='selected'";?>><?php echo $lang['admin']['products_master_cat2'];?></option>

      <option value="stock_level" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="stock_level") echo "selected='selected'";?>><?php echo $lang['admin']['products_stock_level'];?></option>

      <option value="price" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="price") echo "selected='selected'";?>><?php echo $lang['admin']['products_normal_price'];?></option>

      <option value="sale_price" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="sale_price") echo "selected='selected'";?>><?php echo $lang['admin']['products_sale_price'];?></option>

    </select>-->

   <!-- <?php echo $lang['admin']['products_in']; ?>-->

    <!--<select name="orderDir" class="textbox">

      <option value="ASC" <?php if(isset($_GET['orderDir']) && $_GET['orderDir']=="ASC") echo "selected='selected'";?>><?php echo $lang['admin']['products_asc'];?></option>

      <option value="DESC" <?php if(isset($_GET['orderDir']) && $_GET['orderDir']=="DESC") echo "selected='selected'";?>><?php echo $lang['admin']['products_desc'];?></option>

    </select>-->

    

    <input type="text" placeholder="<?php echo $lang['admin']['products_containing_text'];?>" name="searchStr" class="textbox2" value="<?php if(isset($_GET['searchStr']))echo $_GET['searchStr']; ?>"  style="margin-left:30px;"/>

    <input name="submit" style="margin:0 10px 0 11px" type="submit" class="submit" value="<?php echo $lang['admin']['products_filter'];?>" />

    <input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=products/index');return document.returnValue" value="<?php echo $lang['admin']['products_reset'];?>" class="submit" />

  </p>

</form>

<?php } ?>

<?php 

if (permission("xml Feeds", "write") == true)

    { 

echo "<p style='text-align: right;height:35px'><a id='addpro' style='cursor:pointer;' class='txtLink' onclick='Extractproducts(); '><img src='".$glob['adminFolder']."/images/buttons/new.gif' border='0' />&nbsp;Update Products Cost Price</a> <img id='imgProgress' src='admin/images/imgProgress.gif' border='0' style='display:none;'  /></p>";



	}

 ?>

 <form method="post" name="frmExtract" id="frmExtract" action="">



  <input type="hidden" id="action" name="action" value="getproducts" />



</form>

<!--<p class="pagination" style="text-align: right;"><?php echo $pagination; ?></p>-->

<form method="post" id="moveProducts" enctype="multipart/form-data">

  <table width="100%"  border="1" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" class="mainTable mainTable4">

    <tr>

      <td align="center" nowrap="nowrap"  width="38" class="tdTitle">&nbsp;</td>

      <td align="center" nowrap="nowrap" width="153" class="tdTitle"><?php echo $lang['admin']['products_name'];?></td>

     <!-- <td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['products_id'];?></td>-->

      <td align="center" nowrap="nowrap" width="38" class="tdTitle"><?php echo $lang['admin']['products_type'];?></td>

      <td align="center"  width="74" class="tdTitle"><?php echo $lang['admin']['products_prod_code'];?></td>

      

      <td align="center" nowrap="nowrap" width="190" class="tdTitle"><?php echo $lang['admin']['products_master_cat2'];?></td>

      <td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['products_image'];?></td>

      <td align="center" nowrap="nowrap" width="88" class="tdTitle"><?php echo $lang['admin']['products_price_sale_price'];?></td>

      <td align="center" nowrap="nowrap"  width="88" class="tdTitle"><?php echo $lang['admin']['products_in_stock'];?></td>

      <td width="15%" colspan="4" align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['products_action'];?></td>

    </tr>

    <?php 

  if ($results == true) {

  	

	for ($i=0; $i<count($results); $i++){ 

  	

	$cellColor = "";

	$cellColor = cellColor($i);

  ?>

    <tr>

      <td align="center" class="<?php echo $cellColor; ?>"><input type="checkbox" name="product[]" class="productCheckbox" id="product_<?php echo $results[$i]['productId']; ?>" value="<?php echo $results[$i]['productId']; ?>" />

        <input type="hidden" name="current[<?php echo $results[$i]['productId']; ?>]" value="<?php echo $results[$i]['cat_id']; ?>" /></td>

      <td align="left" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['name']; ?></span></td>

      <!--<td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['productId']; ?></span></td>-->

      <td align="center" class="<?php echo $cellColor; ?>"><img src="<?php echo $glob['adminFolder']; ?>/images/productIcon<?php echo $results[$i]['digital'];?>.gif" alt="" width="16" height="16" title="" /></td>

      <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['productCode']; ?></span></td>

     

      <td class="<?php echo $cellColor; ?>"><span class="txtDir"><?php echo getCatDir($results[$i]['cat_name'], $results[$i]['cat_father_id'], $results[$i]['cat_id']);?></span><br />

        <a href="javascript:;" <?php if (permission("products","edit")==TRUE){ ?>onclick="openPopUp('<?php echo $glob['adminFile'];?>?_g=products/extraCats&amp;productId=<?php echo $results[$i]['productId']; ?>&amp;cat_id=<?php echo $results[$i]['cat_id']; ?>&amp;cat_father_id=<?php echo $results[$i]['cat_father_id']; ?>&amp;cat_name=<?php echo urlencode(html_entity_decode(stripslashes($results[$i]['cat_name']))); ?>&amp;name=<?php echo urlencode($results[$i]['name']); ?>','extraCats',500,450,1);" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['products_manage_cats'];?></a></td>

      <td align="center" valign="middle"  class="<?php echo $cellColor; ?>"><?php

	$thumbPathRoot = imgPath($results[$i]['image'],$thumb=1,$path="root");

	$thumbPathRel = imgPath($results[$i]['image'],$thumb=1,$path="rel");

	

	$masterPathRoot = imgPath($results[$i]['image'],$thumb=0,$path="root");

	$masterPathRel = imgPath($results[$i]['image'],$thumb=0,$path="rel");

	

	if (file_exists($thumbPath) && !empty($results[$i]['image'])) {

		$imgSize = getimagesize($thumbPath);

		$imgFile = $thumbPathRel; 

	} else if (file_exists($masterPathRoot) && !empty($results[$i]['image'])) {

		$imgSize = getimagesize($masterPathRoot); 

		$imgFile = $masterPathRel;

	}

		

	if (isset($imgFile) && !empty($imgFile)) { 

	?>

          <img src="<?php echo $imgFile; ?>" alt="<?php echo $results[$i]['name']; ?>" title="" height="50" /><br />

        <!-- place of div highlighted-->

          <?php

		if (permission("products","edit") == true) {

			$link = 'javascript:openPopUp(\'?_g=products/extraImgs&amp;productId='.$results[$i]['productId'].'&amp;img='.urlencode($results[$i]['image']).'\',\'extraImgs\',550,450,1);" class="txtLink"';

		} else {

			$link = $link401;

		}

		echo sprintf('<a href="%s">%s</a>', $link, $lang['admin']['products_manage_images']);

		/*

		<a href="#" <?php if(permission("products","edit")==TRUE){ ?>onclick="openPopUp('<?php echo $glob['adminFile'];?>?_g=products/extraImgs&amp;productId=<?php echo $results[$i]['productId']; ?>&amp;img=<?php echo urlencode($results[$i]['image']); ?>','extraImgs',550,450,1);" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['products_manage_images'];?></a></div>

		<?php

		*/ 

		unset($imgFile);

	} else {

		echo "&nbsp;";

	}

	?></td>

      <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo priceFormat($results[$i]['price'], true); ?></span>

        <?php 

	$salePrice = salePrice($results[$i]['price'], $results[$i]['sale_price']);

	if ($salePrice) { ?>

        <br />

        <span class="txtRed">

        <?php

	echo priceFormat($salePrice,true);

	?>

        </span>

        

        <?php } 

	if ($results[$i]['costprice'] > 0) { ?>

        <br />

        <span class="txtRed" style="color:#0F0">

        <?php

	echo priceFormat($results[$i]['costprice']);

	?>

        </span>

        

        <?php } ?></td>

      <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText">

        <?php if($results[$i]['useStockLevel']==1) { echo $results[$i]['stock_level']; } else { echo "n/a"; }?>

        </span></td>

      <td align="center"  colspan="3" class="<?php echo $cellColor; ?> a2"><a <?php if(permission("products","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/index&amp;edit=<?php echo $results[$i]['productId']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>

      

      <!--<a <?php if(permission("products","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/index&amp;delete=<?php echo $results[$i]['productId']; ?>&amp;cat_id=<?php echo $results[$i]['cat_id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q']));?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a>

      

      

      <a <?php if(permission("products","edit")==TRUE){ ?>href="?_g=products/index&amp;clone=<?php echo $results[$i]['productId']; ?>" class="txtLink"<?php } else { echo $link401; } ?>>Clone</a>-->
<a <?php if(permission("products","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/languages&amp;prod_master_id=<?php echo $results[$i]['productId']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin']['products_languages'];?></a>
      </td>

    

    

      

    </tr>

    <?php } // end loop ?>

    <tr>

      <td><img src="<?php echo $glob['adminFolder'];?>/images/selectAll.gif" alt="" width="16" height="11" /></td>

      <td colspan="9" class="tdText"><a href="#" class="txtLink" onclick="return checkUncheck('moveProducts', 'productCheckbox');"><?php echo $lang['admin']['products_check_uncheck_all'];?></a> &nbsp;

        <?php 

	 if(permission("products","edit")==true) {

	 ?>

        <select name="MoveTo" class="textbox2">

          <option value="" selected="selected"><?php echo $lang['admin']['products_move_selected_to'];?></option>

          <?php echo showCatList_digital($results[0]['cat_id']); ?>

        </select>

        <input type="submit" name="EditSelected" class="submit" value="<?php echo $lang['admin']['products_go'];?>" />

        <?php

	 }

	 if(permission("products","delete")==true) {

	 ?>

        <input type="submit" name="DeleteSelected" class="submit" value="Delete Selected" />

        <?php

	 }

	 ?></td>

    </tr>

    <?php

  } else { ?>

    <tr>

      <td colspan="10" class="tdText"><?php echo $lang['admin']['products_no_products_exist'];?></td>

    </tr>

    <?php } ?>

  </table>

</form>

<p class="pagination" style="text-align: right;"><?php echo $pagination; ?></p>

<?php

if(isset($msg2))

{ 

	echo msg($msg2); 

}

?>

<!--<form id="update_prices" name="update_prices" method="post" action="<?php echo $glob['adminFile']; ?>?_g=products/index">

  <table border="0" cellspacing="1" cellpadding="3" class="mainTable">

    <tr>

      <td colspan="3" class="tdTitle"><?php echo $lang['admin']['products_update_prices_by_cat']; ?></td>

    </tr>

    <tr>

      <td align="left" class="tdText" width="100"><strong><?php echo $lang['admin']['products_normal_price2']; ?></strong></td>

      <td colspan="2" align="left" class="tdText"><strong>

        <input name="normPer" type="text" size="5" maxlength="5" />

        <select name="normPerMethod">

          <option value="percent"><?php echo $lang['admin']['products_val_percent']; ?></option>

          <option value="value"><?php echo $lang['admin']['products_val_amount']; ?></option>

          <option value="actual"><?php echo $lang['admin']['products_val_actual']; ?></option>

        </select>

        <?php echo $lang['admin']['products_sale_price2']; ?></strong>

        <input name="salePer" type="text" size="5" maxlength="5" />

        <select name="salePerMethod">

          <option value="percent"><?php echo $lang['admin']['products_val_percent']; ?></option>

          <option value="value"><?php echo $lang['admin']['products_val_amount']; ?></option>

          <option value="actual"><?php echo $lang['admin']['products_val_actual']; ?></option>

        </select></td>

    </tr>

    <tr>

      <td align="left" valign="top" class="tdText" width="100"><strong><?php echo $lang['admin']['products_category']; ?></strong><br />

        <?php echo $lang['admin']['products_hold_ctrl']; ?></td>

      <td align="left" valign="top" class="tdText"><select name="cat_id_price[]" size="5" multiple="multiple" class="textbox">

          <option value="0">-- <?php echo $lang['admin_common']['all']; ?> --</option>

          <?php echo showCatList_digital($results[0]['cat_id']); ?>

        </select></td>

      <td align="right" valign="bottom" class="tdText"><input type="submit" name="submit_prices" value="<?php echo $lang['admin']['products_update_prices']; ?>" <?php if(permission("products","edit")==false){ echo "disabled='disabled' class='submitDisabled'"; } else { echo "class='submit'"; } ?> /></td>

    </tr>

    <tr>

      <td colspan="3" align="left" class="tdText"><?php echo $lang['admin']['products_eg_1']; ?><br />

        <?php echo $lang['admin']['products_eg_2']; ?></td>

    </tr>

  </table>

</form>-->

<?php 

} else if ($_GET["mode"] == "new" || $_GET["edit"]>0 || $_GET["clone"]>0) {

if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 

if (isset($_GET["clone"]) && $_GET["clone"]>0) {unset($results[0]['productCode'], $results[0]['productId']);}

?>

<p class="copyText"><?php echo $lang['admin']['products_add_prod_desc'];?></p>

<div class="headingBlackbg"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt; } echo " ".$lang['admin']['products_product'];?></div>

<form action="<?php echo $glob['adminFile']; ?>?_g=products/index" method="post" enctype="multipart/form-data" name="form1" language="javascript">

  <table border="0" cellspacing="1" cellpadding="3" class="mainTable" style="color:#464646">

    <tr>

      <td width="123"  align="right"><strong><?php echo $lang['admin']['products_prod_name2'];?></strong></td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

      <input name="name" type="text"  value="<?php if(isset($results[0]['name'])) echo validHTML($results[0]['name']); ?>" maxlength="255" /><span class="bgright"></span></div></td>

    </tr>

     <tr>

      <td  align="right" ><strong><?php echo $lang['admin']['products_category'];?></strong></td>

      <td >

      <div class="inputbox"><span class="bgleft"></span>

      <select name="cat_id" >

          <?php echo showCatList_digital($results[0]['cat_id']); ?>

        </select><span class="bgright"></span></div>

      </td>

    </tr>

    <tr>

      <td  align="right"><strong>Delivery Time:</strong></td>

      <td>

      	<div class="inputbox"><span class="bgleft"></span>

       <input name="deltime" type="text" class="textbox" value="<?php if(isset($results[0]['deltime'])) echo ($results[0]['deltime']); ?>" maxlength="255" /><span class="bgright"></span></div>

     </td>

    </tr>

    <tr>

      <td  align="right"   ><strong>Message:</strong></td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

     <input name="sdesc" type="text" class="textbox" value="<?php if(isset($results[0]['sdesc'])) echo ($results[0]['sdesc']); ?>" maxlength="255" /><span class="bgright"></span></div>

      </td>

    </tr>

    <tr>

      <td  align="right"  ><strong><?php echo $lang['admin']['products_disable'];?></strong></td>

      <td>

      <div class="inputbox" style="width:186px"><span class="bgleft"></span>

      <select name="disabled"  style="width:177px" >

          <option value="1" <?php if (isset($results[0]['disabled']) && $results[0]['disabled']==1) echo 'selected="selected"'; ?>>Yes</option>

          <option value="0" <?php if (!isset($results[0]['disabled']) || $results[0]['disabled']==0) echo 'selected="selected"'; ?>>No</option>

        </select><span class="bgright"></span></div>

     </td>

    </tr>

    <tr>

      <td  align="right"  ><strong><?php echo 'Multiple Imei';?></strong></td>

      <td>

      <div class="inputbox" style="width:186px"><span class="bgleft"></span>

      <select name="mulimei" style="width:177px">



        <option value="1" <?php if (isset($results[0]['mulimei']) && $results[0]['mulimei']==1) echo 'selected="selected"'; ?>><?php echo $lang['admin_common']['yes'];?></option>



        <option value="0" <?php if (!isset($results[0]['mulimei']) || $results[0]['mulimei']==0) echo 'selected="selected"'; ?>><?php echo $lang['admin_common']['no'];?></option>



      </select><span class="bgright"></span></div>

     </td>

    </tr>

    <!--<tr>

      <td  align="right"  ><strong><?php echo $lang['admin']['products_prod_stock_no'];?></strong> 

       </td>

      <td >

      <div class="inputbox"><span class="bgleft"></span>

       <input name="productCode" type="text" class="textbox" value="<?php if(isset($results[0]['productCode'])) echo $results[0]['productCode']; ?>" maxlength="255" /><span class="bgright"></span></div>

       <span class="left sm" > <?php echo $lang['admin']['products_auto_generated'];?></span>

     </td>

    </tr>-->

        <tr>

      <td  align="right"  ><strong>Supported Handsets:</strong> 

        </td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

      <input name="short_desc" type="text" class="textbox" value="<?php if(isset($results[0]['short_desc'])) echo $results[0]['short_desc']; ?>" maxlength="255" /><span class="bgright"></span></div>

 

      </td>

    </tr>

    <tr>

      <td  align="right"  ><strong>Product Title:</strong> 

        </td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

      <input name="short_title" type="text" class="textbox" value="<?php if(isset($results[0]['short_title'])) echo $results[0]['short_title']; ?>" maxlength="255" /><span class="bgright"></span></div>

      <span class="left sm"><?php echo "if leave blank default text displayed as 'Unlocking Your Apple iPhone'";?></span>

      </td>

    </tr>
 <tr>

    <td width="123"  align="right"><strong><?php echo 'Api Status';?></strong></td>

    <td>

	  <select name="api_status" class="textbox">
			 <option value="2" <?php if (isset($results[0]['api_status']) && $results[0]['api_status']==2) echo 'selected="selected"'; ?> onclick="clearapidata();">ImeiUnlock</option>
        <option value="1" <?php if (isset($results[0]['api_status']) && $results[0]['api_status']==1) echo 'selected="selected"'; ?> onclick="loadapidata();">Dhru</option>

        <option value="0" <?php if (!isset($results[0]['api_status']) || $results[0]['api_status']==0) echo 'selected="selected"'; ?> onclick="clearapidata();">Disable</option>

      </select>

    </td>

  </tr>
<tr>
<td colspan="2" id="apidata" <?php if($results[0]['api_status']!=1) echo "style='display:none'" ?>>
<table width="100%">
  <script>
    document.write(unescape('%3Cscript type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/jquery-1.8.3.min.js"%3E%3C/script%3E'));
</script>
<script>
    jQuery.noConflict();
    var $j = jQuery;
</script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/dhruapi.js"></script>
 <tr>
    <td width="123"  align="right"><strong><?php echo 'Dhru Vendor';?></strong></td>

    <td>

	  <select name="vendor" class="textbox" onchange="updateproductdata(this.value, '<?php echo $GLOBALS['storeURL']; ?>');" id="vendorss">
      <option> Select vender</option>
<?php 
$vendors = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders ORDER BY id ASC");
if($vendors){
	for($v=0;$v<count($vendors);$v++){
?>
        <option value="<?php echo $vendors[$v]['id']; ?>" <?php if ($results[0]['vendor'] == $vendors[$v]['id']) echo 'selected="selected"'; ?>><?php echo $vendors[$v]['vender_title'];?></option>
<?php } 
}?>
      </select>

    </td>

  </tr>
  <tr>
    <td width="123" align="right"><strong><?php echo 'Map Product';?></strong></td>

    <td id="mapload">

	 <?php
if($results[0]['vendor'] > 0){
$catquery		= "SELECT * FROM ".$glob['dbprefix']."dhru_cat".$results[0]['vendor']."  ORDER BY catname ASC";
$catResult = $db->select($catquery); 

if(!empty($catResult)){
?>
<select name="mapid" onchange="loadbrands(this.value,'<?php echo $GLOBALS['storeURL']; ?>');">
<option>Select product for Map</option>
<?php
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
?>
<optgroup style="font-size:13px; font-weight:bold;" label="<?php echo $catResult[$j]['catname']; ?>">
<?php
		$queryall = "SELECT SERVICENAME,SERVICEID  FROM ".$glob['dbprefix']."dhru_products".$results[0]['vendor']." WHERE cat_id = ".$catResult[$j]['catid'];
		$ResultAll= $db->select($queryall);
		if(!empty($ResultAll)){
			for($z=0;$z<count($ResultAll);$z++){
		?>
		<option value="<?php echo $ResultAll[$z]['SERVICEID']; ?>" <?php if ($ResultAll[$z]['SERVICEID']== $results[0]['mapid']) echo 'selected="selected"'; ?>><?php echo $ResultAll[$z]['SERVICENAME']; ?></option>
<?php			
			
			
			}
			?>
			</optgroup> <?php 
	}
	}
	?>
	</select>
    <?php
}
}
?>
    </td>

  </tr>

  <tr>
    <td width="123"  align="right"><strong><?php echo 'Map model';?></strong></td>

    <td id="mapmodel">
    <select name="model">
    <option value="">Select Model</option>
    <option value="<?php echo $results[0]['model']; ?>" selected="selected"><?php echo $results[0]['model']; ?></option>
    </select>
    </td>

  </tr>
  
  <tr>
    <td width="123"  align="right"><strong><?php echo 'Map provider';?></strong></td>

    <td id="mapprovider">
    <select name="provider">
    <option value="">Select Provider</option>
    <option value="<?php echo $results[0]['provider']; ?>" selected="selected"><?php echo $results[0]['provider']; ?></option>
    </select>
    </td>

  </tr>
  </table>
  </td>
  </tr>
    <!--<tr>

      <td  align="right"  ><strong><?php echo $lang['admin']['products_ean_upc'];?></strong></td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

      <input name="eanupcCode" type="text" class="textbox" value="<?php if(isset($results[0]['eanupcCode'])) echo $results[0]['eanupcCode']; ?>" maxlength="17" /><span class="bgright"></span></div>

      </td>

    </tr>-->

    <tr>

      <td ><span class="tdText"><strong><?php echo $lang['admin']['products_description'];?></strong> <?php echo $lang['admin']['products_primary_lang'];?></span></td>

      <td></td>

    </tr>

    <tr>

      <td  align="right"  colspan="2" ><?php

		require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");

		

		$oFCKeditor				= new FCKeditor('FCKeditor');

		$oFCKeditor->BasePath	= $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/' ;

		$oFCKeditor->Value		= (isset($results[0]['description'])) ? $results[0]['description'] : '';

		

		if (!$config['richTextEditor']) $oFCKeditor->off = true;

		$oFCKeditor->Create();

?></td>

    </tr>

   

    <tr>

      <td  align="right"><strong><?php echo $lang['admin']['products_image2'];?></strong> 

        </td>

      <td    valign="top" style="display:none;"><?php

	//$imgSrc = (!empty($results[0]['image'])) ? imgPath($results[0]['image'], 'thumb', 'rel') : $glob['rootRel'].'images/general/px.gif';

	?>

        <!--<img src="<?php echo $imgSrc; ?>" alt="" id="previewImage" title="" />-->

        <div>

          <input name="upload" class="submit" type="button" id="upload" onclick="openPopUp('<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/includes/rte/editor/filemanager/browser/default/browser.html?Type=uploads&Connector=<?php echo urlencode($GLOBALS['rootRel'].$glob['adminFolder']); ?>%2Fincludes%2Frte%2Feditor%2Ffilemanager%2Fconnectors%2Fphp%2Fconnector.php','filemanager',700,600)" value="<?php echo $lang['admin']['products_browse_upload_image'];?>" />

          <input type="button" class="submit" value="<?php echo "Remove Image";?>" onclick="findObj('previewImage').src='<?php echo $glob['rootRel']; ?>/images/general/px.gif';findObj('imageName').value = '';" />

          <input type="hidden" name="imageName" id="imageName" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />

        </div></td>

      <td>

      <div class="inputbox" style="background:none; width:200px">

       <input type="file" name="primaryImage" class="textbox" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />

     

        <input type="hidden" name="oldimage" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />		</div>

        <span class="left sm"><?php echo $lang['admin']['products_opt_and_thumbs'];?></span>

        <?php 

	if(!empty($results[0]['image'])) { 

		$imgSrc = imgPath($results[0]['image'],'thumb',$path="rel");

	} else {

		$imgSrc = $GLOBALS['storeURL']."/images/general/nophoto.gif";

	}

	?>

       

        <img src="<?php echo $imgSrc; ?>" alt="" id="previewImage" title="" /></td>

    </tr>

    <tr>

      <td  align="right"  ><strong><?php echo $lang['admin']['products_image2'];?></strong> 

       </td>

      <td valign="top" style="display:none;"><?php

	$imgSrc = (!empty($results[0]['image2'])) ? imgPath($results[0]['image2'], 'thumb', 'rel') : $glob['rootRel'].'images/general/px.gif';

	?>

        <img src="<?php echo $imgSrc; ?>" alt="" id="previewImage" title="" />

        <div>

          <input name="upload" class="submit" type="button" id="upload" onclick="openPopUp('<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/includes/rte/editor/filemanager/browser/default/browser.html?Type=uploads&Connector=<?php echo urlencode($GLOBALS['rootRel'].$glob['adminFolder']); ?>%2Fincludes%2Frte%2Feditor%2Ffilemanager%2Fconnectors%2Fphp%2Fconnector.php','filemanager',700,600)" value="<?php echo $lang['admin']['products_browse_upload_image'];?>" />

          <input type="button" class="submit" value="<?php echo "Remove Image";?>" onclick="findObj('previewImage').src='<?php echo $glob['rootRel']; ?>/images/general/px.gif';findObj('imageName').value = '';" />

          <input type="hidden" name="imageName2" id="imageName2" value="<?php if(isset($results[0]['image2'])) echo $results[0]['image2']; ?>" />

        </div></td>

      <td>

      <div class="inputbox" style="background:none; width:200px">

      <input type="file" name="primaryImage2" class="textbox" value="<?php if(isset($results[0]['image2'])) echo $results[0]['image2']; ?>" />

        <input type="hidden" name="oldimage2" value="<?php if(isset($results[0]['image2'])) echo $results[0]['image2']; ?>" />  </div>  <span class="left sm"><?php echo $lang['admin']['products_opt_and_thumbs'];?></span>

        <?php 

	if(!empty($results[0]['image2'])) { 

		$imgSrcc = imgPath($results[0]['image2'],'thumb',$path="rel");

	} else {

		$imgSrcc = $GLOBALS['storeURL']."/images/general/iphone.jpg";

	}

	?>

        

        <img src="<?php echo $imgSrcc; ?>" alt="" id="previewImage" title="" /></td>

    </tr>

    <?php

	// Flexible Taxes, by Estelle Winterflood

	$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");

	

	if(!isset($_REQUEST['clone']) && $_REQUEST['clone'] == 0 && $results[0]['parentId'] == 0)

	{

?>

    <tr>

      <td  align="right"    ><strong><?php echo $lang['admin']['products_normal_price2']; if ($config_tax_mod['status']) echo $lang['admin']['settings_excl_tax']; ?> </strong></td>

      <td>

      	<div class="inputbox"><span class="bgleft"></span>

      <input name="price" value="<?php if(isset($results[0]['price'])) echo $results[0]['price']; ?>" type="text" class="textbox" size="10" />

      <span class="bgright"></span> </div>

      <div class="inputbox" style="background:none; padding-left:20px; width:340px;">

      <strong>Cost Price</strong>&nbsp;&nbsp;&nbsp;<span style="color:#F00" ><?php if(isset($results[0]['costprice'])) echo $results[0]['costprice']; ?></span><br />

      <?php if($results[0]['costprice'] == 0) echo "This Product is Currently not Available on Imei-Unlock.net"; ?>

      </div>

       

      </td>

    </tr>

    <tr>

      <td  align="right"  ><strong><?php echo $lang['admin']['products_sale_price2']; if ($config_tax_mod['status']) echo $lang['admin']['settings_excl_tax']; ?> </strong><br />

        </td>

      <td>

      <div class="inputbox"><span class="bgleft"></span>

      <input name="sale_price" value="<?php if(isset($results[0]['sale_price'])) echo $results[0]['sale_price']; ?>" type="text" class="textbox" size="10" />

      <span class="bgright"></span>

      </div>

    <!--  <span class="left sm"> <?php echo $lang['admin']['products_sale_mode_desc'];?></span>-->

      </td>

    </tr>

    <!--<tr>

    <td class="tdText"><strong>Discount Price on Customer Type:</strong></td>

    <td class="tdText">

	<table width="643" height="52" border="1" cellpadding="5">

  <tr>

    <td class="tdText"><strong>Wholeseller</strong></td>

    <td class="tdText"><strong>LjTronics</strong></td>

    <td class="tdText"><strong>David Zheng</strong></td>

    <td class="tdText"><strong>Natt</strong></td>

    <td class="tdText"><strong>Stephannie </strong></td>

    <tr>

    <td><input name="Wholeseller" value="<?php if(isset($results[0]['Wholeseller'])) echo $results[0]['Wholeseller']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="LjTronics" value="<?php if(isset($results[0]['LjTronics'])) echo $results[0]['LjTronics']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="David_Zheng" value="<?php if(isset($results[0]['David_Zheng'])) echo $results[0]['David_Zheng']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="Natt" value="<?php if(isset($results[0]['Natt'])) echo $results[0]['Natt']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="Stephannie" value="<?php if(isset($results[0]['Stephannie'])) echo $results[0]['Stephannie']; ?>" type="text" class="textbox" size="10" /></td>

    </tr>

    </tr>

    <tr>

    <td class="tdText"><strong>themobilephoneclinic</strong></td>

    <td class="tdText"><strong>asghar cellone</strong></td>

    <td class="tdText"><strong>Anam</strong></td>

     <td class="tdText"><strong>Sara</strong></td>

     <td class="tdText"><strong>Canida 1</strong></td>

  </tr>

  

    <tr>

    

    <td><input name="themobilephoneclinic" value="<?php if(isset($results[0]['themobilephoneclinic'])) echo $results[0]['themobilephoneclinic']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="asghar_cellone" value="<?php if(isset($results[0]['asghar_cellone'])) echo $results[0]['asghar_cellone']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="Anam" value="<?php if(isset($results[0]['Anam'])) echo $results[0]['Anam']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="Sara" value="<?php if(isset($results[0]['Sara'])) echo $results[0]['Sara']; ?>" type="text" class="textbox" size="10" /></td>

     <td><input name="canada_1" value="<?php if(isset($results[0]['canada_1'])) echo $results[0]['canada_1']; ?>" type="text" class="textbox" size="10" /></td>

  </tr>

  <tr>

    <td class="tdText"><strong>Canida 2</strong></td>

    <td class="tdText"><strong>Uk 1</strong></td>

    <td class="tdText"><strong>Uk 2</strong></td>

    <td class="tdText"><strong>fonefun</strong></td>

    <td class="tdText"><strong>cellsanity</strong></td>

    

  </tr>

  

    <tr>

    

    <td><input name="canada_2" value="<?php if(isset($results[0]['canada_2'])) echo $results[0]['canada_2']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="uk_1" value="<?php if(isset($results[0]['uk_1'])) echo $results[0]['uk_1']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="uk_2" value="<?php if(isset($results[0]['uk_2'])) echo $results[0]['uk_2']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="fonefun" value="<?php if(isset($results[0]['fonefun'])) echo $results[0]['fonefun']; ?>" type="text" class="textbox" size="10" /></td>

    <td><input name="cellsanity" value="<?php if(isset($results[0]['cellsanity'])) echo $results[0]['cellsanity']; ?>" type="text" class="textbox" size="10" /></td>

    

  </tr>

  <tr>

    <td class="tdText"><strong>usman mobiles</strong></td>

    

  </tr>

  <tr>

    

    <td><input name="usman_mobiles" value="<?php if(isset($results[0]['usman_mobiles'])) echo $results[0]['usman_mobiles']; ?>" type="text" class="textbox" size="10" /></td>

    

  </tr>

</table>

	</td>

  </tr>-->

    <tr>

      <td  colspan="2" ><strong>Wholesale Group:</strong></td>

    </tr>

    <tr>

      <td class="tdText" colspan="2"><div class="W-group">

          <?= getwholesalegroup($results[0]['productId'])?>

        </div></td>

    </tr>

    <?php } ?>

   <!-- <tr>

      <td><strong><?php echo $lang['admin']['products_tax_inclusive'];?></strong></td>

      <td>

      <input name="tax_inclusive" value="1" type="checkbox" <?php if ($results[0]['tax_inclusive']) echo 'checked="checked" '; ?>/></td>

    </tr>

    <tr>

      <td class="tdText"><strong><?php echo $lang['admin']['products_tax_class'];?></strong></td>

      <td class="tdText">

      <div class="inputbox"><span class="bgleft"></span>

      <select name="taxType">

          <option value="0">Please Select</option>

          <?php

	$taxTypes = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes"); 

	 for($i=0; $i<count($taxTypes);$i++){ ?>

          <option value="<?php echo $taxTypes[$i]['id']; ?>" <?php if(isset($results[0]['taxType']) && $taxTypes[$i]['id'] == $results[0]['taxType']) echo "selected='selected'"; ?>><?php echo $taxTypes[$i]['taxName'];  if (! $config_tax_mod['status']) echo "(".$taxTypes[$i]['percent']."%)"; ?></option>

          <?php } ?>

        </select>

        <span class="bgright"></span></div>

        </td>

    </tr>

    <tr>

      <td class="tdText"><strong><?php echo $lang['admin']['products_prod_weight'];?></strong></td>

      <td class="tdText">

      <div class="inputbox"><span class="bgleft"></span>

      <input name="prodWeight" type="text" class="textbox" size="10" value="<?php if(isset($results[0]['prodWeight'])) echo $results[0]['prodWeight']; ?>" />

      <span class="bgright"></span></div>

      <span class="sm left">

        <?php echo $config['weightUnit']; ?></span></td>

    </tr>-->

    <tr>

      <td class="tdText"><strong><?php echo $lang['admin']['products_stock_level2'];?>

        </strong></td>

      <td class="tdText">

      <div class="inputbox"><span class="bgleft"></span>

      <input name="stock_level" value="<?php if(isset($results[0]['stock_level'])) echo $results[0]['stock_level']; ?>" type="text" class="textbox" size="10" />

      <span class="bgright"></span></div>

	  <span class="sm left"><?php echo $lang['admin']['products_reduce_stock_level'];?></span></td>

    </tr>

    <?php

  if ($config['stock_warn_type']) {

  ?>

    <tr>

      <td class="tdText"><strong><?php echo $lang['admin']['products_stock_level_warn'];?><br />

        </strong></td>

      <td class="tdText"><input name="stockWarn" value="<?php if(isset($results[0]['stockWarn'])) echo $results[0]['stockWarn']; ?>" type="text" class="textbox" size="10" /></td>

    </tr>

    <?php

  }

  ?>

    <tr>

      <td class="tdText"><strong><?php echo $lang['admin']['products_use_stock_q'];?></strong></td>

      <td class="tdText" height="37"><?php echo $lang['admin_common']['yes'];?>

        <input name="useStockLevel" type="radio" value="1" <?php if(isset($results[0]['useStockLevel']) && $results[0]['useStockLevel'] || !isset($results[0]['useStockLevel'])) echo "checked='checked'"; ?> />

        <?php echo $lang['admin_common']['no'];?>

        <input name="useStockLevel" type="radio" value="0" <?php if(isset($results[0]['useStockLevel']) && !$results[0]['useStockLevel']) echo "checked='checked'"; ?> /></td>

    </tr>

    <tr>

      <td class="tdText" ><strong><?php echo $lang['admin']['products_product_type'];?></strong></td>

      <td class="tdText" height="40"><?php echo $lang['admin']['products_tangible'];?><span class="tdText">

        <input name="digital" onclick="digitalDir.className='hiddenTextbox';digitalDir.value=''" type="radio" value="0" <?php if(isset($results[0]['digital']) && !$results[0]['digital'] || !isset($results[0]['digital'])) echo "checked='checked'"; ?> />

        </span> <?php echo $lang['admin']['products_digital'];?><span class="tdText">

        <input name="digital" type="radio" value="1" <?php if(isset($results[0]['digital']) && $results[0]['digital']) echo "checked='checked'"; ?> />

       <!-- <input name="digitalDir" type="text" id="digitalDir" value="<?php if(isset($results[0]['digitalDir'])) echo $results[0]['digitalDir']; ?>" maxlength="255" <?php if(isset($results[0]['digitalDir']) && $results[0]['digital']==1) { echo "class='dirTextbox'"; } else { ?>class="hiddenTextbox" <?php } ?> />-->

        <br />

       </span></td>

    </tr>

  <!--  <tr>

      <td class="tdText" colspan="2"><strong class="left"  style="width: 286px; margin-top: 10px;"><?php echo $lang['admin']['products_show_featured'];?></strong>

      <div class="inputbox inputbox2"><span class="bgleft"></span>

      <select name="showFeatured" class="textbox">

          <option value="1" <?php if(isset($results[0]['showFeatured']) && $results[0]['showFeatured']==1) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['yes'];?></option>

          <option value="0" <?php if(isset($results[0]['showFeatured']) && $results[0]['showFeatured']==0) echo "selected='selected'"; ?>><?php echo $lang['admin_common']['no'];?></option>

        </select>

        <span class="bgright"></span></div>

        </td>

    </tr>-->

    <tr>

    	<td colspan="2">

        <div class="maindiv seprator"></div>

        </td>

    </tr>

    <tr>

      <td >&nbsp;</td>

      <td><input type="hidden" name="oldCatId" value="<?php if(isset($results[0]['cat_id'])) echo $results[0]['cat_id']; ?>" />

        <input type="hidden" name="productId" value="<?php if(isset($results[0]['productId'])) echo $results[0]['productId']; ?>" />

        <input name="Submit"  style="margin-bottom:6px" type="submit" class="submit" value="<?php echo $modeTxt." ".$lang['admin']['products_product'];?>" /></td>

    </tr>

  </table>

  <?php

$optquery		= sprintf('SELECT * FROM %sImeiUnlock_options_top WHERE 1', $glob['dbprefix']);

$options_result = $db->select($optquery);

if ($options_result) {

?>

  <div class="headingBlackbg" style="margin-top:10px;">Product Options</div>

  <table class="mainTable" cellspacing="1" cellpadding="3" border="0" style="color:#464646" width="100%">

    

    <tr>

      <td width="123" class="tdText">&nbsp;</td>

      <td align="left"><div id="options_added" >

          <?php

		## Product Options (Current - Select)

		if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {

			$optionsc_query		= sprintf("SELECT T.option_name, M.value_name, B.* FROM %1\$sImeiUnlock_options_top AS T, %1\$sImeiUnlock_options_mid AS M, %1\$sImeiUnlock_options_bot AS B WHERE B.option_id = T.option_id AND B.value_id = M.value_id AND B.product = %2\$s AND T.option_type = '0' GROUP BY B.value_id ORDER BY T.option_name, M.value_name ASC", $glob['dbprefix'], $results[0]['productId']);

			$optionsc_result	= $db->select($optionsc_query);

			if ($optionsc_result) {

				foreach ($optionsc_result as $option) {

					$option['option_data'] = sprintf('%d|%d|%s', $option['option_id'], $option['value_id'], $option['option_price']);

					

					?>

          <div id="option_<?php echo $option['assign_id']; ?>" style="clear: right;"> <span style="float: right; text-align: right;"> <a href="#" onclick="optionEdit('<?php echo $option['assign_id']; ?>', '<?php echo $option['option_data']; ?>'); return false;"><img src="images/icons/edit.png" alt="edit" /></a> <a href="#" onclick="optionRemove('<?php echo $option['assign_id']; ?>'); return false;"><img src="images/icons/delete.png" alt="delete" /></a> </span> <strong><?php echo $option['option_name']; ?></strong>: <?php echo $option['value_name']; ?>

            <?php if($option['option_price'] > 0) {  ?>

            (<?php echo $option['option_symbol'].$option['option_price']; ?>)

            <?php

				  }

				  ?>

          </div>

          <?php

				}

			}

		}

		## Product Options (Current - Text)

		$optionsct_query	= sprintf("SELECT B.*, T.* FROM %1\$sImeiUnlock_options_bot AS B, %1\$sImeiUnlock_options_top AS T WHERE B.option_id = T.option_id AND B.product = '%2\$d' AND T.option_type != '0' ORDER BY T.option_name ASC", $glob['dbprefix'], $results[0]['productId']);		

		$optionsct_result	= $db->select($optionsct_query);

		

		if ($optionsct_result) {

			foreach ($optionsct_result as $key => $option) {

				$option['option_data'] = sprintf('%d|%d|%s', $option['option_id'], 0, $option['option_price']);

				?>

          <div id="option_<?php echo $option['assign_id']; ?>" style="clear: right;"> <span style="float: right; text-align: right;"> <a href="#" onclick="optionEdit('<?php echo $option['assign_id']; ?>', '<?php echo $option['option_data']; ?>'); return false;"><img src="images/icons/edit.png" alt="edit" /></a> <a href="#" onclick="optionRemove('<?php echo $option['assign_id']; ?>'); return false;"><img src="images/icons/delete.png" alt="delete" /></a> </span> <strong>Custom</strong>: <?php echo $option['option_name']; ?> (<?php echo $option['option_symbol'].$option['option_price']; ?>) </div>

          <?php

			}

		}

		

		?>

        </div></td>

    </tr>

   

    <tr>

    	<td width="123"><strong>Select Option:</strong></td>

      <td >

      <div class="inputbox inputbox2">

    	  <span class="bgleft"></span>

      <select id="opt_mid" class="textbox">

          <option value="">Select Option</option>

          <?php

			## Product Options (Additional)

			if ($options_result) {

				foreach ($options_result as $option) {

					if ($option['option_type'] == '0') {

						$valquery = sprintf("SELECT * FROM %sImeiUnlock_options_mid WHERE father_id = '%d' ORDER BY value_name ASC;", $glob['dbprefix'], $option['option_id']);

						$values_result = $db->select($valquery);

					

						if ($values_result) {

							echo sprintf('<optgroup id="%d" label="%s">', $option['option_id'], $option['option_name']); 

							foreach ($values_result as $value) {

								echo sprintf('<option value="%d" class="sub">%s</option>', $value['value_id'], $value['value_name']);

							}

							echo '</optgroup>';

						}

					} else {

						## New textbox/textarea options

						echo sprintf('<option value="%d" class="top">%s</option>', $option['option_id'], $option['option_name']);

					}

				}

			}

		?>

        </select>

        <span class="bgright"></span>

      </div>

       

        <input type="hidden" id="opt_assign_id" value="0" />

      <div class="inputbox inputbox2" style="margin:0 10px">

   		   <span class="bgleft"></span>

        <input type="text" id="opt_price" value="0.00" class="textbox" />

        <span class="bgright"></span>

      </div>

        

        <input type="submit" value="Add Option" onclick="optionAdd(); return false;" class="submit" /></td>

    </tr>

    <tr>

    	<td  colspan="2"><div class="seprator"></div></td>

    </tr>

    <tr>

      <td>&nbsp;</td>

      <td><input name="Submit" type="submit" class="submit" style="margin-bottom:5px;" value="<?php echo $modeTxt." ".$lang['admin']['products_product'];?>" /></td>

    </tr>

  </table>

  <?php 

}

if ($config['seftags']) { 

?>

  <div class="headingBlackbg" style="margin-top:10px;"><?php echo $lang['admin']['products_meta_data'];?></div>

  <table class="mainTable" cellspacing="1" cellpadding="3" border="0" style="color:#464646" width="100%">

    

    <!--<tr>

      <td  class="tdText"><strong><?php echo $lang['admin']['products_custom_url'];?>:</strong></td>

      <td align="left">

      <div class="inputbox" style="width:493px"><span class="bgleft"></span>

      <input name="seo_custom_url" type="text" size="35" class="textbox"  value="<?php if(isset($results[0]['seo_custom_url'])) echo $results[0]['seo_custom_url']; ?>" />

      <span class="bgright"></span></div>

      </td>

    </tr>-->

    <tr>

      <td  class="tdText"><strong><?php echo $lang['admin']['products_browser_title'];?></strong></td>

      <td align="left">

      <div class="inputbox" style="width:493px"><span class="bgleft"></span>

      <input name="prod_metatitle" type="text" size="35" class="textbox" value="<?php if(isset($results[0]['prod_metatitle'])) echo $results[0]['prod_metatitle']; ?>" />

      <span class="bgright"></span></div>

      </td>

    </tr>

    <tr>

      <td  align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['products_meta_desc'];?></strong></td>

      <td align="left">

       

      <textarea name="prod_metadesc" cols="35" rows="3" class="textarea"><?php if(isset($results[0]['prod_metadesc'])) echo $results[0]['prod_metadesc']; ?>

</textarea>



</td>

    </tr>

    <tr>

      <td align="left" valign="top" class="tdText"><strong><?php echo $lang['admin']['products_meta_keywords'];?></strong> <?php echo $lang['admin']['settings']['comma_separated'];?></td>

      <td align="left">

      <textarea name="prod_metakeywords" cols="35" rows="3" class="textarea"><?php if(isset($results[0]['prod_metakeywords'])) echo $results[0]['prod_metakeywords']; ?>

</textarea></td>

    </tr>

    <tr>

    	<td colspan="2"><div class="seprator"></div></td>

    </tr>

    <tr>

      <td >&nbsp;</td>

      <td><input name="Submit" type="submit" style="margin-bottom:5px" class="submit" value="<?php echo $modeTxt." ".$lang['admin']['products_product'];?>" /></td>

    </tr>

  </table>

  <?php 

} 

?>

 <!-- <br />

  <div class="tdText"><em><u><strong><?php echo $lang['admin']['products_digi_info'];?></strong></u></em> <?php echo $lang['admin']['products_digi_desc'];?> </div>-->

</form>

<?php

}

?>

