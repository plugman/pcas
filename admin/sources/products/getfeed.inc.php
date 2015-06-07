<?php
/*
+--------------------------------------------------------------------------
|	xml_devices.inc.php
|   ========================================
|	Manage XML Devices
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); }
require_once ("classes" . CC_DS . "xmlparse" . CC_DS . "xml2array.php");
$lang = getLang("admin".CC_DS."admin_categories.inc.php");
permission("xml Feeds", "read", true);
$devicesPerPage = 20;
function categoriesname($catId)
{
	if($catId) {
		global $db;				
		$query		= "SELECT cat_id,cat_name FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_id =".$catId;
		$results 	= $db->select($query);
		//echo $results;
		if($results)
			return $results[0]['cat_name'];
}
}
function productexit($proid)
{
	global $db;		
 	 $query		= "SELECT productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE xmlproductId =".$proid;
	$results 	= $db->select($query);	
	//print_r($results);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function isImage( $url )
  {
    $pos = strrpos( $url, ".");
	if ($pos === false)
	  return false;
	$ext = strtolower(trim(substr( $url, $pos)));
	$imgExts = array(".gif", ".jpg", ".jpeg", ".png", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
	if ( in_array($ext, $imgExts) )
	  return true;
    return false;
  }
function productoptexit($proopt){
	global $db;		
 	$query		= "SELECT assign_id FROM ".$glob['dbprefix']."ImeiUnlock_options_bot WHERE assign_id =".$proopt;
	$results 	= $db->select($query);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function productoptmidexit($promid){
	global $db;	
 	$query		= "SELECT value_id FROM ".$glob['dbprefix']."ImeiUnlock_options_mid WHERE value_id =".$promid;
	$results 	= $db->select($query);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function productopttopexit($protop){
	global $db;	
 	$query		= "SELECT option_id FROM ".$glob['dbprefix']."ImeiUnlock_options_top WHERE option_id =".$protop;
	$results 	= $db->select($query);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function productcatexit($procat){
	global $db;	
 	$query		= "SELECT cat_id FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE xmlcat_id =".$procat;
	$results 	= $db->select($query);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function catidxexit($proidx){
	global $db;		
 	$query		= "SELECT id FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx WHERE id =".$proidx;
	$results 	= $db->select($query);	
	if(!empty($results))
	return 0;
	else
	return 1;
}
function getcatid($xmlcatid){
	global $db;		
 	$query		= "SELECT cat_id FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE xmlcat_id =".$xmlcatid;
	$results 	= $db->select($query);	
	if(!empty($results))
	return $results[0]['cat_id'];
}
function getproid($xmlproid){
	global $db;		
 	$query		= "SELECT productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE xmlproductId =".$xmlproid;
	$results 	= $db->select($query);	
	if(!empty($results))
	return $results[0]['productId'];
}
function copyFile($url, $dirname){
    @$file = fopen ($url, "rb");
    if (!$file) {
       // echo"<font color=red>Failed to copy $url!</font><br>";
        //return false;
    }else {
      $filename = basename($url);
	
        $fc = fopen($dirname.$filename, "wr");
        while (!feof ($file)) {
           $line = fread ($file, 1028);
           fwrite($fc,$line);
        }
        fclose($fc);
        //echo "<font color=blue>File $url saved to PC!</font><br>";
        //return true;
    }
}
if(isset($_POST['EditSelected'])&& is_array($_POST['products']))
	{
	 	 $proval	= $_POST['products']; 
	
		if(isset($_POST['MoveTo']) && trim($_POST['MoveTo'])!="" && !empty($_POST['products']))
		{
			foreach($proval as $prokey)
			{
				$selectindex = $db->select("Select productId from ImeiUnlock_inventory where productId = " . $prokey);
				
				if(!empty($selectindex))
				{
				//	 echo $devicekey."<br>";
					$where = "productId=".$prokey;
				 	$record['disabled'] = $db->mySQLSafe($_POST['MoveTo']);
					$update	= $db->update("ImeiUnlock_inventory", $record, $where);
					unset($record);
				}			
			}
			if($_POST['MoveTo']==0)
			{				
				$msg = "<p class='infoText'>Selected Products are set to show.</p>";	
			}
			else if($_POST['MoveTo']==1)
			{
				$msg = "<p class='infoText'>Selected Products are set to hide.</p>";	
			}
		}
		
	}
	
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getproducts")
{
	//$xmlfilePath 	=  xmlfeedPath('master_feed.xml', 'url');	
	//$data = @file_get_contents($xmlfilePath ,false); 
	$data = getxml();
	if(isset($data) && !empty($data))
	{
		$converter = new Xml2Array();
		$converter->setXml($data);
		$xml_array = $converter->get_array();
		
		 $_count	   = $xml_array['data']['products']['counter']['countcat']['#text'];
		
		//Extracting and Pushing categories into DB
		for($x=0; $x< $_count; $x++){
				$record["xmlcat_id"] 		= $db->mySQLSafe($xml_array['data']['products']['productscats']['procats'][$x]['cat_id']['#text']);
				$record["cat_name"] 	= $db->mySQLSafe($xml_array['data']['products']['productscats']['procats'][$x]['cat_name']['#text']);
				$record["type"] 	= $db->mySQLSafe($xml_array['data']['products']['productscats']['procats'][$x]['type']['#text']);		
				if(productcatexit($record["xmlcat_id"])){
					$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_category", $record);					
						unset($record);		
					}else{
						
						$where = "xmlcat_id=".$record["xmlcat_id"];
						unset($record["xmlcat_id"] );
						 $db->update($glob['dbprefix']."ImeiUnlock_category", $record, $where);
					}
						unset($record);	
					unset($where);
		}
		unset($record);
		unset($_count);
		
		//Extracting and Pushing  products into DB
		 $_count	   = $xml_array['data']['products']['counter']['count']['#text'];
		$_catcount = 0;
		/*if($_count>1){
		//do nothing
		}else{
		$xml_array['data']['products']['product'][0]= $xml_array['data']['products']['product'];
		}*/
		
		
