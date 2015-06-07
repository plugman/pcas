<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Ja",
'no' => "Nein",
'na' => "Nicht zutreffend",
'sort' => 'Sortieren',
'misc_pages' => " Seiten ",
'misc_perofOrderSub' => " % der Zwischensumme",
'misc_freeForOrdOver' => "Frei bei Bestellungen &uuml;ber",
'misc_freeShipping' => "Freier Versand",
'misc_byWeight1stClass' => "Nach Gewicht (1. Klasse)",
'misc_1stClass' => "(1. Klasse)",
'misc_byWeight2ndClass' => "Nach Gewicht (2. Klasse)",
'misc_2ndClass' => "(2. Klasse)",
'misc_flatRate' => "Pauschale",
'misc_free' => "Frei",
'misc_national' => "Inland",
'misc_international' => "Ausland",
'misc_byCategory' => "Nach Kategorie",
'misc_perItem' => "Pro Artikel",
'misc_nextDayEarlyAm' => "N&auml;chster Tag Luftfracht fr&uuml;h morgens",
'misc_nextDayAir' => "N&auml;chster Tag Luftfracht",
'misc_nextDayAirSaver' => "N&auml;chster Tag Luftfracht Saver",
'misc_2ndDayEarlyAm' => "2. Tag Luftfracht fr&uuml;h morgens",
'misc_2ndDayAir' => "2. Tag Luftfracht",
'misc_3daySelect' => "3. Tag ausw&auml;hlen",
'misc_ground' => "Boden",
'misc_canadaStandard' => "Kanada Standard",
'misc_worldwideExpress' => "Worldwide Express",
'misc_worldwideExpressPlus' => "Worldwide Express Plus",
'misc_worldwideExpedited' => "Worldwide Expedited",
'popup_thumb_alt' => "Anklicken um ein gr&ouml;&szlig;eres Bild anzuzeigen",
'popup_large_alt' => "Volle Bildgr&ouml;&szlig;e",
'login_view_price' => "Sie m&uuml;ssen angemeldet sein um unsere Preise anzuzeigen!",
'misc_price_hidden' => "?.??"
);
?>