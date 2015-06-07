// JavaScript Document
var storeUrl = $("#storeaddres").val();
var visit1 = '';
var visit2 = '';
var visit4 = '';
$(document).ready(function () {
extendbox();
});

var access_token = '';
window.fbAsyncInit = function () {
    // init the FB JS SDK
   FB.init({
       appId: $('#fbappid').val(),
       cookie: true,
       xfbml: true,
       oauth: true
    });
    FB.getLoginStatus(function (response) {
        // console.log(response);
        if (response.status === 'connected') {
            $('#fb-content-login').hide();
        } else if (response.status === 'not_authorized') {
             //  login();
        }
    }, true);
    FB.Event.subscribe('auth.authResponseChange', function (response) {
        getAlbumPhotos();
        getFriends();
    });
};
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;

    js.src = "//connect.facebook.net/en_US/all.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function extendbox(){
	 $(".grabbable").each( function(index){
		 div = document.createElement('div');
		 div.className = "draggable"
		 div.id = "draggable"+ index;
			 $(this).append(div);
		 
	 });
		$("#knockout").load(function(){
			$("#image_edit").css({
            'height': $(this).height(),
            'width': $(this).width(),
			});
		});
}

(function ($) {
    jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function (arg) {
        return function (elem) {
            return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    function listFilter(header, list) {
        var form = $("<form>").attr({
            "class": "filterform",
            "action": "#"
        }),
            input = $("<input>").attr({
                "class": "filterinput",
                "type": "text",
                "placeholder": "Search",
            });
        $(form).append(input).prependTo(header);
        $(input).change(function () {
            var filter = $(this).val();
            if (filter) {
                $(list).find("span:not(:Contains(" + filter + "))").closest('li').slideUp();
                $(list).find("span:Contains(" + filter + ")").closest('li').slideDown();
            } else {
                $(list).find("li").slideDown();
            }
            return false;
        }).keyup(function () {
            $(this).change();
        });
    }
    $(function () {
        listFilter($("#fb-friends-container"), $("#fb-friend-list"));
    });
}(jQuery));


function connectionerror(XMLHttpRequest, textStatus, errorThrown) {
    alert("System is unable to connect to server");
}

function successmodels(data) {
    var datavalue = data.split("::");
    if (datavalue[0] == 1) {
        if (datavalue[1] != '') {
            $('#boxes-area').html('');
            $('#knockout').attr('src', datavalue[1]);
            $('#case-price').html(datavalue[2]);
            $('#phone_model').html(datavalue[3]);
            $('#layoutbox').html(datavalue[4]);
            $('#boxes-area').html(datavalue[5]);
			if(datavalue[7] == 1)
			$('#casetype').html(datavalue[6]);
			if(datavalue[8] == 1){

				$("#image_edit").css('background','none');
			}else{
				$("#image_edit").css('background','url('+datavalue[8]+') no-repeat center');

			}
			$('#casewidth').val(datavalue[9]);
			$('#caseheight').val(datavalue[10]);
            $('#layoutbox > ul li:first > img').addClass('active-layout');
            callback_reload();
			extendbox();
  			editimage();
        } else {
            alert('Image not Available');
        }
    }
}

function loadlayout() {
    $("#layoutbox > ul > li > img").unbind('click').click(function () {
		if(visit2 == ''){
		$('.tab2 > a').click();
		visit2 = '1';
	}
        if ($(this).hasClass("active-layout")) {
            return;
        }
        var layout = this.id;
		thisid = this;
	var done = 0;
	$(".grabbable").each( function(){
	if($(this).find("img").length > 0){
		done = 1;
		}
	});
	if(done == 1){
		
	 $.colorbox({inline:true, href:".confirm-reset-design-popup", width: "40%"});
	 $('#cboxClose').hide();
	 $("#reset-done-btn").unbind('click').click(function (e) {
	changedesignlayout(layout,thisid);
		 
	 });
	 $("#reset-cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
	}else{
		changedesignlayout(layout,thisid);
	}
   
        

    });

}
function changedesignlayout(layout,thisid){
	$("#layoutbox > ul > li > img").removeClass('active-layout');
        $(thisid).addClass('active-layout');
		$('#cboxClose').click();
        $.ajax({
            type: "POST",
            url: storeUrl + "ajax/getlayout.php",
            data: "layoutid=" + layout,
            success: successlayout,
            error: connectionerror
        });
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
$(document).ready(function () {
    callback_reload();
    preloadfun();
	if(readCookie('visitdone') != 'done'){
	$('#next1').closest('div').removeClass('hide');
	document.cookie="visitdone=done";
	}

$("#next1").unbind('click').click(function (e) {
	e.preventDefault();
	$(this).closest('div').addClass('hide');
	$('#next2').closest('div').removeClass('hide');
});
$("#next2").unbind('click').click(function (e) {
	e.preventDefault();
	$(this).closest('div').addClass('hide');
	$('#next3').closest('div').removeClass('hide');
});
$("#next3").unbind('click').click(function (e) {
	e.preventDefault();
	$(this).closest('div').addClass('hide');
	$('#next4').closest('div').removeClass('hide');
});
$("#next4").unbind('click').click(function (e) {
	e.preventDefault();
	$(this).closest('div').addClass('hide');
});
$(".skip").unbind('click').click(function (e) {
	e.preventDefault();
	$(this).closest('div').addClass('hide');
});
$(".help").unbind('click').click(function (e) {
	e.preventDefault();
	$('#next1').closest('div').removeClass('hide');
});


$("#reset-design").unbind('click').click(function (e) {
	e.preventDefault();
	var done = 0;
	$(".grabbable").each( function(){
		if($(this).find("img").length > 0){
			done = 1;
		}
	});
	if(done == 1){
		
	 $.colorbox({inline:true, href:".confirm-reset-design-popup", width: "40%"});
	 $('#cboxClose').hide();
	 $("#reset-done-btn").unbind('click').click(function (e) {
		  document.location.reload();
		  $('#cboxClose').show();
	 });
	 $("#reset-cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
	}else{
		document.location.reload();
	}
});

    $("#save-design").unbind('click').click(function () {
        screenShot();
    });
 $("#auto_shuffle").unbind('click').click(function () {
        shuffleimages();
    });
	$("#stmp-back > a").unbind('click').click(function (e) {
		 e.preventDefault();
		 $('#stmp-image-box').hide();
		 $('#stamp-detail-list').show();
	});
	$("#stamp-detail-list >  li > a").unbind('click').click(function (e) {
		 e.preventDefault();
         $('#stamp-detail-list').hide();
		  $('#img-loader').show();
        var stmp = this.id;
$('#stmp-pictures').html('');
        $.ajax({
            type: "POST",
            url: storeUrl + "ajax/getstmpimg.php",
            data: "stmp=" + stmp,
			 error: connectionerror,
            success:  function (data) {
				//console.log(data);
               var datavalue = data.split("::");
    if (datavalue[0] == 1) {
		$('#stmp-image-box').show();
        $('#stmp-pictures').html(datavalue[1]);
		$('#img-loader').hide();
    }
            }
           
        });


    });
$("#phonecat > li > a").unbind('click').click(function () {
	var leng = $("#casetype li").length;
	if(visit1 == ''){
		//console.log(leng);
		if(leng > 1){
		$('.tab5 > a').click();
		}else{
			$('.tab6 > a').click();
		}
		visit1 = '1';
	}
    if ($(this).hasClass("active-model")) {
        return;
    }
    var model = this.id;
    src_array = model.split('-');
    model = src_array[1];
	thisid = this;
	var done = 0;
	$(".grabbable").each( function(){
		if($(this).find("img").length > 0){
			done = 1;
		}
	});
	if(done == 1){
		
	 $.colorbox({inline:true, href:".confirm-reset-design-popup", width: "40%"});
	 $('#cboxClose').hide();
	 $("#reset-done-btn").unbind('click').click(function (e) {
	changelayout(model,thisid);
		 
	 });
	 $("#reset-cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
	}else{
		changelayout(model,thisid);
	}
   
});
$("#casetype > li > a").live('click', function (e) {
	if(visit4 == ''){
		$('.tab6 > a').click();
		visit4 = '1';
	}
    if ($(this).parent().hasClass("active")) {
        return;
    }
    var model = this.id;
    src_array = model.split('-');
    model = src_array[1];
	thisid = this;
	var done = 0;
	$(".grabbable").each( function(){
		if($(this).find("img").length > 0){
			done = 1;
		}
	});
	if(done == 1){
		
	 $.colorbox({inline:true, href:".confirm-reset-design-popup", width: "40%"});
	 $('#cboxClose').hide();
	 $("#reset-done-btn").unbind('click').click(function (e) {
	changecase(model,thisid);
		 
	 });
	 $("#reset-cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
	}else{
		changecase(model,thisid);
	}
   
});
});
function changecase(model,thisid){
	$("#casetype > li").removeClass('active');
    $(thisid).parent().addClass('active');
	 $('#cboxClose').show();
	 $('#cboxClose').click();
    $.ajax({
        type: "POST",
        url: storeUrl + "ajax/getmodel.php",
        data: "modelid=" + model,
        success: successmodels,
        error: connectionerror
    });
}
function changelayout(model,thisid){
	$("#phonecat > li > a").removeClass('active-model');
    $(thisid).addClass('active-model');
	 $('#cboxClose').show();
	 $('#cboxClose').click();
    $.ajax({
        type: "POST",
        url: storeUrl + "ajax/getmodel.php",
        data: "modelid=" + model,
        success: successmodels,
        error: connectionerror
    });
}
function successlayout(data) {
    //alert(data);
    var datavalue = data.split("::");
    if (datavalue[0] == 1) {
        $('#boxes-area').html(datavalue[1]);
    }
	extendbox();
    editimage();
	
}

function removeimage() {
    $("#user-photos > ul li > div > i").unbind('click').click(function () {
        var photoid = this.id;
        $.ajax({
            type: "POST",
            url: storeUrl + "ajax/removephoto.php",
            data: "photoid=" + photoid,
            error: connectionerror,
            success: function (data) {
                if (data == 1) {
                    $("#userphoto-" + photoid).closest('li').remove();
                    callback_reload();
                }
            }

        });
    });
}

function reloaduserphotos(userfolder) {
    $.ajax({
        type: "POST",
        url: storeUrl + "ajax/updateuserphotos.php",
        data: "userfolder=" + userfolder,
        error: connectionerror,
        success: function (data) {
            var datavalue = data.split("::");
            if (datavalue[0] == 1) {
                $('#user-photos').html(datavalue[1]);
                callback_reload();
            }
        }

    });
}

function callback_reload() {
    loadlayout();
    editimage();

    removeimage();
    autoloadimage();
    preloadfun();
}

function preloadfun() {
    $("#remove-image").unbind('click').click(function () {
        var activeimg = $('.editable').attr('id');
		
        $('.editable').dblclick();
		$("#" + activeimg).prev().remove();
        $("#" + activeimg).prev().removeClass("imageloaded");

        $("#" + activeimg).prev().parent().css({
            'border': '',
            'height': '+=-2px',
            'width': '+=-2px',
            'z-index': '55'
        });
        $("#" + activeimg).remove();
    });
    $("#cancel-edit").unbind('click').click(function () {
        $('.editable').dblclick();
    });
    $("#done-edit").unbind('click').click(function () {
        $('.editable').dblclick();
    });

$("#filter-list-container li > a > div").unbind("click").unbind('click').click(function (e) {
	$('#filter-list-container > li a div').removeClass('active-filter');
$(this).addClass('active-filter');
	
	var effect = $(this).attr('class').split(" ")[0];
	appyfilter(effect);
	 e.preventDefault();
});

} 
function appyfilter(effect){
	if(effect == 'vintage-color'){
		
		 var options = {
        onError: function() {
            alert('ERROR');
        }
    };
    var effect = {
              
    curves: (function() {
      var rgb = function (x) {
        return -12 * Math.sin( x * 2 * Math.PI / 255 ) + x;
      },
      r = function(x) {
        return -0.2 * Math.pow(255 * x, 0.5) * Math.sin(Math.PI * (-0.0000195 * Math.pow(x, 2) + 0.0125 * x ) ) + x;
      },
      g = function(x) {
        return -0.001045244139166791 * Math.pow(x,2) + 1.2665372554875318 * x;
      },
      b = function(x) {
        return 0.57254902 * x + 53;
      },
      c = {r:[],g:[],b:[]};
      for(var i=0;i<=255;++i) {
        c.r[i] = r( rgb(i) );
        c.g[i] = g( rgb(i) );
        c.b[i] = b( rgb(i) );
      }
      return c;
    })(),
    screen: {
      r: 227,
      g: 12,
      b: 169,
      a: 0.15
    },
    vignette: 0.7,
    viewFinder: false // or path to image 'img/viewfinder.jpg'
 
            };
			
				 $(".grabbable").unbind("each").each( function(){
            var currid = jQuery(this).find("img").next().attr('id');
			 var m_parent = jQuery(this).children("div").attr('id');
			  if ($('#'+m_parent).hasClass("imageloaded")) {
			 $("#"+m_parent).css('background-color','rgba(0, 0, 0, 0.3)');
		$("#"+m_parent).html('<div class="spinner-wrap absolute "><div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div></div>');
			var vjsAPI = $('#'+currid).data('vintageJS');
			
if(! vjsAPI){
	
	$('img#'+currid).vintage(options, effect);
  			
 }else{
			vjsAPI.vintage(effect);
			}
			  }
        });
  
   
        
   

		
		}else if(effect == 'sepia-color'){
		
		 var options = {
        onError: function() {
            alert('ERROR');
        }
    };
    var effect = {
    curves: (function() {
      var rgb = function (x) {
        return -12 * Math.sin( x * 2 * Math.PI / 255 ) + x;
      },
      c = {r:[],g:[],b:[]};
      for(var i=0;i<=255;++i) {
        c.r[i] = rgb(i);
        c.g[i] = rgb(i);
        c.b[i] = rgb(i);
      }
      return c;
    })(),
    sepia: true
  };
			 $(".grabbable").unbind("each").each( function(){
            var currid = jQuery(this).find("img").next().attr('id');
			 var m_parent = jQuery(this).children("div").attr('id');
			 if ($('#'+m_parent).hasClass("imageloaded")) {
			  $("#"+m_parent).css('background-color','rgba(0, 0, 0, 0.3)');
		$("#"+m_parent).html('<div class="spinner-wrap absolute "><div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div></div>');
			var vjsAPI = $('#'+currid).data('vintageJS');
			
if(! vjsAPI){
	$('img#'+currid).vintage(options, effect);
  			
 }else{
			vjsAPI.vintage(effect);
			}
			 }
        });

		}
		else if(effect == 'greenish-color'){
		
		 var options = {
        onError: function() {
            alert('ERROR');
        }
    };
    var effect = {
    curves: (function() {
      var rgb = function (x) {
        return -12 * Math.sin( x * 2 * Math.PI / 255 ) + x;
      },
      c = {r:[],g:[],b:[]};
      for(var i=0;i<=255;++i) {
        c.r[i] = rgb(i);
        c.g[i] = rgb(i);
        c.b[i] = rgb(i);
      }
      return c;
    })(),
    vignette: 0.6,
    lighten: 0.1,
    screen: {
      r: 255,
      g: 255,
      b: 0,
      a: 0.15
    }
  };
			 $(".grabbable").unbind("each").each( function(){
            var currid = jQuery(this).find("img").next().attr('id');
			 var m_parent = jQuery(this).children("div").attr('id');
			 if ($('#'+m_parent).hasClass("imageloaded")) {
			  $("#"+m_parent).css('background-color','rgba(0, 0, 0, 0.3)');
		$("#"+m_parent).html('<div class="spinner-wrap absolute "><div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div></div>');
			var vjsAPI = $('#'+currid).data('vintageJS');
			
if(! vjsAPI){
	
	$('img#'+currid).vintage(options, effect);
  			
 }else{
			vjsAPI.vintage(effect);
			}
			 }
        });

		
		}else if(effect == 'reddish-color'){
		
		 var options = {
        onError: function() {
            alert('ERROR');
        }
    };
    var effect = {
    curves: (function() {
      var rgb = function (x) {
        return -12 * Math.sin( x * 2 * Math.PI / 255 ) + x;
      },
      c = {r:[],g:[],b:[]};
      for(var i=0;i<=255;++i) {
        c.r[i] = rgb(i);
        c.g[i] = rgb(i);
        c.b[i] = rgb(i);
      }
      return c;
    })(),
    vignette: 0.6,
    lighten: 0.1,
    screen: {
      r: 255,
      g: 0,
      b: 0,
      a: 0.15
    }
  };
			 $(".grabbable").unbind("each").each( function(){
            var currid = jQuery(this).find("img").next().attr('id');
			 var m_parent = jQuery(this).children("div").attr('id');
			 if ($('#'+m_parent).hasClass("imageloaded")) {
			  $("#"+m_parent).css('background-color','rgba(0, 0, 0, 0.3)');
		$("#"+m_parent).html('<div class="spinner-wrap absolute "><div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div></div>');
			var vjsAPI = $('#'+currid).data('vintageJS');
			
if(! vjsAPI){
	
	$('img#'+currid).vintage(options, effect);
  			
 }else{
			vjsAPI.vintage(effect);
			}
			 }
        });

		}
		else if(effect == 'none-color'){
		
		
			 $('.grabbable').each(function () {
            var currid = jQuery(this).find("img").next().attr('id');
			var vjsAPI = $('#'+currid).data('vintageJS');
			if(! vjsAPI){
			}else{
			//console.log(vjsAPI) 
 vjsAPI.reset();
			}
        });

		}
}
function autoloadimage() {
    $("#user-photos > ul li > div > img, #pictures > ul > li > div > img, #fb-friend-album_pictures > ul > li > div > img, #ig-friend-album_pictures > ul > li > div > img, #ig-pictures > ul > li > div > img, #stmp-pictures li > div > img").unbind('dblclick').live('dblclick', function () {
        src = $('#' + this.id).attr('source');

        $('.grabbable').each(function () {
            var currid = jQuery(this).children("div").attr('id');
            if (!$("#" + currid).hasClass("imageloaded") && $("#" + currid).hasClass("draggable")) {
                m_parent = currid;
                return false;
            }
        });
        ImageController.loadPhoto(src, m_parent);

    });
}
function shuffleimages() {
	var activealbum = '';
	$('.social-pics').each(function () {
		if($(this).is(':visible')){
			 activealbum = this;
		
		}
	});
	if(activealbum == ''){
		$("#toaster-text").html('Please Select An Album');
	 $("#toaster").show();
	setTimeout( function(){
    $("#toaster").hide();
  }, 2000);
	return;
	}else{
	var done = 0;
	$(".grabbable").each( function(){
		if($(this).find("img").length > 0){
			done = 1;
		}
	});
	if(done == 1){
		
	 $.colorbox({inline:true, href:".confirm-reset-design-popup", width: "40%"});
	 $('#cboxClose').hide();
	 $("#reset-done-btn").unbind('click').click(function (e) {
		 var imgArr = $(activealbum).find(".dragable-image").map(function () {
            return this.id;
        }).get();
        $('.grabbable').each(function () {
            loopimg = imgArr[Math.floor(Math.random() * imgArr.length)];
            src = $('#' + loopimg).attr('source');

            var currid = jQuery(this).children("div").attr('id');

            m_parent = currid;
            ImageController.loadPhoto(src, m_parent);

        });
		$('#cboxClose').click();
		  $('#cboxClose').show();
	 });
	 $("#reset-cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
	}else{
        var imgArr = $(activealbum).find(".dragable-image").map(function () {
            return this.id;
        }).get();
        $('.grabbable').each(function () {
            loopimg = imgArr[Math.floor(Math.random() * imgArr.length)];
            src = $('#' + loopimg).attr('source');

            var currid = jQuery(this).children("div").attr('id');

            m_parent = currid;
            ImageController.loadPhoto(src, m_parent);

        });
	}

	}
}



function checkIfArrayIsUnique(arr) {
    var map = {}, i, size;

    for (i = 0, size = arr.length; i < size; i++){
        if (map[arr[i]]){
            return false;
        }

        map[arr[i]] = true;
    }

    return true;
}
function screenShot(id) {
    cur_path = document.getElementById('ccuser').value;
	handset = $("#phone_model").text();
	handsetid = $("#casetype").find('.active').children().attr('id');
	handsetid = handsetid.split("-");
	handsetid = handsetid[1];
	var done = 1;
	$(".grabbable").each( function(){
		if($(this).find("img").length == 0){
			done = 0;
		}
	});
	
	var imgArr = $(".draggable2").map(function () {
            src=  this.id;
			return $('#'+src).attr('src');
        }).get();
		var uniquearr = checkIfArrayIsUnique(imgArr);
		if(uniquearr == false){
			$.colorbox({inline:true, href:".confirm-design-popup", width: "40%"});
			$('#cboxClose').hide();
			
		}else{
			$('#cboxClose').click();
			donescreenshot(handset,handsetid, done );
			
		}
$("#cancel-btn").unbind('click').click(function () {
	$('#cboxClose').click();
});
$("#done-btn").unbind("click").click(function (e) {
	$('#cboxClose').click();
	donescreenshot(handset,handsetid, done );
});
}
function donescreenshot(handset,handsetid, done){	
if(done !=0){
	$("#progress-bar").css({
		'width': '1%',
     });
	 $("#progress-text").text('1%');
	 $('#save-design-preview').attr('src', '') ;
	$("#progress-bar-container").removeClass('hide');
	$(".saving-overlay").removeClass('hide');
    html2canvas([document.getElementById('phone')], {
       // proxy: "ajax/html2canvasproxy.php",
        useCORS: true,
        onrendered: function (canvas) {

            var img = canvas.toDataURL("image/png");

            var output = img.replace(/^data:image\/(png|jpg);base64,/, "");
            output = encodeURIComponent(img);
			  html2canvas([document.getElementById('boxes-area')], {
      // proxy: "ajax/html2canvasproxy.php",
        useCORS: true,
        onrendered: function (canvas) {
		var width = $('#casewidth').val();
		var height = $('#caseheight').val();
			var extra_canvas = document.createElement("canvas");
                extra_canvas.setAttribute('width',width);
                extra_canvas.setAttribute('height',height);
               var ctx = extra_canvas.getContext('2d');
                ctx.drawImage(canvas,0,0,canvas.width, canvas.height,0,0,width,height);
                var dataURL = extra_canvas.toDataURL("image/png");
				var output2 = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
            	 output2 = encodeURIComponent(dataURL);
				 
				
           	 var Parameters = "image=" + output + "&image2=" + output2  +"&filedir=" + cur_path+ "&phone=" + handset+ "&handsetid=" + handsetid;
			 generatecanvasimage(Parameters);
        }
    });
        }
    });
	
      
}else{
	$("#toaster-text").html('Please Complete Design before save');
	 $("#toaster").show();
	setTimeout( function(){
    $("#toaster").hide();
  }, 2000);
}
}
function generatecanvasimage(Parameters){
	
	      $.ajax({
			  xhr: function()
    {
      var xhr = new window.XMLHttpRequest();
      //Upload progress
      xhr.upload.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total * 100;
		  var percentComplete = percentComplete.toString().split('.')[0];
		  $("#loader").hide();;
          //Do something with upload progress
		  $("#progress-bar").css({
		'width': percentComplete+'%',
     });
$("#progress-text").text(percentComplete+'%');
       }
     }, false);
     //Download progress
     xhr.addEventListener("progress", function(evt){
       if (evt.lengthComputable) {
         var percentComplete = evt.loaded / evt.total * 100;
         //Do something with download progress
        // console.log(percentComplete);
       }
     }, false);
     return xhr;
   },
                type: "POST",
                url: storeUrl + "ajax/savePNG.php",
                data: Parameters,
                success: function (data) {
                   $("#progress-bar-container").addClass('hide');
	$(".saving-overlay").addClass('hide');
                    var datavalue = data.split("::");
                    if (datavalue[0] == 1) {
						$('#save-design-preview').attr('src', datavalue[1]) ;
						$('#cboxClose').show();
						$.colorbox({inline:true, href:".save-design-popup", width: "60%", height: "465px"});
						$("#fb-share-btn").unbind("click").click(function (e) {
							//console.log(datavalue[1]);
							FB.ui({
							  method: "feed",
							  display: 'popup',
								link: datavalue[3],
								picture:  $('#save-design-preview').prop('src'),
								name: 'Check out my Caseprint',
								description: 'Make your case with Facebook, Instagram photos'
							}, function(response){});
						});
						$("#twitter-share-btn").unbind("click").click(function (e) {
							 window.open("http://www.twitter.com/share?url=" + encodeURIComponent(datavalue[3]) + "&text=" + encodeURIComponent('Check out my new @PhotoCase using Instagram & Facebook photos. Make yours and get $5 off:'), "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600");
        
						});
						$("#email-share-btn").unbind("click").click(function (e) {
								e.stopPropagation();
								$("#share-with-mail-input").toggleClass('hide');
								$("#share-with-mail-btn").unbind("click").click(function (e) {
								 email = $("#share-with-mail-text").val();
								 istrue = validateEmail(email);
								if (istrue == true){
									 $.ajax({
									type: "POST",
									url: storeUrl + "ajax/Artwork.php",
									data: "designid=" + datavalue[2] + "&email=" + email + "&image=" + $('#save-design-preview').prop('src')+ "&designname=" + datavalue[4],
									success: function (data) {
									   
										var datavalue = data.split("::");
										if (datavalue[0] == 1) {
											$("#share-with-mail-input").toggleClass('hide');
											$("#share-with-mail-text").val('');
										}
									}
									});
									 
								}else{
									$("#toaster-text").html('Invalid Email Address');
											 $("#toaster").show();
											setTimeout( function(){
											$("#toaster").hide();
										  }, 2000);
								}
						});		
						});
                       
                    }
                }
            })
}

function login() {
    FB.login(function (response) {
        // alert(response.authResponse.accessToken);
        access_token = response.authResponse.accessToken;
        $('#fb-content-login').hide();
    }, {
        scope: 'email,offline_access,user_photos,friends_photos'

    });
}

function getFriends() {

    FB.api('/me/friends?fields=name,picture', function (resp) {
//console.log(resp);
        for (var i = 0, l = resp.data.length; i < l; i++) {

            $('#fb-friend-list').append('<li ><a href="javascript:;" class="friends-list" id="' + resp.data[i].id + '"><img alt="' + resp.data[i].name + '" src="' + resp.data[i].picture.data.url + '" class="avatar vertical-middle inline" id="' + resp.data[i].id + '"><span class="vertical-middle inline text-default ml1">' + resp.data[i].name + '</span></a></li>');
        }
    });

};

function getAlbumPhotos() {
    $('#fb-album-container').html();
	  FB.api('/me/picture?type=small', function (resp) {
		  $('#fb-your-profile > a > img').attr('src', resp.data.url);
		 
	  });
    FB.api('/me/albums', function (resp) {



        for (var i = 0, l = resp.data.length; i < l; i++) {

            album = resp.data[i];
            // console.log(album);
            getalbumcover(album);
        }
        $('#album-area').show();
    });

};


function getalbumcover(album) {
    FB.api('/' + album.id + '/picture?type=album', function (cover) {
        $('#fb-album-container').append('<li class="column6 mt1 text-center"><a href="javascript:;" class="album-pics" id="' + album.id + '"><div class="inline relative"  id="album-' + album.cover_photo + '"><span class="white-color inline relative"><img alt="' + album.name + '" src="' + cover.data.url + '" class="s-pic vertical-bottom" id="' + album.id + '"></span></div><span class="inline mt1 text-default ellipsis" id="' + album.id + '">' + album.name + '</span></a></li>');

    });

}

function get_friend_album(friend) {

    $('#fb-friend-album').html('');
    FB.api('/' + friend + '/albums', function (resp) {



        for (var i = 0, l = resp.data.length; i < l; i++) {

            album = resp.data[i];
            // console.log(album);
            getfriendalbumcover(album);
        }
        $('#img-loader').hide();
    });

};


function getfriendalbumcover(album) {
    FB.api('/' + album.id + '/picture', function (cover) {
        $('#fb-friend-album').append('<li class="column6 mt1 text-center"><a href="javascript:;" class="album-pics" id="' + album.id + '"><div class="inline relative"  id="album-' + album.cover_photo + '"><span class="white-color inline relative"><img alt="' + album.name + '" src="' + cover.data.url + '" class="s-pic vertical-bottom" id="' + album.id + '"></span></div><span class="inline mt1 text-default ellipsis" id="' + album.id + '">' + album.name + '</span></a></li>');

    });

}

function get_photo(album_id) {
    FB.api('/' + album_id + '/photos', function (resp) {
        $('#pictures').html('');
     loadfbalbumimages(resp);
    });
}
function loadfbalbumimages(resp){
	
	   var picture = document.getElementById('pictures');
		var ul = document.createElement('ul');
		ul.className = 'social-pics';
		picture.appendChild(ul);
		 var maxWidth = 150;
        var ratio = 0;
        for (var i = 0; i < resp.data.length; i++) {
            //console.log(resp.data[i].picture);
li = document.createElement('li'),
li.className = 'column4';
div = document.createElement('div'),
            img = document.createElement('img');
            img.src = resp.data[i].picture;
            img.className = "dragable-image";
            img.setAttribute("source", resp.data[i].source);
            img.ondragstart = function (event) {
                drag(event)
            };
            img.id = resp.data[i].id;
			
			img.onload = function() {
				$(this).next().addClass('hide');
    } ;

            div.appendChild(img);
			spinner = document.createElement('div'),
			spinner.className = 'spinner-wrap absolute';
			 spinner.innerHTML = '<div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div>';
			 div.appendChild(spinner);
			 li.appendChild(div);
			 ul.appendChild(li);
            $('#img-loader').hide();
        }
		$('#img-loader').hide();
			$('#pictures').unbind('scroll');
        if (resp.paging.cursors.after != null) {
            $('#pictures').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					 $('#img-loader').show();
					 $.getJSON(resp.paging.next, function(response){          
            loadfbalbumimages(response);
        });
       
                }
            });
        }
        callback_reload();
}
function get_friend_photo(album_id) {
    FB.api('/' + album_id + '/photos', function (resp) {
   
        $('#fb-friend-album_pictures').html('');
       loadfrfrndalpic(resp);
    });
}
function loadfrfrndalpic(resp){
	console.log(resp);
	 var picture = document.getElementById('fb-friend-album_pictures');
		var ul = document.createElement('ul');
		ul.className = 'social-pics';
		picture.appendChild(ul);
        for (var i = 0; i < resp.data.length; i++) {
           li = document.createElement('li'),
li.className = 'column4';
div = document.createElement('div'),
            img = document.createElement('img');
            img.src = resp.data[i].picture;
            img.setAttribute("source", resp.data[i].source);
            img.className = "dragable-image";
            img.ondragstart = function (event) {
                drag(event)
            };
            img.id = resp.data[i].id;
           img.onload = function() {
				$(this).next().addClass('hide');
    } ;

            div.appendChild(img);
			spinner = document.createElement('div'),
			spinner.className = 'spinner-wrap absolute';
			 spinner.innerHTML = '<div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div>';
			 div.appendChild(spinner);
			 li.appendChild(div);
			 ul.appendChild(li);
            $('#img-loader').hide();
        }
			$('#img-loader').hide();
			$('#fb-friend-album-container').unbind('scroll');
        if (resp.paging.next != null) {
            $('#fb-friend-album-container').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					 $('#img-loader').show();
					 $.getJSON(resp.paging.next, function(response){          
            loadfrfrndalpic(response);
        });
       
                }
            });
        }
        callback_reload();
}

