<?php

/*

+--------------------------------------------------------------------------

|	navigation.inc.php

|   ========================================

|	Admin Navigation links

+--------------------------------------------------------------------------

*/



if(!defined('CC_INI_SET')){ die("Access Denied"); }



$link401 = "href=\"javascript:alert('".$lang['admin_common']['nav_permission_error']."');\" class=\"txtNullLink\"";

?>

<div id="adminNavigation" >

  

	



  <div id="menuList" class="navMenu" >

   <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreLinks');" >

    <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b1.jpg'; ?>"   />

	<?php echo $lang['admin_common']['nav_navigation'];?>

     <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  />

    </span>

     <div class="navStoreLinks">

	<ul>

		<li>

        <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i1.jpg'; ?>"  />

        <a href="<?php echo $GLOBALS['rootRel'].$glob['adminFile']; ?>" target="_self" class="txtLink"><?php echo $lang['admin_common']['nav_admin_home'];?></a></li>

		<li>

        <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i2.jpg'; ?>"  />

        <a href="<?php echo $GLOBALS['rootRel']; ?>index.php" target="_blank" class="txtLink"><?php echo $lang['admin_common']['nav_store_home'];?></a></li>

	</ul>

	</div></div>

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreConfig');">

	<img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b2.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_store_config'];?>

    <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  />

    </span>

    <div class="navStoreConfig">

	<ul>

		<li>

        <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i3.jpg'; ?>"  />

        <a <?php if(permission("settings","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_gen_settings'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("settings","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/tax" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_taxes'];?></a></li>

		

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i27.jpg'; ?>" /><a <?php if(permission("settings","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/currency" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_currencies'];?></a></li>		
<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("settings","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/emailContent" class="txtLink"<?php } else { echo $link401; } ?>>Email Contents</a></li>
	</ul>

	</div></div>

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreModules');" >

    <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n1.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_modules'];?>

     <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

      <div class="navStoreModules">

	<ul>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("shipping","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=shipping" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_shipping'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i28.jpg'; ?>"  /><a <?php if(permission("gateways","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=gateway" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_gateways'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i29.jpg'; ?>"  /><a <?php if(permission("gateways","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=altCheckout" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_alt_checkout'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("filemanager","edit")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=filemanager/language&loc=/en/includes/content/casecustomization.inc.php" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_edit_langs'];?>&nbsp;</a></li>
      <!--  <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i30.jpg'; ?>" height="12"  width="12" /><a <?php if(permission("settings","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=flashbanner/cat_flashbanner" class="txtLink"<?php } else { echo $link401; } ?>>Category Banner</a></li>-->

		<!--<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("settings","write")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=modules&amp;module=installer" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_module_installer']; ?></a></li>-->

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i30.jpg'; ?>" height="12"  width="12" /><a <?php if(permission("banner","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=flashbanner/flashbanner" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Flash Banners";?></a></li>
 <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i6.jpg'; ?>"  /><a <?php if(permission("testimonials","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=testimonials/comments" class="txtLink"<?php } else { echo $link401; } ?>> Testimonials </a></li>
	</ul>

	</div></div>

    <!--<div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreCatalog');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n2.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_catalog'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	 <div class="navStoreCatalog">

	<ul>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i19.jpg'; ?>"  /><a <?php if(permission("products","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_view_products'];?></a></li>

        <li><a <?php if(permission("products","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/tangible" class="txtLink"<?php } else { echo $link401; } ?>>View Tangible Products</a></li>

  

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i20.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/getfeed" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Update Products ";?></a></li>

                <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/bup.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/priceupdate" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Bulk Price Update";?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i21.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/options" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_product_options'];?></a></li>



		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i22.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=wholesalegroup/wholesale" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Wholesale Group";?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i23.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=faq/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Faq's";?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reviews","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reviews/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_prod_reviews'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i24.jpg'; ?>"  /><a <?php if(permission("offers","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_coupons'];?></a></li>

		<!--<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("products","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=settings/stock" class="txtLink"<?php } else { echo $link401; } ?>>Stock</a></li> 

		

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/giftCertificates" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_gift_certificates'];?></a></li>

		

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("categories","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=categories/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_view_categories'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i26.jpg'; ?>"  /><a <?php if(permission("categories","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=categories/index&amp;mode=new" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_add_categories'];?></a></li>

		

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("products","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/import" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_import_cat'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("products","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/export" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_export_cat'];?></a></li>

	</ul>

    </div></div>-->
     <!--<div class="toggle">
    <span class="navTitle" onclick="javascript: NavigationSlider('navdhru');" >
	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i17.jpg'; ?>"  />
	<?php echo "Dhru Api"; ?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>
     <div class="navdhru">
	<ul >
    <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i18.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=dhru/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Api Setting";?></a></li>
	</ul>
</div></div>-->
<div class="toggle">
    <span class="navTitle" onclick="javascript: NavigationSlider('navcase');" >
	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i17.jpg'; ?>"  />
	<?php echo "Case Customization"; ?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>
     <div class="navcase">
	<ul >
    <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i18.jpg'; ?>"  /><a <?php if(permission("case","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=case/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Manage Devices";?></a></li>
     <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i18.jpg'; ?>"  /><a <?php if(permission("stamp","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=case/stamp" class="txtLink"<?php } else { echo $link401; } ?>><?php echo "Manage Stamp";?></a></li>
     <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i24.jpg'; ?>"  /><a <?php if(permission("offers","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=products/coupons" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_coupons'];?></a></li>
	</ul>
</div></div>
    <!--<div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreRepair');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n2.jpg'; ?>"  />

	<?php echo "Mobile Repair";?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	 <div class="navStoreRepair">

	<ul>

		

          <li><a <?php if(permission("products","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/repair" class="txtLink"<?php } else { echo $link401; } ?>>View Repair Problems</a></li>		

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("categories","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_view_categories'];?></a></li><li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("categories","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/pickup" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'Pickup Timing';?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("rorders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/salesrep" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'sales Representative';?></a></li>
        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("rorders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/repairedby" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'Repaired By';?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("rorders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/referer" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'View Referer';?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("rsetting","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/setting" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'Repair Setting';?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("rorders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/orders" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'Repair Orders';?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i25.jpg'; ?>"  /><a <?php if(permission("emais","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=repair/emailContent" class="txtLink"<?php } else { echo $link401; } ?>><?php echo 'Email Contents';?></a></li>

	</ul>

    </div>

    </div>-->

     <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreCustomers');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n3.jpg'; ?>"  />

	<!--<?php echo $lang['admin_common']['nav_customers'];?>-->

    Customers & Orders

     <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	 <div class="navStoreCustomers">

	<ul>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i8.jpg'; ?>"  /><a <?php if(permission("customers","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=customers/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_view_customers'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i5.jpg'; ?>"  /><a <?php if(permission("customers","write")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=customers/email" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_email_customers'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i6.jpg'; ?>"  /><a <?php if(permission("orders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=orders/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_orders'];?></a></li>

        

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i5.jpg'; ?>"  /><a <?php if(permission("orders","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=orders/transLogs" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_transaction_logs'];?></a></li>

        

       

	</ul>

	</div></div>

	<!--<span class="navTitle" onclick="javascript: NavigationSlider('navStoreFilemanager');">

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n4.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_file_manager'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	<ul  class="navItem unchecked" id="navStoreFilemanager">

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("filemanager","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=filemanager/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_manage_images']?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("filemanager","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=filemanager/index"  onclick="openPopUp('<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/includes/rte/editor/filemanager/browser/default/browser.html?Type=uploads&Connector=<?php echo urlencode($GLOBALS['rootRel'].$glob['adminFolder']); ?>%2Fincludes%2Frte%2Feditor%2Ffilemanager%2Fconnectors%2Fphp%2Fconnector.php','filemanager',700,600)" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_upload_images'];?></a></li>		

	</ul>-->

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreStats');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n5.jpg'; ?>"  /> View

	<?php echo $lang['admin_common']['nav_statistics'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	 <div class="navStoreStats">

	<ul>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i9.jpg'; ?>"  /><a <?php if(permission("statistics","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=stats/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_view_stats'];?></a></li>

	</ul>

	</div></div>

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreDocuments');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n6.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_documents'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	<div class="navStoreDocuments">

	<ul>

		<!--<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i10.jpg'; ?>"  /><a <?php if(permission("documents","edit")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=docs/home" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_homepage'];?></a></li>-->

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i11.jpg'; ?>"  /><a <?php if(permission("documents","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=docs/siteDocs" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_site_docs'];?></a></li>

	</ul>

	</div></div>

	<!--<span class="navTitle" onclick="javascript: NavigationSlider('navStoreMisc');">

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n7.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_misc'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	<ul class="navItem unchecked" id="navStoreMisc">

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a href="<?php echo $glob['adminFile']; ?>?_g=misc/serverInfo" class="txtLink"><?php echo $lang['admin_common']['nav_server_info'];?></a></li>

	</ul>-->

	<div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreUsers');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/n8.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_admin_users'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	 <div class="navStoreUsers">

	<ul>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i12.jpg'; ?>"  /><a <?php if(permission("administrators","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=adminusers/administrators" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_administrators'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i13.jpg'; ?>"  /><a href="<?php echo $glob['adminFile']; ?>?_g=adminusers/sessions" class="txtLink"><?php echo $lang['admin_common']['nav_admin_sessions'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i14.jpg'; ?>"  /><a href="<?php echo $glob['adminFile']; ?>?_g=adminusers/logs" class="txtLink"><?php echo $lang['admin_common']['nav_admin_logs'];?></a></li>

	</ul>

	</div></div>

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navStoreMaintenance');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i15.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_maintenance'];?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	<div class="navStoreMaintenance">

	<ul>

		<!--<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("maintenance","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=maintenance/database" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_database'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("maintenance","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=maintenance/backup" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_backup'];?></a></li>

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("maintenance","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=maintenance/thumbnails" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_thumbnails'];?></a></li>-->

		<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i16.jpg'; ?>"  /><a <?php if(permission("maintenance","read")==true){ ?>href="<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_rebuild'];?></a></li>

	</ul>

    </div></div>

    <div class="toggle">

	<span class="navTitle" onclick="NavigationSlider('navReports');" >

	 <img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i17.jpg'; ?>"  />

	<?php echo $lang['admin_common']['nav_reports']; ?> <img alt="" class="blackarrow" src="<?php echo $glob['storeURL'].'/admin/images/blackarrow.jpg'; ?>"  /></span>

	<div class="navReports">

	<ul>

    <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/i18.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/creditcard_topup" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['Credit'];?></a></li>

		<!--<li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/index" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_low_stock'];?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/searchreport" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_search_keywords'];?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/productreviewsratingreport" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_prod_reviews_rating'];?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/registered_users" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_registered_users'];?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/transLogs" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_transactions_history'];?></a></li>

        <li><img alt="" class="bullet" src="<?php echo $glob['storeURL'].'/admin/images/b3.jpg'; ?>"  /><a <?php if(permission("reports","read")==TRUE){ ?>href="<?php echo $glob['adminFile']; ?>?_g=reports/profitable_customers" class="txtLink"<?php } else { echo $link401; } ?>><?php echo $lang['admin_common']['nav_profit_customer'];?></a></li>-->

	</ul>

    </div></div>

  </div>

</div>