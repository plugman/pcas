<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "FEIL - %1\$s",
'no_error_msg' => "Beklager, men ingen feilmelding er spesifisert for den feilkoden",
'10001' => "Beklageligvis finnes det ingen passende forsendelsesmetoder for din ordre. Dette er enten fordi totalvekten av din ordre er for h&oslash;y eller fordi vi ikke kan sende til ditt land. Vennligst kontakt oss dersom du skulle ha sp&oslash;rsm&aring;l ang&aring;ende dette.",
'10002' => "Din nedlastningslink er utg&aring;tt eller er ikke gyldig. Vennligst kontakt oss, s&aring; kan vi kanskje resette koden eller tilby alternative m&aring;ter &aring; f&aring; tilgang til filen p&aring;.",
'10003' => "Vi beklager, men vi kan kun ta PayPal-ordrer fra kunder med verifiserte adresser."
);
?>