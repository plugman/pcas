<?php
/*
+--------------------------------------------------------------------------
|	online.inc.php
|   ========================================
|	View Front Sessions	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$timeLimit = time() - 900;
$query = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_sessions LEFT JOIN ".$glob['dbprefix']."ImeiUnlock_customer ON ".$glob['dbprefix']."ImeiUnlock_sessions.customer_id = ".$glob['dbprefix']."ImeiUnlock_customer.customer_id WHERE timeLast>".$timeLimit." ORDER BY timeLast DESC";
// query database
$results = $db->select($query, $rowsPerPage, $page);
$numrows = $db->numrows($query);
?><br />

<p class='pageTitle'><?php echo $lang['admin']['stats_cust_online'];?></p><br />

<p class="copyText"><?php echo $lang['admin']['stats_cust_active'];?></p><br />


<table width="100%" border="1" cellpadding="3" cellspacing="1" class="mainTable mainTable4">
  <tr>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_hash'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_customer'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_location'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_sess_start_time'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_last_click_time'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_last_ip_address'];?></td>
    <td nowrap="nowrap" class="tdTitle"><?php echo $lang['admin']['stats_sess_length'];?></td>
  </tr>
<?php 
if($results==TRUE) 
{
  		
	for ($i=0; $i<count($results); $i++)
	{
		
		$rank = ($page * $rowsPerPage) + ($i + 1);
			
		$cellColor = cellColor($i);
?>

  <tr>
    <td class="<?php echo $cellColor; ?>" width="15"><span class="copyText"><?php echo $rank; ?>.</span></td>
    <td class="<?php echo $cellColor; ?>" width="100" nowrap='nowrap'>
	<span class="copyText">
	<?php if($results[$i]['customer_id']==0){ 
	echo $lang['admin']['stats_geust'];
	} else {
	echo $results[$i]['title']." ".$results[$i]['firstName']." ".$results[$i]['lastName'];
	} ?>
	</span></td>
	<td class="<?php echo $cellColor; ?>" width="100" nowrap='nowrap'><a href="<?php echo $results[$i]['location']; ?>" class="txtLink"><?php echo $results[$i]['location']; ?></a></td>
    <td class="<?php echo $cellColor; ?>" nowrap='nowrap'><span class="copyText"><?php echo formatTime($results[$i]['timeStart']); ?></span></td>
	<td class="<?php echo $cellColor; ?>" nowrap='nowrap'><span class="copyText"><?php echo formatTime($results[$i]['timeLast']); ?></span></td>
    <td class="<?php echo $cellColor; ?>" nowrap='nowrap'><a href="javascript:;" class="txtLink" onclick="openPopUp('<?php echo $glob['adminFile']; ?>?_g=misc/lookupip&amp;ip=<?php echo $results[$i]['ip']; ?>','misc',300,120,'yes,resizable=yes')"><?php echo $results[$i]['ip']; ?></a></td>
    <td class="<?php echo $cellColor; ?>" nowrap='nowrap'><span class="copyText"><?php echo sprintf("%.2f",($results[$i]['timeLast']-$results[$i]['timeStart'])/60); ?> <?php echo $lang['admin']['stats_mins'];?></span></td>
  </tr>
		<?php } 	} else { ?>
  <tr>
    <td colspan="7"><span class="copyText"><?php echo $lang['admin']['stats_sorry_no_data'];?></span></td>
  </tr>  
  <?php } ?>
</table>
