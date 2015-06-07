<?php

/*

+--------------------------------------------------------------------------

|	order.php

|   ========================================

|	Core Order Class	

+--------------------------------ImeiUnlock------------------------------------------

*/

if (!defined('CC_INI_SET')) die("Access Denied");



class order {

	

	var $order;

	var $orderSum;

	var $orderInv;

	

	/*

	function order() {

		## Process level constants

		define('ORDER_PENDING', 1);

		define('ORDER_PROCESSING', 2);

		define('ORDER_COMPLETE', 3);

		define('ORDER_DECLINED', 4);

		define('ORDER_FAILED', 5);

		define('ORDER_CANCELLED', 6);

	}

	*/

	

	function storeTrans($transData, $forceLog = true) {

		global $glob, $db;

	

		$transDataSQL['time'] 			= $db->MySQLSafe(time());

		$transDataSQL['customer_id'] 	= $db->MySQLSafe($transData['customer_id']);

		$transDataSQL['gateway'] 		= $db->MySQLSafe($transData['gateway']);

		$transDataSQL['extra'] 			= $db->MySQLSafe($transData['extra']);

		$transDataSQL['trans_id'] 		= $db->MySQLSafe($transData['trans_id']);

		$transDataSQL['order_id']		= $db->MySQLSafe($transData['order_id']);

		$transDataSQL['status'] 		= $db->MySQLSafe($transData['status']);

		$transDataSQL['amount'] 		= $db->MySQLSafe($transData['amount']);

		$transDataSQL['notes'] 			= $db->MySQLSafe($transData['notes']);

		

		// make sure status isn't repeated on last call

		$maxStatus = $db->select("SELECT max(`id`), `status` FROM ".$glob['dbprefix']."ImeiUnlock_transactions WHERE `trans_id` = ".$transDataSQL['trans_id']." GROUP BY `id` DESC");	

		

		if (!$forceLog && ($maxStatus[0]['status'] !== $transData['status'] || !$maxStatus)) {

			$db->insert($glob['dbprefix']."ImeiUnlock_transactions", $transDataSQL);

		} else if ($forceLog) {

			$db->insert($glob['dbprefix']."ImeiUnlock_transactions", $transDataSQL);

		}

	}


	

	function mkOrderNo() {

		global $config,$db;;


	$order_id = $db->select("SELECT Auto_increment  FROM information_schema.tables WHERE table_schema = DATABASE() AND TABLE_NAME = 'ImeiUnlock_order_sum' ");
	
		$this->cart_order_id =$order_id[0]['Auto_increment'];

		return $this->cart_order_id;

	}

	

	function getOrderSum($cart_order_id) {

		global $db, $glob;

		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id = ".$db->mySQLSafe($cart_order_id);

		//$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum  WHERE `cart_order_id` = ".$db->mySQLSafe($cart_order_id);

		$order = $db->select($query);

		$this->orderSum = $order[0];

		return $order[0];

	}

	

	function getOrderInv($cart_order_id) {

		global $db, $glob;

		$products = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($cart_order_id));

		$this->orderInv = $products;

