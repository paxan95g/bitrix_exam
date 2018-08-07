<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/*
На страницах каталога выводить в левую колонку список материалов (значения свойства
материал) товаров данного раздела. Рядом с каждым материалом в скобках указать количество
товаров, изготовленных из него.
 */
$arMaterial = [];

$arSelect = Array("PROPERTY_MATERIAL");
$arFilter = Array("IBLOCK_ID"=>2, "IBLOCK_SECTION_ID" => $arResult['ORIGINAL_PARAMETERS']['SECTION_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
// группируем по материалам. (убираются дубли и добавляется поле 'CNT' с количеством элементов)
$arGroupBy = [
    "PROPERTY_MATERIAL"
];
$res = CIBlockElement::GetList(Array(), $arFilter, $arGroupBy, false, $arSelect);
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arMaterial[] = $arFields;
}

$arResult['MATERIALS'] = $arMaterial;
?>