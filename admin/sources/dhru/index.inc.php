<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage faqs :: FM 19-04-13
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_faqs.inc.php");
permission("dhru","read",TRUE);

## get product and update database
$rowsPerPage = 25;
if (isset($_GET['status'])) {	
	$record['active'] = $_GET['status'];
	$where = "id=".$db->mySQLSafe($_GET['id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_venders", $record, $where);
	
	$msg = ($update == true) ? "<p class='infoText'>".$lang['admin']['faqs_update_success']."</p>" : "<p class='warnText'>".$lang['admin']['faqs_update_fail']."</p>";
}else if(isset($_GET["delete"]) && $_GET["delete"]>0)

{

	

	// instantiate db class

	$where = "id=".$db->mySQLSafe($_GET["delete"]);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_venders", $where);
	$dropcat = "DROP TABLE ".$glob['dbprefix']."dhru_cat".$_GET["delete"];
	$droppro = "DROP TABLE ".$glob['dbprefix']."dhru_products".$_GET["delete"];
	$db->misc($dropcat);
	$db->misc($droppro);
	if($delete == TRUE)

	{

		$msg = "<p class='infoText'>".'delete success'."</p>";

	} 

	else 

	{

		$msg = "<p class='warnText'>".'Deletion Failed'."</p>";

	}



} 
if(isset($_POST['url'])){
	$data['vender_title'] = $db->mySQLSafe($_POST['title']);
	$data['vender_url'] = $db->mySQLSafe($_POST['url']);
	$data['vender_user'] = $db->mySQLSafe($_POST['usename']);
	$data['vender_key'] = $db->mySQLSafe($_POST['key']);
if($_POST['dhruid'] > 0){
	$where = "id=".$db->mySQLSafe($_POST['dhruid']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_venders", $data, $where);
	

}else{
	$update = $db->insert($glob['dbprefix']."ImeiUnlock_venders", $data);
	$id = $db->insertid();
	$install_table_cat = "CREATE TABLE dhru_cat".$id."(
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_unicode_ci;";
	$db->misc($install_table_cat);
	$install_table_pro = "CREATE TABLE dhru_products".$id."(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SERVICEID` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `SERVICENAME` varchar(200) DEFAULT NULL,
  `CREDIT` int(11) DEFAULT NULL,
  `TIME` varchar(100) DEFAULT NULL,
  `INFO` varchar(250) DEFAULT NULL,
  `Requires.Network` varchar(100) DEFAULT NULL,
  `Requires.Mobile` varchar(100) DEFAULT NULL,
  `Requires.Provider` varchar(100) DEFAULT NULL,
  `Requires.PIN` varchar(100) DEFAULT NULL,
  `Requires.KBH` varchar(100) DEFAULT NULL,
  `Requires.MEP` varchar(100) DEFAULT NULL,
  `Requires.PRD` varchar(100) DEFAULT NULL,
   `Requires.Type` varchar(100) DEFAULT NULL,
    `Requires.Locks` varchar(100) DEFAULT NULL,
	 `Requires.Reference` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_unicode_ci;";
	$db->misc($install_table_pro);
}
if($update == TRUE){

			$msg = "<p class='infoText'>".'Update successfull'."</p>";

		} else {

			$msg = "<p class='warnText'>".'Update Fail'."</p>";

		}
}
if ($_GET['mode']!=="new") {

		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders ORDER BY id ASC";

	}if (isset($_GET['edit']) && $_GET['edit']>0) {
	$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders WHERE id =" .$db->mySQLSafe($_GET['edit']);
	}
	

	// query database

	if (isset($query)) {

		$page = (is_numeric($_GET['page'])) ? $_GET['page'] : 0;

		$dhruData = $db->select($query, $rowsPerPage, $page);

		$numrows = $db->numrows($query);

		$pagination = paginate($numrows, $rowsPerPage, $page, "page");

	}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getproducts"){
require ($glob['adminFolder']."/sources".CC_DS."dhru".CC_DS."api_files".CC_DS."header.php");
include ($glob['adminFolder']."/sources".CC_DS."dhru".CC_DS."api_files".CC_DS."dhrufusionapi.class.php");
$vendata		= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_venders WHERE id=".$db->mySQLSafe($_POST['vendor']);
$vendata = $db->select($vendata);
define("REQUESTFORMAT", "JSON");
$api = new DhruFusion();

// Debug on
//$api->debug = true;

$arr = array();
$request = $api->action('imeiservicelist', $arr, $vendata[0]['vender_url'], $vendata[0]['vender_user'], $vendata[0]['vender_key']);
//echo "<pre>";
//print_r($request);
//die();
if($request['SUCCESS'][0]['LIST']){
$trucatepro = $db->truncate($glob['dbprefix']."dhru_products".$_POST['vendor']);
$trucatecat = $db->truncate($glob['dbprefix']."dhru_cat".$_POST['vendor']);
$catid = 0;
foreach ($request['SUCCESS'][0]['LIST'] as $GROUPNAME) {
	$catid++;
	$catdata['catname'] = $db->mySQLSafe($GROUPNAME['GROUPNAME']);
	//print_r($catdata);
	$insert = $db->insert($glob['dbprefix']."dhru_cat".$_POST['vendor'], $catdata);
	foreach ($GROUPNAME['SERVICES'] as $SERVICES) {
	$prodata['SERVICEID'] = 	$db->mySQLSafe($SERVICES['SERVICEID']);
	$prodata['cat_id'] = 	$db->mySQLSafe($catid);
	$prodata['SERVICENAME'] = 	$db->mySQLSafe($SERVICES['SERVICENAME']);
	$prodata['CREDIT'] = 		$db->mySQLSafe($SERVICES['CREDIT']);
	$prodata['TIME'] = 			$db->mySQLSafe($SERVICES['TIME']);
	$prodata['INFO'] = 			$db->mySQLSafe($SERVICES['INFO']);
	$prodata['Requires.Network'] = $db->mySQLSafe($SERVICES['Requires.Network']);
	$prodata['Requires.Mobile'] = $db->mySQLSafe($SERVICES['Requires.Mobile']);
	$prodata['Requires.Provider'] = $db->mySQLSafe($SERVICES['Requires.Provider']);
	$prodata['Requires.PIN'] = $db->mySQLSafe($SERVICES['Requires.PIN']);
	$prodata['Requires.KBH'] = $db->mySQLSafe($SERVICES['Requires.KBH']);
	$prodata['Requires.MEP'] = $db->mySQLSafe($SERVICES['Requires.MEP']);
	$prodata['Requires.PRD'] = $db->mySQLSafe($SERVICES['Requires.PRD']);
	$prodata['Requires.Type'] = $db->mySQLSafe($SERVICES['Requires.Type']);
	$prodata['Requires.Locks'] = $db->mySQLSafe($SERVICES['Requires.Locks']);
	$prodata['Requires.Reference'] = $db->mySQLSafe($SERVICES['Requires.Reference']);
	$insert = $db->insert($glob['dbprefix']."dhru_products".$_POST['vendor'], $prodata);
}
}
}else
{
	echo "<pre>";
print_r($request);
}
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



<table width="100%" cellspacing="0" cellpadding="0" border="0">

 <tr>

    <td nowrap="nowrap" class="pageTitle">Dhru Api</td>

     <td valign="middle" align="right"><?php if($_GET['edit'] < 1){ ?><a class="txtLink" href="admin.php?_g=dhru/index&amp;mode=new"><img hspace="4" border="0" title="" alt="" src="admin/images/buttons/new.gif">Add New</a><?php } ?></td>
  </tr>
<tr><td colspan="2">&nbsp;</td></tr>
</table>

</div>
  <?php
 if(isset($msg))

{ 

	echo msg($msg); 

}

if(!isset($_GET['edit']) && !isset($_GET['mode'])){
	?>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="mainTable">

  <tr>

    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo 'ID'; ?></td>
 	<td align="center" nowrap="nowrap" class="tdTitle"><?php echo 'Title'; ?></td>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo 'Api Url'; ?></td>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo 'Api user name'; ?></td>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo 'Api access key'; ?></td>
    <td colspan="6" nowrap="nowrap" class="tdTitle" align="center"><?php echo 'Action'; ?></td>

  </tr>

<?php 

if ($dhruData) { 

	for ($i=0; $i<count($dhruData); $i++) {

		$cellColor = cellColor($i);

?>

  <tr>

    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $dhruData[$i]['id']; ?>

	

	</td>
 	<td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $dhruData[$i]['vender_title']; ?>
	</td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $dhruData[$i]['vender_url']; ?>
	</td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $dhruData[$i]['vender_user']; ?>
	</td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $dhruData[$i]['vender_key']; ?>
	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<a <?php if(permission("customers","edit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=dhru/index&amp;edit=<?php echo $dhruData[$i]['id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<a <?php if(permission("customers","delete")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=dhru/index&amp;delete=<?php echo $dhruData[$i]['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="action" >
	<?php
		switch($dhruData[$i]['active']) {
			case 0:
				$url	= (permission("FAQs","edit")==true) ? 'href="?_g=dhru/index&amp;status=1&amp;id='.$dhruData[$i]['id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin']['faqs_faqs_active'];
				break;
			case 1:
				$url	= (permission("FAQs","edit")==true) ? 'href="?_g=dhru/index&amp;status=0&amp;id='.$dhruData[$i]['id'].'" class="txtLink"' : $link401;
				$title	= $lang['admin']['faqs_faqs_inactive'];
				break;
		}
		echo sprintf('<a %s>%s</a>', $url, $title);
	?>
	</span></td>
  </tr>
<?php 

  		} // end loop  

	} 

	else 

	{ ?>

   <tr>

    <td colspan="7" class="tdText"><?php echo 'No Record Found'; ?></td>

  </tr>

<?php

  } 

?>

</table>

<p class="copyText"><?php echo $pagination; ?></p>
<?php  }
 
else if($_GET["mode"]=="new" || $_GET["edit"]>0) {
	 if(isset($_GET['edit'])){

	echo "<span style='float: right;'><a id='addpro' style='cursor:pointer;' class='txtLink' onclick='Extractproducts(); '><img src='".$glob['adminFolder']."/images/buttons/new.gif' border='0' />&nbsp;Update Products</a> <img id='imgProgress' src='admin/images/imgProgress.gif' border='0' style='display:none;'  /></span>";
	 }
	?>
    <form method="post" id="" enctype="multipart/form-data" action="<?php echo $glob['adminFile']; ?>?_g=dhru/index">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="66%">



	<tr>

		<td colspan="2" class="tdTitle" id=""><strong><?php echo 'Dhru Api Setting';?></strong></td>

	</tr>
<tr>

	  <td width="30%" class="tdText"><strong><?php echo 'Vendor Title';?></strong></td>

	  <td align="left"><input type="text" size="35" class="textbox" name="title" value="<?php echo $dhruData[0]['vender_title']; ?>" /></td>

    </tr>
	<tr>

	  <td width="30%" class="tdText"><strong><?php echo 'DHRUFUSION URL';?></strong></td>

	  <td align="left"><input type="text" size="35" class="textbox" name="url" value="<?php echo $dhruData[0]['vender_url']; ?>" /></td>

    </tr>

	<tr>

	  <td width="30%" class="tdText"><strong><?php echo 'DHRUFUSION User Name';?></strong></td>

	  <td align="left"><input type="text" size="35" class="textbox" name="usename" value="<?php echo $dhruData[0]['vender_user']; ?>" /></td>

    </tr>
    <tr>

	  <td width="30%" class="tdText"><strong><?php echo 'DHRUFUSION Api Key';?></strong></td>

	  <td align="left"><input type="text" size="35" class="textbox" name="key" value="<?php echo $dhruData[0]['vender_key']; ?>" /></td>

    </tr><tr>

<td><input type="hidden" name="dhruid" value="<?php echo $dhruData[0]['id']; ?>" /></td>

<td><input name="submit" type="submit" class="submit" id="submit" value="<?php if($_GET['mode'] == 'new') echo 'Add Vendor'; else echo 'Update Vendor'; ?>" /></td>

</tr>

</table>
</form>
<?php 
	 if(isset($_GET['edit'])){
		 ?>
 <script>
    document.write(unescape('%3Cscript type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/jquery-1.8.3.min.js"%3E%3C/script%3E'));
</script>
<script>
    jQuery.noConflict();
    var $j = jQuery;
</script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/dhruapi.js"></script>
<?php
$catquery		= "SELECT * FROM ".$glob['dbprefix']."dhru_cat".$_GET['edit']."  ORDER BY catname ASC";
$catResult = $db->select($catquery); 

if(!empty($catResult)){
?>
<select onchange="getdata(this.value, '<?php echo $GLOBALS['storeURL']; ?>', '<?php echo $_GET['edit']; ?>');" style="display:inline-block; margin:15px;">
<?php
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
?>
<optgroup style="font-size:13px; font-weight:bold;" label="<?php echo $catResult[$j]['catname']; ?>">
<?php
		$queryall = "SELECT SERVICENAME,SERVICEID  FROM ".$glob['dbprefix']."dhru_products".$_GET['edit']." WHERE cat_id = ".$catResult[$j]['catid'];
		$ResultAll= $db->select($queryall);
		if(!empty($ResultAll)){
			for($i=0;$i<count($ResultAll);$i++){
		?>
		<option value="<?php echo $ResultAll[$i]['SERVICEID']; ?>"><?php echo $ResultAll[$i]['SERVICENAME']; ?></option>
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

?>

<form method="post" name="frmExtract" id="frmExtract" action="">

  <input type="hidden" id="action" name="action" value="getproducts" />
  <input type="hidden" value="<?php echo $_GET['edit']; ?>" name="vendor" />

</form>
<div id="dhrudata">

</div>
<?php
}
}
?>