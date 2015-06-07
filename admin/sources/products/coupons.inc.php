<?php
/*
+--------------------------------------------------------------------------
|	coupons.inc.php
|   ========================================
|	Add/Edit/Delete Coupons	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_products.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");

if(isset($_POST) && isset($_GET['delete']) && $_GET['delete']>0){	
	$where = "id = ".$db->mySQLSafe($_GET['delete']);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Coupons", $where, ""); 

	if($delete == TRUE){
		$msg = "<p class='infoText'>".$lang['admin']['coupon_del_success']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['coupon_del_fail']."</p>";
	}
		
} elseif(isset($_GET['status'])) {
	$record["status"] = $db->mySQLSafe($_GET['status']);

	$where = "id = ".$db->mySQLSafe($_GET['id']);
	
	$update = $db->update($glob['dbprefix']."ImeiUnlock_Coupons", $record, $where);
	
	if($update == TRUE){
		$msg = "<p class='infoText'>".$lang['admin']['coupon_status_updated']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['coupon_status_not_updated']."</p>";
	}
	
} else if(isset($_POST['code']) && !empty($_POST['code'])) {

	$data['code'] = $db->mySQLSafe($_POST['code']);
	$data['discount_percent'] = $db->mySQLSafe($_POST['discount_percent']);
	$data['discount_price'] = $db->mySQLSafe($_POST['discount_price']);
	$data['expires'] = $db->mySQLSafe($_POST['expires']);
	$data['allowed_uses'] = $db->mySQLSafe($_POST['allowed_uses']);
	$data['count'] = $db->mySQLSafe($_POST['count']);
	$data['desc'] = $db->mySQLSafe($_POST['desc']);
	
	if($_POST['id']>0){
		
		$data['id'] = $db->mySQLSafe($_POST['id']);
		// update
		$result = $db->update($glob['dbprefix']."ImeiUnlock_Coupons", $data, "id = ".$db->mySQLSafe($_POST['id']));
		
		if($result == TRUE) {
			$msg = "<p class='infoText'>".$lang['admin']['coupon_updated_success']."</p>";
		} else {
			$msg = "<p class='warnText'>".$lang['admin']['coupon_updated_fail']."</p>";
		}
		
	} else {
	// insert
	
		// make sure no duplicate exists
		$query = "SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_Coupons WHERE `code` = ".$db->mySQLSafe($_POST['code']);
		$exists = $db->select($query);
		
		if($exists == true) {
			$msg = "<p class='warnText'>".sprintf($lang['admin']['coupon_already_exists'],$_POST['code'])."</p>";
		} else {
			$result = $db->insert($glob['dbprefix']."ImeiUnlock_Coupons", $data);
		}
		
		if($result == TRUE) {
			$msg = "<p class='infoText'>".$lang['admin']['coupon_add_success']."</p>";
		} elseif($exists == false) {
			$msg = "<p class='warnText'>".$lang['admin']['coupon_add_fail']."</p>";
		}
		
	} 

}

$couponsPerPage = 50;

$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_Coupons";

$results = $db->select($query, $couponsPerPage, $_GET['page']);
$numrows = $db->numrows($query);
$pagination = paginate($numrows, $couponsPerPage, $_GET['page'], "page");

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['coupon_management'];?></p>
<?php 
if(isset($msg)){ 
	echo msg($msg); 
}
?>
<p><?php echo $pagination; ?></p>
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_id'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['is_gift_cert'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_code'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_discount_price'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_discount_percent'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_td_expires'];?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['coupon_uses'];?></td>
    <td colspan="3" align="center" class="tdTitle"><?php echo $lang['admin']['coupon_action'];?></td>
  </tr>
  
   <?php 
  if($results == true) {
  	
	for ($i=0; $i<count($results); $i++){ 
  	
	$cellColor = "";
	$cellColor = cellColor($i);
  ?>
  
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['id']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<?php
	if(!empty($results[$i]['cart_order_id'])) {
		$is_gift_cert = 1;
	} else {
		$is_gift_cert = 0;
	}
	?>
	<img src="<?php echo $glob['adminFolder']; ?>/images/<?php echo $is_gift_cert; ?>.gif" border="0" />
	</td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['code']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['discount_price']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['discount_percent']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php if(empty($results[$i]['expires'])) { echo "Never"; } else { echo $results[$i]['expires']; } ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['count']; ?> / <?php echo $results[$i]['allowed_uses']; ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<a <?php if(permission("offers","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons&amp;edit=<?php echo $results[$i]['id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit'];?></a>	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<a <?php if(permission("offers","delete")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons&amp;delete=<?php echo $results[$i]['id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete'];?></a>	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
	
	<?php if($results[$i]['status']==1){ ?>
	<a <?php if(permission("offers","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons&amp;id=<?php echo $results[$i]['id']; ?>&amp;status=0" class="txtRed"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['disable'];?></a>
	<?php } else { ?>
	<a <?php if(permission("offers","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons&amp;id=<?php echo $results[$i]['id']; ?>&amp;status=1" class="txtGreen"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['enable'];?></a>
	<?php } ?>	</td>
  </tr>
   
  <?php
  	}
  } else {
  ?>
  <tr>
    <td colspan="10" class="tdText"><?php echo $lang['admin']['coupon_no_coupons'];?></td>
  </tr>
  <?php } ?>
</table>
<p><?php echo $pagination; ?></p>
<br />
<?php
if(permission("offers","write")==true && permission("offers","edit")==true){

	if($_GET['edit']>0) {
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_Coupons WHERE id=".$db->mySQLSafe($_GET['edit']);
		$coupon = $db->select($query);
	}
	?>
	<form id="addeditcoupon" name="addeditcoupon" method="post" action="<?php echo $glob['adminFile']; ?>?_g=products/coupons">
	<table  border="0" cellspacing="1" cellpadding="3" class="mainTable">
	  <tr>
		<td colspan="2" class="tdTitle"><?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { $lang['admin_common']['add']; } echo $lang['admin']['coupon_coupon']; ?></td>
	  </tr>
	  <tr>
		<td class="tdText"><?php echo $lang['admin']['coupon_code_colon'];?></td>
		<td class="tdText"><input name="code" type="text" class="textbox" value="<?php echo $coupon[0]['code']; ?>" size="25" maxlength="25" /></td>
	  </tr>
	  <tr>
		<td class="tdText"><?php echo $lang['admin']['coupon_discount_colon'];?></td>
		<td class="tdText"><input name="discount_percent" type="text" class="textbox" value="<?php echo $coupon[0]['discount_percent']; ?>" size="5" 
		onclick="findObj('discount_price').value = '';" 
		onfocus="findObj('discount_price').value = '';" />
		<?php echo $lang['admin']['coupon_eg_discount_method']; ?>
			<input name="discount_price" type="text" class="textbox" value="<?php echo $coupon[0]['discount_price']; ?>" size="5" 
			onclick="findObj('discount_percent').value = '';" 
			onfocus="findObj('discount_percent').value = '';" /> 
		<?php echo $lang['admin']['coupon_eg_5_quid_ooooh_yeah']; ?> </td>
	  </tr>
	  <tr>
		<td class="tdText"><?php echo $lang['admin']['coupon_expires']; ?> </td>
		<td class="tdText"><input name="expires" type="text" class="textbox" value="<?php echo $coupon[0]['expires']; ?>" size="10" maxlength="10" /></td>
	  </tr>
	  <tr>
		<td class="tdText"><?php echo $lang['admin']['coupon_allowed_uses']; ?> </td>
		<td class="tdText"><input name="allowed_uses" type="text" class="textbox" value="<?php echo $coupon[0]['allowed_uses']; ?>" size="5" /> 
		<?php echo $lang['admin']['coupon_zero_4_infinte']; ?> </td>
	  </tr>
	  <tr>
		<td valign="top" class="tdText"><?php echo $lang['admin']['coupon_times_used'];?></td>
		<td class="tdText"><input name="count" type="text" class="textbox" value="<?php echo $coupon[0]['count']; ?>" size="5" /> 
		 <?php echo $lang['admin']['coupon_zero_4_infinte']; ?></td>
	  </tr>
	  <tr>
		<td valign="top" class="tdText"><?php echo $lang['admin']['coupon_description'];?><br />
	<?php echo $lang['admin']['coupon_internal_use'];?> </td>
		<td class="tdText"><textarea name="desc" cols="40" rows="3" class="textbox"><?php echo htmlspecialchars($coupon[0]['desc']); ?></textarea></td>
	  </tr>
	  <tr>
		<td class="tdText">&nbsp;</td>
		<td class="tdText">
		  <input type="hidden" name="id" value="<?php echo $coupon[0]['id']; ?>" />
		  <input name="Submit" type="submit" class="submit" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['edit']; } echo " ".$lang['admin']['coupon_coupon']; ?>" />    </td>
	  </tr>
	</table>
	</form>
<?php 
} 
?>