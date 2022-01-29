<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class ValuesRoute {

    function triggerActionForGetWelcomeValue($chatID, $firstname, $inlineValue) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getWelcomeValueText($firstname);
        $keyboard = $keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetFirstValue($chatID, $companyId, $inlineValue) {
        $constants = new constants();
        $keyboards = new keyboards();
        sendSticker($chatID, $constants::getTruthAndFactsValueSticker($companyId));
        $reply = $constants::getTruthAndFactsValueText();
        $keyboard = $keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetSecondValue($chatID, $companyId, $inlineValue) {
        $constants = new constants();
        $keyboards = new keyboards();
        sendSticker($chatID, $constants::getOpennessAndTransparencyValueSticker($companyId));
        $reply = $constants::getOpennessAndTransparencyValueText();
        $keyboard = $keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetThirdValue($chatID, $companyId, $inlineValue) {
        $constants = new constants();
        $keyboards = new keyboards();
        sendSticker($chatID, $constants::getWorkIsAFavoriteAffairValueSticker($companyId));
        $reply = $constants::getWorkIsAFavoriteAffairValueText();
        $keyboard = $keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetFourthValue($chatID, $companyId, $inlineValue) {
        $constants = new constants();
        $keyboards = new keyboards();
        sendSticker($chatID, $constants::getMindedTeamValueSticker($companyId));
        $reply = $constants::getMindedTeamValueText();
        $keyboard = $keyboards::getValueKeyboard($inlineValue);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetLastValue($chatID, $firstname) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getFinalValueText($firstname);
        $keyboard = $keyboards::mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }
}