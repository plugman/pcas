<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['admin'] = array(
'misc_trans_logs_emptied' => "Bezahltransaktionsprotokolle wurden gel&ouml;scht.",
'misc_trans_logs_not_emptied' => "Bezahltransaktionsprotokolle wurden nicht gel&ouml;scht.",
'misc_empty_translogs' => "Bezahltransaktionsprotokolle l&ouml;schen",
'misc_clear_sql_cache' => "Cache l&ouml;schen",
'misc_remove_copy_key' => "ImeiUnlock Copyright Entfernungscode beseitigen",
'misc_clear_search_cache' => "Suchgeschichte l&ouml;schen",
'misc_del_orphaned_thumbs' => "Verwaiste Thumbnails l&ouml;schen",
'misc_reset_prod_views' => "Anzahl Produktanzeigen zur&uuml;cksetzen",
'misc_rebuild_no_cust_orders' => "Z&auml;hlung Kundenbestellungen neu starten",
'misc_recalc_cat_prod_count' => "Z&auml;hlung Kategorie Produkt neu starten",
'misc_recalculate_upload_size' => "Ordnergr&ouml;&szlig;e zum Hochladen neu bestimmen",
'misc_operation' => "Optionen",
'misc_title_recount' => "Neu starten &amp; Z&auml;hlung wiederholen",
'misc_thumbs_folder_empty' => "Der Thumbs-Ordner ist leer.",
'misc_redundant_thumbs_gone' => "Alle &uuml;berfl&uuml;ssigen Thumbnails wurden entfernt.",
'misc_no_customers_exist' => "Datenbank enth&auml;lt keine Kunden.",
'misc_customers_orders_counted' => "Z&auml;hlung Kundenbestellungen erfolgreich wiederholt.",
'misc_search_terms_not_reset' => "Suchbegriffe konnten nicht zur&uuml;ckgesetzt werden.",
'misc_search_terms_reset' => "Suchbegriffe wurden zur&uuml;ckgesetzt.",
'misc_prod_views_not_reset' => "Produktanzeigen konnten nicht zur&uuml;ckgesetzt werden.",
'misc_prod_views_zero' => "Produktanzeigen auf Null zur&uuml;ckgesetzt.",
'misc_cat_count_no_prod' => "Keine Z&auml;hlung m&ouml;glich, da die Datenbank keine Produkte enth&auml;lt.",
'misc_cat_count_success' => "Z&auml;hlung Kategorie erfolgreich wiederholt.",
'misc_cache_cleared' => "Cache erfolgreich geleert.",
'misc_client_browser' => "Client-Browser:",
'misc_server_software' => "Server-Software:",
'misc_license_form' => "Lizenzformular",
'misc_write_error' => "includes/global.inc.php konnte nicht zum Schreiben ge&ouml;ffnet werden. Versuchen Sie, den CHMOD-Wert auf 0777 zu setzen. Vergessen Sie nicht, ihn danach wieder auf 0644 zur&uuml;ckzusetzen!",
'misc_try_again' => "Erneut versuchen",
'misc_purchase_cubecart' => "ImeiUnlock Copyright Entfernung:",
'misc_invalid_key' => "Der Linzenzcode ist entweder ung&uuml;ltig oder wurde bereits verwendet.",
'misc_purchase_license_key' => "Copyright-Entfernungscode erwerben",
'misc_run_unlicensed' => "Bitte geben Sie Ihren Code unten ein, um unsere <a href='http://www.cubecart.com/copyright-removal-key' target='_blank' class='txtLink'>Copyright-Hinweise</a> zu entfernen.",
'misc_license_key' => "Copyright-Entfernungscode:",
'misc_submit_key' => "Code abschicken",
'misc_server_info' => "Server-Info",
'misc_ini_set_desc' => "Die folgenden Informationen zeigen Ihre aktuellen Serverumgebungseinstellungen. Dazu geh&ouml;ren eine Reihe von Angaben, die evtl. ge&auml;ndert werden m&uuml;ssen, wenn Probleme mit ImeiUnlock auftreten. Bitte beachten: wenn Sie einen Server teilen oder &uuml;ber virtuelles Hosting verf&uuml;gen, haben Sie voraussichtlich nur begrenzten Zugang zur &Auml;nderung von Einstellungen . H&auml;ufig kann die Funktion</span> <a href='http://www.php.net/ini_set' target='_blank' class='txtLink'>ini_set()</a> <span class='copyText'> verwendet werden, um diese Einstellungen aufzuheben.",
'misc_module_name' => "Modulname",
'misc_module_action' => "Vorgang",
'misc_module_status' => "Status",
'misc_clear_sessions' => 'Verwaltungssitzungen l&ouml;schen',
'misc_clear_sessions_empty' => 'Verwaltungssitzungen wurden gel&ouml;scht.',
'misc_clear_logs' => 'Verwaltungsprotokolle l&ouml;schen',
'misc_clear_logs_empty' => 'Verwaltungsprotokolle wurden gel&ouml;scht.',
'misc_bkup_check_one' => 'Please check at least one of the checkboxes to take a backup.',
'misc_bkup_required_dep' => 'If you select "Include Drop Table" then you must also check "Include Structure"',
'misc_bkup_title' => 'Backup Tool',
'misc_bkup_inc_drop' => '<strong>Include Drop Table?</strong><br />Check this if you want your backup to overwrite existing data if used to restore.',
'misc_bkup_inc_structure' => '<strong>Include Structure?</strong><br />This is critical for creating the database structure for the data to be imported into.',
'misc_bkup_inc_data' => '<strong>Include Data?</strong><br />This includes all your store inventory including products, customers etc which is imported into the database core structure.',
'misc_download_now' => 'Download Now',
'misc_db_success' => "Action `%1\$s TABLE` has been performed successfully.",
'misc_db_none_selected' => 'Please check the tables you wish to perform maintenance on.',
'misc_db_maintenance' => 'Database `%1\$s` Maintenance',
'misc_db_info' => '<strong>MySQL %1\$s</strong> running on <strong>%2\$s</strong> as <strong>%3\$s@%4\$s</strong>',
'misc_db_table' => 'Tabelle',
'misc_db_operation' => 'Operation',
'misc_db_msg_type' => 'Message Typ',
'misc_db_msg_text' => 'Message Text',
'misc_db_check_all' => 'Alle auswählen',
'misc_db_uncheck_all' => 'Auswahl entfernen',
'misc_db_with_sel' => 'Mit gewählt:',
'misc_db_optimise' => 'Optimiere Tabelle',
'misc_db_check' => '&Uuml;berpr&uuml;fe Tabelle',
'misc_db_repair' => 'Repariere Tabelle',
'misc_db_analyze' => 'Analysiere Tabelle',
'misc_db_records' => 'Eintr&auml;ge',
'misc_db_type' => 'Typ',
'misc_db_size' => 'Gr&ouml;&szlig;e',
'misc_db_overhead' => '&Uuml;berhang',
'misc_img_db_success' => 'Update Image Database'
);
?>