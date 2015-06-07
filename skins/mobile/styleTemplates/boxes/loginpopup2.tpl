<!-- BEGIN: loginpopup -->

<div id="login-box" class="login-popup">

            <form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post" onsubmit="javascript: return getLoginDetails();">
        <a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a>
          <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo.jpg" /></a>
          <div class="logintitle">Log in</div>
          <div class="loginbox1">
          <div style=" text-align:right; width:424px;">
         
          <table border="0" cellpadding="0" cellspacing="10" >
          <tr><td></td>
          <td nowrap="nowrap" height="14px" align="left"><span id="Id_error" style="color:#F00"></span></td>
          </tr>
          
          <tr>
          <td nowrap="nowrap"><label>Email Address:</label></td>
          <td nowrap="nowrap"><input type="text" name="username" id="username" class=" textbox" value="{VAL_USERNAME}" > <span class="star" id="msg">*</span></td>
          </tr>
          <tr>
          <td><label>Password:</label></td>
          <td align="left"><input type="password" name= "password" id="password" class=" textbox"> <span class="star">*</span></td>
          </tr>
          <tr>
          <td></td>
          <td align="left" valign="middle"><input type="checkbox" name="remember" style="float:left;"/><a href=""><label class="reme">Remember me</label></a></td>
          </tr>
          </table>
          
                    </div>
              
    </div>
    <div class="botombox">
    <input type="submit" value="" class="lsubmit" /><br />
  
    <label class="fields">All fields marked with <span style="color:#fff; font-family:Verdana, Geneva, sans-serif;">* </span>are mandatory</label>
    			</div>
                  </form>
                <div class="botombox2">
                <label><span class="pinkclr">HELP! </span>I forgot my password</label><label><br />I don't have an account <span class="pinkclr">Please register me free</span></label>
    </div>
		</div>
<!-- END: loginpopup -->