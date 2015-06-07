// JavaScript Document
function loginAuth(){	
var storeUrl = jQuery("#storeurl").val();
	document.getElementById('loginAuthenticate').style.display="none";
	document.getElementById('loader').style.display="inline-block";
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/ajaxAuthenticate.inc.php",
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
	document.getElementById('loginAuthenticate').style.display="inline-block";
	document.getElementById('loader').style.display="none";
	}
}
function ValidateRegister(){

	if(document.getElementById("fname").value == ""){

		$("#fname").css('background-color' , '#FFCECE');

		document.getElementById("fname").focus();

		return false;

	}

	if(document.getElementById("fname").value != ''){

		if(Validate(document.getElementById("fname").value,"[^A-Za-z+\\-\\ ]") == true) {

		//alert("");
		$("#fname").css('background-color' , '#FFCECE');
		document.getElementById("fname").focus();

		return false;

		}

	}
	if(document.getElementById("txtEmail").value == ""){

		$("#txtEmail").css('background-color' , '#FFCECE');

		document.getElementById("txtEmail").focus();

		return false;

	}
	if(document.getElementById("txtEmail").value != ''){

		if(Validate(document.getElementById("txtEmail").value,"[A-Za-z0-9_\\.][A-Za-z]*@[\-A-Za-z]*\\.[A-Za-z0-9]") == false){

			$("#txtEmail").css('background-color' , '#FFCECE');

			document.getElementById("mbrEmailExist").innerHTML='';

			document.getElementById("txtEmail").focus();

			return false;

		}

	}

	if(document.getElementById("txtphone").value == ""){

		$("#txtphone").css('background-color' , '#FFCECE');

		document.getElementById("txtphone").focus();

		return false;

	}
	if(document.getElementById("txtpwd").value == ""){

		$("#txtpwd").css('background-color' , '#FFCECE');

		document.getElementById("txtpwd").focus();

		return false;

	}
	if(document.getElementById("cpassword").value == "") {

		$("#cpassword").css('background-color' , '#FFCECE');

		document.getElementById("cpassword").focus();

		return false;

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


function Validatequantity(){
var q = document.getElementById('quan').value;
var minq = document.getElementById('mquan').value;
 
if(q < parseInt(minq)){
document.getElementById('qerror').innerHTML="Minnimum Quantity for this product is "+minq;
return false;	
}
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
	var storeUrl = jQuery("#storeurl").val();

	mbrEmail = document.getElementById('txtEmail').value;

	

	if(mbrEmail != ''){

		if(Validate(document.getElementById("txtEmail").value,"[A-Za-z0-9_\\.][A-Za-z]*@[\-A-Za-z0-9]*\\.[A-Za-z0-9]") == false){				

			document.getElementById("err_email").innerHTML='Invalid Email';			

			document.getElementById("mbrEmailExist").innerHTML='';

			//document.getElementById("email").focus();

			return false;			

		}else{

			jQuery("#mbrEmailExist").load(storeUrl+"/js/check-email-existance.php?email="+mbrEmail);

			document.getElementById("err_email").innerHTML='';			

		}

	}	

}





function RemoveProduct(valProdKey,prodid){
var storeUrl = jQuery("#storeurl").val();
	jQuery.ajax({
		type:"POST",

		url: storeUrl+"/includes/content/ajaxBasket.inc.php",

		data: "remove="+valProdKey+"&prdid="+prodid+"&basketcount="+jQuery("#basketcount").val(),

		success: BasketSuccess,

		error: BasketError

	});	

}
function BasketPage(){
	var storeUrl = jQuery("#storeurl").val();
	if(jQuery("#txtcoupon").val()!=""){
		jQuery.ajax({
			type:"POST",
			url: storeUrl+"/includes/content/ajaxBasket.inc.php",
			data: "coupon="+jQuery("#txtcoupon").val(),
			success: BasketPageSuccess,
			error: BasketError
		});	
	}else{
		document.getElementById("errorDiscount").style.display='';
	}
}
function BasketPageSuccess(data){
	//alert(data);	
	var datavalue = data.split("::");
	if(datavalue[0]==1)	{
		
		document.getElementById('IdCouponCodeDiv').innerHTML 	= datavalue[1];
		//document.getElementById('IdCartTotal').innerHTML		= datavalue[2];
		document.getElementById('IdCartTotal').innerHTML		= datavalue[2];
		document.getElementById('paypalfee').innerHTML		= datavalue[3];
		//document.getElementById('cartTotalId').innerHTML	= datavalue[2];	
		//document.getElementById('discount').innerHTML 		= datavalue[3];	
		//document.getElementById('sub_total').innerHTML 		= datavalue[4];	
		//document.getElementById('IdBasketData').innerHTML 		= datavalue[4];
	}else{
		jQuery("#cart").submit();
	}	
}

function RemoveCouponCode(codeval){
	var storeUrl = jQuery("#storeurl").val();
	//alert(storeUrl);
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/ajaxBasket.inc.php",
		data: "remCode="+codeval+"&basketcount="+jQuery("#basketcount").val(),
		success: BasketPageSuccess,
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
	var storeUrl = jQuery("#storeurl").val();
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/ajaxBasket.inc.php",
		data: "checkoutval=1",
		success: CheckoutSuccess,
		error: BasketError
	});	
}
function CheckoutSuccess(data){
	var storeUrl = jQuery("#storeurl").val();
	if(data==0){
		window.location=storeUrl+"/index.php?_g=co&_a=step3";
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
function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
		 }
else{
         return true;
      }
	  }
