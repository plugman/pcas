<?php
/*
+--------------------------------------------------------------------------
|	account.inc.php
|   ========================================
|	Customers Account Homepage	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");
$lang = getLang("includes".CC_DS."content".CC_DS."account.inc.php");
$lang = getLang("orders.inc.php");
$account = new XTemplate ("content".CC_DS."account.tpl");

$account->assign("LANG_YOUR_ACCOUNT", $lang['account']['your_account']);
$account->assign("TXT_PERSONAL_INFO", $lang['account']['personal_info']);
$account->assign("TXT_ORDER_HISTORY", $lang['account']['order_history']);
$account->assign("TXT_CHANGE_PASSWORD", $lang['account']['change_password']);
$account->assign("TXT_NEWSLETTER", $lang['account']['newsletter']);
$account->assign("LANG_LOGIN_REQUIRED", $lang['account']['login_to_view']);
$meta['siteTitle']			= "Your Account - IMEI Unlock";
if ($cc_session->ccUserData['customer_id']>0) {
	$account->assign("VAL_BAL", priceformat($cc_session->ccUserData['card_balance']));
$account->assign("VAL_USER", $cc_session->ccUserData['firstName']);
$account->assign("VAL_EMAIL", $cc_session->ccUserData['email']);
if($cc_session->ccUserData['lastTime2'] > 0)
$lasttime = explode(",", formatTime($cc_session->ccUserData['lastTime2']));
else
$lasttime = explode(",", formatTime($cc_session->ccUserData['timeLast']));
$account->assign("VAL_LAST_DATE", $lasttime[0]);
$order = $db->select("SELECT ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id, customer_id,".$glob['dbprefix']."ImeiUnlock_order_inv.productId,".$glob['dbprefix']."ImeiUnlock_order_inv.name,price,stat,digital FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv ON ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_inv.cart_order_id WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']));
## calculate orders % for progressbar
//echo "<pre>";
//print_r($order);
$unlocked = 0;
$reject = 0;
$pending = 0;
$totrecep =0;
$totlocked = 0;
$totalorders = count($order);
if (is_array($order)){
foreach($order as $value)
{
  if($value['stat'] === '2' && $value['digital'] === '1'){
    $unlocked++;
	$totrecep += $value['price'];
  }
	else if($value['stat'] === '1'  && $value['digital'] === '1'){
    $pending++;
	$totlocked +=  $value['price'];
	}
	else if($value['stat'] === '3'  && $value['digital'] === '1'){
    $reject++;
	}
}
}
$unlockedper =  $unlocked/$totalorders * 100;
$pendingper =  $pending/$totalorders * 100;
$rejectedper =  $reject/$totalorders * 100;
$account->assign("UNLOCKED_PER", $unlockedper);
$account->assign("PENDING_PER", $pendingper);
$account->assign("REJECTED_PER", $rejectedper);
$account->assign("UNLOCKED_VAL", $unlocked);
$account->assign("PENDING_VAL", $pending);
$account->assign("REJECTED_VAL", $reject);
$account->assign("VAL_REC", priceformat($totrecep));
$account->assign("VAL_LOCKED", priceformat($totlocked));
## load filter information
$allproducts = $db->select("SELECT name, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE digital = ".$db->mySQLsafe(1)) ;
			for($z=0; $z<count($allproducts);$z++){
				if($_POST['network'] == $allproducts[$z]['productId'])
				$account->assign("SELECTED_PRO", 'selected="selected"');
				else 
				$account->assign("SELECTED_PRO", '');
				$account->assign("PRO_NAME", $allproducts[$z]['name']);
				$account->assign("PRO_ID", $allproducts[$z]['productId']);
				$account->parse("account.session_true.repeatproducrs");
			}
			if($_POST['status'] == '1')
				$account->assign("SELECTED_STATUS1", 'selected="selected"');
				else if($_POST['status'] == '2')
				$account->assign("SELECTED_STATUS2", 'selected="selected"');
				else if($_POST['status'] == '3')
				$account->assign("SELECTED_STATUS3", 'selected="selected"');
				else 
				$account->assign("SELECTED_STATUS3", '');
					if($_POST['date'] == 1)
					$account->assign("SELECTED_DATE1", 'selected="selected"');
					else if($_POST['date'] == 7)
					$account->assign("SELECTED_DATE2", 'selected="selected"');
					else if($_POST['date'] == 30)
					$account->assign("SELECTED_DATE3", 'selected="selected"');
					else if($_POST['date'] == 90)
					$account->assign("SELECTED_DATE4", 'selected="selected"');
					$account->parse("account.session_true.repeatdate");
				
				
	## database queries
if(isset($_POST['imei']) && $_POST['imei']>0){
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = "WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id'])." AND OI.imei like '".$_POST['imei']."%' ";
	$account->assign("IMEI_TXT", $_POST['imei']);
}
else if(isset($_POST['status']) && $_POST['status']> 0){
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = "WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id'])." AND OI.stat =".$_POST['status'];
}else if(isset($_POST['network']) && $_POST['network']> 0){
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = "WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id'])." AND OI.productId =".$_POST['network'];
}else if(isset($_POST['date']) && $_POST['date']> 0){
	$timeperiod = strtotime('-'.$_POST['date'].' day');
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = "WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id'])." AND ImeiUnlock_order_sum.time > ".$timeperiod;
}
else{
	$inner_inv = " INNER JOIN ".$glob['dbprefix']."ImeiUnlock_order_inv as OI ON OI.cart_order_id = ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id ";
	$sqlQuery = " WHERE customer_id = ".$db->mySQLsafe($cc_session->ccUserData['customer_id']);
}
$orders = $db->select("SELECT DISTINCT  ImeiUnlock_order_sum.status, ImeiUnlock_order_sum.cart_order_id, ImeiUnlock_order_sum.time FROM ".$glob['dbprefix']."ImeiUnlock_order_sum ".$inner_inv.$sqlQuery." ORDER BY `time` DESC") ;

if($orders){
	for($i=0; $i<count($orders);$i++)
		{
			$state = $orders[$i]['status'];
			$orders[$i]['state'] =  $lang['glob']['orderState_'.$state];
			$account->assign("TD_CART_CLASS",cellColor($i, $tdEven="tdcartEven", $tdOdd="tdcartOdd"));
			$account->assign("DATA",$orders[$i]);	
			$ortime = explode(",", formatTime($orders[$i]['time']));
			$account->assign("VAL_DATE_TIME", $ortime[0]);
			$orders2 = $db->select("SELECT name, imei,stat,digital FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLsafe($orders[$i]['cart_order_id'])) ;
			for($j=0; $j<count($orders2);$j++){
			$account->assign("VAL_PRO_NAME", $orders2[$j]['name']);
			$account->assign("VAL_PRO_IMEI", $orders2[$j]['imei']);
			if($orders2[$j]['digital'] == 0){
			$account->assign("VAL_PRO_STATUS", $lang['glob']['accessState_'.$orders2[$j]['stat']]);
			}
			elseif($orders2[$j]['digital'] == 1){
			$account->assign("VAL_PRO_STATUS", $lang['glob']['orderStat_'.$orders2[$j]['stat']]);
			}
			elseif($orders2[$j]['digital'] == 2){
			$account->assign("VAL_PRO_STATUS", $lang['glob']['repairState_'.$orders2[$j]['stat']]);
			}
			if($orders2[$j]['stat'] == 1)
			$account->assign("PENDING_STYLE", 'style="background:#ff9a3a"');
			else if($orders2[$j]['stat'] == 3)
			$account->assign("PENDING_STYLE", 'style="background:#da171a"');
			else
			$account->assign("PENDING_STYLE", '');
			if($j > 0)
			$account->assign("BORDER_STYLE", 'style="border-top: 1px solid #e0e0e0;"');
			else
			$account->assign("BORDER_STYLE", '');
			$account->parse("account.session_true.allorders.allnetworks");
			$account->parse("account.session_true.allorders.allimei");
			$account->parse("account.session_true.allorders.allstatus");
			}
			$account->parse("account.session_true.allorders");
		}
	
}
else{
	$account->assign("TXT_NO_ORDERS", "No Record Found in Your Account");
	$account->parse("account.session_true.noorders");
}
	if($config['sef']){
		
	$account->assign("BALANCE",'Balance.html');
	$account->assign("PROFILE",'Profile.html');
	$account->assign("ORDERS",'Orders.html');
	$account->assign("NEWS",'NewsLetter.html');
	$account->assign("PASSWORD",'ChangePassword.html');
}
else{
	$account->assign("BALANCE",'index.php?_a=topupBalance');
	$account->assign("PROFILE",'index.php?_a=profile');
	$account->assign("NEWS",'index.php?_a=newsletter');
	$account->assign("PASSWORD",'index.php?_a=changePass');
	$account->assign("ORDERS",'index.php?_g=co&_a=viewOrders');
}
	$account->parse("account.session_true");
} else {
	$account->parse("account.session_false");
}

$account->parse("account");	
$page_content = $account->text("account");
?>