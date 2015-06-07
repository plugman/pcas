<?php
/*
+--------------------------------------------------------------------------
|   Cub3Cart 4
|   ========================================
+--------------------------------------------------------------------------
|	checkproducts.inc.php
|   ========================================
|	Add/Delete Related Products	
|	Created By AH | 17072012
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
$lang = getLang("admin".CC_DS."admin_products.inc.php");
permission('products', 'read', true);

$productsPerPage = 25;
if (isset($_POST['Submit']) &&  $_POST['Submit'] != "")
{	
	$query = "Delete FROM ".$glob['dbprefix']."ImeiUnlock_related WHERE productId = ".$_REQUEST['edit'];
	$db->misc($query);
	foreach($_POST['prodID'] as $key => $product_id) 
	{
		$record['productId'] 		= $db->mySQLSafe($_REQUEST['edit']);
		$record['relatedProductId'] = $db->mySQLSafe($product_id);  				
		$insertIdx = $db->insert($glob['dbprefix']."ImeiUnlock_related", $record);
	}
}
$query   = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE  productId != '".$_REQUEST['edit']."' and digital='0'";
$results = $db->select($query);

// query database
$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
	
$results = $db->select($query, $productsPerPage, $page);
$numrows = $db->numrows($query);
$pagination = paginate($numrows, $productsPerPage, $page, "page", "txtLink", 7, array('delete'));
## Get Product Name
 $queryProductName   = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE  productId = ".$_REQUEST['edit']." LIMIT 0,1";
$resultsProductName = $db->select($queryProductName);
?>

<link href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/styles/style.css" rel="stylesheet" type="text/css" />
<form action="<?php echo $glob['adminFile']; ?>?_g=products/checkproducts&edit=<?=$_REQUEST['edit']?>" method="post" enctype="multipart/form-data" name="form1" language="javascript" >
  <input type="hidden" name="edit" id="edit" value="<?=$_REQUEST['edit']?> "/>
  <p class="copyText" style="text-align: right;"><?php echo $pagination; ?></p>
  <p class="copyText"><strong> <?=$resultsProductName[0]['name']?> - Recommendations</strong></p>
  <p style="color:#F00;font-family:Verdana, Geneva, sans-serif;font-size:10px"><? if(isset($insertIdx) && $insertIdx == 1) {   echo "Record has been updated sucessfully"; } ?></p>
  <table width="100%" border="0" class="mainTable" >
    <tr>
	  <td class="tdTitle" width="2%">ID.</td>
	  <td class="tdTitle" width="10%">Associate</td>
	  <td class="tdTitle" width="80%">Product Name</td>      
    </tr>
    <?php for($i=0 ; $i < count($results);  $i++){
			$cellColor = "";
			$cellColor = cellColor($i); 
	?>
    <tr>
    <td class="<?php echo $cellColor; ?> copyText"><?=$results[$i]['productId']?></td>
    <td class="<?php echo $cellColor; ?> copyText" align="center"><input name="prodID[]"  id="prodID[]"  type="checkbox" 
    <?php 	
		$brdquerychk   = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_related WHERE productId = ".$_REQUEST['edit'];
		$brandArraychk = $db->select($brdquerychk);
		for($j=0 ; $j < count($brandArraychk) ; $j++){
			if($brandArraychk[$j]['relatedProductId'] == $results[$i]['productId']){
	?>
     checked="checked"                
	<?php 		}
		}
	 ?>
       value="<?=$results[$i]['productId']?>" /></td>
    <td class="<?php echo $cellColor; ?> copyText"><?=$results[$i]['name']?></td>
    </tr>
    <? } ?>
  </table>
  <p align="center"><input type="submit" name="Submit" id="Submit" value="Submit" class="submit" /></p>
</form>
