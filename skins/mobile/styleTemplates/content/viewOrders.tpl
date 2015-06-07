<!-- BEGIN: view_orders -->
        <div class="loginbox3">
	   <ul class="tablist">
   	<li class="tablista ">
             <a  href="Balance.html"> <span class="imgbox">
             <img title="" src="skins/{VAL_SKIN}/styleImages/balanc.png" alt="">
             </span>Your Balance</a>
            </li>
     <li class="tablista">
   
     <a  href="Profile.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr1.png" alt="">
     </span>Personal Information</a></li>
     <li class="tablista active"> <a  href="Orders.html" >
         <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr2.png" alt="">
     </span>Order History</a></li>
     </ul>
    
<div class="maindiv mainbox">
	<div  class="p10">
	<strong>{LANG_ORDER_LIST}</strong>
	
	<!-- BEGIN: session_true -->
	<div class="vieworder">	
		<!-- BEGIN: orders_true -->
		
		<table width="100%" border="1" cellpadding="3" cellspacing="0" >
		  <tr class="trorder">
		    <td width="80" align="center" class="tdcartTitle">{LANG_ORDER_NO}</td>
            <td align="center" class="tdcartTitle">{LANG_DATE_TIME}</td>
            <td align="center" class="tdcartTitle">Country Network</td>
            <td align="center" class="tdcartTitle">IMEI Number</td>
			<td align="center" class="tdcartTitle">{LANG_STATUS}</td>
			<td align="center" class="tdcartTitle">{LANG_ACTION}</td>
		  </tr>
		  <!-- BEGIN: repeat_orders -->
		  <tr style="height:75px;">
		    <td align="center" class="{TD_CART_CLASS}"><a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="txtLink">{DATA.cart_order_id}</a></td>
            <td align="center" class="{TD_CART_CLASS}">{VAL_DATE_TIME}</td>
             <td align="center" class="{TD_CART_CLASS}">{NAME_REP}</td>
              <td align="center" class="{TD_CART_CLASS}">{IMEI_REP}</td>
			<td align="center" class="{TD_CART_CLASS}">{VAL_STATE}</td>
			
			<td align="center" class="{TD_CART_CLASS}">
            <a href="index.php?_g=co&amp;_a=viewOrder&amp;cart_order_id={DATA.cart_order_id}" class="viewordert">{LANG_VIEW_ORDER}</a>
            
			
			<!-- BEGIN: make_payment -->
			<br />
			<a href="index.php?_g=co&amp;_a=step3&amp;cart_order_id={DATA.cart_order_id}" class="viewordert viewordert2">{LANG_COMPLETE_PAYMENT}</a>
			<!-- END: make_payment -->
			<!-- BEGIN: courier_tracking -->
			<br />
			<a href="{TRACKING_URL}" class="txtLink" target="_blank">{LANG_COURIER_TRACKING}</a>
			<!-- END: courier_tracking -->
			</td>
		  </tr>
		  <!-- END: repeat_orders -->
	  </table>
		
		<!-- END: orders_true -->
		
		<!-- BEGIN: orders_false -->
		<p>{LANG_NO_ORDERS}</p>
		<!-- END: orders_false -->
	</div>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->
	</div>		
</div>
<ul class="tablist">

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
<!-- END: view_orders -->