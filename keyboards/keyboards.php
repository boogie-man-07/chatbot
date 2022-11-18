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
                    array("text" => "Телефонный справочник"),
                    array("text" => "КДП и Заработная плата")
                ),
                array(
                    array("text" => "Наши ценности"),
                    array("text" => "Общая информация")
                ),
                array(
                    array("text" => "Правила"),
                    array("text" => "Помощь ИТ специалиста")
                ),
                array(
                    array("text" => "ДМС"),
                    array("text" => "Выход")
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

    function getDmsSendingInlineKeyboard() {
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Отправить вопрос",
                        "callback_data" => "sendDmsQuestion"
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

    function getDmsMenuKeyboard($dmsType, $isPollFinished) {
        if ($dmsType == 0) {
            return json_encode(array(
                "keyboard" => array(
                    array(
                        array("text" => "Задать вопрос"),
                        array("text" => "Назад")
                    )
                ),
                "resize_keyboard" => true,
                "one_time_keyboard" => false
            ));
        } else {
            if (!$isPollFinished) {
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Памятка"),
                            array("text" => "Перечень клиник"),
                        ),
                        array(
                            array("text" => "Контакты"),
                            array("text" => "Пройти опрос")
                        ),
                        array(
                            array("text" => "Задать вопрос"),
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            } else {
                return json_encode(array(
                    "keyboard" => array(
                        array(
                            array("text" => "Памятка"),
                            array("text" => "Перечень клиник"),
                        ),
                        array(
                            array("text" => "Контакты"),
                            array("text" => "Задать вопрос")
                        ),
                        array(
                            array("text" => "Назад")
                        )
                    ),
                    "resize_keyboard" => true,
                    "one_time_keyboard" => false
                ));
            }
        }
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

    function getProceedDmsSurveyInlineKeyboard($pollState) {
        $title = $pollState == 0 ? "Начать" : "Продолжить";
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => $title,
                        "callback_data" => "proceedDmsSurvey"
                    )
                )
            )
        ));
    }

//     function getInlineKeyboardForAskADmsPollQuestion($userId, $pollInfo, $pollQuestionInfo, $isQuestionStartedEarlier) {
//         $replyList = array();
//         $id = $pollInfo['poll_state'];
//         $options = json_decode(($isQuestionStartedEarlier ? $pollQuestionInfo[$id]['responses'] : $pollQuestionInfo[$id]['reply_options']), true);
//         $nextButtonText = $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? "Завершить" : "Продолжить";
//         $nextButtonCallbackData =  $pollQuestionInfo[$id]['question_id'] >= array_count_values(array_column($pollQuestionInfo, 'question_id')) ? 'finishDmsPoll' : 'nextDmsPollOption';
//         foreach($options['options'] as $key=>$value) {
//             $itemTitle = $value['isSelected'] ? $value['title']." ".hex2bin('E29C85') : $value['title'];
//             $callbackData = $userId."_".$pollQuestionInfo[$id]['poll_id'].$pollQuestionInfo[$id]['question_id']."*".$value['id'];
//             $replyItem = array(array(
//                 "text" => $itemTitle,
//                 "callback_data" => $callbackData
//             ));
//             array_push($replyList, $replyItem);
//         }
//         $nextButtonItem = array(array(
//             "text" => $nextButtonText,
//             "callback_data" => $nextButtonCallbackData
//         ));
//         array_push($replyList, $nextButtonItem);
//
//         return json_encode(array(
//             "inline_keyboard" => $replyList
//         ));
//     }

    function getEmployeeMonthlyWorkdaysCalendar($monthlyWorkData) {
        $data = $this->createdCalendar($monthlyWorkData);
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Дней ".hex2bin("E29880")." ".$monthlyWorkData['totalWorkDays']." = ".$monthlyWorkData['totalDayWorkHours']." ч / Ночей ".hex2bin("F09F8C99")." ".$monthlyWorkData['totalWorkNights']." = ".$monthlyWorkData['totalNightWorkHours']." ч",
                        "callback_data" => "defaultCallbackResponse"
                    )
                ),
                array(
                    array("text" => "Пн", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Вт", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Ср", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Чт", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Пт", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Сб", "callback_data" => "defaultCallbackResponse"),
                    array("text" => "Вс", "callback_data" => "defaultCallbackResponse")
                ),
                $data[0]
                array(
                    array("text" => "<<", "callback_data" => "previousMonthCalendarDataAction"),
                    array("text" => $monthlyWorkData['currentMonth'], "callback_data" => "defaultCallbackResponse"),
                    array("text" => ">>","callback_data" => "nextMonthCalendarDataAction")
                )
            )
        ));
    }

    function createdCalendar($monthlyWorkData) {
        $daysList = $monthlyWorkData['daysList'];
        $itemsCount = count($daysList);
        $startCell = $monthlyWorkData['firstDayOfMonthWeekIndex'];
        $mainArray = array();
        $firstRowArray = array();
        $c = 0;
        $count = 0;
//         for ($i = 0; $i < $startCell; $i++) {
//             $count++;
//             array_push($firstRowArray, array("text" => " ", "callback_data" => "defaultCallbackResponse"));
//         }
//
//         for ($m = $startCell; $m < 7; $m++) {
//             $count++;
//             array_push($firstRowArray, array(
//                 "text" => (string)$daysList[$c]['buttonText'],
//                 "callback_data" => "defaultCallbackResponse")
//             );
//             $c++;
//         }
//
//         array_push($mainArray, $firstRowArray);

        while ($count < 14) {
            $rowArray = array();
            while ($count < 7) {
                array_push($rowArray, array(array(
                    "text" => (string)$daysList[$count]['buttonText'],
                    "callback_data" => "defaultCallbackResponse")
                ));
                $c++;
                $count++;
            }
            array_push($mainArray, $rowArray);
        }

        return $mainArray;
    }

    function getCalendar($month) {
        $dayEmoji = hex2bin("E29880");
        $nightEmoji = hex2bin("F09F8C99");
        return json_encode(array(
            "inline_keyboard" => array(
                array(
                    array(
                        "text" => "Дней ".hex2bin("E29880")." 18 = 113,67 ч / Ночей ".hex2bin("F09F8C99")." 11 = 75,33 ч",
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
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("E29880"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "17",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "18",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "19",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "20",
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => "21",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "22",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "23",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "24",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "25",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "26",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => "27",
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => hex2bin("F09F8C99"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("F09F8C99"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("F09F8C99"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => hex2bin("F09F8C99"),
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => " ",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => " ",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => " ",
                        "callback_data" => "noAction"
                    )
                ),
                array(
                    array(
                        "text" => "<<",
                        "callback_data" => "noAction"
                    ),
                    array(
                        "text" => $month,
                        "callback_data" => "emptyAction"
                    ),
                    array(
                        "text" => ">>",
                        "callback_data" => "noAction"
                    )
                )
            )
        ));
    }
}
