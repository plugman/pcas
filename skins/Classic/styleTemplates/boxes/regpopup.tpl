<!-- BEGIN: regpopup -->

<div id="reg-box" class="reg-popup"> <a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a> <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo.jpg" /></a>
  <div class="logintitle">I would like to create an account</div>
  <form action="index.php?_g=co&amp;_a=reg" target="_self" method="post" onsubmit="javascript:return ValidateRegister();">
  <input type="hidden" id="email_exist_flag" name="email_exist_flag" value="" />
    <div class="loginbox2">
      <div style=" text-align:right; width:535px;">
        <table  border="0" cellspacing="10" cellpadding="0" width="100%">
        
          <tr>
            <td ></td>
            <td id="err_fname" align="left" height="20" style="color:#F00;"></td> <td width="100" nowrap="nowrap"></td>
          </tr>
        
          <tr>
            <td align="right"><label>Full Name: </label></td>
            <td nowrap="nowrap" align="left"><input type="text" name="firstName"  id="fname" class=" textbox" size="16"  tabindex="1"/>
              <span class="star">*</span></td> <td  width="100" nowrap="nowrap"></td>
          </tr>
         
          <tr>
            <td align="right"><label >Email: </label></td>
            <td nowrap="nowrap" align="left"><div id="mbrEmailExist" style="height:0px; float:right;"></div><input type="text" name="email"  id="txtEmail" class=" textbox" size="16" tabindex="2" onblur="javascript:EmailExist();"/>
              <span class="star">*</span> </td><td id="err_email" width="100" nowrap="nowrap" align="left" style="color:#F00;"></td>
          </tr>
          
          <!--  <tr>
            <td>Customer Type:</td>
            <td nowrap="nowrap" align="left">
				<select name="customer_type"  class="refered">
               
                <option value="0">Individual</option>
                    <option value="1">Wholeseller</option>
              
              </select>
			  </td>
           
          </tr>-->
        
          <tr>
            <td align="right"><label >Phone: </label></td>
            <td align="left"><input type="text" name= "phone"  id="txtphone" class=" textbox" size="16" value="{VAL_PHONE}" tabindex="3"/>
              <span class="star">*</span></td><td id="err_phone" width="100" nowrap="nowrap" align="left" height="20" style="color:#F00;"></td>
          </tr>
        
            
         
          <tr>
            <td align="right"><label >Password: </label></td>
            <td align="left"><input type="password" name="password"  id="txtpwd" class=" textbox" size="16" value="{VAL_PASSWORD}" tabindex="4" />
              <span class="star">*</span></td><td id="err_pwd" width="100" nowrap="nowrap" align="left" height="20" style="color:#F00;"></td>
          </tr>
         
          <tr>
            <td align="right"><label>Confirm Password: </label></td>
            <td align="left"><input type="password" name= "passwordConf"  id="cpassword" class=" textbox" size="16" value="{VAL_PASSWORD_CONF}" tabindex="5"/>
              <span class="star">*</span></td> <td id="err_cpassword" width="100" nowrap="nowrap" align="left" height="20" style="color:#F00;"></td>
          </tr>
          <tr>
            <td align="right"><label>Referred By:</label></td>
            <td align="left"><select class="refered" name="refered" style="padding-right:5px;">
            <option value="Anam Khan">Anam Khan</option>
            <option value="Sara Connor">Sara Connor</option>
            <option value="Usman Butt">Usman Butt</option>
            <option value="Google">Google</option>
            <option value="Facebook">Facebook</option>
            <option selected="selected" value="Other">Other</option>
            </select>
            <input type="hidden" name="optIn1st" value="1" {VAL_OPTIN1ST_CHECKED}/>
</td>
</tr>

         <!-- <tr>
            <td align="right"><label>Skype IM:</label></td>
            <td align="left"><input type="text" name= "skype" class=" textbox" size="16" value="{VAL_SKYPE_IM}" tabindex="6">
</td>
          </tr>-->
          <tr style="display:none;">
       <td></td>
            <td align="left"><input type="checkbox" checked="checked" name="tandc" id="tacon"  />
              </td>    <td width="100" nowrap="nowrap" align="left" height="20" style="color:#F00;"></td>
          </tr>
           <tr>
       <td></td>
            <td align="left"><input type="checkbox" name="wholesaler_request" value="1" />
              <label class="reme">Apply for Wholesaler</label></td>    <td id="tacond" width="100" nowrap="nowrap" align="left" height="20" style="color:#F00;"></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="botombox">
      <input type="submit"  value="" class="regisbtn" />
      <br />
      <label class="fields">All fields marked with <span style="color:#fff; font-family:Verdana, Geneva, sans-serif;">* </span>are mandatory</label>
    </div>
  </form>
</div>
<!-- END: regpopup -->