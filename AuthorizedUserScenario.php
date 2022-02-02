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

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $phonebookroute, $valuesRoute, $mainRulesRoute, $mainInformationRoute, $salaryRoute, $commands, $states, $state, $logics) {
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
                if ($this->isSalaryMode()) {
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
                $this->salaryRoute->triggerActionForGetApplicationsInformation($this->chatID, $this->user['firstname']);
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
                $this->mainRulesRoute->triggerActionForNavigateBack($this->chatID);
                exit;
            default:
                if (!$this->isDialogInProgress($this->state)) {
                    $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                    exit;
                } else {
                    switch ($this->state) {
                        case $this->states['findTelephoneNumberState']:
                            $lastname = $this->phonebookroute->getUserLastname($text);
                            $firstname = $this->phonebookroute->getUserFirstname($text);
                            if (!$this::isCorrectFLFormat($firstname, $lastname)) {
                                $this->commonmistakeroute->triggerActionForIncorrectFLFormat($this->chatID);
                                exit;
                            } else {
                                $result = $this->access->getUserByFirstnameAndLastName($firstname, $lastname, $this->logics->getUserPrivelegesForUserCards($this->user));
                                if ($result) {
                                    $this->access->saveFindUserData($this->chatID, $result['firstname'], $result['lastname']);
                                    //$this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
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
                    // TODO refactor
                    $this->commonmistakeroute->triggerErrorForSendFeedback();
                    exit;
                }
            case $this->commands['regularVacationCaseInline']:
                $this->salaryRoute->triggerActionForRegularApplicationPreparations($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            case $this->commands['postponedVacationCaseInline']:
                if ($this->user['company_id'] == 2) {
                    $this->access->setState($this->chatID, $this->states['postponedVacationStartDateWaitingState']);
                }
                $this->salaryRoute->triggerActionForPostponedApplicationPreparations($this->chatID, $this->user['firstname'], $this->user['company_id']);
                exit;
            default:
                sendMessage($this->chatID, "Default finished inline", null);
                exit;
        }
    }

    // Первая буква заглавная, работает для русского языка
    function mb_ucfirst($str, $encoding='UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    function isCorrectFLFormat($first, $last) {
        if (mb_strlen($first) < 2 || mb_strlen($last) < 2) {
            return false;
        } else {
            return true;
        }
    }

    function isSalaryMode() {
        return $this->state == $this->states['salaryState'];
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