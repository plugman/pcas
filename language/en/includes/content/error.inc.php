<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "ERROR - %1\$s",
'no_error_msg' => "Sorry but there is no error message specified for that error code.",
'10001' => "Unfortunately there are no suitable shipping methods available for your order. This may be because the total weight of your order is too high or we cannot ship to your country. Please contact a member of our staff for any further inquiries.<p>It may help to <a href='index.php?_g=co&amp;_a=step2&amp;remlast=1' class='txtLink'>remove the last item added to your basket</a>.</p>",
'10002' => "Your download link has expired or is not valid. Please contact a member of staff who may be able to reset it for you or provide an alternative means of accessing the file.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address."
);
?>