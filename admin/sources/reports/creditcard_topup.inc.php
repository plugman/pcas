<?php

if(!defined('CC_INI_SET')){ die("Access Denied"); }


//$lang = getLang("orders.inc.php");
$lang = getLang("admin".CC_DS."admin_customers.inc.php");
permission("reports","read",$halt=TRUE);
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
$rowsPerPage = 25;
if(isset($_POST['transactionId'])) 
{
	$record["transactionId"] = $db->mySQLSafe($_POST['transactionId']);
	$record["amount"] = $db->mySQLSafe($_POST['amount']);  
	$record["gateway"] = $db->mySQLSafe($_POST['gateway']);  
	$record["paypalfee"] = $db->mySQLSafe($_POST['paypalfee']); 
	$record["status"] = $db->mySQLSafe($_POST['status']); 
	$record["date_topped"] = $db->mySQLSafe(time()); 
	$record["notes"] = $db->mySQLSafe($_POST['notes']);
	
	if($_POST['transactionId']>0){
		
		$where = "transactionId=".$db->mySQLSafe($_POST['transactionId']);
		$update = $db->update($glob['dbprefix']."tbl_topup_payment_transactions", $record, $where);
		
		if($update == TRUE){
			$msg = "<p class='infoText'>".$lang['admin']['customers_update_success']."</p>";
		} else {
			$msg = "<p class='warnText'>".$lang['admin']['customers_update_fail']."</p>";
		}
	
	} 
}


	if (isset($_GET['orderCol']) && isset($_GET['orderDir'])) 
	{
		$orderBy =  " ORDER BY ".$_GET['orderCol']." ".$_GET['orderDir']; 
		
	} else {
		$orderBy = " ORDER BY P.date_topped DESC";
	}


		/////Searching Createria Implemented Here\\\\\\\\\\\\\
		if((isset($_GET['searchStr']) && $_GET['searchStr'] != "")  || (isset($_GET['daterange']) && $_GET['daterange']>0)) 
		{
			if(trim($_GET['searchStr']) != ''){
			$searchwords = split ( "[ ,]", trim($_GET['searchStr'])); 
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
					if($searchArray[$i] != '' && $searchArray[$i] != "" && !empty($searchArray[$i]))
					$like .= "(C.firstName LIKE '%".$searchArray[$i]."%' OR P.status LIKE '%".$searchArray[$i]."%' OR P.notes LIKE '%".$searchArray[$i]."%' OR P.gateway LIKE '%".$searchArray[$i]."%' OR P.transactionId LIKE '%".$searchArray[$i]."%' OR C.lastName LIKE '%".$searchArray[$i]."%' OR  P.amount LIKE '%".$searchArray[$i]."%') OR ";
				} 
				else 
				{
					$like = substr($like,0,strlen($like)-3);
					$like .= $ucSearchTerm;
				}  
		
			}
		 }
			
			$like = substr($like,0,strlen($like)-3);
			
			$where="";
			
			if(isset($_GET['daterange']) && $_GET['daterange']>0) {
			$dateRange = str_replace(" ", "", $_GET["daterange"]);
			$arrDateRange = explode("-", $dateRange);
			if(count($arrDateRange)==1){
				$date1 = strtotime(dateFormat($arrDateRange[0], "/"));
				$date2 = strtotime(dateFormat($arrDateRange[0], "/"))+round((23.999 * 60 * 60));			
				}else{
				$date1 = strtotime(dateFormat($arrDateRange[0], "/"));
				$date2 = strtotime(dateFormat($arrDateRange[1], "/"))+round((23.999 * 60 * 60));			
				}
			 $where.= " P.date_topped BETWEEN ".$date1 . " AND " . $date2;
			}
			if(strlen($like)>0 && strlen($where)>0){
				$like = $like." AND (".$where.")";
			}
			else if(strlen($like) > 0 && $where ==""){
				$like = $like;
			}
			else if(strlen($where)>0 && $like==""){
				$like = $where;
				}
			if($like != '')	
			$like = " AND ".$like;
			else
			$like = "";
			
			$query = "SELECT  P.id, P.transactionId, P.customerId, P.amount, P.date_topped, P.gateway, P.status, P.carrier_id ,
					P.notes, C.firstName, C.lastName
					FROM    
					ImeiUnlock_customer as C INNER JOIN tbl_topup_payment_transactions as P ON (C.customer_id = P.customerId) WHERE gateway != \"Scratch Card\" ".$like;

	}else if(isset($_GET['edit']) && $_GET['edit']>0)
	{
		 
		$query = sprintf("SELECT * FROM ".$glob['dbprefix']."tbl_topup_payment_transactions WHERE transactionId = %s", $db->mySQLSafe($_GET['edit'])); 
						
		// $query = "SELECT * FROM tbl_scratchcards WHERE carrier_id = ".$_GET['carrierID'];
						
	} 
	else if ($_GET['mode']!=="new") {
		$query 	 	  = "SELECT  P.id, P.transactionId, P.customerId, P.amount, P.paypalfee, P.date_topped, P.gateway, P.status, P.carrier_id ,
						P.notes, C.firstName, C.lastName
						FROM    
						ImeiUnlock_customer as C INNER JOIN tbl_topup_payment_transactions as P ON (C.customer_id = P.customerId)
						WHERE gateway != \"Scratch Card\"";
	}
	
	if(isset($orderBy))
	$query = $query." ".$orderBy;

	// query database
	//echo $query;
	//die();
	if (isset($query)) 
	{
		$page = (is_numeric($_GET['page'])) ? $_GET['page'] : 0;
		$customerData = $db->select($query, $rowsPerPage, $page);
		$numrows = $db->numrows($query);
		$pagination = paginate($numrows, $rowsPerPage, $page, "page");
	}
	
