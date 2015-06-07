<?php 
/*

|	sales.year.inc.php
|   ========================================
|	Sales Stats by Year	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<div class="headingBlackbg"><?php echo $lang['admin']['stats_monthly_sales'];?></div>
<?php

$query = "SELECT min(`cart_order_id`) AS `lowest` FROM `".$glob['dbprefix']."ImeiUnlock_order_sum` WHERE `status` = 3;";
$firstOrder = $db->select($query);

$monthNow = date("m");
$yearNow  = date("y");
$dayNow = date("d");

if($firstOrder[0]['lowest']>0){
	$monthValLowest = substr($firstOrder[0]['lowest'],0,4);
	$firstYearLowest = substr($firstOrder[0]['lowest'],0,2);
	$firstMonthLowest = substr($firstOrder[0]['lowest'],2,2);
	$firstDayLowest = substr($firstOrder[0]['lowest'],4,2);
} else {
	$monthValLowest = $yearNow.$monthNow;
	$firstYearLowest = $yearNow;
	$firstMonthLowest =$monthNow;
	$firstDayLowest = $dayNow;
}

$monthsArray = array(

  "01" => $lang['admin']['stats_month_01'],
  "02" => $lang['admin']['stats_month_02'],
  "03" => $lang['admin']['stats_month_03'],
  "04" => $lang['admin']['stats_month_04'],
  "05" => $lang['admin']['stats_month_05'],
  "06" => $lang['admin']['stats_month_06'],
  "07" => $lang['admin']['stats_month_07'],
  "08" => $lang['admin']['stats_month_08'],
  "09" => $lang['admin']['stats_month_09'],
  "10" => $lang['admin']['stats_month_10'],
  "11" => $lang['admin']['stats_month_11'],
  "12" => $lang['admin']['stats_month_12']

);

function nextMonth($month){
	if (substr($month, 2, 2) == 12) {
		return sprintf("%02d", substr($month, 0, 2)+1)."01";
	} else {
		return sprintf("%02d", substr($month,0,2)).sprintf("%02d", substr($month,2,2)+1);
	}
}

function oneMonthLater(&$year, &$month) {
	if ($month >= 12) {
		$year++;
		$month = sprintf('%02d', 1);
	} else {
		$month++;
	}
}

$monthLast	= date('ym', time()-28927184);
$monthVal	= $monthValLowest;

if ($monthVal < $monthLast) {
?>
<p class='copyText'><?php echo $lang['admin']['stats_by_year']; ?>
<select name="yStart" onChange="jumpMenu('parent', this, 0);">
<?php
	while ($monthVal <= $monthLast) {
?>
  <option value="<?php echo $glob['adminFile'] ?>?_g=stats/index&amp;stats=sales&amp;yStart=<?php echo $monthVal; ?>&amp;mStart=<?php echo $_GET['mStart'];?>&amp;dStart=<?php echo $_GET['dStart'];?>" <?php if($_GET['yStart']==$monthVal) { echo "selected='selected'"; } elseif((!isset($_GET['yStart']) || empty($_GET['yStart'])) && $monthVal==$monthLast) { echo "selected='selected'"; } ?>>20<?php echo substr($monthVal, 0, 2); ?> - <?php echo $monthsArray[substr($monthVal, 2, 2)]; ?></option> 
<?php
		$monthVal = nextMonth($monthVal);
	}
?>
</select>
<input name="reset" value="<?php echo $lang['admin']['stats_reset']; ?>" onClick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=sales&amp;mStart=<?php echo $_GET['mStart'];?>&amp;dStart=<?php echo $_GET['dStart'];?>');return document.returnValue" type="button" class="submit" />
</p>
<?php
}

// make SQL query starting from this month
if(isset($_GET['yStart']) && !empty($_GET['yStart'])) {
	$oneYrOn = strftime("%y%m",mktime( 12 , 0 , 0, substr($_GET['yStart'],2,2), 5 , substr($_GET['yStart'],0,2), 0)+28927182);
	$oneYrPrev = $_GET['yStart'];
} else {
	$oneYrPrev = strftime("%y%m",mktime( 12 , 0 , 0, $monthNow, 5 , $yearNow, 0)-28927182);
	$oneYrOn = $yearNow.$monthNow;
}

$query = "SELECT prod_total, cart_order_id FROM `".$glob['dbprefix']."ImeiUnlock_order_sum` WHERE cart_order_id>'".$oneYrPrev."01-000000-0000' AND cart_order_id<'".$oneYrOn ."31-999999-9999' AND `status` = 3 ORDER BY cart_order_id ASC;";
$year = $db->select($query);

// set months
$monthKey = $oneYrPrev;
$month[$monthKey] = 0;

for($i=0;$i<11;$i++) {
	$monthKey = nextMonth($monthKey);
	$month[$monthKey] = 0;
}

if($year==TRUE) {
	for($i=0;$i<count($year);$i++){
		$monthInt = substr($year[$i]['cart_order_id'],0,4);
		$month[$monthInt] = $month[$monthInt] + $year[$i]['prod_total'];
	}
}

$chartData = array();

foreach($month as $key => $value) {
	$chartData[] = array($monthsArray[substr($key,2,2)]."'".substr($key,0,2),round($value));
}

$imageNo++;
	
$filename = md5(CC_ROOT_DIR.$glob['license_key'].$imageNo).".png";

$graph = new PHPlot;
$graph->SetIsInline(true);
$graph->SetPlotType('bars');
$graph->SetNumXTicks(1);
$graph->SetSkipRightTick(true);
$graph->SetSkipLeftTick(true);
$graph->SetPlotAreaWorld(null, 0, null, null);
$graph->PHPlot(550,300,"cache".CC_DS.$filename);
$graph->SetDataValues($chartData);
$graph->SetTitle(sprintf($lang['admin']['stats_monthly_sales_chart'],$monthsArray[substr($oneYrPrev,2,2)]."'".substr($oneYrPrev,0,2)." - ".$monthsArray[substr($oneYrOn,2,2)]."'".substr($oneYrOn,0,2)));
$graph->SetXTitle($lang['admin']['stats_month_of_yr_chart']);
$graph->SetYTitle(sprintf($lang['admin']['stats_sales_vol'],$config['defaultCurrency']));
$graph->DrawGraph();

unset($month,$chartData,$monthInt,$year);

echo "<img src='cache/".$filename."' />";
?>