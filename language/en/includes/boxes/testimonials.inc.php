<?php

if(!defined('CC_INI_SET')){ die("Access Denied"); }  

$box_content = new XTemplate ("boxes".CC_DS."testimonials.tpl");
  
      $query = "SELECT title, review FROM ".$glob['dbprefix']."ImeiUnlock_testimonials ORDER BY testimonial_id DESC LIMIT 1";
      
/*$testmonials = $db->numrows($query);
if($testmonials==0){
	$query = "SELECT title, review FROM ".$glob['dbprefix']."ImeiUnlock_reviews ORDER BY id DESC LIMIT"." ".$config['nooftestimonial'];
}*/
$test = $db->select($query);   

if ($test) {
    for ($i=0; $i<count($test); $i++) {
    if (isset($test[$i]['title']) && !empty($test[$i]['title'])) {
    $box_content->assign("CLASS", cellColor($i, "tdEven", "tdOdd"));
    
         $box_content->assign("TITLE", $test[$i]['title']); 
		
		    $box_content->assign("REVIEW", substr(strip_tags($test[$i]['review']), 0, 200)."&hellip;"); 
         $box_content->parse("view_testimonial.announce_true.repeat");
                 
      }
    }
  
   $box_content->parse("view_testimonial.announce_true"); 
}else{
  $box_content->assign("NO_ANNOUNCMENTS", "There are no testimonial to display.");  
  $box_content->parse("view_testimonial.announce_false");   
}
$box_content->parse("view_testimonial");
$box_content = $box_content->text("view_testimonial");
?>