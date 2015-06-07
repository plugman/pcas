<?php

/*

+--------------------------------------------------------------------------

|	orderBuilder.inc.php

|   ========================================

|	Ability to add/edit orders

+--------------------------------------------------------------------------

*/



if (!defined('CC_INI_SET')) die("Access Denied");



$lang = getLang("admin".CC_DS."admin_orders.inc.php");

$lang = getLang("orders.inc.php");



require_once "classes".CC_DS."cart".CC_DS."order.php";

$order = new order();

require $glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php";



permission("orders", "write", true);



if (isset($_GET['reset']) && $_GET['reset']>0) {

	$record['noDownloads']	= 0;

	$record['expire']		= time()+$config['dnLoadExpire'];

	

	$where	= 'id = '.$_GET['reset'];

	$update	= $db->update($glob['dbprefix']."ImeiUnlock_Downloads", $record, $where);

	

	httpredir($glob['adminFile'].'?_g=repair/orderBuilder&edit='.$_GET['edit']);

}

if (isset($_POST['cart_order_id']) && !isset($_POST['prodRowsSubmit'])) {

	

	if(empty($_POST['cart_order_id'])){
		$_POST['cart_order_id'] = $order->mkOrderNo();
	}

	

	// ORDER INVENTORY

	$newOrderInv['name'] 			= $db->mySQLSafe($_POST['problem_d']);

	$newOrderInv['cart_order_id'] 	= $db->mySQLSafe($_POST['cart_order_id']);

	$newOrderInv['make'] 			= $db->mySQLSafe($_POST['make_d']);

	$newOrderInv['device'] 			= $db->mySQLSafe($_POST['device_d']);

	$newOrderInv['model'] 			= $db->mySQLSafe($_POST['model_d']);

	$newOrderInv['price'] 			= $db->mySQLSafe($_POST['price']);

	$newOrderInv['imei'] 			= $db->mySQLSafe($_POST['imei']);

	$newOrderInv['digital'] 		= $db->mySQLSafe('2');

	$newOrderInv['extra_notes'] 	= $db->mySQLSafe($_POST['notes']);

	$newOrderInv['battery'] 	= $db->mySQLSafe($_POST['battery']);

	$newOrderInv['sim'] 	= $db->mySQLSafe($_POST['sim']);

	$newOrderInv['memcard'] 	= $db->mySQLSafe($_POST['memcard']);

	$newOrderInv['fixdate'] 	= $db->mySQLSafe($_POST['fixdate']);

	//$newOrderInv['stat'] 	= $db->mySQLSafe($_POST['status']);

	

	$newOrderInv['product_options'] =  $db->mySQLSafe($_POST['product_options']);

	// ORDER SUMMARY

	$newOrderSum['cart_order_id'] 	= $db->mySQLSafe($_POST['cart_order_id']);

	//$newOrderSum['status'] 	= $db->mySQLSafe($_POST['status']);

	$newOrderSum['customer_id'] 	= $db->mySQLSafe($_POST['customer_id']);

	$newOrderSum['name'] 			= $db->mySQLSafe($_POST['name'].' '.$_POST['lastName']);

	$newOrderSum['add_1'] 			= $db->mySQLSafe($_POST['add_1']);

	$newOrderSum['town'] 			= $db->mySQLSafe($_POST['town']);

	$newOrderSum['suburb'] 			= $db->mySQLSafe($_POST['suburb']);

	$newOrderSum['county'] 			= $db->mySQLSafe($_POST['county']);

	$newOrderSum['postcode'] 		= $db->mySQLSafe($_POST['postcode']);

	$newOrderSum['phone'] 			= $db->mySQLSafe($_POST['phonee']);

	$newOrderSum['comments'] 		= $db->mySQLSafe($_POST['comments']);

	$newOrderSum['customer_comments'] = $db->mySQLSafe($_POST['customer_comments']);

	$newOrderSum['extra_notes'] 	= $db->mySQLSafe($_POST['extra_notes']);

	$newOrderSum['refered_by'] 	= $db->mySQLSafe($_POST['refered_by']);
	$newOrderSum['repairedby'] 	= $db->mySQLSafe($_POST['repairedby']);

	$newOrderSum['salesrep'] 	= $db->mySQLSafe($_POST['salesrep']);

	$newOrderSum['email'] 			= $db->mySQLSafe($_POST['email']);

	$newOrderSum['subtotal'] 		= $db->mySQLSafe($_POST['subtotal']);

	$newOrderSum['discount'] 		= $db->mySQLSafe($_POST['discount']);

	$newOrderSum['prod_total'] 		= $db->mySQLSafe($_POST['prod_total']);

	$newOrderSum['total_tax'] 		= $db->mySQLSafe($_POST['total_tax']);

	$newOrderSum['gateway'] 		= $db->mySQLSafe($_POST['gateway']);

	if (isset($_GET['edit'])) {

		

		$where = "cart_order_id = ".$db->mySQLSafe($_GET['edit']);

		$where2 = "id = ".$db->mySQLSafe($_GET['repair']);

		$update = $db->update($glob['dbprefix']."ImeiUnlock_order_sum", $newOrderSum, $where);

		$update2 = $db->update($glob['dbprefix']."ImeiUnlock_order_inv", $newOrderInv, $where2);

	

			

		if ($update == true || $update2 == true) {

			$msg .= "<p class='infoText'>Order # ".sprintf($lang['admin']['orders_updated_successfully'],$_GET['edit'])."</p>"; 

		}/* else {

			$msg .= "<p class='warnText'>".sprintf($lang['admin']['orders_update_failed'], $_GET['edit'])."</p>"; 

		} */

	

	} else {

		$newOrderSum['ip'] = $db->mySQLSafe(get_ip_address());

		$newOrderSum['time'] = $db->mySQLSafe(time());

		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_order_sum", $newOrderSum);

		$insert2 = $db->insert($glob['dbprefix']."ImeiUnlock_order_inv", $newOrderInv);

		$repair = $db->insertid();

		if ($_POST['customer_id']>0) {

			$record['noOrders'] = "noOrders + 1";

			$where = "customer_id = ".$_POST['customer_id'];

			$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);

		}

		

		if ($insert == true || $insert == true) {

			$msg .= "<p class='infoText'>Order # ".sprintf($lang['admin']['orders_add_success'],$_POST['cart_order_id'])."</p>";

		} else {

			$msg .= "<p class='warnText'>Order # ".sprintf($lang['admin']['orders_add_fail'],$_POST['cart_order_id'])."</p>"; 

		}

	}

	

	// update order status email etc

	require_once CC_ROOT_DIR.CC_DS."classes".CC_DS."cart".CC_DS."repair.php";

		$repairorder = new repairorder();

		$repairorder->orderStatus($_POST['status'], $_POST['cart_order_id'], $_GET['repair'] > 0 ? $_GET['repair'] : $repair, true);

	

	if ($_POST['cart_order_id']!==$_GET['edit']) {

		

		httpredir($glob['adminFile']."?_g=repair/orderBuilder&edit=".$_POST['cart_order_id']."&repair=".$repair."&add=success");

	}

}



