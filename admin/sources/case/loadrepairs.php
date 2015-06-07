<?php 
require_once ("../../../ini.inc.php");
require_once ("../../../includes".CC_DS."global.inc.php");
require_once ("../../../includes".CC_DS."functions.inc.php");
require_once ("../../../classes".CC_DS."db".CC_DS."db.php");
require_once ("../../../classes".CC_DS."cache".CC_DS."cache.php");

$db = new db();
if(isset($_POST['catid']) && $_POST['catid'] >0){
	$result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_category WHERE cat_father_id = ".$db->mySQLSafe($_POST['catid']). " AND hide = '0' AND type = '2'  ORDER BY priority,cat_name ASC");
	if($result){
		if($_POST['level'] == 1){
			$level = '2';
			$name = 'device';
			}elseif($_POST['level'] == 2){
				$name = 'model';
		$level = '3';
			}
			?>
    <select name="<?php echo $name ?>" class="textbox5" onchange="loaddevicess('<?php echo $glob['storeURL']; ?>', this.value, '<?php echo $level; ?>');" id="<?php echo $name ?>">
    <option>Select product for Map</option>
        <?php
		for($i=0;$i<count($result);$i++){
			?>
           <option value=" <?php echo $result[$i]['cat_id'] ?>"><?php echo $result[$i]['cat_name'] ?> </option>
            <?php
		}
		?>
        </select>
        <?php
		echo '::'.$_POST['level'].'::1';
	}else if(($result = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE cat_id = ".$db->mySQLSafe($_POST['catid']). "  AND digital = '2'  ORDER BY productId ASC"))){
		
		?>
         <select name="problem" class="textbox5" onchange="loaddetailss('<?php echo $glob['storeURL']; ?>', this.value, '4');" id="probleme">
          <option>Select Problem</option>
        <?php
	
		for($i=0;$i<count($result);$i++){
			?>
           <option value=" <?php echo $result[$i]['productId'] ?>"><?php echo $result[$i]['name'] ?> </option>
            <?php 
		}
		echo '::3::1';
	}else
	echo '2::'.$_POST['level'];
}
$db->close();	
?>