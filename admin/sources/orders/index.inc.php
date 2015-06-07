<?php

/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage Orders
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }
require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");
$lang = getLang("admin".CC_DS."admin_orders.inc.php");
$lang = getLang("orders.inc.php");

permission("orders", "read", true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

// delete document

if(isset($_GET['delete']) && $_GET['delete']>0) {

	$cache = new cache();
	$cache->clearCache();

	$record['noOrders'] = "noOrders - 1";
	$where = "customer_id = ".$_GET['customer_id'];
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);	

	$where = "cart_order_id = ".$db->mySQLSafe($_GET['delete']);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_sum", $where);

	if ($delete) {
		$msg = "<p class='infoText'>".$lang['admin']['orders_delete_success']."</p>";
	} else {
		$msg = "<p class='infoText'>".$lang['admin']['orders_delete_fail']."</p>";
	}

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where); 
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Coupons", $where);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Downloads", $where);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_transactions", "`order_id` = ".$db->mySQLSafe($_GET['delete']));
}

$sqlQuery = "WHERE OI.digital != '2'";
$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
if(isset($_GET['status'])){

	$sqlQuery = "WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.status = ".$db->mySQLsafe($_GET['status']);

} elseif(isset($_GET['oid'])) {

	if(empty($_GET['oid'])) {
	 	# Show all
		$sqlQuery = "";
	} else {
		$sqlQuery = "WHERE OI.cart_order_id = ".$db->mySQLsafe($_GET['oid']);
	}
} elseif(isset($_GET['customer_id']) && $_GET['customer_id']>0 && !isset($_GET['delete'])) {
	$sqlQuery = "WHERE ".$glob['dbprefix']."ImeiUnlock_customer.customer_id = ".$db->mySQLsafe($_GET['customer_id']);
}

if(isset($_POST['imei']) && $_POST['imei']!=""){
	 $sqlQuery = " WHERE OI.imei like '%".$_POST['imei']."%' AND OI.digital != '2'";
}
else if(isset($_POST['cart_order_id']) && $_POST['cart_order_id']!=""){
	
	 $sqlQuery = " WHERE OI.cart_order_id like '%".$_POST['cart_order_id']."%' AND OI.digital != '2'";
}
// query database

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 0;
}


$ordersPerPage = 25;

$query = "SELECT DISTINCT ".$glob['dbprefix']."ImeiUnlock_customer.customer_id, ".$glob['dbprefix']."ImeiUnlock_order_sum.status, ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id, time, title, firstName, ".$glob['dbprefix']."ImeiUnlock_customer.lastName, ip, prod_total, ".$glob['dbprefix']."ImeiUnlock_customer.email FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id ".$inner_inv.$sqlQuery." ORDER BY time DESC";

$results = $db->select($query, $ordersPerPage, $page);
$numrows = $db->numrows($query);
$exclude		= array('delete' => 1);
$pagination = paginate($numrows, $ordersPerPage, $page, "page", 'txtLink', 10, $exclude);
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap' class="pageTitle"><?php echo $lang['admin']['orders_orders']; ?></td>
     <?php if(!isset($_GET["mode"])){ ?><td align="right" valign="middle"></td><?php } ?>
  </tr>
</table>
<?php
if(isset($msg)){ 
	echo msg($msg); 
}
?>
<p class="copyText" style="padding:5px 0"><?php echo $lang['admin']['orders_all_orders']; ?></p>
<div style="padding:0 0 10px 0; float:left; width:100%;">
<form name="frm_imei" method="post" action="" style=" float:left">
<span class="left" style="margin-top: 10px; font-weight: bold;">IMEI:</span>
	<div class="inputbox"  style="width:150px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:140px"  maxlength="15" type="text" name="imei" id="imei" value="<?=$_POST['imei']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go">
</form>
<form name="frm_order_id" method="post" action="" style="float:left; margin-left:2px;">

<span class="left" style="margin-top: 10px; font-weight: bold;">By Order No: </span>
	<div class="inputbox"  style="width:150px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<input type="text" style="width:140px" name="cart_order_id" id="cart_order_id" value="<?=$_POST['cart_order_id']?>" />
 <span class="bgright"></span></div>
  <input type="submit" class="submit" value="Go">
</form>
<div style=" float:right; " >
<span class="left" style="margin-top: 10px; font-weight: bold;"><?php echo $lang['admin']['orders_filter']; ?> </span>
<div class="inputbox"  style="width:150px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<select name="status" style="width:140px;" class="dropDown" onchange="jumpMenu('parent',this,0)">
	<option value="<?php echo $glob['adminFile']; ?>?_g=orders/index">-- <?php echo $lang['admin_common']['all']; ?> --</option>
<?php 
	for($i=1; $i<=6; $i++){ 
?>
	<option value="<?php echo $glob['adminFile']; ?>?_g=orders/index&amp;status=<?php echo $i; ?>" <?php if($_GET['status']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['glob']['orderState_'.$i]; ?></option>
<?php 
	} 
?>
</select>
 <span class="bgright"></span></div>

</div>

</div>


<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
  <tr>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_order_no']; ?></td>
    <td  align="center" class="tdTitle"><?php echo $lang['admin']['orders_status']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_date_time']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_customer']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_ip_address']; ?></td>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_cart_total']; ?></td>
    <td class="tdTitle" align="center"><?php echo $lang['admin']['orders_action']; ?></td>
  </tr>
  <?php
  if($results == TRUE){
	for ($i=0; $i<count($results); $i++){  	

	$cellColor = "";
	$cellColor = cellColor($i);
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>"><a href="<?php echo $glob['adminFile']; ?>?_g=orders/orderBuilder&amp;edit=<?php echo $results[$i]['cart_order_id']; ?>" class="txtLink"><?php echo $results[$i]['cart_order_id']; ?></a></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php 
	echo $lang['glob']['orderState_'.$results[$i]['status']];
	?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo formatTime($results[$i]['time']); ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?>"><a href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;searchStr=<?php echo urlencode($results[$i]['email']); ?>" class="txtLink"><?php echo $results[$i]['title']." ".$results[$i]['firstName']." ".$results[$i]['lastName']; ?></a></td>
    <td align="center" class="<?php echo $cellColor; ?>"><a href="javascript:;" class="txtLink" onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=misc/lookupip&amp;ip=<?php echo $results[$i]['ip']; ?>','misc',300,130,'yes,resizable=yes')"><?php echo $results[$i]['ip']; ?></a></td>
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo priceFormat($results[$i]['prod_total'],true); ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?> a2">
	<a <?php if(permission("orders","delete")==TRUE){ ?>href="javascript:decision('<?php echo $lang['admin_common']['delete_q']; ?>','<?php echo $glob['adminFile']; ?>?_g=orders/index&amp;delete=<?php echo $results[$i]['cart_order_id']; ?>&customer_id=<?php echo $results[$i]['customer_id']; ?>');" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a>

	

	<a <?php if(permission("orders","write")==TRUE){ ?>href="<?php $glob['adminFile']; ?>?_g=orders/orderBuilder&amp;edit=<?php echo $results[$i]['cart_order_id']; ?>" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>
	</td>
  </tr>
  <?php } // end loop
  } else { ?>
   <tr>
    <td colspan="7" class="tdText"><?php echo $lang['admin']['orders_no_orders_in_db']; ?></td>
   </tr>
  <?php } ?>
</table>

<p class="" align="right"><span class="pagination"><?php echo $pagination; ?></span></p>