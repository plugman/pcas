<!-- BEGIN: product -->
<div class="maincenter">
  <div class="borderbox">
  <div class="left-column">
	<div class="imgbox">
          <img alt="case" src="{SAVED_IMAGE_SRC}" >
	</div>
    	<div class="multiple">
        	
        </div>
    </div>
    
    <div class="right-column">
          <h2 class="txt30 txt-grey"> {SAVED_IMAGES_name}</h2>
               <h3 class="txt18 txtorange">  Free shipping today</h3>       
                               
                    
                       <div class="maindiv mb1"></div>   
                       
               
            

            <form method="post" action="Cart.html">
            		<span class="txt30 txtorange" style="float: left; margin-right: 30px; margin-top: 11px;">   
               			 {SAVED_IMAGES_PRICE}
                	 </span>
					<input type="submit" value="Add to Cart" class="btn2 radius3px">
					
                    <input type="hidden" value="{SAVED_case_ID}" id="caseid" name="case[caseid]"> 
                  
                    <input type="hidden" value="1" name="case[case]">
                     <input type="hidden" value="{SAVED_IMAGES_ID}" id="designid" name="case[designid]"> 
                    </form>

            

          

            

            <div class="maindiv txt14 txt-grey" style="line-height:18px;margin:10px 0;">
               Protection meets customization. 3-part extremely durable bezel protects the screen from directly contacting surfaces. The lightweight, minimalist design delivers a stylish profile, while allowing access to all buttons and ports at ease. Interchangeable Backplates adds one-of-a-kind personalization with endless choices.
                
                
            </div>
            <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal" pi:pinit:url="http://www.addthis.com/features/pinterest" pi:pinit:media="http://www.addthis.com/cms-content/images/features/pinterest-lg.png"></a>
<a class="addthis_button_tweet"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-524bfb1011bba71a"></script>
<!-- AddThis Button END -->

        </div>
   </div>
    <!-- BEGIN: design_true -->
    <div class="otherCaseBox"> 
    	<h4 class="txt18 latoBold txt-grey">Your other cases </h4>
		<div id="first" >
            <ul  id="saved-gallery">

             <!-- BEGIN: all_userimages_saved -->
            <li id="{SAVED_IMAGES_ID2}">
                    <div class="imgbox">
                        <a class="case-pic text-center" href="product/{SAVED_IMAGES_names2}/product_{SAVED_IMAGES_ID2}.html">
                            
                            <img width="116" height="230" src="{SAVED_IMAGE_SRC2}" alt="case">
                        </a> 
                           
                        <a class="remove" href="#" id="{SAVED_IMAGES_ID2}">&nbsp; </a>  
                        <div class="sharebox">
                        	<span class="left">Share this </span> 
                            <span class="right">
                            <a href="#" class="fbshare"> <img src="http://localhost/photocase/skins/Classic/styleImages/gf.png" alt=""></a>
                            
                            <a href="#" class="twshare"> <img src="http://localhost/photocase/skins/Classic/styleImages/gt.png" alt=""></a>
                           
                            </span>
                        </div>            
                    </div>
                   <div class="txt14 latoLight txtuppercase"> {DESIGN_NAME2}</div>
                   <div style="margin:5px 0 10px;" class="txt14">{USER_NAME2}</div>
                    <a href="" class="addtofav {FAV}">{ADD_TO_FAV}</a> </li>
                
                   
                </li>
             <!-- END: all_userimages_saved -->

          </ul>
        </div>
    </div>
    <script type="text/javascript">
$("#saved-gallery .fbshare").click(function (e) {
		 e.preventDefault();
	var pagelink = $(this).closest('li').attr('id');
	img = $("#"+pagelink+ ' > div > a > img').prop('src');
	pagelink = $("#"+pagelink+ ' > div > a').attr('href');
							FB.ui({
							  method: "feed",
							  display: 'popup',
								link: pagelink,
								picture:  img,
								name: 'Check out my Caseprint',
								description: 'Make your case with Facebook, Instagram photos'
							}, function(response){});
						});
$("#saved-gallery .twshare").click(function (e) {
	 e.preventDefault();
	var pagelink = $(this).closest('li').attr('id');
	pagelink = $("#"+pagelink+ ' > div > a').attr('href');
							 window.open("http://www.twitter.com/share?url=" + encodeURIComponent(pagelink) + "&text=" + encodeURIComponent('Check out my new @Casetify using Instagram & Facebook photos. Make yours and get $5 off:'), "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600");
        
						});
$("#saved-gallery .addtofav").click(function (e) {
	e.preventDefault();
	if ($(this).hasClass("favorite")) {
        return;
    }
	 
	
	var pagelink = $(this).closest('li').attr('id');
	 $.ajax({
            type: "POST",
            url: storeUrll + "controllers/addtofav.php",
            data: "photoid=" + pagelink,
            error: connectionerror,
            success: function (data) {
                if (data == 1) {
                    location.reload();
              
                }
            }

        });
        
						});
</script>
     <!-- END: design_true -->
</div>
<!-- END: product -->
<ul class="row " id="saved-gallery">

            <!-- BEGIN: all_userimages_saved -->
            <li class="column3 s-column4 xs-column6 xxs-column12 case mb1 artwork">
                    <div class="relative text-center all-gallery">
                        <a href="product/{SAVED_IMAGES_name}/product_{SAVED_IMAGES_ID}" class="case-pic text-center">
                            
                            <img alt="case" src="{SAVED_IMAGE_SRC}" class="case-img absolute flex-img">
                        </a> 
                           
                        <a href="#" class="remove-case absolute clearfix-overflow">
                            <i  class="text-dark block">X</i>
                            
                        </a>              
                    </div>
                </li>
            <!-- END: all_userimages_saved -->
          </ul>