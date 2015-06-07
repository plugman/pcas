<?
	require("includes/global.inc.php");
	require("includes/functions.inc.php");
	require("classes/db/db.php");
	$db 		= new db();
?>
<?php


if (!empty($_FILES)) 
{
	$tempFile 	= $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$NewFile 	= date("jnYHis").$_FILES['Filedata']['name'];
	$targetFile =  str_replace('//','/',$targetPath) . $NewFile;
	
	move_uploaded_file($tempFile,$targetFile);	
	
	$data['productId']  = $db->mySQLSafe($_REQUEST['prdid']);
	$data['image']		= $db->mySQLSafe($NewFile);
	$status = $db->insert("tbl_tmpimg_idx", $data);			
	
	echo "1";		
}
?>