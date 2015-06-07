<!-- BEGIN: body -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset={VAL_ISO}" />
<title>{META_TITLE}</title>
<meta name="description" content="{META_DESC}" />
<meta name="keywords" content="{META_KEYWORDS}" />
<link href="favicon.ico" rel="icon" type="image/x-icon" />
<!-- BEGIN: headercss -->
<link href="skins/{VAL_SKIN}/styleSheets/default.css" rel="stylesheet" type="text/css" />
<!-- END: headercss -->
<!-- BEGIN: casegram -->
<link href="skins/{VAL_SKIN}/styleSheets/casecustomization.css" rel="stylesheet" type="text/css" />
<!-- END: casegram -->

<script type="text/javascript" src="js/jquery-1.9.1.min.js" ></script>
<script type="text/javascript" src="js/default.js"></script>
<script type="text/javascript" src="datafiles/ImageController.js"></script>
 
<link href="skins/{VAL_SKIN}/styleSheets/uploadfilemulti.css" rel="stylesheet" type="text/css" />

<link href="skins/{VAL_SKIN}/styleSheets/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/fileupload.js"></script>
<script type="text/javascript" src="js/jquery.fileuploadmulti.min.js"></script>
<script type="text/javascript" src="js/jquery.vintage.js"></script>
<script type="text/javascript" src="js/jquery.corner.js"></script>



<!--[if IE]>
<link href="skins/{VAL_SKIN}/styleSheets/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->


	<script type="text/javascript"  language="javascript">
		$(function(){
			$('.jqsel').jqTransform({imgPath:'jqtransformplugin/img/'});
		});
	</script>
    <script type="text/javascript" charset="utf-8">
		$(function () {
			var tabContainers = $('div.tabs > div');
			tabContainers.hide().filter(':first').show();
			
			$('div.tabs ul.tabNavigation a').click(function () {
				tabContainers.hide();
				tabContainers.filter(this.hash).show();
				$('div.tabs ul.tabNavigation a').removeClass('selected');
				$(this).addClass('selected');
				return false;
			}).filter(':first').click();
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
<div aria-hidden="true" class="saving-overlay hide"></div>
<div class="toaster error-color pt1 pb1 hide" id="toaster" >
       <div class="wrapper text-center" id="toaster-text">Please choose a case before saving</div>
      </div>
<div class="maindiv bgtop"  style="{CUSTOMCASE}">
  <div class="maincenter">
    <div class="header">
    	<a href="index.php" class="logobox" >
     		<img alt="" src="skins/{VAL_SKIN}/styleImages/logo.png"  class="logo"  title="Logo"/>
      	</a>
      
      <div class="shoping">
         {SHOPPING_CART} 
         {SESSION} 
	   <!--  {SEARCH_FORM}  -->          
        </div>
        
      {MENU} 
     
    {CARTPOPUP}
     </div>
    </div>
</div>   

<div class="maindiv contentbg">

	{PAGE_CONTENT} 
</div>

    
<!-- BEGIN: footer -->

<div class="maindiv footerbg">
  <div class="maincenter">
    <div class="footer">
    <div  class="fcolumn lesss">
    	<h4 class="txt18 lucidaBold ">Design Your case</h4>
        <ul class="latoLight">
        <!-- BEGIN: all_models -->
        	<li><a href="CaseCustomization-{MODEL_NAME}-model_{MODEL_ID}.html">{MODEL_NAME}</a></li>
         <!-- END: all_models -->
        </ul>
    </div> 
    <div  class="fcolumn lesss">
    
    
    
    {SITE_DOCS}
    
    
    
    
    
    </div>
    <div  class="fcolumn maxx">
    	<h4 class="txt18 lucidaBold">From Facebook</h4>
        <ul class="list-unstyled">
        <!-- BEGIN: fbpost_true -->
        
        <!-- BEGIN: repeat_posts -->
                <li>
                 <img alt="" src="{POST_PIC}"  />{POST_MSG}<br />
                  <span><a href="{POST_LINK}" target="_blank">{POST_DAYS} days ago</a></span>
                </li>
                
                 <!-- END: repeat_posts -->
                <!-- END: fbpost_true -->
            </ul>
    </div>
    <div class="sociallinks">
        {SOCIAL_LINKS}
       <div class="maindiv">
       		{MAIL_LIST}
       </div>
      </div>
       <div class="docsite">
       <div class="left latoLight" style="width:430px; ">
           <span class="maindiv">Copyright &copy; 2014 - Pair Mobile Repairs Dublin. All rights reserved.</span>
        
        </div>
        <div class="left"  style="padding:15px 0 0 20px"><img alt="" src="skins/{VAL_SKIN}/styleImages/visa.jpg"  /></div>
        <span class="right" style="padding-top:22px">Website design by: 
        	<span class="txt14 latoBold" >
        	 <a href="http://www.imeicart.com/" style="color:#fff" > IMEICart </a>
             </span>
        </span>
       </div>
        
    </div>
    {SKIN}
  </div>
</div>

 <!-- END: footer -->


  {DEBUG_INFO}
  <script type="text/javascript" src="js/common.js"></script>
</body>

</html>
<!-- END: body -->

