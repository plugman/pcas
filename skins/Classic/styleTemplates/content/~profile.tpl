<!-- BEGIN: profile -->
{JS_COUNTY_OPTIONS}
    <div class="maincenter">
    <!-- BEGIN: session_true -->
     <!-- BEGIN: update_info -->
     <script type="text/javascript" charset="utf-8">
	 $(document).ready(function(){
	 $('#editinfo').click();
	 });
	 </script>
      <!-- END: update_info -->
       <!-- BEGIN: update_add -->
     <script type="text/javascript" charset="utf-8">
	 $(document).ready(function(){
	 $('#editadd').click();
	 });
	 </script>
      <!-- END: update_add -->
      <!--<script type="text/javascript" charset="utf-8">
	 $(document).ready(function(){
		 if(window.location.hash == "#editinfo"){
	 		$('#editinfo').click();
		 }else if(window.location.hash == "#editadd"){
	 		$('#editadd').click();
		 }
	 });
	 </script>-->
        <h2 class="mainheading">Hello {VAL_CUSTOMER}</h2>
     <div class="sitedoc account">
     <div class="leftsideP">
    	<ul class="txt16 latoLight">
        	<li class="first"><a href="Profile.html" class="txtorange" >Profile Settings</a></li>
        	<li><a href="Gallery.html" class="white" >My Gallery</a></li>
            <li><a href="Orders.html" class="white" >Order History</a></li>
            <li><a href="Order-Lookup.html" class="white" >Track Order</a></li>
            <li><a href="ChangePassword.html" class="white" >Update Password</a></li>
            <li><a href="NewsLetter.html" class="white" >Newsletter Subscription</a></li>
            <li><a href="Logout.html" class="white" >Log Out</a></li>
            <li><a href="Social.html" class="white" >Social Media Settings</a></li>
        </ul>
    </div>

<div class= "rightsideP" >
<form action="index.php?_a=profile{VAL_EXTRA_GET}" target="_self" method="post">

   <div id="profile-Top">
	<h2 class="txt18 lucidaBold">My profile</h2>
	<div class="maindiv">
        <div class="imgbox">
            <img alt="" src="{USER_IMAGE}"   />
        </div>
        </div>
        <span class="txt16 latoBold txtorange">{VAL_CUSTOMER}</span><br />
        <p class="txt14 txt-grey" style="text-align:24px;">
        
        	Address: {VAL_ADD_1}<br />
            Email: {VAL_EMAIL}<br />
            Facebook: {VAL_FACEBOOK}
        </p>
        <a href="#editinfo"  class="editSetting button"  onclick="$('#profile-form').slideToggle(); $('#profile-Top').slideToggle();" id="editinfo" >Edit</a>
        <h2 class="txt18 lucidaBold">Shipping Address</h2>
    	<span class="txt16 latoBold txtorange">{VAL_CUSTOMER}</span><br />
        <p class="txt14 txt-grey" style="text-align:24px;">
        
        	{VAL_DADD_1}


        </p>
         <a href="#editadd"  class="editSetting button"  onclick="$('#billing').slideToggle(); $('#profile-Top').slideToggle();" id="editadd">Edit</a>
    </div>
      <div style="display:none" id="profile-form">
       	 <h2  class="txt18 lucidaBold" >Profile</h2>
         <!-- BEGIN: no_error -->
	<p>{LANG_PROFILE_DESC}</p>
	<!-- END: no_error -->
           <!-- BEGIN: error -->
        <p class="txtError"><span class="errbg"> {VAL_ERROR}</span></p>
        <!-- END: error -->
        <div class="maindiv">
           <label class="txt14 txt-grey maindiv">First Name</label>
       		<div class="txtboxmain">
             
            <input type="text" name="firstName"  id="fname"    value="{VAL_FIRST_NAME}" required="required" />
             <span class="required">*</span>
             
            </div>
            </div>
            <div class="txtdiv">
              <label class="txt14 txt-grey maindiv">Last Name</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text" name="lastName"  id="lastName"    value="{VAL_LAST_NAME}"/>
                <span class="txtboxmain-right"> </span> </div>
            </div>
        <div class="maindiv">
           <label class="txt14 txt-grey maindiv">Email</label>
       		<div class="txtboxmain">            
           <input type="text" name="email"  id="txtEmail"   onblur="javascript:EmailExist();" value="{VAL_EMAIL}"/>  <span class="required">*</span>
            </div>
            <span id="err_email" class="maindiv errormessage"></span>
         </div>   
         <div class="maindiv">
              <label class="txt14 txt-grey maindiv">Phone</label>
              <div class="txtboxmain"> 
                <input name="phone" type="text" id="phone" size="16" value="{VAL_PHONE}"   required="required" /> <span class="required">*</span>
                </div>
             </div>
         <div class="maindiv">
           <label class="txt14 txt-grey maindiv">Twitter</label>
       		<div class="txtboxmain">
            <input type="text" name="tw_add"      value="{VAL_TWITTER}"/>
            </div>
       </div>  
       <div class="maindiv">
           <label class="txt14 txt-grey maindiv">Facebook</label>
       		<div class="txtboxmain">
            <input type="text" name="fb_add"      value="{VAL_FACEBOOK}"/>
            </div>
       </div>     
         <div class="maindiv">
           <label class="txt14 txt-grey maindiv">Address</label>
       		<div class="txtboxmain">
             
            <input name="add_1" type="text" id="add_1" size="16" value="{VAL_ADD_1}" required="required" /> <span class="required">*</span>
             
             
            </div>
            </div>       
     
       	<div class="maindiv">
         <label class="txt14 txt-grey maindiv">Town/City</label>
       		<div class="txtboxmain">
            
          <input name="town" type="text" id="dtown" size="16" value="{VAL_TOWN}" tabindex="12" required="required" />
              <span class="required">*</span>
            
            </div>
            <span id="err_email" class="maindiv errormessage"></span>
         </div>
       	<div class="maindiv">
            <label class="txt14 txt-grey maindiv">Zip</label>
       		<div class="txtboxmain">
             
            <input name="postcode" type="text" id=d"postcode" size="16" value="{VAL_POSTCODE}" tabindex="13" />
             
            </div>
            <span id="err_phone" class="maindiv errormessage" ></span>
        </div>
        <div class="maindiv">
           <label class="txt14 txt-grey maindiv">State</label>
       		<div class="txtboxmain">
             
            <input name="county" type="text" id="county" value="{VAL_COUNTY}" maxlength="50"  tabindex="14" required="required" />
             
              <span class="required">*</span>
            </div>
            </div>
     	<div class="maindiv">
           <label class="txt14 txt-grey maindiv">Image</label>
       		<div class="txtboxmain">
             <div class="imgbox">  <img alt="" src="{USER_IMAGE}"   /></div>
            
            </div>
            </div>
            <div class="maindiv">
    <div class="fileUpload  radius3px txt14">
    <span>Upload Image</span>
    <input type="file" class="upload" />
