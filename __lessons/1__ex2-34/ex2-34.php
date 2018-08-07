<?php


// ФАЙЛ     /local/templates/furniture_red/header.php?>
<meta property= "specialdate" content="<?$APPLICATION->ShowProperty("specialdate")?>">



<?
// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/bitrix/news.list/.default/result_modifier.php
if($arResult['ITEMS'][0]["ACTIVE_FROM"]) {
    $arResult["SPECIALDATE"] = $arResult['ITEMS'][0]["ACTIVE_FROM"];
    $this->__component->SetResultCacheKeys([
        "SPECIALDATE"
    ]);
}



// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/bitrix/news.list/.default/component_epilog.php
if($arParams['SPECIALDATE'] == 'Y') {
    $APPLICATION->SetPageProperty("specialdate",  $arResult["SPECIALDATE"]);
}



// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/.parameters.php
$arTemplateParameters["SPECIALDATE"] = [
    "NAME" => GetMessage("SPECIALDATE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "Y",
];


// Добавить в параметры вызова news.list
//  "SPECIALDATE" => $arParams["SPECIALDATE"],