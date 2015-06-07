<!-- BEGIN: profile -->
{JS_COUNTY_OPTIONS}
<div class="boxContent">

	<span class="txtContentTitle">{LANG_PERSONAL_INFO_TITLE}</span>
	
	<!-- BEGIN: session_true -->
	<!-- BEGIN: no_error -->
	<p>{LANG_PROFILE_DESC}</p>
	<!-- END: no_error -->
	<!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	
		<form action="index.php?_a=profile{VAL_EXTRA_GET}" target="_self" method="post">
			<table border="0" cellspacing="0" cellpadding="3" align="center">
				<tr>
					<td align="right"><strong>{TXT_TITLE}</strong></td>
					<td><input name="title" type="text" class="textbox" id="title" value="{VAL_TITLE}" size="7" maxlength="30" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_FIRST_NAME}</strong></td>
					<td><input name="firstName" type="text" class="textbox" id="firstName" value="{VAL_FIRST_NAME}" maxlength="100" /> *</td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_LAST_NAME}</strong></td>
					<td><input name="lastName" type="text" class="textbox" id="lastName" value="{VAL_LAST_NAME}" maxlength="100" /> *</td>
				</tr>
				<tr>
					<td align="right"><strong>{TXT_EMAIL}</strong></td>
					<td><input name="email" type="text" class="textbox" id="email" value="{VAL_EMAIL}" maxlength="100" /> *</td>
				</tr>
				<tr>
				  <td align="right" valign="top"><strong>{TXT_COMPANY_NAME}</strong></td>
				  <td><input name="companyName" type="text" class="textbox" id="companyName" value="{VAL_COMPANY_NAME}" maxlength="150" /></td>
			  	</tr>
				<tr>
				  <td align="right" valign="top"><strong>{TXT_ADD_1}</strong></td>
				  <td><input name="add_1" type="text" class="textbox" id="add_1" value="{VAL_ADD_1}" maxlength="100" /> *</td>
			  	</tr>
				<tr>
				  <td align="right" valign="top"><strong>{TXT_ADD_2}</strong></td>
				  <td><input name="add_2" type="text" class="textbox" id="add_2" value="{VAL_ADD_2}" maxlength="100" /></td>
			  	</tr>
				<tr>
				  <td align="right" valign="top"><strong>{TXT_TOWN}</strong></td>
				  <td><input name="town" type="text" class="textbox" id="town" value="{VAL_TOWN}" maxlength="100" /> *</td>
			  	</tr>
				<tr>
                  <td align="right" valign="top"><strong>{TXT_POSTCODE}</strong></td>
				  <td><input name="postcode" type="text" class="textbox" id="postcode" value="{VAL_POSTCODE}" maxlength="100" />
				    *</td>
			  </tr>
				<tr>
                  <td align="right" valign="top"><strong>{TXT_COUNTRY}</strong></td>
				  <td><select name="country" id="country" class="textbox" onchange="updateCounty(this.form);">
                      <!-- BEGIN: country_opts -->
                      <option value="{VAL_COUNTRY_ID}" {COUNTRY_SELECTED}>{VAL_COUNTRY_NAME}</option>
                      <!-- END: country_opts -->
                    </select>
				    *</td>
			  </tr>
				<tr>
				  <td align="right" valign="top"><strong>{TXT_COUNTY}</strong></td>
				  <td><div id="divCountyText" {VAL_COUNTY_TXT_STYLE}>
		  <input name="county" type="text" class="textbox" id="county" value="{VAL_DEL_COUNTY}" maxlength="50" />
		</div>
		<div id="divCountySelect" {VAL_COUNTY_SEL_STYLE}>
		  <select name="county_sel" id="county_sel" class="textbox">
		  <!-- BEGIN: county_opts -->
		  <option value="{VAL_DEL_COUNTY_ID}" {COUNTY_SELECTED}>{VAL_DEL_COUNTY_NAME}</option>
		  <!-- END: county_opts -->
		  </select>
		</div>
		<input name="which_field" type="hidden" id="which_field" value="{VAL_COUNTY_WHICH_FIELD}" />  *</td>
			  	</tr>
				<tr>
					<td align="right"><strong>{TXT_PHONE}</strong></td>
					<td><input name="phone" type="text" class="textbox" id="phone" value="{VAL_PHONE}" maxlength="100" /> *</td>
				</tr>
				<tr>
				  <td align="right"><strong>{TXT_MOBILE}</strong></td>
				  <td><input name="mobile" type="text" class="textbox" id="mobile" value="{VAL_MOBILE}" maxlength="100" /></td>
			  	</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><input name="submit" type="submit" value="{TXT_SUBMIT}" class="submit" /></td>
			  </tr>
		</table>
	</form>
	<!-- END: session_true -->
	
	<!-- BEGIN: session_false -->
	<p>{LANG_LOGIN_REQUIRED}</p>
	<!-- END: session_false -->

</div>
<!-- END: profile -->