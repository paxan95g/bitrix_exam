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

// Не используем существующий кэш, если включен Фильтр
if(isset($_REQUEST['F'])) {
    $arParams['FILTER_F'] = 'N';
}

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

        // Формируем массив со списков id Новостей, для дальнейшей выборки
        foreach($res[$arParams["PROPERTY_CODE"]] as $newsID) {
            if(!isset($arNewsID[$newsID])) {
                $arNewsID[$newsID] = $newsID;
            }
        }
        $arSections[$res['ID']] = $res;
    }

    // Получаем эементы инфоблока Продукция и раскидываем по разделам
    // Дополнительно фильтруем при наличии параметра F
    $count = 0;
    $arFilter = [
        'IBLOCK_ID' => $arParams["CATALOG_IBLOCK_ID"],
        'ACTIVE' => 'Y',
    ];
    // Дополнительные параметры фильтрации, если включен Фильтр
    if(isset($_REQUEST['F'])) {
        $arFilter[] = [
            'LOGIC' => 'OR',
            [
                '<=PROPERTY_PRICE' => 1700,
                'PROPERTY_MATERIAL' => 'Дерево, ткань',
            ],
            [
                '<PROPERTY_PRICE' => 1500,
                'PROPERTY_MATERIAL' => 'Металл, пластик',
            ],
        ];
    }

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
    $arSelect = ['ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM'];
    $ob = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    while($res = $ob->Fetch()) {

        //  Эрмитаж
        $arButtons = CIBlock::GetPanelButtons(
            $res["IBLOCK_ID"],
            $res["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        //pre($arButtons, true);
        $res["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $res["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

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

    // Определяем ключи для кэширования
    $this->setResultCacheKeys(array(
        "COUNT",
    ));

    // Не создаем кэш, если включен Фильтр
    if(isset($_REQUEST['F'])) {
        $this->abortResultCache();
    }
    $this->includeComponentTemplate();
}
// Устанавливаем заголовок страницы
$APPLICATION->SetTitle(GetMessage('PRODUCT_COUNT') . ' [' . $arResult['COUNT'] . ']');

//  Эрмитаж
if(
    $arParams["CATALOG_IBLOCK_ID"] > 0
    && $USER->IsAuthorized()
    && !$APPLICATION->GetShowIncludeAreas()
    && Loader::includeModule("iblock")
)
{

    $arButtons = CIBlock::GetPanelButtons($arParams["CATALOG_IBLOCK_ID"], 0, 0, array("SECTION_BUTTONS"=>false));

    pre($arButtons);
    $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
}