/*
		echo "<PRE>";
		print_r($xml_array['data']['products']);
		exit();*/
		
		
		//Extracting and Pushing Products into DB
		for($i=0; $i< $_count; $i++)
		{
				$xmlcproId				= $xml_array['data']['products']['product'][$i]['product_id']['#text']; 
				$record["xmlproductId"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['product_id']['#text']);
				$record["image"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['image']['#text']);
				$record["disabled"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['disabled']['#text']);
				$record["productCode"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['productCode']['#text']);
				$record["quantity"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['quantity']['#text']);
				$record["noImages"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['noImages']['#text']);
				$record["sale_price"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['sale_price']['#text']);
				$record["date_added"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['date_added']['#text']);
				$record["deltime"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['deltime']['#text']);
				$record["short_desc"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['short_desc']['#text']);
				$record["prod_type"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['prod_type']['#text']);
				$record["useStockLevel"]= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['stocklevel']['#text']);
				$record["sdesc"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['sdesc']['#text']);
				$record["price"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['price']['#text']);
				$record["description"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['description']['#text']);
				
				$record["name"] 		= trim($db->mySQLSafe($xml_array['data']['products']['product'][$i]['name']['#text']));	
				$record["image2"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['image2name']['#text']);	
				$record["short_title"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['protitle']['#text']);
				$record["premium"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['premium']['#text']);
				$record["min_quantity"] = $db->mySQLSafe($xml_array['data']['products']['product'][$i]['min_quantity']['#text']);
				$record["shipping"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['shipping']['#text']);
				$record["processingtime"]= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['processingtime']['#text']);
				$record["digital"] 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['digital']['#text']);	
				$record["vdate_added"] 	= $db->mySQLSafe(time());	
				$record["is_XML"] 		= $db->mySQLSafe(1);
				
				## check or swap catid
				$vcat_id 		= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['cat_id']['#text']);		
				$catid = getcatid($vcat_id);
				if($catid > 0){
					$record["cat_id"] = $db->mySQLSafe($catid);
				}
							
		/*		echo "<pre>";
			print_r($record);
			echo $record[$i]['product_id']; die();*/
				
				if(productexit($xmlcproId) == 0){
					
				$record2["disabled"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['disabled']['#text']);
				$record2["deltime"] 	= $db->mySQLSafe($xml_array['data']['products']['product'][$i]['deltime']['#text']);
				$where = "xmlproductId=".$xmlcproId;
				$db->update($glob['dbprefix']."ImeiUnlock_inventory", $record2, $where);
				unset($record2);
				unset($where);
					}
					else
					{
						$url = $xml_array['data']['products']['product'][$i]['imageurl']['#text'];
						$url2 = $xml_array['data']['products']['product'][$i]['image2url']['#text'];
						$thumburl = $xml_array['data']['products']['product'][$i]['thumbimageurl']['#text'];
						 $rootMasterPath = imgPath('', '', $path="root");
					  $rootMasterPaththum = $rootMasterPath.'thumbs/';
					  if(isImage($url) == true)
						 copyFile($url, $rootMasterPath);
						 if(isImage($url2) == true)
						 copyFile($url2, $rootMasterPath);
					  if(isImage($thumburl) == true)
						copyFile($thumburl, $rootMasterPaththum);
						$_catcount++; 
						$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_inventory", $record);
						unset($record);		
						//GetProductID($XMLcatId)
					}
			
		}
		unset($record);
		unset($_count);
		 $_count	   = $xml_array['data']['products']['counter']['countopt']['#text'];
		//Extracting and Pushing Options boot into DB
		for($j=0; $j< $_count; $j++){
				$record["assign_id"] 		= $db->mySQLSafe($xml_array['data']['products']['productsoptions']['options'][$j]['asigned_id']['#text']);
				$record["option_symbol"] 		= $db->mySQLSafe($xml_array['data']['products']['productsoptions']['options'][$j]['value_symbol']['#text']);
				
				$record["option_id"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptions']['options'][$j]['option_id']['#text']);
				$record["value_id"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptions']['options'][$j]['value_id']['#text']);
				$vproid  				= $db->mySQLSafe($xml_array['data']['products']['productsoptions']['options'][$j]['product']['#text']);
				$proid = getproid($vproid);
				$record["product"] 		= $db->mySQLSafe($proid);	
							
				if(productoptexit($record["assign_id"])){
					$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_options_bot", $record);
						unset($record);		
					}else{
						$where = "assign_id=".$record["assign_id"];
						unset($record["assign_id"]);
						 $db->update($glob['dbprefix']."ImeiUnlock_options_bot", $record, $where);
					}
					unset($record);	
					unset($where);
		}
		unset($record);
		unset($_count);
		 $_count	   = $xml_array['data']['products']['counter']['countoptmid']['#text'];
		//Extracting and Pushing Optionsmid into DB
		for($x=0; $x< $_count; $x++){
				$record["value_id"] 		= $db->mySQLSafe($xml_array['data']['products']['productsoptionsmid']['optionsmid'][$x]['value_id']['#text']);
				$record["value_name"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptionsmid']['optionsmid'][$x]['value_name']['#text']);
				$record["father_id"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptionsmid']['optionsmid'][$x]['father_id']['#text']);				
				if(productoptmidexit($record["value_id"])){
					$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_options_mid", $record);
						unset($record);		
					}else{
						
						$where = "value_id=".$record["value_id"];
						unset($record["value_id"]);
						 $db->update($glob['dbprefix']."ImeiUnlock_options_mid", $record, $where);
					}
					unset($record);	
					unset($where);	
		}
		unset($record);
		unset($_count);
		 $_count	   = $xml_array['data']['products']['counter']['countopttop']['#text'];
		//Extracting and Pushing Optionstop into DB
		for($x=0; $x< $_count; $x++){
				$record["option_id"] 		= $db->mySQLSafe($xml_array['data']['products']['productsoptionstop']['optionstop'][$x]['option_id']['#text']);
				$record["option_name"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptionstop']['optionstop'][$x]['option_name']['#text']);
				$record["option_type"] 	= $db->mySQLSafe($xml_array['data']['products']['productsoptionstop']['optionstop'][$x]['option_type']['#text']);				
				if(productopttopexit($record["option_id"])){
					$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_options_top", $record);
						unset($record);		
					}else{
						
						$where = "option_id=".$record["option_id"];
						unset($record["option_id"]);
						 $db->update($glob['dbprefix']."ImeiUnlock_options_top", $record, $where);
					}
						unset($record);	
					unset($where);
		}
		unset($record);
		unset($_count);
	
		 $_count	   = $xml_array['data']['products']['counter']['countcatidx']['#text'];
		//Extracting and Pushing Optionsmid into DB
		for($x=0; $x< $_count; $x++){
				$vid 		= $db->mySQLSafe($xml_array['data']['products']['productscatidx']['catidx'][$x]['id']['#text']);
				
				$vcatid 	= $db->mySQLSafe($xml_array['data']['products']['productscatidx']['catidx'][$x]['cat_id']['#text']);
				$catid = getcatid($vcatid);	
				$record["cat_id"] = $db->mySQLSafe($catid );
				$vproid 	= $db->mySQLSafe($xml_array['data']['products']['productscatidx']['catidx'][$x]['productId']['#text']);	
				$proid = getproid($vproid);	
				$record["productId"] = $db->mySQLSafe($proid );
				if(catidxexit($vid)){
					$insert  	= $db->insert($glob['dbprefix']."ImeiUnlock_cats_idx", $record);
						unset($record);		
					}else{
						
						$where = "id=".$record["cat_id"];
						unset($record["id'"]);
						 $db->update($glob['dbprefix']."ImeiUnlock_cats_idx", $record, $where);
					}
						unset($record);	
					unset($where);
		}
		if($_catcount==0)
		{
			$msg = "<p class='infoText'>No new Products available.</p>";	
		}else
		{
			$msg = "<p class='infoText'>".$_catcount."&nbsp;Products were extracted successfully.</p>";	
			$updatedate = fetchDbConfig("config");
			$record['lastdate'] = time();	
			writeDbConf ($record, 'config', $updatedate, true);
		}
	
		//$xml_array['message']['device'][0]['model']['#text'];
	}
	else if(empty($data)){
	$msg = "<p class='infoText'>No new Products available</p>";
	}
	else{
	$msg = "<p class='infoText'>XML feed was not read or Invalid Configuration Setting Contact with Imei-Unlock Administration</p>";
	}
}
if (isset($_GET['hide'])) 
{
	//die("hide");
	$cache = new cache();
	$cache->clearCache();
	$record['disabled']	= sprintf("'%d'", $_GET['hide']);
	$where			= "productId = " . $db->mySQLSafe($_GET['id']);
	$update			= $db->update("ImeiUnlock_inventory", $record, $where);
	$catNameRS = $db->select("SELECT name FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = ".$db->mySQLSafe($_GET['id']));
	
	## Start-Logging-CR [MI]: Modification message - Show Product name
	$msg	= ($update == true) ? "<p class='infoText'> Product '".$catNameRS[0]['name']."' Updated $_action successfully!</p>" : "<p class='warnText'>Product '".$catNameRS[0]['name']."' failed to $_action.</p>";
	## End-Logging-CR [MI]: Modification message - Show Product name
}
if (!isset($_GET['mode'])) 
{
	//die("not mode");
	
	// make sql query
	if (isset($_GET['edit']) && $_GET['edit'] > 0)
	{
		$query = sprintf("SELECT * FROM ImeiUnlock_inventory WHERE is_XML = '1' AND proid = %s", $db->mySQLSafe($_GET['edit'])); 
	} 
	else 
	{
		$query = "SELECT * FROM ImeiUnlock_inventory WHERE is_XML = '1' ORDER BY cat_id ASC";
		
	}
		
	// query database
	//echo $query;
		$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
		$results = $db->select($query, $devicesPerPage, $page);
		$numrows = $db->numrows($query);
		$pagination = paginate($numrows, $devicesPerPage, $page, "page", "txtLink", 7, array('delete'));
}
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<script type="text/javascript">
function Extractproducts()
{
	document.getElementById('imgProgress').style.display = '';
	document.getElementById('addpro').style.display = 'none';
	document.getElementById('action').value = "getproducts";
	document.getElementById('frmExtract').submit();
}
</script>
<div>
  <?php
if (!isset($_GET['mode'])) 
{
if (permission("xml Feeds", "write") == true)
    { 
	echo "<span style='float: right;'><a id='addpro' style='cursor:pointer;' class='txtLink' onclick='Extractproducts(); '><img src='".$glob['adminFolder']."/images/buttons/new.gif' border='0' />&nbsp;Extract Products</a> <img id='imgProgress' src='admin/images/imgProgress.gif' border='0' style='display:none;'  /></span>";
	}
}
	
?>
  <p class="pageTitle"> Update Products </p>
</div>
<?php 
if(isset($msg)) echo msg($msg);
if (!isset($_GET['mode']) && !isset($_GET['edit'])) {
?>
<p class="copyText">Below is a list of all the Products which were extracted via XML Feeds.</p>
<form method="post" id="reorderProduct" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="mainTable">
    <tr>
      <td class="tdTitle">&nbsp;</td>
      <td class="tdTitle">Product Name</td>
      <td class="tdTitle">Network</td>
      <td class="tdTitle" align="center">Action</td>
    </tr>
    <?php 
if ($results == true) {
	$count = count($results)-1;
	foreach ($results as $i => $result) 
	{
		$cellColor	= cellColor($i);
		/*$sql		= sprintf("SELECT id FROM %sImeiUnlock_Product WHERE cat_father_id = '%d'", $glob['dbprefix'], $results[$i]['id']);
		$subcat		= $db->numrows($sql);*/
		
?>
    <tr class="productRow <?php echo $cellColor; ?> tdText">
      <td width="2%" align="left" valign="middle"><input type="checkbox" name="products[]" class="productCheckbox" id="products<?php echo $results[$i]['productId']; ?>" value="<?php echo $results[$i]['productId']; ?>" />
        <input type="hidden" name="current[<?php echo $results[$i]['productId']; ?>]" value="<?php echo $results[$i]['productId']; ?>" /></td>
      <td width="25%" align="left" valign="middle"><a href="<?php echo $glob['adminFile']; ?>?_g=products/index&amp;edit=<?php echo $results[$i]['productId']; ?>" class="txtLink"><?php echo $results[$i]['name'];?></a></td>
      <td width="25%" align="left" valign="middle"><?php echo $name = categoriesname($results[$i]['cat_id']);?></td>
      <td width="20%" align="center" valign="middle"><?php
				switch($results[$i]['disabled']) {
					case 0:
						$url	= (permission("xml Feeds","edit")) ? 'href="?_g=products/getfeed&amp;hide=1&amp;id='.$results[$i]['productId'].'" class="txtLink"' : $link401;
						$title	= $lang['admin_common']['hide'];
						break;
					case 1:
						$url	= (permission("xml Feeds","edit")) ? 'href="?_g=products/getfeed&amp;hide=0&amp;id='.$results[$i]['productId'].'" class="txtLink"' : $link401;
						$title	= $lang['admin_common']['show'];
						break;
				}
				echo sprintf('<a %s>%s</a>', $url, $title);
			?>
        &nbsp;&nbsp; </td>
    </tr>
    <?php
	} // end loop
	?>
    <tr>
      <td><img src="<?php echo $glob['adminFolder'];?>/images/selectAll.gif" alt="" width="16" height="11" /></td>
      <td colspan="17" class="tdText"><a href="#" class="txtLink" onclick="return checkUncheck('reorderProduct', 'productCheckbox');">Check/Uncheck all </a> &nbsp;
        <?php if(permission("products","edit")==true) {  ?>
        <select name="MoveTo" class="textbox">
          <option value="" selected="selected">Select Status</option>
          <option value="1" >Hide </option>
          <option value="0" >Show </option>
        </select>
        <input type="submit" name="EditSelected" class="submit" value="Go" />
        <?php } ?></td>
    </tr>
    <?php
} else {
?>
    <tr>
      <td colspan="4" class="tdText"> No XML extracted Product exist in the database. </td>
    </tr>
    <?php } ?>
  </table>
</form>
<p class="copyText" style="text-align: right;"><?php echo $pagination; ?></p>
<?php 
}  
?>
<form method="post" name="frmExtract" id="frmExtract" action="">
  <input type="hidden" id="action" name="action" value="getproducts" />
</form>
