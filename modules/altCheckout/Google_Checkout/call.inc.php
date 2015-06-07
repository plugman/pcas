<?php

/**
 * Copyright (C) 2006 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

 /* This is the response handler code that will be invoked every time
  * a notification or request is sent by the Google Server
  *
  * To allow this code to receive responses, the url for this file
  * must be set on the seller page under Settings->Integration as the
  * "API Callback URL'
  * Order processing commands can be sent automatically by placing these
  * commands appropriately
  *
  * To use this code for merchant-calculated feedback, this url must be
  * set also as the merchant-calculations-url when the cart is posted
  * Depending on your calculations for shipping, taxes, coupons and gift
  * certificates update parts of the code as required
  *
  */

if(!defined('CC_INI_SET')){ die("Access Denied"); }

require_once('library'.CC_DS.'googleresponse.php');
require_once('library'.CC_DS.'googlemerchantcalculations.php');
require_once('library'.CC_DS.'googleresult.php');

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = $HTTP_RAW_POST_DATA;
if (get_magic_quotes_gpc()){
	$xml_response = stripslashes($xml_response);
}
$headers = getallheaders();

require_once(CC_ROOT_DIR.CC_DS."classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php");

// Create new response object
$merchant_id = $module['merchId'];
$merchant_key = $module['merchKey']; 
$server_type = $module['mode'];

$response = new GoogleResponse($merchant_id, $merchant_key, $xml_response, $server_type);
$root = $response->root;
$data = $response->data;

function doDebug($extra = ""){

	global $config,$module,$xml_response,$response;
	
	if($module['debug']==1){
		// email to storekeeper	
		$mail = new htmlMimeMail();
			
		$text = print_r($response,true)."\n\n\n".$extra;
		
		$mail->setText($text);
		$mail->setReturnPath($config['masterEmail']);
		$mail->setFrom($config['masterEmail']);
		$mail->setSubject("Google Checkout Debug XML");
		$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
		$mail->send(array($config['masterEmail']), $config['mailMethod']);
	}

}

//Check status and take appropriate action
$status = $response->HttpAuthentication($headers);

