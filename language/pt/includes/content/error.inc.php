<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['error'] = array(
'error' => "ERRO - %1\$s",
'no_error_msg' => "Desculpe mas n&atilde;o existe uma mensagem de erro espec&iacute;fica para esse c&oacute;digo de erro.",
'10001' => "Infelizmente n&atilde;o existem m&eacute;todos de envio adequados dispon&iacute;veis para a sua encomenda. Isto deve-se ao peso total da sua encomenda ser muito elevado ou a n&atilde;o podermos fazer envios para o seu pa&iacute;s. Por favor contacte um membro do nosso pessoal para quaisquer outras d&uacute;vidas.",
'10002' => "O seu link de download expirou ou n&atilde;o &eacute; v&aacute;lido. Por favor contacte um membro do pessoal que possa ser capaz de o reiniciar ou fornecer um meio alternativo de aceder ao ficheiro.",
'10003' => "Lamentamos mas apenas aceitamos pagamentos por PayPal de contas com endere&ccedil;o verificado."
);
?>
