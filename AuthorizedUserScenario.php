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

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $phonebookroute, $valuesRoute, $mainRulesRoute, $mainInformationRoute, $salaryRoute, $commands, $states, $state, $logics, $forms, $email, $vacationInfo) {
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
    }

    function run($text) {
        switch ($text) {
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
                $this->salaryRoute->triggerActionForGetRestVacationInfo($this->chatID, $this->vacationInfo, $this->user['email']);
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
                            if ($this->salaryRoute->isCorrectDateFormat($text)) {
                                if ($this->salaryRoute->isDateNotInPast($text)) {
                                    $this->access->setRegularVacationStartDate($this->chatID, $text);
                                    $this->access->setState($this->chatID, $this->states['regularVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetRegularVacationEndDate($this->chatID);
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
                            if ($this->salaryRoute->isCorrectDateFormat($text)) {
                                if ($this->salaryRoute->isDateNotInPast($text)) {
                                    $this->access->setSelectedVacationNewStartDate($this->chatID, $text);
                                    $this->access->setState($this->chatID, $this->states['postponedVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationDuration($this->chatID);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['postponedVacationDurationWaitingState']:
                            if ($this->salaryRoute->isCorrectVacationDurationFormat($text)) {
                                $this->access->setSelectedVacationNewDuration($this->chatID, $text);
                                $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                if ($text == $vacationInfo['amount']) {
                                    $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                    exit;
                                } else if ((int)$text > (int)$vacationInfo['amount']) {
                                    $this->commonmistakeroute->triggerActionForVacationDurationError($this->chatID, $vacationInfo['amount']);
                                    exit;
                                } else {
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
                            if ($this->salaryRoute->isCorrectDateFormat($text)) {
                                if ($this->salaryRoute->isDateNotInPast($text)) {
                                    $vacationInfo = $this->access->getSelectedVacationInfo($this->chatID);
                                    $this->access->saveSeparatedUserVacationStartDate($this->chatID, $text, $vacationInfo);
                                    $this->access->setState($this->chatID, $this->states['postponedSeparateVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationDuration($this->chatID);
                                    exit;
                                } else {
                                    $this->commonmistakeroute->triggerActionForDateInThePastError($this->chatID);
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
                        default:
                            $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            exit;
                    }
                }
        }
    }

    function runInline($text) {
        switch ($text) {
            case $this->commands['userFullCardInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserCard($this->chatID, $result);
                    exit;
                } else {
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userEmailInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserEmail($this->chatID, $result['email']);
                    exit;
                } else {
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userMobileNumberInline']:
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserMobileNumber($this->chatID, $result['mobile_number']);
                    exit;
                } else {
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['userOfficeNumberInline']: // need to clear state after search
                $userForFind = $this->access->getFindUserData($this->chatID);
                if ($userForFind) {
                    $result = $this->access->getUserByFirstnameAndLastName($userForFind['find_userfirstname'], $userForFind['find_userlastname'], $this->logics->getUserPrivelegesForUserCards($this->user));
                    $this->phonebookroute->triggerActionForGetUserOfficeNumber($this->chatID, $result['office_number'], $result['internal_number']);
                    exit;
                } else {
                    $this->commonmistakeroute->triggerActionForRestartFindUser($this->chatID);
                    exit;
                }
            case $this->commands['firstRuleInline']:
                $this->valuesRoute->triggerActionForGetFirstValue($this->chatID, $this->user['company_id'], $this->commands['secondRuleInline']);
                exit;
            case $this->commands['secondRuleInline']:
                $this->valuesRoute->triggerActionForGetSecondValue($this->chatID, $this->user['company_id'], $this->commands['thirdRuleInline']);
                exit;
            case $this->commands['thirdRuleInline']:
                $this->valuesRoute->triggerActionForGetThirdValue($this->chatID, $this->user['company_id'], $this->commands['fourthRuleInline']);
                exit;
            case $this->commands['fourthRuleInline']:
                $this->valuesRoute->triggerActionForGetFourthValue($this->chatID, $this->user['company_id'], $this->commands['lastRuleInline']);
                exit;
            case $this->commands['lastRuleInline']:
                $this->valuesRoute->triggerActionForGetLastValue($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['sendFeedbackInline']:
                $feedback = $this->access->getFeedbackInfo($this->chatID);
                $feedbackText = $feedback['feedback_text'];
                if ($feedback) {
                    switch ($this->state) {
                        case 'waiting for ERP feedback':
                            $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#1C &".$this->user['email']."&",
                                $feedbackText
                            );
                            break;
                        case 'waiting for hardware feedback':
                            $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#ADM &".$this->user['email']."&",
                                $feedbackText
                            );
                            break;
                        case 'waiting for resources feedback':
                            $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "#ADM &".$this->user['email']."&",
                                $feedbackText
                            );
                            break;
                        case 'waiting for other feedback':
                            $this->swiftmailer->sendFeedback(
                                $this->user['company_id'],
                                'it_help@diall.ru',
                                "&".$this->user['email']."&",
                                $feedbackText
                            );
                            break;
                    }
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->mainInformationRoute->triggerActionForSendFeedback($this->chatID);
                    exit;
                } else {
                    $this->commonmistakeroute->triggerErrorForSendFeedback();
                    exit;
                }
            case $this->commands['regularVacationCaseInline']:
                $this->salaryRoute->triggerActionForRegularApplicationPreparations($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['postponedVacationCaseInline']:
                if ($this->user['company_id'] == 2 || $this->user['company_id'] == 3) {
                    $data = $this->vacationInfo->getVacationsInfo($this->user['email']);
                    if ($data) {
                        $this->access->saveUserVacations($this->chatID, $data);
                        $this->access->setState($this->chatID, $this->states['postponedVacationChooseVacationState']);
                    }
                    $this->salaryRoute->triggerActionForChooseVacationToPostpone($this->chatID, $data, $this->user['firstname']);
                    exit;
                }
                exit;
            case $this->commands['triggerMainVacationInline']:
                $this->access->setRegualarVacationType($this->chatID, '0');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerAdditionalVacationInline']:
                $this->access->setRegualarVacationType($this->chatID, '1');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerNoPaymentVacationInline']:
                $this->access->setRegualarVacationType($this->chatID, '2');
                $this->access->setState($this->chatID, $this->states['regularVacationStartDateWaitingState']);
                $this->salaryRoute->triggerActionForRegularVacationStartPreparations($this->chatID);
                exit;
            case $this->commands['triggerAcademicVacationInline']:
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
                if ($vacationFormData['vacation_type'] == '3') {
                    $this->forms->getNewRegularVacationForm($this->user["position"], $this->user['form_fullname'], $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], $vacationFormData["reason"], $day, $month, $year, $sign, $this->user['company_id']);
                } else {
                    $this->forms->getNewRegularVacationForm($this->user["position"], $this->user['form_fullname'], $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], null, $day, $month, $year, $sign, $this->user['company_id']);
                }
                $template = $this->email->generateNewRegularVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $this->swiftmailer->sendNewRegularVacationMailWithAttachementViaSmtp(
                    $vacationFormData['vacation_type'],
                    $this->user['company_id'],
                    $this->user['email'],
                    "Образец заявления на отпуск",
                    $template
                );
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->salaryRoute->triggerActionForSendRegularVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['sendOldRegularVacationFormInline']:
                $template = $this->email->generateRegularVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $this->swiftmailer->sendRegularVacationMailWithAttachementViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "Образец заявления на отпуск",
                    $template
                );
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->salaryRoute->triggerActionForSendOldRegularVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['sendPostponedVacationFormInline']:
                //$vacationFormData = $this->access->getDataForVacationForm($this->chatID);
                $vacationFormData = $this->access->getSelectedVacationInfo($this->chatID);
                $separatedVacationFormData = $this->access->getSeparatePostponedVacationsInfo($this->chatID);
                $sendData = $this->salaryRoute->getSendData($this->user, $vacationFormData, $separatedVacationFormData);
                $sign = $this->salaryRoute->getSign($this->user['fullname']);
                $date = new dateTime();
                $day = $date->format("d");
                $month = $date->format("F");
                $year = $date->format("Y");
                $position = $sendData['position'];
                $fullName = $sendData['fullName'];
                $startDate = $sendData['startDate'];
                $endDate = $sendData['endDate'];
                $companyId = $sendData['companyId'];

                sendMessage($this->chatID, (string)count($separatedVacationFormData), null);
                foreach ($separatedVacationFormData as &$value) {

                    sendMessage($this->chatID, (string)$value['id'], null);
                    $this->forms->getPostponeVacationForm($position, $fullName, $startDate, $endDate, $value['startDate'], $value['endDate'], $value['reason'], $day, $month, $year, $sign, $companyId);
                    unset($value);
                    //$template = $this->email->generatePostponeVacationForm($this->user['company_id']);
                    //$template = str_replace("{firstname}", $this->user['firstname'], $template);

                }
//                                     $this->swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
//                                         $this->user['company_id'],
//                                         "booogie.man.07@gmail.com",
//                                         "Образец заявления на перенос отпуска",
//                                         $template
//                                     );
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->salaryRoute->triggerActionForSendPostponedVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['sendOldPostponedVacationFormInline']:
                $template = $this->email->generatePostponeVacationForm($this->user['company_id']);
                $template = str_replace("{firstname}", $this->user['firstname'], $template);
                $this->swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "Образец заявления на перенос отпуска",
                    $template
                );
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->salaryRoute->triggerActionForSendOldPostponedVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            default:
                switch ($this->state) {
                    case $this->states['postponedVacationChooseVacationState']:
                        if ($this->user['company_id'] == 2 || $this->user['company_id'] == 3) {
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