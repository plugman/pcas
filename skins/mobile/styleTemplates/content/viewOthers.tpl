<!-- BEGIN: view_cat -->
<div class="maindiv breadbg">
      <div class=" maincenter">
        <a href="index.php" class=" homeclr">Home</a> / {TXT_CAT_TITLE}
      </div>
    </div>
    <div class="maincenter">
      <div class="maincontent">
        <h3 class="h3arial">
          {TXT_CAT_TITLE} &nbsp; &nbsp; &nbsp; &nbsp;
          &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        </h3>
        
        <div class="blankdiv"></div>
      
          <div class="mainpricebox">
           <!-- BEGIN : cat_desc -->
       {TXT_CAT_DESC}
        <!-- END : cat_desc -->
         <!-- <h3 class="pricehead">{LANG_H1}
          </h3><br />
          <label class="kazo">{LANG_H2}</label>
          <p class="para">{LANG_PARAGRAPH}</p>
          <br />
          <label class="kazo">{LANG_H3}</label>--><br /><br />
         <!-- BEGIN: cat_true -->
          <div class="detailbox5" id="{TXT_CAT_ID}">
          <div class="blankdiv" style="width:920px;"></div>
        <!-- BEGIN: productTable -->     
  <!-- BEGIN: products -->
        <div class="inerbox5">
        <div class="imgcenter">
        <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self"><img src="{SRC_PROD_THUMB}" alt="{TXT_TITLE}" border="0" title="{TXT_TITLE}" /></a>
        </div>
        <div class="detbox">
        <span ><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="txtDefault"><strong class="titledet">{TXT_TITLE}</strong></a></span><br />
<br /><strong class="pricebox">{TXT_PRICE}&nbsp;{TXT_SALE_PRICE}</strong><br />
    
	<span class="timepe">{TXT_DESCDELVERY_TIME}
    </span>
    </div>
     <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}"><img alt="" src="skins/{VAL_SKIN}/styleImages/ordernow2.jpg" /></a>
    	
        		</div>
                <!-- END: products -->
                <!-- END: productTable -->
                <!-- BEGIN: noProducts -->
<div style="float:left;">{TXT_NO_PRODUCTS}</div>
<!-- END: noProducts -->

<!-- BEGIN: pagination_bot -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_bot -->
          </div>
          <!-- END: cat_true -->
          </div>
          
          </div>
          </div>
<!-- END: view_cat -->



