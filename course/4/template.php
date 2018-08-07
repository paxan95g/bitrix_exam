<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/*
ТЕЛО ШАБЛОНА
*/?>

<?// список материалов для раздела каталога (ля вывода в сайтбаре)
if(is_array($arResult['MATERIALS'])):?>

    <?$this->SetViewTarget('product_material_list');?>
    <div class="content-block">
        <div class="content-block-inner">
            <?foreach($arResult['MATERIALS'] as $item):?>
                <p><?=$item['PROPERTY_MATERIAL_VALUE']?> (<?=$item['CNT']?>)</p>
            <?endforeach;?>
        </div>
    </div>
    <?$this->EndViewTarget();?>
<?endif;



/*
 *  Выносим в header.php
 */
$APPLICATION->ShowViewContent('product_material_list');
?>