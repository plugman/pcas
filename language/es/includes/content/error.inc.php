<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "ERROR - %1\$s",
'no_error_msg' => "Disculpe, pero no hay un mensaje de error especificad para ese c&oacute;digo de error.",
'10001' => "Lamentablemente, no hay m&eacute;todos de env&iacute;o apropiados disponibles para su pedido. Esto puede deberse a que el peso total de su pedido es demasiado alto o a que no podemos enviar a su pa&iacute;s. Por favor contacte a un miembro de nuestro equipo si tiene preguntas adicionales.",
'10002' => "Su v&iacute;nculo de descarga ha expirado o no es v&aacute;lido. Por favor contacte a un miembro del equipo, el cual tal vez pueda restablecerlo o proveerle una forma alternativa para acceder al archivo.",
'10003' => "I am sorry but we can only take PayPal orders from accounts with a verified address."
);
?>
