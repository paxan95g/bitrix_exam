<?php

spl_autoload_register(function ($class_name) {
    include $_SERVER['DOCUMENT_ROOT']."/local/php_interface/include/$class_name.php";
});

// Константы
define('MANAGER_GROUP_ID', 5);

// Обработчики событий
AddEventHandler("main", "OnBuildGlobalMenu", array("EventHandlers", "OnBuildGlobalMenuHandler"));







