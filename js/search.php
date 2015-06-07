<?php	
	include_once("../includes/global.inc.php");
	include_once("../classes/db/db.php");
	$db = new db();
	include_once("../includes/functions.inc.php");
if(isset($_POST['name']) && !empty($_POST['name'])){
$partialStates = $_POST['name'];

$states =("SELECT email, customer_id FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE email LIKE '$partialStates%'");
$stateArray = $db->select($states);
$count = count($stateArray);
echo "<ul>";
for($i=0;$i< $count ;$i++){
	echo "<li>" ;
	echo '<a href="?_g=customers/viewcustomerdetail&amp;customerdetail='.$stateArray[$i]['customer_id'].'"style="color:#000">'.$stateArray[$i]['email'] .'</a>';
	echo "</li>";
}
echo "</ul>";
}

?>