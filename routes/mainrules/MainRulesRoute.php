<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class MainRulesRoute {

    function triggerActionForEnterMainRulesMenu($chatID) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getReplyForEnterMainRulesMenu();
        $keyboard = $keyboards::getMainRulesMenuKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetMeetingInfo($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getMeetingsRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetPhoneCallsInfo($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getPhoneConversationsRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetOfficeRulesInfo($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getOfficeRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetAppearanceInfo($chatID, $firstname) {
        $constants = new constants();
        $reply = $constants::getAppearanceRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForNavigateBack($chatID) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getNavigateBackText();
        $keyboard = $keyboards::mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }
}

?>