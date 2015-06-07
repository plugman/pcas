<!-- BEGIN: contact_us -->

<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a><span class="breadSeprator"></span> Contact Us
     </div>
</div>
<div class="maincenter">
<h2 class="mainheading"> Contact Us</h2>
  <div class="maindiv mainbox ">
  
   <!-- BEGIN: error -->
    <p id="tdstatus" class="txtError" >{VAL_ERROR}</p>
    <!-- END: error -->
     <!-- BEGIN: mail_sent -->
    <p id="tdstatus" class="txtError" style=" background:#093; color:#fff; font-weight:bold">{MAIL_SENT}</p>
    <!-- END: mail_sent --> 
    <div class="maindiv" style="margin-bottom:10px;">
      <label class="txt24 lucidaBold" > We would love to hear from you.</label>
    </div>
   
    <!-- BEGIN: form -->
    <form action="" method="post" id="frmContactus" name="frmContactus" class="formValidation">
      <div class="loginleftContac" >
        <div class="maindiv">
          <label class=" txt-grey maindiv">{LANG_NAME}</label>
          <div class="txtboxmain"> 
            <input type="text"  name="name" id="name" value="{VAL_NAME}" />
             </div>
        </div>
        <div class="maindiv">
          <label class=" txt-grey maindiv">{LANG_EMAIL}</label>
          <div class="txtboxmain"> 
            <input type="text"  name="email" id="email" value="{VAL_EMAIL}"  />
             </div>
        </div>
        <div class="maindiv">
          <label class=" txt-grey maindiv">{LANG_PHONE}</label>
          <div class="txtboxmain"> 
            <input type="text"  name="phone" id="phone" value="{VAL_PHONE}"  />
             </div>
        </div>
        <div class="maindiv">
          <label class=" txt-grey maindiv">{LANG_COMMENTS}</label>
          <div class="txtboxmain txtboxmain2"> <span class="txtboxmain-left txtboxmain-left2"></span>
            <textarea name="msg"  id="msg"   class="textarea"  cols="1" rows="1" >{VAL_COMMENTS}</textarea>
            <span class="txtboxmain-right txtboxmain-right2"> <span class="mandatory"></span> </span> </div>
        </div>
        <div class="maindiv">
        <center>
          <input type="submit"  class="submitlogin button radius3px"   value="Submit"   />
          </center>
        </div>
      </div>
      <div  class=" loginrightc "  > 
        
        <!-- BEGIN: view_doc --> 
        
       
        
        
        {DOC_CONTENT} 
        <!-- END: view_doc --> 
        
      </div>
         </form>
    <!-- END: form --> 
       <!-- BEGIN: map_true --> 
      <div class="map_can">
        <div id="map" style="width: 940px; height: 400px;"></div>
      </div>
      <script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
  <script type="text/javascript">
 //<![CDATA[
    var locations = [
      ['{VAL_TIT2}', '{VAL_LATITUDE2}','{VAL_LONGITUDE2}', 2],
	  ['{VAL_TIT}', '{VAL_LATITUDE}','{VAL_LONGITUDE}', 1]
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: new google.maps.LatLng('{VAL_LATITUDE2}','{VAL_LONGITUDE2}'),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
	//]]>
  </script>
    <!-- END: map_true --> 
  </div>

</div>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script type="text/javascript" language="javascript">
jQuery.validator.addMethod("lettersonly", function(value, element) {
				        return this.optional(element) || /^[a-z \s]+$/i.test(value);
				}, "Only Letters Allowed.");
	
$(document).ready(function(){
	$("#frmContactus").validate({
	   rules: {
				name:  {required:true, lettersonly: true, maxlength:20},
				email: {required: true, maxlength:50, email: true},
				phone:{digits:true, maxlength:20},
				msg: {required:true, maxlength:500},
			  },
	   messages:
			  {
				name: {required: 'Please enter Name'},
				email: {required: 'Please enter Email Id.', email: 'Please enter valid Email Id.', maxlength: 'Max 50 Characters Allowed.'},
				phone:{maxlength: 'Max 20 characters Allowed.'},
				msg:{required: 'Please enter Comments.', maxlength: 'Max 500 Characters Allowed.'}
			  }
	});
});

function ResetForm()
{
		var validator = $("#frmContactus").validate();
		validator.resetForm();
		document.frmContactus.reset();
}
</script> 
<script type="text/javascript" language="javascript">
function ResetForm()
{
	var validator = $("#frmContactus").validate();
	validator.resetForm();
	document.frmContactus.reset();
}
</script> 

<!-- END: contact_us --> 
