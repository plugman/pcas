<!-- BEGIN: newsletter -->
<div class="boxContent">

	<span class="txtContentTitle">{LANG_NEWSLETTER_TITLE}</span>
	
	<!-- BEGIN: session_true -->
	<p>{LANG_NEWSLETTER_DESC}</p>
	
		<form action="index.php?_a=newsletter" target="_self" method="post">
			<table border="0" cellspacing="0" cellpadding="3" align="center">
				<tr align="left">
				  <td colspan="2"><strong>{TXT_SUBSCRIBED}</strong></td>
			  </tr>
				<tr>
					<td align="right">{LANG_YES}
                    <input type="radio" name="optIn1st" value="1" {STATE_SUBSCRIBED_YES}  /></td>
					<td align="right">
					{LANG_NO} 
					  <input type="radio" name="optIn1st" value="0" {STATE_SUBSCRIBED_NO} />
				    </td>
				</tr>
				<tr align="left">
				  <td colspan="2"><strong>{TXT_EMAIL_FORMAT}</strong></td>
			  </tr>
				<tr>
					<td align="right">{LANG_TEXT}
                    <input type="radio" name="htmlEmail" value="0" {STATE_HTML_TEXT} /></td>
					<td align="right">
					<abbr title="{LANG_HTML_ABBR}">{LANG_HTML}</abbr> <input type="radio" name="htmlEmail" value="1" {STATE_HTML_HTML} />
					</td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><input name="submit" type="submit" value="{TXT_SUBMIT}" class="submit" /></td>
			  </tr>
		</table>
	</form>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->

</div>
<!-- END: newsletter -->