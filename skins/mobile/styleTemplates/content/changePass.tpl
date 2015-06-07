<!-- BEGIN: change_pass -->
<div class="loginbox3 ">

	 <div class="maindiv">
           <div class="yourbal">
            <div class="imgbox">
                <img alt="balance image" src="skins/{VAL_SKIN}/styleImages/balanc.jpg"  />
            </div>
            <a href="Balance.html" >Your Balance</a>
            </div>
        </div>
       <ul class="tablist">
   
     <li class="tablista">
   
     <a  href="Profile.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr1.png" alt="">
     </span>Personal Info</a></li>
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
     </span>{LANG_CHANGE_PASS_TITLE}</a></li>
     </ul>
       
      <div class="maindiv mainbox">
       
       <div class="p10">

	
	<!-- BEGIN: session_true -->
	<div style="padding:0 20px 10px;" align="center">
	<!-- BEGIN: no_error -->
	<p align="center">{LANG_PASS_DESC}</p>
	<!-- END: no_error -->
	
	<!-- BEGIN: error -->
	<p class="txtError" align="center">{VAL_ERROR}</p>
	<!-- END: error -->
	</div>
		<!-- BEGIN: form -->
		<form action="index.php?_a=changePass" target="_self" method="post">
         <div class="loginleft2">
         
         	<div class="maindiv">
           <label class="txt18 txt-grey maindiv">{TXT_OLD_PASS}</label>
           </div>
           <div class="maindiv">
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input name="oldPass" type="password"  id="oldPass" maxlength="30" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
           <label class="txt18 txt-grey maindiv">{TXT_NEW_PASS}</label>
            </div>
           <div class="maindiv">
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input name="newPass" type="password"  id="newPass" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            <div class="maindiv">
           <label class="txt18 txt-grey maindiv">{TXT_NEW_PASS_CONF}</label>
            </div>
           <div class="maindiv">
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input name="newPassConf" type="password"  id="newPassConf" maxlength="100" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
            
			
          
         
       	 <div class="maindiv footerlogin" align="center">
         <input name="submit" type="submit" 
         value="{TXT_SUBMIT}" class="submitlogin" style="margin:13px 0 0 20px; float:none" />
       	
        
       </div>
       </div>
	</form>
	<!-- END: form -->
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
</div>

</div>
<ul class="tablist">

    
  
   
    <li class="tablista"><a  href="Balance.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr5.png" alt="">
     </span>Credit History</a></li>
    	</ul>
</div>
<!-- END: change_pass -->