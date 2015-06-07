<!-- BEGIN: reg -->
{JS_COUNTY_OPTIONS}
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         Login or create an account
      </div>
    </div>
<div class="maindiv patern">
     
    <div class="maincenter">
        
     <div class="maindiv  register-content">
     <div class="loginleft">
     	<div  class="loginheading txt18 lucidaBold">Login</div>
        <div class="loginleftInner">
     	  <!-- BEGIN: form -->
        <form action="index.php?_a=reg&amp;redir={VAL_SELF}" target="_self" method="post">
          <div  class="maindiv">
        <p class="txt14 txtblue lucidaBold">{LOGIN_STATUS} <br /><br /></p>
       		<label class="txt18 txt-grey">Email Address:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name="username"  value="{VAL_USERNAME}" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <div class="maindiv">
            <label class="txt18 txt-grey">Password:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="password" name="password" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
            <center>
            	<input name="submit" type="submit" value="{TXT_LOGIN}" class="submitlogin button radius3px" /><br />
            	<a href="ForgotPassword.html" class="forgetpass txt14  txt-grey">{LANG_FORGOT_PASS}</a>
               
               </center>
            </div>
       </div>
        </form>
        <!-- END: form --> 
        </div>
     </div>
     <form name="registerForm" method="post" action="{VAL_ACTION}">
      <div class="loginright ">
      
       	 <div  class="loginheading txt18 lucidaBold">Register</div>
           <!-- BEGIN: error -->
        <p class="txtError"><span class="errbg"> {VAL_ERROR}</span></p>
        <!-- END: error -->
       
      
        	<!-- BEGIN: no_error -->
          <!--  <p>{LANG_REGISTER_DESC}</p>-->
            <!-- END: no_error -->
         <div class="loginleftInner">
        
	
       	<div class="maindiv">
           <label class="txt18 txt-grey maindiv">Full Name:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input type="text" name="firstName"  id="fname"   tabindex="1" value="{VAL_FIRST_NAME}"/>
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
		
       	<div class="maindiv">
         <label class="txt18 txt-grey maindiv">Email Address:</label>
       		<div class="txtboxmain">
            <span class="txtboxmain-left"></span>
           <input type="text" name="email"  id="txtEmail"  tabindex="2" onblur="javascript:EmailExist();" value="{VAL_EMAIL}"/>
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_email" class="maindiv errormessage"></span>
         </div>
       	<div class="maindiv">
            <label class="txt18 txt-grey maindiv">Phone</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name= "phone"  id="txtphone"  value="{VAL_PHONE}" tabindex="3"/>
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_phone" class="maindiv errormessage" ></span>
        </div>
<!--<div class="maindiv">
            <label class="txt18 txt-grey maindiv">Country</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <select name="country" class="refered" tabindex="6" onchange="updateCounty(this.form);">
          <!-- BEGIN: repeat_countries -->
          <option value="{VAL_COUNTRY_ID}" {VAL_COUNTRY_SELECTED}>{VAL_COUNTRY_NAME}</option>
          <!-- END: repeat_countries -->
        </select>
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_phone" class="maindiv errormessage" ></span>
        </div>-->
       	<div class="maindiv">
            <label class="txt18 txt-grey maindiv">Choose Password:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="password" name="password"  id="txtpwd"  value="{VAL_PASSWORD}" tabindex="4" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_pwd" class="maindiv errormessage"></span>
            </div>
        
       	<div class="maindiv">
           <label class="txt18 txt-grey">Confirm Password:</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="password" name= "passwordConf"  id="cpassword"  value="{VAL_PASSWORD_CONF}" tabindex="5"/>
             
             <span class="txtboxmain-right">
             	<span class="mandatory" ></span>
             </span>
            </div>
            <span class="maindiv errormessage" id="err_cpassword "></span>
            </div>
          
         <div class="maindiv">
             
             <div class="checkboxreg" id="tco" onclick="changebg();">
            <!-- <input type="checkbox" name="wholesaler_request" value="1"  />-->
             <input type="checkbox"  name="tandc" id="tacond"  />
             </div>
             <div class=" label3 txt-purple">
                 <a href="{LINK_TANDCS}" target="_blank" class="txt-grey txt14">{LANG_PLEASE_READ} {LANG_TANDCS}</a>
              </div>
         
         </div>
         <div class="maindiv">
         
         <div class="checkboxreg checkboxcheck" id="tco2" onclick="changebg2();">
        <!-- <input type="checkbox" name="wholesaler_request" value="1"  />-->
        <!-- <input type="checkbox"  name="tandc" id="tacond2" checked="checked"  />-->
         </div>
         <div class=" label3 txt-purple">
         	 <a  class="txt-grey txt14">shipping details are same as billing details </a>
          </div>
         
         </div>
         	<div id="billing" style="display:none">
            
            <div class="maindiv">
           <label class="txt18 txt-grey">Address</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name= "add_1"   value="" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory" ></span>
             </span>
            </div>
           
            </div>
            <div class="maindiv">
           <label class="txt18 txt-grey">city</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name= "town"  value="" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory" ></span>
             </span>
            </div>
            
            </div>
            <div class="maindiv">
           <label class="txt18 txt-grey">Post Code</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name= "postcode"  value="" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory" ></span>
             </span>
            </div>
            
            </div>
            <div class="maindiv">
           <label class="txt18 txt-grey">State</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name= "State"  value="" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory" ></span>
             </span>
            </div>
            
            </div>
         </div>
         
         
         <center>
             <a href="javascript:submitDoc('registerForm');">
             <input type="submit" value="Register" class="submitlogin button radius3px" />
             </a> 
             </center>   
             </form>  
          </div>
       </div>
        
     
    </div>
<div class="maindiv  hf">
  <div class="maincenter">
    
  </div>
</div>
</div>
</div>


<!-- END: reg --> 
