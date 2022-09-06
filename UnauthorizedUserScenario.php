<?php

class UnauthorizedUserScenario {

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
    var $phoneNumber = null;

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commands, $states, $state, $email, $phoneNumber) {
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
        $this->phoneNumber = $phoneNumber;
    }

    function run($text) {
        switch ($text) {
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->authroute->triggerActionForNewUserAuthorization($this->chatID, $this->username);
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
                        case $states['mobileNumberWaitingState']:
                            sendMessage($this->chatID, (string)$phoneNumber, null);
                            exit;
                        default:
                            $commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            exit;
                    }
                }

        }
    }
}

?>