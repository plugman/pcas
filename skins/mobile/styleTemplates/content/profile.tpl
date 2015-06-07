<!-- BEGIN: profile -->
{JS_COUNTY_OPTIONS}
<div class="maindiv loginbox3" style="margin:0">
<!--<div class="yourbal">
        <div class="imgbox">
        	<img alt="balance image" src="skins/{VAL_SKIN}/styleImages/balanc.jpg"  />
        </div>
        <a href="Balance.html" >Your Balance</a>
        </div>-->
         
<ul class="tablist">
   	 <li class="tablista ">
             <a  href="Balance.html"> <span class="imgbox">
             <img title="" src="skins/{VAL_SKIN}/styleImages/balanc.png" alt="">
             </span>Your Balance</a>
            </li>
     <li class="tablista active">
   
     <a  href="Profile.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr1.png" alt="">
     </span>{LANG_PERSONAL_INFO_TITLE}</a></li>
     </ul>
    
      <div class="maindiv mainbox register-content">
      	
       

<form action="index.php?_a=profile{VAL_EXTRA_GET}" target="_self" method="post">
<!-- BEGIN: session_true -->
	
	
	<div class="loginleft2" >
      <div style=" padding:10px 0; text-align:center;">
    	<!-- BEGIN: no_error -->
	<p class="txt-purple maindiv" >{LANG_PROFILE_DESC}</p>
	<!-- END: no_error -->
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
    	</div>
	
    	<div class="maindiv">
          <div class="maindiv">
           <label class="txt18 txt-grey">{TXT_FIRST_NAME}</label>
           </div>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input name="firstName" type="text"  id="firstName" value="{VAL_FIRST_NAME}" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
        <div class="maindiv">
        	 <div class="maindiv">
           <label class="txt18 txt-grey">{TXT_EMAIL}</label>
             </div>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input name="email" type="text"  id="email" value="{VAL_EMAIL}" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
        <div class="maindiv">
           <div class="maindiv">
           <label class="txt18 txt-grey">{TXT_PHONE}</label>
           </div>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input name="phone" type="text"  id="phone" value="{VAL_PHONE}" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
<div class="maindiv">
			   <div class="maindiv">
            <label class="txt18 txt-grey">Country</label>
            </div>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
             <select name="country" class="refered" tabindex="6" onchange="updateCounty(this.form);" style="z-index:10">
          <!-- BEGIN: repeat_countries -->
          <option value="{VAL_COUNTRY_ID}" {COUNTRY_SELECTED}>{VAL_COUNTRY_NAME}</option>
          <!-- END: repeat_countries -->
        </select>
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            <span id="err_phone" class="maindiv errormessage" ></span>
        </div>
	</div>
   
    <!-- END: session_true -->
     
      <div class="maindiv" align="center">
      All fields marked with * are mandatory.
      	<input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin" />
       	
        
       </div>
       </form>
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
</div>
<ul class="tablist">
<li class="tablista"> <a  href="Orders.html" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr2.png" alt="">
     </span>Order History</a></li>
    <li class="tablista"><a  href="NewsLetter.html" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr3.png" alt="">
     </span>Newsletter</a></li>
  
    <li class="tablista"> <a  href="ChangePassword.html" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr4.png" alt="">
     </span>Change Password</a></li>
    <li class="tablista"><a  href="Balance.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr5.png" alt="">
     </span>Credit History</a></li>
    	</ul>

</div>

<!-- END: profile -->