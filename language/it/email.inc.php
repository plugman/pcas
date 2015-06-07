<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['email'] = array(
'coupon_subject' => "Il tuo Certificato degli Omaggi!",
'coupon_body' => "Caro {RECIP_NAME},

{SENDER_NAME} ti ha inviato un buono omaggio dal valore di {AMOUNT} che pu� essere riscattato con merci del nosro magazzino! 

~~~~~~~~~~~~~~~~~~~~~~~~~~
Messaggio: (da {SENDER_NAME} <{SENDER_EMAIL}>)
{MESSAGE}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Codice del Buono: {COUPON}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Perch� lo spendi adesso?

Goto: {STORE_URL}",
'downloads_body' => "Caro {RECIP_NAME},

Grazie per il tuo ordine n�: {ORDER_ID} piazzato su {ORDER_DATE}

Sotto ci sono i links che tu hai bisogno per accedere ai prodotti digitali che hai ordinato.

IMPORTANTE questi links scadranno il {EXPIRE_DATE} e tu hai tentativi di download {DOWNLOAD_ATTEMPTS}. Se hai problemi prego contattarci specificando il numero del tuo ordine.

~~~~~~~~~~~~~~~~~~~~~~~~~~\n",
'downloads_body_2' => "{PRODUCT_NAME}
LINK di DOWNLOAD:
{DOWNLOAD_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n",
'downloads_subject' => "Accesso ai Downloads: {ORDER_ID}",
'order_breakdown_1' => "Caro {RECIP_NAME},

Grazie per il tuo ordine n�: {ORDER_ID} piazzato il{ORDER_DATE}

La transazione � riuscita e ti invieremo la merce alla prima occasione possibile (se pertinente).

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nome: {INVOICE_NAME}
Totale Parziale: {SUBTOTAL}
Spese postali & Imballaggio {SHIPPING_COST}
{TAX_COST}
Totale Generale: {GRAND_TOTAL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Indirizzo Fattura:
{INVOICE_NAME}
{INVOICE_ADD_1}
{INVOICE_ADD_2}
{INVOICE_CITY}
{INVOICE_REGION}
{INVOICE_POSTCODE}
{INVOICE_COUNTRY}

Indirizzo di Consegna:
{DELIVERY_NAME}
{DELIVERY_ADD_1}
{DELIVERY_ADD_2}
{DELIVERY_CITY}
{DELIVERY_REGION}
{DELIVERY_POSTCODE}
{DELIVERY_COUNTRY}

Modalit� di Pagamento: {PAYMENT_METHOD}
Modalit� di Spedizione: {DELIVERY_METHOD}\n",
'order_breakdown_2' => "\n Tuoi commenti: {CUSTOMER_COMMENTS}\n",
'order_breakdown_3' => "\n~~~~~~~~~~~~~~~~~~~~~~~~~~\n

Giacenze Ordine:\n",
'order_breakdown_4' =>"Prodotto: {PRODUCT_NAME}\n",
'order_breakdown_5' => "Opzioni: {PRODUCT_OPTIONS}\n",
'order_breakdown_6' => "Quantit�: {PRODUCT_QUANTITY}
Codice Prodotto: {PRODUCT_CODE}
Prezzo: {PRODUCT_PRICE}\n\n",

'order_breakdown_subject' => "Completamento Ordine #{ORDER_ID}",
'admin_pending_order_subject' => "Ordine da evadere n�{ORDER_ID}",
'admin_pending_order_body' => "{CUSTOMER_NAME}, ha recentemente piazzato l'ordine n�{ORDER_ID}. Questo ordine ha il pagamento in sospeso e come conseguenza lnon dovrebbere essere evaso finch� non riceviamo la somma a saldo  Prego seguire il link sotto per visionare questo ordine:

{ADMIN_ORDER_URL}

Loggato Indirizzo IP: {SENDER_ID}",
'order_acknowledgement_subject' => "Conferma d'Ordine n�{ORDER_ID}",
'order_acknowledgement_body' => "Caro {CUSTOMER_NAME},

Questa email conferma che tu hai piazzato con successo un nuovo ordine n�{ORDER_ID}. Una volta che il pagamento � stato ricevuto usciremo la merce alla prima occasione possibile.

Puoi visionare la condizione del tuo ordine in qualsiasi momento, tramite il nostro sito web seguendo il link sotto: 

{ORDER_URL}

Prego sentirti libero di contattare un nostro addetto se hai quesiti o problemi relativi al tuo acquisto.",
'reset_password_body' => "Caro {RECIP_NAME},

La tua password  � stata adesso ripristinata. Prego notare i dettagli del tuo nuovo accesso:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Indirizzo Email: {EMAIL}
Password: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~
Per fare login, prego seguire il link sotto:
{STORE_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Indirizzo IP del Richiedente: {SENDER_IP}",
'reset_password_subject' => "Nuova Password",
'profile_mofified_body' => "Caro/a {CUSTOMER_NAME},

Questa email � stata inviata per confermare che i tuoi dati personali sono stati aggiornati con successo. Se tu ritieni che il tuo account sia stato aggiornato da qualcun altro, prego contattare un nostro addetto immediatamente.\n\n

Questa email � stata inviata da {STORE_URL}\n

Indirizzo IP del Visitatore: {SENDER_IP}",
'profile_mofified_subject' => "Info Personale aggiornata",
'new_reg_subject' => "Dettagli del tuo Account",
'new_reg_body' => "Caro/a {CUSTOMER_NAME},

Per tua conoscenza, il seguente account � stato sistemato in modo che tu possa fare loggin sul nostro sito. Una volta fatto login puoi visionare la condizione dei tuoi ordini, fare ordini ripetitivi efficientemente ed emendare il tuo profilo.

I tuoi dettagli di accesso sono:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Email: 		{EMAIL}
Password: 	{PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Questa email � stata inviata da {STORE_URL}

Indirizzo IP di Registrazione: {SENDER_IP}",
'tellafriend_body' => "Caro/a {RECIP_NAME},

{MESSAGE}

~~~~~~~~~~~~~~~~~~~~~~~~~~
Per visionare questo prodotto, prego seguire il link sotto:
{PRODUCT_URL}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Questa email � stata inviata da {STORE_URL}

Indirizzo IP del Mittente: {SENDER_IP}",
'tellafriend_subject' => "Prodotto Raccomandato da {SENDER_NAME}",
'fraud_subject' => "Ordine {ORDER_ID} Fallito Riscontrata Frode",
'fraud_body' => "Caro/a {RECIP_NAME},

Ci rincresce informarti che il pagamento del tuo ordine {ORDER_ID} non ha superato i controlli di sicurezza eseguiti sia dal nostro personale sia dalla banca. Se hai ulteriori domande riguardanti l'argomento, prego fare riferimento alle note del tuo ordine seguendo il link sotto oppure contatta un nostro addetto citando il numero d'ordine.

{ORDER_URL_PATH}

Ragioni caratterische a proposito:
- Il Paese selezionato pu� darsi non abbia corrispondenza con quello dove � stata emessa la carta. Questo � un caso comune.
- Il codice di sicurezza che si trova sul retro della carta pu� darsi che sia stato immesso erratamente.
- Pu� darsi che tu stia comprando con l'ordine in un Paese diverso da quello dove la tua carta � stata emessa.

Se desideri formulare un nuovo ordine, prego sentirti libero di farlo. Nessuna carta o account � stato addebitato per questo ordine.

Questa email � stata inviata da {STORE_URL}",


'payment_complete_subject' => "Pagamento Ricevuto per {ORDER_ID}",
'payment_complete_body' => "Caro/a {RECIP_NAME},

Ci pregiamo informarti che il pagamento relativo all'ordine n� {ORDER_ID} � stato effettuato e tu dovresti ricevere la tua merce tra breve.

Questa email � stata inviata da {STORE_URL}",


'payment_cancelled_subject' => "Ordine {ORDER_ID} Annullato",
'payment_cancelled_body' => "Caro/a {RECIP_NAME},

L'ordine n� {ORDER_ID} � stato annullato. Ulteriori informazioni al riguardo si possono riscontrare tra le note dell'ordine trovabili tramite il seguente link.

{ORDER_URL_PATH}

N.B. Gli ordini possono essere annullati dal cliente in fase d'acquisto o da un ns. addetto.  Se desideri formulare un nuovo ordine, prego sentirti libero di farlo.

Questa email � stata inviata da {STORE_URL}",
'admin_reset_pass_body' => "Caro/a {RECIP_NAME},

Tu o qualcuno fingendosi tu, ha richiesto che la tua password fosse ripristinata.

I tuoi dettagli di nuovo accesso sono:

~~~~~~~~~~~~~~~~~~~~~~~~~~
Nome utente: {USERNAME}
Password: {PASSWORD}
~~~~~~~~~~~~~~~~~~~~~~~~~~

Questa email � stata inviata da {STORE_URL}\n

Indirizzo IP del Richiedente: {SENDER_IP}",
'admin_reset_pass_subject' => "Dettagli Nuovo Accesso Amministrazione",
'new_review_subject' => "Nuova Recensione/Commento Prodotto",
'new_review_body' => "Nome Autore: {AUTHOR_NAME}
Email Autore: {AUTHOR_EMAIL}
Indirizzo IP Autore: {SENDER_ID}
Prodotto Recensito: {PRODUCT_NAME}
Voto: {RATING}
Titolo Recensione: {REVIEW_TITLE}
Copia Recensione:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{REVIEW_COPY}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Approva: {APPROVE_URL}
Rifiuta: {DECLINE_URL}"
);
?>