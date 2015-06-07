<?php
/*
+--------------------------------------------------------------------------
|	export.inc.php
|   ========================================
|	Export Catalogue	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");
$lang = getLang("admin".CC_DS."admin_products.inc.php");

$whereClause = '';
if(!isset($_GET['quan'])) {
	$_GET['quan'] = 500;
}
$download_part = ($_GET['page']+1);

if (isset($_GET['format']) && strtolower($_GET['format']) == 'googlebase') {
	
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category on ".$glob['dbprefix']."ImeiUnlock_inventory.cat_id = ".$glob['dbprefix']."ImeiUnlock_category.cat_id WHERE `disabled` = 0 ORDER BY name ASC";
	$results = $db->select($query, $_GET['quan'], $_GET['page']);

	if ($results == true) {
		$googleBaseContent = "id\tproduct_url\tname\tdescription\timage_url\tprice\tcurrency\tcondition\r\n";
		
		for ($i=0; $i<count($results); $i++) {
			$salePrice = salePrice($results[$i]['price'], $results[$i]['sale_price']);
			$price = ($salePrice > 0) ? $salePrice : $price = $results[$i]['price'];
			
			$name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['name']));
			$name = str_replace("  ","",$name);
			$desc = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['description']));
			$desc = str_replace("  ","",$desc);
			
			$googleBaseContent .= $results[$i]['productId']."\t";
			
			if ($config['sef'] == 0) {
				$googleBaseContent .= $glob['storeURL']."/index.php?_a=viewProd&productId=".$results[$i]['productId']."\t".$name."\t".$desc;
			} else {
				include_once("includes".CC_DS."sef_urls.inc.php");
				$googleBaseContent .= $glob['storeURL']. "/" .generateProductUrl($results[$i]['productId'])."\t".$name."\t".$desc;			
			}
			
			if ($results[$i]['image']) {
				$googleBaseContent .= "\t".$glob['storeURL']."/images/uploads/".str_replace(" ","%20",$results[$i]['image']);
			} else {
				$googleBaseContent .= "\t".$glob['storeURL']."/skins/".$config['skinDir']."/styleImages/nophoto.gif";
			}
			
			$googleBaseContent .= "\t".$price."\t".$config['defaultCurrency']."\tnew\r\n";
		}
		
		$filename = "GoogleBaseFeed_".date("Ymd")."_".$download_part.".txt";
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-type: text/plain");
		header("Content-length: ".strlen($googleBaseContent));
		header("Content-Transfer-Encoding: binary");
		echo $googleBaseContent;
		exit;
	}
} else if (isset($_GET['format']) && strtolower($_GET['format']) == 'shopzilla') {
	
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category on ".$glob['dbprefix']."ImeiUnlock_inventory.cat_id = ".$glob['dbprefix']."ImeiUnlock_category.cat_id WHERE `disabled` = 0 ORDER BY name ASC";
	$results = $db->select($query, $_GET['quan'], $_GET['page']);

	if ($results == true) {
		$shopzillaContent = "Category\tManufacturer\tTitle\tProduct Description\tLink\tImage\tSKU\tStock\tCondition\tShipping Weight\tShipping Cost\tBid\tPromotional Description\tEAN/UPC\tPrice\n";
		
		for ($i=0; $i<count($results); $i++) {
			$row = $results[$i];
				
			$salePrice = salePrice($results[$i]['price'], $results[$i]['sale_price']);
			$price = ($salePrice > 0) ? $salePrice : $price = $results[$i]['price'];
						
			$name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['name']));
			$name = str_replace("  ","",$name);
			$name = substr($name, 0, 100);
			
			$desc = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['description']));
			$desc = str_replace("  ","",$desc);
			$desc = substr($desc, 0, 1000);
			
			$cat_name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['cat_name']));
			$cat_name = str_replace("  ","",$cat_name);
			
			if (!empty($name)) {
				if ($config['sef'] == 0) {
                    $shopzillaContent .= $cat_name."\t\t".$name."\t\"".$desc."\"\t".$glob['storeURL']."/index.php?_a=viewProd&productId=".$results[$i]['productId'];
                } else {
                    include_once("includes".CC_DS."sef_urls.inc.php");
                    $shopzillaContent .= $cat_name."\t\t".$name."\t\"".$desc."\"\t".$glob['storeURL'].'/'.generateProductUrl($results[$i]['productId']);
                }
                
                if ($results[$i]['image']) {
                    $shopzillaContent .= "\t".$glob['storeURL']."/images/uploads/".str_replace(" ","%20",$results[$i]['image']);
                } else {
                    $shopzillaContent .= "\t".$glob['storeURL']."/skins/".$config['skinDir']."/styleImages/nophoto.gif";
                }
                
                $shopzillaContent .= "\t".$results[$i]['productCode']."\tIn Stock\tNew\t".$results[$i]['prodWeight']."\t\t\t\t".$results[$i]['eanupcCode']."\t".$price."\n"; 
				
				$shopzillaContent .= "\t".$results[$i]['productCode']."\tIn Stock\tNew\t".$results[$i]['weight']."\t\t\t\t".$results[$i]['eanupcCode']."\t".$price."\n";
	//		$shopzillaContent .= "\t".$results[$i]['productCode']."\tIn Stock\tNew\t".$results[$i]['prodWeight']."\t\t\t\t".$results[$i]['eanupcCode']."\t".$price."\n";

      }
		}
		
		$filename = "ShopZilla_".date("Ymd")."_".$download_part.".txt";

		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-type: text/plain");
		header("Content-length: ".strlen($shopzillaContent));

		//header("Content-Transfer-Encoding: binary");
		echo $shopzillaContent;
		exit;
	}
} elseif (isset($_GET['format']) && strtolower($_GET['format']) == 'shopping.com') {
	
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE `disabled` = 0 ORDER BY name ASC";
	$results = $db->select($query, $_GET['quan'], $_GET['page']);

	if ($results == true) {
		$shoppingContent = "mpn,upc,manufacturer,product name,product description,price,stock,stock description,product url,image url,category\n";
		
		for ($i=0; $i<count($results); $i++) {
			$row = $results[$i];
				
			$salePrice = salePrice($results[$i]['price'], $results[$i]['sale_price']);
			$price = ($salePrice > 0) ? $salePrice : $price = $results[$i]['price'];
						
			$name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['name']));
			$name = str_replace("  ","",$name);
			$desc = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['description']));
			$desc = str_replace("  ","",$desc);
			
			if (!empty($name) && $price > 0) {
				if ($config['sef'] == 0) {
					$url = $glob['storeURL']."/index.php?_a=viewProd&productId=".$results[$i]['productId'];
				} else {
					include_once("includes".CC_DS."sef_urls.inc.php");
					$url = $glob['storeURL'].'/'.generateProductUrl($results[$i]['productId']);
				}
				if ($results[$i]['image']) {
					$image = $glob['storeURL']."/images/uploads/".str_replace(" ","%20",$results[$i]['image']);
				} else {
					$image = $glob['storeURL']."/skins/".$config['skinDir']."/styleImages/nophoto.gif";
				}				
				$shoppingContent .= sprintf("%s,%s,%s,\"%s\",\"%s\",%s,%s,%s,%s,%s,%s\n", $row['productCode'], $row['eanupcCode'], '', $name, addslashes(html_entity_decode_utf8($desc, ENT_QUOTES)), $price, $stock, '', $url, $image, '');
			}
		}
		
		$filename = "Shopping.com_".date("Ymd")."_".$download_part.".txt";
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-type: text/plain");
		header("Content-length: ".strlen($shoppingContent));
		//header("Content-Transfer-Encoding: binary");
		echo $shoppingContent;
		exit;
	} 
} else if (isset($_GET['format']) && strtolower($_GET['format']) == 'cubecart') {
		
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE `disabled` = 0";
	$results = $db->select($query, $_GET['quan'], $_GET['page']);

	if ($results == true) {
	
	$ccContent = "Product Name,Product Code,Product Description,Price,Sale Price,Image,Stock Level,Use Stock,Master Category ID\r\n";
	
		for ($i=0; $i<count($results); $i++) {
			
			$name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['name']));
			$name = str_replace("  ","",$name);
			$desc = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","
			"),"",strip_tags($results[$i]['description']));
			$desc = str_replace("  ","",$desc);
			
			$ccContent 	.= 	"\"".$name.
							"\",\"".$results[$i]['productCode'].
							"\",\"".$desc.
							"\",\"".$results[$i]['price'].
							"\",\"".$results[$i]['sale_price'].
							"\",\"".str_replace(" ","%20",$results[$i]['image']).
							"\",\"".$results[$i]['stock_level'].
							"\",\"".$results[$i]['useStockLevel'].
							"\",\"".$results[$i]['cat_id'].
							"\"\r\n";
			
		}
		
		$filename = "ImeiUnlock_Products_".date("Ymd")."_".$download_part.".csv";
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-type: text/plain");
		header("Content-length: ".strlen($ccContent));
		header("Content-Transfer-Encoding: binary");
		echo $ccContent;
		exit;	
	}
	
} else if (isset($_GET['format']) && strtolower($_GET['format']) == 'csv') {
	
	$query		= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory INNER JOIN ".$glob['dbprefix']."ImeiUnlock_category on ".$glob['dbprefix']."ImeiUnlock_inventory.cat_id = ".$glob['dbprefix']."ImeiUnlock_category.cat_id WHERE `disabled` = 0 ORDER BY name ASC";
	$results	= $db->select($query, $_GET['quan'], $_GET['page']);
	
	if ($results) {
		
		$output[] = '';
	}
	
} else if (isset($_GET['format']) && strtolower($_GET['format']) == 'archive') {
	## To Do
}

###################################################

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");


/*
if (isset($_GET['export'])) {
	$msg = 'Creating catalogue backup...';
	
	$query		= sprintf("SELECT * FROM `%sImeiUnlock_inventory`", $glob['dbprefix']);
	$results	= $db->select($query);
	
	function formatstrings(&$value, $key, $separator) {
		$value = str_replace(array("\n","\r"), '', $value);
		$value = str_replace($separator, '\\'.$separator, $value);
	}
	
	$csvfile = '/tmp/exportfile';
	
	$separator = (isset($_GET['separator'])) ? $_GET['separator'] : ',';
	for ($i=0;$i<count($results);$i++) {
		if ($_GET['format'] == 'csv') {
			$row = $results[$i];
			if ($i==0) {
				foreach ($row as $key => $val) {
					$fields[] = $key;
				}
				$output[] = implode($separator, $fields);
			}
			// create CSV file with headers
			array_walk($row, 'formatstrings', $separator);
			$output[] = implode($separator, $row);
		}
		// write to file?
	}
	$fp = fopen($csvfile, 'w+');
	fwrite($fp, implode("\n", $output));
	fclose($fp);
	
	// Create the archive
	include "/home/martin/cubecart/classes/misc/zip.inc.php";
	$createZip = new createZip;  

	$createZip->addDirectory("catalog/");
	
	$zipArray[] = $csvfile;
	
	$createZip->addFiles($zipArray);

	$fileContents = file_get_contents("/tmp/exportfile");  
	$createZip->addFile($fileContents, "dir/exportfile");  

	$createZip->saveArchive('archive.zip');
//	$fileName = "archive.zip";


	$fd = fopen ($fileName, "wb");
	$out = fwrite ($fd, $createZip->getZippedfile());
	fclose ($fd);
	
	//@unlink($csvfile);
}
*/


