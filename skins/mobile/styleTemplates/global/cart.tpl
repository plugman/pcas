<!-- BEGIN: body -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={VAL_ISO}" />
<title>{META_TITLE}</title>
<meta name="description" content="{META_DESC}" />
<meta name="keywords" content="{META_KEYWORDS}" />
<link href="favicon.ico" rel="icon" type="image/x-icon" />
<link href="skins/{VAL_SKIN}/styleSheets/default.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="skins/{VAL_SKIN}/styleSheets/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="js/jquery-1.6.4.min.js" ></script>
<script type="text/javascript" src="js/default.js"></script>
<script type="text/javascript" src="js/jslibrary.js"></script>
	<script type="text/javascript"  language="javascript">
		$(function(){
			$('.jqsel').jqTransform({imgPath:'jqtransformplugin/img/'});
		});
	</script>
    
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	
	$('a.login-window').click(function() {		
		// Getting the variable's value from a link 
		var loginBox = $(this).attr('href');
	//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append("<div id='mask'></div>");
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('#mask').remove();  
	});$('.close, #mask').live('click', function() { 
	  $('#mask , .login-popup ,.reg-popup').fadeOut(300 , function() {
		 
	return false;
	});
	
});
//]]>
</script>
<script type="text/javascript">
        $(document).ready(function() {
            $("#menus").click(function() {
                $("#menus ul li ul").toggle();
            });
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("menus"))
                    $("#menus ul li ul").hide();
            });
			 $("#menus2").click(function() {
                $("#menus2 ul li ul").toggle();
            });
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("menus"))
                    $("#menus2 ul li ul").hide();
            });
			$(".topbutton").click(function() {
                $("#togglebox").slideToggle();return false;
            });
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("#togglebox"))
                    $("#togglebox").hide();
            });
        });
    </script>
 
<script type="text/javascript">
var fileBottomNavCloseImage = '{VAL_ROOTREL}skins/{VAL_SKIN}/styleImages/lightbox/close.gif';
var fileLoadingImage = '{VAL_ROOTREL}skins/{VAL_SKIN}/styleImages/lightbox/loading.gif';
</script>
<script type="text/javascript" >
 var RecaptchaOptions = {
    theme : 'custom'
 }
</script>
</head>

<body>
<div style="padding:0 10px;">
 
  
    <div class="header">
    <a class="home2" href="index.php" >&nbsp; </a>
    
     <div class="logo">
     <a href="index.php"  >
     	<img alt="" src="skins/{VAL_SKIN}/styleImages/logo.jpg"   title="Logo"/>
       <label class="logotxt">(03)96706719</label>
      </a>
     </div>
      <div class="shoping">
            <p class="topnavigation left"  style="display:inline-block"> 
              {SESSION} 
            </p>
           <!-- <div class="currancybox">
            {CURRENCY}
            </div>-->
        </div>
      <div class="Hshopingcart">
        {SHOPPING_CART} 
        </div>
     
      {MENU}
  <!--  {CARTPOPUP}-->
     </div>
    

<!--  end of header--> 

{PAGE_CONTENT} 

<!--Footer-->



<div class="maindiv footerbg2" style="display:none;">
  <div class="maincenter">
    <div class="footer"> 
       <div class="docsite">
    	{SITE_DOCS}
        <div class="sociallinks">
        <span class="left" style="font-weight: bold;">Stay Connected</span> 
        <a href="{VAL_FBADD}" class="f"></a>
         <a href="{VAL_TWADD}" class="t"></a> 
      </div>
       </div>
        {MAIL_LIST}
    </div>
  </div>
</div>
<div class="maindiv copyrightbg">
  
  	
    <div class="copybox">
        <a href="{VAL_FBADD}" class="f2"><img alt="" src="skins/{VAL_SKIN}/styleImages/f.jpg"   title=""/></a>
     
    <strong>© Copyright 2013. {VAL_StoreName} </strong>
        <span   class=" converter txt18 lucidaBold">{SKIN}</span>
    <!--  <span class="right">This system is powered by <a href="#" class="modindex"> &nbsp; IMEI Unlock Team </a></span> -->
    </div>
  
</div>

 {DEBUG_INFO}
 </div>
</body>
</html>
<!-- END: body -->