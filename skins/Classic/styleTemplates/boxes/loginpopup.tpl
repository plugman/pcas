<!-- BEGIN: loginpopup -->

<div id="login-box" class="login-popup">
  <form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post" id="frmLoginBox">
    <input type="hidden" name="username" id="email" />
    <input type="hidden" name="remember" id="remember" />
    <input type="hidden" name="password" id="password" />
     <input type="hidden" name="redir" id="redir" />
  </form>
  <form action="" target="_self" method="post" onsubmit="javascript: return false;">
    <a href="#" class="close"><img src="skins/{VAL_SKIN}/styleImages/close.png" class="btn_close" title="Close Window" alt="Close" /></a> <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/logo.jpg" /></a>
    <div class="logintitle">Log in</div>
    <div class="loginbox1">
      <div style=" text-align:right; width:424px;">
        <table border="0" cellpadding="0" cellspacing="10" >
          <tr>
            <td></td>
            <td nowrap="nowrap" height="14" align="left"><span id="loginFailedBox_error" style="color:#F00;display:none; ">Login failed!</span></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label>Email Address:</label></td>
            <td nowrap="nowrap"><input type="text" name="login_username" id="login_email" class=" textbox" value="{VAL_USERNAME}"  />
              <span class="star" id="msg">*</span></td>
          </tr>
          <tr>
            <td><label>Password:</label></td>
            <td align="left"><input type="password" name="login_password" id="login_password" class=" textbox" />
              <span class="star">*</span></td>
          </tr>
          <tr>
            <td></td>
            <td align="left"><input type="checkbox" name="remember"  id="login_remember" value="1" {CHECKBOX_STATUS}/>
              <label class="reme">Remember me</label></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="botombox">
      <input type="submit" value="" class="lsubmit" id="loginAuthenticate" onclick="loginAuth();"   />
      <img src="skins/{VAL_SKIN}/styleImages/lsubmit.png" id="loader"  alt="" style="width:133px; height:31px; display:none" />
      <br />
      <label class="fields">All fields marked with <span style="color:#fff; font-family:Verdana, Geneva, sans-serif;">* </span>are mandatory</label>
            <input type="hidden" value="" id="logredir" name="loginredirect" />
    </div>
  </form>
  <div class="botombox2">
    <label><a href="index.php?_a=forgotPass" class="pinkclr">HELP ! </a>&nbsp;I forgot my password</label>
    <label><br />
      I don't have an account <a href="#reg-box" class="close login-window pinkclr">Please register me free</a></label>
  </div>
</div>
<!-- END: loginpopup -->