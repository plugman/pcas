<!-- BEGIN: view_orders -->
<div class="boxContent">
	
	<span class="txtContentTitle">{LANG_YOUR_VIEW_ORDERS}</span>
	
	<!-- BEGIN: session_true -->
	<div>	
		<!-- BEGIN: orders_true -->
		<p>{LANG_ORDER_LIST}</p>
		<table width="100%" border="0" cellpadding="3" cellspacing="0" style="margin-bottom: 25px;">
		  <tr>
		    <td align="center" class="tdcartTitle">{LANG_ORDER_NO}</td>
			<td align="center" class="tdcartTitle">{LANG_STATUS}</td>
			<td align="center" class="tdcartTitle">{LANG_DATE_TIME}</td>
			<td align="center" class="tdcartTitle">{LANG_ACTION}</td>
		  </tr>
		  <!-- BEGIN: repeat_orders -->
		  <tr>
		    <td align="center" class="{TD_CART_CLASS}"><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLink">{DATA.cart_order_id}</a></td>
			<td align="center" class="{TD_CART_CLASS}">{VAL_STATE}</td>
			<td align="center" class="{TD_CART_CLASS}">{VAL_DATE_TIME}</td>
			<td align="center" class="{TD_CART_CLASS}"><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLink">{LANG_VIEW_ORDER}</a>
			<!-- BEGIN: make_payment -->
			<br />
			<a href="index.php?_g=co&amp;_a=step3&amp;cart_order_id={DATA.cart_order_id}" class="txtLink">{LANG_COMPLETE_PAYMENT}</a>
			<!-- END: make_payment -->
			<!-- BEGIN: courier_tracking -->
			<br />
			<a href="{TRACKING_URL}" class="txtLink" target="_blank">{LANG_COURIER_TRACKING}</a>
			<!-- END: courier_tracking -->
			</td>
		  </tr>
		  <!-- END: repeat_orders -->
	  </table>
		<ol style="margin-left: 20px;">
			<!-- BEGIN: repeat_status -->
			<li style="list-style-position:outside">
			<strong>{LANG_ORDER_STATUS}</strong> - {LANG_ORDER_STATUS_DESC}
			</li>
			<!-- END: repeat_status -->
		</ol>
		<!-- END: orders_true -->
		
		<!-- BEGIN: orders_false -->
		<p>{LANG_NO_ORDERS}</p>
		<!-- END: orders_false -->
	</div>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
			
</div>
<!-- END: view_orders -->