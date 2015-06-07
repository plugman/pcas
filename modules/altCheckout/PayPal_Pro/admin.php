<?php 
if (!defined('CC_INI_SET')) die("Access Denied");
  if(strstr($orderSum['0']['gateway'], "PayPal Website Payments Pro")) {
  
  	$lang = getLang("admin".CC_DS."admin_order_transactions.inc.php");

	$query = sprintf("SELECT * FROM %sImeiUnlock_transactions WHERE order_id = %s AND `status` = 'SUCCESS' ORDER BY time ASC", $glob['dbprefix'], $db->mySQLsafe($_GET['edit']));
	$results = $db->select($query);
  
  $thisPage = currentPage(array("PayPal-Pro"=>0, "form"=>0));
  ?><p class="pageTitle">PayPal Website Payments Pro Functionality</p>
  <p>The functions below are specifically for orders purchased with PayPal Express Checkout without the need to login to your PayPal account.</p>
<?php
function doVoidForm ($authorizationID, $id, $TENDER) {
	
	global $thisPage;
	
	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doVoidAuth".$id."#ppresult' id='doVoidForm".$id."' method='post' enctype='multipart/form-data'>
				<strong>Void Authorization ID: `".$authorizationID."`</strong>
				<p>
				  <strong>Message to customer:</strong><br />
				  <textarea name='note' cols='35' rows='3' class='textbox'>Please visit again soon.</textarea>
				</p>
				  <input type='hidden' name='TENDER' value='".$TENDER."' />
				  <input type='hidden' name='authorization_id' value='".$authorizationID."' />
				  <input type='hidden' name='id' value='".$id."' />
				  <input type='submit' name='order_void' class='submit' value='Void Now' />
				</form>";
	
	return $htmlOut;
}


function doCaptureForm ($authorizationID, $exampleAmount, $id, $TENDER, $remainder) {

	global $thisPage,$module;
	
	$maxAmount = sprintf("%.2f",$exampleAmount - $remainder);


	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doCapture".$id."#ppresult' id='doCaptureForm".$id."' method='post' enctype='multipart/form-data'>
	<p><strong>Capture all or part of Authorization ID  `".$authorizationID."`</strong></p>
	<strong>Final Capture:</strong><select name='CompleteCodeType' class='textbox'>
					<option value='NotComplete'>No</option>
					<option value='Complete'>Yes (If selected no further captures can be made)</option>
     			</select>
				<p><strong>Capture Amount:</strong>
			<input name='amount' type='text' class='textbox' id='amount' onclick='this.value=\"\"' value='e.g. &quot;".$maxAmount."&quot;' size='10' /></p>";
			
			if($remainder>0) {
				$htmlOut .= "(Max: ".$maxAmount.") ".sprintf("%.2f",$remainder)." has already been captured from this transaction.";
			}
			
			$htmlOut .= "<p><strong>Message to customer:</strong><br />
			<textarea name='note' cols='35' rows='3' class='textbox'>Many thanks for your order.
			</textarea></p>
			<input type='submit' name='order_capture' class='submit' value='Capture Now' />
			<input type='hidden' name='authorizationID' value='".$authorizationID."' />
			<input type='hidden' name='TENDER' value='".$TENDER."' />
			<input type='hidden' name='id' value='".$id."' />
		  </form>";
	
	return $htmlOut;
}

function doReAuthForm ($originalAuthorizationID, $exampleAmount,$id, $TENDER) {
	global $thisPage;
	
	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doReAuth".$id."#ppresult' id='doReAuthForm".$id."' method='post' enctype='multipart/form-data'>
			<p><strong>Reauthorize ID  `".$originalAuthorizationID."`</strong></p>
			<strong>Reauthorization Amount:</strong> <input name='amount' type='text' class='textbox' id='amount' onclick='this.value=\"\"' value='e.g. &quot;".$exampleAmount."&quot;' size='10' />
			<input type='hidden' name='authorizationID' value='".$originalAuthorizationID."' />
			<input type='hidden' name='TENDER' value='".$TENDER."' />
			<p><input type='submit' name='order_auth' class='submit' value='ReAuthorize Now' /></p>
			</form>";
	
	return $htmlOut;
}

function doAuthForm ($originalAuthorizationID, $exampleAmount,$id, $TENDER) {
	global $thisPage;
	
	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doAuth".$id."#ppresult' id='doAuthForm".$id."' method='post' enctype='multipart/form-data'>
			<p><strong>Authorize ID  `".$originalAuthorizationID."`</strong></p>
			<strong>Authorization Amount:</strong> <input name='amount' type='text' class='textbox' id='amount' onclick='this.value=\"\"' value='e.g. &quot;".$exampleAmount."&quot;' size='10' />
			<input type='hidden' name='authorizationID' value='".$originalAuthorizationID."' />
			<input type='hidden' name='TENDER' value='".$TENDER."' />
			<p><input type='submit' name='order_auth' class='submit' value='Authorize Now' /></p>
			</form>";
	
	return $htmlOut;
}

function doRefundForm ($transactionId,$exampleAmount,$id, $TENDER) {
	global $thisPage;
	
	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doRefund".$id."#ppresult' id='doRefundForm".$id."' method='post' enctype='multipart/form-data'>
		  <p><strong>Refund all or partial amount of transaction ID  `".$transactionId."`</strong></p>
		  <strong>Refund Amount:</strong><br />
		  <input name='amount' type='text' class='textbox' id='amount' onclick='this.value=\"\"' value='e.g. &quot;".$exampleAmount."&quot;' size='10' />
		  <p><strong>Message to customer</strong>:<br />
			<textarea name='note' cols='35' rows='3' class='textbox'>Payment for order number ".$_GET['edit']." has been refunded. </textarea></p>
			<input type='hidden' name='transactionId' value='".$transactionId."' />
			<input type='hidden' name='TENDER' value='".$TENDER."' />
			<input type='hidden' name='id' value='".$id."' />
			<input type='submit' name='order_refund' class='submit' value='Refund Now' />
    </form>";

	return $htmlOut;
}

function doFMF ($transactionID, $id) {
	
	global $thisPage;
	
	$htmlOut = "<form action='".$thisPage."&amp;PayPal-Pro=doFMF".$id."#ppresult' id='doFMF".$id."' method='post' enctype='multipart/form-data'>
				<strong>Transaction ID: `".$transactionID."`</strong>
				<p>	
					Accept Payment: <input type='radio' name='action' value='Accept' /><br />
					Deny Payment:  <input type='radio' name='action' value='Deny' />
				</p>
				  <input type='hidden' name='transactionID' value='".$transactionID."' />
				  <input type='hidden' name='id' value='".$id."' />
				  <input type='submit' name='doFMF' class='submit' value='Update' />
				</form>";
	
	return $htmlOut;
}
?>
<?php if (isset($ppMsg)) echo $ppMsg; ?>
<table width="100%" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
	<td align="center" class="tdTitle" width="50%">Details</td>
	<td align="center" class="tdTitle" width="50%">Action</td>
  </tr>
<?php
	if ($results==true) {
		for ($i=0; $i<count($results); $i++) {
			$cellColor = cellColor($i);
?>
	<tr>
	  <td class="<?php echo $cellColor; ?> copyText" valign="top" width="50%">
	  	<strong>Time:</strong> <?php echo formatTime($results[$i]['time']); ?><br />
		<strong>Action:</strong> <?php echo $results[$i]['gateway']; ?><br />
		<strong>Transaction Id:</strong> <?php echo $results[$i]['trans_id']; ?><br />
		<?php if($results[$i]['amount']>0) { ?><strong>Amount:</strong> <?php echo priceFormat($results[$i]['amount'],true); ?><br /><?php } ?>
		<strong>Notes:</strong> <?php echo $results[$i]['notes']; ?></td>
	  <td class="<?php echo $cellColor; ?> copyText" width="50%">
	  <?php
	  
	  $action = false;
	  
	  $links = "<div style='text-align: center;'>";
	  
	  switch ($results[$i]['gateway']) {
		
		case "PayPal Website Payments Pro (Authorization)":
		case "PayPal Website Payments Pro (Order)":
		
		if($results[$i]['amount'] > $results[$i]['remainder']) {
			
			
			
			$links .= "<a href='".$thisPage."&amp;form=doCaptureForm".$results[$i]['id']."#doCaptureForm".$results[$i]['id']."' class='txtLink'>Capture</a> | ";
			$action = true;
	
			// reauth only possible if the initial auth is three days old or more
			$threeDaysAgo = time()-259200;
			
			if($results[$i]['time']<$threeDaysAgo) {
				$links .= "<a href='".$thisPage."&amp;form=doReAuthForm".$results[$i]['id']."#doReAuthForm".$results[$i]['id']."' class='txtLink'>ReAuthorize</a> | ";
			}
			
			if($results[$i]['gateway']=="PayPal Website Payments Pro (Order)") {
				$links .= "<a href='".$thisPage."&amp;form=doAuthForm".$results[$i]['id']."#doAuthForm".$results[$i]['id']."' class='txtLink'>Authorize</a> | ";
			}
			
			$links .= "<a href='".$thisPage."&amp;form=doVoidForm".$results[$i]['id']."#doVoidForm".$results[$i]['id']."' class='txtLink'>Void</a> | ";
			
			echo substr($links,0,-2)."</div>";
			
			
			if($_GET['form']=="doCaptureForm".$results[$i]['id'] && !isset($_POST['order_capture'])) {
				echo "<hr size='1' />";
				echo doCaptureForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra'], $results[$i]['remainder']);
			} elseif($_GET['form']=="doVoidForm".$results[$i]['id'] && !isset($_POST['order_void'])) {
				echo "<hr size='1' />";
				echo doVoidForm ($results[$i]['trans_id'], $results[$i]['id'], $results[$i]['extra']);
			} elseif($_GET['form']=="doReAuthForm".$results[$i]['id'] && !isset($_POST['order_auth'])) {
				echo "<hr size='1'/>";
				echo doReAuthForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra']);
			} elseif($_GET['form']=="doAuthForm".$results[$i]['id'] && !isset($_POST['order_auth'])) {
				echo "<hr size='1'/>";
				echo doAuthForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra']);
			}
			
		}
		
		break;
		case "PayPal Website Payments Pro (Sale)":
		case "PayPal Website Payments Pro (DoCapture)":
		case "PayPal Website Payments Pro (FMF Accept)":
		
		if($results[$i]['amount'] > $results[$i]['remainder']) {
			
			echo " <div style='text-align: center;'><a href='".$thisPage."&amp;form=doRefundForm".$results[$i]['id']."#doRefundForm".$results[$i]['id']."' class='txtLink'>Refund</a></div> ";
			
			if($_GET['form']=="doRefundForm".$results[$i]['id'] && !isset($_POST['order_refund'])) {
				echo "<hr />";
				echo doRefundForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra']);
			}
			
		
			$action = true;
		}
		break;
		/*
		case "PayPal Website Payments Pro (DoRefund)":
		echo "&nbsp;";
		break;
		*/
		case "PayPal Website Payments Pro (DoAuthorization)":
		$action = true;
		$links .= "<a href='".$thisPage."&amp;form=doCaptureForm".$results[$i]['id']."#doCaptureForm".$results[$i]['id']."' class='txtLink'>Capture</a> | ";
		$links .= "<a href='".$thisPage."&amp;form=doReAuthForm".$results[$i]['id']."#doReAuthForm".$results[$i]['id']."' class='txtLink'>ReAuthorize</a> | ";
		$links .= "<a href='".$thisPage."&amp;form=doVoidForm".$results[$i]['id']."#doVoidForm".$results[$i]['id']."' class='txtLink'>Void</a> | ";
		
		echo substr($links,0,-2)."</div>";
		
		
		if($_GET['form']=="doCaptureForm".$results[$i]['id'] && !isset($_POST['order_capture'])) {
				echo "<hr size='1' />";
				echo doCaptureForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra'], $results[$i]['remainder']);
			} elseif($_GET['form']=="doVoidForm".$results[$i]['id'] && !isset($_POST['order_void'])) {
				echo "<hr size='1' />";
				echo doVoidForm ($results[$i]['trans_id'], $results[$i]['id'], $results[$i]['extra']);
			} elseif($_GET['form']=="doReAuthForm".$results[$i]['id'] && !isset($_POST['order_auth'])) {
				echo "<hr size='1'/>";
				echo doReAuthForm($results[$i]['trans_id'],$results[$i]['amount'],$results[$i]['id'], $results[$i]['extra']);
			} 
		
		break;
		case "PayPal Website Payments Pro (FMF Review)":
			$action = true;
			$links .= "<a href='".$thisPage."&amp;form=doFMF".$results[$i]['id']."#doFMF".$results[$i]['id']."' class='txtLink'>Accept / Deny Payment</a>";
			if($_GET['form']=="doFMF".$results[$i]['id'] && !isset($_POST['doFMF'])) {
				echo doFMF($results[$i]['trans_id'], $results[$i]['id']);
			}
		break;
		
		/*
		case "PayPal Website Payments Pro (DoVoid)":
		echo "&nbsp;";
		break;
		*/
	  }
	  
	  
	  
	  
	  if($action==false) echo "<div style='text-align: center;'>Complete</div>";
	  ?>	  </td>
	</tr>
<?php
		}
	}
}
?>