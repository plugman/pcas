<!-- BEGIN: mail_list -->
<div class="mailinglist">
<div class=" txt22 latoBold">{LANG_MAIL_LIST_TITLE}</div>
<div class="boxContentRightt">
	{LANG_MAIL_LIST_DESC}
	<!-- BEGIN: form -->
	<form action="{FORM_METHOD}" method="post">
	<!--<strong>{LANG_EMAIL}</strong>-->
	<input name="email" type="text" size="14" maxlength="255" class="textbox2 radius3px" value="{LANG_EMAIL_ADDRESS}" onclick="this.value='';" /> 
	<input type="hidden" name="act" value="mailList" />
	
		<input name="submit" type="submit" value="" class="go" />
	
	</form>
	<!-- END: form -->
</div>
</div>
<!-- END: mail_list -->