<?php
if (!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Sim",
'no' => "N&atilde;o",
'na' => "N/A",
'sort' => 'Ordena&ccedil;&atilde;o',
'misc_pages' => " p&aacute;ginas ",
'misc_perofOrderSub' => " % do Subtotal da Encomenda",
'misc_freeForOrdOver' => "Gratuito para Encomendas Superiores a",
'misc_freeShipping' => "Envio Gratuito",
'misc_byWeight1stClass' => "Por Peso (1ª Classe)",
'misc_1stClass' => "(1ª Classe)",
'misc_byWeight2ndClass' => "Por Peso (2ª Classe)",
'misc_2ndClass' => "(2ª Classe)",
'misc_flatRate' => "Taxa Uniforme",
'misc_free' => "Gratuito",
'misc_national' => "Nacional",
'misc_international' => "Internacional",
'misc_byCategory' => "Por Categoria",
'misc_perItem' => "Por Artigo",
'misc_nextDayEarlyAm' => "A&eacute;reo Dia Seguinte de Manh&atilde;",
'misc_nextDayAir' => "A&eacute;reo Dia Seguinte",
'misc_nextDayAirSaver' => "A&eacute;reo Dia Seguinte Poupan&ccedil;a",
'misc_2ndDayEarlyAm' => "A&eacute;reo 2ºDia de Manh&atilde;",
'misc_2ndDayAir' => "A&eacute;reo 2º Dia",
'misc_3daySelect' => "Selec&ccedil;&atilde;o de 3 Dias",
'misc_ground' => "Por Terra",
'misc_canadaStandard' => "Correio Normal do Canad&aacute;",
'misc_worldwideExpress' => "Correio Expresso Mundial",
'misc_worldwideExpressPlus' => "Correio Expresso Mundial Mais",
'misc_worldwideExpedited' => "Correio Acelerado Mundial",
'popup_thumb_alt' => "Carregar para Ampliar",
'popup_large_alt' => "Vista Completa",
'login_view_price' => "Deve fazer login para ver os nossos pre&ccedil;os!",
'misc_price_hidden' => "?.??"
);
?>