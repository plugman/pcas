<?php
/*
+--------------------------------------------------------------------------
|	giftCertificates.inc.php
|   ========================================
|	Gift Certificate Settings	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_products.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."currencyVars.inc.php");
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['gc'])){
	$cache = new cache();
	$cache->clearCache();
	$gc = fetchDbConfig('gift_certs');
	$msg = writeDbConf($_POST['gc'], 'gift_certs', $_POST['gc']);
}
$gc = fetchDbConfig('gift_certs');

?>
<p class="pageTitle" style="margin-bottom:10px;"><?php echo $lang['admin']['gc_title'] ;?></p>
<?php 
if(isset($msg)){ 
	echo msg($msg); 
}
?>
<div class="headingBlackbg"><?php echo $lang['admin']['gc_title'] ;?></div>
<form id="gc" name="gc" method="post" action="<?php echo $glob['adminFile']; ?>?_g=products/giftCertificates">
	
	<table width="100%"  cellspacing="0" cellpadding="0" class="mainTable">
	  
	  <tr>
		<td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_title'] ;?></strong></td>
		<td class="tdText">
        <div class="inputbox">
		<span class="bgleft"></span>
    	<select name="gc[status]">
			<option value="0" <?php if($gc['status']==0) { echo "selected='selected'"; }?>>Disabled</option>
			<option value="1" <?php if($gc['status']==1) { echo "selected='selected'"; }?>>Enabled</option>
		</select>	
	   <span class="bgright"></span>
	   </div>
		</td>
	  </tr>
  	  <tr>
		<td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['products_tax_inclusive'] ;?></strong></td>
		<td class="tdText">
        
        <input name="gc[tax]" type="checkbox" value="1" <?php if ($gc['tax']) { echo 'checked="checked"'; } ?> /></td>
	  </tr>

	  <tr>
		<td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_max_amount'] ;?></strong>  </td>
		<td class="tdText">
        <div class="inputbox">
		<span class="bgleft"></span>
    <input name="gc[max]" type="text"  value="<?php echo $gc['max']; ?>" maxlength="10" />
	   <span class="bgright"></span>
	   </div>
       </td>
	  </tr>
	  <tr>
		<td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_min_amount'] ;?></strong>  </td>
		<td class="tdText">
         <div class="inputbox">
		<span class="bgleft"></span>
 <input name="gc[min]" type="text" value="<?php echo $gc['min']; ?>" maxlength="10" />
	   <span class="bgright"></span>
	   </div>
       </td>
	  </tr>

	  <tr>
	    <td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_delivery'] ;?></strong></td>
	    <td class="tdText">
          <div class="inputbox">
		<span class="bgleft"></span>
 <select name="gc[delivery]">
          <option value="1" <?php if($gc['delivery']==1) { echo "selected='selected'"; }?>><?php echo $lang['admin']['gc_email_only']; ?></option>
          <option value="2" <?php if($gc['delivery']==2) { echo "selected='selected'"; }?>><?php echo $lang['admin']['gc_paper_only']; ?></option>
		  <option value="3" <?php if($gc['delivery']==3) { echo "selected='selected'"; }?>><?php echo $lang['admin']['gc_email_and_paper']; ?></option>
        </select>
	   <span class="bgright"></span>
	   </div>
       </td>
      </tr>
	  
	  <tr>
	    <td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_paper_weight'] ;?></strong><br />
      

</td>
	    <td class="tdText">
          <div class="inputbox">
		<span class="bgleft"></span>
  <input name="gc[weight]" type="text" value="<?php echo $gc['weight']; ?>"  />
	   <span class="bgright"></span>
	   </div>
         <span class="left sm" style="width:280px;"><?php echo $lang['admin']['gc_paper_weight_desc'] ;?></span>
       </td>
       
      </tr>
	  
	  <tr>
	    <td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_product_code'] ;?></strong></td>
	    <td class="tdText">
         <div class="inputbox">
		<span class="bgleft"></span>
  <input name="gc[productCode]" type="text" value="<?php echo $gc['productCode']; ?>"  />
	   <span class="bgright"></span>
	   </div>
       </td>
      </tr>
	  
	  <tr>
	    <td class="tdText" width="25%" align="right"><strong><?php echo $lang['admin']['gc_tax_code'] ;?></strong></td>
	    <td class="tdText">
         <div class="inputbox">
		<span class="bgleft"></span>
 <select name="gc[taxType]">
    <?php
	$taxTypes = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_taxes"); 
	 for($i=0; $i<count($taxTypes);$i++){ ?>
	<option value="<?php echo $taxTypes[$i]['id']; ?>" <?php if($taxTypes[$i]['id'] == $gc['taxType']) echo "selected='selected'"; ?>><?php echo $taxTypes[$i]['taxName'];  if (! $config_tax_mod['status']) echo "(".$taxTypes[$i]['percent']."%)"; ?></option>
	<?php } ?>
	</select>
	   <span class="bgright"></span>
	   </div>
		
	</td>
      </tr>
	  <tr>
      <td class="tdText" colspan="2">
      	<div class="seperator"></div>
      </td>
      </tr>
	  <tr>
		<td class="tdText">&nbsp;</td>
		<td class="tdText">
		  <input name="Submit" type="submit" class="submit" value="<?php echo $lang['admin_common']['update'];?>" />		</td>
	  </tr>
	</table>
</form>