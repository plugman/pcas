<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "S&iacute;",
'no' => "No",
'na' => "N/D",
'sort' => 'Clasificar',
'misc_pages' => " p&aacute;ginas ",
'misc_perofOrderSub' => " Subtotal de % de Pedido",
'misc_freeForOrdOver' => "Gratis para Pedidos por encima de",
'misc_freeShipping' => "Env&iacute;o Gratuito",
'misc_byWeight1stClass' => "Por Peso (1era Clase)",
'misc_1stClass' => "(1era Clase)",
'misc_byWeight2ndClass' => "Por Peso (2da Clase)",
'misc_2ndClass' => "(2da Clase)",
'misc_flatRate' => "Tarifa Fija",
'misc_free' => "Gratis",
'misc_national' => "Nacional",
'misc_international' => "Internacional",
'misc_byCategory' => "Por Categor&iacute;a",
'misc_perItem' => "Por &Iacute;tem",
'misc_nextDayEarlyAm' => "Al D&iacute;a Siguiente Por Aire Temprano AM",
'misc_nextDayAir' => "Al D&iacute;a Siguiente por Aire",
'misc_nextDayAirSaver' => "Ahorro Al D&iacute;a Siguiente por Aire",
'misc_2ndDayEarlyAm' => "2do D&iacute;a Por Aire Temprano AM",
'misc_2ndDayAir' => "2do D&iacute;a por Aire",
'misc_3daySelect' => "Selecci&oacute;n de 3 D&iacute;as",
'misc_ground' => "Por Tierra",
'misc_canadaStandard' => "Canad&aacute; Est&aacute;ndar",
'misc_worldwideExpress' => "Worldwide Express",
'misc_worldwideExpressPlus' => "Expreso Mundial Plus",
'misc_worldwideExpedited' => "Mundial Expedito",
'popup_thumb_alt' => "Haga Clic para Agrandar la Imagen",
'popup_large_alt' => "Vista de Imagen Completa",
'login_view_price' => "&iexcl;Debe acceder para ver sus piezas!",
'misc_price_hidden' => "?.??"
);
?>
