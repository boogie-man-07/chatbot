<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


class commonmistakeroute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForCommonMistake($chatID) {
        $reply = $this->constants->getReplyForCommonMistake();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfNotAuthorized($chatID, $username) {
        $reply = $this->constants->getReplyForCommonErrorIfNotAuthorized($username);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfAuthorizationNotFinished($chatID, $username) {
        $reply = $this->constants->getReplyForUserNotFinishedAuthorization($username);
        $keyboard = $this->keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForCommonErrorIfLoginIncorrect($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfLoginIncorrect();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfLoginNotFound($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfLoginNotFound();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForMobileAuthorizationUnavailable($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfMobileAuthorizationUnavailable();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForMobilePhoneNotFound($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfMobilePhoneNotFound();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfConfirmationCodeIncorrect($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfConfirmationCodeIsIncorrect();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfConfirmationCodeFormatIncorrect($chatID) {
        $reply = $this->constants->getReplyForCommonErrorIfConfirmationCodeFormatIsIncorrect();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForConfirmationCodeExpired($chatID) {
        $reply = $this->constants->getReplyForConfirmationCodeExpired();
        $keyboard = $this->keyboards->backToAuthorizationKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetUserCardError($chatID, $username) {
        $reply = $this->constants->getNoPhoneCardError($username);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForRestartFindUser($chatID) {
        $reply = $this->constants::getReplyForRestartFindUser();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForIncorrectFLFormat($chatID) {
        $reply = $this->constants->getReplyForIncorrectFLFormatError();
        sendMessage($chatID, $reply, null);
    }

    function triggerErrorForSendFeedback($chatID) {
        $reply = $this->constants::gerReplyForSendFeedbackError();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForDateInThePastError($chatID) {
        $reply = $this->constants->getDateInThePastErrorText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForPostponedDateInThePastError($chatID) {
            $reply = $this->constants->getPostponedDateInThePastErrorText();
            sendMessage($chatID, $reply, null);
        }

    function triggerActionForDateFormatError($chatID) {
        $reply = $this->constants->getDateFormatErrorText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionAcademicVacationDurationFormatError($chatID) {
        $reply = $this->constants->getRegularAcademicVacationFormatErrorText();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForVacationDurationFormatError($chatID) {
       $reply = $this->constants->getVacationDurationFormatErrorText();
       sendMessage($chatID, $reply, null);
   }

   function triggerActionForVacationDurationError($chatID, $realDuration) {
       $reply = $this->constants->getVacationDurationErrorText($realDuration);
       sendMessage($chatID, $reply, null);
   }

   function triggerActionForMaxVacationDurationLimitError($chatID, $restVacationDuration) {
       $reply = $this->constants->getVacationDurationLimitErrorText($restVacationDuration);
       sendMessage($chatID, $reply, null);
   }
}













?>