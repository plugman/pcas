<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Store Statistics	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$lang = getLang("admin".CC_DS."admin_stats.inc.php");

permission("statistics", 'read', true);

include_once($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
include("classes".CC_DS."gd".CC_DS."phplot.php");
?>
<p class="pageTitle"><?php echo $lang['admin']['stats_store_stats'];?></p>

<table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
  <tr>
    <td colspan="2" class="tdTitle"><?php echo $lang['admin']['stats_choose_view'];?></td>
  </tr>
  <tr>
    <td colspan="2">
    <div class="tabs">
	<ul class="tabNavigation">
	<li>
   
    <a href="<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=sales"> <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/tabimage1.jpg'; ?>" /></span><?php echo $lang['admin']['stats_sales'];?></a></li>
	<!--<li><a href="<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=searchTerms"  class="txtLink"><?php echo $lang['admin']['stats_search_terms'];?></a></li>-->
	<li>
   
    <a href="<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=prodViews"> <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/tabimage3.jpg'; ?>" /></span><?php echo $lang['admin']['stats_product_pop'];?></a></li>
	<li>
   
    <a href="<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=prodSales"> <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/tabimage4.jpg'; ?>" /></span><?php echo $lang['admin']['stats_product_pop_sales'];?></a></li>
	<li>
 
    <a href="<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=online"> <span class="imgbox"><img alt="" src="<?php echo $glob['storeURL'].'/admin/images/tabimage5.jpg'; ?>" /></span><?php echo $lang['admin']['stats_cust_online'];?></a></li>
	</ul>
    </div>
	</td>
  </tr>
</table>
<?php 

$imageNo = 0;

switch ($_GET['stats']) {
	case "sales";
		require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");
		include("sales.year.inc.php");
		include("sales.month.inc.php");
		include("sales.day.inc.php");
		break;
	case "searchTerms";
		include("search.inc.php");
		break;
	case "prodSales";
		include("product.sales.inc.php");
    	break;
	case "prodViews";
		include("product.views.inc.php");
	    break;
	case "online";
		include("online.inc.php");
		break;
}
?>