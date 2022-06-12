<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class MainRulesRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForEnterMainRulesMenu($chatID) {
        $reply = $this->constants::getReplyForEnterMainRulesMenu();
        $keyboard = $this->keyboards::getMainRulesMenuKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetMeetingInfo($chatID, $firstname) {
        $reply = $this->constants::getMeetingsRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetPhoneCallsInfo($chatID, $firstname) {
        $reply = $this->constants::getPhoneConversationsRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetOfficeRulesInfo($chatID, $firstname) {
        $reply = $this->constants::getOfficeRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetAppearanceInfo($chatID, $firstname) {
        $reply = $this->constants::getAppearanceRulesText($firstname);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForNavigateBack($chatID) {
        $reply = $this->constants::getNavigateBackText();
        $keyboard = $this->keyboards::mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }
}

?>