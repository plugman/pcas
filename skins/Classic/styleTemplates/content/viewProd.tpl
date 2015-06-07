<!-- BEGIN: view_prod -->
<script type="text/javascript">
function stringval(id){
	var str = document.getElementById(id).value;
	var n = str.replace(/'/g,"");
	var m = n.replace(/\"/g, "");
	document.getElementById(id).value =m;
}
</script>
<script type="text/javascript">
	$(document).ready(function()
{
    $("#txtimeii").attr('maxlength','15');
	$("#selcetproductdetail").val($('ul#ulvalue > li:first').val());
	$("#selectedopt").text($('ul#ulvalue > li:first').text());

$("#dropselect").click(function()
{
	    $("#selcetproductdetail").trigger('change');

});
});
     </script>

<!-- BEGIN: added --> 
<script type="text/javascript">
window.setTimeout(ShowBasket,500);
</script> 

<!-- END: added -->
  <!-- BEGIN: digital -->
<div class="maindiv breadbg txt-grey">
  <div class=" maincenter">
   <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a>/
   <a href="index.php" class=" homeclr">iPhone Unlock </a> / Order Now 
   </div>
</div>

<form action="{CURRENT_URL}" method="post" id="prod{PRODUCT_ID}" name="addtobasket" target="_self" onsubmit="javascript:return Validateproduct();">
 <!-- BEGIN: prod_true -->
<div class="maincenter">
    <div class="mainorderbox radius2px">
        <!-- BEGIN: opts_notice -->
	<p class="txtError">{LANG_OPTS_NOTICE}</p>
	<!-- END: opts_notice -->

      <div class="boxsplit" style="position:relative;">
      <h3 class="headingprductdetail"> {TXT_PRO_TITLE}</h3>
        <table border="0" cellpadding="3" cellspacing="8" class="tablest">
          <tr>
            <td width="130" align="right" >Network: </td>
            <td  class="kk"><div class=" menus" id="menus">
                <div class="dropdown arrow"> </div>
                <ul>
                  <li>{SELECTED_PROD.name} 
                    <!-- BEGIN: topcat-->
                    <ul>
                      <!-- BEGIN: cat_loop -->
                      
                      <li class="first-top">{CAT_NAME}</li>
                      <!-- BEGIN: all --> 
                      <!-- BEGIN: loop -->
                      <li> <a href="index.php?_a=viewProd&amp;productId={ALL_PROD.productId}" >{ALL_PROD.name}</a></li>
                      <!-- END: loop --> 
                      <!-- END: all --> 
                      <!-- END: cat_loop -->
                    </ul>
                    <!-- END: topcat--> 
                  </li>
                </ul>
              </div>
              <span class="star1">*</span></td>
          </tr>
          <tr>
            <td></td>
            <td   align="left"  class="txt14 txt-red">{TXT_SDESC}</td>
          </tr>
        
           
             
              
                <!-- BEGIN: prod_opts --> 
                <!-- BEGIN: repeat_options -->
                
                <tr>
                 
                    <td  align="right">{VAL_OPTS_NAME} </td>
                    <td >
                     <div class=" menus" id="menus2">
                    <div class="dropdown arrow"> </div>
                <input type="hidden" value="" id="selcetproductdetail" name="productOptions[{VAL_OPTION_ID}]"  />
                		<ul>
                  			<li><span  id="selectedopt"></span>
                            	<ul id="ulvalue">
                                	 <!-- BEGIN: repeat_values -->
                                	<li value="{VAL_ASSIGN_ID}" onclick="changeopt(this.value, '{VAL_VALUE_NAME}');" class="valoption"><a>{VAL_VALUE_NAME}</a> </li>
                                    <!-- END: repeat_values -->
                                </ul>
                            </li>
                  		</ul>
                     </div>
                   <!--<div class="selcetproductdetail">
                    <label for="selcetproductdetail" class="arrowp2"  id="dropselect"> </label>
                    <select  id="selcetproductdetail"  name="productOptions[{VAL_OPTION_ID}]">
                        
                        <option value="{VAL_ASSIGN_ID}"> {VAL_VALUE_NAME} 
                       
                        
                      
                      </select>
                     </div>-->
                      <span class="star1"> *</span> </td>
                  </tr>
                  <!-- END: repeat_options -->
                
                 
                <tr>
                    <td  align="right" class="lefttd">IMEI Number : </td>
                    <td>
                    <!-- BEGIN: singleimei -->
                    <input type="text" name="imei"  onchange="stringval('txtimei');" id="txtimeii" class=" menus"  onkeypress="return isNumberKey(event)"  style="text-indent:5px;" /> <span class="star1"> *</span>
                    <input type="hidden" id="txtimei" value="123456789012345" />
                    <!-- END: singleimei -->
                     <!-- BEGIN: multipleimei -->
                   <textarea onchange="stringval('txtimei');" id="txtimei" class=" menus"  onkeypress="return isNumberKey(event)" style="height:100px; padding-top:10px; padding-left:10px; width:445px;" name="imei" onkeyup="keyup_imei()" ></textarea>
                   <input type="hidden" value="1" name="mulitimi" /> <span class="star1"> *</span>
                   <p class="noticetext">You can enter several serial numbers (one per line) if you have several similar phones (for the same unlock solution and the same information)</p>
                <input type="hidden" value="{PROPRICE}" id="cost_1_unlock" />
                <input type="hidden" value="{SYMBL_LEFT}" id="symbolleft" />
                    <!-- END: multipleimei -->
                     </td>
                  </tr>
                  <!-- BEGIN: text_opts -->
                <tr>
                    <td valign="top" align="right" >{VAL_OPTS_NAME} 
                      <!-- BEGIN: repeat_price --> 
                      ({VAL_OPT_SIGN}{VAL_OPT_PRICE}) 
                      <!-- END: repeat_price --></td>
                    <td>
                    <!-- BEGIN: textbox -->
                      
                      <input type="text" name="productOptions[{VAL_OPTION_ID}]" class=" menus q2"  onchange="stringval('productOptions_{VAL_OPTION_ID}');"  id="productOptions_{VAL_OPTION_ID}" />
                     <span class="star1"> * </span>
                      <!-- END: textbox --> 
                      <!-- BEGIN: textarea -->
                      
                      <textarea  name="productOptions[{VAL_OPTION_ID}]" class=" menus" cols="1" rows="1" style="height:90px; text-indent:5px" onchange="stringval('productOptions_{VAL_OPTION_ID}');"  id="productOptions_{VAL_OPTION_ID}"></textarea>
                   <div class="maindiv txtred" style="font-size:11px;"> You can enter several serial numbers (one per line) if you have several similar phones<br /> (for the
same unlock solution and the same information)</div>
                      
                      <!-- END: textarea -->
                      </td>
                  </tr>
                  <!-- END: text_opts --> 
                  <!-- END: prod_opts -->
          <tr>
            <td>&nbsp;</td>
            <td>
            	
         <div class="dlvtime">
          <span class="txt14 txt-grey"> {LANG_DELTIMETXT}</span><br />
          <span class="txt24 txtgreen lucidaBold" style="display: inline-block; padding-top: 5px;">  {TXT_DELTIME}</span>
          </div>
           <div class="dlvtime">
            <span class="txt14 txt-grey"> {LANG_PRICE}</span><br />
             <span class="txt24 txtgreen lucidaBold" style="display: inline-block; padding-top: 5px;"  id="totalprice">
             	{TXT_PRICE_VIEW} 
                {TXT_SALE_PRICE_VIEW}
             </span>
           </div>
            </td>
          
          </tr>
           <!-- BEGIN: facebook_page -->
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
             <tr>
            	<td>&nbsp;</td>
                <td><script language="javascript" type="text/javascript">
//<![CDATA[
document.write('<div class="fb-like" data-href="{FACEBOOK_PAGE_ADDRESS}" data-send="false" data-width="450" data-show-faces="false" data-font="segoe ui"></div>');
//]]>
</script></td>
            </tr>
             <!-- END: facebook_page -->
             </table>   
         
        
      </div>
      <div class="right">
      		<img alt="" src="{IMG_SRC2}"   />
      </div>
    </div>

<div class="bg2 radius2px">
 <div class="boxleft">
 <div class="maindiv" style="min-height:100px; margin-bottom:20px;">
<span class="txx16 caps txtyellow lucidaBold">Supported Handsets:</span><br />
  <span class="txt16">{TXT_SUPHANDSETS}</span>
  </div>
  <div class="maindiv" style="margin-bottom:30px;">
  
  	 <!-- BEGIN: buy_btn -->
        <input name="quan" type="text" value="1" size="2"  style=" display:none;" />
        <a href="javascript:submitDoc('addtobasket');">
          <input type="submit" value="Order Unlock Now" class="ordernow2 radius3px" />
          </a> 
                    
    <!-- END: buy_btn -->
  </div>
  <div class="maindiv">
 <span class="txx16 caps txtyellow lucidaBold" > About: </span>
  
  <p>
  	{TXT_DESCRIPTION}

  </p>
	</div>
  </div> 
  
  <img alt="" src="skins/{VAL_SKIN}/styleImages/guarantee.jpg" class="right" style=" margin-top:44px;"/>         
</div>
</div>   

  <!-- END: prod_true --> 
    <!-- BEGIN: prod_false -->
    <div class="maincenter">
    <div class="mainorderbox">
    <p class="boxContent">{LANG_PRODUCT_EXPIRED}</p>
    </div>
    </div>
    <!-- END: prod_false -->    
      <input type="hidden" name="addimei" value="{PRODUCT_ID}" />  
</form>
 <!-- END: digital -->
      <!-- BEGIN: tangible -->

<div class="maindiv breadbg txt-grey">
  <div class=" maincenter">
   <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a>/
   <a href="index.php" class=" homeclr">Mobile Accessories  </a> 
   </div>
</div>
<!-- BEGIN: prod_true -->
<div class="maincenter">
    
 <div class="mainorderbox1" >
 <form action="{CURRENT_URL}" method="post" id="prod{PRODUCT_ID}" name="addtobasket" target="_self" onsubmit="javascript:return Validatequantity();">

 <div class="imagearea">

  <div class="pdpicbox">
  <table width="100%" style="height:100%">
  <tr>
  <td valign="top" align="center">
  <img src="{IMG_SRC}" alt="{TXT_PRODTITLE}" border="0" title="{TXT_PRODTITLE}" />
  </td>
  </tr>
  <tr>
  <td valign="top" align="center">
   <!-- BEGIN: image_gallery -->

		<div id="imgThumbSpace">
			<!-- BEGIN: img_repeat -->
			<a href="{VALUE_IMG_SRC}" rel="lightbox[imageset]"><img src="{VALUE_THUMB_SRC}" width="{VALUE_THUMB_WIDTH}" border="0" /></a>
			<!-- END: img_repeat -->
		</div>

	<!-- END: image_gallery -->
  </td>
  </tr>
  </table>
  </div>
  <!-- BEGIN: popup_gallery -->
	<div style="text-align: center;"><a href="javascript:;" onclick="openPopUp('index.php?_g=ex&amp;_a=prodImages&amp;productId={PRODUCT_ID}', 'images', 548, 455, 0); return false;" class="txtDefault">{LANG_MORE_IMAGES}</a></div>
	<!-- END: popup_gallery -->
	
  </div>
  <div class="titlearea">
  
  <div class="prodname">{TXT_PRODTITLE}</div>
  <div class="maindiv">
	<p class="txt14">{LANG_PRODCODE}<span class="txtblue">&nbsp; &nbsp; &nbsp; {TXT_PRODCODE}</span></p> 
  </div>
  <!-- BEGIN: prod_opts -->  
   <div class="maindiv" style="margin-bottom:10px;">
	
	 <div class=" optionheading txtyellow txt18 lucidaBold">{TXT_PROD_OPTIONS}</div>
	<table border="0" cellspacing="0" cellpadding="3"  class="optiontable">
		<!-- BEGIN: repeat_options -->
		<tr>
			<td><strong>{VAL_OPTS_NAME}</strong></td>
			<td>
                <div class="option">
				<select name="productOptions[{VAL_OPTION_ID}]"  >
					<!-- BEGIN: repeat_values -->
					<option value="{VAL_ASSIGN_ID}" selected="selected">
					{VAL_VALUE_NAME}
					<!-- BEGIN: repeat_price -->
					({VAL_OPT_SIGN}{VAL_OPT_PRICE})
					<!-- END: repeat_price -->
					</option>
					<!-- END: repeat_values -->
				</select>
                </div>
			</td>
		</tr>
		<!-- END: repeat_options -->
		<!-- BEGIN: text_opts -->
		<tr>
		  <td ><strong>{VAL_OPTS_NAME}</strong>
		  <!-- BEGIN: repeat_price -->
({VAL_OPT_SIGN}{VAL_OPT_PRICE})
<!-- END: repeat_price -->
		  </td>
		  <td>
			<!-- BEGIN: textbox -->
			<input type="text" name="productOptions[{VAL_OPTION_ID}]" class="textbox3"  />
			<!-- END: textbox -->
			<!-- BEGIN: textarea -->
			<textarea name="productOptions[{VAL_OPTION_ID}]" class="textbox3" cols="30" rows="4"  style="height:80px"></textarea>
			<!-- END: textarea -->
		  </td>
		</tr>
		<!-- END: text_opts -->
	</table>
	</div>
    <!-- END: prod_opts -->
     <div class="minqty maindiv lucidaBold" >Minnimum Quantity {TXT_MIN_QUAN} items</div>
    <div class="box4 radius3px">
    <div class="qunttext">
    <span>{LANG_QUAN}</span><br />
    <input name="mquan" id="mquan" type="hidden" value="{TXT_MIN_QUAN}" />
    <input name="quan" id="quan" type="text" value="{TXT_MIN_QUAN}" class="radius3px txtblue" />
    </div>
  <div class="pricediv"><span>Price:</span> <br /><p>{TXT_SALE_PRICE_VIEW}<br />{TXT_PRICE_VIEW}</p></div>
 
  <!-- BEGIN: buy_btn -->
		<div class="cartbtnp">
    	<input type="submit" value="Add to Cart" class="btn radius3px" />      
    	 </div>
<div class="qerror" id="qerror"></div>
	<!-- END: buy_btn -->
    </div>
  <!-- BEGIN: bulk_discount -->
  <div class="bulkbox radius3px">
  <h4 class="bulkhead txt18">Bulk Discount Available</h4>
  <div class="bulkimage">
  <table width="100%" style="height:100%">
  <tr>
  <td valign="middle" align="center">
  <img alt="" src="skins/{VAL_SKIN}/styleImages/bulkimage.png" />
  </td>
  </tr>
  </table>
  </div>
  <div class="bulkcontent">
   <!-- BEGIN: repeat -->
  <div class="bquan"> {DATA.quantity} units for <span class="bprice">{TXT_BULK_PRICE}</span>&nbsp;/&nbsp;item</div>
 <!-- END: repeat -->
  </div>
  
  </div>
   <!-- END: bulk_discount -->
   
  <!-- <div class="ship"><span>Shipping Cost:</span>{TXT_SHIPPING}</div>
   <div class="ship"><span>Delivery Time:</span>{TXT_DEL_TIME}</div>
   <div class="ship"><span>Processing Time:</span>{TXT_PROCESS_TIME}</div>-->
  <!--  <div class="wishlink1"><a href="index.php?_a=viewWish&amp;productId={PRODUCT_ID}">Add to wishlist</a></div>-->
  			
	  
    <div class="maindiv">
    		<!-- BEGIN: reviews_true -->
	
                <!-- BEGIN: review_stars -->
                <img src="skins/{VAL_SKIN}/styleImages/icons/rating/{VAL_STAR}.gif" width="15" height="15" />
                <!-- END: review_stars -->
            <br />
            <a  class="txt-purple txt14">{LANG_BASED_ON_X_REVIEWS}</a>
            
            <!-- END: reviews_true -->
            <div class="left">
        <!-- AddThis Button BEGIN -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-524bfb1011bba71a"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-524bfb1011bba71a"></script>
<!-- AddThis Button END -->
     </div>
     </div>
<input type="hidden" name="add" value="{PRODUCT_ID}" />
 <input type="hidden" name="digital" value="0" />
 <input type="hidden" name="imei" value="0"  />
  </div>
 </form>
 
 
                    
  </div>
  <div class="tabsdiv">
   <div class="tabs">
        <ul class="tabNavigation">
            <li><a class="" href="#first">Products Detail</a></li>
            <li><a class="" href="#second">Shipping & Returns</a></li>
           	<li><a class="" href="#third">Reviews</a></li>
        </ul>
        <div  id="first">
     {TXT_DESCRIPTION}
        </div>
        <div  id="second">
        <!-- BEGIN: site_docs -->
          <!-- BEGIN: repeat -->
        {DATA.doc_content}
         <!-- END: repeat --> 
        <!-- END: site_docs --> 
        </div>
        <div id="third">
  


        <!-- BEGIN: review -->
        
       <strong class="txtContentTitle" id="read_review">{LANG_REVIEWS_AND_COMMENTS}</strong>
       <div id="readreviw">
         <!-- BEGIN: reviews_true -->
 
  
 <div class="RatingTop">
  
  <div style="padding-bottom: 3px; border-bottom: 2px solid white;">
  
  <span style="float: right;">
  
  <!-- BEGIN: review_stars -->
  <img src="skins/{VAL_SKIN}/styleImages/icons/rating/{VAL_REVIEW_STAR}.gif" width="15" height="15" /> 
  <!-- END: review_stars --></span>{LANG_TYPE} <strong style="text-transform:uppercase;">{VAL_REVIEW_TITLE}</strong>
  
  </div>
 </div>
 <div class="RatingMain">
     &quot;{VAL_REVIEW}&quot;
 </div>
 <div class="RatingBottom">
  <span style="float: right;">{VAL_REVIEW_DATE}</span>{LANG_BY} {VAL_REVIEW_NAME} </div>
 <br />
 
 <!-- END: reviews_true -->
 </div>
 <div style="text-align:right">{VAL_REVIEW_PAGINATION}</div>
 
  <!-- BEGIN: reviews_false -->
  <p id="false">{LANG_NO_REVIEWS_MADE}</p>
  <!-- END: reviews_false -->
         <div style="text-align:right">{VAL_REVIEW_PAGINATION}</div>
   <div class="maindiv">
   
   		<a href="#" id="wreview" onclick="$('#write_review').slideToggle(); $('#wreview').slideToggle();$('#readreviw').slideToggle();$('#false').slideToggle();$('#read_review').slideToggle(); return false;" class="submitlogin button radius3px"  style="margin:10px"> Write a review </a>
   </div>
<form action="index.php?_a=viewProd&amp;review=write&amp;productId={PRODUCT_ID}#write_review" method="post" id="write_review"  style="display:none">
 <strong class="txtContentTitle">{LANG_SUBMIT_REVIEW}</strong>
 <!-- BEGIN: error -->
 <p class="txtError">{VAL_ERROR}</p>
 <!-- END: error -->
 
 <!-- BEGIN: success -->
 <p>{VAL_SUCCESS}</p>
 <!-- END: success -->

 <!-- BEGIN: form -->
 <p>{LANG_SUBMIT_REVIEW_COMPLETE}</p>
 <div style="width: 408px; padding: 10px;">
  <div   class="maindiv">
  <label class="txt14  txt-grey maindiv">{LANG_REVIEW_TYPE}</label>
  <div class="txtboxmain">
             <span class="txtboxmain-left"></span>
              <select name="review[type]"  class="refered">
  <option value="0" onclick="findObj('rating_p').style.display = '';" {VAL_REV_TYPE_R_SELECTED}>{LANG_REVIEW}</option>
  <option value="1" onclick="findObj('rating_p').style.display = 'none';" {VAL_REV_TYPE_C_SELECTED}>{LANG_COMMENT}</option>
  </select> 
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
 </div>
  
   <p  id="rating_p"  class="radius3px"> 
  <label  class="txt14 txt-grey left">{LANG_RATING}</label>
  <img src="images/general/px.gif" name="star0" width="15" height="15" id="star0" onmouseover="stars(0,'{VAL_ROOT_REL}skins/{VAL_SKIN}/styleImages/icons/rating/');" style="cursor: pointer; cursor: hand;" alt=""/>
    <!-- BEGIN: review_stars -->
  <img src="skins/{VAL_SKIN}/styleImages/icons/rating/{VAL_STAR}.gif" name="star{VAL_STAR_I}" width="15" height="15" id="star{VAL_STAR_I}" onmouseover="stars({VAL_STAR_I},'{VAL_ROOT_REL}skins/{VAL_SKIN}/styleImages/icons/rating/');" style="cursor: pointer; cursor: hand;" alt="" /> 
  <!-- END: review_stars -->
   
   </p>
  <!-- BEGIN: spambot -->
  <div class="maindiv">
  <p style="text-align:right;">
 
  <strong style="float: left;">{LANG_SPAMBOT}</strong>
  {IMG_SPAMBOT}<br />

 <input name="review[spambot]" type="text" class="textbox" style="width: 118px;" maxlength="5" />
  </p>
  </div>
  <!-- END: spambot -->
  
  
  <!-- BEGIN: recaptcha -->
  <p style="text-align:right;">
 
  <strong style="float: left;">{LANG_SPAMBOT}</strong><br />
  {RECAPTCHA}
  </p>
  <!-- END: recaptcha -->
  
  
  <div class="maindiv">
      <label class="txt14 txt-grey maindiv">{LANG_NAME}</label>
      <div class="txtboxmain">
      <span class="txtboxmain-left"></span>
      <input name="review[name]" type="text"  value="{VAL_REV_NAME}" onclick="this.value = ''" />
      <span class="txtboxmain-right"><span class="mandatory"></span></span>
      </div>
  </div>
  <div class="maindiv">
  <label class="txt14 txt-grey maindiv">{LANG_EMAIL} {LANG_NOT_DISPLAYED}</label>
  	<div class="txtboxmain">
    <span class="txtboxmain-left"></span>
  	<input name="review[email]" type="text"   value="{VAL_REV_EMAIL}" />
    <span class="txtboxmain-right"><span class="mandatory"></span></span>
    </div>
  </div>
   <div class="maindiv">
  <label class="txt14 txt-grey maindiv">{LANG_TITLE}</label>
  	<div class="txtboxmain">
    <span class="txtboxmain-left"></span>
  	<input name="review[title]" type="text" value="{VAL_REV_TITLE}" />
    <span class="txtboxmain-right"><span class="mandatory"></span></span>
    </div>
  </div>
 
 <div class="maindiv">
  <label class="txt14 txt-grey maindiv">{LANG_DETAILS}</label>
  	<div class="txtboxmain" style="height:103px">
    <span class="txtboxmain-left"></span>
  	 <textarea name="review[review]" rows="7"  class="textarea"  style="width:406px" cols="5">{VAL_REVIEW}</textarea>
    <span class="txtboxmain-right"><span class="mandatory"></span></span>
    </div>
  </div>

 
 <div class="maindiv" style="padding:10px 0">
 <input name="ESC" type="hidden" value="{VAL_ESC}" />
 <input type="hidden" name="review[rating]" id="rating_val" value="{VAL_RATING}" /> 
 <input name="submit" type="submit" value="{LANG_SUBMIT_REVIEW}" class="submitlogin button radius3px" />
 <a class="submitlogin button radius3px" href="#" onclick="$('#write_review').slideToggle(); $('#wreview').slideToggle();$('#readreviw').slideToggle();$('#false').slideToggle();$('#read_review').slideToggle(); return false;" style="padding: 8px 0px; vertical-align: top;" >Cancel</a>
 </div>
 
 </div>
   <!-- END: form -->
 </form>


 
 

 

 
 <!-- END: review -->
        </div>
        
    </div>

 </div>
    <!-- BEGIN: related_products -->
 <div class="related">
             
                 	<h1>Related Products</h1>
                    <!-- BEGIN: repeat_prods -->
              <div class="rprodbox">
                    
                        <div class="left" style="width:100%; height:150px;">
                        	<table width="100%" style="height:100%">
                        	<tr>
                            	<td valign="middle" align="center">
                                	<a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}"><img src="{VAL_IMG_SRC}" alt="{VAL_PRODUCT_NAME}" border="0" title="{VAL_PRODUCT_NAME}" /></a>
                                </td>
                            </tr>
                        </table>
                        </div>
                        <a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}" class="txt11">{VAL_PRODUCT_NAME}</a>
                        <!--<div class="proddesc">{VAL_PRODUCT_LOCATION}</div>-->
                        
                        <div class="rprice" {P}>{TXT_SALE_PRICE}  <span>{TXT_PRICE}</span></div>
                        
                    </div>
                    <!-- END: repeat_prods -->
             		
                    </div>
   <!-- END: related_products --> 
  </div>
  
    <!-- END: prod_true -->
<!-- BEGIN: prod_false -->
	<div class="maindiv imei">
  <div class="maincenter">
    <h2 class="imehead">{LANG_UNSURE}</h2>
    <div class="imeprice"> <a href="{LANG_IMEILINK}" class="ptext"> {LANG_ORDERIMEI}</a> </div>
  </div>
</div>
    <div class="maincenter">
    <p class="boxContent" style="margin-left:20px">Sorry this Product is currently not Available</p>
    </div>
<!-- END: prod_false --> 

  
 <!-- END: tangible -->
 
	<script type="text/javascript" charset="utf-8">
		$(function () {
			var tabContainers = $('div.tabs > div');
			tabContainers.hide().filter(':first').show();
			
			$('div.tabs ul.tabNavigation a').click(function () {
				tabContainers.hide();
				tabContainers.filter(this.hash).show();
				$('div.tabs ul.tabNavigation a').removeClass('selected');
				$(this).addClass('selected');
				return false;
			}).filter(':first').click();
		});
	</script>
<!-- END: view_prod -->