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
<link href="skins/{VAL_SKIN}/styleSheets/lightbox.css" rel="stylesheet" type="text/css" media="all, screen"  />
<!--[if IE]>
<link href="skins/{VAL_SKIN}/styleSheets/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript">
var fileBottomNavCloseImage = '{VAL_ROOTREL}images/lightbox/close.gif';
var fileLoadingImage = '{VAL_ROOTREL}images/lightbox/loading.gif';
</script>
<script type="text/javascript" src="js/jslibrary.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<script>
 var RecaptchaOptions = {
    theme : 'custom'
 }
</script>
</head>

<body onload="initialiseMenu();{EXTRA_EVENTS}">
  <div id="pageSurround">
	<div id="topHeader">
	  <div>{SEARCH_FORM}</div>
	  <div>{SESSION}</div>
	</div>
  <div>

  <div class="colLeft">
	{CATEGORIES}
	{RANDOM_PROD}		
	{INFORMATION}	
	{CURRENCY}
	{LANGUAGE}
  </div>
	
  <div class="colMid">
	{PAGE_CONTENT}
  </div>
	
  <div class="colRight">
		{SHOPPING_CART}
		{POPULAR_PRODUCTS}
		{SALE_ITEMS}
		{MAIL_LIST}
  </div>
</div>

<br clear="all" />

<span style="float: right;">{SKIN}</span>

<br clear="all" />

<div>{SITE_DOCS}</div>

</div>
{DEBUG_INFO}
</body>
</html>
<!-- END: body -->