<?php
set_time_limit(0);

require_once("includes/global.inc.php");
require_once("includes/functions.inc.php");
require_once("classes/db/db.php");
$lang = getLang('orders.inc.php');

$db = new db();
$config = fetchdbconfig("config");
require_once ("includes/currencyVars.inc.php");
$sqlQuery = "1=1 AND I.digital = '2' ";
if(!empty($_POST['bycategory'])) {	
	$sqlQuery .= " AND I.cat_id = ". $_POST['bycategory'];
}

if(isset($_POST['exportCSV'])){
$query = "SELECT 
			I.name,I.price,I.productId,C.cat_id,C.cat_name,C.cat_father_id
		FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id WHERE  ".$sqlQuery." ORDER BY productId DESC";
$results = $db->select($query, $resultsPerPage, $page);
$ccContent = "Category ID,Category,Problem,Price\r\n";
foreach($results as $value){
	$name = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","

			"),"",strip_tags($value['name']));

			$name = str_replace("  ","",$name);
			
	$cateroty =	getproglemtree($value['cat_name'], $value['cat_father_id'], $value['cat_id']);
	
	$cateroty = str_replace(array("&nbsp;","\t","\r","\n","\0","\x0B","

			")," ",strip_tags($cateroty));

			$cateroty = str_replace("  ","",$cateroty);
			
			$ccContent 	.= 	"\"".$value['cat_id'].

							"\",\"".$cateroty.

							"\",\"".$name.

							"\",\"".$value['price'].					

							"\"\r\n";
}

		$filename = "ImeiUnlock_Problems_".date("Ymd")."_".$download_part.".csv";

		header('Pragma: private');

		header('Cache-control: private, must-revalidate');

		header("Content-Disposition: attachment; filename=".$filename);

		header("Content-type: text/plain");

		header("Content-length: ".strlen($ccContent));

		header("Content-Transfer-Encoding: binary");

		echo $ccContent;

		exit;	
}
?>