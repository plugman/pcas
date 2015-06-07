<!-- BEGIN: view_orders -->
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php">Home</a> <span class="breadSeprator"></span> <a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span> Your Orders
    <h2 class="mainheading" style="margin:10px 0;">  <center>Your Orders</center></h2>
        
      </div>
    </div>
<div class="maincenter">
   <div class="mainbox">   
	
	
	<!-- BEGIN: session_true -->
	<div class="allorders" >
       
       <table width="100%" border="0" cellpadding="3" cellspacing="0">
		  <tr class="trorder">
		    <td align="center" class="tdcartTitle">Date</td>
            <td align="center" class="tdcartTitle">Order ID</td>
			<td align="center" class="tdcartTitle">Phone Case/Model</td>
			<td align="center" class="tdcartTitle">Design Title</td>
			<td align="center" class="tdcartTitle">QTY</td>
            <td align="center" class="tdcartTitle">Status</td>
		  </tr>
		  <!-- BEGIN: allorders -->
		  <tr style="height:55px; background:#f0f0f0; margin-bottom:2px;">
          <td align="center" class="{TD_CART_CLASS}">{VAL_DATE_TIME}</td>
		    <td align="center" class="{TD_CART_CLASS}"><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{DATA.cart_order_id}</a></td>
            
            <td align="center" class="{TD_CART_CLASS}">
            <table>
            <!-- BEGIN: allnetworks -->
            <tr>
            <td align="center" class="paddingtd" {BORDER_STYLE}><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{VAL_PRO_NAME}</a></td>
            </tr>
            <!-- END: allnetworks -->
            </table>
            </td>
            <td align="center" class="{TD_CART_CLASS}"><table>
            <!-- BEGIN: dname -->
            <tr>
            <td class="paddingtd" {BORDER_STYLE} ><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{VAL_DESIGN_NAME}</a></td>
            </tr>
            <!-- END: dname -->
            </table></td>
			<td align="center" class="{TD_CART_CLASS}"> <table>
            <!-- BEGIN: allimei -->
            <tr>
            <td class="paddingtd" {BORDER_STYLE} ><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{VAL_PRO_QTY}</a></td>
            </tr>
            <!-- END: allimei -->
            </table></td>
			
			<td align="center" class="{TD_CART_CLASS}">
            <!-- BEGIN: make_payment -->
			
			<span class="orstatus radius3px"><a href="index.php?_g=co&amp;_a=step4&amp;cart_order_id={DATA.cart_order_id}" class="txtLink" style="color:#fff;">Complete Payment</a></span>
			<br />
            <!-- END: make_payment -->
             <a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLink">View Details</a>
			
			</td>
		  </tr>
          <tr style="height:2px;">
          <td colspan="6"></td>
          </tr>
		  <!-- END: allorders -->
           <!-- BEGIN: noorders -->
           <tr>
           <td colspan="6"><span class="nopro">{TXT_NO_ORDERS}</span>
           </td>
           </tr>
            <!-- END: noorders -->
	  </table>
        </div>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	 <div class="allorders" style="min-height:300px; margin-bottom:20px; padding:10px; width:930px;">
	<p>{LANG_LOGIN_REQUIRED}</p>
    </div>
	<!-- END: session_false -->
	</div>		
</div>

<!-- END: view_orders -->