<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class ValuesRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForGetWelcomeValue($chatID, $firstname, $inlineValue) {
        $reply = $this->constants::getWelcomeValueText($firstname);
        $keyboard = $this->keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetFirstValue($chatID, $companyId, $inlineValue) {
        sendSticker($chatID, $this->constants::getTruthAndFactsValueSticker($companyId));
        $reply = $this->constants::getTruthAndFactsValueText();
        $keyboard = $this->keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetSecondValue($chatID, $companyId, $inlineValue) {
        sendSticker($chatID, $this->constants::getOpennessAndTransparencyValueSticker($companyId));
        $reply = $this->constants::getOpennessAndTransparencyValueText();
        $keyboard = $this->keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetThirdValue($chatID, $companyId, $inlineValue) {
        sendSticker($chatID, $this->constants::getWorkIsAFavoriteAffairValueSticker($companyId));
        $reply = $this->constants::getWorkIsAFavoriteAffairValueText();
        $keyboard = $this->keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetFourthValue($chatID, $companyId, $inlineValue) {
        sendSticker($chatID, $this->constants::getMindedTeamValueSticker($companyId));
        $reply = $this->constants::getMindedTeamValueText();
        $keyboard = $this->keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetLastValue($chatID, $firstname) {
        $reply = $this->constants::getFinalValueText($firstname);
        $keyboard = $this->keyboards::mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }
}