<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
    <?foreach($arResult['USERS'] as $user):?>
        <li>
            [<?=$user['ID']?>] - <b><?=$user['NAME']?></b>
            <ul>
                <?foreach($user['ITEMS'] as $news):?>
                    <li><?=$news['NAME'] . ' - ' . $news['DATE_ACTIVE_FROM']?></li>
                <?endforeach?>
            </ul>
        </li>
    <?endforeach?>
</ul>