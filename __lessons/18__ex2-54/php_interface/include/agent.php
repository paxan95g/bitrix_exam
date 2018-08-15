<?
function CheckUserCount() {
    global $DB;

    // Последнее время запуска агента
    $lastDate = COption::GetOptionString("main", "checkUserCount_last_start");
// Текущая дата/время
    $curDate = date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time());
// Количестов дней, прошедших с последней проверки
    $difDate = 0;
// Получаем пользователей, которые зарегистрировлись после последнего запуска Агента
    if($lastDate) {

        // округляем в большую сторону
        $difDate = ceil((MakeTimeStamp($curDate) -  MakeTimeStamp($lastDate)) / (60*60*24));
        $filter = [
            "DATE_REGISTER_1" => $lastDate,
            "DATE_REGISTER_2" => $curDate,
        ];
    } else {
        $filter = [];
    }
    $arUsers = [];
    $rsUsers = CUser::GetList($by="id", $order="desc", $filter);
    while($user = $rsUsers->Fetch()) {
        $arUsers[] = $user;
    }
// Количество пользователей, которые зарегистрировлись после последнего запуска Агента
    $countUsers = count($arUsers);

// Получаем email всех администраторов
    $arAdminsMail = [];
    $filter = [
        "GROUPS_ID" => 1,
        "ACTIVE" => "Y"
    ];
    $rsUsers = CUser::GetList($by="id", $order="desc", $filter);
    while($user = $rsUsers->Fetch()) {
        $arAdminsMail[] = $user['EMAIL'];
    }

// Отправляем письма
    $arEventFields = array(
        "DAYS" => $difDate,
        "COUNT" => $countUsers,
        "ADMIN_EMAILS" => implode(",", $arAdminsMail),
    );
    CEvent::Send("CHECK_USER_COUNT", SITE_ID, $arEventFields);

// Установим последнее время запуска агента
    COption::SetOptionString("main", "checkUserCount_last_start", $curDate);

    return "CheckUserCount();";
}