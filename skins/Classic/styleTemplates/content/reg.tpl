<!-- BEGIN: reg -->

<div id="fb-root" style="float:left; width:1px;"></div>
<script type="text/javascript" src="js/commonjs.js"></script>
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a> <span class="breadSeprator"></span> {LANG_REGISTER} </div>
</div>
<div class="maindiv" >
  <div class="maincenter">
    <div class="maindiv">
      <h2 class="mainheading">{LANG_REGISTER}</h2>
      <form name="registerForm" method="post" action="{VAL_ACTION}">
        <div class="loginleft "> 
          
          <center>
            <a href="#" class="facebooklogin radius3px txt18" id="facebookreg">with facebook</a> <a href="#" class="instagramlogin radius3px txt18" id="instagramreg">with Instagram</a>
          </center>
          <div class="loginseprator"><span>OR</span></div>
          <!-- BEGIN: error -->
          <br />
          <p class="txtError"><span class="errbg"> {VAL_ERROR}</span></p>
          <!-- END: error --> 
          <!-- BEGIN: no_error --> 
            <p style="padding-top:10px;">{LANG_REGISTER_DESC}</p> 
          <!-- END: no_error -->
          <div class="loginleftInner radius3px">
            <div  class="reginnerheading txt16 lucidaBold ">Your Details</div>
            <div class="maindiv">
              <label class="txt18 txt-grey">First Name</label><span class="required">*</span>
              <div class="txtboxmain"> 
                <input type="text" name="firstName"  id="fname"   tabindex="1" value="{VAL_FIRST_NAME}"/  required="required" >
                 </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Last Name</label>
              <div class="txtboxmain"> 
                <input type="text" name="lastName"  id="lastName"   tabindex="2" value="{VAL_LAST_NAME}"/>
               </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Email Address</label> <span class="required">*</span> 
              <div class="txtboxmain"> 
                <input type="text" name="email"  id="txtEmail"  tabindex="3" onblur="javascript:EmailExist();" value="{VAL_EMAIL}"  required="required"/>
               </div>
              <span id="err_email" class="maindiv errormessage"></span> </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Phone</label> <span class="required">*</span> 
              <div class="txtboxmain"> 
                <input name="phone" type="text" id="phone" size="16" value="{VAL_PHONE}" tabindex="4"   required="required" />
               </div>
              <span id="err_mobile" class="maindiv errormessage" ></span> </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Choose Password</label> <span class="required">*</span>
              <div class="txtboxmain"> 
                <input type="password" name="password"  id="txtpwd"  value="{VAL_PASSWORD}" tabindex="5"  required="required"/>
                </div>
              <span id="err_pwd" class="maindiv errormessage"></span> </div>
            <div class="maindiv">
              <label class="txt18 txt-grey">Confirm Password</label><span class="required">*</span>
              <div class="txtboxmain"> 
                <input type="password" name= "passwordConf"  id="cpassword"  value="{VAL_PASSWORD_CONF}" tabindex="6"  required="required"/>
                 </div>
              <span class="maindiv errormessage" id="err_cpassword "></span> </div>
            <div  class="reginnerheading txt16 lucidaBold">Delivery Address</div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Address</label><span class="required">*</span>
              <div class="txtboxmain"> 
                <input name="add_1" type="text" id="add_1" size="16" value="{VAL_ADD_1}" tabindex="8"  required="required" />
                </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">Town/City</label>  <span class="required">*</span> 
              <div class="txtboxmain">
                <input name="town" type="text" id="town" size="16" value="{VAL_TOWN}" tabindex="10"  required="required" />
             </div>
              <span id="err_town" class="maindiv errormessage"></span> </div>
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">Zip</label>
              <div class="txtboxmain"> 
                <input name="postcode" type="text" id="postcode" size="16" value="{VAL_POSTCODE}" tabindex="11" />
              </div>
              <span id="err_postcode" class="maindiv errormessage" ></span> </div>
            <div class="maindiv">
              <label class="txt18 txt-grey ">State</label>  <span class="required">*</span> 
              <div class="txtboxmain">
                <input name="county" type="text" id="county" value="{VAL_DEL_COUNTY}" maxlength="50"  t   required="required" tabindex="12" />
               </div>
            </div>
            
            <div class="maindiv">
              <div class="checkboxreg" id="optIn1s" onclick="optIn1st();" tabindex="13">
                <input type="checkbox"  name="optIn1st" id="optIn1st" value="1"   />
              </div>
              <div class=" label3 txt-purple"> <span class="txt-grey txt14">Subscribe to mailing list?</span> </div>
            </div>
            
            <div class="maindiv">
              <div class="checkboxreg" id="tco" onclick="changebg();" tabindex="14"> 
                <!-- <input type="checkbox" name="wholesaler_request" value="1"  />-->
                <input type="checkbox"  name="tandc" id="tacond"  />
              </div>
              <div class=" label3 txt-purple"> <span class="txt-grey txt14">{LANG_PLEASE_READ} </span><strong ><a href="{LINK_TANDCS}" target="_blank" class="txtorange txt14"> {LANG_TANDCS}</a></strong> </div>
            </div>
            <center>
              <input type="submit" value="Register" class="submitlogin button radius3px"  tabindex="15" />
              
            </center>
          </div>
        </div>
      </form>
      <form name="registerForm" method="post" action="Register.html" id="registerform" class="hide">
       <input type="hidden" name="fName"     value="{VAL_FIRST_NAME}"/>
       <input type="hidden" name="lName"    value="{VAL_LAST_NAME}"/>
        <input type="hidden" name="email2"    value="{VAL_EMAIL}"  />
         <input name="town2" type="hidden"  size="16" value="{VAL_TOWN}" />
          <input type="hidden" value="" name="socialreg"  />
                <input type="hidden" value="" name="profilepic"  />
                <input type="hidden" value="" name="coverpic"  />
      </form>
       <form name="registerForm" method="post" action="Login.html" id="logform" class="hide" >
        <input type="hidden" name="username"  value=""  />
       <input type="hidden" name="username2"  value=""  />
          <input type="hidden" value="" name="sociallog"  />
            <input type="password" name="password" />
      </form>
    </div>
    <div class="maindiv  hf">
      <div class="maincenter"> </div>
    </div>
  </div>
</div>

<!-- END: reg --> 
