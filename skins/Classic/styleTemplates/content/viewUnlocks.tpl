<!-- BEGIN: view_cat -->
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         iPhone Unlock
      </div>
    </div>
    <div class="maincenter">
    
        <div class="headingBorder maindiv">
            <h3 class="txt18 txt-purple" >
              {LANG_H1} 
            </h3>
        </div>
        <div class="country" style="display:none" >
       
        <label class="left" >{LANG_COUNTRY}</label>
         <div class=" right menus" id="menus" >
        	<div class="arrow"> </div>
         
           <ul>
                   
       		 <li>Select Country
          
          <ul>
      <!-- BEGIN: cat_selLoop -->
 
    <li><a href="{JUMPTO}" >{TXT_CAT_TITLE}</a></li>
   <!-- END: cat_selLoop-->
  </ul>

  </li>
  </ul>
  
         </div>
        </div>
       
       
          <div class="box">
       		{TXT_MAIN_TEXT}
       	   </div>
         <!-- BEGIN: cat_true -->
          <div class="detailbox5 maindiv" id="ab{TXT_CAT_ID}" >
            <div class="headingBorder maindiv">
                <h3 class="txt16 txt-purple">
                {TXT_CAT_TITLE}
                </h3>
            </div>
       <div class="box">
        <!-- BEGIN: productTable -->     
  <!-- BEGIN: products -->
        <div class="inerbox5 radius2px">
        <div class="imgcenter">
        <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self"><img src="{SRC_PROD_THUMB}" alt="{TXT_TITLE}" border="0" title="{TXT_TITLE}" /></a>
        </div>
        <div class="detbox">
        <span ><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="txtDefault"><strong class="titledet txt-purple">{TXT_TITLE}</strong></a></span><br />
<strong class="pricebox">{TXT_PRICE}</strong><br />
    
	<span class="timepe">{TXT_DESCDELVERY_TIME}
    </span>
    </div>
     <a class="orderNow radius2px" href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}">Order Now</a>
    	
        		</div>
                <!-- END: products -->
                <!-- END: productTable -->
                </div>
                <!-- BEGIN: noProducts -->
<div style="float:left;">{TXT_NO_PRODUCTS}</div>
<!-- END: noProducts -->

<!-- BEGIN: pagination_bot -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_bot -->
          </div>
          <!-- END: cat_true -->
         
          </div>
<!-- END: view_cat -->



