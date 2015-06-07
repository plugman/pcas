<!-- BEGIN: cartpopup -->
  <input type="hidden" name="storeurl" value="{STOREURL}"  id="storeurl"/>
{JS_COUNTY_OPTIONS}
<div id="cart-box" class="login-popup"> 
<div class="cartheader">
<a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a> 
<a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo.png" /></a>

  <div class="ordtotal">
  <table cellpadding="0" cellspacing="5" border="0">
  <!-- BEGIN: paypalfee -->
  <tr>
  	<td class="ord">{LANG_PAYPAL}</td>
    <td class="ordprice" id="paypalfee">{VAL_PAYPAL_FEE}</td></tr>
    <!-- END: paypalfee -->
    <tr>
  	<td class="ord">{LANG_SHIPPING}</td>
    <td class="ordprice">{VAL_SHIPPING}</td></tr>
    <tr>
  	<td class="ord">{LANG_DISCOUNT}</td>
    <td class="ordprice">{VAL_DISCOUNT}</td></tr>
    <tr>
  	<td class="ord">{LANG_TAX}</td>
    <td class="ordprice">{VAL_TAX}</td></tr>
      <tr>
       <!-- BEGIN: repeat_more_taxes -->
      <tr>
        <td class="ord">{LANG_TAX}</td>
        <td class="ordprice">{VAL_TAX}</td>
      </tr>
      <!-- END: repeat_more_taxes -->
  	<td class="ord">{LANG_CART_TOTAL}</td>
    <td class="ordprice" id="IdCartTotal">{VAL_CART_TOTAL}</td></tr>
    </table>
   </div>
<div class="carttitle"  id="IdCartItem">Your shopping Basket has {VAL_CART_ITEMS} item </div>
   
  </div>
  <!-- BEGIN: cart_true -->
 
  <form name="cart" method="post" id="cart" action="{VAL_FORM_ACTION}">
    
      <div class="cartpopup radius3px">
      <table border="0" cellpadding="0" cellspacing="0" width="630">
        <tr style="height:31px;"  class="topheading">
         	<td >
               <span class="left" style="padding-left:20px;">Order Details</span>
               <span class="right" style="padding-right:53px;">Line Price</span>
               <span class="right">{LANG_QTY}</span>
               <span class="right">Unit Price</span>
            </td>
        </tr>
		<tr >
            <td >
                <div id="IdBasketData">
                    <table cellpadding="0" cellspacing="0" width="630">
                
                <!-- BEGIN: repeat_cart_contents -->
                 <tr>
                    <td align="center"  width="85">
                        <img alt="" class="imgcartpopup radius3px" src="{VAL_IMG_SRC}"  />
                    </td>
                    <td  width="250">
                    <span class="pname">{LANG_P_NAME}</span><br />
                    {VAL_PRODUCT_NAME}
                    <span class="pname">{LANG_DEV}</span>
                    {VAL_DELTIME}
                    <span class="pname">{LANG_IMEI}</span>
                        {VAL_IMEI} 
                         <!-- BEGIN: options -->
                       
                    <span class="pname">  {VAL_OPT_NAME}</span><br />
                    {VAL_OPT_VALUE}
                     <br />
                    <!-- END: options -->
                    </td>
                    <td align="left"  width="86" > {VAL_UNITPRICE}</td>
                    <td align="center"> 
                    <!-- BEGIN: quanEnabled -->
         <input name="quan[{VAL_PRODUCT_KEY}]" type="text" value="{VAL_QUANTITY}" class="quantityPopup radius3px" {QUAN_DISABLED} />
                    <!-- END: quanEnabled -->
                    <!-- BEGIN: quanDisabled -->
         <input name="quan[{VAL_PRODUCT_KEY}]" type="text" value="{VAL_QUANTITY}" class="quantityPopup radius3px" disabled="disabled" />
                    <input name="quan[{VAL_PRODUCT_KEY}]" type="hidden" value="{VAL_QUANTITY}" />
                    <!-- END: quanDisabled -->
                    
                    </td>
                    <td  align="center" > {VAL_LINE_PRICE}</td> 
                    <td width="50" align="center"> <a onclick="RemoveProduct('{VAL_PRODUCT_KEY}','{VAL_PRODUCT_ID}');" href="javascript:void(0);" class="removeedit">&nbsp; </a></td>
                
               
                
                </tr>
        
                <!-- END: repeat_cart_contents -->
                </table>
                </div>
            </td>
        </tr>
        </table>
        </div>
      
        
         
     
      <div class="botombox3"> 
      <a href="{CONT_VAL}" class="{CLASS_CHECKOUT} button" onclick="loginredir()">make payment</a> 
      <a href="index.php" class="pinkclr">Continue Shopping</a> 
      </div>
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
   
  </form>
  
  <!-- END: cart_true --> 
  
  <!-- BEGIN: cart_false -->
  <p style=" padding:20px 8px;" class="txt-darkpurple">{LANG_CART_EMPTY}</p>
  <!-- END: cart_false --> 
</div>

<!-- END: cartpopup --> 

