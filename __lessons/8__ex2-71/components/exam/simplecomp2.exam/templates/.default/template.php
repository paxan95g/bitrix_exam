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

<b>Каталог:</b>
<ul>
    <?foreach ($arResult['FIRMS'] as $firm):?>
        <li>
            <b><?=$firm['NAME']?></b>
            <?foreach ($firm['ITEMS'] as $item):?>
                <ul>
                    <li>
                        <a href="<?=$item['DETAIL_PAGE_URL']?>">
                            <?=$item['NAME'] . ' - ' . $item['PROPERTY_PRICE_VALUE'] . ' - ' . $item['PROPERTY_MATERIAL_VALUE'] . ' - ' . $item['PROPERTY_ARTNUMBER_VALUE']?>
                        </a>

                    </li>
                </ul>
            <?endforeach;?>
        </li>
    <?endforeach;?>
</ul>