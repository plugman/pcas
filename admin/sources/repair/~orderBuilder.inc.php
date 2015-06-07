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
require $glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php";
permission("orders", "write", true);

if (isset($_GET['reset']) && $_GET['reset']>0) {
	$record['noDownloads']	= 0;
	$record['expire']		= time()+$config['dnLoadExpire'];
	
	$where	= 'id = '.$_GET['reset'];
	$update	= $db->update($glob['dbprefix']."ImeiUnlock_Downloads", $record, $where);
	
	httpredir($glob['adminFile'].'?_g=repair/orderBuilder&edit='.$_GET['edit']);
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
		httpredir($glob['adminFile']."?_g=repair/orderBuilder&edit=".$_POST['cart_order_id']);
	}
}
	if (count($_POST['prodName'])>0) {
		require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
		$orderInv = $order->getOrderInv($_POST['cart_order_id']);
		$orderSum =$order-> getOrderSum($_POST['cart_order_id']);
				$lang = getLang("email.inc.php");
				$langAdmin = getLang("email.inc.php");
				$mail = new htmlMimeMail();
				$macroArrayMain = array(
								"RECIP_NAME"		=> $orderSum['name'],
								"ORDER_ID"			=> $orderSum['cart_order_id'],
								"ORDER_DATE"		=> formatTime($orderSum['time']),
							);
							
		for ($i=0;$i<=count($_POST['prodName']);$i++) {
			if ((!empty($_POST['prodName'][$i]) && !empty($_POST['quantity'][$i]) && !empty($_POST['price'][$i])) || $_POST['delId'][$i] == 1) {
				$newOrderInv['stat'] = $db->mySQLSafe($_POST['stat'][$i]);
				$newOrderInv['extra_notes'] = $db->mySQLSafe($_POST['extra_notes'][$i]);	
					
				if ($_POST['delId'][$i] == 1) {
					$where = "id = ".$db->mySQLSafe($_POST['id'][$i]);
					$delete =  $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where);
				} else if ($_POST['id'][$i]>0) {
					if(getdbostatus($_POST['stat'][$i],$_POST['id'][$i]) == 1)
					$mailbit = true;
					$where = "id = ".$db->mySQLSafe($_POST['id'][$i]);
				 	$update = $db->update($glob['dbprefix']."ImeiUnlock_order_inv", $newOrderInv, $where);
					if ($update == true) {
					$orderstatarray[$i]['cart_order_id'] = $_POST['vcartid'];
					$orderstatarray[$i]['vid'] = $_POST['vid'][$i];
					$orderstatarray[$i]['status'] = $_POST['stat'][$i];
					$orderstatarray[$i]['notes'] = $_POST['extra_notes'][$i];
			$msg = "<p class='infoText'>".sprintf($lang['admin']['orders_updated_successfully'],$_GET['edit'])."</p>";	
			$cart_order_id[$i]['orderid'] = $orderInv[$i]['cart_order_id'];
			
			if($_POST['stat'][$i] == 2){
				$complete = true;
			$maintest = macroSub($lang['email']['order_breakdown_1'],$macroArrayMain);
							$maintestadmin = macroSub($langAdmin['email']['order_breakdown_1'],$macroArrayMain);
							
							unset($macroArray);
				$macroArray = array(
									"PRODUCT_NAME" => $orderInv[$i]['name'],
									"PRODUCT_CODE" => $orderInv[$i]['productCode'],
									"IMEI" => $orderInv[$i]['imei'],
									"COMENTS" => $_POST['extra_notes'][$i],
									"MODEL" => $orderInv[$i]['product_options'],						
									"PRODUCT_PRICE" => priceFormat($orderInv[$i]['price'],true)
								);					
								$text .= macroSub($lang['email']['order_breakdown_6'], $macroArray);
								$imeis .= "&nbsp;".$orderInv[$i]['imei'];
								$textAdmin .= macroSub($langAdmin['email']['order_breakdown_6'], $macroArray);
								unset($macroArray);
							
		}
		else if($_POST['stat'][$i] == 3){
			$cancell = true;
			$returncredits = $returncredits + $_POST['price'][$i];
			$maintest = macroSub($lang['email']['payment_cancelled_body'],$macroArrayMain);
							$maintestadmin = macroSub($langAdmin['email']['payment_cancelled_body'],$macroArrayMain);
							unset($macroArray);
						 
				$macroArray = array(
									"PRODUCT_NAME" => $orderInv[$i]['name'],
									"PRODUCT_CODE" => $orderInv[$i]['productCode'],
									"IMEI" => $orderInv[$i]['imei'],
									"COMENTS" => $_POST['extra_notes'][$i],
									"MODEL" => $orderInv[$i]['product_options'],						
									"PRODUCT_PRICE" => priceFormat($orderInv[$i]['price'],true)
								);					
								$text .= macroSub($lang['email']['order_breakdown_6'], $macroArray);
								$imeis .= "&nbsp;".$orderInv[$i]['imei'];
								$textAdmin .= macroSub($langAdmin['email']['order_breakdown_6'], $macroArray);
								unset($macroArray);
					if($orderSum['status']== 2 && $returncredits > 0){
					if($orderSum['gateway']== "TopUp" || $orderSum['gateway']== "PayPal"){
					$query = $db->select("SELECT transactionId,id FROM ".$glob['dbprefix']."tbl_topup_payment_transactions WHERE transactionId=".$db->mySQLSafe($orderSum['cart_order_id'].$_POST['id'][$i])."AND carrier_id =".$db->mySQLSafe($_POST['id'][$i]));
					if(empty($query)){					
						$record['notes'] = $db->mySQLSafe("Credit added on cancel order");
						$record['amount'] = $db->mySQLSafe($_POST['price'][$i]);
						$record['status'] = $db->mySQLSafe(1);
						$record['date_topped'] = $db->mySQLSafe(time(0));
						$record['customerid'] = $db->mySQLSafe($orderSum['customer_id']);
						$record['gateway'] = $db->mySQLSafe("Admin");
						$record['transactionId'] = $db->mySQLSafe($orderSum['cart_order_id'].$_POST['id'][$i]);
						$record['carrier_id'] = $db->mySQLSafe($_POST['id'][$i]);
						$insert = $db->insert("tbl_topup_payment_transactions", $record);
						$updaterecord['card_balance'] = "card_balance + ".$_POST['price'][$i]."";
						$where = "customer_id = ".$db->mySQLSafe($orderSum['customer_id']);
						 $db->update($glob['dbprefix']."ImeiUnlock_customer", $updaterecord, $where);
						 // insert transaction record
			$SelectBalance 	= $db->select("SELECT `card_balance` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `customer_id`=".$db->mySQLSafe($orderSum['customer_id']));
						$transData['customer_id']	= $orderSum['customer_id'];
						$transData['trans_id'] 		= $orderSum['cart_order_id'].$_POST['id'][$i];	
						$transData['dr'] 		= $_POST['price'][$i];
						$transData['imei'] 		= $_POST['imei'][$i];
						$transData['notes'] 		= "Credits Reversal on order cancel.";
						$transData['balance'] 		= $SelectBalance[0]['card_balance'];
						storeCreditTrans($transData);	
					}
					}
					}
								
							
		}
					$updatevender = 1;
					}
				} else {
					$insert = $db->insert($glob['dbprefix']."ImeiUnlock_order_inv", $newOrderInv);
				}
			}	
		}
		if ($mailbit == true) {
			if($orderSum['gateway'] == "TopUp"){
			$macroArray = array(
								"PAYMENT_METHOD"	=> "Credits",
							);
							$text .= macroSub($lang['email']['order_breakdown_7'],$macroArray);
							$textAdmin .= macroSub($langAdmin['email']['order_breakdown_7'],$macroArray);
							unset($macroArray);
			}else{
				$macroArray = array(
								"PAYMENT_METHOD"	=> $orderSum['gateway'],
							);
							$text .= macroSub($lang['email']['order_breakdown_7'],$macroArray);
							$textAdmin .= macroSub($langAdmin['email']['order_breakdown_7'],$macroArray);
							unset($macroArray);
			}
			$text = $maintest.$text;
			$textAdmin = $maintest.$textAdmin;
				$mail->setText($text);
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setReturnPath($config['masterEmail']);
				if($complete == true)
				$mail->setSubject(macroSub($lang['email']['order_breakdown_subject'], array("IMEIS" => $imeis)));
				if($cancell == true)
				$mail->setSubject(macroSub($lang['email']['payment_cancelled_subject'], array("IMEIS" => $imeis)));
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array(sanitizeVar($orderSum['email'])), $config['mailMethod']);			
				$mailAdmin = new htmlMimeMail();

							$mailAdmin->setText($textAdmin);

							$mailAdmin->setReturnPath($config['masterEmail']);

							$mailAdmin->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
							if($complete == true)
							$mailAdmin->setSubject(macroSub($langAdmin['email']['order_breakdown_subject'], array("IMEIS" => $imeis)));
							if($cancell == true)
							$mailAdmin->setSubject(macroSub($langAdmin['email']['payment_cancelled_subject'], array("IMEIS" => $imeis)));

							$mailAdmin->setHeader('X-Mailer', 'ImeiUnlock Mailer');

							$mailAdmin->send(array($config['masterEmail']), $config['mailMethod']);
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
	$orderInv = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($_GET['edit']));
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
<form action="<?php echo $glob['adminFile']; ?>?_g=repair/orderBuilder<?php if(isset($_GET['edit'])) { echo "&amp;edit=".$_GET['edit']; } ?>" method="post" enctype="multipart/form-data" name="orderBuilder" target="_self">