function Logout() {
    FB.logout(function () {
        document.location.reload();
    });
}

$(document).ready(function () {


    $('#facebook-login > a').click('click', function (e) {

        login();
        return false;
        e.preventDefault();
    });
    $('#instagram-login > a').click('click', function (e) {

        insta_login();
        return false;
        e.preventDefault();
    });
});
$(document).ready(function () {
    $('#fb-album-container > li > a').live('click', function (e) {
        $('#fb-album-container').hide();
        $('#fb-image-box').show();
        $('#img-loader').show();
        get_photo($(this).attr('id'));
        return false;
        e.preventDefault();
    });
    $('#fb-friend-album > li > a').live('click', function (e) {
        $('#fb-friend-album').hide();
        $('#fb-friend-album_pictures').show();
        $('#img-loader').show();
        $('#fb-back-to-friend-list').hide();
        $('#fb-back-to-friend-album').show();
        get_friend_photo($(this).attr('id'));
        return false;
        e.preventDefault();
    });
    $('#fb-friend-list > li > a').live('click', function (e) {
        $('#fb-friends-container').hide();
        $('#fb-friend-album-container').show();
        $('#fb-back-to-friend-list').show();
        $('#fb-friend-album').show();

        $('#img-loader').show();
        get_friend_album($(this).attr('id'));
        return false;
        e.preventDefault();
    });
    $('#fb-back > a').on('click', function (e) {
        $('#fb-image-box').hide();
        $('#pictures').html('');
        $('#fb-album-container').show();

        return false;
        e.preventDefault();
    });
    $('#fb-back-to-friend-list > a').on('click', function (e) {
        $('#fb-friend-album-container').hide();
        $('#fb-back-to-friend-list').hide();
        $('#fb-friend-album').html('');
        $('#fb-friends-container').show();

        return false;
        e.preventDefault();
    });
    $('#fb-back-to-friend-album > a').on('click', function (e) {
        $('#fb-friend-album_pictures').hide();
        $('#fb-friend-album_pictures').html('');
        $('#fb-back-to-friend-list').show();
        $('#fb-friend-album').show();
        $('#fb-back-to-friend-album').hide();

        return false;
        e.preventDefault();
    });
   // $(".grabbable").css({
//        'z-index': '55',
//    });
    $('#fb-your-profile').on('click', function (e) {
        $('#fb-friends-container').hide();
        $('#fb-friend-album-container').hide();
        $('#fb-image-box').hide();
        $('#fb-album-container').show();
        return false;
        e.preventDefault();

    });
    $('#fb-your-friends').on('click', function (e) {
        $('#fb-album-container').hide();
        $('#fb-image-box').hide();
        $('#fb-friend-album').hide();
        $('#fb-back-to-friend-album').hide();
        $('#fb-friend-album-container').hide();
        $('#fb-friend-album_pictures').hide();
        $('#fb-friends-container').show();
        return false;
        e.preventDefault();

    });
    $('#ig-your-profile').on('click', function (e) {
        $('#ig-friends-container').hide();
        $('#ig-friend-album-container').hide();
        $('#ig-friend-pictures-container').hide();

        $('#ig-image-box').show();
        return false;
        e.preventDefault();

    });
    $('#ig-your-friends').on('click', function (e) {
        $('#ig-image-box').hide();

 $('#img-loader').show();
       
        if ($('ul#ig-friend-list li').length < 1) {
			 $('#ig-friends-container').show();
			 $('#ig-friend-list').html('');
            getFollowers('');
        }
        return false;
        e.preventDefault();

    });
	
})
$('#ig-friend-list > li > a').live('click', function (e) {
	 $('#img-loader').show();
    $('#ig-friends-container').hide();
    $('#ig-friend-pictures-container').show();
    $('#ig-back-to-friend-list').show();
    $('#ig-friend-album_pictures').show();
    $('#ig-friend-album_pictures').html('');
    $('#img-loader').show();
    getPhotosFromFollow($(this).attr('id'), '');
    return false;
    e.preventDefault();
});
$('#ig-back-to-friend-list > a').live('click', function (e) {
    $('#ig-friend-pictures-container').hide();
    $('#ig-friend-album_pictures').html('');
    $('#ig-friends-container').show();


    return false;
    e.preventDefault();
});
/////// instagram code
var ACCESS_TOKEN;
var USER_ID;
var ig;

