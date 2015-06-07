<?php
/*
+--------------------------------------------------------------------------
|	index.php
|   ========================================
|	Manage Main Store Settings	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_settings.inc.php");

$msg = false;

permission("pickup","read", true);
if (isset($_POST) && !empty($_POST['day'])) {
	/*echo "<pre>";
	print_r($_POST);*/
	//die();
	for($i=0;$i<count($_POST['count']);$i++){
		$record['day'] = $db->mySQLSafe($_POST['day'][$i]);
		$record['from'] = $db->mySQLSafe($_POST['from'][$i]);
		$record['to'] = $db->mySQLSafe($_POST['to'][$i]);
		//$recodr['type'] = $db->mySQLSafe($_POST['day'][$i]);
		if(!isset($_POST['id'][$i])  && isset($_POST['from'][$i])){
			$insert = $db->insert("ImeiUnlock_pickup", $record);
		}
		elseif(isset($_POST['id'][$i]) && isset($_POST['from'][$i])){
		$where = " id = ".$db->mySQLSafe($_POST['id'][$i]);
		$update = $db->update("ImeiUnlock_pickup", $record, $where);	
		}elseif(isset($_POST['id'][$i]) && !isset($_POST['from'][$i])){
		$where = " id = ".$db->mySQLSafe($_POST['id'][$i]);
		$delete = $db->delete("ImeiUnlock_pickup", $where);
		}
		unset($recodr);
		
	}
}
$results = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_pickup ORDER BY day ASC");
/*echo "<pre>";
print_r($results);*/
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");
?>

<p class="pageTitle">Pickup Timing</p>
<?php if (isset($msg)) echo msg($msg); ?>
</p>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script> 
<script type="text/javascript" src="<?php echo $GLOBALS['rootRel']; ?>js/jquery.timePicker.js"></script>
<link href="<?php echo $GLOBALS['rootRel'].$glob['adminFolder']; ?>/styles/timePicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
  function updatetime(){
    // Default.
    $("#time1").timePicker();
    // 02.00 AM - 03.30 PM, 15 minutes steps.
    $("#time2").timePicker({
  startTime: "02.00",  // Using string. Can take string or Date object.
  endTime: new Date(0, 0, 0, 15, 30, 0),  // Using Date object.
  show24Hours: false,
  separator:'.',
  step: 15});
    
    // An example how the two helper functions can be used to achieve 
    // advanced functionality.
    // - Linking: When changing the first input the second input is updated and the
    //   duration is kept.
    // - Validation: If the second input has a time earlier than the firs input,
    //   an error class is added.
    
    // Use default settings
   // $("#time3, #time4").timePicker();
   $("#time3, #time4").timePicker({
  startTime: "00.00",  // Using string. Can take string or Date object.
  endTime: "23.00",  // Using Date object.
  show24Hours: false,
  separator:'.',
  step: 60});
    // Store time used by duration.
    var oldTime = $.timePicker("#time3").getTime();
    // Keep the duration between the two inputs.
    $("#time3").change(function() {
      if ($("#time4").val()) { // Only update when second input has a value.
        // Calculate duration.
        var duration = ($.timePicker("#time4").getTime() - oldTime);
        var time = $.timePicker("#time3").getTime();
        // Calculate and update the time in the second input.
        $.timePicker("#time4").setTime(new Date(new Date(time.getTime() + duration)));
        oldTime = time;
      }
    });
    // Validate.
    $("#time4").change(function() {
      if($.timePicker("#time3").getTime() > $.timePicker(this).getTime()) {
        $(this).addClass("error");
      }
      else {
        $(this).removeClass("error");
      }
    });
    
  }
  </script>
<form name="updateSettings" method="post" enctype="multipart/form-data" target="_self" action="<?php echo $glob['adminFile']; ?>?_g=repair/pickup">
  <table border="0" cellspacing="1" cellpadding="3" class="mainTable" width="100%">
    <tr>
      <td  class="tdTitle" colspan="6" ><strong>Pickup Timing</strong></td>
    </tr>
    <tr>
      <td colspan="6"><input type="hidden" id="rowCount" value="<?php echo $rows ?>" />
        <table border="0" cellspacing="1" cellpadding="3" width="100%" id="monday">
          <tr>
            <td class="tdText" colspan="3" width="12%"><strong>Pickup Timing</strong></td>
            <td  valign="left"  colspan="3"><input type="submit"  size="10" value="Add Rows" onclick="return addtimerow('monday', 'mondayRowsAdd')"/>
              <input type="hidden"  size="2" value="1" id="mondayRowsAdd" name="prodRowsAdd"></td>
          </tr>
          <?php
	if($results){
		
		for($i=0;$i<count($results);$i++){
			?>
            <input type="hidden" name="id[]" value="<?php echo $results[$i]['id']; ?>"  />
            <input type="hidden" name="count[]" value=""  />
          <tr id="<?php echo 'orderrow_'.$results[$i]['id']; ?>">
            <td   width="3%"><a href="" onclick="return delRow('<?php echo 'orderrow_'.$results[$i]['id']; ?>')"><img src="admin/images/del.gif" alt="" /></a></td>
            <td  class="tdText" width="15%"><select name="day[]">
                <option value="1" <?php if($results[$i]['day'] == 1) echo "selected='selected'";?>>Monday</option>
                <option value="2" <?php if($results[$i]['day'] == 2) echo "selected='selected'";?>>Tuesday</option>
                <option value="3" <?php if($results[$i]['day'] == 3) echo "selected='selected'";?>>Wednessday</option>
                <option value="4" <?php if($results[$i]['day'] == 4) echo "selected='selected'";?>>Thursday</option>
                <option value="5" <?php if($results[$i]['day'] == 5) echo "selected='selected'";?>>Friday</option>
                <option value="6" <?php if($results[$i]['day'] == 6) echo "selected='selected'";?>>Saturday</option>
                <option value="7" <?php if($results[$i]['day'] == 7) echo "selected='selected'";?>>Sunday</option>
              </select></td>
            <td  class="tdText" width="6%">From</td>
            <td  valign="left" width="25%"><input type="text" id="time3" size="10" value="<?php echo $results[$i]['from']; ?>" class="textbox" onclick="updatetime();" name="from[]"/></td>
            <td  class="tdText" width="6%">To</td>
            <td  valign="left" width="25%"><input type="text" id="time4" size="10" value="<?php echo $results[$i]['to']; ?>" class="textbox" onclick="updatetime();" name="to[]"/>
              <input type="hidden" id="rowCount" value="1" /></td>
              </tr>
            <?php
		}
	}
	?>
        </table></td>
    </tr>
    <tr>
      <td colspan="1" align="left"><div>
          <input name="submit" type="submit" class="submit" id="submit" value="<?php echo $lang['admin']['settings_update_settings'];?>" />
        </div></td>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
</form>
