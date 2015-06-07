<!-- BEGIN: cartpopup -->
  <input type="hidden" name="storeurl" value="{STOREURL}"  id="storeurl"/>
{JS_COUNTY_OPTIONS}
<div id="cart-box" class="login-popup"> <a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a> <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo2.jpg" /></a>
  <div class="ordtotal">
  <table cellpadding="0" cellspacing="5" border="0">
  <!-- BEGIN: paypalfee -->
  <tr>
  	<td class="ord">{LANG_PAYPAL}</td>
    <td class="ordprice" id="paypalfee">{VAL_PAYPAL_FEE}</td></tr>
    <!-- END: paypalfee -->
      <tr>
  	<td class="ord">{LANG_CART_TOTAL}</td>
    <td class="ordprice" id="IdCartTotal">{VAL_CART_TOTAL}</td></tr>
    </table>
   </div>
  
  <!-- BEGIN: cart_true -->
  <div class="carttitle" style="float:none;" id="IdCartItem">Your shopping Basket has {VAL_CART_ITEMS} Items </div>
  <form name="cart" method="post" id="cart" action="{VAL_FORM_ACTION}">
    <div id="IdBasketData">
      <div class="cartpopup">
      <table border="0" cellpadding="0" cellspacing="0" width="580">
        <tr style="height:31px;" bgcolor="#9171ce" class="topheading">
          <td align="left"  style="padding-left:12px;">Order Details
         
            </td>
          <td align="right" style="padding-right:54px;">Price </td>
        </tr>
          <tr>
        	<td colspan="2">
            <div style="max-height:264px; overflow:auto; overflow-x:hidden;">
       
      
        <!-- BEGIN: repeat_cart_contents -->
      
         <table border="0" cellpadding="0" cellspacing="0" width="580">
         
        
        <tr class="detailrow">
          <td align="center" width="170"><img alt="" src="{VAL_IMG_SRC}"  /><br />
            <span class="removeedit"> <a onclick="RemoveProduct('{VAL_PRODUCT_KEY}','{VAL_PRODUCT_ID}');" href="javascript:void(0);" class="removeedit">Remove </a></span></td>
          <td width="250">
          <table cellspacing="3" style="color:#FFF; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">
              <tr>
                <td align="right" class="rcolr"> Network: </td>
                <td align="left" width="200" style="display:inline-block;">{VAL_PRODUCT_NAME}</td>
              </tr>
              <tr>
                <td align="right" class="rcolr" nowrap="nowrap"> Dilivery time: </td>
                <td align="left">{VAL_DELTIME}</td>
              </tr>
              <tr>
                <td align="right" class="rcolr" nowrap="nowrap"> IMEI # </td>
                <td align="left"   style="display:inline-table">{VAL_IMEI}</td>
              </tr>
              
               <!-- BEGIN: options -->
                <tr>
                    <td align="right" class="rcolr" nowrap="nowrap"> {VAL_OPT_NAME}</td>
                    <td align="left">{VAL_OPT_VALUE}</td>
                </tr>	
         		<!-- END: options -->
                 
            </table></td>
          <td align="center" class="white" style="padding-right:22px;"> {VAL_LINE_PRICE}</td>
        </tr>
        </table>
        
        

        <!-- END: repeat_cart_contents -->
          
        </div>
        </td>
       
        </tr>
        
              </table>
      </div>
      <div class="botombox3"> <a href="index.php" class="pinkclr" style="float:left; margin:16px 10px 0 0;" >Continue Shopping</a> <a href="{CONT_VAL}" class="{CLASS_CHECKOUT} button" onclick="loginredir()">make payment</a> </div>
      <div class="coupons" id="IdCouponCodeDiv">
    
	<!-- BEGIN: enter_coupon_code -->
	<span class="coupontxt">Add a gift certificate or coupon code</span>

    <input type="text" name="coupon" id="txtcoupon" value="Enter code...." onclick="if(this.value=='Enter code....')this.value=''"  class="textbox2"/>
    <a href="javascript:void(0);"  onclick="BasketPage();" class="button"> Apply Now</a>
	<!-- END: enter_coupon_code -->  
    <!-- BEGIN: coupon_code_result -->
      <span class="coupontxt"> {LANG_CODE_RESULT}</span>
        <!-- BEGIN: remove -->
         <a href="javascript:void(0);" onclick="RemoveCouponCode('{VAL_OLD_CODE}');" style="color:#F00;">{LANG_CODE_REMOVE}</a>
        <!-- END: remove -->
     
    <!-- END: coupon_code_result --> 
</div>
    </div>
  </form>
  <!-- END: cart_true --> 
  
  <!-- BEGIN: cart_false -->
  <p style=" padding:20px 8px;" class="txt-darkpurple">{LANG_CART_EMPTY}</p>
  <!-- END: cart_false --> 
</div>

<!-- END: cartpopup --> 

