<?php
// Автозагрузка классов
spl_autoload_register(function ($class_name) {
    include $_SERVER['DOCUMENT_ROOT']."/local/php_interface/include/$class_name.php";
});

// Константы
define('PRODUCT_IBLOCK_ID', 2);

// Обработчики событий
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EventHandlers", "OnBeforeIBlockElementUpdateHandler"));






