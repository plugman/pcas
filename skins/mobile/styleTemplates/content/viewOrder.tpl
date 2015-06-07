<!-- BEGIN: view_order -->

<div>
      <div class="maincontent">
      <div class="headingBorder maindiv">
        <h3 class="txt18 txt-purple">
          {LANG_YOUR_VIEW_ORDER}
        </h3>
        <span>&nbsp;</span>
        </div>
      
<div class="orderhistory2">
<!-- BEGIN: session_true -->
		<!-- BEGIN: order_true -->
		<strong class="txt14 txt-darkpurple">{LANG_ORDER_LIST}</strong>
		
			<!-- BEGIN: make_payment -->
			<p class="completepay txt-darkpurple">{LANG_MAKE_PAYMENT}</p>
			<!-- END: end_payment -->
           <div class="vieworder" style="float:none">	

	<table width="100%" border="1" cellpadding="3" cellspacing="0" >
      <tr class="trorder ">
        <td class="tdcartTitle" align="center" width="370"><strong>{LANG_PRODUCT}</strong></td>
        <td class="tdcartTitle" align="center" ><strong>Order date/Time</strong></td>
         <td class="tdcartTitle">Unlock Status</td>
          <td class="tdcartTitle">Order Notes</td>
        <td align="center" class="tdcartTitle" ><strong>{LANG_PRICE}</strong></td>
      </tr>
	 <!-- BEGIN: repeat_products -->
	  <tr>
        <td class="{TD_CLASS}"  align="center" >
        <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border:none">
          <tr>
            <th scope="row" width="124" >Network: </th>
            <td>{VAL_PRODUCT}</td>
          </tr>
          <tr>
            <th scope="row">MEI: </th>
            <td> {VAL_IND_QUANTITY}</td>
          </tr>
          <tr>
            <th scope="row">Payments:  </th>
            <td>{VAL_GATEWAY}</td>
          </tr>
          <tr>
            <th scope="row">Model: </th>
            <td>{VAL_MODEL}</td>
          </tr>
        </table>
		</td>
         <td class="{TD_CLASS}" align="center">{VAL_TIMEE}<!--<span class="txt9 txt-grey">01:09 AM</span>--></td>          
		<td class="{TD_CLASS}" align="center">{VAL_STAT}</td>
        <td class="{TD_CLASS}" align="center">{EX_NOTES}</td>
         
        <td align="center" class="{TD_CLASS}">{VAL_IND_PRICE}</td>
      </tr>
	  <!-- END: repeat_products -->
	 
    </table>
    </div>
   <table cellpadding="0" cellspacing="0" width="100%">
   <tr>
     <td width="30%">
      <table border="0" cellspacing="5" cellpadding="3" style="margin-top:10px;">
          <tr>
            <td ><strong>{LANG_ORDER_STATUS}</strong></td>
            <td>{VAL_ORDER_STATUS}</td>
          </tr>
          <tr>
            <td><strong>{LANG_ORDER_TIME}</strong></td>
            <td>{VAL_ORDER_TIME}</td>
          </tr>
          <tr>
            <td><strong>{LANG_GATEWAY}</strong></td>
            <td>{VAL_GATEWAY}</td>
          </tr>
            <!-- BEGIN: coments -->
           <tr>
            <td><strong>Coments by Suplier</strong></td>
            <td>{COMENTS}</td>
          </tr>
            <!-- END: coments -->
          <tr>
            <td>&nbsp;</td>
            <td><a href="{VAL_SHIP_TRACK}" class="txtLink" target="_blank">{LANG_SHIP_TRACK}</a></td>
          </tr>
          
        </table>
     </td>
     <td align="right" >	  	
	  <table width="280" cellpadding="0" cellspacing="0" style="margin-top:10px" >
        <tr class="tdsotalna">
        
	    <td class="btmSubNav tdsotalna" align="right" width="110px"><span>{LANG_SUBTOTAL}</span></td>
	    <td align="center" class="btmSubNav tdsotalna" width="110px">
		<span>{VAL_SUBTOTAL}</span>		</td>
	  </tr>
      <tr>
	    <td align="right" class="tdsotalna" nowrap="nowrap">{LANG_PAYPALL_FEE}</td>
	    <td align="center" class="tdsotalna">{VAL_PAYPAL_FEE}</td>
	  </tr>
	  <tr>
	    <td align="right" class="tdsotalna">{LANG_DISCOUNT}</td>
	    <td align="center" class="tdsotalna">{VAL_DISCOUNT}</td>
	  </tr>
      
	  <tr>
	    <td align="right" class="tdsotalna"><span>{LANG_TOTAL_TAX}</span></td>
	    <td align="center" class="tdsotalna">
		{VAL_TOTAL_TAX}		</td>
	    </tr>
	  
	  <tr>
	    <td style=" background-color:#281942; height:30px; color:#fff;" align="right"><strong>{LANG_GRAND_TOTAL}</strong></td>
	    <td align="center" class="btmSubNav" style=" background-color:#281942; color:#fff;">
		<strong>{VAL_GRAND_TOTAL}</strong></td>
	    </tr></table>
     </td>	
     </tr>
     </table>
		<!-- END: order_true -->
		
		<!-- BEGIN: order_false -->
		<p>{LANG_NO_ORDERS}</p>
		<!-- END: order_false -->
	
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
			
</div>

</div>
</div>
<!-- END: view_order -->