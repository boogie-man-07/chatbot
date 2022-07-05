<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


$file = parse_ini_file("../../Testbotdb.ini");
$token = trim($file["token"]);
$website = "https://api.telegram.org/bot".$token;

class Messages {

    function sendMessage($chatID, $text, $keyboard) {
        $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
        file_get_contents($url);
    }

    function sendPhoto($chatID, $imageUrl, $keyboard) {
      $url = $GLOBALS[website]."/sendPhoto?chat_id=$chatID&parse_mode=HTML&photo=".$imageUrl."&reply_markup=".$keyboard;
      file_get_contents($url);
    }

    function sendSticker($chatID, $sticker) {
      $url = $GLOBALS[website]."/sendSticker?chat_id=$chatID&sticker=$sticker";
      file_get_contents($url);
    }
}









?>