<!-- BEGIN: forgot_pass -->
 <div class="maindiv loginbox3">
     
    <div class="boxtop" >
     
       <div class="headingboxtop">
        {LANG_FORGOT_PASS_TITLE}
       </div>
       
       <div class="" align="center" style="clear:both" >
      <!-- BEGIN: error -->
	<p class="txtError" align="center">{VAL_ERROR}</p>
	<!-- END: error -->
	
	<p class="txt-grey"  align="center">{FORGOT_PASS_STATUS}</p>
	
	<!-- BEGIN: form -->
	<form action="index.php?_a=forgotPass" target="_self" method="post">
      <div class="loginleft2">
		<table border="0" cellspacing="10" cellpadding="3" align="center" width="100%">
			<tr>
				<td>
                <div class="maindiv">
         <label class="txt18 txt-grey">{LANG_EMAIL}</label>
               </div>
               <div class="maindiv">
       		<div class="txtboxmain">
            <span class="txtboxmain-left"></span>
              <input type="text" name="email"  />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_email" class="maindiv errormessage"></span>
         </div>
                </td>
			</tr>
			<!-- BEGIN: spambot -->
				<tr>
				  <td align="right" valign="bottom"><strong>{TXT_SPAMBOT}</strong></td>
                  </tr>
                  <tr>
				  <td>{IMG_SPAMBOT}<br />
				  <input name="spamcode" type="text" class="textbox" value="" size="5" maxlength="5" /></td>
				</tr>
			<!-- END: spambot -->
			<!-- BEGIN: recaptcha -->
				<tr>
				  <td align="right" valign="top"><strong>{TXT_SPAMBOT}</strong></td>
                  </tr>
                  <tr>
				  <td>{RECAPTCHA}</td>
				</tr>
			<!-- END: recaptcha -->
			<tr>
				<td><input name="ESC" type="hidden" value="{VAL_ESC}" /></td>
				
			</tr>
	  </table>

	  <div class="footerlogin">
       
        
        	 <a href="index.php?_a=login" class="forgetpass txt18  txt-grey"><u>Back to Login</u></a>
             <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin" style="float:none" />
              
       
       </div>
       </div>
     </form>
	<!-- END: form -->

</div>

</div>

</div>
<!-- END: forgot_pass -->