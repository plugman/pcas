<?php 
/*

|	search.inc.php
|   ========================================
|	Search Keyword Stats	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<p class='pageTitle'><?php echo $lang['admin']['stats_search_terms'];?></p>
<?php

$rowsPerPage = 15;

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 0;
}

// make sql query
$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_search ORDER BY hits DESC";
// query database

$results = $db->select($query, $rowsPerPage, $page);
$numrows = $db->numrows($query);
$totalHits = $db->select("SELECT sum(hits) as totalHits FROM  ".$glob['dbprefix']."ImeiUnlock_search");

if($results==TRUE){
  		
	$i=0;
	$chartData = array();
	$noResults = count($results);
	
	$keyStr = "<table width='100%' border='0' cellspacing='1' cellpadding='3' class='mainTable'>
	<tr>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_rank']."</td>
		<td class='tdTitle'>".$lang['admin']['stats_search_word']."</td>
		<td class='tdTitle' style='text-align: center;'>".$lang['admin']['stats_searches']."</td>
		<td class='tdTitle' style='text-align: center;'>".sprintf($lang['admin']['stats_percent_total'],$totalHits[0]['totalHits'])."</td>
	</tr>\n";
	
	for ($i=0; $i<$noResults; $i++){
		
		$cellColor = cellColor($i);	
		
		$percentage = 100*($results[$i]['hits'] / $totalHits[0]['totalHits']);
		$percentage = number_format($percentage,2);
		$position = (($page*$rowsPerPage)+1)+$i;
		$chartData[] = array($position,$percentage);
		
		$keyStr .= "<tr>
			<td class='".$cellColor." copyText' style='text-align: center;'>".$position."</td>
			<td class='".$cellColor." copyText'>".ucfirst(strtolower($results[$i]['searchstr']))."</td>
			<td class='".$cellColor." copyText' style='text-align: center;'>".$results[$i]['hits']."</td>
			<td class='".$cellColor." copyText' style='text-align: center;'>".$percentage."%</td>
		</tr>\n";

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
	$graph->SetTitle($lang['admin']['stats_search_terms']." (".(($page*$rowsPerPage)+1)." - ".(($page*$rowsPerPage) + $noResults).")");
	$graph->SetXTitle($lang['admin']['stats_search_term_key']);
	$graph->SetYTitle($lang['admin']['stats_percent']);
	$graph->DrawGraph();

	echo "<img src='cache/".$filename."' />";
	?>
	<p class="copyText"><?php echo paginate($numrows, $rowsPerPage, $page, "page"); ?></p>
	<?php
	echo $keyStr;
	
} else { 
	echo "<p class='copyText'>".$lang['admin']['stats_sorry_no_data']."</p>";
} 
?>