function ImageController() {
    var instance = this;
    this.loadPhoto = function (src, m_parent) {
		
		$("#"+m_parent).css('background-color','rgba(0, 0, 0, 0.3)');
		$("#"+m_parent).html('<div class="spinner-wrap absolute "><div class="spiner"><div class="circle absolute f-height f-width"></div><div class="circle absolute f-height f-width"></div></div></div>');

loadimagetobox(src, src, m_parent);
		}



}

var ImageController = new ImageController();
// load image
var draggedEl;
var newId = 0;

function allowDrop(ev) {
    ev.preventDefault();
	 
	  if ($(ev.target).hasClass('draggable')) {
	 $(ev.target).css('background-color','rgba(0, 0, 0, 0.3)');
	 }else if($(ev.target).hasClass('draggable2')){
		  $(ev.target).prev().css('background-color','rgba(0, 0, 0, 0.3)');
	 }
	// m_parent = $('#' + el.id).attr('id');
}
function removetarget(ev) {
	ev.preventDefault();
	 if ($(ev.target).hasClass('draggable')) {
     $(ev.target).removeAttr('style');
	 }else if($(ev.target).hasClass('draggable2')){
		  $(ev.target).prev().removeAttr('style');
	 }
}
function drag(ev) {
	
    draggedEl = ev.target.cloneNode(true);
    // draggedEl.id = draggedEl.id+(newId++);
    draggedEl.ondrop = undefined;
    draggedEl.ondragover = undefined;
    draggedEl.ondragstart = undefined;
	
}

function drop(ev) { 
 ev.preventDefault();
   
    var el = ev.target;
	//$(ev.target).removeAttr('style');
	
    m_parent = $('#' + el.id).attr('id');
	
	if($("#"+m_parent).is("img")){
		m_parent = $("#"+m_parent).prev().attr('id');
	}
	
    if ($(ev.target).hasClass('draggable') || $(ev.target).hasClass('draggable2')) {
        src = $('#' + draggedEl.id).attr('source');
   if($('#' + draggedEl.id).hasClass('draggable2')){
	   $("#" + draggedEl.id).next().remove();
        $("#" + draggedEl.id).prev().removeClass("imageloaded");

        $("#" + draggedEl.id).prev().parent().css({
            'border': '',
            'height': '+=-2px',
            'width': '+=-2px',
            'z-index': '55'
        });
        $("#" + draggedEl.id).remove();
   }
			
        ImageController.loadPhoto(src, m_parent);
       // $('#spinner2').hide();
    }
}
function editimage(){
    $('.draggable').each(function () {
        var y = this.id;
		$("#" + y+"-userdpPhoto").unbind("dblclick").dblclick(function () {
			$("#" + y).dblclick();
		});
        $("#" + y).dblclick(function () {
            var userphoto = y + "-userPhoto";
            var src = $("#" + userphoto).attr('src');
            if (src != null && !$(this).hasClass("transform-holder")) {
                $(this).parent().css({
                    'overflow': 'visible',
                });


                $(this).addClass("transform-holder");
                $("#" + userphoto).addClass("editable");
                $(this).css({
                    'overflow': 'visible',
                    'z-index': '57'
                });
                var minwidth = $("#" + y).parent().css("width");
                var minheifht = $("#" + y).parent().css("height");

                $("#" + userphoto).css({
                    'z-index': '56',
                });
			
                $("#" + userphoto).resizable({
                    aspectRatio: true,
                    handles: "nw, ne, sw,se",
                    //minWidth: minwidth.replace('px', ''),
                    minHeight: minheifht.replace('px', ''),

                });

                $(".ui-wrapper").draggable({
                    stop: function (ev, ui) {
                        var hel = ui.helper,
                            pos = ui.position;
                        //horizontal
                        var h = -(hel.outerHeight() - $(hel).parent().outerHeight());
                        if (pos.top >= 0) {
                            hel.css({
                                top: 0
                            });
                        } else if (pos.top <= h) {
                            hel.css({
                                top: h
                            });

                        }
                        // vertical
                        var v = -(hel.outerWidth() - $(hel).parent().outerWidth());
                        if (pos.left >= 0) {
                            hel.css({
                                left: 0
                            });
                        } else if (pos.left <= v) {
                            hel.css({
                                left: v
                            });
                        }
                    }
                });
                $("#transform-tool").show();
                $("#" + userphoto).dblclick(function () {
                    $("#" + y).parent().css({
                        'overflow': 'hidden',
                    });


                    $("#" + y).removeClass("transform-holder");
                    $(this).removeClass("editable");
                    $("#" + y).css({
                        'overflow': 'hidden',
                        'z-index': '50',
                    });
                    $(this).css({
                        'z-index': '10',
                    });
					try{
                    $("#" + userphoto).resizable("destroy");
					}catch(err)
 					 {
 							 //Handle errors here
  					}
					$("#transform-tool").hide();
					//editimage();
                    

                });

            }
        });


    });
}
$(document).ready(function(){
	editimage();
});

