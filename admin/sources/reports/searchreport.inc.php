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

$lang = getLang("admin".CC_DS."admin_stats.inc.php");

permission("statistics","read",$halt=TRUE);


require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
include("classes".CC_DS."gd".CC_DS."phplot.php");
?>
<p class='pageTitle'>Search Keyword Report</p>
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
	$graph->SetDataValues($chartData);
	$graph->SetIsInline(true);
	$graph->PHPlot(550,300,"cache".CC_DS.$filename);
	$graph->SetPlotType("bars");
	$graph->SetXLabelAngle("0");
	$graph->skip_right_tick = TRUE;
	$graph->SetNumXTicks(1);
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