<?php
/*
+--------------------------------------------------------------------------
|	regpopup.inc.php
|   ========================================
|	Remove customer id from session	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

## include lang file
$lang = getLang("includes".CC_DS."boxes".CC_DS."regpopup.inc.php");


$box_content = new XTemplate ("boxes".CC_DS."regpopup.tpl");

# Customer Type
	/*$customerType = $db->select("SELECT id, customer_type FROM ".$glob['dbprefix']."ImeiUnlock_customer_type ORDER BY customer_type");
	
	
	for ($i=0; $i<count($customerType); $i++) {
		if ($customerType[$i]['id'] == $cc_session->ccUserData['customer_type']) {
			$box_content->assign("CUSTOMER_TYPE_SELECTED","selected='selected'");
		} else {
			$box_content->assign("CUSTOMER_TYPE_SELECTED","");
		}
		
		$box_content->assign("VAL_CUSTOMER_TYPE_ID",$customerType[$i]['id']);

	
		$customerTypes = $customerType[$i]['customer_type'];

		$box_content->assign("VAL_CUSTOMER_TYPE_NAME",$customerTypes);
		$box_content->parse("regpopup.customer_type_opts");
	
	} */
	//End customer Type


$box_content->parse("regpopup");
$box_content = $box_content->text("regpopup");


?>