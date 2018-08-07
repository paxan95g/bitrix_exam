<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */



// Дефолтные шаблоны адресов для ЧПУ режима
$arDefaultUrlTemplates404 = array(
    "vacancy" => "",
    "detail" => "#VACANCY_ID#/",
    "resume" => "#VACANCY_ID#/resume/",
);
// Дефолтные значения переменных для ЧПУ режима
$arDefaultVariableAliases404 = array();

// Дефолтные шаблоны адресов для НЕ ЧПУ режима
$arDefaultVariableAliases = array();
// Дефолтные значения переменных для НЕ ЧПУ режима
$arComponentVariables = array(
	"SEND_RESUME", // ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
	"VACANCY_ID",
	"VACANCY_CODE",
);


// Проверка ЧПУ или не ЧПУ
if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();

	// шаблоны с учетом настроек компонента
	$arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	// переменные из url
	$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);


    // определяем, какой файл шаблона подключать
    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );
    // Определяем страницу по умолчанию
	if(!$componentPage)
	{
		$componentPage = "vacancy";
	}

	// Инициализация переменных
	CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	// Формируем $arResult
	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
	);
}
else
{
    // Разираем текущую страницу
	$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	// Инициализируем переменные
	CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

	// Определяем текущую страницу
	$componentPage = "";

    if(isset($arVariables["SEND_RESUME"]) && isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0 )
        $componentPage = "resume";
    elseif( (isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0) )
        $componentPage = "detail";
	else
		$componentPage = "vacancy";


	// Формируем $arResult
	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => array(
			"vacancy" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"detail" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["VACANCY_ID"]."=#VACANCY_ID#"),
            "resume" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["VACANCY_ID"]."=#VACANCY_ID#&SEND_RESUME"),
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}

// Подключаем шаблон
$this->includeComponentTemplate($componentPage);