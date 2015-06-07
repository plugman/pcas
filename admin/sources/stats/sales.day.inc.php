<?php 
/*

|	sales.day.inc.php
|   ========================================
|	Sales Stats by Day	
+--------------------------------------------------------------------------
*/
if(!defined('CC_INI_SET')){ die("Access Denied"); } ?>
<div class="headingBlackbg"><?php echo $lang['admin']['stats_hourly_sales'] ;?></div>
<p class='copyText'><?php echo $lang['admin']['stats_by_day'] ;?>
 <div class="inputbox">
		<span class="bgleft"></span>
    	<select name="hStart" onchange="jumpMenu('parent',this,0)">
<?php

$monthVal = $monthValLowest;
$firstMonth = $firstMonthLowest;
$firstYear = $firstYearLowest;

while ($monthVal <= $yearNow.$monthNow) {
	echo sprintf('<optgroup label="%s - %s">', $firstYear, $monthsArray[$firstMonth]);
	
	$daysInCurMonth = cal_days_in_month(CAL_GREGORIAN, $firstMonth, "20".$firstYear);

	for ($i=1; $i<=$daysInCurMonth; $i++) {
		$optVal = $firstYear.sprintf("%02d",$firstMonth).sprintf("%02d",$i);
		
		if ($optVal<=$yearNow.$monthNow.$dayNow && $optVal >= $firstYearLowest.$firstMonthLowest.$firstDayLowest) {
		
			echo "	<option value='".$glob['adminFile']."?_g=stats/index&amp;stats=sales&amp;dStart=".$optVal."&amp;yStart=".$_GET['yStart']."&amp;mStart=".$_GET['mStart']."'";
			
			if($optVal == $_GET['dStart']) { 
				echo " selected='selected'"; 
			} elseif($optVal==$yearNow.$monthNow.$dayNow) {
				echo " selected='selected'";
			} 
			echo ">".$i."</option>\n";
		}
	}
	echo "</optgroup>\n\n";
	
	if ($firstMonth==12) {
		$firstMonth = "01";
		$firstYear = sprintf("%02d", $firstYear + 1);
	} else {
		$firstMonth = sprintf("%02d", $firstMonth + 1);
	}
	$monthVal = $firstYear.$firstMonth;
}
?>
</select>	
	   <span class="bgright"></span>
	   </div>

<input name="reset" value="<?php echo $lang['admin']['stats_reset']; ?>" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=stats/index&amp;stats=sales&amp;yStart=<?php echo $_GET['yStart'] ?>&amp;mStart=<?php echo $_GET['mStart'];?>');return document.returnValue" type="button" class="submit" />
</p>
<?php

if(isset($_GET['dStart']) && !empty($_GET['dStart'])) {
	$sqlDay = $_GET['dStart'];	
} else {
	$sqlDay = $yearNow.$monthNow.$dayNow;
}

$query = "SELECT prod_total, cart_order_id FROM `".$glob['dbprefix']."ImeiUnlock_order_sum` WHERE cart_order_id>'".$sqlDay."-000000-0000' AND cart_order_id<'".$sqlDay."-999999-9999' AND `status` = 3 ORDER BY cart_order_id ASC;";

$day = $db->select($query);

for($i=0;$i<23;$i++) {
	$hour[sprintf("%02d",$i)] = 0;
}


for($i=0;$i<count($day);$i++){
	$hourInt = substr($day[$i]['cart_order_id'],7,2);
	$hour[$hourInt] = $hour[$hourInt] + $day[$i]['prod_total'];
}

$i=0;
$chartData = array();

foreach($hour as $key => $value){
	$chartData[] = array(sprintf("%02d",$i),round($value));
	$i++;
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
$graph->SetTitle(sprintf($lang['admin']['stats_hourly_sales_chart'],$monthsArray[substr($sqlDay,2,2)]." ".substr($sqlDay,4,2)." '".substr($sqlDay,0,2)));
$graph->SetXTitle($lang['admin']['stats_hours_of_day']);
$graph->SetYTitle(sprintf($lang['admin']['stats_sales_vol'],$config['defaultCurrency']));
$graph->DrawGraph();

echo "<img src='cache/".$filename."' />";
?>