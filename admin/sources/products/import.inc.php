<?php
/*
+--------------------------------------------------------------------------
|	import.inc.php
|   ========================================
|	Import Catalogue	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_products.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

$uploadComplete = false;

$delimiter = (isset($_POST['Delimiter'])) ? $_POST['Delimiter'] : ',';
if ($delimiter == 'tab') $delimiter = "\t";

if (isset($_POST['ProcessImport']) && file_exists(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'importdata')) {
	## Make sure the process doesn't get stopped halfway
	@ignore_user_abort(true);
	@ini_set('max_execution_time', '0');
	@set_time_limit(0);
	@ini_set('auto_detect_line_endings', true);
	
	## Look for the Import category
	$catSearch = sprintf("SELECT * FROM %sImeiUnlock_category WHERE cat_name = 'Imported Products'", $glob['dbprefix']);
	$result = $db->select($catSearch, 1);
	
	if ($result) {
		## Import category exists, define a variable with it's cat_id
		$cat_id = $result[0]['cat_id'];
	} else {
		## Category doesn't exist - let's create it
		$record = array(
			'cat_name'	=> $db->mySQLSafe('Imported Products'),
			'cat_desc'	=> $db->mySQLSafe('##HIDDEN##'),
			'hide'		=> 1,
		);
		$db->insert($glob['dbprefix'].'ImeiUnlock_category', $record);
		## Define the cat_id
		$cat_id = $db->insertid();
	}
	
	if ($_POST['ImportMethod'] == 'replace') {
		$db->misc('TRUNCATE TABLE '.$glob['dbprefix'].'ImeiUnlock_inventory;');
		$db->misc('TRUNCATE TABLE '.$glob['dbprefix'].'ImeiUnlock_cats_idx;');
	}
	
	## Open the import file
	$fp = fopen(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'importdata', 'rb');
	$i=0;
	while (($data = fgetcsv($fp, 1000, $delimiter)) !== false) {
		if ($i==0 && $_POST['ColumnHeaders']=='1') {
			## skip this first row
		} else {
			foreach ($_POST['column'] as $key => $field) {
				if (!empty($field) && $field != $data[$key]) {
					
					## dirty pricefix hack - need to clean it up a bit more
					if ($field == 'price') $data[$key] = preg_replace('#[^0-9\.]#i', '', $data[$key]);
					
					if ($field == 'useStockLevel') {
						$useStockDefined = true;
					}
					
					if ($field == 'cat_id') {
						if (empty($data[$key])) break;
						$cat_id_defined = true;
						$current_cat_id = $data[$key];
					}
					
					$fields[] = $field;
					$values[] = sprintf("'%s'", addslashes($data[$key]));
				}
			}
			
			## Set the default Category ID for imported products if not defined in import
			if (!$cat_id_defined) {
				$fields[] = 'cat_id';
				$values[] = "'".$cat_id."'";
				$current_cat_id = $cat_id;
			}
			
			##  If use stock level is not defined set it to zero
			if(!$useStockDefined) {
				$fields[] = 'useStockLevel';
				$values[] = '0';
			}
			
			$query = sprintf("INSERT INTO %sImeiUnlock_inventory (%s) VALUES (%s);", $glob['dbprefix'], implode(',', $fields), implode(',', $values));
			$db->misc($query);
			
			## Add index to cats_idx table
			$query = sprintf("INSERT INTO %sImeiUnlock_cats_idx (`cat_id`,`productId`) VALUES ('%d', '%d');",$glob['dbprefix'], $current_cat_id, $db->insertid());
			$db->misc($query);
			
			## Update category count for number of products in that category
			$db->categoryNos($current_cat_id, "+");
			
			unset($fields, $values, $cat_id_defined, $current_cat_id);
		}
		$i++;
	}
	
	## Clear Cache
	if ($config['cache']) {
		$cache = new cache();
		$cache->clearCache();
	}
	
	@unlink(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'importdata');
	$msg = $lang['admin']['products_import_complete'];
	$uploadComplete = true;
}

?>
<p class="pageTitle"><?php echo $lang['admin']['products_import_cat']?></p>
<?php 
if (isset($msg)) echo '<p class="tdText">'.msg($msg)."</p>";

if (isset($_POST['Upload']) && !empty($_FILES['ImportData']['name']) && $_FILES['ImportData']['size'] > 0 && preg_match('#\.(csv|txt)$#i', $_FILES['ImportData']['name'])) {
	echo '<p class="pageTitle">'. $lang['admin']['products_import_step_two'] .'</p>';
	
	echo "<p class=\"tdText\">". $lang['admin']['products_import_step_two_desc']."</p>";

	if (is_uploaded_file($_FILES['ImportData']['tmp_name'])) {
		@move_uploaded_file($_FILES['ImportData']['tmp_name'], CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'importdata');
		$fp = fopen(CC_ROOT_DIR.CC_DS.'cache'.CC_DS.'importdata', 'rb');
		$headers = fgetcsv($fp, 1000, $delimiter);
		@fclose($fp);
	}

	$cc_inventory_fields = array(
		'productCode'	=> $lang['admin']['products_import_field_productcode'],
		'name'			=> $lang['admin']['products_import_field_productname'],
		'description'	=> $lang['admin']['products_import_field_description'],
		'price'			=> $lang['admin']['products_import_field_price'],
		'sale_price'	=> $lang['admin']['products_import_field_saleprice'],
		'stock_level'	=> $lang['admin']['products_import_field_stockcount'],
		'useStockLevel'	=> $lang['admin']['products_import_field_usestocklevel'],
		'prodWeight'	=> $lang['admin']['products_import_field_weight'],
		'image'			=> $lang['admin']['products_import_field_image'],
		'cat_id'		=> $lang['admin']['products_import_fleid_cat_id']
	);
	
	echo '<form method="post">';
	if(is_array($headers)) {
		foreach ($headers as $key => $header) {
			foreach ($cc_inventory_fields as $field => $name) {
				$selected = ($field == $header) ? ' selected="selected"' : '';
				$fieldList[] = sprintf('<option value="%s"%s>%s</option>', $field, $selected, $name);
			}
			echo sprintf('<div style="clear: both; width: 500px; margin-top: 5px;" class="tdText"><span style="float: right;"><select name="column[%d]" class="textbox"><option value=""></option>%s</select></span> %02d. <strong>%s</strong></div>', $key++, implode("\n",$fieldList), $key, $header);
			unset($fieldList);
		}
	}
	echo sprintf('<input type="hidden" name="ImportMethod" value="%s" />', $_POST['ImportMethod']);
	echo sprintf('<input type="hidden" name="ColumnHeaders" value="%d" />', $_POST['ColumnHeaders']);
	echo sprintf('<input type="hidden" name="Delimiter" value="%s" />', $_POST['Delimiter']);
	
	echo '<p><input type="submit" name="ProcessImport" class="submit" value="'.$lang['admin']['products_import_go'].'" /></p></form>';
} else {
	if ($uploadComplete != true) {
?>
<form method="post" enctype="multipart/form-data">
<p class="pageTitle"><?php echo $lang['admin']['products_import_step_one']?></p>
<table>
  <tr>
	<td></td>
	<td></td>
  </tr>
  <tr>
	<td class="tdText"><?php echo $lang['admin']['products_import_file'];?></td>
	<td><input type="file" id="ImportData" name="ImportData" class="textbox" /></td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['products_delimiter'];?></td>
	<td class="tdText">
	  <select name="Delimiter" id="Delimiter" class="textbox">
		<option value=","><?php echo $lang['admin']['products_delimiter_comma'];?></option>
		<option value=";"><?php echo $lang['admin']['products_delimiter_semicolon'];?></option>
		<option value="tab"><?php echo $lang['admin']['products_delimiter_tab'];?></option>
		<option value="|"><?php echo $lang['admin']['products_delimiter_pipe'];?></option>
	  </select>
	</td>
  </tr>
  <tr>
    <td class="tdText"><?php echo $lang['admin']['products_delimiter'];?></td>
	<td class="tdText">
	  <select name="ImportMethod" id="ImportMethod" class="textbox">
		<option value="append" selected="selected">Append the import to the existing data</option>
		<option value="replace">Erase and replace the existing data</option>
	  </select>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td class="tdText"><input type="checkbox" id="ColumnHeaders" name="ColumnHeaders" value="1" /> <?php echo $lang['admin']['products_import_headers'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
	<td><input type="submit" id="Upload" name="Upload" class="submit" value="<?php echo $lang['admin']['products_import_upload'];?>" /></td>
  </tr>
</table>
</form>
<hr />
<p class="tdText">
<?php echo $lang['admin']['products_import_basic_instructions'];?>
</p>
<?php } 
} ?>