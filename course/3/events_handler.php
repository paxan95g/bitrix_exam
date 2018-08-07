<?php

// регистрируем обработчики
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EventHandlersClass", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("EventHandlersClass", "OnBeforeIBlockElementDeleteHandler"));
AddEventHandler("main", "OnBeforeUserUpdate", Array("EventHandlersClass", "OnBeforeUserUpdateHandler"));

// Класс Хэлпер
class EventHandlersClass {


    /*******  Урок 3 задача 1 *******
    Реализовать проверку, если деактивируется элемент ИБ Новости, созданный меньше, чем 3 дня
    назад, то отменять деактивацию и выводить предупреждение «Вы деактивировали свежую
    новость». Обратите внимание, что необходимо проверять текущее состояние новости. Отменять
    любые изменения неактивной новости не требуется.
    */
    function OnBeforeIBlockElementUpdateHandler(&$arFields) {

        $iblock = (int)$arFields['IBLOCK_ID'];

        switch($iblock) {
            case 1:
                if ($arFields['ACTIVE'] != 'Y') {


                    $arSelect = [
                        'DATE_CREATE_UNIX',
                    ];
                    $arFilter = [
                        "IBLOCK_ID" => IntVal($iblock),
                        'ID' => IntVal($arFields['ID']),
                        'ACTIVE_DATE' => 'Y',
                        'ACTIVE' => 'Y',
                    ];
                    $arFieldsItem = [];
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                    if ($ob = $res->GetNextElement()) {
                        $arFieldsItem = $ob->GetFields();

                        // определяем, что новость старше 3 дней
                        $arFieldsItem['DAY_OLD'] = intval((time() - intval($arFieldsItem['DATE_CREATE_UNIX'])) / (60 * 60 * 24));

                        if (intval($arFieldsItem['DAY_OLD']) < 3) {
                            global $APPLICATION;
                            $APPLICATION->throwException("Вы деактивировали свежую новость");
                            return false;
                        }
                    }
                }
                break;
            default;
                break;
        }
    }



    /*
    Реализовать проверку – при удалении товара из каталога, проверять: если количество
    просмотров товара (поле SHOW_COUNTER) больше 1, то отменять удаление, деактивировать
    товар и выводить в административный раздел соответствующее уведомление с указанием
    количества просмотров.
     */
    function OnBeforeIBlockElementDeleteHandler($ID) {

        $arSelect = [
            'SHOW_COUNTER',
        ];
        $arFilter = [
            "IBLOCK_ID" => 2,
            'ID' => $ID
        ];
        $arFieldsItem = [];
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($ob = $res->GetNextElement()) {
            $arFieldsItem = $ob->GetFields();
        }

        if(intval($arFieldsItem['SHOW_COUNTER']) > 0) {

            global $APPLICATION;
            global $USER;

            $el = new CIBlockElement;
            $arLoadProductArray = Array(
                "MODIFIED_BY"    => $USER->GetID(),
                "ACTIVE"         => 'N',
            );

            if ( !$res = $el->Update($ID, $arLoadProductArray) ) {
                $APPLICATION->throwException($el->LAST_ERROR);
                return false;
            }
            /*
             * ВАЖНО!
             * Вносим изменения в базу, до того, как будет выброшено исключения.
             */
            $GLOBALS['DB']->Commit();

            $APPLICATION->throwException("Количество просмотров элемента: ". $arFieldsItem['SHOW_COUNTER'] .". Элемент деактивирован.");
            return false;
        }
    }



    /*
    При добавлении пользователя в группу «Контент-редакторы» - уведомлять об этом на email
    других пользователей группы Контент-редакторы.

    Важно! Событие должно срабатывать только при переводе пользователя в эту группу, при других
    изменениях пользователей, уже состоящих в этой группе, событие обрабатываться не должно.
    Пользователи в эту группу должны попадать не сразу после регистрации, а только «ручным»
    перевод администратором.
     */
    function OnBeforeUserUpdateHandler(&$arFields) {

       // pre($arFields, true);

        // проверяем, добавляется ли пользователь в групу Контент-редактор
        $toContent = false;
        foreach($arFields['GROUP_ID'] as $group) {
            if($group['GROUP_ID'] == 5) {
                $toContent = true;
                break;
            }
        }
        // Если пользователь добавляется
        if($toContent) {
            // Проверяем, состоит ли пользователь в групе Контент-редактор
            $isContent = false;
            if( in_array(5, CUser::GetUserGroup(intval($arFields['ID']))) ) {
                $isContent = true;
            }
            // Если не состоит
            if(!$isContent) {

                // получаем емайл всех контент-редакторов и отправляем им письмо
                $arEmailContent = [];

                $filter = Array("GROUPS_ID" => Array(5));
                $rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
                while ($arUser = $rsUsers->GetNext()) {
                    $arEmailContent[] = $arUser['EMAIL'];
                }

                if(count($arEmailContent) > 0) {
                    $arEventFields = array(
                        "CONTENT_EMAILS" => implode(', ', $arEmailContent),
                    );
                    CEvent::Send("NEW_CONTENT", SITE_ID, $arEventFields);
                }
            }
        }
    }
}