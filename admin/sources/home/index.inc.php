<?php

/*

+--------------------------------------------------------------------------

|	index.inc.php

|   ========================================

|	Main Homepage of Admin

+--------------------------------------------------------------------------

*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }



$lang = getLang("admin".CC_DS."admin_misc.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

// no Products
 echo '<div class="maindiv">'; 
$query = "SELECT count(productId) as noProducts FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE digital != '2'";

$noProducts = $db->select($query);



// no Categories

$query		= sprintf("SELECT COUNT(S.cart_order_id) as noOrders FROM %1\$sImeiUnlock_order_sum AS S, %1\$sImeiUnlock_customer AS C WHERE C.customer_id = S.customer_id", $glob['dbprefix']);

$noOrders	= $db->select($query);



// no Customers

$query = "SELECT count(customer_id) as noCustomers FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE type = 1 OR type = 2";

$noCustomers = $db->select($query);



// last admin session

$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_admin_sessions ORDER BY time DESC LIMIT 1, 1";

$lastSession = $db->select($query);



## check if setup folder remains after install/upgrade

if ($glob['installed'] == true && $config['debug'] == false && file_exists(CC_ROOT_DIR."/setup")) {

	echo sprintf('<p class="warnText">%s</p>', $lang['admin_common']['setup_folder_exists']);

}

@chmod("includes".CC_DS."global.inc.php",0444);

if (substr(PHP_OS, 0, 3) !== "WIN" && cc_is_writable("includes".CC_DS."global.inc.php")) {

	echo sprintf('<p class="warnText">%s</p>', $lang['admin_common']['other_global_risk']);

}



## check if setup folder remains after install/upgrade

if ($glob['dbusername'] == 'root') {

	echo sprintf('<p class="warnText">%s</p>', 'WARNING: You are currently connected to the MySQL database using the root account. This is very insecure, and should be changed if possible.');

}

if($key->key_data['expiry']>1){

	echo sprintf('<p class="warnText">%s</p>', 'WARNING: This store is currently using a trial software license key that is set to expire on '.formatTime($key->key_data['expiry']).'. If you have purchased a full software license key please be sure to edit the includes/global.inc.php file accordingly. <a href="https://support.xxxxx.xxxx/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=61&nav=0,17">More Information</a>');	

}

?>



<?php

if ($lastSession == true) {

	$loginTime = formatTime($lastSession[0]['time']);

	if ($lastSession[0]['success'] == true) {

		echo "<p class='infoText'>".sprintf($lang['admin_common']['other_last_login_success'], strip_tags($lastSession[0]['username']), $loginTime)."</p>";

	} else { 

		echo "<p class='warnText'>".sprintf($lang['admin_common']['other_last_login_failed'], strip_tags($lastSession[0]['username']), $loginTime)."</p>"; 

	}

}

?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">

  <tr>

    <td width="685" valign="top" style="padding-right: 5px;">
	<div class="center">
<div class="maindiv">
	<div class="boxbg">
      <div class="boxbginner"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/img1.jpg'; ?>" /></div>
      <p>
      <span><a class="txt-purple" href="admin.php?_g=products/index">View Products</a></span><br />
       View list of products in the database
      </p>
    </div>
    <div class="boxbg">
      <div class="boxbginner"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/img2.jpg'; ?>" /></div>
      <p>
      <span ><a class="txt-purple" href="admin.php?_g=categories/index&mode=new">New Category</a></span><br />
       Create a new category and organize your products
      </p>
    </div>
    <div class="boxbg">
      <div class="boxbginner"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/img3.jpg'; ?>" /></div>
      <p>
      <span ><a class="txt-purple" href="admin.php?_g=orders/index">Manage Orders</a></span><br />
       View list of all orders in the database
      </p>
    </div>
    <div class="boxbg">
      <div class="boxbginner"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/img4.jpg'; ?>" /></div>
      <p>
      <span ><a class="txt-purple" href="admin.php?_g=stats/index">View Statistics</a></span><br />
        View website statistics to improve your marketing campaign
      </p>
    </div>
</div>
</div>
<h4 class="heading2"><?php echo $lang['admin_common']['other_store_inventory']; ?></h4>
<table  border="1" cellpadding="0" bordercolor="#ddd" cellspacing="0" class="mainTable3">


  <tr>

   <td width="50%" class="tdText" bgcolor="#eeeeee"><span ><?php echo $lang['admin_common']['other_no_products'];?></span></td>

    <td width="50%" class="tdText" bgcolor="#f7f7f7"><?php echo number_format($noProducts[0]['noProducts']); ?></td>

  </tr>

  <tr>

    <td  class="tdText" bgcolor="#eeeeee"><span ><?php echo $lang['admin_common']['other_no_customers']; ?></span></td>

    <td  class="tdText" bgcolor="f7f7f7"><?php echo number_format($noCustomers[0]['noCustomers']); ?></td>

  </tr>

  <tr>

    <td  class="tdText" bgcolor="#eeeeee"><?php echo $lang['admin_common']['other_no_orders']; ?></td>

    <td  class="tdText" bgcolor="#f7f7f7"><?php echo number_format($noOrders[0]['noOrders']); ?></td>

  </tr>

</table>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">

	  <tr>

	    <td colspan="2" class="tdTitle"><?php echo $lang['admin_common']['other_quick_search']; ?></td>

    </tr>

	  <tr>

	    <td><strong  ><?php echo "Repair Order Number:"; ?></strong></td>

      <td>

  <form name="orderSearch" method="get" action="<?php echo $glob['adminFile']; ?>">

<input type="hidden" name="_g" value="repair/orders" />

  <input name="oid" type="text" class="textbox2" size="30" <?php if(permission("orders","read")==FALSE) { echo "disabled";    } ?> /> 

  <input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin_common']['other_search_now']; ?>" <?php if(permission("orders","read")==FALSE) { echo "disabled"; } ?> /> 

  </form></td>

    </tr>
<tr>

	    <td><strong  ><?php echo "Other Order Number:"; ?></strong></td>

      <td>

  <form name="orderSearch" method="get" action="<?php echo $glob['adminFile']; ?>">

<input type="hidden" name="_g" value="orders/index" />

  <input name="oid" type="text" class="textbox2" size="30" <?php if(permission("orders","read")==FALSE) { echo "disabled";    } ?> /> 

  <input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin_common']['other_search_now']; ?>" <?php if(permission("orders","read")==FALSE) { echo "disabled"; } ?> /> 

  </form></td>

    </tr>
	  <tr>

	    <td><strong ><?php echo $lang['admin_common']['other_customer']; ?></strong></td>

      <td>

  <form name="customerSearch" method="get" action="<?php echo $glob['adminFile']; ?>">

  <input type="hidden" name="_g" value="customers/index" />

  <input name="searchStr" type="text" class="textbox2" id="searchStr" size="30" <?php if(permission("customers","read")==FALSE) { echo "disabled"; } ?> /> 

  <input name="search" type="submit" class="submit" id="search" value="<?php echo $lang['admin_common']['other_search_now']; ?>" <?php if(permission("customers","read")==FALSE) { echo "disabled"; } ?> /> 

  </form></td>
 

    </tr>
     <tr>
  <td><strong>Search Transaction Log</strong></td>
  <td><form method="get" enctype="text/plain">

<input type="hidden" name="_g" value="orders/transLogs" />

<input type="text" name="searchKey" value="" class="textbox2" /> <input type="submit" value="Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="submit" />

</form></td>
</tr>

    </table>

</td>

<td valign="top" style="padding-left: 1px;">
<?php

//	$query = "SELECT cart_order_id, name, time, ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.status = 1 OR ".$glob['dbprefix']."ImeiUnlock_order_sum.status = 2 ORDER BY `time` DESC"; 
$query = "SELECT cart_order_id, name, time, ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id FROM ".$glob['dbprefix']."ImeiUnlock_order_sum INNER JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_order_sum.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE ".$glob['dbprefix']."ImeiUnlock_order_sum.status = 2 ORDER BY `time` DESC"; 


	$poPerPage = 10;

	$pendingOrders = $db->select($query, $poPerPage, $_GET['po']);

	$numrows = $db->numrows($query);

	$pagination = paginate($numrows, $poPerPage, $_GET['po'], "po");

	

	if ($pendingOrders) {

	?>

	<div class="hp"><?php echo $lang['admin_common']['other_pending_orders']; ?> </div>

	<div class="processing">

 

 		<ul>
	<?php
		
		for ($i=0; $i<count($pendingOrders); $i++) {

			echo "<li><a href='".$glob['adminFile']."?_g=orders/orderBuilder&amp;edit=".$pendingOrders[$i]['cart_order_id']."' class='name'>".$pendingOrders[$i]['name']."</a> <span class='date'>(".formatTime($pendingOrders[$i]['time']).")</span> </li>";

		}
		if($pagination)
		echo "<li class='pagination' style='background:none; border:0;'>".$pagination."</li>";

	?>
		</ul>
        <!--<a href='".$GLOBALS['rootRel'].$glob['adminFile']."?_g=orders/orderBuilder&amp;edit=".$pendingOrders[$i]['cart_order_id']."' class='txtDash'>".$pendingOrders[$i]['cart_order_id']."</a> ---> 
        <a href="admin.php?_g=orders/index"  class="btnsmall">View All</a>
   </div>




<?php

}

?>

<?php

$query = sprintf("SELECT R.id, R.name, R.time FROM %1\$sImeiUnlock_reviews AS R RIGHT JOIN %1\$sImeiUnlock_inventory as I ON R.productId = I.productId WHERE R.approved = 0 ORDER BY time ASC", $glob['dbprefix']);

$reviewsPerPage = 5;

$reviews = $db->select($query, $reviewsPerPage, $_GET['rev']);

$numrows = $db->numrows($query);

$pagination = paginate($numrows, $reviewsPerPage, $_GET['rev'], "rev");

if ($reviews == true) {

?>
<div class="hp"><?php echo $lang['admin_common']['other_product_reviews']; ?> </div>

	<div class="processing">

 

 		<ul>

<?php

	for ($i=0; $i<count($reviews); $i++) {

		echo "<li><a href='".$glob['adminFile']."?_g=reviews/index&amp;edit=".$reviews[$i]['id']."' class='name'>".$reviews[$i]['name']."</a> (".formatTime($reviews[$i]['time']).")</li>";

	}

	echo $pagination ? "<li class='pagination' style='background:none; border:0;'>".$pagination."</li>" : '';

	?>
		</ul>
   

   </div><br />

<?php

}



if ($config['stock_warn_type'] == 1) {

	$query = "SELECT name, stock_level, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE useStockLevel = 1 AND stock_level <= stockWarn ORDER BY stock_level ASC"; 

} else {

	if (!is_numeric($config['stock_warn_level'])) $config['stock_warn_level'] = 5;

	$query = "SELECT name, stock_level, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE useStockLevel = 1 AND stock_level <= ".$config['stock_warn_level']." ORDER BY stock_level ASC"; 

}



$stockPerPage = 20;

$stock = $db->select($query, $stockPerPage, $_GET['po']);

$numrows = $db->numrows($query);

$pagination = paginate($numrows, $stockPerPage, $_GET['po'], "po");

	

if ($stock == true) {

?>

  <!--<table width="100%" border="0" cellpadding="3" cellspacing="1" class="toDoTable">

	<tr>

	  <td width="50%" align="left" valign="top" class="tdtoDo"><?php echo $lang['admin_common']['other_stock_warnings'];?></td>

	</tr>

	<tr>

	  <td width="50%" align="left" valign="top" class="tdText">

	<?php

	for ($i=0; $i<count($stock); $i++) {

		echo " <a href='".$glob['adminFile']."?_g=products/index&amp;edit=".$stock[$i]['productId']."' class='txtDash'>".$stock[$i]['name']."</a> (".$stock[$i]['stock_level'].")<br />";

	}

	echo $pagination;

	?>

	  </td>

	</tr>

  </table>-->

  <br />

<?php } ?>

<!--<table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">

  <tr>

    <td colspan="2" class="tdTitle"><?php echo $lang['admin_common']['other_store_overview']; ?></td>

  </tr>

  <tr>

    <td width="33%"><a href="http://www.php.net" target="_blank" class="txtLink">PHP</a> <span  class="tdText"><?php echo $lang['admin_common']['other_version']; ?></span></td>

    <td width="50%"><span class="tdText"><?php echo phpversion();?></span></td>

  </tr>

  <tr>

    <td width="33%"><a href="http://www.mysql.com" target="_blank" class="txtLink">MySQL</a> <span class="tdText"><?php echo $lang['admin_common']['other_version']; ?></span></td>

    <td width="50%"><span class="tdText"><?php echo mysql_get_server_info(); ?></span></td>

  </tr>

  <tr>

    <td width="33%" class="tdText"><?php echo $lang['admin_common']['other_img_upload_size']; ?></td>

    <td width="50%" class="tdText">

	<?php 

	echo format_size($config['uploadSize']); 

	?> </td>

  </tr>

  <tr>

    <td width="33%" class="tdText"><?php echo $lang['admin']['misc_server_software']; ?></td>

    <td width="50%" class="tdText"><?php echo @$_SERVER["SERVER_SOFTWARE"]; ?></td>

  </tr>

  <tr>

    <td width="33%" class="tdText"><?php echo $lang['admin']['misc_client_browser']; ?></td>

    <td width="50%" class="tdText"><?php echo @$_SERVER["HTTP_USER_AGENT"]; ?></td>

  </tr>

  </table>-->

  <br />

  



	</td>

  </tr>

</table>
</div>
<!-- Code added for ImeiUnlock Support Staff please ignore

Licensed Domain: <?php echo $key->key_data['hostname']; ?> 

Software License Key: <?php echo $key->key_data['license_key']; ?> 

--> 

