<?php
/*
+--------------------------------------------------------------------------|   ImeiUnlock 4
|   ========================================
|	ImeiUnlock is a registered trade mark of Devellion Limited
|   Copyright Devellion Limited 2006. All rights reserved.
|   Devellion Limited,
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.devellion.com
|	UK Private Limited Company No. 5323904
|   ========================================
|   Web: http://www.cubecart.com
|   Email: info (at) cubecart (dot) com
|	License Type: ImeiUnlock is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.cubecart.com/v4-software-license
+--------------------------------------------------------------------------
|	transfer.inc.php
|   ========================================
|	Core Print Order Form Functions
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

function repeatVars()
{

		return FALSE;
	
}

function fixedVars()
{
	
		return FALSE;

}

///////////////////////////
// Other Vars
////////
$formAction = "index.php?_g=cs&amp;_p=".urlencode("modules/gateway/Print_Order_Form/orderForm.inc.php")."&amp;cart_order_id=".$orderSum['cart_order_id'];
$formMethod = "post";
$formTarget = "_self";
$transfer = "auto";
?>