<?php

/*

|	index.inc.php

|   ========================================

|	Manage Categories

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



//$lang = getLang("admin".CC_DS."admin_categories.inc.php");



permission("images","read",$halt=TRUE);



$catsPerPage = 50;



## SET BANNER WIDTH AND HEIGHT HERE

$req_banner_width	= 1330;

$req_banner_height	= 422;



if (isset($_POST['saveOrder']) && !empty($_POST['priority'])) {

	foreach ($_POST['priority'] as $index => $img_id) {

		

		

		$sql = sprintf("UPDATE ImeiUnlock_flashbanner SET priority = '%s' WHERE img_id = '%d' LIMIT 1;", $index+1, $img_id);

		$db->misc($sql);

	}

	$cache = new cache();

	$cache->clearCache();

}



if (isset($_GET['hide'])) {

	$cache = new cache();

	$cache->clearCache();

	

	$record['img_status'] = $_GET['hide'];

	$where = "img_id=".$db->mySQLSafe($_GET['img_id']);

	$update = $db->update("ImeiUnlock_flashbanner", $record, $where);

		

	$msg = ($update == true) ? "<p class='infoText'>'".$_POST['img_title']."' Updated successfully</p>" : "<p class='warnText'>Updated failed</p>";



} else if (isset($_GET["delete"]) && $_GET["delete"]>0) {

	$cache = new cache();

	$cache->clearCache();

	

	$where = "img_id=".$db->mySQLSafe($_GET["delete"]);

	$rsVideo = $db->select("SELECT * FROM ImeiUnlock_flashbanner WHERE ".$where);

	//@unlink("images/uploads/flashbanner/".$rsVideo[0]["vid_file"]);

	@unlink("images/uploads/flashbanner/".$rsVideo[0]["img_file"]);

	$delete = $db->delete("ImeiUnlock_flashbanner", $where);

	

	$msg = ($delete == true) ? "<p class='infoText'>Deleted successfully</p>" : "<p class='warnText'>Deleted failed</p>";



} else if (isset($_POST['img_id'])) {



	$cache = new cache();

	$cache->clearCache();



	$record["img_title"] 	= $db->mySQLSafe($_POST['img_title']);

	$record["img_link"] 		= $db->mySQLSafe($_POST['img_link']);

	$record["img_status"] 	= $db->mySQLSafe($_POST['img_status']);

	$record["lang"] = $db->mySQLSafe($_POST['banner_lang']);

	$record["img_created_on"] = $db->mySQLSafe(date("Y-m-d"));



	$newFileName = '';



/*    if ($_FILES['filename']['name'] != "")

    {

        $path_parts2 = pathinfo($_FILES['filename']['name']);

        $ext = "." . $path_parts2['extension'];

        $newFileName = date("jnYHis") . $ext;

        $uploadFilePath = "upload/videos/" . $newFileName;		

        if (!move_uploaded_file($_FILES['filename']['tmp_name'], $uploadFilePath))

        {

            $Flash_Type_Error = true;

        }

		@chmod($uploadFilePath, 0777);

		$record["vid_file"] = $db->mySQLSafe($newFileName);

		@unlink("upload/videos/".$_POST['oldfilename']);	

		

	}*/

	

	////////////////////////////////////////// Image Uploading /////////////////////////////////////////////////

	

	    if ($_FILES['imagename']['name'] != "")

    {

        $path_parts2 = pathinfo($_FILES['imagename']['name']);

        $ext = "." . $path_parts2['extension'];

        $newImageName = date("jnYHis") . $ext;

        $uploadImagePath = "uploads/flashbanner/" . $newImageName;		

        if (!move_uploaded_file($_FILES['imagename']['tmp_name'], $uploadImagePath))

        {

            $Flash_Type_Error = true;

        }

		else

		{		

			list($width,$height) = getimagesize($uploadImagePath);

			

			if($height <= $req_banner_height)

			{				

				/*ResizeImageSimple_New($uploadImagePath, $req_banner_width, $req_banner_height);	*/	

				@chmod($uploadImagePath, 0777);

				$record["img_file"] = $db->mySQLSafe($newImageName);		

				@unlink("uploads/flashbanner/".$_POST['oldimagename']);

			}

			else

			{

				@unlink($uploadImagePath);

				$msg = "Please Upload image with Maximum Height ($req_banner_height) "; 			

				header("Location:admin.php?_g=flashbanner/flashbanner&msg=$msg");

				die();

			}

		}

		

		@chmod($uploadImagePath, 0777);

		$record["img_file"] = $db->mySQLSafe($newImageName);		

		@unlink("uploads/flashbanner/".$_POST['oldimagename']);

	}

	/*if($_POST['vid_is_featured'] == 1) {		

		$recTemp = array();

		$recTemp["vid_is_featured"] = 0;

		$where = " 1=1 ";

		$update = $db->update("tbl_videos", $recTemp, $where);

	}*/

	

	if (is_numeric($_POST['img_id'])) {		

		$where = "img_id=".$db->mySQLSafe($_POST['img_id']);

		$update = $db->update("ImeiUnlock_flashbanner", $record, $where);



		$msg = ($update == true) ? "<p class='infoText'>'".$_POST['img_title']."' Updated successfully</p>" : "<p class='warnText'>Updated failed</p>";

	} else {	

		$insert = $db->insert("ImeiUnlock_flashbanner", $record);

		$msg = ($insert == true) ? "<p class='infoText'>'".$_POST['img_title']."' Added successfully</p>" : "<p class='warnText'>Added failed</p>";

	}

}

