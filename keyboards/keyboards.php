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
                    "text" => "Авторизация по номеру телефона"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
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
            "one_time_keyboard" => false
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
            "one_time_keyboard" => false
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
            "one_time_keyboard" => false
        );
        return json_encode($keyboard);
    }

    function employeeCardOptionsKeyboard() {
        $keyboard = array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => 'Карточка',
                        "callback_data" => 'getUserCard'
                    ),
                    array(
                        "text" => "Email",
                        "callback_data" => 'getUserEmail'
                    )
                ),
                array(
                    array(
                        "text" => "Мобильный телефон",
                        "callback_data" => 'getUserMobileNumber'
                    ),
                    array(
                        "text" => "Рабочий телефон",
                        "callback_data" => 'getUserOfficeNumber'
                    )
                )
            )
        );
        return json_encode($keyboard);
    }

    function getValueKeyboard($inlineValue) {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Далее",
                        "callback_data" => "$inlineValue"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getMainRulesMenuKeyboard() {
        return json_encode(array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Проведение совещаний"
                    ),
                    array(
                        "text" => "Общение по телефону"
                    ),
                ),
                array(
                    array(
                        "text" => "Работа в офисах"
                    ),
                    array(
                        "text" => "Внешний вид"
                    ),
                ),
                array(
                    array(
                        "text" => "Назад"
                    )
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getMainInformationMenuKeyboard($companyID) {
        switch ($companyID) {
            case 1:
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Как добраться")
                        ),
                        array(
                            array("text" => "Схема проезда (Сколково)")
                        ),
                        array(
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            case 3:
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Как добраться")
                        ),
                        array(
                            array("text" => "Схема проезда (Сколково)"),
                            array("text" => "Схема проезда (Саратов)")
                        ),
                        array(
                            array("text" => "Помощь ИТ специалиста"),
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
        }
    }

    function getItHelpMenuInlineKeyboard($companyId) {
        switch ($companyId) {
            case 1:
                return null;
            case 3:
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array(
                                "text" => "1С, ERP"
                            ),
                            array(
                                "text" => "Оборудование"
                            ),
                        ),
                        array(
                            array(
                                "text" => "Ресурсы"
                            ),
                            array(
                                "text" => "Другое"
                            ),
                        ),
                        array(
                            array(
                                "text" => "Назад"
                            )
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
        }
    }

    function getFeedbackSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить обращение",
                        "callback_data" => "sendFeedback"
                    )
                )
            )
        ));
    }

    function getSalaryMenuKeyboard() {
        return json_encode(array(
            "keyboard" => array(
                array(
                    array("text" => "Общая информация"),
                    array("text" => "Сроки выплаты"),
                ),
                array(
                    array("text" => "Заявления"),
                    array("text" => "Мой отпуск")
                ),
                array(
                    array("text" => "Календарь"),
                    array("text" => "Назад")
                )
            ),
            "resize_keyboard" => true,
            "one_time_keyboard" => false
        ));
    }

    function getApplicationMenuInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Заявление на отпуск",
                        "callback_data" => "regularVacationCase"
                    )
                ),
                array(
                    array(
                        "text" => "Заявление на перенос отпуска",
                        "callback_data" => "postponedVacationCase"
                    )
                )
            )
        ));
    }

    function getChooseVacationToPostponeInlineKeyboard($chatID, $data) {
        $vacation = array();
        foreach($data['vacations'] as $key=>$value) {
            $newDate = date('d.m.Y', strtotime($value['date1']));
            $itemTitle = "$newDate (дней: ".$value['amount'].")";
            $callback_data = $chatID."_".$key;
            $vacationItem = array(array(
                "text" => $itemTitle,
                "callback_data" => $callback_data
            ));
            array_push($vacation, $vacationItem);
        }

        return json_encode(array(
            "inline_keyboard" => $vacation
        ));
    }

    function getApplicationPreparationsInlineKeyboard($companyId) {
        switch ($companyId) {
            case 1:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldRegularVacationForm"
                            )
                        )
                    )
                ));
            case 3:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Основной",
                                "callback_data" => "triggerMainVacation"
                            ),
                            array(
                                "text" => "Дополнительный",
                                "callback_data" => "triggerAdditionalVacation"
                            )
                        ),
                        array(
                            array(
                                "text" => "Без сохранения",
                                "callback_data" => "triggerNoPaymentVacation"
                            ),
                            array(
                                "text" => "Учебный",
                                "callback_data" => "triggerAcademicVacation"
                            )
                        )
                    )
                ));
        }
    }

    function getPostponedApplicationPreparationsInlineKeyboard($companyId) {
        switch ($companyId) {
            case 1:
                return json_encode(array(
                    "inline_keyboard" => array(
                        array(
                            array(
                                "text" => "Продолжить",
                                "callback_data" => "sendOldPostponeVacationForm"
                            )
                        )
                    )
                ));
            case 2; case 3; case 22; case 33:
                return null;
        }
    }

    function getSendRegularVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить заявление",
                        "callback_data" => "sendNewRegularVacationForm"
                    )
                )
            )
        ));
    }

    function getSendPostponedVacationFormInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить заявление",
                        "callback_data" => "sendPostponedVacationForm"
                    )
                )
            )
        ));
    }

    function getCalendar() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Дней 18 = 113,67 ч / Ночей 11 = 75,33 ч",
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => "Пн",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Вт",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Ср",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Чт",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Пт",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Сб",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "Вс",
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => " ",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "1",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "2",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "3",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "4",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "5",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "6",
                        "callback_data" => "noAction"
                    )
                )
            )
        ));
    }
}
