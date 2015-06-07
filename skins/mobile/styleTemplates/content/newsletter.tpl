<!-- BEGIN: newsletter -->

<div class="loginbox3">

      <!--<div class="headingBorder maindiv">
        <h3 class="txt18 txt-purple">
         {LANG_NEWSLETTER_TITLE}
        </h3>
        <span>&nbsp;</span>
        </div>-->
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
     </ul>
<div class="maindiv mainbox">

	<div class="p10">
	<!-- BEGIN: session_true -->
	<p align="center">{LANG_NEWSLETTER_DESC}</p>
	
		<form action="index.php?_a=newsletter" target="_self" method="post">
			<table border="0" cellspacing="10" cellpadding="3" align="center" width="100%" >
				<tr >
				  <td align="center"><strong>{TXT_SUBSCRIBED}</strong></td>
			  </tr>
				<tr>
					<td align="center" >{LANG_YES}
                    <input type="radio" name="optIn1st" value="1" {STATE_SUBSCRIBED_YES}  />
					&nbsp; &nbsp; 
					{LANG_NO} 
					  <input type="radio" name="optIn1st" value="0" {STATE_SUBSCRIBED_NO} />
				    </td>
				</tr>
				<tr align="center">
				  <td ><strong>{TXT_EMAIL_FORMAT}</strong></td>
			  </tr>
				<tr>
					<td align="center" >{LANG_TEXT}
                    <input type="radio" name="htmlEmail" value="0" {STATE_HTML_TEXT} />
                    &nbsp; &nbsp; 
					<abbr title="{LANG_HTML_ABBR}">{LANG_HTML}</abbr> <input type="radio" name="htmlEmail" value="1" {STATE_HTML_HTML} />
					</td>
				</tr>
				<tr>
				  
				  <td  align="center">
                  <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin" style="float:none" />
                  
                  </td>
			  </tr>
		</table>
	</form>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
</div>
</div>

<ul class="tablist">

    
  
    <li class="tablista"> <a  href="ChangePassword.html" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr4.png" alt="">
     </span>Change Password</a></li>
    <li class="tablista"><a  href="Balance.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr5.png" alt="">
     </span>Credit History</a></li>
    	</ul>
</div>
<!-- END: newsletter -->