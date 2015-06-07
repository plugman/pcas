<?php
if(!isset($langBully)) require("language".CC_DS. $config['defaultLang'].CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['admin_common'] = array(
'misc_pages' => " p&aacute;ginas ",
'yes' => "Sim",
'update' => "Actualiza&ccedil;&atilde;o",
'no' => "N&atilde;o",
'edit' => "Editar",
'delete' => "Apagar",
'disabled' => "Desactivado",
'enabled' => "Activado",
'disable' => "Desactivar",
'enable' => "Activar",
'add' => "Adicionar",
'edit' => "Editar",
'resize' => "Redimensionar",
'delete_q' => "Tem a certeza que quer apagar isto?",
'sure_q' => "Tem a certeza que quer fazer isto?",
'add' => "Adicionar",
'add_new' => "Adicionar Novo",
'write' => "Escrever",
'read' => "ler",
'na' => "N/A", // como em n&atilde;o aplic&aacute;vel
'all' => "TODOS",
'remove' => "Remover",
'hide' => "Esconder",
'show' => "Mostrar",
'please_wait' => "Por favor espere ...",
'other_announcements' => "&Uacute;ltimas Comunica&ccedil;&otilde;es",
'other_no_announcements' => "De momento n&atilde;o existem comunica&ccedil;&otilde;es.",
'other_pending_orders' => "Encomendas Pendentes / em Processamento ",
'other_product_reviews' => "An&aacute;lises ao Produto",
'other_stock_warnings' => "Avisos de Stock",
'other_no_pending_orders' => "N&atilde;o existem encomendas pendentes.",
'other_no_reviews_moderate' => "N&atilde;o h&aacute; novas an&aacute;lises para moderar.",
'other_no_low_stock' => "N&atilde;o existem n&iacute;veis de stock baixos.",
'ip' => "IP:",
'blocked' => "Autentica&ccedil;&atilde;o bloqueada durante %1\$s minutos por raz&otilde;es de seguran&ccedil;a.",
'other_global_risk' => "AVISO: O principal ficheiro de configura&ccedil;&atilde;o 'includes/global.inc.php' &eacute; edit&aacute;vel e a sua loja est&aacute; em perigo. Por favor altere as permiss&otilde;es de ficheiros para que seja apenas de leitura.",
'setup_folder_exists' => "AVISO: A pasta de configura&ccedil;&atilde;o 'setup/' do  ImeiUnlock existe no seu servidor. Deve ser apagada imediatamente dado que a sua loja est&aacute; em perigo",
'other_401' => "Erro 401: N&atilde;o tem permiss&atilde;o para aceder a esta p&aacute;gina. Por favor pe&ccedil;a a um dos super utilizadores que lhe conceda isto.",
'other_welcome_note' => "Bem-vindo ao Painel de controlo de administra&ccedil;&atilde;o do ImeiUnlock&trade; ",
'other_last_login_failed' => "O &uacute;ltimo login de %1\$s, falhou em %2\$s",
'other_last_login_success' => " O &uacute;ltimo login de %1\$s em %2\$s",
'other_store_overview' => "Vis&atilde;o Geral da Loja:",
'other_version' => "Vers&atilde;o:",
'other_visit_cc' => "Visite o Servidor de Downloads do ImeiUnlock",
'other_no_products' => "N&uacute;mero de Produtos:",
'other_no_customers' => "N&uacute;mero de Clientes:",
'other_img_upload_size' => "Dimens&atilde;o do carregamento de pasta de imagens:",
'other_no_orders' => "N&uacute;mero de Encomendas:",
'other_quick_search' => "Busca R&aacute;pida:",
'other_order_no' => "N&uacute;mero de Encomenda:",
'other_search_now' => "Procure agora",
'other_customer' => "Cliente:",
'other_login_failed' => "Login Falhou! O nome de utilizador ou a semana est&atilde;o incorrectos.",
'other_new_pass_sent' => "Foi enviada uma nova senha para o e-mail",
'other_no_admin_sess' => "N&atilde;o foi encontra nenhuma sess&atilde;o de administra&ccedil;&atilde;o.",
'other_login_fail_2' => "Login falhou. Por favor tente novamente.",
'other_login_below' => "Por favor fa&ccedil;a o login em baixo:",
'other_username' => "Nome do Utilizador:",
'other_password' => "Senha:",
'other_login_ssl' => "Use um login seguro:",
'other_request_pass' => "Pe&ccedil;a senha",
'other_login' => "Login",
'other_pass_reset_failed' => "Redefini&ccedil;&atilde;o de senha falhou.",
'other_enter_email_below' => "Por favor introduza o seu endere&ccedil;o de e-mail em baixo:",
'other_email_address' => "Endere&ccedil;o de e-mail:",
'other_send_pass' => "Enviar Senha ",
'other_store_inventory' => "Resumo de Invent&aacute;rio:",
'nav_transaction_logs' => "Registos de Transac&ccedil;&atilde;o",
'nav_edit_langs' => "Idiomas",
'nav_coupons' => "Vales",
'nav_gift_certificates' => "Cheques-prenda",
'nav_maintenance' => "Manuten&ccedil;&atilde;o",
'nav_database' => "Base de Dados",
'nav_backup' => "C&oacute;pia de Seguran&ccedil;a",
'nav_Miniaturas' => "Miniaturas",
'nav_rebuild' => "Reconstruir &amp; Recontar",
'nav_admin_logs' => "Registos de Administrador",
'nav_permission_error' => "N&atilde;o tem permiss&atilde;o para aceder a isto.",
'nav_navigation' => "Navega&ccedil;&atilde;o",
'nav_admin_home' => "P&aacute;gina Inicial do Administrador",
'nav_store_home' => "P&aacute;gina Inicial da Loja",
'nav_store_config' => "Configura&ccedil;&atilde;o da Loja ",
'nav_gen_settings' => "Defini&ccedil;&otilde;es Gerais ",
'nav_taxes' => "Taxas",
'nav_logo' => "Log&oacute;tipo",
'nav_countries_zones' => "Pa&iacute;ses &amp; Zonas",
'nav_currencies' => "Moedas",
'nav_modules' => "M&oacute;dulos",
'nav_shipping' => "M&eacute;todos de envio",
'nav_gateways' => "M&eacute;todos de Pagamento",
'nav_affiliates' => "Acompanhamento Associado",
'nav_alt_checkout' => "Checkout Alternativos",
'nav_catalog' => "Cat&aacute;logo",
'nav_view_products' => "Ver Produtos",
'nav_add_product' => "Adicionar Produto",
'nav_product_options' => "Op&ccedil;&otilde;es de Produto",
'nav_prod_reviews' => "An&aacute;lises ao Produto",
'nav_view_categories' => "Ver Categorias",
'nav_add_categories' => "Adicionar Categoria",
'nav_import_cat' => "Importar Cat&aacute;logo ",
'nav_export_cat' => "Exportar Cat&aacute;logo",
'nav_customers' => "Clientes",
'nav_view_customers' => "Ver Clientes",
'nav_email_customers' => "Enviar e-mail para Clientes",
'nav_orders' => "Encomendas",
'nav_file_manager' => "Gestor de Ficheiros ",
'nav_manage_images' => "Gestor de Imagens",
'nav_upload_images' => "Carregamento de Imagens",
'nav_statistics' => "Estat&iacute;sticas",
'nav_view_stats' => "Ver Estat&iacute;sticas",
'nav_documents' => "Documentos",
'nav_homepage' => "P&aacute;gina Inicial",
'nav_site_docs' => "Documentos do Site ",
'nav_misc' => "V&aacute;rios",
'nav_server_info' => "Informa&ccedil;&atilde;o do Servidor ",
'nav_admin_users' => "Utilizadores Administradores ",
'nav_administrators' => "Administradores",
'nav_admin_sessions' => "Sess&otilde;es de Administrador",
'incs_administration' => "Administra&ccedil;&atilde;o",
'incs_logged_in_as' => "Fez o login como:",
'incs_logout' => "Logout",
'incs_change_pass' => "Alterar Senha",
'incs_error_editing' => "Erro ao tentar editar. Dados introduzidos n&atilde;o eram uma matriz.",
'incs_config_updated' => "Configura&ccedil;&atilde;o Actualizada. Por favor certifique-se que a permiss&atilde;o de ficheiros foi redefinida correctamente.",
'incs_cant_write' => "N&atilde;o conseguiu abrir '%1\$s' para editar.<br />Tente alterar o valor CHMOD para 0777. Lembre-se de o redefinir depois para 0644!",
'incs_db_config_updated' => "Configura&ccedil;&atilde;o Actualizada.",
'incs_db_cant_write' => "N&atilde;o foram feitas altera&ccedil;&otilde;es!",
'incs_select_above' => "Seleccionar Acima ",
'close_window' => "Fechar Janela",
'history_back' => 'Regressar',
'order_details' => 'Ver pormenores da encomenda',
'nav_module_installer' => 'Instalador de M&oacute;dulo',
);
?>
