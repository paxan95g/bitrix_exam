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
    $arParams["CACHE_TIME"] = 36000000;

$arParams["CATALOG_IBLOCK_ID"] = intval($arParams["CATALOG_IBLOCK_ID"]);
$arParams["NEWS_IBLOCK_ID"] = intval($arParams["NEWS_IBLOCK_ID"]);
$arParams["NEWS_COUNT"] = intval($arParams["NEWS_COUNT"]);
if($arParams["NEWS_COUNT"]<=0)
    $arParams["NEWS_COUNT"] = 20;


global $CACHE_MANAGER;//  [ex2-107]

//   /servicesIblock - папка, где будут хранится файлы кэша
if($this->startResultCache(false, false, '/servicesIblock')) //[ex2-107]
{
    if(!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    // 3 - id инфоблока, при обновлении элементов которого будет сброшен кэш
    $CACHE_MANAGER->RegisterTag('iblock_id_3');// [ex2-107]

    // Получаем разделы инфоблока Продукция
    $arSections = [];
    $arFilter = ['IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"], 'ACTIVE' => 'Y', '!'.$arParams["PROPERTY_CODE"] => false];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', $arParams["PROPERTY_CODE"]];
    $ob = CIBlockSection::GetList([], $arFilter, false, $arSelect);
    while($res = $ob->Fetch()) {

        // Формируем массив со списков id Новостей, для дальнейшей выборки
        foreach($res[$arParams["PROPERTY_CODE"]] as $newsID) {
            if(!isset($arNewsID[$newsID])) {
                $arNewsID[$newsID] = $newsID;
            }
        }
        $arSections[$res['ID']] = $res;
    }

    // Получаем эементы инфоблока Продукция и раскидываем по разделам
    $count = 0;
    $arFilter = [
        'IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"],
        'ACTIVE' => 'Y',
    ];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'PROPERTY_PRICE', 'PROPERTY_ARTNUMBER', 'PROPERTY_MATERIAL'];
    $ob = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    while($res = $ob->Fetch()) {
        if (isset($arSections[$res['IBLOCK_SECTION_ID']])) {
            $arSections[$res['IBLOCK_SECTION_ID']]['ITEMS'][$res['ID']] = $res;
            $count++;
        }
    }

    // Получаем эементы инфоблока Новости
    $arNews = [];
    $arFilter = ['IBLOCK_ID' => $arParams["NEWS_IBLOCK_ID"], 'ID' => $arNewsID, 'ACTIVE' => 'Y'];
    // Параметры постраничной навигации
    $arNavParams = ["nPageSize"=>$arParams["NEWS_COUNT"], "bShowAll" => false];
    $arSelect = ['ID', 'IBLOCK_ID', 'IBLOCK_CODE', 'NAME', 'DATE_ACTIVE_FROM'];
    $ob = CIBlockElement::GetList([], $arFilter, false, $arNavParams, $arSelect);
    // Получаем HTML код постраничной навигации
    $navString = $ob->GetPageNavString("Страницы");
    while($res = $ob->Fetch()) {
        $news_iblockCode = $res['IBLOCK_CODE'];
        $arNews[$res['ID']] = $res;
    }

    // Раскидываем элементы по Новостям
    foreach($arSections as $sect) {
        foreach($sect['UF_NEWS_LINK'] as $newsID) {
            if(isset($arNews[$newsID])) {
                $arNews[$newsID]['SECTIONS'][$sect['ID']] = $sect;
            }
        }
    }

    // Убираем новости, без товаров
    foreach ($arNews as $k => $news) {
        $toDel = true;
        foreach ($news['SECTIONS'] as $sect) {
            if(count($sect['ITEMS'])) {
                $toDel = false;
                break;
            }
        }
        if($toDel) {
            unset($arNews[$k]);
        }
    }

    // Формируем выходной массив
    $arResult['NEWS'] = $arNews;
    $arResult['COUNT'] = $count;
    $arResult["NAV_STRING"] = $navString;


    // Определяем ключи для кэширования
    $this->setResultCacheKeys(array(
        "COUNT",
    ));
    $this->includeComponentTemplate();
    //$this->endResultCache(); // [ex2-107]
}
// Устанавливаем заголовок страницы
$APPLICATION->SetTitle(GetMessage('PRODUCT_COUNT') . ' [' . $arResult['COUNT'] . ']');



// [ex2-107]
// Включаем Тегированый кэш в init.php
// define("BX_COMP_MANAGED_CACHE", true);