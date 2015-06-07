<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Ja",
'no' => "Nee",
'na' => "Niet van toepassing",
'sort' => 'Sorteren',
'misc_pages' => " pagina's ",
'misc_perofOrderSub' => " % van Order Subtotaal",
'misc_freeForOrdOver' => "Gratis voor orders groter dan",
'misc_freeShipping' => "Gratis verzonden",
'misc_byWeight1stClass' => "Per Gewicht(1e Klasse)",
'misc_1stClass' => "(1e Klasse)",
'misc_byWeight2ndClass' => "Per Gewicht(2e Klasse)",
'misc_2ndClass' => "(2e Klasse)",
'misc_flatRate' => "Forfaitair bedrag",
'misc_free' => "Gratis",
'misc_national' => "Nationaal",
'misc_international' => "Internationaal",
'misc_byCategory' => "Per Categorie",
'misc_perItem' => "Per Item",
'misc_nextDayEarlyAm' => "Volgende dag per Lucht 's morgens vroeg",
'misc_nextDayAir' => "Volgende dag per lucht",
'misc_nextDayAirSaver' => "Volgende dag per lucht lager tarief",
'misc_2ndDayEarlyAm' => "2e Dag per lucht 's morgens vroeg",
'misc_2ndDayAir' => "2e Dag per lucht",
'misc_3daySelect' => "3e Dag Select",
'misc_ground' => "Grond",
'misc_canadaStandard' => "Canada Standaard",
'misc_worldwideExpress' => "Wereldwijde Express",
'misc_worldwideExpressPlus' => "Wereldwijde Express Plus",
'misc_worldwideExpedited' => "Wereldwijd Snel",
'popup_thumb_alt' => "Klik voor een grotere afbeelding",
'popup_large_alt' => "Bekijk volledige afbeelding",
'login_view_price' => "Je moet ingelogd zijn om onze prijzen te zien!",
'misc_price_hidden' => "?.??"
);
?>