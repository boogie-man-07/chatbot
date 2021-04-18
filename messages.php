<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


$file = parse_ini_file("../Botdb.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);
$token = trim($file["token"]);

class messages {

    function sendMessage($chatID, $text, $keyboard) {
        $url = "https://api.telegram.org/bot".$token."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
        file_get_contents($url);
    }
}









?>