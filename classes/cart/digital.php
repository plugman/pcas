<?php

/*

+--------------------------------------------------------------------------

|	order.php

|   ========================================

|	Core Order Class	

+--------------------------------ImeiUnlock------------------------------------------

*/

if (!defined('CC_INI_SET')) die("Access Denied");



class digitalorder {

	

	var $order;

	var $orderSum;

	var $orderInv;

	function getOrderSum($cart_order_id) {

		global $db, $glob;

		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum  WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id = ".$db->mySQLSafe($cart_order_id);

		//$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum  WHERE `cart_order_id` = ".$db->mySQLSafe($cart_order_id);

		$order = $db->select($query);

		$this->orderSum = $order[0];

		return $order[0];

	}

	

	function getOrderInv($id) {

		global $db, $glob;

		$products = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE id = ".$db->mySQLSafe($id));

		$this->orderInv = $products[0];

		return $this->orderInv;

	}


	function getOrderStatus() {

		$currentStatus = $db->select("SELECT status FROM ".$GLOBALS['glob']['dbprefix']."ImeiUnlock_order_sum WHERE cart_order_id = ".$db->MySQLSafe($cart_order_id));

		if ($currentStatus) {

			return $currentStatus[0]['status'];

		}

		return false;

	}
	function getmailcontent($id){
		global $db, $glob;
		$email_cont = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_email_content WHERE id = ".$db->MySQLSafe($id));
		$this->mailcontent = $email_cont[0];
		return $this->mailcontent;
	}
	

		

