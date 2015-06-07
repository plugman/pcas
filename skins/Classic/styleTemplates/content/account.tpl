<!-- BEGIN: account -->
 <div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php">Home</a> <span class="breadSeprator"></span>  Account
      <h2 class="mainheading"> Account</h2>
        
      </div>
    </div>
    <div class="maincenter">
      <div class=" mainbox"  style="width:950px; padding:0 10px 10px;">
      	

        <!-- BEGIN: session_true -->
        <div class="balanceinfo">
        <a href="{BALANCE}" class="yourbal">Your Balance</a>
        <div class="remainbal">
      <div class="youbal">  {VAL_BAL}&nbsp;<span style="font-size:18px; vertical-align:middle;">Available</span></div>
      <ul class="recamount">
      <li>Total Unlocks&nbsp;&nbsp;&nbsp;&nbsp;{VAL_REC}</li>
      <li>Locked Amount&nbsp;&nbsp;&nbsp;&nbsp;{VAL_LOCKED}</li>
      </ul>
        <a href="{BALANCE}" class="adfund"> + ADD FUNDS</a>
        </div>
        </div>
        <div class="loginbox3" style="width:338px; height:228px; margin-left:10px; padding-left:0;">
        <div class="cumdet">
        <img alt="" src="skins/{VAL_SKIN}/styleImages/userprofile.png"  style="float:left;"/>
        <div class="userinfo"><strong style="font-size:16px;">Welcome {VAL_USER}!</strong><br /><br />{VAL_EMAIL}<br /><strong>Last Login:</strong> {VAL_LAST_DATE}</div>
        </div>
       <div class="outerd"> <a  href="{PROFILE}" class="general info"><span class="textwidth">{TXT_PERSONAL_INFO}</span></a></div>
         <div class="outerd"><a  href="{ORDERS}" class="general his"><span class="textwidth">{TXT_ORDER_HISTORY}</span></a></div>
        <div class="outerd">  <a  href="{NEWS}" class="general track"><span class="textwidth">{TXT_NEWSLETTER}</span></a></div>
          <div class="outerd"> <a  href="{PASSWORD}" class="general cpass"><span class="textwidth">{TXT_CHANGE_PASSWORD}</span></a></div>
        </div>
        <div class="ordersum">
        <p class="orderhead">Order Summary</p>
        <ul>
        <li>Unlocked<span class="ordercount" style="color:#57a119">{UNLOCKED_VAL}</span><div class="bargb"><div class="bariner" style="width:{UNLOCKED_PER}%"></div></div></li>
        <li>Rejected<span class="ordercount" style="color:#c51114">{REJECTED_VAL}</span><div class="bargb"><div class="bariner rejected" style="width:{REJECTED_PER}%"></div></div></li>
        <li>Pending<span class="ordercount" style="color:#282828">{PENDING_VAL}</span><div class="bargb"><div class="bariner pending" style="width:{PENDING_PER}%"></div></div></li>
        </ul>
        </div>
        <div class="allorders">
       <p class="quickview">Quick View</p>
       <div class="soringorders ">
       <p><label>By Date:</label>
       <form method="post" action="YourAccount.html">
        <span class="jqsel bdate">
       <select onchange="this.form.submit();" name="date" id="cat">
       <option>All</option>
       
       <option value="1" {SELECTED_DATE1}>Yesterday</option>
     <option value="7" {SELECTED_DATE2}>Last Week</option>
     <option value="30" {SELECTED_DATE2}>Last month</option>
     <option value="90" {SELECTED_DATE4}>Last 3 month</option>       
       </select></span></form></p>
       <p><label>By Network:</label>
        <form method="post" action="YourAccount.html">
        <span class="jqsel network">
       <select onchange="this.form.submit();" name="network" class="small">
       <option>All</option>
        <!-- BEGIN: repeatproducrs -->
       <option value="{PRO_ID}" {SELECTED_PRO} >{PRO_NAME}</option>
        <!-- END: repeatproducrs -->
       </select></span></form></p>
       <p><label>By IMEI:</label>
       <form method="post" action="YourAccount.html" style="float:left;"  >

       <input type="text" value="{IMEI_TXT}" class="imeiinput" style=" margin-top:8px;" name="imei"  /></form></p>
       <p><label>By Status:</label>
       <form method="post" action="YourAccount.html" >
        <span class="jqsel">
       <select onchange="this.form.submit();" name="status">
       <option>All</option>
       <option value="1" {SELECTED_STATUS1}>Processing</option>
       <option value="2" {SELECTED_STATUS2}>Unlocked</option>
        <option value="3" {SELECTED_STATUS3}>Rejected</option>
       </select></span></form></p>
       </div>
       <table width="100%" border="1" cellpadding="3" cellspacing="0" style=" border-collapse:collapse; border:1px solid #edeaea">
		  <tr class="trorder">
		    <td align="center" class="tdcartTitle">Date</td>
            <td align="center" class="tdcartTitle">Order ID</td>
			<td align="center" class="tdcartTitle">Country Network</td>
			<td align="center" class="tdcartTitle">IMEI Number</td>
            <td align="center" class="tdcartTitle">Status</td>
		  </tr>
		  <!-- BEGIN: allorders -->
		  <tr style="height:55px;">
          <td align="center" class="{TD_CART_CLASS}">{VAL_DATE_TIME}</td>
		    <td align="center" class="{TD_CART_CLASS}"><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{DATA.cart_order_id}</a></td>
            
            <td align="center" class="{TD_CART_CLASS}">
            <table>
            <!-- BEGIN: allnetworks -->
            <tr>
            <td align="center" class="paddingtd" {BORDER_STYLE}><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{VAL_PRO_NAME}</a></td>
            </tr>
            <!-- END: allnetworks -->
            </table>
            </td>
			<td align="center" class="{TD_CART_CLASS}"> <table>
            <!-- BEGIN: allimei -->
            <tr>
            <td class="paddingtd" {BORDER_STYLE} ><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks">{VAL_PRO_IMEI}</a></td>
            </tr>
            <!-- END: allimei -->
            </table></td>
			
			<td align="center" class="{TD_CART_CLASS}"> <table>
            <!-- BEGIN: allstatus -->
            <tr>
            <td><span class="orstatus" {PENDING_STYLE}><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLinks" style="color:#fff;">{VAL_PRO_STATUS}</a></span></td>
            </tr>
            <!-- END: allstatus -->
            </table>
			</td>
		  </tr>
		  <!-- END: allorders -->
           <!-- BEGIN: noorders -->
           <tr>
           <td colspan="5"><span class="nopro">{TXT_NO_ORDERS}</span>
           </td>
           </tr>
            <!-- END: noorders -->
	  </table>
        </div>
        <!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
     
        
    </div>
    </div>
<!-- END: account -->

