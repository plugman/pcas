<!-- BEGIN: view_order -->
<div class="boxContent">
	
	<span class="txtContentTitle">{LANG_YOUR_VIEW_ORDER}</span>
	
	<!-- BEGIN: session_true -->
	<div>	
		<!-- BEGIN: order_true -->
		<p>{LANG_ORDER_LIST}</p>
		
			<!-- BEGIN: make_payment -->
			<p>{LANG_MAKE_PAYMENT}</p>
			<!-- END: end_payment -->
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
      <td colspan="2" class="tdcartTitle">{LANG_CUSTOMER_INFO}</td>
    </tr>
    <tr>
      <td align="left" valign="top">
	  <strong>{LANG_INVOICE_ADDRESS}</strong>
      <div style="padding: 10px;">
	  {VAL_INVOICE_NAME}<br/>
	  {VAL_INVOICE_ADD_1}<br/>
	  {VAL_INVOICE_ADD_2}<br/>
	  {VAL_INVOICE_TOWN}, {VAL_INVOICE_COUNTY}<br/>
	  {VAL_INVOICE_POSTCODE}<br/>
	  {VAL_INVOICE_COUNTRY}</div>
	  </td>
      <td align="left" valign="top">
	  <strong>{LANG_DELIVERY_ADDRESS}</strong>
      <div style="padding: 10px;">{VAL_DELIVERY_NAME}<br/>
	  {VAL_DELIVERY_ADD_1}<br/>
	  {VAL_DELIVERY_ADD_2}<br/>
	  {VAL_DELIVERY_TOWN}, {VAL_DELIVERY_COUNTY}<br/>
	  {VAL_DELIVERY_POSTCODE}<br/>
	  {VAL_DELIVERY_COUNTRY}</div>
	  </td>
    </tr>

    <tr>
      <td colspan="2" align="left" valign="top"><strong>{LANG_CUSTOMER_COMMENTS}</strong>
	    <div style="padding: 10px;">&quot;{VAL_CUSTOMER_COMMENTS}&quot;</div></td>
      </tr>
  <tr>
    <td colspan="2" class="tdcartTitle">{LANG_ORDER_SUMMARY}</td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="bottom" style="padding: 0px;">
	<table width="100%" border="0" cellpadding="7" cellspacing="0" style="padding: 7px;">
      <tr>
        <td class="subHead"><strong>{LANG_PRODUCT}</strong></td>
        <td class="subHead"><strong>{LANG_PRODUCT_CODE}</strong></td>
        <td class="subHead"><strong>{LANG_QUANTITY}</strong></td>
        <td align="right" class="subHead"><strong>{LANG_PRICE}</strong></td>
      </tr>
	 <!-- BEGIN: repeat_products -->
	  <tr>
        <td class="{TD_CLASS}">
		{VAL_PRODUCT}<br />
		{VAL_PRODUCT_OPTS}
			<!-- BEGIN: digital_link -->
			<br />
			[<a href="{VAL_DOWNLOAD_LINK}" class="txtDefault">{LANG_DOWNLOAD_LINK}</a>]
			<!-- END: digital_link -->		</td>
        <td class="{TD_CLASS}">{VAL_IND_PROD_CODE}</td>
        <td class="{TD_CLASS}">{VAL_IND_QUANTITY}</td>
        <td align="right" class="{TD_CLASS}">{VAL_IND_PRICE}</td>
      </tr>
	  <!-- END: repeat_products -->
	  <tr>
	    <td rowspan="5" align="left" valign="bottom" class="btmSubNav" style="padding: 0px;">
		<table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td>{LANG_ORDER_STATUS}</td>
            <td>{VAL_ORDER_STATUS}</td>
          </tr>
          <tr>
            <td>{LANG_ORDER_TIME}</td>
            <td>{VAL_ORDER_TIME}</td>
          </tr>
          <tr>
            <td>{LANG_GATEWAY}</td>
            <td>{VAL_GATEWAY}</td>
          </tr>
          <tr>
            <td>{LANG_SHIP_METHOD}</td>
            <td>{VAL_SHIP_METHOD}</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><a href="{VAL_SHIP_TRACK}" class="txtLink" target="_blank">{LANG_SHIP_TRACK}</a></td>
          </tr>
        </table></td>
	    <td rowspan="5" class="btmSubNav">&nbsp;</td>
	    <td class="btmSubNav"><span>{LANG_SUBTOTAL}</span></td>
	    <td align="right" class="btmSubNav">
		<span>{VAL_SUBTOTAL}</span>		</td>
	  </tr>
	  <tr>
	    <td>{LANG_DISCOUNT}</td>
	    <td align="right">{VAL_DISCOUNT}</td>
	  </tr>
	  <tr>
	    <td><span>{LANG_TOTAL_TAX}</span></td>
	    <td align="right">
		{VAL_TOTAL_TAX}		</td>
	    </tr>
	  <tr>
	    <td><span>{LANG_TOTAL_SHIP}</span></td>
	    <td align="right">
		{VAL_TOTAL_SHIP}		</td>
	    </tr>
	  <tr>
	    <td style="border-top: 1px solid #000000;"><strong>{LANG_GRAND_TOTAL}</strong></td>
	    <td align="right" class="btmSubNav" style="border-top: 1px solid #000000;">
		<strong>{VAL_GRAND_TOTAL}</strong></td>
	    </tr>
    </table></td>
  </tr>
</table>

		<!-- END: order_true -->
		
		<!-- BEGIN: order_false -->
		<p>{LANG_NO_ORDERS}</p>
		<!-- END: order_false -->
	</div>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
			
</div>
<!-- END: view_order -->