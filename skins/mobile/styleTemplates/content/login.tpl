<!-- BEGIN: login -->
<div class="maindiv ">
    	 
     <div class="maindiv mainbox2">
      <div class="headingbox" >
     	<span class="txt30 heading" >{LANG_LOGIN_TITLE}</span>
     	
     </div>
       
        <form action="index.php?_a=login&amp;redir={VAL_SELF}" target="_self" method="post">
       <div  class="loginright">
        <p class="txt14" align="center" style="color:#fff; font-size:16px">{LOGIN_STATUS}<br /><br /></p>
        
          	<div class="maindiv" align="center">
       		
       		<div class="txtboxmain3">
            <label class="txt18">Email Address:</label>
             <input type="text" name="username"  value="{VAL_USERNAME}" />
           
             	<span class="mandatory"></span>
             
            </div>
            </div>
            <div class="maindiv" align="center">
       		
       		<div class="txtboxmain3">
            <label class="txt18">Password:</label>
              <input type="password" name="password" />
           
             	<span class="mandatory"></span>
             
            </div>
            </div>
          
       </div>
       <div class="maindiv footerlogin">
       
        <center>
        	 <a href="index.php?_a=forgotpass" class="forgetpass txt18  txt-grey"><u>{LANG_FORGOT_PASS}</u></a>
               <input name="submit" type="submit" value="{TXT_LOGIN}" class="submitlogin"  style="float:none" />
      </center>
       </div>
       </form>
        
     
    </div>


</div>
<!-- END: login -->

