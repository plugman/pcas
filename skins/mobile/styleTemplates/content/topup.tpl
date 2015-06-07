<!-- BEGIN: topup -->

<div class="maincontent">
   
    <div class="boxtop"> 
     <div class="headingboxtop ">
         {LANG_MAKE_PAYMENT}
        </div>
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
      <div  align="center" style="margin-top:20px;">
       	<div class="balancebox">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="txt24">
                <td class="txt-white" width="217" align="center" ><!--{LANG_CURRENT_BALANCE}-->Current Balance</td>
                <td style="padding-left:20px" class="txt-darkpurple">{BALANCE_AMOUNT}</td>
              </tr>
            </table>

        	
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
          <p style="margin-top:20px;" align="center">
            <input type="submit" class="submitlogin"  value="Make Payment »" style="float:none;" />
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
  
<!-- END: topup -->