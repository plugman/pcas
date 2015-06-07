function ValidateRegister()
{
		if(document.getElementById("firstName").value == "") 
		{
			document.getElementById("err_fname").innerHTML='Fill Required';
			document.getElementById("firstName").focus();
			return false;
		}
		else
			document.getElementById("err_fname").innerHTML='';
		
		if(document.getElementById("firstName").value != '')
		{
			if(Validate(document.getElementById("firstName").value,"[^A-Za-z+\\-\\ ]") == true) {
			//alert("");
			document.getElementById("err_fname").innerHTML = 'Only Characters are allowed.';
			document.getElementById("firstName").focus();
			return false;
			}
		}
		else
		document.getElementById("err_fname").innerHTML='';
		
		if(document.getElementById("email").value == "") 
		{
			document.getElementById("err_email").innerHTML='Fill Required Required';
			document.getElementById("email").focus();
			return false;
		}
		else
		document.getElementById("err_email").innerHTML='';
		
		
		if(document.getElementById("email").value != '')
		{
			if(Validate(document.getElementById("email").value,"[A-Za-z0-9_\\.][A-Za-z]*@[A-Za-z0-9]*\\.[A-Za-z0-9]") == false)
			{
				document.getElementById("err_email").innerHTML='Invalid Email';
				document.getElementById("mbrEmailExist").innerHTML='';
				document.getElementById("email").focus();
				return false;
			}
		}
		else
		document.getElementById("err_email").innerHTML='';
		
		
		if(document.getElementById("pwd").value == "") 
		{
			document.getElementById("err_pwd").innerHTML='Required';
			document.getElementById("pwd").focus();
			return false;
		}
		else
		document.getElementById("err_pwd").innerHTML='';
		
		if(document.getElementById("cpassword").value == "") {
			document.getElementById("err_cpassword").innerHTML='Required';
			document.getElementById("cpassword").focus();
			return false;
		}
		else
		document.getElementById("err_cpassword").innerHTML='';
		
		if(document.getElementById("cpassword").value != document.getElementById("pwd").value) 
		{
			document.getElementById("err_cpassword").innerHTML='Not Matched';
			document.getElementById("cpassword").focus();
			return false;
		}
		else
		document.getElementById("err_cpassword").innerHTML='';
		
		if(document.getElementById("security_answer").value == "") {
			document.getElementById("err_security_answer").innerHTML='Required';
			document.getElementById("security_answer").focus();
			return false;
		}
		else
		document.getElementById("err_security_answer").innerHTML='';
		
		
		if(document.getElementById('optMobile') != null)
		{
			var optMobile = document.getElementById('optMobile').checked;	
			if(optMobile)
			{
				if(document.getElementById('mobile_number').value == '')
				{
					document.getElementById("err_mobile_number").innerHTML='Mobile Number Required';
					document.getElementById("mobile_number").focus();	
					return false;
				}
			}
		}
		
		if(document.getElementById('email_exist_flag').value == 1)
		return true;
		else
		return false;
		
}

/*function Hide_Show_Mobile()
{
	var optMobile = document.getElementById('optMobile').checked;	
	if(optMobile)
	{
		document.getElementById('mobilePanel').style.display = '';
	}
	else
	{
		document.getElementById('mobilePanel').style.display = 'none';
	}
}*/

function Validate(strToValidate,RegPattern)
{
		var expr = new RegExp(RegPattern,"g");
		var result = expr.test(strToValidate);
		if(result==true)
			return true;
		else
			return false;
}