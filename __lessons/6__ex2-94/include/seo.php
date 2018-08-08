<?php
use \Bitrix\Main\Loader;
if(Loader::includeModule('iblock')) {
    global $APPLICATION;

    // Получаем текущую страницу
    $curPage = $APPLICATION->GetCurPage();

    // Получаем данные из инфоблока с учетом текущей страницы
    $arFilter = [
        'IBLOCK_ID' => SEO_IBLOCK_ID,
        'NAME' => $curPage
    ];
    $arFields = [
        'ID',
        'IBLOCK_ID',
        'PROPERTY_TITLE',
        'PROPERTY_DESCRIPTION'
    ];
    $seoResult = [];
    $ob = CIBlockElement::GetList([], $arFilter, false, false, $arFields);
    if($seoData = $ob->Fetch()) {

        // Устанавливаем свойства страницы
        $APPLICATION->SetPageProperty('title', $seoData['PROPERTY_TITLE_VALUE']);
        $APPLICATION->SetPageProperty('description', $seoData['PROPERTY_DESCRIPTION_VALUE']);
    }
}
