<?php

class AuthorizedUserScenario {

    var $chatID = null;
    var $user = null;
    var $username = null;
    var $access = null;
    var $swiftmailer = null;
    var $authroute = null;
    var $commonmistakeroute = null;
    var $phonebookroute = null;
    var $valuesRoute = null;
    var $mainRulesRoute = null;
    var $mainInformationRoute = null;
    var $salaryRoute = null;
    var $commands = null;
    var $states = null;
    var $state = null;
    var $logics = null;
    var $forms = null;
    var $email = null;
    var $vacationInfo = null;
    var $calendarInfo = null;
    var $query = null;
    var $logs = null;
    var $messageId = null;

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $phonebookroute, $valuesRoute, $mainRulesRoute, $mainInformationRoute, $salaryRoute, $commands, $states, $state, $logics, $forms, $email, $vacationInfo, $calendarInfo, $query, $logs, $messageId) {
        $this->chatID = $chatID;
        $this->user = $user;
        $this->username = $username;
        $this->access = $access;
        $this->swiftmailer = $swiftmailer;
        $this->authroute = $authroute;
        $this->commonmistakeroute = $commonmistakeroute;
        $this->phonebookroute = $phonebookroute;
        $this->valuesRoute = $valuesRoute;
        $this->mainRulesRoute = $mainRulesRoute;
        $this->mainInformationRoute = $mainInformationRoute;
        $this->salaryRoute = $salaryRoute;
        $this->commands = $commands;
        $this->states = $states;
        $this->state = $state;
        $this->logics = $logics;
        $this->forms = $forms;
        $this->email = $email;
        $this->vacationInfo = $vacationInfo;
        $this->calendarInfo = $calendarInfo;
        $this->query = $query;
        $this->logs = $logs;
        $this->messageId = $messageId;
    }

    function run($text) {
        if($this->chatID == '187967374' || $this->chatID == '5389293300') {
            $this->logs->logCustom($text, $this->user['fullname']);
        }

        switch ($text) {
            // remove
            case $this->commands['calendar']:
                $calendar = $this->calendarInfo->getMonthlyDataForEmployee();
                sendMessage($this->chatID, $calendar, null);
                //$this->salaryRoute->triggerCalendarAction($this->chatID, "январь");
                exit;
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->authroute->triggerActionForBotRestartedByAuthorized($this->chatID, $this->user['fullname']);
                exit;
            case $this->commands['exit']:
                $isUserRemoved = $this->access->removeUserCredentialsByChatID($this->chatID);
                $isUserStateRemoved = $this->access->removeUserStateByChatID($this->chatID);
                if ($isUserRemoved && $isUserStateRemoved) {
                    $this->authroute->triggerActionForGoToTheStart($this->chatID, $this->username);
                    exit;
                }
            case $this->commands['phones']:
                $this->access->setState($this->chatID, $this->states['findTelephoneNumberState']);
                $this->phonebookroute->triggerActionForFindPhoneNumber($this->chatID);
                exit;
            case $this->commands['values']:
                $this->valuesRoute->triggerActionForGetWelcomeValue($this->chatID, $this->user['firstname'], $this->commands['firstRuleInline']);
                exit;
            case $this->commands['mainRules']:
                $this->mainRulesRoute->triggerActionForEnterMainRulesMenu($this->chatID);
                exit;
            case $this->commands['commonInformation']:
                if ($this->salaryRoute->isSalaryMode($this->state, $this->states)) {
                    $this->salaryRoute->triggerActionForGetMainSalaryInformation($this->chatID, $this->user['company_id']);
                    exit;
                } else {
                    $this->mainInformationRoute->triggerActionForEnterMainInformationMenu($this->chatID, $this->user['company_id']);
                    exit;
                }
            case $this->commands['paymentDates']:
                $this->salaryRoute->triggerActionForGetPaymentDatesInformation($this->chatID, $this->user['company_id']);
                exit;
            case $this->commands['applications']:
                $this->access->removeVacationDataByChatID($this->chatID);
                $this->salaryRoute->triggerActionForGetApplicationsInformation($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['myVacation']:
                $this->salaryRoute->triggerActionForGetRestVacationInfo($this->chatID, $this->user['user_id'], $this->vacationInfo);
                exit;
            case $this->commands['howToNavigate']:
                $this->mainInformationRoute->triggerActionForShowHowToNavigateToOffice($this->chatID, $this->user['company_id']);
                exit;
            case $this->commands['navigationSchemeSkolkovo']:
                $this->mainInformationRoute->triggerActionForShowNavigationSchemeToSkolkovo($this->chatID);
                exit;
            case $this->commands['navigationSchemeOskol']:
                $this->mainInformationRoute->triggerActionForShowNavigationSchemeToOskol($this->chatID);
                exit;
            case $this->commands['navigationSchemeSaratov']:
                $this->mainInformationRoute->triggerActionForShowNavigationSchemeToSaratov($this->chatID);
                exit;
            case $this->commands['itHelp']:
                $this->mainInformationRoute->triggerActionForShowItHelpMenu($this->chatID, $this->user['company_id']);
                exit;
            case $this->commands['erpAnd1CFeedback']:
                $this->access->setState($this->chatID, $this->states['erpFeedbackWaitingState']);
                $this->mainInformationRoute->triggerActionForProceedErpAnd1CFeedback($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['hardwareFeedback']:
                $this->access->setState($this->chatID, $this->states['hardwareFeedbackWaitingState']);
                $this->mainInformationRoute->triggerActionForProceedHardwareFeedback($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['resourcesFeedback']:
                $this->access->setState($this->chatID, $this->states['resourcesFeedbackWaitingState']);
                $this->mainInformationRoute->triggerActionForProceedResourcesFeedback($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['otherFeedback']:
                $this->access->setState($this->chatID, $this->states['otherFeedbackWaitingState']);
                $this->mainInformationRoute->triggerActionForProceedOtherFeedback($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['salaryInformation']:
                $this->access->setState($this->chatID, $this->states['salaryState']);
                $this->salaryRoute->triggerActionForShowSalaryMenu($this->chatID);
                exit;
            case $this->commands['dmsInformation']:
                $this->salaryRoute->triggerActionForShowDmsMenu($this->chatID, $this->user['dms_type'], false);
                exit;
            case $this->commands['dmsMemo']:
                $this->salaryRoute->triggerActionForSendDmsMemo($this->chatID, $this->user['dms_type']);
                exit;
            case $this->commands['dmsClinics']:
                $this->salaryRoute->triggerActionForSendDmsClinics($this->chatID, $this->user['dms_type']);
                exit;
            case $this->commands['dmsContacts']:
                $this->salaryRoute->triggerActionForSendDmsContacts($this->chatID, $this->user['dms_type']);
                exit;
            case $this->commands['dmsGoToSurvey']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                if ($pollInfo) {
                    if ($pollInfo['is_finished']) {
                        sendMessage($this->chatID, 'Вы уже прошли данный опрос, спасибо за уделенное время!', null);
                    } else {
                        $this->salaryRoute->triggerActionForProceedDmsSurvey($this->chatID, $pollInfo['poll_state']);
                    }
                } else {
                    $this->access->setDmsPollInfo($this->user['user_id'], 0, 0);
                    $this->salaryRoute->triggerActionForProceedDmsSurvey($this->chatID, 0);
                }
                exit;
            case $this->commands['dmsAskAQuestion']:
                $this->access->setState($this->chatID, $this->states['dmsQuestionWaitingState']);
                $this->salaryRoute->triggerActionForAskADmsQuestion($this->chatID);
                exit;
            case $this->commands['meetings']:
                $this->mainRulesRoute->triggerActionForGetMeetingInfo($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['phoneCalls']:
                $this->mainRulesRoute->triggerActionForGetPhoneCallsInfo($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['officeWork']:
                $this->mainRulesRoute->triggerActionForGetOfficeRulesInfo($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['appearance']:
                $this->mainRulesRoute->triggerActionForGetAppearanceInfo($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['navigateToMainScreen']:
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->access->removeFindUserDataByChatID($this->chatID);
                $this->access->removeVacationDataByChatID($this->chatID);
                $this->mainRulesRoute->triggerActionForNavigateBack($this->chatID);
                exit;
            default:
                $lastname = $this->phonebookroute->getUserLastname($text);
                $firstname = $this->phonebookroute->getUserFirstname($text);
                $result = $this->access->getUserByFirstnameAndLastName($firstname, $lastname, $this->logics->getUserPrivelegesForUserCards($this->user));
                if ((substr_count(trim($text), ' ') == 1) && (!empty($result))) {
                     if (mb_strlen($firstname) < 2 || mb_strlen($lastname) < 2) {
                        $reply = "Ну не может быть имя или фамилия из одной буквы ".hex2bin('f09f9982');
                        sendMessage($this->chatID, $reply, null);
                        exit;
                    } else {
                        $this->access->saveFindUserData($this->chatID, $result['firstname'], $result['lastname']);
                        //todo maybe comment below, need to check how it works
                        $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                        $this->phonebookroute->triggerActionForGetUserCardOptions($this->chatID);
                        exit;
                    }
                }


                if (!$this->salaryRoute->isDialogInProgress($this->state)) {
                    $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                    exit;
                } else {
                    switch ($this->state) {
                        case $this->states['findTelephoneNumberState']:
                            $lastname = $this->phonebookroute->getUserLastname($text);
                            $firstname = $this->phonebookroute->getUserFirstname($text);
                            if (!$this->salaryRoute->isCorrectFLFormat($firstname, $lastname)) {
                                $this->commonmistakeroute->triggerActionForIncorrectFLFormat($this->chatID);
                                exit;
                            } else {
                                $result = $this->access->getUserByFirstnameAndLastName($firstname, $lastname, $this->logics->getUserPrivelegesForUserCards($this->user));
                                if ($result) {
                                    $this->access->saveFindUserData($this->chatID, $result['firstname'], $result['lastname']);
                                    //todo maybe comment below, need to check how it works
                                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                    $this->phonebookroute->triggerActionForGetUserCardOptions($this->chatID);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForGetUserCardError($this->chatID, $this->user['firstname']);
                                    exit;
                                }
                            }
                        case $this->states['erpFeedbackWaitingState']:
                            $this->access->setFeedbackInfo($this->chatID, $text);
                            $this->mainInformationRoute->triggerActionForSendFeedbackConfirmation($this->chatID);
                            exit;
                        case $this->states['hardwareFeedbackWaitingState']:
                            $this->access->setFeedbackInfo($this->chatID, $text);
                            $this->mainInformationRoute->triggerActionForSendFeedbackConfirmation($this->chatID);
                            exit;
                        case $this->states['resourcesFeedbackWaitingState']:
                            $this->access->setFeedbackInfo($this->chatID, $text);
                            $this->mainInformationRoute->triggerActionForSendFeedbackConfirmation($this->chatID);
                            exit;
                        case $this->states['otherFeedbackWaitingState']:
                            $this->access->setFeedbackInfo($this->chatID, $text);
                            $this->mainInformationRoute->triggerActionForSendFeedbackConfirmation($this->chatID);
                            exit;
                        case $this->states['regularVacationStartDateWaitingState']:
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                if ($this->salaryRoute->isDateNotInPast($correctText)) {
                                    $restVacationCount = $this->vacationInfo->getRestVacationCountByUserId($this->user['user_id']);
                                    $this->access->setRegularVacationStartDate($this->chatID, $correctText);
                                    $this->access->setState($this->chatID, $this->states['regularVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetRegularVacationDuration($this->chatID, $restVacationCount);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['regularVacationDurationWaitingState']:
                            if ($this->salaryRoute->isCorrectVacationDurationFormat($text)) {
                                $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                                $restVacationCount = $this->vacationInfo->getRestVacationCountByUserId($this->user['user_id']);
                                if ($text <= $restVacationCount) {
                                    if ($vacationFormData['vacation_type'] != '3') {
                                        $this->access->setRegularVacationDuration($this->chatID, $text);
                                        $this->access->setState($this->chatID, $this->states['regularVacationFormSendingWaitingState']);
                                        $this->salaryRoute->triggerActionForSendRegularVacationForm($this->chatID);
                                        exit;
                                    } else {
                                        $this->access->setRegularVacationDuration($this->chatID, $text);
                                        $this->access->setState($this->chatID, $this->states['regularVacationAcademicReasonWaitingState']);
                                        $this->salaryRoute->triggerActionForSetRegularVacationAcademicReason($this->chatID);
                                        exit;
                                    }
                                } else {
                                    $this->commonmistakeroute->triggerActionForMaxVacationDurationLimitError($this->chatID, $restVacationCount);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionAcademicVacationDurationFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['regularVacationAcademicReasonWaitingState']:
                            $this->access->setRegularVacationAcademicReason($this->chatID, $text);
                            $this->access->setState($this->chatID, $this->states['regularVacationFormSendingWaitingState']);
                            $this->salaryRoute->triggerActionForSendRegularVacationForm($this->chatID);
                            exit;
//                         case $this->states['postponedVacationStartDateWaitingState']:
//                             if ($this->salaryRoute->isCorrectDateFormat($text)) {
//                                 if ($this->salaryRoute->isDateNotInPast($text)) {
//                                     $this->access->setVacationStartDate($this->chatID, $text);
//                                     $this->access->setState($this->chatID, $this->states['postponedVacationEndDateWaitingState']);
//                                     $this->salaryRoute->triggerActionForSetPostponedVacationEndDate($this->chatID);
//                                     exit;
//                                 } else {
//                                     $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
//                                     exit;
//                                 }
//                             } else {
//                                 $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
//                                 exit;
//                             }
//                         case $this->states['postponedVacationEndDateWaitingState']:
//                             if ($this->salaryRoute->isCorrectDateFormat($text)) {
//                                 if ($this->salaryRoute->isDateNotInPast($text)) {
//                                     $this->access->setVacationEndDate($this->chatID, $text);
//                                     $this->access->setState($this->chatID, $this->states['postponedVacationNewStartDateWaitingState']);
//                                     $this->salaryRoute->triggerActionForSetPostponedVacationNewStartDate($this->chatID);
//                                     exit;
//                                 } else {
//                                     $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
//                                     exit;
//                                 }
//                             } else {
//                                 $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
//                                 exit;
//                             }
//                         case $this->states['postponedVacationNewStartDateWaitingState']:
//                             if ($this->salaryRoute->isCorrectDateFormat($text)) {
//                                 if ($this->salaryRoute->isDateNotInPast($text)) {
//                                     $this->access->setVacationNewStartDate($this->chatID, $text);
//                                     $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
//                                     $this->salaryRoute->triggerActionForSetPostponedVacationNewEndDate($this->chatID);
//                                     exit;
//                                 } else {
//                                     $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
//                                     exit;
//                                 }
//                             } else {
//                                 $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
//                                 exit;
//                             }
                        case $this->states['postponedVacationNewStartDateWaitingState']:
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                if ($this->salaryRoute->isDateNotInPast($correctText)) {
                                    $this->access->setSelectedVacationNewStartDate($this->chatID, $correctText);
                                    $this->access->setState($this->chatID, $this->states['postponedVacationDurationWaitingState']);
                                    $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationDuration($this->chatID, $vacationInfo['amount']);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForPostponedDateInThePastError($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['postponedVacationDurationWaitingState']:
                            if ($this->salaryRoute->isCorrectVacationDurationFormat($text)) {

                                $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);

                                if ((int)$text == (int)$vacationInfo['amount']) {
                                    $this->access->setSelectedVacationNewDuration($this->chatID, $text);
                                    $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                    $this->access->saveSeparatedUserVacations($this->chatID, $vacationInfo);
                                    $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                    exit;
                                } else if ((int)$text > (int)$vacationInfo['amount']) {
                                    $this->commonmistakeroute->triggerActionForVacationDurationError($this->chatID, $vacationInfo['amount']);
                                    exit;
                                } else {
                                    $this->access->setSelectedVacationNewDuration($this->chatID, $text);
                                    $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                    $this->access->saveSeparatedUserVacations($this->chatID, $vacationInfo);
                                    $totalVacationsDuration = $this->access->getSumOfVacationParts($this->chatID);
                                    $restVacationsDuration =  (int)$vacationInfo['amount'] - (int)$totalVacationsDuration;
                                    if ($restVacationsDuration > 0) {
                                        $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
                                        $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, $restVacationsDuration);
                                        exit;
                                    } else {
                                        $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                        $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                        exit;
                                    }
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForVacationDurationFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['postponedSeparateVacationStartDateWaitingState']:
                            $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                            $lastSeparateVacation = $this->access->getLastSeparateVacation($this->chatID);
                            $totalVacationsDuration = $this->access->getSumOfVacationParts($this->chatID);
                            $restVacationsDuration =  (int)$vacationInfo['amount'] - (int)$totalVacationsDuration;
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                //sendMessage($this->chatID, $lastSeparateVacation['enddate'], null); exit;
                                if ($this->salaryRoute->isSeparateVacationDateNotInPast($correctText, $lastSeparateVacation['enddate'])) {
                                    $this->access->saveSeparatedUserVacationStartDate($this->chatID, $correctText, $vacationInfo);
                                    $this->access->setState($this->chatID, $this->states['postponedSeparateVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationDuration($this->chatID, $restVacationsDuration);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForPostponedDateInThePastError($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['postponedSeparateVacationDurationWaitingState']:
                            if ($this->salaryRoute->isCorrectVacationDurationFormat($text)) {
                                $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                $totalVacationsDuration = $this->access->getSumOfVacationParts($this->chatID);
                                $restVacationsDuration =  (int)$vacationInfo['amount'] - (int)$totalVacationsDuration;

                                if ((int)$text  > $restVacationsDuration) {
                                    $this->commonmistakeroute->triggerActionForVacationDurationError($this->chatID, $restVacationsDuration);
                                    exit;
                                } else if ((int)$text  < $restVacationsDuration) {
                                    $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);
                                    $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
                                    $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, ((int)$text  < $restVacationsDuration));
                                    exit;
                                } else {
                                    $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);
                                    $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                    exit;
                                }

                                $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);

                                if ($restVacationsDuration > 0) {
                                    $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
                                    $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, $restVacationsDuration);
                                    exit;
                                } else {
                                    $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                    exit;
                                }
                                //$this->access->setState($this->chatID, $this->states['postponedSeparateVacationDurationWaitingState']);
                                //$this->salaryRoute->triggerActionForSetPostponedVacationDuration($this->chatID);
                                exit;
                            } else {
                                $this->commonmistakeroute->triggerActionForVacationDurationFormatError($this->chatID);
                                exit;
                            }
//                         case $this->states['postponedVacationNewEndDateWaitingState']:
//                             if ($this->salaryRoute->isCorrectDateFormat($text)) {
//                                 if ($this->salaryRoute->isDateNotInPast($text)) {
//                                     $this->access->setVacationNewEndDate($this->chatID, $text);
//                                     $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
//                                     $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
//                                     exit;
//                                 } else {
//                                     $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
//                                     exit;
//                                 }
//                             } else {
//                                 $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
//                                 exit;
//                             }
                        case $this->states['postponedVacationReasonWaitingState']:
                            $this->access->setSelectedVacationReason($this->chatID, $text);
                            $this->access->setSeparateVacationsReasons($this->chatID, $text);
                            //$this->access->setState($this->chatID, $this->states['vacationFormSendingWaitingState']);
                            $this->salaryRoute->triggerActionForSendPostponedVacationForm($this->chatID);
                            exit;
                        case $this->states['dmsQuestionWaitingState']:
                            $this->access->setDmsQuestionInfo($this->chatID, $text);
                            $this->salaryRoute->triggerActionForDmsSendingConfirmation($this->chatID);
                            exit;
                        default:
                            $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            exit;
                    }
                }
        }
    }

    function runInline($text) {
        if($this->chatID == '187967374' || $this->chatID == '5389293300') {
            $this->logs->logCustom($text, $this->user['fullname']);
        }
        switch ($text) {
            // remove
            case $this->commands['calendarInline']:
                $this->salaryRoute->triggerNextCalendarAction($this->chatID, $this->messageId, "Февраль");
                // start delete segment
                $vacationInfo = $this->vacationInfo->getRestVacationCountByUserId($this->user['user_id']);
                $calendarEmployee = $this->calendarInfo->getMonthlyDataForEmployee();
                $calendarOffice = $this->calendarInfo->getMonthlyDataForOffice();
                sendMessage($this->chatID, json_encode($vacationInfo), null);
                sendMessage($this->chatID, json_encode($calendarEmployee), null);
                sendMessage($this->chatID, json_encode($calendarOffice), null);
                // end delete segment
                answerCallbackQuery($this->query["id"], "Получены данные за февраль!");
                exit;
            case $this->commands['userFullCardInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    answerCallbackQuery($this->query["id"], "Пользователь найден!");
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserCard($this->chatID, $result);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "...");
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userEmailInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    answerCallbackQuery($this->query["id"], "Адрес электронной почты найден!");
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserEmail($this->chatID, $result['email']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Адрес электронной почты не найден!");
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userMobileNumberInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    answerCallbackQuery($this->query["id"], "Номер мобильного телефона найден!");
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserMobileNumber($this->chatID, $result['mobile_number']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Номер мобильного телефона не найден!");
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userOfficeNumberInline']: // need to clear state after search?
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    answerCallbackQuery($this->query["id"], "Номер рабочего телефона найден!");
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserOfficeNumber($this->chatID, $result['office_number'], $result['internal_number']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Номер рабочего телефона не найден!");
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['firstRuleInline']:
                answerCallbackQuery($this->query["id"], "Правда и факты!");
                $this->valuesRoute->triggerActionForGetFirstValue($this->chatID, $this->user['company_id'], $this->commands['secondRuleInline']);
                exit;
            case $this->commands['secondRuleInline']:
                answerCallbackQuery($this->query["id"], "Открытость и прозрачность!");
                $this->valuesRoute->triggerActionForGetSecondValue($this->chatID, $this->user['company_id'], $this->commands['thirdRuleInline']);
                exit;
            case $this->commands['thirdRuleInline']:
                answerCallbackQuery($this->query["id"], "Работа - любимое дело!");
                $this->valuesRoute->triggerActionForGetThirdValue($this->chatID, $this->user['company_id'], $this->commands['fourthRuleInline']);
                exit;
            case $this->commands['fourthRuleInline']:
                answerCallbackQuery($this->query["id"], "Значимые отношения!");
                $this->valuesRoute->triggerActionForGetFourthValue($this->chatID, $this->user['company_id'], $this->commands['lastRuleInline']);
                exit;
            case $this->commands['lastRuleInline']:
                answerCallbackQuery($this->query["id"], "...");
                $this->valuesRoute->triggerActionForGetLastValue($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['sendFeedbackInline']:
                $feedback = $this->access->getFeedbackInfo($this->chatID);
                $feedbackText = $feedback['feedback_text'];
                if ($feedback) {
                    switch ($this->state) {
                        case 'waiting for ERP feedback':
                            $isSended = $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#1C &".$this->user['email']."&",
                                $feedbackText
                            );
                            if ($isSended) {
                                answerCallbackQuery($this->query["id"], "Обращение успешно отправлено!");
                                break;
                            } else {
                                answerCallbackQuery($this->query["id"], "Не удалось отправить обращение, попробуйте еще раз!");
                                break;
                            }
                        case 'waiting for hardware feedback':
                            $isSended = $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#ADM &".$this->user['email']."&",
                                $feedbackText
                            );
                            if ($isSended) {
                                answerCallbackQuery($this->query["id"], "Обращение успешно отправлено!");
                                break;
                            } else {
                                answerCallbackQuery($this->query["id"], "Не удалось отправить обращение, попробуйте еще раз!");
                                break;
                            }
                        case 'waiting for resources feedback':
                            $isSended = $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#ADM &".$this->user['email']."&",
                                $feedbackText
                            );
                            if ($isSended) {
                                answerCallbackQuery($this->query["id"], "Обращение успешно отправлено!");
                                break;
                            } else {
                                answerCallbackQuery($this->query["id"], "Не удалось отправить обращение, попробуйте еще раз!");
                                break;
                            }
                        case 'waiting for other feedback':
                            $isSended = $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "&".$this->user['email']."&",
                                $feedbackText
                            );
                            if ($isSended) {
                                answerCallbackQuery($this->query["id"], "Обращение успешно отправлено!");
                                break;
                            } else {
                                answerCallbackQuery($this->query["id"], "Не удалось отправить обращение, попробуйте еще раз!");
                                break;
                            }
                    }
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->mainInformationRoute->triggerActionForSendFeedback($this->chatID);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Не удалось отправить обращение, попробуйте еще раз!");
                    $this->commonmistakeroute->triggerErrorForSendFeedback();
                    exit;
                }
            case $this->commands['regularVacationCaseInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                $this->salaryRoute->triggerActionForRegularApplicationPreparations($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['postponedVacationCaseInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                if ($this->user['company_id'] == 3) {
                    $data = $this->vacationInfo->getVacationsInfo($this->user['user_id']);
                    if ($data) {
                        $this->access->saveUserVacations($this->chatID, $data);
                        $this->access->setState($this->chatID, $this->states['postponedVacationChooseVacationState']);
                    }
                    $this->salaryRoute->triggerActionForChooseVacationToPostpone($this->chatID, $data, $this->user['firstname']);
                    exit;
                }
                exit;
            case $this->commands['triggerMainVacationInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                $this->access->setRegualarVacationType($this->chatID, '0');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerAdditionalVacationInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                $this->access->setRegualarVacationType($this->chatID, '1');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerNoPaymentVacationInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                $this->access->setRegualarVacationType($this->chatID, '2');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerAcademicVacationInline']:
                answerCallbackQuery($this->query["id"], "Успешно!");
                $this->access->setRegualarVacationType($this->chatID, '3');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['sendNewRegularVacationFormInline']:
                $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                //$sign = $this->salaryRoute->getSign($this->user['firstname'], $this->user['middlename'], $this->user['lastname']);
                $sign = $this->salaryRoute->getSign($this->user['fullname']);
                $date = new dateTime();
                $day = $date->format("d");
                $month = $date->format("F");
                $year = $date->format("Y");
                $bossSign = $this->salaryRoute->getSign($this->user['boss']);
                if ($vacationFormData['vacation_type'] == '3') {
                    $this->forms->getNewRegularVacationForm($this->user, $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], $vacationFormData["reason"], $day, $month, $year, $sign, $bossSign);
                } else {
                    $this->forms->getNewRegularVacationForm($this->user, $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], null, $day, $month, $year, $sign, $bossSign);
                }
                $template = $this->email->generateNewRegularVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $isSended = $this->swiftmailer->sendNewRegularVacationMailWithAttachementViaSmtp(
                    $vacationFormData['vacation_type'],
                    $this->user['company_id'],
                    $this->user['email'],
                    "Заявление на отпуск",
                    $template
                );
                if ($isSended) {
                    answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->salaryRoute->triggerActionForSendRegularVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Не удалось отправить письмо, повторите попытку!");
                    exit;
                }

            case $this->commands['sendOldRegularVacationFormInline']:
                $template = $this->email->generateRegularVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $isSended = $this->swiftmailer->sendRegularVacationMailWithAttachementViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "Заявление на отпуск",
                    $template
                );
                if ($isSended) {
                    answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->salaryRoute->triggerActionForSendOldRegularVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Не удалось отправить письмо, повторите попытку!");
                    exit;
                }
            case $this->commands['sendPostponedVacationFormInline']:
                //$vacationFormData = $this->access->getDataForVacationForm($this->chatID);
                $vacationFormData = $this->access->getSelectedVacationInfo($this->chatID);
                $separatedVacationFormData = $this->access->getSeparatePostponedVacationsInfo($this->chatID);
                $sendData = $this->salaryRoute->getSendData($this->user, $vacationFormData, $separatedVacationFormData);
                $sign = $this->salaryRoute->getSign($this->user['fullname']);

                $position = $sendData['position'];
                $fullName = $sendData['fullName'];
                $startDate = $sendData['startDate'];
                $endDate = $sendData['endDate'];
                $companyId = $sendData['companyId'];
                $vacationList = $sendData['vacations'];

                //sendMessage($this->chatID, (string)count($separatedVacationFormData), null);
                $sendInfo = $this->forms->getPostponeVacationForm($this->chatID, $sendData, $sign);
                foreach ($sendInfo as $info) {
                    $template = $this->email->generatePostponeVacationForm($this->user['company_id']);
                    $template = str_replace("{firstname}", $this->user['firstname'], $template);
                    $this->swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
                        $this->user['company_id'],
                        $this->user['email'],
                        "Заявление на перенос отпуска",
                        $template,
                        (string)$info
                    );
                }
                answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->salaryRoute->triggerActionForSendPostponedVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['sendOldPostponedVacationFormInline']:
                $template = $this->email->generatePostponeVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $isSended = $this->swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "Заявление на перенос отпуска",
                    $template
                );
                if ($isSended) {
                    answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->salaryRoute->triggerActionForSendOldPostponedVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                    exit;
                } else {
                    answerCallbackQuery($this->query["id"], "Не удалось отправить письмо, повторите попытку!");
                    exit;
                }
            case $this->commands['proceedDmsSurveyInline']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                $pollQuestionInfo = $this->access->getDmsPollQuestionInfo(1, $pollInfo['poll_state']);
                sendMessage($chatID, json_encode($pollInfo), null);
                sendMessage($chatID, json_encode($pollQuestionInfo), null);
//                 $this->salaryRoute->triggerActionForAskDmsQuestion($this->chatID, $pollQuestionInfo);
                answerCallbackQuery($this->query["id"], "Вопрос загружен!");
                exit;
            case $this->commands['sendDmsQuestionInline']:
                $questionInfo = $this->access->getDmsQuestionInfo($this->chatID);
                if ($questionInfo) {
                    $template = $this->email->generateDmsQuestionForm($this->user['company_id']);
                    $template = str_replace("{fullname}", $this->user['fullname'], $template);
                    $template = str_replace("{question}", $questionInfo['question_text'], $template);
                    $isSended = $this->swiftmailer->sendDmsQuestion(
                        $this->user['company_id'],
                        'booogie.man.07@gmail.com',
                        "Вопрос в рамках ДМС (Персональный ассистент работника)",
                        $template
                    );
                    if ($isSended) {
                        answerCallbackQuery($this->query["id"], "Ваш вопрос успешно отправлен!");
                        $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                        $this->access->removeDmsQuestionInfo($this->chatID);
                        $this->salaryRoute->triggerActionForDmsQuestionIsSended($this->chatID);
                        exit;
                    } else {
                        answerCallbackQuery($this->query["id"], "Не удалось отправить вопрос, попробуйте еще раз!");
                        exit;
                    }
                } else {
                    answerCallbackQuery($this->query["id"], "Не удалось отправить вопрос, попробуйте еще раз!");
                    $this->commonmistakeroute->triggerErrorForSendFeedback();
                    exit;
                }
            default:
                switch ($this->state) {
                    case $this->states['postponedVacationChooseVacationState']:
                        if ($this->user['company_id'] == 3) {
                            $this->access->setSelectedVacation($this->chatID, $text);
                            $this->access->setState($this->chatID, $this->states['postponedVacationNewStartDateWaitingState']);
                            $this->salaryRoute->triggerActionForSetPostponedVacationNewStartDate($this->chatID);
                            exit;
                        }
                    default:
                        sendMessage($this->chatID, "Default finished inline", null);
                        exit;
                }
        }
    }
}

?>