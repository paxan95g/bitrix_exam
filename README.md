GIT:
https://github.com/polyanski/bitrix_exam

git remote add origin git@github.com:polyanski/bitrix_exam.git
git push -u origin master
==============================================================================================


# Подключение query в битрикс ( CJSCore::Init(array("jquery2")); )

#  JS библиотека Bitrix Framework для отправки AJAX запросов

# $this->__component->SetResultCacheKeys(array(''));
    ИЛИ
  $obj_comp = $this->GetComponent();
  $obj_comp->SetResultCacheKeys(array(''));


# $componentPage = CComponentEngine::ParseComponentPath (комплексный. определяем какой файл шаблона подключать при ЧПУ)

# \Bitrix\Main\Loader::includeModule('iblock');

# IncludeTemplateLangFile(__FILE__); (подключаем ланг файлы)

# $ob->SetUrlTemplates($arParams["DETAIL_LINK"]); (Устанавливаем шаблон для детальной ссылки)

#  $arResult["NAV_STRING"] = $ob->GetPageNavString("Страницы");  (Формируем постраничную навигацию)

# define("BX_COMP_MANAGED_CACHE", true);   (Включаем тегированый кэш. Для взможности удаления по тегу)

# global $CACHE_MANAGER;
# $CACHE_MANAGER->RegisterTag('iblock_id_3');

# date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time());   (Дата в формате текущего сайта)

# COption::SetOptionString  /  COption::GetOptionString   (Установка/получание параметра модуля)

# Событие BeforeIndex   (Перед индексацией элементов инфоблока)

# json_encode()    (php)

# LocalRedirect     (битрикс)
