<!-- BEGIN: index -->
{BANNERS}
 this is testing please ignore
<div class="clear"></div>
<div class="maincenter">
 	
  	<div  class="heading1 txt30 latoBlack">how it works<br /> <span></span></div>
    </center>
    <ul class="fbox">
    	<!--<li>
        <h4 class="txt18">{DATA.0.doc_name}</h4>
       {DATA.0.doc_content} </li>
		<li>
        <h4 class="txt18">{DATA.1.doc_name}</h4>
       {DATA.1.doc_content}
 </li>
		<li>
        <h4 class="txt18">{DATA.2.doc_name}</h4>
       {DATA.2.doc_content} </li>-->
       <li class="firstCol">
       		
       		<h4 class="txt14 latoLight">choose your mobile</h4>
       </li>
       <li class="secondCol">
       		
       		<h4 class="txt14 latoLight">upload your photo</h4>
       </li>
       <li class="thirdCol">
       		
       		<h4 class="txt14 latoLight">customise your case</h4>
       </li>
    </ul>
    <div class="clear"></div>
    <img alt="" src="skins/{VAL_SKIN}/styleImages/img4.jpg"  />
    <center>
    <div class=" latoBlack heading2 ">Customised Cases for your mobile</div>
    <div class="txtorange txt24">An opportunity for you to design your case.</div>
    </center>
    <div class="design txt18 latoLight">
    <span>
    <span>You can design your case using</span>
    <img alt="" src="skins/{VAL_SKIN}/styleImages/img5.jpg"  />
   <img alt="" src="skins/{VAL_SKIN}/styleImages/img6.jpg"  />
   <img alt="" src="skins/{VAL_SKIN}/styleImages/img7.jpg"  />
   </span>
   </div>
   <center>
    <a href="CaseCustomization.html" class="getstarted txt24 latoLight radius3px">Get Started Here</a>
    </center>
    <script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
          
      <script>
      function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var map_options = {
          center: new google.maps.LatLng(44.5403, -78.5463),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(map_canvas, map_options)
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script>
      function initialize() {
        var map_canvas = document.getElementById('map_canvas2');
        var map_options = {
          center: new google.maps.LatLng(44.5403, -78.5463),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(map_canvas, map_options)
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <div class="homemapbox">
    	<span class="txt18">Blanchardstown Shopping Centre,<br />
		Blanchardstown,  Dublin 15
        </span>
        <div  class="google-map">
        
        		<div id="map_canvas"></div>
        		 <ul class="latoLight txt14">
                	<li class="phone">+353 (1) 822 6363</li>
                    <li class="mail">help@pairmobile.ie</li>
                </ul>
        </div>
    </div>
    <div class="sepratorHome left"><img alt="" src="skins/{VAL_SKIN}/styleImages/img9.jpg"  /></div>
    <div class="homemapbox">
    	<span class="txt18">Pavilions Shopping Centre,  Swords,<br />
Co. Dublin
		
        </span>
        <div  class="google-map">
        
        		<div id="map_canvas2"></div>
                <ul class="latoLight txt14">
                	<li class="phone">+353 (1) 822 6363</li>
                    <li class="mail">help@pairmobile.ie</li>
                </ul>
        
        </div>
    </div>
    <!-- BEGIN: welcome_note -->
      <div class="fbox2 maindiv" >
      <div style="padding:10px; float:left; width:98%">

<h4 class="lucidaBold txt30">{HOME_TITLE}</h4>
<br />
{HOME_CONTENT}
</div>
      </div>
      <!-- END: welcome_note -->
</div>
<!-- END: index -->