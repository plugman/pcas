<?php 

require_once ("../../../../ini.inc.php");
require_once ("../../../../includes".CC_DS."global.inc.php");
require_once ("../../../../includes".CC_DS."functions.inc.php");
require_once ("../../../../classes".CC_DS."db".CC_DS."db.php");
$db = new db();
/*echo "<pre>";
		print_r($_POST);*/
$queryall = "SELECT *  FROM ".$glob['dbprefix']."dhru_products".$_POST['vendor']." WHERE SERVICEID = ".$_POST['SERVICEID'];
		$ResultAll= $db->select($queryall);
$db->close();
		
?>
<table width="900" cellspacing="1" cellpadding="3" border='1'>
<tr>
<td>SERVICE ID</td><td><?php echo $ResultAll[0]['SERVICEID']; ?></td></tr>
<tr>
<td>SERVICE NAME</td><td><?php echo $ResultAll[0]['SERVICENAME']; ?></td></tr>
<tr>
<td>CREDIT</td><td><?php echo $ResultAll[0]['CREDIT']; ?></td></tr>
<tr>
<td>TIME</td><td><?php echo $ResultAll[0]['TIME']; ?></td></tr>
<tr>
<td>INFO</td><td width="400;"><?php echo $ResultAll[0]['INFO']; ?></td></tr>
<tr>
<td>Requires.Network</td><td><?php echo $ResultAll[0]['Requires.Network']; ?></td></tr>
<tr>
<td>Requires.Mobile</td><td><?php echo $ResultAll[0]['Requires.Mobile']; ?></td></tr>
<tr>
<td>Requires.Provider</td><td><?php echo $ResultAll[0]['Requires.Provider']; ?></td></tr>
<tr>
<td>Requires.PIN</td><td><?php echo $ResultAll[0]['Requires.PIN']; ?></td></tr>
<tr>
<td>Requires.KBH</td><td><?php echo $ResultAll[0]['Requires.KBH']; ?></td></tr>
<tr>
<td>Requires.MEP</td><td><?php echo $ResultAll[0]['Requires.MEP']; ?></td></tr>
<tr>
<td>Requires.PRD</td><td><?php echo $ResultAll[0]['Requires.PRD']; ?></td></tr>
<tr>
<td>Requires.Type</td><td><?php echo $ResultAll[0]['Requires.Type']; ?></td></tr>
<tr>
<td>Requires.Locks</td><td><?php echo $ResultAll[0]['Requires.Locks']; ?></td></tr>
<tr>
<td>Requires.Reference</td><td><?php echo $ResultAll[0]['Requires.Reference']; ?></td>
</tr>
</table>