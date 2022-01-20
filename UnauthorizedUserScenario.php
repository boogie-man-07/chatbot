<?php

class UnauthorizedUserScenario {

    var $chatID = null;
    var $username = null;
    var $access = null;
    var $authroute = null;
    var $commands = null;
    var $states = null;

    function __construct($chatID, $username, $access, $authroute, $commands, $states) {
        $this->chatID = $chatID;
        $this->username = $username;
        $this->access = $access;
        $this->authroute = $authroute;
        $this->commands = $commands;
        $this->states = $states;
    }

    function start($text) {
        switch ($text) {
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states->startAuthorizationState);
                $this->authroute->triggerActionForNewUserAuthorization($this->chatID, $this->username);
                exit;

            case $this->commands['emailAuthorization']:
                $this->access->setState($this->chatID, $this->states->loginWaitingState);
                $this->authroute->triggerActionForStartingEmailAuthorization($this->chatID);
                exit;

            case $this->commands['smsAuthorization']:
                $this->access->setState($this->chatID, $this->states->mobileNumberWaitingState);
                $this->authroute->triggerActionForStartingSmsAuthorization($this->chatID, $this->username);
                exit;

            case $this->commands['toStart']:
                $this->access->setState($this->chatID, $this->states->startAuthorizationState);
                $this->authroute->triggerActionForMoveToStart($this->chatID, $this->username);
                exit;

            default:
                //$commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                exit;
        }
    }


}

?>