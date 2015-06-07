<?php

	if(!defined('CC_INI_SET')){ die("Access Denied"); }
	
	// include lang file
	$lang1 	= getLang("includes".CC_DS."content".CC_DS."topupBalance.inc.php");
	$lang2	= getLang("includes".CC_DS."content".CC_DS."account.inc.php");
	$lang = array_merge($lang1, $lang2);
/*echo "<pre>";
print_r($_REQUEST);
die();*/
#########################################################################################
	if( (isset($_REQUEST['gateway']) && !empty($_REQUEST['gateway'])) && (isset($_REQUEST['card_amount']) && !empty($_REQUEST['card_amount'])))
	{
		if( (check_int($_REQUEST['gateway']) != 1) || (check_int($_REQUEST['card_amount']) != 1))
		header("Location: index.php?_a=error");
	}
	$gateway 		= $_REQUEST['gateway'];
	$card_amount 	= $_REQUEST['card_amount'];
	global $currencyVars; 
/*	echo "<pre>";
print_r($currencyVars);
die();*/
		if(isset($gateway) && $gateway > 0)
	{
		if($gateway == 1)
		{
		   		##Converting the coming amount to USD as PayPal receives USD Amounts
				## Below number formatiing is applied bcz paypal dont take more than 2 decimal number 
				## and the valu coming is 5decimals for currency rates correction
				//$card_amount = number_format(GetPrice_USD($card_amount,true), 2);
				
				
				$paypal = $config['paypal'];
				if(isset($paypal) && $paypal > 0){
						$paypalfee = $card_amount / 100 * $paypal ;
						//$paypalfee = number_format(GetPrice_USD($paypalfee,true), 2);
						}
						else {
							
							$paypalfee =0;
							}
				##Fetch Module Details
					$module = fetchDbConfig('PayPal' , 1);
					
					//Paypal Work
						if($module['testMode']==1) {
							$formAction = "https://www.sandbox.paypal.com/cgi-bin/webscr";
							$formMethod = "post";
							$formTarget = "_self";
						} else {
							$formAction = "https://www.paypal.com/cgi-bin/webscr";
							$formMethod = "post";
							$formTarget = "_self";
						}
						$toPayAmmount = number_format($card_amount + $paypalfee, 2);
		
						$hiddenVars = "<input type='hidden' name='cmd' value='_xclick' />
						<input type='hidden' name='business' value='".$module['email']."' />
						<input type='hidden' name='item_name' value='Topup Balance:".$card_amount."' />
						<input type='hidden' name='item_number' value='".$cc_session->ccUserData['customer_id']."' />
						
						<input type='hidden' name='quantity' value='1'>
						<input type='hidden' name='amount' value='".$toPayAmmount."' />
						<input type='hidden' name='shipping' value='0.00' />
						<input type='hidden' name='invoice' value='".mkInvoiceNo()."' />
						<input type='hidden' name='first_name' value='".$cc_session->ccUserData['firstName']."' />
						<input type='hidden' name='last_name' value='".$cc_session->ccUserData['lastName']."' />
						<input type='hidden' name='currency_code' value='".$currencyVars[0]['code']."' />
						<input type='hidden' name='add' value='1' />
						<input type='hidden' name='rm' value='2' />
						<input type='hidden' name='no_note' value='1' />";
						
						if($config['defaultCurrency']=="CAD") {
							$hiddenVars .= "<input type='hidden' name='bn' value='ImeiUnlock_Cart_ST_CA'>";
						} else {
							$hiddenVars .= "<input type='hidden' name='bn' value='ImeiUnlock_Cart_ST'>";
						}
						
						$hiddenVars .= "<input type='hidden' name='upload' value='1' />
						<input type='hidden' name='notify_url' value='".$GLOBALS['storeURL']."/index.php?_a=Paypal_Return' />
						<input type='hidden' name='return' value='".$GLOBALS['storeURL']."/index.php?_a=Paypal_Return' />
						<input type='hidden' name='cancel_return' value='".$GLOBALS['storeURL']."/index.php?_a=topupBalance&s=3' />";
						
						
		
		}//end if
		else if($gateway == 2)
		{
			
			##Fetch Module Details
			$module = fetchDbConfig('BankofDubai',1);
			
			if($module['testMode']==1) {
				$formAction = "https://migs.mastercard.com.au/vpcpay";
				$formMethod = "post";
				$formTarget = "_self";
			} else {
				$formAction = "https://migs.mastercard.com.au/vpcpay";
				$formMethod = "post";
				$formTarget = "_self";
			}
			############################################################
			//$SECURE_SECRET = "DD685112AA4F2F02EEE0BFACA1DF68E3"; // live setting
			//$SECURE_SECRET 	= "5F99414F87738BED7DEC1D5781460BB9"; // testing 
			$SECURE_SECRET = $module['secureSecret'];
			$ArrayData["vpc_AccessCode"] 	= $module['accessCode'];
			
/*			global $currencyVars; 
			if($currencyVars[0]['symbolLeft'] == 'AED' || $currencyVars[0]['symbolLeft'] == "AED")
			$ArrayData["vpc_Amount"] = $card_amount*100;
			else*/
			$ArrayData["vpc_Amount"] = BankPriceFormat($card_amount,true); 
						
			//$ArrayData["vpc_Amount"] 		= BankPriceFormat($card_amount,true); 
			
			$ArrayData["vpc_Command"] 	 	= "pay";
			$ArrayData["vpc_Locale"] 		= "en";
			$ArrayData["vpc_MerchTxnRef"]	= mkTransactionNo();
			$ArrayData["vpc_Merchant"] 		= $module['acNo']; 
			$ArrayData["vpc_OrderInfo"] 	= $cc_session->ccUserData['customer_id'];
			$ArrayData["vpc_ReturnURL"] 	= $GLOBALS['storeURL']."/index.php?_a=DubaiBank_Return";
			$ArrayData["vpc_Version"] 		= 1;
			
			ksort ($ArrayData);
			
			$md5HashData  = $SECURE_SECRET;
			
			foreach($ArrayData as $key => $value) 
			{
				if (strlen($value) > 0)
					$md5HashData .= $value;
			}
		
			if (strlen($SECURE_SECRET) > 0) 
				$hashedvalue .= strtoupper(md5($md5HashData));
			
			//--------------------------------------------------
			
			foreach($ArrayData as $key => $value) 
			{
				if (strlen($value) > 0) 
						$hiddenVars .= "<input type='hidden' name='".$key."' value='".$value."'/>";
			}
			
			$hiddenVars .="<input type='hidden' name='vpc_SecureHash' value='".$hashedvalue."'/>";	
			
		}//end else
	}
#########################################################################################

	$topup_balance = new XTemplate ("content".CC_DS."topupBalance_processing.tpl");


#########################################################################################
	if(isset($hiddenVars) && !empty($hiddenVars))
	{
	
		//Incase when hidden fields are prepared for the Payment Processing
		$topup_balance->assign("FORM_ACTION",$formAction);
		$topup_balance->assign("FORM_METHOD",$formMethod);
		$topup_balance->assign("FORM_TARGET",$formTarget);
		
		$topup_balance->assign("FORM_PARAMETERS",$hiddenVars);
		$topup_balance->assign("EXECUTE_SCRIPT","<script>document.getElementById('frmTopup').submit();</script>");
	}
#########################################################################################

	$topup_balance->parse("topup_balance_processing");
	$page_content = $topup_balance->text("topup_balance_processing");
?>