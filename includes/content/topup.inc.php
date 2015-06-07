<?php
/*
+--------------------------------------------------------------------------
|   Cub3Cart 4
|	topup.inc.php
|   ========================================
|	Choose and transfer to Balance
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

if($cc_session->ccUserData['customer_id']<1){
	httpredir("index.php");
}

## Session Required for PayPal Express Checkout

// include lang file
$lang = getLang("includes".CC_DS."content".CC_DS."topup.inc.php");


require_once("classes".CC_DS."cart".CC_DS."shoppingCart.php");
require_once("classes".CC_DS."cart".CC_DS."order.php");

$cart = new cart();
$order = new order();

if($_GET['contShop'] == true && isset($_GET['cart_order_id'])) {
	
	$pastBasket = $db->select(sprintf("SELECT `basket` FROM `%sImeiUnlock_order_sum` WHERE `cart_order_id` = %s",$glob['dbprefix'],$db->MySQLSafe($_GET['cart_order_id'])));
	
	if($pastBasket==true) {
		$record['basket'] = "'".$pastBasket[0]['basket']."'";
		$db->update($glob['dbprefix']."ImeiUnlock_sessions", $record, "`sessId`= '".$cc_session->ccUserData['sessId']."'");
	}
	
	$order->orderStatus(6, $_GET['cart_order_id'], false, true);
	httpredir("index.php");
}

$basket = $cart->cartContents($cc_session->ccUserData['basket']);
$topup = new XTemplate ("content" . CC_DS . "topup.tpl");

$topup->assign("LANG_MAKE_PAYMENT","Make ".$lang['topup']['payment']);
$topup->assign("LANG_CART",$lang['topup']['cart']);
$topup->assign("LANG_CHECKOUT",$lang['topup']['checkout']);
$topup->assign("LANG_PAYMENT",$lang['topup']['payment']);
$topup->assign("LANG_COMPLETE",$lang['topup']['complete']);
$topup->assign("LANG_PAYMENT_TOPUP",$lang['topup']['topup_makepayment']);
$topup->assign("LANG_AMOUNT_TO_PAY",$lang['topup']['amount_to_pay']);
$topup->assign("LANG_CURRENT_BALANCE",$lang['topup']['current_balance']);
$topup->assign("LANG_REMAINING_BALANCE",$lang['topup']['remaining_balance']);
$topup->assign("LANG_ORDER_ID",$lang['topup']['orderID']);
$topup->assign("LANG_REMAINING_ACCOUNT_BALANCE",$lang['topup']['remaing_account_balance']);



if(isset($_GET['cart_order_id'])){
	
	 $cart_order_id 	= $_GET['cart_order_id'];
	
	$order->getOrderInv($cart_order_id);
	$orderSum 		= $order->getOrderSum($cart_order_id);
	
	//$totalAmount = floor($orderSum['prod_total']+$orderSum['tax1_amt']+$orderSum['tax2_amt']+$orderSum['tax3_amt']);
	$totalAmount = $orderSum['prod_total']- $orderSum['paypalfee'];
	$Amount2pay  = $totalAmount ; 
	// $paypalfee1 	 = $orderSum['paypalfee'];
	// paypal processing fee
/*	$paypal = $config['paypal'];
	
	if(isset($paypal) && $paypal > 0){
		$paypalfee = $totalAmount / 100 * $paypal ;
		//$basket = $cart->setVar($paypalfee, "paypalfee");
		//$box_content->assign("LANG_PAYPAL", $lang2['cart']['paypal']);
		//$box_content->assign("VAL_PAYPAL_FEE",priceFormat($paypalfee));
		//$box_content->parse("cartpopup.paypalfee");		
		}
		else {
			
			$paypalfee =0;
			}
			
	$totalAmount = $totalAmount - $paypalfee ;*/
