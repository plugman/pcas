<?php


if (isset($_GET['cart_order_id']) && !empty($_GET['cart_order_id'])) {
	if (isset($_GET['cancelled'])) {
		$paymentResult = 1;
	} else {
		$status = $db->select("SELECT `status` FROM `".$glob['dbprefix']."ImeiUnlock_order_sum` WHERE `cart_order_id` = ".$db->MySQLSafe($_GET['cart_order_id']));
		if ($status) {
			switch ((int)$status[0]['status']) {
				case 2:
				case 3:
					$paymentResult = 2;
					break;
				default:
					$paymentResult = 3;
			}
		}
	}
} else {
	$paymentResult = 3;
}

?>