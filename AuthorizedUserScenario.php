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
    var $hotRoute = null;
    var $commands = null;
    var $states = null;
    var $analyticsTypes = null;
    var $state = null;
    var $logics = null;
    var $forms = null;
    var $email = null;
    var $vacationInfo = null;
    var $calendarInfo = null;
    var $query = null;
    var $logs = null;
    var $messageId = null;
    var $hrLinkApiProvider = null;
    var $adApiProvider = null;

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $phonebookroute, $valuesRoute, $mainRulesRoute, $mainInformationRoute, $salaryRoute, $hotRoute, $commands, $states, $analyticsTypes, $state, $logics, $forms, $email, $vacationInfo, $calendarInfo, $query, $logs, $messageId, $hrLinkApiProvider, $adApiProvider) {
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
        $this->hotRoute = $hotRoute;
        $this->commands = $commands;
        $this->states = $states;
        $this->analyticsTypes = $analyticsTypes;
        $this->state = $state;
        $this->logics = $logics;
        $this->forms = $forms;
        $this->email = $email;
        $this->vacationInfo = $vacationInfo;
        $this->calendarInfo = $calendarInfo;
        $this->query = $query;
        $this->logs = $logs;
        $this->messageId = $messageId;
        $this->hrLinkApiProvider = $hrLinkApiProvider;
        $this->adApiProvider = $adApiProvider;
    }

    function run($text) {
        if($this->chatID == '187967374' || $this->chatID == '5389293300') {
            $this->logs->logCustom($text, $this->user['fullname']);
        }

        $this->hotRoute->proceedIfHotDialog($this->chatID, $text);

        switch ($text) {
            // remove
            case $this->commands['myScheduler']:
                $currentMonth = $this->salaryRoute->getCurrentMonth();
                $calendarOffset = "0";
                $this->access->setCalendarOffset($this->user['user_id'], $calendarOffset);
                $monthlyWorkData = $this->calendarInfo->getMonthlyData($this->user['user_id'], $currentMonth, $calendarOffset);
                $this->salaryRoute->triggerCalendarAction($this->chatID, $monthlyWorkData);
                exit;
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                $this->authroute->triggerActionForBotRestartedByAuthorized($this->chatID, $this->user['fullname']);
                exit;
            case $this->commands['exit']:
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->authroute->triggerActionForExitConfirmation($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['confirmedExit']:
                $isUserRemoved = $this->access->removeUserCredentialsByChatID($this->chatID);
                $isUserStateRemoved = $this->access->removeUserStateByChatID($this->chatID);
                if ($isUserRemoved && $isUserStateRemoved) {
                    $this->authroute->triggerActionForGoToTheStart($this->chatID, $this->user['firstname']);
                    exit;
                } else {
                    exit;
                }
            case $this->commands['declinedExit']:
                $this->authroute->triggerActionForDeclinedExit($this->chatID, $this->user['firstname']);
                exit;
            case $this->commands['phones']:
                $this->access->setState($this->chatID, $this->states['findTelephoneNumberState']);
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->phonebookroute->triggerActionForFindPhoneNumber($this->chatID);
                exit;
            case $this->commands['values']:
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->valuesRoute->triggerActionForGetWelcomeValue($this->chatID, $this->user['firstname'], $this->commands['firstRuleInline']);
                exit;
            case $this->commands['mainRules']:
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->mainRulesRoute->triggerActionForEnterMainRulesMenu($this->chatID);
                exit;
            case $this->commands['commonInformation']:
                if ($this->salaryRoute->isSalaryMode($this->state, $this->states)) {
                    $this->salaryRoute->triggerActionForGetMainSalaryInformation($this->chatID, $this->user['company_id']);
                    exit;
                } else {
                    $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text, NULL);
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
                $this->salaryRoute->triggerActionForGetMyVacationInformation($this->chatID);
                exit;
            case $this->commands['restVacationAmount']:
                $this->salaryRoute->triggerActionForGetRestVacationInfo($this->chatID, $this->user['user_id'], $this->vacationInfo);
                exit;
            case $this->commands['regularVacationCase']:
                $this->salaryRoute->triggerActionForRegularApplicationPreparations($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['postponedVacationCase']:
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
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->mainInformationRoute->triggerActionForShowItHelpMenu($this->chatID, $this->user['company_id'], $this->user['email']);
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
            case $this->commands['unlockAccount']:
                $activationResult = $this->adApiProvider->activate($this->user['email']);
                if (!$activationResult['result']) {
                    $this->commonmistakeroute->triggerActionForADActivationError($this->chatID);
                    $template = $this->email->generateUnlockErrorForm();
                    $template = str_replace("{fullname}", $this->user['fullname'], $template);
                    $template = str_replace("{error}", $activationResult['message'], $template);
                    $this->swiftmailer->sendMailViaSmtp(
                        3,
                        array('booogie.man.07@gmail.com','ivanovds@diall.ru'),
                        "Personalbot, error unlock AD",
                        $template
                    );
                    exit;
                } else {
                    $this->mainInformationRoute->triggerActionForADSuccessfulActivation($this->chatID);
                    exit;
                }
            case $this->commands['salaryInformation']:
                $this->access->setState($this->chatID, $this->states['salaryState']);
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->salaryRoute->triggerActionForShowSalaryMenu($this->chatID);
                exit;
            case $this->commands['dmsInformation']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['DESTINATION'], $text);
                $this->salaryRoute->triggerActionForShowDmsMenu($this->chatID, $this->user['firstname'], $this->user['dms_type'], $pollInfo['is_finished'], $this->user['is_poll_available']);
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
            case $this->commands['askForDmsUsage']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                if ($pollInfo) {
                    if ($pollInfo['is_finished']) {
                        $this->salaryRoute->triggerActionForPollIsAlreadyFinished($this->chatID);
                        exit;
                    } else {
                        $this->salaryRoute->triggerActionForAskToProceedDmsSurvey($this->chatID);
                        exit;
                    }
                } else {
//                     $this->access->setDmsPollInfo($this->user['user_id'], 0, 0, 1);
                    $this->salaryRoute->triggerActionForAskToProceedDmsSurvey($this->chatID);
                    exit;
                }
            case $this->commands['dmsAskAQuestion']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                if (count($pollInfo != 0) && $this->salaryRoute->pollShouldBeContinued($this->state)) {
                    $this->access->setDmsPollLastState($this->user['user_id'], $this->state);
                }
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
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                if (count($pollInfo != 0) && $this->salaryRoute->pollShouldBeContinued($this->state)) {
                    $this->access->setDmsPollLastState($this->user['user_id'], $this->state);
                }
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
                        //todo maybe need to comment setState below, need to check how it works
                        $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                        $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['INPUT'], NULL, $text);
                        $this->phonebookroute->triggerActionForGetUserCardOptions($this->chatID);
                        exit;
                    }
                }

                if (!$this->salaryRoute->isDialogInProgress($this->state)) {
                    $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                    $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['GENERAL'], NULL, $text);
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
                                    //todo maybe need to comment setState below, need to check how it works
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
                            $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                if ($this->salaryRoute->isDateNotInPast($correctText, $vacationFormData['vacation_type'])) {
                                    $restVacationData = $this->vacationInfo->getRestVacations($this->user['user_id']);
                                    $this->access->setRegularVacationStartDate($this->chatID, $correctText);
                                    $this->access->setState($this->chatID, $this->states['regularVacationDurationWaitingState']);
                                    $this->salaryRoute->triggerActionForSetRegularVacationDuration($this->chatID, $restVacationData, $vacationFormData['vacation_type']);
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
                                if ($this->salaryRoute->restVacationShouldBeChecked($vacationFormData['vacation_type'])) {
                                    $restVacationData = $this->vacationInfo->getRestVacations($this->user['user_id']);
                                    $restVacationCount = $vacationFormData['vacation_type'] == 0 ? $restVacationData['main'] : $restVacationData['additional'];
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
                                        $this->commonmistakeroute->triggerActionForMaxVacationDurationLimitError($this->chatID, $restVacationCount, $vacationFormData['vacation_type']);
                                        exit;
                                    }
                                } else {
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
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionAcademicVacationDurationFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['regularVacationAcademicReasonWaitingState']:
                            $this->access->setRegularVacationAcademicReason($this->chatID, $text);
                            $this->access->setState($this->chatID, $this->states['regularVacationAcademicCauseWaitingState']);
                            $this->salaryRoute->triggerActionForSetRegularVacationAcademicCause($this->chatID);
                            exit;
                        case $this->states['regularVacationAcademicCauseWaitingState']:
                            $this->access->setRegularVacationAcademicCause($this->chatID, $text);
                            $this->access->setState($this->chatID, $this->states['regularVacationFormSendingWaitingState']);
                            $this->salaryRoute->triggerActionForSendRegularVacationForm($this->chatID);
                            exit;
                        case $this->states['postponedSmsCodeEnteringWaitingState']:
                            $vacationFormData = $this->access->getSelectedVacationInfo($this->chatID);
                            $separatedVacationFormData = $this->access->getSeparatePostponedVacationsInfo($this->chatID);
                            $sendData = $this->salaryRoute->getSendData($this->user, $vacationFormData, $separatedVacationFormData);
                            $checkSmsCodeState = $this->hrLinkApiProvider->checkSmsCode($this->user['physical_id'], $sendData['vacations'][0]['applicationGroupId'], $vacationFormData['vacations'][0]['signingRequestId'], $text);
                            if($checkSmsCodeState['result']) {
                                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                $this->salaryRoute->triggerActionForSuccessApplicationRegistering($this->chatID);
                                exit;
                            } else {
                                // trigger error
                                sendMessage($this->chatID, 'Код SMS неверен', null);
                                exit;
                            }
                        case $this->states['documentCopySmsCodeEnteringWaitingState']:
                            $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                            $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                            $applicationInfo = $this->access->getApplicationIdsInfo($formData['issue_type']);
                            $checkSmsCodeState = $this->hrLinkApiProvider->checkSmsCode($this->user['physical_id'], $formData['application_group_id'], $formData['signing_request_id'], $text);
                            if($checkSmsCodeState['result']) {
                                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                $this->salaryRoute->triggerActionForSuccessApplicationRegistering($this->chatID);
                                exit;
                            } else {
                                // trigger error
                                sendMessage($this->chatID, 'Код SMS неверен', null);
                                exit;
                            }
                            exit;
                        case $this->states['smsCodeEnteringWaitingState']:
                            $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                            $checkSmsCodeState = $this->hrLinkApiProvider->checkSmsCode($this->user['physical_id'], $vacationFormData['application_group_id'], $vacationFormData['signing_request_id'], $text);
//                             sendMessage($this->chatID, $checkSmsCodeState, null); exit;
                            if($checkSmsCodeState['result']) {
                                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                $this->salaryRoute->triggerActionForSuccessApplicationRegistering($this->chatID);
                                exit;
                            } else {
                                // trigger error
//                                 $this->commonmistakeroute->triggerSmsCodeSendingError($this->chatID, $smsSendingState['message'], $vacationFormData['vacation_type']);
                                sendMessage($this->chatID, 'Код SMS неверен', null);
                                exit;
                            }

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

                                // to delete
                                if ((int)$text != (int)$vacationInfo['amount']) {
                                    sendMessage($this->chatID, 'Количество дней переносимого отпуска не совпадает! Введите корректное количество дней.', null);
                                    exit;
                                }

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
                                        sendMessage($this->chatID, 'Количество дней переносимого отпуска не совпадает! Введите корректное количество дней.', null);
//                                         $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
//                                         $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, $restVacationsDuration);
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
                                    sendMessage($this->chatID, 'Количество дней переносимого отпуска не совпадает! Введите корректное количество дней.', null);
//                                     $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);
//                                     $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
//                                     $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, ((int)$text  < $restVacationsDuration));
                                    exit;
                                } else {
                                    $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);
                                    $this->access->setState($this->chatID, $this->states['postponedVacationReasonWaitingState']);
                                    $this->salaryRoute->triggerActionForSetPostponedVacationReason($this->chatID);
                                    exit;
                                }

                                $this->access->saveSeparatedUserVacationDuration($this->chatID, $text);

                                if ($restVacationsDuration > 0) {
                                    sendMessage($this->chatID, 'Количество дней переносимого отпуска не совпадает! Введите корректное количество дней.', null);
//                                     $this->access->setState($this->chatID, $this->states['postponedSeparateVacationStartDateWaitingState']);
//                                     $this->salaryRoute->triggerActionForCheckPostponedVacationDuration($this->chatID, $restVacationsDuration);
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
                            $this->salaryRoute->triggerActionForRegisterPostponedVacationForm($this->chatID);
                            exit;
                        case $this->states['documentPeriodStartDateWaitingState']:
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                $this->access->setIssuingDocumentStartDate($this->user['user_id'], $correctText);
                                $this->access->setState($this->chatID, $this->states['documentPeriodEndDateWaitingState']);
                                $this->salaryRoute->triggerActionForRequestDocumentEndDate($this->chatID);
                                exit;
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['documentPeriodEndDateWaitingState']:
                            $correctText = $this->salaryRoute->formatDate($text);
                            if ($this->salaryRoute->isCorrectDateFormat($correctText)) {
                                $this->access->setIssuingDocumentEndDate($this->user['user_id'], $correctText);
//                                 $this->salaryRoute->triggerActionForRegisterDocumentForm($this->chatID);
                                $this->access->setState($this->chatID, $this->states['documentDeliveryTypeWaitingState']);
                                $this->salaryRoute->triggerActionForRequestDocumentDeliveryType($this->chatID);
                                exit;
                            } else {
                                $this->commonmistakeroute->triggerActionForDateFormatError($this->chatID);
                                exit;
                            }
                        case $this->states['documentOtherTypeWaitingState']:
                            $this->access->setIssuingDocumentType($this->user['user_id'], $text);
//                             $this->salaryRoute->triggerActionForRegisterDocumentForm($this->chatID);
                            $this->access->setState($this->chatID, $this->states['documentDeliveryTypeWaitingState']);
                            $this->salaryRoute->triggerActionForRequestDocumentDeliveryType($this->chatID);
                            exit;
                        case $this->states['documentDeliveryTypeFreeFormWaitingState']:
                            $this->access->setIssuingDocumentDeliveryTypeFreeForm($this->user['user_id'], $text);
                            $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                            switch ($formData['issue_type']) {
                                case 5:
                                    $this->salaryRoute->triggerActionForRegisterDocumentForm($this->chatID);
                                    exit;
                                case 6:
                                    $this->salaryRoute->triggerActionForRegisterDocumentCopyForm($this->chatID);
                                    exit;
                            }
                        case $this->states['issuingDocumentTypeCopyWaitingState']:
//                             $this->forms->generateDocumentCopyForm($this->chatID);
                            $this->access->saveIssuingDocumentData($this->user['user_id'], $text);
                            $this->access->setState($this->chatID, $this->states['documentDeliveryTypeWaitingState']);
                            $this->salaryRoute->triggerActionForRequestDocumentDeliveryType($this->chatID);
//                             $this->salaryRoute->triggerActionForRegisterDocumentCopyForm($this->chatID);
                            exit;
                        case $this->states['dmsQuestionWaitingState']:
                            $this->access->setDmsQuestionInfo($this->chatID, $text);
                            if($this->user['email'] == '' || $this->user['email'] == null) {
                                $this->access->setState($this->chatID, $this->states['dmsEmailWaitingState']);
                                $this->salaryRoute->triggerActionForDmsEmptyEmail($this->chatID);
                                exit;
                            } else {
                                $this->salaryRoute->triggerActionForDmsSendingConfirmation($this->chatID);
                                exit;
                            }
                        case $this->states['dmsEmailWaitingState']:
                            if ($this->salaryRoute->isCorrectEmailFormat($text)) {
                                $this->access->addEmailToDmsQuestionInfo($this->chatID, $text);
                                $this->salaryRoute->triggerActionForDmsSendingConfirmation($this->chatID);
                                exit;
                            } else {
                                $this->commonmistakeroute->triggerActionForIncorrectEmailFormat($this->chatID);
                                exit;
                            }
                        case $this->states['dmsPoolReplyWaitingState']:
                            $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                            $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                            $id = $pollInfo['poll_state'];

                            if ($this->salaryRoute->shouldGoToNextQuestion($pollInfo, $pollQuestionInfo)) {
                                $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                                if ($pollQuestionInfo[$id]['question_type'] == 1) {
                                    $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
                                    $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                                    $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                                    $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                                }

                                $newPollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                                $newPollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                                $newId = $newPollInfo['poll_state'];

                                switch ($pollQuestionInfo[$newId]['question_type']) {
                                    case 1:
                                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithSingleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                                        exit;
                                    case 2:
                                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithMultipleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                                        exit;
                                    case 3:
                                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithFreeReply($this->chatID, $newPollInfo, $pollQuestionInfo);
                                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                                        exit;
                                    case 4:
                                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithScaleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                                        exit;
                                }
                            } else {
                                $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                                if ($pollQuestionInfo[$id]['question_type'] == 1) {
                                    $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
                                    $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                                    $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                                } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                                    $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                                }
                                $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                $this->access->setPollAsFinished($this->user['user_id'], $pollInfo);
                                $this->access->disablePollAvailability($this->user['user_id']);
                                $this->salaryRoute->triggerActionForFinishDmsPollQuestion($this->chatID);
                                answerCallbackQuery($this->query["id"], "Опрос завершен!");
                                exit;
                            }
