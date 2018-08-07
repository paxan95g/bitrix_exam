<?php

function old_actions_agent() {
    CModule::IncludeModule('iblock');

    $arResult = [];
    $arSelect = Array("ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", '!ACTIVE_DATE' => 'Y');
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    $count = 0;
    while($ob = $res->GetNextElement()) {
        $count++;
    }

    if($count) {

        CEventLog::Add(array(
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => "OLD_ACTIONS",
            "MODULE_ID" => "main",
            "DESCRIPTION" => "Количество просроченных акций: $count",
        ));

        // получаем email
        $arEmails = [];
        $filter = [
            "ACTIVE"              => "Y",
            "GROUPS_ID"           => Array(1)
        ];
        $emailsResult = CUser::GetList(($by="personal_country"), ($order="desc"), $filter);

        while ($ob = $emailsResult->GetNext()) {
            $arEmails = $ob['EMAIL'];
        }

        $arEventFields = array(
            'OLD_ACT_COUNT' => $count,
            'EMAILS' => $arEmails
        );
        CEvent::Send("OLD_ACTIONS", SITE_ID, $arEventFields);
    }

    return 'old_actions_agent();';
}