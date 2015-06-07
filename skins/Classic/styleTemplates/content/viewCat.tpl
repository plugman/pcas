<!-- BEGIN: mobile_access -->
<script type="text/javascript" src="js/jquery.aw-showcase.js" ></script>
<script type="text/javascript">
 
$(document).ready(function()
{
	$("#showcase1").awShowcase(
	{
		content_width:			690,
		content_height:			269,
		fit_to_parent:			false,
		auto:					false,
		interval:				3000,
		continuous:				true,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					false,
		buttons:				true,
		btn_numbers:			false,
		keybord_keys:			true,
		mousetrace:				false, /* Trace x and y coordinates for the mouse */
		pauseonover:			true,
		stoponclick:			false,
		transition:				'hslide', /* hslide/vslide/fade */
		transition_delay:		0,
		transition_speed:		500,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				false,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'horizontal', /* vertical/horizontal */
		thumbnails_slidex:		1, /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
		dynamic_height:			false, /* For dynamic height to work in webkit you need to set the width and height of images in the source. Usually works to only set the dimension of the first slide in the showcase. */
		speed_change:			true, /* Set to true to prevent users from swithing more then one slide at once. */
		viewline:				false, /* If set to true content_width, thumbnails, transition and dynamic_height will be disabled. As for dynamic height you need to set the width and height of images in the source. */
		custom_function:		null /* Define a custom function that runs on content change */
	});
});

</script>
<script type="text/javascript">
			$(function(){
				$('ul#mainmenu-nav li:has(ul)').addClass('hassub');
			 });
		</script>
  <!-- BEGIN: added --> 
<script type="text/javascript">
window.setTimeout(ShowBasket,500);
</script> 

<!-- END: added -->
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         {LANG_HEADING}
      </div>
    </div>
    <div class="maincenter">
    <!-- BEGIN: cat_desc -->
    	<!--<p>{TXT_CAT_DESC}</p>-->
    <!-- END: cat_desc -->  
          

          <div class="leftbox">
          {CATEGORIES}
          <!-- BEGIN: recent -->
          <div class="recent">
          <div class="svhead1">Recently Viewed Items</div>
          <div class="rprodbox1">
           
               <!-- BEGIN: repeat -->
          <div class="picbox2">
          <table width="100%" style="height:100%">
          <tr>
          <td valign="middle" align="center">
           <a href="index.php?_a=viewProd&amp;productId={PROD_ID}" target="_self"><img src="{REC_IMG_SRC}" alt="" border="0" width="56" height="55" /></a>
          </td>
          </tr>
          </table>
          </div>
           <!-- END: repeat -->
        
          </div>
          </div>
            <!-- END: recent -->
          </div>
             <div class="rightbox2" style="min-height:288px;">
             <div class="boxTitleLeft">{TXT_CAT_TITLE}</div>
             <div class="catbanner">
      <div id="showcase1" class="showcase"> 
        <!-- BEGIN:banner_true --> 
        <!-- BEGIN:repeat -->
        <div class="showcase-slide">
          <div class="showcase-content"> <a href="{DATA.img_link}"> <img title="{DATA.img_title}" src="uploads/flashbanner/{DATA.img_file}" class="slide" alt="" /> </a> </div>
        </div>
        <!-- END:repeat --> 
        <!-- END:banner_true --> 
        
      </div>
    </div>
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
          <div class="title"><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}">{TXT_TITLE}</a></div>
          <div class="pricediv2 lucidaBold txt18">{TXT_PRICE}&nbsp;&nbsp;{TXT_SALE_PRICE} </div>
         <!-- <div class="minqty">Minnimum Quantity {MIN_QUANTITY} items</div>
          <div class="ship"><img alt="" src="skins/{VAL_SKIN}/styleImages/freeshippingicon.jpg" />&nbsp;{SHIPPING}</div>-->
          <div class="cartbtn">
          <!-- BEGIN: buy_btn -->
	<form action="{CURRENT_URL}" style="text-align:center; display:inline-block" method="post" name="prod{PRODUCT_ID}">
	<input type="hidden" name="add" value="{PRODUCT_ID}" />
	<input type="hidden" name="quan" value="1" />
	<input type="submit" class="cart_icon button radius3px" title="{BTN_BUY}" value="Buy" />
	</form>
	<!-- END: buy_btn -->
          <a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" target="_self" class="button radius3px" title="More Details">
            {BTN_MORE}
          </a>
          </div>
            <!--<div class="wishlink"><a href="index.php?_a=viewWish&amp;productId={PRODUCT_ID}">Add to wishlist</a></div>-->			</div>
          </div>
          
          <!-- END: products -->
      
          <!-- END: productTable -->
           <!-- BEGIN: noProducts --><br />
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
            <div class="premium">
             
                 	<div class="svhead">Premium Products</div>
                    <!-- BEGIN: repeat_prods -->
              <div class="rprodbox">
                    
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
                    </div>
                    <!-- END: repeat_prods -->
             		
                    </div>
                    <!-- END: related_products --> 
          </div>
          
<!-- END: mobile_access -->