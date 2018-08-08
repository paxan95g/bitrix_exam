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

if($this->startResultCache())
{
    if(!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    // Получаем разделы инфоблока Продукция
    $arSections = [];
    $arFilter = ['IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"], 'ACTIVE' => 'Y', '!'.$arParams["PROPERTY_CODE"] => false];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', $arParams["PROPERTY_CODE"]];
    $ob = CIBlockSection::GetList([], $arFilter, false, $arSelect);
    while($res = $ob->Fetch()) {
        $arSections[$res['ID']] = $res;
    }

    // Получаем эементы инфоблока Продукция и раскидываем по разделам
    $count = 0;
    $arFilter = ['IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"], 'ACTIVE' => 'Y'];
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
    $arFilter = ['IBLOCK_ID' => $arParams["NEWS_IBLOCK_ID"], 'ACTIVE' => 'Y'];
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM'];
    $ob = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    while($res = $ob->Fetch()) {
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
    // Убираем Новости, в которых нет привызянных разделов
    foreach($arNews as $k =>$news) {
        if(!isset($news['SECTIONS']) || count($news['SECTIONS']) <=0) {
            unset($arNews[$k]);
        }
    }

    // Формируем выходной массив
    $arResult['NEWS'] = $arNews;
    $arResult['COUNT'] = $count;

    // Определяем ключи для кэширования
    $this->setResultCacheKeys(array(
        "COUNT",
    ));
    $this->includeComponentTemplate();
}
// Устанавливаем заголовок страницы
$APPLICATION->SetTitle(GetMessage('PRODUCT_COUNT') . ' [' . $arResult['COUNT'] . ']');