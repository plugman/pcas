<?php
/*

+--------------------------------------------------------------------------

|	index.php

|   ========================================

|	Manage Main Store Settings	

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }
$lang = getLang("admin".CC_DS."admin_settings.inc.php");

$lang = getLang("orders.inc.php");
session_start();
set_time_limit(0);

require_once ("includes/currencyVars.inc.php");

if(isset($_POST['exportCSV'])){
	$_SESSION['orderId'] = $_POST['orderId'];
}
if(isset($_POST['submit'])){
	$sqlQuery = "1=1";
if(!empty($_SESSION['orderId'])) {
	$arrOrderIds = "'";
	$arrOrderIds .= implode("','", $_SESSION['orderId']);
	$arrOrderIds .= "'";
	
	 $sqlQuery .= " AND OI.id IN (".$arrOrderIds.") ";
}

if(isset($_POST['submit'])){
 $query = "SELECT 
			C.customer_id, 
			OI.*,
			OI.extra_notes AS enotes,
			OI.name AS problem,
			OS.*, 
			R.title AS salesrep,
			H.title AS referal,
			C.email
		FROM ".$glob['dbprefix']."ImeiUnlock_order_sum AS OS LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_customer AS C ON ".$glob['dbprefix']."OS.customer_id = ".$glob['dbprefix']."C.customer_id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_sales_rep AS R ON ".$glob['dbprefix']."OS.salesrep = ".$glob['dbprefix']."R.id LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_referer AS H ON ".$glob['dbprefix']."OS.refered_by = ".$glob['dbprefix']."H.id INNER JOIN ImeiUnlock_order_inv AS OI ON OS.cart_order_id = OI.cart_order_id  WHERE ".$sqlQuery." AND OI.digital = '2' ORDER BY time DESC";
$orders = $db->select($query, $ordersPerPage, $page);
$_totalOrders = $db->numrows($query);
$fileContent = '';
$heading= '';
$fileContent .= '<table cellspacing="0" cellpadding="0">';
$heading .= '	<td bgcolor="#FFFF00"><strong>Order #</strong></td>';
if(isset($_POST['date']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Date</strong></td>';				
if(isset($_POST['phoneno']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Phone #</strong></td>';
if(isset($_POST['rep']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Rep</strong></td>';
if(isset($_POST['status']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Order Status</strong></td>';
if(isset($_POST['cname']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Customer Name</strong></td>';	
if(isset($_POST['email']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Email Address</strong></td>';
if(isset($_POST['postcode']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Post Code</strong></td>';
if(isset($_POST['suburb']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Suburb</strong></td>';
if(isset($_POST['state']))
$heading .= '	<td bgcolor="#FFFF00"><strong>State</strong></td>';
if(isset($_POST['referal']))
$heading .= '	<td bgcolor="#FFFF00"><strong>How did you find us? </strong></td>';
if(isset($_POST['ccoments']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Customer Comments</strong></td>';
if(isset($_POST['scoments']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Staff Comments</strong></td>';
if(isset($_POST['nstocu']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Notes to send to customer</strong></td>';
if(isset($_POST['pmethod']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Payment Method</strong></td>';
if(isset($_POST['phone']))
$heading .= '	<td bgcolor="#FFFF00"><strong>phone</strong></td>';
if(isset($_POST['device']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Device</strong></td>';
if(isset($_POST['model']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Model</strong></td>';
if(isset($_POST['problem']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Problem</strong></td>';
if(isset($_POST['price']))
$heading .= '	<td bgcolor="#FFFF00"><strong>price</strong></td>';
if(isset($_POST['suplied']))
$heading .= '	<td bgcolor="#FFFF00"><strong>suplied</strong></td>';
if(isset($_POST['fdate']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Estimated Fix Date</strong></td>';
if(isset($_POST['imei']))
$heading .= '	<td bgcolor="#FFFF00"><strong>IMEI</strong></td>';
if(isset($_POST['rcomments']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Coments</strong></td>';
if(isset($_POST['options']))
$heading .= '	<td bgcolor="#FFFF00"><strong>Other Options</strong></td>';						  
				  
	/*echo "<pre>";
	print_r($heading);
	die();*/			  
