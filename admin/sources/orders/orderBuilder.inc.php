<?php
/*
+--------------------------------------------------------------------------
|	orderBuilder.inc.php
|   ========================================
|	Ability to add/edit orders
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_orders.inc.php");
$lang = getLang("orders.inc.php");

require_once "classes".CC_DS."cart".CC_DS."order.php";
$order = new order();
require_once "classes".CC_DS."cart".CC_DS."accessries.php";
$accessries = new accessorder();
require_once "classes".CC_DS."cart".CC_DS."digital.php";
$digital = new digitalorder();
require $glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php";
permission("orders", "write", true);

if (isset($_GET['reset']) && $_GET['reset']>0) {
	$record['noDownloads']	= 0;
	$record['expire']		= time()+$config['dnLoadExpire'];
	
	$where	= 'id = '.$_GET['reset'];
	$update	= $db->update($glob['dbprefix']."ImeiUnlock_Downloads", $record, $where);
	
	httpredir($glob['adminFile'].'?_g=orders/orderBuilder&edit='.$_GET['edit']);
}

if (isset($_POST['cart_order_id']) && !isset($_POST['prodRowsSubmit']) && $_POST['customer_id']>0) {
	
	$cache = new cache();
	$cache->clearCache();
	// ORDER INVENTORY
	// ORDER SUMMARY
	$newOrderSum['cart_order_id'] 	= $db->mySQLSafe($_POST['cart_order_id']);
	$newOrderSum['customer_id'] 	= $db->mySQLSafe($_POST['customer_id']);
	/*$newOrderSum['name'] 			= $db->mySQLSafe($_POST['name']);
	$newOrderSum['add_1'] 			= $db->mySQLSafe($_POST['add_1']);
	$newOrderSum['add_2'] 			= $db->mySQLSafe($_POST['add_2']);
	$newOrderSum['town'] 			= $db->mySQLSafe($_POST['town']);
	$newOrderSum['county'] 			= $db->mySQLSafe($_POST['county']);
	$newOrderSum['postcode'] 		= $db->mySQLSafe($_POST['postcode']);
	$newOrderSum['country'] 		= $db->mySQLSafe($_POST['country']);
	$newOrderSum['name_d'] 			= $db->mySQLSafe($_POST['name_d']);
	$newOrderSum['companyName'] 	= $db->mySQLSafe($_POST['companyName']);
	$newOrderSum['companyName_d'] 	= $db->mySQLSafe($_POST['companyName_d']);
	$newOrderSum['add_1_d'] 		= $db->mySQLSafe($_POST['add_1_d']);
	$newOrderSum['add_2_d'] 		= $db->mySQLSafe($_POST['add_2_d']);
	$newOrderSum['town_d'] 			= $db->mySQLSafe($_POST['town_d']);
	$newOrderSum['county_d'] 		= $db->mySQLSafe($_POST['county_d']);
	$newOrderSum['postcode_d'] 		= $db->mySQLSafe($_POST['postcode_d']);
	$newOrderSum['country_d'] 		= $db->mySQLSafe($_POST['country_d']);
	$newOrderSum['phone'] 			= $db->mySQLSafe($_POST['phone']);
	$newOrderSum['mobile'] 			= $db->mySQLSafe($_POST['mobile']);*/
		// removed as this is done further down $newOrderSum['status'] = $db->mySQLSafe($_POST['status']);
	/*$newOrderSum['comments'] 		= $db->mySQLSafe($_POST['comments']);
	$newOrderSum['customer_comments'] = $db->mySQLSafe($_POST['customer_comments']);*/
	$newOrderSum['extra_notes'] 	= $db->mySQLSafe($_POST['extra_notes']);
	/*$newOrderSum['email'] 			= $db->mySQLSafe($_POST['email']);
	$newOrderSum['ship_date'] 		= $db->mySQLSafe($_POST['ship_date']);
	$newOrderSum['shipMethod'] 		= $db->mySQLSafe($_POST['shipMethod']);
	$newOrderSum['gateway'] 		= $db->mySQLSafe($_POST['gateway']);*/

	$newOrderSum['courier_tracking'] = $db->mySQLsafe($_POST['courier_tracking']);
	
	if (isset($_GET['edit'])) {
		
		$where = "cart_order_id = ".$db->mySQLSafe($_GET['edit']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_order_sum", $newOrderSum, $where);
		
		if (isset($_POST['cc_delete'])) {
			$record['offline_capture'] = "''";
			$db->update($glob['dbprefix'].'ImeiUnlock_order_sum', $record, array('customer_id' => $_POST['customer_id'], 'cart_order_id' => $_POST['cart_order_id']));
		## If not under SSL card fileds defalt to "xxx"
		} elseif($_POST['card_type']!=="xxx") {
			if (isset($_POST['card_number'])) {
				$cardData = array(
					'card_type'		=> $_POST['card_type'],
					'card_number'	=> $_POST['card_number'],
					'card_expire'	=> $_POST['card_expire'],
					'card_valid'	=> $_POST['card_valid'],
					'card_issue'	=> $_POST['card_issue'],
					'card_cvv'	=> $_POST['card_cvv'],
				);
				if (function_exists('mcrypt_module_open')) {
					require_once("classes".CC_DS."cart".CC_DS."encrypt.inc.php");
					$keyArray = array($_POST['cart_order_id']);
					$crypt = new encryption($keyArray);
					$record['offline_capture'] = "'".base64_encode($crypt->encrypt(serialize($cardData)))."'";
				}
				$db->update($glob['dbprefix'].'ImeiUnlock_order_sum', $record, array('customer_id' => $_POST['customer_id'], 'cart_order_id' => $_POST['cart_order_id']));
			}
		}
			
		if ($update == true) {
			$msg .= "<p class='infoText'>".sprintf($lang['admin']['orders_updated_successfully'],$_GET['edit'])."</p>"; 
		}/* else {
			$msg .= "<p class='warnText'>".sprintf($lang['admin']['orders_update_failed'], $_GET['edit'])."</p>"; 
		} */
	
	} else {
		$newOrderSum['ip'] = $db->mySQLSafe(get_ip_address());
		$newOrderSum['time'] = $db->mySQLSafe(time());
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_order_sum", $newOrderSum);
		
		if ($_POST['customer_id']>0) {
			$record['noOrders'] = "noOrders + 1";
			$where = "customer_id = ".$_POST['customer_id'];
			$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);
		}
		
		if ($insert == true) {
			$msg .= "<p class='infoText'>".sprintf($lang['admin']['orders_add_success'],$_POST['cart_order_id'])."</p>";
			// send email confirmation
			$order->newOrderEmail($_POST['cart_order_id']); 
		} else {
			$msg .= "<p class='warnText'>".sprintf($lang['admin']['orders_add_fail'],$_POST['cart_order_id'])."</p>"; 
		}
	}
	
	// update order status email etc
	$order->orderStatus($_POST['status'], $_POST['cart_order_id'], true);
		if ($_POST['cart_order_id']!==$_GET['edit']) {
		httpredir($glob['adminFile']."?_g=orders/orderBuilder&edit=".$_POST['cart_order_id']);
	}
}
	if (count($_POST['prodName'])>0) {					
		for ($i=0;$i<=count($_POST['prodName']);$i++) {
			if ((!empty($_POST['prodName'][$i]) && !empty($_POST['quantity'][$i]) && !empty($_POST['price'][$i])) || $_POST['delId'][$i] == 1) {
				//$newOrderInv['stat'] = $db->mySQLSafe($_POST['stat'][$i]);
				$newOrderInv['extra_notes'] = $db->mySQLSafe($_POST['extra_notes'][$i]);
				if ($_POST['delId'][$i] == 1) {
					$where = "id = ".$db->mySQLSafe($_POST['id'][$i]);
					$delete =  $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where);
				} else if ($_POST['id'][$i]>0) {
					$where = "id = ".$db->mySQLSafe($_POST['id'][$i]);
				 	$update = $db->update($glob['dbprefix']."ImeiUnlock_order_inv", $newOrderInv, $where);
					if($_POST['type'][$i] == 1){
						## update status and email
						$digital->orderStatus($_POST['stat'][$i], $_POST['cart_order_id'], $_POST['id'][$i], $_POST['refund'][$i]);
					}elseif($_POST['type'][$i] == 0){
						## update status and email
						$accessries->orderStatus($_POST['stat'][$i], $_POST['cart_order_id'], $_POST['id'][$i], $_POST['refund'][$i]);
					}
				}
					if ($update == true) {
			$msg = "<p class='infoText'>".sprintf($lang['admin']['orders_updated_successfully'],$_GET['edit'])."</p>";	
			$cart_order_id[$i]['orderid'] = $orderInv[$i]['cart_order_id'];

			}
							
		}
		}
		

	
	$newOrderSum2['subtotal'] 		= $db->mySQLSafe($_POST['subtotal']);
	$newOrderSum2['discount'] 		= $db->mySQLSafe($_POST['discount']);
	$newOrderSum2['prod_total'] 		= $db->mySQLSafe($_POST['prod_total']);
