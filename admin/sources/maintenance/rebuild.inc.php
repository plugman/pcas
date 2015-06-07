<?php 
/*

|	rebuild.inc.php
|   ========================================
|	Used to rebuild/recount things
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("maintenance", 'read', true);

$lang = getLang("admin".CC_DS."admin_misc.inc.php");

if (isset($_GET['filemanager'])) {
	include_once CC_ROOT_DIR.CC_DS.'classes'.CC_DS.'filemanager.class.php';
	$filemanager	= new Filemanager();
	$filemanager->buildDatabase(true);
	$msg = "<p class='infoText'>Image database has been updated.</p>";
}

if($_GET['emptyTransLogs']==1) {

	$truncate = $db->misc("TRUNCATE TABLE `".$glob['dbprefix']."ImeiUnlock_transactions`"); 
	
	if ($truncate) {
		$msg = "<p class='infoText'>".$lang['admin']['misc_trans_logs_emptied']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['misc_trans_logs_not_emptied']."</p>";
	}

} else if ($_GET['clearCache']==1){
	$cache = new cache();
	$cache->clearCache();
	
	if (file_exists(CC_ROOT_DIR.CC_DS.'includes'.CC_DS.'extra'.CC_DS.'admin_cat_cache.txt')) {
		unlink(CC_ROOT_DIR.CC_DS.'includes'.CC_DS.'extra'.CC_DS.'admin_cat_cache.txt');
	}
	
	$msg = "<p class='infoText'>".$lang['admin']['misc_cache_cleared']."</p>";
	
} elseif($_GET['removeKey']==1) {

	$config = fetchDbConfig("config");
	$newConfig = $config;
	unset($newConfig['lk'],$newConfig['lkv']);
	$msg = writeDbConf($newConfig,"config", $newConfig, TRUE);
	unset($config);
	$config = fetchDbConfig("config");
	
} elseif($_GET['uploadSize']==1) {
	$dirArray = walkDir(CC_ROOT_DIR .CC_DS."images".CC_DS."uploads", true, 0, 0, false, $int = 0);
	$size = 0;
	
	if(is_array($dirArray)){

		foreach($dirArray as $file) {
			
			if(file_exists($file)){
				$size = filesize($file) + $size;
			}
		
		}
	
	}

	$rebuild['uploadSize'] = $size;
	$msg = writeDbConf($rebuild,"config", $config, true);
	
} else if ($_GET['catCount'] == 1) {
	
	## Lets override the default execution time
	@set_time_limit(0);
	$success = false;
	
	if ($config['cache']) {
		## Purge the Cache
		$cache = new cache();
		$cache->clearCache();
	//	$msg = "<p class='infoText'>".$lang['admin']['misc_cache_cleared']."</p>";
	
		if (file_exists(CC_ROOT_DIR.CC_DS.'includes'.CC_DS.'extra'.CC_DS.'admin_cat_cache.txt')) {
			unlink(CC_ROOT_DIR.CC_DS.'includes'.CC_DS.'extra'.CC_DS.'admin_cat_cache.txt');
		}
	}
	
	## Set the number of products in all categories to 0
	$record['noProducts'] = 0;
	$update = $db->update($glob['dbprefix'].'ImeiUnlock_category', $record, '');
	
	## Count primary categories of products
#	$prodquery	= sprintf("SELECT COUNT(productId) as Count, cat_id FROM %sImeiUnlock_inventory WHERE disabled = '0' GROUP BY cat_id", $glob['dbprefix']);
#	$products	= $db->select($prodquery);
#	if ($products) {
#		foreach ($products as $product) {
#			$db->categoryNos($product['cat_id'], '+', $product['Count']);
#		}
#		$success = true;
#	}
	
	## Delete records from cats_idx if the productId isn't in the inventory
	$idxquery = sprintf("DELETE FROM %1\$sImeiUnlock_cats_idx WHERE productId NOT IN (SELECT DISTINCT productId FROM %1\$sImeiUnlock_inventory WHERE disabled = '0')", $glob['dbprefix']);
	$db->misc($idxquery);
	
	## Delete duplicate cat_idx rows credit to Sir Willaim. Thanks Bill!!
	$sql = "SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_cats_idx ORDER BY `productId`, `cat_id` ASC";
	$results = $db->select($sql);
	
	$noResults = count($results);
	
	if($results) {
		for($i=0, $noResults; $i<$noResults; $i++) {
			if($thiscat == $results[$i]['cat_id'] && $thisprod == $results[$i]['productId']) {
				$results[$i]['flag'] = true;
				$flagged = true;
			} else {
				$results[$i]['flag'] = false;
			}
			$thiscat = $results[$i]['cat_id'];
			$thisprod = $results[$i]['productId'];
		} // end for loop
	
		if($flagged == true) {
			foreach($results as $product) {
				if($product['flag'] == true) {
					$db->delete($glob['dbprefix']."ImeiUnlock_cats_idx", "`id` = ".$db->MySQLSafe($product['id']));
				}
			} // end foreach loop

		} 

	} 
		
	## Count the number of products in the cats_idx table by category
	$countQuery	= sprintf("SELECT COUNT(cat_id) as count, cat_id FROM %1\$sImeiUnlock_cats_idx WHERE cat_id IN(SELECT DISTINCT cat_id FROM %1\$sImeiUnlock_cats_idx WHERE 1) GROUP BY cat_id", $glob['dbprefix']);
	$catCount	= $db->select($countQuery);
	
	if ($catCount) {
		foreach ($catCount as $category) {
			## Set the number of products in each category
			$db->categoryNos($category['cat_id'], '+', $category['count']);
		}
		$success = true;
	}
	
	buildCatList();
		
	if ($success) {
		$msg .= "<p class='infoText'>".$lang['admin']['misc_cat_count_success']."</p>";
	} else {
		$msg .= "<p class='warnText'>".$lang['admin']['misc_cat_count_no_prod']."</p>";
	}
	
} else if ($_GET['prodViews'] == 1) {

	if ($config['cache']) {
		
		$cache = new cache();
		$cache->clearCache();
	
		$msg = "<p class='infoText'>".$lang['admin']['misc_cache_cleared']."</p>";
		
	}

	// set noProducts in all categories to 0
	$record['popularity'] = $db->mySQLSafe(0);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_inventory", $record, $where="");
	
	if($update) {
	
		$msg .= "<p class='infoText'>".$lang['admin']['misc_prod_views_zero']."</p>";
	
	} else {
	
		$msg .= "<p class='warnText'>".$lang['admin']['misc_prod_views_not_reset']."</p>";
	
	}
	
} elseif($_GET['clearSearch']==1) {
	// set noProducts in all categories to 0
	$truncate = $db->misc("TRUNCATE TABLE `".$glob['dbprefix']."ImeiUnlock_search`"); 
	
	if($truncate == TRUE) {
	
		$msg = "<p class='infoText'>".$lang['admin']['misc_search_terms_reset']."</p>";
	
	} else {
	
		$msg = "<p class='warnText'>".$lang['admin']['misc_search_terms_not_reset']."</p>";
	
	}
	
} elseif($_GET['orderCount']==1) {
	// set noOrders for all products to 0
	$record['noOrders'] = $db->mySQLSafe(0);
	$update = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, $where="");
	
	// get all customers
	$customers = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_customer");
	
	if($customers==TRUE){
		for ($i=0; $i<count($customers); $i++){
			$noOrders = $db->numrows("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_order_sum WHERE customer_id=".$db->mySQLSafe($customers[$i]['customer_id']));
			$record['noOrders'] = $noOrders;
			$result = $db->update($glob['dbprefix']."ImeiUnlock_customer", $record, "customer_id=".$db->mySQLSafe($customers[$i]['customer_id']));
				
		}
		
		$msg = "<p class='infoText'>".$lang['admin']['misc_customers_orders_counted']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['misc_no_customers_exist']."</p>";
	}

/* Removed as it can't be used due to mult folder paths	
} else if ($_GET['thumbs'] == 1) {
	$path = CC_ROOT_DIR .CC_DS."images".CC_DS."uploads".CC_DS."thumbs";
	$dirArray = walkDir($path, false, 0, 0, false, $int = 0);
	unset($dirArray['max']);
	if (is_array($dirArray)) {
		foreach ($dirArray as $file) {
			$masterFilename = str_replace(CC_ROOT_DIR .CC_DS."images".CC_DS."uploads".CC_DS."thumbs".CC_DS."thumb_","",$file);
			// delete files that dont contain thumb_
			if (!strstr($file, "thumb_")) {
				echo $file." - arse<hr />";
				unlink($file);
			} else if (!file_exists(CC_ROOT_DIR .CC_DS."images".CC_DS."uploads".CC_DS. $masterFilename)) {
				unlink($file);
			}
		} 
		$msg = "<p class='infoText'>".$lang['admin']['misc_redundant_thumbs_gone']."</p>";
	} else {
		$msg = "<p class='warnText'>".$lang['admin']['misc_thumbs_folder_empty']."</p>";
	}
*/
	
} else if ($_GET['clearLogs'] == 1) {
	$sql = sprintf("TRUNCATE TABLE %sImeiUnlock_admin_log", $glob['dbprefix']);
	$db->misc($sql);
	
	$msg = '<p class="warnText">'.$lang['admin']['misc_clear_logs_empty'].'</p>';
	
} else if ($_GET['clearSession'] == 1) {
	$sql = sprintf('TRUNCATE TABLE %sImeiUnlock_admin_sessions', $glob['dbprefix']);
	$db->misc($sql);
	
	$msg = '<p class="warnText">'.$lang['admin']['misc_clear_sessions_empty'].'</p>';
}