if (!empty($orders)) {	
	
	for ($i = 0; $i < $_totalOrders; $i++) {
		if(empty($orders[$i]['make'])){
	$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($orders[$i]['productId']));
	$tree = getmaketree($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
	$orders[$i]['make'] = $tree[0];
	$orders[$i]['device'] = $tree[1];
	$orders[$i]['model'] = $tree[2];
	$options = explode("\n", $orders[$i]['product_options']);
	$searchword = 'imei';
	$matches = array();
		foreach($options as $k=>$v) {
    	if(preg_match("/\b$searchword\b/i", $v)) {
        $imei[$k] = $v;
   		 }
}
	if(is_array($imei))
	$imei = array_values($imei);
	 $imei = explode(" - ", $imei[0]);
	$orders[$i]['imei'] = $imei[1];
	$searchword = 'Coments';
	$matches = array();
		foreach($options as $k=>$v) {
    	if(preg_match("/\b$searchword\b/i", $v)) {
        $coments[$k] = $v;
   		 }
}
	if($coments)
	$coments = array_values($coments);
	$coments = explode(" - ", $coments[0]);
	$orders[$i]['extra_notes'] = $coments[1];
	
	}
	
	$suplied = '';
		$num = $i + 1;
	$imei =	(string)$orders[$i]['imei'];
	$date = date("d - M - Y", $orders[$i]['time']);
	if($orders[$i]['battery'] == 1)
	$suplied .= "Battery , ";
	if($orders[$i]['sim'] == 1)
	$suplied .= "Sim Card , ";
	if($orders[$i]['battery'] == 1)
	$suplied .= "Memory Card , ";
$repeatcontent .= '<tr>			
				<td>'.$orders[$i]['cart_order_id'].'&nbsp;</td>';
if(isset($_POST['date']))
$repeatcontent .='<td>'.$date.'&nbsp;</td>';
if(isset($_POST['phoneno']))
$repeatcontent .= '<td>'.$orders[$i]['phone'].'&nbsp;</td>';
if(isset($_POST['rep']))
$repeatcontent .= '<td>'.$orders[$i]['salesrep'].'&nbsp;</td>';
if(isset($_POST['status']))
$repeatcontent .= '<td>'.$lang['glob']['repairState_'.$orders[$i]['stat']].'&nbsp;</td>';
if(isset($_POST['cname']))
$repeatcontent .= '<td>'.$orders[$i]['name'].'&nbsp;</td>';
if(isset($_POST['email']))
$repeatcontent .= '<td>'.$orders[$i]['email'].'&nbsp;</td>';
if(isset($_POST['postcode']))
$repeatcontent .= '<td>'.$orders[$i]['postcode'].'&nbsp;</td>';
if(isset($_POST['suburb']))
$repeatcontent .= '<td>'.$orders[$i]['suburb'].'&nbsp;</td>';
if(isset($_POST['state']))
$repeatcontent .= '<td>'.$orders[$i]['county'].'&nbsp;</td>';
if(isset($_POST['referal']))
$repeatcontent .= '<td>'.$orders[$i]['referal'].'&nbsp;</td>';
if(isset($_POST['ccoments']))
$repeatcontent .= '<td>'.$orders[$i]['customer_comments'].'&nbsp;</td>';
if(isset($_POST['scoments']))
$repeatcontent .= '<td>'.$orders[$i]['comments'].'&nbsp;</td>';
if(isset($_POST['nstocu']))
$repeatcontent .= '<td>'.$orders[$i]['extra_notes'].'&nbsp;</td>';
if(isset($_POST['pmethod']))
$repeatcontent .= '<td>'.$orders[$i]['gateway'].'&nbsp;</td>';
if(isset($_POST['phone']))
$repeatcontent .= '<td>'.$orders[$i]['make'].'&nbsp;</td>';
if(isset($_POST['device']))
$repeatcontent .= '<td>'.$orders[$i]['device'].'&nbsp;</td>';
if(isset($_POST['model']))
$repeatcontent .= '<td>'.$orders[$i]['model'].'&nbsp;</td>';
if(isset($_POST['problem']))
$repeatcontent .= '<td>'.$orders[$i]['problem'].'&nbsp;</td>';
if(isset($_POST['price']))
$repeatcontent .= '<td>'.$orders[$i]['price'].'&nbsp;</td>';
if(isset($_POST['suplied']))
$repeatcontent .= '<td>'.$suplied.'&nbsp;</td>';
if(isset($_POST['fdate']))
$repeatcontent .= '<td>'.$orders[$i]['fixdate'].'&nbsp;</td>';
if(isset($_POST['imei']))
$repeatcontent .= '<td>'.$orders[$i]['imei'].'&nbsp;</td>';
if(isset($_POST['rcomments']))
$repeatcontent .= '<td>'.$orders[$i]['enotes'].'&nbsp;</td>';
if(isset($_POST['options']))
$repeatcontent .= '<td>'.html_entity_decode($orders[$i]['product_options'], ENT_QUOTES, "UTF-8").'&nbsp;</td>';
														
$repeatcontent .= '</td></tr>';
	}
}
$tableclose .= '</table>';

