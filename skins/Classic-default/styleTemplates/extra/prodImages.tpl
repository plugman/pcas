<!-- BEGIN: prod_images -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={VAL_ISO}" />
<title>{META_TITLE}</title>
<link href="skins/{VAL_SKIN}/styleSheets/popup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jslibrary.js"></script>
</head>

<body>
	<div id="pageSurround">
		<div style="padding: 5px;">
			<div id="divThumbsImg">
				<!-- BEGIN: thumbs -->
				<div style="padding: 5px;"><a href="javascript:setMainImage('{VALUE_SRC}');"><img src="{VALUE_THUMB_SRC}" alt="{ALT_THUMB}" width="{VALUE_THUMB_WIDTH}" border="0" class="thumbsImg" title="{ALT_THUMB}" /></a></div>
				<!-- END: thumbs -->
			</div>
			
			<div id="divMainImg">
				<img src="{VALUE_MASTER_SRC}" alt="{ALT_LARGE}" id="img" title="{ALT_LARGE}" />
			</div>

		</div>
		<br clear="all" />
		</div>
			
		<div style="text-align: center;">
			<a href="javascript:window.close();" class="popupLink">Close Window</a>
		</div>
			
		
	
</body>
</html>
<!-- END: prod_images -->