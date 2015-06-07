<!-- BEGIN: language -->
<div class="boxTitleLeft">{LANG_LANGUAGE_TITLE}</div>
<div class="boxContentLeft">
	<select name="lang" class="dropDown" onchange="jumpMenu('parent',this,0)">
		<!-- BEGIN: option -->
		<option value="index.php?_g=sw&amp;r={VAL_CURRENT_PAGE}&amp;lang={LANG_VAL}" {LANG_SELECTED} onmouseover="javascript:getImage('language/{LANG_VAL}/flag.gif');">{LANG_NAME}</option>
		<!-- END: option -->
	</select>
	
	<img src="language/{ICON_FLAG}" alt="" width="21" height="14" id="img" title="" /> 

</div>
<!-- END: language -->	