////////////////////////////////////////Below code is for reporting\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		$customerData_report = $db->select($query);
		$numrows2 			 = $db->numrows($query);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap' class="pageTitle">Credit Card Topup Logs</td>
    <td align="right" valign="middle">
        <!-- <?
            if($numrows2 > 0)
            {
				$query = str_replace('%',"~",$query);
         ?>		
				<a class="txtLink" href="admin/sources/reports/export_to_excel.php?file=creditcardtopup&qry=<?=urlencode($query)?>" style="cursor:pointer;" target="_blank">Export to CSV</a>
         <?
            }
         ?>-->
     </td>
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
<input type="hidden" name="_g" value="reports/creditcard_topup" />
<table width="100%" border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td align="right">
    <strong> Sort By:</strong>
    </td>
    <td>
    <select name="orderCol" class="textbox">
      <option value="firstName" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="firstName") echo "selected='selected'";?>>Customer</option>
	  <option value="transactionId" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="transactionId") echo "selected='selected'";?>>Transaction ID</option>
	  <option value="status" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="status") echo "selected='selected'";?>>Status</option>
	  <option value="gateway" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="gateway") echo "selected='selected'";?>>Gateway</option>
	  <option value="amount" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="amount") echo "selected='selected'";?>>Price</option>
	  <option value="date_topped" <?php if(isset($_GET['orderCol']) && $_GET['orderCol']=="date_topped") echo "selected='selected'";?>>Date (Topped)</option>
    </select></td>
    <td align="right"><strong>In</strong>
    </td>
    <td>
    <select name="orderDir" class="textbox">
      <option value="ASC" <?php if(isset($_GET['orderDir']) && $_GET['orderDir']=="ASC") echo "selected='selected'";?>>Ascending</option>
      <option value="DESC" <?php if(isset($_GET['orderDir']) && $_GET['orderDir']=="DESC") echo "selected='selected'";?>>Descending</option>
    </select></td>
  </tr>
  <tr>
   
    <td align="right">
	<strong><?php echo $lang['admin']['customers_search_term']; ?></strong>
    </td> 
    <td>
     <input type="text" name="searchStr" id="searchStr"class="textbox" value="<?php if(isset($_GET['searchStr'])) echo $_GET['searchStr']; ?>" onchange="javascript: return checkkey()"  />
    </td>
     <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  <td></td>
  <td colspan="3">
   <input name="Submit" type="submit" class="submit" value="Filter" />
    <input name="Button" type="button" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=reports/creditcard_topup');return document.returnValue" value="<?php echo $lang['admin']['customers_reset']; ?>" class="submit" />
  </td>
  </tr>
</table>

	
 	
</form>

<p class="copyText"><?php echo $pagination; ?></p>
<table width="100%" border="1" cellspacing="1" cellpadding="3" class="mainTable mainTable4">
  <tr align="center">
    <td  nowrap="nowrap" class="tdTitle">Customer Name</td>
    <td  nowrap="nowrap" class="tdTitle">Transaction ID</td>
	<td  nowrap="nowrap" class="tdTitle">Gateway</td>
    <td  nowrap="nowrap" class="tdTitle">Amount</td>
     <td  nowrap="nowrap" class="tdTitle">paypal fee</td>
	<td  nowrap="nowrap" class="tdTitle">Date(Topped)</td>
	<?php /*?><td align="left" nowrap="nowrap" class="tdTitle">Notes</td><?php */?>
    <!--<td  nowrap="nowrap" class="tdTitle">Action</td>-->
  </tr>
