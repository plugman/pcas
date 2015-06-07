<!-- BEGIN: view_cart -->

{JS_COUNTY_OPTIONS}
<div class="maincenter"> 
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a> <span class="breadSeprator"></span> Shopping Cart </div>
</div>
<h2 class="mainheading" style="padding: 19px 0px 4px; font-weight: normal;">{LANG_VIEW_CART}</h2>
 <!-- BEGIN: checkout -->
 <div class="checkoutprogress"><span class="checkleft">Step:{CHCKSTEP}</span><span class="checktitle">{CHCKTIT}</span></div>
  <!-- END: checkout -->
  <!-- BEGIN: cart_false -->
   <div class="account sitedoc">
  <!--  <p>{LANG_CART_EMPTY}</p>-->
  <h2 class="mainheading" style="padding: 19px 0px 4px; font-weight: normal;">You have no items!</h2>
<center><a href="CaseCustomization.html" class="submitlogin button radius3px">Make a Case</a>
<a href="Gallery.html"  class="submitlogin button radius3px">	Go to Gallery</a></center>
  </div>
  <!-- END: cart_false -->
   <!-- BEGIN: customer_profile -->
   <div class="account sitedoc">
  <form action="index.php?_g=co&_a=step2" target="_self" method="post">
  
           <!-- BEGIN: error -->
        <p class="txtError"><span class="errbg"> {VAL_ERROR}</span></p>
        <!-- END: error -->
        <div class="txtdiv">
           <label class="txt22 txt-grey txtdiv paddingbottom">Your Personal Details</label>
       		
            </div>
             <div class="txtdiv">
           <label class="txt22 txt-grey txtdiv paddingbottom">Your Delivery Details</label>
       		
            </div>
        <div class="txtdiv">
           <label class="txt14 txt-grey ">First Name</label> <span class="required">*</span>
       		<div class="txtboxmain">
             
            <input type="text" name="firstName"  id="fname"    value="{VAL_FIRST_NAME}" required="required" />
            
             
            </div>
            </div>
            <div class="txtdiv">
           <label class="txt14 txt-grey ">Address</label><span class="required">*</span>
       		<div class="txtboxmain">
             
            <input name="add_1" type="text" id="add_1" size="16" value="{VAL_ADD_1}" required="required" /> 
             
             
            </div>
            </div>
            <div class="txtdiv">
              <label class="txt14 txt-grey ">Last Name</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text" name="lastName"  id="lastName"    value="{VAL_LAST_NAME}"/>
                <span class="txtboxmain-right"> </span> </div>
            </div>
            <div class="txtdiv">
         <label class="txt14 txt-grey ">Town/City</label><span class="required">*</span>
       		<div class="txtboxmain">
            
          <input name="town" type="text" id="dtown" size="16" value="{VAL_TOWN}" tabindex="12" required="required" />
              
            
            </div>
            <span id="err_email" class="txtdiv errormessage"></span>
         </div>
        <div class="txtdiv">
           <label class="txt14 txt-grey ">Email</label><span class="required">*</span>
       		<div class="txtboxmain">            
           <input type="text" name="email"  id="txtEmail"   onblur="javascript:EmailExist();" value="{VAL_EMAIL}" required="required" />  
            </div>
            <span id="err_email" class="txtdiv errormessage"></span>
         </div>   
         <div class="txtdiv">
            <label class="txt14 txt-grey txtdiv">Zip</label>
       		<div class="txtboxmain">
             
            <input name="postcode" type="text" id=d"postcode" size="16" value="{VAL_POSTCODE}" tabindex="13" />
             
            </div>
            <span id="err_phone" class="txtdiv errormessage" ></span>
        </div>
         <div class="txtdiv">
              <label class="txt14 txt-grey ">Phone</label> <span class="required">*</span>
              <div class="txtboxmain"> 
                <input name="phone" type="text" id="phone" size="16" value="{VAL_PHONE}"   required="required" />
                </div>
             </div>

        <div class="txtdiv">
           <label class="txt14 txt-grey ">State</label><span class="required">*</span>
       		<div class="txtboxmain">
             
            <input name="county" type="text" id="county" value="{VAL_COUNTY}" maxlength="50"  tabindex="14" required="required" />
             
              
            </div>
            </div>
            <!-- BEGIN: register -->
            <div class="txtdiv">
              <label class="txt14 txt-grey ">Choose Password</label> <span class="required">*</span> 
              <div class="txtboxmain"> 
                <input type="password" name="password"  id="txtpwd"  value="{VAL_PASSWORD}" tabindex="5"  required="required"/>
               </div>
              <span id="err_pwd" class="maindiv errormessage"></span> </div>
            <div class="txtdiv">
              <label class="txt14 txt-grey ">Confirm Password</label> <span class="required">*</span>
              <div class="txtboxmain"> 
                <input type="password" name= "passwordConf"  id="cpassword"  value="{VAL_PASSWORD_CONF}" tabindex="6"  required="required"/>
                </div>
              <span class="maindiv errormessage" id="err_cpassword "></span> </div>
              <div class="maindiv">
              <div class="checkboxreg" id="tco" onclick="changebg();"> 
                <input type="checkbox"  name="tandc" id="tacond"  />
              </div>
              <div class=" label3 txt-purple"> <span class="txt-grey txt14">{LANG_PLEASE_READ} </span><strong ><a href="{LINK_TANDCS}" target="_blank" class="txtorange txt14"> {LANG_TANDCS}</a></strong> </div>
            </div>
        
              <!-- END: register -->
             <input name="submit" type="submit" value="{UPDATE}" class="submitlogin button radius3px" />
            </form>
            
  </div>
  <!-- END: customer_profile -->
  <!-- BEGIN: customer_login -->
   <div class="account sitedoc">
    
        <div class="txtdiv" style="width:460px;">
           <label class="txt22 txt-grey txtdiv paddingbottom">Register</label>
       		 <div class="chckregister">
             <span class="purp">Register with us for future convenience:</span><br />
              <span class="chckreg">Register</span>
              <span class="purp">Register and save Time:</span>
                <span class="chckreg2 chckreg">Easy checkout.</span>
                  <span class="chckreg2 chckreg">Easy access</span>
             </div>
              <center><a href="index.php?_g=co&_a=step2" class="submitlogin button radius3px" style="margin-top:20px;" >Continue</a></center>
            </div>
             <div class="txtdiv">
           <label class="txt22 txt-grey txtdiv paddingbottom" style="margin-left:20px;">Already Registered</label>
           <span class="chckpupr">Sign Up</span>
       		<div class="loginleft" style="border:none; margin-left:0">
   <!-- BEGIN: error -->
        <p class="txtError"><span class="errbg"> {VAL_ERROR}</span></p>
        <!-- END: error -->
        <center>
     	<a href="#" class="facebooklogin radius3px txt18" id="facebookreg">with facebook</a>
        <a href="#" class="instagramlogin radius3px txt18" id="instagramreg">with Instagram</a>
        </center>
        <div class="loginseprator"><span>OR</span></div>
        <span class="chckpupr" style=" margin:0; padding:20px 0 7px 0;">Login</span>
        <div class="loginleftInner">
     	     <div id="fb-root" style="float:left; width:1px;"></div>
            <script type="text/javascript" src="js/commonjs.js"></script>
          <form action="index.php?_g=co&_a=step1" target="_self" method="post">
        
          <div  class="maindiv">
        <p class="txt14 txtblue lucidaBold">{LOGIN_STATUS} <br /></p>
       		<label class="txt18 txt-grey">Email Address:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name="username"  value="{VAL_USERNAME}" required="required" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <div class="maindiv">
            <label class="txt18 txt-grey">Password:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="password" name="password"  required="required" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
              <tr>
                <td colspan="2" align="center">
                <input name="submit" type="submit" value="{TXT_LOGIN}" class="submitlogin button radius3px" />
                </td>
                
              </tr>
              <tr>
               
                <td align="center" colspan="2"><a href="ForgotPassword.html" class="forgetpass txt14  txtorange"><u>{LANG_FORGOT_PASS}</u></a></td>
              </tr>
            </table>

           
            </div>
       </div>
        </form>
       <form name="registerForm" method="post" action="index.php?_g=co&_a=step2" id="registerform" class="hide">
       <input type="hidden" name="fName"     value="{VAL_FIRST_NAME}"/>
       <input type="hidden" name="lName"    value="{VAL_LAST_NAME}"/>
        <input type="hidden" name="email2"    value="{VAL_EMAIL}"  />
         <input name="town2" type="hidden"  size="16" value="{VAL_TOWN}" />
          <input type="hidden" value="" name="socialreg"  />
                <input type="hidden" value="" name="profilepic"  />
                <input type="hidden" value="" name="coverpic"  />
                
      </form>
       <form name="registerForm" method="post" action="index.php?_g=co&_a=step1" id="logform" class="hide" >
       <input type="hidden" name="username"  value=""  />
        <input type="hidden" name="username2"  value=""  />
      
          <input type="hidden" value="" name="sociallog"  />
            <input type="password" name="password" />
            
      </form>
        
        </div>
     </div>
            </div>
   
   </div>
   <!-- END: customer_login -->
  <!-- BEGIN: cart_true -->
  <form name="cart" method="post" id="cart" action="{VAL_FORM_ACTION}">
  <div class="cartboxLeft">
  

   
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    
      <!-- BEGIN: repeat_cart_contents -->
      <tr>
      	<td align="center" width="150" class="{TD_CART_CLASS}"><img src="{VAL_IMG_SRC}" alt="" title="" /></td>
        <td  class="{TD_CART_CLASS}" width="234" valign="top">
        	<span class="txt14 latoBold">Device: &nbsp; <span class="latoLight">{VAL_PRODUCT_NAME}</span></span>
          <!-- BEGIN: options -->
          <br />
          <span class="txt14 latoBold">{VAL_OPT_NAME}: &nbsp; <span class="latoLight">{VAL_OPT_VALUE}</span></span>
          
          <!-- END: options -->
         <br />
        
         <!-- BEGIN: quanEnabled -->
          <input name="quan[{VAL_PRODUCT_KEY}]" type="text" value="{VAL_QUANTITY}" size="2" class="quantity radius3px txt18 latoLight"  {QUAN_DISABLED} />
          <!-- END: quanEnabled -->
          <!-- BEGIN: quanDisabled -->
          <input name="quan[{VAL_PRODUCT_KEY}]" type="text" value="{VAL_QUANTITY}" size="2" class="textboxDisabled quantity radius3px txt18 latoLight" style="text-align:center;" disabled="disabled" />
          <input name="quan[{VAL_PRODUCT_KEY}]" type="hidden" value="{VAL_QUANTITY}" />
          <!-- END: quanDisabled -->
        <br />
        
        <a href="index.php?_g=co&amp;_a={VAL_CURRENT_STEP}&amp;remove={VAL_PRODUCT_KEY}" class="txt14 latoLight remove">Remove</a></td>
       
        <!--<td align="center" class="{TD_CART_CLASS}">{VAL_PRODUCT_CODE}</td>
        <td align="center" class="{TD_CART_CLASS}">{VAL_INSTOCK}</td>
        <td align="right" class="{TD_CART_CLASS}">{VAL_IND_PRICE}</td>-->
        <td   class="{TD_CART_CLASS} txt18 txt-grey latoBold" valign="top">{VAL_LINE_PRICE}</td>
      </tr>
      <!-- BEGIN: stock_warn -->
      <tr>
        <td align="center" class="{TD_CART_CLASS}">&nbsp;</td>
        <td colspan="2" align="left" class="{TD_CART_CLASS}"><span class="txtStockWarn">{VAL_STOCK_WARN}</span></td>
      </tr>
      <!-- END: stock_warn -->
      <!-- END: repeat_cart_contents -->
	</table>
    
  </div>
  <div class="cartboxRight radius2px" >
  	<h2 class="txt18 txtorange" >Cart Summary</h2>
    
	<table width="100%" border="0" cellpadding="3" cellspacing="0" class="summary">
      
      <tr>
        <td  >{LANG_SUBTOTAL}</td>
        <td  >{VAL_SUBTOTAL}</td>
      </tr>
     
      <tr>
   
        <td  >{LANG_TAX}</td>
        <td  >{VAL_TAX}</td>
      </tr>
      <!-- BEGIN: repeat_more_taxes -->
      <tr>
        <td  >{LANG_TAX}</td>
        <td  >{VAL_TAX}</td>
      </tr>
      <!-- END: repeat_more_taxes -->
      <tr>
        <td   width="165" class="tdCartSubTotal">{LANG_DISCOUNT}</td>
        <td class="tdCartSubTotal">{VAL_DISCOUNT}</td>
      </tr>
      <tfoot>
      <tr>
        <td  >{LANG_CART_TOTAL}</td>
        <td  >{VAL_CART_TOTAL}</td>
      </tr>
     </tfoot>
    </table>
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
     <!-- BEGIN: coupon_code_result -->
      <tr>
        <td align="center" style="padding-top: 7px; padding-bottom: 7px;"><strong>{LANG_CODE_RESULT}</strong>
          <!-- BEGIN: remove -->
          <a href="{VAL_CURRENT_PAGE}&amp;remCode={VAL_OLD_CODE}" class="txtDefault" style="color:#fff;">{LANG_CODE_REMOVE}</a>
          <!-- END: remove -->
        </td>
      </tr>
      <!-- END: coupon_code_result -->
      <!-- BEGIN: enter_coupon_code -->
      <tr>
      <!--  <td >{LANG_GOT_CODE} {LANG_ENTER_CODE}</td>-->
        <td align="left" nowrap="nowrap" >
        <div class="coupnBox">
        <input name="coupon" type="text" value="{LANG_ENTER_CODE}" class="textbox radius3px" onclick="if(this.value=='{LANG_ENTER_CODE}')this.value=''"  onblur="if(this.value=='')this.value='{LANG_ENTER_CODE}'" />
        <a href="javascript:submitDoc('cart');" class="txtUpdate radius3px"></a>
        
         <br />
        {VAL_SHIPPING}
        
        </div>
        </td>
      </tr>
      <!-- END: enter_coupon_code -->
      </table>
      <center>
       <a href="{CONT_VAL}" class="button radius3px">{LANG_CHECKOUT_BTN}</a><br />
    
    <a href="index.php" class="txt14 txtorange continue" style="font-family:Arial, Helvetica, sans-serif;">
    	Continue shopping
    </a><br />
    
   <a href="javascript:submitDoc('cart');" class="txtUpdate txtorange">{LANG_UPDATE_CART}</a> <br />{LANG_UPDATE_CART_DESC}
    </center>
    </div>
  </form>
  <!-- BEGIN: alt_checkout -->
  <br clear="all" />
  <p class="txtContentTitle" style="text-align: right; margin-right: 30px; font-weight: bold">{LANG_ALTERNATIVE_CHECKOUT}</p>
  <!-- BEGIN: custom_warn -->
  <p class="txtError">{LANG_CUSTOM_WARN}</p>
  <!-- END: custom_warn -->
  <div style="text-align:right">
  <!-- BEGIN: loop_button -->
  {IMG_CHECKOUT_ALT}<br />
  <!-- END: loop_button -->
  </div>
  <!-- END: alt_checkout -->
  <br clear="all" />
  <!-- END: cart_true -->
</div>
<!-- END: view_cart -->
