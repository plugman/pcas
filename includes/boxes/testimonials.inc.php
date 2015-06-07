<?php

if(!defined('CC_INI_SET')){ die("Access Denied"); }  

$box_content = new XTemplate ("boxes".CC_DS."testimonials.tpl");
  
      $query = "SELECT title, review, name FROM ".$glob['dbprefix']."ImeiUnlock_testimonials ORDER BY testimonial_id DESC";
      
/*$testmonials = $db->numrows($query);
if($testmonials==0){
	$query = "SELECT title, review FROM ".$glob['dbprefix']."ImeiUnlock_reviews ORDER BY id DESC LIMIT"." ".$config['nooftestimonial'];
}*/
$test = $db->select($query);   

if ($test) {
     $box_content->assign("DATA", $test);
   $box_content->parse("view_testimonial.announce_true"); 
}else{
  $box_content->assign("NO_ANNOUNCMENTS", "There are no testimonial to display.");  
  $box_content->parse("view_testimonial.announce_false");   
}
$box_content->parse("view_testimonial");
$box_content = $box_content->text("view_testimonial");
?>