<?php
/*
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Configure United States Postal Service
+--------------------------------------------------------------------------
*/


if(!defined('CC_INI_SET')){ die("Access Denied"); }

permission("shipping","read",$halt=TRUE);

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($_POST['module'])){
	require CC_ROOT_DIR.CC_DS.'modules'.CC_DS.'status.inc.php';	
	$cache = new cache("config.".$moduleName);
	$cache->clearCache();
	//$module = fetchDbConfig($moduleName); // Uncomment this is you wish to merge old config with new
	$module = array(); // Comment this out if you don't want the old config to merge with new
	$msg = writeDbConf($_POST['module'], $moduleName, $module);
}
$module = fetchDbConfig($moduleName);
?>




<p><a href="http://www.usps.com/"><img src="modules/<?php echo $moduleType; ?>/<?php echo $moduleName; ?>/admin/logo.gif" alt="" border="0" title="" /></a></p>
<?php 
if(isset($msg))
{ 
	echo msg($msg); 
}
?>

<form action="<?php echo $glob['adminFile']; ?>?_g=<?php echo $_GET['_g']; ?>&amp;module=<?php echo $_GET['module']; ?>" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3" class="mainTable">
  <tr>
    <td colspan="10" class="tdTitle">Configuration Settings </td>
  </tr>
  <tr>
    <td align="left" class="tdText"><strong>Status:</strong></td>
    <td class="tdText" colspan="9">
		<select name="module[status]">
		<option value="1" <?php if($module['status']==1) echo "selected='selected'"; ?>>Enabled</option>
		<option value="0" <?php if($module['status']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select>
    </td> 	
    </tr>
<!-- ### Removed by Sir William Test Mode No Longer Necessary ###
  <tr>
  <td align="left" class="tdText"><strong>Test Mode:</strong></td>
    <td class="tdText"><select name="module[test]">
      <option value="1" <!?php if($module['test']==1) echo "selected='selected'"; ?>>Enabled</option>
      <option value="0" <!?php if($module['test']==0) echo "selected='selected'"; ?>>Disabled</option>
    </select></td>
    <td class="tdText">&nbsp;</td>
    <td class="tdText">&nbsp;</td>
    <td class="tdText">&nbsp;</td>
    </tr>
-->
    <tr>
      <td  class="tdText"><strong>Debugging: </strong></td>
      <td  class="tdText" colspan="9"><select name="module[debug]">
        <option value="1" <?php if($module['debug']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['debug']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
    </tr>
    <tr>
      <td  class="tdText"><strong>Domestic Services:</strong></td>
      <td  class="tdText"><strong>Status</strong></td>
      <td  class="tdText"><strong>Package Size</strong></td>
      <td  class="tdText"><strong>Container/Mail Types</strong></td>
      <td  class="tdText"><strong>Weight Limit</strong></td>
      <td  class="tdText"><strong>Machinable</strong></td>
      <td  class="tdText"><strong>Length<br />
      </strong>(Rectangular &amp; Non Rectangular)</td>
      <td  class="tdText"><strong>Width<br />
      </strong>(Rectangular &amp; Non Rectangular)</td>
      <td  class="tdText"><strong>Height<br />
      </strong>(Rectangular &amp; Non Rectangular)</td>
      <td  class="tdText"><strong>Girth <br />
      </strong>(Non Rectangular Only) </td>
    </tr>
    <tr>
      <td  class="tdText">Express  Mail  </td>
      <td valign="top"  class="tdText">
	  <select name="module[serviceExpress]">
        <option value="1" <?php if($module['serviceExpress']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceExpress']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	  </td>
      <td valign="top"  class="tdText">
        <select name="module[expressSize]">
        <option value="REGULAR" <?php if($module['expressSize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
		<option value="LARGE" <?php if($module['expressSize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
		</select>      </td>
      <td valign="top"  class="tdText">
	
	  <select name="module[expressContainer]">
        <option value="Flat Rate Envelope" <?php if($module['expressContainer']=="Flat Rate Envelope") echo "selected='selected'"; ?>>Flat Rate Envelope</option>
		<option value="Variable" <?php if($module['expressContainer']=="Variable") echo "selected='selected'"; ?>>Variable</option>
      </select></td>
      <td valign="top"  class="tdText">70 lbs.</td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td  class="tdText">First Class</td>
      <td valign="top"  class="tdText">
	  <select name="module[serviceFirstClass]">
        <option value="1" <?php if($module['serviceFirstClass']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceFirstClass']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	  </td>
      <td valign="top"  class="tdText">
	  <!--
	  <select name="module[FirstClassSize]">
        <option value="REGULAR" <?php if($module['FirstClassSize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['FirstClassSize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
      </select>
	  -->
	  N/A</td>
      <td valign="top"  class="tdText">
	  
	  <select name="module[FirstClassMailType]">
        <option value="LETTER" <?php if($module['FirstClassMailType']=="LETTER") echo "selected='selected'"; ?>>Letter</option>
		<option value="FLAT" <?php if($module['FirstClassMailType']=="FLAT") echo "selected='selected'"; ?>>Flat</option>
		<option value="PARCEL" <?php if($module['FirstClassMailType']=="PARCEL") echo "selected='selected'"; ?>>Parcel</option>
      </select>	  </td>
      <td valign="top"  class="tdText">13 oz.</td>
      <td valign="top"  class="tdText">
	  <select name="module[FirstClassMachineable]">
        <option value="TRUE" <?php if($module['FirstClassMachineable']=="TRUE") echo "selected='selected'"; ?>>True</option>
        <option value="FALSE" <?php if($module['FirstClassMachineable']=="FALSE") echo "selected='selected'"; ?>>False</option>
      </select></td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td  class="tdText">Priority Mail </td>
      <td valign="top"  class="tdText">
	  <select name="module[servicePriority]">
        <option value="1" <?php if($module['servicePriority']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['servicePriority']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	  </td>
      <td valign="top"  class="tdText"><select name="module[prioritySize]">
        <option value="REGULAR" <?php if($module['prioritySize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['prioritySize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
      </select></td>
      <td valign="top"  class="tdText">
	
	  <select name="module[priorityContainer]">
	  	
		<option value="Variable" <?php if($module['priorityContainer']=="Variable") echo "selected='selected'"; ?>>Variable Rate</option>
		<!--
		<option value="RECTANGULAR" <?php if($module['priorityContainer']=="RECTANGULAR") echo "selected='selected'"; ?>>Rectangular</option>
		<option value="NONRECTANGULAR" <?php if($module['priorityContainer']=="NONRECTANGULAR") echo "selected='selected'"; ?>>Non Rectangular</option>
        -->
		<option value="Flat Rate Envelope" <?php if($module['priorityContainer']=="Flat Rate Envelope") echo "selected='selected'"; ?> >Flat Rate Envelope</option>
		<option value="Flat Rate Box" <?php if($module['priorityContainer']=="Flat Rate Box") echo "selected='selected'"; ?>>Flat Rate Box</option>
      </select>	  </td>
      <td valign="top"  class="tdText">70 lbs.</td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText"><input name="module[priorityLength]" type="text" size="5" value="<?php echo $module['priorityLength'];?>" /></td>
      <td valign="top"  class="tdText"><input name="module[priorityWidth]" type="text" size="5" value="<?php echo $module['priorityWidth'];?>" /></td>
      <td valign="top"  class="tdText"><input name="module[priorityHeight]" type="text" size="5" value="<?php echo $module['priorityHeight'];?>" /></td>
      <td valign="top"  class="tdText"><input name="module[priorityGirth]" type="text" size="5" value="<?php echo $module['priorityGirth'];?>" /></td>
    </tr>
    <tr>
      <td  class="tdText">Parcel Post </td>
      <td valign="top"  class="tdText">
	  <select name="module[serviceParcel]">
        <option value="1" <?php if($module['serviceParcel']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceParcel']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	  </td>
      <td valign="top"  class="tdText"><select name="module[parcelSize]">
        <option value="REGULAR" <?php if($module['parcelSize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['parcelSize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
		<option value="OVERSIZE" <?php if($module['parcelSize']=="OVERSIZE") echo "selected='selected'"; ?>>Oversize</option>
      </select></td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">70 lbs.</td>
      <td valign="top"  class="tdText"><select name="module[parcelMachineable]">
        <option value="TRUE" <?php if($module['parcelMachineable']=="TRUE") echo "selected='selected'"; ?>>True</option>
        <option value="FALSE" <?php if($module['parcelMachineable']=="FALSE") echo "selected='selected'"; ?>>False</option>
      </select></td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td  class="tdText">BPM (Bound Printed Matter)</td>
      <td valign="top"  class="tdText">
	  <select name="module[serviceBPM]">
        <option value="1" <?php if($module['serviceBPM']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceBPM']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	  </td>
      <td valign="top"  class="tdText"><select name="module[BPMSize]">
         <option value="REGULAR" <?php if($module['BPMSize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['BPMSize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
      </select></td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">15 lbs.</td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td  class="tdText">Library</td>
      <td valign="top"  class="tdText">
	  <select name="module[serviceLibrary]">
        <option value="1" <?php if($module['serviceLibrary']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceLibrary']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select>	 </td>
      <td valign="top"  class="tdText"><select name="module[LibrarySize]">
        <option value="REGULAR" <?php if($module['LibrarySize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['LibrarySize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
      </select></td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">70 lbs.</td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    <tr>
      <td  class="tdText">Media</td>
      <td valign="top"  class="tdText"><select name="module[serviceMedia]">
        <option value="1" <?php if($module['serviceMedia']==1) echo "selected='selected'"; ?>>Enabled</option>
        <option value="0" <?php if($module['serviceMedia']==0) echo "selected='selected'"; ?>>Disabled</option>
      </select></td>
      <td valign="top"  class="tdText"><select name="module[MediaSize]">
        <option value="REGULAR" <?php if($module['MediaSize']=="REGULAR") echo "selected='selected'"; ?>>Regular</option>
        <option value="LARGE" <?php if($module['MediaSize']=="LARGE") echo "selected='selected'"; ?>>Large</option>
      </select></td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">70 lbs.</td>
      <td valign="top"  class="tdText">N/A</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
      <td valign="top"  class="tdText">&nbsp;</td>
    </tr>
    
    <tr>
    	<td colspan='3'><strong>International Services:</strong></td> <td colspan='8'>&nbsp;  </td>
    </tr>
    <tr>
   		<td colspan='3'>Global Express Guaranteed</td> <td colspan='10'> <input name='module[GlobalExpressGuaranteed]' type='checkbox' value='1' <?php if($module['GlobalExpressGuaranteed']==true) echo 'checked=\'checked\'';?> /> </td>
   	</tr>
    <tr>
    	<td colspan='3'>Global Express Guaranteed Non-Document Rectangular</td> <td colspan='8'> <input name='module[GlobalExpressGuaranteedNonDocumentRectangular]' type='checkbox' value='1' <?php if($module['GlobalExpressGuaranteedNonDocumentRectangular']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Global Express Guaranteed Non-Document Non-Rectangular</td> <td colspan='8'> <input name='module[GlobalExpressGuaranteedNonDocumentNonRectangular]' type='checkbox' value='1' <?php if($module['GlobalExpressGuaranteedNonDocumentNonRectangular']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>USPS GXG Envelopes</td> <td colspan='8'> <input name='module[USPSGXGEnvelopes]' type='checkbox' value='1' <?php if($module['USPSGXGEnvelopes']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Express Mail International (EMS)</td> <td colspan='8'> <input name='module[ExpressMailInternationalEMS]' type='checkbox' value='1' <?php if($module['ExpressMailInternationalEMS']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
   		<td colspan='3'>Express Mail International (EMS) Flat-Rate Envelope</td> <td colspan='8'> <input name='module[ExpressMailInternationalEMSFlatRateEnvelope]' type='checkbox' value='1' <?php if($module['ExpressMailInternationalEMSFlatRateEnvelope']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Priority Mail International</td> <td colspan='8'> <input name='module[PriorityMailInternational]' type='checkbox' value='1' <?php if($module['PriorityMailInternational']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Priority Mail International Flat-Rate Envelope</td> <td colspan='8'> <input name='module[PriorityMailInternationalFlatRateEnvelope]' type='checkbox' value='1' <?php if($module['PriorityMailInternationalFlatRateEnvelope']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Priority Mail International Flat-Rate Box</td> <td colspan='8'> <input name='module[PriorityMailInternationalFlatRateBox]' type='checkbox' value='1' <?php if($module['PriorityMailInternationalFlatRateBox']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>Priority Mail International Large Flat-Rate Box</td> <td colspan='8'> <input name='module[PriorityMailInternationalLargeFlatRateBox]' type='checkbox' value='1' <?php if($module['PriorityMailInternationalLargeFlatRateBox']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>First Class Mail International Large Envelope</td> <td colspan='8'> <input name='module[FirstClassMailInternationalLargeEnvelope]' type='checkbox' value='1' <?php if($module['FirstClassMailInternationalLargeEnvelope']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>
    <tr>
    	<td colspan='3'>First Class Mail International Package</td> <td colspan='8'> <input name='module[FirstClassMailInternationalPackage]' type='checkbox' value='1' <?php if($module['FirstClassMailInternationalPackage']==true) echo 'checked=\'checked\'';?> /> </td>
    </tr>    
	<tr>
	  <td align="left" class="tdText"><strong>Handling Fee:</strong></td>
	  <td class="tdText" colspan="9"><input type="text" name="module[handling]" value="<?php echo $module['handling']; ?>" class="textbox" size="10" /></td>
	</tr>
    <tr>
		<td  class="tdText"><strong>Origin Zip Code:</strong><br /></td>
		<td valign="top"  class="tdText" colspan="9">
		  <input type="text" name="module[ziporigin]" value="<?php echo $module['ziporigin']; ?>" class="textbox" />    </td>
    </tr>
  <tr>
    <td  class="tdText"><strong>USPS Username:</strong><br /></td>
    <td valign="top"  class="tdText" colspan="9">
      <input type="text" name="module[username]" value="<?php echo $module['username']; ?>" class="textbox" />    </td>
  </tr>
  <!--
  <tr><td align="left" class="tdText"><strong>USPS Password:</strong></td>
    <td class="tdText"><input type="text" name="module[password]" value="<?php echo $module['password']; ?>" class="textbox" /></td>
    <td class="tdText" colspan="8">&nbsp;</td>
  </tr>
  -->
  <tr>
    <td align="right" class="tdText">&nbsp;<input type="hidden" name="module[password]" value="none" /></td>
    <td class="tdText" colspan="9"><input type="submit" class="submit" value="Edit Config" /></td>
  </tr>
</table>
</form>
<p><strong>Important Notes:</strong><br />
To get this shipping method to work you must first sign up for an account here <a href="http://www.usps.com/webtools/" class="txtLink">http://www.usps.com/webtools/</a>.</p>
<p>After you have registered please email <a href="mailto:icustomercare@usps.com" class="txtLink">icustomercare@usps.com</a> or
call USPS on <strong>1-800-344-7779</strong> (7:00 AM to 11:00 PM EST daily) and ask them to activate your account so that it can access the "production server".</p>