<?php


/*


+--------------------------------------------------------------------------


|	index.inc.php


|   ========================================


|	Manage faqs :: FM 19-04-13


+--------------------------------------------------------------------------


*/





if(!defined('CC_INI_SET')){ die("Access Denied"); }





$lang = getLang("admin".CC_DS."admin_repair.inc.php");


$lang2 = getLang("admin".CC_DS."admin_products.inc.php");


permission("repair","read",TRUE);


require("classes".CC_DS."gd".CC_DS."gd.inc.php");





require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");


$productsperpage = 50;





if (isset($_POST['saveOrder']) && !empty($_POST['priority'])) {


	foreach ($_POST['priority'] as $index => $productId) {


		$sql = sprintf("UPDATE %sImeiUnlock_inventory SET priority = '%d' WHERE productId = '%d' LIMIT 1;", $glob['dbprefix'], $index+1, $productId);


		$db->misc($sql);


	}


	$cache = new cache();


	$cache->clearCache();


}





if (isset($_GET['disabled'])) {


	$cache = new cache();


	$cache->clearCache();


	


	$record['disabled'] = $_GET['disabled'];


	$where = "productId=".$db->mySQLSafe($_GET['productId']);


	$update = $db->update($glob['dbprefix']."ImeiUnlock_inventory", $record, $where);


	


	$msg = ($update == true) ? "<p class='infoText'>"."Update Success"."</p>" : "<p class='warnText'>"."Update Failed"."</p>";


} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {





	$cache = new cache();


	$cache->clearCache();





	// delete index


	$where = "productId=".$db->mySQLSafe($_GET["delete"]);	


	


	// delete testimonials


	$where = "productId=".$db->mySQLSafe($_GET["delete"]);


	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_inventory", $where);


	


	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['faqs_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_delete_failed']."</p>";





} else if (isset($_POST['name'])) {





	$cache = new cache();


	$cache->clearCache();


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


	$record["name"] = $db->mySQLSafe($_POST['name']);


	$record["price"] = $db->mySQLSafe($_POST['price']);


	$record["disabled"] = $db->mySQLSafe($_POST['disabled']);


	$record["cat_id"] = $db->mySQLSafe($_POST['cat_id']);


	$record["digital"] = $db->mySQLSafe(2);


	$fckEditor = (detectSSL()==true && $config['force_ssl']==false) ?  str_replace($config['rootRel_SSL'],$glob['rootRel'],$_POST['FCKeditor']) : $_POST['FCKeditor'];


	$record["description"] = $db->mySQLSafe(str_replace('##HIDDEN##', '', $fckEditor));	


	if (is_numeric($_POST['productId'])) {


		// update product count. This is gonna be tricky!!! 


		


		$where = "productId=".$db->mySQLSafe($_POST['productId']);


		$update = $db->update($glob['dbprefix']."ImeiUnlock_inventory", $record, $where);





		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['faq_title']."' ".$lang['admin']['faqs_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_update_fail']."</p>";


	} else {		


		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_inventory", $record);


		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['faq_title']."' ".$lang['admin']['faqs_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_add_failed']."</p>";


	}


}





if (!isset($_GET['mode'])) {


	// make sql query


	if ((isset($_GET['edit']) && $_GET['edit'] > 0) || (isset($_GET['clone']) && $_GET['clone']>0)) {

	$repair = (isset($_GET['edit']) && $_GET['edit']>0) ? $db->mySQLSafe($_GET['edit']) : $db->mySQLSafe($_GET['clone']);	
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = $repair"); 


	} else {		


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


			$whereClause .= $like;
			if($_GET['category'] > 0 && !empty($_GET['searchStr'])){
				//do nothing
			}else{
			$whereClause .=	" AND";
			}

		}	


		if (isset($_GET['category']) && $_GET['category']>0) {





		$whereClause .= (isset($like)) ? ' AND ' : ' ';





			$whereClause .= "I.cat_id = ".$_GET['category']." AND";


		}


		$query = "SELECT I.name,I.price,I.description,I.productId,I.disabled,C.cat_id,C.cat_name,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_inventory as I INNER JOIN  ".$glob['dbprefix']."ImeiUnlock_category as C ON C.cat_id = I.cat_id  WHERE ".$whereClause. " I.digital = '2' ORDER BY productId ASC";


	}


	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;


	


	// query database


	$results = $db->select($query, $productsperpage, $page);


	$numrows = $db->numrows($query);


	$pagination = paginate($numrows, $productsperpage, $page, "page");


}





