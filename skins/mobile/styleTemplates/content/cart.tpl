<!-- BEGIN: view_cart -->

{JS_COUNTY_OPTIONS}

<!--<h3 class="h3arial">
  {LANG_VIEW_CART} 
</h3>
-->
<div class="boxContent"  style="min-height:300px;"> 
 
  
  <!-- BEGIN: cart_false -->
  <p class="carttitle">{LANG_CART_EMPTY}</p>
  <!-- END: cart_false -->
   <!-- BEGIN: cart_true -->
  
 <table width="100%" border="0" cellspacing="5" cellpadding="0" class="total">
  <tr>
    <td><strong>PayPal Processing Fee:</strong></td>
    <td width="70" class="txtpurplelight"><strong>{VAL_LINE_PRICE}</strong></td>
  </tr>
  <tr>
    <td><strong>{LANG_CART_TOTAL}</strong></td>
    <td class="txtpurplelight"><strong>{VAL_CART_TOTAL}</strong></td>
  </tr>
</table>

   
  <div class="carttitle" style="float:none;" id="IdCartItem">Your shopping Basket has {VAL_CART_ITEMS} Items </div>
  <form name="cart" method="post" id="cart" action="{VAL_FORM_ACTION}">
    <div id="IdBasketData">
      <div class="cartpopup">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr style="height:55px;" bgcolor="#9171ce" class="topheading">
          <td align="left"  width="550" style="padding-left:12px;">Order Details
         
            </td>
          <td align="left" style="padding-left:50px;">Price </td>
        </tr>
          <tr>
        	<td colspan="2">
           
       
      
        <!-- BEGIN: repeat_cart_contents -->
      
         <table border="0" cellpadding="0" cellspacing="0" width="100%">
         
        
        <tr class="detailrow">
          <td align="center" width="170"><img alt="" src="{VAL_IMG_SRC}"  /><br />
            <span class="removeedit"> <!--<a onclick="RemoveProduct('{VAL_PRODUCT_KEY}','{VAL_PRODUCT_ID}');" href="javascript:void(0);" class="removeedit">Remove </a>-->
            <a  href="index.php?_g=co&amp;_a={VAL_CURRENT_STEP}&amp;remove={VAL_PRODUCT_KEY}" class="removeedit">Remove </a>
            </span>
            
            </td>
          <td width="400">
          <table class="detailrow2" cellspacing="3" >
              <tr  >
                <td align="right" class="rcolr"> Network: </td>
                <td align="left" width="200" style="display:inline-block;">{VAL_PRODUCT_NAME}</td>
              </tr>
              <tr>
                <td align="right" class="rcolr" nowrap="nowrap"> Dilivery time: </td>
                <td align="left">{VAL_DELTIME}</td>
              </tr>
              
              
               <!-- BEGIN: options -->
                <tr>
                    <td align="right" class="rcolr" nowrap="nowrap"> {VAL_OPT_NAME}</td>
                    <td align="left">{VAL_OPT_VALUE}</td>
                </tr>	
         		<!-- END: options -->
                <tr>
                <td align="right" class="rcolr" nowrap="nowrap"> IMEI # </td>
                <td align="left"   style="display:inline-table">{VAL_IMEI}</td>
              </tr>
                 
            </table>
          </td>
          <td align="left" class="white" style="padding-left:42px; font-size:14px"> {VAL_LINE_PRICE}</td>
        </tr>
        </table>
        <!-- END: repeat_cart_contents -->
        </td>
        </tr>
              
        </table>
      </div>
      
      <div class="coupons" id="IdCouponCodeDiv">
    
	<!-- BEGIN: enter_coupon_code -->
	<span class="coupontxt">Add a gift certificate or coupon code</span>
<br />
    <input type="text" name="coupon" id="txtcoupon" value="Enter code...." onclick="if(this.value=='Enter code....')this.value=''"  class="textbox2"/>
    <a href="javascript:void(0);"  onclick="BasketPage();" class="button txtshadow"> Apply Now</a>
	<!-- END: enter_coupon_code -->  
    <!-- BEGIN: coupon_code_result -->
      <span class="coupontxt"> {LANG_CODE_RESULT}</span>
        <!-- BEGIN: remove -->
         <a href="javascript:void(0);" onclick="RemoveCouponCode('{VAL_OLD_CODE}');" style="color:#F00;">{LANG_CODE_REMOVE}</a>
        <!-- END: remove -->
     
    <!-- END: coupon_code_result --> 
</div>
	  <div class="botombox3"> 
          <a href="index.php" class="button" style="float:left; margin:0 10px 0 0;" >Continue Shopping</a>
          <a href="{CONT_VAL}" class="{CLASS_CHECKOUT} button" onclick="loginredir()">Make Payment</a>
      </div>
    </div>
  </form>
  <!-- END: cart_true --> 
   
</div>

<!-- END: view_cart -->

    
    