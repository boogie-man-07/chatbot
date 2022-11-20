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

    function triggerActionForShowDmsMenu($chatID, $firstname, $dmsType, $isPollFinished) {
        $reply = $this->constants->getReplyForEnterDmsMenu($firstname, $dmsType, $isPollFinished);
        $keyboard = $this->keyboards->getDmsMenuKeyboard($dmsType, $isPollFinished);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSendDmsMemo($chatID, $dmsType) {
        $reply = $this->constants->getUrlForSendDmsMemo($dmsType);
        sendDocument($chatID, $reply, "{}");
    }

    function triggerActionForSendDmsClinics($chatID, $dmsType) {
        $reply = $this->constants->getUrlForSendDmsClinics($dmsType);
        sendDocument($chatID, $reply, "{}");
    }

    function triggerActionForSendDmsContacts($chatID, $dmsType) {
        $reply = $this->constants->getDmsContacts($dmsType);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForProceedDmsSurvey($chatID, $pollState) {
        $reply = $this->constants->getReplyForProceedDmsSurvey($pollState);
        $keyboard = $this->keyboards->getProceedDmsSurveyInlineKeyboard($pollState);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForAskDmsPollQuestion($chatID, $pollInfo, $pollQuestionInfo) {
        $reply = $this->constants->getReplyForAskADmsPollQuestion($pollInfo, $pollQuestionInfo);
        $keyboard = $this->keyboards->getInlineKeyboardForAskADmsPollQuestion($pollInfo, $pollQuestionInfo);
        sendMessage($chatID, $reply, $keyboard);
    }

//     function triggerActionForAskNextDmsPollQuestion($chatID, $userId, $pollInfo, $pollQuestionInfo) {
//         $reply = $this->constants->getReplyForAskADmsPollQuestion($pollInfo, $pollQuestionInfo);
//         sendMessage($chatID, $reply, null);
//     }

    function triggerActionForAskADmsQuestion($chatID) {
        $reply = $this->constants->getReplyForAskADmsQuestion();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForDmsSendingConfirmation($chatID) {
        $reply = $this->constants->getReplyForDmsSending();
        $keyboard = $this->keyboards->getDmsSendingInlineKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForDmsEmptyEmail($chatID) {
        $reply = $this->constants->getReplyForDmsEmptyEmail();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForDmsQuestionIsSended($chatID) {
        $reply = $this->constants->getReplyForDmsIsSent();
        sendMessage($chatID, $reply, null);
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

    function triggerActionForGetRestVacationInfo($chatID, $userId, $vacationInfo) {
        $data = $vacationInfo->getRestVacationCountByUserId($userId);
        $vacations = $vacationInfo->getVacationsInfo($userId);
        $reply = $this->constants->getRestVacationInfoText($data, $vacations);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForChooseVacationToPostpone($chatID, $data, $firstname) {
        if (!$data) {
            $reply = $this->constants->getRestVacationInfoToChooseText($firstname, false);
            $keyboard = null;
        } else {
            $reply = $this->constants->getRestVacationInfoToChooseText($firstname, true);
            $keyboard = $this->keyboards->getChooseVacationToPostponeInlineKeyboard($chatID, $data);
        }
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForCheckPostponedVacationDuration($chatID, $restDuration) {
        $reply = $this->constants->getReplyCheckPostponedVacationDuration($restDuration);
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

    function triggerActionForSetRegularVacationDuration($chatID, $restVacationDuration) {
        $reply = $this->constants->getRegularVacationDurationText($restVacationDuration);
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

    function triggerActionForSetPostponedVacationDuration($chatID, $restVacationDuration) {
        $reply = $this->constants->getPostponedVacationDurationText($restVacationDuration);
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
    // remove
    function triggerCalendarAction($chatID, $monthlyWorkData) {
        $reply = "Ваши рабочие дни на ".$monthlyWorkData['currentMonth']." года.";
        $keyboard = $this->keyboards->getEmployeeMonthlyWorkdaysCalendar($monthlyWorkData);
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerNextCalendarAction($chatID, $messageId, $monthlyWorkData) {
        editMessageText($chatID, $messageId, "Ваши рабочие дни на ".$monthlyWorkData['currentMonth']." года.");
        $keyboard = $this->keyboards->getEmployeeMonthlyWorkdaysCalendar($monthlyWorkData);
        editMessageReplyMarkup($chatID, $messageId, $keyboard);
    }

    function triggerPreviousCalendarAction($chatID, $messageId, $monthlyWorkData) {
        editMessageText($chatID, $messageId, "Ваши рабочие дни  на ".$monthlyWorkData['currentMonth']." года.");
        $keyboard = $this->keyboards->getEmployeeMonthlyWorkdaysCalendar($monthlyWorkData);
        editMessageReplyMarkup($chatID, $messageId, $keyboard);
    }

    function isCorrectDateFormat($text) {
        return preg_match('/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}$/', $text);
    }

    function isCorrectEmailFormat($text) {
        if(filter_var($text, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    function isCorrectVacationDurationFormat($text) {
        return preg_match('/^([1-9]\d?)$/', $text);
    }

//     function isCorrectDigit($text) {
//         return preg_match('/^([1-9]\d?)$/', $text);
//     }

    function shouldGoToNextQuestion($pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'] + 1;
        return $id < count($pollQuestionInfo);
    }

    function isDateNotInPast($text) {
        $postponedStartDate = new DateTime($text);
        $currentDate = new DateTime();
        return $postponedStartDate > $currentDate->modify('+5 days');
    }

    function isSeparateVacationDateNotInPast($text, $lastSeparateVacationEndDate) {
        $postponedStartDate = new DateTime($text);
        $lastDate = new DateTime($lastSeparateVacationEndDate);
        return $postponedStartDate > $lastDate;
    }

    function getSign($fullname) {
        $nArray = explode(' ', $fullname);
        $nameFirstLetter = substr($nArray[1], 0, 2);
        $middlenameFirstLetter = substr($nArray[2], 0, 2);
        return $nArray[0]." ".$nameFirstLetter.".".$middlenameFirstLetter.".";
    }

    function getCurrentMonth() {
        $firstDay = strtotime('first day of this month', time());
        return date('d.m.Y', $firstDay);
    }

    function getNextMonth($offset) {
        $firstDay = strtotime("first day of $offset month", time());
        return date('d.m.Y', $firstDay);
    }

    function getPreviousMonth($offset) {
        $firstDay = strtotime("first day of $offset month", time());
        return date('d.m.Y', $firstDay);
    }

    function generateNextOffset($offset) {
        $number = "0";
        if (strpos($offset, "+") === false) {
            $number = -abs(substr($offset, strpos($offset, "-") + 1));
        } else if (strpos($offset, "-") === false) {
            $number = +abs(substr($offset, strpos($offset, "+") + 1));
        }
        $newOffset = $number + 1;
        return $newOffset > 0 ? "+".(string)$newOffset : (string)$newOffset;
    }

    function generatePreviousOffset($offset) {
        $number = "0";
        if (strpos($offset, "-") === false) {
            $number = +abs(substr($offset, strpos($offset, "+") + 1));
        } else if (strpos($offset, "+") === false) {
            $number = -abs(substr($offset, strpos($offset, "-") + 1));
        }
        $newOffset = $number - 1;
        return $newOffset > 0 ? "+".(string)$newOffset : (string)$newOffset;
    }

    function formatDate($text) {
        $date = strstr($text, '.', true);
        $correctDate = mb_strlen($date) == 1 ? '0'.$date : $date;
        $month = strstr(substr(strstr($text, '.'), 1), '.', true);
        $correctMonth = mb_strlen($month) == 1 ? '0'.$month : $month;
        $correctYear = substr(strrchr($text, "."), 1);
        return $correctDate.'.'.$correctMonth.'.'.$correctYear;
    }

    function isCorrectFLFormat($first, $last) {
        if (mb_strlen($first) < 2 || mb_strlen($last) < 2) {
            return false;
        } else {
            return true;
        }
    }

    function isDmsPollReplyCouldBeAccepted($userId, $pollInfo, $pollOptions) {
        $controlArray = array();
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollOptions[$id];
        $responses = json_decode($pollQuestionData['responses'], true);
        foreach ($responses['options'] as $key=>$value) {
            if ($value['isSelected'] == true) {
                array_push($controlArray, 1);
            } else {
                array_push($controlArray, 0);
            }
        }
        if (array_sum($controlArray) == 0) {
            return false;
        } else {
            return true;
        }
    }

    function isSalaryMode($state, $states) {
        return $state == $states['salaryState'];
    }

    function getSendData($user, $vacationData, $separatedVacationData) {
        $separatedDataArray = array();
        $boss = $this->getSign($user['boss']);
        foreach($separatedVacationData as $data) {
            $item = array(
                'id' => $data['id'],
                'startDate' => $data['startdate'],
                'endDate' => $data['enddate'],
                'reason' => $data['reason']
            );
            array_push($separatedDataArray, $item);
        }

        return array(
            'position' => $user['position'],
            'formFullName' => $user['form_fullname'],
            'companyId' => $user['company_id'],
            'startDate' => $vacationData['startdate'],
            'endDate' => $vacationData['enddate'],
            'boss' => $boss,
            'bossPosition' => $user['boss_position'],
            'vacations' => $separatedDataArray
        );
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
            'waiting for choose vacation to postpone',
            'waiting for postponed vacation duration',
            'waiting for postponed separate vacation startdate',
            'waiting for postponed separate vacation duration',
            'waiting for vacation form sending',
            'waiting for choose dms pool reply',
            'waiting for dms question',
            'waiting for email for dms question reply'
        );
        if (in_array($currentState, $dialogState)) {
            return true;
        } else {
            return false;
        }
    }
}

?>