function insta_login() {
    if (!(window.localStorage && window.localStorage.getItem('ig_token'))) {

        var igloginwin = window.open('ig/ig.html', 'iglogin', 'width=480,height=370');
    } else {
        insta_init();
    }
}
$(document).ready(function () {
    if (!(window.localStorage && window.localStorage.getItem('ig_token'))) {
        //do nothing
    } else {
		 $('#img-loader').show();
        insta_init();
    }
});

function insta_init() {



    if (window.localStorage && window.localStorage.getItem('ig_token')) {
        $("#ig-content-login").hide();
        ig = new Instagram();
        ig.setOptions({
            token: window.localStorage.getItem('ig_token')
        });
        if (!USER_ID) {
            ig.currentUser(function (res) {
                USER_ID = res.data.id;
				img = document.createElement('img');
           		 img.src = res.data.profile_picture;
            img.className = "avatar";

            $("#ig-your-profile > a").prepend(img);
			document.getElementById('ig-pictures').innerHTML = '';
                getRecentPhotos('');
            });
        }
    }

}

function getRecentPhotos(next_max_id) {
    if (!(ig && USER_ID)) return;
    user_id = USER_ID;
    document.getElementById('ig-photo-area').style.display = 'block';
    
    var picture = document.getElementById('ig-pictures');
	var ul = document.createElement('ul');
		ul.className = 'social-pics';
		picture.appendChild(ul);
    ig.getPhotos(user_id, function (resp) {
        for (var i = 0; i < resp.data.length; i++) {
            li = document.createElement('li'),
li.className = 'column4';
div = document.createElement('div'),
            img = document.createElement('img');
            img.src = resp.data[i].images.thumbnail.url;
            img.className = "dragable-image";
            img.setAttribute("source", resp.data[i].images.standard_resolution.url);
             img.ondragstart = function (event) {
                drag(event)
            };
            img.id = resp.data[i].id;

			img.onload = function() {
				$(this).next().addClass('hide');
    } ;

            div.appendChild(img);
			spinner = document.createElement('div'),
			spinner.className = 'spinner-wrap absolute';
			 spinner.innerHTML = '<div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div>';
			 div.appendChild(spinner);
			 li.appendChild(div);
			 ul.appendChild(li);
        }   
		$('#ig-pictures').unbind('scroll');
        if (resp.pagination.next_max_id != null) {
            $('#ig-pictures').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					 $('#img-loader').show();
                    getRecentPhotos(resp.pagination.next_max_id);
                }
            });
        }
        