<div class="order">
	<div class="ordertop">
    <div class="hleft">
    Customer Information</div>
     <div class="hright">
    Order Information</div>
    </div>
    <div class="oleft">
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
   <tr>
  	<td align="right"><?php echo $lang['admin']['orders_country'];?></td>
    <td ><?php echo $orderSum[0]['country_d']; ?></td>
  </tr>
   <tr>
  	<td align="right"><?php echo $lang['admin_common']['other_customer']; ?></td>
    <td ><?=GetCustomerType($orderSum[0]['customer_id'])?></td>
  </tr>
 <?php
	$balanceRs 	= $db->select("SELECT card_balance FROM ImeiUnlock_customer WHERE customer_id =".$db->mySQLSafe($orderSum[0]['customer_id']));
	?>
  <tr>
  	<td align="right"><?php echo "Credits:"; ?></td>
    <td ><? echo $balanceRs[0]['card_balance']?></td>
  </tr>
</table>
	</div>
  <div class="oright">
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
 <!-- <table cellspacing="1" cellpadding="3" class="mainTable" width="100%">
    <tr>
      <td colspan="4" class="tdTitle"><?php echo $lang['admin']['orders_order_summary'];?></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin_common']['other_order_no']; ?></strong></td>
      <td class="tdText">
      <input name="cart_order_id" type="text" class="textbox" value="<?php if(isset($orderSum[0]['cart_order_id'])) { echo $orderSum[0]['cart_order_id'].'" readonly="readonly'; } else { echo $order->mkOrderNo(); } ?>" size="22" /></td>
      <td class="tdText"><strong><?php echo $lang['admin_common']['other_customer']; ?></strong>(<span>
        <?=GetCustomerType($orderSum[0]['customer_id'])?>
        </span>)</td>
      <td class="tdText"><?php
      if ($customers == true) {
      ?>
        <select name="customer_id" id="customer_select" onchange="populate();">
          <?php if($orderSum == false) { ?>
          <option value="0" <?php if(!$_POST['customer_id'] && $orderSum == false) { echo "selected='selected'"; } ?>>-- <?php echo $lang['admin_common']['na'];?> --</option>
          <?php
		}
		
			//for ($i=0; $i<count($customers); $i++) {
			foreach ($customers as $customer) {
			#	$customer = array_map('html_entity_decode', $customer);
			#	$customer = array_map('addslashes', $customer);
		?>
          <option value="<?php echo $customer['customer_id'];?>" 
		<?php if($customer['customer_id']==$_POST['customer_id'] || $customer['customer_id']==$orderSum[0]['customer_id']){ echo "selected='selected'"; } ?>
		json="<?php json_encode($customer); ?>"
		onmouseover="findObj('name').value='<?php echo addslashes($customer['title'].' '.html_entity_decode($customer['firstName'].' '.$customer['lastName'], ENT_QUOTES));?>';findObj('companyName').value='<?php echo addslashes(html_entity_decode($customer['companyName'], ENT_QUOTES));?>';findObj('add_1').value='<?php echo addslashes(html_entity_decode($customer['add_1'], ENT_QUOTES));?>';findObj('add_2').value='<?php echo addslashes(html_entity_decode($customer['add_2'], ENT_QUOTES));?>';findObj('town').value='<?php echo addslashes(html_entity_decode($customer['town'], ENT_QUOTES));?>';findObj('country').value='<?php echo $countriesArray[$customer['country']];?>';findObj('postcode').value='<?php echo $customer['postcode'];?>';findObj('county').value='<?php echo $customer['county'];?>';findObj('phone').value='<?php echo $customer['phone'];?>';findObj('mobile').value='<?php echo $customer['mobile'];?>';findObj('email').value='<?php echo $customer['email'];?>';"
		> <?php echo $customer['lastName'];?>, <?php echo $customer['firstName'];?> (<?php echo $customer['customer_id'];?>)</option>
          <?php
			}
			
		?>
        </select>
        <?php } else { ?>
        <input type="textbox" name="customer_id" class="textbox" value="<?php echo isset($_POST['customer_id']) ? $_POST['customer_id'] : $orderSum[0]['customer_id']; ?>" />
        <?php }
		 ?></td>
    </tr>
    <tr>
      <td colspan="2" class="tdTitle"><?php echo $lang['admin']['orders_billing_info']; ?></td>
      <td colspan="2" class="tdTitle"><?php echo $lang['admin']['orders_shipping_info']; ?></td>
    </tr>
    <tr>
      <td class="tdText">&nbsp;</td>
      <td class="tdText"><input type="button" name="clear" value="<?php echo $lang['admin']['orders_reset_billing'];?>" onclick="findObj('name').value='';findObj('companyName').value='';findObj('add_1').value='';findObj('add_2').value='';findObj('town').value='';findObj('country').value='';findObj('postcode').value='';findObj('county').value='';findObj('phone').value='';findObj('mobile').value='';findObj('email').value='';" class="submit" /></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText"><input type="button" name="shipCopy" value="<?php echo $lang['admin']['orders_copy_from_billing']; ?>" onclick="findObj('name_d').value = findObj('name').value;findObj('companyName_d').value = findObj('companyName').value;findObj('add_1_d').value = findObj('add_1').value;findObj('add_2_d').value = findObj('add_2').value;findObj('town_d').value = findObj('town').value;findObj('country_d').value = findObj('country').value;findObj('postcode_d').value = findObj('postcode').value;findObj('county_d').value = findObj('county').value;"  class="submit"/></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_name']; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="name" id="name" value="<?php echo $orderSum[0]['name']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_name']; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="name_d" id="name_d" value="<?php echo $orderSum[0]['name_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo "Store URL"; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="storeurl" id="storeurl" value="<?php echo $orderSum[0]['storeurl']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_company_name']; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="companyName_d" id="companyName_d" value="<?php echo $orderSum[0]['companyName_d']; ?>" /></td>
    </tr>
    <?php
	$balanceRs 	= $db->select("SELECT card_balance FROM ImeiUnlock_customer WHERE customer_id =".$db->mySQLSafe($orderSum[0]['customer_id']));
	?>
    <tr>
      <td class="tdText"><strong><?php echo "Vender Order NO";?></strong></td>
      <td class="tdText"><input name="vcart_order_id" type="text" class="textbox" value="<?php echo $orderSum[0]['vcart_order_id'].'" readonly="readonly';?>" size="22" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_address'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="add_1_d" id="add_1_d" value="<?php echo $orderSum[0]['add_1_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo "Customer Available Balance";?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="add_2" id="add_2" value="<?php echo $balanceRs[0]['card_balance']; ?>" /></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText"><input type="text" class="textbox" name="add_2_d" id="add_2_d" value="<?php echo $orderSum[0]['add_2_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_town'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="town" id="town" value="<?php echo $orderSum[0]['town']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_town'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="town_d" id="town_d" value="<?php echo $orderSum[0]['town_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_state'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="county" id="county" value="<?php echo $orderSum[0]['county']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_state'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="county_d" id="county_d" value="<?php echo $orderSum[0]['county_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_postcode'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="postcode" id="postcode" value="<?php echo $orderSum[0]['postcode']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_postcode'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="postcode_d" id="postcode_d" value="<?php echo $orderSum[0]['postcode_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_country'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="country" id="country" value="<?php echo $orderSum[0]['country']; ?>" /></td>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_country'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="country_d" id="country_d" value="<?php echo $orderSum[0]['country_d']; ?>" /></td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_phone'];?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="phone" id="phone" value="<?php echo $orderSum[0]['phone']; ?>" /></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_cell_phone']; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="mobile" id="mobile" value="<?php echo $orderSum[0]['mobile']; ?>" /></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_email']; ?></strong></td>
      <td class="tdText"><input type="text" class="textbox" name="email" id="email" value="<?php echo $orderSum[0]['email']; ?>" /></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_status']; ?></strong></td>
      <td valign="top" class="tdText"><select name="status" class="dropDown">
          <?php
		for ($i=1; $i<=6; $i++) {
		?>
          <option value="<?php echo $i; ?>" <?php if($orderSum[0]['status']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['glob']['orderState_'.$i]; ?></option>
          <?php 
		} 
		?>
        </select></td>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_shipping_date']; ?></strong></td>
      <td class="tdText"><input name="ship_date" type="text" value="<?php echo $orderSum['0']['ship_date']; ?>" class="textbox" id="ship_date" size="25" />
        <br />
        <?php echo $lang['admin']['orders_ship_today']; ?>
        <input name="shipToday" type="checkbox" id="shipToday" value="checkbox" onclick="findObj('ship_date').value='<?php echo strip_tags(date($config['dateFormat'], time()+$config['timeOffset'])); ?>';" /></td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_customer_comments']; ?></strong></td>
      <td class="tdText"><textarea name="customer_comments" cols="30" rows="3" class="textbox"><?php echo $orderSum['0']['customer_comments']; ?></textarea></td>
      <td valign="top" class="tdText">&nbsp;</td>
      <td valign="top" class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_staff_comments']; ?></strong></td>
      <td class="tdText"><textarea name="comments" cols="30" rows="3" class="textbox"><?php echo $orderSum['0']['comments']; ?></textarea></td>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_ship_method']; ?></strong></td>
      <td valign="top" class="tdText"><input type="text" name="shipMethod" class="textbox" value="<?php echo str_replace("_"," ",$orderSum['0']['shipMethod']); ?>" /></td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_extra_notes']; ?></strong></td>
      <td class="tdText"><textarea name="extra_notes" cols="30" rows="3" class="textbox"><?php echo $orderSum['0']['extra_notes']; ?></textarea></td>
      <td valign="top" class="tdText">&nbsp;</td>
      <td valign="top" class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td class="tdText"><strong><?php echo $lang['admin']['orders_payment_method']; ?></strong></td>
      <td class="tdText"><?php if(strstr($orderSum['0']['gateway'], "PayPal Website Payments Pro")) { ?>
        <input type="hidden" name="gateway" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />
        <?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>
        <?php } else { ?>
        <input type="text" name="gateway" class="textbox" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />
        <?php
	  }
	  ?></td>
      <td class="tdText">&nbsp;</td>
      <td class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="right" class="tdText"><span class="tdTitle">
        <input type="submit" name="submit22" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['orders_order'];?>"  class="submit" />
        </span></td>
    </tr>
  </table>
  
  <table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
    <tr>
      <td class="tdTitle"><?php echo $lang['admin']['orders_courier_tracking'];?></td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_courier_tracking_url'];?>:</strong> <br />
        <textarea name="courier_tracking" rows="1" class="textbox" style="width: 99%;"><?php echo $orderSum[0]['courier_tracking']; ?></textarea></td>
    </tr>
    <tr>
      <td align="right" class="tdText"><input type="submit" name="submit2" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['orders_order'];?>"  class="submit" /></td>
    </tr>
  </table>-->
  <?php if ((!empty($orderSum[0]['offline_capture']) || !isset($_GET['edit'])) && function_exists('mcrypt_module_open')) { ?>
  
  <!--<table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
    <tr>
      <td class="tdTitle" colspan="2"><?php echo $lang['admin']['orders_card_details'] ?></td>
    </tr>
    <?php
	require_once("classes".CC_DS."cart".CC_DS."encrypt.inc.php");
	//echo $orderSum[0]['offline_capture'];
	$decrypt = new encryption(array($orderSum[0]['cart_order_id']));
	$card = unserialize($decrypt->decrypt(base64_decode($orderSum[0]['offline_capture'])));
	$card = (!empty($card)) ? $card : array('card_type' => '', 'card_number' => '', 'card_expire' => '', 'card_valid' => '', 'card_issue' => '', 'card_cvv' => '');
	$lang['admin']['orders_card_cvv'] = "Security Code";
	
	$cardfield = 0;
	$showField = detectSSL();
	foreach ($card as $field => $value) {
		$disabled = false; 
		if (!$showField) {
			$value		= 'xxx';
			$disabled	= true;
		}
		echo sprintf('<tr><td class="tdText">'.$lang['admin']['orders_'.$field].'</td><td class="tdText"><input type="text" class="textbox" name="'.$field.'" value="%s" %s /></td></tr>', $value, ($disabled) ? 'disabled="disabled"' : '');
		$cardfield++;
	}
	unset($cardfield);
?>
    <tr>
      <td class="tdText" colspan="2" align="right"><input type="submit" class="submit" name="cc_delete" id="cc_delete" value="Delete Card Details" /></td>
    </tr>
  </table>-->
  <?php } ?>
</form>

<form action="<?php echo $glob['adminFile']; ?>?_g=repair/orderBuilder<?php if(isset($_GET['edit'])) { echo "&amp;edit=".$_GET['edit']; } ?>" method="post" enctype="multipart/form-data" name="orderBuilder" target="_self">
 <table class="mainTable mainTable4" style="margin-bottom:0" width="100%" cellspacing="0" cellpadding="0" bordercolor="#d4d4d4" border="1">
    <tr>
      <td align="center" class="tdTitle" width="50" ></td>
      <td align="center" class="tdTitle" ><?php echo $lang['admin']['orders_product'];?> Name</td>
      <td align="center" class="tdTitle" colspan="2">Order Info</td>
      <td align="center" class="tdTitle">Notes to send to customers</td>
 <!-- <td class="tdTitle"><?php echo $lang['admin']['orders_options'];?></td>
      <td align="center" class="tdTitle"><?php echo $lang['admin']['orders_quantity'];?></td>-->
      <td  align="center" class="tdTitle"><?php echo $lang['admin']['orders_price'];?></td>
      <td  align="center" class="tdTitle"><?php echo "Lock Status";?></td>
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
      <td colspan="2" >
     
       
        <textarea style="width:240px; height:70px;" name="product_options[<?php echo $i; ?>]" cols="30" rows="1" class="textareasmall"><?php  echo str_replace('&amp;','&',$orderInv[$i]['product_options']); ?></textarea>
     
      </td>
      
      <td >
       <textarea style="width:160px; float:none;" name="extra_notes[<?php echo $i; ?>]" cols="30" rows="1" class="textareasmall"><?php
      echo str_replace('&amp;','&',$orderInv[$i]['extra_notes']);
      ?></textarea>
     <!-- <input name="imei[<?php echo $i; ?>]" type="text" class="textbox" value="<?php if(permission("imei","read")==true){ echo $orderInv[$i]['imei']; }else echo "";?>" size="15" style="width:150px;" />-->
     </td>
 <!-- <td valign="top">
      <textarea name="product_options[<?php echo $i; ?>]" cols="30" rows="1" class="textbox">
      <?php
      //echo stripslashes(str_replace("&amp;#39;","&#39;",$orderInv[$i]['product_options'])); 
      echo str_replace('&amp;','&',$orderInv[$i]['product_options']);
      ?>
      </textarea></td>
      <td align="center" ><input name="quantity[<?php echo $i; ?>]" type="text" class="textbox" style="text-align:center;" value="<?php echo $orderInv[$i]['quantity']; ?>" size="3" /></td> -->
      <input name="quantity[<?php echo $i; ?>]" type="hidden" class="textbox" style="text-align:center;" value="<?php echo $orderInv[$i]['quantity']; ?>" size="3" />
      <td  align="center" >
      <div class="inputbox" style="width:70px; float:none;">
        <span class="bgleft"></span>
      <input  name="price[<?php echo $i; ?>]" type="text" class="textbox" style=" width:60px; text-align:center" value="<?php echo $orderInv[$i]['price']; ?>" size="7" />
       <span class="bgright"></span></div>
      </td>
      <td  align="center" >
      <div class="inputbox" style="width:160px; float:none;">
        <span class="bgleft"></span>
      <select name="stat[<?php echo $i; ?>]" style="width:150px;" class="dropDown">
          <?php
		for ($j=1; $j<=3; $j++) {
		?>
          <option value="<?php echo $j; ?>"<?php if($orderInv[$i]['stat'] == $j){echo 'selected="selected"';} ?>><?php echo $lang['glob']['orderStat_'.$j]; ?></option>
          <?php 
		} 
		?>
        </select>
        <span class="bgright"></span></div>
        <input name="vid[<?php echo $i; ?>]" id="vid" type="hidden"  value="<?php echo $orderInv[$i]['vid']; ?>"/></td>
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
      <td align="right" class="tdText" width="89%"><strong><?php echo $lang['admin']['orders_subtotal']; ?></strong></td>
      <td width="90"  align="left">
      <div class="inputbox" style="width:82px; float:none;">
      <span class="bgleft"></span>
      <input name="subtotal" id="subtotal" type="text" class="textbox" style="width:72px;" value="<?php echo $orderSum[0]['subtotal']; ?>" size="7" />
      <span class="bgright"></span></div>
      </td>
    </tr>
    <tr>
      <td  rowspan="4">&nbsp;</td>
    </tr>
    
    
<tr>
  
      <td align="right" class="tdText"><?php echo $lang['admin']['orders_discount']; ?></td>
      <td width="150" align="center"><input name="discount" id="discount" type="text" class="textbox" style="width:80px;" value="<?php echo $orderSum[0]['discount']; ?>" size="7" /></td>
    </tr>
    <tr>
      <td align="right" class="tdText"><strong><?php echo "Paypal Processing Fee"; ?></strong></td>
      <td  align="left">
      <div class="inputbox" style="width:82px;">
      <span class="bgleft"></span>
      <input  name="total_ship" type="text" class="textbox"  style="width:72px;" value="<?php echo $orderSum[0]['paypalfee']; ?>" size="7" />
      <span class="bgright"></span></div>
      </td>
    </tr>
 <input name="cart_order_id" type="hidden" class="textbox" value="<?php if(isset($orderSum[0]['cart_order_id'])) { echo $orderSum[0]['cart_order_id'].'" readonly="readonly'; } else { echo $order->mkOrderNo(); } ?>" size="22" />
    <tr>
      <td align="right" class="tdText"><strong><?php echo $lang['admin']['orders_grand_total']; ?></strong></td>
      <td  align="center">
      <div class="inputbox" style="width:82px;">
      <span class="bgleft"></span>
      <input name="prod_total" id="prod_total" type="text" class="textbox" style="width:72px;" value="<?php echo $orderSum[0]['prod_total']; ?>"  />
      <span class="bgright"></span></div>
      </td>
    </tr>
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

