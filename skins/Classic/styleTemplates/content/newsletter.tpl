<!-- BEGIN: newsletter -->
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a><span class="breadSeprator"></span><a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span> {LANG_NEWSLETTER_TITLE}
     </div>
</div>
<div class="maincenter">
<h2 class="mainheading">Hello {VAL_CUSTOMER}</h2>
      <div class="sitedoc account">
      
      
<div class="orderhistory">

	 <div class="leftsideP">
    	<ul class="txt16 latoLight">
        	<li class="first"><a href="Profile.html" class="white" >Profile Settings</a></li>
        	<li><a href="Gallery.html" class="white" >My Gallery</a></li>
            <li><a href="Orders.html" class="white" >Order History</a></li>
            <li><a href="Order-Lookup.html" class="white" >Track Order</a></li>
            <li><a href="ChangePassword.html" class="white" >Update Password</a></li>
            <li><a href="NewsLetter.html" class="txtorange" >Newsletter Subscription</a></li>
            <li><a href="Logout.html" class="white" >Log Out</a></li>

        </ul>
    </div>
    <div class= "rightsideP" >
	<!-- BEGIN: session_true -->
	<h2  class="txt18 lucidaBold" >{LANG_NEWSLETTER_DESC}</h2>
	<div class="maindiv">
		<form action="index.php?_a=newsletter" target="_self" method="post">
			<table border="0" cellspacing="10" cellpadding="3" align="center">
				<tr align="left">
				  <td colspan="2"><strong class="txtorange latoBold">{TXT_SUBSCRIBED}</strong></td>
			  </tr>
				<tr>
					<td > <input type="radio" name="optIn1st" value="1" {STATE_SUBSCRIBED_YES}  /> {LANG_YES}
                    </td>
					<td > 
                    <input type="radio" name="optIn1st" value="0" {STATE_SUBSCRIBED_NO} /> 
					{LANG_NO} 
					  
				    </td>
				</tr>
				<!--<tr align="left">
				  <td colspan="2"><strong>{TXT_EMAIL_FORMAT}</strong></td>
			  </tr>-->
				<tr>
					<td > <input type="radio" name="htmlEmail" value="0" {STATE_HTML_TEXT} /> {LANG_TEXT}
                    </td>
					<td > <input type="radio" name="htmlEmail" value="1" {STATE_HTML_HTML} /> 
					<abbr title="{LANG_HTML_ABBR}">{LANG_HTML}</abbr> 
					</td>
				</tr>
                
               	 
		</table>
           <div class="seprastor"></div>
                  <input name="submit" type="submit" value="{TXT_SUBMIT}" class="submitlogin button radius3px" />		
	</form>
    </div>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
    </div>
</div></div>
</div>
<!-- END: newsletter -->