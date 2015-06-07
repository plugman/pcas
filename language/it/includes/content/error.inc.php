<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "ERRORE - %1\$s",
'no_error_msg' => "Spiacenti ma non c'&egrave; alcun messagio di errore specificato per quel codice errore.",
'10001' => "Sfortunatamente non ci sono idonee modalit&agrave; di spedizione disponibili per il tuo ordine. Questo perch&eacute; il peso totale.del tuo ordine &egrave; troppo alto o non possiamo spedire nel tuo Paese. Prego contattare un nostro addetto per ulteriori informazioni.",
'10002' => "Il tuo link di download &egrave; scaduto o non &egrave; valido. Prego contattare un nostro addetto che potrebbe essere in grado di ripristinartelo o fornire un mezzo alternativo per accedere al file.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address."
);
?>