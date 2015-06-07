<?php
/*
+--------------------------------------------------------------------------
|   Cub3Cart 4
|   ========================================
|	
|   
|   
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.d.e.v.e.l.l.i.o.n.com
|	
|   ========================================
|   Web: http://www.c.u.b.e.c.a.r.t.com
|   Email: info (at) c.u.b.e.c.a.r.t (dot) com
|	  License Type: C.u.b.e.C.a.r.t is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.c.u.b.e.c.a.r.t.com/site/faq/license.php
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Add/Edit/Delete Products	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }


$lang = getLang("admin".CC_DS."admin_products.inc.php");
require("classes".CC_DS."gd".CC_DS."gd.inc.php");
require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");

/////////////////////////////////////   Low Stock Report :: START  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($config['stock_warn_type'] == 1) 
{
	$query = "SELECT name, stock_level, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE useStockLevel = 1 AND stock_level <= stockWarn ORDER BY stock_level ASC"; 
} else {
	if (!isset($config['stock_warn_level'])) $config['stock_warn_level'] = 5;
	$query = "SELECT name, stock_level, productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE useStockLevel = 1 AND stock_level <= ".$config['stock_warn_level']." ORDER BY stock_level ASC"; 
}

$stockPerPage = 20;
$stock = $db->select($query, $stockPerPage, $_GET['po']);
$numrows = $db->numrows($query);
$pagination = paginate($numrows, $stockPerPage, $_GET['po'], "po");
/////////////////////////////////////   Low Stock Report :: END  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap='nowrap' class="pageTitle">Low Stock Report</td>
     <td align="right" valign="middle">
     <a href="<?php echo $glob['adminFile']; ?>?_g=reports/index&amp;mode=export_low_tax" class="txtLink">
     Export to CSV
     </a>
     </td>
  </tr>
</table>
<?
if ($stock == true) 
{
?>
  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
	<tr>
	  <td width="40%" align="left" valign="top" class="tdTitle">
	  Product
      </td>
      <td width="40%" align="left" valign="top" class="tdTitle">
	  In Stock
      </td>
	</tr>
	<?php
	for ($i=0; $i<count($stock); $i++) 
	{
	?>
        <tr>
          <td width="50%" align="left" valign="top" class="tdText">
			<?
                echo " <a href='".$glob['adminFile']."?_g=products/index&amp;edit=".$stock[$i]['productId']."' class='txtDash'>".$stock[$i]['name']."</a>";
            ?>
	  </td>
      <td width="50%" align="left" valign="top" class="tdText">
    	<? echo $stock[$i]['stock_level'];  ?>
      </td>
	</tr>
    <?
	}
	?>
    <tr>
        <td colspan="2" align="left" valign="top">
        <? echo $pagination; ?>
        </td>
    </tr>
  </table>
  <br />
<?php }
else
{
	echo "<p class='copyText'>Sorry, no data found.</p>";
}
?>