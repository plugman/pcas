<!-- BEGIN: topup -->
<div class="maindiv breadbg">
      <div class=" maincenter">
      <a href="index.php">Home</a> <span class="breadSeprator"></span> 
         iPhone Unlock
      </div>
    </div>
<div class="maincenter">
    <div class="headingBorder maindiv">
        <h3 class="txt18 txt-purple">
         {LANG_MAKE_PAYMENT}
        </h3>
        <span>&nbsp;</span>
        </div>
    <div class="orderhistory"> 
    
      <!-- BEGIN: payment_done -->
       <strong>{SUCCESS_MSG}</strong>
      <p style="padding:0px 20px 20px 0px; text-align:center; color:#F90;"> <br />
        <br />
        <br />
        {LANG_REMAINING_ACCOUNT_BALANCE} <strong><span style="color:#999">{CURRENT_BALANCE_AMOUNT}</span></strong> </p>
      
      <!-- END: payment_done --> 
      
      <!-- BEGIN: cart_false -->
      <p  style="margin-top:20px; text-align:center; color:#F00;"><strong>{LANG_CART_EMPTY}</strong></p>
      <!-- END: cart_false --> 
      <!-- BEGIN: cart_true -->
      <div class="maindiv">
       	<div class="balancebox">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="txt24">
                <td class="txt-white" width="217" align="center" ><!--{LANG_CURRENT_BALANCE}-->Current Balance</td>
                <td style="padding-left:20px" class="txt-darkpurple">{BALANCE_AMOUNT}</td>
              </tr>
            </table>

        	<span cl></span><span></span>
        </div>
      	<p style="padding:0px 20px 20px 0px;">  <strong></strong> </p>
        <form action="index.php?_g=co&_a=topup" method="post" name="topuppaynow" target="{VAL_TARGET}">
          <div class="tablebox">
          <table  cellspacing="0" width="100%" cellpadding="0" class="txt-darkpurple" >
            <tr>
              <td width="150px"  align="right"
               style="padding-right:20px; border-bottom:1px solid #fff; border-right:1px solid #fff;"><strong>{LANG_ORDER_ID}</strong></td>
              <td style="border-bottom:1px solid #fff; padding-left:50px"> {VAL_ORDER_ID} </td>
            </tr>
            <tr>
              <td class="{TD_CART_CLASS}" align="right" style="padding-right:20px; border-bottom:1px solid #fff; border-right:1px solid #fff;"><strong>{LANG_AMOUNT_TO_PAY}</strong></td>
              <td   style="border-bottom:1px solid #fff;padding-left:50px"> {AMOUNT} </td>
            </tr>
            
            <tr>
              <td align="right" style="padding-right:20px;border-right:1px solid #fff;"><strong>{LANG_REMAINING_BALANCE}</strong></td>
              <td style="padding-left:50px">{VAL_BALANCE_AMOUNT}</td>
            </tr>
            
          </table>
          </div>
          <!-- BEGIN: paynow-->
          <p style="margin-top:20px;">
            <input type="submit" class="submitlogin button radius3px"  value="Make Payment »" />
            <input type="hidden" name="topup" value="paynow" />
            <input type="hidden" name="cart_order_id" value="{VAL_ORDER_ID}" />
          </p>
          <!-- END: paynow-->
        </form>
        
        <!-- BEGIN: paynow_false-->
        <p style="padding:20px 20px 20px 0px; text-align:center"> {LANG_ERROR_ACCOUNT_BALANCE} <a href="index.php?_a=topupBalance&cart_order_id={VAL_ORDER_ID}" target="_parent" style="color:#F60"><strong>{LANG_CLICK_HERE}</strong></a></p>
        <!-- END: paynow_false--> 
      </div>
      
      <!-- END: cart_true --> 
    </div>
  </div>
  </div>
</div>
<!-- END: topup -->