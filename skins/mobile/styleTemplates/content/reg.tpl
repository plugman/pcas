<!-- BEGIN: reg -->

{JS_COUNTY_OPTIONS}
<div class="maindiv mainbox2">
  <form name="registerForm" method="post" action="{VAL_ACTION}" onsubmit="javascript:return ValidateRegister();">
    <div class="headingbox"> <span class="txt30  heading">Create Your Account</span> </div>
    <div class="loginright"> 
      <!-- BEGIN: no_error -->
      <p style="color:#fff;" align="center">{LANG_REGISTER_DESC}</p>
      <!-- END: no_error --> 
      <!-- BEGIN: error -->
      <p class="txtError">{VAL_ERROR}</p>
      <!-- END: error -->
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Full Name:</label>
          <input type="text" name="firstName"  id="fname"   tabindex="1" value="{VAL_FIRST_NAME}"/>
          <span class="mandatory"></span> </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Email Address:</label>
          <input type="text" name="email"  id="txtEmail"  tabindex="2" onblur="javascript:EmailExist();" value="{VAL_EMAIL}"/>
          <span class="mandatory"></span> </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Phone</label>
          <input type="text" name= "phone"  id="txtphone"  value="{VAL_PHONE}" tabindex="3"/>
          <span class="mandatory"></span> </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Mobile</label>
          <input type="text" name= "mobile"  id="txtphone"  value="{VAL_MOBILE}" />
        </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Choose Password:</label>
          <input type="password" name="password"  id="txtpwd"  value="{VAL_PASSWORD}" tabindex="4" />
          <span class="mandatory"></span> </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Confirm Password:</label>
          <input type="password" name= "passwordConf"  id="cpassword"  value="{VAL_PASSWORD_CONF}" tabindex="5"/>
          <span class="mandatory"></span> </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Address</label>
          <input name="add_1" type="text" id="add_1" size="16" value="{VAL_ADD_1}" tabindex="8" />
        </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Town/City</label>
          <input name="town" type="text" id="town" size="16" value="{VAL_TOWN}" tabindex="10" />
        </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">Zip</label>
          <input name="postcode" type="text" id="postcode" size="16" value="{VAL_POSTCODE}" tabindex="11" />
        </div>
      </div>
      <div class="maindiv" align="center">
        <div class="txtboxmain3">
          <label class="txt18">State</label>
          <input name="county" type="text" id="county" value="{VAL_DEL_COUNTY}" maxlength="50"  tabindex="13" />
          <input type="hidden"  name="sameaddress"  value="1"  />
        </div>
      </div>
      <div class="maindiv" align="center">
        <div  style="width:370px;">
          <div class="checkboxreg" id="tco" onclick="changebg();"> 
            <!-- <input type="checkbox" name="wholesaler_request" value="1"  />-->
            <input type="checkbox"  name="tandc" id="tacond"  />
          </div>
          <div class=" label3 txt-purple"> <a href="{LINK_TANDCS}" target="_blank"  style="color:#fff"><u>{LANG_PLEASE_READ} {LANG_TANDCS}</u></a> </div>
        </div>
      </div>
      <div class="maindiv" align="center">
        <div  style="width:370px;">
          <div class="checkboxreg" id="tco1" onclick="changebg1();">
            <input type="checkbox"  name="wholesaler_request" id="sameaddress" value="1"  />
          </div>
          <div class=" label3 txt-purple"> <span class="txt-grey txt14">Apply for Wholesaler</span> </div>
        </div>
      </div>
    </div>
    <div class="maindiv footerlogin">
      <center>
        <a href="javascript:submitDoc('registerForm');">
        <input type="submit" value="Register" class="submitlogin" / style="float:none;">
        </a>
      </center>
    </div>
  </form>
</div>

<!-- END: reg --> 
