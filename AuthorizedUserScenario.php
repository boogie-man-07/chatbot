<?php

class AuthorizedUserScenario {

    var $chatID = null;
    var $user = null;
    var $username = null;
    var $access = null;
    var $authroute = null;
    var $commands = null;

    function __construct($chatID, $user, $username, $access, $authroute, $commands) {
        $this->chatID = $chatID;
        $this->user = $user;
        $this->username = $username;
        $this->access = $access;
        $this->authroute = $authroute;
        $this->commands = $commands;
    }

    function run($text) {
        switch ($text) {
            case $this->commands['exit']:
                $isUserRemoved = $this->access->removeUserCredentialsByChatID($this->chatID);
                $isUserStateRemoved = $this->access->removeUserStateByChatID($this->chatID);

                if ($isUserRemoved && $isUserStateRemoved) {
                    $reply = $this->username."!\nЯ Ваш личный ассистент по возникающим внутренним вопросам Компании. Для использования моих возможностей необходимо авторизироваться.";
                    $keyboard = array(
                        "keyboard" => array(
                            array(
                                array(
                                    "text" => "Авторизация по email"
                                )
                            ),
                            array(
                                array(
                                    "text" => "Авторизация по телефону"
                                )
                            )
                        ),
                        "resize_keyboard" => true,
                        "one_time_keyboard" => true
                    );
                    $markup = json_encode($keyboard);
                    sendMessage($this->chatID, $reply, $markup);
                    exit;
                }
            default:
                sendMessage($this->chatID, "Default authorized", null);
                exit;
        }
    }

    // Первая буква заглавная, работает для русского языка
    function mb_ucfirst($str, $encoding='UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

}

?>