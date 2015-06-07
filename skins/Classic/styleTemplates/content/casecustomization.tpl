<!-- BEGIN: casecustomization -->
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
		$(function () {
			var tabContainers = $('div.tabs2 > div');
			tabContainers.hide().filter(':first').show();
			
			$('div.tabs2 ul.tabNavigation2 li a').click(function () {
				tabContainers.hide();
				tabContainers.filter(this.hash).show();
				$('div.tabs2 ul.tabNavigation2 li').removeClass('selected');
				$(this).parent('li').addClass('selected');
				
				return false;
			}).filter(':first').click();
			
			
		});
		
		
		$(function () {
			var tabContainers = $('div.tabs3 > div');
			tabContainers.hide().filter(':first').show();
			
			$('div.tabs3 ul.tabNavigation3 a').click(function () {
				tabContainers.hide();
				tabContainers.filter(this.hash).show();
				$('div.tabs3 ul.tabNavigation3 a').removeClass('selected');
				$(this).addClass('selected');
				return false;
			}).filter(':first').click();
		});
	</script>
<script type="text/javascript">
	$(document).ready(function()
{
	var loader = $('#loader');
	//Fade in the Popup and add close button
		
		//Set the center alignment padding + border
		var loaderTop = ($(loader).height() + 24) / 2; 
		var loaderLeft = ($(loader).width() + 24) / 2; 
		$(loader).css({ 
			'margin-top' : -loaderTop,
			'margin-left' : -loaderLeft
		});
    jQuery.ajaxSetup({
  beforeSend: function() {
     $('#loader').show();
  },
  complete: function(){
     $('#loader').hide();
  },
  success: function() {}
});
});
     </script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/all.js"></script>
<script>
var photosDir = '{USER_DIR}/';

$(document).ready(ImageController.onDocumentReady);


     
</script>
<div aria-hidden="true" class="saving-overlay hide"></div>
<div class="toaster error-color pt1 pb1 hide" id="toaster" >
       <div class="wrapper text-center" id="toaster-text">Please choose a case before saving</div>
      </div>
      <div  id="progress-bar-container" class="absolute f-width hide">
				<div class="table f-width f-height">
					<div class="t-cell f-width f-height vertical-middle">
						<span class="h3-like mt0 mb0 text-main">Saving</span>
						<div class="load-bar clearfix-overflow">
							<div class="f-height relative" id="progress-bar">
								<span class="h3-like mt0 mb0 block f-height absolute text-main" id="progress-text">1%</span>
							</div>
						</div>
					</div>
				</div>
			</div>
<!--<div class="header maindiv" style="width:100%; background:#222; height:75px;"> <a href="index.php" class="logo left" ><img alt="logo" src="skins/{VAL_SKIN}/styleImages/logo.png"  /></a> <a href="#" class="save right" id="save-design">Save</a> <a href="#" class="reset right" id="reset-design">Reset</a> </div>-->
<div id="loader"></div>

