<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
global $USER;

// Обрабатываем входящие параметры
$arParams["NEWS_IBLOCK_ID"] = intval($arParams["NEWS_IBLOCK_ID"]);
$authorCode = trim($arParams["AUTHOR_CODE"]);
$userAuthorTypeCode = trim($arParams["USER_AUTHOR_TYPE_CODE"]);
$currentUserID = $USER->GetID();
if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;
// Строка, выполняющая роль идентификатора поиска кэша
$cacheString = 'USER_ID_'.$currentUserID;

// Если пользователь авторизован
if($currentUserID) {
    // Кэшируемая область для текущего пользователя
    if ($this->startResultCache(false, $cacheString)) {
        // Подключаем модуль iblock
        if (!Loader::includeModule("iblock")) {
            ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
            return;
        }

        if ($arParams["NEWS_IBLOCK_ID"] > 0) {

            // Получаем список пользователей
            $arUsers = [];
            $arOrderUser = array('ID');
            $sortOrder = "asc";
            $arFilterUser = array("ACTIVE" => "Y");
            $arParameters = [
                'SELECT' => [
                    $userAuthorTypeCode,
                ],
                'FIELDS' => [
                    'ID',
                    'LOGIN',
                    'NAME'
                ]
            ];
            $rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, $arParameters);
            while ($arUser = $rsUsers->GetNext()) {
                $arUsers[$arUser['ID']] = $arUser;
            }

            // Определяем тип группы текущего пользователя
            if (isset($arUsers[$currentUserID])) {
                $curUserTypeGroup = $arUsers[$currentUserID][$userAuthorTypeCode];
            }
            // Убираем всех пользователей, кроме тех, кто состоит в той же группе, что и текущий
            foreach ($arUsers as $user) {
                if ($user['ID'] == $currentUserID || $user[$userAuthorTypeCode] != $curUserTypeGroup) {
                    unset($arUsers[$user['ID']]);
                }
            }

            // Получаем список Новостей
            $count = 0;
            $arSort = array("NAME" => "ASC");
            $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_" . $authorCode);
            $arFilter = array(
                "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
                "ACTIVE" => "Y"
            );
            $rsElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
            while ($arElement = $rsElements->GetNext()) {

                // Если в авторстве данной новости присутствует текущий пользователь, то не выводим ее
                if (in_array($currentUserID, $arElement['PROPERTY_' . $authorCode . '_VALUE'])) {
                    continue;
                }
                // Добавляем привязанные новости пользователям
                foreach ($arElement['PROPERTY_' . $authorCode . '_VALUE'] as $userID) {
                    if (isset($arUsers[$userID])) {
                        $arUsers[$userID]['ITEMS'][$arElement['ID']] = $arElement;
                        $count++;
                    }
                }
            }

            // Заполняем массив $arResult
            $arResult['USERS'] = $arUsers;
            $arResult['COUNT'] = $count;
        }

        // Выбираем параматры для кэширования
        $this->setResultCacheKeys(array(
            'COUNT'
        ));
        // Подключаем шаблон
        $this->includeComponentTemplate();
    }
    $APPLICATION->SetTitle(GetMessage('NEWS_COUNT') . '[' . $arResult['COUNT'] . ']');
} else {
    // Пишем, что пользователь не авторизован
    $APPLICATION->SetTitle(GetMessage('USER_NOT_AUTHORIZED'));
}