<!-- BEGIN: session -->
<!-- BEGIN: session_false --><span>{LANG_WELCOME_GUEST} </span>
  <a href="#login-box" class="login-window" ><span class="login">{LANG_LOGIN}</span></a>|
<a href="#reg-box" class="login-window"><span class="register">{LANG_REGISTER}</span></a>|<a href="index.php?_a=orderlookup" class="lookup">&nbsp;  Order lookup </a> |
<!-- END: session_false -->
  <!-- BEGIN: session_true -->
  <span >{LANG_WELCOME_BACK}&nbsp;{TXT_USERNAME}! | &nbsp;<a href="{BALANCE}" style="color:#fff; font-size:12px; font-weight:bold;"> Available Balance: {VAL_BALANCE} </a>&nbsp; | &nbsp; </span><a href="index.php?_a=logout" >  {LANG_LOGOUT} </a> &nbsp; | <a href="{ACCOUNT}" class="txtSession">&nbsp;  {LANG_YOUR_ACCOUNT} </a>&nbsp;| 
  <!-- END: session_true -->
<!-- END: session -->
<!-- BEGIN: session_false -->
	<span class="txtSession">{LANG_WELCOME_GUEST} [</span><a href="index.php?_a=login&amp;redir={VAL_SELF}" class="txtSession">{LANG_LOGIN}</a> <span class="txtSession">|</span> <a href="index.php?_g=co&amp;_a=reg&amp;redir={VAL_SELF}" class="txtSession">{LANG_REGISTER}</a><span class="txtSession">]</span>
  <!-- END: session_false -->
  <!-- BEGIN: session_true -->
	<span class="txtSession">{LANG_WELCOME_BACK} {TXT_USERNAME} [</span><a href="index.php?_a=logout" class="txtSession">{LANG_LOGOUT}</a> <span class="txtSession">|</span> <a href="index.php?_a=account" class="txtSession">{LANG_YOUR_ACCOUNT}</a><span class="txtSession">]</span>
  <!-- END: session_true -->
  <a href="index.php?_a=login&amp;redir={VAL_SELF}" ><span class="login">{LANG_LOGIN}</span></a>|
<a href="index.php?_g=co&amp;_a=reg&amp;redir={VAL_SELF}"><span class="register">{LANG_REGISTER}</span></a>|