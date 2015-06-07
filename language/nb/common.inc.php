<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Ja",
'no' => "Nei",
'na' => "Ikke tilgjengelig",
'sort' => 'Sorter',
'misc_pages' => " sider ",
'misc_perofOrderSub' => " % av subtotal for ordren ",
'misc_freeForOrdOver' => "Gratis for bestillinger over",
'misc_freeShipping' => "Gratis forsendelse",
'misc_byWeight1stClass' => "Etter vekt (F&oslash;rste klasse)",
'misc_1stClass' => "(F&oslash;rste klasse)",
'misc_byWeight2ndClass' => "Etter vekt (Andre klasse)",
'misc_2ndClass' => "(Andre klasse)",
'misc_flatRate' => "Fastpris",
'misc_free' => "Gratis",
'misc_national' => "Innenlands",
'misc_international' => "Internasjonal",
'misc_byCategory' => "Etter kategori",
'misc_perItem' => "Per produkt",
'misc_nextDayEarlyAm' => "Neste dag - flypost morgen",
'misc_nextDayAir' => "Neste dag - flypost",
'misc_nextDayAirSaver' => "Neste dag - flypost &oslash;konomi",
'misc_2ndDayEarlyAm' => "To-dagers flypost - morgen",
'misc_2ndDayAir' => "To-dagers flypost",
'misc_3daySelect' => "Tre-dagers post",
'misc_ground' => "Standard",
'misc_canadaStandard' => "Canada Standard",
'misc_worldwideExpress' => "Verdensomspennende Ekspress",
'misc_worldwideExpressPlus' => "Verdensomspennende Ekspress Pluss",
'misc_worldwideExpedited' => "Verdensomspennende via ekspedit&oslash;r",
'popup_thumb_alt' => "Klikk for st&oslash;rre bilde",
'popup_large_alt' => "Vis fullt bilde",
'login_view_price' => "Du m&aring; logge inn for &aring; se v&aring;re priser!",
'misc_price_hidden' => "?.??"
);
?>