if (isset($_GET['edit'])) {	

	$orderSum = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE cart_order_id = ".$db->mySQLSafe($_GET['edit']));

	$orderInv = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE id = ".$db->mySQLSafe($_GET['repair']));

}



if (count($orderInv) < 1 && !empty($_GET['edit'])) {

	$msg .= "<p class='warnText'>".sprintf($lang['admin']['orders_no_products'], $_GET['edit'])."</p>"; 

}

$sql = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE `type` > 0";

$noCustomers = $db->numrows($sql);

## Work around to change the drop dowm menu to a text box if there are over 500 customers. Current 

## solution drastically slows or even halts the page. Ajax lookup required. 

## See bug 1212

if($noCustomers<500) {

	$customers = $db->select($sql." ORDER BY lastName, firstName ASC");

}	

$countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries");



if ($countries == true) {

	$countriesArray = array();

	for($i=0;$i<=count($countries);$i++){

		$countriesArray[$countries[$i]['id']] = $countries[$i]['printable_name'];

	}

}



if(isset($_GET['PayPal-Pro']) && !empty($_GET['PayPal-Pro'])) {

	

	// Get Module Config

	$module = fetchDbConfig("PayPal_Pro");

	

	$basePPPath = "modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."wpp-".str_replace(array('ECO','DPO'),'',$module['mode']).CC_DS;

	

	$order_id = $_GET['edit'];

	

	$ppfunction = preg_replace('#[^a-z]#i', '', $_GET['PayPal-Pro']);

	

	switch($ppfunction) {

		

		case "doCapture":

			require_once($basePPPath."DoCaptureReceipt.php");

		break;

		

		case "doAuth":

		case "doReAuth":

			require_once($basePPPath."DoReauthorizationReceipt.php");

		break;

		

		case "doRefund":

			require_once($basePPPath."RefundReceipt.php");

		break;

		

		case "doVoidAuth":

			require_once($basePPPath."DoVoidReceipt.php");

		break;

		

		case "doFMF":

			require_once($basePPPath."ManagePendingTransactionStatus.php");

		break;

	

	

	}

}



