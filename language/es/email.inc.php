<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "¡Su certificado de regalo!",
'coupon_body' => "Estimado {RECIP_NAME},

{SENDER_NAME} le ha enviado un vale para regalo por valor de {AMOUNT} ¡que puede canjearse por cualquier artículo en nuestra tienda! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Mensaje: (de {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Código de Vale: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

¿Por qué no gastarlo ahora?

Ir a: {STORE_URL}",
'downloads_body' => "Estimado {RECIP_NAME},

Gracias por su pedido no: {ORDER_ID} realizado el {ORDER_DATE}

A continuación están los vínculos para acceder a los productos digitales que ordenó.

IMPORTANTE estos vínculos expirarón el {EXPIRE_DATE} y tiene {DOWNLOAD_ATTEMPTS} intentos para descargarlos. Si tuviera cualquier problema, por favor contáctenos declarando el número de pedido.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
VÍNCULO DE DESCARGA:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Acceso de descargas: {ORDER_ID}",
'order_breakdown_1' => "Estimado {RECIP_NAME},

Gracias por su pedido no: {ORDER_ID} realizado el {ORDER_DATE}

La transacción fue exitosa y enviaremos sus artículos lo antes posible (si corresponde).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nombre: {INVOICE_NAME}
Subtotal: {SUBTOTAL}
Franqueo y Envalado: {SHIPPING_COST}
{TAX_COST}
Total Final: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Dirección de Facturación:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Dirección de Entrega:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Método de Pago: {PAYMENT_METHOD}
Método de Envío: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\nSus comentarios: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Order Inventory:\n",
'order_breakdown_4' =>"Producto: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Opciones: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Cantidad: {PRODUCT_QUANTITY}
Código de Producto: {PRODUCT_CODE}
Precio: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Pedido Completado #{ORDER_ID}",
'admin_pending_order_subject' => "Pedido Pendiente #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, recientemente realizó el pedido #{ORDER_ID}. Este pedido esté pendiente de pago y por ende, no se completaró hasta que usted reciba el total de los fondos. Por favor siga el vínculo siguiente para ver este pedido:

{ADMIN_ORDER_URL}

Dirección de IP de Acceso: {SENDER_ID}",
'order_acknowledgement_subject' => "Confirmación de Pedido #{ORDER_ID}",
'order_acknowledgement_body' => "Estimado {CUSTOMER_NAME},

Este email confirma que usted realizó con éxito un nuevo pedido #{ORDER_ID}.  Una vez se reciba el pago, enviaremos sus artículos lo antes posible.

Puede ver el estado de su pedido en cualquier momento, por medio de nuestro sitio web, siguiendo el vínculo siguiente: 

{ORDER_URL}

Por favor no dude en contactar a un miembro del equipo si tiene cualquier pregunta o problema con su compra.",
'reset_password_body' => "Estimado {RECIP_NAME},

Su contraseña se ha restablecido. Por favor vea sus nuevos detalles de acceso a continuación:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Dirección de Email: {EMAIL}
Contraseña: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Para acceder, por favor siga el vínculo siguiente:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Dirección de IP del Solicitante: {SENDER_IP}",
'reset_password_subject' => "Nueva Contraseña",
'profile_mofified_body' => "Estimado {CUSTOMER_NAME},

Este email se ha enviado para confirmar que su información personal se ha actualizado con éxito. Si considera que su cuenta fue actualizada por alguien más, por favor contacte a un miembro del equipo de forma inmediata.\n\n

Este email se envió desde {STORE_URL}\n

Dirección de IP del Visitante: {SENDER_IP}",
'profile_mofified_subject' => "Información Personal Actualizada",
'new_reg_subject' => "Sus Detalles de Cuenta",
'new_reg_body' => "Estimado {CUSTOMER_NAME},

Para su registro, la siguiente cuenta se ha creado para que usted pueda acceder a nuestro sitio. Una vez que hubiera accedido, puede ver el estado de sus pedidos, realizar pedidos repetidos de forma eficiente y enmendar su perfil.

Sus detalles de acceso son:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Email: 		{EMAIL}
Contraseña: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este email se envió desde {STORE_URL}

Dirección de IP de Registro: {SENDER_IP}",
'tellafriend_body' => "Estimado {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Por favor siga el vínculo siguiente para ver este producto:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este email se envió desde {STORE_URL}

Dirección de IP del Remitente: {SENDER_IP}",
'tellafriend_subject' => "Producto Recomendado por {SENDER_NAME}",
'fraud_subject' => "Pedido {ORDER_ID} Revisión de Fraude Fallida",
'fraud_body' => "Estimado {RECIP_NAME},

Lamentamos informarle que el pago de su pedido {ORDER_ID} no aprobó el control de seguridad realizado por nuestro equipo o el banco. Si tuviera cualquier pregunta con relación a esto, por favor refiérase a las notas de su pedido siguiendo el vínculo de abajo o contacte a un miembro del equipo citando su número de pedido.

{ORDER_URL_PATH}

Razones comunes para esto:
- Puede que el país elegido no hubiera coincidido con el de emisión de la tarjeta. Éste es un accidente comón.
- Puede que el código de seguridad, que se encuentra en el reverso de su tarjeta, se hubiera ingresado de forma incorrecta.
- Puede que esté comprando el pedido en un país distinto al de la emisión de su tarjeta.

Si desea crear un nuevo pedido, por favor no dude en hacerlo. No se ha cargado este pedido a ninguna tarjeta ni cuenta.

Este email se envió desde {STORE_URL}",
'payment_complete_subject' => "Pago Recibido para {ORDER_ID}",
'payment_complete_body' => "Estimado {RECIP_NAME},

Sólo deseamos informarle que el pago por el pedido número {ORDER_ID} ha sido aprobado y estará recibiendo sus artículos en breve.

Este email se envió desde {STORE_URL}",


'payment_cancelled_subject' => "Pedido {ORDER_ID} Cancelado",
'payment_cancelled_body' => "Estimado {RECIP_NAME},

El pedido número {ORDER_ID} se ha cancelado. Podría encontrarse más información sobre ello en las notas del pedido, que pueden encontrarse en el siguiente vínculo:

{ORDER_URL_PATH}

Los Pedidos N.B. pueden cancelarse por el cliente durante la compra o por un miembro del equipo. Si desea realizar un nuevo pedido, por favor no dude en hacerlo.

Este email se envió desde {STORE_URL}",
'admin_reset_pass_body' => "Estimado {RECIP_NAME},

Usted, o alguien haciéndose pasar por usted solicitó que su contraseña se restablezca.

Sus nuevos detalles de acceso son:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nombre de usuario: {USERNAME}
Contraseña: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Este email se envió desde {STORE_URL}\n

Dirección de IP del Solicitante: {SENDER_IP}",
'admin_reset_pass_subject' => "Detalles de Acceso Nuevo Admin",
'new_review_subject' => "Nueva Reseña/Comentario de Producto",
'new_review_body' => "Nombre del Autor: {AUTHOR_NAME}
Email del Autor: {AUTHOR_EMAIL}
Dirección de IP del Autor: {SENDER_ID}
Producto Revisado: {PRODUCT_NAME}
Puntuación: {RATING}
Título de la Reseña: {REVIEW_TITLE}
Ejemplar para Reseña:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Aprobar: {APPROVE_URL}
Rechazar: {DECLINE_URL}"
);
?>
