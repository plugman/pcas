<!-- BEGIN: session_page -->
<div class="boxContent">
			<!-- BEGIN: cart_false -->
            <p>{LANG_CART_EMPTY}</p>
			<!-- END: cart_false -->
			<!-- BEGIN: cart_true -->
				<span class="txtContentTitle">{LANG_LOGIN_TITLE}</span>
				<div style="text-align: center; height: 25px;">
					<div class="cartProgress">
					{LANG_CART} --- <span class="txtcartProgressCurrent">{LANG_CHECKOUT}</span> --- {LANG_PAYMENT} --- {LANG_COMPLETE}
					</div>
				</div>
			<div>
					<p>{LANG_LOGIN_BELOW}</p>
					<form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post">
						<table border="0" cellspacing="0" cellpadding="3">
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
									<a href="index.php?_a=forgotPass" class="txtDefault">{LANG_FORGOT_PASS}</a>
								</td>
							</tr>
					  </table>
					</form>
			</div>
			<div class="regSep"><span class="txtContentTitle">{LANG_EXPRESS_REGISTER}</span>
			<p>{LANG_CONT_REGISTER}</p>
			<a href="index.php?_g=co&amp;_a=reg&amp;co=1" class="txtCheckout">{LANG_REGISTER_BUTN}</a>
			</div>
			<div style="padding-bottom: 3px;"><span class="txtContentTitle">{LANG_CONT_SHOPPING}</span> 
			<p>{LANG_CONT_SHOPPING_DESC}</p>
			<a href="index.php" class="txtUpdate">{LANG_CONT_SHOPPING_BTN}</a>
			</div>
			<!-- END: cart_true -->
</div>
<!-- END: session_page -->