<?php

/*

+--------------------------------------------------------------------------

|   Cub3Cart 4

|   ========================================

|	

|   

|   

|   5 Bridge Street,

|   Bishops Stortford,

|   HERTFORDSHIRE.

|   CM23 2JU

|   UNITED KINGDOM

|   http://www.d.e.v.e.l.l.i.o.n.com

|	

|   ========================================

|   Web: http://www.c.u.b.e.c.a.r.t.com

|   Email: info (at) c.u.b.e.c.a.r.t (dot) com

|	  License Type: C.u.b.e.C.a.r.t is NOT Open Source Software and Limitations Apply 

|   Licence Info: http://www.c.u.b.e.c.a.r.t.com/site/faq/license.php

+--------------------------------------------------------------------------

|	index.inc.php

|   ========================================

|	Manage Product Reviews/Comments

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



$lang = getLang("admin".CC_DS."admin_reviews.inc.php");

require_once($backPath."includes".CC_DS."functions.inc.php");



permission("reviews","read",$halt=TRUE);



if(isset($_POST['edit']) && $_POST['edit']>0){

	

	$data['productId'] = $db->mySQLSafe($_POST['productId']);

	$data['name'] = $db->mySQLSafe($_POST['name']);

	$data['email'] = $db->mySQLSafe($_POST['email']);

	$data['title'] = $db->mySQLSafe($_POST['title']);

	

	//Filteration of Abusive words here :: 

	//Function return array; 0 index contains isBad bit and 1 index contains modified comment

	$result_arr 	= abusive_filteration( $_POST['review'] , "***" );

	

	$data['review'] = $db->mySQLSafe($result_arr[1]);

	$data['isBad'] 	= $db->mySQLSafe($result_arr[0]);

	if(isset($_POST['rating_val']))

	{

		$data['rating'] = $db->mySQLSafe($_POST['rating_val']);

	}

	$data['approved'] = $db->mySQLSafe($_POST['approved']);

	

	$update = $db->update($glob['dbprefix']."ImeiUnlock_reviews",$data,"id=".$_POST['edit']);

	

	if($update==TRUE){

		$msg = "<p class='infoText'>".$lang['admin']['reviews_update_success']."</p>";

	} else {

		$msg = "<p class='warnText'>".$lang['admin']['reviews_update_fail']."</p>";

	}



}



if($_GET['delete']>0){

	

	$where = "id=".$db->mySQLSafe($_GET["delete"]);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_reviews", $where);

	

	if($delete==TRUE){

		$msg = "<p class='infoText'>".sprintf($lang['admin']['reviews_deleted'],$_GET['delete'])."</p>";

	}	

}

if (is_numeric($_GET['approved']) && $_GET['id']>0){

	$where = "id=".$db->mySQLSafe($_GET['id']);

	$record['approved'] = $db->mySQLSafe($_GET['approved']);

	$update = $db->update($glob['dbprefix']."ImeiUnlock_reviews", $record, $where);

	

	if($_GET['approved']==1){

		$msg = "<p class='infoText'>".sprintf($lang['admin']['reviews_update_published'],$_GET['id'])."</p>";

	} else {

		$msg = "<p class='infoText'>".sprintf($lang['admin']['reviews_update_unpublished'],$_GET['id'])."</p>";

	}	

}





if($_GET['edit']>0) {

	

	$query = "SELECT ".$glob['dbprefix']."ImeiUnlock_inventory.name as `prodName`, `id`, `approved`, ".$glob['dbprefix']."ImeiUnlock_reviews.productId, `type`, `rating`, ".$glob['dbprefix']."ImeiUnlock_reviews.name, `email`, `title`, `review`, `ip`, `time` FROM ".$glob['dbprefix']."ImeiUnlock_reviews INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory ON ".$glob['dbprefix']."ImeiUnlock_reviews.productId = ".$glob['dbprefix']."ImeiUnlock_inventory.productId WHERE `id`=".$db->mySQLSafe($_GET['edit']);

	

} else {



	// get comments / reviews

	if(isset($_GET['column']) && isset($_GET['direction'])) {

		$orderBy = $_GET['column']." ".$_GET['direction'];

	} else {

		$orderBy = "time DESC";

	}

	

	if(isset($_GET['productId']) && $_GET['productId']>0) {

		$whereExtra = "WHERE ".$glob['dbprefix']."ImeiUnlock_reviews.productId = ".$_GET['productId'];

	} elseif(isset($_GET['searchStr']) && !empty($_GET['searchStr'])) {

		$whereExtra = "WHERE `review` LIKE '%".$_GET['searchStr']."%' OR `title` LIKE '%".$_GET['searchStr']."%'";

	}

	

	$query = "SELECT ".$glob['dbprefix']."ImeiUnlock_inventory.name as `prodName`, `id`, `approved`, ".$glob['dbprefix']."ImeiUnlock_reviews.productId, `type`, `rating`, ".$glob['dbprefix']."ImeiUnlock_reviews.name, isBad, `email`, `title`, `review`, `ip`, `time`,".$glob['dbprefix']."ImeiUnlock_reviews.productId FROM ".$glob['dbprefix']."ImeiUnlock_reviews INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory ON ".$glob['dbprefix']."ImeiUnlock_reviews.productId = ".$glob['dbprefix']."ImeiUnlock_inventory.productId ".$whereExtra." ORDER BY ".$orderBy;



}



if(isset($_GET['page'])){

	$page = $_GET['page'];

} else {

	$page = 0;

}

// query database

$reviewsPerPage = 20;



$results = $db->select($query, $reviewsPerPage, $page);

$numrows = $db->numrows($query);

$pagination = paginate($numrows, $reviewsPerPage, $page, "page");



if($results==FALSE){

$msg = "<p class='warnText'>".$lang['admin']['reviews_no_reviews']."</p>";

}



require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 



if(isset($msg)){ 

	echo msg($msg); 

}

?>

<p class="pageTitle"><?php echo $lang['admin']['reviews_page_title']; ?></p>

<?php

if(permission("reviews","edit")==TRUE && isset($_GET['edit']) && $results==TRUE){

?> 

<form action="<?php echo $glob['adminFile']; ?>?_g=reviews/index" target="_self" method="post" enctype="multipart/form-data">

<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">

  <tr>

    <td class="tdTitle" colspan="2"><?php echo $lang['admin']['reviews_edit_below']; ?></td>

  </tr>

  <tr>

    <td class="tdText"><strong><?php echo $lang['admin']['reviews_review_of']; ?></strong></td>

    <td>

	<select name="productId" class="textbox">

	<?php

	$products = $db->select("SELECT productId, name FROM ".$glob['dbprefix']."ImeiUnlock_inventory");

	

	for($n=0;$n<count($products);$n++){

	?>

	<option value="<?php echo $products[$n]['productId']; ?>" <?php if($results[0]['productId']==$products[$n]['productId']) { echo "selected='selected'"; } ?>><?php echo $products[$n]['name']; ?></option>

	<?php

	}

	?>

	</select>

	</td>

  </tr>

  <tr>

    <td class="tdText"><strong><?php echo $lang['admin']['reviews_author_name'];?></strong></td>

    <td><input type="textbox" name="name" value="<?php echo stripslashes($results[0]['name']); ?>" class="textbox" /></td>

  </tr>

  <tr>

    <td class="tdText"><strong><?php echo $lang['admin']['reviews_author_email'];?></strong></td>

    <td><input type="textbox" name="email" value="<?php echo $results[0]['email']; ?>" class="textbox" /></td>

  </tr>

  <tr>

    <td class="tdText"><strong><?php echo $lang['admin']['reviews_title'];?></strong></td>

    <td><input type="textbox" name="title" value="<?php echo stripslashes($results[0]['title']); ?>" class="textbox" /></td>

  </tr>

  <tr>

    <td class="tdText" valign="top"><strong><?php echo $lang['admin']['reviews_review'];?></strong> <br />

    <?php echo $lang['admin']['reviews_no_html'];?></td>

    <td><textarea name="review" cols="40" rows="5"><?php echo stripslashes($results[0]['review']); ?></textarea></td>

  </tr>

  <?php

  if($results[0]['type']==0){

  ?>

  <tr>

    <td class="tdText">

	<span style="float: right;">

	<?php echo "<img src='".$GLOBALS['rootRel']."images/general/px.gif' name='star0' width='15' height='15' id='star0' onmouseover='stars(0,\"".$glob['adminFolder']."/images/rating/\");' style='cursor: pointer; cursor: hand;' />\n"; ?>

	</span>

	<strong><?php echo $lang['admin']['reviews_rating'];?></strong></td>

    <td>

	<?php

		

	for($j=0;$j<5;$j++) 

	{

		echo "<img src='".$glob['adminFolder']."/images/rating/".starImg($j,$results[0]['rating']).".gif' name='star".($j+1)."' width='15' height='15' id='star".($j+1)."' onmouseover='stars(".($j+1).",\"".$glob['adminFolder']."/images/rating/\");' style='cursor: pointer; cursor: hand;' />\n";

	}

	

	?>

	<input type="hidden" value="<?php echo $results[0]['rating']; ?>" name="rating_val" id="rating_val" />

	</td>

  </tr>

  <?php

  }

  ?>

  

  <tr>

    <td class="tdText"><strong><?php echo $lang['admin']['reviews_status'];?></strong></td>

	<td>

	<select name="approved" class="textbox">

		<option value="0" <?php if($results[0]['approved']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['reviews_unpublished'];?></option>

		<option value="1" <?php if($results[0]['approved']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['reviews_published'];?></option>

	</select>

	</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td>

	<input type="hidden" name="edit" value="<?php echo $results[0]['id']; ?>" />

	<input name="submit" type="submit" value="<?php echo $lang['admin']['reviews_btn_update'];?>" class="submit" />

	</td>

  </tr>

</table>

</form>

<?php

} else {

?>

<form action="<?php echo $glob['adminFile']; ?>" target="_self" method="get" class="tdText" style="margin:10px 0"><?php echo $lang['admin']['reviews_order_by']; ?> 

<input type="hidden" name="_g" value="reviews/index" />

<select name="column" class="textbox2">

	<option value="name" <?php if($_GET['column']=="name") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_name']; ?></option>

	<option value="email" <?php if($_GET['column']=="email") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_email']; ?></option>

	<option value="title" <?php if($_GET['column']=="title") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_title']; ?></option>

	<option value="time" <?php if($_GET['column']=="time") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_date']; ?></option>

	<option value="prodName" <?php if($_GET['column']=="prodName") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_prod_name']; ?></option>

	<option value="rating" <?php if($_GET['column']=="rating") echo "selected='selected'" ?>><?php echo $lang['admin']['reviews_filter_rating']; ?></option>

    <option value="isBad" <?php if($_GET['column']=="isBad") echo "selected='selected'" ?>>Abusive Comments</option>

</select>

<select name="direction" class="textbox2" style="margin:0 10px;">

	<option value="ASC" <?php if($_GET['direction']=="ASC") { echo "selected = 'selected'"; } ?>><?php echo $lang['admin']['reviews_filder_asc']; ?></option>

	<option value="DESC" <?php if($_GET['direction']=="DESC") { echo "selected = 'selected'"; } ?>><?php echo $lang['admin']['reviews_filder_desc']; ?></option>

</select>

<?php echo $lang['admin']['reviews_cont_text']; ?> <input name="searchStr" type="text" class="textbox2" value="<?php echo sanitizeVar($_GET['searchStr']); ?>" style="float:none" />

<input name="submit" type="submit" value="<?php echo $lang['admin']['reviews_filter_go']; ?>" class="submit" />



<input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=reviews/index');return document.returnValue" value="<?php echo $lang['admin']['reviews_filter_reset']; ?>" class="submit" />

</form>

</p>

<p class="copyText" style="text-align: right;"><?php echo $pagination; ?></p>



  <?php 

  if($results==TRUE){

	  

	for($i=0;$i<count($results);$i++){

	$cellColor = "";

	$cellColor = cellColor($i);

  ?>

	<div class="<?php echo $cellColor;?> tdText" style="border: 1px black solid; margin-bottom: 10px;">

	<p style="padding: 3px; margin: 0px;">

	<span style="float: right;">

	<?php

	if($results[$i]['type']==0) {

		for($j=0;$j<5;$j++) 

		{

				

			echo "<img src='".$glob['adminFolder']."/images/rating/".starImg($j,$results[$i]['rating']).".gif' />\n";	

		}

	}

	?></span>

	<strong><?php echo $lang['admin']['reviews_name_2']; ?></strong> <?php echo $results[$i]['name']; ?> | <strong><?php echo $lang['admin']['reviews_email_2']; ?></strong> <a href="mailto:<?php echo $results[$i]['email']; ?>" class="txtLink"><?php echo $results[$i]['email']; ?></a>	

	|  <strong><?php echo $lang['admin']['reviews_ip']; ?></strong> <a href="javascript:;" class="txtLink" onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=misc/lookupip&amp;ip=<?php echo $results[$i]['ip']; ?>','misc',300,130,'yes,resizable=yes')"><?php echo $results[$i]['ip']; ?></a> </p>

    <p style="padding: 3px; margin: 0px;">

<span style="font-weight:bold; text-transform:uppercase;"><?php echo $results[$i]['title']; ?></span>

</p>

<?

	if($results[$i]['isBad'] == 1)

	{

?>

        <p style="padding: 3px; margin: 0px;">

            <span style="float: right;">

            <img src="<?=$glob['adminFolder']?>/images/bad_comment.png" border="0" title="Found bad comment." />

            </span>

        </p>  

<?

	}

?>

<p style="padding: 3px; margin: 0px;">&quot;<?php echo $results[$i]['review']; ?>&quot;</p>

<p style="border-top: 1px black solid; padding: 2px; margin: 0px; font-size:10px">

<span style="float: right;">

	<?php 

	$currentPage = currentPage();

	

	if($results[$i]['approved']==1){ 

	?>

	<a <?php if(permission("reviews","edit")==TRUE) { ?>href="javascript:decision('<?php echo $lang['admin_common']['sure_q'];?>','<?php echo $currentPage; ?>&amp;approved=0&amp;id=<?php echo $results[$i]['id']; ?>');"<?php } else { echo $link401; } ?> class="txtRed"><?php echo $lang['admin']['reviews_unpublish'];?></a>

	<?php

	} else {

	?> 

	<a <?php if(permission("reviews","edit")==TRUE) { ?>href="javascript:decision('<?php echo $lang['admin_common']['sure_q'];?>','<?php echo $currentPage; ?>&amp;approved=1&amp;id=<?php echo $results[$i]['id']; ?>');"<?php } else { echo $link401; } ?> class="txtGreen"><?php echo $lang['admin']['reviews_publish'];?></a>

	<?php

	} 

	?>

	/ 

	<a <?php if(permission("reviews","edit")==TRUE) { ?>href="<?php echo $glob['adminFile']; ?>?_g=reviews/index&amp;edit=<?php echo $results[$i]['id'];?>"<?php } else { echo $link401; } ?>  class="txtLink"><?php echo $lang['admin_common']['edit'];?></a> 

	/ 

	<a <?php if(permission("reviews","delete")==TRUE) { ?>href="javascript:decision('<?php echo $lang['admin_common']['delete_q'];?>','<?php echo $glob['adminFile']; ?>?_g=reviews/index&amp;delete=<?php echo $results[$i]['id'];?>');"<?php } else { echo $link401; } ?> class="txtLink"><?php echo $lang['admin_common']['delete'];?></a>

	</span>

	

<strong><?php echo $lang['admin']['reviews_date_2']; ?></strong> <?php echo formatTime($results[$i]['time']); ?>

   | <strong><?php echo $lang['admin']['reviews_type']; ?></strong> <?php if($results[$i]['type']==1) { echo $lang['admin']['reviews_type_comment']; } else { echo $lang['admin']['reviews_type_review']; } ?>

   | <strong><?php echo $lang['admin']['reviews_product'];?></strong> <a href="<?php echo $glob['adminFile']; ?>?_g=products/index&edit=<?php echo $results[$i]['productId']; ?>" class="txtLink"><?php echo $results[$i]['prodName']; ?></a></p>

</div>

  <?php

  	} 

  }

  ?>

<p class="copyText" style="text-align: right;"><?php echo $pagination; ?></p>

<?php

}

?>