function loadimagetobox(URL, src, m_parent) {
    

		
		 
		src_array = src.split('/');
        filename = src_array[src_array.length - 1];
        var div = $("#" + m_parent); // [TODO] This should be a parameter that's set in the contructor or on app init
        //Remove existing photo, hide elbme edit and show image edit with spinner
		if ($("#" + m_parent + "-userdpPhoto").length > 0) $("#" + m_parent + "-userdpPhoto").remove();
        if ($("#" + m_parent + "-userPhoto").length > 0) $("#" + m_parent + "-userPhoto").remove();
		
      
        $("#emblem_edit").hide();
        $("#" + m_parent).show();
        $("#spinner2").show();
        image_filename = filename;
		
        //Create image element and bind event handlers
		var src = URL;
        var image = new Image();
        $(image).css("position", "absolute");
        $(image).css("top", "10000px"); // hide the image while it's loading
        $(image).bind("load", function () {
            $("imageEditControls").hide(); // enable editing buttons
            $(".controlsMask").hide();
            // On image load, store original dimentions then find and set the minimum zoom and center position
            var origw = $(image).width();
            var origh = $(image).height();
            var photoname = filename;
            image_height = $(image).height();
            image_width = $(image).width();
            var scaleX = $(div).width() / $(image).width();
            var scaleY = $(div).height() / $(image).height();
            var scale = scaleX;
            if (scaleY > scaleX) scale = scaleY;
            image_minScale = scale;

            var newHeight = $(image).height() * scale;
            var newWidth = $(image).width() * scale;
            $(image).height(newHeight);
            $(image).width(newWidth);
            var left = Math.round(($(div).width() - newWidth) / 2);
            var top = Math.round(($(div).height() - newHeight) / 2);
            $(image).css("position", "absolute");
            $(image).css("top", top + "px");
            $(image).css("left", left + "px");
            image_zoom = 1;
            image_left = left;
            image_top = top;
            // Set the Z values
            $(image).css("z-Index", 10);

        });
        // Add the image to the dom and hide it
        $(div).after(image);
        $(image).attr("id", m_parent + "-userPhoto");
        src2= src;
		var s = '';
		if( src.indexOf("https") == 0 ) {
   			s='https:';
			} else if( src.indexOf("http") == 0 ) {
 	   		s='http:';
			}else{
				s = 1;
			}
		
		if(s!=1){
		src = src.replace('http:', '');
		src = src.replace('https:', '');
        var imageURL = 'controller.php?s='+s+'&url='+encodeURIComponent(src);
		}else{
			var imageURL = src;
		}
        $(image).attr("src", imageURL);
		$(image).crossOrigin = "Anonymous";
        
		$(image).load(function(){
			 if (!$('#'+m_parent).hasClass("imageloaded")) {
		$("#"+m_parent).parent().css({
		'border': 'none',
		'height': '+=2px' ,
		'width': '+=2px',
		'z-index': 'auto'
     });
	}
			$("#"+m_parent).removeAttr('style');
			$("#" + m_parent).addClass("imageloaded");
			$("#"+m_parent).html('');
			
		});
			
		 var image2 = new Image();
		 image2.setAttribute("source", src2);
		 image2.setAttribute("ondragstart", 'drag(event)');
        $(image2).css("position", "absolute");
        $(image2).css("top", "10000px"); // hide the image while it's loading
        $(image2).bind("load", function () {
            $("imageEditControls").hide(); // enable editing buttons
            $(".controlsMask").hide();
            // On image load, store original dimentions then find and set the minimum zoom and center position
            var origw = $(image2).width();
            var origh = $(image2).height();
            var photoname = filename;
            image2_height = $(image2).height();
            image2_width = $(image2).width();
            var scaleX = $(div).width() / $(image2).width();
            var scaleY = $(div).height() / $(image2).height();
            var scale = scaleX;
            if (scaleY > scaleX) scale = scaleY;
            image2_minScale = scale;

            var newHeight = $(image2).height() * scale;
            var newWidth = $(image2).width() * scale;
            $(image2).height(newHeight);
            $(image2).width(newWidth);
            var left = Math.round(($(div).width() - newWidth) / 2);
            var top = Math.round(($(div).height() - newHeight) / 2);
            $(image2).css("position", "absolute");
            $(image2).css("top", top + "px");
            $(image2).css("left", left + "px");
            image2_zoom = 1;
            image2_left = left;
            image2_top = top;
            // Set the Z values
            $(image2).css("z-Index", 51);

        });
        // Add the image2 to the dom and hide it
        $(div).after(image2);
        $(image2).attr("id", m_parent + "-userdpPhoto");
    
   $(image2).attr("src", src2);
   
   $(image2).addClass('hide-cont draggable2');
   editimage();
}