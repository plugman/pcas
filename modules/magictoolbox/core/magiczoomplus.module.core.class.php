<?php

if(!in_array('MagicZoomPlusModuleCoreClass', get_declared_classes())) {

    require_once(dirname(__FILE__) . '/magictoolbox.params.class.php');

	class MagicZoomPlusModuleCoreClass {
		var $uri;
		var $jsPath;
		var $cssPath;
		var $imgPath;
		var $params;
        var $mainImageID;
        var $type = 'standard';

 		function MagicZoomPlusModuleCoreClass() {
			$this->params = new MagicToolboxParams();
			$this->_paramDefaults();
		}
		
		function headers($jsPath = '', $cssPath = null, $notCheck = false) {
			if($cssPath == null) $cssPath = $jsPath;
			$headers = array();
            $headers[] = '<!-- Magic Zoom Plus ImeiUnlock (v.3.x and v.4.x) module version 3.9.2.3 -->';         
            $headers[] = '<link type="text/css" href="' . $cssPath . '/magiczoomplus.css" rel="stylesheet" media="screen" />';         
			$headers[] = '<script type="text/javascript" src="' . $jsPath . '/magiczoomplus.js"></script>';
            
            $conf = Array(
                "'expand-speed': " . $this->params->getValue("expand-speed"),
                "'restore-speed': " . $this->params->getValue("restore-speed"),
                "'expand-effect': '" . $this->params->getValue("expand-effect") . "'",
                "'restore-effect': '" . $this->params->getValue("restore-effect") . "'",
                "'expand-align': '" . $this->params->getValue("expand-align") . "'",
                "'expand-position': '" . $this->params->getValue("expand-position") . "'",
                "'image-size': '" . $this->params->getValue("image-size") . "'",
                //"'keep-thumbnail': " . $this->params->getValue("keep-thumbnail"),
                //"'click-to-initialize': " . $this->params->getValue("click-to-initialize"),
                "'background-color': '" . $this->params->getValue("background-color") . "'",
                "'background-opacity': " . $this->params->getValue("background-opacity"),
                "'background-speed': " . $this->params->getValue("background-speed"),
                "'caption-speed': " . $this->params->getValue("caption-speed"),
                "'caption-position': '" . $this->params->getValue("caption-position") . "'",
                "'caption-height': " . $this->params->getValue("caption-height"),
                "'caption-width': " . $this->params->getValue("caption-width"),
                "'buttons': '" . $this->params->getValue("buttons") . "'",
                "'buttons-position': '" . $this->params->getValue("buttons-position") . "'",
                "'buttons-display': '" . $this->params->getValue("buttons-display") . "'",
                //"'show-loading': " . $this->params->getValue("show-loading"),
                "'loading-msg': '" . $this->params->getValue("loading-msg") . "'",
                "'loading-opacity': " . $this->params->getValue("loading-opacity"),

                "'swap-image': '" . $this->params->getValue("swap-image") . "'",
                "'thumb-change': '" . $this->params->getValue('swap-image') ."'",
        
                "'swap-image-delay': " . $this->params->getValue("swap-image-delay"),
                "'selectors-mouseover-delay': " . $this->params->getValue('swap-image-delay') ,

                "'slideshow-effect': '" . $this->params->getValue("slideshow-effect") . "'",
                "'slideshow-speed': " . $this->params->getValue("slideshow-speed"),
                //"'slideshow-loop': " . $this->params->getValue("slideshow-loop"),
                //"'link': '" . $this->params->getValue("link") . "'",
                //"'link-target': '" . $this->params->getValue("link-target") . "'",
                //"'thumb-id': '" . $this->params->getValue("thumb-id") . "'",
                //"'group': '" . $this->params->getValue("group") . "'",
                //"'keyboard': " . $this->params->getValue("keyboard"),
                //"'keyboard-ctrl': " . $this->params->getValue("keyboard-ctrl"),
                "'z-index': " . $this->params->getValue("z-index"),

                "'opacity': " . $this->params->getValue('opacity'),
                "'zoom-width': " . $this->params->getValue('zoom-width'),
                "'zoom-height': " . $this->params->getValue('zoom-height'),
                "'zoom-position': '" . $this->params->getValue('zoom-position') ."'",
                //"'thumb-change': '" . $this->params->getValue('thumb-change') ."'",
                "'smoothing-speed': " . $this->params->getValue('smoothing-speed'),
                "'zoom-distance': " . $this->params->getValue('zoom-distance'),
                "'zoom-fade-in-speed': " . $this->params->getValue('zoom-fade-in-speed'),
                "'zoom-fade-out-speed': " . $this->params->getValue('zoom-fade-out-speed'),
                //"'hotspots': " . $this->params->getValue('hotspots'),
                "'fps': " . $this->params->getValue('fps'),
                "'loading-position-x': " . $this->params->getValue('loading-position-x'),
                "'loading-position-y': " . $this->params->getValue('loading-position-y'),
                "'x': " . $this->params->getValue('x'),
                "'y': " . $this->params->getValue('y'),
                "'show-title': " . ($this->params->getValue('show-title')=='disable'?'false':"'".$this->params->getValue('show-title')."'"),
                "'selectors-effect': '" . $this->params->getValue('selectors-effect') ."'",
                "'selectors-effect-speed': " . $this->params->getValue('selectors-effect-speed'),


            );
            
            if($notCheck) {
                $conf = array_merge($conf, array(
                    "'disable-zoom': " . $this->params->getValue("disable-zoom"),
                    "'disable-expand': " . $this->params->getValue("disable-expand"),
                    "'keep-thumbnail': " . $this->params->getValue("keep-thumbnail"),
                    "'click-to-initialize': " . $this->params->getValue("click-to-initialize"),
                    "'show-loading': " . $this->params->getValue("show-loading"),
                    "'slideshow-loop': " . $this->params->getValue("slideshow-loop"),
                    "'keyboard': " . $this->params->getValue("keyboard"),
                    "'keyboard-ctrl': " . $this->params->getValue("keyboard-ctrl"),
                    
                    "'drag-mode': " . $this->params->getValue('drag-mode'),
                    "'always-show-zoom': " . $this->params->getValue('always-show-zoom'),
                    "'smoothing': " . $this->params->getValue('smoothing'),
                    "'opacity-reverse': " . $this->params->getValue('opacity-reverse'),
                    "'click-to-activate': " . $this->params->getValue('click-to-activate'),
                    "'preload-selectors-small': " . $this->params->getValue('preload-selectors-small'),
                    "'preload-selectors-big': " . $this->params->getValue('preload-selectors-big'),
                    "'zoom-fade': " . $this->params->getValue('zoom-fade'),
                    "'move-on-click': " . $this->params->getValue('move-on-click'),
                    "'preserve-position': " . $this->params->getValue('preserve-position'),
                    "'fit-zoom-window': " . $this->params->getValue('fit-zoom-window'),
                    "'entire-image': " . $this->params->getValue('entire-image'),
                ));
            } else {
                $conf = array_merge($conf, array(
                    "'disable-zoom': " . ($this->params->getValue("disable-zoom")=='Yes'?'true':'false'),
                    "'disable-expand': " . ($this->params->getValue("disable-expand")=='Yes'?'true':'false'),
                    "'keep-thumbnail': " . ($this->params->getValue('keep-thumbnail')=='Yes'?'true':'false'),
                    "'click-to-initialize': " . ($this->params->getValue('click-to-initialize')=='Yes'?'true':'false'),
                    "'show-loading': " . ($this->params->getValue('show-loading')=='Yes'?'true':'false'),
                    "'slideshow-loop': " . ($this->params->getValue('slideshow-loop')=='Yes'?'true':'false'),
                    "'keyboard': " . ($this->params->getValue('keyboard')=='Yes'?'true':'false'),
                    "'keyboard-ctrl': " . ($this->params->getValue('keyboard-ctrl')=='Yes'?'true':'false'),

                    "'drag-mode': " . ($this->params->checkValue('drag-mode', 'Yes') ? 'true' : 'false'),
                    "'always-show-zoom': " . ($this->params->checkValue('always-show-zoom', 'Yes') ? 'true' : 'false'),
                    "'smoothing': " . ($this->params->checkValue('smoothing', 'Yes') ? 'true' : 'false'),
                    "'opacity-reverse': " . ($this->params->checkValue('opacity-reverse', 'Yes') ? 'true' : 'false'),
                    "'click-to-activate': " . ($this->params->checkValue('click-to-activate', 'Yes') ? 'true' : 'false'),
                    "'preload-selectors-small': " . ($this->params->checkValue('preload-selectors-small', 'Yes') ? 'true' : 'false'),
                    "'preload-selectors-big': " . ($this->params->checkValue('preload-selectors-big', 'Yes') ? 'true' : 'false'),
                    "'zoom-fade': " . ($this->params->checkValue('zoom-fade', 'Yes') ? 'true' : 'false'),
                    "'move-on-click': " . ($this->params->checkValue('move-on-click', 'Yes') ? 'true' : 'false'),
                    "'preserve-position': " . ($this->params->checkValue('preserve-position', 'Yes') ? 'true' : 'false'),
                    "'fit-zoom-window': " . ($this->params->checkValue('fit-zoom-window', 'Yes') ? 'true' : 'false'),
                    "'entire-image': " . ($this->params->checkValue('entire-image', 'Yes') ? 'true' : 'false'),
                ));
            }

            $cSource = $this->params->get("caption-source");
            if(isset($cSource['core']) && $cSource['core']) {
                $conf = array_merge($conf, array(
                    "'caption-source': '" . $this->params->getValue("caption-source") . "'"
                ));
            } else {
                $conf = array_merge($conf, array(
                    "'caption-source': 'span'"
                ));
            }
            
            $headers[] = "<script type=\"text/javascript\">\n\tMagicZoomPlus.options = {\n\t\t".implode(",\n\t\t",$conf)."\n\t}\n</script>\n";
            
			return implode("\r\n", $headers);
		}
		
        function template($params) {
            extract($params);

            if(!isset($img) || empty($img)) return false;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);
            
            if(!isset($alt) || empty($alt)) $alt = '';
            if(!isset($title) || empty($title)) $title = '';
            if(!isset($description)) $description = '';

            if($this->params->checkValue('show-caption', 'Yes')) {
                $captionSource = $this->params->getValue('caption-source');
                $captionSource = trim($captionSource);
                if($captionSource == 'All') {
                    $captionSource = $this->params->getValues('caption-source');
                } else {
                    $captionSource = explode(',',$captionSource);
                }
                $fullTitle = array();
                foreach($captionSource as $caption) {
                    $caption = trim($caption);
                    $caption = strtolower($caption);
                    $caption = lcfirst(implode(explode(' ', ucwords($caption))));
                    if($caption == 'all') continue;
                    if(!isset($$caption)) continue;
                    if($$caption == '') continue;
                    if($caption == 'title') {
                        $fullTitle[] = '<b>' . $$caption . '</b>';
                    } else {
                        $fullTitle[] = $$caption;
                    }
                }
                $description_new = implode('<br/>',$fullTitle);
            } else $description_new = '';
            $description = $description_new;
            $description = trim(preg_replace("/\s+/is", " ", $description));
            if(!empty($description)) {
                $description = preg_replace("/<(\/?)a([^>]*)>/is", "[$1a$2]", $description);
                $description = "<span>{$description}</span>";
            }
            if(!empty($title) && !$this->params->checkValue('show-title', 'disable')) {
                $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            } else $title = '';
            
            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";
            
            if($this->params->checkValue('show-message', 'Yes')) {
                $message = '<br />' .$this->params->getValue('message');
            } else $message = '';

            $this->mainImageID = $id;
            
            $rel[] = 'disable-zoom:'.($this->params->getValue("disable-zoom")=='Yes'?'true':'false');
            $rel[] = 'disable-expand: ' . ($this->params->getValue("disable-expand")=='Yes'?'true':'false');

            if(isset($link) && !empty($link)) {
                $rel[] = 'link: ' . ($link);
            }

            if(isset($group) && !empty($group)) {
                $rel[] = 'group: ' . ($group);
            }
            
            $rel = implode('; ',$rel);
            
            return "<a class=\"MagicZoomPlus\"{$title} id=\"MagicZoomPlusImage{$id}\" href=\"{$img}\" rel=\"{$rel}\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" />{$description}</a>" . $message;
        }
		
        function subTemplate($params) {
            extract($params);
            
            if(!isset($alt) || empty($alt)) $alt = '';
            if(!isset($img) || empty($img)) return false;
            if(!isset($medium) || empty($medium)) $medium = $img;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);
            if(!isset($title) || empty($title) || $this->params->checkValue('show-caption', 'No')) $title = '';
            else {
                $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            }
            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";

            /* onclick - to allow change image for MagicThumb effect */
            //$onclick = " onclick=\"MagicZoomPlusResreshMagicThumb(this);\"";
            
            return "<a{$title} href=\"{$img}\" rel=\"zoom-id: MagicZoomPlusImage{$id}; caption-source: a:title\" rev=\"{$medium}\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" /></a>";
        }

		
		function addonsTemplate($imgPath = '') {
			if ($this->params->getValue("loading-animation") == "Yes"){
				return '<img style="display:none;" class="MagicZoomLoading" src="' . $imgPath . '/' . $this->params->getValue("loading-image") . '" alt="' . $this->params->getValue("loading-text") . '"/>';
            } else return '';
		}
		
		function _paramDefaults() {
			$params = array("opacity"=>array("id"=>"opacity","default"=>"50","label"=>"Square opacity","type"=>"num"),"zoom-width"=>array("id"=>"zoom-width","default"=>"300","label"=>"Zoomed area width (in pixels)","type"=>"num"),"zoom-height"=>array("id"=>"zoom-height","default"=>"300","label"=>"Zoomed area height (in pixels)","type"=>"num"),"zoom-position"=>array("id"=>"zoom-position","default"=>"right","label"=>"Zoomed area position","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","inner")),"zoom-distance"=>array("id"=>"zoom-distance","default"=>"15","label"=>"Distance between small image and zoom window (in pixels)","type"=>"num"),"show-message"=>array("id"=>"show-message","default"=>"Yes","label"=>"Show message under image?","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"message"=>array("id"=>"message","default"=>"Move your mouse over image or click to enlarge","label"=>"Message under images","type"=>"text"),"drag-mode"=>array("id"=>"drag-mode","default"=>"No","label"=>"Use drag mode?","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"always-show-zoom"=>array("id"=>"always-show-zoom","default"=>"No","label"=>"Always show zoom?","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"smoothing"=>array("id"=>"smoothing","default"=>"Yes","label"=>"Enable smooth zoom movement","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"smoothing-speed"=>array("id"=>"smoothing-speed","default"=>"40","label"=>"Speed of smoothing (1-99)","type"=>"num"),"opacity-reverse"=>array("id"=>"opacity-reverse","default"=>"No","label"=>"Add opacity to background instead of hovered area","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"click-to-initialize"=>array("id"=>"click-to-initialize","default"=>"No","label"=>"Click to initialize Magic Zoom and download large image","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"click-to-activate"=>array("id"=>"click-to-activate","default"=>"No","label"=>"Click to show the zoom","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"show-title"=>array("id"=>"show-title","default"=>"top","label"=>"Show the title of the image in the zoom window","type"=>"array","subType"=>"select","values"=>array("top","bottom","disable")),"selectors-mouseover-delay"=>array("id"=>"selectors-mouseover-delay","default"=>"200","label"=>"Multiple images delay in ms before switching thumbnails","type"=>"num"),"preload-selectors-small"=>array("id"=>"preload-selectors-small","default"=>"Yes","label"=>"Multiple images, preload small images","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"preload-selectors-big"=>array("id"=>"preload-selectors-big","default"=>"No","label"=>"Multiple images, preload large images","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"zoom-fade"=>array("id"=>"zoom-fade","default"=>"No","label"=>"Zoom window fade effect","type"=>"array","subType"=>"select","values"=>array("Yes","No")),"zoom-fade-in-speed"=>array("id"=>"zoom-fade-in-speed","default"=>"200","label"=>"Zoom window fade-in speed (in milliseconds)","type"=>"num"),"zoom-fade-out-speed"=>array("id"=>"zoom-fade-out-speed","default"=>"200","label"=>"Zoom window fade-out speed  (in milliseconds)","type"=>"num"),"fps"=>array("id"=>"fps","default"=>"25","label"=>"Frames per second for zoom effect","type"=>"num"),"show-loading"=>array("id"=>"show-loading","default"=>"Yes","label"=>"Loading message","type"=>"array","subType"=>"select","values"=>array("Yes","No")),"loading-msg"=>array("id"=>"loading-msg","default"=>"Loading zoom...","label"=>"Loading message text","type"=>"text"),"loading-opacity"=>array("id"=>"loading-opacity","default"=>"75","label"=>"Loading message opacity (0-100)","type"=>"num"),"loading-position-x"=>array("id"=>"loading-position-x","default"=>"-1","label"=>"Loading message X-axis position, -1 is center","type"=>"num"),"loading-position-y"=>array("id"=>"loading-position-y","default"=>"-1","label"=>"Loading message Y-axis position, -1 is center","type"=>"num"),"move-on-click"=>array("id"=>"move-on-click","default"=>"Yes","label"=>"Click alone will also move zoom (drag mode only)","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"x"=>array("id"=>"x","default"=>"-1","label"=>"Initial zoom X-axis position for drag mode, -1 is center","type"=>"num"),"y"=>array("id"=>"y","default"=>"-1","label"=>"Initial zoom Y-axis position for drag mode, -1 is center","type"=>"num"),"preserve-position"=>array("id"=>"preserve-position","default"=>"No","label"=>"Position of zoom can be remembered for multiple images and drag mode","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"fit-zoom-window"=>array("id"=>"fit-zoom-window","default"=>"Yes","label"=>"Resize zoom window if big image is smaller than zoom window","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"selectors-effect"=>array("id"=>"selectors-effect","default"=>"dissolve","label"=>"Dissolve or cross fade thumbnail when switching thumbnails","type"=>"array","subType"=>"select","values"=>array("dissolve","fade","disable")),"selectors-effect-speed"=>array("id"=>"selectors-effect-speed","default"=>"400","label"=>"Selectors effect speed, ms","type"=>"num"),"entire-image"=>array("id"=>"entire-image","default"=>"No","label"=>"Show entire large image on hover","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"expand-speed"=>array("id"=>"expand-speed","default"=>"500","label"=>"Expand duration (milliseconds: 0-10000)","type"=>"num"),"restore-speed"=>array("id"=>"restore-speed","default"=>"-1","label"=>"Restore duration (milliseconds: 0-10000, -1: use expand-speed value)","type"=>"num"),"expand-effect"=>array("id"=>"expand-effect","default"=>"linear","label"=>"Effect while expanding image","type"=>"array","subType"=>"select","values"=>array("linear","cubic","back","elastic","bounce")),"restore-effect"=>array("id"=>"restore-effect","default"=>"linear","label"=>"Effect while restoring image","type"=>"array","subType"=>"select","values"=>array("linear","cubic","back","elastic","bounce")),"expand-align"=>array("id"=>"expand-align","default"=>"screen","label"=>"Align expanded image relative to screen or thumbnail","type"=>"array","subType"=>"select","values"=>array("screen","image")),"expand-position"=>array("id"=>"expand-position","default"=>"center","label"=>"Precise position of enlarged image (px)","type"=>"text","description"=>"The value can be 'center' or coordinates. E.g. 'top:0, left:0' or 'bottom:100, left:100'"),"image-size"=>array("id"=>"image-size","default"=>"fit-screen","label"=>"Size of the enlarged image","type"=>"array","subType"=>"select","values"=>array("original","fit-screen")),"keep-thumbnail"=>array("id"=>"keep-thumbnail","default"=>"Yes","label"=>"Show/hide thumbnail when image enlarged","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"background-color"=>array("id"=>"background-color","default"=>"#000000","label"=>"Fade background color (RGB)","type"=>"text"),"background-opacity"=>array("id"=>"background-opacity","default"=>"0","label"=>"Opacity of the background effect (0-100)","type"=>"num"),"background-speed"=>array("id"=>"background-speed","default"=>"200","label"=>"Speed of the fade effect (milliseconds: 0 or larger)","type"=>"num"),"show-caption"=>array("id"=>"show-caption","default"=>"Yes","label"=>"Show caption","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"caption-source"=>array("id"=>"caption-source","default"=>"Description","label"=>"Caption source","type"=>"text","values"=>array("Title","Description","All")),"caption-speed"=>array("id"=>"caption-speed","default"=>"250","label"=>"Speed of the caption slide effect (milliseconds: 0 or larger)","type"=>"num"),"caption-position"=>array("id"=>"caption-position","default"=>"bottom","label"=>"Where to position the caption","type"=>"array","subType"=>"select","values"=>array("bottom","right","left")),"caption-height"=>array("id"=>"caption-height","default"=>"300","label"=>"Max height of bottom caption (pixels: 0 or larger)","type"=>"num"),"caption-width"=>array("id"=>"caption-width","default"=>"300","label"=>"Max width of bottom caption (pixels: 0 or larger)","type"=>"num"),"buttons"=>array("id"=>"buttons","default"=>"show","label"=>"Whether to show navigation buttons","type"=>"array","subType"=>"select","values"=>array("show","hide","autohide")),"buttons-position"=>array("id"=>"buttons-position","default"=>"auto","label"=>"Location of navigation buttons","type"=>"array","subType"=>"select","values"=>array("auto","top left","top right","bottom left","bottom right")),"buttons-display"=>array("id"=>"buttons-display","default"=>"previous, next, close","label"=>"Display button","type"=>"text","description"=>"Show all three buttons or just one or two. E.g. 'previous, next' or 'close, next'"),"swap-image"=>array("id"=>"swap-image","default"=>"click","label"=>"Method to switch between multiple images","type"=>"array","subType"=>"radio","values"=>array("click","mouseover")),"swap-image-delay"=>array("id"=>"swap-image-delay","default"=>"100","label"=>"Delay before switching thumbnails (milliseconds: 0 or larger)","type"=>"num"),"slideshow-effect"=>array("id"=>"slideshow-effect","default"=>"dissolve","label"=>"Visual effect for switching images","type"=>"array","subType"=>"select","values"=>array("dissolve","fade","expand")),"slideshow-speed"=>array("id"=>"slideshow-speed","default"=>"800","label"=>"Speed of slideshow effect (milliseconds: 0 or larger)","type"=>"num"),"slideshow-loop"=>array("id"=>"slideshow-loop","default"=>"Yes","label"=>"Restart slideshow after last image","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"keyboard"=>array("id"=>"keyboard","default"=>"Yes","label"=>"Ability to use keyboard shortcuts","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"keyboard-ctrl"=>array("id"=>"keyboard-ctrl","default"=>"No","label"=>"Require Ctrl key to permit shortcuts","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"z-index"=>array("id"=>"z-index","default"=>"10001","label"=>"The z-index for the enlarged image","type"=>"num"),"thumb-size"=>array("id"=>"thumb-size","default"=>"200","label"=>"Size of thumbnail (in pixels)","type"=>"num"),"selector-size"=>array("id"=>"selector-size","default"=>"100","label"=>"Size of addittional thumbnails (in pixels)","type"=>"num"),"use-effect-on-product-page"=>array("id"=>"use-effect-on-product-page","default"=>"Yes","label"=>"Use effect for products pages","type"=>"array","subType"=>"radio","values"=>array("Yes","Zoom","Expand","No")),"use-effect-on-category-page"=>array("id"=>"use-effect-on-category-page","default"=>"No","label"=>"Use effect for categories","type"=>"array","subType"=>"radio","values"=>array("Yes","Zoom","Expand","No")),"use-effect-on-featured-products"=>array("id"=>"use-effect-on-featured-products","default"=>"No","label"=>"Use effect for featured product blocks","type"=>"array","subType"=>"radio","values"=>array("Yes","Zoom","Expand","No")),"size-depends"=>array("id"=>"size-depends","default"=>"both","label"=>"Images size depends","type"=>"array","subType"=>"select","values"=>array("width","height","both")),"square-images"=>array("id"=>"square-images","default"=>"disable","label"=>"Create square images","description"=>"If enabled then the white/transparent padding will be added around the image","type"=>"array","subType"=>"radio","values"=>array("enable","disable")),"imagemagick"=>array("id"=>"imagemagick","default"=>"auto","label"=>"Path to Imagemagick binaries (convert tool)","description"=>"You can set 'auto' to automatically detect imagemagick location or 'off' to disable imagemagick and use php GD lib instead","type"=>"text"),"watermark"=>array("id"=>"watermark","default"=>"","label"=>"Path to watermark image","description"=>"Relative for site base path. Use empty to disable watermark","type"=>"text"),"watermark-opacity"=>array("id"=>"watermark-opacity","default"=>"50","label"=>"Opacity of the watermark image","description"=>"0-100","type"=>"num"),"watermark-size"=>array("id"=>"watermark-size","default"=>"50%","label"=>"Watermark image size","description"=>"pixels (fixed size) or percent (relative for image size)","type"=>"text"),"watermark-size-depends"=>array("id"=>"watermark-size-depends","default"=>"both","label"=>"Watermark size depends","type"=>"array","subType"=>"select","values"=>array("width","height","both")),"watermark-position"=>array("id"=>"watermark-position","default"=>"center","label"=>"Position of the watermark","description"=>"'watermark-size' will ignore when 'watermark-position' sets to 'stretch'","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","top-left","bottom-left","top-right","bottom-right","center","stretch")),"watermark-offset-x"=>array("id"=>"watermark-offset-x","default"=>"0","label"=>"Watermark horizontal offset","description"=>"Offset from left and/or right image borders. Pixels (fixed size) or percent (relative for image size)","type"=>"text"),"watermark-offset-y"=>array("id"=>"watermark-offset-y","default"=>"0","label"=>"Watermark vertical offset","description"=>"Offset from top and/or bottom image borders. Pixels (fixed size) or percent (relative for image size)","type"=>"text"));
			$this->params->appendArray($params);
		}
	}

}
?>
