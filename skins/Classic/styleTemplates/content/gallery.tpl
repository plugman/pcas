<!-- BEGIN: gallery -->
<!-- BEGIN: session_true -->
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a><span class="breadSeprator"></span><a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span> Gallery
     </div>
</div>
<div id="fb-root" style="float:left; width:1px;"></div>
<script type="text/javascript" src="js/commonjs.js"></script>

<div class="maincenter"> 
  <!-- BEGIN: customer_true -->
  <div class="coverbox maindiv">
    <div class="coverphoto"> 
      <img alt="pic" id="my_image" src="{USER_COVER}"  /> 
      <a href="javascript:;" onclick="javascript:getval();" class="changecover radius3px">Change Cover</a> </div>
    <div class="coverboxleftside">
      <div class="imgbox"><img alt="" src="{USER_IMAGE}"  /> </div>
      <div class="clear"></div>
      <h4 class="txt24"> {USER_NAME}</h4>
      <!--<div class="counter maindiv"> 02 Followers  &nbsp; | &nbsp;  256 Following </div>-->
     <div class="maincenter" style="float: left; margin-top: -201px; margin-left: 400px; "> <div  id="upload-files"  style="display:none;">
        <div id="mulitplefileuploader">Change Cover</div>
        <div id="status"></div>
        <div id="user-photos">
        
        </div>
       
        <input type="hidden" value="{USER_FOL}" id="ccuser" />
<script>

var storeUrl = $("#storeaddres").val();

$(document).ready(function(){
	var settings = {
		url: "ajax/cover-upload.php",
		method: "POST",
		allowedTypes:"jpg,png,gif,doc,pdf,zip",
		fileName: "myfile",
		data:"user={USER_FOL}",
		multiple: false,
		onSuccess:function(files,data,xhr){ 
			reloadProfilephoto('{USER_FOL}');		
		},
		onError: function(files,status,errMsg){		
			$("#status").html("<font color='red'>Upload is Failed</font>");
		}
	}
	$("#mulitplefileuploader").uploadFile(settings);
});
function getval(){
	
	$('#upload-files').show();
}
function reloadProfilephoto(userfolder) {
	var src = $('img[alt="pic"]').attr('src');

	var fileNameIndex = src.lastIndexOf("/") + 1;
	var filename = src.substr(fileNameIndex);

	//var loc = filename.indexOf(".");
//	if (loc != -1) {
//		var theImgSrc = filename.substr(0, loc);
//	}
/*	var filename1 =  src.substr(fileNameIndex);
	var oldimg= encodeURIComponent(theImgSrc);*/
   var dataval = userfolder+"||"+filename+"||cover_photo" ;
    $.ajax({
        type: "POST",
        url: storeUrl + "ajax/updatecoverimage.php",
        data: "dataval=" + dataval,
        error: connectionerror,
        success: function (data) {
            var datavalue = data.split("::");
            if (datavalue[1] == 1) {
                $('#user-photos').html();				
                $("#my_image").attr("src",datavalue[2]);
				$('#upload-files').hide();
            }
        }

    });
}


</script>  
      </div></div>
      <center>  
       <!-- <a href="" class="editSetting latoLight">Edit settings</a>-->
      </center>
    </div>
  </div>
  <!-- END: customer_true -->
  <div class="clear"></div>
  <div  id="gallery">
    <div class="tabs">
      <ul class="tabNavigation">
        <li><a  href="#first">All <span class="arrowtab">&nbsp;</span></a> </li>
        <li><a  href="#second">favorite <span class="arrowtab">&nbsp;</span></a></li>
      </ul>
      <p class="clear"></p>
      <div  id="first" class="min-height">
        <ul id="saved-gallery">
          <!-- BEGIN: img_true --> 
          <!-- BEGIN: all_userimages_saved -->
          <li id="{SAVED_IMAGES_ID}">
            <div class="imgbox"> <a href="product/{SAVED_IMAGES_name}/product_{SAVED_IMAGES_ID}.html" class="case-pic text-center"> <img alt="case" src="{SAVED_IMAGE_SRC}" width="116" height="230"> </a> <a href="#" class="remove" id="{SAVED_IMAGES_ID}">&nbsp; </a>
              <div class="sharebox"> <span class="left">Share this </span> <span class="right"> <a href="#" class="fbshare"> <img alt="" src="skins/{VAL_SKIN}/styleImages/gf.png"  /></a> <a href="#" class="twshare"> <img alt="" src="skins/{VAL_SKIN}/styleImages/gt.png"  /></a> </span> </div>
            </div>
            <div class="txt14 latoLight txtuppercase">{DESIGN_NAME}</div>
            <div class="txt14" style="margin:5px 0 10px;">{USER_NAME}</div>
            <a href="" class="addtofav {FAV}">{ADD_TO_FAV}</a> </li>
          <!-- END: all_userimages_saved --> 
          <!-- END: img_true --> 
          <!-- BEGIN: img_false -->
          <p>Your Gallery is Empty</p>
          <!-- END: img_false -->
        </ul>
      </div>
      <div  id="second">
         <ul id="saved-gallery">
          <!-- BEGIN: img_truef --> 
          <!-- BEGIN: all_userimages_savedf -->
          <li id="{SAVED_IMAGES_ID}">
            <div class="imgbox"> <a href="product/{SAVED_IMAGES_name}/product_{SAVED_IMAGES_ID}.html" class="case-pic text-center"> <img alt="case" src="{SAVED_IMAGE_SRC}" width="116" height="230"> </a> <a href="#" class="remove" id="{SAVED_IMAGES_ID}">&nbsp; </a>
              <div class="sharebox"> <span class="left">Share this </span> <span class="right"> <a href="#" class="fbshare"> <img alt="" src="skins/{VAL_SKIN}/styleImages/gf.png"  /></a> <a href="#" class="twshare"> <img alt="" src="skins/{VAL_SKIN}/styleImages/gt.png"  /></a> </span> </div>
            </div>
            <div class="txt14 latoLight txtuppercase">{DESIGN_NAME}</div>
            <div class="txt14" style="margin:5px 0 10px;">{USER_NAME}</div>
 </li>
          <!-- END: all_userimages_savedf --> 
          <!-- END: img_truef --> 
          <!-- BEGIN: img_falsef -->
          <p>Your Gallery is Empty</p>
          <!-- END: img_falsef -->
        </ul>
      </div>
    </div>
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
<div id="delete-design" class="hide">
<div class="save-design-popup modal-box f-width absolute">
<header >
				<h3 class="txt-grey latoLight">Are You Sure<br /> <span>This Design will be deleted</span></h3>
				
			</header>
			<center><input type="button" value="Delete" class="delete-design" /></center>
</div>
</div>
<script src="js/jquery.colorbox.js"></script>
<!-- END: session_true --> 

<!-- BEGIN: session_false -->
<div class="maincenter">
  <p>Please Login</p>
</div>
<!-- END: session_false --> 
<!-- END: gallery --> 
