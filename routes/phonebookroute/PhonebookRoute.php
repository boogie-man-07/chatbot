<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59

class PhonebookRoute {

    function triggerActionForFindPhoneNumber($chatID) {
        $constants = new constants();
        $reply = $constants::getReplyForFindPhoneNumber();
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserCardOptions($chatID) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants::getReplyForEmployeeCardOptions();
        $keyboard = $keyboards::employeeCardOptionsKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForGetUserCard($chatID, $user) {
        $constants = new constants();
        $reply = $constants::getReplyForEmployeeCard($user);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserEmail($chatID, $email) {
        $constants = new constants();
        $reply = $constants::getReplyForEmployeeEmail($email);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserMobileNumber($chatID, $mobileNumber) {
        $constants = new constants();
        $reply = $constants::getReplyForEmployeeMobileNumber($mobileNumber);
        sendMessage($chatID, $reply, null);
    }

    function triggerActionForGetUserOfficeNumber($chatID, $officeNumber, $internalNumber) {
        $constants = new constants();
        $reply = $constants::getReplyForEmployeeOfficeNumber($officeNumber, $internalNumber);
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