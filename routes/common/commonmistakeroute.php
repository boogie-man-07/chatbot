<?php

# Created by Murad Adygezalov
# Date: 28.03.2021
# Time: 16:59


class commonmistakeroute {

    function triggerActionForCommonMistake($chatID) {
        $constants = new constants();
        $reply = $constants->getReplyForCommonMistake();
        sendMessage($chatID, $reply, null);
    }

    /*function triggerActionForCommonErrorIfNotAuthorized($chatID, $username) {
        $constants = new constants();
        $reply = $constants->getReplyForCommonErrorIfNotAuthorized($username);
        $this->sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfAuthorizationNotFinished($chatID, $username) {
        $constants = new constants();
        $reply = $constants->getReplyForUserNotFinishedAuthorization($username);
        $this->sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfLoginIncorrect($chatID) {
        $constants = new constants();
        $reply = $constants->getReplyForCommonErrorIfLoginIncorrect();
        $this->sendMessage($chatID, $reply, null);
    }

    function triggerActionForCommonErrorIfLoginNotFound($chatID) {
        $constants = new constants();
        $reply = $constants->getReplyForCommonErrorIfLoginNotFound();
        $this->sendMessage($chatID, $reply, null);
    }*/

    function sendMessage($chatID, $text, $keyboard) {
        $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($text)."&reply_markup=".$keyboard;
        file_get_contents($url);
    }
}













?>