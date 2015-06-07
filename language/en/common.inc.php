<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Yes",
'no' => "No",
'na' => "N/A",
'sort' => 'Sort',
'misc_pages' => " pages ",
'misc_perofOrderSub' => " % of Order Subtotal",
'misc_freeForOrdOver' => "Free for Orders Over",
'misc_freeShipping' => "Free Shipping",
'misc_byWeight1stClass' => "By Weight (1st Class)",
'misc_1stClass' => "(1st Class)",
'misc_byWeight2ndClass' => "By Weight (2nd Class)",
'misc_2ndClass' => "(2nd Class)",
'misc_flatRate' => "Flat Rate",
'misc_free' => "Free",
'misc_national' => "National",
'misc_international' => "International",
'misc_byCategory' => "By Category",
'misc_perItem' => "Per Item",
'misc_nextDayEarlyAm' => "Next Day Air Early AM",
'misc_nextDayAir' => "Next Day Air",
'misc_nextDayAirSaver' => "Next Day Air Saver",
'misc_2ndDayEarlyAm' => "2nd Day Air Early AM",
'misc_2ndDayAir' => "2nd Day Air",
'misc_3daySelect' => "3 Day Select",
'misc_ground' => "Ground",
'misc_canadaStandard' => "Canada Standard",
'misc_worldwideExpress' => "Worldwide Express",
'misc_worldwideExpressPlus' => "Worldwide Express Plus",
'misc_worldwideExpedited' => "Worldwide Expedited",
'popup_thumb_alt' => "Click for Larger Image",
'popup_large_alt' => "Full Image View",
'login_view_price' => "You must login to view our prices!",
'misc_price_hidden' => "?.??"
);
?>