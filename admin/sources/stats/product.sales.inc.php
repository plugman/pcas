<?php 
/*

|	product.sales.inc.php
|   ========================================
|	Product Sales Stats	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<div class="headingBlackbg"><?php echo $lang['admin']['stats_product_pop_sales'];?></div>
<?php
$rowsPerPage = 15;

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 0;
}


$query = "SELECT sum(a.quantity) AS quan, a.productId, b.name FROM `".$glob['dbprefix']."ImeiUnlock_order_inv` a INNER JOIN `".$glob['dbprefix']."ImeiUnlock_inventory` b ON a.productId = b.productId GROUP BY productId DESC ORDER BY `quan` DESC";

$results = $db->select($query, $rowsPerPage, $page);
$noResults = $db->numrows($query);
$totalItemsSold = $db->select("SELECT SUM(quantity) as totalProducts FROM  `".$glob['dbprefix']."ImeiUnlock_order_inv`");

if($results==TRUE) {
  	$i=0;
	$chartData = array();
	
	$keyStr = "<table width='100%' border='1' cellspacing='1' cellpadding='3' class='mainTable mainTable4'>
	<tr>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_rank']."</td>
		<td class='tdTitle'>".$lang['admin']['stats_prod_name']."</td>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_quan_sold']."</td>
		<td class='tdTitle' style='text-align: center;'>".sprintf($lang['admin']['stats_percent_total'],$totalItemsSold[0]['totalProducts'])."</td>
	</tr>\n";
		
	for ($i=0; $i<$noResults; $i++){
			
		$cellColor = cellColor($i);	
			
		$percentage = 100 * ($results[$i]['quan'] / $totalItemsSold[0]['totalProducts']);
		$percentage = number_format($percentage, 2);
		if ($percentage >= 0 && $results[$i]['quan'] >= 1) {
			$position = (($page*$rowsPerPage)+1)+$i;
			$chartData[] = array($position,$percentage);
			
			$keyStr .= "<tr>
				<td class='".$cellColor." copyText' style='text-align: center;'>".$position."</td>
				<td class='".$cellColor." copyText'>".$results[$i]['name']."</td>
				<td class='".$cellColor." copyText' style='text-align: center;'>".$results[$i]['quan']."</td>
				<td class='".$cellColor." copyText' style='text-align: center;'>".$percentage."%</td>
			</tr>\n";
		}
 
	}
	
	$keyStr .= "</table>";
	
	$imageNo++;
	
	$filename = md5(CC_ROOT_DIR.$glob['license_key'].$imageNo).".png";
	
	$graph = new PHPlot;
	$graph->SetIsInline(true);
	$graph->SetPlotType('bars');
	$graph->SetNumXTicks(1);
	$graph->SetSkipRightTick(true);
	$graph->SetSkipLeftTick(true);
	$graph->SetPlotAreaWorld(null, 0, null, 100);
	$graph->PHPlot(550,300,"cache".CC_DS.$filename);
	$graph->SetDataValues($chartData);
	$graph->SetXLabelAngle('0');
	$graph->SetTitle($lang['admin']['stats_product_pop_sales']." (".(($page*$rowsPerPage)+1)." - ".(($page*$rowsPerPage) + count($chartData)).")");
	$graph->SetXTitle($lang['admin']['stats_product_name_key']);
	$graph->SetYTitle($lang['admin']['stats_product_sales']);
	$graph->DrawGraph();
	
	echo "<img src='cache/".$filename."' />";
	
	?>
	<p class="copyText"><?php echo paginate(count($chartData), $rowsPerPage, $page, "page"); ?></p>
	<?php
	echo $keyStr;
	
} else { 
	echo "<p class='copyText'>".$lang['admin']['stats_sorry_no_data']."</p>";
} 
?>