require_once($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");



?>



<p class="pageTitle">

  <?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?>

  <?php echo $lang['admin']['orders_order'];?></p>

<?php if (isset($msg)) echo $msg; ?>
<?php if ($_GET['add'] == "success")
echo "<p class='infoText'>Order # ".sprintf($lang['admin']['orders_add_success'],$_GET['edit'])."</p>";
?>
<p class="pinvoice" style="height:30px;"> <!--<span class="pinvoicebg">&nbsp;</span>

  <input type="button" class="submit " onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=repair/print&amp;cart_order_id=<?php echo $_GET['edit']; ?>', 'PrintSlip', 600, 550, '1,toolbar=1')" value="<?php echo $lang['admin']['orders_print_packing_slip'];?>" style="padding-left:30px;" />-->

  <span class="dater"><strong>Order Date: </strong> &nbsp;<span class="dateo">

  <?php if(isset($_GET['edit'])) { echo date("d/M/Y", $orderSum[0]['time']); } else { echo date("d/M/Y", time()); } ?>

  </span></span> </p>

<?php 

## Discontinued from 4.1.0 final onwards

//if(getCountryFormat($config['siteCountry'],'id','iso')=="GB") { 

?>

<?php

//}

?>

<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/repair_sec.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $GLOBALS['rootRel']; ?>/uploads/calendar/jsDatePick_ltr.min.css" />

<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>/uploads/calendar/jsDatePick.full.1.3.js"></script> 

<script type="text/javascript">

	window.onload = function(){		

		new JsDatePick({

			useMode:2,

			target:"fixdate",

			yearsRange:[1899,2050],

			dateFormat:"%Y-%m-%d"

			

			/*selectedDate:{				This is an example of what the full configuration offers.

				day:5,						For full documentation about these settings please see the full version of the code.

				month:9,

				year:2006

			},

			yearsRange:[1978,2020],

			limitToToday:false,

			cellColorScheme:"beige",

			dateFormat:"%m-%d-%Y",

			imgPath:"img/",

			weekStartDay:1*/

		});

		

	};

	function toogleinfo(id, button){

		if($j("#"+button).val() == '+'){

			$j("#"+id).toggle();

			$j("#"+button).val("-");

		}else{

			$j("#"+id).toggle();

			$j("#"+button).val("+");

		}

	}

	

</script>
<script type="text/javascript">
	$j(document).ready(function()
{
    $j("#txtimeii").attr('maxlength','15');
});
function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
		 }
else{
         return true;
      }
	  }
     </script>
