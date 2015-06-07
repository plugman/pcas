<?php

/*

+--------------------------------------------------------------------------

|	viewOrders.inc.php

|   ========================================

|	Displays the Customers Orders	

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



// include lang file



$lang = getLang("includes".CC_DS."content".CC_DS."viewOrders.inc.php");

$lang = getLang("orders.inc.php");
$view_orders=new XTemplate ("content".CC_DS."viewOrders.tpl");
$meta['siteTitle']			= "Your Orders - IMEI Unlock";

if ($cc_session->ccUserData['customer_id']>0) {

				
				
	
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = " WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);

$orders = $db->select("SELECT DISTINCT  ImeiUnlock_order_sum.status, ImeiUnlock_order_sum.cart_order_id, ImeiUnlock_order_sum.time FROM ".$glob['dbprefix']."ImeiUnlock_order_sum ".$inner_inv.$sqlQuery." ORDER BY `time` DESC") ;
}
if($orders){
	for($i=0; $i<count($orders);$i++)
		{
			if (in_array($orders[$i]['status'], array(1,4))) {
				$view_orders->assign("LANG_COMPLETE_PAYMENT",$lang['viewOrders']['complete_payment']);
				$view_orders->parse("view_orders.session_true.allorders.make_payment");	
			
			}
			$state = $orders[$i]['status'];
			$orders[$i]['state'] =  $lang['glob']['orderState_'.$state];
			$view_orders->assign("TD_CART_CLASS",cellColor($i, $tdEven="tdcartEven", $tdOdd="tdcartOdd"));
			$view_orders->assign("DATA",$orders[$i]);	
			$ortime = explode(",", formatTime($orders[$i]['time']));
			$view_orders->assign("VAL_DATE_TIME", $ortime[0]);
			$orders2 = $db->select("SELECT name, imei,stat,digital,quantity,product_options FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLsafe($orders[$i]['cart_order_id'])) ;
			
			for($j=0; $j<count($orders2);$j++){
			$view_orders->assign("VAL_PRO_NAME", $orders2[$j]['name']);
			$view_orders->assign("VAL_PRO_IMEI", $orders2[$j]['imei']);
			$view_orders->assign("VAL_PRO_QTY", $orders2[$j]['quantity']);
			$options = explode("\n", $orders2[$j]['product_options']);
					$searchword = 'Design Name';
				
					$matches = array();
				
						foreach($options as $k=>$v) {
				
						if(preg_match("/\b$searchword\b/i", $v)) {
				
						$dname[$k] = $v;
				
						 }
				
				}
				
					if($dname)
				
				 $dname = array_values($dname);
				
					 $dname = explode(" - ", $dname[0]);
			$view_orders->assign("VAL_DESIGN_NAME", $dname[1]);
			if($orders2[$j]['digital'] == 0)
			$view_orders->assign("VAL_PRO_STATUS", $lang['glob']['accessState_'.$orders2[$j]['stat']]);
			elseif($orders2[$j]['digital'] == 1)
			$view_orders->assign("VAL_PRO_STATUS", $lang['glob']['orderStat_'.$orders2[$j]['stat']]);
			elseif($orders2[$j]['digital'] == 2){
			$view_orders->assign("VAL_PRO_STATUS", $lang['glob']['repairState_'.$orders2[$j]['stat']]);
			}
			if($orders2[$j]['stat'] == 1)
			$view_orders->assign("PENDING_STYLE", 'style="background:#F47245"');
			else if($orders2[$j]['stat'] == 3)
			$view_orders->assign("PENDING_STYLE", 'style="background:#da171a"');
			else
			$view_orders->assign("PENDING_STYLE", '');
			if($j > 0)
			$view_orders->assign("BORDER_STYLE", 'style="border-top: 1px solid #e0e0e0;"');
			else
			$view_orders->assign("BORDER_STYLE", '');
			$view_orders->parse("view_orders.session_true.allorders.allnetworks");
			$view_orders->parse("view_orders.session_true.allorders.allimei");
			$view_orders->parse("view_orders.session_true.allorders.dname");
			$view_orders->parse("view_orders.session_true.allorders.allstatus");
			}
			$view_orders->parse("view_orders.session_true.allorders");
		}
	
}
else{
	$view_orders->assign("TXT_NO_ORDERS", "No Record Found in Your DataBase");
	$view_orders->parse("view_orders.session_true.noorders");
}
	

	

	$view_orders->assign("LANG_LOGIN_REQUIRED",$lang['viewOrders']['login_required']);

	

	if($cc_session->ccUserData['customer_id']>0) $view_orders->parse("view_orders.session_true");

	

	else $view_orders->parse("view_orders.session_false");

	

	$view_orders->parse("view_orders");

	

$page_content = $view_orders->text("view_orders");

?>