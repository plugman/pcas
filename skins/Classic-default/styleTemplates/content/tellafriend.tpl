<!-- BEGIN: tellafriend -->
<div class="boxContent">

	<span class="txtContentTitle">{TAF_TITLE}</span>
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	<p>{TAF_DESC}</p>
	
		<form action="index.php?_a=tellafriend&amp;productId={PRODUCT_ID}" target="_self" method="post">
			<table border="0" cellspacing="0" cellpadding="3" align="center">
				<tr>
					<td align="right"><strong>{TXT_RECIP_NAME}</strong></td>
					<td><input type="text" name="recipName" value="{VAL_RECIP_NAME}"class="textbox" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_RECIP_EMAIL}</strong></td>
					<td><input type="text" name="recipEmail" value="{VAL_RECIP_EMAIL}" class="textbox" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_SENDER_NAME}</strong></td>
					<td><input type="text" name="senderName" class="textbox" value="{VAL_SENDER_NAME}" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_SENDER_EMAIL}</strong></td>
					<td><input type="text" name="senderEmail" class="textbox" value="{VAL_SENDER_EMAIL}" /></td>
				</tr>
				<!-- BEGIN: spambot -->
				<tr>
				  <td align="right" valign="bottom"><strong>{TXT_SPAMBOT}</strong></td>
				  <td>{IMG_SPAMBOT}<br />
				    <input name="spamcode" type="text" class="textbox" value="" size="5" maxlength="5" /></td></tr>
			  	<!-- END: spambot -->
			  	<!-- BEGIN: recaptcha -->
				<tr>
				  <td align="right" valign="top"><strong>{TXT_SPAMBOT}</strong></td>
				  <td>{RECAPTCHA}</td>
				</tr>
				<!-- END: recaptcha -->
				<tr>
					<td align="right" valign="top"><strong>{TXT_MESSAGE}</strong></td>
					<td><textarea name="message" cols="30" rows="5" class="textbox">{VAL_MESSAGE}</textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input name="submit" type="submit" value="{TXT_SUBMIT}" class="submit" /></td>
				</tr>
		</table>
	<input name="ESC" type="hidden" value="{VAL_ESC}" />
	</form>

</div>
<!-- END: tellafriend -->