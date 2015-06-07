<?php
/*
+--------------------------------------------------------------------------
|	changePass.inc.php
|   ========================================
|	Change the Customers Password	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

// include lang file
$lang 	= getLang("includes".CC_DS."content".CC_DS."topupBalance.inc.php");
//$lang2	= getLang("includes".CC_DS."content".CC_DS."account.inc.php");
## AH ## Date: 19 July 2011 -  ## Enhancement CR ## Start ## URL Rewriting - Links ##
/*$lang3 = getLang("includes".CC_DS."content".CC_DS."uri.inc.php");*/
/*$lang = array_merge($lang1, $lang2, $lang3);*/
## AH ## Date: 19 July 2011 -  ## Enhancement CR ## End ## URL Rewriting - Links ##
/*$carrier_info =  getCarrierInfo($_SERVER['HTTP_HOST']);*/
//$carrier_info['carrier_id'];
if(isset($_GET['cart_order_id'])&& $_GET['cart_order_id']!=""){
$_SESSION['topup']['cart_order_id'] = $_GET['cart_order_id'];
}

//if(isset($_POST['cardSubmit']) && $cc_session->ccUserData['customer_id']>0)
//{
//	
//	if(isset($_POST['gateway']) && $_POST['gateway'] > 0)
//	{
//	
//		if($_POST['gateway'] == 1)
//		$moduleId = 3;
//		elseif($_POST['gateway'] == 2)
//		$moduleId = 2;
//		
//		$query 		= "SELECT status from ImeiUnlock_Modules where moduleId =".$moduleId;
//		$module		= $db->select($query);
//		
//		if($module[0]['status'] == 1)
//		{
//				$gateway 		= trim($_POST['gateway']);
//				
//				$card_amount 	= GetPrice_defaultCurrency(trim($_POST['card_amount']));
//				
//				if($card_amount > 0 && !empty($card_amount) && isset($card_amount))
//				{
//				
//						header("Location: index.php?_a=topupBalance_processing&gateway=".$gateway."&card_amount=".$card_amount);
//				}
//				else
//				{
//					$_POST['optPayment'] = 2;
//					$errorMsg = $lang['topupBalance']['topup_amount_required'];
//				}	
//		}
//		else
//		{
//			$_POST['optPayment'] = 2;
//			$errorMsg = $lang['topupBalance']['payment_gateway_disabled'];
//		}
//		
//		
//	}
//	
//}
if(isset($_POST['submitPaymentOption']) && $cc_session->ccUserData['customer_id']>0)
{

	if(isset($_POST['optPayment']))
	{
		if(check_int($_REQUEST['optPayment']) != 1)
		{
			header("Location: index.php?_a=error");
		}
		else
		{
			if( $_POST['optPayment'] == 1 )
		{
			//Scratch Card case
			if(empty($_POST['scratch_number']))
				$errorMsg = $lang['topupBalance']['scratchcard_not_provided'];
			else
			{
				////////////////////////////////////
				$scratch_card_number	= $_POST['scratch_number'];
				$arr 					= str_split($scratch_card_number,3);
				$card_prefix 			= $arr[0];
				$scratch_code 			= $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];
				/////////////////////////////
				## AH ## MC-CR to fix tdbug# 31  ## Start ## 
				/*$query 		= "SELECT id,price FROM tbl_scratchcards WHERE carrier_id = ".$db->mySQLSafe($_SESSION['carrier']['carrier_id'])." AND customer_id = 0 AND (date_first_use = '0' OR date_first_use = NULL) AND status = '0' AND scratch_code = ".$db->mySQLSafe($scratch_code);*/
				$query 		= "SELECT id,price FROM tbl_scratchcards WHERE carrier_id = ".$db->mySQLSafe($carrier_info['carrier_id'])." AND customer_id = 0 AND (date_first_use = '0' OR date_first_use = NULL) AND status = '0' AND scratch_code = ".$db->mySQLSafe($scratch_code);
				## AH ## MC-CR to fix tdbug# 31  ## End ## 
				$results	= $db->select($query);
		
				if ($results == true && count(results) > 0)
				{
					$card_price					= $results[0]['price'];
					$card_id					= $results[0]['id'];
					//Update scratch card table
					$record["date_first_use"] 	= $db->mySQLSafe(time());
					$record["customer_id"] 		= $db->mySQLSafe($cc_session->ccUserData['customer_id']);
					$record["status"] 			= $db->mySQLSafe(2);
					
					$where 						= "id = ".$db->mySQLSafe($card_id);
					
					//Inserting the topup_transaction table
					$recordTransaction["date_topped"] 		= $db->mySQLSafe(time());
					$recordTransaction["transactionId"]		= $db->mySQLSafe($card_prefix.$scratch_code);
					$recordTransaction["customerId"] 		= $db->mySQLSafe($cc_session->ccUserData['customer_id']);
					$recordTransaction["status"] 			= $db->mySQLSafe(1);
					$recordTransaction["notes"] 			= $db->mySQLSafe('Payment successful.');
					$recordTransaction["gateway"] 			= $db->mySQLSafe("Scratch Card");
					$recordTransaction["amount"] 			= $db->mySQLSafe($card_price);
					$recordTransaction["carrier_id"] 		= $db->mySQLSafe($carrier_info['carrier_id']);
					$db->insert("tbl_topup_payment_transactions", $recordTransaction);
					#########################################
					$updateResult 	= $db->update("tbl_scratchcards",$record, $where);
					
					if($updateResult == true)
					{
						//Retreiving the Previous Balance Amount 
						$query 		= "SELECT card_balance as balance FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = ".$db->mySQLSafe($cc_session->ccUserData['customer_id']);
						$results	= $db->select($query);
						
						if ($results == true && count(results) > 0)
						{
							$prev_amount			= $results[0]['balance'];
							//Now adding customer balance amount
							$data["card_balance"] 	= $db->mySQLSafe($card_price + $prev_amount);
							$where 					= "customer_id = ".$db->mySQLSafe($cc_session->ccUserData['customer_id']);
							$updateResult 			= $db->update("ImeiUnlock_customer",$data, $where);
							
							if($updateResult == true){
								$successMsg = "<span class='greenMessage'>".$lang['topupBalance']['scratchcard_loaded']."</span>";
								if(isset($_SESSION['topup']['cart_order_id']) && $_SESSION['topup']['cart_order_id']!=""){
								
								$SessionTopOrderId = $_SESSION['topup']['cart_order_id'];
								unset($_SESSION['topup']['cart_order_id']);
								header("Location: index.php?_g=co&_a=topup&cart_order_id=".$SessionTopOrderId);
								}
							}
							else
								$errorMsg = $lang['topupBalance']['scratchcard_not_loaded'];
						}
						else
							$errorMsg = $lang['topupBalance']['scratchcard_not_loaded'];
					}
					else
						$errorMsg = $lang['topupBalance']['scratchcard_not_loaded'];
				}
				else
					$errorMsg = $lang['topupBalance']['scratchcard_not_found'];
		
			}//end else
			
		}
		else 
		if( $_POST['optPayment'] == 2 || $_POST['optPayment'] == 3)
		{
			//CBD Case and PayPal Case
				
			//	if($_POST['optPayment'] == 2)
//				{
//					$gateway = 2;
//					/*global $currencyVars; 
//				
//					if($currencyVars[0]['symbolLeft'] == 'AED' || $currencyVars[0]['symbolLeft'] == "AED")
//						$card_amount 	= number_format(trim($_POST['cbd_amount']), 2);
//					else*/
//					$card_amount 	= GetPrice_defaultCurrency(trim($_POST['cbd_amount']));
//				}
//				else
				if($_POST['optPayment'] == 3)
				{
					
					$gateway = 1;
					$card_amount 	= trim($_POST['paypal_amount']);
					//$card_amount = $_POST['paypal_amount'];
				//	// paypal processing fee
//					$paypal = $config['paypal'];
//					
//					if(isset($paypal) && $paypal > 0){
//						$paypalfee = $amountcur / 100 * $paypal ;
//						$paypalfee = number_format($paypalfee, 2, '.', '');	
//						}
//						else {
//							
//							$paypalfee =0;
//							}
//									$card_amount   = $amountcur + $paypalfee;
								}
/*								echo "<pre>";
print_r($card_amount);
die();*/
				$moduleId 		= $_POST['optPayment'];
				
				
				$query 		= "SELECT status from ImeiUnlock_Modules where moduleId =".$moduleId;
				$module		= $db->select($query);
				if($module[0]['status'] == 1)
				{
	
						if($card_amount > 0 && !empty($card_amount) && isset($card_amount))
						
							header("Location: index.php?_a=topupBalance_processing&gateway=".$gateway."&card_amount=".$card_amount);
						else
							$errorMsg = $lang['topupBalance']['topup_amount_required'];
				}
				else
					$errorMsg = $lang['topupBalance']['payment_gateway_disabled'];
		}
		}
	}
	
	if(isset($_POST['optPayment']))
	$optPayment = $_POST['optPayment'];
	
}
	$page = (isset($_GET['page'])) ? sanitizeVar($_GET['page']) : 0;
	$topup_balance = new XTemplate ("content".CC_DS."topupBalance.tpl");

	## AH ## Date: 19 July 2011 -  ## Enhancement CR ## Start ## URL Rewriting - Links ##
	if($config['sef']){
	$topup_balance->assign("BALANCE",'topupBalance.html');
}
else{
	$topup_balance->assign("BALANCE",'index.php?_a=topupBalance');
}
	## AH ## Date: 19 July 2011 -  ## Enhancement CR ## End ## URL Rewriting - Links ##
	## Checing Enabled Modules
	$query 		= "SELECT moduleId,folder,status from ImeiUnlock_Modules";
	$moduleRs	= $db->select($query);
	//print_r($moduleRs);
	
	//$topup_balance->assign("DISPLAY_TOPUP", "display:block;");
	//$topup_balance->assign("DISPLAY_CBD", "display:block;");
	//$topup_balance->assign("DISPLAY_PAYPAL", "display:block;");

	if(!empty($moduleRs))
	{
		if($moduleRs[0]['status'] == 0 && $moduleRs[1]['status'] == 0 && $moduleRs[2]['status'] == 0)
		$errorMsg = $lang['topupBalance']['no_payment_gateway_enabled'];
		else
		{
			if($moduleRs[0]['status'] == 1)
			$topup_balance->assign("DISPLAY_TOPUP", "display:block;");
			
			if($moduleRs[1]['status'] == 1)
			$topup_balance->assign("DISPLAY_CBD", "display:block;");
			
			if($moduleRs[2]['status'] == 1)
			$topup_balance->assign("DISPLAY_PAYPAL", "display:block;");	
		}
	}
	else
	$errorMsg = $lang['topupBalance']['no_payment_gateway_enabled'];
	
	
	//Show the History
	## AH ## MC-CR to fix tdbug# 31  ## Start ## 
	
