<!-- BEGIN: mobile_access -->

		<script type="text/javascript">
			$(function(){
				 $('ul#mainmenu-nav li:has(ul)').append('<strong class="arrowli" />');
			 });
		</script>
	<!--<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         {LANG_HEADING}
      </div>
    </div>-->
    <div  class="maindiv">
    
    <!-- BEGIN: cat_desc -->
    	<!--<p>{TXT_CAT_DESC}</p>-->
    <!-- END: cat_desc -->  
          

          <div class="leftbox">
          {CATEGORIES}
         
          </div>
             
             <div class="headingbox2 headingbox"><span class="heading">{TXT_CAT_TITLE}</span></div>
            <div class="rightbox2 radius3px">
          <!-- BEGIN: productTable -->
            <!-- BEGIN: products -->
         
          <div class="prodbox">
            <div class="prodboxInner">
         	 <div class="picbox">
              <table width="100%" style="height:100%" >
                  <tr>
                  <td valign="middle" align="center">
                   <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self"><img src="{SRC_PROD_THUMB}" alt="{TXT_TITLE}" border="0" title="{TXT_TITLE}" /></a>
                  </td>
                  </tr>
              </table>
          </div>
          <p>
          <a class="txt16 title" href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}">{TXT_TITLE}</a><br />
          <span class="txtblue lucida txt24">{TXT_PRICE}&nbsp;&nbsp;{TXT_SALE_PRICE} </span><br />
         <!-- <div class="minqty">Minnimum Quantity {MIN_QUANTITY} items</div>
          <div class="ship"><img alt="" src="skins/{VAL_SKIN}/styleImages/freeshippingicon.jpg" />&nbsp;{SHIPPING}</div>-->
          
          <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="button radius3px">
            {BTN_MORE}
          </a>
          </p>
          
            <!--<div class="wishlink"><a href="index.php?_a=viewWish&amp;productId={PRODUCT_ID}">Add to wishlist</a></div>-->			</div>
          </div>
          
          <!-- END: products -->
      
          <!-- END: productTable -->
           <!-- BEGIN: noProducts -->
<div class="maindiv">{TXT_NO_PRODUCTS}</div>
<!-- END: noProducts -->
           <!--   <div class="pagediv">
              <div class="sorttext">{LANG_SORTBY}</div>
          <select id="sortMethod" class="textbox" onchange="goUrl('sortMethod');">
           <option value="{SORT_BEST}"{SORT_BEST_SELECTED}>{LANG_BEST}</option>
  <option value="{SORT_NAME}"{SORT_NAME_SELECTED}>{LANG_NAME}</option>
  <option value="{SORT_PRICE}"{SORT_PRICE_SELECTED}>{LANG_PRICE}</option>
  
</select>
         
          </div>-->
         <div class="maindiv">
         	 <!-- BEGIN: pagination_top -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_top -->
         </div>
            </div>
              <!-- BEGIN: related_products -->
            <!--<div class="premium">
             
                 	<div class="svhead">Premium Products</div>-->
                    <!-- BEGIN: repeat_prods -->
             <!-- <div class="rprodbox">
                    
                        <div class="picbox1">
                        	<table width="100%" style="height:100%">
                        	<tr>
                            	<td valign="middle" align="center">
                                	<a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}"><span class="discimage"> <img alt="" src="skins/{VAL_SKIN}/styleImages/disctag.png" /></span><img src="{VAL_IMG_SRC}" alt="{VAL_PRODUCT_NAME}" border="0" title="{VAL_PRODUCT_NAME}" /></a>
                                </td>
                            </tr>
                        </table>
                        </div>
                        <div class="prodtitle"><a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}" class="txtDefault">{VAL_PRODUCT_NAME}</a></div>
                        <div class="proddesc">{VAL_PRODUCT_LOCATION}</div>
                        
                        <div class="pricediv" {P}>{TXT_SALE_PRICE} / <span class='pricetext'>per piece</span></div>
                        <div class="pricediv" style="font-size:14px; font-weight:bold;">{TXT_PRICE}</div>
                    </div>-->
                    <!-- END: repeat_prods -->
             		
                  <!--  </div>-->
                    <!-- END: related_products --> 
        <!--  </div>-->
          
<!-- END: mobile_access -->

