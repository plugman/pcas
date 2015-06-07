<?php

/*

+--------------------------------------------------------------------------

|	index.inc.php

|   ========================================

|	Manage Categories

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



$lang = getLang("admin".CC_DS."admin_categories.inc.php");



permission("categories", "read", true);



$catsPerPage = 25;




if (isset($_GET['hide'])) {	

	$record['hide']	= sprintf("'%d'", $_GET['hide']);

	$where			= "id=".$db->mySQLSafe($_GET['id']);

	$update			= $db->update($glob['dbprefix']."ImeiUnlock_case_models", $record, $where);

		

	$msg	= ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_update_fail']."</p>";





} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {
	

	$where = "id=".$db->mySQLSafe($_GET["delete"]);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_case_models", $where);

	

	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['categories_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_delete_failed']."</p>";

	## Rebuild the cached list


	

} else if (isset($_POST['id'])) {

	$record["name"] = $db->mySQLSafe($_POST['name']);	
	$record["isfot"] = $db->mySQLSafe($_POST['isfot']);	
	$record["device_id"] = $db->mySQLSafe($_POST['device_id']);	
	$record["width"] = $db->mySQLSafe($_POST['width']);
	$record["height"] = $db->mySQLSafe($_POST['height']);
	$record["ble_width"] = $db->mySQLSafe($_POST['ble_width']);
	$record["ble_height"] = $db->mySQLSafe($_POST['ble_height']);
	$record["price"] = $db->mySQLSafe($_POST['price']);		
	if ($_FILES['icon']['name'] != ""){


				$path_parts2 	= pathinfo($_FILES['icon']['name']);


				$ext 		 	= ".".$path_parts2['extension'];


				$iconname		= date("jnYHis")."_1".$ext ;


				


				 $iconpath		= CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'smallimages'.CC_DS.$iconname;

				
				if (!move_uploaded_file($_FILES['icon']['tmp_name'], $iconpath)){


						$Flash_Type_Error = true;


					}

					
					if(file_exists($iconpath)){

					 $oldrootMasterFile = CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'smallimages'.CC_DS.$_POST['oldicon'];
					 

					if(file_exists($oldrootMasterFile)){


						@unlink($oldrootMasterFile);


					}		
		



								


				}

			$record['icon']	= $db->mySQLSafe($iconname);	
			}
		if ($_FILES['image']['name'] != ""){


				$path_parts2 	= pathinfo($_FILES['image']['name']);


				$ext 		 	= ".".$path_parts2['extension'];


				$mainimage		= date("jnYHis")."_1".$ext ;


				


				 $imagepath		= CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'mainimage'.CC_DS.$mainimage;

				
				if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagepath)){


						$Flash_Type_Error = true;


					}

					
					if(file_exists($imagepath)){

					 $oldrootMasterFile = CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'mainimage'.CC_DS.$_POST['oldimage'];
					 

					if(file_exists($oldrootMasterFile)){


						@unlink($oldrootMasterFile);


					}		
	


				}

			$record['image']	= $db->mySQLSafe($mainimage);	
			}
			if ($_FILES['imagebg']['name'] != ""){


				$path_parts2 	= pathinfo($_FILES['imagebg']['name']);


				$ext 		 	= ".".$path_parts2['extension'];


				$bgimage		= date("jnYHis")."_1".$ext ;


				


				 $imagepath		= CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'bgimage'.CC_DS.$bgimage;

				
				if (!move_uploaded_file($_FILES['imagebg']['tmp_name'], $imagepath)){


						$Flash_Type_Error = true;


					}

					
					if(file_exists($imagepath)){

					 $oldrootMasterFile = CC_ROOT_DIR.CC_DS.'images'.CC_DS.'uploads'.CC_DS.'casecustomization'.CC_DS.'bgimage'.CC_DS.$_POST['oldimagebg'];
					 

					if(file_exists($oldrootMasterFile)){


						@unlink($oldrootMasterFile);


					}		
	


				}

			$record['imagebg']	= $db->mySQLSafe($bgimage);	
			}
	if (is_numeric($_POST['id'])) {
		

		$where = "id=".$db->mySQLSafe($_POST['id']);

		$update = $db->update($glob['dbprefix']."ImeiUnlock_case_models", $record, $where);

		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_update_fail']."</p>";

	} else {		

		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_case_models", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_add_failed']."</p>";

		

	}

}




if (!isset($_GET['mode'])) {

	if (isset($_GET['edit']) && $_GET['edit'] > 0) {

		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE id = %s", $db->mySQLSafe($_GET['edit'])); 

	}else{
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_case_models WHERE device_id = %s", $db->mySQLSafe($_GET['device'])); 
	}

		

	// query database

	$results = $db->select($query);

}



require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<div class="maindiv" style="margin-bottom:10px">
<?php

if (!isset($_GET['mode'])) {

	$url = (permission("case","write") == true) ? 'href="?_g=case/models&amp;device='.$_GET['device'].'&amp;mode=new" class="txtLink"' : $link401;

	echo sprintf('<span style="float: right;" ><a %s><img src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', $lang['admin_common']['add_new']);

}

?>



</div>

<div class="clear"></div>

<?php 

if(isset($msg)) echo msg($msg);



if (!isset($_GET['mode']) && !isset($_GET['edit'])) {

?>

<p class="copyText">Below is a list of all the current Devices in the database.</p>





<form method="post" id="reorderCategory" enctype="multipart/form-data">

<div class="wbox">

<div class="headingBlackbg2" >

   <span class="catid">ID</span>

  <span class="catName2" style="width:340px;">Device Name</span>

  <span class="action2"> Action</span>

</div>



<?php 

if ($results == true) {

	$count = count($results)-1;

	foreach ($results as $i => $result) {

		$cellColor	= cellColor($i);

		

?>	

<div id="product_<?php echo $results[$i]['id']; ?>" class="productRow <?php echo $cellColor; ?> tdText">

<span class="catid">

  <? echo $results[$i]['id']; ?>

  </span>

  <span class="catName2" style="width:340px;">

  <?php

	echo '<a href="?_g=case/layout&amp;device='.$results[$i]['id'].'" class="txtLink" title="View Models">'.$results[$i]['name'].'</a>' ;

  ?>

  </span>

 

  <div class="action2" style="width:300px;">

    
<span class="right3" style="width:70px;">

<?php

		$url = (permission("case","edit"))? 'href="?_g=case/casemodels&amp;device='.$results[$i]['id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, "Case Types");

	?></span>
    <span class="right3" style="width:70px;">

<?php

		$url = (permission("case","edit"))? 'href="?_g=case/layout&amp;device='.$results[$i]['id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, "Add Layout");

	?></span>



    <span class="right3">

	<?php

		$url = (permission("case","edit")) ? 'href="?_g=case/models&amp;device='.$_GET['device'].'&amp;edit='.$results[$i]['id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);

	?>

    </span>

    <span class="right3">

	<?php

		if (permission("case","delete")) {


				$url = 'href="?_g=case/models&amp;device='.$_GET['device'].'&amp;delete='.$results[$i]['id'].'" onclick=" return confirm(\''.str_replace("\n", '\n', $lang['admin_common']['delete_q']).'\');" class="txtLink"';

		} 

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['delete']);

	?>

    </span>

    <span class="right3">

  	<?php

		switch($results[$i]['hide']) {

			case 0:

				$url	= (permission("case","edit")) ? 'href="?_g=case/models&amp;device='.$_GET['device'].'&amp;hide=1&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;

				$title	= $lang['admin_common']['hide'];

				break;

			case 1:

				$url	= (permission("case","edit")) ? 'href="?_g=case/models&amp;device='.$_GET['device'].'&amp;hide=0&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;

				$title	= $lang['admin_common']['show'];

				break;

		}

		echo sprintf('<a %s>%s</a>', $url, $title);

	?>

    </span>



 

  </div>


</div>

<?php

	} // end loop

	?>

    </div>

    <?php

} else {

?>

  <div class="tdText">No Data Exit</div>

<?php } ?>

</form>



<?php 

}else if ($_GET["mode"]=="new"  || $_GET["edit"] > 0){  



if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = $lang['admin_common']['edit']; } else { $modeTxt = $lang['admin_common']['add']; } 

?>

<form action="?_g=case/models&amp;device=<?php echo $_GET['device']; ?>" method="post" enctype="multipart/form-data" name="form1">

<div class="headingBlackbg" style="margin-top:30px;"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> Device</div>

<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">

 

  <tr>

    <td width="20%" class="tdText">Device Name</td>

    <td>

      <input name="name" type="text" class="textbox" value="<?php if(isset($results[0]['name'])) echo $results[0]['name']; ?>" maxlength="255" />   
      </td>

  </tr>
<tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Price:</td>


    <td width="83%">


      <input name="price" type="text" class="textbox" value="<?php if(isset($results[0]['price'])) echo validHTML($results[0]['price']); ?>" maxlength="1000" />    </td>


  </tr>
  <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Design Width:</td>


    <td width="83%">


      <input name="width" type="text" class="textbox" value="<?php if(isset($results[0]['width'])) echo validHTML($results[0]['width']); ?>" maxlength="1000" /> &nbsp;mm    </td>


  </tr>
  <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Design height:</td>


    <td width="83%">


      <input name="height" type="text" class="textbox" value="<?php if(isset($results[0]['height'])) echo validHTML($results[0]['height']); ?>" maxlength="1000" />&nbsp; mm    </td>


  </tr>
  <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Bleeding Width:</td>


    <td width="83%">


      <input name="ble_width" type="text" class="textbox" value="<?php if(isset($results[0]['ble_width'])) echo validHTML($results[0]['ble_width']); ?>" maxlength="1000" /> &nbsp;mm    </td>


  </tr>
  <tr>


    <td width="17%" class="tdText" style="font-weight:bold;">Bleeding height:</td>


    <td width="83%">


      <input name="ble_height" type="text" class="textbox" value="<?php if(isset($results[0]['ble_height'])) echo validHTML($results[0]['ble_height']); ?>" maxlength="1000" />&nbsp; mm    </td>


  </tr>
  <tr>


      <td width="25%" align="left" valign="top" class="tdText"><strong>Small Icon</strong> <br />


       </td>


      


      <td><input type="file" name="icon" class="textbox" value="<?php if(isset($results[0]['icon'])) echo $results[0]['icon']; ?>" />


        <input type="hidden" name="oldicon" value="<?php if(isset($results[0]['icon'])) echo $results[0]['icon']; ?>" />


        <?php 


	if(!empty($results[0]['icon'])) { 


		$imgSrc = imgPath($results[0]['icon'],'',$path="smallicon");


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


      <td width="25%" align="left" valign="top" class="tdText"><strong>Main Image</strong> <br />


       </td>


      


      <td><input type="file" name="image" class="textbox" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />


        <input type="hidden" name="oldimage" value="<?php if(isset($results[0]['image'])) echo $results[0]['image']; ?>" />


        <?php 


	if(!empty($results[0]['image'])) { 


		$imgSrc = imgPath($results[0]['image'],'',$path="pngimage");


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


      <td width="25%" align="left" valign="top" class="tdText"><strong>Background Image</strong> <br />


       </td>


      


      <td><input type="file" name="imagebg" class="textbox" value="<?php if(isset($results[0]['imagebg'])) echo $results[0]['imagebg']; ?>" />


        <input type="hidden" name="oldimagebg" value="<?php if(isset($results[0]['imagebg'])) echo $results[0]['imagebg']; ?>" />


        <?php 


	if(!empty($results[0]['imagebg'])) { 


		$imagebg = imgPath($results[0]['imagebg'],'',$path="bgimage");


	} else {


		$imagebg = $GLOBALS['storeURL']."/images/general/nophoto.gif";


	}


	?>


        <br />


        <br />


         <br />


        <img src="<?php echo $imagebg; ?>" alt="" id="previewImage" title="" /></td>


    </tr>
<tr>
      <td class="tdText"><strong>List in Footer</strong></td>
      <td><input name="isfot"  type="checkbox" class="checkbox" value="1" <?php if($results[0]['isfot'] == 1) {?> checked="checked" <?php } ?>  />      </td>
    </tr>
  <tr>

    <td>&nbsp;</td>

    <td>
<input type="hidden" name="id" value="<?php echo $results[0]['id']; ?>" />
<input type="hidden" name="device_id" value="<?php echo $_GET['device']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>

  </tr>

  

  


   

  </table>



</form>

<?php 

} 
?>