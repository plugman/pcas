<!-- BEGIN: forgot_pass -->

<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php">Home</a> <span class="breadSeprator"></span> 
        {LANG_FORGOT_PASS_TITLE}
      </div>
    </div>     
    <div class="maincenter">


       <div class="maindiv mainbox" >
       
      
      
      <!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	
	<p class="txt-grey" style="padding:0 0 0 30px; float:left;">{FORGOT_PASS_STATUS}</p>
	
	<!-- BEGIN: form -->
	<form action="index.php?_a=forgotPass" target="_self" method="post">
      <div class="maindiv">
		<table border="0" cellspacing="10" cellpadding="3" align="center">
			<tr>
				<td>
                <div class="maindiv" style="margin-left:21px;">
         <label class="txt18 txt-grey maindiv">{LANG_EMAIL}</label>
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
</div>
	  <div class="maindiv"  style="width:50%">
       
        <center>
        	 <a href="index.php?_a=reg" class="forgetpass txt18  txt-grey">Back to Login</a><br />
             <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin button radius3px" />
              
        </center>
       </div>
     </form>
	<!-- END: form -->

</div>

</div>


<!-- END: forgot_pass -->