<!-- BEGIN: view_cat -->
<!--<div class="maindiv breadbg">
      
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         iPhone Unlock
      
    </div>-->
    
      <div class="maincontent">
        <!--<div class="headingBorder maindiv">
        <h3 class="txt18 txt-purple" style="color:#281942">
          {LANG_H1} 
        </h3>
        <span>&nbsp;</span>
        </div>-->
        <div class="country" style="display: none">
       
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
       
       
      
        {TXT_MAIN_TEXT}<br />
       <ul class="tablist2 tablist">
         <!-- BEGIN: cat_true -->
         <script type="text/javascript">
       
		
		$(document).ready(function(){
  $(".ab{TXT_CAT_ID}").click(function(){
    
      $(".cd{TXT_CAT_ID}").toggle('active');
    $(".ab{TXT_CAT_ID} span").toggleClass("arrow3");
  });
  
});
    </script>
         <li id="ab{TXT_CAT_ID}" class="detailbox5 maindiv">
          <div class="headingBorder2 ab{TXT_CAT_ID} ">
          <div class="imgbox">
           <img alt="NoImage" src="skins/{VAL_SKIN}/styleImages/networkimg{IMAGEINDEX}.png" height="97" />
          </div>
          {TXT_CAT_TITLE}
       
        	<span>&nbsp;</span>
        </div>
       
        <!-- BEGIN: productTable -->  
        <div class="mainbox5 maindiv cd{TXT_CAT_ID}">   
  <!-- BEGIN: products -->
        <div class="inerbox5">
        
        <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="imgcenter">
        <img src="{SRC_PROD_THUMB}" alt="{TXT_TITLE}" border="0" title="{TXT_TITLE}"  />
        </a>
        <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="titledet txt-darkpurple txt18">
        {TXT_TITLE}
        </a><br />
        <span class="pricebox">
        	price<br />
          <strong class="txt14">
          	{TXT_PRICE}
          </strong>
        </span>
	<span class="timepe" >
    Delivery Time<br />
    <strong class="txt14">{TXT_DESCDELVERY_TIME}</strong>
    </span>
    
    <!-- <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}">
     <img alt="" src="skins/{VAL_SKIN}/styleImages/ordernow2.jpg" />
     </a>-->
    	
        		</div>
        <div class="sepratorCat"></div>  
                <!-- END: products -->
                </div>
                <!-- END: productTable -->
                <!-- BEGIN: noProducts -->
<div style="float:left;">{TXT_NO_PRODUCTS}</div>
<!-- END: noProducts -->

<!-- BEGIN: pagination_bot -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_bot -->
          </li>
          <!-- END: cat_true -->
          </ul>
          
          
          </div>

          
<!-- END: view_cat -->



