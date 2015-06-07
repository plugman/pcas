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
|	Tracking code for tradeDoubler	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
$module = fetchDbConfig('tradeDoubler');

$testVar = ($module['testMode'] == 1) ? "&testonly=1" : "";

$affCode = "<!-- Begin tradeDoubler Affiliate Tracker -->
<img src='http://www.awin1.com/sale.php?sale=".sprintf("%.2f",$orderSum['prod_total'])."&extra=".$cart_order_id."&type=s&mid=".$module['acNo'].$testVar."' width='0' height='0' alt='' />
<!-- End tradeDoubler Affiliate Tracker -->";
?>