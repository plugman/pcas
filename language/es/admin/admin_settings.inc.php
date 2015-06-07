<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['admin'] = array(
'settings_force_ssl_desc' => "Con esto habilitado todas las p&aacute;ginas se proveer&aacute;n a trav&eacute;s de SSL en vez de s&oacute;lo las requeridas.",
'settings_zero_disabled' => "(Ingresando '0' deshabilita esta opci&oacute;n)",
'settings_order_expire' => "Cancelar de forma autom&aacute;tica Nuevos Pedidos (Pendientes) despu&eacute;s de cierto tiempo:",
'settings_stock_replace_time' => "&iquest;Aumentar el nivel del stock una vez si el estado del pedido se cambia a alguno de los siguientes?",
'settings_ftp_seo_title' => "Generando archivos PHP con FTP",
'settings_ftp_complete' => "La escritura de los archivos FTP se ha completado.",
'settings_writing_docs' => "Escribiendo documents&hellip; del sitio",
'settings_writing_cats' => "Escribiendo pages&hellip; de categor&iacute;as",
'settings_writing_taf' => "Escribiendo pages&hellip; de av&iacute;sele a un amigo",
'settings_seo_htaccess' => "Para utilizar <em>&quot;Apache Rewrite&quot;</em> o <em>&quot;Apache Directory 'Loopback' And ForceType&quot;</em> se debe crear un archivo <em>&quot;.htaccess&quot;</em> en el directorio ra&iacute;z de su tienda. Para hacerlo, por favor abra un editor de texto como Notepad o TextEdit, copie y pegue los contenidos del &aacute;rea de texto de en frente, y gu&aacute;rdelo como <em>&quot;htaccess.txt&quot;</em>. Cargue este archivo a su servidor y c&aacute;mbiele el nombre a <em>&quot;.htaccess&quot;</em>.</p>
<p>Se se muestra un error de servidor, por favor borre el archivo .htaccess y utilice <em>&quot;Use FTP Generated FTP Pages&quot;</em> o <em>&quot;Apache Directory 'Loopback'&quot;</em>.",
'settings_ftp_created' => "%1\$s creado",
'settings_ftp_writeing' => "Escribiendo pages&hellip; de producto",
'settings_ftp_conn_fail' => "No puede conectarse a su servidor de FTP. Compruebe que sus opciones de FTP est&eacute;n correctas.",
'settings_ftp_server' => "Servidor FTP:",
'settings_ftp_user' => "Usuario de FTP:",
'settings_ftp_pass' => "Contrase&ntilde;a de FTP:",
'settings_ftp_dir' => "Directorio Ra&iacute;z de FTP:",
'settings_seo_cat_cat_prod' => "categor&iacute;a->sub categor&iacute;a->nombre de producto",
'settings_seo_prod_name_cat_cat' => "nombre de producto->sub categor&iacute;a->categor&iacute;a",
'settings_meta_browser_cat_and_prod' => "(Nombres de Categor&iacute;a y de producto)",
'settings_meta_browser_title_format' => "Buscar formato de t&iacute;tulo:",
'settings_meta_disabled' => "Deshabilitar esta opci&oacute;n y utilizar datos meta globales",
'settings_meta_combined' => "Combinar con datos meta globales (Recomendado)",
'settings_meta_or_glob_desc_key' => "Invalidar datos meta globales s&oacute;lo con descripci&oacute;n y palabras clave",
'settings_meta_behaviour_desc' => "Con esto los datos meta habilitados (o etiquetas meta) se pueden especificar de forma manual para cada documento, categor&iacute;a y producto del sitio.",
'settings_meta_behaviour' => "Comportamiento Meta Data:",
'settings_seo_generate_pages_inst' => "<a href='%1\$s' class='txtLink'>Generar las p&aacute;ginas PHP ahora</a>. Esto puede demorar un rato, se mostrar&aacute; un mensaje cuando se complete. Por favor aseg&uacute;rese de que hubiera completado su informaci&oacute;n de acceso FTP en este p&aacute;gina. Note que esto crear&aacute; directorios y archivos de permiso 755 (en equipos Linux/Unix) en so carpeta ImeiUnlock. ",
'settings_seo_generate_pages_desc' => "Para usar las p&aacute;ginas PHP generadas por FTP tendr&aacute; que regenerarlas cada vez que a&ntilde;ada nuevos documentos/categor&iacute;as/productos o modifique cualquiera de sus t&iacute;tulos. Si no ve estos archivos y los datos de la tienda actuales estar&aacute;n sin sincronizar.",
'settings_seo_generate_pages' => "Generar P&aacute;ginas:",
'settings_seo_method_mod_rewrite' => "Se soporta Apache RewriteRule (Recomendado)",
'settings_seo_method_lookback_force' => "Se soportan 'lookback' directorio Apache y ForceType",
'settings_seo_method_lookback' => "S&oacute;lo se soporta 'lookback' directorio Apache",
'settings_seo_method_ftp' => "Utilizar p&aacute;ginas PHP generadas por FTP (si todos los otros m&eacute;todos fallan)",
'settings_seo_method' => "El m&eacute;todo que elija depende del tipo de servidor ImeiUnlock que est&aacute; corriendo y su configuraci&oacute;n. Si nada de esto funciona, por favor visite <a href='https://support.cubecart.com' target='_blank'  class='txtLink'>Ayuda T&eacute;cnica</a>.",
'settings_url_method' => "M&eacute;todo de Construcci&oacute;n URL:",
'settings_seo' => "Optimizaci&oacute;n del Motor de B&uacute;squeda",
'settings_use_seo' => "&iquest;Utilizar URL amigable al motor de b&uacute;squeda?",
'settings_proxy_host' => "Anfitri&oacute;n Proxy:",
'settings_proxy_port' => "Puerto Proxy:",
'settings_use_proxy' => "&iquest;El servidor est&aacute; detr&aacute;s de un proxy?",
'settings_proxy' => "Servidor Proxy",
'use_cache' => "Utilizar SQL Query Caching (&iexcl;Recomendado!)",
'show_empty_cat' => '&iquest;Mostrar Categor&iacute;as Vac&iacute;as?',
'disable_alert_email' => '&iquest;Deshabilitar alertas de nuevos pedidos?',
'settings_debug' => "&iquest;Habilitar resultado depurado?",
'settings_debug_desc' => "(Informaci&oacute;n de depuraci&oacute;n de resultados en la p&aacute;gina. &Uacute;til para el desarrollo.)",
'pop_products_source' => "Fuente para datos de productos conocidos:",
'pop_products_views' => "Cantidad de Vistas",
'pop_products_sales' => "Cantidad de Ventas",
'settings_autoupdate' => "Actualizaci&oacute;n Autom&aacute;tica",
'settings_top' => "Arriba",
'settings_jump_to' => "Saltar a:",
'settings_stock_warn_type' => "M&eacute;todo de advertencia de stock:",
'settings_stock_warn_level' => "Advertencia de nivel de stock global:",
'settings_stock_global_warn' => "Global (Utiliza el valor de abajo)",
'settings_stock_product_warn' => "Por Producto (Establecer cuando a&ntilde;ade/edita producto)",
'settings_stock_warn_level_desc' => "(Cuando el nivel del stock est&aacute; por debajo de esta cantidad se mostrar&aacute; un mensaje de advertencia en la p&aacute;gina principal del admin.)",
'settings_rte_height' => "Alto:",
'settings_ref_only' => "Este cuadro de texto es s&oacute;lo para referencia. Para cambiar el valor, por favor edite includes/global.inc.php",
'settings_ob_gzhandler' => "&iquest;Habilitar tope de salida gzip?",
'settings_ob_gzhandler_desc' => "(Esto puede reducir dram&aacute;ticamente el tama&ntilde;o de salida de la p&aacute;gina y por lo tanto el ancho de banda)",
'settings_latestNewsRSS' => "URL a la Fuente RSS/XML de las &Uacute;ltimas Noticias:",
'settings_excl_tax' => "(excl. impuesto)",
'settings_tax_details_upd_success' => "El registro(s) de Detalle de Impuestos se actualiz&oacute; con &eacute;xito",
'settings_tax_details_upd_failure' => "No es necesario actualizar registros de Detalles de Impuestos",
'settings_tax_details_upd_error' => "No se pudo actualizar el registro(s) de Detalles de Impuestos",
'settings_tax_details_del_success' => "Se elimin&oacute; el Detalle de Impuestos con &eacute;xito",
'settings_tax_details_del_failure' => "No se elimin&oacute; el Detalle de Impuestos",
'settings_tax_rates_add_success' => "Se a&ntilde;adi&oacute; la Tasa/Zona Impositiva con &eacute;xito",
'settings_tax_rates_add_failure' => "No se pudo a&ntilde;adir la Tasa/Zona Impositiva � La tasa ya est&aacute; definida para esa combinaci&oacute;n de impuesto, clase, zona",
'settings_tax_rates_upd_success' => "El registro(s) de Tasa/Zona Impositiva se actualiz&oacute; con &eacute;xito",
'settings_tax_rates_upd_failure' => "No es necesario actualizar registros de Tasa/Zona Impositiva",
'settings_tax_rates_upd_error' => "No se pudo actualizar el registro(s) la Tasa/Zona Impositiva � La tasa ya est&aacute; definida para esa combinaci&oacute;n de impuesto, clase, zona",
'settings_tax_rates_del_success' => "Se elimin&oacute; la Tasa/Zona Impositiva con &eacute;xito",
'settings_tax_rates_del_failure' => "No se elimin&oacute; la Tasa/Zona Impositiva",
'settings_multi_tax_config' => "Impuestos Flexibles",
'settings_multi_tax_info' => "La funcionalidad de Impuestos Flexibles le permitir&aacute; cargar impuestos en varios pa&iacute;ses o cargar varios impuestos (ej. GST y PST Canadiense), o cargar tasas impositivas distintas en base al pa&iacute;s y condado/estado/zona.",
'settings_status_help' => "Para utilizar la funcionalidad de Impuestos Flexibles tendr&aacute; que tener el estado en &quot;Habilitado&quot;.",
'settings_mode_help' => "Puede que sea aconsejable comenzar en el modo &quot;Probando Configuraci&oacute;n&quot; hasta que hubiera confirmado que sus Impuestos Flexibles est&aacute;n configurados correctamente.",
'settings_status' => "Estado:",
'settings_enabled' => "Habilitado",
'settings_disabled' => "Deshabilitado",
'settings_mode' => "Modo:",
'settings_live' => "Tienda al Vivo",
'settings_testing' => "Prueba de Configuraci&oacute;n",
'settings_update' => "Actualizar",
'settings_cancel' => "Cancelar",
'settings_update_all' => "Actualizar Todo",
'settings_popup' => "Mostrar/Editar Tasas Impositivas",
'settings_tax_classes' => "Clases de Impuesto",
'settings_tax_details' => "Detalles de Impuesto",
'settings_add_edit_delete' => "A&ntilde;adir/Editar/Borrar",
'settings_show_help' => "[?] Mostrar Ayuda",
'settings_tax_warn_testing' => "NOTA: Con el modo \"Testing Configuration\", se mostrar&aacute; informaci&oacute;n impositiva detallade durante la compra en el Paso 4 (Selecci&oacute;n de Env&iacute;o) para ayudarle a comprobar que sus impuestos se configuraron correctamente. No olvide cambiar al modo \"Live Store\" antes de abrir su tienda a los clientes.",
'settings_tax_details_help' => "<strong>Nombre:</strong> Un nombre para su referencia. Cada fila debe tener un nombre &uacute;nico. Ejemplo: GST<br/><br/>
	<strong>Mostrar Como:</strong> &Eacute;ste es el nombre del impuesto como quiere que se muestre en las p&aacute;ginas de compra y en los recibos. Ejemplo: Impuesto (GST)<br/><br/>
	<strong>Mostrar N&uacute;mero de Reg.:</strong> (Opcional) Si desea que se muestre su n&uacute;mero(s) de registro tributario en sus recibos, entonces ingr&eacute;selo exactamente como desea que se muestre. Ejemplo: GST # 123-456-789<br/><br/>
	<strong>Estado:</strong> Puede utilizar el estado para deshabilitar alg&uacute;n impuesto en particular.",
'settings_name' => "Nombre: (debe ser &uacute;nico)",
'settings_display_as' => "Mostrar Como:",
'settings_reg_number' => "Mostrar N&uacute;mero de Reg.:",
'settings_deleted' => "(eliminado)",
'settings_filter_by_country' => "Filtrar la Muestra por Pa&iacute;s",
'settings_tax_rates' => "Tasas/Zonas Impositivas",
'settings_tax_rates_help_class' => "Cada producto y m&eacute;todo de env&iacute;o ser&aacute; asociado con una sola clase de impuesto. Por lo general, todos los productos y m&eacute;todos de env&iacute;o estar&aacute;n asociados con la clase \"Impuesto Est&aacute;ndar\" y usted deber&iacute;a configurar todas sus tasas/zonas impositivas con esta clase de impuesto.",
'settings_tax_rates_help_country' => "",
'settings_tax_rates_help_tax' => "Esto se refiere a una de las configuraciones de Detalles de Impuestos en la p&aacute;gina anterior. Ejemplo: GST",
'settings_tax_rates_help_rate' => "",
'settings_tax_rates_help_apply' => "&iquest;El impuesto deber&iacute;a aplicarse a bienes (ST), env&iacute;o (S&amp;H), o ambos (ST &amp; S&amp;H)?",
'settings_tax_rates_help_active' => "Utilice esta opci&oacute;n si desea activar/desactivar alguna tasa/zona impositiva en particular. Ej. Puede que las tiendas canadienses tengan que activar/desactivar el PST en ciertas provincias.",
'settings_please_setup_tax_details' => "NOTA: Por favor configure los Detalles de Impuestos primero",
'settings_class' => "Clase:",
'settings_state' => "Condado/Estado/Zona:",
'settings_rate' => "Tasa (%):",
'settings_apply_to' => "Aplicar A:",
'settings_active' => "Activar:",
'settings_goods_and_shipping' => "ST + S&amp;H",
'settings_goods_only' => "ST",
'settings_shipping_only' => "S&amp;H",
'settings_update_all_rates' => "(Para actualizar todas las tasas y las configuraciones activas/inactivas)",
'settings_richTextEditor' => "&iquest;Apagar los editores de texto enriquecido?",
'settings_floodControl' => "&iquest;Habilitar Control de Inundaci&oacute;n de Script/Bot?",
'settings_floodControlDesc' => "Obliga a los usuarios a ingresar un c&oacute;digo durante el registro, cuando le avisa a un amigo etc para evitar que bots llenen de spam la tienda.",
'settings_smtpHost' => "Anfitri&oacute;n SMTP:",
'settings_defaultHost' => "(Predeterminado: anfitri&oacute;n local)",
'settings_smtpPort' => "Puerto SMTP:",
'settings_defaultPort' => "(Predeterminado: 25)",
'settings_smtpAuth' => "&iquest;Utilizar Autenticaci&oacute;n?",
'settings_defaultAuth' => "(Predeterminado: No)",
'settings_smtpUsername' => "Nombre de usuario SMTP:",
'settings_smtpPassword' => "Contrase&ntilde;a SMTP:",
'settings_show_latest' => "&iquest;Mostrar los &Uacute;ltimos Productos en la p&aacute;gina principal?",
'settings_no_latest' => "Cantidad de &uacute;ltimos productos a mostrar:",
'settings_off_line_settings' => "Configuraciones de Desconexi&oacute;n",
'settings_sef' => "Optimizaci&oacute;n del Motor de B&uacute;squeda",
'settings_off_line' => "&iquest;Apagar la tienda?",
'settings_off_line_content' => "Mensaje de Desconexi&oacute;n:",
'settings_off_line_allow_admin' => "&iquest;Permitir a los administradores ver la tienda desconectada? (Requiere sesi&oacute;n de admin)",
'settings_store_settings' => "Configuraci&oacute;n de la Tienda",
'settings_edit_below' => "Por favor edite la configuraci&oacute;n de su tienda abajo:",
'settings_meta_data' => "Datos Meta",
'settings_browser_title' => "T&iacute;tulo Global de Navegador:",
'settings_meta_desc' => "Descripci&oacute;n Meta Global:",
'settings_meta_keywords' => "Palabras Clave Meta Globales:",
'settings_comma_separated' => "(Separado por Coma)",
'settings_store_co_name' => "Nombre de Tienda/Compa&ntilde;&iacute;a:",
'settings_store_address' => "Direcci&oacute;n de la Tienda:",
'settings_country' => "Pa&iacute;s:",
'settings_zone' => "Condado/Estado/Zona:",
'settings_dirs_folders' => "Directorios &amp; Carpetas",
'settings_rootRel' => "Ruta en Relaci&oacute;n a la Ra&iacute;z HTTP:",
'settings_storeURL' => "URL Absoluto de HTTP:",
'settings_eg_domain_com' => "ej. http://www.example.com/store",
'settings_rootDir' => "HTTP Root Path:",
'settings_eg_root_path' => "ej. /ruta/a/su/tiendaweb",
'settings_rootRel_SSL' => "Ruta en Relaci&oacute;n a la Ra&iacute;z HTTP<span style='color: red;'>S</span>:",
'settings_eg_rootRel' => "ej. /tienda/",
'settings_storeURL_SSL' => "URL Absoluto de HTTP<span style='color: red;'>S</span>:",
'settings_eg_domain_SSL' => "ej. https://seguro.dominio.com/tienda",
'settings_rootDir_SSL' => "Ruta Ra&iacute;z HTTP<span style='color: red;'>S</span>:",
'settings_eg_root_path_secure' => "ej. /ruta/a/su/tiendaweb/segura",
'settings_enable_ssl' => "Habilitar SSL:",
'settings_force_ssl' => "Forzar SSL:",
'settings_ssl_warn' => "<a href='https://www.cubecart.com/site/helpdesk/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=24&nav=0,2,4' class='txtLink' target='_blank'>&iexcl;Ayuda!</a>",
'settings_digital_downloads' => "Descargas Digitales",
'settings_download_expire_time' => "Descargar Tiempo de Expiraci&oacute;n:",
'settings_seconds' => "(Segundos)",
'settings_download_attempts' => "Intentos de Descarga:",
'settings_attempts_desc' => "(Cantidad de veces que el cliente puede descargar el producto.)",
'settings_styles_misc' => "Estilos &amp; Misc",
'settings_default_language' => "Idioma Predeterminado:",
'settings_store_skin' => "Dise&ntilde;o de la Tienda:",
'settings_changeskin' => "&iquest;Permitir a los usuarios cambiar el dise&ntilde;o?",
'settings_no_cats_per_row' => "No hay Categor&iacute;as Por Fila:",
'settings_dir_symbol' => "S&iacute;mbolo de Directorio:",
'settings_prods_per_page' => "No hay Productos por P&aacute;gina:",
'settings_precis_length' => "Largo del precis de producto:",
'settings_chars' => "(Caracteres)",
'settings_no_sale_items' => "Cant. de &Iacute;tems en el Cuadro de &Iacute;tems de Venta:",
'settings_no_pop_prod' => "Cant. de &Iacute;tems en el Cuadro de &Iacute;tems Conocidos:",
'settings_email_name' => "Nombre de Email:",
'settings_email_name_desc' => "(Esto se utiliza como el nombre de env&iacute;o de los emails del sitio.)",
'settings_email_address' => "Direcci&oacute;n de Email:",
'settings_email_address_desc' => "(Esto se utiliza como la direcci&oacute;n de email de los emails del sitio.)",
'settings_mail_method' => "M&eacute;todo de Env&iacute;o de Correo:",
'settings_mail_recommended' => "(Se recomienda SMTP)",
'settings_max_upload_size' => "Tama&ntilde;o M&aacute;ximo de Archivo para Carga:",
'settings_under_x_recom' => "(Se Recomienda Menos de 2048Kb)",
'settings_max_sess_length' => "Duraci&oacute;n M&aacute;xima de la Sesi&oacute;n:",
'settings_db_settings' => "Configuraciones de Bases de Datos",
'settings_db_host' => "Nombre de Anfitri&oacute;n de la Base de Datos:",
'settings_db_username' => "Nombre de usuario de la Base de datos:",
'settings_db_password' => "Contrase&ntilde;a de la Base de Datos:",
'settings_db_name' => "Nombre de la Base de Datos:",
'settings_db_prefix' => "Prefijo de la Base de datos:",
'settings_gd_settings' => "Configuraciones GD",
'settings_gd_ver' => "Versi&oacute;n de GD:",
'settings_gd_thumb_size' => "Tama&ntilde;o de Imagen en Miniatura:",
'settings_gd_gif_support' => "Permitir Soporte GIF: (Por favor aseg&uacute;rese de que esto est&eacute; habilitado en su servidor)",
'settings_gd_max_img_size' => "Tama&ntilde;o M&aacute;ximo de Imagen:",
'settings_gd_img_quality' => "Calidad de Imagen GD:",
'settings_recom_quality' => "(se recomienda 60 - 80)",
'settings_stock_settings' => "Configuraciones de Stock",
'settings_use_stock' => "&iquest;Mostrar nivel del stock?",
'settings_allow_out_of_stock_purchases' => "&iquest;Permitir compras fuera de stoch?",
'settings_stock_change_time' => "&iquest;Cu&aacute;ndo disminuir nivel de stock?",
'settings_stock_change_timement' => "Cuando se hubiera completado el pago (Cuando el estado del pedido sea Completado)",
'settings_stock_decrease_onbasket' => "Cuando se a&ntilde;ade a la canastilla",
'settings_stock_decrease_onorderbuild' => "Cuando el pedido se crea (Cuando el estado del pedido es Pendiente)",
'settings_add_to_basket_act' => "&iquest;Ir al carrito cuando se a&ntilde;ade a la canastilla?",
'settings_weight_unit' => "Unidad de Peso:",
'settings_time_and_date' => "Fecha &amp; Hora",
'settings_time_format' => "Formato de Hora:",
'settings_time_format_desc' => "(Ver <a href='http://www.php.net/strftime' target='_blank' class='txtLink'>www.php.net/strftime</a>)",
'settings_time_offset' => "Ajuste de Hora:",
'settings_time_offset_desc' => "(Segundos � Se utiliza para servidores en uso horario distinto)",
'settings_date_format' => "Formato de Fecha:",
'settings_date_format_desc' => "(Ver <a href='http://www.php.net/date' target='_blank' class='txtLink'>www.php.net/date</a>)",
'settings_locale_settings' => "Configuraciones del Local",
'settings_default_currency' => "Moneda Predeterminada",
'settings_inc_tax_prices' => "&iquest;A&ntilde;adir impuestos de ventas a clientes que califican?",
'settings_tax_del_inv' => "C&oacute;mo cargar impuestos al cliente:",
'settings_tax_del_add' => "Calcular Impuesto en la Direcci&oacute;n de Entrega",
'settings_tax_inv_add' => "Calcular Impuesto en la Direcci&oacute;n de Facturaci&oacute;n",
'settings_tax_either_add' => "Calcular Impuesto en la Direcci&oacute;n de Facturaci&oacute;n o Direcci&oacute;n de Entrega (si cualquiera de ellas califica)",
'settings_sale_mode' => "Modo de Venta:",
'settings_percent_of_all' => "Porcentaje de todos los precios",
'settings_ind_sale_per_item' => "Precios de Venta Individuales por &iacute;tem",
'settings_sale_mode_off' => "Modo de Venta Apagado",
'settings_sale_per_off' => "Porcentaje de Venta Apagado:",
'settings_sale_per_off_desc' => "(El porcentaje se debe sacar de todos los precios)",
'settings_diff_dispatch' => "&iquest;Permitir env&iacute;o a una direcci&oacute;n distinta de la direcci&oacute;n de facturaci&oacute;n?",
'settings_update_settings' => "Configuraciones de Actualizaci&oacute;n",
'settings_update_success' => "actualizaci&oacute;n exitosa.",
'settings_update_fail' => "no se actualiz&oacute;.",
'settings_add_success' => "se a&ntilde;adi&oacute; con &eacute;xito",
'settings_add_fail' => "no se a&ntilde;adi&oacute;.",
'settings_delete_success' => "Se elimin&oacute; con &eacute;xito.",
'settings_delete_failed' => "Eliminaci&oacute;n fall&oacute;.",
'settings_currencies' => "Monedas",
'settings_currency' => "Moneda",
'settings_currency_auto_method' => "M&eacute;todo de Tasa de Cambio Autom&aacute;tico:",
'settings_currency_csv' => "M&eacute;todo CSV de Yahoo",
'settings_currency_pear' => "PEAR &quot;M&eacute;todo de Tasasdecambio&quot;",
'settings_source_exchange' => "Fuente de Tasas de Cambio:",
'settings_nbi' => "Banco Nacional de Israel",
'settings_ecb' => "Banco Central Europeo",
'settings_nbp' => "Banco Nacional de Polonia",
'settings_currencies_desc' => "Por favor a&ntilde;ada, edite o borre las monedas de abajo:",
'settings_c_code' => "C&oacute;digo",
'settings_c_name' => "Nombre",
'settings_c_value' => "Valor",
'settings_symbol_left' => "S&iacute;mbolo a la Izquierda",
'settings_symbol_right' => "S&iacute;mbolo a la Derecha",
'settings_decimal_places' => "Posiciones Decimales",
'settings_decimal_format' => "Formato Decimal",
'settings_decimal_point' => "(Punto Decimal)",
'settings_comma' => "(Coma)",
'settings_last_updated' => "&Uacute;ltima Actualizaci&oacute;n",
'settings_c_status' => "Estado",
'settings_no_currencies' => "No hay monedas en la base de datos..",
'settings_countries' => "Pa&iacute;ses",
'settings_country' => "Pa&iacute;s",
'settings_edit_countries_below' => "Por favor a&ntilde;ada, edite o borre los pa&iacute;ses de abajo:",
'settings_disable' => "Deshabilitar",
'settings_enable' => "Habilitar",
'settings_iso' => "ISO",
'settings_iso_name' => "Nombre",
'settings_iso3' => "ISO3",
'settings_num_code' => "C&oacute;digo de Num",
'settings_action' => "Acci&oacute;n",
'settings_warn_del_country' => "&iquest;Est&aacute; seguro de que desea eliminar esto? Todos los condados, estados y zonas se borrar&aacute;n por encima de &eacute;l.",
'settings_no_countries_in_db' => "No hay pa&iacute;ses en la base de datos.",
'settings_edit_counties' => "Por favor a&ntilde;ada, edite o borre los condados/zonas/estados de abajo:",
'settings_no_counties_in_db' => "No hay condados, estados o zonas en la base de datos.",
'settings_tax_settings' => "Configuraci&oacute;n de Impuesto",
'settings_edit_locale_below' => "Por favor edite su configuraci&oacute;n local abajo:",
'settings_tax_only_to' => "Aplicar Impuestos S&Oacute;LO a la siguiente &aacute;rea:",
'settings_manage_tax_below' => "Por favor administra sus distintos tipos de impuestos abajo. &Eacute;stos se pueden aplicar para cada producto para que pueda tener productos exentos de impuestos y diferentes niveles de impuestos para distintos tipos de productos.",
'settings_tax_class2' => "Clase de Impuesto",
'settings_rate_per' => "Tasa (%)",
'settings_no_taxes_setup' => "Disculpe, no hay configuraci&oacute;n de impuestos.",
'settings_tax' => "Impuesto",
'settings_img_gallery_type' => "Tipo de Galer&iacute;a de Imagen del Producto:",
'settings_img_gallery_type_popup' => "Aparecer en ventana nueva",
'settings_img_gallery_type_lightbox' => "Caja luminosa (Recomendado)",
'settings_cat_tree' => "&iquest;Utilizar el &aacute;rbol de categor&iacute;a DHTML expandible?",
'hide_prices' => "&iquest;Esconder los precios hasta que el cliente hubiera iniciado sesi&oacute;n?",
'settings_logo_reverted' => "El logo `%1\$s` se ha revertido al predeterminado.",
'settings_logo_invalid' => "El formato de archivo del logo `%1\$s` no es de un tipo v&aacute;lido. Por favor utilice s&oacute;lo archivos GIF, JPEG o PNG.",
'settings_logo_changed' => "El logo `%1\$s` se ha cambiado con &eacute;xito.",
'settings_logo_title' => "Administraci&oacute;n de Logo",
'settings_logo_skin_name' => "Nombre del Dise&ntilde;o",
'settings_logo_default_skin' => "Dise&ntilde;o Predeterminado",
'settings_logo_default_logo' => "Logo Predeterminado",
'settings_logo_current_logo' => "Logo Actual",
'settings_logo_action' => "Acci&oacute;n",
'settings_logo_default_missing' => "&iexcl;No se encuentra el Logo Predeterminado!",
'settings_logo_revert' => "Revertir al Predeterminado",
'settings_logo_revert_warn' => "&iquest;Est&aacute; seguro de que desea borrar el logo actual?",
'settings_logo_dimensions' => "Dimensiones: %1\$s x %2\$s px",
'settngs_logo_upload' => "Cargar Imagen(es)",
'settings_show' => "Mostrar",
'settings_reset' => "Restablecer",
'cat_newest_first' => 'Display newest products first',
'cat_newest_first_info' => 'If enabled, the default category view will be to show the most recently added products first.',

'google_analytics' => 'Google Analytics ID',
'google_analytics_info' => 'This can be found in the code provided by Google, and will look something like "UA-######-#"',

);
?>
