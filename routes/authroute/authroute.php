<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


class authroute {

    function triggerActionForNewUserAuthorization($chatID, $username) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants->getReplyForNonAuthorizedUser($username);
        $keyboard = $keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForStartingEmailAuthorization($chatID) {

        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants->getReplyForLoginWaiting();
        $keyboard = $keyboards->backToStartKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForMoveToStart($chatID, $username) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants->getReplyForMoveToStart($username);
        $keyboard = $keyboards->helloKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForStartingSmsAuthorization($chatID, $username) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants->getReplyForAllowToCheckMobileNumber($username);
        $keyboard = $keyboards->smsAuthorizationKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    function triggerActionForLoginAcceptance($chatID, $username) {
        $constants = new constants();
        $keyboards = new keyboards();
        $reply = $constants->getReplyForSendConfirmationCodeApprovalFromUser($username);
        $keyboard = $keyboards->emailAuthorizationProceedKeyboard();
        sendMessage($chatID, $reply, $keyboard);
    }

    /*function triggerActionForAuthorizedUser($chatID, $username) {
        $constants = new constants();
        $reply = $constants->getReplyForAuthorizedUser($username);
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => 'Телефонный справочник'
                    ),
                    array(
                        "text" => 'КДП и Заработная плата'
                    )
                ),
                array(
                    array(
                        "text" => 'Наши ценности'
                    ),
                    array(
                        "text" => 'Общая информация'
                    )
                ),
                array(
                    array(
                        "text" => 'Правила'
                    ),
                    array(
                        "text" => 'Выход'
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        $markup = json_encode($keyboard);
        $this->sendMessage($chatID, $reply, $markup);
    }*/

    function triggerActionWithSendingConfirmationEmail($chatID, $username) {
        $constants = new constants();
        $reply = $constants->getReplyForEmailIsSended($username);
        $this->sendMessage($chatID, $reply, null);
    }

    function checkLogin($text) {
        if (preg_match('/([A-Za-z])/', mb_strtolower($text))) {
            return true;
        } else {
            return false;
        }
    }

    function checkConfirmationCode($text) {
        if ((preg_match('^/[A-Za-z0-9]/', $text)) || (strlen($text) < 10)) {
            return false;
        } else {
            return true;
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

    function sendMessage($chatID, $text, $keyboard) {
        $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
        file_get_contents($url);
    }
}













?>