	function orderStatus($statusId, $cart_order_id, $repairid, $force = false, $skipEmail = false) {

		global $db, $glob, $config;

		$currentStatus = $db->select("SELECT `stat` FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE `id` = ".$db->MySQLSafe($repairid) );
		if ($currentStatus[0]['stat'] !== $statusId) {
			require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
			switch($statusId) {

				case 1;		## Processing Nothing to do

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
					break;

				

				case 2:		## Order Complete (Payment Taken/Cleared)
					## Look up order
					$this->getOrderSum($cart_order_id);
					$this->getOrderInv($repairid);
					$this->getmailcontent('10');
					if (is_array($this->orderInv)) {
					$macroArray = array(
								"RECIP_NAME"		=>  $this->orderSum['name'],
								"ORDER_ID"			=>  $this->orderSum['cart_order_id'],
								"ORDER_DATE"		=> formatTime( $this->orderSum['time']),
								"PRODUCT_NAME" => $this->orderInv['name'],
								"PRODUCT_CODE" => $this->orderInv['productCode'],
								"IMEI" => $this->orderInv['imei'],
								"COMENTS" => $this->orderInv['extra_notes'],
								"MODEL" => $this->orderInv['product_options'],						
								"PRODUCT_PRICE" => priceFormat($this->orderInv['price'],true),
								"PAYMENT_METHOD"	=> $this->orderSum['gateway'] == "TopUp" ? "Credits" : $this->orderSum['gateway']
							);

							$text = macroSub($this->mailcontent['email_content'], $macroArray);
							## Send email

							require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
		
							$mail = new htmlMimeMail();					
							$html = stripslashes($text);
							$mail->setHtml($html);
		
							$mail->setReturnPath($config['masterEmail']);
		
							$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
		
							$mail->setSubject(macroSub($this->mailcontent['subject'], array("IMEIS" => $this->orderInv['imei'])));
		
							$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		
							$mail->setBcc($config['masterEmail']);
							$mail->send(array($this->orderSum['email']), $config['mailMethod']);
							
					}

						break;

				

				case 3: // Dispose 
				
					$this->getOrderSum($cart_order_id);
					$this->getOrderInv($repairid);
					$this->getmailcontent('9');
					if (is_array($this->orderInv)) {
					$macroArray = array(
								"RECIP_NAME"		=>  $this->orderSum['name'],
								"ORDER_ID"			=>  $this->orderSum['cart_order_id'],
								"ORDER_DATE"		=> formatTime( $this->orderSum['time']),
								"PRODUCT_NAME" => $this->orderInv['name'],
								"PRODUCT_CODE" => $this->orderInv['productCode'],
								"IMEI" => $this->orderInv['imei'],
								"COMENTS" => $this->orderInv['extra_notes'],
								"MODEL" => $this->orderInv['product_options'],						
								"PRODUCT_PRICE" => priceFormat($this->orderInv['price'],true),
								"PAYMENT_METHOD"	=> $this->orderSum['gateway'] == "TopUp" ? "Credits" : $this->orderSum['gateway']
							);

							$text = macroSub($this->mailcontent['email_content'], $macroArray);
							## Send email

							require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
		
							$mail = new htmlMimeMail();					
							$html = stripslashes($text);
							$mail->setHtml($html);
		
							$mail->setReturnPath($config['masterEmail']);
		
							$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
		
							$mail->setSubject(macroSub($this->mailcontent['subject'], array("IMEIS" => $this->orderInv['imei'])));
		
							$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		
							$mail->setBcc($config['masterEmail']);
							$mail->send(array($this->orderSum['email']), $config['mailMethod']);
						// refund credits and insert transaction record 
						if($force){
						$updaterecord['card_balance'] = "card_balance + ".$this->orderInv['price']."";
						$where = "customer_id = ".$db->mySQLSafe($this->orderSum['customer_id']);
						 $db->update($glob['dbprefix']."ImeiUnlock_customer", $updaterecord, $where);
							$SelectBalance 	= $db->select("SELECT `card_balance` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `customer_id`=".$db->mySQLSafe($this->orderSum['customer_id']));
							$transData['customer_id']	= $this->orderSum['customer_id'];
							$transData['trans_id'] 		= $this->orderSum['cart_order_id'].$this->orderInv['id'];	
							$transData['dr'] 		= $this->orderInv['price'];
							$transData['imei'] 		= $this->orderInv['imei'];
							$transData['notes'] 		= "Credits Reversal on order cancel.";
							$transData['balance'] 		= $SelectBalance[0]['card_balance'];
							storeCreditTrans($transData);
						}
					}
				

				break;
				

			}

			

			$data['stat'] = $statusId;

			$db->update($glob['dbprefix']."ImeiUnlock_order_inv", $data, "id=".$db->mySQLSafe($repairid));

			return true;

		}

		return false;

	}


function sendSMS($sms_to, $sms_msg) { 
		global $config;
					if($config['SmsGlobal'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" ){
					$user = $config['smsglobaluser']; 
    				$pass = $config['smsglobalpass']; 
    				$sms_from    = $config['smsglobaltitle'];         
					$msg_type = "text";  	
					$unicode = "0";            
					$query_string = "http-api.php?action=sendsms&user=".$user."&password=".$pass;
					$query_string .= "&from=".rawurlencode($sms_from)."&to=".rawurlencode($sms_to);
					$query_string .= "&clientcharset=ISO-8859-1&";
					$query_string .= "text=".rawurlencode(stripslashes($sms_msg)) . "&detectcharset=1";    	
					$url = "http://www.smsglobal.com/".$query_string;  
					$fd = @implode ('', file ($url));  	
					if ($fd)  	
					{  		
						// got response from server  		
						$response = explode("; Sent queued message ID:",$fd);  		
						$response1 = explode(":",$response[0]);  		
						$smsglobal_status = trim($response1[1]);  		
						$response2 = explode(":",$response[1]);  		
						$smsglobalmsgid = trim($response2[1]);    		
						if ($smsglobal_status=="0")  		
						{  			
							// message sent successfully  			
							$ok = $smsglobalmsgid;
							$messageStat = "true";
						}  		
						else   		
						{  			
							// gateway will issue a pause here and output will be delayed  			
							// possible bad user name and password  			
							$ok = false;
							$messageStat = "false";
						}  	
					}  	
					else   	
					{  		
						// no contact with gateway  		
						$ok = false;  	
						$messageStat = "false";
					}  	
					return $ok;  
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


}



?>