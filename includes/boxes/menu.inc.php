<?php
/*
+--------------------------------------------------------------------------
|	cartNavi.inc.php
|   ========================================
|	Cart Pages Navigation Links Box	
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$box_content = new XTemplate ("boxes".CC_DS."menu.tpl");

$qryString = $_SERVER["QUERY_STRING"];
$file = $_SERVER["SCRIPT_NAME"];
$break = explode('/', $file);
$pfile = $break[count($break) - 1]; 
if($pfile == "index.php" && $qryString == ""){
	$box_content->assign("HOME","class='activem'");
}else if(isset($_GET['_a'])&& $_GET['_a']=="viewUnlocks" ){
	$box_content->assign("CAT","class='activem'");
}else if(isset($_GET['_a'])&& $_GET['_a']=="viewother" ){
	$box_content->assign("CATOTHER","class='activem'");
}else if(isset($_GET['_a'])&& $_GET['_a']=="viewProd" ){
	$box_content->assign("SHOP","class='activem'");
}else if(isset($_GET['docId'])&& $_GET['docId']==2 ){
	$box_content->assign("bulkorder","class='activem'");
}else if(isset($_GET['docId'])&& $_GET['docId']==4 ){
	$box_content->assign("howwork","class='activem'");
}else if(isset($_GET['docId'])&& $_GET['docId']==8 ){
	$box_content->assign("contactus","class='activem'");
}else if(isset($_GET['_a'])&& $_GET['_a']=="viewCat" ){
	$box_content->assign("ACCES","class='activem'");
}else if(isset($_GET['_a'])&& $_GET['_a']=="repair" ){
	$box_content->assign("REPAIRACTIVE","class='activem'");
}
if($config['sef']){
		
	$box_content->assign("IPHONEUNLOCK",'AvailableNetworks.html');
	$box_content->assign("OTHERUNLOCK",'Other-Networks.html');
	$box_content->assign("REPAIR",'Repair.html');
	$box_content->assign("ORDERNOW",'OrderNow.html');
	$box_content->assign("BULUCKORDER",'BulckOrder.html');
	$box_content->assign("HOWTOUNCLOK",'How-To-Unlock.html');
	$box_content->assign("CONTACTUS",'Contact-Us.html');
	$box_content->assign("MACCE",'MobileAccessories.html');
}
else{
	$box_content->assign("IPHONEUNLOCK",'index.php?_a=viewCat');
	$box_content->assign("MACCE",'index.php?_a=mobileacces');
	$box_content->assign("REPAIR",'index.php?_a=repair');
	$box_content->assign("OTHERUNLOCK",'index.php?_a=viewother');
	$box_content->assign("ORDERNOW",'index.php?_a=viewProd');
	$box_content->assign("BULUCKORDER",'index.php?_a=viewDoc&docId=2');
	$box_content->assign("HOWTOUNCLOK",'index.php?_a=viewDoc&docId=17');
	$box_content->assign("CONTACTUS",'index.php?_a=contactus&docId=8');
}
$box_content->parse("menu");
$box_content = $box_content->text("menu");
?>