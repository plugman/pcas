<!-- BEGIN: account -->
 <!--<div class="maindiv breadbg">
      <div class=" maincenter">
        <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         Your Account
      </div>
    </div>-->

      <div class="maincontent" >
      	<!--<div class="headingBorder maindiv">
        <h3 class="txt18 txt-purple">
         {LANG_YOUR_ACCOUNT}
        </h3>
        <span>&nbsp;</span>
        </div>-->
       <div class="maindiv">
        <!-- BEGIN: session_true -->
        
        
        <div class="loginbox3">
        <div class="balanceinfo">
        <ul class="tablist">
            <li class="tablista active">
             <a  href="{BALANCE}"> <span class="imgbox">
             <img title="" src="skins/{VAL_SKIN}/styleImages/balanc.png" alt="">
             </span>Your Balance</a>
            </li>
        </ul>
       
        <div class="remainbal">
        <div class="maindiv">
        <a href="{BALANCE}"class="rembalance">{VAL_BAL}</a>
        </div>
        <a href="{BALANCE}" class="adfund"> + ADD FUNDS</a>
        </div>
        </div>
        
        
        <ul class="tablist">
    <li class="tablista">
   
     <a  href="{PROFILE}"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr1.png" alt="">
     </span>{TXT_PERSONAL_INFO}</a></li>
    <li class="tablista"> <a  href="{ORDERS}" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr2.png" alt="">
     </span>{TXT_ORDER_HISTORY}</a></li>
    <li class="tablista"><a  href="{NEWS}" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr3.png" alt="">
     </span>{TXT_NEWSLETTER}</a></li>
   <!-- <li><a href="http://localhost/imeiunlocklive/BulckOrder.html">
    
    Bulk Orders</a></li>-->
    <li class="tablista"> <a  href="{PASSWORD}" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr4.png" alt="">
     </span>{TXT_CHANGE_PASSWORD}</a></li>
    <li class="tablista"><a  href="{BALANCE}"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr5.png" alt="">
     </span>Credit History</a></li>
    	</ul>
       
       
        
       
        
        </div>
        
        <!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
       </div> 
        
    </div>
<!-- END: account -->

