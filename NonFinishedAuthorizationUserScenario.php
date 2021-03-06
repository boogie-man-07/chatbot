<?php

class NonFinishedAuthorizationUserScenario {

    var $chatID = null;
    var $user = null;
    var $username = null;
    var $access = null;
    var $swiftmailer = null;
    var $authroute = null;
    var $commonmistakeroute = null;
    var $commands = null;
    var $states = null;
    var $state = null;
    var $email = null;

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commands, $states, $state, $email) {
        $this->chatID = $chatID;
        $this->user = $user;
        $this->username = $username;
        $this->access = $access;
        $this->swiftmailer = $swiftmailer;
        $this->authroute = $authroute;
        $this->commonmistakeroute = $commonmistakeroute;
        $this->commands = $commands;
        $this->states = $states;
        $this->state = $state;
        $this->email = $email;
    }
    // TODO first check if not authorized then return you're not authorized to do this. Same on not authorized user
    function run($text) {
        switch ($text) {
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->commonmistakeroute->triggerActionForCommonErrorIfAuthorizationNotFinished($this->chatID, $this->username);
                exit;

            case $this->commands['emailAuthorization']:
                $this->access->setState($this->chatID, $this->states['loginWaitingState']);
                $this->authroute->triggerActionForStartingEmailAuthorization($this->chatID);
                exit;

            case $this->commands['smsAuthorization']:
                $this->access->setState($this->chatID, $this->states['mobileNumberWaitingState']);
                $this->authroute->triggerActionForStartingSmsAuthorization($this->chatID, $this->username);
                exit;

            case $this->commands['toStart']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->authroute->triggerActionForMoveToStart($this->chatID, $this->username);
                exit;

            default:
                if (!$this->authroute->isDialogInProgress($this->state)) {
                    $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                    exit;
                } else {
                    switch ($this->state) {
                        case $this->states['loginWaitingState']:
                            if ($this->authroute->checkLogin($text)) {
                                $result = $this->access->getUserByPersonnelNumber($text);
                                if ($result) {
                                    if ($this->authroute->comparse($text, $result['email'])) {
                                        $confirmationCode = $this->email->generateConfirmationCode(10);
                                        $this->access->saveConfirmationCode($confirmationCode, $this->chatID, $result['email']);
                                        $this->access->setState($this->chatID, $this->states['confirmationCodeWaitingState']);
                                        $this->authroute->triggerActionForLoginAcceptance($this->chatID, $result["fullname"]);
                                        exit;
                                    } else {
                                        $this->commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($this->chatID);
                                        exit;
                                    }
                                } else {
                                    $this->commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForCommonErrorIfLoginIncorrect($this->chatID);
                                exit;
                            }

                        //case $this->states['mobileNumberWaitingState']:
                            //exit;
                        case $this->states['confirmationCodeWaitingState']:
                            if ($this->authroute->checkConfirmationCode($text)) {
                                $string_result = strcmp($text, $this->user['confirmation_code']);
                                if ($string_result == 0) {
                                    if($this->authroute->ifConfirmCodeExpired($this->user['confirmation_code_expiration_date'])) {
                                        $this->commonmistakeroute->triggerActionForConfirmationCodeExpired($this->chatID);
                                        exit;
                                    } else {
                                        $this->access->updateAuthorizationFlag(1, null, $this->chatID);
                                        $this->access->setState($this->chatID, $this->states['authorizationCompletedState']);
                                        $this->authroute->triggerActionForSuccessfulLogin($this->chatID, $this->user['fullname']);
                                        exit;
                                    }
                                } else {
                                    $this->commonmistakeroute->triggerActionForCommonErrorIfConfirmationCodeIncorrect($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForCommonErrorIfConfirmationCodeFormatIncorrect($this->chatID);
                                exit;
                            }
                        default:
                            $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            exit;
                    }
                }
        }
    }

    function runInline($text) {
        switch ($text) {
            case $this->commands['sendCodeInline']:
                $template = $this->email->confirmationTemplate($this->user['company_id']);
                $template = str_replace("{confirmationCode}", $this->user['confirmation_code'], $template);
                $template = str_replace("{fullname}", $this->user['fullname'], $template);
                $this->swiftmailer->sendMailViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "?????????????????????????? ?????????????????????? ?? telegram-???????? \"???????????????????????? ?????????????????? ??????????????????\"",
                    $template
                );
                $this->authroute->triggerActionWithSendingConfirmationEmail($this->chatID, $this->username);
                exit;
            case $this->commands['toStartInline']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->authroute->triggerActionForGoToTheStart($this->chatID, $this->username);
                exit;
            default:
                sendMessage($this->chatID, "Default nonfinished inline", null);
                exit;
        }
    }
}

?>