<!-- BEGIN: forgot_pass -->
<div class="boxContent">

	<span class="txtContentTitle">{LANG_FORGOT_PASS_TITLE}</span>
	
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	
	<p>{FORGOT_PASS_STATUS}</p>
	
	<!-- BEGIN: form -->
	<form action="index.php?_a=forgotPass" target="_self" method="post">
		<table border="0" cellspacing="0" cellpadding="3" align="center">
			<tr>
				<td align="right"><strong>{LANG_EMAIL}</strong></td>
				<td><input type="text" name="email" class="textbox" /></td>
			</tr>
			<!-- BEGIN: spambot -->
				<tr>
				  <td align="right" valign="bottom"><strong>{TXT_SPAMBOT}</strong></td>
				  <td>{IMG_SPAMBOT}<br />
				  <input name="spamcode" type="text" class="textbox" value="" size="5" maxlength="5" /></td>
				</tr>
			<!-- END: spambot -->
			<!-- BEGIN: recaptcha -->
				<tr>
				  <td align="right" valign="top"><strong>{TXT_SPAMBOT}</strong></td>
				  <td>{RECAPTCHA}</td>
				</tr>
			<!-- END: recaptcha -->
			<tr>
				<td><input name="ESC" type="hidden" value="{VAL_ESC}" /></td>
				<td><input name="submit" type="submit" value="{TXT_SUBMIT}" class="submit" /></td>
			</tr>
	  </table>
	</form>
	<!-- END: form -->

</div>
<!-- END: forgot_pass -->