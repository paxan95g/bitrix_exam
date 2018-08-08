<?php

// Константы
define('SEO_IBLOCK_ID', 6);

// Подключаем SEO файл
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/seo.php')) {
    require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/seo.php');
}