//	$newOrderSum2['extra_notes'] 		= $db->mySQLSafe($_POST['extra_notes']);
	
	
	if(!empty($_POST['tax1_disp'])) $newOrderSum2['tax1_disp'] = $db->mySQLSafe($_POST['tax1_disp']); 	
	if(!empty($_POST['tax1_amt'])) 	$newOrderSum2['tax1_amt'] = $db->mySQLSafe($_POST['tax1_amt']); 	
	if(!empty($_POST['tax2_disp'])) $newOrderSum2['tax2_disp'] = $db->mySQLSafe($_POST['tax2_disp']); 	
	if(!empty($_POST['tax2_amt'])) 	$newOrderSum2['tax2_amt'] = $db->mySQLSafe($_POST['tax2_amt']);
	if(!empty($_POST['tax3_disp'])) $newOrderSum2['tax3_disp'] = $db->mySQLSafe($_POST['tax3_disp']); 	
	if(!empty($_POST['tax3_amt'])) 	$newOrderSum2['tax3_amt'] = $db->mySQLSafe($_POST['tax3_amt']);
	
	if(!isset($_POST['total_tax'])) { $_POST['total_tax'] = $_POST['tax1_amt'] + $_POST['tax2_amt'] + $_POST['tax3_amt']; }
	
	$newOrderSum2['total_tax'] 		= $db->mySQLSafe($_POST['total_tax']);
	$newOrderSum2['total_ship'] 		= $db->mySQLSafe($_POST['total_ship']);
	if (isset($_GET['edit'])) {
		
		$where = "cart_order_id = ".$db->mySQLSafe($_GET['edit']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_order_sum", $newOrderSum2, $where);
	if ($update == true){
			$msg .= "<p class='infoText'>".sprintf($lang['admin']['orders_updated_successfully'],$_GET['edit'])."</p>"; 
		}
	}
	}