<div class="leftpanel">
<div class="tabs2">
        <ul class="tabNavigation2">
            <li class="tab1"> <a class="" href="#fourth">1.Products</a>
            <div class="bulle-explain exp-product hide">
				<p class="text-center mb0">Select your device and design template</p>
				<a href="#" class="exp-next text-main absolute" id="next1">Next</a>
				<a class="absolute skip" href="#">
					X
				</a>
			</div>
            </li>
            <li class="tab2" ><a class="" href="#fifth">2.Photos</a>
            <div class="bulle-explain exp-photos hide" >
				<p class="text-center mb0">Import photos from your social network, then drag-n-drop to select photo</p>
				<a href="#" class="exp-next text-main absolute" id="next2">Next</a>
				<a class="absolute skip" href="#">
					X
				</a>
			</div></li>
            <li class="tab3 selected" ><a  href="#sixth">3.Filters</a>
            <div class="bulle-explain exp-filters hide" data-position="3">
				<p class="text-center mb0">Pick your case style or apply filters</p>
				<a href="#" class="exp-next text-main absolute" id="next3">Next</a>
				<a class="absolute skip" href="#">
					X
				</a>
			</div></li>
        </ul>
        <div  id="fourth">
            <div class="tabs3">
        <ul class="tabNavigation3">
            <li class="tab4" ><a class="" href="#eight">Device</a></li>
           
             <li class="tab5" ><a class="" href="#nine">Case</a></li>
              <li class="tab6" ><a class="selected" href="#ten">Layout</a></li>
        </ul>
       
        <p class="clear"></p>
        <div  id="eight">
        <center>
        	<span class="selectdevice">Choose your device</span>
        </center>
        <p class="clear"></p>
           <!-- <h4 class="heading" onclick="$('#phonecat').slideToggle(); return false;" > <span></span>Choose your device </h4>-->
 			 <ul id="phonecat" >
                <!-- BEGIN: all_models -->
                <li  >
                	<a href="#" id="model-{MODEL_ID}" {IF_ACTIVE}>
                	<img alt="" src="skins/{VAL_SKIN}/styleImages/devicebg.png"   /><br />
                    {MODEL_NAME}
                    </a>
                </li>
                
                <!-- END: all_models -->
             </ul>
        </div>
        <div  id="nine">
            <div class="messagebox">
                {case_desc}
            </div>
            <p class="clear"></p>
            <ul class="case" id="casetype">
            	<li class="active">
                	<a href="#" id="model-{TYPE_ACT}" class="casetype">
                        <img alt="" src="{TYPE_CASE}"   /><br />
                       Slim fit Case
                    </a>
                </li>
                 <!--  BEGIN: all_casetype -->
       <li>
                	<a href="#" id="model-{TYPE_ID}" class="casetype">
                        <img alt="" src="{VALUE_TYPE_SRC}"   /><br />
                       {TYPE_NAME}
                    </a>
                </li>
        <!--  END: all_casetype -->
               
                
                
              
            </ul>
        </div>
        <div  id="ten">
            <div class="layout-panel">
            <center>
        	<span class="selectdevice">Choose your Layout</span>
        </center>
        <p class="clear"></p>
    	
    <div class="layoutbox" id="layoutbox">
      <ul>
        <!--  BEGIN: all_layouts -->
        <li ><img src="{LAYOUT_SRC}" alt="" id="{LAYOUT_ID}" class="layout-icon {IF_LAYOUT}"></li>
        <!--  END: all_layouts -->
      </ul>
    </div>
    <div id="template" style="display:none">
      <ul>
        
        <li class="active"><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        <li><img src="skins/{VAL_SKIN}/styleImages/case/templateicon.jpg" alt=""  ></li>
        
      </ul>
    </div>
  </div>
        </div>
    </div>
        </div>
        <div  id="fifth">

           <div class="photbox">
    <div class="tabs">
      <ul class="tabNavigation">
        <li><a  href="#firstInner"><img alt="instagrame" src="skins/{VAL_SKIN}/styleImages/case/insta.png"  /><br />Instagram</a></li>
        <li><a  href="#secondInner"><img alt="facebook" src="skins/{VAL_SKIN}/styleImages/case/facebook.png"  /><br />Facebook</a></li>
        <li><a  href="#upload-files"><img alt="upload" src="skins/{VAL_SKIN}/styleImages/case/upload.png"  /><br />Upload</a></li>
        <li><a  href="#fourthInner" ><img alt="upload" src="skins/{VAL_SKIN}/styleImages/case/stamp.png"  /><br />Stamp</a></li>
      </ul>
       
      <div  id="firstInner"> 
       
        <div id="ig-content-login">
          <p >Use your Instagram account<br />
