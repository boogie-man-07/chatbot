<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */


class keyboards {

    function helloKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                    "text" => "Авторизация по email"
                    )
                ),
                array(
                    array(
                    "text" => "Авторизация по SMS"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        return json_encode($keyboard);
    }

    function backToStartKeyboard() {
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
        return json_encode($keyboard);
    }

    function smsAuthorizationKeyboard() {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Передать мобильный номер",
                        'request_contact'=>true
                    )
                ),
                array(
                    array(
                        "text" => "Вернуться в начало"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        );
        return json_encode($keyboard);
    }

    function emailAuthorizationProceedKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Продолжить",
                        "callback_data" => "sendConfirmationCode"
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function backToAuthorizationKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Авторизоваться",
                        "callback_data" => "goToTheStart"
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function mainKeyboard() {
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
        return json_encode($keyboard);
    }
}