switch ($root){
	
	case "request-received": 
	{
		break;
	}
	case "error":
	{
		break;
	}
	case "diagnosis":
	{
		break;
	}
	case "checkout-redirect":
	{
		break;
	}
	case "merchant-calculation-callback":
	{
		// Create the results and send it
		$merchant_calc = new GoogleMerchantCalculations();
		
		// Loop through the list of address ids from the callback
		$addresses = get_arr_result($data[$root]['calculate']['addresses']['anonymous-address']);
		
		foreach($addresses as $curr_address)
		{
			$curr_id = $curr_address['id'];
			$country = $curr_address['country-code']['VALUE'];
			$city = $curr_address['city']['VALUE'];
			$region = $curr_address['region']['VALUE'];
			$postal_code = $curr_address['postal-code']['VALUE'];
			
			// Loop through each shipping method if merchant-calculated shipping
			// support is to be provided
			if(isset($data[$root]['calculate']['shipping']))
			{
				$shipping = get_arr_result($data[$root]['calculate']['shipping']['method']);
				
				foreach($shipping as $curr_ship)
				{
					$name = $curr_ship['name'];
					//Compute the price for this shipping method and address id
					$price = 10; // Modify this to get the actual price
					$shippable = "true"; // Modify this as required
					$merchant_result = new GoogleResult($curr_id);
					$merchant_result->SetShippingDetails($name, $price, $config['defaultCurrency'],
						$shippable);
					
					if($data[$root]['calculate']['tax']['VALUE'] == "true")
					{
					  //Compute tax for this address id and shipping type
					  $amount = 15; // Modify this to the actual tax value
					  $merchant_result->SetTaxDetails($amount, $config['defaultCurrency']);
					}
					
					$codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
						['merchant-code-string']);
					foreach($codes as $curr_code)
					{
					  //Update this data as required to set whether the coupon is valid, the code and the amount
					  $coupons = new GoogleCoupons("true", $curr_code['code'], 5, $config['defaultCurrency'], "test2");
					  $merchant_result->AddCoupons($coupons);
					}
					$merchant_calc->AddResult($merchant_result);
				}
			
			}
			else
			{
			  $merchant_result = new GoogleResult($curr_id);
			  if($data[$root]['calculate']['tax']['VALUE'] == "true")
			  {
				//Compute tax for this address id and shipping type
				$amount = 15; // Modify this to the actual tax value
				$merchant_result->SetTaxDetails($amount, $config['defaultCurrency']);
			  }
			  $codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
				  ['merchant-code-string']);
			  foreach($codes as $curr_code)
			  {
				//Update this data as required to set whether the coupon is valid, the code and the amount
				$coupons = new GoogleCoupons("true", $curr_code['code'], 5, $config['defaultCurrency'], "test2");
				$merchant_result->AddCoupons($coupons);
			  }
			  $merchant_calc->AddResult($merchant_result);
			}
		}
		
		$response->ProcessMerchantCalculations($merchant_calc);
		break;
	}
	case "new-order-notification":
	{
		
		////////////////////////////
		// INSERT ORDER TO DATABASE AND ADD CUSTOMERS
		/////////
		
		// multiple items
		if(isset($data['new-order-notification']['shopping-cart']['items']['item'][0])) {
		
			$amountofkeys = "multiple";
			
			$subTotal = 0;
			// orderInv nees to be a key higher :-/ blame Al for being a dikhed
			$invKey=1;
			
			for($i=0;$i<count($data['new-order-notification']['shopping-cart']['items']['item']);$i++) {
			
				$orderInv[$invKey]['name'] 				= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['item-name']['VALUE'];
				$orderInv[$invKey]['price'] 			= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['unit-price']['VALUE'];
				$orderInv[$invKey]['quantity'] 			= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['quantity']['VALUE'];
				$orderInv[$invKey]['product_options'] 	= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['item-description']['VALUE'];
				// custom data
				$orderInv[$invKey]['productId'] 		= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['merchant-private-item-data']['productid']['VALUE'];
				$orderInv[$invKey]['productCode'] 		= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['merchant-private-item-data']['productcode']['VALUE'];
				$orderInv[$invKey]['digital'] 			= $data['new-order-notification']['shopping-cart']['items']['item'][$i]['merchant-private-item-data']['digital']['VALUE'];
				$orderInv[$invKey]['custom'] 			= 0;
				
				$subTotal = $subTotal + $data['new-order-notification']['shopping-cart']['items']['item'][$i]['unit-price']['VALUE'];
				
				$invKey++;
			
			}
		
		// single item 
		} else {
		
		$amountofkeys = "single";
		
			$subTotal = 0;
			// orderInv nees to be a key higher :-/ blame Al
			$orderInv[1]['name'] 				= $data['new-order-notification']['shopping-cart']['items']['item']['item-name']['VALUE'];
			$orderInv[1]['price'] 				= $data['new-order-notification']['shopping-cart']['items']['item']['unit-price']['VALUE'];
			$orderInv[1]['quantity'] 			= $data['new-order-notification']['shopping-cart']['items']['item']['quantity']['VALUE'];
			$orderInv[1]['product_options'] 	= $data['new-order-notification']['shopping-cart']['items']['item']['item-description']['VALUE'];
			// custom data
			$orderInv[1]['productId'] 			= $data['new-order-notification']['shopping-cart']['items']['item']['merchant-private-item-data']['productid']['VALUE'];
			$orderInv[1]['productCode'] 		= $data['new-order-notification']['shopping-cart']['items']['item']['merchant-private-item-data']['productcode']['VALUE'];
			$orderInv[1]['digital'] 			= $data['new-order-notification']['shopping-cart']['items']['item']['merchant-private-item-data']['digital']['VALUE'];
			$orderInv[1]['custom'] 			= 0;
			
			$subTotal = $subTotal + $data['new-order-notification']['shopping-cart']['items']['item']['unit-price']['VALUE'];
		}
		
		
		// insert/update customer
		
		$email = $data['new-order-notification']['buyer-billing-address']['email']['VALUE'];
		
		$customer = $db->select("SELECT `customer_id` FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `email` = ".$db->MySQLSafe($email));
		
		// update data
		$billingCountryId = getCountryFormat($data['new-order-notification']['buyer-billing-address']['country-code']['VALUE'],"iso","id");
		
		function name($fullName,$key)
		{
		
			$fullNameArray = explode(" ",$fullName);
			$maxKey = count($fullNameArray)-1;
		
			switch ($key) 
			{
				case 0:
				   return $fullNameArray[$key]; 
				break;
				case 1:
					
					$first = "";
					for($i=0;$i<count($fullNameArray);$i++)
					{
						if($i!==0 && $i!==$maxKey)
						{
							$first .=$fullNameArray[$i]." ";
						}
					}
					
					return trim($first);
					
				break;
				case 2:
				   return $fullNameArray[$maxKey];
				break;
			}	
		
		}
		
		$customerData['title']		= $db->MySQLSafe(name($data['new-order-notification']['buyer-billing-address']['contact-name']['VALUE'],0));
		$customerData['firstName'] 	= $db->MySQLSafe(name($data['new-order-notification']['buyer-billing-address']['contact-name']['VALUE'],1));
		$customerData['lastName']	= $db->MySQLSafe(name($data['new-order-notification']['buyer-billing-address']['contact-name']['VALUE'],2));
		$customerData['add_1'] 		= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['address1']['VALUE']);
		$customerData['add_2'] 		= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['address2']['VALUE']);
		$customerData['town'] 		= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['city']['VALUE']);
		$customerData['county']		= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['region']['VALUE']);
		$customerData['postcode']	= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['postal-code']['VALUE']);
		$customerData['country']	= $db->MySQLSafe($billingCountryId);
		$customerData['phone']		= $db->MySQLSafe($data['new-order-notification']['buyer-billing-address']['phone']['VALUE']);
		
		if($data['new-order-notification']['buyer-marketing-preferences']['email-allowed']['VALUE'] == "true")
		{
			$customerData['optIn1st'] = 1;
		}
		
		if($customer==TRUE)
		{
			$db->update($glob['dbprefix']."ImeiUnlock_customer",$customerData,"`email` = ".$db->MySQLSafe($email));
			$customer_id = $customer[0]['customer_id'];
		}
		else
		{
		
			// added insert data
			$password 					= randomPass(6);
			$salt 						= randomPass(6);
			$customerData['salt'] 		= $db->MySQLSafe($salt);
			$customerData['password'] 	= $db->MySQLSafe(md5(md5($salt).md5($password)));	
			$customerData['email']		= $db->MySQLSafe($email);
			$customerData['regTime']	= $db->MySQLSafe(time());
			$customerData['ipAddress']	= $db->MySQLSafe(get_ip_address()); // this will be googles IP :-/
			// ghost membership
			$customerData['type'] 		= $db->MySQLSafe(2);
			
			// send welcome email
			if($module['welcomeEmail']==true) {
				
				$emailLang = getLang("email.inc.php");
				
				$mail = new htmlMimeMail();
					
				$macroArray = array(
				
					"CUSTOMER_NAME" => $data['new-order-notification']['buyer-billing-address']['contact-name']['VALUE'],
					"EMAIL" => $email,
					"PASSWORD" => $password,
					"STORE_URL" => $glob['storeURL'],
					"SENDER_IP" => get_ip_address()
				
				);
				
				$text = macroSub($emailLang['email']['new_reg_body'],$macroArray);
				unset($macroArray);
				
				$mail->setText($text);
				$mail->setReturnPath($config['masterEmail']);
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setSubject($emailLang['email']['new_reg_subject']);
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array($email), $config['mailMethod']);
				// full membership
				$customerData['type'] 		= $db->MySQLSafe(1);
			} else {
				// ghost membership
				$customerData['type'] 		= $db->MySQLSafe(2);
			}
			
			$db->insert($glob['dbprefix']."ImeiUnlock_customer",$customerData);
			$customer_id = $db->insertid();
		
		}
		
		// empty basket and login
		
		$sessionData['customer_id'] = $db->MySQLSafe($customer_id);
		$sessionData['basket'] = "''";
		$sessionData['timeLast'] = $db->MySQLSafe(time());
		
		$db->update($glob['dbprefix']."ImeiUnlock_sessions",$sessionData,"`sessId` = ".$db->MySQLSafe($data['new-order-notification']['shopping-cart']['merchant-private-data']['sessionid']['VALUE']));
		
		$orderSum['cart_order_id'] 	= $data['new-order-notification']['shopping-cart']['merchant-private-data']['cart_order_id']['VALUE'];
		//$orderSum['sec_order_id'] 	= $data['new-order-notification']['google-order-number']['VALUE'];
		$orderSum['customer_id'] 	= $customer_id;
		$orderSum['email'] 			= $email;
		$orderSum['name'] 			= $data['new-order-notification']['buyer-billing-address']['contact-name']['VALUE'];
		$orderSum['add_1'] 			= $data['new-order-notification']['buyer-billing-address']['address1']['VALUE'];
		$orderSum['add_2'] 			= $data['new-order-notification']['buyer-billing-address']['address2']['VALUE'];
		$orderSum['town'] 			= $data['new-order-notification']['buyer-billing-address']['city']['VALUE'];
		$orderSum['county'] 		= $data['new-order-notification']['buyer-billing-address']['region']['VALUE'];
		$orderSum['postcode'] 		= $data['new-order-notification']['buyer-billing-address']['postal-code']['VALUE'];
		$orderSum['country']  		= getCountryFormat($billingCountryId, 'iso', 'printable_name');
		$orderSum['phone'] 			= $data['new-order-notification']['buyer-billing-address']['phone']['VALUE'];
		$orderSum['mobile']  		= ""; // unsupported
		$orderSum['currency'] 		= $config['defaultCurrency'];
		
		$orderSum['name_d'] 		= $data['new-order-notification']['buyer-shipping-address']['contact-name']['VALUE'];
		$orderSum['add_1_d'] 		= $data['new-order-notification']['buyer-shipping-address']['address1']['VALUE'];
		$orderSum['add_2_d'] 		= $data['new-order-notification']['buyer-shipping-address']['address2']['VALUE'];
		$orderSum['town_d'] 		= $data['new-order-notification']['buyer-shipping-address']['city']['VALUE'];
		$orderSum['county_d'] 		= $data['new-order-notification']['buyer-shipping-address']['region']['VALUE'];
		$orderSum['postcode_d'] 	= $data['new-order-notification']['buyer-shipping-address']['postal-code']['VALUE'];
		$orderSum['country_d'] 		= getCountryFormat($data['new-order-notification']['buyer-shipping-address']['country-code']['VALUE'], 'iso', 'printable_name');
		
		$orderSum['subtotal'] 		= $subTotal;
		$orderSum['discount'] 		= $data['new-order-notification']['order-adjustment']['merchant-codes']['coupon-adjustment']['applied-amount']['VALUE'];
		$orderSum['total_ship'] 	= $data['new-order-notification']['order-adjustment']['shipping']['flat-rate-shipping-adjustment']['shipping-cost']['VALUE'];
		$orderSum['total_tax'] 		= $data['new-order-notification']['order-adjustment']['total-tax']['VALUE'];
		$orderSum['prod_total'] 	= $data['new-order-notification']['order-total']['VALUE'];
		$orderSum['shipMethod'] 	= $data['new-order-notification']['order-adjustment']['shipping']['flat-rate-shipping-adjustment']['shipping-name']['VALUE'];
		
		// notification of coupon used
		$orderSum['comments'] 			= $data['new-order-notification']['order-adjustment']['merchant-codes']['coupon-adjustment']['message']['VALUE'];
		
		//$orderSum['tax'.$i.'_disp'] = $taxes[$i]['display'];
		//$orderSum['tax'.$i.'_amt'] = $taxes[$i]['amount'];
		
		$orderSum['gateway'] 		= "Google Checkout";
		
		
		doDebug(count($data['new-order-notification']['shopping-cart']['items']['item'])." - \n".$amountofkeys);
		
		// add order
		$order->createOrder($orderInv, $orderSum, $skipEmail = true);
		
		unset($orderInv,$orderSum);
				
		$transData['status'] = "NEW ORDER";
		$transData['notes']  = "A new order has been placed using Google Checkout";
		$transData['order_id'] = $data['new-order-notification']['shopping-cart']['merchant-private-data']['cart_order_id']['VALUE'];
		$transData['amount'] = $data['new-order-notification']['order-total']['VALUE'];
		$transData['trans_id'] = $data['new-order-notification']['google-order-number']['VALUE'];
		$transData['customer_id'] = $customer_id;
		$transData['gateway'] = "Google Checkout";
		$order->storeTrans($transData,FALSE);
		$response->SendAck();
		
		break;
	
	}
	
	case "order-state-change-notification": 
	{
		
		doDebug();
		
		$response->SendAck();
		$new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
		$new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];
		
		
		$assocData = $db->select("SELECT `customer_id`, `trans_id`, `order_id`, `amount` FROM ".$glob['dbprefix']."ImeiUnlock_transactions WHERE `trans_id` = ".$db->MySQLSafe($data['order-state-change-notification']['google-order-number']['VALUE']));
		
		$transData = $assocData[0];
		unset($assocData);
		$transData['status'] = $new_financial_state;
		$transData['gateway'] = "Google Checkout";
		$cart_order_id = $transData['order_id'];
		
		switch($new_financial_state)
		{
			
			case 'REVIEWING': 
			{
				//$order->orderStatus(2,$cart_order_id);
				
				$transData['notes'] = "REVIEWING is the default financial state for all new orders. Upon receiving a new order, Google reviews the order to confirm that it is chargeable. After determining that the order is chargeable, Google will update the financial order state to CHARGEABLE.";
				
				break;
			
			}
			case 'CHARGEABLE':
			{
			
				//$order->orderStatus(2,$cart_order_id);
				//$response->SendProcessOrder($data[$root]['google-order-number']['VALUE'], 
				//    $message_log);
				//$response->SendChargeOrder($data[$root]['google-order-number']['VALUE'], 
				//    '', $message_log);
				
				$transData['notes'] = "The CHARGEABLE state indicates that you may charge the customer for an order.";
				
				break;
				
			}
			case 'CHARGING':
			{
				
				//$order->orderStatus(2,$cart_order_id);
				
				$transData['notes'] = "The CHARGING state indicates that Google is in the process of charging the customer. You may not take any actions on an order in this state. Once the charge is completed, Google will update the financial order state to CHARGED if you charged the customer for the full amount of the order. Google will revert the financial order state to CHARGEABLE if you only partially charged the customer.";
				
				break;
			}
			case 'CHARGED':
			{
				/////////////////////
				// SET ORDER STATUS = PROCESS
				////
				if($new_fulfillment_order == "PROCESSING") {
					$order->orderStatus(2,$cart_order_id);
				} elseif($new_fulfillment_order == "DELIVERED") {
					// FORCE to 3 as it has been shipped and completed
					$order->orderStatus(3,$cart_order_id, $force = true, $skipEmail = false);
				}
			
				$transData['notes'] = "The CHARGED state indicates that you have fully or partially charged the customer for an order. If you have partially charged the customer, the order will still be chargeable until you have charged the customer for the full order amount.
				
				For partially charged orders, the buyer's account page will display the amount that has been charged.";
				
				break;
			}
			case 'PAYMENT_DECLINED':
			{
			
				$order->orderStatus(4,$cart_order_id);
				
				$transData['notes'] = "The PAYMENT_DECLINED state indicates that Google's effort to authorize or charge the customer's credit card failed. If this happens, Google will email the customer to request a new credit card. The customer will have 72 hours to submit a new card.";
				
				break;
			}
			case 'CANCELLED':
			{
			
				$order->orderStatus(6,$cart_order_id);
				
				$transData['notes'] = "The CANCELLED state indicates that the merchant canceled the order. Once an order is canceled, you may no longer update the order's financial order state.
				
				You may cancel an order that is in either the CHARGEABLE or the PAYMENT_DECLINED financial state. You may not cancel an order that has already been charged until you have already issued a refund for the offer.";
				break;
				
			}
			case 'CANCELLED_BY_GOOGLE': 
			{

				$order->orderStatus(6,$cart_order_id);
				//$response->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
				//    "Sorry, your order is cancelled by Google", true, $message_log);
				
				$transData['notes'] = "The CANCELLED_BY_GOOGLE state indicates that Google canceled an order. Google may cancel an order if the credit card authorization fails and the customer does not provide a new credit card within 72 hours.";		  
				
				break;
			}
			default:
			break;
			
			
		}
		
		$order->storeTrans($transData,FALSE);
		
		//$transData['status'] = $new_fulfillment_order;
		
		/*
		
		switch($new_fulfillment_order)
		{
			
			case 'NEW':
			{
				//$transData['notes'] = "NEW is the default fulfillment state for all new orders.";
				break;
			}
			case 'PROCESSING':
			{
				//$transData['notes'] = "The PROCESSING fulfillment state indicates that you are in the process of filling the customer's order.";
				  break;
			}
			case 'DELIVERED':
			{
				//$order->orderStatus(3);
				//$transData['notes'] = "The DELIVERED fulfillment state indicates that you have shipped the order and it has been delivered.";
				break;
			}
			case 'WILL_NOT_DELIVER':
			{
				//$transData['notes'] = "The WILL_NOT_DELIVER fulfillment state indicates that you will not ship the items to the customer.";
				break;
			}
			default:
				break;
			}
			
			// $order->storeTrans($transData,FALSE);
			
			*/
		
	}
	/*
	case "charge-amount-notification": 
	{
	
		//doDebug();
		
		$response->SendAck();
		//$response->SendDeliverOrder($data[$root]['google-order-number']['VALUE'], 
		//    <carrier>, <tracking-number>, <send-email>, $message_log);
		//$response->SendArchiveOrder($data[$root]['google-order-number']['VALUE'], 
		//    $message_log);
		break;
	}
	case "chargeback-amount-notification":
	{
		//doDebug();
		$response->SendAck();
		break;
	}
	case "refund-amount-notification":
	{
		//doDebug();
		$response->SendAck();
		break;
	}
	case "risk-information-notification":
	{
		//doDebug();
		$response->SendAck();
		break;
	}
	*/
	default:
	{
		break;
	}
}
/* In case the XML API contains multiple open tags
 with the same value, then invoke this function and
 perform a foreach on the resultant array.
 This takes care of cases when there is only one unique tag
 or multiple tags.
 Examples of this are "anonymous-address", "merchant-code-string"
 from the merchant-calculations-callback API
*/
function get_arr_result($child_node)
{

	$result = array();
	
	if(isset($child_node))
	{
		if(is_associative_array($child_node))
		{
			$result[] = $child_node;
		}
		else
		{
			foreach($child_node as $curr_node)
			{
			$result[] = $curr_node;
			}
		}
	}
	
	return $result;
}

/* Returns true if a given variable represents an associative array */
function is_associative_array( $var )
{
	return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
}

?>