/*	$history 	= "SELECT * FROM tbl_topup_payment_transactions WHERE carrier_id =".$db->mySQLSafe($_SESSION['carrier']['carrier_id'])." AND customerId = '".$cc_session->ccUserData['customer_id']."' ORDER BY id DESC";*/
	
	$history = "SELECT * FROM tbl_topup_payment_transactions WHERE  customerId = '".$cc_session->ccUserData['customer_id']."' ORDER BY id DESC";
	
	## AH ## MC-CR to fix tdbug# 31  ## Start ## 
	
	$countrows	= $db->numrows($history);
	$pagination = paginate($countrows, $config['productPages'], $page, "page");
	$historyRs = $db->select($history, $config['productPages'], $page);
	
	if(isset($errorMsg))
	{
		$topup_balance->assign("VAL_ERROR",$errorMsg);
		
		## Start-Logging-CR [MI]: Log error message into database log
		//msg_user($errorMsg);
		## End-Logging-CR [MI]: Log error message into database log
		
		/*if($optPayment == 1)
		{
			$topup_balance->assign("SELECTED_1","checked='checked'");
			$topup_balance->assign("SHOW_POSTED_TD", "Show('TdScratch','TdCBD','TdPaypal');");
		}	
		else if($optPayment == 2)		
		{
			$topup_balance->assign("SELECTED_2","checked='checked'");
			$topup_balance->assign("SHOW_POSTED_TD", "Show('TdCBD','TdScratch','TdPaypal');");
		}
		else */
		//if($optPayment == 3)		
//		{
//			$topup_balance->assign("SELECTED_3","checked='checked'");
//			$topup_balance->assign("SHOW_POSTED_TD", "Show('TdPaypal','TdCBD','TdScratch');");
//		}
		
		$topup_balance->parse("topup_balance.session_true.error");
	}
	if(isset($successMsg))
	{		
		$topup_balance->assign("PAYMENT_MESSAGE",$successMsg);
		$topup_balance->parse("topup_balance.session_true.payment_message");
		//$topup_balance->parse("topup_balance.session_true");
		
		## Start-Logging-CR [MI]: Log error message into database log
		msg_user($successMsg);
		## End-Logging-CR [MI]: Log error message into database log
	}
	
	## Case: Returning from Payment Gateway
	if(isset($_REQUEST['s']) && $_REQUEST['s'] != '')
	{
		if(check_int($_REQUEST['s']) != 1)
		{
			header("Location: index.php?_a=error");
		}
		else
		{	
			
			switch ($_REQUEST['s'])
			{
				case 0:
					$paymentMsg = "Unknown error occured while transaction, Please try again.";
					break;
				case 1:
					$paymentMsg = "<span class='greenMessage'>".$lang['topupBalance']['scratchcard_loaded']."</span>";
					if(isset($_SESSION['topup']['cart_order_id']) && $_SESSION['topup']['cart_order_id']!=""){								
								$SessionTopOrderId = $_SESSION['topup']['cart_order_id'];
								unset($_SESSION['topup']['cart_order_id']);
								header("Location: index.php?_g=co&_a=topup&cart_order_id=".$SessionTopOrderId);
					}
					break;
				case 2:
					$paymentMsg = "Top Up balance was not successful.";
					
					break;
				case 3:
					$paymentMsg = "Transaction was cancelled successfully.";
					break;				
			}
			
			$topup_balance->assign("PAYMENT_MESSAGE",$paymentMsg);
			$topup_balance->parse("topup_balance.session_true.payment_message");
		}	
	}
	#####################################################################
		
	if($cc_session->ccUserData['customer_id']>0)
	{ 
		$topup_balance->assign("LANG_TOPUP_YOUR_BALANCE_TITLE",$lang['topupBalance']['topup_your_balance']);
		$topup_balance->assign("LANG_ENTER_CODE",$lang['topupBalance']['enter_code']);
		$topup_balance->assign("LANG_ENTER_AMOUNT",$lang['topupBalance']['enter_amount']);
		$topup_balance->assign("LANG_CURRENT_BALANCE",$lang['topupBalance']['your_scratch_card_balance']);
		
		//$topup_balance->assign("LANG_SCRATCH_CARD_TITLE",$lang['topupBalance']['scratch_card_title']);
		//$topup_balance->assign("LANG_CREDIT_CARD_TITLE",$lang['topupBalance']['credit_card_payment_title']);
		$topup_balance->assign("LANG_PAYPAL_TITLE",$lang['topupBalance']['paypal_title']);
		
		$topup_balance->assign("LANG_RECHARGE_HISTORY_TITLE",$lang['topupBalance']['recharge_history']);	

		$topup_balance->assign("TXT_SCRATCH_CARD_NUMBER",$lang['topupBalance']['scratch_card_number']);
		$topup_balance->assign("TXT_YOUR_SCRATCH_CARD_BALANCE",$lang['topupBalance']['your_scratch_card_balance']);
		
		
		
		##Display the selected Currency Symbol
		global $currencyVars; 
		
		$selectedCurrency = $currencyVars[0]['symbolLeft'];
		$name = $currencyVars[0]['name'];
		$topup_balance->assign("NAME",$name);
		$topup_balance->assign("SELECTED_CURR_SYMBOL",$selectedCurrency);
		#####################################################
		//Show the User Cards Balance
	$balanceRs 	= $db->select("SELECT card_balance AS balance FROM ImeiUnlock_customer WHERE customer_id = '".$cc_session->ccUserData['customer_id']."';");
	
	$topup_balance->assign("VAL_BALANCE",  priceFormat($balanceRs[0]['balance']) );
	
				
		$topup_balance->parse("topup_balance.session_true.form.payment_options");		
		$topup_balance->parse("topup_balance.session_true.form");
		
		
		
		if(!empty($historyRs) )
		{
			$topup_balance->assign("TXT_TITLE_PACKAGE", $lang['topupBalance']['package_heading']);
			$topup_balance->assign("TXT_SCRATCH_CODE", $lang['topupBalance']['scratch_or_transaction']);
			$topup_balance->assign("TXT_GATEWAY", $lang['topupBalance']['gateway']);
			$topup_balance->assign("TXT_DATE", $lang['topupBalance']['created_date']);
			$topup_balance->assign("TXT_DATE_USED", $lang['topupBalance']['date_topped']);
			$topup_balance->assign("TXT_NOTES", $lang['topupBalance']['notes']);
			$topup_balance->assign("TXT_STATUS", $lang['topupBalance']['status']);
			$topup_balance->assign("TXT_PRICE", $lang['topupBalance']['price']);
			
			$styling_row_even 	= "style='background-color:#eeeeee; height:38px;'";
			$styling_row_odd 	= "";
			
			for ($i=0; $i< count($historyRs); $i++) 
			{
				
				if($i%2)
				$style = $styling_row_odd;
				else
				$style = $styling_row_even;
				
				$topup_balance->assign("ROW_STYLING", $style);
				
				$topup_balance->assign("VAL_TITLE_PACKAGE",$historyRs[$i]['title']);
				$topup_balance->assign("VAL_SCRATCH_CODE",$historyRs[$i]['transactionId']);
				$topup_balance->assign("VAL_DATE",$historyRs[$i]['date_topped']);
				$topup_balance->assign("VAL_GATEWAY",$historyRs[$i]['gateway']);
				$topup_balance->assign("VAL_PRICE", priceFormat($historyRs[$i]['amount'],true));
				$topup_balance->assign("VAL_STATUS",$lang['topupBalance']['orderState_'.$historyRs[$i]['status']]);
				$topup_balance->assign("VAL_NOTES",$historyRs[$i]['notes']);
				
				
				if(!empty($historyRs[$i]['date_topped']))
				$topup_balance->assign("VAL_DATE_USED",formatTime($historyRs[$i]['date_topped']));
				else
				$topup_balance->assign("VAL_DATE_USED","<b>Not Used</b>");
				
				$topup_balance->parse("topup_balance.recharge_history.repeat_cards");
			}
			
			if (!empty($pagination)) 
			{
				$topup_balance->assign("PAGINATION", $pagination);
				$topup_balance->parse("topup_balance.recharge_history.pagination_bot");
			}
			$topup_balance->parse("topup_balance.recharge_history");
		}
		else
		{
			$topup_balance->assign("NO_TOPUP_BALANCE_HISTORY", $lang['topupBalance']['no_topup_balance_history']);
			$topup_balance->parse("topup_balance.recharge_history_not_found");
			
		}		
		$topup_balance->parse("topup_balance.session_true");
		
	} 
	else 
	{ 
		$lang = getLang("includes".CC_DS."content".CC_DS."account.inc.php");
		$topup_balance->assign("LANG_LOGIN_REQUIRED",$lang['account']['login_to_view']);
		$topup_balance->parse("topup_balance.session_false");
	
	}
	$topup_balance->assign("LANG_YOUR_ACCOUNT", $lang['account']['your_account']);
	$topup_balance->parse("topup_balance");
	$page_content = $topup_balance->text("topup_balance");
?>