callback_reload();
    },next_max_id);
	$('#img-loader').hide();

		
}

function getLikedPhotos(obj) {
    if (!(ig && USER_ID)) return;
    user_id = USER_ID;
    document.getElementById('ins_article_div').style.display = 'block';
    document.getElementById('ins_friend_list').innerHTML = '';
    liItem = jQuery(obj);
    ig.getLiked(function (data) {
        addhtml = '';
        liItem.children('span').html(data.data.length);
        for (var i = 0; i < data.data.length; i++) {
            addhtml += "<li ondblclick='show_facebook_big_img(\"" + data.data[i].images.standard_resolution.url + "\")' ref='" + data.data[i].images.standard_resolution.url + "'><img onload='showthisimg(this)' src='" + data.data[i].images.thumbnail.url + "'/></li>"
        }
        inslist = jQuery("#ins_photos_list");
        inslist.html(addhtml);
        setSelectAble(inslist);
        document.getElementById('ins_article_div').style.display = 'none'
    });
}

function getFollowers(next_cursor) {
    
    if (!(ig && USER_ID)) return;
    user_id = USER_ID;

    ig.getFans2(user_id, function (resp) {
        for (var i = 0; i < resp.data.length; i++) {
            $('#ig-friend-list').append('<li ><a href="javascript:;" class="friends-list" id="' + resp.data[i].id + '"><img alt="' + resp.data[i].full_name + '" src="' + resp.data[i].profile_picture + '" class="avatar vertical-middle inline" id="' + resp.data[i].id + '"><span class="vertical-middle inline text-default ml1">' + resp.data[i].full_name + '</span></a></li>');




        }
 $('#img-loader').hide();
    $('#ig-friends-container').unbind('scroll');
        if (resp.pagination.next_cursor != null) {
            $('#ig-friends-container').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					 $('#img-loader').show();
                    getFollowers(resp.pagination.next_cursor);
                }
            });
        }
    },next_cursor);
}

