<?php


// ФАЙЛ     /local/templates/furniture_red/header.php
// скорее всего добавлять не нужно! его по умолчанию выводит ShowHead
    $APPLICATION->ShowProperty("canonical");




// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/bitrix/news.list/.default/result_modifier.php
if($arParams['CANONICAL']) {

    $arFilter = [
        'IBLOCK' => intval($arParams['CANONICAL']),
        'PROPERTY_CANONICAL' => $arResult['ID'],
    ];
    $arFields = [
        'ID',
        'IBLOCK',
        'NAME',
        'PROPERTY_CANONICAL',
    ];
    $obj = CIBlockElement::GetList([], $arFilter, false, false, $arFields);
    if($res = $obj->Fetch()) {
        $arResult['CANONICAL'] = $res;

        $this->__component->SetResultCacheKeys(['CANONICAL']);
    }
}



// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/bitrix/news.list/.default/component_epilog.php
if($arResult['CANONICAL']) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL']['NAME']);
}



// ФАЙЛ     /local/templates/furniture_red/components/bitrix/news/.default/.parameters.php
$arTemplateParameters["CANONICAL"] = [
    "NAME" => GetMessage("CANONICAL"),
    "TYPE" => "STRING",
];


// Добавить в параметры вызова news.detail
//  "CANONICAL" => $arParams["CANONICAL"],