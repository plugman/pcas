var allChecked=false;

var LightboxOptions = {
    fileLoadingImage:        fileLoadingImage,
    fileBottomNavCloseImage: fileBottomNavCloseImage 
};

function addImage(fileUrl) {
	findObj('previewImage').src = fileUrl;
	findObj('imageName').value = fileUrl;
}

function addRows(parent, quantity) {
	var qty			= document.getElementById(quantity).value;
	var rowCount	= document.getElementById('rowCount').value;
	var parentNode	= document.getElementById(parent);
	
	qty = (qty >= 1)  ? qty: 1;
	qty = (qty <= 20) ? qty: 20;
	
	for (i=0; i<qty; i++) {
		var newRow		= document.createElement('tr');
		newRow.setAttribute('id', 'orderRow_'+rowCount);
		parentNode.appendChild(newRow);
		newRow.innerHTML = '<td valign="top"><a href="" onclick="return delRow(\'orderRow_'+rowCount+'\')"><img src="admin/images/del.gif" alt="" /></a></td><td valign="top"><input type="hidden" name="id['+rowCount+']" value="" /><input type="text" name="prodName['+rowCount+']" class="textbox" value="" /></td><td valign="top"><input name="productCode['+rowCount+']" type="text" class="textbox" value="" size="15" /></td><td valign="top"><input name="imei['+rowCount+']" type="text" class="textbox" value="" size="15" /></td><td valign="top"><textarea name="product_options['+rowCount+']" cols="30" rows="1" class="textbox"></textarea></td><td align="center" valign="top"><input name="quantity['+rowCount+']" type="text" class="textbox" style="text-align:center;" value="" size="3" /></td><td align="center" valign="top"><input name="price['+rowCount+']" type="text" class="textbox" style="text-align:right;" value="" size="7" /></td>';
		rowCount++;
	}
	document.getElementById('rowCount').value = rowCount;
	return false;
}
function addRows1(parent, quantity) {
	var qty			= document.getElementById(quantity).value;
	var rowCount	= document.getElementById('rowCount').value;
	var parentNode	= document.getElementById(parent);
	
	qty = (qty >= 1)  ? qty: 1;
	qty = (qty <= 20) ? qty: 20;
	
	for (i=0; i<qty; i++) {
		var newRow		= document.createElement('tr');
		newRow.setAttribute('id', 'orderRow_'+rowCount);
		parentNode.appendChild(newRow);
		newRow.innerHTML = '<td valign="top"><a href="" onclick="return delRow(\'orderRow_'+rowCount+'\')"><img src="admin/images/del.gif" alt="" /></a></td><td valign="top"><span>Quantity: </span><input type="text" name="quantity[]" class="textbox" value="" /></td><td valign="top"><span>Price: </span><input name="dprice[]" type="text" class="textbox" value="" /></td>';
		rowCount++;
	}
	document.getElementById('rowCount').value = rowCount;
	return false;
}
function checkAll(fieldName,val) {
	var checks = findObj(fieldName)
	for (i=0; i<checks.length; i++) {
		if(val == 'true') {
			checks[i].checked = true;
		} else {
			checks[i].checked = false;
		}
	}
}
function checkAll(fieldName,val) {
	var checks = findObj(fieldName)
	for (i=0; i<checks.length; i++) {
		if(val == 'true') {
			checks[i].checked = true;
		} else {
			checks[i].checked = false;
		}
	}
}

function checkUncheck(parent, className) {
	var elements = $(parent).getElementsByClassName(className);
	for (i=0;i<elements.length;i++) {
		var ele = elements[i];
		(allChecked==false) ? ele.checked='checked' : ele.checked='';
	}
	(allChecked!=true) ? allChecked=true : allChecked=false;
	return false;
}

function compareInputbox(idNo) {
	if (findObj('custom_'+idNo) != findObj('default_'+idNo)) {
		// show revert buttons
		findObj('revertLink_'+idNo).style.display = '';
		findObj('revAllLink').style.display='';
		var tds = findObj('tr_'+idNo).getElementsByTagName('td');
		for (var i = 0; i <= 2; i++) {
			tds[i].className = 'tdModified';
		}
	}	
}