//                         case $this->states['dmsPoolReplyWaitingState']:
//                             $selectedOption = substr($text, 0, 1);
//                             if ($this->salaryRoute->isCorrectDigit($text)) {
//                                 $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
//                                 $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
//                                 $isOptionSaved = $this->access->setSelectedDmsPollOption($this->user['user_id'], $pollInfo, $pollQuestionInfo, (int)$selectedOption);
//                                 if ($isOptionSaved) {
//                                     if ($this->salaryRoute->shouldGoToNextQuestion($pollInfo, $pollQuestionInfo)) {
//                                         $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
//                                         $newPollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
//                                         $this->salaryRoute->triggerActionForAskNextDmsPollQuestion($this->chatID, $this->user['user_id'], $newPollInfo, $pollQuestionInfo);
//                                         exit;
//                                     } else {
//                                         $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
//                                         $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
//                                         $this->access->setPollAsFinished($this->user['user_id'], $pollInfo);
//                                         sendMessage($this->chatID, 'Это были все вопросы! Спасибо за уделенное время!', null);
//                                         exit;
//                                     }
//                                 } else {
//                                     sendMessage($this->chatID, 'Не удалось сохранить ответ. Введите, пожалуйста, цифру еще раз!', null);
//                                     exit;
//                                 }
//                             } else {
//                                 // todo move to salaryRoute
//                                 sendMessage($this->chatID, 'Формат неверен. Введите корректную цифру с выбраннным ответом!', null);
//                             }
                        default:
                            $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            $this->access->addAnalytics($this->user['user_id'], $this->analyticsTypes['GENERAL'], NULL, $text);
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
                                'booogie.man.07@gmail.com',
                                $this->mainInformationRoute->removeFormats("#1C&".$this->user['email']."&", $text),
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
                                'booogie.man.07@gmail.com',
                                $this->mainInformationRoute->removeFormats("#ADM&".$this->user['email']."&", $text),
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
                                'booogie.man.07@gmail.com',
                                $this->mainInformationRoute->removeFormats("#ADM&".$this->user['email']."&", $text),
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
                                'booogie.man.07@gmail.com',
                                $this->mainInformationRoute->removeFormats("&".$this->user['email']."&", $text),
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
                $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                $applicationInfo = $this->access->getApplicationIdsInfo($vacationFormData['vacation_type']);
                $registeredUser = $this->hrLinkApiProvider->registerApplication($this->user, $vacationFormData, $bossPhysicalId['physical_id'], $applicationInfo['hrlink_application_id']);
