<?php

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class EventHandlers {

    // обработчик события "OnBeforeEventSend"
    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields) {

        switch($event) {
            case FEEDBACK_EVENT_NAME:
                global $USER;

                if($USER->IsAuthorized()) {
                    $arFields['AUTHOR'] = $USER->GetID()." (".$USER->GetLogin().") ".$USER->GetFullName() . Loc::getMessage('DATA_FROM_FORM') . $arFields['AUTHOR'];
                } else {
                    $arFields['AUTHOR'] = Loc::getMessage('NO_AUTHORIZE') . $arFields['AUTHOR'];
                }
                CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "SEND_MAIL",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => Loc::getMessage('CHANGE_AUTHOR_FIELD') . $arFields['AUTHOR'],
                ));
                break;
            default:
                break;
        }
    }
}