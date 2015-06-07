<!-- BEGIN: contact_us -->
<div class="maindiv" >

<div >

<div class="maindiv mainbox2">
<div class="headingbox">
<span class="txt30  heading">Contact Us </span>
<div class="breadbox">
<a href="index.php">
<img alt="" src="skins/{VAL_SKIN}/styleImages/home2.jpg"    title="home"/>
</a>
/
Contact Us 
</div>
</div>

<!-- BEGIN: error -->
<p id="tdstatus" class="txtError" style="margin-left:10px;">{VAL_ERROR}</p>
<!-- END: error --> 
<!-- BEGIN: form -->
<form action="" method="post" id="frmContactus" name="frmContactus" class="formValidation">
<div class="loginleft3">

<label class="title txt-purple txt24">
If you have any questions, comments or suggestions we would love to hear from you.</label>

<div class="maindiv">
           <label class="txt18 txt-grey maindiv">{LANG_NAME}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input type="text"  name="name" id="name" value="{VAL_NAME}" />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
<div class="maindiv">
           <label class="txt18 txt-grey maindiv">{LANG_EMAIL}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
            <input type="text"  name="email" id="email" value="{VAL_EMAIL}"  />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>           
<div class="maindiv">
           <label class="txt18 txt-grey maindiv">{LANG_PHONE}</label>
       		<div class="txtboxmain">
             <span class="txtboxmain-left"></span>
           <input type="text"  name="phone" id="phone" value="{VAL_PHONE}"  />
             
             <span class="txtboxmain-right">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>   
            <div class="maindiv">
           <label class="txt18 txt-grey maindiv">{LANG_COMMENTS}</label>
       		<div class="txtboxmain txtboxmain2">
             <span class="txtboxmain-left txtboxmain-left2"></span>
            <textarea name="msg"  id="msg"   class="textarea"  cols="1" rows="1" >{VAL_COMMENTS}</textarea>
             
             <span class="txtboxmain-right txtboxmain-right2">
             	<span class="mandatory"></span>
             </span>
            </div>
            </div>
</div>

<div  class="loginright loginrightc "  >
<span class=" left txt24 ">Our Location</span>
<p class="txt-grey txt16 maindiv">
<span class="txt18 txt-darkpurple">IMEI-UNLOCK</span><br />
11 Errol Street, Crace,<br />
Canberra<br />
ACT 2911<br />
</p>
<p class="txt-grey txt18 maindiv">
<span class="txt24 txt-darkpurple">Email:</span><br />
For wholesale IMEI unlock inquiry please email<br />
<u class="txt-darkpurple">sales@imei-unlock.net</u>
</p>
<p class="txt-grey txt18 maindiv">
<span class="txt24 txt-darkpurple">Skype:</span><br />
IMEI-Unlock
</p>



<!-- BEGIN: view_doc --> 

{DOC_CONTENT}
<!-- END: view_doc --> 
</div>
<div class="maindiv footerlogin">
 		<input type="submit"  class="submitlogin left"   value="Submit"  style="margin:12px 0 0 20px" />
</div>
</form>
<!-- END: form --> 
</div>

</div>
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script type="text/javascript" language="javascript">
jQuery.validator.addMethod("lettersonly", function(value, element) {
				        return this.optional(element) || /^[a-z \s]+$/i.test(value);
				}, "Only Letters Allowed.");
	
$(document).ready(function(){
	$("#frmContactus").validate({
	   rules: {
				name:  {required:true, lettersonly: true, maxlength:20},
				email: {required: true, maxlength:50, email: true},
				phone:{digits:true, maxlength:20},
				msg: {required:true, maxlength:500},
			  },
	   messages:
			  {
				name: {required: 'Please enter Name'},
				email: {required: 'Please enter Email Id.', email: 'Please enter valid Email Id.', maxlength: 'Max 50 Characters Allowed.'},
				phone:{maxlength: 'Max 20 characters Allowed.'},
				msg:{required: 'Please enter Comments.', maxlength: 'Max 500 Characters Allowed.'}
			  }
	});
});

function ResetForm()
{
		var validator = $("#frmContactus").validate();
		validator.resetForm();
		document.frmContactus.reset();
}
</script> 
<script type="text/javascript" language="javascript">
function ResetForm()
{
	var validator = $("#frmContactus").validate();
	validator.resetForm();
	document.frmContactus.reset();
}
</script> 
</div>

<!-- END: contact_us -->
