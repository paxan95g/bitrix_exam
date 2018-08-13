<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Loader;

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 3600000;

// Обработка входящих параметров
$arParams["CATALOG_IBLOCK_ID"] = intval($arParams["CATALOG_IBLOCK_ID"]);
$arParams["MANUF_IBLOCK_ID"] = intval($arParams["MANUF_IBLOCK_ID"]);
$arParams["DETAIL_LINK"] = trim($arParams["DETAIL_LINK"]);
$arParams["MANUF_PROPERTY_CODE"] = trim($arParams["MANUF_PROPERTY_CODE"]);

// Область кэширования
if($this->startResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))) {

    if(!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    // Сортировка элементов инфоблока
    $arSort = ['NAME' => "ASC", "SORT" => "ASC"];

    // Получаем список товаров, у которых установлено свойство Фирма - производитель
    $arProduct = [];
    $arFirmsID = [];
    $arFilter = ['IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"], 'ACTIVE' => 'Y', '!PROPERTY_'.$arParams["MANUF_PROPERTY_CODE"] => false, 'CHECK_PERMISSIONS' => 'Y'];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER', 'PROPERTY_'.$arParams["MANUF_PROPERTY_CODE"], 'DETAIL_PAGE_URL'];
    $ob = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
    // Устанавливаем шаблон для детальной ссылки
    $ob->SetUrlTemplates($arParams["DETAIL_LINK"]);
    // ОБЯЗАТЕЛЬНО GetNext - если нужно преобразовать ссылки
    while($res = $ob->GetNext()) {
        $arProduct[] = $res;
        foreach($res['PROPERTY_'.$arParams["MANUF_PROPERTY_CODE"].'_VALUE'] as $firmID) {
            if(!isset($arFirmsID[$firmID])) {
                $arFirmsID[$firmID] = $firmID;
            }
        }
    }

    // Получаем данные по Фирмам-производителям
    $arFirms = [];
    $arFilter = ['IBLOCK_ID' => $arParams["MANUF_IBLOCK_ID"], 'ACTIVE' => 'Y', 'ID' => $arFirmsID, 'CHECK_PERMISSIONS' => 'Y'];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME'];
    $ob = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
    while($res = $ob->Fetch()) {
        foreach($arProduct as $prod) {
            foreach($prod['PROPERTY_'.$arParams["MANUF_PROPERTY_CODE"].'_VALUE'] as $firmID) {
                if($res['ID'] == $firmID) {
                    $res['ITEMS'][$prod['ID']] = $prod;
                }
            }
        }
        $arFirms[] = $res;
    }

    // Заполняем $arResult
    $arResult['FIRMS'] = $arFirms;
    $arResult['COUNT'] = count($arFirms);

    // Указываем какие значения массива кэшировать
    $this->setResultCacheKeys(array(
        'COUNT'
    ));

    // Подключаем шаблон компонента
    $this->includeComponentTemplate();
}

// Устанавливаем заголовок страницы
$APPLICATION->SetTitle(GetMessage('TITLE_SECTION_COUNT') . '[' . $arResult['COUNT']. ']');