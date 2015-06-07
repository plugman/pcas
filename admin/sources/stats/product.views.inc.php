<?php 
/*

|	product.views.inc.php
|   ========================================
|	Product Views Stats	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<div class="headingBlackbg"><?php echo $lang['admin']['stats_product_pop'];?></div>
<?php

$rowsPerPage = 15;
$page = (isset($_GET['page'])) ? $_GET['page'] : 0;

$db	= new db();

$query		= "SELECT popularity, name FROM ".$glob['dbprefix']."ImeiUnlock_inventory ORDER BY popularity DESC";
$results	= $db->select($query, $rowsPerPage, $page);
$noResults	= $db->numrows($query);
$totalHits	= $db->select("SELECT sum(popularity) as totalHits FROM  ".$glob['dbprefix']."ImeiUnlock_inventory");

if ($results) {
  	$i=0;
	$chartData = array();
	
	$keyStr = "<table width='100%' border='1' cellspacing='1' cellpadding='3' class='mainTable mainTable4'>
	<tr>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_rank']."</td>
		<td class='tdTitle'>".$lang['admin']['stats_prod_name']."</td>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_views']."</td>
		<td class='tdTitle' style='text-align: center;'>".sprintf($lang['admin']['stats_percent_total'], $totalHits[0]['totalHits'])."</td>
	</tr>\n";
		
	for ($i=0; $i<$noResults; $i++) {
		if ($results[$i]['popularity'] > 0 && $totalHits[0]['totalHits']) {
			$data = true;
			$cellColor	= cellColor($i);
			$percentage = 100 * ($results[$i]['popularity'] / $totalHits[0]['totalHits']);
			$percentage = number_format($percentage,2);
			
			if ($percentage > 0 && $results[$i]['popularity'] >= 1) {
			
				$position		= (($page*$rowsPerPage)+1)+$i;
				$chartData[]	= array($position, $percentage);
				
				$keyStr .= "<tr>
					<td class='".$cellColor." copyText' style='text-align: center;'>".$position."</td>
					<td class='".$cellColor." copyText'>".$results[$i]['name']."</td>
					<td class='".$cellColor." copyText' style='text-align: center;'>".$results[$i]['popularity']."</td>
					<td class='".$cellColor." copyText' style='text-align: center;'>".$percentage."%</td>
				</tr>\n";
			}
		}
	}
	
	$keyStr .= "</table>";
	
	if($data) {
		$imageNo++;
		
		$filename = md5(CC_ROOT_DIR.$glob['license_key'].$imageNo).".png";
		$graph = new PHPlot;
		$graph->SetIsInline(true);
		$graph->SetPlotType('bars');
		$graph->SetNumXTicks(1);
		$graph->SetSkipRightTick(true);
		$graph->SetSkipLeftTick(true);
		$graph->SetPlotAreaWorld(null, 0, null, 100);
		$graph->PHPlot(550, 300, 'cache'.CC_DS.$filename);
		$graph->SetDataValues($chartData);
		$graph->SetXLabelAngle('0');
		$graph->SetTitle($lang['admin']['stats_product_pop']." (".(($page*$rowsPerPage)+1)." - ".(($page*$rowsPerPage) + count($chartData)).")");
		$graph->SetXTitle($lang['admin']['stats_product_name_key']);
		$graph->SetYTitle($lang['admin']['stats_product_views']);
		$graph->DrawGraph();
	
	
		echo "<img src='cache/".$filename."' />";
		?>
		<p class="copyText"><?php echo paginate(count($chartData), $rowsPerPage, $page, "page"); ?></p>
		<?php
		echo $keyStr;
	} else {
		echo "<p class='copyText'>".$lang['admin']['stats_sorry_no_data']."</p>";
	}
} else { 
	echo "<p class='copyText'>".$lang['admin']['stats_sorry_no_data']."</p>";
}
?>