<div class="boxContent">
{CATEGORIES}
<strong>{LANG_DIR_LOC}</strong> <a href="index.php"><img src="skins/{VAL_SKIN}/styleImages/icons/home.gif" alt="{LANG_HOME}" border="0" title="{LANG_HOME}" /></a> {CURRENT_LOC}
<p class="txtContentTitle">{TXT_CAT_TITLE}</p>
<!-- BEGIN: cat_desc -->
<p>{TXT_CAT_DESC}</p>
<!-- END: cat_desc -->

<!-- BEGIN: sub_cats -->
<div id="subCats">
	<!-- BEGIN: sub_cats_loop -->
	<div class="subCat">
		<a href="index.php?_a=mobileacces&amp;catId={TXT_LINK_CATID}" class="txtDefault"><img src="{IMG_CATEGORY}" alt="{TXT_CATEGORY}" border="0" title="{TXT_CATEGORY}" /></a><br />
		<a href="index.php?_a=mobileacces&amp;catId={TXT_LINK_CATID}" class="txtDefault">{TXT_CATEGORY}</a> ({NO_PRODUCTS})
	</div>
	<!-- END: sub_cats_loop -->
</div>
<!-- END: sub_cats -->

<!-- BEGIN: cat_img -->
<img src="{IMG_CURENT_CATEGORY}" alt="{TXT_CURENT_CATEGORY}" border="0" title="{TXT_CURENT_CATEGORY}" />
<!-- END: cat_img -->

<!-- BEGIN: pagination_top -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_top -->

<!-- BEGIN: productTable -->
<div style="text-align: right; margin: 0px 7px;">
<select id="sortMethod" class="textbox" onchange="goUrl('sortMethod');">
  <option value="{SORT_NAME}"{SORT_NAME_SELECTED}>{LANG_NAME}</option>
  <option value="{SORT_PRICE}"{SORT_PRICE_SELECTED}>{LANG_PRICE}</option>
</select>
<!--<input type="button" class="txtButton" value="{LANG_SORT}" onclick="goUrl('sortMethod');" />-->
</div>
<hr style="clear: both; visibility: hidden;" />
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="tblList">
  <tr>
    <td align="center" class="tdListTitle"><strong>{LANG_IMAGE}</strong></td>
    <td class="tdListTitle"><a href="{SORT_NAME}" class="sortLink"><strong>{LANG_NAME}</strong></a> {SORT_ICON}</td>
    <td align="center" class="tdListTitle"><a href="{SORT_PRICE}" class="sortLink"><strong>{LANG_PRICE}</strong></a> {SORT_ICON}</td>
	<td class="tdListTitle">&nbsp;</td>
  </tr>
  <!-- BEGIN: products -->
  <tr>
    <td align="center" class="{CLASS}"><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self"><img src="{SRC_PROD_THUMB}" alt="{TXT_TITLE}" border="0" title="{TXT_TITLE}" /></a></td>
    <td valign="top" class="{CLASS}"><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="txtDefault"><strong>{TXT_TITLE}</strong></a><br />
	{TXT_DESC}<br /><span class="txtOutOfStock">{TXT_OUTOFSTOCK}</span></td>
	<td align="center" class="{CLASS}">{TXT_PRICE}
    <div class="txtSale">{TXT_SALE_PRICE}</div></td>
    <td align="left" valign="top" nowrap='nowrap' class="{CLASS}">
	<a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="cart_icon"><img src="skins/{VAL_SKIN}/styleImages/icons/information.gif" alt="{BTN_MORE}" title="{BTN_MORE}" border="0" /></a>
	<!-- BEGIN: buy_btn -->
	<form action="{CURRENT_URL}" style="text-align:center;" method="post" name="prod{PRODUCT_ID}">
	<input type="hidden" name="add" value="{PRODUCT_ID}" />
	<input type="hidden" name="quan" value="1" />
	<input type="image" class="cart_icon" src="skins/{VAL_SKIN}/styleImages/icons/cart_put.gif" alt="{BTN_BUY}" title="{BTN_BUY}" />
	</form>
	<!-- END: buy_btn -->
	</td>
</tr>
<!-- END: products -->
</table>
<!-- END: productTable -->

<!-- BEGIN: noProducts -->
<div>{TXT_NO_PRODUCTS}</div>
<!-- END: noProducts -->

<!-- BEGIN: pagination_bot -->
<div class="pagination">{PAGINATION}</div>
<!-- END: pagination_bot -->
</div>