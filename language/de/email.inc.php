<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "Ihr Geschenkgutschein!",
'coupon_body' => "Liebe/ Lieber {RECIP_NAME},

{SENDER_NAME} hat Ihnen einen Geschenkgutschein im Wert von {BETRAG} geschickt, den Sie gegen beliebige Ware in unserem Shop einlösen können! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nachricht: (von {SENDER_NAME} <{SENDER_EMAIL}>)
{NACHRICHT}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Gutscheincode: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Warum nicht gleich einlösen?

Goto: {STORE_URL}",
'downloads_body' => "Liebe/ Lieber {RECIP_NAME},

Vielen Dank für Ihre Bestellung: {ORDER_ID} vom {ORDER_DATE}

Mit den folgenden Links können Sie auf Ihre bestellte Ware in digitaler Form zugreifen.

ACHTUNG diese Links sind nur bis zum {EXPIRE_DATE} gültig und es stehen Ihnen {DOWNLOAD_ATTEMPTS} Versuche zum Herunterladen zur Verfügung. Wenn Probleme auftreten, setzen Sie sich unter Angabe Ihrer Bestell-Nr. mit uns in Verbindung.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
DOWNLOAD LINK:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Downloads Zugriff: {ORDER_ID}",
'order_breakdown_1' => "Liebe/ Lieber {RECIP_NAME},

Vielen Dank für Ihre Bestellung {ORDER_ID} vom {ORDER_DATE}

Der Vorgang war erfolgreich und wir werden Ihre Ware so schnell wie möglich versenden (falls zutreffend).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Name: {INVOICE_NAME}
Zwischensumme: {SUBTOTAL}
Zustellgebühren & Verpackung: {SHIPPING_COST}
{TAX_COST}
Gesamtbetrag: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Rechnungsadresse:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Lieferadresse:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Zahlungsmethode: {PAYMENT_METHOD}
Versandart: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\nIhre Bemerkungen: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Bestellaufnahme:\n",
'order_breakdown_4' =>"Produkt: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Optionen: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Menge: {PRODUCT_QUANTITY}
Produkt-Code: {PRODUCT_CODE}
Preis: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Bestellung ist abgeschlossen #{ORDER_ID}",
'admin_pending_order_subject' => "Anstehende Bestellung #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, hat vor kurzem eine Bestellung mit der Nr. {ORDER_ID} aufgegeben. Für diese Bestellung ist noch keine Bezahlung erfolgt und die Bestellung darf erst ausgeführt werden, wenn der füllige Betrag in voller Höhe eingegangen ist. Bitte auf den folgenden Link klicken, um die Bestellung anzuzeigen:

{ADMIN_ORDER_URL}

Protokollierte IP-Adresse: {SENDER_ID}",
'order_acknowledgement_subject' => "Auftragsbestätigung #{ORDER_ID}",
'order_acknowledgement_body' => "Liebe/ Lieber {CUSTOMER_NAME},

Diese E-Mail bestätigt, dass Sie erfolgreich eine neue Bestellung mit der Nr.{ORDER_ID} aufgegeben haben. Wir werden Ihre Waren so schnell wie möglich ausliefern, sobald Ihre Bezahlung eingegangen ist.

Sie können den Status Ihrer Bestellung jeder Zeit über unsere Website anzeigen, indem Sie auf den folgenden Link klicken: 

{ORDER_URL}

Sie dürfen sich gerne mit Fragen oder Problemen zur Bestellung an unser Shop-Team wenden.",
'reset_password_body' => "Liebe/ Lieber {RECIP_NAME},

Ihr Passwort wurde zurückgesetzt. Hier sind Ihre neuen Zugangsdaten:

~~~~~~~~~~~~~~~~~~~~~~~~~~
E-Mailadresse: {EMAIL}
Passwort: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Um sich einzuloggen klicken Sie bitte auf den folgenden Link:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

IP-Adresse des Antragstellers: {SENDER_IP}",
'reset_password_subject' => "Neues Passwort",
'profile_mofified_body' => "Liebe/ Lieber {CUSTOMER_NAME},

Mit dieser E-Mail bestätigen wir, dass Ihre persönlichen Daten erfolgreich aktualisiert wurden. Wenn Sie den Verdacht haben, dass jemand unzulässigerweise änderungen an Ihrem Konto vorgenommen hat, setzten Sie sich bitte sofort mit unserem Shop-Team in Verbindung.\n\n

Sie erhalten diese E-Mail von {STORE_URL}\n

