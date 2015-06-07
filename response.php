<?php	
	include("ini.inc.php");
include("includes/global.inc.php");
include("classes/db/db.php");

$db  = new db();

include("includes/functions.inc.php");
include("classes/cache/cache.php");
$config = fetchdbconfig("config");
	$module = fetchDbConfig('TopUp');
require_once ("classes/session/cc_session.php");
$cc_session = new session();
require_once ("includes/currencyVars.inc.php");
require_once ("classes" . CC_DS . "cart" . CC_DS . "order.php");
require_once "classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
$lang = getLang("email.inc.php" ,"en");
	$config = fetchDbConfig("config");
$order	= new order();
if(!isset($_POST['order'][0]['cart_order_id'])){
	$order->orderStatus($_POST['status'], $_POST['orderid'], true);
}
$orderInv = $order->getOrderInv($_POST['order'][0]['cart_order_id']);
$orderSum =$order-> getOrderSum($_POST['order'][0]['cart_order_id']);
				$lang = getLang("email.inc.php");
				$mail = new htmlMimeMail();
				$macroArrayMain = array(
								"RECIP_NAME"		=> $orderSum['name'],
								"ORDER_ID"			=> $orderSum['cart_order_id'],
								"ORDER_DATE"		=> formatTime($orderSum['time']),
							);
for($i=0;$i<count($_POST['order']); $i++){
	 if(getdbostatus($_POST['order'][$i]['status'],$_POST['order'][$i]['vid']) == 1)
	$mailbit = true;
 $updatestatus['stat'] =  $db->mySQLSafe($_POST['order'][$i]['status']);
 $updatestatus['extra_notes'] =  $db->mySQLSafe($_POST['order'][$i]['notes']);
 $where =  "id=".$db->mySQLSafe($_POST['order'][$i]['vid']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_order_inv", $updatestatus, $where);
	if($update == true){
		if($_POST['order'][$i]['status'] == 2){
			$maintest = macroSub($lang['email']['order_breakdown_1'],$macroArrayMain);
							$maintestadmin = macroSub($langAdmin['email']['order_breakdown_1'],$macroArrayMain);
							unset($macroArray);
				$macroArray = array(
									"PRODUCT_NAME" => $orderInv[$i]['name'],
									"PRODUCT_CODE" => $orderInv[$i]['productCode'],
									"IMEI" => $orderInv[$i]['imei'],
									"COMENTS" => $_POST['order'][$i]['notes'],	
									"MODEL" => $orderInv[$i]['product_options'],						
									"PRODUCT_PRICE" => priceFormat($orderInv[$i]['price'],true)
								);					
								$text .= macroSub($lang['email']['order_breakdown_6'], $macroArray);
								$textAdmin .= macroSub($langAdmin['email']['order_breakdown_6'], $macroArray);
								unset($macroArray);
							
		}
		else if($_POST['order'][$i]['status'] == 3){
			$maintest = macroSub($lang['email']['payment_cancelled_body'],$macroArrayMain);
							$maintestadmin = macroSub($langAdmin['email']['payment_cancelled_body'],$macroArrayMain);
							unset($macroArray);
				$macroArray = array(
									"PRODUCT_NAME" => $orderInv[$i]['name'],
									"PRODUCT_CODE" => $orderInv[$i]['productCode'],
									"IMEI" => $orderInv[$i]['imei'],
									"COMENTS" => $_POST['order'][$i]['notes'],	
									"MODEL" => $orderInv[$i]['product_options'],						
									"PRODUCT_PRICE" => priceFormat($orderInv[$i]['price'],true)
								);					
								$text .= macroSub($lang['email']['order_breakdown_6'], $macroArray);
								$textAdmin .= macroSub($langAdmin['email']['order_breakdown_6'], $macroArray);
								unset($macroArray);
								if($_POST['credits'] > 0){
							$returncredits = $returncredits + $orderInv[$i]['price'];
	
							if($orderSum['status']== 2 && $module['status'] == 1){
					if($orderSum['gateway']== "TopUp"){
					$updatebalance['card_balance'] = "card_balance +".$orderInv[$i]['price'];
	$where = "customer_id=".$db->mySQLSafe($orderSum['customer_id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $updatebalance, $where);
	// insert transaction record
$SelectBalance 	= $db->select("SELECT `card_balance` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `customer_id`=".$db->mySQLSafe($orderSum['customer_id']));
			$transData['customer_id']	= $orderSum['customer_id'];
			$transData['trans_id'] 		= $orderSum['cart_order_id'];	
			$transData['dr'] 		= 	$orderInv[$i]['price'] ;
			$transData['imei'] 		= $orderInv[$i]['imei'];
			$transData['notes'] 		= "Crddits added on order cancel";
			$transData['balance'] 		= $SelectBalance[0]['card_balance'];
			storeCreditTrans($transData);	
			unset($transData);
					}}
								}
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
				$mail->setText($text);
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setReturnPath($config['masterEmail']);
				$mail->setSubject(macroSub($lang['email']['order_breakdown_subject'], array("ORDER_ID" => $orderSum['cart_order_id'])));
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array(sanitizeVar($orderSum['email'])), $config['mailMethod']);
			

		if($orderSum['status']== 2 && $returncredits > 0 && $module['status'] == 1){
					if($orderSum['gateway']== "TopUp"){
					$query = $db->select("SELECT transactionId,id FROM ".$glob['dbprefix']."tbl_topup_payment_transactions WHERE transactionId=".$db->mySQLSafe($orderSum['cart_order_id'].$_POST['order'][$i]['vid'])."AND carrier_id =".$db->mySQLSafe($_POST['order'][$i]['vid']));
					if(empty($query)){					
						$record['notes'] = $db->mySQLSafe("Credit added on cancel order");
						$record['amount'] = $db->mySQLSafe($returncredits);
						$record['status'] = $db->mySQLSafe(1);
						$record['date_topped'] = $db->mySQLSafe(time(0));
						$record['customerid'] = $db->mySQLSafe($orderSum['customer_id']);
						$record['gateway'] = $db->mySQLSafe("Admin");
						$record['transactionId'] = $db->mySQLSafe($orderSum['cart_order_id'].$_POST['order'][$i]['vid']);
						$record['carrier_id'] = $db->mySQLSafe($_POST['order'][$i]['vid']);
						$insert = $db->insert("tbl_topup_payment_transactions", $record);
				
					}
					}
					}
		}

$db->close();	
?>