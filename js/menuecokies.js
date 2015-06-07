  /************jQuery Cookie**************/
;(function(g){g.cookie=function(h,b,a){if(1<arguments.length&&(!/Object/.test(Object.prototype.toString.call(b))||null===b||void 0===b)){a=g.extend({},a);if(null===b||void 0===b)a.expires=-1;if("number"===typeof a.expires){var d=a.expires,c=a.expires=new Date;c.setDate(c.getDate()+d)}b=""+b;return document.cookie=[encodeURIComponent(h),"=",a.raw?b:encodeURIComponent(b),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":
""].join("")}for(var a=b||{},d=a.raw?function(a){return a}:decodeURIComponent,c=document.cookie.split("; "),e=0,f;f=c[e]&&c[e].split("=");e++)if(d(f[0])===h)return d(f[1]||"");return null}})(jQuery);

                                                                                                      /*********Main start**********/                   
                                                                                                                                                                                                   $j(function(){
    var toggle=$j('.toggle');
    var inner=toggle.find('.navStoreLinks');
    if($j.cookie('navStoreLinks')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreConfig');
    if($j.cookie('navStoreConfig')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreModules');
    if($j.cookie('navStoreModules')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreCatalog');
    if($j.cookie('navStoreCatalog')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreRepair');
    if($j.cookie('navStoreRepair')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreCustomers');
    if($j.cookie('navStoreCustomers')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreStats');
    if($j.cookie('navStoreStats')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreDocuments');
    if($j.cookie('navStoreDocuments')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreUsers');
    if($j.cookie('navStoreUsers')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navStoreMaintenance');
    if($j.cookie('navStoreMaintenance')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navReports');
    if($j.cookie('navReports')=='visible')
        inner.show();
    else
        inner.hide();
		var inner=toggle.find('.navdhru');
	if($j.cookie('navdhru')=='visible')
        inner.show();
    else
        inner.hide();
	var inner=toggle.find('.navcase');
	if($j.cookie('navcase')=='visible')
        inner.show();
    else
        inner.hide();
});
function NavigationSlider(opt){
	 var toggle=$j('.toggle');
	var inner=toggle.find('.'+opt);
	 if(inner.is(':visible')){
            $j.cookie(opt, 'hidden');
			inner.slideUp("slow");
		}
        else{
            $j.cookie(opt, 'visible');
        inner.slideDown("slow");
		}
  
	}
function NavigationSlider2(opt){
	if($j("#"+opt).hasClass("unchecked")){
		$j("#"+opt).slideDown("slow");
  	$j("#"+opt).addClass("checked");
  	$j("#"+opt).removeClass("unchecked");
  }else{
	  $j("#"+opt).slideUp();
	  $j("#"+opt).addClass("unchecked");
  		$j("#"+opt).removeClass("checked");
	  }
  
	}