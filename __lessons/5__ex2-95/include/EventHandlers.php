<?php

class EventHandlers {

    // обработчик события "OnBuildGlobalMenu"
    function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu) {

        global $USER;
        $arGroups = $USER->GetUserGroupArray();

        if(in_array(MANAGER_GROUP_ID, $arGroups) && !$USER->IsAdmin()) {

            // Удаляем Пункты меню верхнего уровня
            foreach($aGlobalMenu as $menuItem) {
                if($menuItem['items_id'] != 'global_menu_content') {
                    unset($aGlobalMenu[$menuItem['items_id']]);
                }
            }

            // Удаляем Пункты меню нижнего уровня
            foreach($aModuleMenu as $key => $menuItem) {
                if($menuItem['parent_menu'] != 'global_menu_content' || $menuItem['items_id'] == 'menu_iblock') {
                    unset($aModuleMenu[$key]);
                }
            }
        }
    }
}