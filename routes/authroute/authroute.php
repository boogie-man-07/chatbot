﻿<?php

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
        // $access->setState($chatID, "waiting for authorization");
        $this->sendMessage($chatID, $reply, null);
    }

    function sendMessage($chatID, $text, $keyboard) {
        $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
        file_get_contents($url);
    }
}













?>