<form action="<?php echo $glob['adminFile']; ?>?_g=repair/orderBuilder<?php if(isset($_GET['edit'])) { echo "&amp;edit=".$_GET['edit']."&amp;repair=".$_GET['repair']; } ?>" method="post" enctype="multipart/form-data" name="orderBuilder" target="_self">

  <table cellspacing="1" cellpadding="3" class="mainTable" width="100%">

    <tr>

      <td colspan="4" class="tdTitle tdTitle2"><?php echo $lang['admin']['orders_order_summary'];?></td>

    </tr>

    <tr>

      <td class="tdText" align="right" width="17%"><span class="titlebox"><?php echo $lang['admin_common']['other_order_no']; ?></span></td>

      <td class="tdText"><input name="cart_order_id" type="text" class="textbox2" value="<?php if(isset($orderSum[0]['cart_order_id'])) { echo $orderSum[0]['cart_order_id']; } ?>" size="22" readonly="readonly" /></td>

      <td align="right" width="28%"><strong><?php echo "Choose Customer:"; ?></strong></td>

      <td ><?php

      if ($customers == true) {

      ?>

        <select name="customer_id" id="customer_select" onchange="populate();" class="textbox5">

          <?php if($orderSum == false) { ?>

          <option value="0" <?php if(!$_POST['customer_id'] && $orderSum == false) { echo "selected='selected'"; } ?>>-- <?php echo $lang['admin_common']['na'];?> --</option>

          <?php

		}

		

			//for ($i=0; $i<count($customers); $i++) {

			foreach ($customers as $customer) {

			#	$customer = array_map('html_entity_decode', $customer);

			#	$customer = array_map('addslashes', $customer);

		?>

          <option value="<?php echo $customer['customer_id'];?>" 

		<?php if($customer['customer_id']==$_POST['customer_id'] || $customer['customer_id']==$orderSum[0]['customer_id']){ echo "selected='selected'"; } ?>

		json="<?php json_encode($customer); ?>"

		onmouseover="findObj('name').value='<?php echo addslashes($customer['title'].' '.html_entity_decode($customer['firstName'], ENT_QUOTES));?>';findObj('lastName').value='<?php echo addslashes(html_entity_decode($customer['lastName'], ENT_QUOTES));?>';findObj('town').value='<?php echo addslashes(html_entity_decode($customer['town'], ENT_QUOTES));?>';findObj('postcode').value='<?php echo addslashes(html_entity_decode($customer['postcode'], ENT_QUOTES));?>';findObj('phonee').value='<?php echo $customer['phone'];?>';findObj('email').value='<?php echo $customer['email'];?>';findObj('county').value='<?php echo $customer['county'];?>';"

		> <?php echo $customer['lastName'];?>, <?php echo $customer['firstName'];?> (<?php echo $customer['customer_id'];?>)</option>

          <?php

			}

		?>

        </select>

        <?php } else { ?>

        <input type="textbox" name="customer_id" class="textbox" value="<?php echo isset($_POST['customer_id']) ? $_POST['customer_id'] : $orderSum[0]['customer_id']; ?>" />

        <?php } ?></td>

    </tr>

    <tr>

      <td class="tdText" align="right"><span class="titlebox"><?php echo "Estimated Fix Date"; ?></span></td>

      <td class="tdText"><input type="text" class="textbox2" name="fixdate" id="fixdate" value="<?php echo $orderInv[0]['fixdate']; ?>" style="float:none;" /></td>

      <td>&nbsp;</td>

      <td>&nbsp;</td>

    </tr>

      </tr>

    

    <tr>

      <td valign="top" class="tdTitle tdTitle2" colspan="4"><strong><?php echo 'Phone Details'; ?></strong><input type="button" class="togbutton" id="pinfob" onclick="toogleinfo('pinfo','pinfob');" value="-"></td>

    </tr>

    <tr>

      <td colspan="4"></td>

    </tr>

    <tr>

      <td colspan="4">

       <div id="pinfo">

      <table cellspacing="1" cellpadding="3" class="repairt" width="100%">

          <tr>

            <td class="tdText" align="right" width="20%;"><strong><?php echo 'Select Make:';?></strong></td>

            <td><?php

	if(empty($orderInv[0]['make'])){

	$tree =	$db->select("SELECT C.cat_name, C.cat_id,C.cat_father_id FROM ".$glob['dbprefix']."ImeiUnlock_category AS C INNER JOIN ".$glob['dbprefix']."ImeiUnlock_inventory AS I ON I.cat_id = C.cat_id WHERE I.productId =".$db->mySQLSafe($orderInv[0]['productId']));

	$tree = getmaketree($tree[0]['cat_name'], $tree[0]['cat_father_id'], $tree[0]['cat_id']);

	$orderInv[0]['make'] = $tree[0];

	$orderInv[0]['device'] = $tree[1];

	$orderInv[0]['model'] = $tree[2];

	$options = explode("\n", $orderInv['0']['product_options']);

	$searchword = 'imei';

	$matches = array();

		foreach($options as $k=>$v) {

    	if(preg_match("/\b$searchword\b/i", $v)) {

        $imei[$k] = $v;

   		 }

}

	if($imei)

	$imei = array_values($imei);

	 $imei = explode(" - ", $imei[0]);

	$orderInv[0]['imei'] = $imei[1];

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

	$orderInv[0]['extra_notes'] = $coments[1];

	

	}

	

	$query 			= "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = 0 AND hide = '0' AND type = '2' ORDER BY priority,cat_name ASC"; ## ORDER BY NOT NEEDED DUE TO NATCASESORT WHICH IS MORE TRUST WORTHY TO GET ALL DATA

	$categoryArray	= $db->select($query);

	?>

              <select name="make" class="textbox5" onchange="loaddevicess('<?php echo $GLOBALS['storeURL']; ?>', this.value, '1');" id="make">

                <option value="0">---Select Phone---</option>

                <?php

	## ADD CATEGORY CACHING HERE

	if($categoryArray){

		for ($i=0; $i<count($categoryArray); $i++){

			echo '<option value="'.$categoryArray[$i]['cat_id'].'" >'.$categoryArray[$i]['cat_name'].'</option>';

		}

	}

	?>

                <option value="0">---Other---</option>

              </select></td>

            <td align="center"><span class="titlebox2">OR</span></td>

            <td class="tdText"><input type="text" class="textbox" name="make_d" id="make_d" value="<?php echo $orderInv[0]['make']; ?>" /></td>

          </tr>

          <tr id="deviceid">

            <td class="tdText" align="right"><strong><?php echo 'Select Device:';?></strong></td>

            <td id="device"></td>

            <td align="center"><span class="titlebox2">OR</span></td>

            <td class="tdText"><input type="text" class="textbox" name="device_d" id="device_d" value="<?php echo $orderInv[0]['device']; ?>" /></td>

          </tr>

          <tr id="modelid">

            <td class="tdText" align="right"><strong><?php echo 'Select Model:';?></strong></td>

            <td id="model"></td>

            <td align="center"><span class="titlebox2">OR</span></td>

            <td class="tdText"><input type="text" class="textbox" name="model_d" id="model_d" value="<?php echo $orderInv[0]['model']; ?>" /></td>

          </tr>

          <tr id="problemid">

            <td class="tdText" align="right"><strong><?php echo 'Select Problem:';?></strong></td>

            <td id="problem"></td>

            <td align="center"><span class="titlebox2">OR</span></td>

            <td class="tdText"><input type="text" class="textbox" name="problem_d" id="problem_d" value="<?php echo $orderInv[0]['name']; ?>" /></td>

          </tr>

          <tr>

            <td align="right"><strong>Handset IMEI:</strong></td>

            <td class="tdText"><input type="text" class="textbox" name="imei" id="txtimeii" value="<?php echo $orderInv[0]['imei']; ?>"  onkeypress="return isNumberKey(event)" /></td>

            <td colspan="2"></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo 'Price:';?></strong></td>

            <td><input type="text" class="textbox" name="price" id="prob_price" value="<?php echo $orderInv[0]['price']; ?>" /></td>

            <td colspan="2"></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo 'Supplied:';?></strong></td>

            <td valign="middle" style="padding-top:7px; height:33px;"><input type="checkbox" value="1" name="battery" <?php if($orderInv[0]['battery'] == 1 ) echo 'checked="checked"'; ?>   style="vertical-align:sub">

              Battery

              <input type="checkbox" value="1" name="sim" class="radio-check" <?php if($orderInv[0]['sim'] == 1 ) echo 'checked="checked"'; ?>  style="margin-left:10px; vertical-align:sub">

              Sim Card

              <input type="checkbox" value="1" name="memcard"  class="radio-check" <?php if($orderInv[0]['memcard'] == 1 ) echo 'checked="checked"'; ?> style="margin-left:10px; vertical-align:sub" >

              Memory Card </td>

            <td colspan="2"></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo 'Sales Representative:'; ?></strong></td>

            <td class="tdText"><select title="sales representative" name="salesrep" id="salesrep" class="textbox5">

                <option value="">-- Select --</option>

                <?php

	  $salesrep = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_sales_rep WHERE hide = '1'");

	  for($j=0; $j<count($salesrep); $j++){

	?>

                <option value="<?php echo $salesrep[$j]['id']; ?>" <?php if($salesrep[$j]['id'] == $orderSum[0]['salesrep']) echo "selected='selected'"; ?>><?php echo $salesrep[$j]['title']; ?></option>

                <?php } ?>

              </select></td>

            <td colspan="2"></td>

          </tr>

          <tr>

            <td valign="top" align="right"><strong><?php echo "Staff Comments:"; ?></strong></td>

            <td class="tdText" colspan="3"><textarea name="notes" cols="30" rows="10" class="textbox" style="height:80px; width:97%"><?php echo $orderInv[0]['extra_notes']; ?></textarea></td>

          </tr>

          <?php

	  if(!empty($orderInv[0]['product_options']))

	  {

	  ?>

          <tr>

            <td valign="top" align="right"><strong><?php echo "Other Details:"; ?></strong></td>

            <td class="tdText" colspan="3"><textarea name="product_options" cols="30" rows="10" class="textbox" style="height:80px; width:97%"><?php echo $orderInv[0]['product_options']; ?></textarea></td>

          </tr>

          <?php

	  }

	  ?>

      <tr>

      <td colspan="4">

      <table cellspacing="0" cellpadding="3" class="repairtotal" width="99%">

      <tr>

      <td width="68%">&nbsp;</td>

      <td class="tdText" align="right" style="padding-right:0px"><span class="titlebox"><?php echo "Subtotal"; ?></span></td>

      <td align="left" ><span class="titlebox3">$</span><input  name="subtotal" id="subtotal" type="text" class="textbox2" value="<?php echo $orderSum[0]['subtotal']; ?>" style="float:none; border-left:none; background:none;" /></td>

      </tr>

      <tr>

      <td width="68%">&nbsp;</td>

      <td class="tdText" align="right" style="padding-right:0px"><span class="titlebox"><?php echo "Tax"; ?></span></td>

      <td align="left" ><input name="total_tax" id="total_tax" type="text" class="textbox2"   value="<?php echo $orderSum[0]['total_tax']; ?>"style="float:none; border-right:none; background:none;" /><span class="titlebox3">%</span></td>

      </tr>

      <tr>

      <td width="68%">&nbsp;</td>

      <td class="tdText" align="right" style="padding-right:0px"><span class="titlebox"><?php echo "Amount Paid "; ?></span></td>

      <td align="left" ><span class="titlebox3">$</span><input name="prod_total" id="prod_total" type="text" class="textbox2"  value="<?php echo $orderSum[0]['prod_total']; ?>" style="float:none; border-left:none; background:none;" /></td>

      </tr>

      <tr>

      <td width="68%">&nbsp;</td>

      <td class="tdText" align="right" style="padding-right:0px"><span class="titlebox"><?php echo "Discount"; ?></span></td>

      <td align="left" ><span class="titlebox3">$</span><input name="discount" id="discount" type="text" class="textbox2"  value="<?php echo $orderSum[0]['discount']; ?>" style="float:none; border-left:none; background:none;" /></td>

      </tr>

      <tr>

      <td width="68%">&nbsp;</td>

      <td class="tdText" align="right" style="padding-right:0px"><span class="titlebox"><?php echo "Balance"; ?></span></td>

      <td align="left" ><span class="titlebox3">$</span><input name="prod_total" id="prod_total" type="text" class="textbox2" value="<?php echo $orderSum[0]['prod_total']; ?>" style="float:none; border-left:none; background:none;" /></td>

      </tr>

      </table>

      </td>

      </tr>

        </table>

        </div>

        </td>

    </tr>

    <tr>

      <td colspan="4" class="tdTitle tdTitle2"><?php echo "Customer Information"; ?>

        <input type="button" class="togbutton" id="cinfob" onclick="toogleinfo('cinfo','cinfob');" value="+"></td>

    </tr>

    <tr>

      <td colspan="4">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="4">

      <div id="cinfo">

      <table cellspacing="1" cellpadding="3" class="repairt" width="100%">

          <tr>

            <td align="right" width="20%"><strong><?php echo $lang['admin']['orders_name']; ?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="name" id="name" value="<?php echo $orderSum[0]['name']; ?>" /></td>

            <td  align="right"><strong><?php echo "Last Name:"; ?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="lastName" id="lastName" value="<?php echo $orderSum[0]['lastName']; ?>" /></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_address'];?></strong></td>

            <td class="tdText" colspan="3"><input type="text" class="textbox" name="add_1" id="add_1" value="<?php echo $orderSum[0]['add_1']; ?>" style="width:98%" /></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo "Suburb:";?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="suburb" id="suburb" value="<?php echo $orderSum[0]['suburb']; ?>" /></td>

            <td align="right"><strong><?php echo $lang['admin']['orders_town'];?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="town" id="town" value="<?php echo $orderSum[0]['town']; ?>" /></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_state']; ?></strong></td>

            <td ><?php 

	  $counties = $db->select("SELECT * FROM  ".$glob['dbprefix']."ImeiUnlock_iso_counties WHERE countryId = '13' ORDER BY `name` ASC;");

	  ?>

              <select name="county" class="textbox5" tabindex="10">

                <option value=""><?php echo $lang['admin_common']['na'];?></option>

                <?php

	for($i=0; $i<count($counties); $i++){

	?>

                <option value="<?php echo $counties[$i]['name']; ?>" <?php if($counties[$i]['name'] == $orderSum[0]['county']) echo "selected='selected'"; ?>><?php echo $counties[$i]['name']; ?></option>

                <?php } ?>

              </select></td>

            <td align="right"><strong><?php echo $lang['admin']['orders_postcode'];?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="postcode" id="postcode" value="<?php echo $orderSum[0]['postcode']; ?>" /></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_phone'];?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="phonee" id="phone" value="<?php echo $orderSum[0]['phone']; ?>" /></td>

            <td align="right"><strong><?php echo $lang['admin']['orders_email']; ?></strong></td>

            <td class="tdText"><input type="text" class="textbox" name="email" id="email" value="<?php echo $orderSum[0]['email']; ?>" /></td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo 'How did you find us?'; ?></strong></td>

            <td class="tdText"><select title="How did you hear about us" name="refered_by" id="refered_by" class="textbox5">

                <option value="">-- Select --</option>

                <?php

	  $referer = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_referer WHERE hide = '1'");

	  for($j=0; $j<count($referer); $j++){

	?>

                <option value="<?php echo $referer[$j]['id']; ?>" <?php if($referer[$j]['id'] == $orderSum[0]['refered_by']) echo "selected='selected'"; ?>><?php echo $referer[$j]['title']; ?></option>

                <?php } ?>

              </select></td>

            <td colspan="2">&nbsp;</td>

          </tr>

        </table>

        </div>

        </td>

    </tr>

    <tr>

      <td colspan="4"></td>

    </tr>

    <tr>

      <td valign="top" class="tdTitle tdTitle2" colspan="4"><strong><?php echo 'Order Information'; ?></strong>  <input type="button" class="togbutton" id="oinfob" onclick="toogleinfo('oinfo','oinfob');" value="+"></td>

    </tr>

    <tr>

      <td colspan="4"></td>

    </tr>

    <tr>

      <td colspan="4">

      <div id="oinfo">

      <table cellspacing="1" cellpadding="3" class="repairt" width="99%">

          <tr>

            <td class="tdText" align="right" width="20%"><strong><?php echo $lang['admin']['orders_status']; ?></strong></td>

            <td valign="top" class="tdText"><select name="status" class="textbox5">

                <?php

		for ($i=1; $i<=4; $i++) {

		?>

                <option value="<?php echo $i; ?>" <?php if($orderInv[0]['stat']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['glob']['repairState_'.$i]; ?></option>

                <?php 

		} 

		?>

              </select></td>

            <td align="right"><strong><?php echo $lang['admin']['orders_payment_method']; ?></strong></td>

            <td class="tdText"><?php if(strstr($orderSum['0']['gateway'], "PayPal Website Payments Pro")) { ?>

              <input type="hidden" name="gateway" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />

              <?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>

              <?php } else { ?>

              <input type="text" name="gateway" class="textbox" value="<?php echo str_replace("_"," ",$orderSum['0']['gateway']); ?>" />

              <?php

	  }

	  ?></td>

          </tr>
<tr>

            <td align="right"><strong><?php echo 'Repaired By:'; ?></strong></td>

            <td class="tdText"><select title="Repaired By" name="repairedby" id="repairedby" class="textbox5">

                <option value="">-- Select --</option>

                <?php

	  $repairedby = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_repaired_by WHERE hide = '1'");

	  for($j=0; $j<count($repairedby); $j++){

	?>

                <option value="<?php echo $repairedby[$j]['id']; ?>" <?php if($repairedby[$j]['id'] == $orderSum[0]['repairedby']) echo "selected='selected'"; ?>><?php echo $repairedby[$j]['title']; ?></option>

                <?php } ?>

              </select></td>

            <td colspan="2"></td>

          </tr>
          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_customer_comments']; ?></strong></td>

            <td class="tdText"><textarea name="customer_comments" cols="30" rows="3" class="textbox" style="height:50px;"><?php echo $orderSum['0']['customer_comments']; ?></textarea></td>

            <td colspan="2">&nbsp;</td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_staff_comments']; ?></strong></td>

            <td class="tdText"><textarea name="comments" cols="30" rows="3" class="textbox" style="height:50px;"><?php echo $orderSum['0']['comments']; ?></textarea></td>

            <td colspan="2">&nbsp;</td>

          </tr>

          <tr>

            <td align="right"><strong><?php echo $lang['admin']['orders_extra_notes']; ?></strong></td>

            <td class="tdText"><textarea name="extra_notes" cols="30" rows="3" class="textbox" style="height:50px;"><?php echo $orderSum['0']['extra_notes']; ?></textarea></td>

            <td colspan="2">&nbsp;</td>

          </tr>

        </table>

        </div>

        </td>

    </tr>

    <tr>

      <td colspan="4" align="right" class="tdText"><span class="tdTitle">

        <input type="submit" name="submit22" value="<?php if(isset($_GET['edit'])) { echo $lang['admin_common']['edit']; } else { echo $lang['admin_common']['add']; } ?> <?php echo $lang['admin']['orders_order'];?>"  class="submit" />

        </span></td>

    </tr>

  </table>

</form>

<script type="text/javascript">

function populate() {

	var json = $('customer_select').readAttribute('json');

}

</script>

<?php

include("modules".CC_DS."altCheckout".CC_DS."PayPal_Pro".CC_DS."admin.php");

?>

</table>