$fileContent = $fileContent.$heading.$repeatcontent.$tableclose;
// $fileContent = html_entity_decode($fileContent, ENT_QUOTES, "UTF-8");
$fileContent =  utf8_decode($fileContent);
//$fileContent = str_replace(194, ' ', $fileContent);
$date = substr(md5(date(c)), 0, 8);
$filename = "Orders-Detail-" . $date;
}
header("Content-type: application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition: attachment; filename=$filename.xlsx");
header("Cache-control: private");
print "$fileContent";

exit();
}
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>

<script type="text/javascript">
$j(document).ready(function () {
    $j('#selectall').click(function () {
        $j('.selectedId').prop('checked', this.checked);
    });

    $j('.selectedId').change(function () {
        var check = ($j('.selectedId').filter(":checked").length == $j('.selectedId').length);
        $j('#selectall').prop("checked", check);
    });
});
</script>
<form name="updateSettings" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=repair/export">
<div class="setting">


<div class="tabs">
       
        
       
        <div  id="tab4">
           <div class="headingBlackbg">Available fields for export</div>

<table border="0" cellspacing="0" cellpadding="" class="mainTable" width="100%">
   <tr>
   <td colspan="2"><strong>Chose Fields to Export</strong></td>
   </tr>
   <tr>
   <td width="18%"><strong>Select All</strong></td>
   <td ><input type="checkbox" name="selectall" value="1" id="selectall" /></td>
   </tr>
   <tr><td colspan="2">
   <table border="0" cellspacing="0" cellpadding="" width="100%">
<tr>
<td width="18%">Order Date</td>
 <td><input type="checkbox" name="date" value="1" class="selectedId" /></td>
 <td width="18%" >Phone #</td>
 <td><input type="checkbox" name="phoneno" value="1" class="selectedId" /></td>
 <td width="18%" >Rep</td>
 <td><input type="checkbox" name="rep" value="1" class="selectedId" /></td>
 </tr>
 
  <tr>
<td >Order Status</td>
 <td><input type="checkbox" name="status" value="1" class="selectedId" /></td>
 <td  >Customer Name</td>
 <td><input type="checkbox" name="cname" value="1" class="selectedId" /></td>
 <td  >Email Address</td>
 <td><input type="checkbox" name="email" value="1" class="selectedId" /></td>
 </tr>
  <tr>
<td >Post Code</td>
 <td><input type="checkbox" name="postcode" value="1" class="selectedId" /></td>
 <td  >Suburb</td>
 <td><input type="checkbox" name="suburb" value="1" class="selectedId" /></td>
 <td  >State</td>
 <td><input type="checkbox" name="state" value="1" class="selectedId" /></td>
 </tr>
  <tr>
<td  >How did you find us? </td>
 <td><input type="checkbox" name="referal" value="1" class="selectedId" /></td>
 <td >Customer Comments</td>
 <td><input type="checkbox" name="ccoments" value="1" class="selectedId" /></td>
 <td  >Staff Comments</td>
 <td><input type="checkbox" name="scoments" value="1" class="selectedId" /></td>
 </tr>
  <tr>
<td >Notes to send to customer</td>
 <td><input type="checkbox" name="nstocu" value="1" class="selectedId" /></td>
 <td  >Payment Method</td>
 <td><input type="checkbox" name="pmethod" value="1" class="selectedId" /></td>
 <td  >phone</td>
 <td><input type="checkbox" name="phone" value="1" class="selectedId" /></td>
 </tr>
  <tr>
<td  >Device</td>
 <td><input type="checkbox" name="device" value="1" class="selectedId" /></td>
 <td  >Model</td>
 <td><input type="checkbox" name="model" value="1" class="selectedId" /></td>
 <td  >Problem</td>
 <td><input type="checkbox" name="problem" value="1" class="selectedId" /></td>
 </tr>
  <tr>
<td >Price</td>
 <td><input type="checkbox" name="price" value="1" class="selectedId" /></td>
 <td >Supplied</td>
 <td><input type="checkbox" name="suplied" value="1" class="selectedId" /></td>
 <td >Estimated Fix Date</td>
 <td><input type="checkbox" name="fdate" value="1" class="selectedId" /></td>
 </tr>
 <tr>
 <td >IMEI</td>
 <td><input type="checkbox" name="imei" value="1" class="selectedId" /></td>
 <td >Comments</td>
 <td><input type="checkbox" name="rcomments" value="1" class="selectedId" /></td>
<td >Other Options</td>
 <td><input type="checkbox" name="options" value="1" class="selectedId" /></td>
 </tr>
    
    </table>
    <input type="hidden" value="<?php $_POST['orderId']; ?>[]"  />
</td>
</tr>
    <tr>

<td  colspan=" 2"><div class="seprator2"></div>
	<input name="submit" type="submit" class="submit submit3" id="submit" value="<?php echo "Export to CSV";?>" /></td>

</tr>

    

    </table>
        </div>
        </div></div></form>