<?php 
if ($customerData) { 
	for ($i=0; $i<count($customerData); $i++) {		
		$cellColor = cellColor($i);
?>
  <tr align="center">
    <td align="center" class="<?php echo $cellColor; ?> tdText"><?=$customerData[$i]['firstName']." ".$customerData[$i]['lastName']; ?>
    </td>
    <td  class="<?php echo $cellColor; ?> tdText"><?=$customerData[$i]['transactionId']; //$j = $i+1; echo $j  ?>
    </td>
    <td class="<?php echo $cellColor; ?> tdText"><?=$customerData[$i]['gateway'];?></td>
    <td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['amount']; ?></td>
    <td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['paypalfee']; ?></td>
	<td class="<?php echo $cellColor; ?>"><?php echo formatTime($customerData[$i]['date_topped']); ?></td>
    <?php /*?><td class="<?php echo $cellColor; ?> tdText"><?php echo $customerData[$i]['notes']; ?></td><?php */?>
    <?php
	$state = $customerData[$i]['status'];	
	?>
    <!--<td align="center" class="<?php echo $cellColor; ?>">
    <?php
	if($customerData[$i]['status'] != 1){ 
    $permission= true;
	}
	else{
		$permission= false;
		}
	?>
	<a <?php if($permission==TRUE){?>href="<?php echo $glob['adminFile']; ?>?_g=reports%2Fcreditcard_topup&amp;edit=<?php echo $customerData[$i]['transactionId']; ?>" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['edit']; ?></a>	</td>-->
  </tr>
<?php 
  		} // end loop  
	} 
	else 
	{ ?>
   <tr>
    <td colspan="7" class="tdText"><?php echo "No such record exist in the database."; ?></td>
  </tr>
<?php
  } 
?>
</table>
	<p class="copyText"><?php echo $pagination; ?></p>
<?php 
} else if ($_GET["mode"]=="new" || $_GET["edit"]>0) {

?>
<form name="editCustomer" method="post" action="<?php echo $glob['adminFile']; ?>?_g=reports%2Fcreditcard_topup">
<table  border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php if($_GET["mode"]=="new") { echo $lang['admin']['customers_add_below']; } else { echo $lang['admin']['customers_edit_below'];; } ?></td>
    </tr>
    <tr>
      <td valign="top" class="tdText"><strong><?php echo $lang['admin']['orders_status']; ?></strong></td>
      <td valign="top" class="tdText"><select name="status" class="dropDown">
          <?php
		for ($i=1; $i<=3; $i++) {
		?>
          <option value="<?php echo $i; ?>" <?php if($customerData[0]['status']==$i) { echo "selected='selected'"; } ?>><?php echo $lang['admin']['orderState_'.$i]; ?></option>
          <?php 
		} 
		?>
        </select>      </td>
      
    </tr>
      
  <tr>
    <td width="175" class="tdText"><strong>Transaction ID</strong></td>
    <td width="175">
      <input name="transactionId" type="text" id="transactionId" value="<?php echo $customerData[0]['transactionId']; ?>" class="textbox" />    </td>
  </tr>
   <tr>
    <td width="175" class="tdText"><strong>Gateway</strong></td>
    <td width="175">
      <input name="gateway" type="text" id="gateway" value="<?php echo $customerData[0]['gateway']; ?>" class="textbox" />    </td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong>Amount</strong></td>
    <td width="175">
      <input name="amount" type="text" id="amount" value="<?php echo $customerData[0]['amount']; ?>" class="textbox" />    </td>
  </tr>
   <tr>
    <td width="175" class="tdText"><strong>paypal fee</strong></td>
    <td width="175">
      <input name="paypalfee" type="text" id="paypalfee" value="<?php echo $customerData[0]['paypalfee']; ?>" class="textbox" />    </td>
  </tr>
  <tr>
    <td width="175" class="tdText"><strong>Date(Topped)</strong></td>
    <td width="175">
      <input name="date_topped" type="text" id="date_topped" value="<?php echo formatTime($customerData[$i]['date_topped']); ?>" class="textbox" />    </td>
  </tr>
   <tr>
    <td width="175" class="tdText"><strong>Status</strong></td>
    <td width="175">
      <input name="notes" type="text" id="notes" value="<?php echo $customerData[0]['notes']; ?>" class="textbox" />    </td>
  </tr>
  
  
  <tr>
    <td width="175">&nbsp;</td>
    <td width="175">
	<input type="hidden" name="transactionId" value="<?php echo $customerData[0]['transactionId']; ?>" />
	<?php 
	if($customerData[0]['transactionId']>0) { 
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
} 
?>

<script language="javascript" type="text/javascript">
 function checkkey()
 {
	if(document.getElementById("searchStr").value!=""){
	// allow ONLY alphanumeric keys, no symbols or punctuation
	// this can be altered for any "checkOK" string you desire
	var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_- ";
	var checkStr = document.getElementById("searchStr").value;
	var allValid = true;
	for (i = 0;  i < checkStr.length;  i++)
		{
			ch = checkStr.charAt(i);
			for (j = 0;  j < checkOK.length;  j++)
			if (ch == checkOK.charAt(j))
			break;
			if (j == checkOK.length)
			{
			allValid = false;
			break;
			}
		}
	if (!allValid)
	{
		alert("Please enter only letter and numeric characters in this field.");
		document.getElementById("searchStr").value="";
		document.getElementById("searchStr").focus();		
		return (false);
	}
	return true;
	}
	else{
		return true;
	}

}

</script>