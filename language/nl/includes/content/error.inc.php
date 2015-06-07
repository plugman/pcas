<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "FOUTMELDING - %1\$s",
'no_error_msg' => "Sorry maar er is geen foutbericht dat met deze foutcode overeenstemt.",
'10001' => "Helaas zijn er geen geschikte verzendwijzen voor uw order. Dit is omdat het totale gewicht van uw order te hoog is of omdat we niet naar uw land kunnen versturen. Contacteer een personeelslid voor verdere informatie.",
'10002' => "Uw downloadlink is vervallen of is niet geldig. Contacteer een personeelslid dat dit voor u opnieuw kan instellen of die u een andere manier kan geven om toegang te krijgen tot het bestand.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address."
);
?>