<!-- BEGIN: view_cat -->
<div class="boxContent">
<strong>{LANG_DIR_LOC}</strong> <a href="index.php"><img src="skins/{VAL_SKIN}/styleImages/icons/home.gif" alt="{LANG_HOME}" border="0" title="{LANG_HOME}" /></a> {CURRENT_LOC}
<p class="txtContentTitle">{TXT_CAT_TITLE}</p>

<!-- BEGIN: cat_desc -->
<p>{TXT_CAT_DESC}</p>
<!-- END: cat_desc -->

<!-- BEGIN: sub_cats -->
<div id="subCats">
	<!-- BEGIN: sub_cats_loop -->
	<div class="subCat">
		<a href="index.php?_a=viewCat&amp;catId={TXT_LINK_CATID}" class="txtDefault"><img src="{IMG_CATEGORY}" alt="{TXT_CATEGORY}" border="0" title="{TXT_CATEGORY}" /></a><br />
		<a href="index.php?_a=viewCat&amp;catId={TXT_LINK_CATID}" class="txtDefault">{TXT_CATEGORY}</a> ({NO_PRODUCTS})
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
<select id="sortMethod" class="textbox">
  <option value="{SORT_NAME}"{SORT_NAME_SELECTED}>{LANG_NAME}</option>
  <option value="{SORT_PRICE}"{SORT_PRICE_SELECTED}>{LANG_PRICE}</option>
</select>
<input type="button" class="txtButton" value="{LANG_SORT}" onclick="goUrl('sortMethod');" />
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
<!-- END: view_cat -->