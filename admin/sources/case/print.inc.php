<?php
/*
+--------------------------------------------------------------------------
|	print.inc.php
|   ========================================
|	Print Packing Slip	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

include("language".CC_DS.$config['defaultLang'].CC_DS."config.php");

$lang = getLang("admin".CC_DS."admin_orders.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");

permission("orders","read", true);
$skipFooter = true;

$result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.cart_order_id = ".$db->mySQLSafe($_GET['cart_order_id']));

// start: Flexible Taxes, by Estelle Winterflood
// count the number of additional taxes
$num_taxes = 1;
$config_tax_mod = fetchDbConfig("Multiple_Tax_Mod");
if ($config_tax_mod['status']) {
	for ($i=1; $i<3; $i++) {
		if ($result[0]['tax'.($i+1).'_disp'] != "") {
			$num_taxes++;
		}
	}

	// tax registration number(s)
	$reg_number = $db->select("SELECT reg_number FROM ".$glob['dbprefix']."ImeiUnlock_tax_details;");
	$reg_string = "";
	for ($i=0; is_array($reg_number) && $i<count($reg_number); $i++)
	{
		if ($reg_number[$i]['reg_number']!="") {
			$reg_string .= $reg_number[$i]['reg_number']."<br/>";
		}
	}
}
// end: Flexible Taxes

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo sprintf($lang['admin']['orders_order_invoice'],$_GET['cart_order_id']);?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charsetIso; ?>" />
  <link rel="stylesheet" href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder'];?>/styles/print.css" />
</head>
<body onload="window.print();">

<div id="header">
  <div id="printLabel">
  	<strong><?php echo $lang['admin']['orders_delivered_to'];?></strong>
	<br />
	<div>
	<?php echo $result[0]['name_d'];
	 ?><br />
	<?php if (!empty($result[0]['companyName_d'])) echo $result[0]['companyName_d'].'<br/>'; ?>
	<?php echo $result[0]['add_1_d']; ?>,<br />
	<?php
	if (!empty($result[0]['add_2_d'])) {
		echo $result[0]['add_2_d'].",<br />";
	}
	echo $result[0]['town_d']; ?>,<br />
	<?php echo $result[0]['county_d']; ?><br />
	<?php echo $result[0]['postcode_d']; ?><br />
	<?php echo $result[0]['country_d']; ?>
	</div>
	<div class="sender"><?php echo $lang['admin']['orders_return_address'];?><br /><?php echo $config['storeAddress']; ?></div>
  </div>
  <div id="storeLabel">
  	<img src="<?php echo $glob['rootRel']; ?>images/getLogo.php?skin=<?php echo $config['skinDir']; ?>" alt="" />
  </div>
</div>

<div class="info">
  <span class="orderid"><strong><?php echo $lang['admin']['orders_order_id'];?></strong> &nbsp; <?php echo $_GET['cart_order_id']; ?></span>
  <strong><?php echo $lang['admin']['orders_invoice_reciept_for'];?></strong> <?php echo formatTime($result[0]['time']);?>
</div>

<div class="product">
  <span class="price"><?php echo $lang['admin']['orders_price'];?></span>
  <strong><?php echo $lang['admin']['orders_product'];?></strong>
</div>

<?php
$results = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_inv WHERE cart_order_id = ".$db->mySQLSafe($_GET['cart_order_id']));
for ($i=0; $i<count($results);$i++){
?>
<div class="product">
  <span class="price"><?php echo priceFormat($results[$i]['price'], true); ?></span>
  <?php
  echo sprintf('%d x %s (%s)', $results[$i]['quantity'], $results[$i]['name'], $results[$i]['productCode']);
  if (!empty($results[$i]['product_options'])) {
  	echo sprintf(' &raquo; <span class="options"><br />%s</span>', nl2br(stripslashes(str_replace("&amp;#39;","&#39;",$results[$i]['product_options']))));
  }
  ?>
</div>
<?php } ?>
<div id="totals">
  <div class="total"><?php echo $lang['admin']['orders_subtotal'];?> <strong><?php echo priceFormat($result[0]['subtotal'], true);?></strong></div>
  <div class="total"><?php echo $lang['admin']['orders_discount'];?> <strong><?php echo priceFormat($result[0]['discount'], true);?></strong></div>
  <div class="total"><?php echo $lang['admin']['orders_shipping'];?> <strong><?php echo priceFormat($result[0]['total_ship'], true);?></strong></div>
<?php
if ($config_tax_mod['status']) {
	for ($i=0; $i<3; $i++) {
		if ($result[0]['tax'.($i+1).'_disp'] != "") {
			$name	= $result[0]['tax'.($i+1).'_disp'];
			$value	= priceFormat($result[0]['tax'.($i+1).'_amt'], true);
			
		} else if ($i==0) {
			$name	= $lang['admin']['orders_total_tax'];
			$value	= priceFormat($result[0]['total_tax'], true);
			
		} else {
			break;
		}
?>
	<div class="total"><?php echo $name ?> <strong><?php echo $value ?></strong></div>
<?php
		}
	} else {
?>
	<div class="total"><?php echo $lang['admin']['orders_total_tax'];?> <strong><?php echo priceFormat($result[0]['total_tax'], true);?></strong></div>
<?php } ?>
  <br />
  <div class="total"><strong><?php echo $lang['admin']['orders_grand_total'];?> <?php echo priceFormat($result[0]['prod_total'], true);?></strong></div>
</div>
<?php if (!empty($result[0]['extra_notes'])) { ?>
<div id="notes"><strong><?php echo $lang['admin']['orders_extra_notes'];?></strong> <?php echo $result[0]['extra_notes']; ?></div>
<?php } ?>
<div id="thanks">
  <?php echo $lang['admin']['orders_thank_you'];?>
</div>
<div id="footer">
  <p><?php echo $config['storeAddress']; ?></p>
  <?php if (isset($reg_string)) echo "<p class='copyText'>".$reg_string."</p>"; ?>
</div>
</body>
</html>
