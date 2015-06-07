<!-- BEGIN: gift_cert -->
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php">Home</a> <span class="breadSeprator"></span>  {LANG_TITLE}
     
        
      </div>
    </div>
<div class="maincenter">
 <h2 class="mainheading">  {LANG_TITLE}</h2>
<div class="sitedoc account giftCertificate">
	<div class="leftside">
	
   <h3 class="txt24">{TXT_MAIN_TIT}</h3>
	<p class="txt14 latoLight disc" >{TXT_MAIN_TEXT}</p>
	<form action="index.php?_a=giftCert" method="post">
    <!-- BEGIN: error -->
	<p class="txtError">{VAL_ERROR}</p>
	<!-- END: error -->
	<table border="0" cellspacing="0" cellpadding="3" class="txt14 txt-grey">
	  <tr>
		<td >Select Amount <span class="required">*</span></td>
	  </tr>
      <tr>
      	<td>
        	<select class="textbox" style="width:93px; float:none; vertical-align:baseline"  id="amountprince">
            	<option>{SYMBL_LEFT}*****</option>
                <option value="20">{SYMBL_LEFT}20</option>
                <option value="35">{SYMBL_LEFT}35</option>
                <option value="50">{SYMBL_LEFT}50</option>
                <option value="100">{SYMBL_LEFT}100</option>
            </select>
            <input type="hidden" value="{SYMBL_LEFT}" id="cursymbol" />
            <span> or </span>
            <select class="textbox" style="width:173px;  float:none;vertical-align:baseline"  id="deviceprice">
            	<option>Devices*******</option>
                <!-- BEGIN: all_models -->
                <option value="{MODEL_PRICE}">{MODEL_NAME}</option>
              <!-- END: all_models -->
            </select>
            <span id="device-price"></span>
            <input type="hidden" name="gc[amount]" value=""  id="device-price2" />
        </td>
      </tr>
     
      <tr>
      	<td>{LANG_RECIP_NAME}<span class="required">*</span></td>
      </tr>
      <tr>
      	<td><input name="gc[recipName]" class="textbox" type="text" id="name" value="{VALUE_RECIPNAME}"  required="required" /></td>
      </tr>
      <tr>
      	<td>{LANG_RECIP_EMAIL} <span class="required">*</span></td>
      </tr>
      <tr>
      	<td><input name="gc[recipEmail]" class="textbox" type="text" id="email" value="{VALUE_EMAIL}"   required="required" /></td>
      </tr>
 
      <tr>
      	<td>{LANG_MESSAGE} </td>
      </tr>
      <tr>
      	<td><textarea name="gc[message]" class="textbox" cols="35" rows="2" id="message">{VALUE_MESSAGE}</textarea></td>
      </tr>	 
	  <tr>
		<td>{LANG_METHOD}</td>
        </tr>
     <tr>
		<td>
		<select name="gc[delivery]" class="textbox">
		<!-- BEGIN: email_opts -->
		<option value='e' {VAL_DELIVERY_E}>{LANG_EMAIL}</option>
		<!-- END: email_opts -->
		<!-- BEGIN: mail_opts -->
		<option value='m' {VAL_DELIVERY_M}>{LANG_MAIL}</option>
		<!-- END: mail_opts -->
		</select>		
		</td>
	  </tr>
	  <tr>
	    <td align="center">
		<input type="hidden" name="gc[cert]" value="1" />
		<input type="submit" class="button" name="Submit" value="Send" />
		</td>
      </tr>
	</table>
<!--{LANG_ADD_TO_BASKET}-->
	</form>
    </div>
    <div class="rightside">
    		<div class="message txt14 white">
    		Simply log onto the site or app and checkout your custom cases with the voucher code 
            </div>
            <div class="maindiv white txt14">
            	<span  class="right radius3px codeBox"  />******</span>
            	<span class="right" style="padding-top:5px;">Voucher Code:</span>
            </div>
            <p class="txt14 latoLight txtorange" style="clear: both; text-align: center; padding: 39px 5px 0px;">* Voucher code shown is for demonstration only</p>
    </div>
</div>
</div>
<!-- END: gift_cert -->