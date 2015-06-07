<!-- BEGIN: login -->
<div class="boxContent">

	<span class="txtContentTitle">{LANG_LOGIN_TITLE}</span>
	
	<p>{LOGIN_STATUS}</p>
	
	<!-- BEGIN: form -->
	<form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post">
		<table border="0" cellspacing="0" cellpadding="3" align="center">
			<tr>
				<td align="right"><strong>{LANG_USERNAME}</strong></td>
				<td><input type="text" name="username" class="textbox" value="{VAL_USERNAME}" /></td>
			</tr>
			<tr>
				<td align="right"><strong>{LANG_PASSWORD}</strong></td>
				<td><input type="password" name="password" class="textbox" /></td>
			</tr>
			<tr>
				<td align="right">{LANG_REMEMBER}</td>
				<td><input name="remember" type="checkbox" value="1" {CHECKBOX_STATUS} /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="submit" type="submit" value="{TXT_LOGIN}" class="submit" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<a href="index.php?_a=forgotPass" class="txtDefault">{LANG_FORGOT_PASS}</a><br/>
					<a href="index.php?_g=co&amp;_a=reg" class="txtDefault">{LANG_REGISTER}</a>
				</td>
			</tr>
	  </table>
	</form>
	<!-- END: form -->

</div>
<!-- END: login -->