<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


$file = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/Botdb.ini');

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

require 'constants/constants.php';

require 'secure/access.php';
$access = new access($host, $user, $pass, $name);
$access->connect();

$website = "https://api.telegram.org/bot".$token;

$updates = file_get_contents('php://input');
$updates = json_decode($updates,TRUE);

//require_once ('messages.php');
//$messages = new messages();

class authroute {

    function triggerActionForNewUserAuthorization($chatID, $username) {

        $constants = new constants();
        $reply = $constants->getReplyForNonAuthorizedUser($username);
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Авторизация по email"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        $markup = json_encode($keyboard);
        $this->sendMessage($chatID, $reply, $markup);
    }

    function triggerActionForStartingAuthorization($chatID) {

        $constants = new constants();
        $reply = $constants->getReplyForLoginWating();
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Вернуться в начало"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        $markup = json_encode($keyboard);
        $this->sendMessage($chatID, $reply, $markup);
    }

    function triggerActionForAuthorizedUser($chatID, $username) {
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
    }

    function triggerActionForLoginAcceptance($chatID, $username) {

        $constants = new constants();
        $reply = $constants->getReplyForSendConfirmationCodeApprovalFromUser($username);
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                            "text" => "Продолжить",
                            "callback_data" => "sendMessage"
                    )
                )
            )
        );
        $markup = json_encode($keyboard);
        $this->sendMessage($chatID, $reply, $markup);
    }

    function triggerActionForMoveToStart($chatID, $username) {

        $constants = new constants();
        $reply = $constants->getReplyForMoveToStart($username);
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Авторизация по email"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        $markup = json_encode($keyboard);
        $this->sendMessage($chatID, $reply, $markup);
    }

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