require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 


?>





<table width="100%" border="0" cellspacing="0" cellpadding="0">


  <tr>


    <td class="pageTitle">Repair Problems</td>


    <td  align="right">


    <?php


if (!isset($_GET['mode'])) {


	$url = (permission("faq","write") == true) ? 'href="?_g=repair/repair&amp;mode=new&amp;parent='.$_GET['parent'].'" class="txtLink"' : $link401;


	echo sprintf('<span class="right"><a %s><img class="left" src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', "<strong style='margin:4px 4px 0' class='left'>Add New</strong>");


}


?>


    </td>


  </tr>


  <tr>


    <td  height="10">&nbsp;</td>


    <td>&nbsp;</td>


  </tr>


</table>








  





<?php 


if(isset($msg)) echo msg($msg);





if (!isset($_GET['mode']) && !isset($_GET['edit']) && !isset($_GET['clone'])) {


?>


<p class="copyText"><?php echo $lang['admin']['faqs_faqs_listing_desc']; ?></p>





<form name="filter" method="get" action="<?php echo $glob['adminFile']; ?>">


  <input type="hidden" name="_g" value="repair/repair" />


  <p  class="copyText" style="margin-top:5px; margin-bottom:10px">


    <select name="category" class="textbox2">


      <option value="All" <?php if(isset($_GET['category']) && $_GET['category']=="All") echo "selected='selected'"; ?>><?php echo $lang2['admin']['products_all_cats'];?></option>


      <?php echo showCatList_repair(isset($_GET['category']) ? $_GET['category'] : null); ?>


    </select>


    


    


    <input type="text" placeholder="<?php echo $lang2['admin']['products_containing_text'];?>" name="searchStr" class="textbox2" value="<?php if(isset($_GET['searchStr']))echo $_GET['searchStr']; ?>"  style="margin-left:30px;"/>


    <input name="submit" style="margin:0 10px 0 11px" type="submit" class="submit" value="<?php echo $lang2['admin']['products_filter'];?>" />


    <input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=repair/repair');return document.returnValue" value="<?php echo $lang2['admin']['products_reset'];?>" class="submit" />


  </p>


</form>











<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">


<tr>


<td class="tdTitle" width="70" align="center"> Sr.#</td>


<td class="tdTitle">Problem</td>


<td class="tdTitle">Category</td>


<td class="tdTitle">price</td>


<td class="tdTitle" width="135" align="center"><?php echo $lang['admin']['faqs_action']; ?></td>


</tr>


<?php 


if ($results == true) {


	for ($i=0; $i<count($results); $i++){


		$cellColor	= cellColor($i);		


?>	


<tr class="<?php echo $cellColor; ?>">


<td align="center"><?php echo ($i+1)?></td>


<td  id="product_<?php echo $results[$i]['productId']; ?>" >


 <?php echo $results[$i]['name'];?>


</td>


<td>


 <?php echo getproglemtree($results[$i]['cat_name'], $results[$i]['cat_father_id'], $results[$i]['cat_id']);?>


</td>


<td  id="product_<?php echo $results[$i]['productId']; ?>" >


 <?php echo priceFormat($results[$i]['price']);?>


</td>


<td align="center" class="a2">


	<span class="action" >


	<?php


	


		switch($results[$i]['disabled']) {


			case 0:


				$url	= (permission("faq","edit")==true) ? 'href="?_g=repair/repair&amp;disabled=1&amp;productId='.$results[$i]['productId'].'" class="txtLink"' : $link401;


				$title	= $lang['admin']['faqs_faqs_active'];


				break;


			case 1:


				$url	= (permission("faq","edit")==true) ? 'href="?_g=repair/repair&amp;disabled=0&amp;productId='.$results[$i]['productId'].'" class="txtLink"' : $link401;


				$title	= $lang['admin']['faqs_faqs_inactive'];


				break;


		}


		echo sprintf('<a %s style="margin-left:7px;">%s</a>', $url, $title);


	?>


	</span>


	


	<span class="action">	


	<?php


		if (permission("faq","delete")==true) {			


			$url = 'href="javascript:void(0);" onclick="javascript:decision(\''.$lang['admin_common']['delete_q'].'\',\'?_g=repair/repair&amp;delete='.$results[$i]['productId'].'\');" class="txtLink"';			


		} else {


			$url = $link401;


		}


		echo sprintf('<a %s style="margin-left:7px;">%s</a>', $url, $lang['admin_common']['delete']);


	?>


	</span>


	<span class="action">
    
      <a <?php if(permission("repair","edit")==TRUE){ ?>href="?_g=repair/repair&amp;clone=<?php echo $results[$i]['productId']; ?>" class="txtLink"<?php } else { echo $link401; } ?> style="margin-left:7px;">Clone</a>
    </span>


	<span class="action" >


	<?php


		$url = (permission("faq","edit")==true) ? 'href="?_g=repair/repair&amp;edit='.$results[$i]['productId'].'" class="txtLink"' : $link401;


		echo sprintf('<a %s style="margin-left:7px;">%s</a>', $url, $lang['admin_common']['edit']);


	?></span>
<span class="action" >
    
     <a <?php if(permission("products","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/languages&amp;prod_master_id=<?php echo $results[$i]['productId']; ?>" class="txtLink"<?php } else { echo $link401; } ?> style="margin-left:7px;">Languages</a>
    </span>

	


	


  


</td>


</tr>

















<?php


	}  // end loop ?>


	 


    <?php


	   


} else {


?>


<tr>


<td colspan="3">Sorry, no data found.</td>


</tr>


<?php } ?>


</table> 





<script type="text/javascript">


	//Sortable.create('reordertestimonials', {ghosting:true,constraint:false,tag:'div',only:'productRow'});


</script>


<p class="copyText" align="right"><span class="pagination"><?php echo $pagination; ?></span></p>








<?php 


} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0 || $_GET["clone"]>0){  





if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 

if (isset($_GET["clone"]) && $_GET["clone"]>0) {unset($results[0]['productCode'], $results[0]['productId']);}
?>





<form action="?_g=repair/repair" method="post" enctype="multipart/form-data" name="form1">


<table border="0" cellspacing="1" cellpadding="3" class="mainTable " width="100%">


  <tr>


    <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> Problem</td>


  </tr>


  <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Problem:</td>


    <td width="83%">


      <input name="name" type="text" class="textbox" value="<?php if(isset($results[0]['name'])) echo validHTML($results[0]['name']); ?>" maxlength="1000" />    </td>


  </tr>


  		 <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Price:</td>


    <td width="83%">


      <input name="price" type="text" class="textbox" value="<?php if(isset($results[0]['price'])) echo validHTML($results[0]['price']); ?>" maxlength="1000" />    </td>


  </tr>


    <tr>


    <td class="tdText" style="font-weight:bold;">Description</td>


    <td>&nbsp;</td>


  </tr>


  <tr>


    <td colspan="2" class="tdText">


	<?php


	


		require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");


		$oFCKeditor = new FCKeditor('FCKeditor');


		$oFCKeditor->BasePath = $glob['rootRel'].$glob['adminFolder'].'/includes/rte/' ;


		$oFCKeditor->Value = (isset($results[0]['description'])) ? $results[0]['description'] : $oFCKeditor->Value = "";


		if (!$config['richTextEditor']) $oFCKeditor->off = true;


		$oFCKeditor->Create();





?>


</td>


    </tr>	


    <tr>


      <td width="25%" align="left" valign="top" class="tdText"><strong><?php echo "Image";?></strong> <br />


       </td>


      


      <td><input type="file" name="primaryImage" class="textbox" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />


        <input type="hidden" name="oldimage" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />


        <?php 


	if(!empty($results[0]['image'])) { 


		$imgSrc = imgPath($results[0]['image'],'thumb',$path="rel");


	} else {


		$imgSrc = $GLOBALS['storeURL']."/images/general/nophoto.gif";


	}


	?>


        <br />


        <br />


         <br />


        <img src="<?php echo $imgSrc; ?>" alt="" id="previewImage" title="" /></td>


    </tr>


	    <tr>


      <td  ><strong><?php echo "category:";?></strong></td>


      <td >


      <select name="cat_id" class="textbox" >


          <?php echo showCatList_repair($results[0]['cat_id']);


		  


		  


		  


		   ?>


        </select>


      </td>


    </tr>


  <tr>


    <td class="tdText"><?php echo $lang['admin']['faqs_faqs_status'];?></td>


    <td>


	  <input name="disabled" type="checkbox" value="1" <?php if(isset($results[0]['disabled']) && $results[0]['disabled'] == 1) { ?> checked="checked" <?php }?> />


	  </td>


  </tr>


  


   <tr>


    <td>&nbsp;</td>


    <td>


	<input type="hidden" name="productId" value="<?php echo $results[0]['productId']; ?>" />


	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>


  </tr>


  </table>


</form>


<?php 


} 





?>