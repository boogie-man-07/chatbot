<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class SalaryRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForShowSalaryMenu($chatID) {
        $reply = $this->constants->getReplyForEnterSalaryMenu();
        $keyboard = $this->keyboards->getSalaryMenuKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetMainSalaryInformation($chatID, $companyId) {
        $reply = $this->constants->getReplyForMainSalaryInformation($companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetPaymentDatesInformation($chatID, $companyId) {
        $reply = $this->constants->getPaymentText($companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetApplicationsInformation($chatID, $firstname) {
        $reply = $this->constants->getApplicationsText($firstname);
        $keyboard = $this->keyboards->getApplicationMenuInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForRegularApplicationPreparations($chatID, $firstname, $companyId) {
        $reply = $this->constants->getReplyForApplicationPreparations($firstname, $companyId);
        $keyboard = $this->keyboards->getApplicationPreparationsInlineKeyboard($companyId);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForPostponedApplicationPreparations($chatID, $firstname, $companyId) {
        $reply = $this->constants->getReplyForPostponedApplicationPreparations($firstname, $companyId);
        $keyboard = $this->keyboards->getPostponedApplicationPreparationsInlineKeyboard($companyId);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForRegularVacationStartPreparations($chatID) {
        $reply = $this->constants->getReplyForRegularVacationStartPreparations();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSetRegularVacationEndDate($chatID) {
        $reply = $this->constants->getSetRegularVacationEndDateText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendRegularVacationForm($chatID) {
        $reply = $this->constants->getSendRegularVacationFormText();
        $keyboard = $this->keyboards->getSendRegularVacationFormInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSetRegularVacationAcademicReason($chatID) {
        $reply = $this->constants->getRegularVacationAcademicReasonText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendRegularVacationFormResult($chatID, $firstname, $companyId) {
        $reply = $this->constants->getSentRegularVacationFormResultText($firstname, $companyId);
        sendMessage($chatID, $reply, null);
    }

    function isCorrectDateFormat($text) {
        return preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/', $text);
    }

    function isCorrectVacationDurationFormat($text) {
        return preg_match('/[0-9]/', $text);
    }

    function isDateNotInPast($text) {
        $date = new DateTime($text);
        $now = new DateTime();
        return $date > $now;
    }

    function getSign($firstname, $middlename, $lastname) {
        $nameFirstLetter = substr($firstname, 0, 2);
        $middlenameFirstLetter = substr($middlename, 0, 2);
        return $nameFirstLetter.".".$middlenameFirstLetter."."." ".$lastname;
        //return $this->mb_ucfirst($nameFirstLetter, $encoding = 'UTF-8').".".$this->mb_ucfirst($middlenameFirstLetter, $encoding = 'UTF-8')."."." ".$this->mb_ucfirst($lastname, $encoding = 'UTF-8');
    }

//     function mb_ucfirst($str, $encoding='UTF-8') {
//         $str = mb_ereg_replace('^[\ ]+', '', $str);
//         $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
//             mb_substr($str, 1, mb_strlen($str), $encoding);
//         return $str;
//     }
}

?>