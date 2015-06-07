<?php
/*
+--------------------------------------------------------------------------
|	Social.inc.php
|   ========================================
|
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

$box_content = new XTemplate ("boxes".CC_DS."sociallinks.tpl");
if(!empty($config['Emailadd'])){
$box_content->assign("VAL_EMAILADD", stripslashes($config['Emailadd']));
$box_content->parse("social.email");
}
if(!empty($config['Fbadd'])){
$box_content->assign("VAL_FBADD", stripslashes($config['Fbadd']));
$box_content->parse("social.facebook");
}
if(!empty($config['Twiadd'])){
$box_content->assign("VAL_TWADD", stripslashes($config['Twiadd']));
$box_content->parse("social.twitter");
}
if(!empty($config['gplus'])){
$box_content->assign("VAL_GPLUS", stripslashes($config['gplus']));
$box_content->parse("social.gplus");
}
if(!empty($config['rss'])){
$box_content->assign("VAL_RSS", stripslashes($config['rss']));
$box_content->parse("social.rss");
}

$box_content->parse("social");
$box_content = $box_content->text("social");
?>