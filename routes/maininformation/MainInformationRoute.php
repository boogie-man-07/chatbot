<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class MainInformationRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForEnterMainInformationMenu($chatID, $companyId) {
        $reply = $this->constants::getReplyForEnterMainInformationMenu();
        $keyboard = $this->keyboards::getMainInformationMenuKeyboard($companyId);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForShowHowToNavigateToOffice($chatID, $companyId) {
        $reply = $this->constants::getRouteText($companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToSkolkovo($chatID) {
        $reply = $this->constants::getSkolkovoMapUrl();
        sendPhoto($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToOskol($chatID) {
        $reply = $this->constants::getOskolMapUrl();
        sendPhoto($chatID, $reply, null);
    }

    function triggerActionForShowNavigationSchemeToSaratov($chatID) {
        $reply = $this->constants::getSaratovMapUrl();
        sendPhoto($chatID, $reply, null);
    }

    function triggerActionForShowItHelpMenu($chatID, $companyId, $email) {
        $reply = $this->constants::getReplyForEnterItHelpInlineMenu($companyId, $email);
        $keyboard = $this->keyboards::getItHelpMenuInlineKeyboard($companyId, $email);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForProceedErpAnd1CFeedback($chatID, $firstname) {
        $reply = $this->constants::getFeedbackText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedHardwareFeedback($chatID, $firstname) {
        $reply = $this->constants::getFeedbackText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedResourcesFeedback($chatID, $firstname) {
         $reply = $this->constants::getFeedbackText($firstname);
         sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedOtherFeedback($chatID, $firstname) {
         $reply = $this->constants::getFeedbackText($firstname);
         sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendFeedbackConfirmation($chatID) {
        $reply = $this->constants::getReplyForFeedbackSending();
        $keyboard = $this->keyboards::getFeedbackSendingInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSendFeedback($chatID) {
        $reply = $this->constants::getReplyForFeedbackIsSent();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForADSuccessfulActivation($chatID) {
        $reply = $this->constants::getReplyForADSuccessfulActivation();
        sendMessage($chatID, $reply, null);
    }

    function removeFormats($prefix, $text) {
        $prefix = str_replace(array("\r\n", "\n", "\r", "\t", " "), ''. $prefix);
        $text = str_replace(array("\r\n", "\n", "\r", "\t"), ''. $text);
        return substr($prefix.$text, 0, 150);
    }
}