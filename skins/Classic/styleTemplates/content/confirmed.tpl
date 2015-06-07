<!-- BEGIN: confirmation -->
<div class="maindiv breadbg">
      <div class=" maincenter">
     <a href="index.php">Home</a><span class="breadSeprator"></span>
         {LANG_CONFIRMATION_SCREEN}
      </div>
    </div>
<div class="maincenter">
      <div class="mainbox">
      
       
<div class="orderhistory">
	
	

	<div>
		
		<!-- BEGIN: order_success -->
		<p style="margin:10px;">{LANG_ORDER_SUCCESSFUL}</p>
		<!-- END: order_success -->
		
		<!-- BEGIN: order_processing -->
		<p style="margin:10px;">{LANG_ORDER_PROCESSING}</p>
		<!-- END: order_processing -->
		
		<!-- BEGIN: order_failed -->
		<p style="margin:10px;">{LANG_ORDER_FAILED}</p>
		<p style="margin:10px;">{LANG_ORDER_RETRY}</p>
		<div style="text-align: center; padding: 10px;"><a href="index.php?_g=co&amp;_a=step3&amp;cart_order_id={VAL_CART_ORDER_ID}"  class="txtCheckout">{LANG_RETRY_BUTTON}</a></div>
		<!-- END: order_failed -->
	</div>
			
</div></div>

</div>

<!-- END: confirmation -->