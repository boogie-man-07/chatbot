<?php

class NonFinishedAuthorizationUserScenario {

    var $chatID = null;
    var $user = null;
    var $username = null;
    var $access = null;
    var $swiftmailer = null;
    var $authroute = null;
    var $commonmistakeroute = null;
    var $commands = null;
    var $states = null;
    var $state = null;
    var $email = null;

    function __construct($chatID, $user, $username, $access, $swiftmailer, $authroute, $commonmistakeroute, $commands, $states, $state, $email) {
        $this->chatID = $chatID;
        $this->user = $user;
        $this->username = $username;
        $this->access = $access;
        $this->swiftmailer = $swiftmailer;
        $this->authroute = $authroute;
        $this->commonmistakeroute = $commonmistakeroute;
        $this->commands = $commands;
        $this->states = $states;
        $this->state = $state;
        $this->email = $email;
    }

    function run($text) {
        switch ($text) {
            case $this->commands['start']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->commonmistakeroute->triggerActionForCommonErrorIfAuthorizationNotFinished($this->chatID, $this->username);
                exit;

            case $this->commands['emailAuthorization']:
                $this->access->setState($this->chatID, $this->states['loginWaitingState']);
                $this->authroute->triggerActionForStartingEmailAuthorization($this->chatID);
                exit;

            case $this->commands['smsAuthorization']:
                $this->access->setState($this->chatID, $this->states['mobileNumberWaitingState']);
                $this->authroute->triggerActionForStartingSmsAuthorization($this->chatID, $this->username);
                exit;

            case $this->commands['toStart']:
                $this->access->setState($this->chatID, $this->states['startAuthorizationState']);
                $this->authroute->triggerActionForMoveToStart($this->chatID, $this->username);
                exit;

            default:
                if (!$this->isDialogInProgress($this->state)) {
                    $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                    exit;
                } else {
                    switch ($this->state) {
                        case $this->states['loginWaitingState']:
                            if ($this->authroute->checkLogin($text)) {
                                $result = $this->access->getUserByPersonnelNumber($text);
                                if ($result) {
                                    if ($this->authroute->comparse($text, $result['email'])) {
                                        $confirmationCode = $this->email->generateConfirmationCode(10);
                                        $this->access->saveConfirmationCode($confirmationCode, $this->chatID, $result['email']);
                                        $this->access->setState($this->chatID, $this->states['confirmationCodeWaitingState']);
                                        $this->authroute->triggerActionForLoginAcceptance($this->chatID, $result["fullname"]);
                                        exit;
                                    } else {
                                        $this->commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($this->chatID);
                                        exit;
                                    }
                                } else {
                                    $this->commonmistakeroute->triggerActionForCommonErrorIfLoginNotFound($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForCommonErrorIfLoginIncorrect($this->chatID);
                                exit;
                            }

                        case $this->states['mobileNumberWaitingState']:
                            exit;
                        case $this->states['confirmationCodeWaitingState']:
                            if ($this->authroute->checkConfirmationCode($text)) {
                                $string_result = strcmp($text, $this->user['confirmation_code']);
                                if ($string_result == 0) {
                                    if($this->authroute->ifConfirmCodeExpired($this->user['confirmation_code_expiration_date'])) {
                                        $reply = "Время жизни кода активации истекло.\nНеобходима повторить процесс авторизации.";
                                        $keyboard = array(
                                            "inline_keyboard" => array(
                                                array(
                                                    array(
                                                        "text" => "Авторизоваться",
                                                        "callback_data" => "go to the start"
                                                    )
                                                )
                                            )
                                        );
                                        $markup = json_encode($keyboard);
                                        sendMessage($this->chatID, $reply, $markup);
                                        exit;
                                    } else {
                                        $this->access->updateAuthorizationFlag(1, null, $this->chatID);
                                        $this->access->setState($this->chatID, "authorization completed");
                                        $reply = "Поздравляю, ".$this->user['fullname']."! Вы успешно прошли процедуру авторизации и можете использовать меня на полную катушку!\nНиже меню с командами, которые я умею выполнять.";
                                        $keyboard = array(
                                            "keyboard" => array(
                                                array(
                                                    array(
                                                        "text" => "Телефонный справочник"
                                                    ),
                                                    array(
                                                        "text" => "КДП и Заработная плата"
                                                    )
                                                ),
                                                array(
                                                    array(
                                                        "text" => "Наши ценности"
                                                    ),
                                                    array(
                                                        "text" => "Общая информация"
                                                    )
                                                ),
                                                array(
                                                    array(
                                                        "text" => "Правила"
                                                    ),
                                                    array(
                                                        "text" => "Помощь ИТ специалиста"
                                                    )
                                                ),
                                                array(
                                                    array(
                                                        "text" => "Выход"
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
                                } else {
                                    $this->commonmistakeroute->triggerActionForCommonErrorIfConfirmationCodeIncorrect($this->chatID);
                                    exit;
                                }
                            } else {
                                $this->commonmistakeroute->triggerActionForCommonErrorIfConfirmationCodeFormatIncorrect($this->chatID);
                                exit;
                            }
                        default:
                            $this->commonmistakeroute->triggerActionForCommonMistake($this->chatID);
                            exit;
                    }
                }
        }
    }

    function runInline($text) {
        switch ($text) {
            case 'sendMessage':
                $template = $this->email->confirmationTemplate($this->user['company_id']);
                $template = str_replace("{confirmationCode}", $this->user['confirmation_code'], $template);
                $template = str_replace("{fullname}", $this->user['fullname'], $template);
                $this->swiftmailer->sendMailViaSmtp(
                    $this->user['company_id'],
                    $this->user['email'],
                    "Подтверждение регистрации в telegram-боте \"Персональный ассистент работника\"",
                    $template
                );
                $this->authroute->triggerActionWithSendingConfirmationEmail($this->chatID, $this->username);
                exit;
            case 'go to the start':
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
                $this->access->setState($this->chatID, "waiting for authorization");
                sendMessage($this->chatID, $reply, $markup);

            default:
                exit;
        }
    }

    function isDialogInProgress($currentState) {
        $dialogState = array('waiting for login', 'waiting for mobile number', 'waiting for confirmation code');
        if (in_array($currentState, $dialogState)) {
            return true;
        } else {
            return false;
        }
    }
}

?>