<?php

/*

+--------------------------------------------------------------------------

|	order.php

|   ========================================

|	Core Order Class	

+--------------------------------ImeiUnlock------------------------------------------

*/

if (!defined('CC_INI_SET')) die("Access Denied");



class repairorder {

	

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

				case 2;		## Processing Nothing to do

					## Email the customer to say payment has been accepted and cleared

					$lang = getLang("email.inc.php",$this->orderSum['lang']);
					
					$this->getmailcontent('2');
					
					$this->getOrderSum($cart_order_id);
					

					$macroArray = array(

						"ORDER_ID"		=> $this->orderSum['cart_order_id'],

						"RECIP_NAME"	=> $this->orderSum['name'],

						"STORE_URL"		=> $glob['storeURL']

					);

				

					$text = macroSub($this->mailcontent['email_content'], $macroArray);

					unset($macroArray);

					

					if (!empty($_POST['extra_notes'])) {

						$text .= "\n\n---\n".$_POST['extra_notes'];

					}

					

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
					## send Sms
					if($config['SmsGlobal'] == true && $config['SmsGlobalp'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" && $this->orderSum['phone'] != "" ){
					$this->getOrderInv($repairid);
					if($this->orderInv['productId'] > 0){
					$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
					$tree = getCatDir($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
					}else{
					$tree = $this->orderInv['make']. ' '.$this->orderInv['device'].' '.$this->orderInv['model'];
					}
					$tree = str_replace('/', ' ', $tree);
					$macroArray = array(

						"DEVICE"		=> $tree,

						"RECIP_NAME"	=> $this->orderSum['name']

					);
					$text = macroSub($this->mailcontent['sms_content'], $macroArray);
					$text = strip_tags($text);
					$destination = $this->orderSum['phone'];
					 $smsglobal_response = $this->sendSMS($destination, $text); 
					}
					break;

				

				case 3:		## Order Complete (Payment Taken/Cleared)
					## Look up order

					$this->getOrderSum($cart_order_id);

					$this->getOrderInv($repairid);
					$this->getmailcontent('1');

					if (is_array($this->orderInv)) {
					if($this->orderInv['make'] == ''){
					$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
					$tree = getmaketree($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
					$this->orderInv['make'] = $tree[0];
					$this->orderInv['device'] = $tree[1];
					$this->orderInv['model'] = $tree[2];
					$options = explode("\n", $this->orderInv['product_options']);
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
					$this->orderInv['imei'] = $imei[1]; 
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
						$this->orderInv['extra_notes'] = $coments[1];
						
						}
							## Send order complete email OOOOOH it's a bit diiiirty


							if ($this->orderSum['discount']>0) {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true)." (-".priceFormat($this->orderSum['discount'], true).")";

							} else {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true);

							}
							$mail = new htmlMimeMail();
							$macroArray = array(

							"ORDER_ID"		=> $this->orderSum['cart_order_id'],

							"RECIP_NAME"	=> $this->orderSum['name'],
							"TIME"	=> formatTime($this->orderSum['time']),

							"MAKE"	=> $this->orderInv['make'],
							"DEVICE"	=> $this->orderInv['device'],
							"MODEL"	=> $this->orderInv['model'],
							"PROBLEM"	=> $this->orderInv['name'],
							"IMEI"	=> $this->orderInv['imei'],
							"PRICE"	=> $this->orderInv['price'],
							"COMMENTS"	=> $this->orderInv['extra_notes'],
							"GATEWAY"	=> $this->orderSum['gateway']

							);

				

							$text = macroSub($this->mailcontent['email_content'], $macroArray);
							$html = stripslashes($text);
							$mail->setHtml($html);
							$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
							$mail->setReturnPath($config['masterEmail']);
							$mail->setSubject($this->mailcontent['subject']);
							$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
							$mail->setBcc($config['masterEmail']);
							$mail->send(array(sanitizeVar($this->orderSum['email'])), $config['mailMethod']);	
							## send Sms
							if($config['SmsGlobal'] == true && $config['SmsGlobalc'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" && $this->orderSum['phone'] != "" ){
							$this->getOrderInv($repairid);
							if($this->orderInv['productId'] > 0){
							$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
							$tree = getCatDir($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
							}else{
							$tree = $this->orderInv['make']. ' '.$this->orderInv['device'].' '.$this->orderInv['model'];
							}
							$tree = str_replace('/', ' ', $tree);
							$macroArray = array(
		
								"DEVICE"		=> $tree,
		
								"RECIP_NAME"	=> $this->orderSum['name']
		
							);
							 $text = macroSub($this->mailcontent['sms_content'], $macroArray);
							$text = strip_tags($text);
							$destination = $this->orderSum['phone'];
							 $smsglobal_response = $this->sendSMS($destination, $text); 
							}

						}

						break;

				

				case 4: // Dispose 
				
				$this->getOrderSum($cart_order_id);
				
					$this->getOrderInv($repairid);
					$this->getmailcontent('3');
					if (is_array($this->orderInv)) {
					if($this->orderInv['make'] == ''){
					$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
					$tree = getmaketree($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
					$this->orderInv['make'] = $tree[0];
					$this->orderInv['device'] = $tree[1];
					$this->orderInv['model'] = $tree[2];
					$options = explode("\n", $this->orderInv['product_options']);
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
					$this->orderInv['imei'] = $imei[1]; 
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
						$this->orderInv['extra_notes'] = $coments[1];
						
						}
							## Send order complete email OOOOOH it's a bit diiiirty


							if ($this->orderSum['discount']>0) {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true)." (-".priceFormat($this->orderSum['discount'], true).")";

							} else {

								$grandTotal = priceFormat($this->orderSum['prod_total'], true);

							}
							$mail = new htmlMimeMail();
							$macroArray = array(

							"ORDER_ID"		=> $this->orderSum['cart_order_id'],

							"RECIP_NAME"	=> $this->orderSum['name'],
							"TIME"	=> formatTime($this->orderSum['time']),

							"MAKE"	=> $this->orderInv['make'],
							"DEVICE"	=> $this->orderInv['device'],
							"MODEL"	=> $this->orderInv['model'],
							"PROBLEM"	=> $this->orderInv['name'],
							"IMEI"	=> $this->orderInv['imei'],
							"PRICE"	=> $this->orderInv['price'],
							"COMMENTS"	=> $this->orderInv['extra_notes'],
							"GATEWAY"	=> $this->orderSum['gateway']

							);

				

							$text = macroSub($this->mailcontent['email_content'], $macroArray);
							$html = stripslashes($text);
							$mail->setHtml($html);
							$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
							$mail->setReturnPath($config['masterEmail']);
							$mail->setSubject($this->mailcontent['subject']);
							$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
							$mail->setBcc($config['masterEmail']);
							$mail->send(array(sanitizeVar($this->orderSum['email'])), $config['mailMethod']);	
							## send Sms
							if($config['SmsGlobal'] == true && $config['SmsGlobalr'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" && $this->orderSum['phone'] != "" ){
							$this->getOrderInv($repairid);
							if($this->orderInv['productId'] > 0){
							$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
							$tree = getCatDir($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
							}else{
							$tree = $this->orderInv['make']. ' '.$this->orderInv['device'].' '.$this->orderInv['model'];
							}
							$tree = str_replace('/', ' ', $tree);
							$macroArray = array(
		
								"DEVICE"		=> $tree,
		
								"RECIP_NAME"	=> $this->orderSum['name']
		
							);
							$text = macroSub($this->mailcontent['sms_content'], $macroArray);
							$text = strip_tags($text);
						    $destination = $this->orderSum['phone'];
							 $smsglobal_response = $this->sendSMS($destination, $text); 
							}

					}
				

				break;
				
				case 10: // pending approval 
				
				## Email the customer to say pending approval 

					
					$this->getmailcontent('11');
					
					$this->getOrderSum($cart_order_id);
					
					$macroArray = array(

						"ORDER_ID"		=> $this->orderSum['cart_order_id'],

						"RECIP_NAME"	=> $this->orderSum['name'],

						"STORE_URL"		=> $glob['storeURL']

					);

				

					$text = macroSub($this->mailcontent['email_content'], $macroArray);

					unset($macroArray);

					

					if (!empty($_POST['extra_notes'])) {

						$text .= "\n\n---\n".$_POST['extra_notes'];

					}

					

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
					## send Sms
					if($config['SmsGlobal'] == true && $config['SmsGlobalpa'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" && $this->orderSum['phone'] != "" ){
					$this->getOrderInv($repairid);
					if($this->orderInv['productId'] > 0){
					$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
					$tree = getCatDir($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
					}else{
					$tree = $this->orderInv['make']. ' '.$this->orderInv['device'].' '.$this->orderInv['model'];
					}
					$tree = str_replace('/', ' ', $tree);
					$macroArray = array(

						"DEVICE"		=> $tree,

						"RECIP_NAME"	=> $this->orderSum['name']

					);
					$text = macroSub($this->mailcontent['sms_content'], $macroArray);
					$text = strip_tags($text);
					$destination = $this->orderSum['phone'];
					 $smsglobal_response = $this->sendSMS($destination, $text); 
					}
				

				break;
				
				case 12: // pending Quite approval 
				
				## Email the customer to say pending Quite approval 

					
					$this->getmailcontent('12');
					
					$this->getOrderSum($cart_order_id);
					
					$macroArray = array(

						"ORDER_ID"		=> $this->orderSum['cart_order_id'],

						"RECIP_NAME"	=> $this->orderSum['name'],

						"STORE_URL"		=> $glob['storeURL']

					);

				

					$text = macroSub($this->mailcontent['email_content'], $macroArray);

					unset($macroArray);

					

					if (!empty($_POST['extra_notes'])) {

						$text .= "\n\n---\n".$_POST['extra_notes'];

					}

					

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
					## send Sms
					if($config['SmsGlobal'] == true && $config['SmsGlobalpq'] == true && $config['smsglobaluser'] != "" && $config['smsglobalpass'] != "" && $this->orderSum['phone'] != "" ){
					$this->getOrderInv($repairid);
					if($this->orderInv['productId'] > 0){
					$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($this->orderInv['productId']));
					$tree = getCatDir($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);
					}else{
					$tree = $this->orderInv['make']. ' '.$this->orderInv['device'].' '.$this->orderInv['model'];
					}
					$tree = str_replace('/', ' ', $tree);
					$macroArray = array(

						"DEVICE"		=> $tree,

						"RECIP_NAME"	=> $this->orderSum['name']

					);
					$text = macroSub($this->mailcontent['sms_content'], $macroArray);
					$text = strip_tags($text);
					$destination = $this->orderSum['phone'];
					 $smsglobal_response = $this->sendSMS($destination, $text); 
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