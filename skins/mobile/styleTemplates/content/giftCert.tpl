<!-- BEGIN: gift_cert -->
<div class="boxContent">
	
	<span class="txtContentTitle">{LANG_TITLE}</span>
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	<p>{LANG_DESC}</p>
	<form action="index.php?_a=giftCert" method="post">
	<table border="0" cellspacing="0" cellpadding="3">
	  <tr>
		<td colspan="2"><strong>{LANG_BUY_CERT}</strong></td>
	  </tr>
	  <tr>
		<td>{LANG_AMOUNT}</td>
		<td><input name="gc[amount]" class="textbox" type="text" id="amount" size="6" maxlength="10" value="{VALUE_AMOUNT}" /> 
		* {LANG_MIN_MAX} </td>
	  </tr>
	  <tr>
		<td>{LANG_RECIP_NAME}</td>
		<td><input name="gc[recipName]" class="textbox" type="text" id="name" value="{VALUE_RECIPNAME}" />  
		* </td>
	  </tr>
	  <tr>
		<td>{LANG_RECIP_EMAIL}</td>
		<td><input name="gc[recipEmail]" class="textbox" type="text" id="email" value="{VALUE_EMAIL}" />  
		*</td>
	  </tr>
	  <tr>
		<td valign="top">{LANG_MESSAGE}</td>
		<td><textarea name="gc[message]" class="textbox" cols="35" rows="5" id="message">{VALUE_MESSAGE}</textarea></td>
	  </tr>
	  <tr>
		<td>{LANG_METHOD}</td>
		<td>
		<select name="gc[delivery]" class="textbox">
		<!-- BEGIN: email_opts -->
		<option value='e' {VAL_DELIVERY_E}>{LANG_EMAIL}</option>
		<!-- END: email_opts -->
		<!-- BEGIN: mail_opts -->
		<option value='m' {VAL_DELIVERY_M}>{LANG_MAIL}</option>
		<!-- END: mail_opts -->
		</select>		
		</td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td>
		<input type="hidden" name="gc[cert]" value="1" />
		<input type="submit" class="submit" name="Submit" value="{LANG_ADD_TO_BASKET}" />
		</td>
      </tr>
	</table>

	</form>
</div>
<!-- END: gift_cert -->