<?php

class EventHandlers {

    // обработчик события "OnBeforeIBlockElementUpdate"
    function OnBeforeIBlockElementUpdateHandler(&$arFields) {

        $iblock_id = intval($arFields['IBLOCK_ID']);

        switch($iblock_id) {
            // Инфоблок Продукция
            case PRODUCT_IBLOCK_ID:
                if($arFields['ACTIVE'] != 'Y') {
                    $filter = [
                        'IBLOCK_ID' => intval($arFields['IBLOCK_ID']),
                        'ID' =>  intval($arFields['ID']),
                    ];
                    $fields = [
                        'ID',
                        'IBLOCK_ID',
                        'SHOW_COUNTER',
                        'ACTIVE'
                    ];
                    $elem = [];
                    $ob = CIBlockElement::GetList([], $filter, false, false, $fields);
                    if($el = $ob->Fetch()) {
                        $elem = $el;
                    }

                    if($elem['ACTIVE'] = 'Y' && intval($elem['SHOW_COUNTER']) > 2) {
                        global $APPLICATION;
                        $APPLICATION->throwException("Товар невозможно деактивировать, у него ". $elem['SHOW_COUNTER'] ." просмотра(ов)");
                        return false;
                    }
                }
                break;
            default:
                break;
        }
    }
}