to customize your case.</p>
          <div id="instagram-login"> <a href="#" class="facebook-color">Connect to instagram </a> </div>
        </div>
      <div id="ig-photo-area">
          <div class="user-selecter" >
            <div class="your-profile" id="ig-your-profile"> <a href="#">Your Photos</a> </div>
            <div class="your-friends" id="ig-your-friends"> <a href="#"><img alt="" src="skins/{VAL_SKIN}/styleImages/case/friends.jpg"  />Your Friends</a> </div>
          </div>
          <div id="ig-image-box">
          <div id="ig-pictures"> </div>
         
        </div>
       		
        <div class="mt1 reset-list row" id="ig-friends-container">
        <ul id="ig-friend-list"></ul>
        </div>
        <div class="mt1 reset-list row" id="ig-friend-pictures-container" >
        
         <div id="ig-back-to-friend-list" > <a class="back-btn " href="#"> Back to friend List</a> </div>
          <div class="mt1 reset-list row" id="ig-friend-album_pictures" style="height:auto">
        </div>
       
         </div>

      </div>
      
      </div>
      <div  id="secondInner">
       
        <div id="fb-content-login">
            
          <p>Use your Facebook account<br />
to customize your case.</p>
          <div id="facebook-login"> <a href="#" class="facebook-color">Connect to Facebook </a> </div>
        </div>
        <div class="clear"></div>
        <div id="fb-root"></div>
        <div id="album-area">
          <div class="user-selecter" >
            <div class="your-profile" id="fb-your-profile">
             
            <a href="#"><img alt="" src="skins/{VAL_SKIN}/styleImages/case/profile.jpg"  /> Your Album</a> 
            </div>
            <div class="your-friends" id="fb-your-friends"> 
            
            <a href="#"><img alt="" src="skins/{VAL_SKIN}/styleImages/case/friends.jpg"  /> Friends Album</a> 
            </div>
          </div>
          <div class="mt1 reset-list row" id="fb-album-container"></div>
       		
        <div class="mt1 reset-list row" id="fb-friends-container">
        <ul id="fb-friend-list"></ul>
        </div>
        <div class="mt1 reset-list row" id="fb-friend-album-container" >
        <div id="fb-back-to-friend-list">  <a href="#" class="back-btn"> Back to friend List</a> </div>
         <div class="mt1 reset-list row" id="fb-friend-album" style="height:auto">   </div>
         <div id="fb-back-to-friend-album" > <a class="back-btn" href="#"> Back to friend Album</a></div>
          <div class="mt1 reset-list row" id="fb-friend-album_pictures" style="height:auto">
        </div>
        
         </div>
        <div id="fb-image-box">
          <div  id="fb-back"> <a href="#" class="back-btn"> Back to Album </a></div>
          <div id="pictures"> </div>
         
        </div>
       
        <div class="photbox2"> </div>
      </div>
      </div>
      <div  id="upload-files">
        <div id="mulitplefileuploader">Upload</div>
        <div id="status"></div>
        <div id="user-photos">
          <ul class="social-pics">
            <!-- BEGIN: all_userimages -->
            <li class="column4"><div> <i id="{IMAGES_ID}">X</i> <img ondragstart="drag(event)" src="{IMAGE_SRC}" id="userphoto-{IMAGES_ID}" class="dragable-image" source="{IMAGE_SRC}"></div> </li>
            <!-- END: all_userimages -->
          </ul>
        </div>
     
        <input type="hidden" value="{USER_FOL}" id="ccuser" />
        <script>

