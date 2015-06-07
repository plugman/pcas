<?php
/*
+--------------------------------------------------------------------------
|	send.inc.php
|   ========================================
|	Send Bulk Email
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_customers.inc.php");

permission("customers","write", true);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

// number of email recipients per page
$perPage = 20;

if($_POST['test']==0){
	$query = "SELECT email, firstName, lastName, htmlEmail FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE optIn1st = 1";
	$emailList = $db->select($query, $perPage, $_GET['page']);
}
?>

<div id="sending"  class="pageTitle">
<?php echo $lang['admin']['customers_sending']; ?> <img src="<?php echo $glob['adminFolder']; ?>/images/progress.gif" alt="" width="32" height="32" title="" /></div>
<div id="sent" class="pageTitle" style="visibility:hidden;"><?php echo $lang['admin']['customers_sending_complete']; ?></div>
<?php
// start email

require "classes".CC_DS."htmlMimeMail".CC_DS."htmlMimeMail.php";
		
$html		= stripslashes($_POST['FCKeditor']);
$subject	= stripslashes($_POST['subject']);
$fromName	= $_POST['fromName'];
$fromEmail	= $_POST['fromEmail'];
//$returnPath = $_POST['returnPath'];

$text		= $_POST['plain_text'];
$find		= array("'".$GLOBALS['rootRel'],"\"".$GLOBALS['rootRel']);
$replace	= array("'".$glob['storeURL']."/","\"".$glob['storeURL']."/");
$html 		= str_replace($find,$replace,$html);

if ($_POST['test']==1) {

	if ($_POST['format']=="user" || $_POST['format']=="html") {
	
		$mail = new htmlMimeMail();
		$mail->setSubject($subject." ".$lang['admin']['customers_send_html_sample']);	## ???
		$mail->setHeader('X-Mailer', 'ImeiUnlock Bulk Mailer');
		$mail->setFrom($fromName." <".$fromEmail.">");
		$mail->setReturnPath($fromName." <".$fromEmail.">");
	#	$mail->setText($text);
		$mail->setHtml($html);
		
		$result = $mail->send(array($_POST['testEmail']), $config['mailMethod']);
	}
	
	if ($_POST['format']=="user" || $_POST['format']=="text") {
	
		$mail = new htmlMimeMail();
		$mail->setSubject($subject." ".$lang['admin']['customers_send_text_sample']);
		$mail->setHeader('X-Mailer', 'ImeiUnlock Bulk Mailer');
		$mail->setFrom($fromName." <".$fromEmail.">");
		$mail->setReturnPath($_POST['testEmail']);
		$mail->setText($text);
		$result = $mail->send(array($_POST['testEmail']), $config['mailMethod']);
	}
			
	echo "<p class='copyText'><strong>".$lang['admin']['customers_recipient']."</strong> ".$_POST['testEmail']."</p>";
	
	?>
	<img src="<?php echo $glob['adminFolder']; ?>/images/progress.gif" alt="" width="1" height="1" title="" onload="showHideLayers('sending','','hide','sent','','show');" />
	<form method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/email&amp;action=send" enctype="multipart/form-data">
	<?php
	// recover post vars
	echo recoverPostVars($_POST,"FCKeditor");
	?>
	<input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin']['customers_prev_page']; ?>" />
	</form>
	<?php
} else {

	$i = 0;
	if (isset($_GET['startTime'])) $startTime = $_GET['startTime']; else $startTime = $_GET['startTime'] = time();
	if ($emailList == TRUE) {
		echo "<table border='0' cellspacing='0' cellpadding='3' class='mainTable'>";
		print "<tr><td class='tdTitle' colspan='3'>".$lang['admin']['customers_page']." ".($_GET['page']+1)."</td></tr>";
		
		for ($i=0; $i<count($emailList); $i++) {
					
			$cellColor = "";
			$cellColor = cellColor($i);
			
			$mail = new htmlMimeMail();
			$mail->setSubject($subject);
			$mail->setHeader('X-Mailer', 'ImeiUnlock Bulk Mailer');
			$mail->setFrom($fromName." <".$fromEmail.">");
			$mail->setReturnPath($fromEmail);
			
			if(($emailList[$i]['htmlEmail']==1 && $_POST['format']=="user") || $_POST['format']=="html")  {
				$mail->setHtml($html);
			} else {
				$mail->setText($text);
			}
			$result = $mail->send(array($emailList[$i]['email']), $config['mailMethod']);
			
			$recipNo = $_GET['emailed']+($i+1);
			
			echo "<tr><td class='".$cellColor."'><span class='copyText'>".($recipNo).".</span></td><td class='".$cellColor."'><span class='copyText'>".$emailList[$i]['firstName']." ".$emailList[$i]['lastName']."</span></td><td class='".$cellColor."'><span class='copyText'> &lt;".$emailList[$i]['email']."&gt;</span></td></tr>\r\n";
			flush();
		}
		
		echo "</table>"; 
		?>
		<form method="post" name="autoSubmitForm" action="<?php echo $glob['adminFile']; ?>?_g=/customers/send&amp;page=<?php echo $_GET['page']+1; ?>&amp;startTime=<?php echo $startTime; ?>&amp;emailed=<?php echo $recipNo;?>" enctype="multipart/form-data">
		<?php
		echo recoverPostVars($_POST,"FCKeditor");
		?>
		<img src="<?php echo $glob['adminFolder']; ?>/images/px.gif" alt="" width="1" height="1" title="" onload="submitDoc('autoSubmitForm');" />
		</form>
		<?php
		} else {
		?>
		<p class="infoText"><?php echo $lang['admin']['customers_bulk_finished'];?></p>
	
		<img src="<?php echo $glob['adminFolder']; ?>/images/px.gif" alt="" width="1" height="1" title="" onload="showHideLayers('sending','','hide','sent','','show');" />
<?php
	} // else

?>
<p class="copyText">
	<strong><?php echo $lang['admin']['customers_time_taken'];?></strong> <?php echo readableSeconds(time() - $startTime); ?><br/>
	<strong><?php echo $lang['admin']['customers_recipients'];?></strong> <?php echo $_GET['emailed'] + $i; ?>
</p>
<?php
}
?>