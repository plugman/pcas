<!-- BEGIN: session -->
<input type="hidden" value="{FTAPPID}"  id="fbappid"/>

<input type="hidden"  value="{STOREURL}"  id="storeaddres"/>

<div class="sessionBox">

<!-- BEGIN: session_false -->
  <a href="{LOGIN}" class="radius3px txt14 link" ><span class="login">{LANG_LOGIN}</span></a>
<a href="{REGISTER}" class="radius3px txt14 link"><span class="register">{LANG_REGISTER}</span></a>

<!-- END: session_false -->
  <!-- BEGIN: session_true -->
 
  <div class="togglebox radius3px">
		<span class="imgbox"><img alt="" src="{USER_IMAGE}"  /></span>
        {TXT_USERNAME}
        <span class="arrow2" onclick="$('.togglebox ul').slideToggle(); $('.arrow2').toggleClass('arrow3'); return false;">&nbsp;</span>
        <ul class="radius3px">





        	<li>
            <a href="{ACCOUNT}" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p2.jpg"  /></span>Profile Settings</a></li>
            <li><a href="Gallery.html" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p3.jpg"  /></span>My Gallery</a></li>
            <li><a href="Order-Lookup.html" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p4.jpg"  /></span>Track Order</a></li>
            <li><a href="ChangePassword.html" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p5.jpg"  /></span>Update Password</a></li>
            <li><a href="NewsLetter.html" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p6.jpg"  /></span>Newsletter </a>Subscription</li>
            <li><a href="{LOGOUT}" ><span class="imgbox"><img alt="" src="skins/{VAL_SKIN}/styleImages/p8.jpg"  /></span>Log Out</a></li>
        </ul>
</div>
   <a href="{LOGOUT}" class="radius3px txt14 link" >  {LANG_LOGOUT} </a> 
   <a href="{ACCOUNT}" class="radius3px txt14 link" > {LANG_YOUR_ACCOUNT} </a>
  <!-- END: session_true -->
  </div>
<!-- END: session -->
