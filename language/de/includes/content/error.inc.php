<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "FEHLER - %1\$s",
'no_error_msg' => "Zu diesem Fehlercode gibt es leider keine Fehlernachricht.",
'10001' => "Wir haben leider keine geeignete Versandart f&uuml;r Ihre Bestellung. Entweder &uuml;berschreitet die Ware das zul&auml;ssige Gesamtgewicht oder wir verschicken keine Ware in Ihr Land. Bitte wenden Sie sich mit weiteren Fragen an unser Shop-Team.",
'10002' => "Ihr Download-Link ist abgelaufen und nicht l&auml;nger g&uuml;ltig. Bitte wenden Sie sich an unser Shop-Team um den Link zur&uuml;ckzusetzen oder Ihnen eine Alternative zum Zugriff auf die Datei zu bieten.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address."
);
?>