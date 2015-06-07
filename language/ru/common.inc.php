<?php
if(!isset($langBully)) require(CC_ROOT_DIR.CC_DS."language".CC_DS.LANG_FOLDER.CC_DS."config.php");
$lv = !$langBully ?  "lang" : "bully";
${$lv}['front'] = array(
'yes' => "Да",
'no' => "Нет",
'na' => "N/A",
'sort' => 'Сортировать',
'misc_pages' => " страницы",
'misc_perofOrderSub' => " % Итог Заказа",
'misc_freeForOrdOver' => "Бесплатно для Заказов Свыше",
'misc_freeShipping' => "Бесплатная Доставка",
'misc_byWeight1stClass' => "По Весу (1 Класс)",
'misc_1stClass' => "(1 Класс)",
'misc_byWeight2ndClass' => "По Весу (2 Класс)",
'misc_2ndClass' => "(2 Класс)",
'misc_flatRate' => "Единая Ставка",
'misc_free' => "Бесплатно",
'misc_national' => "По Стране",
'misc_international' => "По Всему Миру",
'misc_byCategory' => "По Категории",
'misc_perItem' => "По Позиции",
'misc_nextDayEarlyAm' => "Авиа, Следующий День Раннее Утро",
'misc_nextDayAir' => "Следующий День Авиа",
'misc_nextDayAirSaver' => "Следующий День Авиа Эконом",
'misc_2ndDayEarlyAm' => "Авиа, 2ой День Раннее Утро",
'misc_2ndDayAir' => "2-ой День Авиа",
'misc_3daySelect' => "3-ий День Выбрать",
'misc_ground' => "Наземная",
'misc_canadaStandard' => "Канада Стандарт",
'misc_worldwideExpress' => "Экспресс По Всему Миру",
'misc_worldwideExpressPlus' => "Экспресс Плюс По Всему Миру",
'misc_worldwideExpedited' => "Расширенный Экспресс По Всему Миру",
'popup_thumb_alt' => "Щелкните для того, чтобы посмотреть большое изображение",
'popup_large_alt' => "Изображение во Весь Размер",
'login_view_price' => "Вы должны войти в систему, для того чтобы иметь возможность видеть наши цены! ",
'misc_price_hidden' => "?.??"
);
?>