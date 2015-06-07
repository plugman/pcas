function MccBandView()
{
	this.showPhoto = function(url) {
		// Hide the emblem edit div and show the image edit div
		if( $("#userPhoto").length > 0)
			$("#userPhoto").remove();
		$("#emblem_edit").hide();
		$("#image_edit").show();
		$("#spinner2").show();
        	var image = new Image();
        	$(image).load(function() {
                	$(image).css("position", "absolute");
                	$(image).css("top", "0px");
                	$(image).css("left", "0px");
                	$("#image_edit").append(image);
                	$("#knockout").css("zIndex", 20);
                	$("#knockout").css("z-index", 20);
                	$("#knockout").css("z-Index", 20);
                	$(image).css("zIndex", 10);
                	$(image).css("z-index", 10);
                	$(image).css("z-Index", 10);
			$("#spinner2").hide();
			mccBandModel.imageOriginalWidth = $("#userPhoto").width();
			mccBandModel.imageOriginalHeight = $("#userPhoto").height();
        	});
        	$(image).attr("id", "userPhoto");
        	$(image).attr("src", url);
	}
	this.zoomPhoto = function(value) {
		if( $("#userPhoto").length > 0 ) {
			var newWidth = mccBandModel.imageOriginalWidth * value;
			var newHeight = mccBandModel.imageOriginalHeight * value;
			var newTop = (newHeight - mccBandModel.imageOriginalHeight)/2;
			$("#userPhoto").css("width", newWidth + "px");
			$("#userPhoto").css("height", newHeight + "px");
		}
	}

}
var mccBandView = new MccBandView();