if (isset($_GET['edit'])) {	
	$orderSum = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE cart_order_id = ".$db->mySQLSafe($_GET['edit']));
	//$orderInv = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($_GET['edit'])." AND digital != '2'");
	$orderInv = $db->select("SELECT I.*, P.api_status, P.vendor, P.mapid FROM ".$glob['dbprefix']."ImeiUnlock_order_inv I  INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory P  ON I.productId = P.productId WHERE I.cart_order_id = ".$db->mySQLSafe($_GET['edit']) ."AND I.digital != '2'");
	if(!$orderInv){
		$orderInv = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($_GET['edit'])." AND digital != '2'");
	}
}

if (count($orderInv) < 1 && !empty($_GET['edit'])) {
	$msg .= "<p class='warnText'>".sprintf($lang['admin']['orders_no_products'], $_GET['edit'])."</p>"; 
}
$sql = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `type` > 0";
$noCustomers = $db->numrows($sql);
## Work around to change the drop dowm menu to a text box if there are over 500 customers. Current 
## solution drastically slows or even halts the page. Ajax lookup required. 
## See bug 1212
if($noCustomers<500) {
	$customers = $db->select($sql." ORDER BY lastName, firstName ASC");
}	
$countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries");

if ($countries == true) {
	$countriesArray = array();
	for($i=0;$i<=count($countries);$i++){
		$countriesArray[$countries[$i]['id']] = $countries[$i]['printable_name'];
	}
}