function decision(message, url) {
	if (confirm(message)) {
		location.href = url;
	} else {
		return false;
	}
}

function delRow(element) {
	var node	= document.getElementById(element);
	var parent	= node.parentNode;
	parent.removeChild(node);
	return false;
}

function disableSubmit(obj,msg) {
	obj.value=msg; 
	obj.disabled=true;
	obj.disabled=true;
	obj.className='submitDisabled';
}

function displayStatusMsg(msgStr) { //v1.0
	status=msgStr;
	document.returnValue = true;
}

function editVal(id,val) {
	findObj(id).value = val;
}

function findObj(n, d) {
	var p,i,x; 
	if (!d) d=document;
	if ((p=n.indexOf("?"))>0&&parent.frames.length){
		d=parent.frames[n.substring(p+1)].document;
		n=n.substring(0,p);
	}
	if (!(x=d[n])&&d.all) x=d.all[n];
	for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for (i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
	if (!x && d.getElementById) x=d.getElementById(n);
	return x;
}

function flashBasket(flashes) {
	setTimeout("flash("+flashes+")", 500);
}
		
function flash(flashes) {
	var targetBtn = findObj('flashBasket');
	if (flashes<=0) return;
	if ((flashes%2) == 0) {
		targetBtn.className="flashBasket";
	} else {
 		targetBtn.className="txtviewCart";
	}
	setTimeout("flash("+(flashes-1)+")", 300);
	return;
}

function getImage(imageName) {
	var img	= findObj('img');
	img.src	= img.src.replace(/language\/[a-z\-_]{2,5}\/flag.gif/gi, imageName);
}

function setMainImage(imageName) {
	findObj('img').src = imageName;
}

function goToURL() { //v3.0
  var i, args=goToURL.arguments; document.returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function jumpMenu(target, object, restore) { 
	eval(target+".location='"+object.options[object.selectedIndex].value+"'");
	if (restore) object.selectedIndex=0;
}

function menuBlinds() {
	
}

function openPopUp(url, windowName, w, h, scrollbar) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scrollbar+',resizable=1';
	win = window.open(url, windowName, winprops);
	if (parseInt(navigator.appVersion) >= 4) {
		win.window.focus();
	}
}

function resizeOuterTo(w,h) {
	if (parseInt(navigator.appVersion)>3) {
		if (navigator.appName=="Netscape") {
			top.outerWidth=w;
			top.outerHeight=h;
		} else {
			top.resizeTo(w,h);
		}
	}
}

function revert(idNo,className) {
	var tds = findObj('tr_'+idNo).getElementsByTagName('td');
	for (var i = 0; i <= 2; i++) {
		tds[i].className = className;
	}
	findObj('custom_'+idNo).value = findObj('default_'+idNo).value;
	findObj('revertLink_'+idNo).style.display = 'none';
}

function revertAll(maxId) {
	for (var idNo = 1; idNo < maxId; idNo++) {
		// find array of TD's
		var tds = findObj('tr_'+idNo).getElementsByTagName('td');
		var binary = idNo.toString(2);
		for (var j = 0; j <= 2; j++) {
			// restore class
			if (binary.charAt(binary.length - 1) == "1") {
				tds[j].className = 'tdOdd';
			} else {
				tds[j].className = 'tdEven';
			}
		}
		// set default value back
		findObj('custom_'+idNo).value = findObj('default_'+idNo).value;
		// hide revert button
		findObj('revertLink_'+idNo).style.display = 'none';
	}
	// hide revert all button
	findObj('revAllLink').style.display='none';
}

function setTextOfLayer(objName,x,newText) { 
	if ((obj=findObj(objName))!=null) with (obj)
		if (document.layers) {document.write(unescape(newText)); document.close();}
		else innerHTML = unescape(newText);
}

function submitDoc(formName) { 
	var obj=findObj(formName);
	if (obj!=null) {
		obj.submit(); 
	} else {
		alert('The form you are attempting to submit called \'' + formName + '\' couldn\'t be found. Please make sure the submitDoc function has the correct id and name.');
	}
}

function reloadPage(init) {  //reloads the window if Nav4 resized
	if (init==true) with (navigator) {
		if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    		document.pgW=innerWidth; document.pgH=innerHeight;
			onresize=reloadPage;
		}
	} else if (innerWidth!=document.pgW || innerHeight!=document.pgH) location.reload();
}
reloadPage(true);