</div>
     </div>
     <div class="maindiv" style="border-top: 1px solid #ccc; margin-top: 25px; padding-top: 15px;">
     
      <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin button radius3px" />
            
     </div>
     
     
     
       </div>
       <div style="float:left; display:none" id="billing" >
       
        <div  class="reginnerheading txt16 lucidaBold">Delivery Address</div>
              <div class="maindiv">
                <label class="txt18 txt-grey maindiv">Address</label>
                <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                  <input name="dadd_1" type="text" id="dadd_1" size="16" value="{VAL_DADD_1}" tabindex="8" />
                   <span class="required">*</span> </div>
              </div>
              <div class="maindiv">
                <label class="txt18 txt-grey maindiv">Town/City</label>
                <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                  <input name="dtown" type="text" id="dtown" size="16" value="{VAL_DTOWN}" tabindex="10" />
                 <span class="required">*</span></div>
                 </div>
              <div class="maindiv">
                <label class="txt18 txt-grey maindiv">Zip</label>
                <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                  <input name="dpostcode" type="text" id="dpostcode" size="16" value="{VAL_DPOSTCODE}" tabindex="11" />
                   </div>
                </div>
              <div class="maindiv">
                <label class="txt18 txt-grey maindiv">State</label>
                <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                  <input name="dcounty" type="text" id="dcounty" value="{VAL_DEL_DCOUNTY}" maxlength="50"  tabindex="13" />
                  < <span class="required">*</span></div>
              </div>
               <div class="maindiv" style="border-top: 1px solid #ccc; margin-top: 25px; padding-top: 15px;">
     
      <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin button radius3px" />
            
     </div>
              </div>

  
    
      
       </form>
       </div>
       </div>
         <!-- END: session_true -->
	<!-- BEGIN: session_false -->
     <div class="sitedoc account">
	<p>{LANG_LOGIN_REQUIRED}</p>
      </div>
	<!-- END: session_false -->
  
 </div>



<!-- END: profile -->