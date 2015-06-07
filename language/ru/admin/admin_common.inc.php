﻿<?php
if(!isset($langBully)) require("language".CC_DS. $config['defaultLang'].CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['admin_common'] = array(
'misc_pages' => " страницы ",
'yes' => "Да",
'update' => "Обновить",
'no' => "Нет",
'edit' => "Редактировать",
'delete' => "Удалить",
'disabled' => "Выключено",
'enabled' => "Включено",
'disable' => "Выключить",
'enable' => "Включить",
'add' => "Добавить",
'edit' => "Редактировать",
'resize' => "Изменить размер",
'delete_q' => "Вы уверены что хотите удалить это?",
'sure_q' => "Вы уверены, что хотите сделать это?",
'add' => "Добавить",
'add_new' => "Добавить Новый",
'write' => "Написать",
'read' => "Читать",
'na' => "Не Применимо", 
'all' => "ВСЕ",
'remove' => "Убрать",
'hide' => "Скрыть",
'show' => "Показать",
'please_wait' => "Пожалуйста подождите…",
'other_announcements' => "Последние Объявления",
'other_no_announcements' => "В данный момент нет объявлений.",
'other_pending_orders' => "Ожидание / Обработка Заказов",
'other_product_reviews' => "Обзоры Продуктов",
'other_stock_warnings' => "Предупреждения о Наличии",
'other_no_pending_orders' => "Нет ожидающих заказов.",
'other_no_reviews_moderate' => "Нет новых обзоров для модерирования. ",
'other_no_low_stock' => "Нет в наличии, низкий уровень.",
'ip' => "IP:",
'blocked' => "В целях безопасности аутентификация блокирована на %1\$s минут.",
'other_global_risk' => "ПРЕДУПРЕЖДЕНИЕ: Главный конфигурационный файл 'includes/global.inc.php' перезаписываемый и ваш магазин под угрозой. Пожалуйста, измените, права доступа файла, и сделайте его доступным только на чтение.",
'setup_folder_exists' => "ПРЕДУПРЕЖДЕНИЕ: Папка установки ImeiUnlock 'setup/' существует на вашем сервере. Она должна быть незамедлительно удалена, так как ваш магазин под угрозой.",
'other_401' => "Ошибка 401: У вас нет прав для доступа на эту страницу. Пожалуйста, обратитесь к одному из супер пользователей, чтобы они предоставили вам право доступа.",
'other_welcome_note' => "Добро Пожаловать в ImeiUnlock&trade; Административная Панель Управления",
'other_last_login_failed' => "Последний вход %1\$s, не удался на %2\$s",
'other_last_login_success' => "Последний вход %1\$s на %2\$s",
'other_store_overview' => "Обзор Магазина: ",
'other_version' => "Версия:",
'other_visit_cc' => "Посетите Сервер Загрузок ImeiUnlock  ",
'other_no_products' => "Количество Продуктов: ",
'other_no_customers' => "Количество Клиентов:",
'other_img_upload_size' => "Размер папки выгрузки изображений:",
'other_no_orders' => "Количество Заказов:",
'other_quick_search' => "Быстрый Поиск:",
'other_order_no' => "Номера Заказов:",
'other_search_now' => "Начать Поиск",
'other_customer' => "Клиент:",
'other_login_failed' => "Вход не удался! Имя пользователя или пароль неверны.",
'other_new_pass_sent' => "Новый пароль был отправлен на ваш e-mail адрес",
'other_no_admin_sess' => "Не найдено административных сессий.",
'other_login_fail_2' => "Вход не удался. Пожалуйста, попробуйте еще раз.",
'other_login_below' => "Пожалуйста, войдите в систему: ",
'other_username' => "Имя Пользователя:",
'other_password' => "Пароль:",
'other_login_ssl' => "Использовать безопасный вход:",
'other_request_pass' => "Требовать Пароль",
'other_login' => "Войти",
'other_pass_reset_failed' => "Сброс пароля не удался.",
'other_enter_email_below' => "Пожалуйста введите ваш e-mail адрес:",
'other_email_address' => "E-mail адрес:",
'other_send_pass' => "Выслать Пароль",
'other_store_inventory' => "Инвентаризационная Сводка: ",
'nav_transaction_logs' => "Журналы Транзакций ",
'nav_edit_langs' => "Языки:",
'nav_coupons' => "Купоны",
'nav_gift_certificates' => "Подарочные Сертификаты",
'nav_maintenance' => "Обслуживание",
'nav_database' => "База данных",
'nav_backup' => "Резервная Копия",
'nav_thumbnails' => "Миниатюрные изображения",
'nav_rebuild' => "Восстановить &amp; Пересчитать",
'nav_admin_logs' => "Административные Журналы",
'nav_permission_error' => "У вас нет прав для доступа.",
'nav_navigation' => "Навигация",
'nav_admin_home' => "Страничка Администратора ",
'nav_store_home' => "Домашняя Страничка Магазина",
'nav_store_config' => "Конфигурация Магазина",
'nav_gen_settings' => "Общие Установки",
'nav_taxes' => "Налоги",
'nav_logo' => "Логотип",
'nav_countries_zones' => "Страны &amp; Зоны",
'nav_currencies' => "Валюты",
'nav_modules' => "Модули",
'nav_shipping' => "Методы Доставки",
'nav_gateways' => "Методы Оплаты",
'nav_affiliates' => "Объединять Трэкинг",
'nav_alt_checkout' => "Альтернативные Расчеты",
'nav_catalog' => "Каталог",
'nav_view_products' => "Обзор Продуктов",
'nav_add_product' => "Добавить Продукт ",
'nav_product_options' => "Опции Продукта",
'nav_prod_reviews' => "Обзоры Продуктов",
'nav_view_categories' => "Обзор Категорий",
'nav_add_categories' => "Добавить Категорию",
'nav_import_cat' => "Импортировать Каталог",
'nav_export_cat' => "Экспортировать Каталог",
'nav_customers' => "Клиенты",
'nav_view_customers' => "Обзор Клиентов",
'nav_email_customers' => "Написать e-mail клиентам",
'nav_orders' => "Заказы",
'nav_file_manager' => "Управление Файлами",
'nav_manage_images' => "Управление Изображениями",
'nav_upload_images' => "Выгрузить Изображения",
'nav_statistics' => "Статистика ",
'nav_view_stats' => "Обзор Статистики",
'nav_documents' => "Документы",
'nav_homepage' => "Домашняя Страница",
'nav_site_docs' => "Документы Сайта",
'nav_misc' => "Разное",
'nav_server_info' => "Информация Сервера",
'nav_admin_users' => "Пользователи Администраторы",
'nav_administrators' => "Администраторы",
'nav_admin_sessions' => "Сессии Администраторов",
'incs_administration' => "Администрация ",
'incs_logged_in_as' => "Вошел в систему как:",
'incs_logout' => "Выйти из системы",
'incs_change_pass' => "Изменить пароль ",
'incs_error_editing' => "Ошибка редактирования. Вводимые данные не упорядочены.",
'incs_config_updated' => "Конфигурация Обновлена. Пожалуйста, убедитесь что права доступа файла установлены корректно. ",
'incs_cant_write' => "Не возможно открыть '%1\$s' для записи.<br />Попытайтесь изменить значение CHMOD на 0777. Не забудьте, затем изменить значение на 0644",
'incs_db_config_updated' => "Конфигурация обновлена. ",
'incs_db_cant_write' => "Изменений не внесено! ",
'incs_select_above' => "Выбрать Выше",
'close_window' => "Закрыть Окно",
'history_back' => 'Назад',
'order_details' => 'Обзор подробностей заказа',
'nav_module_installer' => 'Инсталлятор Модуля',
);
?>