<!-- BEGIN: gateway -->


      <div class="maincontent">
      
   
<div class="boxtop">
	
	<div class="headingboxtop">
        Choose {LANG_PAYMENT} Method
        
        </div>
        
        <div style="padding:20px">
	 <!-- BEGIN: cart_false -->
	<p>{LANG_CART_EMPTY}</p>
	<!-- END: cart_false -->
	<!-- BEGIN: cart_true -->
	
	<form action="{VAL_FORM_ACTION}" method="{VAL_FORM_METHOD}" name="gateway" target="{VAL_TARGET}">
		
		<!-- BEGIN: choose_gate -->
		<div class="miandiv">
		<p class="gbox">{LANG_PAYMENT_SUMMARY}</p>
		
		<p class="txt-darkpurple" style="margin:15px 8px; font-size:14px; font-weight:bold; line-height:18px;">{LANG_CHOOSE_GATEWAY}</p>
		</div>
		<table width="350px" border="0" align="center" cellspacing="0" cellpadding="0"  style="margin:10px;">
			<!-- BEGIN: gateways_true -->
			<tr style="height:90px;">
				<td width="150px"> <img alt="" src="skins/{VAL_SKIN}/styleImages/{VAL_GATEWAY_DESC}" /></td>
				<td width="50" align="center" >
				<input name="gateway" type="radio" value="{VAL_GATEWAY_FOLDER}" {VAL_CHECKED} />
				</td>
			</tr>
			<!-- END: gateways_true -->
			
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
				<p style="font-size:22px; font-weight:bold; padding-bottom:15px;">{LANG_TRANSFERRING}</p>
				<p><img src="skins/{VAL_SKIN}/styleImages/progress.gif"  alt="" title="" onload="submitDoc('gateway')" /></p>
			</div>
			<!-- END: auto_submit-->
			<!-- BEGIN: manual_submit-->
			<p align="left">{LANG_FORM_TITLE}</p>
			{FORM_TEMPLATE}
			<!-- END: manual_submit-->
			
		<!-- END: transfer -->
		<a href="javascript:submitDoc('gateway');" class="submitlogin" >
        <span class="warrow2"></span><strong class="left txt24" style="margin:2px 0 0 0">{LANG_CHECKOUT_BTN}</strong></a>
		
	</form>
	
		<!-- BEGIN: affiliate_code -->
		{VAL_AFFILIATE_TRACK_HTML}
		<!-- END: affiliate_code -->
	
	<!-- END: cart_true -->
    </div>
</div></div>
<!-- END: gateway -->