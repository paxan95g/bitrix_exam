<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
 * за основу взят компонент furniture.vacancies
 *
 * Взято из news.list:
 * ** формирование массива с ключами кеша
 * ** формирование кнопок для поддержки технологии «Эрмитаж»
 * */
use Bitrix\Main\Loader;


// Обработка параметров
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
if($arParams['IBLOCK_ID']<=0)
	return;

if(isset($arParams["IBLOCK_TYPE"]) && $arParams["IBLOCK_TYPE"]!='')
	$arFilter['IBLOCK_TYPE'] = $arParams["IBLOCK_TYPE"];

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

//SELECT
$arSelect = [
    'ID',
    'IBLOCK_ID',
    'NAME',
    'IBLOCK_SECTION_ID',
    'PREVIEW_TEXT',
    'PROPERTY_STAZ',
    'PROPERTY_WORT_TIME',
    'PROPERTY_EDUC'
];
//WHERE
$arFilter = Array(
	'IBLOCK_ID' => $arParams["IBLOCK_ID"],
	'ACTIVE' => 'Y',
	'IBLOCK_ACTIVE' => 'Y',
);
//ORDER BY
$arOrder = Array(
	'IBLOCK_SECTION_ID' => 'ASC',
	'ID' => 'DESC',
);

$arAddCacheParams = array(
	"MODE" => $_REQUEST['bitrix_show_mode']?$_REQUEST['bitrix_show_mode']:'view',
	"SESS_MODE" => $_SESSION['SESS_PUBLIC_SHOW_MODE']?$_SESSION['SESS_PUBLIC_SHOW_MODE']:'view',
);


// Начало кеширования
if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arFilter, $arAddCacheParams)))
{
    if(!Loader::includeModule("iblock"))  {
        $this->abortResultCache();
        return;
    }


    /* Получаем список разделов */
    $arSections = [];




    $arFilter = Array('IBLOCK_ID'=> $arParams["IBLOCK_ID"], 'GLOBAL_ACTIVE'=>'Y');
    $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
    while($ar_result = $db_list->GetNext()) {
        $arSections[$ar_result['ID']] = $ar_result;
    }
    $arResult['SECTIONS'] = $arSections;
    $arResult['SECTIONS']['UNSECTION']['NAME'] = 'Разное';

    /* Получаем элементы */
	$arResult['ITEMS'] = Array();
	$arItems = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

	while($arResItems = $arItems->Fetch()){

	    // Формирование кнопок для поддержки технологии «Эрмитаж»
        $arButtons = CIBlock::GetPanelButtons(
            $arResItems["IBLOCK_ID"],
            $arResItems["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        $arResItems["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arResItems["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
		
		$arResult['ITEMS'][$arResItems['ID']] = $arResItems;

		if( isset($arResult['SECTIONS'][$arResItems['IBLOCK_SECTION_ID']]) ) {
            $arResult['SECTIONS'][$arResItems['IBLOCK_SECTION_ID']]['ITEMS_ID'][] = $arResItems['ID'];
        } else {
            $arResult['SECTIONS']['UNSECTION']['ITEMS_ID'][] = $arResItems['ID'];
        }

	}

	if(!count($arResult['SECTIONS']['UNSECTION']['ITEMS_ID'])) {
	    unset($arResult['SECTIONS']['UNSECTION']);
    }


	if(count($arResult['ITEMS'])<=0)
	{
		$this->AbortResultCache();
		@define("ERROR_404", "Y");
		return;
	}

	// формирование массива с ключами кеша
    $this->setResultCacheKeys(array(
        "SECTIONS",
        "ITEMS"
    ));

	// подключение шаблона компонента
	$this->IncludeComponentTemplate();
}
if($USER->IsAuthorized())
{
	if(
		$APPLICATION->GetShowIncludeAreas()
		|| $arParams["SET_TITLE"]
		|| isset($arResult[$arParams["BROWSER_TITLE"]])
	)
	{
		if(Loader::IncludeModule("iblock")) {
			
			$arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK_ID"], 0, $arParams["SECTION_ID"]);

			foreach ($arButtons as $key => $arButton){
				unset($arButtons[$key]['add_section']);
				unset($arButtons[$key]['edit_section']);
				unset($arButtons[$key]['delete_section']);  
			}

			if($APPLICATION->GetShowIncludeAreas())
				$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
		}
	}
}
?>