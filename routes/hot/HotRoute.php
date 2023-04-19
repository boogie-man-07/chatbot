<?php

# Created by Murad Adygezalov
# Date: 19.04.2023
# Time: 12:46

class HotRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function isHotPhrase($text) {
        return mb_strtolower($text) === 'отпуск';
    }

    function proceedIfHotDialog($chatID, $text) {

        if ($this->isHotPhrase($text)) {
            $phrase = mb_strtolower($text); sendMessage($chatID, $phrase, null); exit;
            switch ($phrase) {
                case 'отпуск':
                    $this->triggerActionForGetMyVacationInformation($chatID);
                    exit;
            }
        }
    }

    function triggerActionForGetMyVacationInformation($chatID) {
        $reply = $this->constants->getVacationInformationText();
        $keyboard = $this->keyboards->getVacationInformationKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }
}