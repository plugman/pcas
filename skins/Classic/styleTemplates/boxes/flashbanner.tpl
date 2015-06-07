<!-- BEGIN: flash_banner -->
<script type="text/javascript" src="js/jquery.aw-showcase.js" ></script>
<script type="text/javascript">
 
$(document).ready(function()
{
	$("#showcase").awShowcase(
	{
		content_width:			1330,
		content_height:			422,
		fit_to_parent:			true,
		auto:					true,
		interval:				3000,
		continuous:				true,
		loading:				true,
		tooltip_width:			200,
		tooltip_icon_width:		32,
		tooltip_icon_height:	32,
		tooltip_offsetx:		18,
		tooltip_offsety:		0,
		arrows:					false,
		buttons:				false,
		btn_numbers:			true,
		keybord_keys:			true,
		mousetrace:				false, /* Trace x and y coordinates for the mouse */
		pauseonover:			true,
		stoponclick:			false,
		transition:				'hslide', /* hslide/vslide/fade */
		transition_delay:		0,
		transition_speed:		500,
		show_caption:			'onload', /* onload/onhover/show */
		thumbnails:				false,
		thumbnails_position:	'outside-last', /* outside-last/outside-first/inside-last/inside-first */
		thumbnails_direction:	'horizontal', /* vertical/horizontal */
		thumbnails_slidex:		1, /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
		dynamic_height:			false, /* For dynamic height to work in webkit you need to set the width and height of images in the source. Usually works to only set the dimension of the first slide in the showcase. */
		speed_change:			true, /* Set to true to prevent users from swithing more then one slide at once. */
		viewline:				false, /* If set to true content_width, thumbnails, transition and dynamic_height will be disabled. As for dynamic height you need to set the width and height of images in the source. */
		custom_function:		null /* Define a custom function that runs on content change */
	});
});

</script>
<!-- BEGIN: true -->
<div class="maindiv bannerbg" >

    <div id="showcase" class="showcase">       
      <!-- BEGIN: li -->      
      <div class="showcase-slide"> 
        <!-- Put the slide content in a div with the class .showcase-content. -->
        <div class="showcase-content"> <a href="{FDATA.img_link}"> <img  src="uploads/flashbanner/{FDATA.img_file}" class="slide" alt="{FDATA.name}" title="{FDATA.img_title}"/> </a> 
        </div>
       <!-- <div class="showcase-thumbnail">
        		
				<div class="showcase-thumbnail-content">
                	<span class="timgbox"><img  src="skins/{VAL_SKIN}/styleImages/thumb{imgserial}.png"  alt="{DATA.name}"/></span>
               <span class="textmessage">{TXT_THU}</span>
                </div>
				
			</div>-->
      </div>
      <!-- END: li --> 

          
           
    </div>

</div>
<!-- END: true --> 
<!-- END: flash_banner -->