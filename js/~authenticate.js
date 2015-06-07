// JavaScript Document

function loginAuth(){	

	jQuery.ajax({

		type:"POST",

		url: "includes/content/ajaxAuthenticate.inc.php",

		data: "username="+jQuery("#login_email").val()+"&password="+jQuery("#login_password").val(),

		success: LoginSuccess,

		error: LoginError

	});	

}



function LoginError(XMLHttpRequest, textStatus, errorThrown){

	alert("System is unable to connect to server");	

}



function LoginSuccess(data){

	if(data == 1) {

		var login_email = jQuery("#login_email").val();

		var login_password = jQuery("#login_password").val();

		if( jQuery("#login_remember").attr('checked')==true){

			var remember = 1;

		}else{

			var remember = 0;

		}		
		if(document.getElementById("logredir").value == 1){
			var redir = 1;
		}else{

			var redir = 0;
		}		
		jQuery("#email").val(login_email);

		jQuery("#password").val(login_password);

		jQuery("#remember").val(remember);		
		
		jQuery("#redir").val(redir);	
		jQuery("#frmLoginBox").submit();

	} else {

	jQuery("#loginFailedBox_error").fadeIn('slow').animate({opacity: 1.0}, 3000).fadeOut('slow');

	}

}



function ValidateRegister(){

	if(document.getElementById("fname").value == ""){

		document.getElementById("err_fname").innerHTML='Required';

		document.getElementById("fname").focus();

		return false;

	}else{

		document.getElementById("err_fname").innerHTML='';

	}

	

	if(document.getElementById("fname").value != ''){

		if(Validate(document.getElementById("fname").value,"[^A-Za-z+\\-\\ ]") == true) {

		//alert("");

		document.getElementById("err_fname").innerHTML = 'Only Characters are allowed.';

		document.getElementById("fname").focus();

		return false;

		}

	}else{

		document.getElementById("err_fname").innerHTML='';

	}

	

	if(document.getElementById("txtEmail").value == ""){

		document.getElementById("err_email").innerHTML='Required';

		document.getElementById("txtEmail").focus();

		return false;

	}else{

	document.getElementById("err_email").innerHTML='';

	}

	

	if(document.getElementById("txtEmail").value != ''){

		if(Validate(document.getElementById("txtEmail").value,"[A-Za-z0-9_\\.][A-Za-z]*@[A-Za-z]*\\.[A-Za-z0-9]") == false){

			document.getElementById("err_email").innerHTML='Invalid Email';

			document.getElementById("mbrEmailExist").innerHTML='';

			document.getElementById("txtEmail").focus();

			return false;

		}

	}else{

	document.getElementById("err_email").innerHTML='';

	}

	

	

	if(document.getElementById("txtphone").value == ""){

		document.getElementById("err_phone").innerHTML='Required';

		document.getElementById("txtphone").focus();

		return false;

	}else{

	document.getElementById("err_phone").innerHTML='';

	}

	

	/*if(document.getElementById("txtphone").value != ''){

		if(Validate(document.getElementById("txtphone").value,"/^(\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/") == false){

			document.getElementById("err_phone").innerHTML='Invalid Phone Number';			

			document.getElementById("txtphone").focus();

			return false;

		}

	}else{

	document.getElementById("err_phone").innerHTML='';

	}*/

	//alert(document.getElementById("txtpwd").value);

	if(document.getElementById("txtpwd").value == ""){

		document.getElementById("err_pwd").innerHTML='Required';

		document.getElementById("txtpwd").focus();

		return false;

	}else{

	document.getElementById("err_pwd").innerHTML='';

	}

	

	if(document.getElementById("cpassword").value == "") {

		document.getElementById("err_cpassword").innerHTML='Required';

		document.getElementById("cpassword").focus();

		return false;

	}else{

	document.getElementById("err_cpassword").innerHTML='';

	}

	

	if(document.getElementById("cpassword").value != document.getElementById("txtpwd").value){

		document.getElementById("err_cpassword").innerHTML='Not Matched';

		document.getElementById("cpassword").focus();

		return false;

	}else{

	document.getElementById("err_cpassword").innerHTML='';

	}
	if(document.getElementById("tacon").checked == false) {

		document.getElementById("err_fname").innerHTML='Read Term and Conditions';

		document.getElementById("tacon").focus();

		return false;

	}else{

	document.getElementById("tacond").innerHTML='';

	}

	

	if(document.getElementById('email_exist_flag').value == 1)

	return true;

	else

	return false;	

}



function Validate(strToValidate,RegPattern){

		var expr = new RegExp(RegPattern,"g");

		var result = expr.test(strToValidate);

		if(result==true)

			return true;

		else

			return false;

}



function EmailExist(){

	mbrEmail = document.getElementById('txtEmail').value;

	

	if(mbrEmail != ''){

		if(Validate(document.getElementById("txtEmail").value,"[A-Za-z0-9_\\.][A-Za-z]*@[A-Za-z0-9]*\\.[A-Za-z0-9]") == false){				

			document.getElementById("err_email").innerHTML='Invalid Email';			

			document.getElementById("mbrEmailExist").innerHTML='';

			//document.getElementById("email").focus();

			return false;			

		}else{

			jQuery("#mbrEmailExist").load("js/check-email-existance.php?email="+mbrEmail);

			document.getElementById("err_email").innerHTML='';			

		}

	}	

}





function RemoveProduct(valProdKey,prodid){



	jQuery.ajax({

		type:"POST",

		url: "includes/content/ajaxBasket.inc.php",

		data: "remove="+valProdKey+"&prdid="+prodid+"&basketcount="+jQuery("#basketcount").val(),

		success: BasketSuccess,

		error: BasketError

	});	

}



function BasketSuccess(data){	



	var datavalue = data.split("::");

	

	if(datavalue[0]==1)	{

		document.getElementById('IdBasketData').innerHTML 	= datavalue[1];

		document.getElementById('IdCartItem').innerHTML		= datavalue[3];

		document.getElementById('IdCartTotal').innerHTML	= datavalue[2];

		document.getElementById('paypalfee').innerHTML		= datavalue[5];

		//document.getElementById('IdTotalQTY').innerHTML		= datavalue[4];

		

	}else{

		jQuery("#cart").submit();

	}	

}



function Checkout(){

	jQuery.ajax({

		type:"POST",

		url: "includes/content/ajaxBasket.inc.php",

		data: "checkoutval=1",

		success: CheckoutSuccess,

		error: BasketError

	});	

}



function CheckoutSuccess(data){

	if(data==0){

		window.location="index.php?_g=co&_a=step3";

	}else{

		jQuery("a.basketbox").click();

	}

}



function BasketError(){

	alert("error ouccured during loading page.");

}

function ShowBasket(){



		jQuery("#basketbox").click();

}
function loginredir(){
	document.getElementById('logredir').value = 1;
	jQuery('#cart-box a.close').click();
	}