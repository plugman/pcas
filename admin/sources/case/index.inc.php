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

	$update			= $db->update($glob['dbprefix']."ImeiUnlock_case_devices", $record, $where);

		

	$msg	= ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_update_fail']."</p>";





} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {
	

	$where = "id=".$db->mySQLSafe($_GET["delete"]);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_case_devices", $where);

	

	$msg = ($delete == true) ? "<p class='infoText'>".$lang['admin']['categories_delete_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_delete_failed']."</p>";

	## Rebuild the cached list


	

} else if (isset($_POST['id'])) {

	$record["name"] = $db->mySQLSafe($_POST['name']);		

	if (is_numeric($_POST['id'])) {
		

		$where = "id=".$db->mySQLSafe($_POST['id']);

		$update = $db->update($glob['dbprefix']."ImeiUnlock_case_devices", $record, $where);

		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_update_fail']."</p>";

	} else {		

		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_case_devices", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['name']."' ".$lang['admin']['categories_add_success']."</p>" : "<p class='warnText'>".$lang['admin']['categories_add_failed']."</p>";

		

	}

}




if (!isset($_GET['mode'])) {

	if (isset($_GET['edit']) && $_GET['edit'] > 0) {

		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_case_devices WHERE id = %s", $db->mySQLSafe($_GET['edit'])); 

	}else{
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_case_devices"; 
	}

		

	// query database

	$results = $db->select($query);

}



require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 
?>
<div class="maindiv" style="margin-bottom:10px">
<?php

if (!isset($_GET['mode'])) {

	$url = (permission("case","write") == true) ? 'href="?_g=case/index&amp;mode=new" class="txtLink"' : $link401;

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

	echo '<a href="?_g=case/models&amp;device='.$results[$i]['id'].'" class="txtLink" title="View Models">'.$results[$i]['name'].'</a>' ;

  ?>

  </span>

 

  <div class="action2" style="width:300px;">

    

    <span class="right3" style="width:70px;">

<?php

		$url = (permission("case","edit"))? 'href="?_g=case/models&amp;device='.$results[$i]['id'].'&amp;mode=new" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, "Add Models");

	?></span>



    <span class="right3">

	<?php

		$url = (permission("case","edit")) ? 'href="?_g=case/index&parent=device&device=1&amp;edit='.$results[$i]['id'].'" class="txtLink"' : $link401;

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['edit']);

	?>

    </span>

    <span class="right3">

	<?php

		if (permission("case","delete")) {


				$url = 'href="?_g=case/index&amp;delete='.$results[$i]['id'].'" onclick=" return confirm(\''.str_replace("\n", '\n', $lang['admin_common']['delete_q']).'\');" class="txtLink"';

		} 

		echo sprintf('<a %s>%s</a>', $url, $lang['admin_common']['delete']);

	?>

    </span>

    <span class="right3">

  	<?php

		switch($results[$i]['hide']) {

			case 0:

				$url	= (permission("case","edit")) ? 'href="?_g=case/index&amp;hide=1&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;

				$title	= $lang['admin_common']['hide'];

				break;

			case 1:

				$url	= (permission("case","edit")) ? 'href="?_g=case/index&amp;hide=0&amp;id='.$results[$i]['id'].'" class="txtLink"' : $link401;

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

<form action="?_g=case/index" method="post" enctype="multipart/form-data" name="form1">

<div class="headingBlackbg" style="margin-top:30px;"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?> Device</div>

<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">

 

  <tr>

    <td width="20%" class="tdText">Device Name</td>

    <td>

      <input name="name" type="text" class="textbox" value="<?php if(isset($results[0]['name'])) echo $results[0]['name']; ?>" maxlength="255" />   
      </td>

  </tr>

  

  <tr>

    <td>&nbsp;</td>

    <td>
<input type="hidden" name="id" value="<?php echo $results[0]['id']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php echo $modeTxt; ?>" /></td>

  </tr>

  

  


   

  </table>



</form>

<?php 

} 
?>