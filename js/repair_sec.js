// JavaScript Document
var idaddress = '';
var togglebox = '';
function loaddevices(catid, level, id){
	if(level == 1)
	var box = 'make';
	else if(level == 2)
	var box = 'devices';
	else if(level == 3)
	var box = 'model';
	 $('#'+box+'> div').click(function(e) {
        e.preventDefault();
        $('#'+box+'> div > div > div > div').removeClass('success');
        $(this+'> div > div > div').addClass('success');
    });
	var storeUrl = jQuery("#storeurl").val();
	 idaddress = id;
	 togglebox = box+'_1';
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/loadrepairs.php",
		data: "catid="+catid+"&level="+level,
		success: success,
		error: connectionerror
	});	
}
function loaddetails(proid, level, id){
	$('#problems > div').click(function(e) {
        e.preventDefault();
        $('#problems > div > div').removeClass('success');
        $(this+'> div > div > div').addClass('success');
    });
	var storeUrl = jQuery("#storeurl").val();
	 idaddress = id;
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/includes/content/loaddetails.php",
		data: "proid="+proid+"&level="+level,
		success: successdetails,
		error: connectionerror
	});	
}
function connectionerror(XMLHttpRequest, textStatus, errorThrown){
	alert("System is unable to connect to server");	
}
function successdetails(data){
	//alert(data);
	var datavalue = data.split("::");
	if(datavalue[0]==1){
		$('#'+idaddress).addClass('success');
		$("#problemdetail").css("display", "block");
		document.getElementById('problemtit').innerHTML 	= datavalue[1];
		document.getElementById('problemprice').innerHTML 	= datavalue[2];
		document.getElementById('probtree').innerHTML 	= datavalue[5];
		document.getElementById('contactus').href 	= datavalue[6];
		document.getElementById('repairid').href 	= datavalue[7];
		$('#problems_1').slideUp(1000);
		//$("#repairid").val(datavalue[4]);
	}
}
function success(data){
	//alert(data);
	var datavalue = data.split("::");
	if(datavalue[0]==1){
		
	if(datavalue[1]==1)	{
		document.getElementById('devices').innerHTML 	= datavalue[2];
		$("#devices").css("display", "block");
		$('#'+idaddress).addClass('success');
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problems').innerHTML 	= '';
		$("#model").css("display", "none");
		$("#problems").css("display", "none");
		$("#problemdetail").css("display", "none");
	}
	else if(datavalue[1]==2)	{
		document.getElementById('model').innerHTML 	= datavalue[2];
		$("#model").css("display", "block");
		$('#'+idaddress).addClass('success');
		document.getElementById('problems').innerHTML 	= '';
		$("#problems").css("display", "none");
		$("#problemdetail").css("display", "none");
	}
	else if(datavalue[1]==3){
		if(datavalue[3]==007){
			document.getElementById('model').innerHTML 	= '';
			$("#model").css("display", "none");
		}
		document.getElementById('problems').innerHTML 	= datavalue[2];
		$("#problems").css("display", "block");
		$('#'+idaddress).addClass('success');
		$("#problemdetail").css("display", "none");
	}
	$('#'+togglebox).slideUp(1000);
	}else{
		if(datavalue[0]==2)	{
			if(datavalue[1]==1)	{
		document.getElementById('devices').innerHTML 	= '';
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problems').innerHTML 	= '';
		$("#devices").css("display", "none");
		$("#model").css("display", "none");
		$("#problems").css("display", "none");
		$("#problemdetail").css("display", "none");
	}
	else if(datavalue[1]==2)	{
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problems').innerHTML 	= '';
		$("#model").css("display", "none");
		$("#problems").css("display", "none");
		$("#problemdetail").css("display", "none");
	}else if(datavalue[1]==3)	{
		document.getElementById('problems').innerHTML 	= '';
		$("#problems").css("display", "none");
		$("#problemdetail").css("display", "none");
	}
		}
	}
}
function loaddevicess(url, catid, level){
	if(level == 1){
	var value = $j("#make option:selected").text();
	document.getElementById("make_d").value= value;
	}else if(level == 2){
	var value = $j("#device option:selected").text();
	document.getElementById("device_d").value= value;
	}else if(level == 3){
	var value = $j("#model option:selected").text();
	document.getElementById("model_d").value= value;
	}
	var storeUrl = url;
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/admin/sources/repair/loadrepairs.php",
		data: "catid="+catid+"&level="+level,
		success: successadmin,
		error: connectionerror
	});	
}
function successadmin(data){
	
	var datavalue = data.split("::");
	//alert(datavalue);
	if(datavalue[2]==1){
	if(datavalue[1]==1)	{
		document.getElementById('device').innerHTML 	= datavalue[0];
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problem').innerHTML 	= '';

	}
	else if(datavalue[1]==2){
		document.getElementById('model').innerHTML 	= datavalue[0];
		document.getElementById('problem').innerHTML 	= '';
	}
	else if(datavalue[1]==3){
		document.getElementById('problem').innerHTML 	= datavalue[0];
	}
	}else{
		if(datavalue[0]==2)	{
			if(datavalue[1]==1)	{
		document.getElementById('device').innerHTML 	= '';
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problem').innerHTML 	= '';
	}
	else if(datavalue[1]==2)	{
		document.getElementById('model').innerHTML 	= '';
		document.getElementById('problem').innerHTML 	= '';
	}else if(datavalue[1]==3)	{
		document.getElementById('problem').innerHTML 	= '';
		$("#problem").css("display", "none");
	}
		}
	}
}
function loaddetailss(url, proid, level){
	var value = $j("#probleme option:selected").text();
	document.getElementById("problem_d").value= value;
	var storeUrl = url;
	jQuery.ajax({
		type:"POST",
		url: storeUrl+"/admin/sources/repair/loaddetails.php",
		data: "proid="+proid+"&level="+level,
		success: successdetailss,
		error: connectionerror
	});	
}
function successdetailss(data){
	//alert(data);
	var datavalue = data.split("::");
	if(datavalue[0]==1){
		document.getElementById('prob_price').value 	= datavalue[1];
		//$("#prob_price").val(datavalue[1]);
	}
}
function togglethis(id){
	if ($('#'+id).is(':visible')){
			$('#'+id).slideUp(1000);
	}else{
		$('#'+id).slideDown(1000);
		}
}