//	echo "<PRE>";
//	print_r ($totalAmount);
//	die();
	$customerId		= $cc_session->ccUserData['customer_id'];
	// get module config	
	$module = fetchDbConfig("TopUp");		
	
 	$totalAmount 	= priceFormat($totalAmount,true);
	//$totalAmount1 	= price_val($totalAmount,true);
	
	
	$topup->assign("LANG_PAYMENT_SUMMARY", sprintf($lang['topup']['payment_summary'], $totalAmount, $cart_order_id));
	$topup->assign("VAL_ORDER_ID", $cart_order_id);
	$topup->assign("AMOUNT", $totalAmount);
	$topup->assign("VAL_AMOUNT", $totalAmount2);
		
	$SelectBalance 	= $db->select("SELECT `customer_id`, `card_balance`, 'firstName' FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `customer_id`=".$db->mySQLSafe($customerId));
	
	if(!empty($SelectBalance))
	{
		$topup->assign("BALANCE_AMOUNT", priceFormat($SelectBalance[0]['card_balance'],true));
		$remainingamount = $SelectBalance[0]['card_balance'] - $Amount2pay;
	}
	else
	{	
		$topup->assign("BALANCE_AMOUNT", priceFormat("0", true));		

		$remainingamount = $orderSum['prod_total'];
	}
	
	$topup->assign("VAL_BALANCE_AMOUNT", priceFormat($remainingamount,true));
	if($SelectBalance[0]['card_balance'] >= $Amount2pay)
	{
		$topup->parse("topup.cart_true.paynow");		
		
	}
	else
	{
		$topup->assign("LANG_ERROR_ACCOUNT_BALANCE",$lang['topup']['low_balance']);
		$topup->assign("LANG_CLICK_HERE",$lang['topup']['click_here']);
		
		$topup->parse("topup.cart_true.paynow_false");		
	}
	
	
	$topup->parse("topup.cart_true");
}
else if (isset($_POST['cart_order_id'])&& $_POST['cart_order_id']!="" )
{
	$cart_order_id 	= $_POST['cart_order_id'];	
	$order->getOrderInv($cart_order_id);
	$orderSum 		= $order->getOrderSum($cart_order_id);
	
	$customerId		= $cc_session->ccUserData['customer_id'];
	$SelectBalance 	= $db->select("SELECT `customer_id`, `card_balance`, 'firstName' FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `customer_id`=".$db->mySQLSafe($customerId));
	
	if($orderSum['status']!=2 && $orderSum['status']!=3 && $SelectBalance[0]['card_balance']>=$orderSum['prod_total']- $orderSum['paypalfee'] )
	{
		$remainingamounttem = $orderSum['prod_total'] - $orderSum['paypalfee'];
		$remainingamount = $SelectBalance[0]['card_balance'] - $remainingamounttem ;
		
		$where	= "customer_id=".$db->mySQLSafe($customerId);
		$record['card_balance'] = $db->mySQLSafe($remainingamount);
		$updateBalance	 = $db->update($glob['dbprefix']."ImeiUnlock_customer",$record, $where);
		
//		$order->orderStatus(2,$cart_order_id);
		//echo "<PRE>";
//			print_r ($orderSum['prod_total']);
//			die();
		if($updateBalance)
		{
			$order->orderStatus(3,$cart_order_id);
			//$order->couponOrderStatus($cart_order_id);
			$topup->assign("CURRENT_BALANCE_AMOUNT", priceFormat($remainingamount,true));
			$topup->assign("SUCCESS_MSG", $lang['topup']['payment_done']);
			
			$transData['customer_id']	= $customerId;
			$transData['gateway'] 		= "Credits";
			$transData['trans_id'] 		= $cart_order_id;
			$transData['order_id'] 		= $cart_order_id;	
			$transData['amount'] 		= $orderSum['prod_total']- $orderSum['paypalfee'] ;
			$transData['notes'] 		= "Payment successful.";
			$transData['status'] 		= "successful";
			$order->storeTrans($transData);	
				
				
				
			// insert transaction record
			
			$transData['customer_id']	= $customerId;
			$transData['trans_id'] 		= $cart_order_id;	
			$transData['cr'] 		= $orderSum['prod_total']- $orderSum['paypalfee'] ;
			$transData['notes'] 		= "Credits Deduct on New Order.";
			$transData['balance'] 		= $remainingamount;
			storeCreditTrans($transData);	
			
			//update ordersum table paypal amount & order total
			 
			$array['prod_total'] = $transData['amount'] ;
			$array['paypalfee'] = 0;
			$db->update($glob['dbprefix']."ImeiUnlock_order_sum", $array, "cart_order_id = ".$db->mySQLSafe($cart_order_id));
				
			httpredir("index.php?_g=co&_a=confirmed&s=2&cart_order_id=$cart_order_id");
		}
		else
		{
			$topup->assign("CURRENT_BALANCE_AMOUNT", priceFormat($SelectBalance[0]['card_balance'],true));
			$topup->assign("SUCCESS_MSG", $lang['topup']['payment_fail']);
			httpredir("index.php?_g=co&_a=confirmed&s=1&cart_order_id=$cart_order_id");
		}
	}
	else
	{
		$topup->assign("CURRENT_BALANCE_AMOUNT", priceFormat($SelectBalance[0]['card_balance'],true));
		$topup->assign("SUCCESS_MSG", $lang['topup']['payment_fail']);
	//	httpredir("index.php?_g=co&_a=confirmed&s=1&cart_order_id=$cart_order_id");
	}
	$topup->parse("topup.payment_done");

	
}
else {

	$topup->assign("LANG_CART_EMPTY",$lang['topup']['cart_empty']);
	$topup->parse("topup.cart_false");

} 

$topup->parse("topup");
$page_content = $topup->text("topup");
?>
