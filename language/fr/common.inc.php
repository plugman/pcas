<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Oui",
'no' => "Non",
'na' => "N/A",
'sort' => 'Guichet',
'misc_pages' => " pages ",
'misc_perofOrderSub' => " % du total partiel de la commande",
'misc_freeForOrdOver' => "Gratuit pour les commandes de plus de",
'misc_freeShipping' => "Exp&eacute;dition gratuite",
'misc_byWeight1stClass' => "Par poids (1&egrave;me Classe)",
'misc_1stClass' => "(1&egrave;me Classe)",
'misc_byWeight2ndClass' => "Par poids (2&egrave;me Classe)",
'misc_2ndClass' => "(2eme Classe)",
'misc_flatRate' => "Taux fixe",
'misc_free' => "Gratuit",
'misc_national' => "National",
'misc_international' => "International",
'misc_byCategory' => "Par cat&eacute;gorie",
'misc_perItem' => "Par article",
'misc_nextDayEarlyAm' => "Vol matinal du lendemain ",
'misc_nextDayEarlyAm' => "Vol du lendemain ",
'misc_nextDayEarlyAm' => "Epargnant du vol matinal du lendemain ",
'misc_2ndDayEarlyAm' => "2&egrave;me vol matinal de jour ",
'misc_2ndDayEarlyAm' => "2&egrave;me vol de jour ",
'misc_3daySelect' => "S&eacute;lection de 3 jours",
'misc_ground' => "Route",
'misc_canadaStandard' => "Normes canadiennes ",
'misc_worldwideExpress' => "Worldwide Express",
'misc_worldwideExpressPlus' => "Worldwide Express Plus",
'misc_worldwideExpedited' => "Worldwide Expedited",
'popup_thumb_alt' => "Cliquez pour une image plus large",
'popup_large_alt' => "Affichez l'image compl&egrave;te",
'login_view_price' => "Vous devez vous connecter pour afficher nos prix !",
'misc_price_hidden' => "?.??"
);
?>