<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['glob'] = array(
'orderState_1' => "Open (Nieuw Order)",
'orderState_2' => "Wordt verwerkt (Zie ordernota)",
'orderState_3' => "Order Volledig & Verwerkt",
'orderState_4' => "Geweigerd (Zie ordernota)",
'orderState_5' => "Fraudecontrole mislukt",
'orderState_6' => "Geannuleerd",
'orderState_1_desc' => "Order werd gecre&euml;erd en de betaling wordt afgewacht voor verdere actie wordt ondernomen. Dit order wordt automatisch geannuleerd als de betaling niet binnen een bepaalde tijd wordt uitgevoerd.",
'orderState_2_desc' => "Betaling werd al dan niet gedaan of het order werd nog niet verwerkt.",
'orderState_3_desc' => "Order werd betaald en verstuurd. Goederen zullen dadelijk aankomen. Traceerinformatie is mogelijk beschikbaar.",
'orderState_4_desc' => "Order werd geweigerd. Meer informatie is beschikbaar in de ordernota.",
'orderState_5_desc' => "Betaling van het order raakte niet door de externe/interne fraudecontrole.",
'orderState_6_desc' => "Order werd geannuleerd. Redenen voor de annulatie worden in uw ordernota weergegeven. Opmerking: orders die binnen een bepaalde periode niet betaald werden, worden automatisch geannuleerd."
);
?>