$(document).ready(function()
{

var settings = {
	url: "ajax/upload.php",
	method: "POST",
	allowedTypes:"jpg,png,gif,jpeg",
	fileName: "myfile",
	multiple: true,
	onSuccess:function(files,data,xhr)
	{
	
		$("#user-photos > ul").append(data);
		callback_reload();
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);

});
</script> 
      </div>
      
      
      <div  id="fourthInner">
       
       
	<ul  id="stamp-detail-list">
    
     <!--  BEGIN: all_stampimages -->
	  <li>
         <a href="#"  id="{STMP_ID}">
            <img alt="stamp" src="{VALUE_THUMB_SRC}"  /><br />
            {STMP_NAME}
         </a>         
      </li>
    <!--  END: all_stampimages -->
	</ul>
		<div id="stmp-image-box" style="display:none">
          <div  id="stmp-back"> 
          <a href="#" class="back-btn "> Go back to stamp </a> 
          </div>
          <ul id="stmp-pictures" class="stmp-pictures social-pics"> </ul>
        
        </div>

       
      </div>
     
    </div>
     <div id="img-loader"></div>
     </div>
        </div>
        <div  id="sixth">
            <h4 ><span >Apply filters</span></h4>
			<ul class="effects-list clearfix" id="filter-list-container">
            <li class="block f-left">
         <a class="build-filter-btn block " href="#">

	<div class="none-color cartouche uppercase text-center text-white ellipsis active-filter">
		None
	</div>
</a>         </li>
    <li class="block f-left">
         <a class="build-filter-btn block " href="#">

	<div class="vintage-color cartouche uppercase text-center text-white ellipsis">
		Vintage
	</div>
</a>         </li>
<li class="block f-left">
         <a class="build-filter-btn block " href="#">

	<div class="sepia-color cartouche uppercase text-center text-white ellipsis">
		Sepia
	</div>
</a>         </li>
<li class="block f-left">
         <a class="build-filter-btn block " href="#">

	<div class="greenish-color cartouche uppercase text-center text-white ellipsis">
		Greenish
	</div>
</a>         </li>
	<li class="block f-left">
         <a class="build-filter-btn block " href="#">

	<div class="reddish-color cartouche uppercase text-center text-white ellipsis">
		Reddish
	</div>
</a>         </li>
</ul>
        </div>
    </div>
  
  
  <div class="clear"></div>
  
</div>
<div class="rightpanel">
<a href="index.php" class="logo left" ><img alt="logo" src="skins/{VAL_SKIN}/styleImages/logo2.png"  /></a>
 
          <a class="back-btn2 " href="index.php"> Go back to our site </a> 
       
  <div class="clear"></div>
  <h3 class="heading3" id="phone_model">{ACT_MODEL_NAME}</h3>
  <span class="price" id="case-price">{ACT_MODEL_PRICE}</span><br />
  <span class="fs">{Shipping}</span><br />
  <input type="submit" value="Add to Cart" class="addtocartOrange"  id="save-design"/>
 <div class="bulle-explain exp-save hide">
		<p class="text-center mb0">Save your design</p>
		<a href="#" class="exp-next text-main absolute" id="next4">Ok, got it</a>
		<a class="absolute skip mt0" href="#" >
			X
		</a>
	</div>
</div>
<div class="centerpane">
  <div class="iphonbox" id="printable-area">
    <div id="phone">

      <div ondragover="allowDrop(event)" ondragleave="removetarget(event)" ondrop="drop(event)" class="canvasable" id="image_edit" style="background:url({SRC2}) no-repeat center"> <img style="position:absolute; left:0px; top:0px; z-index: 30;" id="knockout" src="{SRC}">
        <div id="transform-tool" class="f-width absolute" style="top:0px; left:0px; display:none">
          <div>
            <div class="transform-tool-overlay">
              <div class="active-actions">
                <ul>
                  <li><a href="#" id="remove-image">Remove</a></li>
                  <li><a href="#" id="cancel-edit">Cancel</a></li>
                  <li><a href="#" id="done-edit">Ok</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div id="boxes-area"> {LAYOUT_HTML} </div>
      </div>
    </div>
  </div>
  <ul class="buttonbox2">
  	<li><a href="#" class="reset" id="reset-design">Reset</a></li>
    <li><a href="#" class="shuffle2" id="auto_shuffle" >Shuffle</a></li>
    <li><a href="#" class="help">Help</a></li>
    
  </ul>
</div>
<div class="saved-design" id="user-design">
 <ul>
            <!-- BEGIN: all_userimages_saved -->
            <li><div> <img  src="{SAVED_IMAGE_SRC}" id="userphoto-{SAVED_IMAGES_ID}"  source="{IMAGE_SRC}"></div> </li>
            <!-- END: all_userimages_saved -->
          </ul>
</div>
<script src="js/htmltocanvas.js"></script> 
<script src="js/jquery.colorbox.js"></script>
<div id="save-design" style="display:none">
<div class="save-design-popup modal-box f-width absolute">
<header >
				<h3 class="txt-grey latoLight">Looks Great <br /> <span>Design saved</span></h3>
				
			</header>
			<div class="imgbox" >
					<img alt="case overview" src="" class="flex-img" height="260" id="save-design-preview">
			</div>
            <div class="rightside right">
               <center>
            		<span class="txt18 latoLight">Share your design</span>
               </center>
						<ul class="reset-list mb1" style="height:auto;">
							<li class="inline mr1">
								<a title="Share with Facebook" class="mb1" id="fb-share-btn" href="#">
									<i aria-hidden="true"  class="text-mega text-facebook" title="Share on Facebook"></i> 
									
								</a>
							</li>
							<li class="inline mr1">
								<a title="Share with Twitter" class="mb1" id="twitter-share-btn" href="#">
									<i aria-hidden="true" class="text-mega text-twitter" title="Share on Twitter"></i> 
							
								</a>
							</li>
							<li class="inline mr1">
								<a title="Share with Pinterest" class="mb1" id="pinit-share-btn" href="#">
									<i aria-hidden="true" title="Share on Pinterest" class="text-mega text-pinterest"></i> 
								
								</a>
							</li>
							<li class="inline mr1">
								<a title="Share by Email" class="mb1" href="#" id="email-share-btn">
									<i aria-hidden="true" title="Email a friend" class="text-mega text-third"></i> 
								
								</a>
							</li>
						</ul>
                        <div class="f-width mb1 hide" id="share-with-mail-input">
							<div class="t-cell column12 vertical-middle">
								<input type="text" placeholder="Email" id="share-with-mail-text">
							</div>
							<div class="t-cell vertical-middle pl1">
								<input type="Submit" value="Send" class="btn third-color" id="share-with-mail-btn">
							</div>
						</div>
						<div class="buttonbox txt18 latoLight">
					
					<a  class="radius3px" href="Gallery.html">
						Go to your gallery
					</a>
					<a  class="radius3px" href="CaseCustomization.html">
						Make another case
					</a>
                    <a  class="radius3px button" href="index.php?_g=co&_a=cart" style="width:275px;">
						Go to Cart
					</a>
                    
                    </div>
		     </div>
</div>
</div>
<div id="confirm-design" class="hide">
<div class="confirm-design-popup modal-box f-width absolute">
<div class="white-color box relative">
	        <div class="scroll-modal scroll">
	        	<header class="border-bottom mb1 text-center">
					<h3 for="name-design" class="h2-like block mb0">Hang on a sec...</h3>
					<h4 class="h3-like">Some photos are duplicated, do you want to continue?</h4>
	        	</header>
				<footer class="pb1">
					<div class="table centerize">
					<a href="#" class="btn second-color-d mr1" id="cancel-btn">I'll change it</a>
					<a href="#" class="btn main-color" id="done-btn">Continue</a>
					</div>
				</footer>
	        </div>
	    </div>
</div>
</div>
<div id="confirm-reset-design" class="hide">
<div class="confirm-reset-design-popup modal-box f-width absolute">
<div class="white-color box relative">
	        <div class="scroll-modal scroll">
	        	<header class="border-bottom mb1 text-center">
					<h3 for="name-design" class="h2-like block mb0">Are you sure?</h3>
					<h4 class="h3-like">All your pictures will be removed from your design</h4>
	        	</header>
				<footer class="pb1">
					<div class="table centerize">
					<a href="#" class="btn second-color-d mr1" id="reset-cancel-btn">Cancel</a>
					<a href="#" class="btn main-color" id="reset-done-btn">Reset</a>
					</div>
				</footer>
	        </div>
	    </div>
</div>
</div>
<input type="hidden" id="casewidth" value="{ACT_MODEL_WIDTH}" />
<input type="hidden" id="caseheight" value="{ACT_MODEL_HEIGHT}" />
<!-- END: casecustomization -->