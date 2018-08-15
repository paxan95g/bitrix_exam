<?php

// Подключаем Агента
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/agent.php')) {
    require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/agent.php');
}