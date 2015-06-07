<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "Uw cadeaubon!",
'coupon_body' => "Beste {RECIP_NAME},

{SENDER_NAME} heeft u een cadeaubon van {AMOUNT} verstuurd die ingewisseld kan worden tegen elk product in onze winkel! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Bericht: (van {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Bon code: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Waarom niet nu uitgeven?

Ga naar: {STORE_URL}",
'downloads_body' => "Beste {RECIP_NAME},

Bedankt voor uw order nr.: {ORDER_ID} geplaatst op {ORDER_DATE}

Hieronder staan de links naar uw digitale producten die je bestelde.

BELANGRIJK deze links vervallen op {EXPIRE_DATE} en je hebt nog {DOWNLOAD_ATTEMPTS} pogingen om deze te downloaden. Als er zich een probleem voordoet, contacteer ons dan en geef je ordernummer op.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
DOWNLOAD LINK:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Downloads Toegang: {ORDER_ID}",
'order_breakdown_1' => "Beste {RECIP_NAME},

Bedankt voor uw order nr.: {ORDER_ID} geplaatst op {ORDER_DATE}

De transactie werd uitgevoerd en uw goederen worden zo snel mogelijk opgestuurd (indien van toepassing).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Naam: {INVOICE_NAME}
Subtotaal: {SUBTOTAL}
Verzenden & Verpakken: {SHIPPING_COST}
{TAX_COST}
Eindtotaal: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Facturatieadres:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Leveradres:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Betalingswijze: {PAYMENT_METHOD}
Verzendwijze: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\nUw commentaren: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Order Inventaris:\n",
'order_breakdown_4' =>"Product: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Opties: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Hoeveelheid: {PRODUCT_QUANTITY}
Productcode: {PRODUCT_CODE}
Prijs: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Order Volledig #{ORDER_ID}",
'admin_pending_order_subject' => "Openstaand #{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, heeft onlangs een order geplaatst #{ORDER_ID}. Dit order wacht op betaling en wordt daarom niet uitgevoerd totdat de betaling volledig ontvangen is. Gelieve de onderstaande link te volgen om dit order te bekijken:

{ADMIN_ORDER_URL}

Opgeslagen IP Adres: {SENDER_ID}",
'order_acknowledgement_subject' => "Orderbevestiging #{ORDER_ID}",
'order_acknowledgement_body' => "Beste {CUSTOMER_NAME},

Deze email bevestigt dat je een nieuw order geplaatst hebt #{ORDER_ID}. Bij ontvangst van betaling worden de goederen zo snel mogelijk verzonden.

Je kan je orderstatus op elk moment bekijken via onze website door de onderstaande link te volgen: 

{ORDER_URL}

Als je problemen of vragen over je aankoop hebt, neem dan gerust contact op met ons personeel.",
'reset_password_body' => "Beste {RECIP_NAME},

Uw wachtwoord is nu gereset. Hieronder vind je de nieuwe toegangsgegevens:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Emailadres: {EMAIL}
Wachtwoord: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Om in te loggen, volg de onderstaande link:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

IP Adres aanvrager: {SENDER_IP}",
'reset_password_subject' => "Nieuw Wachtwoord",
'profile_mofified_body' => "Beste {CUSTOMER_NAME},

Deze email werd verzonden om te bevestigen dat uw persoonlijke informatie werd bijgewerkt. Als je denkt dat iemand anders je account bijwerkte, contacteer dan onmiddellijk een personeelslid.\n\n

Deze email werd verzonden van {STORE_URL}\n

IP Adres bezoeker: {SENDER_IP}",
'profile_mofified_subject' => "Persoonlijke informatie bijgewerkt",
'new_reg_subject' => "Uw Accountgegevens",
'new_reg_body' => "Beste {CUSTOMER_NAME},

De volgende account werd aangemaakt zodat u op onze site kan inloggen. E�nmaal ingelogd kan u de status van uw orders bekijken en kan u herhaalde orders plaatsen en uw profiel aanpassen.

Uw toegangsgegevens zijn:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Email: 		{EMAIL}
Wachtwoord: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Deze email werd verzonden van {STORE_URL}

Registratie IP Adres: {SENDER_IP}",
'tellafriend_body' => "Beste {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Om dit product te bekijken, klik op onderstaande link:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Deze email werd verzonden van {STORE_URL}

IP Adres afzender: {SENDER_IP}",
'tellafriend_subject' => "Product aangeraden door{SENDER_NAME}",
'fraud_subject' => "Order {ORDER_ID} slaagde niet voor de fraudecontrole",
'fraud_body' => "Beste {RECIP_NAME},

Het spijt ons u mee te delen dat de betaling voor uw order {ORDER_ID} niet door de veiligheidscontrole van ons personeel of bank raakte. Als u hier vragen over heeft, gaat u naar uw ordernota via onderstaande link, of contacteert u een personeelslid en geeft u uw ordernummer op.

{ORDER_URL_PATH}

Gebruikelijke redenen hiervoor:
- Het geselecteerde land stemde niet overeen met dat waar de kaart uitgegeven werd. Dit is een veelvoorkomende fout.
- De veiligheidscode die je op de achterkant van de kaart vindt kan verkeerd ingegeven zijn.
- Je hebt het order geplaatst in ander land dan het land waar je kaart werd uitgegeven.

Als je een nieuw order wil aanmaken gaat u gerust uw gang. Voor dit order werd geen geld van een kaart of rekening gehaald.

Deze email werd verzonden van {STORE_URL}",


'payment_complete_subject' => "Betaling ontvangen voor{ORDER_ID}",
'payment_complete_body' => "Beste {RECIP_NAME},

Wij laten je hierbij weten dat de betaling voor ordernummer {ORDER_ID} werd ontvangen en dat u binnenkort uw goederen ontvangt.

Deze email werd verzonden van {STORE_URL}",


'payment_cancelled_subject' => "Order {ORDER_ID} Geannuleerd",
'payment_cancelled_body' => "Beste {RECIP_NAME},

Ordernummer {ORDER_ID} werd geannuleerd. Meer informatie hierover vindt u in de ordernota van de volgende link:

{ORDER_URL_PATH}

N.B. Orders kunnen door de klant of een personeelslid worden geannuleerd tijdens betaling. Als u een nieuw order wil plaatsen, gaat u gerust uw gang.

Deze email werd verzonden van {STORE_URL}",
'admin_reset_pass_body' => "Beste {RECIP_NAME},

Jij, of iemand die zich voor jou uitgeeft heeft gevraagd om uw wachtwoord te resetten.

Je nieuwe toegangsgegevens zijn:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Gebruikersnaam: {USERNAME}
Wachtwoord: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Deze email werd verzonden van {STORE_URL}\n

IP Adres aanvrager: {SENDER_IP}",
'admin_reset_pass_subject' => "Nieuwe Admin toegangsgegevens",
'new_review_subject' => "Nieuwe Productbeoordeling/Commentaar",
'new_review_body' => "Name auteur: {AUTHOR_NAME}
Email Auteur: {AUTHOR_EMAIL}
IP Adres Auteur: {SENDER_ID}
Beoordeeld product: {PRODUCT_NAME}
Beoordeling: {RATING}
Titel beoordeling: {REVIEW_TITLE}
Kopie beoordeling:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Goedkeuren: {APPROVE_URL}
Weigeren: {DECLINE_URL}"
);
?>
