<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class PhonebookRoute {

    var $constants = null;
    var $keyboards = null;

    function __construct($constants, $keyboards) {
        $this->constants = $constants;
        $this->keyboards = $keyboards;
    }

    function triggerActionForFindPhoneNumber($chatID) {
        $reply = $this->constants::getReplyForFindPhoneNumber();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserCardOptions($chatID) {
        $reply = $this->constants::getReplyForEmployeeCardOptions();
        $keyboard = $this->keyboards::employeeCardOptionsKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetUserCard($chatID, $user) {
        $reply = $this->constants::getReplyForEmployeeCard($user);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserEmail($chatID, $email) {
        $reply = $this->constants::getReplyForEmployeeEmail($email);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserMobileNumber($chatID, $mobileNumber) {
        $reply = $this->constants::getReplyForEmployeeMobileNumber($mobileNumber);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserOfficeNumber($chatID, $officeNumber, $internalNumber) {
        $reply = $this->constants::getReplyForEmployeeOfficeNumber($officeNumber, $internalNumber);
        sendMessage($chatID, $reply, null);
    }

    function getUserLastname($text) {
        $space = strpos($text,  " ");
        return mb_strtolower(substr($text, $space + 1), $encoding='UTF-8');
    }

    function getUserFirstname($text) {
        $space = strpos($text,  " ");
        return mb_strtolower(substr($text, 0, $space), $encoding='UTF-8');
    }
}