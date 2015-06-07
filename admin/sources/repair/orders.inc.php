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
	  $nooforder = $db->select("SELECT cart_order_id FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($_GET['delete']));
	  if(count($nooforder) > 1){
		 $where = "id = ".$db->mySQLSafe($_GET['repair']);

		$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where);
	  }else{
	$record['noOrders'] = "noOrders - 1";
	$where = "customer_id = ".$db->mySQLSafe($_GET['customer_id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);	

	$where = "cart_order_id = ".$db->mySQLSafe($_GET['delete']);

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_sum", $where);
	  
	

	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where); 
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Coupons", $where);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Downloads", $where);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_transactions", "`order_id` = ".$db->mySQLSafe($_GET['delete']));
}
if ($delete) {
		$msg = "<p class='infoText'>".$lang['admin']['orders_delete_success']."</p>";
	} else {
		$msg = "<p class='infoText'>".$lang['admin']['orders_delete_fail']."</p>";
	}
}

$sqlQuery = "";
$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = S.cart_order_id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_sales_rep as R ON S.salesrep = R.id ";
if(isset($_GET['status'])){

	$sqlQuery = "WHERE ".$glob['dbprefix']."OI.stat = ".$db->mySQLsafe($_GET['status'])." AND OI.digital = '2'";

} elseif(isset($_GET['sales_rep'])){

	$sqlQuery = "WHERE ".$glob['dbprefix']."S.salesrep = ".$db->mySQLsafe($_GET['sales_rep'])." AND OI.digital = '2'";

}elseif(isset($_GET['oid'])) {

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
	 $sqlQuery = " WHERE OI.imei like '%".$_POST['imei']."%' ";
}elseif(isset($_POST['name']) && $_POST['name']!=""){
	 $sqlQuery = " WHERE S.name like '%".$_POST['name']."%' AND OI.digital = '2'";
}elseif(isset($_POST['phone']) && $_POST['phone']!=""){
	 $sqlQuery = " WHERE S.phone like '%".$_POST['phone']."%' AND OI.digital = '2'";
}elseif(isset($_POST['date']) && $_POST['date']!=""){
	 $nextday = date('Y-m-d', strtotime("+1 day", strtotime($_POST['date'])));
	 $sqlQuery = " WHERE S.time > ".strtotime($_POST['date'])." AND S.time < ".strtotime($nextday). " AND OI.digital = '2'";
}
else if(isset($_POST['cart_order_id']) && $_POST['cart_order_id']!=""){
	 $sqlQuery = " WHERE OI.cart_order_id like '%".$_POST['cart_order_id']."%' AND OI.digital = '2'";
}else if(isset($_POST['pmodel']) && $_POST['pmodel']!=""){
	 $sqlQuery = " WHERE OI.model like '%".$_POST['pmodel']."%' AND OI.digital = '2'";
}else if(isset($_POST['pbrand']) && $_POST['pbrand']!=""){
	 $sqlQuery = " WHERE OI.make like '%".$_POST['pbrand']."%' AND OI.digital = '2'";
}else if(isset($_POST['fname']) && $_POST['fname']!=""){
	 $sqlQuery = " WHERE S.name like '%".$_POST['fname']."%' AND OI.digital = '2'";
}else if(isset($_POST['lname']) && $_POST['lname']!=""){
	 $sqlQuery = " WHERE S.lastName like '%".$_POST['lname']."%' AND OI.digital = '2'";
}if(!isset($_GET['oid']) && !isset($_GET['customer_id']) &&  $_POST['imei'] == "" &&  $_POST['phone'] == "" && $_POST['cart_order_id']=="" && !isset($_GET['status']) && !isset($_GET['sales_rep']) &&  $_POST['name'] == "" &&  $_POST['date'] == "" &&  $_POST['pmodel'] == "" &&  $_POST['pbrand'] == ""  &&  $_POST['fname'] == "" &&  $_POST['lname'] == "" ){
$sqlQuery .= " WHERE OI.digital = '2'";
}

// query database

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 0;
}


$ordersPerPage = 25;

 $query = "SELECT S.status, S.cart_order_id, S.time, S.name as customer, S.prod_total,R.title, S.email,OI.name,OI.price,OI.make,OI.device,OI.model,OI.imei,OI.extra_notes,OI.product_options,OI.id,OI.stat FROM ".$glob['dbprefix']."ImeiUnlock_order_sum AS S  ".$inner_inv.$sqlQuery."  ORDER BY time DESC";

