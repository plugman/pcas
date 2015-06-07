<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "Ditt gavekort!",
'coupon_body' => "Kj�re {RECIP_NAME},

{SENDER_NAME} har sendt deg et gavekort med en verdi p� {AMOUNT} som kan l�ses inn i varer fra v�r butikk! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Beskjed: (fra {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Gavekortkode: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Hvorfor ikke bruke gavekortet n�?

G� til: {STORE_URL}",
'downloads_body' => "Kj�re {RECIP_NAME},

Takk for din ordre, ordrenr. {ORDER_ID}, innsendt {ORDER_DATE}

Under finner du linkene du beh�ver for � f� tilgang til de digitale produktene du har bestilt.


VIKTIG! Disse linkene vil slutte � fungere den {EXPIRE_DATE} og du har {DOWNLOAD_ATTEMPTS} fors�k p� � laste dem ned. Om du st�ter p� problemer, kontakt oss med ditt ordrenummer.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
Nedlastningslink:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Nedlastningsinformasjon: {ORDER_ID}",
'order_breakdown_1' => "Kj�re {RECIP_NAME},

Takk for din ordre, ordrenr. {ORDER_ID}, innsendt {ORDER_DATE}

Transaksjonen gikk i gjennom, og vil vil sende deg dine varer s� snart som mulig.

~~~~~~~~~~~~~~~~~~~~~~~~~~
Navn: {INVOICE_NAME}
Subtotal: {SUBTOTAL}
Post- og ekspedisjonsavgifter: {SHIPPING_COST}
{TAX_COST}
Totalsum: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Fakturaadresse:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Leveringsadresse:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Betalingsmetode: {PAYMENT_METHOD}
Forsendelsesmetode: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\nDine kommentarer: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Ordredetaljer:\n",
'order_breakdown_4' =>"Produkt: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Valg: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Antall: {PRODUCT_QUANTITY}
Produktkode: {PRODUCT_CODE}
Pris: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Bestilling fullf�rt, ordreid. #{ORDER_ID}",
'admin_pending_order_subject' => "Ventende ordre, ordreid. #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, har nylig sendt inn en ordre, ordreid. #{ORDER_ID}. Denne ordren venter betaling, og m� ikke fullf�res f�r betalingen er mottatt. Vennligst f�lg linken under for � se detaljer om ordren:

{ADMIN_ORDER_URL}

Logget IP-adresse: {SENDER_ID}",
'order_acknowledgement_subject' => "Ordrebekreftelse, ordreid. #{ORDER_ID}",
'order_acknowledgement_body' => "Kj�re {CUSTOMER_NAME},

Denne eposten bekrefter at du har plassert en ny ordre, ordrenr. #{ORDER_ID}, i v�r webbutikk. S� snart vi har mottatt betaling vil vi sende deg dine varer.

Du kan se status p� ordren til enhver tid ved � g� inn p� v�r webside:

{ORDER_URL}

Om noe er uklart eller om du har sp�rsm�l vedr�rende ordren, ta gjerne kontakt med oss!",
'reset_password_body' => "Kj�re {RECIP_NAME},

Ditt passord har n� blitt nullstilt. Under vil du finne dine nye tilgangsdetaljer:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Epostadresse: {EMAIL}
Passord: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
For � logge inn, vennligst benytt linken under:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Endring forespurt av IP-adresse: {SENDER_IP}",
'reset_password_subject' => "Nytt passord_",
'profile_mofified_body' => "Kj�re {CUSTOMER_NAME},

Denne eposten har blitt sendt for � bekrefte at din personlige innformasjon har blitt oppdatert. Om du mener at noen andre enn deg selv har oppdatert denne informasjonen, vennligst ta kontakt med oss umiddelbart.\n\n

Denne eposten ble sendt fra {STORE_URL}\n

Bes�kendes IP-adresse: {SENDER_IP}",
'profile_mofified_subject' => "Personlig informasjon oppdatering",
'new_reg_subject' => "Dine kontodetaljer",
'new_reg_body' => "Kj�re {CUSTOMER_NAME},

F�lgende konto har blitt satt opp slik at du kan logge inn p� v�r side. N�r du har logget inn kan du se status p� dine ordre, gj�re nye ordrer raskere samt endre din profil.

Dine tilgangsdetaljer er:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Epost: 		{EMAIL}
Passord: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Denne eposten ble sendt fra {STORE_URL}

Registrarens IP-adresse: {SENDER_IP}",
'tellafriend_body' => "Kj�re {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
For � se dette produktet, vennligst g� til:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Denne meldingen ble sendt fra {STORE_URL}

Senders IP-adresse: {SENDER_IP}",
'tellafriend_subject' => "Produkt anbefalt av {SENDER_NAME}",
'fraud_subject' => "Ordrenr. {ORDER_ID} ikke godkjent",
'fraud_body' => "Kj�re {RECIP_NAME},

Vi beklager � m�tte informere om at betalingen for din ordre, ordrenr. {ORDER_ID}, ikke gikk gjennom v�re sikkerhetsjekk utf�rt enten av oss eller v�r bank. Om du sp�rsm�l ang�ende dette, vennligst kontakt oss og oppgi ditt ordrenr. Dette vil du finne om du f�lger linken under.

{ORDER_URL_PATH}

Vanlige grunner til at dette har skjedd:
-Landet du har valgt samsvarer ikke med landet hvor ditt bank-/kredittkort ble utstedet.
-Sikkerhetskoden (CVV2) som du kan finne bak p� kortet kan ha blitt feil inntastet.
-Du fors�ker � bestille i et land annet enn landet hvor ditt bank-/kredittkort ble utstedet.

Om du �nsker kan du sende inn en ny ordre. Ingen kort eller kontoer har blitt trukket for denne ordren.


Denne eposten ble sendt fra {STORE_URL}",


'payment_complete_subject' => "Betaling mottatt for ordre, ordreid. {ORDER_ID}",
'payment_complete_body' => "Kj�re {RECIP_NAME},

Vi vil informere deg om at vi har mottatt betaling for din ordre, ordrenr. {ORDER_ID}. Du vil snart motta dine varer.

Denne eposten ble sendt fra {STORE_URL}",


'payment_cancelled_subject' => "Ordrenr. {ORDER_ID} kansellert",
'payment_cancelled_body' => "Kj�re {RECIP_NAME},

Ordrenr. {ORDER_ID} har blitt kansellert. For mer informasjon om dette se ordredetaljene for ordren ved � f�lge f�lgende link:

{ORDER_URL_PATH}

NB! Ordrer kan bli kansellert av en kunde under et kj�p eller av oss. Om du �nsker, kan du sende inn en ny ordre.

Denne eposten ble sendt fra {STORE_URL}",
'admin_reset_pass_body' => "Kj�re {RECIP_NAME},

Du, eller noen som har utgitt seg for � v�re deg, har etterspurt en nullstilling av ditt passord.

Dine nye tilgangsdetaljer er:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Brukernavn: {USERNAME}
Passord: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Denne eposten ble sendt fra {STORE_URL}\n

Ettersp�rrers IP-adresse: {SENDER_IP}",
'admin_reset_pass_subject' => "Nye tilgangsdetaljer for administrator",
'new_review_subject' => "Ny produktanmeldelse/kommentar",
'new_review_body' => "Forfatterens navn: {AUTHOR_NAME}
Forfatterens epostadresse: {AUTHOR_EMAIL}
Forfatterens IP-adresse: {SENDER_ID}
Produkt som har blitt anmeldt/kommentert: {PRODUCT_NAME}
Antall stjerner: {RATING}
Anmeldelsestittel: {REVIEW_TITLE}
Kopi av anmeldelse/kommentar:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Godta: {APPROVE_URL}
Avvis: {DECLINE_URL}"
);
?>