<!-- BEGIN: view_prod -->

	<!-- BEGIN: prod_true -->
	<div class="boxContent">
	
	<strong>{LANG_DIR_LOC}</strong> <a href="index.php"><img src="skins/{VAL_SKIN}/styleImages/icons/home.gif" alt="{LANG_HOME}" border="0" title="{LANG_HOME}" /></a> {CURRENT_DIR}
	
	<!-- BEGIN: opts_notice -->
	<p class="txtError">{LANG_OPTS_NOTICE}</p>
	<!-- END: opts_notice -->
	
	<form action="{CURRENT_URL}" method="post" id="prod{PRODUCT_ID}" name="addtobasket" target="_self">
	<p class="txtContentTitle"><strong>{TXT_PRODTITLE}</strong> </p>
	
	<div style="text-align: center;"><img src="{IMG_SRC}" alt="{TXT_PRODTITLE}" border="0" title="{TXT_PRODTITLE}" /></div>
	<!-- BEGIN: popup_gallery -->
	<div style="text-align: center;"><a href="javascript:;" onclick="openPopUp('index.php?_g=ex&amp;_a=prodImages&amp;productId={PRODUCT_ID}', 'images', 548, 455, 0); return false;" class="txtDefault">{LANG_MORE_IMAGES}</a></div>
	<!-- END: popup_gallery -->
	<!-- BEGIN: image_gallery -->
	<p>{IMAGE_GALLERY}</p>
	
		<div id="imgThumbSpace">
			<!-- BEGIN: img_repeat -->
			<a href="{VALUE_IMG_SRC}" rel="lightbox[imageset]"><img src="{VALUE_THUMB_SRC}" width="{VALUE_THUMB_WIDTH}" border="0" /></a>
			<!-- END: img_repeat -->
		</div>

	<!-- END: image_gallery -->
	<div>
		<strong>{LANG_PRODINFO}</strong>	
		<br />
		
		{TXT_DESCRIPTION}
	</div>
	<p>
	<!-- BEGIN: reviews_true -->
	
		<!-- BEGIN: review_stars -->
		<img src="skins/{VAL_SKIN}/styleImages/icons/rating/{VAL_STAR}.gif" width="15" height="15" />
		<!-- END: review_stars -->
	
	<a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}&amp;review=read#read_review" target="_self" class="txtDefault">{LANG_BASED_ON_X_REVIEWS}</a>
	
	<!-- END: reviews_true -->
	
	<!-- BEGIN: reviews_false -->
	<a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}&amp;review=write#write_review" target="_self" class="txtDefault">{LANG_FIRST_TO_REVIEW}</a>
	<!-- END: reviews_false -->
	</p>
	<p>
		<strong>{LANG_PRICE}</strong> {TXT_PRICE_VIEW} 
		<span class="txtSale">{TXT_SALE_PRICE_VIEW}</span>
	</p>      
	
	<ul>
		<li class="bulletLrg"><a href="index.php?_a=tellafriend&amp;productId={PRODUCT_ID}" target="_self" class="txtDefault">{LANG_TELLFRIEND}</a></li>
		<!-- BEGIN: read_reviews -->
		<li class="bulletLrg"><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}&amp;review=read#read_review" target="_self" class="txtDefault">{LANG_READ_REVIEWS}</a></li>
		<!-- END: read_reviews -->
		<li class="bulletLrg"><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}&amp;review=write#write_review" target="_self" class="txtDefault">{LANG_WRITE_REVIEWS}</a></li>
	</ul>
	
	<!-- BEGIN: prod_opts -->
	<br />
	<strong>{TXT_PROD_OPTIONS}</strong>
	<table border="0" cellspacing="0" cellpadding="3">
		<!-- BEGIN: repeat_options -->
		<tr>
			<td><strong>{VAL_OPTS_NAME}</strong></td>
			<td>
				<select name="productOptions[{VAL_OPTION_ID}]">
					<!-- BEGIN: repeat_values -->
					<option value="{VAL_ASSIGN_ID}">
					{VAL_VALUE_NAME}
					<!-- BEGIN: repeat_price -->
					({VAL_OPT_SIGN}{VAL_OPT_PRICE})
					<!-- END: repeat_price -->
					</option>
					<!-- END: repeat_values -->
				</select>
			</td>
		</tr>
		<!-- END: repeat_options -->
		<!-- BEGIN: text_opts -->
		<tr>
		  <td valign="top"><strong>{VAL_OPTS_NAME}</strong>
		  <!-- BEGIN: repeat_price -->