//                 sendMessage($this->chatID, $registeredUser, null); exit;
                if ($registeredUser['result']) {
                    $this->access->setRegularVacationApplicationGroupId($this->chatID, $registeredUser['applicationGroupId']);
                    $this->salaryRoute->triggerActionForIssuingDocumentConfirmSmsSending($this->chatID);
                    exit;
                } else {
                    // trigger error
                    sendMessage($this->chatID, $registeredUser['message'], null);
                    exit;
                }
//                 $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
//                 //$sign = $this->salaryRoute->getSign($this->user['firstname'], $this->user['middlename'], $this->user['lastname']);
//                 $sign = $this->salaryRoute->getSign($this->user['fullname']);
//                 $date = new dateTime();
//                 $day = $date->format("d");
//                 $month = $date->format("F");
//                 $year = $date->format("Y");
//                 $bossSign = $this->salaryRoute->getSign($this->user['boss']);
//                 if ($vacationFormData['vacation_type'] == '3') {
//                     $this->forms->getNewRegularVacationForm($this->user, $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], $vacationFormData["reason"], $day, $month, $year, $sign, $bossSign);
//                 } else {
//                     $this->forms->getNewRegularVacationForm($this->user, $vacationFormData['vacation_type'], $vacationFormData["vacation_startdate"], $vacationFormData["vacation_duration"], null, $day, $month, $year, $sign, $bossSign);
//                 }
//                 $template = $this->email->generateNewRegularVacationForm($this->user['company_id']);
//                 $template = str_replace("{firstname}", $this->user['firstname'], $template);
//                 $isSended = $this->swiftmailer->sendNewRegularVacationMailWithAttachementViaSmtp(
//                     $vacationFormData['vacation_type'],
//                     $this->user['company_id'],
//                     $this->user['email'],
//                     "Заявление на отпуск",
//                     $template
//                 );
//                 if ($isSended) {
//                     answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
//                     $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
//                     $this->salaryRoute->triggerActionForSendRegularVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
//                     exit;
//                 } else {
//                     answerCallbackQuery($this->query["id"], "Не удалось отправить письмо, повторите попытку!");
//                     exit;
//                 }

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

                if (count($sendData['vacations']) > 1) {
                    sendMessage($this->chatID, 'Нельзя делить отпуск на части при переносе!', null);
                    exit;
                } else {
                    $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                    $applicationInfo = $this->access->getApplicationIdsInfo(4);

                    $registeredUser = $this->hrLinkApiProvider->registerPostponedApplication($this->user, $sendData, $bossPhysicalId['physical_id'], $applicationInfo['hrlink_application_id']);
                    if ($registeredUser['result']) {
                        $this->access->setPostponedVacationApplicationGroupId($this->chatID, $registeredUser['applicationGroupId']);
                        $this->salaryRoute->triggerActionForIssuingPostponedDocumentConfirmSmsSending($this->chatID);
                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                        exit;
                    } else {
                        // trigger error
                        sendMessage($this->chatID, $registeredUser['message'], null);
                        exit;
                    }
                }