?>
<p class="pageTitle"><?php echo $lang['admin']['products_export_cat']?></p>
<?php 
$lang = getLang("admin".CC_DS."admin_orders.inc.php");
$numrows = $db->numrows("SELECT `productId` from ".$glob['dbprefix']."ImeiUnlock_inventory");
if(!$pagination = paginate($numrows, $_GET['quan'], $page, "page", "txtLink", 1000, false, true)) {
	$pagination = '<a class="txtLink" href="?_g=products%2Fexport&amp;page=0&amp;quan='.$_GET['quan'].'">1</a>';
} 
?>
Number of products per export <select name="productsPerPage" class="dropDown" onchange="jumpMenu('parent',this,0)">
  <?php
  $range = array(
  	50, 100, 250, 500, 1000, 5000, 10000, 25000, 50000
  );
  foreach($range as $value) {
	 ?>
	 <option value="?_g=products/export&amp;quan=<?php echo $value; ?>" <?php if($value == $_GET['quan']) echo "selected='selected'"; ?>><?php echo number_format($value); ?></option>
	 <?php
  }
  ?>

</select>
<h4>Google Base</h4>
<?php
echo $lang['admin']['orders_download_link']." ".str_replace("export","export&amp;format=googlebase",$pagination);
?>  
<h4>Shopping.com</h4> 
<?php
echo $lang['admin']['orders_download_link']." ".str_replace("export","export&amp;format=shopping.com",$pagination);
?>
<h4>ShopZilla</h4> 
<?php
echo $lang['admin']['orders_download_link']." ".str_replace("export","export&amp;format=shopzilla",$pagination);
?>
<h4>ImeiUnlock CSV (Comma Separated Value)</h4> 
<?php
echo $lang['admin']['orders_download_link']." ".str_replace("export","export&amp;format=ImeiUnlock",$pagination);
?>
