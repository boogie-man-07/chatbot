﻿<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class authroute {

    var $constants = null;
    var $keyboards = null;
    var $logics = null;

    function __construct($constants, $keyboards, $logics) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
        $this->logics = $logics;
    }

    function triggerActionForNewUserAuthorization($chatID, $username) {
        $reply = $this->constants->getReplyForNonAuthorizedUser($username);
        $keyboard = $this->keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForStartingEmailAuthorization($chatID) {
        $reply = $this->constants::getReplyForLoginWaiting();
        $keyboard = $this->keyboards::backToStartKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForMoveToStart($chatID, $username) {
        $reply = $this->constants->getReplyForMoveToStart($username);
        $keyboard = $this->keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForStartingSmsAuthorization($chatID, $username) {
        $reply = $this->constants->getReplyForAllowToCheckMobileNumber($username);
        $keyboard = $this->keyboards->smsAuthorizationKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForLoginAcceptance($chatID, $username) {
        $reply = $this->constants->getReplyForSendConfirmationCodeApprovalFromUser($username);
        $keyboard = $this->keyboards->emailAuthorizationProceedKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForSuccessfulLogin($chatID, $username) {
        $reply = $this->constants->getReplyForSuccessfulLogin($username);
        $keyboard = $this->keyboards->mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForBotRestartedByAuthorized($chatID, $username) {
        $reply = $this->constants->getReplyForAuthorizedUser($username);
        $keyboard = $this->keyboards->mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForDeclinedExit($chatID, $username) {
        $reply = $this->constants->getReplyForDeclinedExit($username);
        $keyboard = $this->keyboards->mainKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionWithSendingConfirmationEmail($chatID, $username) {
        $reply = $this->constants->getReplyForEmailIsSended($username);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGoToTheStart($chatID, $username) {
        $reply = $this->constants->getReplyForGoToTheStart($username);
        $keyboard = $this->keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForExitConfirmation($chatID, $username) {
        $reply = $this->constants->getReplyForExitConfirmation($username);
        $keyboard = $this->keyboards->exitConfirmationKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function checkLogin($text) {
        if (preg_match('/([A-Za-z])/', mb_strtolower($text))) {
            return true;
        } else {
            return false;
        }
    }

    function checkConfirmationCode($text) {
        if (preg_match('^/[A-Za-z0-9]/', $text)) {
            return false;
        } else {
            if (mb_strlen($text) < 10) {
                return false;
            } else {
                return true;
            }
        }
    }

    function ifConfirmCodeExpired($date) {
        $expirationDate = new DateTime($date);
        $now = new DateTime();
        if ($expirationDate < $now) {
            return true;
        } else {
            return false;
        }
    }

    function comparse($text, $email) {
        $at = strpos($email,  "@");
        $login = mb_strtolower(substr($email, 0, $at), $encoding='UTF-8');
        $comparsion_result = strcmp(mb_strtolower($text, $encoding='UTF-8'), $login);
        if ($comparsion_result == 0) {
            return true;
        } else {
            return false;
        }
    }

    function formatPhoneNumber($phoneNumber) {
        return $this->logics->formatPhoneNumber($phoneNumber);
    }

    function couldBeAuthorized($result) {
        return $result['is_authorized'] == false;
    }

    function isDialogInProgress($currentState) {
        $dialogState = array(
            'waiting for login',
            'waiting for mobile number',
            'waiting for confirmation code'
        );
        if (in_array($currentState, $dialogState)) {
            return true;
        } else {
            return false;
        }
    }
}













?>