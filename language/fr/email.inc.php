<?php
$lv = !$langBully ?  "lang": "bully";
${$lv}['email'] = array(
'coupon_subject' => "Votre chèque-cadeau!",
'coupon_body' => "Cher(ère) {RECIP_NAME},

{SENDER_NAME} vous a envoyé un chèque-cadeau d'une valeur de {MONTANT} qui peut être échangé contre toute marchandise dans notre magasin! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Message: (de {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Code du bon: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Pourquoi ne pas le dépenser maintenant?

Aller à: {STORE_URL}",
'downloads_body' => "Cher(ère) {RECIP_NAME},

Merci pour votre numéro de commande: {ORDER_ID} faite le {ORDER_DATE}

Vous trouverez ci-après des liens qui vous permettront d'accéder aux produits numériques que vous avez commandés.

IMPORTANT ces liens expirerons le {EXPIRE_DATE} et vous avez {DOWNLOAD_ATTEMPTS} des essais pour les télécharger. Si vous avez des problèmes, veuillez nous contacter en indiquant votre numéro de commande. 

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
LIEN DE TELECHARGEMENT:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Accès aux téléchargements: {ORDER_ID}",
'order_breakdown_1' => "Cher(ère) {RECIP_NAME},

Merci pour votre numéro de commande: {ORDER_ID} faite le {ORDER_DATE}

Transaction réussie et vos marchandises vous seront expédiées dès que possible (si applicable).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nom: {INVOICE_NAME}
Total partiel: {SUBTOTAL}
Expédition & emballage: {SHIPPING_COST}
{TAX_COST}
Total général: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Adresse de la facture:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Adresse de livraison:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Méthode de paiement: {PAYMENT_METHOD}
Méthode d'expédition: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\nVos commentaires: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Order Inventory:\n",
'order_breakdown_4' =>"Produit: {PRODUCT_NAME}\n",
'order_breakdown_4' =>"Options: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Quantité: {PRODUCT_QUANTITY}
Code du produit: {PRODUCT_CODE}
Prix: {PRODUCT_PRICE}\n\n",
'order_breakdown_7' => "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{EXTRA_NOTES}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'order_breakdown_subject' => "Commande complète #{ORDER_ID}",
'admin_pending_order_subject' => "Commande en cours #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, a placé une commande tout r�cemment #{ORDER_ID}. Cette commande est en cours de paiement et par la suite, elle ne devrait pas être exécutée si tous les fonds ne sont reçus. Veuillez suivre le lien ci-dessous pour afficher cette commande:

{ADMIN_ORDER_URL}

Adresse IP connectée: {SENDER_ID}",
'order_acknowledgement_subject' => "Accusé de réception de la commande #{ORDER_ID}",
'order_acknowledgement_body' => "Cher(ère) {CUSTOMER_NAME},

Ce courriel confirme que vous avez réussi à placer une nouvelle commande #{ORDER_ID}.  Une fois le paiement reçu, nous vous livrons les marchandises dès que possible.

Vous pouvez visualiser le statut de votre commande à tout moment, via notre site Web en suivant ce lien: 

{ORDER_URL}

N'hésitez pas de contacter un membre du personnel si vous avez des questions ou des problèmes avec votre achat",
'reset_password_body' => "Cher(ère) {RECIP_NAME},

Votre mot de passe est maintenant réinitialisé. Veuillez trouver ci-dessous vos nouveaux détails d'accès:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Adresse de couriel: {EMAIL}
Mot de passe: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Pour vous connecter, veuillez suivre le lien ci-dessous:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Adresse IP du demandeur: {SENDER_IP}",
'reset_password_subject' => "Nouveau mot de passe",
'profile_mofified_body' => "Cher(ère) {CUSTOMER_NAME},

Ce courriel a été envoyé pour confirmer la réussite de l'actualisation de vos informations personnelles. Si vous pensez que votre compte a été mis en jour par quelqu'un d'autre que vous, veuillez contacter immédiatement un membre du personnel.\n\n

Ce courriel vient de {STORE_URL}\n

Adresse IP du visiteur: {SENDER_IP}",
'profile_mofified_subject' => "Actualisation des informations personnelles",
'new_reg_subject' => "Détails de votre compte",
'new_reg_body' => "Cher(ère) {CUSTOMER_NAME},

Pour vos enregistrements, le compte suivant a été configuré pour vous permettre l'accès à notre site. Une fois connecté, vous pouvez afficher le statut de vos commandes, placer de façon efficace des commandes renouvelées et modifier votre profil.

Vos détails d'accès sont:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Courriel: 		{EMAIL}
Mot de passe: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Ce courriel vient de {STORE_URL}

Adresse IP d'inscription: {SENDER_IP}",
'tellafriend_body' => "Cher(ère) {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Pour afficher ce produit, veuillez suivre le lien ci-dessous:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Ce courriel vient de {STORE_URL}

Adresse IP de l'expéditeur: {SENDER_IP}",
'tellafriend_subject' => "Produit recommandé par {SENDER_NAME}",
'fraud_subject' => "Echec du journal de fraude de la commande {ORDER_ID}",
'fraud_body' => "Cher(ère) {RECIP_NAME},

Nous regrettons de vous informer que le paiement de votre commande {ORDER_ID} n'a pas réussi au test de contrôle exécuté par notre personnel ou par la banque. Si vous avez d'autres questions à ce sujet, veuillez consulter vos notes de commandes en suivant le lien ci-dessous ou contactez un membre du personnel en indiquant votre numéro de commande.

{ORDER_URL_PATH}

Raisons types pour ce genre de problèmes:
- Le pays choisi peut ne pas correspondre à celui où la carte a été délivrée. C'est un incident courant.
- Le code de sécurité à l'arrière de la carte peut avoir été mal saisi.
- Vous pourriez être entrain de placer une commande dans un pays autre que celui où votre carte a été délivrée.

N'hésitez pas à placer une nouvelle commande si vous le souhaitez. Aucune carte ni compte a été facturé pour cette commande.

Ce courriel vient de {STORE_URL}",


'payment_complete_subject' => "Paiement reçu pour {ORDER_ID}",
'payment_complete_body' => "Cher(ère) {RECIP_NAME},

Nous voudrions vous informer que le paiement pour la commande numéro {ORDER_ID}  a été réglé et vous recevrez vos marchandises bientôt. 

Ce courriel vient de {STORE_URL}",


'payment_cancelled_subject' => "Commande {ORDER_ID} annulée",
'payment_cancelled_body' => "Cher(ère) {RECIP_NAME},

La commande numéro {ORDER_ID} a été annulée. Pour plus d'informations à ce sujet, consultez les notes de commande qui se trouvent dans le lien suivant:

{ORDER_URL_PATH}

N.B. Les commandes peuvent être annulées par le client pendant l'achat ou par un membre du personnel. N'hésitez pas à placer une nouvelle commande si vous le souhaitez.

Ce courriel vient de {STORE_URL}",
'admin_reset_pass_body' => "Cher(ère) {RECIP_NAME},

Vous ou quelqu'un se passant pour vous demande la réinitialisation de votre mot de passe.

Vos nouveaux détails d'accès sont:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nom d'utilisateur: {USERNAME}
Mot de passe: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Ce courriel vient de {STORE_URL}\n

Adresse IP du demandeur: {SENDER_IP}",
'admin_reset_pass_subject' => "Nouveaux détails d'accès Admin",
'new_review_subject' => "Commentaire/journal du nouveau produit",
'new_review_body' => "Nom de l'auteur: {AUTHOR_NAME}
Courriel de l'auteur: {AUTHOR_EMAIL}
Adresse IP de l'auteur: {SENDER_ID}
Produit réexaminé: {PRODUCT_NAME}
Valeur: {RATING}
Réexaminer le titre: {REVIEW_TITLE}
Réexaminer la copie:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Approuvé: {APPROVE_URL}
Refusé: {DECLINE_URL}"
);
?>
