<!-- BEGIN: unsubscribe -->
<div class="boxContent">

	<span class="txtContentTitle">{UNSUBSCRIBE_TITLE}</span>
	<!-- BEGIN: no_error -->
	<p>{LANG_UNSUBSCRIBE_DESC}</p>
	<!-- END: no_error -->
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->

	<!-- BEGIN: form -->
	<form action="index.php?_a=unsubscribe" target="_self" method="post" style="text-align: center;">
		<strong>{TXT_ENTER_EMAIL}</strong><input type="text" name="email" class="textbox" />
		<input name="submit" type="submit" value="{TXT_SUBMIT}" class="submit" />
	</form>
	<!-- END: form -->
	
</div>
<!-- END: unsubscribe -->