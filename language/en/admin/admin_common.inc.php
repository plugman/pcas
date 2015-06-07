<?php

if(!isset($langBully)) require("language".CC_DS. $config['defaultLang'].CC_DS."config.php");

$lv = !$langBully ?  "lang" : "bully";

${$lv}['admin_common'] = array(

'misc_pages' => " pages ",

'yes' => "On",

'update' => "Update",

'no' => "Off",

'edit' => "Edit",

'delete' => "Delete",

'disabled' => "Disabled",

'enabled' => "Enabled",

'disable' => "Disable",

'enable' => "Enable",

'Credit' => "Credit",

'add' => "Add",

'edit' => "Edit",

'resize' => "Resize",

'delete_q' => "Are you sure you want to delete this?",

'sure_q' => "Are you sure you want to do this?",

'add' => "Add",

'add_new' => "Add New",

'write' => "Write",

'read' => "Read",

'na' => "N/A", // as in not applicable

'all' => "ALL",

'remove' => "Remove",

'hide' => "Hide",

'show' => "Show",

'please_wait' => "Please wait ...",

'other_announcements' => "Latest Announcements",

'other_no_announcements' => "There are currently no announcements.",

'other_pending_orders' => "Processing Orders",

'other_product_reviews' => "Product Reviews",

'other_stock_warnings' => "Stock Warnings",

'other_no_pending_orders' => "There are no pending orders.",

'other_no_reviews_moderate' => "No new reviews to moderate.",

'other_no_low_stock' => "No stock is level is low.",

'ip' => "IP:",

'blocked' => "Authentication blocked for %1\$s minutes for security reasons.",

'other_global_risk' => "WARNING: The main configuration file 'includes/global.inc.php' is writable and your store is at risk. Please change the file permissions so that it is read only.",

'setup_folder_exists' => "WARNING: The ImeiUnlock setup folder 'setup/' exists on your server. It must be deleted immediately as your store is at risk.",

'other_401' => "Error 401: You do not have permission to access that page. Please ask one of the super users to grant this for you.",

'other_welcome_note' => "Welcome to the Administration Control Panel",

'other_last_login_failed' => "Last login by %1\$s, failed on %2\$s",

'other_last_login_success' => "Last login by %1\$s on %2\$s",

'other_store_overview' => "Store Overview:",

'other_version' => "Version:",

'other_visit_cc' => "Visit the ImeiUnlock Downloads Server",

'other_no_products' => "Number of Products:",

'other_no_customers' => "Number of Customers:",

'other_img_upload_size' => "Image upload folder size:",

'other_no_orders' => "Number of Orders:",

'other_quick_search' => "Quick Search:",

'other_order_no' => "Order Number:",

'other_search_now' => "Search Now",

'other_customer' => "Customer:",

'other_login_failed' => "Login Failed! Either the username or password was incorrect.",

'other_new_pass_sent' => "A new password has been emailed to",

'other_no_admin_sess' => "No administration session was found.",

'other_login_fail_2' => "Login failed. Please try again.",

'other_login_below' => "Please login below:",

'other_username' => "Username:",

'other_password' => "Password:",

'other_login_ssl' => "Use secure login:",

'other_request_pass' => "Request Password",

'other_login' => "Login",

'other_pass_reset_failed' => "Password reset failed.",

'other_enter_email_below' => "Please enter your email address below:",

'other_email_address' => "Email Address:",

'other_send_pass' => "Send Password",

'other_store_inventory' => "Inventory Summary:",

'nav_transaction_logs' => "Transaction Logs",

'nav_edit_langs' => "Languages",

'nav_coupons' => "Coupons",

'nav_gift_certificates' => "Gift Certificates",

'nav_maintenance' => "Maintenance",

'nav_reports' => "Reports",

'nav_low_stock' => "Low Stock",

'nav_search_keywords' => "Search Keyword",

'nav_prod_reviews_rating' => "Product Reviews Rating",

'nav_registered_users' => "Registered Users",

'nav_transactions_history' => "Transactions Report",

'nav_profit_customer' => "Profitable Customers",

'nav_database' => "Database",

'nav_backup' => "Backup",

'nav_thumbnails' => "Thumbnails",

'nav_rebuild' => "Rebuild &amp; Recount",

'nav_admin_logs' => "Admin Logs",

'nav_permission_error' => "You do not have permission to access this.",

'nav_navigation' => "Navigation",

'nav_admin_home' => "Admin Home",

'nav_store_home' => "Store Home",

'nav_store_config' => "Store Configuration",

'nav_gen_settings' => "General Settings",

'nav_taxes' => "Taxes",

'nav_logo' => "Logo",

'nav_countries_zones' => "Countries &amp; Zones",

'nav_currencies' => "Currencies",

'nav_modules' => "Modules",

'nav_shipping' => "Shipping Methods",

'nav_gateways' => "Payment Methods",

'nav_affiliates' => "Affiliate Tracking",

'nav_alt_checkout' => "Alternate Checkouts",

'nav_catalog' => "Catalog",

'nav_view_products' => "View Products",

'nav_add_product' => "Add Product",

'nav_product_options' => "Product Options",

'nav_prod_reviews' => "Product Reviews",

'nav_view_categories' => "View Categories",

'nav_add_categories' => "Add Category",

'nav_import_cat' => "Import Catalogue",

'nav_export_cat' => "Export Catalogue",

'nav_customers' => "Customers",

'nav_view_customers' => "View Customers",

'nav_email_customers' => "Email Customers",

'nav_orders' => "Orders",

'nav_file_manager' => "File Manager",

'nav_manage_images' => "Manage Images",

'nav_upload_images' => "Upload Images",

'nav_statistics' => "Statistics",

'nav_view_stats' => "View Stats",

'nav_documents' => "Manage Documents",

'nav_homepage' => "Homepage",

'nav_site_docs' => "Site Documents",

'nav_misc' => "Misc",

'nav_server_info' => "Server Info",

'nav_admin_users' => "Admin Users",

'nav_administrators' => "Administrators",

'nav_admin_sessions' => "Admin Sessions",

'incs_administration' => "Administration",

'incs_logged_in_as' => "Logged in as:",

'incs_logout' => "Logout",

'incs_change_pass' => "Change Password",

'incs_error_editing' => "Error trying to edit. Input data was not an array.",

'incs_config_updated' => "Configuration Updated. Please make sure the file permission have been set back correctly.",

'incs_cant_write' => "Could not open '%1\$s' for writing.<br />Try changing the CHMOD value to 0777. Remember to set it back to 0644 afterwards!",

'incs_db_config_updated' => "Configuration Updated.",

'incs_db_cant_write' => "No changes have been made!",

'incs_select_above' => "Select Above",

'close_window' => "Close Window",

'history_back' => 'Back',

'order_details' => 'View order details',

'nav_module_installer' => 'Module Installer',

);

?>