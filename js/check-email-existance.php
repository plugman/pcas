<?php	
	include_once("../includes/global.inc.php");
	include_once("../classes/db/db.php");
	$db = new db();
	include_once("../includes/functions.inc.php");

	////////////////////////////Checks the username Existence of the customer going to regsiter.\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	
	$rsCheck = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer WHERE email = ".$db->mySQLSafe($_GET['email']));
//die();
	$available_image 		= "<img src='".$glob['storeURL'] ."/images/icons/yes.png' border='0' style='height:16px;padding-top:8px;width:14px;'/>";
	$not_available_image 	= "<img src='".$glob['storeURL'] ."/images/icons/no.png' border='0'  style='height:16px;padding-top:8px;width:14px;'/>";
	
/*	if($skin == "AR_AyoWorld")
	{
		if(!empty($rsCheck))
		echo $not_available_image."<script>document.getElementById('txtEmail').focus(); document.getElementById('email_exist_flag').value=0;document.getElementById('txtEmail').style.width='196px';document.getElementById('txtEmail').style.marginLeft='15px';</script>";			
	else
		echo $available_image."<script>document.getElementById('email_exist_flag').value=1;document.getElementById('txtEmail').style.width='196px';document.getElementById('txtEmail').style.marginLeft='15px';</script>";		
	}
	else 
	{
		if(!empty($rsCheck))
			echo $not_available_image."<script>document.getElementById('txtEmail').focus(); document.getElementById('email_exist_flag').value=0;</script>";			
		else
			echo $available_image."<script>document.getElementById('email_exist_flag').value=1;</script>";
	}*/
	
	if(!empty($rsCheck))
			echo $not_available_image."<script>document.getElementById('txtEmail').focus(); document.getElementById('email_exist_flag').value=0;</script>";			
		else
			echo $available_image."<script>document.getElementById('email_exist_flag').value=1;</script>";
	////////////////////////////Checks the username Existence of the customer going to regsiter.\\\\\\\\\\\\\\\\\\\\\\\\\\\\\	
?>