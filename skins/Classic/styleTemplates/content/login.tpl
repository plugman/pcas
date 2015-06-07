<!-- BEGIN: login -->
<div id="fb-root" style="float:left; width:1px;"></div>
<script type="text/javascript" src="js/commonjs.js"></script>
<div class="maindiv breadbg">
      <div class=" maincenter">
        <a href="index.php">Home</a> <span class="breadSeprator"></span>{LANG_LOGIN_TITLE}
        
      </div>
    </div>

     
    <div class="maincenter">
    	<h2 class="mainheading">{LANG_LOGIN_TITLE}</h2> 
     <div class="maindiv ">
     <div class="loginleft">
        <center>
     	<a href="#" class="facebooklogin radius3px txt18" id="facebookreg">with facebook</a>
        <a href="#" class="instagramlogin radius3px txt18" id="instagramreg">with Instagram</a>
        </center>
        <div class="loginseprator"><span>OR</span></div>
        <div class="loginleftInner">
     	  <!-- BEGIN: form -->
          <form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post">
        
          <div  class="maindiv"> <br />
        <p class="txt14 txtblue lucidaBold">{LOGIN_STATUS}<br /></p>
       		<label class="txt18 txt-grey">Email Address:</label><span class="required">*</span>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="text" name="username"  value="{VAL_USERNAME}"  required="required" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <div class="maindiv">
            <label class="txt18 txt-grey">Password:</label><span class="required">*</span>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <input type="password" name="password" required="required" />
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                <input name="remember" class="left" type="checkbox" value="1" {CHECKBOX_STATUS} /> &nbsp;
            		{LANG_REMEMBER}
            	</td>
                <td align="right"><a href="ForgotPassword.html" class="forgetpass txt14  txtorange"><u>{LANG_FORGOT_PASS}</u></a></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                <input name="submit" type="submit" value="{TXT_LOGIN}" class="submitlogin button radius3px" />
                </td>
                
              </tr>
              <tr>
                <td colspan="2" align="center">
                <p class="txt14 latoLight footerlogin-left">
                    Don't have an account?
                    <a href="Register.html" class="txtorange" ><u> Create an Account.</u></a>
                </p>
              
                </td>
                
              </tr>
            </table>

           
            </div>
       </div>
        </form>
       
        <!-- END: form --> 
        </div>
     </div>
     
       
        
       
       
     
     
    </div>

</div>
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
<!-- END: login -->

