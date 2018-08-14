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
$filterLink = $APPLICATION->GetCurPage().'?F';
?>

<?=time();?>
<br>
<b>Каталог:</b>
<ul>
    <?foreach($arResult['NEWS'] as $news):?>
        <?
        $this->AddEditAction($news['ID'], $news['EDIT_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($news['ID'], $news['DELETE_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
    <li id="<?=$this->GetEditAreaId($news['ID']);?>">
        <b><?=$news['NAME']?></b> - <?=$news['DATE_ACTIVE_FROM']?>
        (<? $sign = '';
        foreach($news['SECTIONS'] as $sect):?>
            <?=$sign.$sect['NAME'];
            if(!$sign) $sign = ', ';
            ?>
        <?endforeach;?>)
        <ul>
            <?foreach($news['SECTIONS'] as $sect):?>
                <?foreach($sect['ITEMS'] as $item):?>
                    <li><?=$item['NAME'] . ' - ' . $item['PROPERTY_PRICE_VALUE'] . ' - ' . $item['PROPERTY_MATERIAL_VALUE'] . ' - ' . $item['PROPERTY_ARTNUMBER_VALUE']?></li>
                <?endforeach;?>
            <?endforeach;?>
        </ul>
    </li>
    <?endforeach;?>
</ul>

<?if($arResult["NAV_STRING"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>