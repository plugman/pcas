<!-- BEGIN: regpopup -->

<div id="reg-box" class="reg-popup">
        <a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a>
          <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo.jpg" /></a>
          <div class="logintitle">I would like to create an account</div>
          <form action="" target="_self" method="post" onsubmit="ValidateRegister();">
          <div class="loginbox2">
          <div style=" text-align:right; width:424px;">
          
           <table  border="0" cellspacing="10" cellpadding="" width="100%">
           <tr>
           <td><span id="err_fname"></span></td></tr>
        <tr>
        <td align="right">
       <label>Full Name: </label></td>
       <td nowrap="nowrap" align="left">
        <input type="text" name="firstName"  id="firstName" class=" textbox" size="16" value="{VAL_FIRST_NAME}" tabindex="1">
          <span class="star">*</span></td></tr>
         <tr>
        <td align="right">
              <label >Email: </label></td>
              <td nowrap="nowrap" align="left">
              	<input type="text" name= "email"  id="email" class=" textbox" size="16" value="{VAL_EMAIL}" tabindex="2">
                 <span class="star">*</span>
                 </td></tr>
                 <tr>
        <td align="right">
                 <label >Phone: </label></td>
                 <td align="left">
              	<input type="text" name= "mobile"  id="mobile" class=" textbox" size="16" value="{VAL_PHONE}" tabindex="3">
                 <span class="star">*</span></td></tr>
                 <tr>
        <td align="right">
                 <label >Password: </label></td>
                 <td align="left">
              	<input type="password" name= "password"  id="password" class=" textbox" size="16" value="{VAL_PASSWORD}" tabindex="4" >
                 <span class="star">*</span></td></tr>
                 <tr>
        <td align="right">
                 <label>Confirm Password: </label></td>
                 <td align="left">
              	<input type="password" name= "passwordConf"  id="passwordConf" class=" textbox" size="16" value="{VAL_PASSWORD_CONF}" tabindex="5">
                 <span class="star">*</span></td></tr>
                 <tr>
        <td align="right">
                 <label>Skype IM:</label></td>
                 <td align="left">
              	<input type="text" name= "SkypeIM" class=" textbox" tabindex="6" >
               </td></tr>
                 
                 <tr>
                 <td></td>
                 <td align="left"> <input type="checkbox" name="remember" /><label class="reme">I agree to Terms and Conditions</label></td>
                 </tr>
                 </table>
                    </div>
    </div>
    <div class="botombox">
    <input type="submit"  value="" class="regisbtn" /><br />
    <label class="fields">All fields marked with <span style="color:#fff; font-family:Verdana, Geneva, sans-serif;">* </span>are mandatory</label>
    			</div>
		</form>
        </div>
<!-- END: regpopup -->