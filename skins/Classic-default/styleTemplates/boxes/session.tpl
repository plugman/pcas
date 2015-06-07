<!-- BEGIN: session -->
  <!-- BEGIN: session_false -->
	<span class="txtSession">{LANG_WELCOME_GUEST} [</span><a href="index.php?_a=login&amp;redir={VAL_SELF}" class="txtSession">{LANG_LOGIN}</a> <span class="txtSession">|</span> <a href="index.php?_g=co&amp;_a=reg&amp;redir={VAL_SELF}" class="txtSession">{LANG_REGISTER}</a><span class="txtSession">]</span>
  <!-- END: session_false -->
  <!-- BEGIN: session_true -->
	<span class="txtSession">{LANG_WELCOME_BACK} {TXT_USERNAME} [</span><a href="index.php?_a=logout" class="txtSession">{LANG_LOGOUT}</a> <span class="txtSession">|</span> <a href="index.php?_a=account" class="txtSession">{LANG_YOUR_ACCOUNT}</a><span class="txtSession">]</span>
  <!-- END: session_true -->
<!-- END: session -->