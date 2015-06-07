<!-- BEGIN: view_order -->

    <div class="maindiv breadbg">
<div class="maincenter">

        	<a href="index.php">Home</a> <span class="breadSeprator"></span> <a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span><a href="Orders.html">Your Orders</a> 
            
           
             <h2 class="mainheading" style="margin:10px 0;">  <center>{LANG_YOUR_VIEW_ORDER}</center></h2>
        </div>
</div>
    
<div class="maincenter">
      <div class="mainbox">
      
      
<div class="orderhistory">
<!-- BEGIN: session_true -->
		<!-- BEGIN: order_true -->
		<h2 class="txt22 txt-darkpurple">{LANG_ORDER_LIST}</h2>
		
			<!-- BEGIN: make_payment -->
			<p class="completepay txt-darkpurple">{LANG_MAKE_PAYMENT}</p>
			<!-- END: end_payment -->
           <div class="vieworder">	

	<table width="100%" border="0" cellpadding="3" cellspacing="0" >
      <tr class="trorder ">
        <td class="tdcartTitle" align="center" width="170"><strong>Phone Case/Model</strong></td>
        <td class="tdcartTitle" align="center" ><strong>Order date/Time</strong></td>
         <td class="tdcartTitle">{LANG_STA}</td>
          <td class="tdcartTitle">Order Notes</td>
           <td class="tdcartTitle">Quantity</td>
             <td class="tdcartTitle">Order Design</td>
        <td align="center" class="tdcartTitle" ><strong>{LANG_PRICE}</strong></td>
      </tr>
	 <!-- BEGIN: repeat_products -->
     <tr style="height:4px;">
          <td colspan="7"></td>
          </tr>
	  <tr style="height:55px; background:#f0f0f0; margin-bottom:2px;">
        <td class="{TD_CLASS}"  align="center" >
        <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border:none">
          <tr>
           
            <td align="center">{VAL_PRODUCT}</td>
          </tr>
          
          
     
        </table>
		</td>
         <td class="{TD_CLASS}" align="center">{VAL_TIMEE}<!--<span class="txt9 txt-grey">01:09 AM</span>--></td>          
		<td class="{TD_CLASS}" align="center">{VAL_STAT}</td>
        <td class="{TD_CLASS}" align="center">{EX_NOTES}</td>
         <td class="{TD_CLASS}" align="center">{VAL_QTY}</td>
         <td class="{TD_CLASS}" align="center"><a href="{VAL_DESIGN}" target="_new"><img src="{VAL_DESIGN}" alt="Your Design" title="Your Design" style="width:50px;" /></a></td>
        <td align="center" class="{TD_CLASS}">{VAL_IND_PRICE}</td>
      </tr>
      
	  <!-- END: repeat_products -->
	 
    </table>
    </div>
    <div class="left" style="width:50%">
    <table border="0" cellspacing="5" cellpadding="3" style="margin-top:10px;">
          
          <tr>
            <td nowrap="nowrap"><strong>{LANG_GATEWAY}</strong></td>
            <td>{VAL_GATEWAY}</td>
          </tr>
            <!-- BEGIN: coments -->
           <tr>
            <td nowrap="nowrap"><strong>Admin Comments</strong></td>
            <td>{COMENTS}</td>
          </tr>
            <!-- END: coments -->
          <tr>
            <td>&nbsp;</td>
            <td><a href="{VAL_SHIP_TRACK}" class="txtLink" target="_blank">{LANG_SHIP_TRACK}</a></td>
          </tr>
        
        </table>
    </div>
			<div class="cartboxRight radius2px"  style="margin-top:10px;">
			<table width="100%" cellspacing="0" style="margin-top:5px;">
        <tr class="tdsotalna">
        
	    <td class="btmSubNav tdsotalna"  width="110px"><span>{LANG_SUBTOTAL}</span></td>
	    <td align="center" class="btmSubNav tdsotalna" width="110px">
		<span>{VAL_SUBTOTAL}</span>		</td>
	  </tr>
      <tr>
	    <td  class="tdsotalna" nowrap="nowrap">{LANG_TOTAL_SHIP}</td>
	    <td align="center" class="tdsotalna">{VAL_TOTAL_SHIP}</td>
	  </tr>
	  <tr>
	    <td class="tdsotalna">{LANG_DISCOUNT}</td>
	    <td align="center" class="tdsotalna">{VAL_DISCOUNT}</td>
	  </tr>
      
	  <tr>
	    <td  class="tdsotalna"><span>{LANG_TOTAL_TAX}</span></td>
	    <td align="center" class="tdsotalna">
		{VAL_TOTAL_TAX}		</td>
	    </tr>
	  
	  <tr>
	    <td style=" background-color:#25292C; height:30px; color:#fff; font-size:20px;"><strong>{LANG_GRAND_TOTAL}</strong></td>
	    <td align="center" class="btmSubNav" style=" background-color:#25292C; color:#fff;">
		<strong>{VAL_GRAND_TOTAL}</strong></td>
	    </tr></table>
        	</div>
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