$results = $db->select($query, $ordersPerPage, $page);
$numrows = $db->numrows($query);
$exclude		= array('delete' => 1);
$pagination = paginate($numrows, $ordersPerPage, $page, "page", 'txtLink', 10, $exclude);
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap' class="pageTitle"><?php echo $lang['admin']['orders_orders']; ?></td>
     <?php if(!isset($_GET["mode"])){ ?><td align="right" valign="middle"><a <?php if(permission("orders","write")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/orderBuilder" class="txtLink" <?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new'];?></a></td><?php } ?>
  </tr>
</table>
<?php
if(isset($msg)){ 
	echo msg($msg); 
}
?>
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/repair_sec.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $GLOBALS['rootRel']; ?>/uploads/calendar/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>/uploads/calendar/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){		
		new JsDatePick({
			useMode:2,
			target:"date",
			yearsRange:[1899,2050],
			dateFormat:"%Y-%m-%d"
			
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
		
	};
</script>
<p class="copyText" style="padding:5px 0"><?php echo $lang['admin']['orders_all_orders']; ?></p>
<table width="100%" cellpadding="0" cellspacing="10">
<tr>

<td align="center" valign="top"><form name="frm_order_id" method="post" action="">

<span class="left" style="margin-top: 10px; font-weight: bold;">REF/ Order #: </span>
<div style=" float:right">
	<div class="inputbox"  style="width:110px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<input type="text" style="width:100px" name="cart_order_id" id="cart_order_id" value="<?=$_POST['cart_order_id']?>" />
 <span class="bgright"></span></div>
  <input type="submit" class="submit" value="Go"></div>
</form></td>
<td align="center" valign="top"><form name="frm_imei" method="post" action="">
<span class="left" style="margin-top: 10px; font-weight: bold;">IMEI:</span>
<div style=" float:right">
	<div class="inputbox"  style="width:110px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:100px"  maxlength="15" type="text" name="imei" id="imei" value="<?=$_POST['imei']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go">
   </div>
</form></td>
<td align="center" valign="top"><div >
<span class="left" style="margin-top: 10px; font-weight: bold;"><?php echo $lang['admin']['orders_filter']; ?> </span>
<div style=" float:right">
<div class="inputbox"  style="width:183px;">
    <span class="bgleft"  ></span>
<select name="status" style="width:174px;" class="dropDown" onchange="jumpMenu('parent',this,0)">
	<option value="<?php echo $glob['adminFile']; ?>?_g=repair/orders">-- <?php echo $lang['admin_common']['all']; ?> --</option>
<?php 

	for($i=1; $i<=4; $i++){ 
?>
	<option value="<?php echo $glob['adminFile']; ?>?_g=repair/orders&amp;status=<?php echo $i; ?>" <?php if($_GET['status']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['glob']['repairState_'.$i]; ?></option>
<?php 
	} 
?>
</select>
 <span class="bgright"></span></div>
</div>
</div></td>
</tr>
<tr>
<td align="center" valign="top"><div >
<span class="left" style="margin-top: 10px; font-weight: bold;"><?php echo "Sales Rep"; ?> </span>
<div style=" float:right">
<div class="inputbox"  style="width:183px;">
    <span class="bgleft"  ></span>
<select name="refer" style="width:174px;" class="dropDown" onchange="jumpMenu('parent',this,0)">
	<option value="<?php echo $glob['adminFile']; ?>?_g=repair/orders">-- <?php echo $lang['admin_common']['all']; ?> --</option>
<?php

	  $sales_rep = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_sales_rep WHERE hide = '1'");
	  for($j=0; $j<count($sales_rep); $j++){
	?>
	<option value="<?php echo $glob['adminFile']; ?>?_g=repair/orders&amp;sales_rep=<?php echo $sales_rep[$j]['id']; ?>" <?php if($_GET['sales_rep']== $sales_rep[$j]['id']) { echo "selected='selected'"; } ?>><?php echo $sales_rep[$j]['title']; ?></option>
	<?php } ?>
</select>
 <span class="bgright"></span></div>
</div>
</div></td>
<td align="center" valign="top"><form name="frm_imei" method="post" action="">
<span class="left" style="margin-top: 10px; font-weight: bold;">Name:</span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:100px"  maxlength="15" type="text" name="name" id="name" value="<?=$_POST['name']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go"></div>
</form></td>
<td align="center" valign="top"><form name="frm_Date" method="post" action="" >

<span class="left" style="margin-top: 10px; font-weight: bold;">Date: </span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<input type="text" style="width:100px; float:none" name="date" id="date" value="<?=$_POST['date']?>" />
 <span class="bgright"></span></div>
  <input type="submit" class="submit" value="Go"></div>
</form></td>
</tr>
<tr>

<td align="center" valign="top"><form name="frm_Phone" method="post" action="">
<span class="left" style="margin-top: 10px; font-weight: bold;">Phone #:</span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:100px"  maxlength="15" type="text" name="phone" id="phone" value="<?=$_POST['phone']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go"></div>
</form></td>
<td align="center" valign="top"><form name="frm_model" method="post" action="">
<span class="left" style="margin-top: 10px; font-weight: bold;">Phone Model :</span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:100px"   type="text" name="pmodel" id="name" value="<?=$_POST['pmodel']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go"></div>
</form></td>
<td align="center" valign="top"><form name="frm_Brand" method="post" action="" >

<span class="left" style="margin-top: 10px; font-weight: bold;">Brand: </span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<input type="text" style="width:100px; float:none" name="pbrand" id="date" value="<?=$_POST['pbrand']?>" />
 <span class="bgright"></span></div>
  <input type="submit" class="submit" value="Go"></div>
</form></td>
</tr>
<tr>


<td align="center" valign="top"><form name="frm_fname" method="post" action="">
<span class="left" style="margin-top: 10px; font-weight: bold;">First Name :</span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
 <input style="width:100px"   type="text" name="fname" id="fname" value="<?=$_POST['fname']?>" />
 <span class="bgright"></span></div>
 
   <input type="submit" class="submit" value="Go"></div>
</form></td>
<td align="center" valign="top"><form name="frm_lname" method="post" action="" >

<span class="left" style="margin-top: 10px; font-weight: bold;">Last Name: </span>
<div style=" float:right">
	<div class="inputbox"  style="width:111px; margin:0 5px 0 8px">
    <span class="bgleft"  ></span>
<input type="text" style="width:100px; float:none" name="lname" id="lname" value="<?=$_POST['lname']?>" />
 <span class="bgright"></span></div>
  <input type="submit" class="submit" value="Go"></div>
</form></td>
<td >&nbsp;</td>
</tr>
</table>
<div style="padding:0 0 10px 0; float:left; width:100%;">

<form action="<?php echo $glob['adminFile']; ?>?_g=repair/export" method="post">
<input name="exportCSV" type="submit" class="submit" value="Export Orders to CSV" style="float:right; margin-right:10px; width:183px;" />




</div>


<table class="mainTable mainTable4" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
  <tr>
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_order_no']; ?></td>
    <td align="center" class="tdTitle"><?php echo "Phone"; ?></td>
	<td align="center" class="tdTitle"><?php echo "Problem"; ?></td>
   <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_date_time']; ?></td>
   <td align="center" class="tdTitle"><?php echo "Rep"; ?></td> 
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_customer']; ?></td>
     <td  align="center" class="tdTitle"><?php echo $lang['admin']['orders_status']; ?></td> 
    <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_cart_total']; ?></td>
    <td class="tdTitle" align="center"><?php echo $lang['admin']['orders_action']; ?></td>
  </tr>
  <?php
  if($results == TRUE){
	for ($i=0; $i<count($results); $i++){  	
	if(empty($results[$i]['make'])){
	$options = explode("\n", $results[$i]['product_options']);
	$searchword = 'Device';
	$matches = array();
		foreach($options as $k=>$v) {
    	if(preg_match("/\b$searchword\b/i", $v)) {
        $matches[$k] = $v;
   		 }
}	
		if($matches)
	 $matches = array_values($matches);
	 $Device =explode(" - ", $matches[0]);
	$phone  = $Device[1];
	}else
	$phone = $results[$i]['make']. ' ' . $results[$i]['device']. ' ' . $results[$i]['model'];
	$cellColor = "";
	$cellColor = cellColor($i);
  ?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?>"><input type="checkbox" class="selectOrder" name="orderId[<?php echo $i ?>]" value="<?php echo $results[$i]['id']?>" style="vertical-align:sub"/> <a href="<?php echo $glob['adminFile']; ?>?_g=repair/orderBuilder&amp;edit=<?php echo $results[$i]['cart_order_id']; ?>&amp;repair=<?php echo $results[$i]['id']; ?>" class="txtLink"><?php echo $results[$i]['cart_order_id']; ?></a></td>
    
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $phone; ?></span></td>
    
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['name']; ?></span></td>
    
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo date("d - M - Y", $results[$i]['time']); ?></span></td>
    
     <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['title']; ?></span></td>
     
     <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo $results[$i]['customer']; ?></span></td>
     
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php 	echo $lang['glob']['repairState_'.$results[$i]['stat']];
	?></span></td>
    
    
    
    <td align="center" class="<?php echo $cellColor; ?>"><span class="copyText"><?php echo priceFormat($results[$i]['price'],true); ?></span></td>
    <td align="center" class="<?php echo $cellColor; ?> a2">
	<a <?php if(permission("orders","delete")==TRUE){ ?>href="javascript:decision('<?php echo $lang['admin_common']['delete_q']; ?>','<?php echo $glob['adminFile']; ?>?_g=repair/orders&amp;delete=<?php echo $results[$i]['cart_order_id']; ?>&amp;repair=<?php echo $results[$i]['id']; ?>');" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a>

	

	<a <?php if(permission("orders","write")==TRUE){ ?>href="<?php $glob['adminFile']; ?>?_g=repair/orderBuilder&amp;edit=<?php echo $results[$i]['cart_order_id']; ?>&amp;repair=<?php echo $results[$i]['id']; ?>" class="txtLink" <?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>
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