		return $this->orderInv;

	}

	

	function deleteOrder($cart_order_id) {

		global $db, $glob;

		$where = "cart_order_id = '".$cart_order_id."'";

		$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_sum", $where);

		$delete = $db->delete($glob['dbprefix']."ImeiUnlock_order_inv", $where);

		$delete = $db->delete($glob['dbprefix']."ImeiUnlock_Downloads", $where);

	}

	

	function customerOrderCount($customerId, $value) {

		global $db, $glob;

		

		$record['noOrders'] = ($value>0) ? "noOrders + ".$value : "noOrders - ".$value;

		$where = "customer_id = ".$customerId;

		$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);

	}

	

	function manageStock($statusId,$cart_order_id){

		

		global $db, $glob, $config;

		

		if(!is_array($this->orderInv)){

			$this->getOrderInv($cart_order_id);

		}

	

		for($i=0; $i<count($this->orderInv); $i++) {

		

			// see if product uses stock or not

			$useStock = $db->select("SELECT `useStockLevel` FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE `productId` = ".$db->mySQLSafe($this->orderInv[$i]['productId']));		

			

			// if it does continue

			if ($useStock[0]['useStockLevel']) {

				// When order has been completed (Order status: Complete)

				if ($config['stock_change_time'] == 0) {

					$reduceStockStatus = 3;

				// When payment has been received (Order status: Processing) 

				} elseif ($config['stock_change_time'] == 1) {

					$reduceStockStatus = 2;

				// When order is built (Order status: Pending)

				} elseif($config['stock_change_time'] == 2) { 

					$reduceStockStatus = 1;

					// override possible config error cant put stock back for pending orders in this state

					$config['stock_replace_time'][1] = 0;

					

				}

				

				// reduce stock if not already and status matches time to reduce stock

				if($this->orderInv[$i]['stockUpdated']==0 && $statusId == $reduceStockStatus) {

					$this->stockLevel($this->orderInv[$i]['quantity'], "-", $this->orderInv[$i]['productId'], $this->orderInv[$i]['id'], 1);

				// replace stock if reduced already and status permits

				} elseif($this->orderInv[$i]['stockUpdated']==true && $config['stock_replace_time'][$statusId]==true) {

					$this->stockLevel($this->orderInv[$i]['quantity'], "+", $this->orderInv[$i]['productId'], $this->orderInv[$i]['id'], 0);

				}

			

			}

			

		}

	

	}

	

	function getOrderStatus() {

		$currentStatus = $db->select("SELECT status FROM ".$GLOBALS['glob']['dbprefix']."ImeiUnlock_order_sum WHERE cart_order_id = ".$db->MySQLSafe($cart_order_id));

		if ($currentStatus) {

			return $currentStatus[0]['status'];

		}

		return false;

	}

	function getordertype($cart_order_id){
		global $db, $glob;
		$repairorders = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE `cart_order_id` = ".$db->MySQLSafe($cart_order_id)." AND digital = '2'");
		$digitalorders = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE `cart_order_id` = ".$db->MySQLSafe($cart_order_id)." AND digital = '1'");
		$tangibalorders = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE `cart_order_id` = ".$db->MySQLSafe($cart_order_id)." AND digital = '0'");
		if($repairorders && !$digitalorders && !$tangibalorders) ## only repair orders
		return 1;
		if(!$repairorders && $digitalorders && !$tangibalorders) ## only digital orders
		return 2;
		if(!$repairorders && !$digitalorders && $tangibalorders) ## only tangibal orders
		return 3;
		else if($repairorders && $digitalorders && !$tangibalorders) ## repair and digital order
		return 4;
		else if(!$repairorders && $digitalorders && $tangibalorders) ## tangibal and digital order
		return 5;
		else if($repairorders && !$digitalorders && $tangibalorders) ## repair and tangibal order
		return 6;
		else if($repairorders && $digitalorders && $tangibalorders) ## mix order
		return 7;
		else
		return 8;
	}

		function getmailcontent($id){
		global $db, $glob;
		$email_cont = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_email_content WHERE id = ".$db->MySQLSafe($id));
		$this->mailcontent = $email_cont[0];
		return $this->mailcontent;
	}

	function orderStatus($statusId, $cart_order_id, $force = false, $skipEmail = false) {

		global $db, $glob, $config;

		

		/*

		1. Pending (New Order)

		2. Processing (See order notes)

		3. Order Complete & Dispatched

		4. Declined (See notes)

		5. Failed Fraud Review

		6. Cancelled

		*/

		

		// First make sure this process isn't being repeated! Some payment processors 

		// send more than once in the XML if other attributes have changed

		

		$currentStatus = $db->select("SELECT `status` FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE `cart_order_id` = ".$db->MySQLSafe($cart_order_id) );

		

		$this->manageStock($statusId, $cart_order_id);

		

		if ($currentStatus[0]['status'] !== $statusId) {

			switch($statusId) {

				case 2;		## Processing Nothing to do
				
				## check order type
					 $ordertype = $this->getordertype($cart_order_id);
					## repair order email
					if($ordertype == 1 || $ordertype == 4 || $ordertype == 6 || $ordertype == 7){
					require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."cart".CC_DS."repair.php";
					$repair = new repairorder();
					$this->getOrderInv($repairid);
					$repair->orderStatus($statusId, $cart_order_id, $this->OrderInv['stat']);
					}
					## tangibal order email
					if($ordertype == 3 || $ordertype == 5 || $ordertype == 6 || $ordertype == 7 || $ordertype == 8){
					require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."cart".CC_DS."accessries.php";

					$repair = new accessorder();
					$this->getOrderInv($repairid);
					$repair->orderStatus($statusId, $cart_order_id, $this->OrderInv['stat']);
					}
					## digital order email
					if($ordertype == 3 || $ordertype == 2 || $ordertype == 4 || $ordertype == 7 ){
					## Email the customer to say payment has been accepted and cleared

					$this->getmailcontent('8');
					
					$this->getOrderSum($cart_order_id);					

					$macroArray = array(

						"ORDER_ID"		=> $this->orderSum['cart_order_id'],

						"RECIP_NAME"	=> $this->orderSum['name'],

					);

				

					$text = macroSub($this->mailcontent['email_content'], $macroArray);

					unset($macroArray);


					## Send email

					require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";

					$mail = new htmlMimeMail();					
					$html = stripslashes($text);
					$mail->setHtml($html);

					$mail->setReturnPath($config['masterEmail']);

					$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');

					$mail->setSubject(macroSub($this->mailcontent['subject'], array("ORDER_ID" => $this->orderSum['cart_order_id'])));

					$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');

					$mail->setBcc($config['masterEmail']);
					$mail->send(array($this->orderSum['email']), $config['mailMethod']);
					}
					$this->getOrderInv($cart_order_id);
					if (is_array($this->orderInv)) {

						foreach ($this->orderInv as $i => $orderItem) {

			

							## Send Gift Certificate 


							if (!empty($this->orderInv[$i]['custom'])) {

								$customArray = unserialize(html_entity_decode($this->orderInv[$i]['custom']));
								if ($customArray['cert'] == true) {

									$this->sendCoupon($customArray, $this->orderInv[$i]['id']);
								$breakStatus = true;  ## Stops email sending below no way to stop this case ?!
								}

							}

						}
					}
					break;

				

				case 3:		## Order Complete (Payment Taken/Cleared)

					$breakStatus = false;

					## Look up order

					$this->getOrderSum($cart_order_id);

					$this->getOrderInv($cart_order_id);

					

				#	$count = count($this->orderInv);

				#	for ($i=0; $i<$count; $i++) {

					if (is_array($this->orderInv)) {

						foreach ($this->orderInv as $i => $orderItem) {

				

							## If the order contains tangible items, we set it to ORDER_PROCESSING, and break the loop

							if (!$force) {

								$this->orderStatus(2, $cart_order_id);

								$statusId = 2; ## Safeguard

								$breakStatus = true;  ## Stops email sending below no way to stop this case ?!

								break;

							}

							

							## Send Gift Certificate 


						/*	if (!empty($this->orderInv[$i]['custom'])) {

								$customArray = unserialize(html_entity_decode($this->orderInv[$i]['custom']));
								if ($customArray['cert'] == true) {

									$this->sendCoupon($customArray, $this->orderInv[$i]['id']);
								$breakStatus = true;  ## Stops email sending below no way to stop this case ?!
								}

							}*/

						}

						

						if($breakStatus == false) {

						

							## If order is completely digital send digital file and keep status as complete

							//$this->digitalAccess();

							## Send order complete email OOOOOH it's a bit diiiirty

							$lang = getLang("email.inc.php",$this->orderSum['lang']);

							$langAdmin = getLang("email.inc.php");

							if ($this->orderSum['discount']>0) {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true)." (-".priceFormat($this->orderSum['discount'], true).")";

							} else {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true);

							}

							

							

							## Get taxes

							$tax_cost = "";

							$lang_tax = getLang("admin".CC_DS."admin_orders.inc.php");

							$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");

							if ($config_tax_mod['status']) {

								for ($i=0; $i<3; $i++) {

									$tax_key_name = 'tax'.($i+1).'_disp';

									$tax_key_value = 'tax'.($i+1).'_amt';

									if ($this->orderSum[$tax_key_name] != "") {

										$name	= $this->orderSum[$tax_key_name];

										$value	= priceFormat($this->orderSum[$tax_key_value], true);

										$tax_cost .= $name." ".$value."\n";

									} else if ($i==0) {

										$tax_key_value = 'total_tax';

										$name	= $lang_tax['admin']['orders_total_tax'];

										$value	= priceFormat($this->orderSum[$tax_key_value], true);

										$tax_cost .= $name." ".$value."\n";

									} else {

										break;

									}

						

								}

								$tax_cost = substr($tax_cost, 0, -2);

							} else {

								$tax_cost = $lang_tax['admin']['orders_total_tax']." ".priceFormat($this->orderSum['total_tax'], true);

							}

							

							

							

						}

					


					}

					break;

				

				//case 4: // Declined nothing to do 

				

				//break;

				

				case 5:

					## email customer to explain their order failed fraud review

					$this->orderSum = $this->getOrderSum($cart_order_id);

					

					$lang = getLang("email.inc.php",$this->orderSum['lang']);

					

					$macroArray = array(

						"ORDER_ID" => $this->orderSum['cart_order_id'],

						"RECIP_NAME" => $this->orderSum['name'],

						"ORDER_URL_PATH" => $glob['storeURL']."/index.php?_g=co&_a=viewOrder&cart_order_id=".$this->orderSum['cart_order_id'],

						"STORE_URL" => $glob['storeURL']

					);

				

					$text = macroSub($lang['email']['fraud_body'],$macroArray);

					unset($macroArray);

					

					if (!empty($_POST['extra_notes'])) {

						$text .= "\n\n---\n".$_POST['extra_notes'];

					}

					

					## send email

					require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";

					

					$mail = new htmlMimeMail();

					$mail->setText($text);

					$mail->setReturnPath($config['masterEmail']);

					$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');

					$mail->setSubject(macroSub($lang['email']['fraud_subject'],array("ORDER_ID" => $this->orderSum['cart_order_id'])));

					$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');

					$mail->setBcc($config['masterEmail']);

					$mail->send(array($this->orderSum['email']), $config['mailMethod']);

					break;

				

				case 6: ## cancelled (Can be cancelled by either admin/customer)

					$this->orderSum = $this->getOrderSum($cart_order_id);

					

					## Prevent Voucher Fraud (#1400)

					$db->update($glob['dbprefix'].'ImeiUnlock_Coupons', array('status' => '0'), array('cart_order_id' => $this->orderSum['cart_order_id']));
				

			}

			

			$data['status'] = $statusId;

			$db->update($glob['dbprefix']."ImeiUnlock_order_sum", $data, "cart_order_id=".$db->mySQLSafe($cart_order_id));

			if($statusId == 2){
				$repairorders = $db->select("SELECT `id` FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE `digital` != '1' AND `cart_order_id` = ".$cart_order_id);				if($repairorders){
					foreach($repairorders as $value){
					$data2['stat'] = 2;					
					$db->update($glob['dbprefix']."ImeiUnlock_order_inv", $data2, "id=".$db->mySQLSafe($value['id']));
					}
				}
		
	
			}

			return true;

		}

		return false;

	}

	

	

	function stockLevel($level, $sign, $productId, $orderInvId, $stockUpdated) {

		global $db, $glob;

		

		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET stock_level = stock_level ".$sign." ".$level." WHERE `productId` = ".$productId;

		$update = $db->misc($query);

		

		$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_order_inv SET stockUpdated =  ".$stockUpdated." WHERE `id` = ".$orderInvId;

		$update = $db->misc($query);

		

	}

	

	

	function sendCoupon($customArray, $id) {

		

		global $db, $cart_order_id, $glob, $lang, $config, $order;

		

		## Create coupon code for the gift certificate

		$chars		= array("A","B","C","D","E","F","G","H","J","K","L",'M',"N","P","Q","R","S","T","U","V","W","X","Y","Z");

		$max_chars	= count($chars)-1;

		$coupon		= sprintf('%s-%d-%d', $chars[mt_rand(0, $max_chars)].$chars[mt_rand(0, $max_chars)], time(), mt_rand(1000, 9999));

		

		## e.g: RW-1147691506-6723

		

		$data['status']				= $db->mySQLSafe(1); 

		$data['code']				= $db->mySQLSafe($coupon);

		$data['discount_percent']	= $db->mySQLSafe(0);

		$data['discount_price']		= $db->mySQLSafe($customArray['amount']);

		$data['expires']			= $db->mySQLSafe(0); 

		$data['allowed_uses']		= $db->mySQLSafe(0);

		$data['cart_order_id']		= $db->mySQLSafe($this->orderSum['cart_order_id']); 

		

		$db->insert($glob['dbprefix']."ImeiUnlock_Coupons", $data);

		

		$couponId['couponId']		= $db->insertid();

		$db->update($glob['dbprefix'].'ImeiUnlock_order_inv', $couponId, 'id='.$db->mySQLSafe($id));

		

		$lang = getLang('email.inc.php',$this->orderSum['lang']);

		

		$macroArray = array(

			"RECIP_NAME"	=> $customArray['recipName'],

			"SENDER_NAME"	=> $this->orderSum['name'],

			"SENDER_EMAIL"	=> $this->orderSum['email'],

			"AMOUNT"		=> priceFormat($customArray['amount'], true),

			"MESSAGE"		=> html_entity_decode($customArray['message']),

			"COUPON"		=> $coupon,

			"STORE_URL"		=> $glob['storeURL']

		);

		

		$couponText = macroSub($lang['email']['coupon_body'], $macroArray);

		unset($macroArray);

		

		## Send email

		require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";

		

		$mail = new htmlMimeMail();

		$mail->setText($couponText);

		$mail->setReturnPath($config['masterEmail']);

		$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');

		$mail->setSubject($lang['email']['coupon_subject']);

		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');

		$mail->send(array($customArray['recipEmail']), $config['mailMethod']);

	

	}

	

	function digitalAccess() {
		
		}

	

	function cancelOldOrders() {

	

		global $db, $glob, $config;

		

		if($config['orderExpire']==0) {

		

			return false;

		

		} else {

		

			$expiryLimit = time() - $config['orderExpire'];

		

			$expiredOrders = $db->select("SELECT `cart_order_id` FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE `status` = 1 AND `time` < ".$expiryLimit);

			

			if($expiredOrders) {

				for($i=0; $i<count($expiredOrders); $i++) {

					$this->orderStatus(6, $expiredOrders[$i]['cart_order_id']);

				}	

			}

			return true;

		}

	

	}

	

	function createOrder($orderInv, $orderSum, $skipEmail = false, $lang = false, $code_used = false) {

		global $glob, $config, $db;

		

		if(!empty($code_used)){

			$orderSumIn['comments'] = $db->mySQLSafe('Voucher: '.$code_used);	

		}

		

		$gc	= fetchDbConfig('gift_certs');

		if (is_array($orderInv)) {

			for ($i=1; $i<=count($orderInv); $i++) {

				foreach ($orderInv[$i] as $key => $value) {

					$orderInvIn[$key] = $db->mySQLSafe($value);

					$orderInvIn['cart_order_id'] = $db->mySQLSafe($orderSum['cart_order_id']);

				}

				$insert		= $db->insert($glob['dbprefix']."ImeiUnlock_order_inv", $orderInvIn);

				

			

				if ((bool)$orderInv[$i]['digital'] && $orderInv[$i]['productCode'] !== $gc['productCode']) {

					$digitalProduct['cart_order_id'] = $db->mySQLSafe($orderSum['cart_order_id']);

					$digitalProduct['customerId'] = $db->mySQLSafe($orderSum['customer_id']);

					$digitalProduct['expire'] = $db->mySQLSafe(time()+$config['dnLoadExpire']);

					$digitalProduct['productId'] = $db->mySQLSafe($orderInv[$i]['productId']);

					$digitalProduct['accessKey'] = $db->mySQLSafe(randomPass());

					$insert = $db->insert($glob['dbprefix']."ImeiUnlock_Downloads", $digitalProduct);

				}

			}

			if (!$insert) {

				echo "An error building the order inventory was encountered. Please inform a member of staff.";

				exit;

			}

		}

		

		## Insert order summary

		if (is_array($orderSum)) {

			foreach ($orderSum as $key => $value) {

				$orderSumIn[$key] 	= $db->mySQLSafe($value);

			}

			$orderLang = ($lang==true) ? $lang : $config['defaultLang'];

			$orderSumIn['ip']  		= $db->mySQLSafe(get_ip_address());

			$orderSumIn['time'] 	= $db->mySQLSafe(time());

			$orderSumIn['lang'] 	= $db->mySQLSafe($orderLang);

			

			$db->insert($glob['dbprefix']."ImeiUnlock_order_sum", $orderSumIn);

		}



		## update customers order count + 1

		$this->customerOrderCount($orderSum['customer_id'], 1);

		$this->orderSum = $orderSum;

		if($skipEmail==false) {

			$this->newOrderEmail();

		}

		##  set order status to 1, this will reduce stock accordingly 

		$this->orderStatus(1, $orderSum['cart_order_id']);

		$this->cancelOldOrders();

	}

	

	function newOrderEmail($cart_order_id = '') {

		global $glob, $config, $lang;

		if (!empty($cart_order_id)) {

			$this->getOrderSum($cart_order_id);

			$this->getOrderInv($cart_order_id);	

		}

		

		if (!class_exists('htmlMimeMail')) {

			require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";

		}

		$lang 			= getLang("email.inc.php",$this->orderSum['lang']);

		$langDefault = getLang("email.inc.php");

		## email to storekeeper

		if ($config['disable_alert_email'] != true) {

			$mail = new htmlMimeMail();

			

			$macroArray = array(

				"CUSTOMER_NAME" => $this->orderSum['name'],

				"ORDER_ID" => $this->orderSum['cart_order_id'],

				"ADMIN_ORDER_URL" => $glob['storeURL']."/".$glob['adminFile']."?_g=orders/orderBuilder&edit=".$this->orderSum['cart_order_id'],

				"SENDER_ID" => get_ip_address(),

			);

			$text = macroSub($langDefault['email']['admin_pending_order_body'],$macroArray);

			unset($macroArray);

			$mail->setText($text);

			$mail->setReturnPath($config['masterEmail']);

			$mail->setFrom($this->orderSum['name'].' <'.$this->orderSum['email'].'>');

			$mail->setSubject(macroSub($langDefault['email']['admin_pending_order_subject'],array("ORDER_ID" => $this->orderSum['cart_order_id'])));

			$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');

			$mail->send(array($config['masterEmail']), $config['mailMethod']);

		}

		

		

	}

}



?>