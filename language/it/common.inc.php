<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Si",
'no' => "No",
'na' => "N/A",
'sort' => 'Scegli',
'misc_pages' => " pagine ",
'misc_perofOrderSub' => " % del Totale Parziale Ordine",
'misc_freeForOrdOver' => "Gratis per i rimanenti Ordini",
'misc_freeShipping' => "Spedizione Gratuita",
'misc_byWeight1stClass' => "A Peso (1^ Class)",
'misc_1stClass' => "(1^ Class)",
'misc_byWeight2ndClass' => "A Peso (2^ Class)",
'misc_2ndClass' => "(2^ Class)",
'misc_flatRate' => "Tariffa Netta",
'misc_free' => "Gratis",
'misc_national' => "Nazionale",
'misc_international' => "Internazionale",
'misc_byCategory' => "A Categoria",
'misc_perItem' => "Per Articolo",
'misc_nextDayEarlyAm' => "Giorno Successivo Via Aera Mattina",
'misc_nextDayEarlyAm' => "Giorno Successivo Via Aera in Mattinata",
'misc_nextDayAirSaver' => " Giorno Successivo Via Aerea a Risparmio",
'misc_2ndDayEarlyAm' => "2° Giorno Via Aerea Mattinata",
'misc_2ndDayAir' => "2° Giorno Via Aerea",
'misc_3daySelect' => "3° Giorno Selezionato",
'misc_ground' => "Via terra",
'misc_canadaStandard' => "Canada Standard",
'misc_worldwideExpress' => "Worldwide Express",
'misc_worldwideExpressPlus' => "Worldwide Express Plus",
'misc_worldwideExpedited' => "Worldwide Expedited",
'popup_thumb_alt' => "Clicca per Ingrandire Immagine",
'popup_large_alt' => "Visione a Piena Immagine",
'login_view_price' => "Devi fare login per visionare i nostri prezzi!",
'misc_price_hidden' => "?.??"
);
?>