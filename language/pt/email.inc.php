<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "O seu cheque-prenda!",
'coupon_body' => "Caro/a {RECIP_NAME},

{SENDER_NAME} enviou-lhe um vale de compras no valor de {AMOUNT} que pode ser trocado por qualquer bem na nossa loja! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Mensagem: (De {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
C&oacute;digo do Vale: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Vai us&aacute;-lo agora?

Ir para: {STORE_URL}",
'downloads_body' => "Caro/a {RECIP_NAME},

Obrigado pela sua encomenda n.º: {ORDER_ID} feita em {ORDER_DATE}

Em baixo est&atilde;o os links de que necessita para aceder aos produtos digitais que encomendou.

IMPORTANTE estes links ir&atilde;o expirar em {EXPIRE_DATE} e voc&ecirc; tem {DOWNLOAD_ATTEMPTS} tentativas para os descarregar. Se tiver algum problema, por favor contacte-nos, indicando o seu n&uacute;mero de encomenda.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
LINK PARA O DOWNLOAD:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Acesso aos Downloads: {ORDER_ID}",
'order_breakdown_1' => "Caro/a {RECIP_NAME},

Obrigado pela sua encomenda n.º: {ORDER_ID} feita em {ORDER_DATE}

A transac&ccedil;&atilde;oo foi bem sucedida e iremos enviar-lhe os seus bens na primeira oportunidade (se aplic&aacute;vel).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nome: {INVOICE_NAME}
Sub-total: {SUBTOTAL}
Portes & Embalagem: {SHIPPING_COST}
{TAX_COST}
Total: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Endere&ccedil;o na Factura:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Endere&ccedil;o para Entrega:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Forma de Pagamento: {PAYMENT_METHOD}
Forma de Envio: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\n Os seus coment&aacute;rios: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Resumo de Encomendas:\n",
'order_breakdown_4' =>"Produto: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Op&ccedil;&otilde;es: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Quantidade: {PRODUCT_QUANTITY}
C&oacute;digo do Produto: {PRODUCT_CODE}
Pre&ccedil;o: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Encomenda Completa #{ORDER_ID}",
'admin_pending_order_subject' => "Encomenda Pendente #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, fez recentemente a encomenda #{ORDER_ID}. Esta encomenda est&aacute; pendente do pagamento e como resultado n&atilde;o ser&aacute; expedida enquanto n&atilde;o liquidada. Por favor siga o seguinte link para ver esta encomenda:

{ADMIN_ORDER_URL}

Endere&ccedil;o IP de Registado: {SENDER_ID}",
'order_acknowledgement_subject' => "Aviso de Recec&ccedil;&atilde;o da Encomenda #{ORDER_ID}",
'order_acknowledgement_body' => "Caro/a {CUSTOMER_NAME},

Este e-mail conforma que voc&ecirc; realizou com sucesso uma nova encomenda #{ORDER_ID}. Assim que o pagamento seja confirmado a encomenda ser&aacute; expedida.

Pode ver o estado da sua encomenda a qualquer momento, atrav&eacute;s do nosso site, seguindo o seguinte link:

{ORDER_URL}

Por favor, esteja &aacute; vontade para contactar um membro do pessoal, se tiver algumas quest&otilde;es ou problemas com a sua compra.",
'reset_password_body' => "Caro/a {RECIP_NAME},

A sua senha foi agora redefinida. Por favor encontre os seus novos pormenores de acesso em baixo:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Endere&ccedil;o de e-mail: {EMAIL}
Senha: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Para fazer o login, por favor siga o seguinte link:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Endere&ccedil;o de IP do Requerente: {SENDER_IP}",
'reset_password_subject' => "Senha Nova",
'profile_mofified_body' => "Caro/a {CUSTOMER_NAME},

Este e-mail foi enviado para confirmar que a sua informa&ccedil;&atilde;o pessoal foi actualizada com sucesso. Se pensa que a sua conta foi actualizada por algu&ecirc;m que n&atilde;o voc&ecirc;, por favor contacte imediatamente um membro do pessoal.\n\n

Este e-mail foi enviado a partir de {STORE_URL}\n

Endere&ccedil;o de IP do Visitante: {SENDER_IP}",
'profile_mofified_subject' => "Informa&ccedil;&atilde;o Pessoal Actualizada",
'new_reg_subject' => "Os Pormenores da Sua Conta",
'new_reg_body' => "Caro/a {CUSTOMER_NAME},

Para seu registo, a conta seguinte foi configurada de modo a que possa fazer o login ao nosso site. Uma vez feito o login, consultar o estado das suas encomendas, repetir encomendas eficazmente e corrigir o seu perfil.

Os seus dados de acesso s&atilde;o:

~~~~~~~~~~~~~~~~~~~~~~~~~~
E-mail: 	{EMAIL}
Senha:	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este e-mail foi enviado a partir de {STORE_URL}

Endere&ccedil;o de IP de Registo: {SENDER_IP}",
'tellafriend_body' => "Caro/a {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Para ver este produto, por favor siga o seguinte link:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este e-mail foi enviado a partir de {STORE_URL}

Endere&ccedil;o de IP do Remetente: {SENDER_IP}",
'tellafriend_subject' => "Produto Recomendado por {SENDER_NAME}",
'fraud_subject' => "Encomenda {ORDER_ID} An&aacute;lise de Fraude Falhada",
'fraud_body' => "Caro/a {RECIP_NAME},

Lamenta-mos inform&aacute;-lo que o pagamento referente &aacute; encomenda {ORDER_ID} n&atilde;o passou as verifica&ccedil;&otilde;es de seguran&ccedil;a realizadas pelo nosso pessoal ou pelo banco. Se tiver mais quest&otilde;es relativas a isto, por favor consulte as suas notas de encomenda seguindo o seguinte link, ou contacte um membro do pessoal, apresentando o seu n&uacute;mero de encomenda.

{ORDER_URL_PATH}

Raz&otilde;es habituais para isto:
- O pa&iacute;s seleccionado pode n&atilde;o estar em conformidade com aquele em que o cart&atilde;o foi emitido. Este &eacute; um erro normal.
- O c&oacute;digo de seguran&ccedil;a que pode encontrar na parte de tr&aacute;s do seu cart&atilde;o pode ter sido introduzido incorrectamente.
- Pode estar a comprar a encomenda num pa&aacute;s diferente daquele em que o cart&atilde;o foi emitido.

Se deseja criar uma nova encomenda, por favor esteja &aacute; vontade para o fazer. N&atilde;o foi cobrado qualquer valor por esta encomenda.

Este e-mail foi enviado a partir de {STORE_URL}",


'payment_complete_subject' => "Pagamento Recebido para {ORDER_ID}",
'payment_complete_body' => "Caro/a {RECIP_NAME},

Gostariamos de o informar que o seu pagamento para a encomenda n&uacute;mero {ORDER_ID} foi autorizado e voc&ecirc; deve receber os seus bens em breve.

Este e-mail foi enviado a partir de {STORE_URL}",


'payment_cancelled_subject' => "Encomenda {ORDER_ID} Cancelada",
'payment_cancelled_body' => "Caro/a {RECIP_NAME},

A encomenda n&uacute;mero {ORDER_ID} foi cancelada. Mais informa&ccedil;&atilde;o sobre isto pode encontrar-se nas notas de encomenda, que podem ser encontradas seguindo este link:

{ORDER_URL_PATH}

P.S. As encomendas podem ser canceladas pelo cliente durante a aquisi&ccedil;&atilde;o, ou por um membro do pessoal. Se deseja fazer uma nova encomenda, por favor esteja &aacute; vontade para o fazer.

Este e-mail foi enviado a partir de {STORE_URL}",
'admin_reset_pass_body' => "Caro/a {RECIP_NAME},

Voc&ecirc;, ou algu&ecirc;m a tentar passar por si, pediu que a sua senha fosse redefinida.

Os novos pormenores do seu acesso s&atilde;o:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nome do Utilizador: {USERNAME}
Senha: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este e-mail foi enviado a partir de {STORE_URL}\n

Endere&ccedil;o de IP do Requerente: {SENDER_IP}",
'admin_reset_pass_subject' => "Novos Pormenores de Acesso de Administrador",
'new_review_subject' => "Nova An&aacute;lise/Coment&aacute;rio ao Produto",
'new_review_body' => "Nome do Autor: {AUTHOR_NAME}
E-mail do Autor: {AUTHOR_EMAIL}
Endere&ccedil;o IP do Autor: {SENDER_ID}
Produto Analisado: {PRODUCT_NAME}
Classifica&ccedil;&atilde;o: {RATING}
T&iacute;tulo da An&aacute;lise: {REVIEW_TITLE}
C&oacute;pia da An&aacute;lise:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Aprovar: {APPROVE_URL}
Recusar: {DECLINE_URL}"
);
?>
