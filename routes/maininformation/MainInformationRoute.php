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

    function triggerActionForShowItHelpMenu($chatID, $companyId) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getReplyForEnterItHelpInlineMenu($companyId);
        $keyboard = $keyboards::getItHelpMenuInlineKeyboard($companyId);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForProceedErpAnd1CFeedback($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getFeedbackText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedHardwareFeedback($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getFeedbackText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedResourcesFeedback($chatID, $firstname) {
         $constants = new constants();
         $reply = $constants::getFeedbackText($firstname);
         sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedOtherFeedback($chatID, $firstname) {
         $constants = new constants();
         $reply = $constants::getFeedbackText($firstname);
         sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendFeedbackConfirmation($chatID) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getReplyForFeedbackSending();
        $keyboard = $keyboards::getFeedbackSendingInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSendFeedback($chatID) {
        $constants = new constants();
        $reply = $constants::getReplyForFeedbackIsSent();
        sendMessage($chatID, $reply, null);
    }
}