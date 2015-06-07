<!-- BEGIN: orderlookup -->

<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
    Order Lookup </div>
</div>
<div class="maindiv ">
  <div class="maincenter">
    <div class="maindiv mainbox"> 
      
      <!-- BEGIN: error -->
      <div class="maindiv">
        <label style="color:#F00; padding:0 32px; float:left;">{LANG_ERROR}</label>
      </div>
      <!-- END: error -->
      
      <form action="index.php?_a=orderlookup" target="_self" method="post" >
        <div class="boxtrack2">
          <div class="maindiv"  style="width:50%;">
            <label class="txt18 txt-grey maindiv">{LANG_EMAIL}</label>
            <div class="txtboxmain"> <span class="txtboxmain-left"></span>
              <input type="text" name="email"  value="{EMAIL}" />
            </div>
          </div>
          <div class="maindiv"  style="width:50%;">
            <label class="txt18 txt-grey maindiv">Order #:</label>
            <div class="txtboxmain"> <span class="txtboxmain-left"></span>
              <input type="text" name="cart_order_id"  value="{ORDERNO}" />
            </div>
          </div>
          <div class="maindiv"  style="width:50%;">
            <label class="txt18 txt-grey maindiv">{LANG_IMEI}</label>
            <div class="txtboxmain"> <span class="txtboxmain-left"></span>
              <input type="text" name="imei"  />
            </div>
          </div>
          <div class="maindiv"> <span  class="forgetpass txt18  txt-grey">{LANG_MANDATORY}</span><br />
            <input type="submit" name="submit" class="submitlogin button radius3px" value="Lookup Order" />
          </div>
        </div>
      </form>
      
      <!--  <p class="kazo">{LANG_P1}</p>--> 
      
    </div>
  </div>
</div>

<!-- END: orderlookup -->