IP-Adresse des Besuchers: {SENDER_IP}",
'profile_mofified_subject' => "Persönliche Daten aktualisiert",
'new_reg_subject' => "Ihre Kontoinformationen",
'new_reg_body' => "Liebe/ Lieber {CUSTOMER_NAME},

Das folgende Konto wurde für Sie eingerichtet, über das Sie sich in unsere Website einloggen können. Wenn Sie eingeloggt sind, können Sie den Status Ihrer Bestellungen anzeigen, schnell und einfach eine neue Bestellung aufgeben und Ihr Profil ergänzen.

Ihre Zugangsdaten sind:

~~~~~~~~~~~~~~~~~~~~~~~~~~
E-Mail: 		{EMAIL}
Passwort: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Sie erhalten diese E-Mail von {STORE_URL}

Registrierungs-IP-Adresse: {SENDER_IP}",
'tellafriend_body' => "Liebe/ Lieber {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Um dieses Produkt anzuzeigen klicken Sie bitte auf den folgenden Link:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Sie erhalten diese E-Mail von {STORE_URL}

IP-Adresse des Absenders: {SENDER_IP}",
'tellafriend_subject' => "Produkt empfohlen von {SENDER_NAME}",
'fraud_subject' => "Bestellung {ORDER_ID} Sicherheitscheck nicht erfolgreich",
'fraud_body' => "Liebe/ Lieber {RECIP_NAME},

Wir müssen Ihnen leider mitteilen, dass der Sicherheitscheck unseres Shop-Teams oder der Bank für die Bezahlung Ihrer Bestellung {ORDER_ID} nicht erfolgreich war. Bei Fragen zu diesem Thema lesen Sie bitte die Bemerkungen zu Ihrer Bestellung über diesen Link oder setzen Sie sich unter Angabe Ihrer Bestell-Nr. mit unserem Shop-Team in Verbindung.

{ORDER_URL_PATH}

Typische Gründe können sein:
- Das ausgewählte Land ist nicht das Land, in dem die Karte ausgestellt wurde. Das ist ein relativ häufiges Problem.
- Der Sicherheitscode auf der Rückseite der Kreditkarte wurde nicht richtig eingegeben.
- Das Land, von dem aus die Bestellung erfolgte, ist nicht dasselbe Land, in dem die Karte ausgestellt wurde.

Sie dürfen gerne eine neue Bestellung aufgeben. Für die aktuelle Bestellung wurde kein Geld eingezogen.

Sie erhalten diese E-Mail von {STORE_URL}",


'payment_complete_subject' => "Bezahlung erhalten für {ORDER_ID}",
'payment_complete_body' => "Liebe/ Lieber {RECIP_NAME},

Wir möchten Sie nur kurz informieren, dass die Bezahlung für die Bestellung mit der Nr. {ORDER_ID} eingegangen ist und Sie Ihre Ware in Kürze erhalten werden.

Sie erhalten diese E-Mail von {STORE_URL}",


'payment_cancelled_subject' => "Bestellung {ORDER_ID} storniert",
'payment_cancelled_body' => "Liebe/ Lieber {RECIP_NAME},

Die Bestellung mit der Nr. {ORDER_ID} wurde storniert. Weitere Einzelheiten zu der Angelegenheit finden Sie u.U. in den Bemerkungen zur Bestellung unter dem folgenden Link:

{ORDER_URL_PATH}

Bitte beachten Sie, dass Bestellungen vom Kunden oder dem Shop-Team storniert werden können. Sie dürfen gerne eine neue Bestellung aufgeben.

Sie erhalten diese E-Mail von {STORE_URL}",
'admin_reset_pass_body' => "Dear {RECIP_NAME},

Sie oder jemand mit Ihren Zugriffsdaten hat das Zurücksetzen Ihres Passwortes beantragt.

Ihre neuen Zugriffsdaten sind:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Username: {USERNAME}
Passwort: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Sie erhalten diese E-Mail von {STORE_URL}\n

IP-Adresse des Antragstellers: {SENDER_IP}",
'admin_reset_pass_subject' => "Neue Admin-Zugangsdaten",
'new_review_subject' => "Neue Produktbewertung/-bemerkung",
'new_review_body' => "Name des Verfassers: {AUTHOR_NAME}
E-Mail des Verfassers: {AUTHOR_EMAIL}
IP-Adresse des Verfassers: {SENDER_ID}
Bewertetes Produkt: {PRODUCT_NAME}
Rating: {RATING}
Titel: {REVIEW_TITLE}
Bewertung Copy:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Akzeptieren: {APPROVE_URL}
Ablehnen: {DECLINE_URL}"
);
?>