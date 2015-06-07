<!-- BEGIN: shopping_cart -->
<div class="cartbox radius3px">
<!-- BEGIN: contents_true -->
<!-- END: contents_true -->
<!-- BEGIN: contents_false -->
<!-- END: contents_false -->

<!-- BEGIN: view_cart -->
<a href="index.php?_g=co&amp;_a={CART_STEP}" ><span class="cat"> </span>{VAL_CART_ITEMS} Item(s)</a>
<!-- END: view_cart -->

	</div>
<!-- END: shopping_cart -->




<div class="boxTitleRight">{LANG_SHOPPING_CART_TITLE}</div>
<div class="boxContentRight">
	<div class="txtCart">
		<!-- BEGIN: contents_true -->
		<span class="txtCartPrice">{PRODUCT_PRICE}</span><a href="index.php?_a=viewProd&amp;productId={PRODUCT_ID}" class="txtCartProduct">{VAL_NO_PRODUCT} x {VAL_PRODUCT_NAME}</a><br clear="all" />
		<!-- END: contents_true -->
		<!-- BEGIN: contents_false -->
		<span style="float: right; padding-right: 3px;"><img src="skins/{VAL_SKIN}/styleImages/icons/basket.gif" alt="" width="13" height="12" title="" /></span>{LANG_CART_EMPTY}
		<!-- END: contents_false -->
		<div class="cartTotal">
			<span class="txtCartPrice">{VAL_CART_ITEMS}</span>{LANG_ITEMS_IN_CART}<br />
			<span class="txtCartPrice"><strong>{VAL_CART_TOTAL}</strong></span><strong>{LANG_TOTAL_CART_PRICE}</strong>
		</div>
		
	</div>
	<!-- BEGIN: view_cart -->
	<div style="text-align: center; padding-top: 3px;"><a href="index.php?_g=co&amp;_a={CART_STEP}" class="txtviewCart" id="flashBasket">{LANG_VIEW_CART}</a></div>
	<!-- END: view_cart -->
</div>