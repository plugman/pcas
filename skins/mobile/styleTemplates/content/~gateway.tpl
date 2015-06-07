<!-- BEGIN: gateway -->
<div class="maincenter">
      <div class="maincontent">
        <h3 class="h3arial">
          {LANG_PAYMENT} &nbsp; &nbsp; &nbsp; &nbsp;
          &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        </h3>
        <div class="blankdiv"></div>
<div class="boxContent">
	<div style="text-align: center; height: 25px;">
		<div class="cartProgress">
		{LANG_CART} --- {LANG_CHECKOUT} --- <span class="txtcartProgressCurrent">{LANG_PAYMENT}</span> --- {LANG_COMPLETE}
		</div>
	</div>
	
	<!-- BEGIN: cart_false -->
	<p>{LANG_CART_EMPTY}</p>
	<!-- END: cart_false -->
	<!-- BEGIN: cart_true -->
	
	<form action="{VAL_FORM_ACTION}" method="{VAL_FORM_METHOD}" name="gateway" target="{VAL_TARGET}">
		
		<!-- BEGIN: choose_gate -->
		
		<p style="margin:10px;">{LANG_PAYMENT_SUMMARY}</p>
		
		<p style="margin:10px;">{LANG_CHOOSE_GATEWAY}</p>
		
		<table width="150" border="0" align="center" cellspacing="0" cellpadding="0"  style="margin:10px;">
			<!-- BEGIN: gateways_true -->
			<tr>
				<td class="{TD_CART_CLASS}"><strong>{VAL_GATEWAY_DESC}</strong></td>
				<td width="50" align="center" class="{TD_CART_CLASS}">
				<input name="gateway" type="radio" value="{VAL_GATEWAY_FOLDER}" {VAL_CHECKED} />
				</td>
			</tr>
			<!-- END: gateways_true -->
			<tr>
				<td colspan="2" align="left">{LANG_COMMENTS}</td>
			</tr>
			<tr align="right">
			  <td colspan="2"><textarea name="customer_comments" cols="60" rows="3" class="textbox" style="height:70px; font-size:16px;">{VAL_CUSTOMER_COMMENTS}</textarea></td>
			</tr>
			<!-- BEGIN: gateways_false -->
			<tr>
				<td>{LANG_GATEWAYS_FALSE}</td>
			</tr>
			<!-- END: gateways_false -->
		</table>
		<!-- END: choose_gate -->
		
		<!-- BEGIN: transfer -->
			
			{FORM_PARAMETERS}
			<!-- BEGIN: auto_submit-->
			<div style="text-align: center;">
				<p>{LANG_TRANSFERRING}</p>
				<p><img src="skins/{VAL_SKIN}/styleImages/progress.gif"  alt="" title="" onload="submitDoc('gateway')" /></p>
			</div>
			<!-- END: auto_submit-->
			<!-- BEGIN: manual_submit-->
			<p align="left">{LANG_FORM_TITLE}</p>
			{FORM_TEMPLATE}
			<!-- END: manual_submit-->
			
		<!-- END: transfer -->
		<p style="text-align: center;"><a href="javascript:submitDoc('gateway');" class="txtCheckout">{LANG_CHECKOUT_BTN}</a></p>
		
	</form>
	
		<!-- BEGIN: affiliate_code -->
		{VAL_AFFILIATE_TRACK_HTML}
		<!-- END: affiliate_code -->
	
	<!-- END: cart_true -->
</div></div></div>
<!-- END: gateway -->