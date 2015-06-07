<!-- BEGIN: categories -->
<div class="headingbox"><span class="heading">{LANG_CATEGORY_TITLE}</span></div>

<div class="boxContentLeft" style="padding: 0px;">
	<ul  id="mainmenu-nav" >
		
		<li ><div class="headingBorder2"> <a href="index.php" class="txtDefault">{LANG_HOME}</a></div></li>

			<!-- BEGIN: a -->
            <script type="text/javascript">
       
		
			$(document).ready(function(){
					  $(".ab{DATA.cat_id}").click(function(){
						
						 $("ul.cd{DATA.cat_id}").toggle('active'); 
					  });
					 
					});
    		</script>
			<!-- BEGIN: ul_start -->
			<ul class="cd{DATA.cat_id}" style="display:none">
				<!-- END: ul_start -->
				<!-- BEGIN: li_start -->
                
				<li class="li-nav " id="ab{DATA.cat_id}"  >
				<!-- END: li_start -->
                <div class="headingBorder2 ab{DATA.cat_id} ">
					<a href="index.php?_a=viewCat&amp;catId={DATA.cat_id}">{DATA.cat_name} ({DATA.noProducts})</a>
                    
                 </div>
				<!-- BEGIN: li_end -->
				</li>
				<!-- END: li_end -->
				<!-- BEGIN: ul_end -->
			</ul>
		</li>
		<!-- END: ul_end -->
		<!-- END: a -->
		
		<!-- BEGIN: gift_certificates -->
		<li class="li-nav"><div class="headingBorder2"> <a href="index.php?_a=giftCert" class="txtDefault">{LANG_GIFT_CERTS}</a></div></li>
		<!-- END: gift_certificates -->
		
		<!-- BEGIN: sale -->
		<li class="li-nav"><div class="headingBorder2"> <a href="index.php?_a=viewCat&amp;catId=saleItems" class="txtDefault">{LANG_SALE_ITEMS}</a></div></li>
		<!-- END: sale -->
	</ul>

</div>
<!-- END: categories -->