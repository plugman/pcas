<!-- BEGIN: orderlookup -->
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php">Home</a><span class="breadSeprator"></span><a href="YourAccount.html">My Account</a> <span class="breadSeprator"></span> Track Order
     </div>
</div>
  <div class="maincenter">
    <h2 class="mainheading">Hello {VAL_CUSTOMER}</h2>
    <div class="account sitedoc">
      <div class="leftsideP">
    	<ul class="txt16 latoLight">
        	<li class="first"><a href="Profile.html" class="white" >Profile Settings</a></li>
        	<li><a href="Gallery.html" class="white" >My Gallery</a></li>
            <li><a href="Orders.html" class="white" >Order History</a></li>
            <li><a href="Order-Lookup.html" class="txtorange" >Track Order</a></li>
            <li><a href="ChangePassword.html" class="white" >Update Password</a></li>
            <li><a href="NewsLetter.html" class="white" >Newsletter Subscription</a></li>
            <li><a href="Logout.html" class="white" >Log Out</a></li>

        </ul>
    </div>
    	<div class= "rightsideP" >
         <h2  class="txt18 lucidaBold" > Here you can track your existing order</h2>
       <!-- BEGIN: error -->
       <div class="maindiv">
      <label style="color:#F00; float:left;">{LANG_ERROR}</label>
      </div>
      <!-- END: error -->
     
      <form action="index.php?_a=orderlookup" target="_self" method="post" >
        <div class="boxtrack2">
        
          
          <div class="maindiv"  >
          
            <label class="txt14 txt-grey maindiv">{LANG_EMAIL}</label><span class="required">*</span>
            <div class="txtboxmain">  
              <input type="text" name="email"  value="{EMAIL}" />
              </div>
          </div>
          <div class="maindiv" >
          
            <label class="txt14 txt-grey maindiv">Order #:</label><span class="required">*</span>
            <div class="txtboxmain">  
              <input type="text" name="cart_order_id"  value="{ORDERNO}" />
              </div>
          </div>
          
        <div class="seprastor"></div>
        <div class="maindiv">
        <input type="hidden" value="1" name="track" />
          <!-- <span  class="forgetpass txt14 txt-grey">{LANG_MANDATORY}</span><br />-->
            <input type="submit" name="submit" class="submitlogin button radius3px" value="Lookup Order" />
          
        </div>
        </div>
      </form>
      
      <!--  <p class="kazo">{LANG_P1}</p>--> 
      
    </div>
	</div>
  </div>

<!-- END: orderlookup -->