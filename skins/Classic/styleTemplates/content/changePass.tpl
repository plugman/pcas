<!-- BEGIN: change_pass -->
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a><span class="breadSeprator"></span><a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span> {LANG_CHANGE_PASS_TITLE}
     </div>
</div>
<div class="maincenter">
   <!-- BEGIN: session_true -->
	<h2 class="mainheading">Hello {VAL_CUSTOMER}</h2>
       
      <div class="account sitedoc">
      <div class="leftsideP">
    	<ul class="txt16 latoLight">
        	<li class="first"><a href="Profile.html" class="white" >Profile Settings</a></li>
        	<li><a href="Gallery.html" class="white" >My Gallery</a></li>
            <li><a href="Orders.html" class="white" >Order History</a></li>
            <li><a href="Order-Lookup.html" class="white" >Track Order</a></li>
            <li><a href="ChangePassword.html" class="txtorange" >Update Password</a></li>
            <li><a href="NewsLetter.html" class="white" >Newsletter Subscription</a></li>
            <li><a href="Logout.html" class="white" >Log Out</a></li>

        </ul>
    </div>
    <div class= "rightsideP" >
   
      <h2  class="txt18 lucidaBold" >
     
      	<!-- BEGIN: no_error -->
	{LANG_PASS_DESC}
	<!-- END: no_error -->
      	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
     </h2>
	
	
		<!-- BEGIN: form -->
		<form action="index.php?_a=changePass" target="_self" method="post">
         <div>
         
         	<!-- BEGIN: not_social -->
         	<div class="maindiv">
           <label class="txt18 txt-grey maindiv">{TXT_OLD_PASS}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input name="oldPass" type="password"  id="oldPass" maxlength="30" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <!-- END: not_social -->
            <div class="maindiv">
           <label class="txt14 txt-grey maindiv">{TXT_NEW_PASS}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input name="newPass" type="password"  id="newPass" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
           <label class="txt14 txt-grey maindiv">{TXT_NEW_PASS_CONF}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input name="newPassConf" type="password"  id="newPassConf" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            
			
          </div>
         <div class="seprastor"></div>
       	 
         <input name="submit" type="submit" value="{TXT_SUBMIT}" class="button radius3px"  />
       	
        
      
	</form>
	<!-- END: form -->
	
	
	
    </div>
</div>  
<!-- END: session_true -->
<div class="account sitedoc">
	<p>{LANG_LOGIN_REQUIRED}</p>
    </div>
	<!-- END: session_false -->
</div>



<!-- END: change_pass -->