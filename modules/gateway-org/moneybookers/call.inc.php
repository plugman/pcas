<?php

if (isset($_POST['transaction_id']) && !empty($_POST['transaction_id']) && isset($_POST['status'])) {
	$module			= fetchDbConfig('moneybookers');
	if (isset($module['secret'])) {
		$hash	= md5($_POST['merchant_id'].$_POST['transaction_id'].strtoupper($module['secret']).$_POST['mb_amount'].$_POST['mb_currency'].$_POST['status']);
		$proceed	= ($hash === $_POST['md5sig']) ? true : false;
	} else {
		$proceed	= true;
	}
	
	if ($proceed) {
		$cart_order_id	= $_POST['transaction_id'];
		switch ((int)$_POST['status']) {
			case '0':	## Pending
				$order->orderStatus(1, $cart_order_id);
				break;
			case '2':	## Processed
				$order->orderStatus(2, $cart_order_id);
				break;
			case '-2':	## Failed
				$order->orderStatus(4, $cart_order_id);
				break;
			case '-1':	## Cancelled
				$order->orderStatus(6, $cart_order_id);
				break;
		}
	} else {
		## We have a problem!!!
	}
}

?>