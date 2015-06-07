// JavaScript Document
function getdata(id, url, vendor){
	//alert(id);
	$j.ajax({
		type:"POST",
		url: url+"/admin/sources/dhru/api_files/dhruapi.php",
		data: "SERVICEID="+id+"&vendor="+vendor,
		success: dhrudata,
		error: error
	});	
}
function dhrudata(data){	


//alert(data);
		document.getElementById('dhrudata').innerHTML 	= data;
	

}
function error(){
	alert("System is unable to connect to server");	
}
function loadapidata(){
	document.getElementById('apidata').style.display="table-cell";
}
function clearapidata(){
	document.getElementById('apidata').style.display="none";
}
function updateproductdata(id, url){
	$j.ajax({
		type:"POST",
		url: url+"/admin/sources/dhru/api_files/loadapidata.php",
		data: "vendorid="+id,
		success: loaddata,
		error: error
	});	
}function loaddata(data){	
	document.getElementById('mapload').innerHTML 	= data;
	

}
function error(){
	alert("System is unable to connect to server");	
}
function sendorder(url, vendor, imei, id, mapid){
	//alert(id);
	$j.ajax({
		type:"POST",
		url: url+"/admin/sources/dhru/api_files/sendorder.php",
		data: "vendor="+vendor+"&imei="+imei+"&orderid="+id+"&serviceid="+mapid,
		success: ordersuccess,
		error: error
	});	
}
function ordersuccess(data){
alert(data);
}
function getstatus(url, vendor, refid){
	//alert(refid);
	$j.ajax({
		type:"POST",
		url: url+"/admin/sources/dhru/api_files/getorderstatus.php",
		data: "vendor="+vendor+"&refid="+refid,
		success: statussuccess,
		error: error
	});	
}
function statussuccess(data){
	alert(data);
}
function loadbrands(id, url){
	var vendor = $j("#vendorss option:selected").val();
	//alert(url);
	$j.ajax({
		type:"POST",
		url: url+"/admin/sources/dhru/api_files/loadbrands.php",
		data: "vendor="+vendor+"&paraid="+id,
		success: brandsuccess,
		error: error
	});	
}
function brandsuccess(data){
	//alert(data);
	var datavalue = data.split("::");
	//alert(datavalue[0]);

	//document.getElementById('mapbrand').innerHTML 	= datavalue[0];
	document.getElementById('mapmodel').innerHTML 	= datavalue[1];
	//document.getElementById('mapcountry').innerHTML 	= datavalue[2];
	document.getElementById('mapprovider').innerHTML 	= datavalue[3];
}