if (!isset($_GET['mode'])) {

	// make sql query

	if (isset($_GET['edit']) && $_GET['edit'] > 0) {

		$query = sprintf("SELECT * FROM ImeiUnlock_flashbanner WHERE img_id = %s", $db->mySQLSafe($_GET['edit'])); 

	} else {

		if($whereClause != "")

		$whereClause = " WHERE " . $whereClause;

		 

		$query = "SELECT * FROM ImeiUnlock_flashbanner ".$whereClause." ORDER BY priority, img_title ASC";

	}

	$page = (isset($_GET['page'])) ? $_GET['page'] : 0;

	

	// query database

	$results = $db->select($query, $catsPerPage, $page);

	$numrows = $db->numrows($query);

	$pagination = paginate($numrows, $catsPerPage, $page, "page");

}

/*$query = "SELECT cat_id, cat_name, cat_father_id, cat_desc FROM tbl_video_category ORDER BY cat_id ASC";

$categoryArray = $db->select($query);*/

if(isset($_GET['msg'])&& $_GET['msg']!="")

		{

			$msg = 

			 ($update == true) ? "<p class='infoText'>'".$_GET['msg']."'</p>" : "<p class='warnText'>'".$_GET['msg']."'</p>";

		}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php"); 

?>

<style type="text/css">

span.vidTitle {

	float:left;

	width:120px;

}



span.vidStatus {

	float:left;

	width:120px;

}



span.vidFeatured {

	float:left;

	width:120px;

}

</style>

<script language="javascript">

function fileTypeCheck(filename)

{	

	var fileTypes=["gif","png","jpg","jpeg"]; 

	var defaultPic="spacer.gif";

	var source=filename.value;

	var ext=source.substring(source.lastIndexOf(".")+1,source.length).toLowerCase();

	for (var i=0; i<fileTypes.length; i++) if (fileTypes[i]==ext) break;

	globalPic=new Image();

	if (i<fileTypes.length) globalPic.src=source;

	else 

	{

		//globalPic.src=defaultPic;

		alert("Invalid image. Please upload a file ending with jpg, jpeg, gif or png");

		filename.focus();

		return false;

	}

	return true;

}

function FrmValidation(mode)

