// JavaScript Document

function getLoginDetails(){	
if(jQuery("#username").val()!="" && jQuery("#password").val()!=""){
	jQuery.ajax({
  type:"POST",
  url: "ajax/loginajax.php",
  data: "email="+jQuery("#username").val()+"&pass="+jQuery("#password").val(),
  success: pageSuccess,
  error: ajaxError
 });
}else{
	jQuery("#Id_error").html("fill the required");
	return false;
}
return false;
}
function pageSuccess(data){
//	return data;
alert(data);
return false;
 /*datavalue = data.split("::"); 
 document.getElementById('filterBox').innerHTML   = datavalue[1];
 var parm  = datavalue[0].split("|");
 var groupId = parm[0];
 var optionId= parm[1];
 var catId = parm[2];
 
 addOptionList(optionId, catId,  groupId);*/
}
function ajaxError(){
 alert("error ouccured during loading page.");
}