function showHideLayers() { //v6.0
  var i,p,v,obj,args=showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function stars(rating, pathImg) {
	/* Positive Stars */
	for (var i = 0; i <= rating; i++) {
		if (i>0) findObj("star"+i).src = pathImg+'1.gif';
	}
	/* Negative Stars */
	for (var i = rating+1; i <= 5; i++) {
		findObj("star"+i).src = pathImg+'0.gif';
	}
	findObj('rating_val').value=rating;
}

function toggleReg() {
	var password = findObj('password');
	var passwordConf = findObj('passwordConf');
	var password_required = findObj('password_required');
	var passwordConf_required = findObj('passwordConf_required');
	if (password.disabled == false && passwordConf.disabled==false) {
		password.disabled=true;
		passwordConf.disabled=true;
		password.className="textboxDisabled";
		passwordConf.className="textboxDisabled";
		passwordConf_required.style.visibility="hidden";
		password_required.style.visibility="hidden";
	} else {
		password.disabled=false;
		passwordConf.disabled=false;
		password.className="textbox";
		passwordConf.className="textbox";
		passwordConf_required.style.visibility="visible";
		password_required.style.visibility="visible";
	}
	password.value="";
	passwordConf.value="";
}

function toggleProdStatus(i,messageRemove, messageNotRemoved,removeImgSrc,noRemoveImgSrc){
	if(findObj('delId['+i+']').value==1) {
		var result = false;
		var className = 'textbox';
		findObj('del['+i+']').src = removeImgSrc;
		findObj('delId['+i+']').value = 0;
		// change class
		findObj('prodName['+i+']').className = "textbox";
		findObj('productCode['+i+']').className = "textbox";
		findObj('imei['+i+']').className = "textbox";
		findObj('product_options['+i+']').className = "textbox";
		findObj('quantity['+i+']').className = "textbox";
		findObj('price['+i+']').className = "textbox";
		alert(messageNotRemoved);
	} else {
		var result = true;
		var className = 'textboxDisabled';
		findObj('del['+i+']').src = noRemoveImgSrc;
		findObj('delId['+i+']').value = 1;
		// change class
		findObj('prodName['+i+']').className = "textboxDisabled";
		findObj('productCode['+i+']').className = "textboxDisabled";
		findObj('imei['+i+']').className = "textboxDisabled";
		findObj('product_options['+i+']').className = "textboxDisabled";
		findObj('quantity['+i+']').className = "textboxDisabled";
		findObj('price['+i+']').className = "textboxDisabled";
		alert(messageRemove);
	}
	
	findObj('prodName['+i+']').disabled = result;
    findObj('prodName['+i+']').className = className;
	
	findObj('productCode['+i+']').disabled = result;
	findObj('productCode['+i+']').className = className;
	
	findObj('product_options['+i+']').disabled = result;
	findObj('product_options['+i+']').className = className;
	
	findObj('quantity['+i+']').disabled = result;
	findObj('quantity['+i+']').className = className;
	
	findObj('price['+i+']').disabled = result;
	findObj('price['+i+']').className = className;
}


function toggleProductStatus() {
	return false;
}

function goUrl(element) {
	var url = document.getElementById(element).options[document.getElementById(element).selectedIndex].value;
	window.location = url;
}



/* Start Cross-Browser DHTML Menu */
function showSubMenu() {
	var objThis = this;	
	for (var i = 0; i  < objThis.childNodes.length; i++) {
		if (objThis.childNodes.item(i).nodeName == "UL")	{							
			objThis.childNodes.item(i).style.display = "block";
		}		
	}	
}

function hideSubMenu() {								
	var objThis = this;	
	for (var i = 0; i  < objThis.childNodes.length; i++) {
		if (objThis.childNodes.item(i).nodeName == "UL") {				
			objThis.childNodes.item(i).style.display = "none";			
			return;
		}			
	}	
}			

function initialiseMenu() {
	var objLICollection = document.body.getElementsByTagName("LI");		
	for (var i = 0; i < objLICollection.length; i++) {
		var objLI = objLICollection[i];										
		for (var j = 0; j  < objLI.childNodes.length; j++) {
			if(objLI.childNodes.item(j).nodeName == "UL") {
				objLI.onmouseover=showSubMenu;
				objLI.onmouseout=hideSubMenu;
				for (var j = 0; j  < objLI.childNodes.length; j++) {
					if(objLI.childNodes.item(j).nodeName == "A") {
						objLI.childNodes.item(j).className = "hassubmenu";								
					}
				}
			}
		}
	}
}
/* End Cross-Browser DHTML Menu */

/* New Options code */
function optionEdit(assign_id, optionData) {
	var data		= optionData.split('|');
	var elements	= $('opt_mid').getElementsByTagName('option');
	for (i=0; i<elements.length; i++) {
		elements[i].removeAttribute('selected');
		
		if (data[1] == '0') {
			if (elements[i].value == data[0] && elements[i].getAttribute('class') == 'top') {
				elements[i].setAttribute('selected', 'selected');
				elements[i].selected = true;
			}
		} else {
			if (elements[i].value == data[1] && elements[i].getAttribute('class') == 'sub') {
				elements[i].setAttribute('selected', 'selected');
				elements[i].selected = true;
			}
		}
	}
	$('opt_price').value = data[2];	
	$('opt_assign_id').value = assign_id;
	
	optionRemove(assign_id, true);
}

function optionRemoveTemp(element) {
	var object = $(element).parentNode
	$('options_added').removeChild(object);
}

function optionRemove(id, preserve) {
	$('option_'+id).remove();
	if (!preserve) {
		new Insertion.Bottom($('options_added'), '<input type="hidden" name="option_remove[]" value="'+id+'"/>');
	}
}

function optionAdd() {
	
	var assign_id = $('opt_assign_id').value;
	
	var opt_top_title = $('opt_mid').options[$('opt_mid').selectedIndex].parentNode.getAttribute('label');
	var opt_mid_title = $('opt_mid').options[$('opt_mid').selectedIndex].innerHTML;
	
	var opt_mid_value	= $('opt_mid').options[$('opt_mid').selectedIndex].value;
	var opt_top_value	= $('opt_mid').options[$('opt_mid').selectedIndex].parentNode.id;
	
	var opt_price	= $('opt_price').value;
	opt_price		= (!opt_price) ? 0.00 :  opt_price;
	
	if (opt_top_value != '' && opt_mid_value != '') {
		if (assign_id != '0') {
			var method	= 'option_edit['+assign_id+']';
			$('opt_assign_id').value = '0';
		} else {
			var method	= 'option_add[]';
		}
		if (opt_top_title == null) opt_top_title = 'Custom';
		var hidden = '<input type="hidden" name="'+method+'" value="'+opt_top_value+'|'+opt_mid_value+'|'+opt_price+'"/>';
		new Insertion.Bottom('options_added', '<div style="clear: right;">'+hidden+'<span style="float: right;"><a href="#" onclick="optionRemoveTemp(this.parentNode); return false;"><img src="images/icons/delete.png" alt="delete" /></a></span><strong>'+opt_top_title+'</strong>: '+opt_mid_title+' ('+opt_price+')</div>');
		$('opt_price').value = '0.00';
	}
}