({VAL_OPT_SIGN}{VAL_OPT_PRICE})
<!-- END: repeat_price -->
		  </td>
		  <td>
			<!-- BEGIN: textbox -->
			<input type="text" name="productOptions[{VAL_OPTION_ID}]" class="textbox"  />
			<!-- END: textbox -->
			<!-- BEGIN: textarea -->
			<textarea name="productOptions[{VAL_OPTION_ID}]" class="textbox" cols="30" rows="4"></textarea>
			<!-- END: textarea -->
		  </td>
		</tr>
		<!-- END: text_opts -->
	</table>
	<!-- END: prod_opts -->
	<br />

	<strong>{LANG_PRODCODE}</strong> {TXT_PRODCODE} 

	<div>
	{TXT_INSTOCK}<span class="txtOutOfStock">{TXT_OUTOFSTOCK}</span>
	
    <!-- BEGIN: buy_btn -->
	<div style="position: relative; text-align: right;">{LANG_QUAN} 
	<input name="quan" type="text" value="1" size="2" class="textbox" style="text-align:center;" />
	<a href="javascript:submitDoc('addtobasket');" class="txtButton">{BTN_ADDBASKET}</a>
	</div>
	<!-- END: buy_btn -->
	</div>  
<input type="hidden" name="add" value="{PRODUCT_ID}" />
</form>
</div>