//                 $sign = $this->salaryRoute->getSign($this->user['fullname']);
//
//                 $position = $sendData['position'];
//                 $fullName = $sendData['fullName'];
//                 $startDate = $sendData['startDate'];
//                 $endDate = $sendData['endDate'];
//                 $companyId = $sendData['companyId'];
//                 $vacationList = $sendData['vacations'];
//
//                 $sendInfo = $this->forms->getPostponeVacationForm($this->chatID, $sendData, $sign);
//                 foreach ($sendInfo as $info) {
//                     $template = $this->email->generatePostponeVacationForm($this->user['company_id']);
//                     $template = str_replace("{firstname}", $this->user['firstname'], $template);
//                     $this->swiftmailer->sendPostponedVacationMailWithAttachementViaSmtp(
//                         $this->user['company_id'],
//                         $this->user['email'],
//                         "Заявление на перенос отпуска",
//                         $template,
//                         (string)$info
//                     );
//                 }
//                 answerCallbackQuery($this->query["id"], "Письмо успешно отправлено!");
//                 $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
//                 $this->salaryRoute->triggerActionForSendPostponedVacationFormResult($this->chatID, $this->user['firstname'], $this->user['company_id']);
            case $this->commands['sendDocumentFormInline']:
                $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                $applicationInfo = $this->access->getApplicationIdsInfo($formData['issue_type']);
                $registeredUser = $this->hrLinkApiProvider->registerDocumentApplication($this->user, $formData, $bossPhysicalId['physical_id'], $applicationInfo['hrlink_application_id']);