function getPhotosFromFollow(follow_id, next_max_id) {
    if (!(ig && USER_ID)) return;

    ig.getPhotos(follow_id, function (data) {
        var picture = document.getElementById('ig-friend-album_pictures');
		var ul = document.createElement('ul');
		ul.className = 'social-pics';
		picture.appendChild(ul);
		img = document.createElement('img');
           		 img.src = data.data[0].user.profile_picture;
            img.className = "avatar";
			$("#ig-your-friends > a > img").remove();
            $("#ig-your-friends > a").prepend(img);
        for (var i = 0; i < data.data.length; i++) {



            li = document.createElement('li'),
li.className = 'column4';
div = document.createElement('div'),

            img = document.createElement('img');
            img.src = data.data[i].images.thumbnail.url;
            img.className = "dragable-image";
            img.setAttribute("source", data.data[i].images.standard_resolution.url);
             img.ondragstart = function (event) {
                drag(event)
            };
            img.id = data.data[i].id;

			img.onload = function() {
				$(this).next().addClass('hide');
    } ;

            div.appendChild(img);
			spinner = document.createElement('div'),
			spinner.className = 'spinner-wrap absolute';
			 spinner.innerHTML = '<div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div>';
			 div.appendChild(spinner);
			 li.appendChild(div);
			 ul.appendChild(li);

        }
        $('#ig-friend-pictures-container').unbind('scroll');
        if (data.pagination.next_max_id != null) {
            $('#ig-friend-pictures-container').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					 $('#img-loader').show();
                    getPhotosFromFollow(follow_id, data.pagination.next_max_id);
                }
            });
        }
		 $('#img-loader').hide();
		 callback_reload();
    }, next_max_id);


}


