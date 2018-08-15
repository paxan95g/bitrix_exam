<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// [ex2-104]
// Чтобы работал BX ля неавторизованых пользователей
if(!$USER->IsAuthorized()) {
    CJSCore::Init();
}

// Без ajax режима
if($_GET['TYPE'] == 'RESPONCE') {
    // Если есть ID - то все хорошо
    if(isset($_GET['ID']) && intval($_GET['ID']) > 0) {?>
        <script>
            var textElem = document.getElementById("report__responce");
            textElem.innerText = 'Ваше мнение учтено, №' + '<?=intval($_GET['ID'])?>';
        </script>
    <?} else {?>
        <script>
            var textElem = document.getElementById("report__responce");
            textElem.innerText = "Ошибка!";
        </script>
    <?}
} else {
    // ajax режим
    if(isset($_GET['ID'])) {

        if(CModule::IncludeModule('iblock')) {

            // Определяем пользователя
            if($USER->IsAuthorized()) {
                $user = "[".$USER->GetID()."] (".$USER->GetLogin().") ".$USER->GetFullName();
            } else {
                $user = 'Не авторизован';
            }

            // Добавляю элемент в инфоблок
            $el = new CIBlockElement;
            $arLoadProductArray = Array(
                "IBLOCK_ID" => 8,
                "NAME" => "Новость " . intval($_GET['ID']),
                "ACTIVE" => "Y",
                "PROPERTY_VALUES"=> [
                    'USER' => $user,
                    'NEWS_ID' => intval($_GET['ID'])
                ],
                "ACTIVE_FROM" => date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time())
            );
            if($REPORT_ID = $el->Add($arLoadProductArray)) {

                if($_GET['TYPE'] == 'AJAX') {
                    // Сбрасываем буфер страницы
                    $GLOBALS['APPLICATION']->RestartBuffer();
                    // Формируем данные в формате JSON
                    echo json_encode(['ID' => $REPORT_ID]);
                    die();
                } elseif($_GET['TYPE'] == 'GET') {
                    // Редиректим, добавляя параметр RESPONCE и ID жалобы
                    LocalRedirect($APPLICATION->GetCurPage().'?TYPE=RESPONCE&ID='.$REPORT_ID);
                }
            } else {
                // Ошибка
                LocalRedirect($APPLICATION->GetCurPage().'?TYPE=RESPONCE');
            }
        }
    }
}
// [ex2-104] end