<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "ERREUR - %1\$s",
'no_error_msg' => "D&eacute;sol&eacute;, mais il n'existe pas de message d'erreur sp&eacute;cifi&eacute; pour e code d'erreur.",
'10001' => "Malheureusement, il n'existe pas de m&eacute;thodes d'exp&eacute;dition appropri&eacute;es disponibles pour votre commande. Ceci, soit parce que le poids total de votre commande est tr&egrave;s &eacute;lev&eacute;, soit parce que nous ne pouvons pas l'exp&eacute;dier dans votre pays. Veuillez contacter un membre de notre personnel pour plus d'informations.",
'10002' => "Votre lien de t&eacute;l&eacute;chargement a expir&eacute; ou n'est pas valide. Veuillez contacter un membre du personnel qui pourrait le restaurer ou fournir un moyen alternatif d'acc&egrave;s au fichier.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address." 
);
?>