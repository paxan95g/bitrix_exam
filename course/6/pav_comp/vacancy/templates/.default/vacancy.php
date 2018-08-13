<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?$APPLICATION->IncludeComponent(
    "pav_comp:vacancy.list",
    "vacancy",
    array(
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    ),
    $component
);?>