<!-- BEGIN: form -->
{JS_COUNTY_OPTIONS}
<!-- BEGIN: error -->
<p class="txtError" style="text-align: left;">{LANG_ERROR}</p>
<!-- END: error -->
<p>{VAL_AMOUNT_DUE}</p>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr align="left">
		<td colspan="4" class="tdcartTitle"><strong>{LANG_CC_INFO_TITLE}</strong></td>
	</tr>
	<tr align="left">
		<td><strong>{LANG_FIRST_NAME}</strong></td>
	    <td><input type="text" name="firstName" value="{VAL_FIRST_NAME}" class="textbox" /></td>
		<td><strong>{LANG_LAST_NAME}</strong></td>
	    <td><input type="text" name="lastName" value="{VAL_LAST_NAME}" class="textbox" /></td>
	</tr>
	<tr align="left">
		<td align="left"><strong>{LANG_CARD_NUMBER}</strong></td>
		<td align="left"><input type="text" name="cardNumber" value="" size="19" maxlength="19" class="textbox" /></td>
	    <td><strong>{LANG_CARD_TYPE}</strong></td>
        <td><select name="cardType" class="textbox">
          <!-- BEGIN: repeat_cards -->
          <option value="{VAL_CARD_TYPE}" {CARD_SELECTED}>{VAL_CARD_NAME}</option>
          <!-- END: repeat_cards -->
        </select></td>
	</tr>
  
<!-- BEGIN: issue_info -->
	</tr>
		<tr align="left">
		  <td><strong>{LANG_ISSUE_NO}</strong>    
		  <td><input type="text" name="issueNo" value="" size="2" maxlength="4" class="textbox" style="text-align: center" /></td>
  	</tr>
	<tr align="left">
		<td><strong>{LANG_ISSUE_DATE}</strong>
    <td>
		<select name="issueMonth" class="textbox">
			<!-- BEGIN: issue_months -->
			<option value="{VAL_ISSUE_MONTH}" {ISSUE_MONTHS_SELECTED}>{VAL_ISSUE_MONTH}</option>
			<!-- END: issue_months -->
		</select>
		/   
		<select name="issueYear" class="textbox">
		  <!-- BEGIN: issue_years -->
		  <option value="{VAL_ISSUE_YEAR}" {ISSUE_YEARS_SELECTED}>{VAL_ISSUE_YEAR}</option>
		  <!-- END: issue_years -->
		</select>
	</td>
    
  <!-- END: issue_info -->
  
  <tr align="left">
		<td align="left"><strong>{LANG_EXPIRES}</strong>
	  
	  <td align="left">
	  <select name="expirationMonth" class="textbox">
        <!-- BEGIN: expiration_months -->
        <option value="{VAL_EXPIRE_MONTH}" {EXPIRE_MONTHS_SELECTED}>{VAL_EXPIRE_MONTH}</option>
        <!-- END: expiration_months -->
      </select>
/
<select name="expirationYear" class="textbox">
  <!-- BEGIN: expiration_years -->
  <option value="{VAL_EXPIRE_YEAR}" {EXPIRE_YEARS_SELECTED}>{VAL_EXPIRE_YEAR}</option>
  <!-- END: expiration_years -->
</select></td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr align="left">
	  <td align="left"><strong>{LANG_SECURITY_CODE}</strong>    
	  <td align="left"><input type="text" name="cvc2" value="" size="4" maxlength="4" class="textbox" style="text-align: center" /> 
      <span onmouseover="findObj('cvv-img').src='images/general/cvv.gif'" onmouseout="findObj('cvv-img').src='images/general/px.gif'" style="cursor: pointer; cursor: hand;">&nbsp;?&nbsp;</span> <div id="cvv" style="position:absolute; width: 1px; height: 1px;"><img src="images/general/px.gif" border="0" id="cvv-img" alt="" /></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
  </tr>
	<tr>
	  <td colspan="6" align="right" valign="bottom">&nbsp;</td>
	</tr>
	<tr align="left">
	  <td colspan="6" class="tdcartTitle"><strong>{LANG_CUST_INFO_TITLE}</strong></td>
	</tr>				
	<tr align="left">
		<td><strong>{LANG_EMAIL}</strong>
	  <td colspan="3"><input type="text" name="emailAddress" value="{VAL_EMAIL_ADDRESS}" size="50" class="textbox" /></td>
	</tr>
	<tr align="left">
		<td><strong>{LANG_ADDRESS}</strong></td>
	  <td colspan="3"><input type="text" name="addr1" value="{VAL_ADD_1}" size="50" class="textbox" /></td>
	</tr>
	<tr align="left">
		<td>&nbsp;</td>
	  <td colspan="3"><input type="text" name="addr2" value="{VAL_ADD_2}" size="50" class="textbox" /> {LANG_OPTIONAL}</td>
	</tr>
	<tr align="left">
		<td>
		<strong>{LANG_CITY}</strong>		</td>
		<td colspan="3">
		<input type="text" name="city" value="{VAL_CITY}" class="textbox" />	  </td>
  </tr>
		
		<tr align="left">
		<td>
		<strong>{LANG_STATE}</strong>		</td>
		<td colspan="3">
		<div id="divCountyText" {VAL_COUNTY_TXT_STYLE}> 
		<input name="county" type="text" class="textbox" id="county" value="{VAL_DEL_COUNTY}" maxlength="50" />
		</div>
		
		<div id="divCountySelect" {VAL_COUNTY_SEL_STYLE}> 
		<select name="county_sel" id="county_sel" class="textbox">
		<!-- BEGIN: county_opts -->
		<option value="{VAL_DEL_COUNTY_ID}" {COUNTY_SELECTED}>{VAL_DEL_COUNTY_NAME}</option>
		<!-- END: county_opts -->
		</select>
		</div>
		<input name="which_field" type="hidden" id="which_field" value="{VAL_COUNTY_WHICH_FIELD}" />		</td>
		</tr>
		
		<tr align="left">
		<td>
		<strong>{LANG_ZIPCODE}</strong>		</td>
		<td colspan="3">
		<input type="text" name="postalCode" value="{VAL_POST_CODE}" size="10" maxlength="10" class="textbox" />	  </td>
	</tr>
	<tr align="left">
		<td><strong>{LANG_COUNTRY}</strong>
		<td colspan="3">
			<select name="country" id="country" class="textbox" onchange="updateCounty(this.form);">
			<!-- BEGIN: country_opts -->
			<option value="{VAL_COUNTRY_ID}" {COUNTRY_SELECTED}>{VAL_COUNTRY_NAME}</option>
			<!-- END: country_opts -->
  			</select>	 
			 </td>
	</tr>
</table>
<input type="hidden" name="gateway" value="{VAL_GATEWAY}" />
<!-- END: form -->

<!-- BEGIN: 3ds -->
<center>
  <iframe style="width:390px; height:400px; margin: 0px auto; border: 1px solid #CCCCCC;" scrolling="auto" src="{STORE_URL}/modules/altCheckout/PayPal_Pro/3dsecure.php"></iframe>
</center>
<br />
<!-- END: 3ds -->