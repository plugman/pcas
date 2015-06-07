<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage Customers Accounts
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_customers.inc.php");

permission("customers","read", true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

$rowsPerPage = 25;

if(isset($_GET["delete"]) && $_GET["delete"]>0)
{
	
	// instantiate db class
	$where = "customer_id=".$db->mySQLSafe($_GET["delete"]);
	$delete = $db->delete($glob['dbprefix']."ImeiUnlock_customer", $where);
		
	if($delete == TRUE)
	{
		$msg = "<p class='infoText'>".$lang['admin']['customers_delete_success']."</p>";
	} 
	else 
	{
		$msg = "<p class='warnText'>".$lang['admin']['customers_delete_success']."</p>";
	}

} 
else if(isset($_POST['customer_id']) && (isset($_POST['addcredit']) && $_POST['addcredit'] > 0)) 
{
	/*echo "<pre>";
	print_r($_POST);
	die();*/
	$record['notes'] = $db->mySQLSafe("Credit added by admin");
	$record['amount'] = $db->mySQLSafe($_POST['addcredit']);
	$record['status'] = $db->mySQLSafe(1);
	$record['date_topped'] = $db->mySQLSafe(time(0));
	$record['customerid'] = $db->mySQLSafe($_POST['customer_id']);
	$record['gateway'] = $db->mySQLSafe("Admin");
	$record['transactionId'] = $db->mySQLSafe(time() + rand(0,17));
	$insert = $db->insert("tbl_topup_payment_transactions", $record);
	$updatebalance['card_balance'] = "card_balance +".$_POST['addcredit']."";
	$where = "customer_id=".$db->mySQLSafe($_POST['customer_id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $updatebalance, $where);
	if($update == true){
		// insert transaction record
			
			$transData['customer_id']	= $_POST['customer_id'];
			$transData['trans_id'] 		= time() + rand(0,17);	
			$transData['dr'] 		= $_POST['addcredit'];
			$transData['notes'] 		= "Credits Recharge.";
			$transData['balance'] 		= $_POST['credits'] + $_POST['addcredit'];
			storeCreditTrans($transData);	
	 $msg = "<p class='infoText'>".$lang['admin']['customers_update_success']."</p>";
	 require_once "classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
				
				$lang = getLang("email.inc.php");
				$mail = new htmlMimeMail();
				
				$macroArray = array(
					"CUSTOMER_NAME" => sanitizeVar($_POST["firstName"]),
					"CREDITS"		=> sanitizeVar($_POST['addcredit']),
					"STORE_URL"		=> $GLOBALS['storeURL'],
				);
				
				$text = macroSub($lang['email']['messege_add_credit'],$macroArray);
				unset($macroArray);
				
				$mail->setText($text);
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setReturnPath($config['masterEmail']);
				$mail->setSubject($lang['email']['addcredit_subject']);
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array(sanitizeVar($_POST['email'])), $config['mailMethod']);
				}
	else {
	$msg = "<p class='warnText'>".$lang['admin']['customers_insert_fail']."</p>";}
	}
else if(isset($_POST['customer_id']) && (isset($_POST['delcredit']) && $_POST['delcredit'] > 0)) 
{
/*	echo "<pre>";
	print_r($_POST);
	die();*/
	$record['notes'] = $db->mySQLSafe("Credit Deducted by admin");
	$record['amount'] = $db->mySQLSafe("-".$_POST['delcredit']);
	$record['status'] = $db->mySQLSafe(1);
	$record['date_topped'] = $db->mySQLSafe(time(0));
	$record['customerid'] = $db->mySQLSafe($_POST['customer_id']);
	$record['gateway'] = $db->mySQLSafe("Admin");
	$record['transactionId'] = $db->mySQLSafe(time() + rand(0,17));
	if($_POST['credits'] >0){
	$insert = $db->insert("tbl_topup_payment_transactions", $record);
	$updatebalance['card_balance'] = "card_balance -".$_POST['delcredit']."";
	$where = "customer_id=".$db->mySQLSafe($_POST['customer_id']);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $updatebalance, $where);
	if($update == true){
		$transData['customer_id']	= $_POST['customer_id'];
			$transData['trans_id'] 		= time() + rand(0,17);	
			$transData['cr'] 		= $_POST['delcredit'];
			$transData['notes'] 		= "Credits Deduct by Admin.";
			$transData['balance'] 		= $_POST['credits'] - $_POST['delcredit'];
			storeCreditTrans($transData);	
	 $msg = "<p class='infoText'>".$lang['admin']['customers_update_success']."</p>";
	 require_once "classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
				
				$lang = getLang("email.inc.php");
				$mail = new htmlMimeMail();
				
				$macroArray = array(
					"CUSTOMER_NAME" => sanitizeVar($_POST["firstName"]),
					"CREDITS"		=> sanitizeVar($_POST['delcredit']),
					"STORE_URL"		=> $GLOBALS['storeURL'],
				);
				
				$text = macroSub($lang['email']['messege_del_credit'],$macroArray);
				unset($macroArray);
				
				$mail->setText($text);
				$mail->setFrom($config['masterName'].' <'.$config['masterEmail'].'>');
				$mail->setReturnPath($config['masterEmail']);
				$mail->setSubject($lang['email']['delcredit_subject']);
				$mail->setHeader('X-Mailer', 'ImeiUnlock Mailer');
				$mail->send(array(sanitizeVar($_POST['email'])), $config['mailMethod']);
		}
	}
	else {
	$msg = "<p class='warnText'>".$lang['admin']['customers_insert_fail']."</p>";}
	}


elseif(isset($_POST['customer_id']) && !isset($_POST['credits'])) 
{
	$record["customer_type"] = $db->mySQLSafe($_POST['customer_type']);
	$record["title"] = $db->mySQLSafe($_POST['title']);
	$record["block"] = $db->mySQLSafe($_POST['block']);
	$record["type"] = $db->mySQLSafe($_POST['type']);		
	$record["firstName"] = $db->mySQLSafe($_POST['firstName']);	
	$record["skype"] = $db->mySQLSafe($_POST['skype']);	
	$record["lastName"] = $db->mySQLSafe($_POST['lastName']);
	$record["email"] = $db->mySQLSafe($_POST['email']);  
	$record["companyName"] = $db->mySQLSafe($_POST['companyName']);  
	$record["add_1"] = $db->mySQLSafe($_POST['add_1']); 
	$record["add_2"] = $db->mySQLSafe($_POST['add_2']); 
	$record["town"] = $db->mySQLSafe($_POST['town']);
	$record["county"] = $db->mySQLSafe($_POST['county']);
	$record["country"] = $db->mySQLSafe($_POST['country']);
	$record["phone"] = $db->mySQLSafe($_POST['phone']);
	$record["mobile"] = $db->mySQLSafe($_POST['mobile']);
	$record["postcode"] = $db->mySQLSafe($_POST['postcode']);
	$record["optIn1st"] = $db->mySQLSafe($_POST['optIn1st']);
	$record["api"] = $db->mySQLSafe($_POST['api']);
	$record["username"] = $db->mySQLSafe($_POST['username']);
	$record["api_access_key"] = $db->mySQLSafe($_POST['api_access_key']);

	if( (!empty($_POST['password']) && !empty($_POST['password_conf']) && $_POST['password']==$_POST['password_conf']) ){
		$salt = randomPass(6);
		$record["salt"] = "'".$salt."'";
		$record["password"] = $db->mySQLSafe(md5(md5($salt).md5($_POST['password']))); 
	}
	
	
	if($_POST['customer_id']>0){
		
		$where = "customer_id=".$db->mySQLSafe($_POST['customer_id']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);
		
		if($update == TRUE){
			$msg = "<p class='infoText'>".$lang['admin']['customers_update_success']."</p>";
		} else {
			$msg = "<p class='warnText'>".$lang['admin']['customers_update_fail']."</p>";
		}
	
	} else {
		$record["regTime"] = $db->mySQLSafe(time());
		
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_customer", $record);
		
		if($insert == TRUE) {
			$msg = "<p class='infoText'>".$lang['admin']['customers_insert_success']."</p>";
		} else {
			$msg = "<p class='warnText'>".$lang['admin']['customers_insert_fail']."</p>";
		}
	
	}

}

	if (isset($_GET['edit']) && $_GET['edit']>0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = %s", $db->mySQLSafe($_GET['edit'])); 
		
	}else if (isset($_GET['addcredit']) && $_GET['addcredit']>0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = %s", $db->mySQLSafe($_GET['addcredit'])); 	
			 
		}else if (isset($_GET['delcredit']) && $_GET['delcredit']>0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = %s", $db->mySQLSafe($_GET['delcredit'])); 		 
		 }else if (isset($_GET['creport']) && $_GET['creport']>0) {
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_credits_trans_report WHERE customer_id = %s", $db->mySQLSafe($_GET['creport']) ."Order By date DESC, id ASC"); 	
	} else if (isset($_GET['searchStr'])) {
		
		if (is_numeric($_GET['searchStr'])) {
			
			$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id  = ".$db->mySQLSafe($_GET['searchStr']);
		} else {
			
			$searchwords = split ( "[ ,]", trim($_GET['searchStr'])); /* bug fix 1448 thanks Brivtech */ 
			  
			foreach($searchwords as $word) {
				$searchArray[]=$word;
			}
		
			$noKeys = count($searchArray);
			
			$like = "";
			
			for ($i=0; $i<$noKeys;$i++) {
				
				$ucSearchTerm = strtoupper($searchArray[$i]);
				if(($ucSearchTerm!=="AND")AND($ucSearchTerm!=="OR")) {
					
					$like .= "(email LIKE '%".$searchArray[$i]."%' OR title LIKE '%".$searchArray[$i]."%' OR  firstName LIKE '%".$searchArray[$i]."%' OR lastName LIKE '%".$searchArray[$i]."%' OR add_1 LIKE '%".$searchArray[$i]."%' OR  add_2 LIKE '%".$searchArray[$i]."%' OR town LIKE '%".$searchArray[$i]."%' OR county LIKE '%".$searchArray[$i]."%' OR  postcode LIKE '%".$searchArray[$i]."%' OR country LIKE '%".$searchArray[$i]."%' OR phone LIKE '%".$searchArray[$i]."%' OR  ipAddress LIKE '%".$searchArray[$i]."%') OR ";
					
				} else {
					$like = substr($like,0,strlen($like)-3);
					$like .= $ucSearchTerm;
				}  
		
			}
			$like = substr($like,0,strlen($like)-3);
			
			$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE ".$like;
	
	}
	
	} else if ($_GET['mode']!=="new") {
		
		$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer ORDER BY regTime DESC";
	
	}
	
	// query database
	if (isset($query)) {
		$page = (is_numeric($_GET['page'])) ? $_GET['page'] : 0;
		$customerData = $db->select($query, $rowsPerPage, $page);
		$numrows = $db->numrows($query);
		$pagination = paginate($numrows, $rowsPerPage, $page, "page");
	}
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <!--<td nowrap='nowrap' class="pageTitle"><?php echo $lang['admin']['customers_customers']; ?></td>-->
     <?php if(!isset($_GET["mode"])){ ?><td align="right" valign="middle"><a <?php if(permission("customers","write")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;mode=new" class="txtLink" <?php } else { echo $link401; } ?>><img src="<?php echo $glob['adminFolder']; ?>/images/buttons/new.gif" alt="" hspace="4" border="0" title="" /><?php echo $lang['admin_common']['add_new'];?></a></td><?php } ?>
  </tr>
</table>
<br />

<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}

if(!isset($_GET['mode']) && !isset($_GET['edit']) && !isset($_GET['addcredit']) && !isset($_GET['delcredit'])  && !isset($_GET['creport']))
{
?>

<form name="filter" method="get" action="<?php echo $glob['adminFile']; ?>">
<input type="hidden" name="_g" value="customers/index" />
 	<div align="right" class="copyText maindiv" style="margin-bottom:10px">
	<div style="float:left; line-height:34px; margin-right:8px;"><?php echo $lang['admin']['customers_search_term']; ?></div>
      <div class="inputbox">
		<span class="bgleft"></span>
    	 <input type="text" name="searchStr" value="<?php if(isset($_GET['searchStr'])) echo $_GET['searchStr']; ?>" />  
	   <span class="bgright"></span>
	   </div>
     <div class="left" style="margin-left:10px">
    <input name="Submit" type="submit" class="submit" value="Filter" />
    <input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=customers/index');return document.returnValue" value="<?php echo $lang['admin']['customers_reset']; ?>" class="submit" />
    </div>
	</div>
</form>


<!--<p class="pagination" ><?php echo $pagination; ?></p>-->
<table width="100%" border="1" cellspacing="1" cellpadding="3" class="mainTable mainTable4">
  <tr>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_type']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_name']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_email']; ?></td>
   <!-- <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_invoice_add']; ?></td>-->
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_phone']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_reg_ip']; ?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_no_orders']; ?></td>
    <td colspan="4" align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_action']; ?></td>
  </tr>
<?php 
if ($customerData) { 
	for ($i=0; $i<count($customerData); $i++) {
		
	#	$orderCount	= sprintf("SELECT COUNT(cart_order_id) AS noOrders FROM %sImeiUnlock_order_sum WHERE customer_id = %d", $glob['dbprefix'], $customerData[$i]['customer_id']);
	#	$orderResult= $db->select($orderCount);
		
		$cellColor = cellColor($i);
?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><img src="<?php echo $glob['adminFolder']; ?>/images/type<?php echo $customerData[$i]['type']; ?>.gif" alt="<?php echo $lang['admin']['customers_type'.$customerData[$i]['type']]; ?>" title="<?php echo $lang['admin']['customers_type'.$customerData[$i]['type']]; ?>" />
	
	</td>
    <td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['title']." ".$customerData[$i]['firstName']." ".$customerData[$i]['lastName']; ?></td>
    <td class="<?php echo $cellColor; ?>"><a href="mailto:<?php echo $customerData[$i]['email']; ?>" class="txtLink"><?php echo $customerData[$i]['email']; ?></a></td>
   <!-- <td class="<?php echo $cellColor; ?> tdText"><?php 
	if(!empty($customerData[$i]['companyName'])) echo $customerData[$i]['companyName'].", ";
	if(!empty($customerData[$i]['add_1'])) echo $customerData[$i]['add_1'].", "; 
	if(!empty($customerData[$i]['add_2'])) echo $customerData[$i]['add_2'].", "; 
	if(!empty($customerData[$i]['town'])) echo $customerData[$i]['town'].", ";
	if(!empty($customerData[$i]['county'])) echo $customerData[$i]['county'].", ";
	if(!empty($customerData[$i]['postcode'])) echo $customerData[$i]['postcode'].", "; 	
	if(!empty($customerData[$i]['country'])) echo getCountryFormat($customerData[$i]['country'],"id","printable_name");
	?>	</td>-->
    <td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['phone']; ?><br/><?php echo $customerData[$i]['mobile']; ?></td>
    <td nowrap='nowrap' class="<?php echo $cellColor; ?> tdText">
		<?php echo formatTime($customerData[$i]['regTime']); ?><br />
		<a href="javascript:;" class="txtLink" onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=misc/lookupip&amp;ip=<?php echo $customerData[$i]['ipAddress']; ?>','misc',300,130,'yes,resizable=yes')"><?php echo $customerData[$i]['ipAddress']; ?></a>	</td>
    <td align="center" class="<?php echo $cellColor; ?>">
	<?php if($customerData[$i]['noOrders']>0) { ?>
	<a href="<?php echo $glob['adminFile']; ?>?_g=orders/index&amp;customer_id=<?php echo $customerData[$i]['customer_id']; ?>" class="txtLink"><?php echo $customerData[$i]['noOrders']; ?></a>
	<?php } else { ?>
	<span class="tdText"><?php echo $customerData[$i]['noOrders']; ?></span>
	<?php } ?>	</td>
     <td align="center" colspan="4" class="<?php echo $cellColor; ?> a2">
     <a <?php if(permission("customers","addcredit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;creport=<?php echo $customerData[$i]['customer_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/i17.jpg'; ?>"  title="Credits Report"/></a>
	<a <?php if(permission("customers","addcredit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;addcredit=<?php echo $customerData[$i]['customer_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>>   <img alt="" src="<?php echo $glob['storeURL'].'/admin/images/badd.png'; ?>"  title="Add balance"/></a>	
	<a <?php if(permission("customers","addcredit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;delcredit=<?php echo $customerData[$i]['customer_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>>   <img alt="" src="<?php echo $glob['storeURL'].'/admin/images/bless.png'; ?>"  title="Delete balance"/></a>	
   
	<a <?php if(permission("customers","edit")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;edit=<?php echo $customerData[$i]['customer_id']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>	
	<a  <?php if(permission("customers","delete")==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index&amp;delete=<?php echo $customerData[$i]['customer_id']; ?>" onclick="return confirm('<?php echo str_replace("\n", '\n', addslashes($lang['admin_common']['delete_q'])); ?>')" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['delete']; ?></a></td>
  </tr>
<?php 
  		} // end loop  
	} 
	else 
	{ ?>
   <tr>
    <td colspan="7" class="tdText"><?php echo $lang['admin']['customers_no_cust_exist']; ?></td>
  </tr>
<?php
  } 
?>
</table>
<p class="pagination" align="right"> <span class="right"><?php echo $pagination; ?></span> </p>
<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"]>0) {

?>
<form name="editCustomer" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/index">
<div class="headingBlackbg"><?php if($_GET["mode"]=="new") { echo $lang['admin']['customers_add_below']; } else { echo $lang['admin']['customers_edit_below'];; } ?></div>
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
 
    <tr>
    <td align="right" width="25%" class="tdText"><strong>Customer Type:</strong></td>
    <td>
    <?php
	# Customer Type
	$customerType = $db->select("SELECT wholesaleId, customer_type FROM ".$glob['dbprefix']."ImeiUnlock_customer_type ORDER BY wholesaleId");
	if ($customerType > 0)
	{
		?>
         <div class="inputbox">
		<span class="bgleft"></span>
    	<select class="textboxdropdown" id="customer_type" name="customer_type">
        
        <?php
		for ($i=0; $i<count($customerType); $i++) {
	?>
      <option value="<?php echo $customerType[$i]['wholesaleId'];?>" <?php if ($customerType[$i]['wholesaleId'] == $customerData[0]['customer_type']) { ?> selected="selected" <?php }?>><?php echo $customerType[$i]['customer_type'];?></option>
                  <?php }}?>
                  
                </select>	
	   <span class="bgright"></span>
	   </div>
           
                
                </td>
  </tr>
 <!-- <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_title']; ?></strong></td>
    <td width="175">
      <input name="title" type="text" id="title" value="<?php echo $customerData[0]['title']; ?>" class="textbox" />    </td>
  </tr>-->
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_first_name']; ?></strong></td>
    <td>
      <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="firstName" type="text" id="firstName" value="<?php echo $customerData[0]['firstName']; ?>" class="textbox" />	
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <!--<tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_last_name']; ?></strong></td>
    <td width="175"><input name="lastName" type="text" id="lastName" value="<?php echo $customerData[0]['lastName']; ?>" class="textbox" /></td>
  </tr>-->
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_email2']; ?></strong></td>
    <td>
    <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="email" type="text" id="email" value="<?php echo $customerData[0]['email']; ?>" class="textbox" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo "Topup Balance"; ?></strong></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="Balance" type="text" id="Balance" value="<?php echo $customerData[0]['card_balance']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td class="tdText" align="right"><strong><?php echo $lang['admin']['customers_address']; ?></strong></td>
    <td width="175">
    <div class="inputbox">
		<span class="bgleft"></span>
    <input name="add_1" type="text" id="add_1" value="<?php echo $customerData[0]['add_1']; ?>" class="textbox" />
     <span class="bgright"></span>
	   </div>
       </td>
  </tr>
 <!-- <tr>
    <td width="175">&nbsp;</td>
    <td width="175"><input name="add_2" type="text" id="add_2" value="<?php echo $customerData[0]['add_2']; ?>" class="textbox" /></td>
  </tr>-->
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_town']; ?></strong></td>
    <td >
    <div class="inputbox">
		<span class="bgleft"></span>
        <input name="town" type="text" id="town" value="<?php echo $customerData[0]['town']; ?>" class="textbox" />
        <span class="bgright"></span>
	   </div></td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_county']; ?></strong></td>
    <td >
    <div class="inputbox">
		<span class="bgleft"></span>
        <input name="county" type="text" id="county" value="<?php echo $customerData[0]['county']; ?>" class="textbox" />
        <span class="bgright"></span>
	   </div></td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_postcode']; ?></strong></td>
    <td>
    <div class="inputbox">
		<span class="bgleft"></span>
        <input name="postcode" type="text" id="postcode" value="<?php echo $customerData[0]['postcode']; ?>" class="textbox" />
        <span class="bgright"></span>
	   </div></td>
  </tr>
  <!--<tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_country']; ?></strong></td>
    <td width="175">
	<?php 
	  $countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY `printable_name` ASC"); 
	  ?>
	
	<select name="country" id="country">
	<option value=""><?php echo $lang['admin_common']['na'];?></option>
	<?php
	for($i=0; $i<count($countries); $i++){
	?>
	<option value="<?php echo $countries[$i]['id']; ?>" <?php if($countries[$i]['id'] == $customerData[0]['country']) echo "selected='selected'"; ?>><?php echo $countries[$i]['printable_name']; ?></option>
	<?php } ?>
	</select>	</td>
  </tr>-->
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_phone2']; ?></strong></td>
    <td>
      <div class="inputbox">
		<span class="bgleft"></span>
    	  <input name="phone" type="text" id="phone" value="<?php echo $customerData[0]['phone']; ?>" class="textbox" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_country']; ?></strong></td>
    <td>
    <div class="inputbox">
    <span class="bgleft"></span>
		
	<?php 
	  $countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries ORDER BY `printable_name` ASC"); 
	  ?>
	
	<select name="country" id="country">
	<option value=""><?php echo $lang['admin_common']['na'];?></option>
	<?php
	for($i=0; $i<count($countries); $i++){
	?>
	<option value="<?php echo $countries[$i]['id']; ?>" <?php if($countries[$i]['id'] == $customerData[0]['country']) echo "selected='selected'"; ?>><?php echo $countries[$i]['printable_name']; ?></option>
	<?php } ?>
	</select>	
     <span class="bgright"></span>
	   </div></td>
  </tr>
 
  <tr>
    <td align="right" class="tdText"><strong><?php echo "Disable/Block"; ?></strong></td>
    <td>
      <div class="inputbox">
		<span class="bgleft"></span>
    	 <select name="block" id="block">
      <option value="1" <?php if($customerData[0]['block']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['yes'];?></option>
      <option value="0" <?php if($customerData[0]['block']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['no'];?></option>
    </select>
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_mail_list']; ?></strong></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
    	 <select name="optIn1st" id="optIn1st">
      <option value="1" <?php if($customerData[0]['optIn1st']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['yes'];?></option>
      <option value="0" <?php if($customerData[0]['optIn1st']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['no'];?></option>
    </select>
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>

    <td align="right" class="tdText"><strong><?php echo "API Enable"; ?></strong></td>

    <td>  <div class="inputbox">
		<span class="bgleft"></span><select name="api" id="api">

      <option value="1" <?php if($customerData[0]['api']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['yes'];?></option>

      <option value="0" <?php if($customerData[0]['api']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['no'];?></option>

    </select> <span class="bgright"></span>
	   </div></td>

  </tr>
 <script language="javascript" type="text/javascript">
function randomString() {
 var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
 var string_length = 24;
 var randomstring = '';
 for (var i=0; i<string_length; i++) {
  var rnum = Math.floor(Math.random() * chars.length);
  randomstring += chars.substring(rnum,rnum+1);
  if(randomstring.length == 4 || randomstring.length == 9 || randomstring.length == 14 || randomstring.length == 19 || randomstring.length == 24 )
  randomstring += '-';
  
 } 
 document.getElementById('api_access_key').value = randomstring;

}
</script>
  <tr>

     <td align="right" class="tdText"><strong><?php echo "Api User Name"; ?></strong>
</td>

    <td><div class="inputbox">
		<span class="bgleft"></span><input name="username" type="text" id="username" value="<?php echo $customerData[0]['username']; ?>" class="textbox"  autocomplete="off"/><span class="bgright"></span>
	   </div> </td>

  </tr>
  <tr>

    <td align="right" class="tdText"><strong><?php echo "Api Access Key"; ?></strong></td>

   <td><div class="inputbox">
		<span class="bgleft"></span><input name="api_access_key" type="text" id="api_access_key" value="<?php echo $customerData[0]['api_access_key']; ?>" class="textbox" autocomplete="off" /> 
  <span class="bgright"></span>
	   </div> <br />&nbsp; &nbsp;<span style="color:#F00; cursor:pointer;" onclick="randomString();" >Generate Key</span>
   </td>

  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_password']; ?></strong><br />
        <?php echo $lang['admin']['customers_password_leave']; ?></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
    	  <input name="password" type="password" id="password" value="" class="textbox" />
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
  <tr>
    <td align="right" class="tdText"><strong><?php echo $lang['admin']['customers_password_conf']; ?></strong></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="password_conf" type="password" id="password_conf" value="" class="textbox" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="customer_id" value="<?php echo $customerData[0]['customer_id']; ?>" />
	<?php 
	if($customerData[0]['customer_id']>0) { 
	?> 
		<input type='hidden' name='type' value='<?php echo $customerData[0]['type']; ?>' />
	<?php 
	} else {
	?>
		<input type='hidden' name='type' value='1' />
	<?php
	}
	?>
	<input name="Submit" type="submit" class="submit" value="<?php if($_GET['mode']=='new') { echo $lang['admin']['customers_add_customer']; } else { echo $lang['admin']['customers_edit_customer']; } ?>" />	</td>
  </tr>
</table>
</form>
<?php 
}else if ($_GET["addcredit"]>0) {
?>
<form name="editCustomer" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/index">
<div class="headingBlackbg"><?php if($_GET["mode"]=="new") { echo $lang['admin']['customers_add_below']; } else { echo $lang['admin']['customers_edit_below']; } ?></div>
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  
  <tr>
    <td class="tdText" width="15%"><strong><?php echo $lang['admin']['customers_first_name']; ?></strong></td>
    <td>
          <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="firstName" type="text" id="firstName" value="<?php echo $customerData[0]['firstName']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo "Email"; ?></strong></td>
    <td>
      <div class="inputbox">
		<span class="bgleft"></span>
    	 <input name="email" type="text" id="email" value="<?php echo $customerData[0]['email']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo 'Current Balance'; ?></strong></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
    <input name="credits" type="text" id="credits" value="<?php echo $customerData[0]['card_balance']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
    </td>
   <tr>
    <td class="tdText"><strong><?php echo 'Add Balance'; ?></strong></td>
    <td>
       <div class="inputbox">
		<span class="bgleft"></span>
     <input name="addcredit" type="text" id="addcredit" value="" class="textbox" />
	   <span class="bgright"></span>
	   </div>
   </td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="customer_id" value="<?php echo $customerData[0]['customer_id']; ?>" />
	<input name="Submit" type="submit" class="submit" value=" Add Balance" />	</td>
  </tr>
</table>
  </form>
  <?php 
}else if ($_GET["delcredit"]>0) {
?>
<form name="editCustomer" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/index">
<div class="headingBlackbg"><?php if($_GET["mode"]=="new") { echo $lang['admin']['customers_add_below']; } else { echo $lang['admin']['customers_edit_below'];; } ?></div>
<table  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_first_name']; ?></strong></td>
    <td>
        <div class="inputbox">
		<span class="bgleft"></span>
  <input name="firstName" type="text" id="firstName" value="<?php echo $customerData[0]['firstName']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo "Email"; ?></strong></td>
    <td>
      <div class="inputbox">
		<span class="bgleft"></span>
 <input name="email" type="text" id="lastName" value="<?php echo $customerData[0]['email']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo 'Current Balance'; ?></strong></td>
    <td>
     <div class="inputbox">
		<span class="bgleft"></span>
  <input name="credits" type="text" id="credits" value="<?php echo $customerData[0]['card_balance']; ?>" class="textbox" readonly="readonly" />
	   <span class="bgright"></span>
	   </div>
  </td>
   <tr>
    <td class="tdText"><strong><?php echo 'Del Balance'; ?></strong></td>
    <td>
        <div class="inputbox">
		<span class="bgleft"></span>
<input name="delcredit" type="text" id="delcredit" value="" class="textbox" />
	   <span class="bgright"></span>
	   </div>
    </td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>
	<input type="hidden" name="customer_id" value="<?php echo $customerData[0]['customer_id']; ?>" />
	<input name="Submit" type="submit" class="submit" value=" Del Balance" />	</td>
  </tr>
</table>
  </form>
  <?php 
}else if ($_GET["creport"]>0) {
	require_once ("includes/currencyVars.inc.php");
	?>
     <p class="copyText"><?php echo $pagination; ?></p>
    <table  border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
 
    <tr>
    <td nowrap="nowrap" align="center" class="tdTitle">Transaction Id</td>
    <td nowrap="nowrap" align="center" class="tdTitle">Date</td>
    <td nowrap="nowrap" align="center" class="tdTitle">IMEI</td>
    <td nowrap="nowrap" align="center" class="tdTitle">Notes</td>
    <td nowrap="nowrap" align="center" class="tdTitle">Dr</td>
    <td nowrap="nowrap" align="center" class="tdTitle">Cr</td>
    <td nowrap="nowrap" align="center" class="tdTitle">Balance</td>
    </tr>
    <?php
	if ($customerData) { 
	for ($i=0; $i<count($customerData); $i++) {		
		$cellColor = cellColor($i);
	?>
        <tr>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['trans_id']; ?></td>
    <td align="center"  class="<?php echo $cellColor; ?> tdText"><?php echo formatTime($customerData[$i]['date']);?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['imei'];?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['notes'];?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php if($customerData[$i]['dr'] > 0) echo priceFormat(number_format((float)$customerData[$i]['dr'], 2, '.', ''), true);?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php if($customerData[$i]['cr'] > 0) echo priceFormat(number_format((float)$customerData[$i]['cr'], 2, '.', ''), true);?></td>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?php echo priceFormat(number_format((float)$customerData[$i]['balance'], 2, '.', ''), true);?></td>
    </tr>
    <?php
	}
	}else 
	{ ?>
   <tr>
    <td colspan="8" class="tdText"><?php echo "No such record exist in the database."; ?></td>
  </tr>
<?php
  } 
?>
    </table>
    <p class="copyText"><?php echo $pagination; ?></p>
    <?php
}
?>