//                 sendMessage($this->chatID, json_encode($registeredUser), null); exit;
                if ($registeredUser['result']) {
                    $this->access->setIssuingDocumentApplicationGroupId($this->user['user_id'], $registeredUser['applicationGroupId']);
                    $this->salaryRoute->triggerActionForIssuingDocumentCopyConfirmSmsSending($this->chatID);
                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                    exit;
                } else {
                    // trigger error
                    sendMessage($this->chatID, $registeredUser['message'], null);
                    exit;
                }
                exit;
            case $this->commands['sendDocumentCopyFormInline']:
                $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                $applicationInfo = $this->access->getApplicationIdsInfo($formData['issue_type']);
                $registeredUser = $this->hrLinkApiProvider->registerDocumentApplication($this->user, $formData, $bossPhysicalId['physical_id'], $applicationInfo['hrlink_application_id']);
                if ($registeredUser['result']) {
                    $this->access->setIssuingDocumentApplicationGroupId($this->user['user_id'], $registeredUser['applicationGroupId']);
                    $this->salaryRoute->triggerActionForIssuingDocumentCopyConfirmSmsSending($this->chatID);
                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                    exit;
                } else {
                    // trigger error
                    sendMessage($this->chatID, $registeredUser['message'], null);
                    exit;
                }
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

            // document
            case $this->commands['documentsIssuingCaseInline']:
                answerCallbackQuery($this->query["id"], "Список документов загружен!");
                $this->access->setState($this->chatID, $this->states['issuingDocumentChooseWaitingState']);
                $this->salaryRoute->triggerActionForGetIssuingDocumentsList($this->chatID);
                exit;
            case $this->commands['documentsCopiesIssuingCaseInline']:
                answerCallbackQuery($this->query["id"], "Данные загружены!");
                $this->access->setState($this->chatID, $this->states['issuingDocumentTypeCopyWaitingState']);
                $this->salaryRoute->triggerActionForRequestIssuingDocumentTypeCopy($this->chatID);
                exit;

            case $this->commands['sendConfirmationSmsInline']:
                $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                $smsSendingState = $this->hrLinkApiProvider->sendSmsCode($this->user['physical_id'], $vacationFormData['application_group_id']);
                if ($smsSendingState['result']) {
                    $this->access->setRegularVacationSigningRequestId($this->chatID, $smsSendingState['signingRequestId']);
                    $this->access->setState($this->chatID, $this->states['smsCodeEnteringWaitingState']);
                    $this->salaryRoute->triggerActionForConfirmationSmsEntering($this->chatID);
                    answerCallbackQuery($this->query["id"], "Код отправлен в SMS!");
                    exit;
                } else {
                    $this->commonmistakeroute->triggerSmsCodeSendingError($this->chatID, $smsSendingState['message'], $vacationFormData['vacation_type']);
                    exit;
                }
            case $this->commands['sendPostponedConfirmationSmsInline']:
                 $vacationFormData = $this->access->getSelectedVacationInfo($this->chatID);
                 $separatedVacationFormData = $this->access->getSeparatePostponedVacationsInfo($this->chatID);
                 $sendData = $this->salaryRoute->getSendData($this->user, $vacationFormData, $separatedVacationFormData);
                 $smsSendingState = $this->hrLinkApiProvider->sendSmsCode($this->user['physical_id'], $sendData['vacations'][0]['applicationGroupId']);
                 if ($smsSendingState['result']) {
                     $this->access->setPostponedVacationSigningRequestId($this->chatID, $smsSendingState['signingRequestId']);
                     $this->access->setState($this->chatID, $this->states['postponedSmsCodeEnteringWaitingState']);
                     $this->salaryRoute->triggerActionForConfirmationSmsEntering($this->chatID);
                     answerCallbackQuery($this->query["id"], "Код отправлен в SMS!");
                     exit;
                 } else {
                     $this->commonmistakeroute->triggerSmsCodeSendingError($this->chatID, $smsSendingState['message'], 4);
                     exit;
                 }
            case $this->commands['sendDocumentCopyConfirmationSmsInline']:
                $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                $applicationInfo = $this->access->getApplicationIdsInfo($formData['issue_type']);
                $smsSendingState = $this->hrLinkApiProvider->sendSmsCode($this->user['physical_id'], $formData['application_group_id']);
                if ($smsSendingState['result']) {
                    $this->access->setIssuingDocumentSigningRequestId($this->user['user_id'], $smsSendingState['signingRequestId']);
                    $this->access->setState($this->chatID, $this->states['documentCopySmsCodeEnteringWaitingState']);
                    $this->salaryRoute->triggerActionForConfirmationSmsEntering($this->chatID);
                    answerCallbackQuery($this->query["id"], "Код отправлен в SMS!");
                    exit;
                } else {
                    $this->commonmistakeroute->triggerSmsCodeSendingError($this->chatID, $smsSendingState['message'], $formData['issue_type']);
                    exit;
                }
            case $this->commands['dmsGoToSurveyInline']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                if ($pollInfo) {
                    if ($pollInfo['is_finished']) {
                        $this->salaryRoute->triggerActionForPollIsAlreadyFinished($this->chatID);
                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                        exit;
                    } else {
                        $this->salaryRoute->triggerActionForProceedDmsSurvey($this->chatID, $pollInfo['poll_state']);
                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                        exit;
                    }
                } else {
                    $this->access->setDmsPollInfo($this->user['user_id'], 0, 0, 1);
                    $this->salaryRoute->triggerActionForProceedDmsSurvey($this->chatID, 0);
                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                    exit;
                }
            case $this->commands['dmsNotRelevantToProceedWithSurveyInline']:
                $this->access->setDmsPollInfo($this->user['user_id'], 0, 1, 0);
                $this->access->disablePollAvailability($this->user['user_id']);
                $this->salaryRoute->triggerActionForNotRelevantToProceedDmsSurvey($this->chatID);
                answerCallbackQuery($this->query["id"], "Данные загружены!");
                exit;
            case $this->commands['proceedDmsSurveyInline']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                $id = $pollInfo['poll_state'];
                $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                $this->access->setState($this->chatID, $this->states['dmsPoolReplyWaitingState']);
                switch ($pollQuestionInfo[$id]['question_type']) {
                    case 1:
                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithSingleChoose($this->chatID, $pollInfo, $pollQuestionInfo);
                        answerCallbackQuery($this->query["id"], "Вопрос загружен!");
                        exit;
                    case 2:
//                         $this->access->setState($this->chatID, $this->states['dmsMultipleKeyboardChooseWaitingState']);
                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithMultipleChoose($this->chatID, $pollInfo, $pollQuestionInfo);
                        answerCallbackQuery($this->query["id"], "Вопрос загружен!");
                        exit;
                    case 3:
                        $this->salaryRoute->triggerActionForAskDmsPollQuestionWithFreeReply($this->chatID, $newPollInfo, $pollQuestionInfo);
                        answerCallbackQuery($this->query["id"], "Вопрос загружен!");
                        exit;
                    case 4:
                        answerCallbackQuery($this->query["id"], "Вопрос загружен!");
                        exit;
                }
            case $this->commands['returnToNonFinishedDmsSurveyInline']:
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                $id = $pollInfo['poll_state'];

                if ($pollInfo['last_state'] != null) {
                    $this->access->setState($this->chatID, $pollInfo['last_state']);
                }

                if ($this->salaryRoute->shouldGoToNextQuestion($pollInfo, $pollQuestionInfo)) {
                    $newPollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                    $newPollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                    $newId = $newPollInfo['poll_state'];

                    switch ($pollQuestionInfo[$newId]['question_type']) {
                        case 1:
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithSingleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            exit;
                        case 2:
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            $this->access->setState($this->chatID, $this->states['dmsMultipleKeyboardChooseWaitingState']);
                            $this->access->resetPollOptionState($this->chatID, $newPollInfo, $pollQuestionInfo);
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithMultipleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            exit;
                        case 3:
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithFreeReply($this->chatID, $newPollInfo, $pollQuestionInfo);
                            exit;
                        case 4:
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithScaleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            exit;
                    }
                } else {
                    $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                    if ($pollQuestionInfo[$id]['question_type'] == 1) {
                        $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
                        $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                        $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                        $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                    }
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->access->setPollAsFinished($this->user['user_id'], $pollInfo);
                    $this->access->disablePollAvailability($this->user['user_id']);
                    $this->salaryRoute->triggerActionForFinishDmsPollQuestion($this->chatID);
                    answerCallbackQuery($this->query["id"], "Опрос завершен!");
                    exit;
                }
            case $this->commands['nextDmsPollOptionInline']:
                $this->access->setState($this->chatID, $this->states['dmsPoolReplyWaitingState']);
                $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                $id = $pollInfo['poll_state'];

                if ($this->salaryRoute->shouldGoToNextQuestion($pollInfo, $pollQuestionInfo)) {
                    $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                    if ($pollQuestionInfo[$id]['question_type'] == 1) {
                        $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
//                         $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                        $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                    } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                        $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                    }

                    $newPollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                    $newPollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                    $newId = $newPollInfo['poll_state'];

                    switch ($pollQuestionInfo[$newId]['question_type']) {
                        case 1:
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithSingleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                        case 2:
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithMultipleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                        case 3:
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithFreeReply($this->chatID, $newPollInfo, $pollQuestionInfo);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                        case 4:
                            $this->salaryRoute->triggerActionForAskDmsPollQuestionWithScaleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                    }
                } else {
                    $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                    $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                    $this->access->setPollAsFinished($this->user['user_id'], $pollInfo);
                    $this->access->disablePollAvailability($this->user['user_id']);
                    $this->salaryRoute->triggerActionForFinishDmsPollQuestion($this->chatID);
                    answerCallbackQuery($this->query["id"], "Опрос завершен!");
                    exit;
                }
            case $this->commands['finishDmsPollInline']:
                answerCallbackQuery($this->query["id"], "Опрос заврешен!");
                exit;
            case $this->commands['sendDmsQuestionInline']:
                $questionInfo = $this->access->getDmsQuestionInfo($this->chatID);
                if ($questionInfo) {
                    $template = $this->email->generateDmsQuestionForm($this->user['company_id']);
                    $template = str_replace("{fullname}", $this->user['fullname'], $template);
                    $template = str_replace("{question}", $questionInfo['question_text'], $template);
                    $isSended = $this->swiftmailer->sendDmsQuestion(
                        $this->user['company_id'],
                        'chernishovava@diall.ru',
                        $questionInfo['response_email'] ? $questionInfo['response_email'] : $this->user['email'],
                        "Вопрос в рамках ДМС (Персональный ассистент работника)",
                        $template
                    );
                    if ($isSended) {
                        $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                        $this->access->removeDmsQuestionInfo($this->chatID);
                        $this->salaryRoute->triggerActionForDmsQuestionIsSended($this->chatID);
                        answerCallbackQuery($this->query["id"], "Ваш вопрос успешно отправлен!");
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
            case $this->commands['nextMonthCalendarInline']:
                $offset = $this->access->getCalendarOffset($this->user['user_id']);
                $nextOffset = $this->salaryRoute->generateNextOffset($offset);
                $nextMonth = $this->salaryRoute->getNextMonth($nextOffset);
                $this->access->setCalendarOffset($this->user['user_id'], $nextOffset);
                $monthlyWorkData = $this->calendarInfo->getMonthlyData($this->user['user_id'], $nextMonth, $nextOffset);
                $this->salaryRoute->triggerNextCalendarAction($this->chatID, $this->messageId, $monthlyWorkData);
                answerCallbackQuery($this->query["id"], "Данные загружены!");
                exit;
            case $this->commands['previousMonthCalendarInline']:
                $offset = $this->access->getCalendarOffset($this->user['user_id']);
                $previousOffset = $this->salaryRoute->generatePreviousOffset($offset);
                $previousMonth = $this->salaryRoute->getPreviousMonth($previousOffset);
                $this->access->setCalendarOffset($this->user['user_id'], $previousOffset);
                $monthlyWorkData = $this->calendarInfo->getMonthlyData($this->user['user_id'], $previousMonth, $previousOffset);
                $this->salaryRoute->triggerPreviousCalendarAction($this->chatID, $this->messageId, $monthlyWorkData);
                answerCallbackQuery($this->query["id"], "Данные загружены!");
                exit;
            default:
                switch ($this->state) {
                    case $this->states['postponedVacationChooseVacationState']:
                        if ($this->user['company_id'] == 3) {
                            $this->access->setSelectedVacation($this->chatID, $text);
                            $this->access->setState($this->chatID, $this->states['postponedVacationNewStartDateWaitingState']);
                            $this->salaryRoute->triggerActionForSetPostponedVacationNewStartDate($this->chatID);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                        } else {
                            exit;
                        }
                    case $this->states['issuingDocumentChooseWaitingState']:
                        switch ((int)$text) {
                            case 1; case 2; case 3:
                                answerCallbackQuery($this->query["id"], "Данные загружены!");
                                $this->access->setIssuingDocumentReferenceType($this->user['user_id'], (int)$text);
//                                 $this->salaryRoute->triggerActionForRegisterDocumentForm($this->chatID);
                                $this->access->setState($this->chatID, $this->states['documentDeliveryTypeWaitingState']);
                                $this->salaryRoute->triggerActionForRequestDocumentDeliveryType($this->chatID);
                                exit;
                            case 4; case 5; case 6:
                                answerCallbackQuery($this->query["id"], "Данные загружены!");
                                $this->access->setIssuingDocumentReferenceType($this->user['user_id'], (int)$text);
                                $this->access->setState($this->chatID, $this->states['documentPeriodStartDateWaitingState']);
                                $this->salaryRoute->triggerActionForRequestDocumentStartDate($this->chatID);
                                exit;
                            case 7:
                                answerCallbackQuery($this->query["id"], "Данные загружены!");
                                $this->access->setIssuingDocumentReferenceType($this->user['user_id'], (int)$text);
                                $this->access->setState($this->chatID, $this->states['documentOtherTypeWaitingState']);
                                $this->salaryRoute->triggerActionForRequestOtherDocumentType($this->chatID);
                                exit;
                        }
                    case $this->states['dmsPoolReplyWaitingState']:
                        $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                        $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                        $id = $pollInfo['poll_state'];

                        if ($this->salaryRoute->shouldGoToNextQuestion($pollInfo, $pollQuestionInfo)) {
                            $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                            if ($pollQuestionInfo[$id]['question_type'] == 1) {
                                $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
                                $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                                $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                                $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                            }

                            $newPollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                            $newPollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                            $newId = $newPollInfo['poll_state'];

                            switch ($pollQuestionInfo[$newId]['question_type']) {
                                case 1:
                                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                                    $this->salaryRoute->triggerActionForAskDmsPollQuestionWithSingleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                    exit;
                                case 2:
                                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                                    $this->access->setState($this->chatID, $this->states['dmsMultipleKeyboardChooseWaitingState']);
                                    $this->salaryRoute->triggerActionForAskDmsPollQuestionWithMultipleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                    exit;
                                case 3:
                                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                                    $this->salaryRoute->triggerActionForAskDmsPollQuestionWithFreeReply($this->chatID, $newPollInfo, $pollQuestionInfo);
                                    exit;
                                case 4:
                                    answerCallbackQuery($this->query["id"], "Данные загружены!");
                                    $this->salaryRoute->triggerActionForAskDmsPollQuestionWithScaleChoose($this->chatID, $newPollInfo, $pollQuestionInfo);
                                    exit;
                            }
                        } else {
                            $this->access->increaseUserDmsPollState($this->user['user_id'], $pollInfo);
                            if ($pollQuestionInfo[$id]['question_type'] == 1) {
                                $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 2) {
                                $this->access->setSelectedDmsPollOptionForMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 3) {
                                $this->access->setSelectedDmsPollOptionForFreeReply($this->user['user_id'], $text, $pollInfo, $pollQuestionInfo);
                            } else if ($pollQuestionInfo[$id]['question_type'] == 4) {
                                $this->access->setSelectedDmsPollOption($this->user['user_id'], $text);
                            }
                            $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                            $this->access->setPollAsFinished($this->user['user_id'], $pollInfo);
                            $this->access->disablePollAvailability($this->user['user_id']);
                            $this->salaryRoute->triggerActionForFinishDmsPollQuestion($this->chatID);
                            answerCallbackQuery($this->query["id"], "Опрос завершен!");
                            exit;
                        }
                    case $this->states['dmsMultipleKeyboardChooseWaitingState']:
                        $pollInfo = $this->access->getDmsPollInfo($this->user['user_id']);
                        $pollQuestionInfo = $this->access->getDmsPollQuestionsInfo(1);
                        $this->access->setSelectedDmsPollOptionForUpdateMultipleChoose($this->user['user_id'], $text, $pollQuestionInfo);
                        $pollUserQuestionInfo = $this->access->getDmsUserPollQuestionsInfo($this->user['user_id'], 1);
                        $this->salaryRoute->triggerActionForUpdateDmsPollQuestionWithMultipleChoose($this->chatID, $this->messageId, $pollInfo, $pollQuestionInfo, $pollUserQuestionInfo);
                        answerCallbackQuery($this->query["id"], "Данные обновлены!");
                        exit;
                    case $this->states['regularVacationTypeWaitingState']:
                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                        $vacationFormData = $this->access->getReguarVacationFormData($this->chatID);
                        $bossPhysicalId = $this->access->getBossPhysicalId($this->user['boss']);
                        $applicationInfo = $this->access->getApplicationIdsInfo($text);
                        $registeredUser = $this->hrLinkApiProvider->registerApplication($this->user, $vacationFormData, $bossPhysicalId['physical_id'], $applicationInfo['hrlink_application_id']);
                        if ($registeredUser['result']) {
                            $this->access->setRegularVacationApplicationGroupId($this->chatID, $registeredUser['applicationGroupId']);
                            $this->salaryRoute->triggerActionForIssuingDocumentConfirmSmsSending($this->chatID);
                            answerCallbackQuery($this->query["id"], "Данные загружены!");
                            exit;
                        } else {
                            // trigger error
                            sendMessage($this->chatID, 'an error occured', null);
                            exit;
                        }
                    case $this->states['documentDeliveryTypeWaitingState']:
                        answerCallbackQuery($this->query["id"], "Данные загружены!");
                        $this->access->setIssuingDocumentDeliveryType($this->user['user_id'], (int)$text);
                        $formData = $this->access->getIssuingDocumentData($this->user['user_id']);
                        switch ((int)$text) {
                            case 1; case 2:
                                switch ($formData['issue_type']) {
                                    case 5:
                                        $this->salaryRoute->triggerActionForRegisterDocumentForm($this->chatID);
                                        exit;
                                    case 6:
                                        $this->salaryRoute->triggerActionForRegisterDocumentCopyForm($this->chatID);
                                        exit;
                                }
                            case 3:
                                $this->access->setState($this->chatID, $this->states['documentDeliveryTypeFreeFormWaitingState']);
                                $this->salaryRoute->triggerActionForRequestDocumentDeliveryTypeFreeForm($this->chatID);
                                exit;
                        }
                    default:
                        answerCallbackQuery($this->query["id"], "Хм, интересно...");
//                         sendMessage($this->chatID, "Default finished inline", null);
                        exit;
                }
        }
    }
}

?>