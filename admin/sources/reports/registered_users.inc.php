<?php
/*
+--------------------------------------------------------------------------
|   Cub3Cart 4
|   ========================================
|   
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.d.e.v.e.l.l.i.o.n.com
|	
|   ========================================
|   Web: http://www.c.u.b.e.c.a.r.t.com
|   Email: info (at) c.u.b.e.c.a.r.t (dot) com
|	  License Type: C.u.b.e.C.a.r.t is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.c.u.b.e.c.a.r.t.com/site/faq/license.php
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Manage Customers Accounts
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_customers.inc.php");

permission("customers","read",$halt=TRUE);

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
elseif(isset($_POST['customer_id'])) 
{

	$record["title"] = $db->mySQLSafe($_POST['title']);		
	$record["firstName"] = $db->mySQLSafe($_POST['firstName']);	
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
	
	if(empty($_POST['firstName']) && empty($_POST['lastName']) && empty($_POST['add_1']) && empty($_POST['town']) && empty($_POST['county']) && empty($_POST['country']) && empty($_POST['phone']) && empty($_POST['postcode']))
	{
		$record["type"] = '0';
	}
	else
	{
		$record["type"] = '1';
	}

	if( (!empty($_POST['password']) && !empty($_POST['password_conf']) && $_POST['password']==$_POST['password_conf']) )
	{
		$record["password"] = $db->mySQLSafe(md5($_POST['password']));
	}
	
	
	if($_POST['customer_id']>0)
	{
		
		$where = "customer_id=".$db->mySQLSafe($_POST['customer_id']);
		$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where);
		
		if($update == TRUE)
		{
			$msg = "<p class='infoText'>".$lang['admin']['customers_update_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>".$lang['admin']['customers_update_fail']."</p>";
		}
	
	}
	else
	{
		$record["regTime"] = $db->mySQLSafe(time());
		
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_customer", $record);
		
		if($insert == TRUE)
		{
			$msg = "<p class='infoText'>".$lang['admin']['customers_insert_success']."</p>";
		} 
		else 
		{
			$msg = "<p class='warnText'>".$lang['admin']['customers_insert_fail']."</p>";
		}
	
	}

}

	if(isset($_GET['edit']) && $_GET['edit']>0)
	{
		
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id = %s", $db->mySQLSafe($_GET['edit'])); 
	
	} 
	elseif(isset($_GET['searchStr'])) 
	{
		
		if(is_int($_GET['searchStr']))
		{
			
			$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE customer_id  = ".$db->mySQLSafe($_GET['searchStr']);
		}
		else
		{
			
			$searchwords = split ( "[ ,]", $_GET['searchStr']); 
			  
			foreach($searchwords as $word)
			{
				$searchArray[]=$word;
			}
		
			$noKeys = count($searchArray);
			
			$like = "";
			
			for ($i=0; $i<$noKeys;$i++) 
			{
				
				$ucSearchTerm = strtoupper($searchArray[$i]);
				if(($ucSearchTerm!=="AND")AND($ucSearchTerm!=="OR"))
				{
					
					$like .= "(email LIKE '%".$searchArray[$i]."%' OR title LIKE '%".$searchArray[$i]."%' OR  firstName LIKE '%".$searchArray[$i]."%' OR lastName LIKE '%".$searchArray[$i]."%' OR add_1 LIKE '%".$searchArray[$i]."%' OR  add_2 LIKE '%".$searchArray[$i]."%' OR town LIKE '%".$searchArray[$i]."%' OR county LIKE '%".$searchArray[$i]."%' OR  postcode LIKE '%".$searchArray[$i]."%' OR country LIKE '%".$searchArray[$i]."%' OR phone LIKE '%".$searchArray[$i]."%' OR  ipAddress LIKE '%".$searchArray[$i]."%') OR ";
					
				} 
				else 
				{
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
    <td nowrap='nowrap' class="pageTitle">List of all Users Registered</td>
  </tr>
</table>

<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}

if(!isset($_GET['mode']) && !isset($_GET['edit']))
{
?>

<form name="filter" method="get" action="<?php echo $glob['adminFile']; ?>">
<input type="hidden" name="_g" value="reports/registered_users" />
 	<p align="right" class="copyText">
	<?php echo $lang['admin']['customers_search_term']; ?>
    <input type="text" name="searchStr" class="textbox" value="<?php if(isset($_GET['searchStr'])) echo $_GET['searchStr']; ?>" />    
    <input name="Submit" type="submit" class="submit" value="Filter" />
    <input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=reports/registered_users');return document.returnValue" value="<?php echo $lang['admin']['customers_reset']; ?>" class="submit" />
	</p>
</form>


<p class="copyText"><?php echo $pagination; ?></p>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td align="center" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_type']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_name']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_email']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_invoice_add']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_phone']; ?></td>
    <td align="left" nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_reg_ip']; ?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['customers_no_orders']; ?></td>
  </tr>
<?php 
if ($customerData) { 
	for ($i=0; $i<count($customerData); $i++) {
		
	#	$orderCount	= sprintf("SELECT COUNT(cart_order_id) AS noOrders FROM %sImeiUnlock_order_sum WHERE customer_id = %d", $glob['dbprefix'], $customerData[$i]['customer_id']);
	#	$orderResult= $db->select($orderCount);
		
		$cellColor = cellColor($i);
?>
  <tr>
    <td align="center" class="<?php echo $cellColor; ?> tdText"><img src="<?php echo $glob['adminFolder']; ?>/images/type<?php echo $customerData[$i]['type']; ?>.gif" alt="<?php echo $lang['admin']['customers_type'.$customerData[$i]['type']]; ?>" title="<?php echo $lang['admin']['customers_type'.$customerData[$i]['type']]; ?>" /></td>
    <td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['title']." ".$customerData[$i]['firstName']." ".$customerData[$i]['lastName']; ?></td>
    <td class="<?php echo $cellColor; ?>"><a href="mailto:<?php echo $customerData[$i]['email']; ?>" class="txtLink"><?php echo $customerData[$i]['email']; ?></a></td>
    <td class="<?php echo $cellColor; ?> tdText"><?php 
	if(!empty($customerData[$i]['companyName'])) echo $customerData[$i]['companyName'].", ";
	if(!empty($customerData[$i]['add_1'])) echo $customerData[$i]['add_1'].", "; 
	if(!empty($customerData[$i]['add_2'])) echo $customerData[$i]['add_2'].", "; 
	if(!empty($customerData[$i]['town'])) echo $customerData[$i]['town'].", ";
	if(!empty($customerData[$i]['county'])) echo $customerData[$i]['county'].", ";
	if(!empty($customerData[$i]['postcode'])) echo $customerData[$i]['postcode'].", "; 	
	if(!empty($customerData[$i]['country'])) echo getCountryFormat($customerData[$i]['country'],"id","printable_name");
	?>	</td>
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
<p class="copyText"><?php echo $pagination; ?></p>
<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"]>0) {

?>
<form name="editCustomer" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/index">
<table  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php if($_GET["mode"]=="new") { echo $lang['admin']['customers_add_below']; } else { echo $lang['admin']['customers_edit_below'];; } ?></td>
    </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_title']; ?></strong></td>
    <td width="175">
      <input name="title" type="text" id="title" value="<?php echo $customerData[0]['title']; ?>" class="textbox" />    </td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_first_name']; ?></strong></td>
    <td width="175"><input name="firstName" type="text" id="firstName" value="<?php echo $customerData[0]['firstName']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_last_name']; ?></strong></td>
    <td width="175"><input name="lastName" type="text" id="lastName" value="<?php echo $customerData[0]['lastName']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_email2']; ?></strong></td>
    <td width="175"><input name="email" type="text" id="email" value="<?php echo $customerData[0]['email']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_company_name']; ?></strong></td>
    <td width="175"><input name="companyName" type="text" id="companyName" value="<?php echo $customerData[0]['companyName']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_address']; ?></strong></td>
    <td width="175"><input name="add_1" type="text" id="add_1" value="<?php echo $customerData[0]['add_1']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175">&nbsp;</td>
    <td width="175"><input name="add_2" type="text" id="add_2" value="<?php echo $customerData[0]['add_2']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_town']; ?></strong></td>
    <td width="175"><input name="town" type="text" id="town" value="<?php echo $customerData[0]['town']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_county']; ?></strong></td>
    <td width="175"><input name="county" type="text" id="county" value="<?php echo $customerData[0]['county']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_postcode']; ?></strong></td>
    <td width="175"><input name="postcode" type="text" id="postcode" value="<?php echo $customerData[0]['postcode']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_country']; ?></strong></td>
    <td width="175">
	<?php 
	  $countries = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_iso_countries"); 
	  ?>
	
	<select name="country" id="country">
	<option value=""><?php echo $lang['admin_common']['na'];?></option>
	<?php
	for($i=0; $i<count($countries); $i++){
	?>
	<option value="<?php echo $countries[$i]['id']; ?>" <?php if($countries[$i]['id'] == $customerData[0]['country']) echo "selected='selected'"; ?>><?php echo $countries[$i]['printable_name']; ?></option>
	<?php } ?>
	</select>	</td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_phone2']; ?></strong></td>
    <td width="175"><input name="phone" type="text" id="phone" value="<?php echo $customerData[0]['phone']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong><?php echo $lang['admin']['customers_mobile']; ?></strong></td>
    <td width="175"><input name="mobile" type="text" id="mobile" value="<?php echo $customerData[0]['mobile']; ?>" class="textbox" /></td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_mail_list']; ?></strong></td>
    <td><select name="optIn1st" id="optIn1st">
      <option value="1" <?php if($customerData[0]['optIn1st']==1) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['yes'];?></option>
      <option value="0" <?php if($customerData[0]['optIn1st']==0) { echo "selected='selected'"; } ?>><?php echo $lang['admin_common']['no'];?></option>
    </select></td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_password']; ?></strong><br />
        <?php echo $lang['admin']['customers_password_leave']; ?></td>
    <td><input name="password" type="password" id="password" value="" class="textbox" /> </td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_password_conf']; ?></strong></td>
    <td><input name="password_conf" type="password" id="password_conf" value="" class="textbox" /></td>
  </tr>
  <tr>
    <td width="175">&nbsp;</td>
    <td width="175">
	<input type="hidden" name="customer_id" value="<?php echo $customerData[0]['customer_id']; ?>" />
	<input name="Submit" type="submit" class="submit" value="<?php if($_GET['mode']=='new') { echo $lang['admin']['customers_add_customer']; } else { echo $lang['admin']['customers_edit_customer']; } ?>" />	</td>
  </tr>
</table>
</form>
<?php 
} 
?>