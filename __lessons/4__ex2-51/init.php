<?php

spl_autoload_register(function ($class_name) {
    include $_SERVER['DOCUMENT_ROOT']."/local/php_interface/include/$class_name.php";
});

// Константы
define('FEEDBACK_EVENT_NAME', 'FEEDBACK_FORM');


// Обработчики событий
AddEventHandler("main", "OnBeforeEventAdd", array("EventHandlers", "OnBeforeEventAddHandler"));