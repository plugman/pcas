<?php 
/*

|	sales.month.inc.php
|   ========================================
|	Sales Stats by Month	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<div class="headingBlackbg"><?php echo $lang['admin']['stats_daily_sales'];?></div>
<?php echo $lang['admin']['stats_by_month'];?>
<div class="inputbox">
		<span class="bgleft"></span>
    	<select name="dStart" onchange="jumpMenu('parent',this,0)">
<?php

$monthVal = $monthValLowest;
$firstMonth = $firstMonthLowest;
$firstYear = $firstYearLowest;

while ($monthVal <= $yearNow.$monthNow){
?>
<option value="<?php echo $glob['adminFile'] ?>?_g=stats/index&amp;stats=sales&amp;mStart=<?php echo $monthVal ?>&amp;dStart=<?php echo $_GET['dStart'];?>&amp;yStart=<?php echo $_GET['yStart'];?>" <?php if($_GET['mStart']==$monthVal) { echo "selected='selected'"; } elseif((!isset($_GET['mStart']) || empty($_GET['mStart'])) && $monthVal==$yearNow.$monthNow) { echo "selected='selected'"; } ?>>20<?php echo $firstYear ?> - <?php echo $monthsArray[$firstMonth]; ?></option> 

<?php
	if($firstMonth==12){
		$firstMonth = sprintf("%02d",1);
		$firstYear = sprintf("%02d",$firstYear + 1);
	} else {
		$firstMonth = sprintf("%02d",$firstMonth + 1);
	}

	$monthVal = $firstYear.$firstMonth;
	
}
?>
</select>	
	   <span class="bgright"></span>
	   </div>

<input name="reset" value="<?php echo $lang['admin']['stats_reset']; ?>" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=sales&amp;yStart=<?php echo $_GET['yStart'] ?>&amp;dStart=<?php echo $_GET['dStart'];?>');return document.returnValue" type="button" class="submit" />
</p>
<?php

if(isset($_GET['mStart']) && !empty($_GET['mStart'])){
	$sqlMonth = $_GET['mStart'];	
} else {
	$sqlMonth = $yearNow.$monthNow;
}

$query = "SELECT prod_total, cart_order_id FROM `".$glob['dbprefix']."ImeiUnlock_order_sum` WHERE cart_order_id>'".$sqlMonth."01-000000-0000' AND cart_order_id<'".$sqlMonth."31-999999-9999' AND `status` = 3 ORDER BY cart_order_id ASC;";

$month = $db->select($query);

$daysInCurMonth = cal_days_in_month(CAL_GREGORIAN, substr($sqlMonth,2,2), "20".substr($sqlMonth,0,2));

// set all months incase some days didn't have sales
for($i=1;$i<=$daysInCurMonth;$i++){
	$day[substr($sqlMonth,2,2).sprintf("%02d",$i)] = 0;
}

for($i=0;$i<count($month);$i++){
	$dayInt = substr($month[$i]['cart_order_id'],2,4);
	$day[$dayInt] = $day[$dayInt] + $month[$i]['prod_total'];
}

$chartData = array();

$i=0;

foreach($day as $key => $value){
	$i++;
	$chartData[] = array($i,round($value));
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
$graph->SetTitle(sprintf($lang['admin']['stats_daily_sales_chart'],$monthsArray[substr($sqlMonth,2,2)]."'".substr($sqlMonth,0,2)));
$graph->SetXTitle($lang['admin']['stats_days_of_month']);
$graph->SetYTitle(sprintf($lang['admin']['stats_sales_vol'],$config['defaultCurrency']));
$graph->DrawGraph();

unset($month,$chartData,$dayInt,$day);
echo "<img src='cache/".$filename."' />";
?>