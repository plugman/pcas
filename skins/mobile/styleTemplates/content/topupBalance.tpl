<!-- BEGIN: topup_balance -->
<div class="loginbox3">
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
     </span>Change Password</a></li>
     <li class="tablista"><a  href="Balance.html"> <span class="imgbox">
     <img title="" src="skins/{VAL_SKIN}/styleImages/pr5.png" alt="">
     </span>{LANG_TOPUP_YOUR_BALANCE_TITLE} </a></li>
     </ul>

  <div class="maindiv mainbox">
    
    <div class="" style="padding:10px;">
      <div class="boxContent" style="border:none;">
        <h3 class=" txt16 txt-darkpurple " > How much prepaid Credit do you want to deposit? </h3>
        <!-- BEGIN: session_true --> 
        <span style="color:#F00; left:190px; top:18px;"> 
        <!-- BEGIN: error --> 
        {VAL_ERROR} 
        <!-- END: error --> 
        <!-- BEGIN: payment_message --> 
        {PAYMENT_MESSAGE} 
        <!-- END: payment_message --> 
        </span> 
        <!-- BEGIN: form --> 
        
        <!-- BEGIN: payment_options -->
        <div class="maindiv">
          <form action="{URI_TOPUP}" target="_self" method="post" id="frmPayment" name="frmPayment">
            <input type="hidden" id="cur_balance" name="cur_balance" value="{VAL_BALANCE}" />
            <input  type="hidden" name="optPayment" value="3"  />
            <input  type="hidden" name="paypalfee" value="30"  />
            <div class="inputamount">
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input name="paypal_amount" type="text"  id="paypal_amount" value="Enter amount here..." onclick="if(this.value=='Enter amount here...') this.value='';" onblur="if(this.value=='')this.value='Enter amount here...'" />
                <span class="txtboxmain-right"> <span class="mandatory" style="top:1px;"></span> </span> </div>
              <div class="paynow">
                <input name="submitPaymentOption" type="submit" value="Pay Now Via PayPal" class="submitlogin"   />
                <p class="txt-darkpurple txt13"style="font-weight: bold;">3.5% PayPal Processing Fee *</p>
              </div>
            </div>
          </form>
          <div class="topupbaldetail">
            <h1 class="yourbal yourbal2" >Current Balance</h1>
            <div class="maindiv">
              <h1 class="rembalance">{VAL_BALANCE}</h1>
            </div>
            <span class="spantext">Want to increase your Credit balance? Enter your desired amount and pay through PayPal. </span> </div>
        </div>
        
        <!-- END: payment_options --> 
        
        <!-- END: form --> 
        
        <!-- END: session_true --> 
        <!-- BEGIN: session_false -->
        <div class="fieldbg">
          <div class="maindiv  formfieldpanel">
            <div class="maindiv" style="color:#FF0000; margin-left:195px;"> {LANG_LOGIN_REQUIRED} </div>
          </div>
        </div>
        <!-- END: session_false -->
        
        <div class="maindiv" style="padding-top:30px;">
          <div class="innerpanels">
            <div class="headingBorder maindiv">
              <h3 class="txt16 txt-purple"> Credits Order History </h3>
              <span>&nbsp;</span> </div>
            
            <!-- BEGIN: recharge_history -->
            <div class="maindiv" style="border:1px solid #ccc" >
              <table  border="0" cellpadding="3" cellspacing="0" width="100%" >
                <tr class="tabelhead"  >
                  <td  align="center" class="bleft Orangeheader">{TXT_SCRATCH_CODE}</td>
                  <td  align="center" class="bleft Orangeheader">Method</td>
                  <td  align="center" class="bleft Orangeheader">{TXT_PRICE}</td>
                  <td  align="center" class="bleft Orangeheader">{TXT_DATE_USED}</td>
                  <td  align="center" class="bleft Orangeheader">{TXT_STATUS}</td>
                  <td  align="center" class="bleft Orangeheader">{TXT_NOTES}</td>
                </tr>
                <!-- BEGIN: repeat_cards -->
                <tr {ROW_STYLING}  class="rowheight">
                  <td align="center" class="{TD_CART_CLASS}">{VAL_SCRATCH_CODE}</td>
                  <td align="center" class="{TD_CART_CLASS}">{VAL_GATEWAY}</td>
                  <td align="center" class="{TD_CART_CLASS}">{VAL_PRICE}</td>
                  <td align="center" class="{TD_CART_CLASS}">{VAL_DATE_USED}</td>
                  <td align="center" class="{TD_CART_CLASS}">{VAL_STATUS}</td>
                  <td align="center" class="{TD_CART_CLASS}">
                  <input type="hidden" id="paymentStatus" name="paymentStatus" value="{VAL_STATUS}" />
                    {VAL_NOTES} </td>
                </tr>
                <!-- END: repeat_cards -->
              </table>
              
              <!-- BEGIN: pagination_bot -->
              <div class="pagination" style="text-align:right;">{PAGINATION}</div>
              <!-- END: pagination_bot --> 
            </div>
            <!-- END: recharge_history --> 
            <!-- BEGIN: recharge_history_not_found -->
            <div class="fieldbg" style="min-height:125px;">
              <div class="maindiv"> {NO_TOPUP_BALANCE_HISTORY} </div>
            </div>
            <!-- END: recharge_history_not_found --> 
          </div>
        </div>
        <script type="text/javascript" language="javascript">
function ShowStatus()
{
document.getElementById('divStatus').style.display ='';
document.getElementById('divStatus2').innerHTML = document.getElementById('paymentStatus').value;
}
function HideStatus()
{
document.getElementById('divStatus').style.display ='none';
}
</script> 
      </div>
    </div>
  </div>
  
</div>
<!-- END: topup_balance -->