{

	//img_title imagename img_link

		

		if(document.getElementById("img_title").value == "")

		{

			alert("Please enter image title");

			document.getElementById('img_title').focus();

			return false;			

		}

		

			 

		if (mode == "Add")

		{

 

 		if(document.getElementById("imagename").value == "")

		{

			alert("Please select image ");

			document.getElementById('imagename').focus();

			return false;			

		}

		if(document.getElementById("imagename").value != "")

		{

			if(fileTypeCheck(document.getElementById("imagename")) == false)

				return false;

			

			if(document.getElementById("imagename").value != "") {

				var iChars = "!@#$%^&*()+=[];,'{}|<>??";

				for (var i = 0; i < document.getElementById("imagename").value.length; i++) {

					if (iChars.indexOf(document.getElementById("imagename").value.charAt(i)) != -1) {

					alert("Special characters are not allowed in image file name");

					document.getElementById("imagename").focus();

					return false;

				   }  // inner if statement

				 }  // for loop statement

			}

		}

	}

		else if (document.getElementById("imagename").value != "")

		{

			if(fileTypeCheck(document.getElementById("imagename")) == false)

				return false;

							

			if(document.getElementById("imagename").value != "") {

				var iChars = "!@#$%^&*()+=[];,'{}|<>??";

				for (var i = 0; i < document.getElementById("imagename").value.length; i++) {

					if (iChars.indexOf(document.getElementById("imagename").value.charAt(i)) != -1) {

					alert("Special characters are not allowed in image file name");

					document.getElementById("imagename").focus();

					return false;

				   }  // inner if statement

				 }  // for loop statement

			}

		}	

						

 		

</script>



  <?php

if (!isset($_GET['mode'])) {?><div class="maindiv" style="margin-bottom:10px"><?

	$url = 'href="?_g=flashbanner/flashbanner&amp;mode=new" class="txtLink"';

	echo sprintf('<span style="float: right;"><a %s><img src="%s" alt="" hspace="4" />%s</a></span>', $url, $glob['adminFolder'].'/images/buttons/new.gif', "Add New");?>

</div><? }

?>

 



<div class="clear"></div>

<?php 

if(isset($msg)) echo msg($msg);



if (!isset($_GET['mode']) && !isset($_GET['edit'])) {

?>





<form method="post" id="reorderCategory" enctype="multipart/form-data">

  <div class="wbox">

  <div  class="headingBlackbg2"> 

	<span class="catName2" style="width:300px" >Image Title</span> 

	<span class="catid">Status</span>

	<span class="catName2"  style="width:230px">Image</span> 

  

    <span class="action2" style="width:120px;">Action</span> 

      <span class="catid" style="width:120px;">Language</span> 

  </div>

  

  <?php 

if ($results == true) {

	for ($i=0; $i<count($results); $i++){

		$cellColor	= cellColor($i);

?>

  <div id="flashbanner_<?php echo $results[$i]['img_id']; ?>" class="productRow <?php echo $cellColor; ?> tdText"> 

   

	

	<span class="catName2" style="width:300px;">

    <?php

	echo $results[$i]['img_title'];

	echo "&nbsp;";

  ?>

    </span> 

	

	<span class="catid">

    <?php

	echo ($results[$i]['img_status'] == 1) ? "Active" : "Inactive";

	echo "&nbsp;";

  ?>

    </span>

	

	<span class="catName2" style="width:230px;">

    <?php

	$imagepath = "uploads/flashbanner/".$results[$i]['img_file'];

	if(file_exists($imagepath)&& $results[$i]['img_file'] !="")

	{

	echo "<img src='$imagepath' height='100' width='200'/>";

	}

	echo "&nbsp;";

  ?>

    </span>

    <span class="catid" style="width:120px;">

    <?php

	echo '<img src="language/'.$results[$i]["lang"].'/flag.gif" alt="" title="" />';

	echo "&nbsp;";

  ?>

    </span>

	<span class="action2" > 

  	<span class="right3">

	<?php

			switch($results[$i]['img_status']) {

				case 1:

					$url	= 'href="?_g=flashbanner/flashbanner&amp;hide=0&amp;img_id='.$results[$i]['img_id'].'" class="txtLink"';

					$title	= "Hide";

					break;

				case 0:

					$url	= 'href="?_g=flashbanner/flashbanner&amp;hide=1&amp;img_id='.$results[$i]['img_id'].'" class="txtLink"';

					$title	= "Show";

					break;

			}

			echo sprintf('<a %s>%s</a>', $url, $title);	

				

	?>

    </span>

		

	<span class="right3">

    <?php

		$url = 'href="?_g=flashbanner/flashbanner&amp;edit='.$results[$i]['img_id'].'" class="txtLink"';

		echo sprintf('<a %s>%s</a>', $url, "Edit");

	?>

    </span> 

    <span class="right3">

    <?php

	 	$url = 'href="?_g=flashbanner/flashbanner&amp;delete='.$results[$i]['img_id'].'" class="txtLink" onClick="return confirm(\'' . str_replace("\n", "\n", addslashes($lang["admin_common"]["delete_q"])).'\')"';

		echo sprintf('<a %s>%s</a>', $url, "Delete");

	?>

    </span> 

 

  </span>

    <input type="hidden" name="priority[]" value="<?php echo $results[$i]['img_id']; ?>" />

    <div style="clear:both"></div>

  </div>

  <?php

	} // end loop

	?>

	</div>

    <?php

} else {

?>

  <div class="tdText">No image exists</div>

  <?php } ?>

  <!--<p>To re-order the image, drag and drop them into your prefered order, then save</p>

  <p>

    <input type="submit" class="submit" name="saveOrder" value="Save Order" />

  </p>-->

</form>

<script type="text/javascript">

	Sortable.create('reorderCategory', {ghosting:true,constraint:false,tag:'div',only:'productRow'});

</script>

<p class="copyText" align="right"><span class="pagination"><?php echo $pagination; ?></span></p>

<?php 

} else if ($_GET["mode"]=="new" || $_GET["edit"] > 0){  



if(isset($_GET["edit"]) && $_GET["edit"]>0){ $modeTxt = "Edit"; } else { $modeTxt = "Add"; } 

$path = CC_ROOT_DIR.CC_DS."language";



$options = "";



foreach (glob($path.CC_DS.'*') as $langpath) {



	$folder = basename($langpath);



	if (is_dir($langpath) && preg_match('#^[a-z]{2}(\_[A-Z]{2})?$#iuU', $folder)) {



		if (file_exists($langpath.CC_DS.'config.php')) {



			include $langpath.CC_DS.'config.php';



			

			if($results[0]['lang']){

				if($folder == $results[0]['lang'])

			$selected =	' selected="selected"';

			else

			$selected = '';

			}else

			$selected = ($config['defaultLang']==$folder) ? ' selected="selected"' : '';



			$options .= sprintf('<option value="%s"%s>%s</option>', $folder, $selected, $langName);



		}



	}



}

?>

<form action="?_g=flashbanner/flashbanner" method="post" enctype="multipart/form-data" name="form1" 

<? if(isset($_GET['edit']) &&  $_GET['edit'] != ""){ ?>

 onSubmit="javascript: return FrmValidation('Edit');">

<? } else {?>

	

    onSubmit="javascript: return FrmValidation('Add');">



<? } ?>

  <table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">

    <tr>

      <td colspan="2" class="tdTitle"><?php if(isset($_GET["edit"]) && $_GET["edit"]>0){ echo $modeTxt; } else { echo $modeTxt;  }  ?>

        <?php echo "images"?></td>

    </tr>

    

     <?php /*?><tr>

      <td class="tdText" width="18%"><strong>Video Category</strong></td>

      <td width="82%">  <select name="category" class="textbox">

	

	<?php for ($i=0; $i<count($categoryArray); $i++){ ?>

	<option value="<?php echo $categoryArray[$i]['cat_id']; ?>" <?php if(isset($_GET['category']) && $categoryArray[$i]['cat_id']==$_GET['category']) echo "selected='selected'"; ?>><?php echo getCatDir($categoryArray[$i]['cat_name'],$categoryArray[$i]['cat_father_id'], $categoryArray[$i]['cat_id']); ?></option>

	<?php } ?>

	</select>

      </td>

    </tr><?php */?> 

    <tr>

      <td class="tdText" width="18%"><strong>Image Title:</strong></td>

      <td width="82%"><input name="img_title" id="img_title" type="text" class="textbox" value="<?php if(isset($results[0]['img_title'])) echo validHTML($results[0]['img_title']); ?>" maxlength="255" required="required" /><span  style="color:#F00">&nbsp; *</span>      </td>

    </tr>

    <?php /*?><tr>

      <td align="left" valign="top" class="tdText"><strong>Video file:</strong></td>

      <td valign="top"><div>

          <input name="filename" class="textbox" style="width: 200px;" type="file" id="filename" value="Browse / Upload File" />

          <input type="hidden" name="oldfilename" id="oldfilename" value="<?php if(isset($results[0]['vid_file'])) echo $results[0]['vid_file']; ?>" />

        </div></td>

    </tr><?php */?>

    <tr>

      <td align="left" valign="top" class="tdText"><strong>Image file:</strong></td>

      <td valign="top"><div>

          <input name="imagename" class="textbox" style="width: 200px;" type="file" id="imagename" value="Browse / Upload File"  required="required"/>

          <input type="hidden" name="oldimagename" id="oldimagename" value="<?php if(isset($results[0]['img_file'])) echo $results[0]['img_file']; ?>" />

          <span  style="color:#F00">*</span>

        </div><span>(<strong>Recommended:</strong> <?=$req_banner_width?>px x <?=$req_banner_height?>px)</span>

        

        <br /><br /> <img src="uploads/flashbanner/<?=$results[0]['img_file']?>" alt="" width="200" height="100" /></td>

    </tr>

    

   <?php /*?> <tr>

      <td class="tdText"><strong>Is Featured</strong></td>

      <td><input name="vid_is_featured" id="vid_is_featured" type="checkbox" class="checkbox" value="1" <?php if($results[0]['vid_is_featured'] == 1) {?> checked="checked" <?php } ?> onchange="javascript: toggleStatus();" />

      </td>

    </tr><?php */?>

     <tr>

      <td class="tdText" width="18%"><strong>Image Link:</strong></td>

      <td width="82%"><input name="img_link" id="img_link" type="text" class="textbox" value="<?php if(isset($results[0]['img_link'])) echo validHTML($results[0]['img_link']); ?>" maxlength="255" />      </td>

    </tr>

    <tr>



    <td ><strong>Banner Language</strong></td>

	<td>

		<select class="textbox" name="banner_lang">



		<?php echo $options; ?>



		</select>



		



	</td>



  </tr>

    <tr>

      <td class="tdText"><strong>Status</strong></td>

      <td><input name="img_status" id="img_status" type="checkbox" class="checkbox" value="1" <?php if($results[0]['img_status'] == 1) {?> checked="checked" <?php } ?> onchange="javascript: toggleStatus();" />      </td>

    </tr>

    <tr>

      <td>&nbsp;</td>

      <td><?php /*?><input type="hidden" name="oldfilename" value="<?php if(isset($results[0]['vid_file'])) echo $results[0]['vid_file']; ?>" /><?php */?>

      <input type="hidden" name="oldimagename" value="<?php if(isset($results[0]['img_file'])) echo $results[0]['img_file']; ?>" />

      <input type="hidden" name="img_id" value="<?php echo $results[0]['img_id']; ?>" />

        <input name="Submit" type="submit"  class="submit" value="<?php echo $modeTxt; ?>" /></td>

    </tr>

  </table>

</form>

<?php 

} 

?>