(function ($, exports) {
    var Instagram;
    Instagram = (function () {
        Instagram.prototype.api = '/ig/ajax.php';
        Instagram.prototype.endPoint = 'https://instagram.com/oauth/authorize/?';

        function Instagram() {}
        Instagram.prototype.auth = function (options) {
            var params;
            params = '';
            $.each(options, function (key, value) {
                return params += key + '=' + value + '&';
            });
            this.authUri = this.endPoint + params;
            return window.location.href = this.authUri;
        };
        Instagram.prototype.getToken = function () {
            return window.location.hash.replace('#access_token=', '');
        };
        Instagram.prototype.setOptions = function (options) {
            var self;
            self = this;
            return $.each(options, function (key, value) {
                return self[key] = value;
            });
        };
        Instagram.prototype.fetch = function (url, callback, params, method) {
            var ajaxData, data;
            data = {};
            if (this.token) {
                data['access_token'] = this.token;
            }
            if (this.client_id) {
                data['client_id'] = this.client_id;
            }
            if (params != '') {
                data['max_id'] = params;
            }
            ajaxData = {
                url: this.api,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: method || 'GET',
                    url: url,
                    params: $.extend({}, data, params)
                },
                success: function (res) {
                    var code;
                    code = res.result.meta.code;
                    switch (code) {
                    case '200':
                        callback(res.data);
                        break;
                    case '400':
                        console.log;
                    }
                    return callback(res.result);
                }
            };
            return $.ajax(ajaxData);
        };
		Instagram.prototype.fetch2 = function (url, callback, params, method) {
            var ajaxData, data;
            data = {};
            if (this.token) {
                data['access_token'] = this.token;
            }
            if (this.client_id) {
                data['client_id'] = this.client_id;
            }
            if (params != '') {
                data['cursor'] = params;
            }
            ajaxData = {
                url: this.api,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: method || 'GET',
                    url: url,
                    params: $.extend({}, data, params)
                },
                success: function (res) {
                    var code;
                    code = res.result.meta.code;
                    switch (code) {
                    case '200':
                        callback(res.data);
                        break;
                    case '400':
                        console.log;
                    }
                    return callback(res.result);
                }
            };
            return $.ajax(ajaxData);
        };
        Instagram.prototype.currentUser = function (callback) {
            return this.fetch('/users/self', callback);
        };
        Instagram.prototype.getFeeds = function (callback, params) {
            return this.fetch('/users/self/feed', callback, params);
        };
        Instagram.prototype.getLiked = function (callback, params) {
            return this.fetch('/users/self/media/liked', callback, params);
        };
        Instagram.prototype.getReqs = function (callback) {
            return this.fetch('/users/self/requested-by', callback);
        };
        Instagram.prototype.getIdByName = function (name, callback) {
            return this.searchUser(name, function (res) {
                var lists, obj;
                lists = res.data;
                name = name.toLowerCase();
                if (lists) {
                    obj = lists[0];
                }
                if (obj && obj['username'] === name) {
                    return callback(obj['id']);
                } else {
                    return callback(false);
                }
            });
        };
        Instagram.prototype.getUser = function (id, callback) {
            return this.fetch('/users/' + id, callback);
        };
        Instagram.prototype.getUserByName = function (name, callback) {
            var self;
            self = this;
            return this.getIdByName(name, function (id) {
                if (id) {
                    return self.getUser(id, function (res) {
                        return callback(res);
                    });
                }
            });
        };
        Instagram.prototype.getPhotos = function (id, callback, params) {
            return this.fetch('/users/' + id + '/media/recent', callback, params);
        };
        Instagram.prototype.getPhotospag = function (id, callback, params) {
            return this.fetch('/users/' + id + '/media/recent', callback, params);
        };
        Instagram.prototype.getFollowing = function (id, callback, params) {
            return this.fetch('/users/' + id + '/follows', callback, params);
        };
        Instagram.prototype.getFans2 = function (id, callback, params) {
            return this.fetch2('/users/' + id + '/followed-by', callback, params);
        };
        Instagram.prototype.getRelationship = function (id, callback) {
            return this.fetch('/users/' + id + '/relationship', callback);
        };
        Instagram.prototype.isPrivate = function (id, callback) {
            return this.getUser(id, function (res) {
                return callback(res.meta.error_message === 'you cannot view this resource');
            });
        };
        Instagram.prototype.isFollowing = function (id, callback) {
            return this.getRelationship(id, function (res) {
                return callback(res.data.outgoing_status === 'follows');
            });
        };
        Instagram.prototype.isFollowedBy = function (id, callback) {
            return this.getRelationship(id, function (res) {
                return callback(res.data.incoming_status !== 'none');
            });
        };
        Instagram.prototype.editRelationship = function (id, callback, action) {
            return this.fetch('/users/' + id + '/relationship', callback, {
                action: action
            }, 'POST');
        };
        Instagram.prototype.follow = function (id, callback) {
            return this.editRelationship(id, callback, 'follow');
        };
        Instagram.prototype.unfollow = function (id, callback) {
            return this.editRelationship(id, callback, 'unfollow');
        };
        Instagram.prototype.block = function (id, callback) {
            return this.editRelationship(id, callback, 'block');
        };
        Instagram.prototype.unblock = function (id, callback) {
            return this.editRelationship(id, callback, 'unblock');
        };
        Instagram.prototype.approve = function (id, callback) {
            return this.editRelationship(id, callback, 'approve');
        };
        Instagram.prototype.deny = function (id, callback) {
            return this.editRelationship(id, callback, 'deny');
        };
        Instagram.prototype.searchUser = function (q, callback) {
            return this.fetch('/users/search?q=' + q, callback);
        };
        Instagram.prototype.getPhoto = function (id, callback, params) {
            return this.fetch('/media/' + id, callback, params);
        };
        Instagram.prototype.searchPhoto = function (callback, params) {
            return this.fetch('/media/search', callback, params);
        };
        Instagram.prototype.getPopular = function (callback, params) {
            return this.fetch('/media/popular', callback, params);
        };
        Instagram.prototype.getComments = function (id, callback, params) {
            return this.fetch('/media/' + id + '/comments', callback, params);
        };
        Instagram.prototype.postComment = function (id, callback, params) {
            return this.fetch('/media/' + id + '/comments', callback, params, 'POST');
        };
        Instagram.prototype.deleteComment = function (id, callback) {
            return this.fetch('/media/' + id + '/comments', callback, {}, 'DELETE');
        };
        Instagram.prototype.getLikes = function (id, callback, params) {
            return this.fetch('/media/' + id + '/likes', callback, params);
        };
        Instagram.prototype.addLike = function (id, callback) {
            return this.fetch('/media/' + id + '/likes', callback, {}, 'POST');
        };
        Instagram.prototype.deleteLike = function (id, callback) {
            return this.fetch('/media/' + id + '/likes', callback, {}, 'DELETE');
        };
        Instagram.prototype.getTag = function (tagName, callback, params) {
            return this.fetch('/tags/' + tagName, callback, params);
        };
        Instagram.prototype.getRecentTags = function (tagName, callback, params) {
            return this.fetch('/tags/' + tagName + '/media/recent', callback, params);
        };
        Instagram.prototype.searchTag = function (q, callback, params) {
            return this.fetch('/tags/search?q=' + q, callback, params);
        };
        Instagram.prototype.getLocation = function (locId, callback, params) {
            return this.fetch('/locations/' + locId, callback, params);
        };
        Instagram.prototype.getRecentLocations = function (locId, callback, params) {
            return this.fetch('/locations/' + locId + '/media/recent', callback, params);
        };
        Instagram.prototype.searchLocation = function (callback, params) {
            return this.fetch('/locations/search', callback, params);
        };
        Instagram.prototype.getNearby = function (id, callback, params) {
            return this.fetch('/geographies/' + id + '/media/recent', callback, params);
        };
        return Instagram;
    })();
    return exports.Instagram = Instagram;
})(jQuery, window);

function validateEmail(email) { 
			    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return re.test(email);
			} 
