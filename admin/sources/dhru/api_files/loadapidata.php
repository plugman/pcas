<?php 

require_once ("../../../../ini.inc.php");
require_once ("../../../../includes".CC_DS."global.inc.php");
require_once ("../../../../includes".CC_DS."functions.inc.php");
require_once ("../../../../classes".CC_DS."db".CC_DS."db.php");
$db = new db();
if($_POST['vendorid']> 0){
$catquery		= "SELECT * FROM ".$glob['dbprefix']."dhru_cat".$_POST['vendorid']."  ORDER BY catname ASC";
$catResult = $db->select($catquery); 

if(!empty($catResult)){
?>
<select name="mapid" onchange="loadbrands(this.value,'<?php echo $glob['storeURL']; ?>');">
<option>Select product for Map</option>
<?php
	$catCount = count($catResult);
	for($j=0; $j<$catCount; $j++){
?>
<optgroup style="font-size:13px; font-weight:bold;" label="<?php echo $catResult[$j]['catname']; ?>">
<?php
		$queryall = "SELECT SERVICENAME,SERVICEID  FROM ".$glob['dbprefix']."dhru_products".$_POST['vendorid']." WHERE cat_id = ".$catResult[$j]['catid'];
		$ResultAll= $db->select($queryall);
		if(!empty($ResultAll)){
			for($z=0;$z<count($ResultAll);$z++){
		?>
		<option value="<?php echo $ResultAll[$z]['SERVICEID']; ?>" <?php if ($ResultAll[$z]['SERVICEID']== $results[0]['mapid']) echo 'selected="selected"'; ?>><?php echo $ResultAll[$z]['SERVICENAME']; ?></option>
<?php			
			
			
			}
			?>
			</optgroup> <?php 
	}
	}
	?>
	</select>
    <?php
}
}
$db->close();
?>