<!-- BEGIN: write_review -->
<form action="index.php?_a=viewProd&amp;review=write&amp;productId={PRODUCT_ID}#write_review" method="post" id="write_review" class="boxContent">
	<strong class="txtContentTitle">{LANG_SUBMIT_REVIEW}</strong>
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	
	<!-- BEGIN: success -->
	<p>{VAL_SUCCESS}</p>
	<!-- END: success -->

	<!-- BEGIN: form -->
	<p>{LANG_SUBMIT_REVIEW_COMPLETE}</p>
	<div style="padding-right: 60px;">
		<p style="text-align:right;"><strong style="float: left;">{LANG_REVIEW_TYPE}</strong> 
		<select name="review[type]" style="width: 152px;" class="textbox">
		<option value="0" onclick="findObj('rating_p').style.display = '';" {VAL_REV_TYPE_R_SELECTED}>{LANG_REVIEW}</option>
		<option value="1" onclick="findObj('rating_p').style.display = 'none';" {VAL_REV_TYPE_C_SELECTED}>{LANG_COMMENT}</option>
		</select> 
		</p>
	  <p style="text-align:right;" id="rating_p"> 
		<strong style="float: left;">{LANG_RATING}</strong>
		<img src="images/general/px.gif" name="star0" width="15" height="15" id="star0" onmouseover="stars(0,'{VAL_ROOT_REL}skins/{VAL_SKIN}/styleImages/icons/rating/');" style="cursor: pointer; cursor: hand;" />
	  	<!-- BEGIN: review_stars -->
		<img src="skins/{VAL_SKIN}/styleImages/icons/rating/{VAL_STAR}.gif" name="star{VAL_STAR_I}" width="15" height="15" id="star{VAL_STAR_I}" onmouseover="stars({VAL_STAR_I},'{VAL_ROOT_REL}skins/{VAL_SKIN}/styleImages/icons/rating/');" style="cursor: pointer; cursor: hand;" />	
		<!-- END: review_stars -->
	  
	  </p>
		<!-- BEGIN: spambot -->
		<p style="text-align:right;">
	
		<strong style="float: left;">{LANG_SPAMBOT}</strong>
		{IMG_SPAMBOT}<br />

	<input name="review[spambot]" type="text" class="textbox" style="width: 118px;" maxlength="5" />
		</p>
		<!-- END: spambot -->
		
		
		<!-- BEGIN: recaptcha -->
		<p style="text-align:right;">
	
		<strong style="float: left;">{LANG_SPAMBOT}</strong><br />
		{RECAPTCHA}
		</p>
		<!-- END: recaptcha -->
		
		
		<p style="text-align:right;">
		<strong style="float: left;">{LANG_NAME}</strong>
		<input name="review[name]" type="text" style="width: 150px;" class="textbox" value="{VAL_REV_NAME}" onclick="this.value = ''" />
		</p>
		<p style="text-align:right;">
		<span style="float: left;"><strong>{LANG_EMAIL}</strong> {LANG_NOT_DISPLAYED}</span>
		<input name="review[email]" type="text" style="width: 150px;" class="textbox" value="{VAL_REV_EMAIL}" />
		</p>
		
		<p style="text-align:right;">
		<strong style="float: left;">{LANG_TITLE}</strong>
		<input name="review[title]" type="text" style="width: 150px;" class="textbox" value="{VAL_REV_TITLE}" />
		</p>
		
		<p>
		<strong style="float: left;">{LANG_DETAILS}
	
	</strong><br />
	
		<textarea name="review[review]" style="width:98%; margin-left: 3px; margin-top: 3px;" rows="7" class="textbox">{VAL_REVIEW}</textarea>
		</p>
	<p style="text-align:right;">
	<input name="ESC" type="hidden" value="{VAL_ESC}" />
	<input type="hidden" name="review[rating]" id="rating_val" value="{VAL_RATING}" /> 
	<input name="submit" type="submit" value="{LANG_SUBMIT_REVIEW}" class="submit" /></p>
	<br clear="all" />
	</div>
			<!-- END: form -->
	</form>
	<!-- END: write_review -->
	
	<!-- BEGIN: read_review -->

	<strong class="txtContentTitle" id="read_review">{LANG_REVIEWS_AND_COMMENTS}</strong>
	
	<div style="text-align:right">{VAL_REVIEW_PAGINATION}</div>
	
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
		<span style="float: right;">{VAL_REVIEW_DATE}</span>{LANG_BY} {VAL_REVIEW_NAME}	</div>
	<br />
	
	<!-- END: reviews_true -->
	<div style="text-align:right">{VAL_REVIEW_PAGINATION}</div>
	
		<!-- BEGIN: reviews_false -->
		<p>{LANG_NO_REVIEWS_MADE}</p>
		<!-- END: reviews_false -->
	
	<!-- END: read_review -->
	
	<!-- BEGIN: related_products -->
	
	<div class="boxContent">
	<span class="txtContentTitle">{LANG_RELATED_PRODUCTS}</span>
		<div style="text-align:justify; margin-top: 10px;">
		<!-- BEGIN: repeat_prods -->
			<div class="latestProds">
				<a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}"><img src="{VAL_IMG_SRC}" alt="{VAL_PRODUCT_NAME}" border="0" title="{VAL_PRODUCT_NAME}" /></a>
				<br />
				<a href="index.php?_a=viewProd&amp;productId={VAL_PRODUCT_ID}" class="txtDefault">{VAL_PRODUCT_NAME}</a>
				<br /> 
				{TXT_PRICE} <span class="txtSale">{TXT_SALE_PRICE}</span>
			</div>
		<!-- END: repeat_prods -->
		<br clear="all" />
		</div>
		<br clear="all" />
		
		
	</div>
		
	<!-- END: related_products -->
	
<!-- END: prod_true -->
<!-- BEGIN: prod_false -->
<p class="boxContent">{LANG_PRODUCT_EXPIRED}</p>
<!-- END: prod_false -->
	
<!-- END: view_prod -->

