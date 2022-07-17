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

    function triggerActionForGetRestVacationInfo($chatID, $vacationInfo, $email) {
        $data = $vacationInfo->getVacationInfo($email);
        $reply = $this->constants->getRestVacationInfoText($data);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForChooseVacationToPostpone($chatID, $vacationInfo, $firstname, $email) {
        $data = $vacationInfo->getVacationsList($email);
        if (!$data) {
            $reply = $this->constants->getRestVacationInfoToChooseText($firstname, false);
        } else {
            $reply = $this->constants->getRestVacationInfoToChooseText($firstname, true);
        }
        sendMessage($chatID, $reply, null);
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
        $reply = $this->constants->getSendVacationFormText();
        $keyboard = $this->keyboards->getSendRegularVacationFormInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSetRegularVacationAcademicReason($chatID) {
        $reply = $this->constants->getRegularVacationAcademicReasonText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendRegularVacationFormResult($chatID, $firstname, $companyId) {
        $reply = $this->constants->getSentVacationFormResultText($firstname, $companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendOldRegularVacationFormResult($chatID, $firstname, $companyId) {
        $reply = $this->constants->getSentVacationFormResultText($firstname, $companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendOldPostponedVacationFormResult($chatID, $firstname, $companyId) {
        $reply = $this->constants->getSentVacationFormResultText($firstname, $companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendPostponedVacationFormResult($chatID, $firstname, $companyId) {
        $reply = $this->constants->getSentVacationFormResultText($firstname, $companyId);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSetPostponedVacationEndDate($chatID) {
        $reply = $this->constants->getSetPostponedVacationEndDateText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSetPostponedVacationNewStartDate($chatID) {
        $reply = $this->constants->getSetPostponedVacationNewStartDateText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSetPostponedVacationNewEndDate($chatID) {
        $reply = $this->constants->getSetPostponedVacationNewEndDateText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSetPostponedVacationReason($chatID) {
        $reply = $this->constants->getSetPostponedVacationReasonText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForSendPostponedVacationForm($chatID) {
        $reply = $this->constants->getSendVacationFormText();
        $keyboard = $this->keyboards->getSendPostponedVacationFormInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function isCorrectDateFormat($text) {
        return preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}$/', $text);
    }

    function isCorrectVacationDurationFormat($text) {
        return preg_match('/[0-9]/', $text);
    }

    function isDateNotInPast($text) {
        $date = new DateTime($text);
        $now = new DateTime();
        return $date > $now;
    }

    function getSign($fullname) {
        $nArray = explode(' ', $fullname);
        $nameFirstLetter = substr($nArray[1], 0, 2);
        $middlenameFirstLetter = substr($nArray[2], 0, 2);
        return $nArray[0]." ".$nameFirstLetter.".".$middlenameFirstLetter.".";
    }

    function isCorrectFLFormat($first, $last) {
        if (mb_strlen($first) < 2 || mb_strlen($last) < 2) {
            return false;
        } else {
            return true;
        }
    }

    function isSalaryMode($state, $states) {
        return $state == $states['salaryState'];
    }

    function isDialogInProgress($currentState) {
        $dialogState = array(
            'find telefone number',
            'salary',
            'waiting for ERP feedback',
            'waiting for hardware feedback',
            'waiting for resources feedback',
            'waiting for other feedback',
            'waiting for regular vacation startdate',
            'waiting for regular vacation duration',
            'waiting for regular vacation form sending',
            'waiting for regular vacation academic reason',
            'waiting for postponed vacation startdate',
            'waiting for postponed vacation enddate',
            'waiting for postponed vacation newstartdate',
            'waiting for postponed vacation newenddate',
            'waiting for postponed vacation reason',
            'waiting for vacation form sending'
        );
        if (in_array($currentState, $dialogState)) {
            return true;
        } else {
            return false;
        }
    }
}

?>