require $glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php";

?>
<p style="margin-bottom:10px;" class="pageTitle"><?php echo $lang['admin']['misc_title_recount']; ?></p>
<?php if (isset($msg)) echo msg($msg); ?>
<table width="100%" border="1" cellspacing="1" cellpadding="3" class="mainTable mainTable4">
  <tr>
	<td class="tdTitle" colspan="2"><?php echo $lang['admin']['misc_operation']; ?></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_img_db_success']; ?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;filemanager=1');return document.returnValue" /></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_recalculate_upload_size']; ?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;uploadSize=1');return document.returnValue" /></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_recalc_cat_prod_count']; ?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;catCount=1');return document.returnValue" /></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_rebuild_no_cust_orders']; ?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;orderCount=1');return document.returnValue" /></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_reset_prod_views']; ?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;prodViews=1');return document.returnValue" /></td>
  </tr>
  <!--
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_del_orphaned_thumbs']; ?></td>
	<td class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;thumbs=1');return document.returnValue" /></td>
  </tr>
  -->
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_clear_search_cache']; ?><strong></strong></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;clearSearch=1');return document.returnValue" /></td>
  </tr>
  
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_clear_logs']; ?><strong></strong></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;clearLogs=1');return document.returnValue" /></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_clear_sessions']; ?><strong></strong></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;clearSession=1');return document.returnValue" /></td>
  </tr>
<?php
if ($config['cache'] ==1) {
?>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_clear_sql_cache'];?></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;clearCache=1');return document.returnValue" /></td>
  </tr>
<?php
}
?>
<tr>
	<td class="tdText"><?php echo $lang['admin']['misc_empty_translogs']; ?><strong></strong></td>
	<td width="140px" align="center" class="tdText"><input name="button" type="button" value="<?php echo $lang['admin_common']['update'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;emptyTransLogs=1');return document.returnValue" /></td>
  </tr>
<?php 
if (isset($config['lkv']) && $config['lkv']>0 && preg_match('#^([0-9]{6})+[-]+([0-9])+[-]+([0-9]{4})$#',$config['lk'])) { ?>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['misc_remove_copy_key'];?></td>
	<td class="tdText">
	<input name="button" type="button" value="<?php echo $lang['admin_common']['remove'];?>" class="submit" onclick="goToURL('parent','<?php echo $glob['adminFile']; ?>?_g=maintenance/rebuild&amp;removeKey=1');return document.returnValue" />
	</td>
  </tr>
<?php
}
?>
</table>