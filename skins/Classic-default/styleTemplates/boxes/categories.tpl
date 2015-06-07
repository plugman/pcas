<!-- BEGIN: categories -->
<div class="boxTitleLeft">{LANG_CATEGORY_TITLE}</div>
<div class="boxContentLeft" style="padding: 0px;">
	<ul id="mainmenu-nav">
		
		<li class="li-nav"> <a href="index.php" class="txtDefault">{LANG_HOME}</a></li>

			<!-- BEGIN: a -->
			<!-- BEGIN: ul_start -->
			<ul class="ul-nav">
				<!-- END: ul_start -->
				<!-- BEGIN: li_start -->
				<li class="li-nav">
				<!-- END: li_start -->
					<a href="index.php?_a=viewCat&amp;catId={DATA.cat_id}">{DATA.cat_name} ({DATA.noProducts})</a>
				<!-- BEGIN: li_end -->
				</li>
				<!-- END: li_end -->
				<!-- BEGIN: ul_end -->
			</ul>
		</li>
		<!-- END: ul_end -->
		<!-- END: a -->
		
		<!-- BEGIN: gift_certificates -->
		<li class="li-nav"> <a href="index.php?_a=giftCert" class="txtDefault">{LANG_GIFT_CERTS}</a></li>
		<!-- END: gift_certificates -->
		
		<!-- BEGIN: sale -->
		<li class="li-nav"> <a href="index.php?_a=viewCat&amp;catId=saleItems" class="txtDefault">{LANG_SALE_ITEMS}</a></li>
		<!-- END: sale -->
	</ul>
	<br clear="all" />
</div>
<!-- END: categories -->