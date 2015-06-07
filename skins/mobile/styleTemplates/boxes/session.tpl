<!-- BEGIN: session -->
<!-- BEGIN: session_false --><!--<span>{LANG_WELCOME_GUEST} </span>-->
  <a href="{LOGIN}" class="login" >{LANG_LOGIN}</a>
<a href="{REGISTER}" class="register">{LANG_REGISTER}</a>
<!--<a href="{ORDERLOOKUP}" class="lookup">&nbsp;  Order lookup </a> |-->
<!-- END: session_false -->
  <!-- BEGIN: session_true -->
 <!-- <span >{LANG_WELCOME_BACK}&nbsp;{TXT_USERNAME}! | &nbsp;-->
  <a href="" style=" float:left;margin-left:-320px; font-size:12px; font-weight:bold;">
  {LANG_WELCOME_BACK}&nbsp;{TXT_USERNAME}!</a>
  <a href="{BALANCE}" style=" float:left;margin-left:-185px; font-size:12px; font-weight:bold;">
   Available Balance: {VAL_BALANCE} </a>
  <a href="{LOGOUT}"  class="login">{LANG_LOGOUT}</a>
  <a href="{ACCOUNT}" class="register">{LANG_YOUR_ACCOUNT}</a>
  <!-- END: session_true -->
<!-- END: session -->