function changebg(){
	// $("#tco").addClass("checkboxcheck");
	if ( $("#tco").hasClass("checkboxcheck")) 
{
    $("#tacond").attr('checked',false);
	 $("#tco").removeClass("checkboxcheck");
}
else
{
    $("#tacond").attr('checked',true);
	 $("#tco").addClass("checkboxcheck");
}
}
function changebg2(){
	$('#billing').slideToggle();
	if ( $("#tco2").hasClass("checkboxcheck")) 
{
    $("#tacond2").attr('checked',false);
	 $("#tco2").removeClass("checkboxcheck");
}
else
{
    $("#tacond2").attr('checked',true);
	 $("#tco2").addClass("checkboxcheck");
}
}
function changebg1(){
 $('#billing').slideToggle();
 if ( $("#tco1").hasClass("checkboxcheck")) 
{
    $("#sameaddress").attr('checked',false);
  $("#tco1").removeClass("checkboxcheck");
}
else
{
    $("#sameaddress").attr('checked',true);
  $("#tco1").addClass("checkboxcheck");
}
}
function Validateproduct(){
	if($("#txtimei").hasClass("error")){
	return false;
}else if($("#txtimei").val().length < 15){
	return false;
}
else if($("#txtimeii").val().length <15){
$("#txtimeii").focus();
$("#txtimeii").css('background-color' , '#FFCECE');
return false;
}
}
function changeopt(valli, textopt){
	$("#selcetproductdetail").val(valli);
	$("#selectedopt").text(textopt);

}
if (typeof imei_length === "undefined")
{
	var imei_length=15;
}
if (typeof use_letters === "undefined")
{
	var use_letters=false;
}
if (typeof use_minus === "undefined")
{
	var use_minus=false;
}
if (typeof use_alert === "undefined")
{
	var use_alert=false;
}
function alltrim(str) {
		return str.replace(/^\s+|\s+$/g, '');
	}
	function keyup_imei(){
		imeisText=$('#txtimei').val();
		imeisText=imeisText.replace(/([\n|\r|\s])/g, '_');
		imeisText=imeisText.replace(/[_]+$/g, '');
		imeis=unescape(imeisText).split('_');
		unlocks_count=0;
		imeisLen=imeis.length;
		errors=false;
		if(imeisLen>0)
		{
			for(i=0; i < imeisLen; i++)
			{
				if(imeis[i].length>0)
				{
					str = alltrim(imeis[i]);
					if(str.length!=imei_length)
					{
						errors=true;
					}
					else
					{
						if(use_minus)
						{
							testul=(/^[a-z0-9-]{4}-[a-z0-9-]{4}/i).test(str)
						}
						else
						{
							if(use_letters)
							{
								testul=(/^[a-z0-9]/i).test(str)
								//alert('da')
							}
							else
							{
								testul=(/^[-+]?[0-9]+$/).test(str)
							}
						}
						if(!testul)
						{
							errors=true;
						}
						else
						{
							unlocks_count++;
						}
					}
				}
			}
		}
		if(errors) $('#txtimei').addClass('error');
		else $('#txtimei').removeClass('error');
		var cost_1_unlock =  $('#cost_1_unlock').val()
		var currencgy_symbol_left =  $('#symbolleft').val()
		$('#totalprice').html(currencgy_symbol_left+parseFloat(unlocks_count*cost_1_unlock).toFixed(2))
	}
function updateshipkey(value){
	var storeUrl = jQuery("#storeurl").val();
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/ajaxBasket.inc.php",
		data: "shipKey="+value,
		success: updateshipprice,
		error: BasketError
	});	
}
function updateshipprice(data){
	var datavalue = data.split("::");		
		document.getElementById('IdCartTotal').innerHTML 	= datavalue[1];
}