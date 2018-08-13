<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach ($arResult['SECTIONS'] as $sect):?>

    <div class="section section-<?=$sect['ID']?>">

        <a href="" class="section-open"><?=$sect['NAME']?></a>
        <ul class="section-items">
            <?foreach ($sect['ITEMS_ID'] as $key=>$id):
                $val = $arResult['ITEMS'][$id];

                $this->AddEditAction($val['ID'],$val['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($val['ID'],$val['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('FAQ_DELETE_CONFIRM', array("#ELEMENT#" => CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_NAME")))));
                ?>
                <li class="section-item" id="<?=$this->GetEditAreaId($val['ID']);?>">
                    <a name="<?=$val["ID"]?>"></a>
                    <h3><?=$val['NAME']?></h3>
                    <p><?=$val['PREVIEW_TEXT']?></p>
                    <?if($val['PROPERTY_STAZ_VALUE']):?>
                        <p>
                            <b>Стаж</b>
                            <span><?=$val['PROPERTY_STAZ_VALUE']?></span>
                        </p>
                    <?endif;?>
                    <?if($val['PROPERTY_WORT_TIME_VALUE']):?>
                        <p>
                            <b>График работы</b>
                            <span><?=$val['PROPERTY_WORT_TIME_VALUE']?></span>
                        </p>
                    <?endif;?>
                    <?if(is_array($val['PROPERTY_EDUC_VALUE'])):?>
                        <p>
                            <b>Образование</b>
                            <br>
                            <ul>
                                <?foreach($val['PROPERTY_EDUC_VALUE'] as $educItem):?>
                                    <li><?=$educItem?></li>
                                <?endforeach;?>
                            </ul>
                        </p>
                    <?endif;?>
                </li>
            <?endforeach;?>
        </ul>
    </div>
<?endforeach;?>