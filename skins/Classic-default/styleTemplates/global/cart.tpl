<!-- BEGIN: body -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={VAL_ISO}" />
<title>{META_TITLE}</title>
<meta name="description" content="{META_DESC}" />
<meta name="keywords" content="{META_KEYWORDS}" />
<link href="skins/{VAL_SKIN}/styleSheets/layout.css" rel="stylesheet" type="text/css" />
<link href="skins/{VAL_SKIN}/styleSheets/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jslibrary.js"></script>
<script>
 var RecaptchaOptions = {
    theme : 'custom'
 }
</script>
</head>

<body onload="initialiseMenu();">
<div id="pageSurround">
	<div id="topHeader">
		<div>{SEARCH_FORM}</div>
		<div>{SESSION}</div>
	</div>
<div>

	<div class="colLeftCheckout">
		{CART_NAVI}	
	</div>
	
	<div class="colMainCheckout">
		{PAGE_CONTENT}
	</div>

</div>

<br clear="all" />

{SITE_DOCS}

</div>

{DEBUG_INFO}

</body>
</html>
<!-- END: body -->