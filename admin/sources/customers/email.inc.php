<?php
/*
+--------------------------------------------------------------------------
|	email.inc.php
|   ========================================
|	Email Customers
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_customers.inc.php");

permission("customers", "write", true);

if (isset($_GET['action']) && $_GET['action']=="download") {
	$query = "SELECT title, email, firstName, lastName, type FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE optIn1st = 1";
	$results = $db->select($query);
	if ($results) {
		$emailList = "";
		for ($i=0; $i<count($results); $i++){
			if ($_POST['incName']==1 && $results[$i]['type']==1) {
				$emailList .=  $results[$i]['title']." ".$results[$i]['firstName']." ".$results[$i]['lastName']." <".$results[$i]['email'].">";
			} else {
				$emailList .=  $results[$i]['email'];
			}
		
			$emailList .=  "\r\n";
		}
		$filename="CustomerEmails_".date("dMy").".txt";
		header('Pragma: private');
		header('Cache-control: private, must-revalidate');
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-type: text/plain");
		header("Content-type: application/octet-stream");
		header("Content-length: ".strlen($emailList));
		header("Content-Transfer-Encoding: binary");
		echo $emailList;
		exit;
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['customers_no_download_email']."</p>";
	}
	exit;
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['customers_email_customers']; ?></p><br />


<?php 
if(isset($_GET['action']) && $_GET['action']=="send")
{ 
?>
<div class="headingBlackbg"><?php echo $lang['admin']['customers_create_email']; ?></div>
<form name="form1" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/send" target="_self" enctype="multipart/form-data">
<table width="100%"  border="0" cellspacing="1" cellpadding="3" class="mainTable">

  <tr>
    <td class="" valign='top'>
    	<strong><?php echo $lang['admin']['customers_send_html']; ?></strong>
    </td>
    <td class="">
	<?php
	require($glob['adminFolder']."/includes".CC_DS."rte".CC_DS."fckeditor.php");
	
	$oFCKeditor = new FCKeditor('FCKeditor');
	$oFCKeditor->BasePath = $GLOBALS['rootRel'].$glob['adminFolder'].'/includes/rte/';
		
	if (isset($_POST['FCKeditor'])) {
		$oFCKeditor->Value = stripslashes($_POST['FCKeditor']);
	} else {
		$oFCKeditor->Value = "";
	}
	
	if (!$config['richTextEditor']) $oFCKeditor->off = true;
	$oFCKeditor->Create();
?></td>
  </tr>
  <tr>
    <td valign="top" class="tdText"><strong><?php echo $lang['admin']['customers_send_text']; ?></strong></td>
    <td><textarea name='plain_text' class="textarea3" style='width: 100%;' rows='14'><?php echo $_POST['plain_text']; ?></textarea></td>
  </tr>
  <tr>
    <td class=""><span class="tdText"><em><strong><?php echo $lang['admin']['customers_hint']; ?></strong> </em></span></td>
    <td class=""><span class="tdText"><em><?php echo $lang['admin']['customers_click_source']; ?></em></span></td>
  </tr>
  <tr>
    <td valign="top" class="tdRichText"><span class="tdText"><em><strong><?php echo $lang['admin']['customers_important']; ?></strong></em></span></td>
    <td class=""><span class="tdText"><em><?php echo $lang['admin']['customers_absolute_links']; ?></em> </span>
        <input name="unsubscribe" type="text" class="textbox" value="<?php echo $GLOBALS['storeURL']."/index.php?_a=unsubscribe"; ?>" size="30" />   </td>
  </tr>
  <tr>
    <td width="110" class="tdText"><strong><?php echo $lang['admin']['customers_email_subject']; ?></strong>      </td>
    <td class="tdText"><input name="subject" type="text" id="subject" class="textbox" value="<?php if(isset($_POST['subject'])) echo stripslashes($_POST['subject']); ?>" /></td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_senders_name']; ?></strong></td>
    <td class="tdText"><input name="fromName" type="text" class="textbox" id="fromName" value="<?php if(isset($_POST['fromName'])) echo 
	$_POST['fromName']; ?>" /></td>
  </tr>
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_senders_email']; ?></strong></td>
    <td class="tdText"><input name="fromEmail" type="text" class="textbox" id="fromEmail" value="<?php if(isset($_POST['fromEmail'])) echo $_POST['fromEmail']; ?>" /></td>
  </tr>
  <!--
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_return_path']; ?></strong></td>
    <td class="tdText"><input name="returnPath" type="text" class="textbox" id="returnPath" value="<?php if(isset($_POST['returnPath'])) echo $_POST['returnPath']; ?>" /> 
      <?php echo $lang['admin']['customers_bounce_desc']; ?></td>
  </tr>
  -->
  <tr>
    <td class="tdText"><strong><?php echo $lang['admin']['customers_send_format']; ?></strong></td>
    <td class="tdText"><?php echo $lang['admin']['customers_send_pref']; ?>   
      <input name="format" type="radio" value="user" <?php if(!isset($_POST['format']) || $_POST['format']=="user") { echo 'checked="checked"'; } ?>  /> 
       <?php echo $lang['admin']['customers_send_html']; ?> 
      <input name="format" type="radio" value="html" <?php if($_POST['format']=="html") { echo 'checked="checked"'; } ?> />
      <?php echo $lang['admin']['customers_send_text']; ?>
      <input name="format" type="radio" value="text" <?php if($_POST['format']=="text") { echo 'checked="checked"'; } ?> /></td>
  </tr>
  <tr>
    <td width="110" class="tdText"><strong><?php echo $lang['admin']['customers_send_test']; ?></strong></td>
    <td class="tdText">
	<?php echo $lang['admin_common']['yes']; ?>      
      <input name="test" type="radio" value="1" <?php if(isset($_POST['test']) && $_POST['test']=="1") echo "checked='checked'"; elseif(!isset($_POST['test'])) echo "checked='checked'"; ?> /> 
	<?php echo $lang['admin_common']['no']; ?> 
      <input name="test" type="radio" value="0" <?php if(isset($_POST['test']) && $_POST['test'] =="0") echo "checked='checked'"; ?> /> 
      <strong><?php echo $lang['admin']['customers_test_email_recip']; ?></strong>
	  <input name="testEmail" type="text" id="testEmail" value="<?php if(isset($_POST['testEmail'])) echo $_POST['testEmail']; else echo $ccAdminData['email']; ?>" /></td>
  </tr>
  <tr>
    <td class="tdText">&nbsp;</td>
    <td class="tdText"><input type="submit" class="submit" value="<?php echo $lang['admin']['customers_send_email']; ?>" /></td>
  </tr>
</table>
</form>
<?php 
} else {  
	if (isset($msg)) echo msg($msg);
?>
<p class="copyText"><?php echo $lang['admin']['customers_download_or_send']; ?></p><br />

<div class="headingBlackbg"><?php echo $lang['admin']['customers_please_choose']; ?></div>
<table width="450" border="0" align="center" cellpadding="3" cellspacing="1" class="mainTable">

  <tr>
    <td width="50%" valign="top" class="copyText"><?php echo $lang['admin']['customers_used_to_download']; ?></td>
    <td width="50%" valign="top" class="copyText"><?php echo $lang['admin']['customers_bulk_to_subscribed']; ?></td>
  </tr>
  <tr align="center">
    <td valign="bottom" class="copyText">
    <form name="download" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/email&amp;action=download">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><?php echo $lang['admin']['customers_include_name']; ?></td>
          <td>
		  <?php echo $lang['admin_common']['yes']; ?>
            <input name="incName" type="radio" value="1" checked='checked' />
		  <?php echo $lang['admin_common']['no']; ?>
<input name="incName" type="radio" value="0" /></td>
        </tr>
        <tr>
          <td height="30" colspan="2"><input name="download" type="submit" class="submit" id="download" value="<?php echo $lang['admin']['customers_download_email']; ?>" /></td>
          </tr>
      </table>
    </form></td>
    <td align="left" valign="bottom" class="copyText">
	<form name="download" method="post" action="<?php echo $glob['adminFile']; ?>?_g=customers/email&amp;action=send" enctype="multipart/form-data">
	  <input name="send" type="submit" class="submit" id="send" value="<?php echo $lang['admin']['customers_send_email'];?>" />
	</form>
	</td>
  </tr>
</table>
<?php 
} 
?>