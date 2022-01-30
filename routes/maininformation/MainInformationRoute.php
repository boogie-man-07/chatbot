<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class MainInformationRoute {

    function triggerActionForEnterMainInformationMenu($chatID, $companyId) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getReplyForEnterMainInformationMenu();
        $keyboard = $keyboards::getMainInformationMenuKeyboard($companyId);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForShowHowToNavigateToOffice($chatID, $companyId) {
        $constants = new constants();
        $reply = $constants::getRouteText($companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToSkolkovo($chatID) {
        $constants = new constants();
        $reply = $constants::getSkolkovoMapUrl();
        sendPhoto($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToOskol($chatID) {
        $constants = new constants();
        $reply = $constants::getOskolMapUrl();
        sendPhoto($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToSaratov($chatID) {
        $constants = new constants();
        $reply = $constants::getSaratovMapUrl();
        sendPhoto($chatID, $reply, null);
    }
}