if(isset($_GET['PayPal-Pro']) && !empty($_GET['PayPal-Pro'])) {
	
	// Get Module Config
	$module = fetchDbConfig("PayPal_Pro");
	
	$basePPPath = "modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."wpp-".str_replace(array('ECO','DPO'),'',$module['mode']).CC_DS;
	
	$order_id = $_GET['edit'];
	
	$ppfunction = preg_replace('#[^a-z]#i', '', $_GET['PayPal-Pro']);
	
	switch($ppfunction) {
		
		case "doCapture":
			require_once($basePPPath."DoCaptureReceipt.php");
		break;
		
		case "doAuth":
		case "doReAuth":
			require_once($basePPPath."DoReauthorizationReceipt.php");
		break;
		
		case "doRefund":
			require_once($basePPPath."RefundReceipt.php");
		break;
		
		case "doVoidAuth":
			require_once($basePPPath."DoVoidReceipt.php");
		break;
		
		case "doFMF":
			require_once($basePPPath."ManagePendingTransactionStatus.php");
		break;
	
	
	}
}

require_once($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>

<!--<p class="pageTitle">
  <?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?>
<?php echo $lang['admin']['orders_order'];?></p>-->
<?php if (isset($msg)) echo $msg; ?>
<!--<p>
  <input type="button" class="submit" onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=orders/print&amp;cart_order_id=<?php echo $_GET['edit']; ?>', 'PrintSlip', 600, 550, '1,toolbar=1')" value="<?php echo $lang['admin']['orders_print_packing_slip'];?>" />
</p>-->
<?php 
## Discontinued from 4.1.0 final onwards
//if(getCountryFormat($config['siteCountry'],'id','iso')=="GB") { 
?>
<!--
<div style="border:#666666 1px solid; background:#FFFFFF; padding: 5px; width: 400px; margin-bottom: 10px;"><strong><a href="http://labels.xxxxx.xxxx" target="_blank"><img src="images/admin/integratedPackSlip.jpg" align="left" alt="Integrated Packing Slip Icon" width="86" height="94" hspace="3" border="0" /></a>Single Integrated Label Sheets</strong><br />
  Speed up the dispatch process with compatible single integrated label sheets. These can be used to peel off a delivery address which can be stuck straight on to the package. <br />
  <span style="float: right;"><a href="http://labels.xxxxx.xxxx" target="_blank" class="txtLink">Purchase</a> | <a href="http://labels.xxxxx.xxxx" target="_blank" class="txtLink">Learn More &raquo;</a></span><br clear='all' />
</div>
-->
<?php
//}
?>
<script type="text/javascript">
function toogleinfo(id, button){
		if($j("#"+button).val() == '+'){
			$j("#"+id).toggle();
			$j("#"+button).val("-");
		}else{
			$j("#"+id).toggle();
			$j("#"+button).val("+");
		}
	}
	</script>
<form action="<?php echo $glob['adminFile']; ?>?_g=orders/orderBuilder<?php if(isset($_GET['edit'])) { echo "&amp;edit=".$_GET['edit']; } ?>" method="post" enctype="multipart/form-data" name="orderBuilder" target="_self">

<div class="order">
	<div class="ordertop">
    <div class="hright">
    Customer Information</div>
    <div class="hright">
    Delivery Address</div>
     <input type="button" class="togbutton" id="ocinfob" onclick="toogleinfo('ocinfo','ocinfob');" value="+" style=" margin-top:7px;">
    </div>
    <div  id="ocinfo">
     <div class="oleft" style="background:none" >
    <table border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="105" align="right"><?php echo $lang['admin']['orders_name']; ?></td>
    <td> <?php echo $orderSum[0]['name']; ?></td>
  </tr>
  <tr>
  	<td align="right"><?php echo $lang['admin']['orders_email']; ?></td>
    <td ><span class="txt-purple "><?php echo $orderSum[0]['email']; ?></span></td>
  </tr>
   <tr>
  	<td align="right"><?php echo $lang['admin']['orders_phone'];?></td>
    <td ><?php echo $orderSum[0]['phone']; ?></td>
  </tr>

</table>
</div>
<div class="oright" style="background:none; min-height:140px;padding: 10px 0 10px 20px;">
    <table border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="105" align="right"><strong>Address:</strong></td>
    <td> <?php echo $orderSum[0]['add_1_d']; ?></td>
  </tr>
  <tr>
  	<td align="right"><strong>Town/City:</strong></td>
    <td ><?php echo $orderSum[0]['town_d']; ?></td>
  </tr>
   <tr>
  	<td align="right"><strong>Zip:</strong></td>
    <td ><?php echo $orderSum[0]['postcode_d']; ?></td>
  </tr>
   <tr>
  	<td align="right"><strong>State:</strong></td>
    <td ><?php echo $orderSum[0]['county_d']; ?></td>
  </tr>
</table>
	</div>
	</div>
    
    
    
    <div class="ordertop" style="margin-bottom:0px;">
    
     <div class="hright">
    Order Information</div>
    <input type="button" class="togbutton" id="ooinfob" onclick="toogleinfo('ooinfo','ooinfob');" value="+" style=" margin-top:7px;">
    </div>
  <div class="oright" style="width:100%" id="ooinfo">
  	<table  border="0" cellspacing="10" cellpadding="0">
      <tr>
        <td align="right" width="130">
        <strong>
		<?php echo $lang['admin_common']['other_order_no']; ?>
         <?php echo $orderSum[0]['cart_order_id']; ?>
        </strong>
      
        </td>
        <td align="right"><?php echo formattime($orderSum[0]['time']); ?><input type="hidden" value="<?php echo $orderSum[0]['cart_order_id']; ?>" name="cart_order_id" /></td>
      </tr>
      <tr>
      	<td align="right"><strong><?php echo $lang['admin']['orders_status']; ?> </strong>      </td>
        <td>
        <div class="inputbox" style="width:300px;"><span class="bgleft"></span>
        	<select name="status" class="dropDown" style="width:290px">
          <?php
		for ($i=1; $i<=6; $i++) {
		?>
          <option value="<?php echo $i; ?>" <?php if($orderSum[0]['status']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['glob']['orderState_'.$i]; ?></option>
          <?php 
		} 
		?>
        </select>
         <input type="hidden" name="customer_id" class="textbox" value="<?php echo isset($_POST['customer_id']) ? $_POST['customer_id'] : $orderSum[0]['customer_id']; ?>" />
        <span class="bgright"></span></div>
        </td>
      </tr>
      <tr>
      <td align="right"><strong><?php echo $lang['admin']['orders_payment_method']; ?></strong></td>
      <td class="tdText"><?php if(strstr($orderSum['0']['gateway'], "PayPal Website Payments Pro")) { ?>
              <input type="hidden" name="gateway" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />
              <?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>
              <?php } else { ?>
              <input type="text" name="gateway" class="textbox" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />
              <?php
	  }
	  ?></td>
      </tr>
      <tr>
      <td  align="right"><strong><?php echo $lang['admin']['orders_ship_method']; ?> </strong></td>
      <td  ><input type="text" name="shipMethod" class="textbox" value="<?php echo str_replace("_"," ",$orderSum['0']['shipMethod']); ?>" /></td>
    </tr>
       <tr>
      	<td align="right"> <strong><?php echo $lang['admin']['orders_customer_comments']; ?></strong>       </td>
        <td>
        
        <textarea name="customer_comments"  cols="1" rows="1" class="textareasmall"><?php echo $orderSum['0']['customer_comments']; ?></textarea></td>
      </tr>
       <tr>
      	<td align="right"><strong><?php echo $lang['admin']['orders_staff_comments']; ?></strong>        </td>
        <td><textarea name="comments" cols="1" rows="1" class="textareasmall"><?php echo $orderSum['0']['comments']; ?></textarea></td>
      </tr>
       <tr>
      	<td align="right"><strong><?php echo $lang['admin']['orders_extra_notes']; ?></strong>        </td>
        <td><textarea name="extra_notes" cols="1" rows="1" class="textareasmall"><?php echo $orderSum['0']['extra_notes']; ?></textarea></td>
      </tr>
       <tr>
      	<td align="right">        </td>
        <td>
        <?php if(permission("orders","edit")==TRUE){ ?><input type="submit" name="submit22" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['orders_order'];?>"  class="submit" /><?php }  ?>
        </td>
      </tr>
 
  </table>

  </div>
      
      
</div>
<div class="clear"></div>

</form>

<form action="<?php echo $glob['adminFile']; ?>?_g=orders/orderBuilder<?php if(isset($_GET['edit'])) { echo "&amp;edit=".$_GET['edit']; } ?>" method="post" enctype="multipart/form-data" name="orderBuilder" target="_self">
 <table class="mainTable mainTable4" style="margin-bottom:0" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
    <tr>
      <td align="center" class="tdTitle" width="50" ></td>
      <td align="center" class="tdTitle" >Phone Case</td>
      <!--<td align="center" class="tdTitle">Case Details</td>-->
      <td align="center" class="tdTitle">Download Design</td>
      <td align="center" class="tdTitle">Notes to send to customers</td>
 <!-- <td class="tdTitle"><?php echo $lang['admin']['orders_options'];?></td>-->
      <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_quantity'];?></td>
      <td  align="center" class="tdTitle"><?php echo $lang['admin']['orders_price'];?></td>
      <td  align="center" class="tdTitle"><?php echo "Order Status";?></td>
      <!-- <td  align="center" class="tdTitle"><?php echo "Refund";?></td>-->
    </tr>
    <?php
  $rows = (isset($_GET['edit'])) ? count($orderInv) : 1;
  if (is_numeric($_POST['prodRowsAdd']) && isset($_POST['prodRowsSubmit']))  $rows=($_POST['prodRowsAdd']+$_POST['currentRowCount']);
  
  for ($i=0; $i<$rows; $i++) {
	$cellColor = "";
	$cellColor = cellColor($i);
	
	if (!isset($orderInv[$i])) {
		$orderInv[$i] = array (
			'name' => $_POST['prodName'][$i],
			'productCode' => $_POST['productCode'][$i],
			'product_options' => $_POST['product_options'][$i],
			'quantity' => $_POST['quantity'][$i],
			'imei' => $_POST['imei'][$i],
			'price' => $_POST['price'][$i],
			'stat' => $_POST['stat'][$i],
			
		);
	}


?>
    <tr id="productItem_<?php echo $i ?>" bgcolor="#ececec" align="center">
      <td align="center" ><?php if ($orderInv[$i]['id']>0) { ?>
        <a href="javascript:toggleProdStatus(<?php echo $i; ?>,'<?php echo addslashes(sprintf($lang['admin']['orders_prod_will_be_removed'],$orderInv[$i]['name']));?>','<?php echo addslashes(sprintf($lang['admin']['orders_prod_wont_be_removed'],$orderInv[$i]['name']));?>','<?php echo $glob['adminFolder']; ?>/images/del.gif','<?php echo $glob['adminFolder']; ?>/images/no_del.gif');"><img src="<?php echo $glob['adminFolder']; ?>/images/delete.png" id="del[<?php echo $i; ?>]" width="12" height="12" border="0" /></a>
        <?php } else { ?>
        &nbsp;
        <?php } ?></td>
      <td ><input type="hidden" name="id[<?php echo $i; ?>]" value="<?php echo $orderInv[$i]['id']; ?>" />
        <input type="hidden" name="delId[<?php echo $i; ?>]" value="0" />
        
        <div class="inputbox" style="width:160px; float:none;">
        <span class="bgleft"></span>
        <input type="text" style="width:140px" name="prodName[<?php echo $i; ?>]" class="textbox" value="<?php echo htmlspecialchars($orderInv[$i]['name']); ?>" />
        <span class="bgright"></span></div>
        <?php if($orderInv[$i]['couponId']>0) {
			$coupon = $db->select("SELECT `code` FROM ".$glob['dbprefix']."ImeiUnlock_Coupons WHERE `id` = ".$orderInv[$i]['couponId']);	
			
			if($coupon == true) {
				echo "<br />".$coupon[0]['code'];
			} else {
				echo "<br />".$lang['admin_common']['na'];
			}
		}
		?></td>
      <!--<td >
      <?php
	  $options = explode("\n", $orderInv[$i]['product_options']);
					$searchword = 'Design Name';
				
					$matches = array();
				
						foreach($options as $k=>$v) {
				
						if(preg_match("/\b$searchword\b/i", $v)) {
				
						$dname[$k] = $v;
				
						 }
				
				}
				
					if($dname)
				
				 $dname = array_values($dname);
				
					 $dname = explode(" - ", $dname[0]);
					 ?>
      <div class="" style="width:150px; float:none;">
   Design Name : 
   
   </div>
      </td>-->
      <td >
      <div class="" style="width:130px; float:none;">
      <?php $orig = imgPath($orderInv[$i]['image'],'',$path="orderdesignori"); ?>
      <?php $design = imgPath($orderInv[$i]['image'],'',$path="orderdesign"); ?>
      <a href="<?php echo $orig; ?>" target="_new" > Original Design</a><br />
       <a href="<?php echo $design; ?>" target="_new" > Customer Design</a>
     </div>
      </td>
      <td >
       <textarea style="width:160px; float:none;" name="extra_notes[<?php echo $i; ?>]" cols="30" rows="1" class="textareasmall"><?php
      echo str_replace('&amp;','&',$orderInv[$i]['extra_notes']);
      ?></textarea>
  
      <td align="center" ><input name="quantity[<?php echo $i; ?>]" type="text" class="textbox" style="text-align:center; width:45px;" value="<?php echo $orderInv[$i]['quantity']; ?>" size="3"  /></td> 
      <input name="quantity[<?php echo $i; ?>]" type="hidden" class="textbox" style="text-align:center;" value="<?php echo $orderInv[$i]['quantity']; ?>" size="3" />
      <td  align="center" >
      <div class="inputbox" style="width:55px; float:none;">
        <span class="bgleft"></span>
      <input  name="price[<?php echo $i; ?>]" type="text" class="textbox" style=" width:45px; text-align:center" value="<?php echo $orderInv[$i]['price']; ?>" size="7" />
       <span class="bgright"></span></div>
      </td>
      <td  align="center" >
      <div class="inputbox" style="width:140px; float:none;">
        <span class="bgleft"></span>
        <?php 
		if($orderInv[$i]['digital'] == 1){
			  ?>
      <select name="stat[<?php echo $i; ?>]" style="width:130px;" class="dropDown">
          <?php
		for ($j=1; $j<=3; $j++) {
		?>
          <option value="<?php echo $j; ?>"<?php if($orderInv[$i]['stat'] == $j){echo 'selected="selected"';} ?>><?php echo $lang['glob']['orderStat_'.$j]; ?></option>
          <?php 
		} 
		?>
        </select>
        

      <?php
		}else{
		?>
        <select name="stat[<?php echo $i; ?>]" style="width:130px;" class="dropDown">
          <?php
		for ($j=1; $j<=6; $j++) {
		?>
          <option value="<?php echo $j; ?>"<?php if($orderInv[$i]['stat'] == $j){echo 'selected="selected"';} ?>><?php echo $lang['glob']['accessState_'.$j]; ?></option>
          <?php 
		} 
		?>
        </select>
        <?php
  }
  ?>
        <span class="bgright"></span></div>
    
       
        <input name="vid[<?php echo $i; ?>]" id="vid" type="hidden"  value="<?php echo $orderInv[$i]['vid']; ?>"/>
         <input name="type[<?php echo $i; ?>]" id="vid" type="hidden"  value="<?php echo $orderInv[$i]['digital']; ?>"/></td>
         <!--<td align="center"><input type="checkbox" value="1" name="refund[<?php echo $i; ?>]"  /></td>-->
    </tr>
    <?php 
	  
  } 
  ?>
  </table>
  <input type="hidden" id="rowCount" value="<?php echo $rows ?>" />
  <table border="0" cellspacing="0" cellpadding="3" class="mainTable"  style="border-top: none;">
    <tr>
      <td class="tdText"><!--<input type="hidden" name="currentRowCount" value="<?php echo $rows; ?>" />
        <?php echo $lang['admin_common']['add'];?>
        <input type="text" name="prodRowsAdd" id="prodRowsAdd" value="1" size="2" style="text-align: center;" class="textbox" />
        <input type="submit" name="prodRowsSubmit" value="Product Rows" class="submit" onclick="return addRows('productList', 'prodRowsAdd')" /> --> </td>
      <td align="right" class="tdText" width="89%"><?php echo $lang['admin']['orders_subtotal']; ?></td>
      <td width="90"  align="left">
      <div class="inputbox" style="width:82px; float:none;">
      <span class="bgleft"></span>
      <input name="subtotal" id="subtotal" type="text" class="textbox" style="width:72px;" value="<?php echo $orderSum[0]['subtotal']; ?>" size="7" />
      <span class="bgright"></span></div>
      </td>
    </tr>
    <tr>
      <td  rowspan="5">&nbsp;</td>
    </tr>
    
    
<tr>
  
      <td align="right" class="tdText"><?php echo $lang['admin']['orders_discount']; ?></td>
      <td width="150" align="center"><input name="discount" id="discount" type="text" class="textbox" style="width:80px;" value="<?php echo $orderSum[0]['discount']; ?>" size="7" /></td>
    </tr>
    <tr>
      <td align="right" class="tdText"><?php echo $lang['admin']['orders_shipping']; ?></td>
      <td width="150" align="center"><input name="total_ship" type="text" class="textbox"  style="width:80px;" value="<?php echo $orderSum[0]['total_ship']; ?>" size="7" /></td>
    </tr>
       <?php
    $config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");
	if ($config_tax_mod['status']) {
		for ($i=0; $i<3; $i++) {
			$tax_key_name = 'tax'.($i+1).'_disp';
			$tax_key_value = 'tax'.($i+1).'_amt';
			if ($orderSum[0][$tax_key_name] != "") {
				$name	= $orderSum[0][$tax_key_name];
				$value	= $orderSum[0][$tax_key_value];
				
			} else if ($i==0) {
				$tax_key_value = 'total_tax';
				$name	= $lang['admin']['orders_total_tax'];
				$value	= $orderSum[0][$tax_key_value];
				
			} else {
				break;
			}
?>
	<tr>
      <td align="right" class="tdText"><?php echo $name; ?></td>
      <td width="150" align="center"><input name="<?php echo $tax_key_value; ?>" id="<?php echo $tax_key_value; ?>" type="text" class="textbox"  style="width:80px;" value="<?php echo $value; ?>" size="7" /></td>
    </tr>
<?php
		}
	} else {
?>
	<tr>
      <td align="right" class="tdText"><?php echo $lang['admin']['orders_total_tax']; ?></td>
      <td width="150" align="center"><input name="total_tax" id="total_tax" type="text" class="textbox"  style="width:80px;" value="<?php echo $orderSum[0]['total_tax']; ?>" size="7" /></td>
    </tr>

<?php	
	
	}
?>

    <tr>
      <td align="right" class="tdText" ><?php echo $lang['admin']['orders_grand_total']; ?></td>
      <td  align="center" width="150">
     
      <input name="prod_total" id="prod_total" type="text" class="textbox" style="width:80px;" value="<?php echo $orderSum[0]['prod_total']; ?>"  />
     
      </td>
    </tr>
     <input name="cart_order_id" type="hidden" class="textbox" value="<?php if(isset($orderSum[0]['cart_order_id'])) { echo $orderSum[0]['cart_order_id'].'" readonly="readonly'; } else { echo $order->mkOrderNo(); } ?>" size="22" />
    <tr>
      <td colspan="3" align="right">
      <div class="seprator2"></div>
        <input name="storeurl" id="storeurl" type="hidden"  value="<?php echo $orderSum[0]['storeurl']; ?>"/>
        <input name="vcartid" id="vcartid" type="hidden"  value="<?php echo $orderSum[0]['vcart_order_id']; ?>"/>
       
      <?php if(permission("orders","edit")==TRUE){ ?> <input type="submit" name="submit" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['orders_order'];?>"  class="submit" style="margin:15px 9px 10px 0;" /><?php } ?></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
function populate() {
//	var json = $('customer_select').readAttribute('json');
}
</script>
<?php
include("modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."admin.php");
?>

