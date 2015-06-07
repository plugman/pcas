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
|	tracker.inc.php
|   ========================================
|	Tracking code for iDevAffiliate	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
$module = fetchDbConfig('iDevAffiliate');

$affCode = "<!-- Begin iDevAffiliate Affiliate Tracker -->
<img border='0' src='".$module['URL']."sale.php?profile=72198&idev_saleamt=".sprintf("%.2f", $orderSum['prod_total'])."&idev_ordernum=".$cart_order_id."' width='0' height='0' alt='' />
<!-- End iDevAffiliate Affiliate Tracker -->";
?>